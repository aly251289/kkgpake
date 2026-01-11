<?php include 'includes/header.php'; ?>

<!-- Page Header -->
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
            Hubungi Kami
        </h1>
        <p class="text-lg text-emerald-100 max-w-2xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="100">
            Punya pertanyaan atau ingin berkolaborasi? Jangan ragu untuk menghubungi kami.
        </p>
    </div>
</section>

<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 bg-white rounded-3xl shadow-xl overflow-hidden">

            <!-- Contact Form -->
            <div class="p-8 md:p-12" data-aos="fade-right">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Kirim Pesan</h3>

                <?php
                require_once 'config/koneksi.php';

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $nama = mysqli_real_escape_string($koneksi, $_POST['name']);
                    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
                    $subjek = mysqli_real_escape_string($koneksi, $_POST['subject']);
                    $isi_pesan = mysqli_real_escape_string($koneksi, $_POST['message']);

                    if (!empty($nama) && !empty($email) && !empty($isi_pesan)) {
                        $sql = "INSERT INTO pesan (nama, email, subjek, isi_pesan) VALUES ('$nama', '$email', '$subjek', '$isi_pesan')";
                        if (mysqli_query($koneksi, $sql)) {
                            // Send WhatsApp Notification
                            require_once 'includes/whatsapp_helper.php';

                            // Get Admin Phone
                            $q_wa = mysqli_query($koneksi, "SELECT admin_wa FROM identitas LIMIT 1");
                            if ($d_wa = mysqli_fetch_assoc($q_wa)) {
                                $target_wa = $d_wa['admin_wa'];
                                if (!empty($target_wa)) {
                                    $wa_msg = "*Pesan Baru di Website KKG*\n\n";
                                    $wa_msg .= "Dari: $nama\n";
                                    $wa_msg .= "Email: $email\n";
                                    $wa_msg .= "Subjek: $subjek\n";
                                    $wa_msg .= "Pesan: $isi_pesan\n\n";
                                    $wa_msg .= "_Balas melalui Admin Panel._";

                                    WhatsAppHelper::sendText($target_wa, $wa_msg);
                                }
                            }

                            echo '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                                    <strong class="font-bold">Berhasil!</strong>
                                    <span class="block sm:inline">Pesan Anda telah terkirim. Terima kasih!</span>
                                  </div>';
                        } else {
                            echo '<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                                    <strong class="font-bold">Gagal!</strong>
                                    <span class="block sm:inline">Terjadi kesalahan, silakan coba lagi.</span>
                                  </div>';
                        }
                    } else {
                        echo '<div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                                    <strong class="font-bold">Perhatian!</strong>
                                    <span class="block sm:inline">Mohon lengkapi semua bidang.</span>
                                  </div>';
                    }
                }
                ?>

                <form action="" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="relative">
                            <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" id="name" name="name" required
                                class="w-full px-5 py-3 rounded-xl border-2 border-gray-100 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 transition-all outline-none bg-gray-50 focus:bg-white"
                                placeholder="Masukkan nama anda">
                        </div>
                        <div class="relative">
                            <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                            <input type="email" id="email" name="email" required
                                class="w-full px-5 py-3 rounded-xl border-2 border-gray-100 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 transition-all outline-none bg-gray-50 focus:bg-white"
                                placeholder="email@contoh.com">
                        </div>
                    </div>

                    <div class="relative">
                        <label for="subject" class="block text-sm font-bold text-gray-700 mb-2">Subjek Pesan</label>
                        <div class="relative">
                            <select id="subject" name="subject" required
                                class="w-full px-5 py-3 rounded-xl border-2 border-gray-100 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 transition-all outline-none bg-gray-50 focus:bg-white appearance-none cursor-pointer">
                                <option value="" disabled selected>Pilih Kategori Pesan</option>
                                <option value="Umum">Pertanyaan Umum</option>
                                <option value="Kerjasama">Kerjasama & Sponsorship</option>
                                <option value="Teknis">Kendala Website/Teknis</option>
                            </select>
                            <div
                                class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <label for="message" class="block text-sm font-bold text-gray-700 mb-2">Isi Pesan</label>
                        <textarea id="message" name="message" rows="5" required
                            class="w-full px-5 py-3 rounded-xl border-2 border-gray-100 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/20 transition-all outline-none bg-gray-50 focus:bg-white resize-none"
                            placeholder="Tuliskan pesan anda secara detail..."></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold py-4 rounded-xl hover:shadow-[0_10px_40px_rgb(5,150,105,0.4)] transition-all transform hover:-translate-y-1 relative overflow-hidden group">
                        <span class="relative z-10 flex items-center justify-center gap-2">
                            Kirim Pesan Sekarang
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </span>
                    </button>
                </form>
            </div>

            <!-- Info & Map -->
            <div class="bg-emerald-800 p-8 md:p-12 text-white flex flex-col justify-between" data-aos="fade-left">
                <div>
                    <h3 class="text-2xl font-bold mb-6">Informasi Kontak</h3>
                    <p class="text-emerald-100 mb-8 leading-relaxed">
                        Kami sangat terbuka untuk segala bentuk komunikasi. Silakan kunjungi sekretariat kami atau
                        hubungi melalui kanal di bawah ini.
                    </p>

                    <ul class="space-y-6">
                        <li class="flex items-start gap-4">
                            <div class="bg-emerald-700 p-3 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">Alamat</h4>
                                <p class="text-emerald-100 text-sm mt-1">
                                    <?= $d_identitas['alamat'] ?? 'Alamat belum diatur' ?>
                                </p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="bg-emerald-700 p-3 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">Email</h4>
                                <p class="text-emerald-100 text-sm mt-1">
                                    <?= $d_identitas['email'] ?? 'info@contoh.com' ?>
                                </p>
                            </div>
                        </li>
                        <li class="flex items-start gap-4">
                            <div class="bg-emerald-700 p-3 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg">Telepon</h4>
                                <p class="text-emerald-100 text-sm mt-1"><?= $d_identitas['no_telp'] ?? '-' ?></p>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Google Maps Embed -->
                <div class="mt-8 rounded-xl overflow-hidden shadow-lg h-48 w-full bg-gray-200">
                    <?php if (!empty($d_identitas['maps'])): ?>
                        <?= $d_identitas['maps'] ?>
                    <?php else: ?>
                        <div class="flex items-center justify-center h-full text-gray-500">
                            Peta belum tersedia.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>