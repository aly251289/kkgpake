<?php
require_once 'config/koneksi.php';

// Data madrasah dari Kemdikbud (hardcoded)
$madrasah_list = [
    '60713584' => 'MI AR ROISIYYAH',
    '60713591' => 'MI ASSYAFI\'IYAH PENER',
    '60713588' => 'MI ISLAMIYAH BALAMOA',
    '60713582' => 'MI MA\'ARIF NU 01 DERMASUCI',
    '60713592' => 'MI MA\'ARIF NU 1 BOGARES KIDUL',
    '60713585' => 'MI MA\'ARIF NU PENUSUPAN',
    '60713587' => 'MI NURUL IMAN KENDALSERUT',
    '60713586' => 'MI NURUL UMAT',
    '60713593' => 'MI RADEN FATAH 01 GROBOGWETAN',
    '60713589' => 'MI RADEN FATAH 02 GROBOGWETAN',
    '60713590' => 'MI RAUDLOTUT THOLIBIN',
    '60713583' => 'MI TARBIYATUL ATHFAL',
    '60713594' => 'MIS NURUL FALAH',
    '69928131' => 'MIS SALAFI KABUPATEN TEGAL',
    '60713540' => 'MI IKHSANIYAH KEBANDINGAN', // Added
    '60713539' => 'MI MIFTAHUL ULUM',        // Added
];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Kontributor - KKG Paket</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gradient-to-br from-emerald-50 via-white to-teal-50 min-h-screen font-sans">

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-emerald-600 to-teal-600 py-12 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Pendaftaran Kontributor</h1>
            <p class="text-emerald-100 text-lg">Bergabunglah dengan KKG Paket untuk mengelola website madrasah Anda</p>
        </div>
    </div>

    <!-- Registration Form -->
    <div class="max-w-2xl mx-auto px-4 py-12">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-8 md:p-10">
                <form id="registerForm" method="POST" action="process/register_save.php">

                    <!-- Informasi Akun -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">Informasi Akun
                        </h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Nama Lengkap <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" required
                                    placeholder="Nama lengkap Anda"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Nomor WhatsApp <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="no_hp" id="no_hp" required
                                    placeholder="Contoh: 088xxxx (Wajib WA Aktif)"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                                <p class="text-xs text-gray-500 mt-1">Nomor ini akan menerima notifikasi persetujuan
                                    akun.</p>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Username <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="username" id="username" required
                                    placeholder="Username untuk login"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                                <p class="text-xs text-gray-500 mt-1">Hanya huruf, angka, dan underscore. Min. 4
                                    karakter</p>
                            </div>


                        </div>
                    </div>

                    <!-- Informasi Madrasah -->
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-gray-800 mb-6 pb-3 border-b border-gray-200">Informasi
                            Madrasah</h3>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 font-bold mb-2">NPSN Madrasah <span
                                        class="text-red-500">*</span></label>
                                <select name="npsn" id="npsn" required
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                                    <option value="">-- Pilih Madrasah --</option>
                                    <?php foreach ($madrasah_list as $npsn => $nama): ?>
                                        <option value="<?= $npsn ?>" data-nama="<?= htmlspecialchars($nama) ?>">
                                            <?= $npsn ?> - <?= $nama ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Pilih madrasah Anda berdasarkan NPSN</p>
                            </div>

                            <div>
                                <label class="block text-gray-700 font-bold mb-2">Nama Madrasah <span
                                        class="text-red-500">*</span></label>
                                <input type="text" name="nama_sekolah" id="nama_sekolah" required readonly
                                    placeholder="Akan terisi otomatis saat memilih NPSN"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-50 focus:outline-none transition">
                                <input type="hidden" name="npsn_value" id="npsn_value">
                            </div>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm text-blue-700">
                                Setelah mendaftar, akun Anda akan menunggu persetujuan admin. <strong>Password login
                                    akan dikirim via WhatsApp</strong> setelah akun disetujui.
                            </p>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="<?= $base_path ?>/login"
                            class="flex-1 text-center px-6 py-3 rounded-lg border-2 border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                            Sudah Punya Akun? Login
                        </a>
                        <button type="submit"
                            class="flex-1 px-6 py-3 rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold hover:from-emerald-700 hover:to-teal-700 shadow-lg hover:shadow-emerald-500/30 transition transform hover:-translate-y-1">
                            Daftar Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Back to Home -->
        <div class="text-center mt-8">
            <a href="<?= $base_path ?>/index" class="text-gray-600 hover:text-emerald-600 font-medium">
                ← Kembali ke Beranda
            </a>
        </div>
    </div>

    <script>
        // NPSN Selection Handler
        const npsnSelect = document.getElementById('npsn');
        const namaSekolahInput = document.getElementById('nama_sekolah');
        const npsnValueInput = document.getElementById('npsn_value');

        npsnSelect.addEventListener('change', function () {
            const selectedOption = this.options[this.selectedIndex];
            if (this.value) {
                const namaMadrasah = selectedOption.getAttribute('data-nama');
                namaSekolahInput.value = namaMadrasah;
                npsnValueInput.value = this.value;
            } else {
                namaSekolahInput.value = '';
                npsnValueInput.value = '';
            }
        });

        // Form Validation
        document.getElementById('registerForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const username = document.getElementById('username').value;
            const no_hp = document.getElementById('no_hp').value;
            const npsn = document.getElementById('npsn').value;

            // Validate No HP
            if (!/^\d+$/.test(no_hp) || no_hp.length < 10) {
                Swal.fire({
                    icon: 'error',
                    title: 'Nomor WA Tidak Valid',
                    text: 'Pastikan nomor WA hanya angka dan minimal 10 digit'
                });
                return;
            }

            // Validate username
            if (username.length < 4) {
                Swal.fire({
                    icon: 'error',
                    title: 'Username Terlalu Pendek',
                    text: 'Username minimal 4 karakter'
                });
                return;
            }



            if (!npsn) {
                Swal.fire({
                    icon: 'error',
                    title: 'NPSN Belum Dipilih',
                    text: 'Silakan pilih madrasah dari dropdown'
                });
                return;
            }

            // Submit form
            this.submit();
        });
    </script>
</body>

</html>