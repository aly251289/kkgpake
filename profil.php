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
            Profil KKG MI
        </h1>
        <p class="text-lg text-emerald-100 max-w-2xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="100">
            Mengenal lebih dekat visi, misi, dan struktur organisasi
            <?= $d_identitas['nama_organisasi'] ?? 'Kelompok Kerja Guru Madrasah Ibtidaiyah' ?>.
        </p>
    </div>
</section>

<!-- Sejarah & Visi Misi -->
<section class="py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row gap-16 items-start">
            <!-- Image / Left Column -->
            <div class="w-full md:w-1/2 sticky top-24 relative" data-aos="fade-right">
                <!-- Decorative blobs behind image -->
                <div
                    class="absolute -top-10 -left-10 w-40 h-40 bg-emerald-200 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob">
                </div>
                <div
                    class="absolute -bottom-10 -right-10 w-40 h-40 bg-sky-200 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob animation-delay-2000">
                </div>

                <div class="relative rounded-3xl overflow-hidden shadow-2xl group border-4 border-white">
                    <img src="https://images.unsplash.com/photo-1557804506-669a67965ba0?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80"
                        alt="Team Meeting"
                        class="w-full h-auto transform group-hover:scale-110 transition duration-1000">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 left-0 p-8 text-white">
                        <span
                            class="inline-block px-3 py-1 bg-white/20 backdrop-blur-md rounded-full text-xs font-bold mb-2 border border-white/30">Since
                            2010</span>
                        <p class="font-bold text-2xl mb-1">Sinergi & Kolaborasi</p>
                        <p class="text-sm text-gray-200">Rapat Koordinasi Pengurus KKG MI Tahun 2025</p>
                    </div>
                </div>
            </div>

            <!-- Content / Right Column -->
            <div class="w-full md:w-1/2 space-y-12">
                <!-- Tentang Kami -->
                <div data-aos="fade-left"
                    class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100 hover:shadow-xl transition-shadow duration-300">
                    <h2 class="text-3xl font-extrabold text-gray-900 mb-6 flex items-center gap-4">
                        <span
                            class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </span>
                        Tentang Kami
                    </h2>
                    <p class="text-gray-600 leading-relaxed mb-4 text-lg">
                        <?= $d_identitas['nama_organisasi'] ?? 'Kelompok Kerja Guru (KKG) Madrasah Ibtidaiyah' ?> adalah
                        forum profesional bagi guru-guru MI yang
                        bertujuan untuk <span class="text-emerald-600 font-bold">meningkatkan kualitas
                            pembelajaran</span> dan kompetensi pendidik.
                    </p>
                    <p class="text-gray-600 leading-relaxed">
                        Kami berkomitmen untuk menjadi wadah aspirasi, berbagi praktik baik, dan pengembangan karier
                        berkelanjutan bagi para guru madrasah di era digital.
                    </p>
                </div>

                <!-- Visi Misi -->
                <div data-aos="fade-left" data-aos-delay="100">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Vision Card -->
                        <div
                            class="bg-gradient-to-br from-emerald-600 to-teal-700 p-8 rounded-3xl shadow-lg run relative overflow-hidden">
                            <div
                                class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full translate-x-8 -translate-y-8 blur-2xl">
                            </div>
                            <h3
                                class="font-bold text-emerald-100 mb-2 uppercase tracking-wide text-sm flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Visi Kami
                            </h3>
                            <p class="text-white text-xl font-medium italic">"Terwujudnya Guru Madrasah Ibtidaiyah yang
                                Profesional, Inovatif, dan Berakhlakul Karimah."</p>
                        </div>

                        <!-- Mission List -->
                        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                            <h3 class="font-bold text-gray-900 mb-6 text-xl">Misi Utama</h3>
                            <ul class="space-y-4">
                                <li class="flex items-start gap-4">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center font-bold text-sm shadow-sm ring-4 ring-emerald-50">
                                        1</div>
                                    <span class="text-gray-600 pt-1">Meningkatkan kompetensi pedagogik, profesional,
                                        sosial, dan kepribadian guru.</span>
                                </li>
                                <li class="flex items-start gap-4">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center font-bold text-sm shadow-sm ring-4 ring-emerald-50">
                                        2</div>
                                    <span class="text-gray-600 pt-1">Memfasilitasi pertukaran informasi dan pengalaman
                                        antar guru MI.</span>
                                </li>
                                <li class="flex items-start gap-4">
                                    <div
                                        class="flex-shrink-0 w-8 h-8 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center font-bold text-sm shadow-sm ring-4 ring-emerald-50">
                                        3</div>
                                    <span class="text-gray-600 pt-1">Mengembangkan model pembelajaran yang kreatif,
                                        efektif, dan menyenangkan.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Struktur Organisasi -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16" data-aos="fade-up">
            <h2 class="text-emerald-600 font-bold tracking-wide uppercase text-sm mb-2">Kepengurusan</h2>
            <h3 class="text-3xl font-bold text-gray-900">Struktur Organisasi</h3>
        </div>

        <div class="flex flex-col items-center gap-12">
            <?php
            $query_pengurus = mysqli_query($koneksi, "SELECT * FROM pengurus ORDER BY urutan ASC");
            $data_pengurus = [];
            while ($row = mysqli_fetch_assoc($query_pengurus)) {
                $data_pengurus[] = $row;
            }

            if (count($data_pengurus) > 0):
                // Ketua (First item)
                $ketua = $data_pengurus[0];
                array_shift($data_pengurus); // Remove first item
                ?>

                <!-- Ketua Row -->
                <div class="flex justify-center w-full relative">
                    <!-- Connector Line (Optional visual flair) -->
                    <div class="absolute bottom-[-24px] left-1/2 w-1 h-12 bg-gray-300 -translate-x-1/2 hidden md:block z-0">
                    </div>

                    <div class="w-full sm:w-1/2 lg:w-1/4 bg-white rounded-xl shadow-xl p-8 text-center transform hover:-translate-y-2 transition duration-300 border-b-4 border-emerald-600 relative z-10"
                        data-aos="fade-up">
                        <div
                            class="w-32 h-32 mx-auto bg-gray-200 rounded-full overflow-hidden mb-4 border-4 border-emerald-100 shadow-md">
                            <?php
                            $foto = $ketua['foto'] ? $ketua['foto'] : 'https://ui-avatars.com/api/?name=' . urlencode($ketua['nama']) . '&background=059669&color=fff';

                            ?>
                            <img src="<?= $foto ?>" alt="<?= $ketua['nama'] ?>" class="w-full h-full object-cover">
                        </div>
                        <h4 class="font-bold text-gray-900 text-2xl mb-1"><?= $ketua['nama'] ?></h4>
                        <p class="text-emerald-600 font-bold uppercase tracking-wider"><?= $ketua['jabatan'] ?></p>
                    </div>
                </div>

                <!-- Members Row -->
                <div class="flex flex-wrap justify-center gap-8 w-full relative z-10 pt-4">
                    <!-- Horizontal Connector (Optional) -->
                    <div class="absolute top-[-24px] left-1/4 right-1/4 h-1 bg-gray-300 hidden md:block z-0"></div>
                    <div class="absolute top-[-24px] left-1/2 w-1 h-6 bg-gray-300 -translate-x-1/2 hidden md:block z-0">
                    </div>

                    <?php foreach ($data_pengurus as $index => $p): ?>
                        <div class="w-full sm:w-1/2 lg:w-1/5 bg-white rounded-xl shadow-lg p-6 text-center transform hover:-translate-y-2 transition duration-300 border-b-4 border-emerald-600 h-full relative"
                            data-aos="fade-up" data-aos-delay="<?= ($index + 1) * 100 ?>">
                            <!-- Vertical Connector to horizontal line -->
                            <div class="absolute top-[-28px] left-1/2 w-1 h-8 bg-gray-300 -translate-x-1/2 hidden md:block z-0">
                            </div>

                            <div
                                class="w-24 h-24 mx-auto bg-gray-200 rounded-full overflow-hidden mb-4 border-4 border-emerald-100">
                                <?php
                                $foto = $p['foto'] ? $p['foto'] : 'https://ui-avatars.com/api/?name=' . urlencode($p['nama']) . '&background=059669&color=fff';

                                ?>
                                <img src="<?= $foto ?>" alt="<?= $p['nama'] ?>" class="w-full h-full object-cover">
                            </div>
                            <h4 class="font-bold text-gray-900 text-lg"><?= $p['nama'] ?></h4>
                            <p class="text-emerald-600 text-sm font-medium"><?= $p['jabatan'] ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>

            <?php else: ?>
                <div class="text-center py-8 text-gray-500">
                    Belum ada data pengurus.
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>