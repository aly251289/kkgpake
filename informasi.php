<?php
require_once 'config/koneksi.php';
include 'includes/header.php';

// Pagination Setup
$limit = 6;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// 1. Fetch Featured News (Berita Utama)
// Prioritize 'is_featured' flag, otherwise latest 'Berita Utama' category, otherwise just latest news.
$query_featured = mysqli_query($koneksi, "SELECT * FROM berita WHERE is_featured=1 ORDER BY tanggal DESC LIMIT 1");
if (mysqli_num_rows($query_featured) == 0) {
    // Fallback if no featured flag set
    $query_featured = mysqli_query($koneksi, "SELECT * FROM berita WHERE kategori='Berita Utama' ORDER BY tanggal DESC LIMIT 1");
    if (mysqli_num_rows($query_featured) == 0) {
        // Fallback to just latest
        $query_featured = mysqli_query($koneksi, "SELECT * FROM berita ORDER BY tanggal DESC LIMIT 1");
    }
}
$featured = mysqli_fetch_assoc($query_featured);
$featured_id = $featured ? $featured['id'] : 0;
?>

<!-- Page Header -->
<!-- Hero Section -->
<section class="relative py-24 bg-gradient-to-r from-emerald-900 to-emerald-700 text-white overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div
            class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-emerald-300/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob">
        </div>
        <div
            class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-sky-300/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000">
        </div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-6" data-aos="fade-up">
            Informasi & Berita
        </h1>
        <p class="text-lg text-emerald-100 max-w-2xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="100">
            Dapatkan update terbaru seputar pendidikan, kebijakan, dan prestasi di lingkungan madrasah.
        </p>
    </div>
</section>

<!-- Berita Utama / Featured -->
<?php if ($featured):
    $feat_img = $featured['gambar'] ? $featured['gambar'] : 'https://ui-avatars.com/api/?name=' . urlencode($featured['judul']) . '&background=059669&color=fff&size=1200';
    ?>
        <section class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl group cursor-pointer" data-aos="zoom-in">
                    <img src="<?= $feat_img ?>" alt="<?= $featured['judul'] ?>"
                        class="w-full h-96 object-cover transform group-hover:scale-105 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/60 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-8 md:p-12 w-full md:w-2/3">
                        <span
                            class="inline-block px-3 py-1 bg-emerald-600 text-white text-xs font-bold rounded-full mb-4"><?= $featured['kategori'] ?></span>
                        <h2
                            class="text-3xl md:text-4xl font-bold text-white mb-4 leading-tight group-hover:text-emerald-400 transition">
                            <a href="post.php?slug=<?= $featured['slug'] ?>"><?= $featured['judul'] ?></a>
                        </h2>
                        <div class="flex items-center text-gray-300 gap-4 text-sm">
                            <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg> <?= date('d M Y', strtotime($featured['tanggal'])) ?></span>
                            <span class="flex items-center gap-1"><svg class="w-4 h-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg> <?= $featured['penulis'] ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
<?php endif; ?>

<!-- List Berita -->
<section class="pb-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h3 class="text-2xl font-bold text-gray-900 mb-8 border-l-4 border-emerald-600 pl-4">Berita Terbaru</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <?php
            // Exclude featured ID from list
            $exclude_sql = $featured_id ? "AND id != '$featured_id'" : "";

            // Count for pagination
            // Count Berita
            $result_count_berita = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM berita WHERE 1=1 $exclude_sql");
            $total_berita = mysqli_fetch_assoc($result_count_berita)['total'];

            // Count School Activities (Non-Admin)
            $result_count_kegiatan = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kegiatan WHERE created_by NOT IN (SELECT id FROM users WHERE role='admin')");
            $total_kegiatan = mysqli_fetch_assoc($result_count_kegiatan)['total'];

            $total_records = $total_berita + $total_kegiatan;
            $total_pages = ceil($total_records / $limit);

            // Fetch News List & School Activities
            // UNION query
            $query_sql = "
                (SELECT id, judul, slug, kategori, tanggal, gambar, isi, created_at, 'berita' as type 
                 FROM berita WHERE 1=1 $exclude_sql)
                UNION ALL
                (SELECT id, judul, slug, 'Kegiatan Madrasah' as kategori, tanggal, gambar, deskripsi as isi, created_at, 'kegiatan' as type 
                 FROM kegiatan WHERE created_by NOT IN (SELECT id FROM users WHERE role='admin'))
                ORDER BY tanggal DESC LIMIT $start, $limit
            ";

            $query = mysqli_query($koneksi, $query_sql);

            if (mysqli_num_rows($query) > 0):
                while ($row = mysqli_fetch_assoc($query)):
                    $img_src = $row['gambar'] ? $row['gambar'] : 'https://ui-avatars.com/api/?name=' . urlencode($row['judul']) . '&background=059669&color=fff&size=600';
                    $detail_url = ($row['type'] == 'kegiatan') ? 'kegiatan_detail.php?slug=' . $row['slug'] : 'post.php?slug=' . $row['slug'];
                    ?>
                            <article class="flex flex-col md:flex-row gap-6 items-start group border-b border-gray-100 pb-8"
                                data-aos="fade-up">
                                <div class="w-full md:w-5/12 overflow-hidden rounded-xl shadow-md h-52 flex-shrink-0">
                                    <img src="<?= $img_src ?>"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500"
                                        alt="<?= $row['judul'] ?>">
                                </div>
                                <div class="w-full md:w-7/12">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="text-emerald-600 text-xs font-bold uppercase"><?= $row['kategori'] ?></span>
                                        <span class="text-gray-400 text-xs">• <?= date('d M Y', strtotime($row['tanggal'])) ?></span>
                                    </div>
                                    <h4
                                        class="text-xl font-bold text-gray-900 mb-3 group-hover:text-emerald-600 transition leading-snug">
                                        <a href="<?= $detail_url ?>"><?= $row['judul'] ?></a>
                                    </h4>
                                    <p class="text-gray-600 text-sm line-clamp-3 mb-4">
                                        <?= substr(strip_tags($row['isi']), 0, 150) ?>...
                                    </p>
                                    <a href="<?= $detail_url ?>"
                                        class="text-emerald-600 font-medium text-sm hover:underline">Baca Selengkapnya &rarr;</a>
                                </div>
                            </article>
                        <?php
                endwhile;
            else:
                ?>
                    <div class="col-span-2 text-center py-10">
                        <p class="text-gray-500">Belum ada berita lainnya.</p>
                    </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
                <div class="mt-12 flex justify-center">
                    <nav class="flex items-center space-x-2">
                        <?php if ($page > 1): ?>
                                <a href="?page=<?= $page - 1 ?>"
                                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-300 transition">Prev</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <?php if ($i == $page): ?>
                                        <span class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold shadow-md"><?= $i ?></span>
                                <?php else: ?>
                                        <a href="?page=<?= $i ?>"
                                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-300 transition"><?= $i ?></a>
                                <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($page < $total_pages): ?>
                                <a href="?page=<?= $page + 1 ?>"
                                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-300 transition">Next</a>
                        <?php endif; ?>
                    </nav>
                </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>