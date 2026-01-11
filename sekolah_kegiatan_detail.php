<?php
include 'includes/init_sekolah.php';
include 'includes/header_sekolah.php';

// Fetch kegiatan detail
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = mysqli_query($koneksi, "SELECT kegiatan.*, users.nama_lengkap as author_name FROM kegiatan LEFT JOIN users ON kegiatan.created_by = users.id WHERE kegiatan.id='$id' AND kegiatan.created_by='$author_id'");

    if (mysqli_num_rows($query) > 0) {
        $kegiatan = mysqli_fetch_assoc($query);
    } else {
        echo "<script>window.location='{$base_path}/mi/{$slug}/kegiatan';</script>";
        exit;
    }
} else {
    echo "<script>window.location='{$base_path}/mi/{$slug}/kegiatan';</script>";
    exit;
}
?>

<!-- Hero Section -->
<div class="relative h-[350px] md:h-[450px] w-full flex items-center justify-center bg-gray-900">
    <?php
    $img_src = $kegiatan['gambar'] ? $kegiatan['gambar'] : 'https://ui-avatars.com/api/?name=' . urlencode($kegiatan['judul']) . '&background=059669&color=fff&size=800';
    ?>
    <div class="absolute inset-0 overflow-hidden">
        <img src="<?= $img_src ?>" alt="Background" class="w-full h-full object-cover opacity-40 blur-sm scale-110">
    </div>
    <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/90 to-transparent"></div>

    <div class="relative z-10 container mx-auto px-4 text-center mt-10">
        <span
            class="bg-emerald-500/20 text-emerald-100 border border-emerald-500/30 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-widest backdrop-blur-md mb-6 inline-block">
            <?= $kegiatan['kategori'] ?>
        </span>
        <h1 class="text-3xl md:text-5xl font-bold text-white mb-6 leading-tight max-w-4xl mx-auto drop-shadow-lg">
            <?= $kegiatan['judul'] ?>
        </h1>
        <div
            class="flex flex-wrap items-center justify-center text-emerald-100 text-sm md:text-base gap-6 bg-black/20 inline-flex px-6 py-3 rounded-full backdrop-blur-sm border border-white/10">
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <?= date('d F Y', strtotime($kegiatan['tanggal'])) ?>
            </span>
            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <?= $kegiatan['lokasi'] ?>
            </span>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

        <!-- Left: Main Content -->
        <div class="lg:col-span-8">
            <!-- Featured Image -->
            <div class="bg-gray-100 rounded-2xl overflow-hidden shadow-sm mb-8 border border-gray-100">
                <img src="<?= $img_src ?>" alt="<?= $kegiatan['judul'] ?>" class="w-full h-auto object-cover">
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6 md:p-10 border border-gray-100">
                <div
                    class="prose prose-lg max-w-none prose-emerald prose-headings:text-gray-900 prose-p:text-justify prose-p:leading-relaxed prose-p:text-gray-700">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Deskripsi Kegiatan</h3>
                    <?= $kegiatan['deskripsi'] ?>
                    <p class="mt-8 text-sm text-gray-500 italic border-t border-gray-100 pt-4">
                        Diposting oleh: <span class="font-medium">
                            <?= $kegiatan['author_name'] ?? 'Admin' ?>
                        </span>
                    </p>
                </div>

                <!-- Back Button -->
                <div class="mt-10 border-t border-gray-100 pt-8">
                    <a href="<?= $base_path ?>/mi/<?= $slug ?>/kegiatan"
                        class="inline-flex items-center text-gray-600 hover:text-emerald-600 font-semibold transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Daftar Kegiatan
                    </a>
                </div>
            </div>
        </div>

        <!-- Right: Sidebar -->
        <div class="lg:col-span-4 space-y-8">

            <!-- Info Box -->
            <div class="bg-emerald-50 rounded-2xl p-6 border border-emerald-100">
                <h3 class="text-lg font-bold text-emerald-900 mb-4">Informasi Kegiatan</h3>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <div class="bg-white p-2 rounded-lg text-emerald-600 shadow-sm mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <span
                                class="text-xs text-emerald-600 font-bold uppercase tracking-wider block mb-0.5">Waktu</span>
                            <span class="text-gray-800 font-medium">
                                <?= date('l, d F Y', strtotime($kegiatan['tanggal'])) ?>
                            </span>
                        </div>
                    </li>
                    <li class="flex items-start">
                        <div class="bg-white p-2 rounded-lg text-emerald-600 shadow-sm mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <span
                                class="text-xs text-emerald-600 font-bold uppercase tracking-wider block mb-0.5">Lokasi</span>
                            <span class="text-gray-800 font-medium">
                                <?= $kegiatan['lokasi'] ?>
                            </span>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Kegiatan Lainnya
                </h3>
                <div class="space-y-4">
                    <?php
                    $query_other = mysqli_query($koneksi, "SELECT * FROM kegiatan WHERE created_by='$author_id' AND id != '{$kegiatan['id']}' ORDER BY tanggal DESC LIMIT 4");
                    while ($other = mysqli_fetch_assoc($query_other)):
                        $img_other = $other['gambar'] ? $other['gambar'] : 'https://ui-avatars.com/api/?name=' . urlencode($other['judul']) . '&background=059669&color=fff&size=200';
                        ?>
                        <div class="flex gap-4 group">
                            <a href="<?= $base_path ?>/mi/<?= $slug ?>/kegiatan/<?= $other['id'] ?>"
                                class="w-16 h-16 flex-shrink-0 rounded-lg overflow-hidden relative block">
                                <img src="<?= $img_other ?>"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition duration-300"
                                    alt="Thumb">
                            </a>
                            <div class="flex-grow">
                                <h4
                                    class="text-sm font-bold text-gray-800 line-clamp-2 group-hover:text-emerald-600 transition mb-1 leading-snug">
                                    <a href="<?= $base_path ?>/mi/<?= $slug ?>/kegiatan/<?= $other['id'] ?>">
                                        <?= $other['judul'] ?>
                                    </a>
                                </h4>
                                <span class="text-xs text-gray-400">
                                    <?= date('d M Y', strtotime($other['tanggal'])) ?>
                                </span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?php include 'includes/footer_sekolah.php'; ?>