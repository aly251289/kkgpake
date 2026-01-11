<?php
require_once 'config/koneksi.php';
$result = mysqli_query($koneksi, "DESCRIBE users");
while ($row = mysqli_fetch_assoc($result)) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}
?>