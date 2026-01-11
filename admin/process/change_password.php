<?php
session_start();
require_once '../../config/koneksi.php';
require_once '../../includes/database.php'; // Security: Include the new database helper

// Security check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../change_password');
    exit;
}

$user_id = $_SESSION['admin_id'];
$old_password = $_POST['old_password'];
$new_password = $_POST['new_password'];
$confirm_password = $_POST['confirm_password'];

// Validate
if (strlen($new_password) < 6) {
    $_SESSION['password_error'] = 'Password baru minimal 6 karakter';
    header('Location: ../change_password');
    exit;
}

if ($new_password !== $confirm_password) {
    $_SESSION['password_error'] = 'Password baru dan konfirmasi tidak cocok';
    header('Location: ../change_password');
    exit;
}

// Security: Use prepared statement to check old password
$query = db_query($koneksi, "SELECT password FROM users WHERE id=?", 'i', [$user_id]);
$user = mysqli_fetch_assoc($query);

if (!$user || !password_verify($old_password, $user['password'])) {
    $_SESSION['password_error'] = 'Password lama salah';
    header('Location: ../change_password');
    exit;
}

// Security: Use prepared statement to update password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
$update = db_query($koneksi, "UPDATE users SET password=?, password_changed=1 WHERE id=?", 'si', [$hashed_password, $user_id]);

if ($update) {
    $_SESSION['password_success'] = true;
    header('Location: ../dashboard');
} else {
    $_SESSION['password_error'] = 'Terjadi kesalahan: ' . mysqli_error($koneksi);
    header('Location: ../change_password');
}
?>