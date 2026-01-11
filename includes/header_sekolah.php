<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= !empty($school['judul_website']) ? $school['judul_website'] : ($school['nama_sekolah'] ?? 'School Profile') ?>
        - Web Sekolah
    </title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-800">

    <!-- Navigation -->
    <nav class="glass-nav fixed w-full z-50 transition-all duration-300 shadow-sm top-0">
        <div class="container mx-auto px-4 py-3">
            <div class="flex justify-between items-center">
                <!-- Brand -->
                <a href="<?= $base_path ?>/mi/<?= $slug ?>" class="flex items-center space-x-3 group">
                    <?php if (!empty($school['logo_sekolah'])): ?>
                        <img src="<?= $base_path ?>/<?= $school['logo_sekolah'] ?>" alt="Logo"
                            class="h-10 w-auto transform group-hover:scale-105 transition">
                    <?php else: ?>
                        <div
                            class="h-10 w-10 bg-emerald-600 rounded-full flex items-center justify-center text-white font-bold">
                            <?= substr($school['nama_sekolah'], 0, 1) ?>
                        </div>
                    <?php endif; ?>
                    <span
                        class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-emerald-700 to-teal-600">
                        <?= !empty($school['judul_website']) ? $school['judul_website'] : $school['nama_sekolah'] ?>
                    </span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex space-x-6 items-center font-medium text-sm">
                    <a href="<?= $base_path ?>/mi/<?= $slug ?>"
                        class="text-gray-600 hover:text-emerald-600 transition <?= basename($_SERVER['PHP_SELF']) == 'sekolah.php' ? 'text-emerald-600 font-bold' : '' ?>">Beranda</a>
                    <a href="<?= $base_path ?>/mi/<?= $slug ?>/profil"
                        class="text-gray-600 hover:text-emerald-600 transition <?= basename($_SERVER['PHP_SELF']) == 'sekolah_profil.php' ? 'text-emerald-600 font-bold' : '' ?>">Profil</a>
                    <a href="<?= $base_path ?>/mi/<?= $slug ?>/berita"
                        class="text-gray-600 hover:text-emerald-600 transition <?= basename($_SERVER['PHP_SELF']) == 'sekolah_berita.php' ? 'text-emerald-600 font-bold' : '' ?>">Berita</a>
                    <a href="<?= $base_path ?>/mi/<?= $slug ?>/kegiatan"
                        class="text-gray-600 hover:text-emerald-600 transition <?= basename($_SERVER['PHP_SELF']) == 'sekolah_kegiatan.php' ? 'text-emerald-600 font-bold' : '' ?>">Kegiatan</a>
                    <a href="<?= $base_path ?>/mi/<?= $slug ?>/kontak"
                        class="text-gray-600 hover:text-emerald-600 transition <?= basename($_SERVER['PHP_SELF']) == 'sekolah_kontak.php' ? 'text-emerald-600 font-bold' : '' ?>">Kontak</a>
                    <a href="<?= $base_path ?>/mi/<?= $slug ?>/pengumuman"
                        class="text-gray-600 hover:text-emerald-600 transition <?= basename($_SERVER['PHP_SELF']) == 'sekolah_pengumuman.php' || basename($_SERVER['PHP_SELF']) == 'sekolah_pengumuman_list.php' ? 'text-emerald-600 font-bold' : '' ?>">Pengumuman</a>
                </div>
                <a href="<?= $base_path ?>/index"
                    class="px-4 py-2 rounded-full bg-gray-100 text-gray-600 hover:bg-emerald-50 hover:text-emerald-600 transition text-xs font-bold border border-transparent hover:border-emerald-200">
                    ← Portal KKG
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="md:hidden text-gray-600 hover:text-emerald-600 focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path id="menu-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"></path>
                    <path id="close-icon" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu"
            class="md:hidden hidden bg-white border-t border-gray-100 absolute w-full left-0 shadow-lg">
            <div class="flex flex-col p-4 space-y-4 font-medium">
                <a href="<?= $base_path ?>/mi/<?= $slug ?>" class="text-gray-600 hover:text-emerald-600">Beranda</a>
                <a href="<?= $base_path ?>/mi/<?= $slug ?>/profil"
                    class="nav-link text-gray-600 hover:text-emerald-600 font-medium transition">Profil</a>
                <a href="<?= $base_path ?>/mi/<?= $slug ?>/berita"
                    class="nav-link text-gray-600 hover:text-emerald-600 font-medium transition">Berita</a>

                <a href="<?= $base_path ?>/mi/<?= $slug ?>/kegiatan"
                    class="text-gray-600 hover:text-emerald-600">Kegiatan</a>
                <a href="<?= $base_path ?>/mi/<?= $slug ?>/pengumuman"
                    class="text-gray-600 hover:text-emerald-600">Pengumuman</a>
                <a href="<?= $base_path ?>/mi/<?= $slug ?>/kontak"
                    class="text-gray-600 hover:text-emerald-600">Kontak</a>
                <hr>
                <a href="<?= $base_path ?>/index"
                    class="text-sm text-center py-2 text-gray-400 hover:text-emerald-600">Kembali ke
                    Portal KKG</a>
            </div>
        </div>
    </nav>

    <script>
        // Simple Mobile Menu Toggle
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const menuIcon = document.getElementById('menu-icon');
        const closeIcon = document.getElementById('close-icon');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
            menuIcon.classList.toggle('hidden');
            closeIcon.classList.toggle('hidden');
        });
    </script>

    <!-- Spacer for fixed nav -->
    <div class="h-16"></div>