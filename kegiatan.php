<?php
require_once 'config/koneksi.php';
include 'includes/header.php';
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
            Kegiatan & Agenda
        </h1>
        <p class="text-lg text-emerald-100 max-w-2xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="100">
            Dokumentasi dan jadwal kegiatan pengembangan profesionalisme guru madrasah.
        </p>
    </div>
</section>

<!-- Filter / Search -->
<?php
// Get current filter values
$current_cat = isset($_GET['cat']) ? $_GET['cat'] : '';
$current_q = isset($_GET['q']) ? $_GET['q'] : '';

// Categories for filter
$categories = ['', 'Workshop', 'Seminar', 'Rapat', 'Pelatihan', 'Kunjungan'];
$category_labels = ['Semua', 'Workshop', 'Seminar', 'Rapat', 'Pelatihan', 'Kunjungan'];
?>
<section class="py-10 bg-gray-50 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex space-x-2 overflow-x-auto pb-2 w-full md:w-auto">
                <?php foreach ($categories as $index => $cat):
                    $isActive = ($current_cat === $cat);
                    $activeClass = $isActive
                        ? 'bg-emerald-600 text-white shadow-md'
                        : 'bg-white text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 border border-gray-200';
                    $url = '?cat=' . urlencode($cat) . ($current_q ? '&q=' . urlencode($current_q) : '');
                    ?>
                    <a href="<?= $url ?>"
                        class="px-5 py-2 rounded-full <?= $activeClass ?> font-medium transition whitespace-nowrap">
                        <?= $category_labels[$index] ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <form method="GET" action="" class="relative w-full md:w-64">
                <?php if ($current_cat): ?>
                    <input type="hidden" name="cat" value="<?= htmlspecialchars($current_cat) ?>">
                <?php endif; ?>
                <input type="text" name="q" value="<?= htmlspecialchars($current_q) ?>" placeholder="Cari kegiatan..."
                    class="w-full pl-10 pr-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition">
                <button type="submit" class="absolute inset-y-0 left-0 pl-3 flex items-center">
                    <svg class="h-5 w-5 text-gray-400 hover:text-emerald-500 transition" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>
</section>

<!-- Kegiatan Grid -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Grid Container -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <!-- Search Logic -->
            <?php
            // Pagination Setup
            $limit = 6;
            $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            $start = ($page - 1) * $limit;

            // Search & Filter
            // USER REQUEST: Show only activities by ADMIN. 
            // Admin role is 'admin'.
            $where = "WHERE created_by IN (SELECT id FROM users WHERE role='admin')";
            if (isset($_GET['cat']) && !empty($_GET['cat'])) {
                $cat = mysqli_real_escape_string($koneksi, $_GET['cat']);
                $where .= " AND kategori = '$cat'";
            }
            if (isset($_GET['q']) && !empty($_GET['q'])) {
                $q = mysqli_real_escape_string($koneksi, $_GET['q']);
                $where .= " AND (judul LIKE '%$q%' OR deskripsi LIKE '%$q%')";
            }

            // Count Total
            $result_count = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kegiatan $where");
            $total_records = mysqli_fetch_assoc($result_count)['total'];
            $total_pages = ceil($total_records / $limit);

            // Fetch Data
            $query = mysqli_query($koneksi, "SELECT * FROM kegiatan $where ORDER BY tanggal DESC LIMIT $start, $limit");

            if (mysqli_num_rows($query) > 0):
                while ($item = mysqli_fetch_assoc($query)):
                    $badgeColor = 'bg-gray-600';
                    switch ($item['kategori']) {
                        case 'Workshop':
                            $badgeColor = 'bg-emerald-600';
                            break;
                        case 'Seminar':
                            $badgeColor = 'bg-purple-600';
                            break;
                        case 'Rapat':
                            $badgeColor = 'bg-blue-600';
                            break;
                        case 'Pelatihan':
                            $badgeColor = 'bg-indigo-600';
                            break;
                        case 'Kunjungan':
                            $badgeColor = 'bg-orange-600';
                            break;
                        default:
                            $badgeColor = 'bg-gray-600';
                            break;
                    }

                    $img_src = $item['gambar'] ? $item['gambar'] : 'https://ui-avatars.com/api/?name=' . urlencode($item['judul']) . '&background=059669&color=fff&size=800';
                    if (strpos($img_src, 'http') === false && strpos($img_src, 'assets') !== false) {
                        $img_src = $img_src;
                    }
                    ?>
                    <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 overflow-hidden group hover:shadow-[0_8px_30px_rgb(16,185,129,0.15)] transition-all duration-300 flex flex-col h-full transform hover:-translate-y-2"
                        data-aos="fade-up">
                        <div class="relative overflow-hidden h-60 flex-shrink-0">
                            <img src="<?= $img_src ?>" alt="<?= $item['judul'] ?>"
                                class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>

                            <div
                                class="absolute top-4 left-4 <?= $badgeColor ?> text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wide shadow-lg ring-2 ring-white/20 backdrop-blur-md">
                                <?= $item['kategori'] ?>
                            </div>
                        </div>
                        <div class="p-6 flex flex-col flex-grow relative">
                            <!-- Date badge overlapping image -->
                            <div
                                class="absolute -top-6 right-6 bg-white p-2 rounded-xl shadow-lg text-center min-w-[60px] border border-gray-100">
                                <span
                                    class="block text-xs text-gray-500 uppercase font-bold"><?= date('M', strtotime($item['tanggal'])) ?></span>
                                <span
                                    class="block text-xl font-black text-gray-800"><?= date('d', strtotime($item['tanggal'])) ?></span>
                            </div>

                            <div class="flex items-center text-sm text-gray-400 mb-3 gap-2 mt-2">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <?= substr($item['lokasi'], 0, 20) . (strlen($item['lokasi']) > 20 ? '...' : '') ?>
                            </div>

                            <h3
                                class="text-xl font-bold text-gray-900 mb-3 group-hover:text-emerald-600 transition leading-snug">
                                <?= $item['judul'] ?>
                            </h3>

                            <p class="text-gray-500 text-sm line-clamp-2 mb-6 flex-grow">
                                <?= strip_tags($item['deskripsi']) ?>
                            </p>

                            <a href="kegiatan_detail.php?slug=<?= $item['slug'] ?>"
                                class="inline-flex items-center justify-center w-full px-4 py-2.5 bg-gray-50 text-gray-700 font-bold rounded-xl group-hover:bg-emerald-600 group-hover:text-white transition-colors duration-300">
                                Lihat Detail
                                <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                <?php endwhile;
            else: ?>
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-12">
                    <p class="text-gray-500 text-lg">Tidak ada kegiatan yang ditemukan.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php
        if ($total_pages > 1):
            // Build query string for pagination
            $query_params = [];
            if ($current_cat)
                $query_params['cat'] = $current_cat;
            if ($current_q)
                $query_params['q'] = $current_q;
            $base_query = http_build_query($query_params);
            $base_query = $base_query ? '&' . $base_query : '';
            ?>
            <div class="mt-16 flex justify-center">
                <nav class="flex items-center space-x-2">
                    <?php if ($page > 1): ?>
                        <a href="?page=<?= $page - 1 ?><?= $base_query ?>"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-300 transition">Previous</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php if ($i == $page): ?>
                            <span class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold shadow-md"><?= $i ?></span>
                        <?php else: ?>
                            <a href="?page=<?= $i ?><?= $base_query ?>"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-300 transition"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?page=<?= $page + 1 ?><?= $base_query ?>"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-300 transition">Next</a>
                    <?php endif; ?>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>