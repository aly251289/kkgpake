<?php
require 'config/koneksi.php';
$id = 2;
// Reset logo path to NULL or empty string
if (mysqli_query($koneksi, "UPDATE users SET logo_sekolah='' WHERE id='$id'")) {
    echo "Logo path cleared in DB.";
} else {
    echo "Error: " . mysqli_error($koneksi);
}
?>