<?php
include 'includes/init_sekolah.php';
include 'includes/header_sekolah.php';

// Pagination Logic
$limit = 6;
$page = isset($_GET['hal']) ? (int) $_GET['hal'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

$total_query = mysqli_query($koneksi, "SELECT count(*) as total FROM berita WHERE created_by='$author_id'");
$total_result = mysqli_fetch_assoc($total_query);
$total_pages = ceil($total_result['total'] / $limit);

$query = mysqli_query($koneksi, "SELECT * FROM berita WHERE created_by='$author_id' ORDER BY tanggal DESC LIMIT $start, $limit");
?>

<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-800 border-l-4 border-emerald-500 pl-4">Berita & Artikel</h1>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php if (mysqli_num_rows($query) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <article
                            class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-gray-100 overflow-hidden group h-full flex flex-col">
                            <div class="relative h-48 overflow-hidden">
                                <img src="/<?= $row['gambar'] ?>" alt="<?= $row['judul'] ?>"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                                <div
                                    class="absolute top-0 right-0 bg-emerald-600 text-white text-xs font-bold px-3 py-1 m-2 rounded-full">
                                    <?= $row['kategori'] ?>
                                </div>
                            </div>
                            <div class="p-5 flex-grow flex flex-col">
                                <div class="text-xs text-gray-500 mb-2 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <?= date('d M Y', strtotime($row['tanggal'])) ?>
                                </div>
                                <h3
                                    class="font-bold text-lg text-gray-800 mb-2 leading-tight hover:text-emerald-600 transition">
                                    <a href="<?= $base_path ?>/mi/<?= $slug ?>/berita/<?= $row['id'] ?>">
                                        <?= substr($row['judul'], 0, 60) . (strlen($row['judul']) > 60 ? '...' : '') ?>
                                    </a>
                                </h3>
                                <div class="mt-auto pt-3">
                                    <a href="<?= $base_path ?>/mi/<?= $slug ?>/berita/<?= $row['id'] ?>"
                                        class="text-sm text-emerald-600 font-semibold hover:underline flex items-center">
                                        Baca Selengkapnya <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="col-span-2 p-12 bg-gray-50 rounded-xl border border-dashed border-gray-300 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Belum ada berita</h3>
                        <p class="text-gray-500">Berita dan artikel terbaru akan muncul di sini.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="mt-12 flex justify-center space-x-2">
                    <?php if ($page > 1): ?>
                        <a href="?slug=<?= $slug ?>&hal=<?= $page - 1 ?>"
                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-emerald-50 text-emerald-600 transition">←
                            Prev</a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <a href="?slug=<?= $slug ?>&hal=<?= $i ?>"
                            class="px-4 py-2 border rounded-lg transition <?= $i == $page ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white border-gray-300 hover:bg-emerald-50 text-gray-700' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($page < $total_pages): ?>
                        <a href="?slug=<?= $slug ?>&hal=<?= $page + 1 ?>"
                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-emerald-50 text-emerald-600 transition">Next
                            →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
            <?php include 'includes/sidebar_sekolah.php'; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer_sekolah.php'; ?>