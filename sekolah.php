<?php
include 'includes/init_sekolah.php';
include 'includes/header_sekolah.php';
?>

<!-- Hero Section (Home) -->
<!-- Hero Section (Home) - Compact Modern Design -->
<div id="home" class="relative py-20 bg-slate-900 overflow-hidden flex items-center">

    <!-- Background Image (Jika Ada) -->
    <?php if (!empty($school['hero_sekolah'])): ?>
        <div class="absolute inset-0 w-full h-full bg-cover bg-center z-0"
            style="background-image: url('<?= $base_path ?>/<?= $school['hero_sekolah'] ?>');"></div>
    <?php endif; ?>

    <!-- Abstract Gradient Overlay -->
    <!-- Jika ada foto, opacity dikurangi menjadi 85% agar foto terlihat samar di bawahnya -->
    <div class="absolute inset-0 w-full h-full bg-gradient-to-r from-emerald-900 to-slate-900 z-10 
        <?= !empty($school['hero_sekolah']) ? 'opacity-90 mix-blend-multiply' : 'opacity-95' ?>">
    </div>

    <!-- Carbon Texture (Hanya jika TIDAK ada foto agar tidak terlalu 'ramai') -->
    <?php if (empty($school['hero_sekolah'])): ?>
        <div
            class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-20 mix-blend-overlay z-10">
        </div>
    <?php endif; ?>

    <!-- Decorative Circles -->
    <div
        class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 bg-emerald-500 rounded-full mix-blend-soft-light filter blur-3xl opacity-20 animate-pulse z-10">
    </div>
    <div
        class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-teal-500 rounded-full mix-blend-soft-light filter blur-3xl opacity-20 animate-pulse delay-700 z-10">
    </div>

    <div class="container mx-auto px-4 relative z-20">
        <div class="max-w-3xl mx-auto text-center">
            <!-- Badge -->
            <div
                class="inline-block mb-4 px-4 py-1.5 rounded-full bg-emerald-500/10 border border-emerald-500/20 backdrop-blur-sm">
                <span class="text-emerald-300 text-sm font-semibold tracking-wider uppercase">Portal Resmi
                    Sekolah</span>
            </div>

            <!-- Title -->
            <h1 class="text-3xl md:text-5xl font-bold text-white mb-6 leading-tight tracking-tight">
                <?= $school['nama_sekolah'] ?>
            </h1>

            <!-- Description -->
            <p class="text-slate-300 text-base md:text-lg mb-8 leading-relaxed max-w-2xl mx-auto font-light">
                <?= $school['deskripsi_sekolah'] ? $school['deskripsi_sekolah'] : 'Pusat informasi, kegiatan, dan layanan pendidikan terkini.' ?>
            </p>

            <!-- Compact Actions -->
            <div class="flex flex-wrap gap-3 justify-center">
                <a href="#profil"
                    class="px-6 py-2.5 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-medium transition shadow-lg shadow-emerald-500/20 text-sm flex items-center">
                    Profil Lengkap
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </a>
                <a href="#berita"
                    class="px-6 py-2.5 rounded-lg bg-white/5 hover:bg-white/10 text-white font-medium border border-white/10 transition text-sm">
                    Lihat Berita
                </a>
            </div>
        </div>
    </div>
</div>





<!-- Pengumuman Section (Static Alert) -->
<?php
// Query Active Announcements: Start <= Today AND (End >= Today OR End IS NULL)
$query_pengumuman = mysqli_query($koneksi, "SELECT * FROM pengumuman WHERE tanggal <= CURDATE() AND (tanggal_berakhir >= CURDATE() OR tanggal_berakhir IS NULL OR tanggal_berakhir = '0000-00-00') ORDER BY tanggal DESC LIMIT 3");
if (mysqli_num_rows($query_pengumuman) > 0):
    ?>
    <section class="container mx-auto px-4 -mt-8 relative z-30 mb-8">
        <div class="bg-white rounded-xl shadow-lg border-l-4 border-red-500 overflow-hidden">
            <div class="flex flex-col md:flex-row">
                <!-- Label -->
                <div
                    class="bg-red-500 text-white px-6 py-4 flex flex-col items-center justify-center min-w-[200px] text-center">
                    <div class="flex items-center mb-2">
                        <svg class="w-6 h-6 mr-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                            </path>
                        </svg>
                        <span class="font-bold text-lg tracking-wide">PENGUMUMAN</span>
                    </div>
                    <a href="<?= $base_path ?>/mi/<?= $slug ?>/pengumuman"
                        class="text-xs bg-white/20 hover:bg-white/30 px-3 py-1 rounded-full transition flex items-center">
                        Lihat Arsip <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </div>

                <!-- Content List -->
                <div class="flex-1 py-3 px-4 md:px-6 flex flex-col justify-center space-y-3 bg-red-50/30">
                    <?php while ($p = mysqli_fetch_assoc($query_pengumuman)): ?>
                        <div
                            class="flex items-start md:items-center group border-b border-red-100 last:border-0 pb-2 last:pb-0">
                            <span
                                class="w-2 h-2 bg-red-400 rounded-full mt-2 md:mt-0 mr-3 flex-shrink-0 group-hover:bg-red-600 transition"></span>
                            <div class="flex-1 flex flex-col md:flex-row md:items-center justify-between gap-2">
                                <a href="<?= $base_path ?>/mi/<?= $slug ?>/pengumuman/<?= $p['id'] ?>"
                                    class="font-medium text-gray-800 hover:text-red-600 transition line-clamp-1">
                                    <?= $p['judul'] ?>
                                </a>
                                <span
                                    class="text-xs text-gray-500 bg-white px-2 py-0.5 rounded border border-gray-200 whitespace-nowrap">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    Berlaku s.d:
                                    <?= $p['tanggal_berakhir'] ? date('d M', strtotime($p['tanggal_berakhir'])) : 'Seterusnya' ?>
                                </span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- Berita Terbaru -->
<div class="container mx-auto px-4 py-8 bg-gray-50">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <!-- Left Column: Content Feed -->
        <div class="lg:col-span-2 space-y-12">

            <!-- Berita Section -->
            <div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 border-l-4 border-emerald-500 pl-3">Berita Terbaru</h2>
                    <a href="/mi/<?= $slug ?>/berita"
                        class="text-sm font-semibold text-emerald-600 hover:text-emerald-700">Lihat Semua →</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php
                    $query_berita = mysqli_query($koneksi, "SELECT * FROM berita WHERE created_by='$author_id' ORDER BY tanggal DESC LIMIT 4");
                    if (mysqli_num_rows($query_berita) > 0):
                        while ($row = mysqli_fetch_assoc($query_berita)):
                            ?>
                            <article
                                class="bg-white rounded-xl shadow-sm hover:shadow-md transition border border-gray-100 overflow-hidden group h-full flex flex-col">
                                <div class="relative h-48 overflow-hidden">
                                    <img src="<?= $row['gambar'] ?>" alt="<?= $row['judul'] ?>"
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
                                        <a
                                            href="<?= $base_path ?>/mi/<?= $slug ?>/berita/<?= $row['id'] ?>"><?= substr($row['judul'], 0, 60) . (strlen($row['judul']) > 60 ? '...' : '') ?></a>
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
                        <?php endwhile; else: ?>
                        <div class="col-span-2 p-8 bg-white rounded-xl shadow-sm border border-gray-100 text-center">
                            <p class="text-gray-500 font-medium">Belum ada berita yang diposting.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Kegiatan Section -->
            <div class="pt-10">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 border-l-4 border-purple-500 pl-3">Agenda & Kegiatan
                    </h2>
                    <a href="/mi/<?= $slug ?>/kegiatan"
                        class="text-sm font-semibold text-purple-600 hover:text-purple-700">Lihat Semua →</a>
                </div>

                <div class="space-y-4">
                    <?php
                    $query_kegiatan = mysqli_query($koneksi, "SELECT * FROM kegiatan WHERE created_by='$author_id' ORDER BY tanggal DESC LIMIT 3");
                    if (mysqli_num_rows($query_kegiatan) > 0):
                        while ($row = mysqli_fetch_assoc($query_kegiatan)):
                            ?>
                            <div
                                class="flex flex-col md:flex-row bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">
                                <div class="md:w-1/3 h-48 md:h-auto relative overflow-hidden">
                                    <img src="<?= $row['gambar'] ?>" alt="<?= $row['judul'] ?>"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                                    <div
                                        class="absolute top-2 left-2 bg-purple-600 text-white text-xs font-bold px-2 py-1 rounded">
                                        <?= $row['kategori'] ?>
                                    </div>
                                </div>
                                <div class="p-6 md:w-2/3 flex flex-col justify-center">
                                    <h3 class="font-bold text-lg text-gray-800 mb-2 hover:text-purple-600 transition">
                                        <a
                                            href="<?= $base_path ?>/mi/<?= $slug ?>/kegiatan/<?= $row['id'] ?>"><?= $row['judul'] ?></a>
                                    </h3>
                                    <div class="flex items-center text-sm text-gray-500 mb-4 space-x-4">
                                        <span class="flex items-center bg-gray-50 px-2 py-1 rounded text-xs"><svg
                                                class="w-4 h-4 mr-1 text-purple-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg> <?= date('d M Y', strtotime($row['tanggal'])) ?></span>
                                        <span class="flex items-center bg-gray-50 px-2 py-1 rounded text-xs"><svg
                                                class="w-4 h-4 mr-1 text-purple-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg> <?= $row['lokasi'] ?></span>
                                    </div>
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
                        <?php endwhile; else: ?>
                        <div
                            class="p-8 bg-white rounded-xl shadow-sm border border-gray-100 text-center text-gray-400 border-dashed border-2">
                            <span>Belum ada kegiatan yang diagendakan.</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Right Column: Sidebar -->
        <div class="space-y-8">
            <?php include 'includes/sidebar_sekolah.php'; ?>
        </div>

    </div>
</div>

<?php include 'includes/footer_sekolah.php'; ?>