<?php
require_once 'config/koneksi.php';
require_once 'admin/includes/SimpleXLSXGen.php';

// Handle Export Request
if (isset($_GET['action']) && $_GET['action'] == 'export') {
    if (ob_get_length())
        ob_clean();

    $madrasah = isset($_GET['madrasah']) ? mysqli_real_escape_string($koneksi, $_GET['madrasah']) : '';

    // Header Row
    $data = [['No', 'Nama Lengkap', 'Asal Madrasah', 'Jabatan']];

    // Query Data
    $where = "WHERE 1=1";
    if (!empty($madrasah)) {
        $where .= " AND jabatan = '$madrasah'";
    }

    $query = mysqli_query($koneksi, "SELECT * FROM guru $where ORDER BY nama ASC");
    $no = 1;
    while ($row = mysqli_fetch_assoc($query)) {
        $data[] = [
            $no++,
            $row['nama'],
            $row['jabatan'],
            $row['asal_madrasah']
        ];
    }

    $filename = empty($madrasah) ? 'data_semua_guru.xlsx' : 'data_guru_' . preg_replace('/[^a-zA-Z0-9]/', '_', $madrasah) . '.xlsx';

    $xlsx = SimpleXLSXGen::fromArray($data);
    $xlsx->downloadAs($filename);
    exit;
}
?>
<?php include 'includes/header.php'; ?>

<!-- Hero Section -->
<section class="relative py-24 bg-gradient-to-r from-emerald-900 to-emerald-700 text-white overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
    <div
        class="absolute top-0 right-0 w-96 h-96 bg-emerald-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob">
    </div>
    <div
        class="absolute -bottom-32 -left-32 w-96 h-96 bg-teal-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000">
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-6" data-aos="fade-up">Anggota KKG</h1>
        <p class="text-xl text-emerald-100 max-w-2xl mx-auto" data-aos="fade-up" data-aos-delay="100">
            Daftar lengkap guru dan anggota aktif
            <?= $d_identitas['nama_organisasi'] ?? 'Kelompok Kerja Guru Madrasah Ibtidaiyah' ?>.
        </p>
    </div>
</section>

<!-- Content -->
<section class="py-20 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden" data-aos="fade-up">
            <div class="p-6 bg-emerald-600 sm:p-10">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-white">Data Anggota Aktif</h2>
                        <p class="text-emerald-100 mt-1">Update per <?= date('d F Y') ?></p>
                    </div>
                </div>
            </div>

            <!-- Filter & Action Bar -->
            <div
                class="p-6 border-b border-gray-100 bg-gray-50 flex flex-col md:flex-row justify-between items-center gap-4">

                <!-- Filter Form -->
                <form method="GET" class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                    <label class="text-gray-700 font-bold hidden sm:block">Filter Madrasah:</label>
                    <select name="madrasah" onchange="this.form.submit()"
                        class="w-full sm:w-64 px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 text-gray-700 shadow-sm">
                        <option value="">-- Tampilkan Semua --</option>
                        <?php
                        // Fetch Distinct Madrasah
                        $q_madrasah = mysqli_query($koneksi, "SELECT DISTINCT jabatan FROM guru ORDER BY jabatan ASC");
                        $selected_madrasah = isset($_GET['madrasah']) ? $_GET['madrasah'] : '';

                        while ($m = mysqli_fetch_assoc($q_madrasah)):
                            ?>
                            <option value="<?= $m['jabatan'] ?>" <?= $selected_madrasah == $m['jabatan'] ? 'selected' : '' ?>>
                                <?= $m['jabatan'] ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </form>

                <!-- Action Buttons -->
                <div class="flex gap-3 w-full md:w-auto">
                    <?php
                    $download_link = "?action=export";
                    if (!empty($selected_madrasah)) {
                        $download_link .= "&madrasah=" . urlencode($selected_madrasah);
                        $btn_text = "Download Data (" . htmlspecialchars($selected_madrasah) . ")";
                    } else {
                        $btn_text = "Download Semua Data";
                    }
                    ?>
                    <a href="<?= $download_link ?>"
                        class="flex-1 md:flex-none flex items-center justify-center bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 px-5 rounded-lg shadow-md transition transform hover:-translate-y-0.5">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        <?= $btn_text ?>
                    </a>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-gray-600 font-bold uppercase text-xs tracking-wider">
                        <tr>
                            <th class="px-6 py-4 text-center w-16">No</th>
                            <th class="px-6 py-4">Nama Lengkap</th>
                            <th class="px-6 py-4">Asal Madrasah</th>
                            <th class="px-6 py-4 text-center">Jabatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php
                        // Pagination Setup
                        $limit = 10;
                        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
                        $start = ($page > 1) ? ($page * $limit) - $limit : 0;

                        // Build Query based on Filter
                        $where_sql = "WHERE 1=1";
                        if (!empty($selected_madrasah)) {
                            $safe_madrasah = mysqli_real_escape_string($koneksi, $selected_madrasah);
                            $where_sql .= " AND jabatan = '$safe_madrasah'";
                        }

                        // Count Total Data (for Pagination)
                        $query_total = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM guru $where_sql");
                        $total_data = mysqli_fetch_assoc($query_total)['total'];
                        $total_pages = ceil($total_data / $limit);

                        $no = $start + 1;
                        $query = mysqli_query($koneksi, "SELECT * FROM guru $where_sql ORDER BY nama ASC LIMIT $start, $limit");

                        if (mysqli_num_rows($query) > 0):
                            while ($row = mysqli_fetch_assoc($query)):
                                ?>
                                <tr class="hover:bg-emerald-50/50 transition duration-150">
                                    <td class="px-6 py-4 text-center text-gray-500 font-medium"><?= $no++ ?></td>
                                    <td class="px-6 py-4">
                                        <span class="font-bold text-gray-800 text-lg"><?= $row['nama'] ?></span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-gray-100 text-gray-800">
                                            <?= $row['jabatan'] ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            <?= $row['asal_madrasah'] ?: '-' ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php
                            endwhile;
                        else:
                            ?>
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <svg class="w-12 h-12 mb-3 text-gray-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                            </path>
                                        </svg>
                                        <p class="text-lg font-medium">Tidak ada data anggota ditemukan.</p>
                                        <?php if (!empty($selected_madrasah)): ?>
                                            <p class="text-sm">Untuk madrasah:
                                                <strong><?= htmlspecialchars($selected_madrasah) ?></strong>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div
                class="bg-gray-50 px-6 py-4 border-t border-gray-100 text-sm text-gray-500 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div>
                    Menampilkan data
                    <?= $total_data > 0 ? ($start + 1) . " - " . min($start + $limit, $total_data) : "0" ?> dari
                    <strong><?= $total_data ?></strong> guru.
                </div>

                <!-- Pagination UI -->
                <?php if ($total_pages > 1): ?>
                    <div class="flex items-center gap-2">
                        <?php
                        $url_params = "";
                        if (!empty($selected_madrasah))
                            $url_params .= "&madrasah=" . urlencode($selected_madrasah);

                        // Prev
                        if ($page > 1):
                            ?>
                            <a href="?page=<?= $page - 1 ?><?= $url_params ?>"
                                class="px-3 py-1 rounded bg-white border border-gray-300 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 transition">
                                &laquo; Prev
                            </a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <?php if ($i == $page): ?>
                                <span
                                    class="px-3 py-1 rounded bg-emerald-600 text-white font-bold border border-emerald-600"><?= $i ?></span>
                            <?php else: ?>
                                <a href="?page=<?= $i ?><?= $url_params ?>"
                                    class="px-3 py-1 rounded bg-white border border-gray-300 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 transition"><?= $i ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <!-- Next -->
                        <?php if ($page < $total_pages): ?>
                            <a href="?page=<?= $page + 1 ?><?= $url_params ?>"
                                class="px-3 py-1 rounded bg-white border border-gray-300 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 transition">
                                Next &raquo;
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>

<?php include 'includes/footer.php'; ?>