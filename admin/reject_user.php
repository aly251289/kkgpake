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

// Send rejection notification via PushWa
require_once '../includes/notification_helper.php';

if (!empty($d_user['no_hp'])) {
    $msg = "*❌ PENDAFTARAN DITOLAK*\n\n";
    $msg .= "Halo {$d_user['nama_lengkap']},\n\n";
    $msg .= "Mohon maaf, pendaftaran Anda sebagai kontributor untuk *{$d_user['nama_sekolah']}* belum dapat kami setujui saat ini.\n\n";
    $msg .= "Kemungkinan alasan:\n";
    $msg .= "• Data tidak lengkap atau tidak valid\n";
    $msg .= "• NPSN tidak terdaftar di wilayah kami\n";
    $msg .= "• Sudah ada kontributor aktif untuk madrasah tersebut\n\n";
    $msg .= "Silakan hubungi admin untuk informasi lebih lanjut.\n\n";
    $msg .= "Terima kasih atas minat Anda bergabung.";

    sendPushWa($d_user['no_hp'], $msg);
}

// Delete user from database
$del = mysqli_query($koneksi, "DELETE FROM users WHERE id='$user_id'");

if ($del) {
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Ditolak!',
            text: 'Pendaftaran ditolak dan notifikasi WA telah dikirim.',
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