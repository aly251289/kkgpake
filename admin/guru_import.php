<?php
require_once '../config/koneksi.php';
require_once 'includes/SimpleXLSX.php';
require_once 'includes/SimpleXLSXGen.php';

// Handle Template Download (Real Valid .xlsx)
if (isset($_GET['download_template'])) {
    if (ob_get_length())
        ob_clean();

    // Generate Template Data
    $data = [
        ['No', 'Nama Lengkap', 'Asal Madrasah', 'Jabatan'],
        ['1', 'Ahmad Fauzi S.Pd', 'MI Al-Ikhlas', 'Guru Kelas'],
        ['2', 'Siti Nurhaliza', 'MI Darussalam', 'Waka Kurikulum'],
        ['3', 'Budi Santoso', 'MI Nurul Huda', 'Kepala Madrasah']
    ];

    $xlsx = SimpleXLSXGen::fromArray($data);
    $xlsx->downloadAs('template_data_guru.xlsx');
    exit;
}

// Handle Import
if (isset($_POST['import'])) {
    $file = $_FILES['file']['tmp_name'];
    $ext = strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION));

    if ($ext != 'xlsx') {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Format Salah',
                text: 'Harap upload file Excel format .xlsx!',
                icon: 'warning',
                confirmButtonColor: '#f59e0b'
            }).then(() => {
                window.location='guru_import.php';
            });
        });
        </script>";
        exit;
    } else {
        if ($xlsx = SimpleXLSX::parse($file)) {
            $i = 0;
            $success = 0;
            $fail = 0;

            foreach ($xlsx->rows as $data) {
                $i++;
                if ($i == 1)
                    continue; // Skip Header

                // Check minimal columns (sometimes empty cells are skipped by simplified parser, need care)
                // Assuming standard fill: [No, Nama, Asal, Status]
                // Our simple parser returns array of values.

                // Safe access
                $nama = isset($data[1]) ? mysqli_real_escape_string($koneksi, $data[1]) : '';
                $asal = isset($data[2]) ? mysqli_real_escape_string($koneksi, $data[2]) : '';
                $jabatan = isset($data[3]) ? mysqli_real_escape_string($koneksi, $data[3]) : 'Guru Kelas';

                if (!empty($nama)) {
                    $sql = "INSERT INTO guru (nama, asal_madrasah, jabatan) VALUES ('$nama', '$asal', '$jabatan')";
                    if (mysqli_query($koneksi, $sql)) {
                        $success++;
                    } else {
                        $fail++;
                    }
                }
            }
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Import Selesai',
                    text: 'Berhasil: $success, Gagal: $fail',
                    icon: 'success',
                    confirmButtonColor: '#059669'
                }).then(() => {
                    window.location='guru.php';
                });
            });
            </script>";
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Gagal',
                    text: 'Gagal membaca file Excel! Pastikan file tidak rusak.',
                    icon: 'error',
                    confirmButtonColor: '#d33'
                }).then(() => {
                    window.location='guru_import.php';
                });
            });
            </script>";
        }
    }
}
?>
<?php include 'includes/header.php'; ?>

<div class="flex flex-col sm:flex-row justify-between items-center mb-6">
    <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">Import Data Guru</h3>
    <a href="guru" class="text-gray-600 hover:text-emerald-600 font-medium transition flex items-center">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
        </svg>
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">

    <!-- Instruction Card -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
        <h4 class="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
            <span
                class="bg-emerald-100 text-emerald-600 w-8 h-8 flex items-center justify-center rounded-full text-sm">1</span>
            Panduan Import
        </h4>
        <ul class="space-y-3 text-gray-600 text-sm mb-6">
            <li class="flex items-start gap-2">
                <svg class="w-5 h-5 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Siapkan data menggunakan Template Excel yang disediakan.</span>
            </li>
            <li class="flex items-start gap-2">
                <svg class="w-5 h-5 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Susunan kolom: <strong>No, Nama, Asal Madrasah, Jabatan</strong>.</span>
            </li>
            <li class="flex items-start gap-2">
                <svg class="w-5 h-5 text-emerald-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>Simpan dan upload file <strong>Excel Workbook (.xlsx)</strong>.</span>
            </li>
        </ul>

        <a href="guru_import.php?download_template=true"
            class="inline-flex items-center px-4 py-2 bg-emerald-50 text-emerald-700 rounded-lg hover:bg-emerald-100 transition font-bold border border-emerald-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
            </svg>
            Download Template Excel
        </a>
    </div>

    <!-- Upload Form -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
        <h4 class="font-bold text-lg text-gray-800 mb-4 flex items-center gap-2">
            <span
                class="bg-emerald-100 text-emerald-600 w-8 h-8 flex items-center justify-center rounded-full text-sm">2</span>
            Upload File Excel (.xlsx)
        </h4>

        <form method="POST" enctype="multipart/form-data">
            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Pilih File Excel</label>
                <input type="file" name="file" accept=".xlsx" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-xs text-gray-400 mt-2">*Hanya menerima format .xlsx</p>
            </div>

            <button type="submit" name="import"
                class="w-full px-6 py-3 rounded-lg bg-emerald-600 text-white font-bold hover:bg-emerald-700 shadow-lg hover:shadow-emerald-500/30 transition transform hover:-translate-y-1 flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
                Import Sekarang
            </button>
        </form>
    </div>

</div>

<?php include 'includes/footer.php'; ?>