<?php
// Quick test to see what init_sekolah.php is loading
require 'includes/init_sekolah.php';

echo "<h2>School Data Debug</h2>";
echo "<style>
    table {border-collapse: collapse; width: 100%; margin: 20px 0;}
    th, td {border: 1px solid #ddd; padding: 12px; text-align: left; vertical-align: top;}
    th {background: #4CAF50; color: white;}
    .good {background: #d4edda; color: #155724;}
    .bad {background: #f8d7da; color: #721c24;}
    pre {background: #f5f5f5; padding: 10px; border-radius: 4px; overflow-x: auto;}
</style>";

echo "<h3>Current School Data from \$school variable:</h3>";
if (isset($school) && is_array($school)) {
    echo "<table>";
    echo "<tr><th>Field</th><th>Value</th><th>Status</th></tr>";

    $image_fields = ['logo_sekolah', 'hero_sekolah', 'foto_kepala_sekolah'];

    foreach ($school as $key => $value) {
        $status = '';
        if (in_array($key, $image_fields)) {
            if (empty($value)) {
                $status = '<span class="bad">EMPTY - No path stored</span>';
            } else {
                $full_path = __DIR__ . '/' . $value;
                $file_exists = file_exists($full_path);
                $status = $file_exists
                    ? '<span class="good">File EXISTS</span>'
                    : '<span class="bad">File NOT FOUND: ' . htmlspecialchars($full_path) . '</span>';
            }
        }

        echo "<tr>";
        echo "<td><strong>" . htmlspecialchars($key) . "</strong></td>";
        echo "<td>" . (is_null($value) ? '<em>NULL</em>' : htmlspecialchars($value)) . "</td>";
        echo "<td>{$status}</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Show how images would render
    echo "<h3>How Images Would Render:</h3>";
    echo "<table>";
    echo "<tr><th>Image Type</th><th>HTML Code</th><th>Preview</th></tr>";

    if (!empty($school['logo_sekolah'])) {
        $html = '&lt;img src="/' . htmlspecialchars($school['logo_sekolah']) . '"&gt;';
        echo "<tr>";
        echo "<td>Logo</td>";
        echo "<td><code>{$html}</code></td>";
        echo "<td><img src='/{$school['logo_sekolah']}' style='max-height: 50px; max-width: 100px;' onerror='this.style.display=\"none\"; this.nextSibling.style.display=\"inline\"'><span style='display:none; color:red;'>❌ Failed to load</span></td>";
        echo "</tr>";
    }

    if (!empty($school['hero_sekolah'])) {
        $html = '&lt;img src="/' . htmlspecialchars($school['hero_sekolah']) . '"&gt;';
        echo "<tr>";
        echo "<td>Hero</td>";
        echo "<td><code>{$html}</code></td>";
        echo "<td><img src='/{$school['hero_sekolah']}' style='max-height: 50px; max-width: 100px;' onerror='this.style.display=\"none\"; this.nextSibling.style.display=\"inline\"'><span style='display:none; color:red;'>❌ Failed to load</span></td>";
        echo "</tr>";
    }

    if (!empty($school['foto_kepala_sekolah'])) {
        $html = '&lt;img src="/' . htmlspecialchars($school['foto_kepala_sekolah']) . '"&gt;';
        echo "<tr>";
        echo "<td>Headmaster Photo</td>";
        echo "<td><code>{$html}</code></td>";
        echo "<td><img src='/{$school['foto_kepala_sekolah']}' style='max-height: 50px; max-width: 100px;' onerror='this.style.display=\"none\"; this.nextSibling.style.display=\"inline\"'><span style='display:none; color:red;'>❌ Failed to load</span></td>";
        echo "</tr>";
    }
    echo "</table>";

} else {
    echo "<p class='bad'><strong>\$school variable is NOT SET or NOT an array!</strong></p>";
    echo "<pre>";
    var_dump($school);
    echo "</pre>";
}

echo "<h3>URL Info:</h3>";
echo "<table>";
echo "<tr><th>Variable</th><th>Value</th></tr>";
echo "<tr><td>\$slug</td><td>" . (isset($slug) ? htmlspecialchars($slug) : '<em>NOT SET</em>') . "</td></tr>";
echo "<tr><td>\$slug_url</td><td>" . (isset($slug_url) ? htmlspecialchars($slug_url) : '<em>NOT SET</em>') . "</td></tr>";
echo "<tr><td>REQUEST_URI</td><td>" . htmlspecialchars($_SERVER['REQUEST_URI']) . "</td></tr>";
echo "</table>";
?>