<?php include 'includes/header.php'; ?>
<?php require_once '../config/koneksi.php'; ?>

<?php
// Handle Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Get image to delete file
    $query_img = mysqli_query($koneksi, "SELECT foto FROM galeri WHERE id='$id'");
    $row_img = mysqli_fetch_assoc($query_img);

    if ($row_img['foto']) {
        if (strpos($row_img['foto'], 'http') === false) {
            $filepath = '../' . $row_img['foto'];
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }
    }

    $delete = mysqli_query($koneksi, "DELETE FROM galeri WHERE id='$id'");

    if ($delete) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Foto galeri berhasil dihapus!',
                icon: 'success',
                confirmButtonColor: '#059669'
            }).then(() => {
                window.location='galeri.php';
            });
        });
        </script>";
    } else {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Gagal!',
                text: 'Gagal menghapus foto!',
                icon: 'error',
                confirmButtonColor: '#d33'
            });
        });
        </script>";
    }
}
?>

<div class="flex flex-col sm:flex-row justify-between items-center mb-6">
    <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">Kelola Galeri Foto</h3>
    <a href="galeri_form"
        class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Foto
    </a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
    <?php
    $query = mysqli_query($koneksi, "SELECT * FROM galeri ORDER BY tanggal DESC");
    if (mysqli_num_rows($query) > 0):
        while ($row = mysqli_fetch_assoc($query)):
            ?>
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden group">
                <div class="relative h-48 overflow-hidden">
                    <img src="<?= strpos($row['foto'], 'http') !== false ? $row['foto'] : '../' . $row['foto'] ?>"
                        alt="<?= $row['judul'] ?>"
                        class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                    <div
                        class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-3">
                        <a href="galeri_form.php?id=<?= $row['id'] ?>"
                            class="p-2 bg-yellow-400 text-white rounded-full hover:bg-yellow-500 transition shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </a>
                        <a href="#"
                            onclick="event.preventDefault(); confirmDelete('galeri.php?action=delete&id=<?= $row['id'] ?>')"
                            class="p-2 bg-red-600 text-white rounded-full hover:bg-red-700 transition shadow-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
                <div class="p-4">
                    <h4 class="font-bold text-gray-800 text-lg mb-1 truncate" title="<?= $row['judul'] ?>"><?= $row['judul'] ?>
                    </h4>
                    <p class="text-xs text-gray-500 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                            </path>
                        </svg>
                        <?= date('d M Y', strtotime($row['tanggal'])) ?>
                    </p>
                </div>
            </div>
            <?php
        endwhile;
    else:
        ?>
        <div
            class="col-span-full p-12 text-center text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-300">
            Belum ada foto di galeri. Silakan tambah foto baru.
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>