<?php include 'includes/header.php'; ?>
<?php require_once '../config/koneksi.php'; ?>

<?php
if (isset($_GET['delete'])) {
    $id = mysqli_real_escape_string($koneksi, $_GET['delete']);
    $delete = mysqli_query($koneksi, "DELETE FROM rdm_links WHERE id='$id'");
    if ($delete) {
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Link RDM berhasil dihapus!',
                icon: 'success',
                confirmButtonColor: '#059669'
            }).then(() => {
                window.location='rdm';
            });
        });
        </script>";
    }
}

$query = mysqli_query($koneksi, "SELECT * FROM rdm_links ORDER BY nama_madrasah ASC");
$total_rdm = mysqli_num_rows($query);
?>

<style>
    .rdm-card {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }

    .rdm-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .rdm-card:hover .rdm-overlay {
        opacity: 1;
    }

    .rdm-card:hover .rdm-logo {
        transform: scale(1.1);
    }

    .gradient-border {
        background: linear-gradient(135deg, #10b981, #3b82f6);
        padding: 3px;
        border-radius: 1rem;
    }
</style>

<!-- Header Section -->
<div class="flex flex-col sm:flex-row justify-between items-center mb-8">
    <div>
        <h3 class="text-2xl font-bold text-gray-800 mb-1">🖥️ Portal RDM Madrasah</h3>
        <p class="text-gray-500 text-sm">Rapor Digital Madrasah - Kelola link akses untuk setiap madrasah</p>
    </div>
    <a href="rdm_form"
        class="mt-4 sm:mt-0 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-emerald-500/30 transition-all transform hover:-translate-y-1 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Tambah Link RDM
    </a>
</div>

<!-- Stats Bar -->
<div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 mb-8 text-white shadow-xl">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                    </path>
                </svg>
            </div>
            <div>
                <p class="text-white/70 text-sm font-medium">Total Madrasah Terdaftar</p>
                <p class="text-4xl font-extrabold"><?= $total_rdm ?></p>
            </div>
        </div>
        <div class="hidden md:block text-right">
            <p class="text-white/70 text-sm">Akses cepat ke sistem rapor digital</p>
            <p class="text-white/70 text-sm">untuk semua madrasah di wilayah KKG</p>
        </div>
    </div>
</div>

<!-- Cards Grid -->
<?php if ($total_rdm > 0): ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <?php
        mysqli_data_seek($query, 0); // Reset pointer
        while ($row = mysqli_fetch_assoc($query)):
            ?>
            <div class="rdm-card rounded-2xl overflow-hidden shadow-lg group">
                <!-- Card Header with Logo -->
                <div
                    class="relative h-32 bg-gradient-to-br from-blue-500 via-indigo-500 to-purple-600 flex items-center justify-center">
                    <!-- Decorative Shapes -->
                    <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2">
                    </div>
                    <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2">
                    </div>

                    <!-- Logo/Avatar -->
                    <div class="rdm-logo relative z-10 transition-transform duration-300">
                        <?php if (!empty($row['gambar'])): ?>
                            <img src="../assets/images/rdm/<?= $row['gambar'] ?>"
                                class="w-20 h-20 rounded-xl object-cover border-4 border-white shadow-xl">
                        <?php else: ?>
                            <div
                                class="w-20 h-20 rounded-xl bg-white flex items-center justify-center text-3xl font-bold text-indigo-600 shadow-xl border-4 border-white">
                                <?= strtoupper(substr($row['nama_madrasah'], 0, 2)) ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Action Overlay -->
                    <div
                        class="rdm-overlay absolute inset-0 bg-black/50 backdrop-blur-sm opacity-0 transition-opacity duration-300 flex items-center justify-center gap-3">
                        <a href="rdm_form?id=<?= $row['id'] ?>"
                            class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-blue-600 hover:bg-blue-100 transition shadow-lg"
                            title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                        </a>
                        <button onclick="confirmDelete('rdm?delete=<?= $row['id'] ?>')"
                            class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-red-600 hover:bg-red-100 transition shadow-lg"
                            title="Hapus">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="p-5">
                    <h4 class="text-lg font-bold text-gray-800 mb-2 truncate"
                        title="<?= htmlspecialchars($row['nama_madrasah']) ?>">
                        <?= htmlspecialchars($row['nama_madrasah']) ?>
                    </h4>

                    <p class="text-xs text-gray-400 mb-4 truncate" title="<?= htmlspecialchars($row['url_rdm']) ?>">
                        📎 <?= htmlspecialchars($row['url_rdm']) ?>
                    </p>

                    <a href="<?= htmlspecialchars($row['url_rdm']) ?>" target="_blank"
                        class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold text-sm hover:from-blue-600 hover:to-indigo-700 transition shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                        Buka RDM
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

<?php else: ?>
    <!-- Empty State -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-12 text-center">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                </path>
            </svg>
        </div>
        <h4 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Link RDM</h4>
        <p class="text-gray-500 mb-6">Mulai tambahkan link Rapor Digital Madrasah untuk setiap sekolah.</p>
        <a href="rdm_form"
            class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold rounded-xl hover:from-emerald-700 hover:to-teal-700 transition shadow-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Link Pertama
        </a>
    </div>
<?php endif; ?>

<script>
    function confirmDelete(url) {
        Swal.fire({
            title: 'Hapus Link RDM?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
</script>

<?php include 'includes/footer.php'; ?>