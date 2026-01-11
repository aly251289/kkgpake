<?php include 'includes/header.php'; ?>
<?php require_once '../config/koneksi.php'; ?>

<?php
$id = '';
$judul = '';
$kategori = '';
$nama_file = '';
$tipe_file = '';
$ukuran_file = '';
$action = 'add';

if (isset($_GET['id'])) {
    $action = 'edit';
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = mysqli_query($koneksi, "SELECT * FROM unduhan WHERE id='$id'");
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $judul = $row['judul'];
        $kategori = $row['kategori'];
        $nama_file = $row['nama_file'];
        $tipe_file = $row['tipe_file'];
        $ukuran_file = $row['ukuran_file'];
    } else {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Tidak Ditemukan',
                text: 'Data tidak ditemukan!',
                icon: 'error',
                confirmButtonColor: '#d33'
            }).then(() => {
                window.location='unduhan.php';
            });
        });
        </script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $diunggah_oleh = $_SESSION['admin_name'];

    // File Upload Handling
    $upload_error = false;
    $final_filename = $nama_file;
    $final_type = $tipe_file;
    $final_size = $ukuran_file;

    if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] == 0) {
        $allowed = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar'];
        $filename = $_FILES['file_upload']['name'];
        $filesize_bytes = $_FILES['file_upload']['size'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Format Salah',
                    text: 'Format file tidak diizinkan! Gunakan PDF, Office Doc, atau Zip.',
                    icon: 'warning',
                    confirmButtonColor: '#f59e0b'
                });
            });
            </script>";
            $upload_error = true;
        } else {
            // Generate unique name
            $new_filename = uniqid() . '_' . str_replace(' ', '_', $filename);
            $target_dir = "../assets/files/";

            // Create dir if not exists
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $target_file)) {
                $final_filename = $new_filename;
                $final_type = $ext;

                // Format Size
                if ($filesize_bytes >= 1048576) {
                    $final_size = number_format($filesize_bytes / 1048576, 2) . ' MB';
                } elseif ($filesize_bytes >= 1024) {
                    $final_size = number_format($filesize_bytes / 1024, 2) . ' KB';
                } else {
                    $final_size = $filesize_bytes . ' bytes';
                }

                // Delete old file if exists
                if ($action == 'edit' && $nama_file) {
                    if (file_exists('../assets/files/' . $nama_file)) {
                        unlink('../assets/files/' . $nama_file);
                    }
                }
            } else {
                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Gagal Upload',
                        text: 'Gagal mengupload file!',
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                });
                </script>";
                $upload_error = true;
            }
        }
    } elseif ($action == 'add') {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Wajib',
                text: 'Wajib memilih file untuk diupload!',
                icon: 'warning',
                confirmButtonColor: '#f59e0b'
            });
        });
        </script>";
        $upload_error = true;
    }

    if (!$upload_error) {
        if ($action == 'add') {
            $sql = "INSERT INTO unduhan (judul, kategori, nama_file, tipe_file, ukuran_file, diunggah_oleh) 
                    VALUES ('$judul', '$kategori', '$final_filename', '$final_type', '$final_size', '$diunggah_oleh')";
        } else {
            $sql = "UPDATE unduhan SET 
                    judul='$judul', 
                    kategori='$kategori', 
                    nama_file='$final_filename', 
                    tipe_file='$final_type',
                    ukuran_file='$final_size'
                    WHERE id='$id'";
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
                    window.location='unduhan.php';
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
    <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0"><?= $action == 'add' ? 'Tambah File Baru' : 'Edit File' ?>
    </h3>
    <a href="unduhan" class="text-gray-600 hover:text-emerald-600 font-medium transition flex items-center">
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
                <label class="block text-gray-700 font-bold mb-2">Nama Data / Judul File</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($judul) ?>" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-gray-700 font-bold mb-2">Kategori</label>
                <select name="kategori" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition bg-white">
                    <option value="Perangkat Ajar" <?= $kategori == 'Perangkat Ajar' ? 'selected' : '' ?>>Perangkat Ajar
                        (RPP/Silabus)</option>
                    <option value="Soal Ujian" <?= $kategori == 'Soal Ujian' ? 'selected' : '' ?>>Soal Ujian / Latihan
                    </option>
                    <option value="Regulasi" <?= $kategori == 'Regulasi' ? 'selected' : '' ?>>Regulasi / SK</option>
                    <option value="Modul" <?= $kategori == 'Modul' ? 'selected' : '' ?>>Modul / Materi</option>
                    <option value="Administrasi" <?= $kategori == 'Administrasi' ? 'selected' : '' ?>>Administrasi
                        Guru/Kelas</option>
                    <option value="Daftar Hadir Kegiatan" <?= $kategori == 'Daftar Hadir Kegiatan' ? 'selected' : '' ?>>
                        Daftar Hadir Kegiatan</option>
                    <option value="Undangan Kegiatan" <?= $kategori == 'Undangan Kegiatan' ? 'selected' : '' ?>>Undangan
                        Kegiatan</option>
                    <option value="Lainnya" <?= $kategori == 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                </select>
            </div>

            <!-- Readonly info for edit -->
            <?php if ($action == 'edit'): ?>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Info File Saat Ini</label>
                    <div class="px-4 py-3 rounded-lg bg-gray-50 border border-gray-200 text-sm text-gray-600">
                        Tipe: <span class="uppercase font-bold"><?= $tipe_file ?></span> | Ukuran: <?= $ukuran_file ?>
                    </div>
                </div>
            <?php else: ?>
                <div></div> <!-- Spacer -->
            <?php endif; ?>

            <!-- Upload File -->
            <div class="col-span-1 md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Upload File Dokumen</label>
                <?php if ($nama_file): ?>
                    <div class="mb-4 p-2 border border-gray-200 rounded-lg inline-flex items-center bg-gray-50">
                        <svg class="w-6 h-6 text-gray-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <span class="text-sm text-gray-700 mr-2"><?= $nama_file ?></span>
                        <span class="text-xs text-green-600 font-bold">(Tersimpan)</span>
                    </div>
                <?php endif; ?>

                <div class="flex items-center justify-center w-full">
                    <label
                        class="flex flex-col w-full h-40 border-4 border-dashed hover:bg-gray-100 hover:border-emerald-300 group cursor-pointer transition">
                        <div class="flex flex-col items-center justify-center pt-10">
                            <svg class="w-12 h-12 text-gray-400 group-hover:text-emerald-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                </path>
                            </svg>
                            <p
                                class="pt-2 text-sm tracking-wider text-gray-500 group-hover:text-emerald-600 font-medium">
                                Klik untuk pilih file (PDF, DOCX, XLSX)
                            </p>
                            <p class="text-xs text-gray-400 mt-1" id="fileNameDisplay">Maks 10MB</p>
                        </div>
                        <input type="file" name="file_upload" class="opacity-0"
                            onchange="document.getElementById('fileNameDisplay').innerText = this.files[0].name"
                            <?= $action == 'add' ? 'required' : '' ?> />
                    </label>
                </div>
            </div>

        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="unduhan"
                class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit"
                class="px-8 py-3 rounded-lg bg-emerald-600 text-white font-bold hover:bg-emerald-700 shadow-lg hover:shadow-emerald-500/30 transition transform hover:-translate-y-1">
                Simpan File
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>