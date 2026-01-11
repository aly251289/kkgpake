<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'includes/header.php';
?>
<?php
// Only for contributor role
if (empty($_SESSION['admin_role']) || strtolower($_SESSION['admin_role']) != 'contributor') {
    echo "<script>window.location='dashboard.php';</script>";
    exit;
}

$id = $_SESSION['admin_id'];

// Check and Add 'judul_website' column if not exists
$check_col = mysqli_query($koneksi, "SHOW COLUMNS FROM users LIKE 'judul_website'");
if (mysqli_num_rows($check_col) == 0) {
    if (!mysqli_query($koneksi, "ALTER TABLE users ADD COLUMN judul_website VARCHAR(100) DEFAULT NULL AFTER nama_sekolah")) {
        die("Gagal menambahkan kolom database: " . mysqli_error($koneksi));
    }
}

// Fetch current data (refresh after alter)
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

// Safe default if data not found
if (!$data) {
    $data = [];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_sekolah = mysqli_real_escape_string($koneksi, $_POST['nama_sekolah']);
    $judul_website = mysqli_real_escape_string($koneksi, $_POST['judul_website']); // New Field
    $slug_sekolah = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nama_sekolah)));
    $deskripsi_sekolah = mysqli_real_escape_string($koneksi, $_POST['deskripsi_sekolah']);
    $alamat_sekolah = mysqli_real_escape_string($koneksi, $_POST['alamat_sekolah']);
    $telepon_sekolah = mysqli_real_escape_string($koneksi, $_POST['telepon_sekolah']);
    $email_sekolah = mysqli_real_escape_string($koneksi, $_POST['email_sekolah']);
    $website_sekolah = mysqli_real_escape_string($koneksi, $_POST['website_sekolah']);

    $update_query = "UPDATE users SET 
                        nama_sekolah='$nama_sekolah',
                        judul_website='$judul_website',
                        slug_sekolah='$slug_sekolah',
                        deskripsi_sekolah='$deskripsi_sekolah',
                        alamat_sekolah='$alamat_sekolah',
                        telepon_sekolah='$telepon_sekolah',
                        email_sekolah='$email_sekolah',
                        website_sekolah='$website_sekolah',
                        nama_kepala_sekolah='" . mysqli_real_escape_string($koneksi, $_POST['nama_kepala_sekolah'] ?? '') . "',
                        sambutan_kepala_sekolah='" . mysqli_real_escape_string($koneksi, $_POST['sambutan_kepala_sekolah'] ?? '') . "'";

    // -- Logic Upload Logo --
    $logo_db = $data['logo_sekolah'] ?? '';
    if (isset($_FILES['logo_sekolah']) && $_FILES['logo_sekolah']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $ext = strtolower(pathinfo($_FILES['logo_sekolah']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $target_dir = "../assets/img/sekolah/";
            if (!file_exists($target_dir))
                mkdir($target_dir, 0777, true);

            $filename = "logo_" . $id . "_" . time() . "." . $ext;
            $target_file = $target_dir . $filename;

            if (move_uploaded_file($_FILES['logo_sekolah']['tmp_name'], $target_file)) {
                // Delete old logo
                if (!empty($data['logo_sekolah']) && file_exists("../" . $data['logo_sekolah'])) {
                    unlink("../" . $data['logo_sekolah']);
                }
                $logo_path = "assets/img/sekolah/" . $filename;
                $update_query .= ", logo_sekolah='$logo_path'";
            }
        }
    }



    // -- Logic Upload Hero (Sampul) --
    $hero_db = $data['hero_sekolah'] ?? '';
    if (isset($_FILES['hero_sekolah']) && $_FILES['hero_sekolah']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['hero_sekolah']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $target_dir = "../assets/img/sekolah/";
            if (!file_exists($target_dir))
                mkdir($target_dir, 0777, true);

            $filename = "hero_" . $id . "_" . time() . "." . $ext;
            $target_file = $target_dir . $filename;

            if (move_uploaded_file($_FILES['hero_sekolah']['tmp_name'], $target_file)) {
                // Hapus hero lama
                if (!empty($hero_db) && file_exists("../" . $hero_db)) {
                    unlink("../" . $hero_db);
                }
                $hero_db = "assets/img/sekolah/" . $filename;
            }
        }
    }

    $update_query .= ", hero_sekolah='$hero_db'";

    // -- Logic Upload Foto Kepala Sekolah --
    $kepala_db = $data['foto_kepala_sekolah'] ?? '';
    if (isset($_FILES['foto_kepala_sekolah']) && $_FILES['foto_kepala_sekolah']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['foto_kepala_sekolah']['name'], PATHINFO_EXTENSION));

        if (in_array($ext, $allowed)) {
            $target_dir = "../assets/img/sekolah/";
            if (!file_exists($target_dir))
                mkdir($target_dir, 0777, true);

            $filename = "kepala_" . $id . "_" . time() . "." . $ext;
            $target_file = $target_dir . $filename;

            if (move_uploaded_file($_FILES['foto_kepala_sekolah']['tmp_name'], $target_file)) {
                if (!empty($kepala_db) && file_exists("../" . $kepala_db)) {
                    unlink("../" . $kepala_db);
                }
                $kepala_path = "assets/img/sekolah/" . $filename;
                $update_query .= ", foto_kepala_sekolah='$kepala_path'";
            }
        }
    }

    $update_query .= " WHERE id='$id'";

    if (mysqli_query($koneksi, $update_query)) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Pengaturan madrasah berhasil disimpan!',
                icon: 'success',
                confirmButtonColor: '#059669'
            }).then(() => {
                window.location='madrasah.php';
            });
        });
        </script>";
    } else {
        $error_msg = mysqli_error($koneksi);
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan: " . addslashes($error_msg) . "',
                icon: 'error',
                confirmButtonColor: '#d33'
            });
        });
        </script>";
    }
}
?>

<div class="mb-6">
    <h3 class="text-2xl font-bold text-gray-800">Pengaturan Madrasah</h3>
    <p class="text-gray-600">Kelola identitas dan informasi website madrasah Anda.</p>
</div>

<!-- Preview Link -->
<?php if (!empty($data['slug_sekolah'])): ?>
    <div
        class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-xl p-4 mb-6 border border-emerald-100 flex items-center justify-between">
        <div>
            <p class="text-sm text-emerald-700 font-medium">Link Website Madrasah Anda:</p>
            <a href="../mi/<?= $data['slug_sekolah'] ?>" target="_blank"
                class="text-emerald-600 hover:text-emerald-800 font-bold text-lg">
                <?= $_SERVER['HTTP_HOST'] ?>/mi/
                <?= $data['slug_sekolah'] ?>
            </a>
        </div>
        <a href="../mi/<?= $data['slug_sekolah'] ?>" target="_blank"
            class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
            </svg>
            Lihat Website
        </a>
    </div>
<?php else: ?>
    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-yellow-700">
                    Silakan lengkapi data profil madrasah Anda terlebih dahulu untuk mengaktifkan halaman website madrasah.
                </p>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8">
    <form method="POST" enctype="multipart/form-data">

        <!-- Identitas Section -->
        <h4 class="text-lg font-bold text-emerald-600 mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                </path>
            </svg>
            Identitas Madrasah
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div>
                <label class="block text-gray-700 font-bold mb-2">Nama Madrasah <span
                        class="text-red-500">*</span></label>
                <input type="text" name="nama_sekolah" value="<?= htmlspecialchars($data['nama_sekolah'] ?? '') ?>"
                    readonly placeholder="Contoh: MI Al-Hidayah"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-100 text-gray-500 cursor-not-allowed focus:outline-none transition">
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Nama Website <span
                        class="text-gray-400 font-normal text-xs">(Judul di Header)</span></label>
                <input type="text" name="judul_website" value="<?= htmlspecialchars($data['judul_website'] ?? '') ?>"
                    placeholder="Contoh: Portal Resmi MI Al-Hidayah"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                <p class="text-xs text-gray-500 mt-1">Jika dikosongkan, akan menggunakan Nama Madrasah.</p>
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Deskripsi Singkat</label>
                <textarea name="deskripsi_sekolah" rows="3"
                    placeholder="Jelaskan visi misi atau deskripsi singkat madrasah Anda..."
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition resize-none"><?= htmlspecialchars($data['deskripsi_sekolah'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- Logo Section -->
        <h4 class="text-lg font-bold text-emerald-600 mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                </path>
            </svg>
            Logo Madrasah
        </h4>
        <div class="mb-8">
            <div class="flex items-start gap-6">
                <!-- Current Logo Preview -->
                <div class="flex-shrink-0">
                    <?php if (!empty($data['logo_sekolah'])): ?>
                        <img src="../<?= $data['logo_sekolah'] ?>" alt="Logo"
                            class="w-32 h-32 object-contain border border-gray-200 rounded-xl bg-gray-50 p-2">
                    <?php else: ?>
                        <div
                            class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center bg-gray-50">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Upload Input -->
                <div class="flex-1">
                    <label class="block text-gray-700 font-bold mb-2">Upload Logo Baru</label>
                    <input type="file" name="logo_sekolah" accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, GIF, WebP. Ukuran maksimal: 2MB. Rasio 1:1
                        disarankan.</p>
                </div>
            </div>
        </div>



        <!-- Hero Image Section -->
        <h4 class="text-lg font-bold text-emerald-600 mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                </path>
            </svg>
            Sampul Header (Hero Image)
        </h4>
        <div class="mb-8">
            <div class="flex items-start gap-6">
                <!-- Current Hero Preview -->
                <div class="flex-shrink-0">
                    <?php if (!empty($data['hero_sekolah'])): ?>
                        <img src="../<?= $data['hero_sekolah'] ?>" alt="Hero"
                            class="w-48 h-24 object-cover border border-gray-200 rounded-xl bg-gray-50">
                    <?php else: ?>
                        <div
                            class="w-48 h-24 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center bg-gray-50">
                            <span class="text-xs text-gray-400">Belum ada sampul</span>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Upload Input -->
                <div class="flex-1">
                    <label class="block text-gray-700 font-bold mb-2">Upload Sampul Baru</label>
                    <input type="file" name="hero_sekolah" accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    <p class="text-xs text-gray-500 mt-2">Disarankan gambar landscape lebar. Format: JPG, PNG. Maks:
                        2MB.</p>
                </div>
            </div>
        </div>

        <!-- Kontak Section -->
        <h4 class="text-lg font-bold text-emerald-600 mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                </path>
            </svg>
            Kontak & Alamat
        </h4>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Alamat Lengkap</label>
                <textarea name="alamat_sekolah" rows="2" placeholder="Jl. Pendidikan No. 123, Kota..."
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition resize-none"><?= htmlspecialchars($data['alamat_sekolah'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Nomor Telepon</label>
                <input type="text" name="telepon_sekolah"
                    value="<?= htmlspecialchars($data['telepon_sekolah'] ?? '') ?>" placeholder="08123456789"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <div>
                <label class="block text-gray-700 font-bold mb-2">Email</label>
                <input type="email" name="email_sekolah" value="<?= htmlspecialchars($data['email_sekolah'] ?? '') ?>"
                    placeholder="info@madrasah.sch.id"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <div class="md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Website (Opsional)</label>
                <input type="url" name="website_sekolah" value="<?= htmlspecialchars($data['website_sekolah'] ?? '') ?>"
                    placeholder="https://madrasah.sch.id"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>
        </div>

</div>

<!-- Kepala Madrasah Section -->
<h4 class="text-lg font-bold text-emerald-600 mb-4 border-b border-gray-100 pb-2 flex items-center gap-2">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
    </svg>
    Kepala Madrasah & Sambutan
</h4>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="md:col-span-1">
        <label class="block text-gray-700 font-bold mb-2">Foto Kepala Madrasah</label>
        <div class="mb-3">
            <?php if (!empty($data['foto_kepala_sekolah'])): ?>
                <img src="../<?= $data['foto_kepala_sekolah'] ?>" alt="Foto Kepala"
                    class="w-full h-48 object-cover object-top border border-gray-200 rounded-xl bg-gray-50">
            <?php else: ?>
                <div
                    class="w-full h-48 border-2 border-dashed border-gray-300 rounded-xl flex items-center justify-center bg-gray-50 p-4 text-center">
                    <span class="text-xs text-gray-400">Belum ada foto</span>
                </div>
            <?php endif; ?>
        </div>
        <input type="file" name="foto_kepala_sekolah" accept="image/*"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        <p class="text-xs text-gray-500 mt-1">Format: JPG/PNG. Rasio Portrait (3:4) disarankan.</p>
    </div>

    <div class="md:col-span-2 space-y-4">
        <div>
            <label class="block text-gray-700 font-bold mb-2">Nama Kepala Madrasah</label>
            <input type="text" name="nama_kepala_sekolah"
                value="<?= htmlspecialchars($data['nama_kepala_sekolah'] ?? '') ?>"
                placeholder="Contoh: Drs. H. Ahmad Fauzi, M.Pd"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
        </div>

        <div>
            <label class="block text-gray-700 font-bold mb-2">Isi Sambutan</label>
            <textarea name="sambutan_kepala_sekolah" rows="8"
                placeholder="Tuliskan kata sambutan kepala madrasah di sini..."
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition resize-y"><?= htmlspecialchars($data['sambutan_kepala_sekolah'] ?? '') ?></textarea>
        </div>
    </div>
</div>

<!-- Submit -->
<div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
    <a href="dashboard"
        class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
        Batal
    </a>
    <button type="submit"
        class="px-8 py-3 rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold hover:from-emerald-700 hover:to-teal-700 shadow-lg hover:shadow-emerald-500/30 transition transform hover:-translate-y-1">
        Simpan Pengaturan
    </button>
</div>
</form>
</div>

<?php include 'includes/footer.php'; ?>