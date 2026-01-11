<?php
include 'includes/init_sekolah.php';
include 'includes/header_sekolah.php';

// Get announcement ID
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$query = mysqli_query($koneksi, "SELECT * FROM pengumuman WHERE id='$id'");

if (mysqli_num_rows($query) == 0) {
    echo "<div class='container mx-auto px-4 py-20 text-center'><h2 class='text-2xl font-bold text-gray-700'>Pengumuman Tidak Ditemukan</h2></div>";
    include 'includes/footer_sekolah.php';
    exit;
}

$p = mysqli_fetch_assoc($query);
?>

<div class="bg-gray-50 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">

            <!-- Header -->
            <div class="bg-gradient-to-r from-red-600 to-rose-600 px-8 py-6">
                <div class="flex items-center text-red-100 text-sm font-medium mb-3">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        Diposting:
                        <?= date('d M Y', strtotime($p['tanggal'])) ?>
                    </span>
                    <?php if ($p['tanggal_berakhir']): ?>
                        <span class="mx-3">|</span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Berlaku s.d:
                            <?= date('d M Y', strtotime($p['tanggal_berakhir'])) ?>
                        </span>
                    <?php endif; ?>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold text-white leading-tight">
                    <?= $p['judul'] ?>
                </h1>
            </div>

            <!-- Content -->
            <div class="p-8">
                <div class="prose prose-lg max-w-none text-gray-700">
                    <?= $p['isi'] ?> <!-- Already contains HTML from TinyMCE -->
                </div>

                <!-- Attachment -->
                <?php if (!empty($p['lampiran'])): ?>
                    <div class="mt-8 border-t border-gray-100 pt-6">
                        <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                </path>
                            </svg>
                            Lampiran Dokumen
                        </h3>
                        <a href="<?= $base_path ?>/<?= $p['lampiran'] ?>" target="_blank"
                            class="inline-flex items-center px-5 py-3 rounded-xl bg-gray-100 text-gray-700 font-medium hover:bg-emerald-50 hover:text-emerald-700 hover:border-emerald-200 border border-transparent transition group">
                            <div class="p-2 bg-white rounded-lg shadow-sm mr-3 group-hover:scale-110 transition">
                                <?php
                                $ext = strtolower(pathinfo($p['lampiran'], PATHINFO_EXTENSION));
                                if ($ext == 'pdf') {
                                    echo '<svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>';
                                } else {
                                    echo '<svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>';
                                }
                                ?>
                            </div>
                            <span>Download / Lihat
                                <?= basename($p['lampiran']) ?>
                            </span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="px-8 py-6 bg-gray-50 border-t border-gray-100 flex justify-end">
                <a href="<?= $base_path ?>/mi/<?= $slug ?>"
                    class="text-sm font-semibold text-gray-500 hover:text-gray-800 flex items-center">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer_sekolah.php'; ?>