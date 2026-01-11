<?php include 'includes/header.php'; ?>
<?php
$id = $_SESSION['admin_id'];
$nama = $_SESSION['admin_name'];
$username = '';
$role = $_SESSION['admin_role'];

// Fetch current data
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$id'");
if ($row = mysqli_fetch_assoc($query)) {
    $nama = $row['nama_lengkap'];
    $username = $row['username'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_baru = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username_baru = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password_baru = $_POST['password'];
    $konfirmasi_password = $_POST['konfirmasi_password']; // Optional check

    $valid = true;

    // Check username uniqueness if changed
    if ($username_baru != $username) {
        $check = mysqli_query($koneksi, "SELECT id FROM users WHERE username='$username_baru' AND id != '$id'");
        if (mysqli_num_rows($check) > 0) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ title: 'Gagal', text: 'Username sudah digunakan!', icon: 'error' });
            });
            </script>";
            $valid = false;
        }
    }

    if ($valid) {
        if (!empty($password_baru)) {
            $hashed_password = password_hash($password_baru, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET nama_lengkap='$nama_baru', username='$username_baru', password='$hashed_password' WHERE id='$id'";
        } else {
            $sql = "UPDATE users SET nama_lengkap='$nama_baru', username='$username_baru' WHERE id='$id'";
        }

        if (mysqli_query($koneksi, $sql)) {
            // Update Session
            $_SESSION['admin_name'] = $nama_baru;

            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Berhasil',
                    text: 'Profil berhasil diperbarui!',
                    icon: 'success'
                }).then(() => {
                    window.location='profile.php';
                });
            });
            </script>";
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ title: 'Gagal', text: 'Gagal menyimpan data!', icon: 'error' });
            });
            </script>";
        }
    }
}
?>

<div class="flex flex-col sm:flex-row justify-between items-center mb-6">
    <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">Edit Profil Saya</h3>
</div>

<div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8 max-w-2xl mx-auto">
    <form method="POST">
        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Nama Lengkap</label>
            <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>" required
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Username</label>
            <input type="text" name="username" value="<?= htmlspecialchars($username) ?>" required
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition bg-gray-50">
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 font-bold mb-2">Password Baru <span
                    class="text-sm font-normal text-gray-500">(Kosongkan jika tidak ingin mengubah)</span></label>
            <input type="password" name="password" placeholder="Masukan password baru..."
                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
        </div>

        <div class="mb-8">
            <label class="block text-gray-700 font-bold mb-2">Role / Hak Akses</label>
            <input type="text" value="<?= ucfirst($role) ?>" disabled
                class="w-full px-4 py-3 rounded-lg border border-gray-300 bg-gray-200 text-gray-500 cursor-not-allowed">
            <p class="text-xs text-gray-500 mt-1">Hanya administrator yang bisa mengubah role.</p>
        </div>

        <div class="flex justify-end gap-3">
            <a href="dashboard"
                class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit"
                class="px-8 py-3 rounded-lg bg-emerald-600 text-white font-bold hover:bg-emerald-700 shadow-lg hover:shadow-emerald-500/30 transition transform hover:-translate-y-1">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>