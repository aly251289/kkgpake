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
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['delete']);
    $delete = mysqli_query($koneksi, "DELETE FROM guru WHERE id='$id'");
    if ($delete) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data guru berhasil dihapus!',
                icon: 'success',
                confirmButtonColor: '#059669'
            }).then(() => {
                window.location='guru.php';
            });
        });
        </script>";
    }
}
?>

<div class="flex flex-col sm:flex-row justify-between items-center mb-6">
    <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">Data Guru Anggota KKG</h3>
    <div class="flex gap-2">
        <a href="guru_import"
            class="bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold py-2 px-4 rounded-lg shadow-sm transition flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            Import Excel
        </a>
        <a href="guru_form"
            class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Anggota
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-gray-600">
            <thead class="bg-gray-50 text-gray-700 font-bold uppercase text-xs">
                <tr>
                    <th class="px-6 py-4">No</th>
                    <th class="px-6 py-4">Nama Lengkap</th>
                    <th class="px-6 py-4">Asal Madrasah</th>
                    <th class="px-6 py-4">Jabatan</th>
                    <th class="px-6 py-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php
                $no = 1;
                $query = mysqli_query($koneksi, "SELECT * FROM guru ORDER BY nama ASC");
                if (mysqli_num_rows($query) > 0):
                    while ($row = mysqli_fetch_assoc($query)):
                        ?>
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4"><?= $no++ ?></td>
                            <td class="px-6 py-4 font-bold text-gray-800"><?= $row['nama'] ?></td>
                            <td class="px-6 py-4"><?= $row['jabatan'] ?></td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-700 py-1 px-3 rounded-md text-xs font-semibold">
                                    <?= $row['asal_madrasah'] ?></span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="guru_form.php?id=<?= $row['id'] ?>"
                                        class="text-blue-600 hover:text-blue-800 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <a href="#"
                                        onclick="event.preventDefault(); confirmDelete('guru.php?delete=<?= $row['id'] ?>')"
                                        class="text-red-600 hover:text-red-800 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php
                    endwhile;
                else:
                    ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 italic">Belum ada data guru.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>