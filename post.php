<?php
require_once 'config/koneksi.php';

// Logic Fetch Data moved to top for SEO/Meta Tags
if (isset($_GET['slug'])) {
    $slug = mysqli_real_escape_string($koneksi, $_GET['slug']);
    $query = mysqli_query($koneksi, "SELECT berita.*, users.nama_lengkap as author_name FROM berita LEFT JOIN users ON berita.created_by = users.id WHERE berita.slug='$slug'");

    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);

        // Update views
        mysqli_query($koneksi, "UPDATE berita SET views = views + 1 WHERE id = '{$row['id']}'");

        // Prepare SEO & Open Graph Data
        $page_title = $row['judul'];
        $og_title = $row['judul'];

        // Description: Clean HTML tags and limit length
        $og_description = substr(strip_tags($row['isi']), 0, 150) . '...';

        // Image: Ensure Absolute URL
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $base_url_auto = $protocol . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
        // Fix trailing slash issue if dirname is root
        $base_url_auto = rtrim($base_url_auto, '/\\');

        if ($row['gambar']) {
            if (strpos($row['gambar'], 'http') !== false) {
                $og_image = $row['gambar'];
            } else {
                $og_image = $base_url_auto . '/' . $row['gambar'];
            }
        } else {
            $og_image = 'https://ui-avatars.com/api/?name=' . urlencode($row['judul']) . '&background=2563eb&color=fff&size=800';
        }

    } else {
        echo "<script>window.location='informasi.php';</script>";
        exit;
    }
} else {
    echo "<script>window.location='informasi.php';</script>";
    exit;
}

include 'includes/header.php';
?>

<!-- Hero / Featured Image Section -->
<div class="relative h-[400px] md:h-[500px] w-full">
    <?php
    $img_src = $row['gambar'] ? $row['gambar'] : 'https://ui-avatars.com/api/?name=' . urlencode($row['judul']) . '&background=2563eb&color=fff&size=800';
    $badgeColor = 'bg-blue-600';
    if ($row['kategori'] == 'Pengumuman')
        $badgeColor = 'bg-red-600';
    elseif ($row['kategori'] == 'Berita Utama')
        $badgeColor = 'bg-purple-600';
    elseif ($row['kategori'] == 'Artikel')
        $badgeColor = 'bg-indigo-600';
    ?>
    <img src="<?= $img_src ?>" alt="<?= $row['judul'] ?>" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>

    <div class="absolute bottom-0 left-0 w-full p-6 md:p-12">
        <div class="container mx-auto">
            <div class="max-w-4xl">
                <span
                    class="<?= $badgeColor ?> text-white text-xs font-bold px-3 py-1.5 rounded-full uppercase tracking-wide mb-4 inline-block">
                    <?= $row['kategori'] ?>
                </span>
                <h1 class="text-3xl md:text-5xl font-bold text-white mb-4 leading-tight">
                    <?= $row['judul'] ?>
                </h1>
                <div class="flex flex-wrap items-center text-gray-300 text-sm md:text-base gap-4 md:gap-6">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <?= date('d F Y', strtotime($row['tanggal'])) ?>
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        <?= $row['penulis'] ?>
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <?= $row['views'] ?>x Dibaca
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content -->
<section class="py-12 bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-10">

            <!-- Left: Article Content -->
            <div class="w-full lg:w-2/3">
                <div class="bg-white rounded-2xl shadow-sm p-6 md:p-10 border border-gray-100">
                    <article
                        class="prose prose-lg max-w-none prose-emerald prose-img:rounded-xl prose-headings:text-gray-800 prose-a:text-blue-600 prose-p:text-justify prose-p:leading-relaxed prose-p:text-gray-700">
                        <?= $row['isi'] ?>

                        <div class="mt-8 mb-4">
                            <?php if (!empty($row['tags'])):
                                $tags = array_map('trim', explode(',', $row['tags']));
                                ?>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($tags as $tag): ?>
                                        <span
                                            class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-sm font-medium hover:bg-gray-200 transition">#<?= htmlspecialchars($tag) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <p class="mt-8 text-sm text-gray-500 italic border-t border-gray-100 pt-4">
                            Diposting oleh: <span
                                class="font-medium"><?= $row['author_name'] ?? $row['penulis'] ?></span>
                        </p>
                    </article>



                    <hr class="my-8 border-gray-100">

                    <!-- Share Button -->
                    <?php
                    $share_url = urlencode($base_url . '/post.php?slug=' . $row['slug']);
                    $share_title = urlencode($row['judul']);
                    ?>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 font-medium">Bagikan:</span>
                        <div class="flex space-x-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $share_url ?>" target="_blank"
                                class="w-9 h-9 rounded-full bg-blue-600 text-white flex items-center justify-center hover:bg-blue-700 transition"
                                title="Share to Facebook">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z" />
                                </svg>
                            </a>
                            <a href="https://twitter.com/intent/tweet?text=<?= $share_title ?>&url=<?= $share_url ?>"
                                target="_blank"
                                class="w-9 h-9 rounded-full bg-sky-500 text-white flex items-center justify-center hover:bg-sky-600 transition"
                                title="Share to Twitter">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M23.44 4.83c-.8.37-1.5.38-2.22.02.94-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z" />
                                </svg>
                            </a>
                            <a href="https://api.whatsapp.com/send?text=<?= $share_title ?>%20<?= $share_url ?>"
                                target="_blank"
                                class="w-9 h-9 rounded-full bg-green-500 text-white flex items-center justify-center hover:bg-green-600 transition"
                                title="Share to WhatsApp">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Sidebar -->
            <div class="w-full lg:w-1/3 space-y-8">

                <!-- Search -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-l-4 border-blue-600 pl-3">Cari Informasi</h3>
                    <form action="informasi.php" method="GET" class="relative">
                        <input type="text" name="q" placeholder="Cari berita..."
                            class="w-full px-4 py-3 bg-gray-50 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition border border-gray-200">
                        <button type="submit"
                            class="absolute right-2 top-2 bg-blue-600 text-white p-1.5 rounded-md hover:bg-blue-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Recent Posts Widget -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 border-l-4 border-blue-600 pl-3">Berita Terbaru</h3>
                    <div class="space-y-4">
                        <?php
                        $query_recent = mysqli_query($koneksi, "SELECT * FROM berita WHERE id != '{$row['id']}' ORDER BY tanggal DESC LIMIT 5");
                        while ($recent = mysqli_fetch_assoc($query_recent)):
                            $img_recent = $recent['gambar'] ? $recent['gambar'] : 'https://ui-avatars.com/api/?name=' . urlencode($recent['judul']) . '&background=2563eb&color=fff&size=200';
                            ?>
                            <div class="flex gap-4 group cursor-pointer"
                                onclick="window.location='post.php?slug=<?= $recent['slug'] ?>'">
                                <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden relative">
                                    <img src="<?= $img_recent ?>"
                                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-300"
                                        alt="Thumb">
                                </div>
                                <div>
                                    <h4
                                        class="text-sm font-bold text-gray-800 line-clamp-2 group-hover:text-blue-600 transition mb-1">
                                        <a href="post.php?slug=<?= $recent['slug'] ?>"><?= $recent['judul'] ?></a>
                                    </h4>
                                    <span
                                        class="text-xs text-gray-400 block"><?= date('d M Y', strtotime($recent['tanggal'])) ?></span>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>