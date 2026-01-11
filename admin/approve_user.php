<?php
session_start();
require_once '../config/koneksi.php';

// Admin check
if (empty($_SESSION['admin_role']) || $_SESSION['admin_role'] != 'admin') {
    header('Location: dashboard');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: users');
    exit;
}

$user_id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Fetch user data for notification
$q_user = mysqli_query($koneksi, "SELECT * FROM users WHERE id='$user_id'");
$d_user = mysqli_fetch_assoc($q_user);

if (!$d_user) {
    header('Location: users');
    exit;
}

// Generate new random password
$new_password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 8);
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

// Update user status to active AND set new password
$sql = "UPDATE users SET status = 'active', password = '$hashed_password' WHERE id = '$user_id'";

if (mysqli_query($koneksi, $sql)) {
    // Notify User via WA
    require_once '../includes/notification_helper.php';

    if (!empty($d_user['no_hp'])) {
        $login_url = url('login');
        $msg = "*✅ AKUN DISETUJUI*\n\n";
        $msg .= "Halo {$d_user['nama_lengkap']},\n";
        $msg .= "Akun kontributor Anda untuk *{$d_user['nama_sekolah']}* telah disetujui.\n\n";
        $msg .= "Berikut data login Anda:\n";
        $msg .= "👤 Username: *{$d_user['username']}*\n";
        $msg .= "🔑 Password: *{$new_password}*\n";
        $msg .= "🌐 Login: $login_url\n\n";
        $msg .= "⚠️ Segera ganti password setelah login pertama.\n\n";
        $msg .= "Silakan login dan lengkapi data madrasah Anda.";

        sendPushWa($d_user['no_hp'], $msg);
    }

    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Berhasil!',
            text: 'User disetujui! Password baru telah dikirim via WA.',
            icon: 'success'
        }).then(() => {
            window.location = 'users';
        });
    });
    </script>";

    include 'includes/header.php';
} else {
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Gagal!',
            text: 'Terjadi kesalahan: " . mysqli_error($koneksi) . "',
            icon: 'error'
        }).then(() => {
            window.location = 'users';
        });
    });
    </script>";

    include 'includes/header.php';
}
?>