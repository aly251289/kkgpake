<?php 
require_once 'config/koneksi.php';
include 'includes/header.php'; 

// Filter Setup
$cat_filter = isset($_GET['cat']) ? mysqli_real_escape_string($koneksi, $_GET['cat']) : '';
$where = "WHERE 1=1";
if($cat_filter) {
    $where .= " AND kategori LIKE '%$cat_filter%'";
}

// Pagination Setup
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// View Data
$query = mysqli_query($koneksi, "SELECT * FROM unduhan $where ORDER BY tanggal_upload DESC LIMIT $start, $limit");

// Count Total
$query_count = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM unduhan $where");
$total_records = mysqli_fetch_assoc($query_count)['total'];
$total_pages = ceil($total_records / $limit);
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
            Area Unduhan
        </h1>
        <p class="text-lg text-emerald-100 max-w-2xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="100">
            Unduh materi, regulasi, dan dokumen penting lainnya untuk kebutuhan pembelajaran.
        </p>
    </div>
</section>

<section class="py-16 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Filter Tabs -->
        <div class="flex flex-wrap justify-center gap-4 mb-12" data-aos="fade-up">
            <a href="unduhan.php" class="<?= $cat_filter == '' ? 'bg-emerald-600 text-white' : 'bg-white text-gray-600 hover:shadow-md' ?> px-6 py-2.5 rounded-full shadow-lg font-medium transform hover:-translate-y-1 transition">Semua Dokumen</a>
            <a href="unduhan.php?cat=Perangkat" class="<?= strpos($cat_filter, 'Perangkat') !== false ? 'bg-emerald-600 text-white' : 'bg-white text-gray-600 hover:shadow-md' ?> px-6 py-2.5 rounded-full shadow-sm border border-gray-100 font-medium transform hover:-translate-y-1 transition">Perangkat Ajar</a>
            <a href="unduhan.php?cat=Regulasi" class="<?= $cat_filter == 'Regulasi' ? 'bg-emerald-600 text-white' : 'bg-white text-gray-600 hover:shadow-md' ?> px-6 py-2.5 rounded-full shadow-sm border border-gray-100 font-medium transform hover:-translate-y-1 transition">Regulasi</a>
            <a href="unduhan.php?cat=Modul" class="<?= strpos($cat_filter, 'Modul') !== false ? 'bg-emerald-600 text-white' : 'bg-white text-gray-600 hover:shadow-md' ?> px-6 py-2.5 rounded-full shadow-sm border border-gray-100 font-medium transform hover:-translate-y-1 transition">Modul</a>
            <a href="unduhan.php?cat=Lainnya" class="<?= $cat_filter == 'Lainnya' ? 'bg-emerald-600 text-white' : 'bg-white text-gray-600 hover:shadow-md' ?> px-6 py-2.5 rounded-full shadow-sm border border-gray-100 font-medium transform hover:-translate-y-1 transition">Lainnya</a>
        </div>

        <!-- File List -->
        <div class="space-y-4">
            <?php
            if(mysqli_num_rows($query) > 0):
                while($row = mysqli_fetch_assoc($query)):
                    // Determine Icon and Color
                    $icon_bg = 'bg-gray-100';
                    $icon_text = 'text-gray-500';
                    $icon_svg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>';

                    $ext = strtolower($row['tipe_file']);
                    if(in_array($ext, ['pdf'])) {
                        $icon_bg = 'bg-red-100 group-hover:bg-red-200';
                        $icon_text = 'text-red-500';
                    } elseif(in_array($ext, ['doc', 'docx'])) {
                        $icon_bg = 'bg-blue-100 group-hover:bg-blue-200';
                        $icon_text = 'text-blue-500';
                        $icon_svg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>';
                    } elseif(in_array($ext, ['xls', 'xlsx'])) {
                        $icon_bg = 'bg-green-100 group-hover:bg-green-200';
                        $icon_text = 'text-green-500';
                        $icon_svg = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>';
                    }
            ?>
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-[0_4px_20px_rgb(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 flex flex-col md:flex-row items-center justify-between group gap-6" data-aos="fade-up">
                <div class="flex items-center gap-6 w-full md:w-auto">
                    <div class="w-16 h-16 <?= $icon_bg ?> rounded-2xl flex items-center justify-center flex-shrink-0 transition-transform group-hover:scale-110 shadow-sm relative overflow-hidden">
                         <div class="absolute inset-0 bg-white/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                         <svg class="w-8 h-8 <?= $icon_text ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                             <?= $icon_svg ?>
                         </svg>
                    </div>
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 group-hover:text-emerald-600 transition mb-1"><?= $row['judul'] ?></h4>
                        <div class="flex flex-wrap gap-3 text-sm text-gray-500">
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <?= $row['kategori'] ?>
                            </span>
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg> 
                                <?= $row['diunggah_oleh'] ?>
                            </span>
                             <span class="text-gray-300">|</span>
                             <span><?= strtoupper($row['tipe_file']) ?> • <?= $row['ukuran_file'] ?></span>
                        </div>
                    </div>
                </div>
                <a href="assets/files/<?= $row['nama_file'] ?>" target="_blank" class="px-6 py-3 bg-emerald-50 text-emerald-700 font-bold rounded-xl hover:bg-emerald-600 hover:text-white transition-all duration-300 flex items-center gap-2 w-full md:w-auto justify-center group-hover:shadow-lg shadow-emerald-500/20 whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Unduh File
                </a>
            </div>
            <?php 
                endwhile;
            else:
            ?>
                <div class="text-center py-12 bg-white rounded-xl border border-dashed border-gray-300">
                    <p class="text-gray-500">Belum ada dokumen yang tersedia.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if($total_pages > 1): ?>
        <div class="mt-12 flex justify-center">
            <nav class="flex items-center space-x-2">
                <?php if($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?><?= $cat_filter ? '&cat='.$cat_filter : '' ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-300 transition">Prev</a>
                <?php endif; ?>
                
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <?php if($i == $page): ?>
                        <span class="px-4 py-2 bg-emerald-600 text-white rounded-lg font-bold shadow-md"><?= $i ?></span>
                    <?php else: ?>
                        <a href="?page=<?= $i ?><?= $cat_filter ? '&cat='.$cat_filter : '' ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-300 transition"><?= $i ?></a>
                    <?php endif; ?>
                <?php endfor; ?>

                <?php if($page < $total_pages): ?>
                    <a href="?page=<?= $page + 1 ?><?= $cat_filter ? '&cat='.$cat_filter : '' ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-300 transition">Next</a>
                <?php endif; ?>
            </nav>
        </div>
        <?php endif; ?>
        
        <div class="mt-4 flex justify-center text-sm text-gray-500">
             Menampilkan <?= mysqli_num_rows($query) ?> dari <?= $total_records ?> dokumen
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>
