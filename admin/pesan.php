<?php include 'includes/header.php'; ?>
<?php
// Access Check
if (empty($_SESSION['admin_role']) || $_SESSION['admin_role'] != 'admin') {
    echo "<script>window.location='dashboard.php';</script>";
    exit;
}
?>
<?php require_once '../config/koneksi.php'; ?>

<?php
// Handle Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $delete = mysqli_query($koneksi, "DELETE FROM pesan WHERE id='$id'");

    if ($delete) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Pesan berhasil dihapus!',
                icon: 'success',
                confirmButtonColor: '#059669'
            }).then(() => {
                window.location='pesan.php';
            });
        });
        </script>";
    } else {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Gagal!',
                text: 'Gagal menghapus pesan!',
                icon: 'error',
                confirmButtonColor: '#d33'
            });
        });
        </script>";
    }
}
?>

<div class="flex flex-col sm:flex-row justify-between items-center mb-6">
    <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">Kotak Masuk</h3>
    <!-- Optional: Add filters or search here later -->
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-700 uppercase text-xs font-bold tracking-wider">
                    <th class="p-4 border-b border-gray-200 text-center w-16">No</th>
                    <th class="p-4 border-b border-gray-200 w-48">Pengirim</th>
                    <th class="p-4 border-b border-gray-200 w-48">Subjek</th>
                    <th class="p-4 border-b border-gray-200">Isi Pesan</th>
                    <th class="p-4 border-b border-gray-200 w-32">Tanggal</th>
                    <th class="p-4 border-b border-gray-200 text-center w-24">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php
                $no = 1;
                $query = mysqli_query($koneksi, "SELECT * FROM pesan ORDER BY tanggal DESC");
                while ($row = mysqli_fetch_assoc($query)):
                    ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-center text-gray-500 font-medium"><?= $no++ ?></td>
                        <td class="p-4">
                            <p class="font-bold text-gray-800"><?= htmlspecialchars($row['nama']) ?></p>
                            <p class="text-xs text-gray-500"><?= htmlspecialchars($row['email']) ?></p>
                        </td>
                        <td class="p-4 text-gray-700 font-medium">
                            <span
                                class="inline-block px-2 py-0.5 rounded textxs bg-gray-100 text-gray-600 border border-gray-200">
                                <?= htmlspecialchars($row['subjek']) ?? 'Tanpa Subjek' ?>
                            </span>
                        </td>
                        <td class="p-4 text-gray-600 text-sm">
                            <?= substr(htmlspecialchars($row['isi_pesan']), 0, 80) . (strlen($row['isi_pesan']) > 80 ? '...' : '') ?>
                        </td>
                        <td class="p-4 text-sm text-gray-500">
                            <?= date('d M Y', strtotime($row['tanggal'])) ?>
                        </td>
                        <td class="p-4 text-center">
                            <a href="#"
                                onclick="event.preventDefault(); confirmDelete('pesan.php?action=delete&id=<?= $row['id'] ?>')"
                                class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition inline-block"
                                title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                    </path>
                                </svg>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php if (mysqli_num_rows($query) == 0): ?>
        <div class="p-8 text-center text-gray-500">
            Belum ada pesan masuk.
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>