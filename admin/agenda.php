<?php include 'includes/header.php'; ?>
<?php require_once '../config/koneksi.php'; ?>

<?php
// Handle Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Ownership Check
    if ($_SESSION['admin_role'] != 'admin') {
        $check = mysqli_query($koneksi, "SELECT created_by FROM agenda WHERE id='$id'");
        $data = mysqli_fetch_assoc($check);
        if ($data['created_by'] != $_SESSION['admin_id']) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ title: 'Gagal', text: 'Anda tidak berhak menghapus data ini!', icon: 'error' });
            });
            </script>";
            exit;
        }
    }

    $delete = mysqli_query($koneksi, "DELETE FROM agenda WHERE id='$id'");

    if ($delete) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data agenda berhasil dihapus!',
                icon: 'success',
                confirmButtonColor: '#059669'
            }).then(() => {
                window.location='agenda.php';
            });
        });
        </script>";
    } else {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Gagal!',
                text: 'Gagal menghapus data!',
                icon: 'error',
                confirmButtonColor: '#d33'
            });
        });
        </script>";
    }
}
?>

<div class="flex flex-col sm:flex-row justify-between items-center mb-6">
    <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">Kelola Agenda / Jadwal</h3>
    <a href="agenda_form"
        class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Agenda
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-700 uppercase text-xs font-bold tracking-wider">
                    <th class="p-4 border-b border-gray-200 text-center w-16">No</th>
                    <th class="p-4 border-b border-gray-200">Nama Agenda</th>
                    <th class="p-4 border-b border-gray-200 w-40">Tanggal</th>
                    <th class="p-4 border-b border-gray-200 w-48">Lokasi</th>
                    <th class="p-4 border-b border-gray-200">Keterangan</th>
                    <th class="p-4 border-b border-gray-200 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php
                $no = 1;
                $query = mysqli_query($koneksi, "SELECT * FROM agenda ORDER BY tanggal DESC");
                while ($row = mysqli_fetch_assoc($query)):
                    ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-center text-gray-500 font-medium"><?= $no++ ?></td>
                        <td class="p-4 font-semibold text-gray-800 hover:text-emerald-600">
                            <?= $row['judul'] ?>
                        </td>
                        <td class="p-4 text-sm text-gray-500">
                            <?= date('d M Y', strtotime($row['tanggal'])) ?>
                        </td>
                        <td class="p-4 text-sm text-gray-600">
                            <?= $row['lokasi'] ?>
                        </td>
                        <td class="p-4 text-sm text-gray-600">
                            <?= $row['keterangan'] ? substr($row['keterangan'], 0, 50) . '...' : '-' ?>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <?php if ($_SESSION['admin_role'] == 'admin' || $row['created_by'] == $_SESSION['admin_id']): ?>
                                    <a href="agenda_form.php?id=<?= $row['id'] ?>"
                                        class="p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <a href="#"
                                        onclick="event.preventDefault(); confirmDelete('agenda.php?action=delete&id=<?= $row['id'] ?>')"
                                        class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition"
                                        title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </a>
                                <?php else: ?>
                                    <span class="text-xs text-gray-400">Read Only</span>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php if (mysqli_num_rows($query) == 0): ?>
        <div class="p-8 text-center text-gray-500">
            Belum ada data agenda. Silahkan tambah agenda baru.
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>