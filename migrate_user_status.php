<?php
/**
 * Migration Script: Add status column to users table
 * Run once to add status field for user approval workflow
 */

require_once 'config/koneksi.php';

echo "<h2>Database Migration: Add User Status Column</h2>";

// Check if column already exists
$check_query = "SHOW COLUMNS FROM users LIKE 'status'";
$check_result = mysqli_query($koneksi, $check_query);

if (mysqli_num_rows($check_result) > 0) {
    echo "<p style='color:orange;'>⚠️ Column 'status' already exists. Skipping migration.</p>";
} else {
    // Add status column
    $sql = "ALTER TABLE users ADD COLUMN status ENUM('active', 'pending', 'suspended') DEFAULT 'active' AFTER role";

    if (mysqli_query($koneksi, $sql)) {
        echo "<p style='color:green;'>✅ Successfully added 'status' column to users table.</p>";

        // Update existing users to 'active'
        $update_sql = "UPDATE users SET status = 'active' WHERE status IS NULL";
        mysqli_query($koneksi, $update_sql);
        echo "<p style='color:green;'>✅ Updated existing users to 'active' status.</p>";
    } else {
        echo "<p style='color:red;'>❌ Error: " . mysqli_error($koneksi) . "</p>";
    }
}

// Also add NPSN column if doesn't exist
$check_npsn = "SHOW COLUMNS FROM users LIKE 'npsn'";
$check_npsn_result = mysqli_query($koneksi, $check_npsn);

if (mysqli_num_rows($check_npsn_result) == 0) {
    $add_npsn = "ALTER TABLE users ADD COLUMN npsn VARCHAR(20) DEFAULT NULL AFTER username";
    if (mysqli_query($koneksi, $add_npsn)) {
        echo "<p style='color:green;'>✅ Successfully added 'npsn' column to users table.</p>";
    } else {
        echo "<p style='color:red;'>❌ Error adding NPSN: " . mysqli_error($koneksi) . "</p>";
    }
} else {
    echo "<p style='color:orange;'>⚠️ Column 'npsn' already exists.</p>";
}

echo "<br><strong>Migration completed. You can delete this file: migrate_user_status.php</strong>";
?>