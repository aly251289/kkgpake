<?php
include 'includes/init_sekolah.php';
include 'includes/header_sekolah.php';
?>

<div class="container mx-auto px-4 py-12 max-w-6xl">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

        <!-- Sidebar (Left - Photo) -->
        <div class="lg:col-span-4 lg:sticky lg:top-24">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 text-center">
                <div class="relative w-48 h-64 mx-auto mb-6 transform rotate-3 hover:rotate-0 transition duration-500">
                    <div
                        class="absolute inset-0 border-2 border-emerald-500 rounded-xl transform translate-x-3 translate-y-3">
                    </div>
                    <?php if (!empty($school['foto_kepala_sekolah'])): ?>
                        <img src="<?= $base_path ?>/<?= $school['foto_kepala_sekolah'] ?>"
                            alt="<?= $school['nama_kepala_sekolah'] ?>"
                            class="absolute inset-0 w-full h-full object-cover rounded-xl shadow-lg bg-gray-100">
                    <?php else: ?>
                        <div
                            class="absolute inset-0 w-full h-full bg-gray-100 rounded-xl flex items-center justify-center border border-gray-200">
                            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>

                <h2 class="text-xl font-bold text-gray-800 mb-1">
                    <?= $school['nama_kepala_sekolah'] ? $school['nama_kepala_sekolah'] : 'Kepala Madrasah' ?>
                </h2>
                <p class="text-emerald-600 font-medium text-sm">Kepala Madrasah</p>

                <div class="mt-6 pt-6 border-t border-gray-100 grid grid-cols-2 gap-4 text-center">
                    <div>
                        <span class="block text-2xl font-bold text-gray-800">10+</span>
                        <span class="text-xs text-gray-500">Tahun Mengabdi</span>
                    </div>
                    <div>
                        <span class="block text-2xl font-bold text-gray-800">S2</span>
                        <span class="text-xs text-gray-500">Pendidikan</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content (Right) -->
        <div class="lg:col-span-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 lg:p-12 relative overflow-hidden">
                <!-- Decorative Quote Icon -->
                <svg class="absolute top-0 right-0 w-64 h-64 text-emerald-50 opacity-50 transform translate-x-12 -translate-y-12"
                    fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H15.017C14.4647 8 14.017 8.44772 14.017 9V11C14.017 11.5523 13.5693 12 13.017 12H12.017V5H22.017V15C22.017 18.3137 19.3307 21 16.017 21H14.017ZM5.0166 21L5.0166 18C5.0166 16.8954 5.91203 16 7.0166 16H10.0166C10.5689 16 11.0166 15.5523 11.0166 15V9C11.0166 8.44772 10.5689 8 10.0166 8H6.0166C5.46432 8 5.0166 8.44772 5.0166 9V11C5.0166 11.5523 4.56889 12 4.0166 12H3.0166V5H13.0166V15C13.0166 18.3137 10.3303 21 7.0166 21H5.0166Z">
                    </path>
                </svg>

                <h1 class="text-3xl lg:text-4xl font-bold text-gray-800 mb-6 relative z-10">Sambutan Kepala Madrasah
                </h1>
                <div class="prose prose-lg text-gray-600 relative z-10 leading-relaxed text-justify">
                    <?php if (!empty($school['sambutan_kepala_sekolah'])): ?>
                        <?= nl2br($school['sambutan_kepala_sekolah']) ?>
                    <?php else: ?>
                        <p class="italic text-gray-400">Belum ada sambutan yang ditambahkan.</p>
                    <?php endif; ?>
                </div>

                <div class="mt-12 pt-8 border-t border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="font-bold text-gray-800 text-lg">
                            <?= $school['nama_kepala_sekolah'] ?>
                        </p>
                        <p class="text-emerald-600">Kepala Madrasah</p>
                    </div>
                    <?php if ($school['logo_sekolah']): ?>
                        <img src="<?= $base_path ?>/<?= $school['logo_sekolah'] ?>"
                            class="h-16 w-auto opacity-50 grayscale hover:grayscale-0 transition duration-300">
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer_sekolah.php'; ?>