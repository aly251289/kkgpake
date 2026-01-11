<?php
$host = 'localhost';
$user = 'root'; // Sesuaikan dengan user database lokal
$pass = '';     // Sesuaikan dengan password database lokal
$db = 'webkkg';

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Base URL configuration - adjust if needed
// Auto detect base url
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host_url = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Calculate relative path from document root
$script_path = str_replace('\\', '/', __DIR__); // c:/xampp/htdocs/kkgpaket/config
$doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']); // c:/xampp/htdocs

// Remove doc_root from script_path to get the relative web path
$relative_path = str_replace($doc_root, '', $script_path); // /kkgpaket/config
// Remove /config from the end to get base path
$base_path = dirname($relative_path); // /kkgpaket (or / if at root)
// Ensure forward slashes and no trailing slash
$base_path = rtrim(str_replace('\\', '/', $base_path), '/');

$base_url = $protocol . "://" . $host_url . $base_path;

function assets($path)
{
    global $base_url;
    return $base_url . '/assets/' . ltrim($path, '/');
}

function url($path)
{
    global $base_url;
    return $base_url . '/' . ltrim($path, '/');
}
?>