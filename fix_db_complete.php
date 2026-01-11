<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'config/koneksi.php';

echo "<h2>Fixing Database Columns...</h2>";

$columns = [
    'judul_website' => "VARCHAR(100) DEFAULT NULL",
    'deskripsi_sekolah' => "TEXT DEFAULT NULL",
    'alamat_sekolah' => "TEXT DEFAULT NULL",
    'telepon_sekolah' => "VARCHAR(20) DEFAULT NULL",
    'email_sekolah' => "VARCHAR(100) DEFAULT NULL",
    'website_sekolah' => "VARCHAR(100) DEFAULT NULL",
    'logo_sekolah' => "VARCHAR(255) DEFAULT NULL"
];

foreach ($columns as $col => $def) {
    $check = mysqli_query($koneksi, "SHOW COLUMNS FROM users LIKE '$col'");
    if (mysqli_num_rows($check) == 0) {
        echo "Adding column <b>$col</b>... ";
        $sql = "ALTER TABLE users ADD COLUMN $col $def";
        if (mysqli_query($koneksi, $sql)) {
            echo "<span style='color:green'>SUCCESS</span><br>";
        } else {
            echo "<span style='color:red'>ERROR: " . mysqli_error($koneksi) . "</span><br>";
        }
    } else {
        echo "Column <b>$col</b> already exists.<br>";
    }
}

echo "<br>Done. <a href='admin/madrasah.php'>Try Saving Again</a>";
?>