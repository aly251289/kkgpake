<?php 
require_once 'config/koneksi.php';
include 'includes/header.php'; 

// Helper function for Indonesian Date
if (!function_exists('tgl_indo')) {
    function tgl_indo($tanggal){
        $bulan = array (
            1 =>   'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        );
        $pecahkan = explode('-', $tanggal);
        return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
    }
}

if (!function_exists('hari_indo')) {
    function hari_indo($tanggal){
        $hari = array ( 1 => 'Senin',
            'Selasa',
            'Rabu',
            'Kamis',
            'Jumat',
            'Sabtu',
            'Minggu'
        );
        $num = date('N', strtotime($tanggal));
        return $hari[$num];
    }
}
?>

<!-- Hero Section -->
<section class="relative py-12 md:py-20 flex items-center justify-center overflow-hidden pt-20">
    <!-- Blob Backgrounds -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-emerald-300/30 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-sky-300/30 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-20 w-[600px] h-[600px] bg-emerald-100/40 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-4000"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center gap-12 text-center md:text-left">
        <!-- Text Content -->
        <div class="w-full md:w-1/2" data-aos="fade-right">
            <span class="inline-block py-1 px-3 rounded-full bg-emerald-100 text-emerald-800 text-sm font-bold mb-4 border border-emerald-200 shadow-sm">
                ✨ Selamat Datang di Website Resmi KKGMI-PAKET
            </span>
            <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 tracking-tight leading-tight mb-4">
                <?php
                if (!empty($d_identitas['slogan_header'])) {
                    $slogan = $d_identitas['slogan_header'];
                    // If doesn't contain HTML tags, apply auto-styling
                    if (strpos($slogan, '<') === false) {
                        // If long, split by spaces
                        $words = explode(' ', $slogan);
                        if (count($words) >= 4) {
                            // Split in half roughly
                            $mid = ceil(count($words) / 2);
                            $first_half = array_slice($words, 0, $mid);
                            $second_half = array_slice($words, $mid);
                            echo implode(' ', $first_half) . ' <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">' . implode(' ', $second_half) . '</span>';
                        } else {
                             // Short slogan, just gradient all of it? Or Just last word?
                             // Let's just output it with gradient if it's short, or keep it simple.
                             // Actually, let's just make the last 2 words gradient if > 2 words
                             if(count($words) > 2) {
                                $last_two = array_slice($words, -2);
                                $start = array_slice($words, 0, -2);
                                echo implode(' ', $start) . ' <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">' . implode(' ', $last_two) . '</span>';
                             } else {
                                echo $slogan;
                             }
                        }
                    } else {
                        // Has HTML (e.g. from default or user knew HTML)
                        echo $slogan;
                    }
                } else {
                    // Fallback Default
                    echo 'Wadah Profesionalisme <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Guru Madrasah</span>';
                }
                ?>
            </h1>
            <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                <?= $d_identitas['deskripsi_header'] ?? 'Membangun sinergi, meningkatkan kompetensi, dan mencetak generasi rabbani yang berprestasi melalui kolaborasi aktif Kelompok Kerja Guru.' ?>
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                <a href="#kegiatan" class="px-8 py-3.5 rounded-full bg-emerald-600 text-white font-bold text-lg shadow-lg hover:bg-emerald-700 hover:shadow-emerald-500/30 transform hover:-translate-y-1 transition-all duration-300 ring-4 ring-emerald-600/20">
                    Lihat Kegiatan
                </a>
                <a href="profil.php" class="px-8 py-3.5 rounded-full bg-white text-emerald-700 font-bold text-lg shadow-md border border-gray-100 hover:bg-gray-50 hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                    Profil Kami
                </a>
            </div>
            
            <?php
            // Count Guru
            // Self-Healing: Check table guru
            $check_guru = mysqli_query($koneksi, "SHOW TABLES LIKE 'guru'");
            if(mysqli_num_rows($check_guru) == 0) {
                 mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS guru (id INT AUTO_INCREMENT PRIMARY KEY, nama VARCHAR(100) NOT NULL, nip VARCHAR(50), asal_madrasah VARCHAR(100) NOT NULL, status ENUM('Aktif', 'Non-Aktif') DEFAULT 'Aktif', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
                 // Dummy Data
                 mysqli_query($koneksi, "INSERT INTO guru (nama, asal_madrasah) VALUES ('Guru 1', 'MI A'), ('Guru 2', 'MI B')");
            }
            
            $q_count_guru = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM guru WHERE status='Aktif'");
            $total_guru = 50; // default fallback
            if ($q_count_guru) {
                $d_count = mysqli_fetch_assoc($q_count_guru);
                $total_guru = $d_count['total'];
                if ($total_guru < 50) $total_guru = 50; // Keep "50+" illusion if real data is low, or just show real data? User said "50+ anggota aktif datanya bisa diambil dari data guru". 
                // Let's show real number + if it's substantial, or just Real Number. 
                // User requirement: "pada bagian 50+ anggota aktif datanya bisa diambil dari data guru". 
                // It implies the number should be dynamic. 
                $total_guru = $d_count['total'];
            }
            ?>
            <div class="mt-8 flex flex-col sm:flex-row items-center justify-center md:justify-start gap-4">
                <!-- Anggota Aktif Card -->
                <div class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white shadow-xl flex items-center gap-4 animate-bounce-slow w-full sm:w-auto hover:scale-105 transition duration-300">
                    <div class="bg-emerald-100 p-3 rounded-full text-emerald-600">
                         <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <span class="block text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500"><?= $total_guru ?>+</span>
                        <span class="text-gray-600 font-bold text-sm uppercase tracking-wide">Anggota Aktif</span>
                    </div>
                </div>

                <!-- Akses RDM Card -->
                <a href="rdm.php" class="bg-white/80 backdrop-blur-md p-6 rounded-2xl border border-white shadow-xl flex items-center gap-4 animate-bounce-slow w-full sm:w-auto hover:scale-105 transition duration-300 group cursor-pointer ring-2 ring-transparent hover:ring-blue-400">
                    <div class="bg-blue-100 p-3 rounded-full text-blue-600 group-hover:bg-blue-600 group-hover:text-white transition">
                         <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <span class="block text-2xl font-extrabold text-gray-800 group-hover:text-blue-600 transition">Akses RDM</span>
                        <span class="text-gray-500 font-bold text-xs uppercase tracking-wide">Rapor Digital</span>
                    </div>
                    <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transform group-hover:translate-x-1 transition ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                </a>
            </div>
        </div>

        <!-- Hero Image/Illustration -->
        <div class="w-full md:w-1/2 relative" data-aos="fade-left" data-aos-delay="200">
            <div class="relative z-10 bg-white p-4 rounded-2xl shadow-2xl transform rotate-2 hover:rotate-0 transition duration-500 border border-gray-100">
                <?php 
                $hero_img = !empty($d_identitas['foto_hero']) ? 'assets/images/'.$d_identitas['foto_hero'] : 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
                ?>
                <img src="<?= $hero_img ?>" alt="Hero Image" class="rounded-xl w-full h-[300px] md:h-[350px] object-cover">
                <div class="absolute -bottom-6 -right-6 bg-white p-4 rounded-xl shadow-xl animate-bounce-slow">
                    <div class="flex items-center gap-3">
                        <div class="bg-yellow-100 p-2 rounded-full">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800">Unggul & Berprestasi</p>
                            <p class="text-xs text-gray-500">Komitmen Bersama</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Decorative Elements -->
            <div class="absolute top-10 -left-10 w-24 h-24 bg-emerald-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
            <div class="absolute bottom-10 -right-10 w-24 h-24 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
        </div>
    </div>
</section>

<!-- Jadwal Kegiatan Section (New Widget) -->
<section class="py-8 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-900 border-l-4 border-emerald-600 pl-4">Agenda / Jadwal Kegiatan</h2>
            <a href="agenda.php" class="text-emerald-600 font-semibold hover:text-emerald-800 transition text-sm">Lihat Semua &rarr;</a>
        </div>

        <div class="relative group" x-data="{
            scrollLeft() {
                this.$refs.scroller.scrollBy({ left: -320, behavior: 'smooth' });
            },
            scrollRight() {
                this.$refs.scroller.scrollBy({ left: 320, behavior: 'smooth' });
            }
        }">
            <!-- Arrows -->
            <button @click="scrollLeft()" class="absolute left-0 top-1/2 -translate-y-1/2 -ml-5 z-10 w-10 h-10 bg-white rounded-full shadow-lg text-emerald-600 hover:text-white hover:bg-emerald-600 transition flex items-center justify-center focus:outline-none opacity-0 group-hover:opacity-100 duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button @click="scrollRight()" class="absolute right-0 top-1/2 -translate-y-1/2 -mr-5 z-10 w-10 h-10 bg-white rounded-full shadow-lg text-emerald-600 hover:text-white hover:bg-emerald-600 transition flex items-center justify-center focus:outline-none opacity-0 group-hover:opacity-100 duration-300">
                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>

            <!-- Scrollable Container -->
            <div x-ref="scroller" class="flex overflow-x-auto snap-x snap-mandatory gap-5 pb-4 hide-scrollbar scroll-smooth p-1">
                <?php
                // Ambil 8 agenda untuk slider
                $query_jadwal = mysqli_query($koneksi, "SELECT * FROM agenda ORDER BY tanggal DESC LIMIT 8");
                if (mysqli_num_rows($query_jadwal) > 0):
                    while($jadwal = mysqli_fetch_assoc($query_jadwal)):
                        $tgl_agenda = strtotime($jadwal['tanggal']);
                        $is_passed = $tgl_agenda < strtotime(date('Y-m-d'));
                        
                        // Styles based on status
                        $cardClass = $is_passed ? 'bg-gray-50 border-gray-100 opacity-75 grayscale' : 'bg-white border-emerald-100 shadow-sm ring-1 ring-emerald-50';
                        $dateBoxClass = $is_passed ? 'bg-gray-100 text-gray-400' : 'bg-emerald-50 text-emerald-600';
                        $badgeClass = $is_passed ? 'bg-gray-200 text-gray-500' : 'bg-emerald-100 text-emerald-700';
                        $statusText = $is_passed ? 'Selesai' : 'Akan Datang';
                ?>
                <div class="min-w-[320px] md:min-w-[360px] snap-start <?= $cardClass ?> rounded-xl p-5 border hover:shadow-md transition flex items-start gap-4 relative overflow-hidden flex-shrink-0">
                    <?php if(!$is_passed): ?>
                        <div class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-emerald-400/20 to-transparent rounded-bl-full -mr-8 -mt-8 pointer-events-none"></div>
                    <?php endif; ?>
                    
                    <!-- Date Box -->
                    <div class="flex-shrink-0 w-14 h-14 rounded-lg flex flex-col items-center justify-center border border-gray-100 <?= $dateBoxClass ?>">
                        <span class="text-[10px] font-bold uppercase"><?= substr(hari_indo($jadwal['tanggal']), 0, 3) ?></span>
                        <span class="text-xl font-bold leading-none"><?= date('d', $tgl_agenda) ?></span>
                    </div>
                    
                    <div class="flex-grow min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider <?= $badgeClass ?>">
                                <?= $statusText ?>
                            </span>
                            <?php if(!$is_passed): ?>
                                <span class="animate-pulse w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            <?php endif; ?>
                        </div>
                        
                        <h3 class="font-bold text-gray-900 text-sm leading-tight mb-1.5 hover:text-emerald-600 transition truncate" title="<?= $jadwal['judul'] ?>">
                            <a href="agenda_detail.php?id=<?= $jadwal['id'] ?>" class="cursor-pointer"><?= $jadwal['judul'] ?></a>
                        </h3>
                        <div class="flex items-center text-xs text-gray-500 gap-3 mb-1">
                             <div class="flex items-center gap-1 truncate">
                                 <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                 <span class="truncate"><?= $jadwal['lokasi'] ?></span>
                             </div>
                        </div>
                        <?php if(!empty($jadwal['keterangan'])): ?>
                        <p class="text-xs text-gray-400 mt-1 line-clamp-1"><?= $jadwal['keterangan'] ?></p>
                        <?php endif; ?>
                        
                        <div class="mt-2">
                             <a href="agenda_detail.php?id=<?= $jadwal['id'] ?>" class="text-[10px] uppercase font-bold text-emerald-600 hover:text-emerald-800 flex items-center gap-1">
                                Lihat Detail & Lampiran <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                             </a>
                        </div>
                    </div>
                    
                    <?php if(!$is_passed): ?>
                    <div class="absolute bottom-2 right-2 text-emerald-100 opacity-50">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endwhile; 
                else: ?>
                <div class="w-full text-center py-6 text-gray-500 italic bg-gray-50 rounded-lg">
                    Belum ada agenda kegiatan mendatang.
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Scroll Down Indicator -->
<div class="hidden md:flex justify-center -mt-8 mb-12 relative z-20 pointer-events-none">
    <a href="#kegiatan" class="animate-bounce p-3 bg-white rounded-full shadow-lg text-emerald-600 hover:text-emerald-800 transition pointer-events-auto">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
    </a>
</div>

<!-- Kegiatan Terbaru -->
<section id="kegiatan" class="py-10 relative bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- ... existing content ... -->
        
        <!-- Keep existing content of Kegiatan section -->
        <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
            <h2 class="text-emerald-600 font-bold tracking-wide uppercase text-sm mb-2">Aktifitas Kami</h2>
            <h3 class="text-3xl font-bold text-gray-900 sm:text-4xl">Kegiatan & Workshop Terbaru</h3>
            <p class="mt-4 text-gray-600 text-lg">Dokumentasi kegiatan, workshop, dan pelatihan yang telah kami laksanakan bersama.</p>
        </div>

        <div class="relative" x-data="{
            scrollLeft() {
                this.$refs.scroller.scrollBy({ left: -300, behavior: 'smooth' });
            },
            scrollRight() {
                this.$refs.scroller.scrollBy({ left: 300, behavior: 'smooth' });
            }
        }">
            <!-- Arrows -->
            <button @click="scrollLeft()" class="absolute left-0 top-1/2 -translate-y-1/2 -ml-4 lg:-ml-12 z-10 w-12 h-12 bg-white rounded-full shadow-lg text-emerald-600 hover:text-white hover:bg-emerald-600 transition flex items-center justify-center focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button @click="scrollRight()" class="absolute right-0 top-1/2 -translate-y-1/2 -mr-4 lg:-mr-12 z-10 w-12 h-12 bg-white rounded-full shadow-lg text-emerald-600 hover:text-white hover:bg-emerald-600 transition flex items-center justify-center focus:outline-none">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>

            <!-- Scrollable Container -->
            <div x-ref="scroller" class="flex overflow-x-auto snap-x snap-mandatory gap-6 pb-8 hide-scrollbar scroll-smooth" data-aos="fade-up" data-aos-delay="200">
                <?php
                $query_kegiatan = mysqli_query($koneksi, "SELECT * FROM kegiatan WHERE created_by IN (SELECT id FROM users WHERE role='admin') ORDER BY tanggal DESC LIMIT 8");
                if (mysqli_num_rows($query_kegiatan) > 0):
                    while($row = mysqli_fetch_assoc($query_kegiatan)):
                        $img_src = $row['gambar'] ? $row['gambar'] : 'https://ui-avatars.com/api/?name='.urlencode($row['judul']).'&background=059669&color=fff&size=800';
                        if (strpos($img_src, 'http') === false && strpos($img_src, 'assets') !== false) {
                             // path is already correct or relative
                        }
                ?>
                <div class="min-w-[85%] md:min-w-[calc(50%-1.5rem)] lg:min-w-[calc(25%-1.125rem)] bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden group hover:shadow-2xl transition-all duration-300 flex flex-col snap-start h-auto">
                    <a href="kegiatan_detail.php?slug=<?= $row['slug'] ?>" class="block relative overflow-hidden h-48 flex-shrink-0 cursor-pointer">
                        <img src="<?= $img_src ?>" alt="<?= $row['judul'] ?>" loading="lazy" class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="absolute top-4 left-4 
                        <?php 
                            if($row['kategori'] == 'Workshop') echo 'bg-emerald-600';
                            elseif($row['kategori'] == 'Seminar') echo 'bg-purple-600';
                            elseif($row['kategori'] == 'Rapat') echo 'bg-sky-500';
                            else echo 'bg-gray-600';
                        ?> text-white text-[10px] font-bold px-2 py-1 rounded-full uppercase tracking-wide">
                            <?= $row['kategori'] ?>
                        </div>
                    </a>
                    <div class="p-5 flex flex-col flex-grow">
                         <div class="flex items-center text-xs text-gray-400 mb-2 space-x-3">
                            <span class="flex items-center gap-1">
                                 <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> 
                                 <?= date('d M Y', strtotime($row['tanggal'])) ?>
                            </span>
                            <span class="flex items-center gap-1">
                                 <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> 
                                 <?= substr($row['lokasi'], 0, 15) ?>
                            </span>
                        </div>
                        <h4 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-emerald-600 transition line-clamp-2">
                            <a href="kegiatan_detail.php?slug=<?= $row['slug'] ?>"><?= $row['judul'] ?></a>
                        </h4>
                        <p class="text-gray-600 text-sm line-clamp-2 mb-4 flex-grow">
                            <?= substr(strip_tags($row['deskripsi']), 0, 80) ?>...
                        </p>
                        <a href="kegiatan_detail.php?slug=<?= $row['slug'] ?>" class="inline-flex items-center text-emerald-600 text-sm font-semibold hover:text-emerald-800 transition mt-auto">
                            Baca Selengkapnya
                            <svg class="w-3 h-3 ml-1 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </div>
                <?php 
                    endwhile;
                else:
                ?>
                    <div class="w-full text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-300 flex-shrink-0">
                        <p class="text-gray-500">Belum ada data kegiatan terbaru.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="text-center mt-12">
            <a href="kegiatan.php" class="inline-block px-8 py-3 rounded-full border-2 border-emerald-600 text-emerald-600 font-bold hover:bg-emerald-600 hover:text-white transition-all duration-300">
                Lihat Semua Kegiatan
            </a>
        </div>
    </div>
</section>

<!-- Informasi & Berita Terbaru -->
<section id="informasi" class="py-10 relative bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16" data-aos="fade-up">
            <h2 class="text-blue-600 font-bold tracking-wide uppercase text-sm mb-2">Update Terbaru</h2>
            <h3 class="text-3xl font-bold text-gray-900 sm:text-4xl">Informasi & Berita</h3>
            <p class="mt-4 text-gray-600 text-lg">Berita terkini, pengumuman, dan informasi penting seputar pendidikan madrasah.</p>
        </div>

        <!-- Grid Layout for News (Compact 1 Main + 4 Side) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6" data-aos="fade-up" data-aos-delay="200">
            <?php
            // IMPROVED LOGIC:
            // 1. Get 1 Highlight Item (Prioritize Featured Berita, otherwise latest Berita/Kegiatan)
            
            $main_news = null;
            // Try fetch Featured Berita
            $q_featured = mysqli_query($koneksi, "SELECT id, judul, slug, kategori, tanggal, gambar, is_featured, 'berita' as type FROM berita WHERE is_featured=1 ORDER BY tanggal DESC LIMIT 1");
            if(mysqli_num_rows($q_featured) > 0){
                $main_news = mysqli_fetch_assoc($q_featured);
            }
            
            $exclude_id = isset($main_news['id']) && $main_news['type'] == 'berita' ? $main_news['id'] : 0;
            $exclude_type = isset($main_news['id']) ? $main_news['type'] : '';

            // If no featured, get the absolute latest item from combined stream
            if(!$main_news) {
                $query_latest = "
                    (SELECT id, judul, slug, kategori, tanggal, gambar, 'berita' as type FROM berita)
                    UNION ALL
                    (SELECT id, judul, slug, 'Kegiatan Madrasah' as kategori, tanggal, gambar, 'kegiatan' as type FROM kegiatan WHERE created_by NOT IN (SELECT id FROM users WHERE role='admin'))
                    ORDER BY tanggal DESC LIMIT 1
                ";
                $r_latest = mysqli_query($koneksi, $query_latest);
                if($r_latest && mysqli_num_rows($r_latest) > 0){
                    $main_news = mysqli_fetch_assoc($r_latest);
                    $exclude_id = $main_news['id'];
                    $exclude_type = $main_news['type'];
                }
            }

            $info_items = [];
            if($main_news) {
                $info_items[] = $main_news; // Index 0 is Main

                // 2. Get 4 Side Items (Combined Stream, Excluding Main)
                // Note: SQL UNION doesn't easily support excluding based on dynamic ID/Type logic efficiently in one go without complex WHERE.
                // Simpler: Fetch 5 items, filter out valid distincts in PHP or use slightly complex SQL.
                
                // Let's use SQL with WHERE logic in both parts
                
                $exclude_berita_sql = ($exclude_type == 'berita') ? "AND id != '$exclude_id'" : "";
                $exclude_kegiatan_sql = ($exclude_type == 'kegiatan') ? "AND id != '$exclude_id'" : "";
                
                $query_side = "
                    (SELECT id, judul, slug, kategori, tanggal, gambar, 'berita' as type 
                     FROM berita WHERE 1=1 $exclude_berita_sql)
                    UNION ALL
                    (SELECT id, judul, slug, 'Kegiatan Madrasah' as kategori, tanggal, gambar, 'kegiatan' as type 
                     FROM kegiatan WHERE created_by NOT IN (SELECT id FROM users WHERE role='admin') $exclude_kegiatan_sql)
                    ORDER BY tanggal DESC LIMIT 4
                ";
                
                $r_side = mysqli_query($koneksi, $query_side);
                while($row = mysqli_fetch_assoc($r_side)) {
                    $info_items[] = $row;
                }
            }
            // End Logic
            
            if (count($info_items) > 0):
                // === LEFT COLUMN: Main Item (First Item) ===
                $main_news = $info_items[0];
                $img_src_main = $main_news['gambar'] ? $main_news['gambar'] : 'https://ui-avatars.com/api/?name='.urlencode($main_news['judul']).'&background=2563eb&color=fff&size=800';
                
                // Determine Link
                $main_link = ($main_news['type'] == 'kegiatan') ? 'kegiatan_detail.php?slug='.$main_news['slug'] : 'post.php?slug='.$main_news['slug'];

                $badgeColorMain = 'bg-blue-600';
                if($main_news['kategori'] == 'Pengumuman') $badgeColorMain = 'bg-red-600';
                elseif($main_news['kategori'] == 'Berita Utama') $badgeColorMain = 'bg-purple-600';
                elseif($main_news['kategori'] == 'Artikel') $badgeColorMain = 'bg-indigo-600';
                elseif($main_news['kategori'] == 'Kegiatan Madrasah') $badgeColorMain = 'bg-emerald-600';
            ?>
            
            <!-- Main News Card (Left Column - Spans 5) -->
            <div class="lg:col-span-5 h-[300px] lg:h-[380px] group relative rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                <a href="<?= $main_link ?>" class="block w-full h-full">
                    <img src="<?= $img_src_main ?>" alt="<?= $main_news['judul'] ?>" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                    
                    <div class="absolute top-4 left-4 <?= $badgeColorMain ?> text-white text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wide backdrop-blur-sm z-10 shadow-sm">
                        <?= $main_news['kategori'] ?>
                    </div>
                    
                    <div class="absolute bottom-0 left-0 p-5 md:p-6 w-full">
                        <div class="flex items-center text-blue-100/90 text-[11px] font-medium mb-2 gap-2">
                             <span class="flex items-center gap-1">
                                 <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> 
                                 <?= date('d M Y', strtotime($main_news['tanggal'])) ?>
                             </span>
                        </div>
                        <h3 class="text-xl md:text-2xl font-bold text-white mb-1 leading-tight group-hover:text-blue-300 transition-colors line-clamp-3">
                            <?= $main_news['judul'] ?>
                        </h3>
                    </div>
                </a>
            </div>

            <!-- Side News (Right Column - Spans 7 - Grid 2x2 Compact) -->
            <div class="lg:col-span-7 grid grid-cols-1 sm:grid-cols-2 gap-4 h-fit">
                <?php 
                // Display up to 4 side news
                $side_news = array_slice($info_items, 1, 4);
                if(count($side_news) > 0):
                    foreach($side_news as $item):
                         $img_src_side = $item['gambar'] ? $item['gambar'] : 'https://ui-avatars.com/api/?name='.urlencode($item['judul']).'&background=2563eb&color=fff&size=600';
                         $item_link = ($item['type'] == 'kegiatan') ? 'kegiatan_detail.php?slug='.$item['slug'] : 'post.php?slug='.$item['slug'];
                         
                         $badgeColorSide = 'bg-blue-600';
                         if($item['kategori'] == 'Pengumuman') $badgeColorSide = 'bg-red-600';
                         elseif($item['kategori'] == 'Berita Utama') $badgeColorSide = 'bg-purple-600';
                         elseif($item['kategori'] == 'Artikel') $badgeColorSide = 'bg-indigo-600';
                         elseif($item['kategori'] == 'Kegiatan Madrasah') $badgeColorSide = 'bg-emerald-600';
                ?>
                <!-- Small Compact Card -->
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden group hover:shadow-md transition-all duration-300 flex flex-col h-[180px]">
                    <a href="<?= $item_link ?>" class="h-24 relative overflow-hidden flex-shrink-0 block">
                        <img src="<?= $img_src_side ?>" alt="<?= $item['judul'] ?>" class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500">
                        <div class="absolute inset-0 bg-black/5 group-hover:bg-black/0 transition"></div>
                        <div class="absolute top-2 left-2 <?= $badgeColorSide ?> text-white text-[9px] font-bold px-2 py-0.5 rounded-sm uppercase tracking-wider shadow-sm">
                            <?= $item['kategori'] ?>
                        </div>
                    </a>
                    <div class="p-3 flex flex-col flex-grow relative">
                         <div class="flex items-center text-[10px] text-gray-400 mb-1 gap-1.5 font-medium uppercase tracking-wide">
                             <span><?= date('d M Y', strtotime($item['tanggal'])) ?></span>
                        </div>
                        <h4 class="text-sm font-bold text-gray-800 leading-snug line-clamp-2 group-hover:text-blue-600 transition">
                            <a href="<?= $item_link ?>"><?= $item['judul'] ?></a>
                        </h4>
                    </div>
                </div>
                <?php 
                    endforeach;
                else: 
                ?>
                    <!-- Fallback if only 1 news total -->
                     <div class="col-span-1 sm:col-span-2 bg-blue-50/50 rounded-xl border border-blue-100 p-8 flex flex-col justify-center items-center text-center h-full">
                        <p class="text-blue-600 mb-1 text-sm font-medium">Berita lainnya menyusul.</p>
                        <a href="informasi.php" class="text-xs font-bold text-blue-700 hover:underline">Lihat Arsip</a>
                     </div>
                <?php endif; ?>
            </div>
            
            <?php else: ?>
                <div class="col-span-12 w-full text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                    <p class="text-gray-500 text-sm">Belum ada informasi terbaru.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center mt-12">
            <a href="informasi.php" class="inline-block px-8 py-3 rounded-full border-2 border-blue-600 text-blue-600 font-bold hover:bg-blue-600 hover:text-white transition-all duration-300">
                Lihat Semua Informasi
            </a>
        </div>
    </div>
</section>

<!-- Gallery Section -->
<section id="galeri" class="py-20 bg-white relative overflow-hidden">
     <!-- Background Blobs -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
    <div class="absolute bottom-0 left-0 w-64 h-64 bg-teal-100 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center max-w-3xl mx-auto mb-12" data-aos="fade-up">
            <h2 class="text-emerald-600 font-bold tracking-wide uppercase text-sm mb-2">Dokumentasi</h2>
            <h3 class="text-3xl font-bold text-gray-900 sm:text-4xl">Galeri Kegiatan</h3>
            <p class="mt-4 text-gray-600 text-lg">Momen-momen berharga dalam setiap langkah kemajuan bersama.</p>
        </div>
        
        <div class="relative" x-data="{
            scrollLeft() {
                this.$refs.scroller.scrollBy({ left: -300, behavior: 'smooth' });
            },
            scrollRight() {
                this.$refs.scroller.scrollBy({ left: 300, behavior: 'smooth' });
            }
        }">
            <!-- Arrows -->
            <button @click="scrollLeft()" class="absolute left-0 top-1/2 -translate-y-1/2 -ml-4 lg:-ml-12 z-10 w-12 h-12 bg-white rounded-full shadow-lg text-emerald-600 hover:text-white hover:bg-emerald-600 transition flex items-center justify-center focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </button>
            <button @click="scrollRight()" class="absolute right-0 top-1/2 -translate-y-1/2 -mr-4 lg:-mr-12 z-10 w-12 h-12 bg-white rounded-full shadow-lg text-emerald-600 hover:text-white hover:bg-emerald-600 transition flex items-center justify-center focus:outline-none">
                 <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </button>

            <!-- Scrollable Container -->
            <div x-ref="scroller" class="flex overflow-x-auto snap-x snap-mandatory gap-4 pb-4 hide-scrollbar scroll-smooth p-2" data-aos="fade-up" data-aos-delay="200">
                <?php
                $query_galeri = mysqli_query($koneksi, "SELECT * FROM galeri ORDER BY tanggal DESC LIMIT 8");
                
                // Self-Healing: If table doesn't exist, create it
                if (!$query_galeri) {
                    $create_table = "CREATE TABLE IF NOT EXISTS galeri (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        judul VARCHAR(255) NOT NULL,
                        tanggal DATE NOT NULL,
                        foto VARCHAR(255) NOT NULL,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    )";
                    mysqli_query($koneksi, $create_table);
                    
                    // Insert dummy data
                    mysqli_query($koneksi, "INSERT INTO galeri (judul, tanggal, foto) VALUES 
                        ('Lomba Adzan Siswa MI', '2025-12-10', 'https://images.unsplash.com/photo-1599547525367-17eb48679cb1?ixlib=rb-4.0.3'),
                        ('Pelatihan Guru Fiqih', '2025-11-20', 'https://images.unsplash.com/photo-1509062522246-3755977927d7?ixlib=rb-4.0.3'), 
                        ('Rapat Kerja Tahunan', '2025-10-15', 'https://images.unsplash.com/photo-1577962917302-cd874c4e31d2?ixlib=rb-4.0.3'), 
                        ('Kunjungan Kemenag', '2025-09-05', 'https://images.unsplash.com/photo-1558403194-611308249627?ixlib=rb-4.0.3')");
                    
                    // Retry query
                    $query_galeri = mysqli_query($koneksi, "SELECT * FROM galeri ORDER BY tanggal DESC LIMIT 8");
                }

                if($query_galeri && mysqli_num_rows($query_galeri) > 0):
                    while($item_galeri = mysqli_fetch_assoc($query_galeri)):
                        $foto_src = $item_galeri['foto'];
                        if (strpos($foto_src, 'http') === false) {
                            $foto_src = str_replace('../', '', $foto_src);
                        }
                ?>
                <a href="galeri_detail.php?id=<?= $item_galeri['id'] ?>" class="min-w-[85%] md:min-w-[calc(50%-1rem)] lg:min-w-[calc(25%-0.75rem)] aspect-square bg-gray-100 rounded-2xl overflow-hidden cursor-pointer block group relative snap-start shadow-md hover:shadow-xl transition-all duration-300">
                    <img src="<?= $foto_src ?>" alt="<?= $item_galeri['judul'] ?>" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                        <span class="text-emerald-300 text-xs font-bold uppercase tracking-wider mb-1"><?= date('d F Y', strtotime($item_galeri['tanggal'])) ?></span>
                        <h4 class="text-white font-bold text-lg leading-tight"><?= $item_galeri['judul'] ?></h4>
                        <span class="mt-2 text-xs text-white/80 flex items-center gap-1">
                            Lihat Album <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </span>
                    </div>
                </a>
                <?php 
                    endwhile;
                else:
                ?>
                <div class="w-full text-center py-10 flex-shrink-0">
                    <p class="text-gray-400 italic">Belum ada foto di galeri. Silakan tambahkan dari Admin Panel.</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- Call to Action: Daftar Kontributor -->
<section class="py-20 relative overflow-hidden bg-gradient-to-br from-purple-700 via-indigo-700 to-blue-800">
    <!-- Decorative Elements -->
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
    <div class="absolute top-0 left-0 w-96 h-96 bg-purple-500/30 rounded-full filter blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-500/30 rounded-full filter blur-3xl translate-x-1/2 translate-y-1/2"></div>
    
    <div class="max-w-6xl mx-auto px-4 relative z-10" data-aos="zoom-in">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <!-- Text Content -->
            <div class="text-center lg:text-left">
                <span class="inline-block px-4 py-1 bg-white/20 text-white/90 text-sm font-bold rounded-full mb-4 backdrop-blur">
                    🎓 Untuk Madrasah di Wilayah Kami
                </span>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-white mb-6 leading-tight">
                    Ingin Madrasah Anda <br class="hidden lg:block">
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-amber-400">Tampil di Website Ini?</span>
                </h2>
                <p class="text-white/80 text-lg mb-8 leading-relaxed">
                    Daftarkan diri Anda sebagai kontributor dan kelola halaman khusus untuk madrasah Anda sendiri. 
                    Publikasikan berita, kegiatan, dan informasi sekolah Anda langsung melalui platform ini!
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                    <a href="register" class="group px-8 py-4 bg-white text-purple-700 font-bold rounded-full shadow-xl hover:shadow-white/30 transition-all transform hover:-translate-y-1 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        Daftar Kontributor Sekarang
                    </a>
                    <a href="anggota" class="px-8 py-4 bg-white/10 text-white border border-white/30 font-bold rounded-full hover:bg-white/20 transition transform hover:-translate-y-1 backdrop-blur">
                        Lihat Daftar Madrasah
                    </a>
                </div>
            </div>
            
            <!-- Illustration/Features -->
            <div class="hidden lg:block">
                <div class="bg-white/10 backdrop-blur-lg rounded-3xl p-8 border border-white/20 shadow-2xl">
                    <h3 class="text-white font-bold text-xl mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                        </svg>
                        Keuntungan Menjadi Kontributor
                    </h3>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3 text-white/90">
                            <span class="flex-shrink-0 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center text-white text-sm">✓</span>
                            <span>Halaman profil khusus untuk madrasah Anda</span>
                        </li>
                        <li class="flex items-start gap-3 text-white/90">
                            <span class="flex-shrink-0 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center text-white text-sm">✓</span>
                            <span>Publikasikan berita & kegiatan madrasah</span>
                        </li>
                        <li class="flex items-start gap-3 text-white/90">
                            <span class="flex-shrink-0 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center text-white text-sm">✓</span>
                            <span>Tampil di galeri anggota KKG</span>
                        </li>
                        <li class="flex items-start gap-3 text-white/90">
                            <span class="flex-shrink-0 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center text-white text-sm">✓</span>
                            <span>100% Gratis, tanpa biaya apapun</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Additional script for custom animations defined in Tailwind config/style -->
<style>
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
    .animate-bounce-slow {
        animation: bounce 3s infinite;
    }
</style>

<?php include 'includes/footer.php'; ?>
