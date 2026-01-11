<?php include 'includes/header.php'; ?>
<?php require_once '../config/koneksi.php'; ?>

<?php
$id = '';
$nama_madrasah = '';
$url_rdm = '';
$gambar_lama = '';
$action = 'add';

// Self-Healing: Ensure 'gambar' column exists
$check_col = mysqli_query($koneksi, "SHOW COLUMNS FROM rdm_links LIKE 'gambar'");
if (mysqli_num_rows($check_col) == 0) {
    mysqli_query($koneksi, "ALTER TABLE rdm_links ADD COLUMN gambar VARCHAR(255) DEFAULT NULL");
}

if (isset($_GET['id'])) {
    $action = 'edit';
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = mysqli_query($koneksi, "SELECT * FROM rdm_links WHERE id='$id'");
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $nama_madrasah = $row['nama_madrasah'];
        $url_rdm = $row['url_rdm'];
        $gambar_lama = $row['gambar'] ?? ''; // Safe access
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_madrasah = mysqli_real_escape_string($koneksi, $_POST['nama_madrasah']);
    $url_rdm = mysqli_real_escape_string($koneksi, $_POST['url_rdm']);

    // Handle Image Upload
    $gambar = $gambar_lama;
    if (!empty($_FILES['gambar']['name'])) {
        $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        $filename = 'rdm_' . time() . '.' . $ext;
        $target_dir = '../assets/images/rdm/';

        // Ensure dir exists
        if (!is_dir($target_dir))
            mkdir($target_dir, 0777, true);

        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_dir . $filename)) {
            $gambar = $filename;
            // Delete old image if exists and not default/empty
            if ($action == 'edit' && !empty($gambar_lama) && file_exists($target_dir . $gambar_lama)) {
                unlink($target_dir . $gambar_lama);
            }
        }
    }

    if ($action == 'add') {
        $sql = "INSERT INTO rdm_links (nama_madrasah, url_rdm, gambar) VALUES ('$nama_madrasah', '$url_rdm', '$gambar')";
    } else {
        $sql = "UPDATE rdm_links SET nama_madrasah='$nama_madrasah', url_rdm='$url_rdm', gambar='$gambar' WHERE id='$id'";
    }

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data Link RDM berhasil disimpan!',
                icon: 'success',
                confirmButtonColor: '#059669'
            }).then(() => {
                window.location='rdm.php';
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
        <?= $action == 'add' ? 'Tambah Link RDM' : 'Edit Link RDM' ?></h3>
    <a href="rdm" class="text-gray-600 hover:text-emerald-600 font-medium transition flex items-center">
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
            <div>
                <label class="block text-gray-700 font-bold mb-2">Nama Madrasah</label>
                <input type="text" name="nama_madrasah" value="<?= htmlspecialchars($nama_madrasah) ?>" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                    placeholder="Contoh: MI At-Taufiq">
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">URL / Link RDM</label>
                <div class="relative">
                    <span class="absolute left-4 top-3.5 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1">
                            </path>
                        </svg>
                    </span>
                    <input type="url" name="url_rdm" value="<?= htmlspecialchars($url_rdm) ?>" required
                        class="w-full pl-12 pr-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                        placeholder="https://rdm.mi-attaufiq.sch.id">
                </div>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Thumbnail / Logo (Opsional)</label>
                <?php if (!empty($gambar_lama)): ?>
                    <div class="mb-2">
                        <img src="../assets/images/rdm/<?= $gambar_lama ?>" alt="Current Image"
                            class="h-20 w-auto rounded border">
                    </div>
                <?php endif; ?>
                <input type="file" name="gambar" accept="image/*" class="w-full text-sm text-gray-500
                  file:mr-4 file:py-2 file:px-4
                  file:rounded-full file:border-0
                  file:text-sm file:font-semibold
                  file:bg-emerald-50 file:text-emerald-700
                  hover:file:bg-emerald-100 transition
                " />
                <p class="text-xs text-gray-500 mt-1">Biarkan kosong jika ingin menggunakan avatar otomatis.</p>
            </div>
        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="rdm"
                class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit"
                class="px-8 py-3 rounded-lg bg-emerald-600 text-white font-bold hover:bg-emerald-700 shadow-lg hover:shadow-emerald-500/30 transition transform hover:-translate-y-1">
                Simpan Link
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>