<?php
/**
 * Script untuk memperbaiki kolom yang terbalik di tabel guru
 * Swap nama kolom: asal_madrasah <-> jabatan
 */

require_once 'config/koneksi.php';

echo "<h2>Fix Database - Swap Kolom asal_madrasah dan jabatan</h2>";

// Step 1: Rename asal_madrasah -> temp_asal
$sql1 = "ALTER TABLE guru CHANGE `asal_madrasah` `temp_asal` VARCHAR(255)";
$result1 = mysqli_query($koneksi, $sql1);
echo $result1 ? "✅ Step 1: asal_madrasah -> temp_asal<br>" : "❌ Step 1 Error: " . mysqli_error($koneksi) . "<br>";

// Step 2: Rename jabatan -> asal_madrasah
$sql2 = "ALTER TABLE guru CHANGE `jabatan` `asal_madrasah` VARCHAR(255)";
$result2 = mysqli_query($koneksi, $sql2);
echo $result2 ? "✅ Step 2: jabatan -> asal_madrasah<br>" : "❌ Step 2 Error: " . mysqli_error($koneksi) . "<br>";

// Step 3: Rename temp_asal -> jabatan
$sql3 = "ALTER TABLE guru CHANGE `temp_asal` `jabatan` VARCHAR(255)";
$result3 = mysqli_query($koneksi, $sql3);
echo $result3 ? "✅ Step 3: temp_asal -> jabatan<br>" : "❌ Step 3 Error: " . mysqli_error($koneksi) . "<br>";

if ($result1 && $result2 && $result3) {
    echo "<br><strong style='color:green;'>✅ Berhasil! Kolom sudah di-swap dengan benar.</strong><br>";
    echo "<br><strong>Silakan hapus file ini setelah selesai: fix_guru_columns.php</strong>";
} else {
    echo "<br><strong style='color:red;'>❌ Ada error. Silakan cek manual.</strong>";
}
?>