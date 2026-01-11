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

    // Get image to delete file
    $query_img = mysqli_query($koneksi, "SELECT foto FROM pengurus WHERE id='$id'");
    $row_img = mysqli_fetch_assoc($query_img);

    if ($row_img['foto']) {
        if (strpos($row_img['foto'], 'http') === false) {
            $filepath = '../' . $row_img['foto'];
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }
    }

    $delete = mysqli_query($koneksi, "DELETE FROM pengurus WHERE id='$id'");

    if ($delete) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data pengurus berhasil dihapus!',
                icon: 'success',
                confirmButtonColor: '#059669'
            }).then(() => {
                window.location='pengurus.php';
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
    <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">Kelola Pengurus</h3>
    <a href="pengurus_form"
        class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Pengurus
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-700 uppercase text-xs font-bold tracking-wider">
                    <th class="p-4 border-b border-gray-200 text-center w-16">No</th>
                    <th class="p-4 border-b border-gray-200 w-16 text-center">Urutan</th>
                    <th class="p-4 border-b border-gray-200 w-24">Foto</th>
                    <th class="p-4 border-b border-gray-200">Nama Lengkap</th>
                    <th class="p-4 border-b border-gray-200">Jabatan</th>
                    <th class="p-4 border-b border-gray-200 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php
                $no = 1;
                $query = mysqli_query($koneksi, "SELECT * FROM pengurus ORDER BY urutan ASC");
                while ($row = mysqli_fetch_assoc($query)):
                    ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-center text-gray-500 font-medium"><?= $no++ ?></td>
                        <td class="p-4 text-center">
                            <span
                                class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 font-bold text-gray-600">
                                <?= $row['urutan'] ?>
                            </span>
                        </td>
                        <td class="p-4">
                            <?php if ($row['foto']): ?>
                                <img src="<?= strpos($row['foto'], 'http') !== false ? $row['foto'] : '../' . $row['foto'] ?>"
                                    alt="Foto" class="w-12 h-12 object-cover rounded-full shadow-sm border border-gray-200">
                            <?php else: ?>
                                <div
                                    class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 text-xs">
                                    No Img</div>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 font-semibold text-gray-800">
                            <?= $row['nama'] ?>
                        </td>
                        <td class="p-4 text-gray-600">
                            <span class="px-2 py-1 bg-emerald-50 text-emerald-700 rounded text-sm font-medium">
                                <?= $row['jabatan'] ?>
                            </span>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="pengurus_form.php?id=<?= $row['id'] ?>"
                                    class="p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition"
                                    title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </a>
                                <a href="#"
                                    onclick="event.preventDefault(); confirmDelete('pengurus.php?action=delete&id=<?= $row['id'] ?>')"
                                    class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition"
                                    title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <?php if (mysqli_num_rows($query) == 0): ?>
        <div class="p-8 text-center text-gray-500">
            Belum ada data pengurus.
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>