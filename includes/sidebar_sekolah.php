<!-- Custom Sidebar for School -->
<div class="space-y-8">

    <!-- Sambutan Kepala Madrasah Widget -->
    <?php if (!empty($school['sambutan_kepala_sekolah'])): ?>
        <div class="bg-white rounded-xl shadow-md p-6 border-t-4 border-emerald-500">
            <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                    </path>
                </svg>
                Sambutan Kepala
            </h3>
            <div class="flex flex-col items-center text-center">
                <?php if (!empty($school['foto_kepala_sekolah'])): ?>
                    <div class="relative w-24 h-32 mb-3">
                        <div
                            class="absolute inset-0 border border-emerald-200 rounded-lg transform translate-x-1 translate-y-1">
                        </div>
                        <img src="<?= $base_path ?>/<?= $school['foto_kepala_sekolah'] ?>" alt="Kepala Madrasah"
                            class="absolute inset-0 w-full h-full object-cover rounded-lg shadow-sm">
                    </div>
                <?php endif; ?>
                <h4 class="font-bold text-gray-800 text-sm mb-1"><?= $school['nama_kepala_sekolah'] ?></h4>
                <p class="text-xs text-emerald-600 font-medium mb-3">Kepala Madrasah</p>

                <div class="text-xs text-gray-500 leading-relaxed mb-3 line-clamp-3 text-justify">
                    <?= strip_tags($school['sambutan_kepala_sekolah']) ?>
                </div>

                <a href="/mi/<?= $slug ?>/sambutan"
                    class="text-xs font-bold text-emerald-600 hover:text-emerald-800 flex items-center">
                    Baca Selengkapnya
                    <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3">
                        </path>
                    </svg>
                </a>
            </div>
        </div>
    <?php endif; ?>

    <!-- Sambutan / Intro Widget -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-bold text-gray-800 mb-4 flex items-center border-l-4 border-emerald-500 pl-3">
            Identitas Sekolah
        </h3>
        <div class="space-y-4">
            <?php if (!empty($school['logo_sekolah'])): ?>
                <img src="<?= $base_path ?>/<?= $school['logo_sekolah'] ?>" alt="Logo" class="w-24 h-auto mx-auto mb-2">
            <?php endif; ?>

            <div class="text-center">
                <h4 class="font-bold text-lg text-emerald-800">
                    <?= $school['nama_sekolah'] ?>
                </h4>
                <p class="text-xs text-gray-500 mt-1 mb-3">Operator:
                    <?= $school['nama_lengkap'] ?>
                </p>
            </div>

            <?php if ($school['alamat_sekolah']): ?>
                <div class="flex items-start text-sm text-gray-600 bg-gray-50 p-3 rounded-lg">
                    <svg class="w-5 h-5 mr-2 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>
                        <?= $school['alamat_sekolah'] ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Contact Widget -->
    <div
        class="bg-gradient-to-br from-emerald-600 to-teal-700 rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
        <!-- Decoration -->
        <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-white opacity-10 rounded-full blur-xl"></div>

        <h3 class="font-bold text-lg mb-4 relative z-10">Hubungi Kami</h3>
        <p class="text-emerald-100 text-sm mb-6 relative z-10">Ada pertanyaan? Chat admin kami sekarang.</p>
        <?php if ($school['telepon_sekolah']): ?>
            <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $school['telepon_sekolah']) ?>" target="_blank"
                class="block w-full bg-white text-emerald-700 font-bold py-3 px-4 rounded-lg text-center hover:bg-emerald-50 transition shadow-sm mb-3 relative z-10 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.506-.669-.514-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                </svg>
                Chat WhatsApp
            </a>
        <?php else: ?>
            <button disabled
                class="block w-full bg-white/50 text-white font-bold py-3 px-4 rounded-lg text-center cursor-not-allowed">
                Kontak Belum Tersedia
            </button>
        <?php endif; ?>
    </div>
</div>