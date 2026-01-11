<?php include 'includes/header.php'; ?>
<?php
require_once '../config/koneksi.php';
require_once '../includes/database.php'; // Security: Include the new database helper
?>

<?php
$id = '';
$judul = '';
$kategori = '';
$lokasi = '';
$deskripsi = '';
$tanggal = date('Y-m-d');
$gambar = '';
$action = 'add';

if (isset($_GET['id'])) {
    $action = 'edit';
    $id = $_GET['id'];
    // Security: Use prepared statement to fetch data
    $query = db_query($koneksi, "SELECT * FROM kegiatan WHERE id=?", 'i', [$id]);
    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query);
        $judul = $row['judul'];
        $kategori = $row['kategori'];
        $lokasi = $row['lokasi'];
        $deskripsi = $row['deskripsi'];
        $tanggal = $row['tanggal'];
        $gambar = $row['gambar'];
    } else {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Tidak Ditemukan',
                text: 'Data kegiatan tidak ditemukan!',
                icon: 'error',
                confirmButtonColor: '#d33'
            }).then(() => {
                window.location='kegiatan.php';
            });
        });
        </script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Security: No need for mysqli_real_escape_string with prepared statements
    $judul = $_POST['judul'];
    // Simple slug generator
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));

    // Security: Use prepared statement to ensure slug is unique
    $check_slug = db_query($koneksi, "SELECT id FROM kegiatan WHERE slug = ? AND id != ?", 'si', [$slug, $id]);
    if ($check_slug && mysqli_num_rows($check_slug) > 0) {
        $slug = $slug . '-' . time();
    }

    $kategori = $_POST['kategori'];
    $lokasi = $_POST['lokasi'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal = $_POST['tanggal'];

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
            $target_dir = "../assets/img/kegiatan/";

            // Create dir if not exists
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $target_file = $target_dir . $new_filename;

            if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
                // === AUTO RESIZE LOGIC ===
                // Get original image dimensions
                list($width, $height) = getimagesize($target_file);
                $max_width = 1000;

                // Only resize if width > max_width
                if ($width > $max_width) {
                    $ratio = $max_width / $width;
                    $new_width = $max_width;
                    $new_height = $height * $ratio;

                    // Create new image resource
                    $src = imagecreatefromstring(file_get_contents($target_file));
                    $dst = imagecreatetruecolor($new_width, $new_height);

                    // Maintain transparency for PNG/GIF
                    if ($ext == 'png' || $ext == 'gif') {
                        imagecolortransparent($dst, imagecolorallocatealpha($dst, 0, 0, 0, 127));
                        imagealphablending($dst, false);
                        imagesavealpha($dst, true);
                    }

                    // Resize
                    imagecopyresampled($dst, $src, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

                    // Save resized image (overwrite original with optimized version)
                    // Save as JPEG for compression usually, or keep original format. 
                    // Let's keep original format logic simple or force JPEG for consistency? 
                    // Let's stick to original extension saving but utilizing the resource.
                    if (strtolower($ext) == 'jpg' || strtolower($ext) == 'jpeg') {
                        imagejpeg($dst, $target_file, 80); // 80% quality
                    } elseif (strtolower($ext) == 'png') {
                        imagepng($dst, $target_file, 8); // Compression level 8
                    } elseif (strtolower($ext) == 'gif') {
                        imagegif($dst, $target_file);
                    }

                    imagedestroy($src);
                    imagedestroy($dst);
                }
                // === END AUTO RESIZE ===

                $image_path = 'assets/img/kegiatan/' . $new_filename;
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

        if (filter_var($ai_url, FILTER_VALIDATE_URL)) {
            $ext = 'jpg';
            $new_filename = uniqid() . '_ai.' . $ext;
            $target_dir = "../assets/img/kegiatan/";
            if (!file_exists($target_dir))
                mkdir($target_dir, 0777, true);
            $target_file = $target_dir . $new_filename;

            // Download Content
            $image_content = @file_get_contents($ai_url);
            if ($image_content !== false) {
                file_put_contents($target_file, $image_content);

                // Resize Logic
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

                $image_path = 'assets/img/kegiatan/' . $new_filename;

                // Delete old image
                if ($action == 'edit' && $gambar && strpos($gambar, 'http') === false) {
                    if (file_exists('../' . $gambar))
                        unlink('../' . $gambar);
                }
            }
        }
    }

    if (!$upload_error) {
        if ($action == 'add') {
            $created_by = $_SESSION['admin_id'];
            // Security: Use prepared statement for INSERT
            $sql = "INSERT INTO kegiatan (judul, slug, kategori, tanggal, lokasi, gambar, deskripsi, created_by) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $params = [$judul, $slug, $kategori, $tanggal, $lokasi, $image_path, $deskripsi, $created_by];
            $types = 'sssssssi';
            $result = db_query($koneksi, $sql, $types, $params);
        } else {
            // Security: Use prepared statement for UPDATE
            $sql = "UPDATE kegiatan SET 
                    judul=?, slug=?, kategori=?, tanggal=?, lokasi=?,
                    gambar=?, deskripsi=?
                    WHERE id=?";
            $params = [$judul, $slug, $kategori, $tanggal, $lokasi, $image_path, $deskripsi, $id];
            $types = 'sssssssi';
            $result = db_query($koneksi, $sql, $types, $params);
        }

        if ($result) {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Data berhasil disimpan!',
                    icon: 'success',
                    confirmButtonColor: '#059669'
                }).then(() => {
                    window.location='kegiatan.php';
                });
            });
            </script>";
        } else {
            echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Gagal Simpan',
                    text: 'Terjadi kesalahan: ' + " . json_encode(mysqli_error($koneksi)) . ",
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
        <?= $action == 'add' ? 'Tambah Kegiatan Baru' : 'Edit Kegiatan' ?>
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
        <a href="kegiatan" class="text-gray-600 hover:text-emerald-600 font-medium transition flex items-center">
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
                <label class="block text-gray-700 font-bold mb-2">Nama Kegiatan</label>
                <input type="text" name="judul" value="<?= htmlspecialchars($judul) ?>" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <!-- Kategori -->
            <div>
                <label class="block text-gray-700 font-bold mb-2">Kategori</label>
                <select name="kategori" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition bg-white">
                    <option value="">Pilih Kategori</option>
                    <?php
                    if ($_SESSION['admin_role'] == 'admin') {
                        $pilihan_kategori = ['Workshop', 'Seminar', 'Rapat', 'Pelatihan', 'Kunjungan', 'Lainnya'];
                    } else {
                        // Kategori untuk Madrasah / Sekolah
                        $pilihan_kategori = [
                            'Upacara',
                            'Peringatan Hari Besar',
                            'Rapat Wali Murid',
                            'Lomba',
                            'PPDB',
                            'Matsama',
                            'Ujian',
                            'Study Tour',
                            'Ekstrakurikuler',
                            'Rapat Guru',
                            'Lainnya'
                        ];
                    }

                    foreach ($pilihan_kategori as $cat) {
                        $selected = ($kategori == $cat) ? 'selected' : '';
                        echo "<option value=\"$cat\" $selected>$cat</option>";
                    }
                    ?>
                </select>
            </div>

            <!-- Tanggal -->
            <div>
                <label class="block text-gray-700 font-bold mb-2">Tanggal Pelaksanaan</label>
                <input type="date" name="tanggal" value="<?= $tanggal ?>" required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <!-- Lokasi (Full width) -->
            <div class="col-span-1 md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Lokasi Kegiatan</label>
                <input type="text" name="lokasi" value="<?= htmlspecialchars($lokasi) ?>" required
                    placeholder="Contoh: Aula Kemenag Kota"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition">
            </div>

            <!-- Deskripsi -->
            <div class="col-span-1 md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Deskripsi Kegiatan</label>
                <textarea name="deskripsi" rows="6"
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition font-mono text-sm"><?= htmlspecialchars($deskripsi) ?></textarea>
            </div>

            <!-- Upload Gambar -->
            <div class="col-span-1 md:col-span-2">
                <label class="block text-gray-700 font-bold mb-2">Gambar Dokumentasi / Banner</label>
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
            <a href="kegiatan"
                class="px-6 py-3 rounded-lg border border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                Batal
            </a>
            <button type="submit"
                class="px-8 py-3 rounded-lg bg-emerald-600 text-white font-bold hover:bg-emerald-700 shadow-lg hover:shadow-emerald-500/30 transition transform hover:-translate-y-1">
                Simpan Data
            </button>
        </div>
    </form>
    </form>
</div>

<!-- AI Generation Modal -->
<div id="aiModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
            onclick="closeAIModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div
            class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-200">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div
                        class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 sm:mx-0 sm:h-10 sm:w-10">
                        <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Generate Kegiatan dengan AI
                        </h3>
                        <div class="mt-2 text-sm text-gray-500">
                            Isi detail singkat kegiatanmu, biarkan AI yang menulis deskripsi lengkapnya.
                        </div>

                        <!-- AI Form -->
                        <div class="mt-4 space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Kegiatan</label>
                                <input type="text" id="ai_nama_kegiatan"
                                    class="mt-1 focus:ring-purple-500 focus:border-purple-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border"
                                    placeholder="Contoh: Workshop Kurikulum Merdeka">
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                                    <input type="date" id="ai_tanggal" value="<?= date('Y-m-d') ?>"
                                        class="mt-1 focus:ring-purple-500 focus:border-purple-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tempat</label>
                                    <input type="text" id="ai_tempat"
                                        class="mt-1 focus:ring-purple-500 focus:border-purple-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border"
                                        placeholder="Contoh: Aula Kemenag">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Peserta</label>
                                <input type="text" id="ai_peserta"
                                    class="mt-1 focus:ring-purple-500 focus:border-purple-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border"
                                    placeholder="Contoh: Seluruh Guru MI Kota Malang">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700">Deskripsi Singkat / Poin
                                    Peting</label>
                                <textarea id="ai_deskripsi" rows="3"
                                    class="mt-1 focus:ring-purple-500 focus:border-purple-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md p-2 border"
                                    placeholder="Jelaskan tujuan kegiatan, narasumber, dan hasil yang diharapkan..."></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Gaya Tulisan</label>
                                    <select id="ai_gaya"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                        <option value="formal">Formal</option>
                                        <option value="santai">Santai / Reportase</option>
                                        <option value="inspiratif">Inspiratif</option>
                                        <option value="edukatif">Edukatif</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Panjang Artikel</label>
                                    <select id="ai_panjang"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                                        <option value="pendek">Pendek</option>
                                        <option value="sedang" selected>Sedang</option>
                                        <option value="panjang">Panjang</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="btnGenerate" onclick="generateArticle()"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm">
                    <span id="btnGenerateText">Generate Artikel</span>
                </button>
                <button type="button" onclick="closeAIModal()"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openAIModal() {
        document.getElementById('aiModal').classList.remove('hidden');
        document.getElementById('ai_nama_kegiatan').focus();
    }

    function closeAIModal() {
        document.getElementById('aiModal').classList.add('hidden');
    }

    // Close on Escape key
    document.addEventListener('keydown', function (event) {
        if (event.key === "Escape") {
            closeAIModal();
        }
    });

    function generateArticle() {
        const nama_kegiatan = document.getElementById('ai_nama_kegiatan').value;
        const tanggal = document.getElementById('ai_tanggal').value;
        const tempat = document.getElementById('ai_tempat').value;
        const peserta = document.getElementById('ai_peserta').value;
        const deskripsi = document.getElementById('ai_deskripsi').value;
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
                type: 'kegiatan', // Explicitly set type to Activity
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

                    const aiLokasi = document.getElementById('ai_tempat').value;
                    if (aiLokasi) {
                        document.querySelector('input[name="lokasi"]').value = aiLokasi;
                    }

                    if (data.kategori) {
                        const kategoriSelect = document.querySelector('select[name="kategori"]');
                        if (kategoriSelect) kategoriSelect.value = data.kategori;
                    }

                    // AI Image Generation (Pollinations.ai)
                    if (data.image_prompt) {
                        const prompt = encodeURIComponent(data.image_prompt + " 3d realistic render, pixar style, disney style, 8k, highly detailed, vibrant colors");
                        const imageUrl = `https://image.pollinations.ai/prompt/${prompt}`;

                        let hiddenInput = document.querySelector('input[name="ai_image_url"]');
                        if (!hiddenInput) {
                            hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'ai_image_url';
                            document.forms[0].appendChild(hiddenInput);
                        }
                        hiddenInput.value = imageUrl;

                        // Preview Container
                        let previewContainer = document.getElementById('ai-image-preview');
                        if (!previewContainer) {
                            const fileInputContainer = document.querySelector('input[name="gambar"]').closest('.col-span-1.md\\:col-span-2');
                            if (fileInputContainer) {
                                previewContainer = document.createElement('div');
                                previewContainer.id = 'ai-image-preview';
                                previewContainer.className = 'mb-4 p-2 border border-purple-200 rounded-lg inline-block bg-purple-50';

                                const label = fileInputContainer.querySelector('label');
                                if (label) label.parentNode.insertBefore(previewContainer, label.nextSibling);
                            }
                        }

                        if (previewContainer) {
                            previewContainer.innerHTML = `
                                <img src="${imageUrl}" alt="AI Generated" class="h-48 object-cover rounded mb-2">
                                <p class="text-xs text-center text-purple-600 font-bold">Generated by AI ✨</p>
                            `;
                        }
                    }

                    if (tinymce.get('deskripsi') || tinymce.activeEditor) {
                        const editor = tinymce.get('deskripsi') || tinymce.activeEditor;
                        editor.setContent(data.isi);
                    } else {
                        document.querySelector('textarea[name="deskripsi"]').value = data.isi;
                    }

                    closeAIModal();

                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Artikel & Gambar berhasil di-generate AI!',
                        confirmButtonColor: '#059669'
                    });

                    document.getElementById('ai_nama_kegiatan').value = '';
                    document.getElementById('ai_deskripsi').value = '';
                }
            })
            .catch(error => {
                btn.disabled = false;
                btnText.textContent = originalText;
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Sistem',
                    text: 'Terjadi kesalahan koneksi.',
                    confirmButtonColor: '#d33'
                });
            });
    }
</script>

<!-- TinyMCE Implementation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: 'textarea[name="deskripsi"]',
        height: 400,
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        content_style: 'body { font-family:Inter,sans-serif; font-size:14px }',

        // Image Upload Config
        images_upload_url: 'upload_image.php',
        automatic_uploads: true,
        images_reuse_filename: false,

        // Path correction
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,

        // File picker for Upload tab in image dialog
        file_picker_types: 'image',
        file_picker_callback: function (cb, value, meta) {
            var input = document.createElement('input');
            input.setAttribute('type', 'file');
            input.setAttribute('accept', 'image/*');

            input.onchange = function () {
                var file = this.files[0];

                var formData = new FormData();
                formData.append('file', file);

                // Show loading
                tinymce.activeEditor.setProgressState(true);

                // Use relative path with cache buster
                fetch('upload_image.php?t=' + new Date().getTime(), {
                    method: 'POST',
                    body: formData
                })
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (result) {
                        tinymce.activeEditor.setProgressState(false);

                        if (result.location) {
                            cb(result.location, { title: file.name });
                        } else if (result.error) {
                            alert('Upload gagal: ' + result.error);
                        } else {
                            alert('Upload gagal: Response tidak valid');
                        }
                    })
                    .catch(function (error) {
                        tinymce.activeEditor.setProgressState(false);
                        console.error('Upload error:', error);
                        alert('Upload gagal: ' + error.message);
                    });
            };

            input.click();
        },

        // Upload handler for drag-and-drop and paste
        images_upload_handler: function (blobInfo, progress) {
            return new Promise(function (resolve, reject) {
                var xhr = new XMLHttpRequest();
                // Use relative path with cache buster
                xhr.open('POST', 'upload_image.php?t=' + new Date().getTime(), true);

                xhr.upload.onprogress = function (e) {
                    progress(e.loaded / e.total * 100);
                };

                xhr.onload = function () {
                    if (xhr.status === 200) {
                        try {
                            var json = JSON.parse(xhr.responseText);
                            if (json.location) {
                                resolve(json.location);
                            } else if (json.error) {
                                reject('Upload gagal: ' + json.error);
                            } else {
                                reject('Format response tidak valid');
                            }
                        } catch (e) {
                            reject('Gagal parsing response: ' + xhr.responseText);
                        }
                    } else {
                        try {
                            var json = JSON.parse(xhr.responseText);
                            reject('Upload gagal: ' + (json.error || 'Kesalahan server'));
                        } catch (e) {
                            reject('Upload gagal dengan status: ' + xhr.status);
                        }
                    }
                };

                xhr.onerror = function () {
                    reject('Koneksi gagal. Periksa koneksi internet Anda.');
                };

                var formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                xhr.send(formData);
            });
        },

        promotion: false,
        branding: false
    });
</script>

<?php include 'includes/footer.php'; ?>