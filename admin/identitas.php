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
// Self-Healing: Create table if not exists
$check_table = mysqli_query($koneksi, "SHOW TABLES LIKE 'identitas'");
if (mysqli_num_rows($check_table) == 0) {
    $sql_create = "CREATE TABLE identitas (
        id INT PRIMARY KEY,
        nama_website VARCHAR(100) DEFAULT 'KKG MI',
        slogan_header VARCHAR(255) DEFAULT 'Wadah Profesionalisme Guru Madrasah',
        deskripsi_header TEXT,
        foto_hero VARCHAR(255) DEFAULT '',
        email VARCHAR(100) DEFAULT 'info@kkgmi.org',
        no_telp VARCHAR(50) DEFAULT '+62 812 3456 7890',
        alamat TEXT,
        facebook VARCHAR(255) DEFAULT '#',
        instagram VARCHAR(255) DEFAULT '#'
    )";
    mysqli_query($koneksi, $sql_create);

    // Insert default row
    $desc_default = "Membangun sinergi, meningkatkan kompetensi, dan mencetak generasi rabbani yang berprestasi melalui kolaborasi aktif Kelompok Kerja Guru.";
    mysqli_query($koneksi, "INSERT INTO identitas (id, deskripsi_header, alamat) VALUES (1, '$desc_default', 'Jl. Pendidikan No. 123, Kota Contoh')");
}

// Self-Healing Column Check
$d_check = mysqli_query($koneksi, "SELECT * FROM identitas LIMIT 1");
$fields = mysqli_fetch_fields($d_check);
$column_names = [];
foreach ($fields as $field) {
    $column_names[] = $field->name;
}

if (!in_array('file_logo', $column_names)) {
    mysqli_query($koneksi, "ALTER TABLE identitas ADD COLUMN file_logo VARCHAR(255) DEFAULT NULL AFTER foto_hero");
}
if (!in_array('file_favicon', $column_names)) {
    mysqli_query($koneksi, "ALTER TABLE identitas ADD COLUMN file_favicon VARCHAR(255) DEFAULT NULL AFTER file_logo");
}
if (!in_array('maps', $column_names)) {
    mysqli_query($koneksi, "ALTER TABLE identitas ADD COLUMN maps TEXT DEFAULT NULL AFTER instagram");
}
if (!in_array('nama_organisasi', $column_names)) {
    mysqli_query($koneksi, "ALTER TABLE identitas ADD COLUMN nama_organisasi VARCHAR(150) DEFAULT 'Kelompok Kerja Guru Madrasah Ibtidaiyah' AFTER nama_website");
}
if (!in_array('waha_api_url', $column_names)) {
    mysqli_query($koneksi, "ALTER TABLE identitas ADD COLUMN waha_api_url VARCHAR(255) DEFAULT 'http://localhost:3000' AFTER maps");
}
if (!in_array('waha_api_key', $column_names)) {
    mysqli_query($koneksi, "ALTER TABLE identitas ADD COLUMN waha_api_key VARCHAR(255) DEFAULT NULL AFTER waha_api_url");
}

// Handler Update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_website = mysqli_real_escape_string($koneksi, $_POST['nama_website']);
    $nama_organisasi = mysqli_real_escape_string($koneksi, $_POST['nama_organisasi']);
    $slogan = mysqli_real_escape_string($koneksi, $_POST['slogan_header']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi_header']);
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $telp = mysqli_real_escape_string($koneksi, $_POST['no_telp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $fb = mysqli_real_escape_string($koneksi, $_POST['facebook']);
    $ig = mysqli_real_escape_string($koneksi, $_POST['instagram']);

    $update_query = "UPDATE identitas SET 
            nama_website='$nama_website',
            nama_organisasi='$nama_organisasi',
            slogan_header='$slogan',
            deskripsi_header='$deskripsi',
            email='$email',
            no_telp='$telp',
            alamat='$alamat',
            facebook='$fb',
            instagram='$ig',
            maps='" . mysqli_real_escape_string($koneksi, $_POST['maps']) . "',
            admin_wa='" . mysqli_real_escape_string($koneksi, $_POST['admin_wa']) . "',
            pushwa_token='" . mysqli_real_escape_string($koneksi, $_POST['pushwa_token']) . "',
            waha_api_url='" . mysqli_real_escape_string($koneksi, $_POST['waha_api_url']) . "',
            waha_api_key='" . mysqli_real_escape_string($koneksi, $_POST['waha_api_key']) . "'";

    // Helper Upload
    $target_dir = "../assets/images/";
    if (!file_exists($target_dir))
        mkdir($target_dir, 0777, true);

    // Hero Image
    if (isset($_FILES['foto_hero']) && $_FILES['foto_hero']['error'] == 0) {
        $ext = pathinfo($_FILES['foto_hero']['name'], PATHINFO_EXTENSION);
        $filename = "hero_" . time() . "." . $ext;
        if (move_uploaded_file($_FILES['foto_hero']['tmp_name'], $target_dir . $filename)) {
            $update_query .= ", foto_hero='$filename'";
        }
    }

    // Logo
    if (isset($_FILES['file_logo']) && $_FILES['file_logo']['error'] == 0) {
        $ext = pathinfo($_FILES['file_logo']['name'], PATHINFO_EXTENSION);
        $filename = "logo_" . time() . "." . $ext;
        if (move_uploaded_file($_FILES['file_logo']['tmp_name'], $target_dir . $filename)) {
            $update_query .= ", file_logo='$filename'";
        }
    }

    // Favicon
    if (isset($_FILES['file_favicon']) && $_FILES['file_favicon']['error'] == 0) {
        $ext = pathinfo($_FILES['file_favicon']['name'], PATHINFO_EXTENSION);
        $filename = "favicon_" . time() . "." . $ext;
        if (move_uploaded_file($_FILES['file_favicon']['tmp_name'], $target_dir . $filename)) {
            $update_query .= ", file_favicon='$filename'";
        }
    }

    $update_query .= " WHERE id=1";

    if (mysqli_query($koneksi, $update_query)) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Disimpan!',
                    text: 'Perubahan berhasil disimpan!',
                    icon: 'success',
                    confirmButtonColor: '#059669'
                }).then(() => {
                    window.location='identitas.php';
                });
            });
            </script>";
    } else {
        // Debug err
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Gagal',
                    text: 'Gagal: ' + " . json_encode(mysqli_error($koneksi)) . ",
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            });
            </script>";
    }
}

// Fetch Data
$query = mysqli_query($koneksi, "SELECT * FROM identitas WHERE id=1");
$data = mysqli_fetch_assoc($query);
?>

<div class="mb-6">
    <h3 class="text-2xl font-bold text-gray-800">Identitas Website</h3>
    <p class="text-gray-600">Atur informasi utama website, logo, judul, dan kontak.</p>
</div>

<div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
    <form method="POST" enctype="multipart/form-data">
        <!-- General Info -->
        <h4 class="text-lg font-bold text-emerald-600 mb-4 border-b border-gray-100 pb-2">Informasi Umum</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Nama Website (Logo Text)</label>
                <input type="text" name="nama_website" value="<?= $data['nama_website'] ?>" required
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 bg-gray-50">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Nama Organisasi (Formal)</label>
                <input type="text" name="nama_organisasi" value="<?= $data['nama_organisasi'] ?? '' ?>"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 bg-gray-50">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Slogan Header</label>
                <input type="text" name="slogan_header" value="<?= $data['slogan_header'] ?>"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 bg-gray-50">
            </div>
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Deskripsi Header</label>
                <textarea name="deskripsi_header" rows="3"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 bg-gray-50"><?= $data['deskripsi_header'] ?></textarea>
            </div>
        </div>

        <!-- Branding Images -->
        <h4 class="text-lg font-bold text-emerald-600 mb-4 border-b border-gray-100 pb-2">Branding (Logo & Icon)</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Logo Upload -->
            <div>
                <label class="block text-gray-700 font-bold mb-2">Logo Website</label>
                <input type="file" name="file_logo"
                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-xs text-gray-500 mt-1">Format PNG transparan disarankan.</p>
            </div>
            <div class="flex items-center">
                <?php if (!empty($data['file_logo'])): ?>
                    <div class="bg-gray-100 p-2 rounded border">
                        <img src="../assets/images/<?= $data['file_logo'] ?>" class="h-12 object-contain">
                    </div>
                <?php else: ?>
                    <span class="text-gray-400 italic text-sm">Belum ada logo uploaded.</span>
                <?php endif; ?>
            </div>

            <!-- Favicon Upload -->
            <div>
                <label class="block text-gray-700 font-bold mb-2">Icon Browser (Favicon)</label>
                <input type="file" name="file_favicon"
                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-xs text-gray-500 mt-1">Format ICO/PNG ukuran kecil (32x32px).</p>
            </div>
            <div class="flex items-center">
                <?php if (!empty($data['file_favicon'])): ?>
                    <div class="bg-gray-100 p-2 rounded border">
                        <img src="../assets/images/<?= $data['file_favicon'] ?>" class="w-8 h-8 object-contain">
                    </div>
                <?php else: ?>
                    <span class="text-gray-400 italic text-sm">Belum ada favion.</span>
                <?php endif; ?>
            </div>
        </div>

        <!-- Hero Image -->
        <h4 class="text-lg font-bold text-emerald-600 mb-4 border-b border-gray-100 pb-2">Tampilan Depan (Hero Image)
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Upload Foto Baru</label>
                <input type="file" name="foto_hero"
                    class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100">
                <p class="text-xs text-gray-500 mt-1">Disarankan ukuran 800x600px atau rasio landscape.</p>
            </div>
            <div>
                <?php if (!empty($data['foto_hero'])): ?>
                    <img src="../assets/images/<?= $data['foto_hero'] ?>"
                        class="h-32 rounded-lg shadow-sm border object-cover">
                <?php else: ?>
                    <div class="h-32 w-full bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">Default
                        (Unsplash)</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Contact Info -->
        <h4 class="text-lg font-bold text-emerald-600 mb-4 border-b border-gray-100 pb-2">Kontak & Sosial Media</h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Email</label>
                <input type="email" name="email" value="<?= $data['email'] ?>"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 bg-gray-50">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">No. Telepon / WA</label>
                <input type="text" name="no_telp" value="<?= $data['no_telp'] ?>"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 bg-gray-50">
            </div>
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Alamat Lengkap</label>
                <textarea name="alamat" rows="2"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 bg-gray-50"><?= $data['alamat'] ?></textarea>
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Link Facebook</label>
                <input type="text" name="facebook" value="<?= $data['facebook'] ?>" placeholder="#"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 bg-gray-50">
            </div>
            <div>
                <label class="block text-gray-700 font-bold mb-2">Link Instagram</label>
                <input type="text" name="instagram" value="<?= $data['instagram'] ?>" placeholder="#"
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 bg-gray-50">
            </div>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Google Maps Embed Code (HTML)</label>
            <textarea name="maps" rows="4"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:ring-2 focus:ring-emerald-500 bg-gray-50 placeholder-gray-400"
                placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'><?= $data['maps'] ?? '' ?></textarea>
            <p class="text-xs text-slate-500 mt-1">Copy "Embed a map" HTML dari Google Maps dan paste di sini.</p>
        </div>

        <!-- Notification Config -->
        <h4 class="text-lg font-bold text-emerald-600 mb-4 border-b border-gray-100 pb-2 mt-8">Konfigurasi PushWa.com
        </h4>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Waha (Self-Hosted) Config -->
            <div class="bg-emerald-50 p-4 rounded-lg border border-emerald-100 md:col-span-2">
                <div class="mb-3">
                    <label class="block text-gray-700 font-bold mb-1 text-sm">Waha API URL (WhatsApp API)</label>
                    <input type="text" name="waha_api_url"
                        value="<?= $data['waha_api_url'] ?? 'http://localhost:3000' ?>"
                        placeholder="https://waha.domain.com"
                        class="w-full px-3 py-2 rounded border border-emerald-300 text-sm">
                    <p class="text-xs text-slate-500 mt-1">Masukkan URL Waha Docker Anda (contoh:
                        https://waha.mirafa01.web.id/ atau http://localhost:3000)</p>
                </div>
                <div class="mb-3">
                    <label class="block text-gray-700 font-bold mb-1 text-sm">Waha API Key (Secret Key)</label>
                    <input type="text" name="waha_api_key" value="<?= $data['waha_api_key'] ?? '' ?>"
                        placeholder="Masukkan API Key Waha..."
                        class="w-full px-3 py-2 rounded border border-emerald-300 text-sm">
                    <p class="text-xs text-slate-500 mt-1">Biarkan kosong jika tidak menggunakan API Key.</p>
                </div>
            </div>

            <!-- PushWa Config -->
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100 md:col-span-2 opacity-75">
                <div class="mb-3">
                    <label class="block text-gray-700 font-bold mb-1 text-sm">Token PushWa</label>
                    <input type="text" name="pushwa_token" value="<?= $data['pushwa_token'] ?? '' ?>"
                        placeholder="Paste Token PushWa disini..."
                        class="w-full px-3 py-2 rounded border border-blue-300 text-sm">
                    <p class="text-xs text-slate-500 mt-1">Dapatkan token di <a href="https://pushwa.com"
                            target="_blank" class="text-blue-600 hover:underline">PushWa.com</a></p>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-1 text-sm">Nomor Admin (Penerima Notifikasi)</label>
                    <input type="text" name="admin_wa" value="<?= $data['admin_wa'] ?? '' ?>" placeholder="08xxxx"
                        class="w-full px-3 py-2 rounded border border-blue-300 text-sm">
                </div>
            </div>
        </div>
</div>

<div class="flex justify-end pt-4">
    <button type="submit"
        class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg shadow-lg transform hover:-translate-y-1 transition-all">
        Simpan Perubahan
    </button>
</div>
</form>
</div>

<?php include 'includes/footer.php'; ?>