<?php include 'includes/header.php'; ?>
<?php
// Access Check
if (empty($_SESSION['admin_role']) || $_SESSION['admin_role'] != 'admin') {
    echo "<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Akses Ditolak',
            text: 'Anda tidak memiliki hak akses ke halaman ini!',
            icon: 'error'
        }).then(() => {
            window.location='dashboard.php';
        });
    });
    </script>";
    include 'includes/footer.php';
    exit;
}

// Delete Logic
if (isset($_GET['delete'])) {
    $id_delete = mysqli_real_escape_string($koneksi, $_GET['delete']);
    // Prevent self-deletion
    if ($id_delete == $_SESSION['admin_id']) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({ title: 'Gagal', text: 'Tidak bisa menghapus akun sendiri!', icon: 'error' });
        });
        </script>";
    } else {
        $del = mysqli_query($koneksi, "DELETE FROM users WHERE id='$id_delete'");
        if ($del) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ title: 'Berhasil', text: 'Pengguna berhasil dihapus!', icon: 'success' }).then(() => { window.location='users.php'; });
            });
            </script>";
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ title: 'Gagal', text: 'Terjadi kesalahan saat menghapus!', icon: 'error' });
            });
            </script>";
        }
    }
}
?>

<div class="flex flex-col sm:flex-row justify-between items-center mb-6">
    <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">Manajemen Pengguna</h3>
    <a href="users_form"
        class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition transform hover:-translate-y-0.5 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Pengguna
    </a>
</div>

<!-- Filter Tabs -->
<div class="mb-4 flex gap-2">
    <a href="users.php?status=all"
        class="px-4 py-2 rounded-lg font-medium transition <?= !isset($_GET['status']) || $_GET['status'] == 'all' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
        Semua
    </a>
    <a href="users.php?status=active"
        class="px-4 py-2 rounded-lg font-medium transition <?= isset($_GET['status']) && $_GET['status'] == 'active' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
        Aktif
    </a>
    <a href="users.php?status=pending"
        class="px-4 py-2 rounded-lg font-medium transition <?= isset($_GET['status']) && $_GET['status'] == 'pending' ? 'bg-amber-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' ?>">
        Pending
        <?php
        $pending_count_query = mysqli_query($koneksi, "SELECT COUNT(*) as count FROM users WHERE status='pending'");
        $pending_count = mysqli_fetch_assoc($pending_count_query)['count'] ?? 0;
        if ($pending_count > 0):
            ?>
            <span
                class="ml-1 bg-white text-amber-600 px-2 py-0.5 rounded-full text-xs font-bold"><?= $pending_count ?></span>
        <?php endif; ?>
    </a>
</div>

<div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr
                    class="bg-gray-50 text-gray-600 uppercase text-xs font-bold tracking-wider border-b border-gray-100">
                    <th class="p-5">No</th>
                    <th class="p-5">Nama Lengkap</th>
                    <th class="p-5">Sekolah / Lembaga</th>
                    <th class="p-5">Username</th>
                    <th class="p-5">Role</th>
                    <th class="p-5">Status</th>
                    <th class="p-5 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php
                // Filter status
                $status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
                $where_clause = "";
                if ($status_filter == 'pending') {
                    $where_clause = "WHERE status = 'pending'";
                } elseif ($status_filter == 'active') {
                    $where_clause = "WHERE status = 'active'";
                }

                $no = 1;
                // Check if status column exists first
                $check_col = mysqli_query($koneksi, "SHOW COLUMNS FROM users LIKE 'status'");
                $has_status = mysqli_num_rows($check_col) > 0;

                if ($has_status) {
                    $query = mysqli_query($koneksi, "SELECT * FROM users $where_clause ORDER BY status ASC, nama_lengkap ASC");
                } else {
                    $query = mysqli_query($koneksi, "SELECT * FROM users ORDER BY nama_lengkap ASC");
                }
                while ($row = mysqli_fetch_assoc($query)):
                    ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-5 text-gray-500 font-medium">
                            <?= $no++ ?>
                        </td>
                        <td class="p-5 font-bold text-gray-800">
                            <?= $row['nama_lengkap'] ?>
                        </td>
                        <td class="p-5">
                            <?php if ($row['nama_sekolah']): ?>
                                <div class="flex flex-col">
                                    <span class="font-semibold text-gray-700"><?= $row['nama_sekolah'] ?></span>
                                    <a href="../mi/<?= $row['slug_sekolah'] ?>" target="_blank"
                                        class="text-xs text-blue-500 hover:underline">Lihat Web ↗</a>
                                </div>
                            <?php else: ?>
                                <span class="text-gray-400">-</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-5 text-gray-600">
                            <?= $row['username'] ?>
                        </td>
                        <td class="p-5">
                            <?php if ($row['role'] == 'admin'): ?>
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700 border border-purple-200 uppercase">Administrator</span>
                            <?php else: ?>
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200 uppercase">Kontributor</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-5">
                            <?php
                            $user_status = $row['status'] ?? 'active';
                            if ($user_status == 'pending'):
                                ?>
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 border border-amber-200 uppercase">Pending</span>
                            <?php elseif ($user_status == 'active'): ?>
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200 uppercase">Aktif</span>
                            <?php else: ?>
                                <span
                                    class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200 uppercase">Suspended</span>
                            <?php endif; ?>
                        </td>
                        <td class="p-5 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <?php if ($user_status == 'pending'): ?>
                                    <!-- Approve Button -->
                                    <button onclick="approveUser(<?= $row['id'] ?>)"
                                        class="text-green-500 hover:text-green-700 bg-green-50 hover:bg-green-100 p-2 rounded-lg transition"
                                        title="Setujui">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </button>
                                    <!-- Reject Button -->
                                    <button onclick="rejectUser(<?= $row['id'] ?>)"
                                        class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition"
                                        title="Tolak">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                <?php else: ?>
                                    <a href="users_form?id=<?= $row['id'] ?>"
                                        class="text-blue-500 hover:text-blue-700 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition"
                                        title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <?php if ($row['id'] != $_SESSION['admin_id']): ?>
                                        <button onclick="confirmDelete('users.php?delete=<?= $row['id'] ?>')"
                                            class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition"
                                            title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function confirmDelete(url) {
        Swal.fire({
            title: 'Apakah anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        })
    }

    function approveUser(userId) {
        Swal.fire({
            title: 'Setujui Pendaftaran?',
            text: "User akan dapat login setelah disetujui",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'approve_user?id=' + userId;
            }
        })
    }

    function rejectUser(userId) {
        Swal.fire({
            title: 'Tolak Pendaftaran?',
            text: "User akan menerima notifikasi WA dan data akan dihapus",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'reject_user?id=' + userId;
            }
        })
    }
</script>

<?php include 'includes/footer.php'; ?>