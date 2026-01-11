<?php
error_reporting(E_ALL);
require 'config/koneksi.php';

echo "<h2>Enhanced Image Diagnostics</h2>";
echo "<style>
    table {border-collapse: collapse; width: 100%; margin: 20px 0;}
    th, td {border: 1px solid #ddd; padding: 8px; text-align: left;}
    th {background: #4CAF50; color: white;}
    .exists {color: green; font-weight: bold;}
    .missing {color: red; font-weight: bold;}
    .empty {color: orange; font-style: italic;}
    h3 {margin-top: 30px; color: #333;}
</style>";

// 1. Check all users first
echo "<h3>1. ALL Users in Database</h3>";
$all_users = mysqli_query($koneksi, "SELECT id, username, nama_sekolah, role, logo_sekolah, hero_sekolah FROM users");
echo "<table><tr><th>ID</th><th>Username</th><th>School Name</th><th>Role</th><th>Logo</th><th>Hero</th></tr>";
while ($row = mysqli_fetch_assoc($all_users)) {
    echo "<tr>";
    echo "<td>{$row['id']}</td>";
    echo "<td>{$row['username']}</td>";
    echo "<td>" . ($row['nama_sekolah'] ?: '<em>NULL</em>') . "</td>";
    echo "<td><strong>{$row['role']}</strong></td>";
    echo "<td>" . ($row['logo_sekolah'] ? htmlspecialchars($row['logo_sekolah']) : '<span class="empty">EMPTY</span>') . "</td>";
    echo "<td>" . ($row['hero_sekolah'] ? htmlspecialchars($row['hero_sekolah']) : '<span class="empty">EMPTY</span>') . "</td>";
    echo "</tr>";
}
echo "</table>";

// 2. Check table structure
echo "<h3>2. Users Table Structure</h3>";
$columns = mysqli_query($koneksi, "SHOW COLUMNS FROM users");
echo "<table><tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th></tr>";
while ($col = mysqli_fetch_assoc($columns)) {
    if (strpos($col['Field'], 'sekolah') !== false || strpos($col['Field'], 'kepala') !== false || $col['Field'] == 'logo_sekolah' || $col['Field'] == 'hero_sekolah') {
        echo "<tr style='background: #fffacd;'>";
        echo "<td><strong>{$col['Field']}</strong></td>";
        echo "<td>{$col['Type']}</td>";
        echo "<td>{$col['Null']}</td>";
        echo "<td>" . ($col['Default'] ?: 'NULL') . "</td>";
        echo "</tr>";
    }
}
echo "</table>";

// 3. Check berita table structure
echo "<h3>3. Berita Table Structure</h3>";
$berita_cols = mysqli_query($koneksi, "SHOW COLUMNS FROM berita");
echo "<table><tr><th>Field</th><th>Type</th></tr>";
$has_foto = false;
while ($col = mysqli_fetch_assoc($berita_cols)) {
    echo "<tr>";
    echo "<td>{$col['Field']}</td>";
    echo "<td>{$col['Type']}</td>";
    echo "</tr>";
    if ($col['Field'] == 'foto' || $col['Field'] == 'gambar') {
        $has_foto = true;
    }
}
echo "</table>";
echo "<p><strong>Has 'foto' column:</strong> " . ($has_foto ? "YES" : "NO") . "</p>";

// 4. Sample berita data (if foto column exists)
if ($has_foto) {
    echo "<h3>4. Recent News with Images</h3>";
    $news = mysqli_query($koneksi, "SELECT id, judul, foto, created_by FROM berita ORDER BY id DESC LIMIT 10");
    echo "<table><tr><th>ID</th><th>Title</th><th>Foto Path</th><th>Created By</th></tr>";
    while ($row = mysqli_fetch_assoc($news)) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>" . htmlspecialchars(substr($row['judul'], 0, 50)) . "</td>";
        echo "<td>" . ($row['foto'] ? htmlspecialchars($row['foto']) : '<span class="empty">EMPTY</span>') . "</td>";
        echo "<td>{$row['created_by']}</td>";
        echo "</tr>";
    }
    echo "</table>";
}

// 5. Check actual files in upload directory
echo "<h3>5. Files in assets/img/sekolah/</h3>";
$upload_dir = __DIR__ . '/assets/img/sekolah/';
if (is_dir($upload_dir)) {
    $files = scandir($upload_dir);
    $files = array_diff($files, ['.', '..']);

    if (count($files) > 0) {
        echo "<p>Found " . count($files) . " files:</p>";
        echo "<ul>";
        foreach ($files as $file) {
            $size = filesize($upload_dir . $file);
            echo "<li><strong>{$file}</strong> - " . number_format($size) . " bytes</li>";
        }
        echo "</ul>";
    } else {
        echo "<p class='missing'><strong>Directory is EMPTY!</strong></p>";
    }
} else {
    echo "<p class='missing'><strong>Directory does NOT exist!</strong></p>";
}
?>