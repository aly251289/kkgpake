<?php include 'includes/header.php'; ?>
<?php require_once 'config/koneksi.php'; ?>

<style>
    .rdm-hero {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #0ea5e9 100%);
        position: relative;
        overflow: hidden;
    }

    .rdm-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('https://www.transparenttextures.com/patterns/cubes.png');
        opacity: 0.1;
    }

    .rdm-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .rdm-card:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 25px 50px rgba(59, 130, 246, 0.25);
    }

    .rdm-card:hover .rdm-icon {
        transform: scale(1.15) rotate(0deg);
    }

    .rdm-card:hover .rdm-button {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    }

    .float-animation {
        animation: float 3s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-10px);
        }
    }
</style>

<!-- Hero Section -->
<section class="rdm-hero py-16 text-white">
    <div class="absolute top-10 right-10 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-cyan-400/20 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur px-4 py-2 rounded-full text-sm font-medium mb-6"
            data-aos="fade-up">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                </path>
            </svg>
            Rapor Digital Madrasah
        </div>

        <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 leading-tight" data-aos="fade-up"
            data-aos-delay="100">
            Portal Akses <span
                class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-amber-400">RDM</span>
        </h1>
        <p class="text-xl text-blue-100 max-w-2xl mx-auto mb-10" data-aos="fade-up" data-aos-delay="200">
            Akses langsung ke aplikasi Rapor Digital Madrasah untuk setiap lembaga yang terdaftar di wilayah KKG MI
            Paket.
        </p>

        <!-- Search Box -->
        <div class="flex justify-center" data-aos="fade-up" data-aos-delay="300">
            <div class="relative w-full max-w-md">
                <input type="text" id="searchRDM" placeholder="🔍 Cari nama madrasah..."
                    class="w-full pl-5 pr-12 py-3 rounded-xl border-0 shadow-xl focus:outline-none focus:ring-4 focus:ring-blue-300/50 transition text-gray-700 placeholder-gray-400">
                <div
                    class="absolute right-2 top-1/2 -translate-y-1/2 w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Content -->
<section class="py-16 bg-gradient-to-b from-gray-50 to-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <?php
        // Self-Healing
        $check_tbl = mysqli_query($koneksi, "SHOW TABLES LIKE 'rdm_links'");
        if (mysqli_num_rows($check_tbl) == 0) {
            mysqli_query($koneksi, "CREATE TABLE IF NOT EXISTS rdm_links (id INT AUTO_INCREMENT PRIMARY KEY, nama_madrasah VARCHAR(100) NOT NULL, url_rdm VARCHAR(255) NOT NULL, gambar VARCHAR(255), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP)");
        }

        $query = mysqli_query($koneksi, "SELECT * FROM rdm_links ORDER BY nama_madrasah ASC");
        $total = mysqli_num_rows($query);
        ?>


        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" id="gridRDM">
            <?php
            if ($total > 0):
                $gradients = [
                    'from-red-500 to-rose-600',
                    'from-orange-500 to-amber-600',
                    'from-yellow-500 to-lime-600',
                    'from-green-500 to-emerald-600',
                    'from-teal-500 to-cyan-600',
                    'from-blue-500 to-indigo-600',
                    'from-indigo-500 to-purple-600',
                    'from-purple-500 to-pink-600',
                    'from-pink-500 to-rose-600',
                ];
                $i = 0;
                while ($row = mysqli_fetch_assoc($query)):
                    $gradient = $gradients[$i % count($gradients)];
                    $initial = strtoupper(substr($row['nama_madrasah'], 0, 2));
                    $i++;
                    ?>
                    <!-- Card Item -->
                    <div class="rdm-card rounded-3xl overflow-hidden shadow-lg group"
                        data-name="<?= strtolower($row['nama_madrasah']) ?>" data-aos="fade-up"
                        data-aos-delay="<?= ($i * 50) ?>">

                        <!-- Card Header -->
                        <div class="relative h-36 bg-gradient-to-br <?= $gradient ?> flex items-center justify-center">
                            <div class="absolute inset-0 bg-black/10"></div>
                            <div
                                class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2">
                            </div>

                            <!-- Logo -->
                            <div class="rdm-icon relative z-10 transition-transform duration-500 transform rotate-6">
                                <?php if (!empty($row['gambar'])): ?>
                                    <img src="assets/images/rdm/<?= $row['gambar'] ?>" alt="<?= $row['nama_madrasah'] ?>"
                                        class="w-20 h-20 rounded-2xl object-cover border-4 border-white shadow-xl">
                                <?php else: ?>
                                    <div
                                        class="w-20 h-20 bg-white rounded-2xl flex items-center justify-center text-2xl font-bold shadow-xl border-4 border-white/50 text-gray-700">
                                        <?= $initial ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-6 text-center">
                            <h3 class="text-lg font-bold text-gray-800 mb-4 group-hover:text-blue-600 transition line-clamp-2"
                                title="<?= htmlspecialchars($row['nama_madrasah']) ?>">
                                <?= htmlspecialchars($row['nama_madrasah']) ?>
                            </h3>

                            <a href="<?= htmlspecialchars($row['url_rdm']) ?>" target="_blank"
                                class="rdm-button inline-flex items-center justify-center gap-2 w-full py-3 px-4 rounded-xl bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 font-bold text-sm hover:text-white transition-all duration-300 shadow-sm hover:shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Akses RDM
                            </a>
                        </div>
                    </div>
                    <?php
                endwhile;
            else:
                ?>
                <div class="col-span-full text-center py-20">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Link RDM</h4>
                    <p class="text-gray-500">Link Rapor Digital Madrasah belum tersedia.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<script>
    // Search functionality
    document.getElementById('searchRDM').addEventListener('keyup', function () {
        let filter = this.value.toLowerCase();
        let cards = document.querySelectorAll('.rdm-card');

        cards.forEach(card => {
            let name = card.getAttribute('data-name');
            if (name.includes(filter)) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>

<?php include 'includes/footer.php'; ?>