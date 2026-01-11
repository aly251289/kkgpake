<?php
require_once 'config/koneksi.php';

// Add status column
$query = "ALTER TABLE users ADD COLUMN status ENUM('active', 'pending', 'suspended') DEFAULT 'active' AFTER role";
if (mysqli_query($koneksi, $query)) {
    echo "Column 'status' added successfully.";
} else {
    echo "Error adding column: " . mysqli_error($koneksi);
}
?>