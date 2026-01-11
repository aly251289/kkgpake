<?php
session_start();

// Check if registration was successful
if (!isset($_SESSION['register_success'])) {
    header('Location: register.php');
    exit;
}

// Clear success flag
unset($_SESSION['register_success']);

require_once 'config/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Berhasil - KKG Paket</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
</head>

<body
    class="bg-gradient-to-br from-emerald-50 via-white to-teal-50 min-h-screen font-sans flex items-center justify-center p-4">

    <div class="max-w-lg w-full">
        <div class="bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
            <!-- Success Icon -->
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 p-12 text-center">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg">
                    <svg class="w-12 h-12 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Pendaftaran Berhasil!</h1>
                <p class="text-emerald-100">Terima kasih telah mendaftar sebagai kontributor</p>
            </div>

            <!-- Content -->
            <div class="p-8">
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                    <div class="flex">
                        <svg class="w-6 h-6 text-blue-400 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <div>
                            <h3 class="text-blue-800 font-bold mb-2">Menunggu Persetujuan Admin</h3>
                            <p class="text-sm text-blue-700">
                                Akun Anda sedang dalam status pending. Admin akan meninjau dan menyetujui pendaftaran
                                Anda dalam waktu 1x24 jam. Anda akan menerima notifikasi setelah akun disetujui.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4 text-gray-600">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-emerald-600 mr-3 flex-shrink-0" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <p>Data pendaftaran Anda sudah berhasil disimpan</p>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-emerald-600 mr-3 flex-shrink-0" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <p>Setelah disetujui, Anda dapat login dan mengelola website madrasah</p>
                    </div>
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-emerald-600 mr-3 flex-shrink-0" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <p>Jika ada pertanyaan, hubungi admin KKG Paket</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-8 flex flex-col sm:flex-row gap-3">
                    <a href="<?= $base_path ?>/index"
                        class="flex-1 text-center px-6 py-3 rounded-lg border-2 border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                        Kembali ke Beranda
                    </a>
                    <a href="<?= $base_path ?>/login"
                        class="flex-1 text-center px-6 py-3 rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold hover:from-emerald-700 hover:to-teal-700 shadow-lg hover:shadow-emerald-500/30 transition transform hover:-translate-y-1">
                        Coba Login
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>