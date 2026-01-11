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
                    <h1 class="text-3xl font-bold text-gray-800">Profil Sekolah</h1>
                    <p class="text-gray-500 mt-2">Mengenal lebih dekat
                        <?= $school['nama_sekolah'] ?>
                    </p>
                </div>

                <?php if ($school['logo_sekolah']): ?>
                    <img src="<?= $base_path ?>/<?= $school['logo_sekolah'] ?>"
                        class="w-32 h-auto mb-6 float-left mr-6 rounded-lg border p-1" alt="Logo">
                <?php endif; ?>

                <div class="prose max-w-none text-gray-600 leading-relaxed">
                    <p class="text-lg font-medium text-emerald-800 mb-4">
                        <?= $school['nama_sekolah'] ?>
                    </p>
                    <div class="whitespace-pre-line">
                        <?= $school['deskripsi_sekolah'] ? $school['deskripsi_sekolah'] : 'Belum ada deskripsi profil untuk sekolah ini.' ?>
                    </div>
                </div>

                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-6 rounded-lg text-sm">
                    <div>
                        <span class="block text-gray-500 font-bold uppercase text-xs tracking-wider mb-1">Alamat</span>
                        <p class="text-gray-800 font-medium">
                            <?= $school['alamat_sekolah'] ? $school['alamat_sekolah'] : '-' ?>
                        </p>
                    </div>
                    <div>
                        <span class="block text-gray-500 font-bold uppercase text-xs tracking-wider mb-1">Kontak /
                            Telepon</span>
                        <p class="text-gray-800 font-medium">
                            <?= $school['telepon_sekolah'] ? $school['telepon_sekolah'] : '-' ?>
                        </p>
                    </div>
                    <div>
                        <span class="block text-gray-500 font-bold uppercase text-xs tracking-wider mb-1">Operator
                            Pengelola</span>
                        <p class="text-gray-800 font-medium">
                            <?= $school['nama_lengkap'] ?>
                        </p>
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