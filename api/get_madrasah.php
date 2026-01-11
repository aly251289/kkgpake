<?php
/**
 * API Endpoint: Get Madrasah by NSM
 * Returns madrasah data for auto-fill in registration form
 */

header('Content-Type: application/json');
require_once '../config/koneksi.php';

if (!isset($_GET['nsm']) || empty($_GET['nsm'])) {
    echo json_encode(['success' => false, 'message' => 'NSM required']);
    exit;
}

$nsm = mysqli_real_escape_string($koneksi, $_GET['nsm']);

// Get madrasah from users table (sekolah that already registered)
$query = "SELECT nama_sekolah, slug_sekolah, nsm FROM users WHERE nsm = '$nsm' AND role = 'contributor' LIMIT 1";
$result = mysqli_query($koneksi, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
    echo json_encode([
        'success' => true,
        'data' => [
            'nama_sekolah' => $data['nama_sekolah'],
            'slug_sekolah' => $data['slug_sekolah'],
            'nsm' => $data['nsm']
        ]
    ]);
} else {
    // If not found in users, return NSM only (new school)
    echo json_encode([
        'success' => true,
        'data' => [
            'nama_sekolah' => '',
            'slug_sekolah' => '',
            'nsm' => $nsm
        ]
    ]);
}
?>