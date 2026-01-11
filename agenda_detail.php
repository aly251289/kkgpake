<?php
require_once 'config/koneksi.php';
include 'includes/header.php';

$id = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : '';
$query = mysqli_query($koneksi, "SELECT agenda.*, users.nama_lengkap as author_name FROM agenda LEFT JOIN users ON agenda.created_by = users.id WHERE agenda.id='$id'");
$agenda = mysqli_fetch_assoc($query);

// Helper function for Indo Date (Reusing or re-declaring if not in a helper file)
if (!function_exists('tgl_indo_full')) {
    function tgl_indo_full($tanggal)
    {
        $bulan = array(
            1 => 'Januari',
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
        return $pecahkan[2] . ' ' . $bulan[(int) $pecahkan[1]] . ' ' . $pecahkan[0];
    }
}
if (!function_exists('hari_indo_full')) {
    function hari_indo_full($tanggal)
    {
        $hari = array(1 => 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu');
        $num = date('N', strtotime($tanggal));
        return $hari[$num];
    }
}
?>

<!-- Page Header -->
<section class="relative py-20 bg-gradient-to-r from-emerald-900 to-emerald-700 overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Detail Agenda</h1>
        <p class="text-emerald-100 text-lg">Informasi lengkap mengenai kegiatan KKG MI.</p>
    </div>
</section>

<section class="py-16 bg-gray-50 min-h-[60vh]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <?php if ($agenda):
            $tgl_agenda = strtotime($agenda['tanggal']);
            $is_passed = $tgl_agenda < strtotime(date('Y-m-d'));
            ?>
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <!-- Header Status -->
                <div
                    class="<?= $is_passed ? 'bg-gray-100 border-b border-gray-200' : 'bg-emerald-50 border-b border-emerald-100' ?> px-8 py-4 flex items-center justify-between">
                    <span
                        class="inline-flex items-center gap-2 <?= $is_passed ? 'text-gray-500' : 'text-emerald-700' ?> font-bold text-sm uppercase tracking-wider">
                        <?php if (!$is_passed): ?><span
                                class="animate-pulse w-2 h-2 rounded-full bg-emerald-500"></span><?php endif; ?>
                        <?= $is_passed ? 'Telah Selesai' : 'Akan Datang' ?>
                    </span>
                    <a href="agenda.php"
                        class="text-gray-500 hover:text-emerald-600 transition text-sm flex items-center font-medium">
                        &larr; Kembali ke Daftar
                    </a>
                </div>

                <div class="p-8 md:p-12">
                    <!-- Date Badge -->
                    <div class="flex flex-col md:flex-row gap-8 items-start">
                        <div
                            class="flex-shrink-0 bg-white border border-gray-200 rounded-2xl shadow-sm p-4 text-center min-w-[100px]">
                            <span
                                class="block text-sm font-bold text-gray-500 uppercase mb-1"><?= substr(hari_indo_full($agenda['tanggal']), 0, 3) ?></span>
                            <span
                                class="block text-4xl font-extrabold text-emerald-600 mb-1"><?= date('d', $tgl_agenda) ?></span>
                            <span
                                class="block text-xs font-bold text-gray-400 uppercase"><?= date('M Y', $tgl_agenda) ?></span>
                        </div>

                        <div class="flex-grow">
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4 leading-tight">
                                <?= $agenda['judul'] ?>
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8 text-gray-600">
                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-gray-100 rounded-lg text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Hari &
                                            Tanggal</span>
                                        <span class="font-medium text-gray-800"><?= hari_indo_full($agenda['tanggal']) ?>,
                                            <?= tgl_indo_full($agenda['tanggal']) ?></span>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div class="p-2 bg-gray-100 rounded-lg text-emerald-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <span
                                            class="block text-xs font-bold text-gray-400 uppercase tracking-wider">Lokasi</span>
                                        <span class="font-medium text-gray-800"><?= $agenda['lokasi'] ?></span>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($agenda['keterangan'])): ?>
                                <div class="prose prose-emerald max-w-none text-gray-600 mb-8">
                                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-2">Deskripsi /
                                        Keterangan</h4>
                                    <p class="whitespace-pre-line"><?= $agenda['keterangan'] ?></p>
                                </div>
                            <?php endif; ?>

                            <!-- Attachments -->
                            <?php if (!empty($agenda['file_lampiran'])):
                                $file_url = "uploads/agenda/" . $agenda['file_lampiran'];
                                $file_ext = pathinfo($agenda['file_lampiran'], PATHINFO_EXTENSION);
                                ?>
                                <div class="mt-8 pt-8 border-t border-gray-100">
                                    <h4 class="text-sm font-bold text-gray-900 uppercase tracking-wider mb-4">Lampiran Dokumen
                                    </h4>
                                    <a href="<?= $file_url ?>" target="_blank" download
                                        class="group inline-flex items-center gap-4 p-4 bg-emerald-50 border border-emerald-100 rounded-xl hover:bg-emerald-100 transition-all duration-300">
                                        <div
                                            class="p-3 bg-white rounded-lg text-red-500 shadow-sm group-hover:scale-110 transition-transform">
                                            <?php if (in_array(strtolower($file_ext), ['pdf'])): ?>
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            <?php elseif (in_array(strtolower($file_ext), ['doc', 'docx'])): ?>
                                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                    </path>
                                                </svg>
                                            <?php else: ?>
                                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                    </path>
                                                </svg>
                                            <?php endif; ?>
                                        </div>
                                        <div class="text-left">
                                            <span
                                                class="block font-bold text-gray-900 group-hover:text-emerald-700 transition">Download
                                                Lampiran</span>
                                            <span class="text-xs text-gray-500 uppercase"><?= $file_ext ?> File</span>
                                        </div>
                                        <div class="ml-2">
                                            <svg class="w-5 h-5 text-gray-400 group-hover:text-emerald-600" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                        </div>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="text-center py-20">
                <div class="inline-block p-4 rounded-full bg-gray-100 text-gray-400 mb-4">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-700 mb-2">Agenda Tidak Ditemukan</h2>
                <p class="text-gray-500 mb-8">Maaf, agenda yang Anda cari tidak tersedia atau telah dihapus.</p>
                <a href="agenda.php"
                    class="px-6 py-3 bg-emerald-600 text-white rounded-lg font-bold hover:bg-emerald-700 transition">Lihat
                    Semua Agenda</a>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include 'includes/footer.php'; ?>