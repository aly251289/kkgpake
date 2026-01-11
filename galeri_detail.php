<?php include 'includes/header.php'; ?>
<?php require_once 'config/koneksi.php'; ?>

<?php
if (!isset($_GET['id'])) {
    echo "<div class='flex items-center justify-center min-h-screen bg-gray-100 text-gray-500'>ID Album tidak ditemukan. <a href='index.php' class='text-emerald-600 ml-2 font-bold'>Kembali</a></div>";
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Fetch Album Info
$query_album = mysqli_query($koneksi, "SELECT * FROM galeri WHERE id='$id'");
if (mysqli_num_rows($query_album) == 0) {
    echo "<div class='flex items-center justify-center min-h-screen bg-gray-100 text-gray-500'>Album tidak ditemukan (ID: $id). <a href='index.php' class='text-emerald-600 ml-2 font-bold'>Kembali</a></div>";
    exit;
}
$album = mysqli_fetch_assoc($query_album);

// Fetch Photos
$query_photos = mysqli_query($koneksi, "SELECT * FROM galeri_foto WHERE id_galeri='$id'");

?>

<!-- Hero Section -->
<section class="relative py-24 bg-gradient-to-r from-emerald-900 to-emerald-700 text-white overflow-hidden">
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
    <!-- Blobs -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
    <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-teal-500 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <a href="index.php" class="inline-flex items-center text-emerald-100 hover:text-white mb-6 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Beranda
        </a>
        <h1 class="text-4xl md:text-5xl font-bold text-white mb-4" data-aos="fade-up"><?= $album['judul'] ?></h1>
        <p class="text-emerald-100 text-lg flex items-center justify-center gap-2" data-aos="fade-up" data-aos-delay="100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            <?= date('d F Y', strtotime($album['tanggal'])) ?>
        </p>
    </div>
</section>

<!-- Gallery Grid -->
<section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php 
                // Prepare JS Array
                $gallery_images = [];
                
                // 1. Main Photo
                $main_photo = strpos($album['foto'], 'http') !== false ? $album['foto'] : $album['foto'];
                $gallery_images[] = $main_photo;
            ?>

            <!-- Main Copy (Thumbnail) - Index 0 -->
            <div onclick="openLightbox(0)" class="group relative rounded-2xl overflow-hidden shadow-lg cursor-pointer" data-aos="fade-up">
                <img src="<?= $main_photo ?>" alt="Main Photo" class="w-full h-80 object-cover transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                    <span class="text-white font-bold px-4 py-2 border-2 border-white rounded-full">Lihat Full</span>
                </div>
            </div>

            <!-- Other Photos -->
            <?php 
            if(mysqli_num_rows($query_photos) > 0):
                $index = 1;
                while($photo = mysqli_fetch_assoc($query_photos)):
                    $img_path = strpos($photo['foto'], 'http') !== false ? $photo['foto'] : $photo['foto'];
                     if (strpos($img_path, 'http') === false) {
                         $img_path = str_replace('../', '', $img_path);
                     }
                    // Add to JS Array
                    $gallery_images[] = $img_path;
            ?>
            <div onclick="openLightbox(<?= $index ?>)" class="group relative rounded-2xl overflow-hidden shadow-lg cursor-pointer" data-aos="fade-up">
                <img src="<?= $img_path ?>" alt="Gallery Photo" class="w-full h-80 object-cover transition duration-500 group-hover:scale-110">
                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                     <span class="text-white font-bold px-4 py-2 border-2 border-white rounded-full">Lihat Full</span>
                </div>
            </div>
            <?php 
                    $index++;
                endwhile;
            endif;
            ?>
        </div>
        
        <?php if(mysqli_num_rows($query_photos) == 0): ?>
            <div class="p-8 bg-gray-50 rounded-2xl text-center border-dashed border-2 border-gray-200">
                <p class="text-gray-500">Belum ada foto tambahan untuk kegiatan ini.</p>
            </div>
        <?php endif; ?>

    </div>
</section>

<!-- Lightbox Modal -->
<div id="lightbox" class="fixed inset-0 z-50 bg-black/95 hidden flex items-center justify-center transition-opacity duration-300 opacity-0 select-none">
    <!-- Close Button -->
    <button onclick="closeLightbox()" class="absolute top-6 right-6 text-white/70 hover:text-white transition z-50 p-2">
        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>

    <!-- Navigation Arrows -->
    <button onclick="changeImage(-1)" class="absolute left-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white transition p-4 hover:bg-white/10 rounded-full z-50">
        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
    </button>

    <button onclick="changeImage(1)" class="absolute right-4 top-1/2 -translate-y-1/2 text-white/70 hover:text-white transition p-4 hover:bg-white/10 rounded-full z-50">
        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
    </button>

    <!-- Image Container -->
    <div class="relative w-full h-full flex items-center justify-center p-4" onclick="if(event.target === this) closeLightbox()">
        <img id="lightbox-img" src="" class="max-h-[90vh] max-w-[90vw] object-contain rounded-lg shadow-2xl transform scale-95 transition-transform duration-300">
        
        <!-- Counter -->
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 bg-black/50 text-white px-4 py-1.5 rounded-full backdrop-blur-md text-sm font-medium">
            <span id="current-index">1</span> / <span id="total-images">1</span>
        </div>
    </div>
</div>

<script>
    // Inject PHP Array to JS
    const galleryImages = <?= json_encode($gallery_images) ?>;
    let currentIndex = 0;

    function openLightbox(index) {
        currentIndex = index;
        updateLightboxContent();
        
        const lightbox = document.getElementById('lightbox');
        const img = document.getElementById('lightbox-img');
        
        lightbox.classList.remove('hidden');
        // Small delay to allow display:block
        setTimeout(() => {
            lightbox.classList.remove('opacity-0');
            img.classList.remove('scale-95');
            img.classList.add('scale-100');
        }, 10);
    }

    function closeLightbox() {
        const lightbox = document.getElementById('lightbox');
        const img = document.getElementById('lightbox-img');
        
        lightbox.classList.add('opacity-0');
        img.classList.remove('scale-100');
        img.classList.add('scale-95');
        
        setTimeout(() => {
            lightbox.classList.add('hidden');
            img.src = '';
        }, 300);
    }

    function changeImage(direction) {
        currentIndex += direction;
        
        // Loop navigation
        if (currentIndex >= galleryImages.length) {
            currentIndex = 0;
        } else if (currentIndex < 0) {
            currentIndex = galleryImages.length - 1;
        }
        
        // Add fade effect
        const img = document.getElementById('lightbox-img');
        img.style.opacity = '0.5';
        
        setTimeout(() => {
            updateLightboxContent();
            img.style.opacity = '1';
        }, 150);
    }

    function updateLightboxContent() {
        const img = document.getElementById('lightbox-img');
        const currentSpan = document.getElementById('current-index');
        const totalSpan = document.getElementById('total-images');
        
        img.src = galleryImages[currentIndex];
        currentSpan.innerText = currentIndex + 1;
        totalSpan.innerText = galleryImages.length;
    }

    // Keyboard Navigation
    document.addEventListener('keydown', function(event) {
        const lightbox = document.getElementById('lightbox');
        if (lightbox.classList.contains('hidden')) return;

        if (event.key === "Escape") closeLightbox();
        if (event.key === "ArrowLeft") changeImage(-1);
        if (event.key === "ArrowRight") changeImage(1);
    });
</script>

<?php include 'includes/footer.php'; ?>
