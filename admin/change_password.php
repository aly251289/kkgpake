<?php
include 'includes/header.php';
?>

<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center gap-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z">
                    </path>
                </svg>
                Ganti Password
            </h2>
        </div>

        <div class="p-6">
            <form id="changePasswordForm" method="POST" action="process/change_password.php">
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Password Lama</label>
                        <input type="password" name="old_password" id="old_password" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                            placeholder="Masukkan password lama">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Password Baru</label>
                        <input type="password" name="new_password" id="new_password" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                            placeholder="Minimal 6 karakter">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Konfirmasi Password Baru</label>
                        <input type="password" name="confirm_password" id="confirm_password" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition"
                            placeholder="Ketik ulang password baru">
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                    <a href="dashboard"
                        class="flex-1 text-center px-6 py-3 rounded-lg border-2 border-gray-300 text-gray-700 font-bold hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="flex-1 px-6 py-3 rounded-lg bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-bold hover:from-emerald-700 hover:to-teal-700 shadow-lg transition">
                        Simpan Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('changePasswordForm').addEventListener('submit', function (e) {
        const newPass = document.getElementById('new_password').value;
        const confirmPass = document.getElementById('confirm_password').value;

        if (newPass.length < 6) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Password Terlalu Pendek',
                text: 'Password baru minimal 6 karakter'
            });
            return;
        }

        if (newPass !== confirmPass) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Password Tidak Cocok',
                text: 'Password baru dan konfirmasi harus sama'
            });
            return;
        }
    });
</script>

<?php include 'includes/footer.php'; ?>