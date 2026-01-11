<?php
include 'includes/init_sekolah.php';
include 'includes/header_sekolah.php';
?>

<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
                <div class="border-b border-gray-100 pb-4 mb-6">
                    <h1 class="text-3xl font-bold text-gray-800">Hubungi Kami</h1>
                    <p class="text-gray-500 mt-2">Informasi kontak dan lokasi
                        <?= $school['nama_sekolah'] ?>
                    </p>
                </div>

                <div class="space-y-6">
                    <!-- Address -->
                    <div class="flex items-start p-4 bg-emerald-50 rounded-lg border border-emerald-100">
                        <div class="bg-emerald-100 p-3 rounded-full mr-4 flex-shrink-0">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 mb-1">Alamat Sekolah</h4>
                            <p class="text-gray-600 leading-relaxed">
                                <?= $school['alamat_sekolah'] ? $school['alamat_sekolah'] : 'Alamat belum tersedia.' ?>
                            </p>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div class="flex items-start p-4 bg-emerald-50 rounded-lg border border-emerald-100">
                        <div class="bg-emerald-100 p-3 rounded-full mr-4 flex-shrink-0">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800 mb-1">Telepon / WhatsApp</h4>
                            <p class="text-gray-600 leading-relaxed mb-2">
                                <?= $school['telepon_sekolah'] ? $school['telepon_sekolah'] : 'Kontak belum tersedia.' ?>
                            </p>
                            <?php if ($school['telepon_sekolah']): ?>
                                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $school['telepon_sekolah']) ?>"
                                    target="_blank" class="text-sm font-bold text-emerald-600 hover:underline">
                                    Hubungi via WhatsApp →
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Map Placeholder -->
                <div class="mt-8">
                    <h4 class="font-bold text-gray-800 mb-4">Lokasi di Peta</h4>
                    <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                        [Google Maps Embed akan muncul di sini jika ada data koordinat]
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-8">
            <?php include 'includes/sidebar_sekolah.php'; ?>
        </div>
    </div>
</div>

<?php include 'includes/footer_sekolah.php'; ?>