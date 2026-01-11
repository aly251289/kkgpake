<?php
require_once 'config/koneksi.php';

// Add npsn column
$query1 = "ALTER TABLE users ADD COLUMN npsn VARCHAR(20) AFTER status";
if (mysqli_query($koneksi, $query1)) {
    echo "Column 'npsn' added successfully.\n";
} else {
    echo "Error adding npsn: " . mysqli_error($koneksi) . "\n";
}

// Update role enum
$query2 = "ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'editor', 'contributor') DEFAULT 'contributor'";
if (mysqli_query($koneksi, $query2)) {
    echo "Column 'role' updated successfully.\n";
} else {
    echo "Error updating role: " . mysqli_error($koneksi) . "\n";
}
?>