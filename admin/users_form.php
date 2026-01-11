<?php include 'includes/header.php'; ?>
<?php
// Access Check
if (empty($_SESSION['admin_role']) || $_SESSION['admin_role'] != 'admin') {
    echo "<script>window.location='dashboard.php';</script>";
    exit;
}
require_once '../includes/database.php'; // Security: Include the new database helper

$id = '';
$nama = '';
$username = '';
$role = 'contributor'; // Default for new users
$nama_sekolah = '';
$logo_sekolah = '';
$deskripsi_sekolah = '';
$alamat_sekolah = '';
$telepon_sekolah = '';

$action = 'add';

if (isset($_GET['id'])) {
    $action = 'edit';
    $id = $_GET['id'];
    // Security: Use prepared statement to fetch user data
    $query = db_query($koneksi, "SELECT * FROM users WHERE id=?", 'i', [$id]);
    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $nama = $row['nama_lengkap'];
        $username = $row['username'];
        $role = $row['role'];

        // School Profile
        $nama_sekolah = $row['nama_sekolah'];
        $logo_sekolah = $row['logo_sekolah'];
        $deskripsi_sekolah = $row['deskripsi_sekolah'];
        $alamat_sekolah = $row['alamat_sekolah'];
        $telepon_sekolah = $row['telepon_sekolah'];

    } else {
        echo "<script>window.location='users.php';</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Security: No need for mysqli_real_escape_string with prepared statements
    $nama = $_POST['nama'];
    $username_new = $_POST['username'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    // School Data
    $nama_sekolah = $_POST['nama_sekolah'];
    $deskripsi_sekolah = $_POST['deskripsi_sekolah'];
    $alamat_sekolah = $_POST['alamat_sekolah'];
    $telepon_sekolah = $_POST['telepon_sekolah'];

    // Generate Slug
    $slug_sekolah = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nama_sekolah)));

    // Handle Logo Upload
    $logo_path = $logo_sekolah; // Default to existing
    if (isset($_FILES['logo_sekolah']) && $_FILES['logo_sekolah']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['logo_sekolah']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $new_filename = 'school_' . uniqid() . '.' . $ext;
            $target = '../assets/img/logo/' . $new_filename;

            // Check dir
            if (!file_exists('../assets/img/logo/')) {
                mkdir('../assets/img/logo/', 0777, true);
            }

            if (move_uploaded_file($_FILES['logo_sekolah']['tmp_name'], $target)) {
                $logo_path = 'assets/img/logo/' . $new_filename;
                // Delete old logo if exists
                if ($action == 'edit' && $logo_sekolah && file_exists('../' . $logo_sekolah)) {
                    unlink('../' . $logo_sekolah);
                }
            }
        }
    }

    $valid = true;

    // Security: Use prepared statement to check for unique username
    if ($action == 'edit') {
        $check_sql = "SELECT id FROM users WHERE username=? AND id != ?";
        $check = db_query($koneksi, $check_sql, 'si', [$username_new, $id]);
    } else {
        $check_sql = "SELECT id FROM users WHERE username=?";
        $check = db_query($koneksi, $check_sql, 's', [$username_new]);
    }

    if ($check && mysqli_num_rows($check) > 0) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({ title: 'Gagal', text: 'Username sudah digunakan!', icon: 'error' });
        });
        </script>";
        $valid = false;
    }

    if ($valid) {
        if ($action == 'add') {
            if (empty($password)) {
                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({ title: 'Gagal', text: 'Password wajib diisi untuk pengguna baru!', icon: 'warning' });
                });
                </script>";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                // Security: Use prepared statement for INSERT query
                $sql = "INSERT INTO users (nama_lengkap, username, password, role, nama_sekolah, slug_sekolah, logo_sekolah, deskripsi_sekolah, alamat_sekolah, telepon_sekolah) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $params = [
                    $nama, $username_new, $hashed_password, $role, $nama_sekolah,
                    $slug_sekolah, $logo_path, $deskripsi_sekolah, $alamat_sekolah, $telepon_sekolah
                ];
                if (db_query($koneksi, $sql, 'ssssssssss', $params)) {
                    echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({ title: 'Berhasil', text: 'Pengguna berhasil ditambahkan!', icon: 'success' }).then(() => { window.location='users.php'; });
                    });
                    </script>";
                } else {
                    echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({ title: 'Gagal', text: 'Database error: " . mysqli_error($koneksi) . "', icon: 'error' });
                    });
                    </script>";
                }
            }
        } else {
            // Security: Use prepared statement for UPDATE query
            $sql = "UPDATE users SET 
                    nama_lengkap=?, username=?, role=?, nama_sekolah=?, slug_sekolah=?,
                    logo_sekolah=?, deskripsi_sekolah=?, alamat_sekolah=?, telepon_sekolah=?";

            $types = 'sssssssss';
            $params = [
                $nama, $username_new, $role, $nama_sekolah, $slug_sekolah,
                $logo_path, $deskripsi_sekolah, $alamat_sekolah, $telepon_sekolah
            ];

            if (!empty($password)) {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $sql .= ", password=?";
                $types .= 's';
                $params[] = $hashed_password;
            }

            $sql .= " WHERE id=?";
            $types .= 'i';
            $params[] = $id;

            if (db_query($koneksi, $sql, $types, $params)) {
                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({ title: 'Berhasil', text: 'Data pengguna berhasil diperbarui!', icon: 'success' }).then(() => { window.location='users.php'; });
                });
                </script>";
            } else {
                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({ title: 'Gagal', text: 'Database error: " . mysqli_error($koneksi) . "', icon: 'error' });
                });
                </script>";
            }
        }
    }
}
?>

<div class="flex flex-col sm:flex-row justify-between items-center mb-6">
    <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">
        <?= $action == 'add' ? 'Tambah Pengguna Baru' : 'Edit Pengguna' ?>
    </h3>
    <a href="users" class="text-gray-600 hover:text-emerald-600 font-medium transition flex items-center">
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
            <!-- Kolom Kiri: Akun -->
            <div>
                <h4 class="text-lg font-bold text-emerald-600 mb-4 border-b pb-2">Informasi Akun</h4>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Username</label>
                    <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" required
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition bg-gray-50">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Role / Hak Akses</label>
                    <select name="role"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition bg-white">
                        <option value="contributor" <?= $role == 'contributor' ? 'selected' : '' ?>>Kontributor (Admin
                            Madrasah/Lembaga)</option>
                        <option value="admin" <?= $role == 'admin' ? 'selected' : '' ?>>Administrator (Super Admin)
                        </option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Password
                        <?= $action == 'edit' ? '<span class="text-sm font-normal text-gray-500">(Kosongkan jika tetap)</span>' : '' ?></label>
                    <input type="password" name="password" placeholder="Masukan password..." <?= $action == 'add' ? 'required' : '' ?>
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                </div>
            </div>

            <!-- Kolom Kanan: Profil Sekolah (Hanya Relevan untuk Contributor, tapi Admin juga boleh input) -->
            <div>
                <h4 class="text-lg font-bold text-purple-600 mb-4 border-b pb-2">Profil Madrasah / Lembaga</h4>
                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Nama Sekolah / Lembaga</label>
                    <input type="text" name="nama_sekolah" value="<?= htmlspecialchars($nama_sekolah) ?>"
                        placeholder="Contoh: MI Al-Ikhlas"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 transition">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Logo Sekolah</label>
                    <?php if ($logo_sekolah): ?>
                        <div class="mb-2">
                            <img src="../<?= $logo_sekolah ?>"
                                class="h-16 w-auto object-contain bg-gray-50 p-1 border rounded">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="logo_sekolah" accept="image/*"
                        class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 transition text-sm">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Alamat Lengkap</label>
                    <textarea name="alamat_sekolah" rows="2"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 transition"><?= htmlspecialchars($alamat_sekolah) ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">No. Telepon / WA</label>
                    <input type="text" name="telepon_sekolah" value="<?= htmlspecialchars($telepon_sekolah) ?>"
                        placeholder="08..."
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 transition">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Deskripsi Singkat</label>
                    <textarea name="deskripsi_sekolah" rows="3"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 transition"><?= htmlspecialchars($deskripsi_sekolah) ?></textarea>
                </div>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6 border-t pt-6">
            <a href="users"
                class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit"
                class="px-8 py-3 rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold hover:from-emerald-700 hover:to-teal-700 shadow-lg hover:shadow-emerald-500/30 transition transform hover:-translate-y-1">
                Simpan & Update Profil
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>