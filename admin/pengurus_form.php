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
$jabatan = '';
$foto = '';
$urutan = 99;
$action = 'add';

if (isset($_GET['id'])) {
    $action = 'edit';
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = mysqli_query($koneksi, "SELECT * FROM pengurus WHERE id='$id'");
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $nama = $row['nama'];
        $jabatan = $row['jabatan'];
        $foto = $row['foto'];
        $urutan = $row['urutan'];
    } else {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Tidak Ditemukan',
                text: 'Data tidak ditemukan!',
                icon: 'error',
                confirmButtonColor: '#d33'
            }).then(() => {
                window.location='pengurus.php';
            });
        });
        </script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jabatan = mysqli_real_escape_string($koneksi, $_POST['jabatan']);
    $urutan = (int) $_POST['urutan'];

    // Image Upload Handling
    $upload_error = false;
    $image_path = $foto; // Default to existing image

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['foto']['name'];
        $filetype = $_FILES['foto']['type'];
        $filesize = $_FILES['foto']['size'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if (!in_array(strtolower($ext), $allowed)) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Format Salah',
                    text: 'Format file tidak diizinkan! Gunakan JPG, PNG, atau GIF.',
                    icon: 'warning',
                    confirmButtonColor: '#f59e0b'
                });
            });
            </script>";
            $upload_error = true;
        } else {
            $new_filename = uniqid() . '_pengurus.' . $ext;
            $target_dir = "../assets/img/pengurus/";

            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_file)) {
                $image_path = 'assets/img/pengurus/' . $new_filename;
                // Delete old image
                if ($action == 'edit' && $foto && strpos($foto, 'http') === false) {
                    if (file_exists('../' . $foto)) {
                        unlink('../' . $foto);
                    }
                }
            } else {
                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Gagal Upload',
                        text: 'Gagal mengupload foto!',
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                });
                </script>";
                $upload_error = true;
            }
        }
    }

    if (!$upload_error) {
        if ($action == 'add') {
            $sql = "INSERT INTO pengurus (nama, jabatan, foto, urutan) VALUES ('$nama', '$jabatan', '$image_path', '$urutan')";
        } else {
            $sql = "UPDATE pengurus SET nama='$nama', jabatan='$jabatan', foto='$image_path', urutan='$urutan' WHERE id='$id'";
        }

        if (mysqli_query($koneksi, $sql)) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data berhasil disimpan!',
                    icon: 'success',
                    confirmButtonColor: '#059669'
                }).then(() => {
                    window.location='pengurus.php';
                });
            });
            </script>";
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Error',
                    text: 'Terjadi kesalahan: ' + " . json_encode(mysqli_error($koneksi)) . ",
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            });
            </script>";
        }
    }
}
?>

<div class="flex flex-col sm:flex-row justify-between items-center mb-6">
    <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">
        <?= $action == 'add' ? 'Tambah Pengurus' : 'Edit Pengurus' ?>
    </h3>
    <a href="pengurus" class="text-gray-600 hover:text-emerald-600 font-medium transition flex items-center">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
        </svg>
        Kembali
    </a>
</div>

<div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8 max-w-2xl mx-auto">
    <form method="POST" enctype="multipart/form-data">
        <div class="space-y-6">

            <!-- Nama -->
            <div>
                <label class="block text-gray-700 font-bold mb-2">Nama Lengkap & Gelar</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <!-- Jabatan -->
            <div>
                <label class="block text-gray-700 font-bold mb-2">Jabatan</label>
                <input type="text" name="jabatan" value="<?= htmlspecialchars($jabatan) ?>" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <!-- Urutan -->
            <div>
                <label class="block text-gray-700 font-bold mb-2">Urutan Tampil</label>
                <input type="number" name="urutan" value="<?= $urutan ?>" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                    placeholder="Contoh: 1">
                <p class="text-xs text-gray-500 mt-1">Angka lebih kecil akan tampil di urutan awal.</p>
            </div>

            <!-- Foto -->
            <div>
                <label class="block text-gray-700 font-bold mb-2">Foto Profil</label>
                <?php if ($foto): ?>
                    <div class="mb-4 flex items-center gap-4">
                        <img src="<?= strpos($foto, 'http') !== false ? $foto : '../' . $foto ?>" alt="Preview"
                            class="w-20 h-20 object-cover rounded-full border border-gray-200">
                        <p class="text-sm text-gray-500">Foto saat ini</p>
                    </div>
                <?php endif; ?>

                <input type="file" name="foto" accept="image/*" class="w-full text-sm text-gray-500
                file:mr-4 file:py-2 file:px-4
                file:rounded-full file:border-0
                file:text-sm file:font-semibold
                file:bg-emerald-50 file:text-emerald-700
                hover:file:bg-emerald-100 transition">
                <p class="text-xs text-gray-500 mt-1">Disarankan rasio 1:1 (persegi).</p>
            </div>

        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="pengurus"
                class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit"
                class="px-8 py-3 rounded-lg bg-emerald-600 text-white font-bold hover:bg-emerald-700 shadow-lg hover:shadow-emerald-500/30 transition transform hover:-translate-y-1">
                Simpan
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>