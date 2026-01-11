<?php include 'includes/header.php'; ?>
<?php require_once '../config/koneksi.php'; ?>

<?php
$id = '';
$judul = '';
$isi = '';
$tanggal = date('Y-m-d');
$tanggal_berakhir = date('Y-m-d', strtotime('+7 days')); // Default 1 week
$lampiran = '';
$action = 'add';

if (isset($_GET['id'])) {
    $action = 'edit';
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = mysqli_query($koneksi, "SELECT * FROM pengumuman WHERE id='$id'");
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $judul = $row['judul'];
        $isi = $row['isi'];
        $tanggal = $row['tanggal'];
        $tanggal_berakhir = $row['tanggal_berakhir'];
        $lampiran = $row['lampiran'];
    } else {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Tidak Ditemukan',
                text: 'Data pengumuman tidak ditemukan!',
                icon: 'error',
                confirmButtonColor: '#d33'
            }).then(() => {
                window.location='pengumuman.php';
            });
        });
        </script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi']);
    $tanggal = $_POST['tanggal'];
    $tanggal_berakhir = $_POST['tanggal_berakhir'];

    // Handle File Upload
    $lampiran_path = $lampiran; // Default to existing
    $upload_error = false;

    if (isset($_FILES['lampiran']) && $_FILES['lampiran']['error'] == 0) {
        $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
        $filename = $_FILES['lampiran']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ title: 'Format Salah', text: 'Hanya file PDF, JPG, dan PNG yang diperbolehkan!', icon: 'warning' });
            });
            </script>";
            $upload_error = true;
        } else {
            $new_filename = uniqid() . '_lampiran.' . $ext;
            $target_dir = "../assets/files/pengumuman/";

            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            if (move_uploaded_file($_FILES['lampiran']['tmp_name'], $target_dir . $new_filename)) {
                $lampiran_path = 'assets/files/pengumuman/' . $new_filename;
                // Delete old file if exists
                if ($action == 'edit' && $lampiran && file_exists('../' . $lampiran)) {
                    unlink('../' . $lampiran);
                }
            } else {
                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({ title: 'Gagal Upload', text: 'Gagal mengupload file lampiran!', icon: 'error' });
                });
                </script>";
                $upload_error = true;
            }
        }
    }

    if (!$upload_error) {
        if ($action == 'add') {
            $created_by = $_SESSION['admin_id'];
            $sql = "INSERT INTO pengumuman (judul, isi, tanggal, tanggal_berakhir, lampiran, created_by) 
                    VALUES ('$judul', '$isi', '$tanggal', '$tanggal_berakhir', '$lampiran_path', '$created_by')";
        } else {
            // Verify ownership if not admin
            if ($_SESSION['admin_role'] != 'admin') {
                $check_owner = mysqli_query($koneksi, "SELECT created_by FROM pengumuman WHERE id='$id'");
                $owner = mysqli_fetch_assoc($check_owner);
                if ($owner['created_by'] != $_SESSION['admin_id']) {
                    // Error handling script...
                    exit;
                }
            }
            $sql = "UPDATE pengumuman SET 
                    judul='$judul', 
                    isi='$isi', 
                    tanggal='$tanggal',
                    tanggal_berakhir='$tanggal_berakhir',
                    lampiran='$lampiran_path'
                    WHERE id='$id'";
        }

        if (mysqli_query($koneksi, $sql)) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data pengumuman berhasil disimpan!',
                    icon: 'success',
                    confirmButtonColor: '#059669'
                }).then(() => {
                    window.location='pengumuman.php';
                });
            });
            </script>";
        } else {
            $error_msg = mysqli_error($koneksi);
            // Error handling...
        }
    }
}
?>

<div class="flex flex-col sm:flex-row justify-between items-center mb-6">
    <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">
        <?= $action == 'add' ? 'Tambah Pengumuman Baru' : 'Edit Pengumuman' ?>
    </h3>
    <a href="pengumuman" class="text-gray-600 hover:text-emerald-600 font-medium transition flex items-center">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
        </svg>
        Kembali
    </a>
</div>

<div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8 max-w-4xl mx-auto">
    <form method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Judul -->
            <div class="col-span-1 md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Judul Pengumuman</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($judul) ?>" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <!-- Tanggal Mulai -->
            <div>
                <label class="block text-gray-700 font-bold mb-2">Tanggal Publikasi</label>
                <input type="date" name="tanggal" value="<?= $tanggal ?>" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <!-- Tanggal Berakhir -->
            <div>
                <label class="block text-gray-700 font-bold mb-2">Berlaku Sampai</label>
                <input type="date" name="tanggal_berakhir" value="<?= $tanggal_berakhir ?>" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <!-- Upload Lampiran -->
            <div class="col-span-1 md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Lampiran File (PDF/Gambar)</label>
                <div class="flex items-center space-x-4">
                    <input type="file" name="lampiran" accept=".pdf,.jpg,.jpeg,.png" class="block w-full text-sm text-slate-500
                        file:mr-4 file:py-2 file:px-4
                        file:rounded-full file:border-0
                        file:text-sm file:font-semibold
                        file:bg-emerald-50 file:text-emerald-700
                        hover:file:bg-emerald-100
                      " />
                </div>
                <?php if ($lampiran): ?>
                    <div class="mt-2 text-sm text-gray-600 flex items-center">
                        <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                            </path>
                        </svg>
                        Terlampir: <a href="../<?= $lampiran ?>" target="_blank"
                            class="text-blue-600 hover:underline"><?= basename($lampiran) ?></a>
                    </div>
                <?php endif; ?>
                <p class="text-xs text-gray-400 mt-1">Opsional. Maksimal upload 5MB.</p>
            </div>

            <!-- Isi Pengumuman -->
            <div class="col-span-1 md:col-span-2">

                <label class="block text-gray-700 font-bold mb-2">Isi Pengumuman</label>
                <textarea name="isi" rows="10"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition font-mono text-sm"><?= htmlspecialchars($isi) ?></textarea>
            </div>

        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="pengumuman"
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

<!-- TinyMCE Implementation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: 'textarea[name="isi"]',
        height: 400,
        plugins: 'advlist autolink lists link charmap preview keymap table help',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | bullist numlist outdent indent | table | removeformat',
        content_style: 'body { font-family:Inter,sans-serif; font-size:14px }',
        branding: false,
        promotion: false
    });
</script>

<?php include 'includes/footer.php'; ?>