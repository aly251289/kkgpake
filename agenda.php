<?php 
require_once 'config/koneksi.php';
include 'includes/header.php'; 

// Helper function for Indonesian Date (if not already in index, but usually good to have in a helper file, defining here to be safe or reusing if included)
if (!function_exists('tgl_indo')) {
    function tgl_indo($tanggal){
        $bulan = array (
            1 =>   'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        );
        $pecahkan = explode('-', $tanggal);
        return $pecahkan[2] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[0];
    }
}
if (!function_exists('hari_indo')) {
    function hari_indo($tanggal){
        $hari = array ( 1 => 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu' );
        $num = date('N', strtotime($tanggal));
        return $hari[$num];
    }
}
?>

<!-- Page Header -->
    <!-- Hero Section -->
    <section class="relative py-24 bg-gradient-to-r from-emerald-900 to-emerald-700 text-white overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-emerald-300/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[500px] h-[500px] bg-sky-300/20 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="text-4xl md:text-5xl font-extrabold text-white tracking-tight mb-6" data-aos="fade-up">
            Agenda Kegiatan
        </h1>
        <p class="text-lg text-emerald-100 max-w-2xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="100">
            Jadwal kegiatan KKG MI mendatang. Jangan lewatkan momen penting untuk pengembangan diri.
        </p>
    </div>
</section>

<!-- Search Section -->
<section class="py-10 bg-gray-50 border-b border-gray-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <form action="" method="GET" class="relative">
            <input type="text" name="q" value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>" placeholder="Cari agenda kegiatan..." class="w-full pl-6 pr-14 py-4 rounded-full border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition text-lg">
            <button type="submit" class="absolute right-3 top-2 bottom-2 bg-emerald-600 text-white rounded-full px-6 font-medium hover:bg-emerald-700 transition flex items-center justify-center">
                Cari
            </button>
        </form>
    </div>
</section>

<!-- Agenda List -->
<section class="py-16 bg-white min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <?php
        // Pagination Setup
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $start = ($page - 1) * $limit;

        // Search Logic
        $where = "WHERE 1=1";
        if(isset($_GET['q']) && !empty($_GET['q'])) {
            $q = mysqli_real_escape_string($koneksi, $_GET['q']);
            $where .= " AND (judul LIKE '%$q%' OR keterangan LIKE '%$q%' OR lokasi LIKE '%$q%')";
        }

        // Count Total
        $result_count = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM agenda $where");
        $total_records = mysqli_fetch_assoc($result_count)['total'];
        $total_pages = ceil($total_records / $limit);

        // Fetch Data
        // Order by upcoming date first? Or just descending? 
        // Use Descending to show latest added / latest date usually makes sense for history, 
        // but for agenda, upcoming is important. 
        // Let's do Standard Descending (Newest dates first) so future events are at top if they are correctly dated.
        $query = mysqli_query($koneksi, "SELECT * FROM agenda $where ORDER BY tanggal DESC LIMIT $start, $limit");
        
        ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php
            // Limit text helper
            function limit_words($string, $word_limit) {
                $words = explode(" ", $string);
                return implode(" ", array_splice($words, 0, $word_limit));
            }

            if(mysqli_num_rows($query) > 0):
                while($item = mysqli_fetch_assoc($query)):
                    $is_past = strtotime($item['tanggal']) < strtotime(date('Y-m-d'));
                    // Styles based on status
                    $cardClass = $is_past ? 'bg-gray-50 opacity-75 grayscale' : 'bg-white border-emerald-100 shadow-lg hover:shadow-xl ring-1 ring-emerald-50';
                    $badgeClass = $is_past ? 'bg-gray-200 text-gray-500' : 'bg-emerald-100 text-emerald-700';
            ?>
            <!-- Agenda Card Grid Item -->
            <div class="<?= $cardClass ?> rounded-2xl p-6 border transition-all duration-300 flex flex-col relative overflow-hidden group h-full" data-aos="fade-up">
                <?php if(!$is_past): ?>
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-emerald-400/20 to-transparent rounded-bl-full -mr-10 -mt-10 pointer-events-none group-hover:scale-110 transition-transform duration-500"></div>
                <?php endif; ?>

                <!-- Header: Date & Status -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-shrink-0 w-16 h-16 rounded-2xl flex flex-col items-center justify-center border <?= $is_past ? 'border-gray-200 bg-gray-100 text-gray-400' : 'border-emerald-100 bg-emerald-50 text-emerald-600' ?>">
                        <span class="text-xs font-bold uppercase"><?= substr(tgl_indo($item['tanggal']), 3, 3) ?></span>
                        <span class="text-2xl font-black leading-none"><?= date('d', strtotime($item['tanggal'])) ?></span>
                    </div>
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $badgeClass ?>">
                        <?= $is_past ? 'Selesai' : 'Akan Datang' ?>
                    </span>
                </div>

                <!-- Content -->
                <div class="flex-grow flex flex-col">
                    <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-emerald-600 transition line-clamp-2" title="<?= $item['judul'] ?>">
                        <a href="agenda_detail.php?id=<?= $item['id'] ?>" class="block focus:outline-none"><?= $item['judul'] ?></a>
                    </h3>
                    
                    <div class="flex items-center text-xs text-gray-500 gap-2 mb-3">
                        <svg class="w-4 h-4 flex-shrink-0 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg> 
                        <span class="truncate"><?= $item['lokasi'] ?></span>
                    </div>

                    <?php if(!empty($item['keterangan'])): ?>
                    <p class="text-gray-500 text-sm leading-relaxed mb-4 line-clamp-3 flex-grow">
                        <?= limit_words(strip_tags($item['keterangan']), 20) ?>...
                    </p>
                    <?php endif; ?>

                    <div class="mt-auto pt-4 border-t border-gray-100 w-full">
                        <a href="agenda_detail.php?id=<?= $item['id'] ?>" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-gray-50 text-gray-700 rounded-xl font-bold text-sm hover:bg-emerald-600 hover:text-white transition-all group-hover:bg-emerald-50 group-hover:text-emerald-700 group-hover:hover:bg-emerald-600 group-hover:hover:text-white">
                            Lihat Detail
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div> 
        <?php else: ?>
            <div class="text-center py-20 bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <h3 class="text-xl font-bold text-gray-900 mb-1">Tidak ada agenda ditemukan</h3>
                <p class="text-gray-500">Coba kata kunci lain atau kembali lagi nanti.</p>
            </div>
        <?php endif; ?>

        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
        <div class="mt-12 flex justify-center">
            <nav class="flex items-center space-x-2">
                <?php if($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?><?= isset($_GET['q']) ? '&q='.htmlspecialchars($_GET['q']) : '' ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-300 transition">Prev</a>
                <?php endif; ?>
                
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if($i == $page): ?>
                        <span class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold shadow-md"><?= $i ?></span>
                    <?php else: ?>
                        <a href="?page=<?= $i ?><?= isset($_GET['q']) ? '&q='.htmlspecialchars($_GET['q']) : '' ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-300 transition"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if($page < $total_pages): ?>
                    <a href="?page=<?= $page + 1 ?><?= isset($_GET['q']) ? '&q='.htmlspecialchars($_GET['q']) : '' ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-300 transition">Next</a>
                <?php endif; ?>
            </nav>
        </div>
        <?php endif; ?>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
