<?php include 'includes/header.php'; ?>
<?php require_once '../config/koneksi.php'; ?>

<?php
// Handle Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Get file info to delete physical file
    $query_file = mysqli_query($koneksi, "SELECT nama_file FROM unduhan WHERE id='$id'");
    $row_file = mysqli_fetch_assoc($query_file);

    if ($row_file['nama_file']) {
        $filepath = '../assets/files/' . $row_file['nama_file'];
        if (file_exists($filepath)) {
            unlink($filepath);
        }
    }

    $delete = mysqli_query($koneksi, "DELETE FROM unduhan WHERE id='$id'");

    if ($delete) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'File berhasil dihapus!',
                icon: 'success',
                confirmButtonColor: '#059669'
            }).then(() => {
                window.location='unduhan.php';
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
    <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">Kelola Unduhan</h3>
    <a href="unduhan_form"
        class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah File
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-700 uppercase text-xs font-bold tracking-wider">
                    <th class="p-4 border-b border-gray-200 text-center w-16">No</th>
                    <th class="p-4 border-b border-gray-200">Judul File</th>
                    <th class="p-4 border-b border-gray-200 w-32">Kategori</th>
                    <th class="p-4 border-b border-gray-200 w-24 text-center">Tipe</th>
                    <th class="p-4 border-b border-gray-200 w-24">Ukuran</th>
                    <th class="p-4 border-b border-gray-200 w-32">Diunggah Oleh</th>
                    <th class="p-4 border-b border-gray-200 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php
                $no = 1;
                $query = mysqli_query($koneksi, "SELECT * FROM unduhan ORDER BY tanggal_upload DESC");
                while ($row = mysqli_fetch_assoc($query)):
                    // Icon based on type
                    $icon_color = 'text-gray-500';
                    if ($row['tipe_file'] == 'pdf')
                        $icon_color = 'text-red-500';
                    elseif ($row['tipe_file'] == 'docx' || $row['tipe_file'] == 'doc')
                        $icon_color = 'text-blue-500';
                    elseif ($row['tipe_file'] == 'xlsx' || $row['tipe_file'] == 'xls')
                        $icon_color = 'text-green-500';
                    ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-center text-gray-500 font-medium"><?= $no++ ?></td>
                        <td class="p-4 font-semibold text-gray-800">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 <?= $icon_color ?>" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <?= $row['judul'] ?>
                            </div>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-700">
                                <?= $row['kategori'] ?>
                            </span>
                        </td>
                        <td class="p-4 text-center uppercase text-xs font-bold text-gray-500">
                            <?= $row['tipe_file'] ?>
                        </td>
                        <td class="p-4 text-sm text-gray-500">
                            <?= $row['ukuran_file'] ?>
                        </td>
                        <td class="p-4 text-sm text-gray-500">
                            <?= $row['diunggah_oleh'] ?>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="../assets/files/<?= $row['nama_file'] ?>" target="_blank"
                                    class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition"
                                    title="Download">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </a>
                                <button onclick="copyToClipboard('<?= $row['nama_file'] ?>')"
                                    class="p-2 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition"
                                    title="Copy Link">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                </button>
                                <a href="unduhan_form.php?id=<?= $row['id'] ?>"
                                    class="p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition"
                                    title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </a>
                                <a href="#"
                                    onclick="event.preventDefault(); confirmDelete('unduhan.php?action=delete&id=<?= $row['id'] ?>')"
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
            Belum ada data unduhan. Silahkan upload file baru.
        </div>
    <?php endif; ?>
</div>


<script>
    function copyToClipboard(filename) {
        // Construct absolute URL
        // Since we are in admin/, the files are in ../assets/files/
        // We use URL constructor relative to current page location
        const fullUrl = new URL('../assets/files/' + filename, window.location.href).href;

        navigator.clipboard.writeText(fullUrl).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Link Disalin!',
                text: 'Link file berhasil disalin ke clipboard',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        }).catch(err => {
            console.error('Failed to copy: ', err);
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Gagal menyalin link',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        });
    }
</script>

<?php include 'includes/footer.php'; ?>