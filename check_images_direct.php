<?php
require 'config/koneksi.php';

// Query untuk user dengan slug mi-raden-fatah-01
$slug = 'mi-raden-fatah-01';
$query = mysqli_query($koneksi, "SELECT * FROM users WHERE slug_sekolah='$slug'");

echo "<style>
    body {font-family: Arial; padding: 20px; background: #f5f5f5;}
    table {border-collapse: collapse; width: 100%; background: white; box-shadow: 0 2px 4px rgba(0,0,0,0.1);}
    th, td {border: 1px solid #ddd; padding: 12px; text-align: left;}
    th {background: #2c3e50; color: white;}
    .null {color: red; font-weight: bold;}
    .has_value {color: green; font-weight: bold;}
    h2 {color: #2c3e50;}
    .highlight {background: #fff3cd; border-left: 4px solid #ffc107;}
</style>";

echo "<h2>Database Check: mi-raden-fatah-01</h2>";

if (mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_assoc($query);

    echo "<h3>Image Fields Status:</h3>";
    echo "<table>";
    echo "<tr><th>Field Name</th><th>DB Value</th><th>Status</th><th>File Check</th></tr>";

    $image_fields = ['logo_sekolah', 'hero_sekolah', 'foto_kepala_sekolah'];

    foreach ($image_fields as $field) {
        $value = $data[$field];
        $is_empty = empty($value);

        $status = $is_empty
            ? '<span class="null">❌ EMPTY/NULL</span>'
            : '<span class="has_value">✅ HAS VALUE</span>';

        $file_check = '';
        if (!$is_empty) {
            $file_path = __DIR__ . '/' . $value;
            $exists = file_exists($file_path);
            $file_check = $exists
                ? '<span class="has_value">✅ File exists</span>'
                : '<span class="null">❌ File NOT found at: ' . htmlspecialchars($file_path) . '</span>';
        }

        $row_class = $is_empty ? 'class="highlight"' : '';

        echo "<tr {$row_class}>";
        echo "<td><strong>{$field}</strong></td>";
        echo "<td>" . ($is_empty ? '<em class="null">NULL/EMPTY</em>' : htmlspecialchars($value)) . "</td>";
        echo "<td>{$status}</td>";
        echo "<td>{$file_check}</td>";
        echo "</tr>";
    }

    echo "</table>";

    // Show all data
    echo "<h3>All Fields (for reference):</h3>";
    echo "<table>";
    echo "<tr><th>Field</th><th>Value</th></tr>";
    foreach ($data as $key => $value) {
        echo "<tr>";
        echo "<td><strong>{$key}</strong></td>";
        echo "<td>" . (is_null($value) || $value === '' ? '<em>NULL/EMPTY</em>' : htmlspecialchars($value)) . "</td>";
        echo "</tr>";
    }
    echo "</table>";

} else {
    echo "<p style='color: red; font-weight: bold;'>❌ NO DATA FOUND for slug: {$slug}</p>";

    // Check all users
    echo "<h3>All users in database:</h3>";
    $all = mysqli_query($koneksi, "SELECT id, username, nama_sekolah, slug_sekolah FROM users");
    echo "<table><tr><th>ID</th><th>Username</th><th>School Name</th><th>Slug</th></tr>";
    while ($row = mysqli_fetch_assoc($all)) {
        echo "<tr><td>{$row['id']}</td><td>{$row['username']}</td><td>{$row['nama_sekolah']}</td><td>{$row['slug_sekolah']}</td></tr>";
    }
    echo "</table>";
}

// Check upload directory
echo "<h3>Files in assets/img/sekolah/:</h3>";
$dir = __DIR__ . '/assets/img/sekolah/';
if (is_dir($dir)) {
    $files = array_diff(scandir($dir), ['.', '..']);
    if (count($files) > 0) {
        echo "<ul>";
        foreach ($files as $file) {
            $size = number_format(filesize($dir . $file));
            echo "<li><strong>{$file}</strong> ({$size} bytes)</li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: orange;'>⚠️ Directory exists but is EMPTY</p>";
    }
} else {
    echo "<p style='color: red;'>❌ Directory does NOT exist!</p>";
}
?>