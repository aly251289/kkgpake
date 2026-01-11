<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login");
    exit;
}

// Auto-fix role missing from session
if (empty($_SESSION['admin_role']) && isset($_SESSION['admin_id'])) {
    require_once '../config/koneksi.php'; // Ensure connection is available for role fix
    $uid_check = $_SESSION['admin_id'];
    $q_role = mysqli_query($koneksi, "SELECT role FROM users WHERE id='$uid_check'");
    if ($r_role = mysqli_fetch_assoc($q_role)) {
        $_SESSION['admin_role'] = !empty($r_role['role']) ? $r_role['role'] : 'contributor';
    } else {
        $_SESSION['admin_role'] = 'contributor'; // Default fallback
    }
}

$current_page = basename($_SERVER['PHP_SELF']);
// Fetch Identity Data specifically for Admin Header (Favicon)
require_once '../config/koneksi.php'; // Ensure connection is available
$id_query = mysqli_query($koneksi, "SELECT * FROM identitas WHERE id=1");
$d_identitas = mysqli_fetch_assoc($id_query);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - <?= $d_identitas['nama_website'] ?? 'KKG MI' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Favicon -->
    <?php if (!empty($d_identitas['file_favicon'])): ?>
        <link rel="icon" type="image/x-icon" href="../assets/images/<?= $d_identitas['file_favicon'] ?>">
    <?php endif; ?>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* ===== PREMIUM SIDEBAR STYLES ===== */

        /* Sidebar base */
        #sidebar {
            transform: translateX(-100%);
            transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }

        #sidebar.open {
            transform: translateX(0);
        }

        /* Desktop: Always visible */
        @media (min-width: 1024px) {
            #sidebar {
                transform: translateX(0) !important;
                position: static !important;
            }
        }

        /* Backdrop */
        #sidebar-backdrop {
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s ease;
        }

        #sidebar-backdrop.active {
            opacity: 1;
            visibility: visible;
        }

        /* Hamburger Animation */
        .hamburger {
            width: 24px;
            height: 18px;
            position: relative;
            cursor: pointer;
        }

        .hamburger span {
            position: absolute;
            left: 0;
            width: 100%;
            height: 2px;
            background: #374151;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .hamburger span:nth-child(1) {
            top: 0;
        }

        .hamburger span:nth-child(2) {
            top: 8px;
        }

        .hamburger span:nth-child(3) {
            top: 16px;
        }

        .hamburger.active span:nth-child(1) {
            top: 8px;
            transform: rotate(45deg);
            background: #059669;
        }

        .hamburger.active span:nth-child(2) {
            opacity: 0;
            transform: translateX(-10px);
        }

        .hamburger.active span:nth-child(3) {
            top: 8px;
            transform: rotate(-45deg);
            background: #059669;
        }

        /* Menu Item Styles */
        .menu-item {
            position: relative;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .menu-item:hover {
            background: rgba(16, 185, 129, 0.15);
            border-left-color: #10b981;
            transform: translateX(5px);
        }

        .menu-item.active {
            background: linear-gradient(90deg, rgba(16, 185, 129, 0.2), transparent);
            border-left-color: #10b981;
        }

        .menu-item:hover .menu-icon {
            transform: scale(1.2);
        }

        .menu-icon {
            transition: transform 0.3s ease;
        }

        /* Logo Animation */
        .logo-wrapper {
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-3px);
            }
        }

        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #10b981, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Scrollbar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: rgba(16, 185, 129, 0.3);
            border-radius: 2px;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb:hover {
            background: rgba(16, 185, 129, 0.5);
        }

        /* Logout Hover */
        .logout-btn:hover {
            background: linear-gradient(90deg, #dc2626, #b91c1c);
        }

        .logout-btn:hover svg {
            animation: shake 0.5s ease;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-2px) rotate(-5deg);
            }

            75% {
                transform: translateX(2px) rotate(5deg);
            }
        }

        /* Badge Pulse */
        .pulse-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.4);
            }

            50% {
                box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
            }
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-50 to-slate-100 antialiased">

    <div class="flex h-screen overflow-hidden">

        <!-- Backdrop Overlay -->
        <div id="sidebar-backdrop" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden"
            onclick="toggleSidebar()"></div>

        <!-- Sidebar -->
        <aside id="sidebar"
            class="fixed inset-y-0 left-0 z-50 w-72 bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 text-white shadow-2xl flex flex-col lg:shadow-xl">

            <!-- Logo Section -->
            <div
                class="h-20 flex items-center justify-center border-b border-white/10 bg-gradient-to-r from-emerald-600/20 to-blue-600/20">
                <div class="logo-wrapper flex items-center gap-3">
                    <div
                        class="w-11 h-11 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30 pulse-badge">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="text-xl font-extrabold tracking-tight">KKG</span>
                        <span class="text-xl font-light text-emerald-400">Panel</span>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="sidebar-scroll flex-1 px-4 py-6 overflow-y-auto">

                <!-- Main Menu -->
                <div class="mb-6">
                    <a href="dashboard"
                        class="menu-item <?= $current_page == 'dashboard.php' ? 'active' : '' ?> flex items-center px-4 py-3 rounded-xl text-slate-300 hover:text-white mb-1">
                        <svg class="menu-icon w-5 h-5 mr-3 text-emerald-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                        <span class="font-medium">Dashboard</span>
                    </a>
                </div>

                <!-- Konten Section -->
                <div class="mb-6">
                    <p class="px-4 mb-3 text-xs font-bold text-slate-500 uppercase tracking-widest">Konten</p>

                    <a href="berita"
                        class="menu-item <?= $current_page == 'berita.php' ? 'active' : '' ?> flex items-center px-4 py-3 rounded-xl text-slate-300 hover:text-white mb-1">
                        <svg class="menu-icon w-5 h-5 mr-3 text-blue-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                            </path>
                        </svg>
                        <span class="font-medium">Berita & Info</span>
                    </a>

                    <a href="kegiatan"
                        class="menu-item <?= $current_page == 'kegiatan.php' ? 'active' : '' ?> flex items-center px-4 py-3 rounded-xl text-slate-300 hover:text-white mb-1">
                        <svg class="menu-icon w-5 h-5 mr-3 text-purple-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        <span class="font-medium">Data Kegiatan</span>
                    </a>

                    <a href="pengumuman"
                        class="menu-item <?= $current_page == 'pengumuman.php' ? 'active' : '' ?> flex items-center px-4 py-3 rounded-xl text-slate-300 hover:text-white mb-1">
                        <svg class="menu-icon w-5 h-5 mr-3 text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                            </path>
                        </svg>
                        <span class="font-medium">Pengumuman</span>
                    </a>

                    <?php if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] == 'admin'): ?>
                        <a href="rdm"
                            class="menu-item <?= $current_page == 'rdm.php' ? 'active' : '' ?> flex items-center px-4 py-3 rounded-xl text-slate-300 hover:text-white mb-1">
                            <svg class="menu-icon w-5 h-5 mr-3 text-cyan-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9">
                                </path>
                            </svg>
                            <span class="font-medium">Portal RDM</span>
                        </a>
                    <?php endif; ?>

                    <a href="agenda"
                        class="menu-item <?= $current_page == 'agenda.php' ? 'active' : '' ?> flex items-center px-4 py-3 rounded-xl text-slate-300 hover:text-white mb-1">
                        <svg class="menu-icon w-5 h-5 mr-3 text-orange-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span class="font-medium">Agenda</span>
                    </a>

                    <?php if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] == 'contributor'): ?>
                        <!-- Madrasah Settings for Contributor -->
                        <a href="madrasah"
                            class="menu-item <?= $current_page == 'madrasah.php' ? 'active' : '' ?> flex items-center px-4 py-3 rounded-xl text-slate-300 hover:text-white mb-1">
                            <svg class="menu-icon w-5 h-5 mr-3 text-yellow-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                            <span class="font-medium">Pengaturan Madrasah</span>
                        </a>
                    <?php endif; ?>
                    <?php if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] == 'admin'): ?>
                        <a href="galeri"
                            class="menu-item <?= $current_page == 'galeri.php' ? 'active' : '' ?> flex items-center px-4 py-3 rounded-xl text-slate-300 hover:text-white mb-1">
                            <svg class="menu-icon w-5 h-5 mr-3 text-pink-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="font-medium">Galeri Foto</span>
                        </a>

                        <a href="unduhan"
                            class="menu-item <?= $current_page == 'unduhan.php' ? 'active' : '' ?> flex items-center px-4 py-3 rounded-xl text-slate-300 hover:text-white mb-1">
                            <svg class="menu-icon w-5 h-5 mr-3 text-teal-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            <span class="font-medium">Unduhan</span>
                        </a>
                    <?php endif; ?>
                </div>

                <!-- Data Section -->
                <?php if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] == 'admin'): ?>
                    <div class="mb-6">
                        <p class="px-4 mb-3 text-xs font-bold text-slate-500 uppercase tracking-widest">Data</p>

                        <a href="guru"
                            class="menu-item <?= $current_page == 'guru.php' ? 'active' : '' ?> flex items-center px-4 py-3 rounded-xl text-slate-300 hover:text-white mb-1">
                            <svg class="menu-icon w-5 h-5 mr-3 text-amber-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <span class="font-medium">Data Guru</span>
                        </a>

                        <a href="pengurus"
                            class="menu-item <?= $current_page == 'pengurus.php' ? 'active' : '' ?> flex items-center px-4 py-3 rounded-xl text-slate-300 hover:text-white mb-1">
                            <svg class="menu-icon w-5 h-5 mr-3 text-indigo-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                </path>
                            </svg>
                            <span class="font-medium">Pengurus</span>
                        </a>

                        <a href="pesan"
                            class="menu-item <?= $current_page == 'pesan.php' ? 'active' : '' ?> flex items-center px-4 py-3 rounded-xl text-slate-300 hover:text-white mb-1">
                            <svg class="menu-icon w-5 h-5 mr-3 text-rose-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="font-medium">Pesan Masuk</span>
                        </a>
                    </div>

                    <!-- Sistem Section -->
                    <div class="mb-6">
                        <p class="px-4 mb-3 text-xs font-bold text-slate-500 uppercase tracking-widest">Sistem</p>

                        <a href="users"
                            class="menu-item <?= $current_page == 'users.php' ? 'active' : '' ?> flex items-center px-4 py-3 rounded-xl text-slate-300 hover:text-white mb-1">
                            <svg class="menu-icon w-5 h-5 mr-3 text-lime-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                </path>
                            </svg>
                            <span class="font-medium">Pengguna</span>
                        </a>

                        <a href="identitas"
                            class="menu-item <?= $current_page == 'identitas.php' ? 'active' : '' ?> flex items-center px-4 py-3 rounded-xl text-slate-300 hover:text-white mb-1">
                            <svg class="menu-icon w-5 h-5 mr-3 text-violet-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                </path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="font-medium">Pengaturan</span>
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Logout -->
                <div class="mt-auto pt-4 border-t border-white/10">
                    <a href="logout"
                        class="logout-btn flex items-center px-4 py-3 rounded-xl text-slate-300 hover:text-white transition-all duration-300">
                        <svg class="w-5 h-5 mr-3 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        <span class="font-medium">Logout</span>
                    </a>
                </div>
            </nav>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Top Header -->
            <header
                class="flex justify-between items-center py-4 px-6 bg-white/80 backdrop-blur-lg shadow-sm border-b border-slate-200/50">
                <div class="flex items-center gap-4">
                    <!-- Hamburger Button -->
                    <div id="hamburger" class="hamburger lg:hidden" onclick="toggleSidebar()">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-slate-800">
                            <?php
                            if ($current_page == 'dashboard.php')
                                echo 'Dashboard';
                            elseif ($current_page == 'berita.php')
                                echo 'Kelola Berita';
                            elseif ($current_page == 'kegiatan.php')
                                echo 'Kelola Kegiatan';
                            elseif ($current_page == 'pengumuman.php')
                                echo 'Kelola Pengumuman';
                            elseif ($current_page == 'pengumuman_form.php')
                                echo 'Form Pengumuman';
                            elseif ($current_page == 'rdm.php')
                                echo 'Portal RDM';
                            elseif ($current_page == 'agenda.php')
                                echo 'Kelola Agenda';
                            elseif ($current_page == 'galeri.php')
                                echo 'Kelola Galeri';
                            elseif ($current_page == 'unduhan.php')
                                echo 'Kelola Unduhan';
                            elseif ($current_page == 'guru.php')
                                echo 'Data Guru';
                            elseif ($current_page == 'pengurus.php')
                                echo 'Kelola Pengurus';
                            elseif ($current_page == 'pesan.php')
                                echo 'Kotak Masuk';
                            elseif ($current_page == 'identitas.php')
                                echo 'Pengaturan';
                            elseif ($current_page == 'users.php')
                                echo 'Data Pengguna';
                            elseif ($current_page == 'madrasah.php')
                                echo 'Pengaturan Madrasah';
                            else
                                echo 'Admin Panel';
                            ?>
                        </h2>
                        <p class="text-sm text-slate-500 hidden sm:block">Selamat datang,
                            <?= $_SESSION['admin_name'] ?? 'Admin' ?>!
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <!-- User Menu -->
                    <div class="relative" id="userMenu">
                        <button onclick="toggleUserMenu()"
                            class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-100 transition-all">
                            <div
                                class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-bold shadow-lg shadow-emerald-500/20">
                                <?= strtoupper(substr($_SESSION['admin_name'] ?? 'A', 0, 1)) ?>
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="font-semibold text-slate-700 text-sm">
                                    <?= $_SESSION['admin_name'] ?? 'Admin' ?>
                                </p>
                                <p class="text-xs text-slate-500 capitalize"><?= $_SESSION['admin_role'] ?? 'User' ?>
                                </p>
                            </div>
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <!-- Dropdown -->
                        <div id="userDropdown"
                            class="hidden absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 ring-1 ring-slate-200 z-50">
                            <a href="profile"
                                class="flex items-center px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">
                                <svg class="w-4 h-4 mr-3 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Profile
                            </a>
                            <hr class="my-2 border-slate-100">
                            <a href="logout" class="flex items-center px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                    </path>
                                </svg>
                                Logout
                            </a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Scrollable Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gradient-to-br from-slate-50 to-slate-100 p-6">

                <script>
                    // Sidebar Toggle
                    function toggleSidebar() {
                        const sidebar = document.getElementById('sidebar');
                        const backdrop = document.getElementById('sidebar-backdrop');
                        const hamburger = document.getElementById('hamburger');

                        sidebar.classList.toggle('open');
                        backdrop.classList.toggle('active');
                        hamburger.classList.toggle('active');
                    }

                    // Close sidebar on window resize to desktop
                    window.addEventListener('resize', function () {
                        if (window.innerWidth >= 1024) {
                            const sidebar = document.getElementById('sidebar');
                            const backdrop = document.getElementById('sidebar-backdrop');
                            const hamburger = document.getElementById('hamburger');

                            sidebar.classList.remove('open');
                            backdrop.classList.remove('active');
                            hamburger.classList.remove('active');
                        }
                    });

                    // User Menu Toggle
                    function toggleUserMenu() {
                        document.getElementById('userDropdown').classList.toggle('hidden');
                    }

                    // Close user menu when clicking outside
                    document.addEventListener('click', function (event) {
                        const userMenu = document.getElementById('userMenu');
                        const dropdown = document.getElementById('userDropdown');

                        if (!userMenu.contains(event.target)) {
                            dropdown.classList.add('hidden');
                        }
                    });

                    // Close sidebar with Escape key
                    document.addEventListener('keydown', function (event) {
                        if (event.key === 'Escape') {
                            const sidebar = document.getElementById('sidebar');
                            const backdrop = document.getElementById('sidebar-backdrop');
                            const hamburger = document.getElementById('hamburger');

                            sidebar.classList.remove('open');
                            backdrop.classList.remove('active');
                            hamburger.classList.remove('active');
                        }
                    });
                </script>