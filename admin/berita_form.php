<?php include 'includes/header.php'; ?>
<?php require_once '../config/koneksi.php'; ?>

<?php
$id = '';
$judul = '';
$kategori = '';
$isi = '';
$tanggal = date('Y-m-d');
$gambar = '';
$is_featured = 0; // Default
$action = 'add';
$waktu_input = ''; // Add helper for slug if needed

if (isset($_GET['id'])) {
    $action = 'edit';
    $id = mysqli_real_escape_string($koneksi, $_GET['id']);
    $query = mysqli_query($koneksi, "SELECT * FROM berita WHERE id='$id'");
    if (mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $judul = $row['judul'];
        $kategori = $row['kategori'];
        $tags = isset($row['tags']) ? $row['tags'] : '';
        $isi = $row['isi'];
        $tanggal = $row['tanggal'];
        $gambar = $row['gambar'];
        $is_featured = isset($row['is_featured']) ? $row['is_featured'] : 0;
    } else {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Tidak Ditemukan',
                text: 'Data berita tidak ditemukan!',
                icon: 'error',
                confirmButtonColor: '#d33'
            }).then(() => {
                window.location='berita.php';
            });
        });
        </script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // DEBUG: Start Request



    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    // Simple slug generator
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));

    // Ensure slug is unique
    $check_slug = mysqli_query($koneksi, "SELECT id FROM berita WHERE slug = '$slug' AND id != '$id'");
    if (mysqli_num_rows($check_slug) > 0) {
        $slug = $slug . '-' . time();
    }

    $kategori = mysqli_real_escape_string($koneksi, $_POST['kategori']);
    $tags = isset($_POST['tags']) ? mysqli_real_escape_string($koneksi, $_POST['tags']) : '';
    $isi = mysqli_real_escape_string($koneksi, $_POST['isi']);
    $tanggal = $_POST['tanggal'];
    $penulis = $_SESSION['admin_name'];

    // Featured handling
    if ($_SESSION['admin_role'] == 'admin') {
        $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    } else {
        if ($id) {
            // Preserve existing value
            $q_ex = mysqli_query($koneksi, "SELECT is_featured FROM berita WHERE id='$id'");
            $d_ex = mysqli_fetch_assoc($q_ex);
            $is_featured = $d_ex['is_featured'];
        } else {
            $is_featured = 0;
        }
    }

    // Image Upload Handling
    $upload_error = false;
    $image_path = $gambar; // Default to existing image

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['gambar']['name'];
        $filetype = $_FILES['gambar']['type'];
        $filesize = $_FILES['gambar']['size'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        if (!in_array(strtolower($ext), $allowed)) {


            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Format Salah',
                    text: 'Format file tidak diizinkan! Gunakan JPG, PNG, atau GIF.',
                    icon: 'warning',
                    confirmButtonColor: '#f59e0b'
                });
            });
            </script>";
            $upload_error = true;
        } else {
            // Generate unique name
            $new_filename = uniqid() . '.' . $ext;
            $target_dir = "../assets/img/berita/";

            // Create dir if not exists (redundant safety)
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                // === AUTO RESIZE LOGIC ===
                list($width, $height) = getimagesize($target_file);
                $max_width = 1000;

                if ($width > $max_width) {
                    $ratio = $max_width / $width;
                    $new_width = $max_width;
                    $new_height = $height * $ratio;

                    $src = imagecreatefromstring(file_get_contents($target_file));
                    $dst = imagecreatetruecolor($new_width, $new_height);

                    if ($ext == 'png' || $ext == 'gif') {
                        imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
                        imagealphablending($dst, false);
                        imagesavealpha($dst, true);
                    }

                    imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

                    if (strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg') {
                        imagejpeg($dst, $target_file, 80);
                    } elseif (strtolower($ext) == 'png') {
                        imagepng($dst, $target_file, 8);
                    } elseif (strtolower($ext) == 'gif') {
                        imagegif($dst, $target_file);
                    }

                    imagedestroy($src);
                    imagedestroy($dst);
                }
                // === END AUTO RESIZE ===

                $image_path = 'assets/img/berita/' . $new_filename;
                // Delete old image if exists and not external URL
                if ($action == 'edit' && $gambar && strpos($gambar, 'http') === false) {
                    if (file_exists('../' . $gambar)) {
                        unlink('../' . $gambar);
                    }
                }
            } else {


                echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: 'Gagal Upload',
                        text: 'Gagal mengupload gambar!',
                        icon: 'error',
                        confirmButtonColor: '#d33'
                    });
                });
                </script>";
                $upload_error = true;
            }
        }
    }
    // === NEW: Handle AI Generated Image URL ===
    elseif (isset($_POST['ai_image_url']) && !empty($_POST['ai_image_url'])) {
        $ai_url = $_POST['ai_image_url'];
        // Validate URL generic
        if (filter_var($ai_url, FILTER_VALIDATE_URL)) {
            $ext = 'jpg'; // Default Pollinations is JPG
            $new_filename = uniqid() . '_ai.' . $ext;
            $target_dir = "../assets/img/berita/";
            if (!file_exists($target_dir))
                mkdir($target_dir, 0777, true);
            $target_file = $target_dir . $new_filename;

            // Download Content
            $image_content = file_get_contents($ai_url);
            if ($image_content !== false) {
                file_put_contents($target_file, $image_content);

                // Optional: Resize Logic (Copy from upload)
                list($width, $height) = getimagesize($target_file);
                $max_width = 1000;
                if ($width > $max_width) {
                    $ratio = $max_width / $width;
                    $new_width = $max_width;
                    $new_height = $height * $ratio;
                    $src = imagecreatefromstring($image_content);
                    $dst = imagecreatetruecolor($new_width, $new_height);
                    imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                    imagejpeg($dst, $target_file, 80);
                    imagedestroy($src);
                    imagedestroy($dst);
                }

                $image_path = 'assets/img/berita/' . $new_filename;

                // Delete old image if exists
                if ($action == 'edit' && $gambar && strpos($gambar, 'http') === false) {
                    if (file_exists('../' . $gambar))
                        unlink('../' . $gambar);
                }
            }
        }
    }

    if (!$upload_error) {
        // Exclusive Featured Logic: If current is featured, unset others (Admin only)
        if ($_SESSION['admin_role'] == 'admin' && $is_featured == 1) {
            mysqli_query($koneksi, "UPDATE berita SET is_featured = 0");
        }

        if ($action == 'add') {
            $created_by = $_SESSION['admin_id'];
            $sql = "INSERT INTO berita (judul, slug, kategori, tags, tanggal, gambar, isi, is_featured, penulis, created_by) 
                    VALUES ('$judul', '$slug', '$kategori', '$tags', '$tanggal', '$image_path', '$isi', '$is_featured', '$penulis', '$created_by')";
        } else {
            // Verify ownership if not admin
            if ($_SESSION['admin_role'] != 'admin') {
                $check_owner = mysqli_query($koneksi, "SELECT created_by FROM berita WHERE id='$id'");
                $owner = mysqli_fetch_assoc($check_owner);
                if ($owner['created_by'] != $_SESSION['admin_id']) {
                    echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({ title: 'Akses Ditolak', text: 'Anda tidak berhak mengedit postingan ini!', icon: 'error' }).then(() => { window.location='berita.php'; });
                    });
                    </script>";
                    exit;
                }
            }

            $sql = "UPDATE berita SET 
                    judul='$judul', 
                    slug='$slug', 
                    kategori='$kategori', 
                    tags='$tags',
                    tanggal='$tanggal', 
                    gambar='$image_path', 
                    isi='$isi', 
                    is_featured='$is_featured',
                    penulis='$penulis'
                    WHERE id='$id'";
        }

        // DEBUG LOGGING
        if (mysqli_query($koneksi, $sql)) {

            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data berita berhasil disimpan!',
                    icon: 'success',
                    confirmButtonColor: '#059669'
                }).then(() => {
                    window.location='berita.php';
                });
            });
            </script>";
        } else {
            $error_msg = mysqli_error($koneksi);

            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Gagal Simpan',
                    text: 'Terjadi kesalahan: ' + " . json_encode($error_msg) . ",
                    icon: 'error',
                    confirmButtonColor: '#d33'
                });
            });
            </script>";
        }
    }
}
?>

<div class="flex flex-col sm:flex-row justify-between items-center mb-6">
    <h3 class="text-2xl font-bold text-gray-800 mb-4 sm:mb-0">
        <?= $action == 'add' ? 'Tambah Berita Baru' : 'Edit Berita' ?>
    </h3>
    <div class="flex items-center gap-3">
        <button type="button" onclick="openAIModal()"
            class="bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-4 py-2 rounded-lg font-medium hover:from-purple-700 hover:to-indigo-700 shadow-lg hover:shadow-purple-500/30 transition transform hover:-translate-y-0.5 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                </path>
            </svg>
            Buat dengan AI
        </button>
        <a href="berita" class="text-gray-600 hover:text-emerald-600 font-medium transition flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                </path>
            </svg>
            Kembali
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-lg border border-gray-100 p-8 max-w-4xl mx-auto">
    <form method="POST" enctype="multipart/form-data">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <!-- Judul (Full width) -->
            <div class="col-span-1 md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Judul Berita</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($judul) ?>" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-gray-700 font-bold mb-2">Kategori</label>
                <select name="kategori" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition bg-white">
                    <option value="Berita" <?= $kategori == 'Berita' ? 'selected' : '' ?>>Berita</option>
                    <option value="Pengumuman" <?= $kategori == 'Pengumuman' ? 'selected' : '' ?>>Pengumuman</option>
                    <option value="Artikel" <?= $kategori == 'Artikel' ? 'selected' : '' ?>>Artikel</option>
                    <option value="Prestasi" <?= $kategori == 'Prestasi' ? 'selected' : '' ?>>Prestasi</option>
                </select>
            </div>

            <!-- Tags -->
            <div class="col-span-1 md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Tags / Kata Kunci</label>
                <input type="text" name="tags" value="<?= isset($tags) ? htmlspecialchars($tags) : '' ?>"
                    placeholder="Contoh: pendidikan, kurikulum merdeka, rapat guru"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
                <p class="text-xs text-gray-500 mt-1">Pisahkan dengan tanda koma (,)</p>
            </div>

            <!-- Tanggal -->
            <div>
                <label class="block text-gray-700 font-bold mb-2">Tanggal Publikasi</label>
                <input type="date" name="tanggal" value="<?= $tanggal ?>" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <!-- Featured Toggle (New) -->
            <?php if ($_SESSION['admin_role'] == 'admin'): ?>
                <div
                    class="col-span-1 md:col-span-2 bg-blue-50 p-4 rounded-lg border border-blue-100 flex items-center justify-between">
                    <div>
                        <span class="block text-blue-800 font-bold text-lg">Jadikan Berita Unggulan (Featured)</span>
                        <span class="block text-blue-600 text-sm">Berita ini akan tampil paling besar di halaman
                            depan.</span>
                    </div>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_featured" value="1" class="sr-only peer" <?= $is_featured == 1 ? 'checked' : '' ?>>
                        <div
                            class="w-14 h-7 bg-gray-300 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-blue-600">
                        </div>
                    </label>
                </div>
            <?php endif; ?>

            <!-- Isi Berita -->
            <div class="col-span-1 md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Isi Berita</label>
                <textarea name="isi" rows="10"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition font-mono text-sm"><?= htmlspecialchars($isi) ?></textarea>
                <p class="text-xs text-gray-500 mt-1">*Bisa menggunakan tag HTML sederhana</p>
            </div>

            <!-- Upload Gambar -->
            <div class="col-span-1 md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Gambar Utama / Thumbnail</label>
                <?php if ($gambar): ?>
                    <div class="mb-4 p-2 border border-gray-200 rounded-lg inline-block bg-gray-50">
                        <img src="<?= strpos($gambar, 'http') !== false ? $gambar : '../' . $gambar ?>" alt="Preview"
                            class="h-40 object-cover rounded">
                        <p class="text-xs text-center text-gray-500 mt-1">Gambar saat ini</p>
                    </div>
                <?php endif; ?>

                <div class="flex items-center justify-center w-full">
                    <label
                        class="flex flex-col w-full h-32 border-4 border-dashed hover:bg-gray-100 hover:border-emerald-300 group">
                        <div class="flex flex-col items-center justify-center pt-7">
                            <svg class="w-10 h-10 text-gray-400 group-hover:text-emerald-600" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <p class="pt-1 text-sm tracking-wider text-gray-400 group-hover:text-emerald-600">
                                Pilih gambar baru (Optional)
                            </p>
                        </div>
                        <input type="file" name="gambar" class="opacity-0" accept="image/*" />
                    </label>
                </div>
            </div>

        </div>

        <div class="mt-8 flex justify-end gap-3">
            <a href="berita"
                class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit"
                class="px-8 py-3 rounded-lg bg-emerald-600 text-white font-bold hover:bg-emerald-700 shadow-lg hover:shadow-emerald-500/30 transition transform hover:-translate-y-1">
                Simpan Data
            </button>
        </div>
    </form>
</div>

<!-- TinyMCE Implementation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: 'textarea[name="isi"]',
        height: 500,
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        content_style: 'body { font-family:Inter,sans-serif; font-size:14px }',

        // Path correction
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,

        // Enable Upload tab
        image_uploadtab: true,

        // File picker for browse button
        file_picker_types: 'image',
        file_picker_callback: function (callback, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/jpeg,image/png,image/gif,image/webp');

            input.addEventListener('change', function (e) {
                var file = e.target.files[0];
                if (!file) return;

                var formData = new FormData();
                formData.append('file', file);

                // Use relative path with cache buster - safe for Cloudflare/Subfolder
                fetch('upload_image.php?t=' + new Date().getTime(), {
                    method: 'POST',
                    body: formData
                })
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (result) {
                        if (result.location) {
                            callback(result.location, { title: file.name, alt: file.name });
                        } else if (result.error) {
                            alert('Upload gagal: ' + result.error);
                        }
                    })
                    .catch(function (error) {
                        alert('Upload gagal: ' + error.message);
                    });
            });

            input.click();
        },

        // Handler for Upload tab, drag-and-drop and paste images
        images_upload_url: 'upload_image.php',
        automatic_uploads: true,
        images_upload_handler: function (blobInfo, progress) {
            return new Promise(function (resolve, reject) {
                var formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());

                var xhr = new XMLHttpRequest();
                // Use relative path with cache buster
                xhr.open('POST', 'upload_image.php?t=' + new Date().getTime(), true);

                xhr.upload.onprogress = function (e) {
                    if (e.lengthComputable) {
                        progress(e.loaded / e.total * 100);
                    }
                };

                xhr.onload = function () {
                    if (xhr.status === 200) {
                        try {
                            var result = JSON.parse(xhr.responseText);
                            if (result.location) {
                                resolve(result.location);
                            } else if (result.error) {
                                reject('Upload gagal: ' + result.error);
                            } else {
                                reject('Format response tidak valid');
                            }
                        } catch (e) {
                            reject('Error parsing response: ' + xhr.responseText);
                        }
                    } else {
                        reject('HTTP Error: ' + xhr.status);
                    }
                };

                xhr.onerror = function () {
                    reject('Network error - Gagal menghubungi server');
                };

                xhr.send(formData);
            });
        },

        promotion: false,
        branding: false
    });
</script>

<!-- AI Generate Modal -->
<div id="aiModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity" onclick="closeAIModal()"></div>

        <!-- Modal panel -->
        <div
            class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Generate Artikel dengan AI</h3>
                            <p class="text-purple-200 text-sm">Isi data kegiatan, AI akan membuatkan artikel</p>
                        </div>
                    </div>
                    <button onclick="closeAIModal()" class="text-white/80 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Form Content -->
            <div class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Topik / Judul Berita <span
                            class="text-red-500">*</span></label>
                    <input type="text" id="ai_nama_kegiatan" placeholder="Contoh: Pencapaian Siswa Juara Olimpiade"
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Tanggal Peristiwa</label>
                        <input type="date" id="ai_tanggal" value="<?= date('Y-m-d') ?>"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Lokasi (Opsional)</label>
                        <input type="text" id="ai_tempat" placeholder="Contoh: Jakarta"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition">
                    </div>
                </div>

                <!-- Peserta Removed for News -->
                <input type="hidden" id="ai_peserta" value="">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Gaya Tulisan</label>
                        <select id="ai_gaya"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition bg-white">
                            <option value="formal">Formal / Straight News</option>
                            <option value="santai">Santai / Reportase</option>
                            <option value="inspiratif">Inspiratif</option>
                            <option value="edukatif">Edukatif</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Panjang Artikel</label>
                        <select id="ai_panjang"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition bg-white">
                            <option value="pendek">Singkat (300-500 kata)</option>
                            <option value="sedang" selected>Sedang (500-800 kata)</option>
                            <option value="panjang">Mendalam (800+ kata)</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-1">Fakta Utama / Konteks <span
                            class="text-red-500">*</span></label>
                    <textarea id="ai_deskripsi" rows="3"
                        placeholder="Jelaskan poin-poin penting yang harus ada dalam berita..."
                        class="w-full px-4 py-2.5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition resize-none"></textarea>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3">
                <button type="button" onclick="closeAIModal()"
                    class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 font-medium hover:bg-gray-100 transition">
                    Batal
                </button>
                <button type="button" onclick="generateArticle()" id="btnGenerate"
                    class="px-5 py-2.5 rounded-lg bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-medium hover:from-purple-700 hover:to-indigo-700 shadow-lg hover:shadow-purple-500/30 transition flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <span id="btnGenerateText">Generate Artikel</span>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // AI Modal Functions
    function openAIModal() {
        document.getElementById('aiModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeAIModal() {
        document.getElementById('aiModal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    function generateArticle() {
        const nama_kegiatan = document.getElementById('ai_nama_kegiatan').value.trim();
        const tanggal = document.getElementById('ai_tanggal').value;
        const tempat = document.getElementById('ai_tempat').value.trim();
        const peserta = document.getElementById('ai_peserta').value.trim();
        const deskripsi = document.getElementById('ai_deskripsi').value.trim();
        const gaya = document.getElementById('ai_gaya').value;
        const panjang = document.getElementById('ai_panjang').value;

        // Validation
        if (!nama_kegiatan || !deskripsi) {
            Swal.fire({
                icon: 'warning',
                title: 'Data Tidak Lengkap',
                text: 'Nama kegiatan dan deskripsi wajib diisi!',
                confirmButtonColor: '#7c3aed'
            });
            return;
        }

        // Set loading state
        const btn = document.getElementById('btnGenerate');
        const btnText = document.getElementById('btnGenerateText');
        const originalText = btnText.textContent;
        btn.disabled = true;
        btnText.innerHTML = '<svg class="animate-spin h-5 w-5 inline mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Generating...';

        // Make API request
        fetch('ai_generate.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                type: 'berita', // Explicitly set type to News
                nama_kegiatan: nama_kegiatan,
                tanggal: tanggal,
                tempat: tempat,
                peserta: peserta,
                deskripsi: deskripsi,
                gaya: gaya,
                panjang: panjang
            })
        })
            .then(response => response.json())
            .then(data => {
                btn.disabled = false;
                btnText.textContent = originalText;

                if (data.error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Generate',
                        text: data.error,
                        confirmButtonColor: '#7c3aed'
                    });
                    return;
                }

                if (data.success) {
                    // Fill the form fields
                    document.querySelector('input[name="judul"]').value = data.judul;

                    // Auto-fill Kategori
                    if (data.kategori) {
                        const kategoriSelect = document.querySelector('select[name="kategori"]');
                        if (kategoriSelect) kategoriSelect.value = data.kategori;
                    }

                    // Auto-fill Tags
                    if (data.tags) {
                        const tagsInput = document.querySelector('input[name="tags"]');
                        if (tagsInput) tagsInput.value = data.tags;
                    }

                    // AI Image Generation (Pollinations.ai)
                    if (data.image_prompt) {
                        // Encode prompt
                        const prompt = encodeURIComponent(data.image_prompt + " 3d realistic render, pixar style, disney style, 8k, highly detailed, vibrant colors");
                        const imageUrl = `https://image.pollinations.ai/prompt/${prompt}`;

                        // Create or Update Hidden Input
                        let hiddenInput = document.querySelector('input[name="ai_image_url"]');
                        if (!hiddenInput) {
                            hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'ai_image_url';
                            document.forms[0].appendChild(hiddenInput); // Append to form
                        }
                        hiddenInput.value = imageUrl;

                        // Show Preview (Find existing preview container or create new)
                        // This assumes the structure "Gambar Utama / Thumbnail" -> if php $gambar -> div img -> endif
                        // We will inject a preview div before the file input
                        let previewContainer = document.getElementById('ai-image-preview');
                        if (!previewContainer) {
                            // Find the file input container to insert before
                            const fileInputContainer = document.querySelector('input[name="gambar"]').closest('.col-span-1.md\\:col-span-2');
                            if (fileInputContainer) {
                                previewContainer = document.createElement('div');
                                previewContainer.id = 'ai-image-preview';
                                previewContainer.className = 'mb-4 p-2 border border-purple-200 rounded-lg inline-block bg-purple-50';

                                // Insert after label
                                const label = fileInputContainer.querySelector('label');
                                if (label) label.parentNode.insertBefore(previewContainer, label.nextSibling);
                            }
                        }

                        if (previewContainer) {
                            previewContainer.innerHTML = `
                                <img src="${imageUrl}" alt="AI Generated" class="h-48 object-cover rounded mb-2">
                                <p class="text-xs text-center text-purple-600 font-bold">Generated by AI ✨</p>
                                <p class="text-xs text-center text-gray-400">(Akan disimpan saat klik Simpan)</p>
                            `;
                        }
                    }

                    // Set TinyMCE content
                    if (tinymce.get('isi') || tinymce.activeEditor) {
                        const editor = tinymce.get('isi') || tinymce.activeEditor;
                        editor.setContent(data.isi);
                    } else {
                        document.querySelector('textarea[name="isi"]').value = data.isi;
                    }

                    // Close modal
                    closeAIModal();

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Artikel & Gambar berhasil di-generate AI!',
                        confirmButtonColor: '#059669'
                    });

                    // Clear AI form
                    document.getElementById('ai_nama_kegiatan').value = '';
                    document.getElementById('ai_tempat').value = '';
                    document.getElementById('ai_peserta').value = '';
                    document.getElementById('ai_deskripsi').value = '';
                }
            })
            .catch(error => {
                btn.disabled = false;
                btnText.textContent = originalText;

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan jaringan. Silakan coba lagi.',
                    confirmButtonColor: '#7c3aed'
                });
                console.error('Error:', error);
            });
    }

    // Close modal with Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeAIModal();
        }
    });
</script>

<?php include 'includes/footer.php'; ?>