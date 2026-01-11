<?php include 'includes/header.php'; ?>
<?php require_once '../config/koneksi.php'; ?>

<?php
// Handle Sub-Photo Deletion
if (isset($_GET['delete_foto']) && isset($_GET['id'])) {
    $delete_id = mysqli_real_escape_string($koneksi, $_GET['delete_foto']);
    $parent_id = mysqli_real_escape_string($koneksi, $_GET['id']);

    $q_del = mysqli_query($koneksi, "SELECT foto FROM galeri_foto WHERE id='$delete_id'");
    $r_del = mysqli_fetch_assoc($q_del);

    if ($r_del['foto']) {
        if (strpos($r_del['foto'], 'http') === false) {
            if (file_exists('../' . $r_del['foto'])) {
                unlink('../' . $r_del['foto']);
            }
        }
    }

    mysqli_query($koneksi, "DELETE FROM galeri_foto WHERE id='$delete_id'");
    echo "<script>window.location='galeri_form.php?id=$parent_id';</script>";
}

$id = '';
$judul = '';
$tanggal = date('Y-m-d');
$foto = '';
$action = 'add';

if (isset($_GET['id']) && !isset($_GET['delete_foto'])) {
    $action = 'edit';
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = mysqli_query($koneksi, "SELECT * FROM galeri WHERE id='$id'");
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $judul = $row['judul'];
        $tanggal = $row['tanggal'];
        $foto = $row['foto'];
    } else {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Tidak Ditemukan',
                text: 'Data tidak ditemukan!',
                icon: 'error',
                confirmButtonColor: '#d33'
            }).then(() => {
                window.location='galeri.php';
            });
        });
        </script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $tanggal = mysqli_real_escape_string($koneksi, $_POST['tanggal']);

    // 1. Handle Main Thumbnail
    $upload_error = false;
    $image_path = $foto;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['foto']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Format Salah',
                    text: 'Format file thumbnail tidak diizinkan!',
                    icon: 'warning',
                    confirmButtonColor: '#f59e0b'
                });
            });
            </script>";
            $upload_error = true;
        } else {
            $new_filename = uniqid() . '_thumb.' . $ext;
            $target_dir = "../assets/img/galeri/";
            if (!file_exists($target_dir))
                mkdir($target_dir, 0777, true);

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $target_dir . $new_filename)) {
                $image_path = 'assets/img/galeri/' . $new_filename;
                // Delete old thumbnail
                if ($action == 'edit' && $foto && strpos($foto, 'http') === false) {
                    if (file_exists('../' . $foto))
                        unlink('../' . $foto);
                }
            }
        }
    } elseif ($action == 'add') {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Wajib',
                text: 'Harap pilih foto thumbnail utama!',
                icon: 'warning',
                confirmButtonColor: '#f59e0b'
            });
        });
        </script>";
        $upload_error = true;
    }

    if (!$upload_error) {
        $galeri_id = $id;

        if ($action == 'add') {
            $sql = "INSERT INTO galeri (judul, tanggal, foto) VALUES ('$judul', '$tanggal', '$image_path')";
            if (mysqli_query($koneksi, $sql)) {
                $galeri_id = mysqli_insert_id($koneksi);
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
                $upload_error = true;
            }
        } else {
            $sql = "UPDATE galeri SET judul='$judul', tanggal='$tanggal', foto='$image_path' WHERE id='$id'";
            mysqli_query($koneksi, $sql);
        }

        // 2. Handle Multiple Photos Upload
        if (!$upload_error && isset($_FILES['fotos'])) {
            $total_files = count($_FILES['fotos']['name']);

            for ($i = 0; $i < $total_files; $i++) {
                if ($_FILES['fotos']['error'][$i] == 0) {
                    $m_filename = $_FILES['fotos']['name'][$i];
                    $m_ext = strtolower(pathinfo($m_filename, PATHINFO_EXTENSION));

                    if (in_array($m_ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                        $m_newname = uniqid() . '_sub_' . $i . '.' . $m_ext;
                        $m_target = "../assets/img/galeri/" . $m_newname;

                        if (move_uploaded_file($_FILES['fotos']['tmp_name'][$i], $m_target)) {
                            $db_path = 'assets/img/galeri/' . $m_newname;
                            mysqli_query($koneksi, "INSERT INTO galeri_foto (id_galeri, foto) VALUES ('$galeri_id', '$db_path')");
                        }
                    }
                }
            }
        }

        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data galeri berhasil disimpan!',
                icon: 'success',
                confirmButtonColor: '#059669'
            }).then(() => {
                window.location='galeri.php';
            });
        });
        </script>";
    }
}
?>

<div class="flex flex-col sm:flex-row justify-between items-center mb-6">
    <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">
        <?= $action == 'add' ? 'Buat Album Galeri Baru' : 'Edit Album Galeri' ?></h3>
    <a href="galeri" class="text-gray-600 hover:text-emerald-600 font-medium transition flex items-center">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
            </path>
        </svg>
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    <!-- Main Form -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
            <form method="POST" enctype="multipart/form-data">
                <div class="space-y-6">

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Judul Kegiatan</label>
                        <input type="text" name="judul" value="<?= htmlspecialchars($judul) ?>" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                            placeholder="Nama Kegiatan...">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Tanggal Kegiatan</label>
                        <input type="date" name="tanggal" value="<?= $tanggal ?>" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Foto Thumbnail Utama</label>
                        <?php if ($foto): ?>
                            <div class="mb-4 relative group w-fit">
                                <img src="<?= strpos($foto, 'http') !== false ? $foto : '../' . $foto ?>" alt="Preview"
                                    class="h-40 w-auto object-cover rounded-lg border border-gray-200">
                                <div
                                    class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition rounded-lg flex items-center justify-center text-white text-xs">
                                    Thumbnail Saat Ini</div>
                            </div>
                        <?php endif; ?>

                        <input type="file" name="foto" accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 transition">
                        <p class="text-xs text-gray-500 mt-1">Ini foto yang akan tampil di halaman depan.</p>
                    </div>

                    <div class="pt-6 border-t border-gray-100">
                        <label class="block text-gray-700 font-bold mb-2">Upload Foto Tambahan (Banyak)</label>
                        <div
                            class="bg-gray-50 p-6 rounded-xl border-2 border-dashed border-gray-300 text-center hover:border-emerald-400 transition cursor-pointer relative">
                            <input type="file" name="fotos[]" multiple accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-2" stroke="currentColor" fill="none"
                                viewBox="0 0 48 48" aria-hidden="true">
                                <path
                                    d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="text-sm text-gray-600 font-medium">Klik atau drop file di sini untuk upload banyak
                                foto sekaligus</p>
                            <p class="text-xs text-gray-500 mt-1">Bisa pilih lebih dari satu file (JPG, PNG)</p>
                        </div>
                    </div>

                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <a href="galeri"
                        class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-8 py-3 rounded-lg bg-emerald-600 text-white font-bold hover:bg-emerald-700 shadow-lg hover:shadow-emerald-500/30 transition transform hover:-translate-y-1">
                        Simpan Semua
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Sidebar: Existing Photos List -->
    <?php if ($action == 'edit'): ?>
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 sticky top-24">
                <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    Foto Dalam Album Ini
                </h4>

                <?php
                $q_photos = mysqli_query($koneksi, "SELECT * FROM galeri_foto WHERE id_galeri='$id'");

                // Self-Healing: Create table if not exists
                if (!$q_photos) {
                    mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS galeri_foto (
                    id INT AUTO_INCREMENT PRIMARY KEY, 
                    id_galeri INT NOT NULL, 
                    foto VARCHAR(255) NOT NULL, 
                    FOREIGN KEY (id_galeri) REFERENCES galeri(id) ON DELETE CASCADE
                )");
                    $q_photos = mysqli_query($koneksi, "SELECT * FROM galeri_foto WHERE id_galeri='$id'");
                }

                if ($q_photos && mysqli_num_rows($q_photos) > 0):
                    ?>
                    <div class="grid grid-cols-2 gap-3">
                        <!-- Show main thumb first as reference -->
                        <div class="relative group rounded-lg overflow-hidden border-2 border-emerald-500">
                            <img src="<?= strpos($foto, 'http') !== false ? $foto : '../' . $foto ?>"
                                class="w-full h-24 object-cover">
                            <span
                                class="absolute bottom-0 inset-x-0 bg-emerald-600 text-white text-[10px] text-center py-1">COVER</span>
                        </div>

                        <?php while ($p = mysqli_fetch_assoc($q_photos)): ?>
                            <div class="relative group rounded-lg overflow-hidden border border-gray-200">
                                <img src="<?= strpos($p['foto'], 'http') !== false ? $p['foto'] : '../' . $p['foto'] ?>"
                                    class="w-full h-24 object-cover">

                                <a href="#"
                                    onclick="event.preventDefault(); confirmDelete('galeri_form.php?id=<?= $id ?>&delete_foto=<?= $p['id'] ?>')"
                                    class="absolute top-1 right-1 bg-red-600 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition shadow-sm hover:bg-red-700">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </a>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <p class="text-sm text-gray-500 italic">Belum ada foto tambahan. Upload foto baru di form sebelah kiri.</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>