<?php
session_start();
require_once '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../register');
    exit;
}

// Get form data (NO PASSWORD - will be generated upon approval)
$nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
$username = mysqli_real_escape_string($koneksi, $_POST['username']);
$no_hp = mysqli_real_escape_string($koneksi, $_POST['no_hp']);
$npsn = mysqli_real_escape_string($koneksi, $_POST['npsn']);
$nama_sekolah = mysqli_real_escape_string($koneksi, $_POST['nama_sekolah']);

// Validation
$errors = [];

// Validate username (min 4 chars, alphanumeric + underscore)
if (strlen($username) < 4) {
    $errors[] = 'Username minimal 4 karakter';
}

if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
    $errors[] = 'Username hanya boleh huruf, angka, dan underscore';
}

// Check username uniqueness
$check_username = mysqli_query($koneksi, "SELECT id FROM users WHERE username='$username'");
if (mysqli_num_rows($check_username) > 0) {
    $errors[] = 'Username sudah digunakan';
}

// Validate NPSN
if (empty($npsn)) {
    $errors[] = 'NPSN wajib diisi';
}

// Validate nama sekolah
if (empty($nama_sekolah)) {
    $errors[] = 'Nama sekolah wajib diisi';
}

// Validate No HP
if (empty($no_hp) || !preg_match('/^[0-9]+$/', $no_hp)) {
    $errors[] = 'Nomor WhatsApp wajib diisi angka';
}

// If there are errors
if (!empty($errors)) {
    $_SESSION['register_errors'] = $errors;
    $_SESSION['register_form_data'] = $_POST;
    header('Location: ../register');
    exit;
}

// Placeholder password (will be replaced upon approval)
$placeholder_password = password_hash('PENDING_APPROVAL', PASSWORD_DEFAULT);

// Generate slug
$slug_sekolah = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $nama_sekolah)));

// Insert new user with status 'pending'
$sql = "INSERT INTO users (
    nama_lengkap, 
    username, 
    password, 
    no_hp,
    role, 
    status, 
    npsn,
    nama_sekolah, 
    slug_sekolah
) VALUES (
    '$nama_lengkap',
    '$username',
    '$placeholder_password',
    '$no_hp',
    'contributor',
    'pending',
    '$npsn',
    '$nama_sekolah',
    '$slug_sekolah'
)";

if (mysqli_query($koneksi, $sql)) {
    // Notify Admin via WA
    require_once '../includes/notification_helper.php';

    $msg = "*🔔 PENDAFTARAN BARU*\n\n";
    $msg .= "Ada user baru mendaftar sebagai kontributor:\n";
    $msg .= "Nama: $nama_lengkap\n";
    $msg .= "Sekolah: $nama_sekolah\n";
    $msg .= "WA: $no_hp\n\n";
    $msg .= "Silakan login ke admin panel untuk menyetujui.";

    notifyAdmin($msg);

    $_SESSION['register_success'] = true;
    header('Location: ../register_success');
} else {
    $_SESSION['register_errors'] = ['Terjadi kesalahan database: ' . mysqli_error($koneksi)];
    $_SESSION['register_form_data'] = $_POST;
    header('Location: ../register');
}
?>