<?php
include 'includes/init_sekolah.php';
include 'includes/header_sekolah.php';

// Fetch berita detail
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = mysqli_query($koneksi, "SELECT berita.*, users.nama_lengkap as author_name FROM berita LEFT JOIN users ON berita.created_by = users.id WHERE berita.id='$id' AND berita.created_by='$author_id'");

    if (mysqli_num_rows($query) > 0) {
        $berita = mysqli_fetch_assoc($query);
        // Update views
        mysqli_query($koneksi, "UPDATE berita SET views = views + 1 WHERE id = '$id'");
    } else {
        echo "<script>window.location='{$base_path}/mi/{$slug}/berita';</script>";
        exit;
    }
} else {
    echo "<script>window.location='{$base_path}/mi/{$slug}/berita';</script>";
    exit;
}
?>

<!-- Hero Section -->
<div class="relative h-[400px] md:h-[500px] w-full">
    <?php
    $img_src = $berita['gambar'] ? $berita['gambar'] : 'https://ui-avatars.com/api/?name=' . urlencode($berita['judul']) . '&background=059669&color=fff&size=800';
    $badgeColor = 'bg-emerald-600';
    ?>
    <img src="<?= $img_src ?>" alt="<?= $berita['judul'] ?>" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>

    <div class="absolute bottom-0 left-0 w-full p-6 md:p-12">
        <div class="container mx-auto">
            <div class="max-w-4xl">
                <span
                    class="<?= $badgeColor ?> text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wide mb-4 inline-block">
                    <?= $berita['kategori'] ?>
                </span>
                <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 leading-tight">
                    <?= $berita['judul'] ?>
                </h1>
                <div class="flex flex-wrap items-center text-gray-300 text-sm md:text-base gap-4 md:gap-6">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <?= date('d F Y', strtotime($berita['tanggal'])) ?>
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <?= $berita['author_name'] ?? $berita['penulis'] ?>
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <?= $berita['views'] ?>x Dibaca
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<div class="container mx-auto px-4 py-12">
    <div class="flex flex-col lg:flex-row gap-10">

        <!-- Left: Article Content -->
        <div class="w-full lg:w-2/3">
            <div class="bg-white rounded-2xl shadow-sm p-6 md:p-10 border border-gray-100">
                <article
                    class="prose prose-lg max-w-none prose-emerald prose-img:rounded-xl prose-headings:text-gray-800 prose-a:text-blue-600 prose-p:text-justify prose-p:leading-relaxed prose-p:text-gray-700">
                    <?= $berita['isi'] ?>

                    <?php if (!empty($berita['tags'])):
                        $tags = array_map('trim', explode(',', $berita['tags']));
                        ?>
                        <div class="mt-8 flex flex-wrap gap-2">
                            <?php foreach ($tags as $tag): ?>
                                <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-sm font-medium">#
                                    <?= htmlspecialchars($tag) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </article>

                <!-- Back Button -->
                <div class="mt-10 border-t border-gray-100 pt-8">
                    <a href="<?= $base_path ?>/mi/<?= $slug ?>/berita"
                        class="inline-flex items-center text-gray-600 hover:text-emerald-600 font-semibold transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Daftar Berita
                    </a>
                </div>
            </div>
        </div>

        <!-- Right: Sidebar -->
        <div class="w-full lg:w-1/3">
            <?php include 'includes/sidebar_sekolah.php'; ?>
        </div>

    </div>
</div>

<?php include 'includes/footer_sekolah.php'; ?>