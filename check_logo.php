<?php
require 'config/koneksi.php';
// Cek ID 2 (sesuai error log user sebelumnya)
$id = 2;
$query = mysqli_query($koneksi, "SELECT logo_sekolah FROM users WHERE id='$id'");
if ($row = mysqli_fetch_assoc($query)) {
    echo "DB Value: [" . $row['logo_sekolah'] . "]\n";
    $fullpath = "c:/xampp/htdocs/kkgpaket/" . $row['logo_sekolah'];
    echo "Full Path: " . $fullpath . "\n";
    echo "File Exists: " . (file_exists($fullpath) ? "YES" : "NO") . "\n";
} else {
    echo "User ID $id not found.\n";
}
?>