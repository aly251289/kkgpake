<?php include 'includes/header.php'; ?>
<?php
// Access Check
if (empty($_SESSION['admin_role']) || $_SESSION['admin_role'] != 'admin') {
    echo "<script>window.location='dashboard.php';</script>";
    exit;
}
?>
<?php require_once '../config/koneksi.php'; ?>

<?php
$id = '';
$nama = '';
$nip = '';
$asal_madrasah = '';
$jabatan = '';
$action = 'add';

if (isset($_GET['id'])) {
    $action = 'edit';
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = mysqli_query($koneksi, "SELECT * FROM guru WHERE id='$id'");
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $nama = $row['nama'];
        $nip = $row['nip'];
        $asal_madrasah = $row['asal_madrasah'];
        $jabatan = isset($row['jabatan']) ? $row['jabatan'] : '';
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    // NIP removed
    $asal_madrasah = mysqli_real_escape_string($koneksi, $_POST['asal_madrasah']);
    $jabatan = mysqli_real_escape_string($koneksi, $_POST['jabatan']);

    if ($action == 'add') {
        $sql = "INSERT INTO guru (nama, asal_madrasah, jabatan) VALUES ('$nama', '$asal_madrasah', '$jabatan')";
    } else {
        $sql = "UPDATE guru SET nama='$nama', asal_madrasah='$asal_madrasah', jabatan='$jabatan' WHERE id='$id'";
    }

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data guru berhasil disimpan!',
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
                title: 'Error',
                text: 'Error: ' + " . json_encode(mysqli_error($koneksi)) . ",
                icon: 'error',
                confirmButtonColor: '#d33'
            });
        });
        </script>";
    }
}
?>

<div class="flex flex-col sm:flex-row justify-between items-center mb-6">
    <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">
        <?= $action == 'add' ? 'Tambah Data Guru' : 'Edit Data Guru' ?>
    </h3>
    <a href="guru" class="text-gray-600 hover:text-emerald-600 font-medium transition flex items-center">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
        </svg>
        Kembali
    </a>
</div>

<div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8 max-w-2xl mx-auto">
    <form method="POST">
        <div class="space-y-6">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Nama Lengkap (dengan Gelar)</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                    placeholder="Contoh: Ahmad S.Pd.I">
            </div>

            <!-- NIP field removed as requested -->

            <div>
                <label class="block text-gray-700 font-bold mb-2">Asal Madrasah</label>
                <input type="text" name="asal_madrasah" value="<?= htmlspecialchars($asal_madrasah) ?>" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                    placeholder="Contoh: MI Al-Ikhlas">
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Jabatan / Tugas Utama</label>
                <input type="text" name="jabatan" value="<?= htmlspecialchars($jabatan) ?>"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                    placeholder="Contoh: Guru Kelas, Kepala Madrasah, Waka Kurikulum">
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="guru"
                class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit"
                class="px-8 py-3 rounded-lg bg-emerald-600 text-white font-bold hover:bg-emerald-700 shadow-lg hover:shadow-emerald-500/30 transition transform hover:-translate-y-1">
                Simpan Data
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>