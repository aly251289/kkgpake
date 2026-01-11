<?php include 'includes/header.php'; ?>
<?php require_once '../config/koneksi.php'; ?>

<?php
$id = '';
$judul = '';
$tanggal = date('Y-m-d');
$lokasi = '';
$keterangan = '';
$file_lampiran = ''; // New variable
$action = 'add';

// Self-Healing: Check if 'file_lampiran' column exists, if not add it
$check_col = mysqli_query($koneksi, "SHOW COLUMNS FROM agenda LIKE 'file_lampiran'");
if (mysqli_num_rows($check_col) == 0) {
    mysqli_query($koneksi, "ALTER TABLE agenda ADD COLUMN file_lampiran VARCHAR(255) DEFAULT NULL");
}

if (isset($_GET['id'])) {
    $action = 'edit';
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = mysqli_query($koneksi, "SELECT * FROM agenda WHERE id='$id'");
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $judul = $row['judul'];
        $tanggal = $row['tanggal'];
        $lokasi = $row['lokasi'];
        $keterangan = $row['keterangan'];
        $file_lampiran = $row['file_lampiran']; // Fetch existing file
    } else {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Tidak Ditemukan',
                text: 'Data agenda tidak ditemukan!',
                icon: 'error',
                confirmButtonColor: '#d33'
            }).then(() => {
                window.location='agenda.php';
            });
        });
        </script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $tanggal = $_POST['tanggal'];
    $lokasi = mysqli_real_escape_string($koneksi, $_POST['lokasi']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

    // File Upload Handling
    $filename = $file_lampiran; // Default to existing file
    if (isset($_FILES['file_lampiran']) && $_FILES['file_lampiran']['error'] == 0) {
        $target_dir = "../uploads/agenda/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_extension = pathinfo($_FILES["file_lampiran"]["name"], PATHINFO_EXTENSION);
        $new_filename = time() . '_' . uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;

        $allowed_types = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
        if (in_array(strtolower($file_extension), $allowed_types)) {
            if (move_uploaded_file($_FILES["file_lampiran"]["tmp_name"], $target_file)) {
                // Delete old file if exists and we are updating
                if ($action == 'edit' && !empty($file_lampiran) && file_exists("../uploads/agenda/" . $file_lampiran)) {
                    unlink("../uploads/agenda/" . $file_lampiran);
                }
                $filename = $new_filename;
            } else {
                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Gagal Upload',
                        text: 'Gagal mengupload file.',
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                });
                </script>";
            }
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Format Salah',
                    text: 'Format file tidak didukung! Gunakan PDF, DOCX, atau Gambar.',
                    icon: 'warning',
                    confirmButtonColor: '#f59e0b'
                });
            });
            </script>";
        }
    }

    if ($action == 'add') {
        $created_by = $_SESSION['admin_id'];
        $sql = "INSERT INTO agenda (judul, tanggal, lokasi, keterangan, file_lampiran, created_by) 
                VALUES ('$judul', '$tanggal', '$lokasi', '$keterangan', '$filename', '$created_by')";
    } else {
        // Ownership Check
        if ($_SESSION['admin_role'] != 'admin') {
            $check_owner = mysqli_query($koneksi, "SELECT created_by FROM agenda WHERE id='$id'");
            $owner = mysqli_fetch_assoc($check_owner);
            if ($owner['created_by'] != $_SESSION['admin_id']) {
                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({ title: 'Akses Ditolak', text: 'Anda tidak berhak mengedit agenda ini!', icon: 'error' }).then(() => { window.location='agenda.php'; });
                });
                </script>";
                exit;
            }
        }

        $sql = "UPDATE agenda SET 
                judul='$judul', 
                tanggal='$tanggal',
                lokasi='$lokasi', 
                keterangan='$keterangan',
                file_lampiran='$filename'
                WHERE id='$id'";
    }

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data agenda berhasil disimpan!',
                icon: 'success',
                confirmButtonColor: '#059669'
            }).then(() => {
                window.location='agenda.php';
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
?>

<div class="flex flex-col sm:flex-row justify-between items-center mb-6">
    <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">
        <?= $action == 'add' ? 'Tambah Agenda Baru' : 'Edit Agenda' ?>
    </h3>
    <a href="agenda" class="text-gray-600 hover:text-emerald-600 font-medium transition flex items-center">
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

            <!-- Judul (Full width) -->
            <div class="col-span-1 md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Nama Agenda / Kegiatan</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($judul) ?>" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <!-- Tanggal -->
            <div>
                <label class="block text-gray-700 font-bold mb-2">Tanggal Pelaksanaan</label>
                <input type="date" name="tanggal" value="<?= $tanggal ?>" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <!-- Lokasi -->
            <div>
                <label class="block text-gray-700 font-bold mb-2">Lokasi</label>
                <input type="text" name="lokasi" value="<?= htmlspecialchars($lokasi) ?>" required
                    placeholder="Contoh: Aula Kemenag"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <!-- File Lampiran -->
            <div class="col-span-1 md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Lampiran (Undangan/Proposal)</label>
                <input type="file" name="file_lampiran"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-xs text-gray-500 mt-1">Format: PDF, DOCX, JPG. Maks 5MB.</p>
                <?php if (!empty($file_lampiran)): ?>
                    <div class="mt-2 text-sm text-emerald-600 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                            </path>
                        </svg>
                        File saat ini: <a href="../uploads/agenda/<?= $file_lampiran ?>" target="_blank"
                            class="underline hover:text-emerald-800 ml-1"><?= $file_lampiran ?></a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Keterangan -->
            <div class="col-span-1 md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Keterangan Tambahan (Optional)</label>
                <textarea name="keterangan" rows="4"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"><?= htmlspecialchars($keterangan) ?></textarea>
            </div>

        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="agenda"
                class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit"
                class="px-8 py-3 rounded-lg bg-emerald-600 text-white font-bold hover:bg-emerald-700 shadow-lg hover:shadow-emerald-500/30 transition transform hover:-translate-y-1">
                Simpan Agenda
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>