<?php
include 'includes/init_sekolah.php';
include 'includes/header_sekolah.php';

// Pagination Logic
$limit = 6;
$page = isset($_GET['hal']) ? (int) $_GET['hal'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

$total_query = mysqli_query($koneksi, "SELECT count(*) as total FROM kegiatan WHERE created_by='$author_id'");
$total_result = mysqli_fetch_assoc($total_query);
$total_pages = ceil($total_result['total'] / $limit);

$query = mysqli_query($koneksi, "SELECT * FROM kegiatan WHERE created_by='$author_id' ORDER BY tanggal DESC LIMIT $start, $limit");
?>

<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-8">
                <h1 class="text-3xl font-bold text-gray-800 border-l-4 border-purple-500 pl-4">Agenda & Kegiatan</h1>
            </div>

            <div class="space-y-6">
                <?php if (mysqli_num_rows($query) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($query)): ?>
                        <div
                            class="flex flex-col md:flex-row bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">
                            <div class="md:w-1/3 h-48 md:h-auto relative overflow-hidden">
                                <img src="/<?= $row['gambar'] ?>" alt="<?= $row['judul'] ?>"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                                <div class="absolute top-2 left-2 bg-purple-600 text-white text-xs font-bold px-2 py-1 rounded">
                                    <?= $row['kategori'] ?>
                                </div>
                            </div>
                            <div class="p-6 md:w-2/3 flex flex-col justify-center">
                                <h3 class="font-bold text-lg text-gray-800 mb-2 hover:text-purple-600 transition">
                                    <a href="<?= $base_path ?>/mi/<?= $slug ?>/kegiatan/<?= $row['id'] ?>">
                                        <?= $row['judul'] ?>
                                    </a>
                                </h3>
                                <div class="flex items-center text-sm text-gray-500 mb-4 space-x-4">
                                    <span class="flex items-center bg-gray-50 px-2 py-1 rounded text-xs"><svg
                                            class="w-4 h-4 mr-1 text-purple-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                        <?= date('d M Y', strtotime($row['tanggal'])) ?>
                                    </span>
                                    <span class="flex items-center bg-gray-50 px-2 py-1 rounded text-xs"><svg
                                            class="w-4 h-4 mr-1 text-purple-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <?= $row['lokasi'] ?>
                                    </span>
                                </div>
                                <p class="text-gray-500 text-sm mb-4 line-clamp-2">
                                    <?= strip_tags($row['deskripsi']) ?>
                                </p>
                                <a href="<?= $base_path ?>/mi/<?= $slug ?>/kegiatan/<?= $row['id'] ?>"
                                    class="text-purple-600 font-semibold text-sm hover:underline inline-flex items-center">
                                    Lihat Detail Kegiatan <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="p-12 bg-gray-50 rounded-xl border border-dashed border-gray-300 text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900">Belum ada kegiatan</h3>
                        <p class="text-gray-500">Agenda kegiatan sekolah akan muncul di sini.</p>
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
                            class="px-4 py-2 border rounded-lg transition <?= $i == $page ? 'bg-purple-600 text-white border-purple-600' : 'bg-white border-gray-300 hover:bg-emerald-50 text-gray-700' ?>">
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