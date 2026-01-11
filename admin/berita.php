<?php include 'includes/header.php'; ?>
<?php require_once '../config/koneksi.php'; ?>

<?php
// Handle Delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Ownership Check
    if ($_SESSION['admin_role'] != 'admin') {
        $check = mysqli_query($koneksi, "SELECT created_by FROM berita WHERE id='$id'");
        $data = mysqli_fetch_assoc($check);
        if ($data['created_by'] != $_SESSION['admin_id']) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({ title: 'Gagal', text: 'Anda tidak berhak menghapus data ini!', icon: 'error' });
            });
            </script>";
            exit; // Stop execution
        }
    }

    // Get image to delete file
    $query_img = mysqli_query($koneksi, "SELECT gambar FROM berita WHERE id='$id'");
    $row_img = mysqli_fetch_assoc($query_img);

    if ($row_img['gambar']) {
        // Extract filename from URL if it's a local file, not external URL
        // Simple check: if it doesn't start with http
        if (strpos($row_img['gambar'], 'http') === false) {
            $filepath = '../' . $row_img['gambar'];
            if (file_exists($filepath)) {
                unlink($filepath);
            }
        }
    }

    $delete = mysqli_query($koneksi, "DELETE FROM berita WHERE id='$id'");

    if ($delete) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Data berhasil dihapus!',
                icon: 'success',
                confirmButtonColor: '#059669'
            }).then(() => {
                window.location='berita.php';
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
    <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">Kelola Berita</h3>
    <a href="berita_form"
        class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Berita
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-700 uppercase text-xs font-bold tracking-wider">
                    <th class="p-4 border-b border-gray-200 text-center w-16">No</th>
                    <th class="p-4 border-b border-gray-200 w-24">Gambar</th>
                    <th class="p-4 border-b border-gray-200">Judul Berita</th>
                    <th class="p-4 border-b border-gray-200 w-32">Kategori</th>
                    <th class="p-4 border-b border-gray-200 w-24 text-center">Dilihat</th>
                    <th class="p-4 border-b border-gray-200 w-32">Tanggal</th>
                    <th class="p-4 border-b border-gray-200 text-center w-32">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php
                $no = 1;
                $query = mysqli_query($koneksi, "SELECT * FROM berita ORDER BY tanggal DESC");
                while ($row = mysqli_fetch_assoc($query)):
                    ?>
                    <tr class="hover:bg-gray-50 transition">
                        <td class="p-4 text-center text-gray-500 font-medium"><?= $no++ ?></td>
                        <td class="p-4">
                            <?php
                            $img_src = $row['gambar'];
                            if ($img_src && strpos($img_src, 'http') === false) {
                                $img_src = '../' . $img_src;
                            }
                            if ($row['gambar']): ?>
                                <img src="<?= $img_src ?>" alt="Thumb" class="w-16 h-12 object-cover rounded shadow-sm">
                            <?php else: ?>
                                <div
                                    class="w-16 h-12 bg-gray-200 rounded flex items-center justify-center text-gray-400 text-xs">
                                    No Img</div>
                            <?php endif; ?>
                        </td>
                        <td class="p-4 font-semibold text-gray-800 hover:text-emerald-600">
                            <?= substr($row['judul'], 0, 50) . (strlen($row['judul']) > 50 ? '...' : '') ?>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                <?= $row['kategori'] ?>
                            </span>
                        </td>
                        <td class="p-4 text-sm text-center font-bold text-gray-600">
                            <?= number_format($row['views'] ?? 0) ?>
                        </td>
                        <td class="p-4 text-sm text-gray-500">
                            <?= date('d M Y', strtotime($row['tanggal'])) ?>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <?php
                                $is_owner = isset($row['created_by']) ? $row['created_by'] == $_SESSION['admin_id'] : false;
                                if ($_SESSION['admin_role'] == 'admin' || $is_owner):
                                    ?>
                                    <a href="berita_form.php?id=<?= $row['id'] ?>"
                                        class="p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition"
                                        title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                            </path>
                                        </svg>
                                    </a>
                                    <button onclick="copyToClipboard('<?= $row['slug'] ?>')"
                                        class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition"
                                        title="Salin Link">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </button>
                                    <a href="#"
                                        onclick="event.preventDefault(); confirmDelete('berita.php?action=delete&id=<?= $row['id'] ?>')"
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
            Belum ada data berita. Silahkan tambah data baru.
        </div>
    <?php endif; ?>
</div>

<script>
    function copyToClipboard(slug) {
        // Construct absolute URL (assumes admin is in /admin/ subdirectory)
        const baseUrl = window.location.href.split('/admin')[0];
        const fullUrl = `${baseUrl}/post.php?slug=${slug}`;

        navigator.clipboard.writeText(fullUrl).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Link Disalin!',
                text: 'Link berita berhasil disalin ke clipboard.',
                showConfirmButton: false,
                timer: 1500,
                toast: true,
                position: 'top-end'
            });
        }).catch(err => {
            console.error('Failed to copy: ', err);
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Gagal menyalin link.',
                toast: true,
                position: 'top-end'
            });
        });
    }
</script>

<?php include 'includes/footer.php'; ?>