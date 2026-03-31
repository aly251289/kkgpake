<?php include 'includes/header.php'; ?>
<?php
require_once '../config/koneksi.php';
require_once '../includes/database.php'; // Security: Include the new database helper
?>

<?php
// Security: Use prepared statements for all dashboard queries
$total_berita = 0;
$total_kegiatan = 0;
$total_agenda = 0;

if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] != 'admin') {
    $user_id = $_SESSION['admin_id'];
    $query_berita = db_query($koneksi, "SELECT COUNT(*) as total FROM berita WHERE created_by=?", 'i', [$user_id]);
    $total_berita = mysqli_fetch_assoc($query_berita)['total'];

    $query_kegiatan = db_query($koneksi, "SELECT COUNT(*) as total FROM kegiatan WHERE created_by=?", 'i', [$user_id]);
    $total_kegiatan = mysqli_fetch_assoc($query_kegiatan)['total'];

    $query_agenda = db_query($koneksi, "SELECT COUNT(*) as total FROM agenda WHERE created_by=?", 'i', [$user_id]);
    $total_agenda = mysqli_fetch_assoc($query_agenda)['total'];
} else {
    $query_berita = db_query($koneksi, "SELECT COUNT(*) as total FROM berita");
    $total_berita = mysqli_fetch_assoc($query_berita)['total'];

    $query_kegiatan = db_query($koneksi, "SELECT COUNT(*) as total FROM kegiatan");
    $total_kegiatan = mysqli_fetch_assoc($query_kegiatan)['total'];

    $query_agenda = db_query($koneksi, "SELECT COUNT(*) as total FROM agenda");
    $total_agenda = mysqli_fetch_assoc($query_agenda)['total'];
}

$query_unduhan = db_query($koneksi, "SELECT COUNT(*) as total FROM unduhan");
$total_unduhan = mysqli_fetch_assoc($query_unduhan)['total'];

$query_users = db_query($koneksi, "SELECT COUNT(*) as total FROM users");
$total_users = mysqli_fetch_assoc($query_users)['total'];

// Recent Activities
$recent_berita = db_query($koneksi, "SELECT judul, created_at FROM berita ORDER BY created_at DESC LIMIT 5");
$recent_kegiatan = db_query($koneksi, "SELECT judul, created_at FROM kegiatan ORDER BY created_at DESC LIMIT 5");

// Pending Users (Admin Only)
$pending_users = null;
if ($_SESSION['admin_role'] == 'admin') {
    $pending_users = db_query($koneksi, "SELECT * FROM users WHERE status='pending' ORDER BY created_at DESC LIMIT 5");
}

// Check if contributor needs to change password
$show_password_reminder = false;
if ($_SESSION['admin_role'] == 'contributor') {
    $q_pw = db_query($koneksi, "SELECT password_changed FROM users WHERE id=?", 'i', [$_SESSION['admin_id']]);
    if ($q_pw) {
        $d_pw = mysqli_fetch_assoc($q_pw);
        if (isset($d_pw['password_changed']) && $d_pw['password_changed'] == 0) {
            $show_password_reminder = true;
        }
    }
}

// Handle success message
$password_success = isset($_SESSION['password_success']) && $_SESSION['password_success'];
if ($password_success) {
    unset($_SESSION['password_success']);
}
?>

<style>
    /* Dashboard Premium Styles */
    .stat-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        transition: transform 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.2) rotate(10deg);
    }

    .glass-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    .activity-item {
        transition: all 0.3s ease;
    }

    .activity-item:hover {
        background: rgba(16, 185, 129, 0.1);
        padding-left: 1.5rem;
    }

    .quick-action {
        transition: all 0.3s ease;
    }

    .quick-action:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }

    .greeting-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    @keyframes wave {

        0%,
        100% {
            transform: rotate(0deg);
        }

        25% {
            transform: rotate(20deg);
        }

        75% {
            transform: rotate(-15deg);
        }
    }

    .wave-emoji {
        display: inline-block;
        animation: wave 1.5s ease-in-out infinite;
        transform-origin: 70% 70%;
    }

    .number-animate {
        font-variant-numeric: tabular-nums;
    }
</style>

<!-- Hero Greeting Section -->
<div class="greeting-bg rounded-2xl p-8 mb-8 text-white relative overflow-hidden shadow-2xl">
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>

    <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold mb-2">
                Selamat Datang, <?= $_SESSION['admin_name'] ?>! <span class="wave-emoji">👋</span>
            </h1>
            <p class="text-white/80 text-sm md:text-base">
                <?php
                $hour = date('H');
                if ($hour >= 5 && $hour < 12)
                    echo "Semoga pagi Anda menyenangkan!";
                elseif ($hour >= 12 && $hour < 17)
                    echo "Semoga siang Anda produktif!";
                elseif ($hour >= 17 && $hour < 21)
                    echo "Semoga sore Anda menyenangkan!";
                else
                    echo "Selamat bekerja di malam hari!";
                ?>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <p class="text-white/60 text-sm">Hari ini</p>
                <p class="font-bold"><?= strftime('%A, %d %B %Y', time()) ?></p>
            </div>
            <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<?php $grid_cols = $_SESSION['admin_role'] == 'admin' ? 'lg:grid-cols-5' : 'lg:grid-cols-3'; ?>
<div class="grid grid-cols-2 md:grid-cols-3 <?= $grid_cols ?> gap-4 md:gap-6 mb-8">
    <!-- Berita -->
    <a href="berita" class="stat-card rounded-2xl p-5 cursor-pointer group">
        <div class="flex items-center justify-between mb-4">
            <div
                class="stat-icon w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                    </path>
                </svg>
            </div>
            <svg class="w-5 h-5 text-slate-300 group-hover:text-blue-500 group-hover:translate-x-1 transition-all"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </div>
        <h3 class="text-3xl font-extrabold text-slate-800 number-animate"><?= $total_berita ?></h3>
        <p class="text-slate-500 text-sm font-medium">Total Berita</p>
    </a>

    <!-- Kegiatan -->
    <a href="kegiatan" class="stat-card rounded-2xl p-5 cursor-pointer group">
        <div class="flex items-center justify-between mb-4">
            <div
                class="stat-icon w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-emerald-500/30">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
            </div>
            <svg class="w-5 h-5 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </div>
        <h3 class="text-3xl font-extrabold text-slate-800 number-animate"><?= $total_kegiatan ?></h3>
        <p class="text-slate-500 text-sm font-medium">Kegiatan</p>
    </a>

    <!-- Agenda -->
    <a href="agenda" class="stat-card rounded-2xl p-5 cursor-pointer group">
        <div class="flex items-center justify-between mb-4">
            <div
                class="stat-icon w-12 h-12 rounded-xl bg-gradient-to-br from-pink-500 to-rose-600 flex items-center justify-center shadow-lg shadow-pink-500/30">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
            <svg class="w-5 h-5 text-slate-300 group-hover:text-pink-500 group-hover:translate-x-1 transition-all"
                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </div>
        <h3 class="text-3xl font-extrabold text-slate-800 number-animate"><?= $total_agenda ?></h3>
        <p class="text-slate-500 text-sm font-medium">Jadwal Agenda</p>
    </a>

    <?php if ($_SESSION['admin_role'] == 'admin'): ?>
        <!-- Unduhan -->
        <a href="unduhan" class="stat-card rounded-2xl p-5 cursor-pointer group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="stat-icon w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center shadow-lg shadow-purple-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                </div>
                <svg class="w-5 h-5 text-slate-300 group-hover:text-purple-500 group-hover:translate-x-1 transition-all"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
            <h3 class="text-3xl font-extrabold text-slate-800 number-animate"><?= $total_unduhan ?></h3>
            <p class="text-slate-500 text-sm font-medium">File Unduhan</p>
        </a>

        <!-- Users -->
        <a href="users" class="stat-card rounded-2xl p-5 cursor-pointer group">
            <div class="flex items-center justify-between mb-4">
                <div
                    class="stat-icon w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-500/30">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>
                <svg class="w-5 h-5 text-slate-300 group-hover:text-amber-500 group-hover:translate-x-1 transition-all"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
            <h3 class="text-3xl font-extrabold text-slate-800 number-animate"><?= $total_users ?></h3>
            <p class="text-slate-500 text-sm font-medium">Pengguna</p>
        </a>
    <?php endif; ?>
</div>

<!-- Quick Actions & Activity Row -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

    <!-- Quick Actions -->
    <div class="glass-card rounded-2xl p-6 shadow-xl">
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z">
                </path>
            </svg>
            Aksi Cepat
        </h3>
        <div class="grid grid-cols-2 gap-3">
            <a href="berita_form"
                class="quick-action flex flex-col items-center justify-center p-4 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl text-white text-center">
                <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="text-xs font-semibold">Berita Baru</span>
            </a>
            <a href="kegiatan_form"
                class="quick-action flex flex-col items-center justify-center p-4 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl text-white text-center">
                <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="text-xs font-semibold">Kegiatan Baru</span>
            </a>
            <a href="agenda_form"
                class="quick-action flex flex-col items-center justify-center p-4 bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl text-white text-center">
                <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                <span class="text-xs font-semibold">Agenda Baru</span>
            </a>
            <a href="pengumuman_form"
                class="quick-action flex flex-col items-center justify-center p-4 bg-gradient-to-br from-amber-500 to-orange-600 rounded-xl text-white text-center">
                <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                    </path>
                </svg>
                <span class="text-xs font-semibold">Pengumuman</span>
            </a>
        </div>
    </div>

    <!-- Recent Berita -->
    <div class="glass-card rounded-2xl p-6 shadow-xl">
        <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z">
                </path>
            </svg>
            Berita Terbaru
        </h3>
        <div class="space-y-2">
            <?php if (mysqli_num_rows($recent_berita) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($recent_berita)): ?>
                    <div class="activity-item p-3 rounded-lg border border-slate-100">
                        <p class="text-sm font-medium text-slate-700 truncate"><?= htmlspecialchars($row['judul']) ?></p>
                        <p class="text-xs text-slate-400"><?= date('d M Y', strtotime($row['created_at'])) ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-slate-400 text-sm text-center py-4">Belum ada berita</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Pending Users or Recent Kegiatan -->
    <?php if ($pending_users && mysqli_num_rows($pending_users) > 0): ?>
        <div class="glass-card rounded-2xl p-6 shadow-xl border-2 border-amber-200">
            <h3 class="text-lg font-bold text-amber-600 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
                Menunggu Persetujuan
            </h3>
            <div class="space-y-2">
                <?php while ($row = mysqli_fetch_assoc($pending_users)): ?>
                    <div class="flex items-center justify-between p-3 rounded-lg bg-amber-50 border border-amber-100">
                        <div>
                            <p class="text-sm font-medium text-slate-700"><?= htmlspecialchars($row['nama_lengkap']) ?></p>
                            <p class="text-xs text-slate-400"><?= htmlspecialchars($row['nama_sekolah']) ?></p>
                        </div>
                        <a href="approve_user?id=<?= $row['id'] ?>"
                            class="px-3 py-1 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-lg transition">
                            Setujui
                        </a>
                    </div>
                <?php endwhile; ?>
            </div>
            <a href="users" class="mt-4 block text-center text-sm text-amber-600 hover:text-amber-700 font-medium">
                Lihat Semua →
            </a>
        </div>
    <?php else: ?>
        <div class="glass-card rounded-2xl p-6 shadow-xl">
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                Kegiatan Terbaru
            </h3>
            <div class="space-y-2">
                <?php if (mysqli_num_rows($recent_kegiatan) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($recent_kegiatan)): ?>
                        <div class="activity-item p-3 rounded-lg border border-slate-100">
                            <p class="text-sm font-medium text-slate-700 truncate"><?= htmlspecialchars($row['judul']) ?></p>
                            <p class="text-xs text-slate-400"><?= date('d M Y', strtotime($row['created_at'])) ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-slate-400 text-sm text-center py-4">Belum ada kegiatan</p>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- System Info Footer -->
<div class="glass-card rounded-2xl p-4 text-center text-slate-400 text-sm">
    <p>🚀 KKG Panel v2.0 — <span class="font-semibold text-emerald-600"><?= $_SESSION['admin_role'] ?></span> Mode • PHP
        <?= phpversion() ?>
    </p>
</div>

<?php if ($show_password_reminder): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: '🔐 Ganti Password Anda!',
            html: 'Untuk keamanan akun, segera ganti password default Anda dengan password baru yang kuat.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ganti Sekarang',
            cancelButtonText: 'Nanti Saja'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'change_password';
            }
        });
    });
    </script>
<?php endif; ?>

<?php if ($password_success): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Berhasil!',
            text: 'Password Anda berhasil diubah.',
            icon: 'success'
        });
    });
    </script>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>