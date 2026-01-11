<?php
require_once 'config/koneksi.php';

// Shared logic to fetch school data based on slug
if (isset($_GET['slug'])) {
    $slug = mysqli_real_escape_string($koneksi, $_GET['slug']);
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE slug_sekolah='$slug'");
    if (mysqli_num_rows($query) > 0) {
        $school = mysqli_fetch_assoc($query);
        $author_id = $school['id'];

        // Define base URL for school pages (helper for rewriting if needed)
        // For now, simpler: separate pages.
        $slug_url = '?slug=' . $school['slug_sekolah'];

    } else {
        // School not found
        header("Location: index.php");
        exit;
    }
} else {
    // No slug provided
    header("Location: index.php");
    exit;
}
?>