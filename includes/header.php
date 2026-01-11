<?php
// Initialize Page Variables
$current_page = basename($_SERVER['PHP_SELF']);
$is_homepage = ($current_page == 'index.php' || $current_page == '');

// Connection Self-Healing
if (!isset($koneksi)) {
    if (file_exists('config/koneksi.php')) {
        require_once 'config/koneksi.php';
    } elseif (file_exists('../config/koneksi.php')) {
        require_once '../config/koneksi.php';
    }
}

// Fetch Identity Data
// Assuming koneksi.php is included.
if (isset($koneksi)) {
    $id_query = mysqli_query($koneksi, "SELECT * FROM identitas WHERE id=1");
    // Check if query is valid
    if ($id_query) {
        $d_identitas = mysqli_fetch_assoc($id_query);
    } else {
        $d_identitas = [];
    }
    $web_name = $d_identitas['nama_website'] ?? 'KKG MI';
} else {
    // If koneksi still fails? Use default.
    $web_name = 'KKG MI';
    $d_identitas = [];
}
?>
<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : ($web_name ?? 'KKG MI') . ' - Website Resmi' ?></title>

    <!-- Open Graph / WhatsApp Preview -->
    <meta property="og:type" content="website">
    <meta property="og:title"
        content="<?= isset($og_title) ? $og_title : (($web_name ?? 'KKG MI') . ' - Website Resmi') ?>">
    <meta property="og:description"
        content="<?= isset($og_description) ? $og_description : 'Website resmi Kelompok Kerja Guru.' ?>">
    <meta property="og:image" content="<?= isset($og_image) ? $og_image : 'assets/images/logo-kkg.png' ?>">
    <meta property="og:url" content="<?= "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Favicon -->
    <?php if (!empty($d_identitas['file_favicon'])): ?>
        <link rel="icon" type="image/x-icon" href="assets/images/<?= $d_identitas['file_favicon'] ?>">
    <?php endif; ?>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=typography"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        emerald: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669', // Primary Green
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        }
                    }
                }
            }
        }
    </script>



    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Style -->
    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: #059669;
            /* Emerald 600 */
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #047857;
            /* Emerald 700 */
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Lightweight Animation Defaults */
        [data-aos] {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
            will-change: opacity, transform;
        }

        [data-aos].aos-animate {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>

<body class="font-sans text-gray-700 bg-gray-50 overflow-x-hidden">

    <!-- Navbar -->
    <!-- Navbar -->
    <?php
    // If homepage: Text Gray (bg is white). If inner page: Text White (bg is dark gradient).
    $navTextClass = $is_homepage ? 'text-gray-600 hover:text-emerald-600' : 'text-white hover:text-emerald-200';
    $logoTextClass = $is_homepage ? 'text-gray-900' : 'text-white';
    $mobileBtnClass = $is_homepage ? 'text-gray-600 hover:text-emerald-600' : 'text-white hover:text-emerald-200';
    ?>
    <nav id="navbar" class="fixed w-full z-50 transition-all duration-300 bg-transparent py-4 text-sm"
        x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)"
        :class="{ 'bg-white/90 backdrop-blur-md shadow-md py-2': scrolled, 'bg-transparent py-4': !scrolled }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-3">
                    <?php if (!empty($d_identitas['file_logo'])): ?>
                        <img src="assets/images/<?= $d_identitas['file_logo'] ?>" alt="Logo"
                            class="h-10 w-auto object-contain">
                    <?php else: ?>
                        <div
                            class="w-10 h-10 bg-emerald-600 rounded-lg flex items-center justify-center text-white font-bold text-xl shadow-lg">
                            K
                        </div>
                    <?php endif; ?>
                    <a href="index" class="font-bold text-xl tracking-tight group transition"
                        :class="scrolled ? 'text-gray-900' : '<?= $logoTextClass ?>'">
                        <?php
                        // Smart Two-Tone Coloring
                        $parts = explode(' ', $web_name);
                        if (count($parts) > 1) {
                            $last = array_pop($parts);
                            echo implode(' ', $parts) . ' <span class="group-hover:text-emerald-700 transition" :class="scrolled ? \'text-emerald-600\' : \'' . ($is_homepage ? 'text-emerald-600' : 'text-emerald-300') . '\'">' . $last . '</span>';
                        } else {
                            echo $web_name;
                        }
                        ?>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-8 items-center">
                    <?php
                    $menu_items = [
                        'index' => 'Beranda',
                        'profil' => 'Profil',
                        'anggota' => 'Anggota',
                        'kegiatan' => 'Kegiatan',
                        'agenda' => 'Agenda',
                        'informasi' => 'Informasi',
                        'unduhan' => 'Unduhan',
                    ];

                    foreach ($menu_items as $file => $label):
                        $isActive = ($current_page == $file . '.php' || $current_page == $file);
                        // Active state underlining color
                        $activeLineColor = $is_homepage ? 'bg-emerald-600' : ($isActive ? 'bg-white' : 'bg-emerald-600');
                        ?>
                        <a href="<?= $file ?>" class="font-medium transition duration-300 relative group"
                            :class="scrolled ? 'text-gray-600 hover:text-emerald-600' : '<?= $navTextClass ?>'">
                            <?= $label ?>
                            <span class="absolute bottom-0 left-0 h-0.5 transition-all duration-300 group-hover:w-full"
                                :class="[scrolled ? 'bg-emerald-600' : (<?= $is_homepage ? 'true' : 'false' ?> ? 'bg-emerald-600' : 'bg-white'), <?= $isActive ? "'w-full'" : "'w-0'" ?>]"></span>
                        </a>
                    <?php endforeach; ?>

                    <a href="kontak"
                        class="px-5 py-2.5 bg-emerald-600 text-white rounded-full font-medium shadow-md hover:bg-emerald-700 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300">
                        Hubungi Kami
                    </a>
                    <a href="register"
                        class="px-5 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-full font-medium shadow-md hover:from-purple-700 hover:to-indigo-700 hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-300 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                            </path>
                        </svg>
                        Daftar Kontributor
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-btn" class="focus:outline-none transition"
                        :class="scrolled ? 'text-gray-600' : '<?= $mobileBtnClass ?>'">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu"
            class="hidden md:hidden bg-white/95 backdrop-blur-md absolute top-full left-0 w-full shadow-lg border-t border-gray-100">
            <div class="px-4 pt-4 pb-6 space-y-2">
                <a href="index"
                    class="block px-3 py-2 rounded-md font-bold text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition">Beranda</a>
                <a href="profil"
                    class="block px-3 py-2 rounded-md font-bold text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition">Profil</a>
                <a href="anggota"
                    class="block px-3 py-2 rounded-md font-bold text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition">Anggota</a>
                <a href="kegiatan"
                    class="block px-3 py-2 rounded-md font-bold text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition">Kegiatan</a>
                <a href="agenda"
                    class="block px-3 py-3 rounded-lg text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition">Agenda</a>
                <a href="informasi"
                    class="block px-3 py-3 rounded-lg text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition">Informasi</a>
                <a href="unduhan"
                    class="block px-3 py-3 rounded-lg text-base font-medium text-gray-700 hover:text-emerald-600 hover:bg-emerald-50 transition">Unduhan</a>
                <a href="kontak"
                    class="block px-3 py-3 mt-4 text-center rounded-lg text-base font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md transition">Hubungi
                    Kami</a>
                <a href="register"
                    class="block px-3 py-3 mt-2 text-center rounded-lg text-base font-bold text-white bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 shadow-md transition">
                    🎓 Daftar Kontributor
                </a>
            </div>
        </div>
    </nav>