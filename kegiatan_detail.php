<?php include 'includes/header.php'; ?>
<?php
require_once 'config/koneksi.php';
require_once 'includes/database.php'; // Security: Include the new database helper
?>

<?php
if (isset($_GET['slug'])) { // Use slug if available
    $slug = $_GET['slug'];
    // Security: Use prepared statement to fetch data
    $query = db_query($koneksi, "SELECT kegiatan.*, users.nama_lengkap as author_name FROM kegiatan LEFT JOIN users ON kegiatan.created_by = users.id WHERE kegiatan.slug=?", 's', [$slug]);

    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
    } else {
        echo "<script>window.location='kegiatan.php';</script>";
        exit;
    }
} else {
    echo "<script>window.location='kegiatan.php';</script>";
    exit;
}
?>

<!-- Hero / Banner Section -->
<div class="relative h-[350px] md:h-[450px] w-full flex items-center justify-center bg-gray-900">
    <?php
    $img_src = $row['gambar'] ? $row['gambar'] : 'https://ui-avatars.com/api/?name=' . urlencode($row['judul']) . '&background=059669&color=fff&size=800';
    $bg_check = strpos($img_src, 'http') === false && strpos($img_src, 'assets') !== false ? $img_src : $img_src;
    ?>
    <div class="absolute inset-0 overflow-hidden">
        <img src="<?= $bg_check ?>" alt="Background" class="w-full h-full object-cover opacity-40 blur-sm scale-110">
    </div>
    <div class="absolute inset-0 bg-gradient-to-t from-emerald-900/90 to-transparent"></div>

    <div class="relative z-10 container mx-auto px-4 text-center mt-10">
        <span
            class="bg-emerald-500/20 text-emerald-100 border border-emerald-500/30 text-xs font-bold px-4 py-1.5 rounded-full uppercase tracking-widest backdrop-blur-md mb-6 inline-block">
            <?= $row['kategori'] ?>
        </span>
        <h1 class="text-3xl md:text-5xl font-bold text-white mb-6 leading-tight max-w-4xl mx-auto drop-shadow-lg">
            <?= $row['judul'] ?>
        </h1>
        <div
            class="flex flex-wrap items-center justify-center text-emerald-100 text-sm md:text-base gap-6 bg-black/20 inline-flex px-6 py-3 rounded-full backdrop-blur-sm border border-white/10">
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
                <?= date('d F Y', strtotime($row['tanggal'])) ?>
            </span>
            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <?= $row['lokasi'] ?>
            </span>
        </div>
    </div>
</div>

<!-- Main Content -->
<section class="py-12 md:py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

            <!-- Left: Main Image & Content -->
            <div class="lg:col-span-8">
                <!-- Featured Image Normal -->
                <div class="bg-gray-100 rounded-2xl overflow-hidden shadow-sm mb-8 border border-gray-100">
                    <img src="<?= $img_src ?>" alt="<?= $row['judul'] ?>" class="w-full h-auto object-cover">
                </div>

                <div
                    class="prose prose-lg max-w-none prose-emerald prose-headings:text-gray-900 prose-p:text-justify prose-p:leading-relaxed prose-p:text-gray-700">
                    <h3 class="text-2xl font-bold text-gray-800 mb-4">Deskripsi Kegiatan</h3>
                    <?= $row['deskripsi'] ?>
                    <p class="mt-8 text-sm text-gray-500 italic border-t border-gray-100 pt-4">
                        Diposting oleh: <span class="font-medium"><?= $row['author_name'] ?? 'Admin' ?></span>
                    </p>
                </div>

                <hr class="my-8 border-gray-100">

                <!-- Share Button -->
                <?php
                $share_url = urlencode($base_url . '/kegiatan_detail.php?slug=' . $row['slug']);
                $share_title = urlencode($row['judul']);
                ?>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600 font-medium">Bagikan Kegiatan:</span>
                    <div class="flex space-x-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $share_url ?>" target="_blank"
                            class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition"
                            title="Share to Facebook">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z" />
                            </svg>
                        </a>
                        <a href="https://twitter.com/intent/tweet?text=<?= $share_title ?>&url=<?= $share_url ?>"
                            target="_blank"
                            class="w-9 h-9 rounded-full bg-sky-500 text-white flex items-center justify-center hover:bg-sky-600 transition"
                            title="Share to Twitter">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.44 4.83c-.8.37-1.5.38-2.22.02.94-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z" />
                            </svg>
                        </a>
                        <a href="https://api.whatsapp.com/send?text=<?= $share_title ?>%20<?= $share_url ?>"
                            target="_blank"
                            class="w-9 h-9 rounded-full bg-green-500 text-white flex items-center justify-center hover:bg-green-600 transition"
                            title="Share to WhatsApp">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Back Button -->
                <div class="mt-10 border-t border-gray-100 pt-8">
                    <a href="kegiatan.php"
                        class="inline-flex items-center text-gray-600 hover:text-emerald-600 font-semibold transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Daftar Kegiatan
                    </a>
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
                                <span
                                    class="text-gray-800 font-medium"><?= date('l, d F Y', strtotime($row['tanggal'])) ?></span>
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
                                <span class="text-gray-800 font-medium"><?= $row['lokasi'] ?></span>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Other Activities -->
                <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100">Kegiatan Lainnya</h3>
                    <div class="space-y-4">
                        <?php
                        // Security: Use prepared statement
                        $query_other = db_query($koneksi, "SELECT * FROM kegiatan WHERE id != ? ORDER BY tanggal DESC LIMIT 4", 'i', [$row['id']]);
                        if ($query_other) {
                            while ($other = mysqli_fetch_assoc($query_other)) :
                                $img_other = $other['gambar'] ? $other['gambar'] : 'https://ui-avatars.com/api/?name=' . urlencode($other['judul']) . '&background=059669&color=fff&size=200';
                        ?>
                            <div class="flex gap-4 group">
                                <a href="kegiatan_detail.php?slug=<?= $other['slug'] ?>"
                                    class="w-16 h-16 flex-shrink-0 rounded-lg overflow-hidden relative block">
                                    <img src="<?= $img_other ?>"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-300"
                                        alt="Thumb">
                                </a>
                                <div class="flex-grow">
                                    <h4
                                        class="text-sm font-bold text-gray-800 line-clamp-2 group-hover:text-emerald-600 transition mb-1 leading-snug">
                                        <a href="kegiatan_detail.php?slug=<?= $other['slug'] ?>"><?= $other['judul'] ?></a>
                                    </h4>
                                    <span
                                        class="text-xs text-gray-400"><?= date('d M Y', strtotime($other['tanggal'])) ?></span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                        <a href="kegiatan.php"
                            class="text-sm font-bold text-emerald-600 hover:text-emerald-700 hover:underline">Lihat
                            Semua Kegiatan &rarr;</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>