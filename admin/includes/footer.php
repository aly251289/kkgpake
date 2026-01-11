</main>
<footer class="text-center py-6 text-sm text-gray-500 border-t border-gray-100 mt-auto">
    <p class="flex items-center justify-center gap-1">
        &copy; 2025 - <?= date('Y') ?> KKG-MI PAKET. Dibuat dengan <span class="text-green-500">💚</span> oleh
        <button onclick="openKoesnoModal()"
            class="text-emerald-400 hover:text-emerald-300 font-medium hover:underline focus:outline-none transition-colors">
            Koesno
        </button>
    </p>
</footer>
</div>
</div>

<!-- Koesno Modal -->
<div id="koesnoModal" class="fixed inset-0 z-[9999] hidden" aria-labelledby="modal-title" role="dialog"
    aria-modal="true">
    <!-- Backdrop with blur -->
    <div class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm transition-opacity opacity-0" id="koesnoBackdrop"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <!-- Modal Panel -->
            <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-sm scale-95 opacity-0 ring-1 ring-black/5"
                id="koesnoPanel">

                <!-- Decorative Top Pattern -->
                <div
                    class="absolute top-0 left-0 right-0 h-32 bg-gradient-to-br from-emerald-400 via-emerald-500 to-teal-600">
                    <div class="absolute inset-0 opacity-10"
                        style="background-image: url('data:image/svg+xml,%3Csvg width=\'20\' height=\'20\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M1 1h2v2H1V1zm4 0h2v2H5V1zm4 0h2v2H9V1zm4 0h2v2h-2V1zm4 0h2v2h-2V1zm-4 4h2v2h-2V5zm-4 0h2v2H5V5zm4 0h2v2H9V5zm4 0h2v2h-2V5zm-4 4h2v2h-2V9zm-4 0h2v2H5V9zm4 0h2v2H9V9zm4 0h2v2h-2V9zm-4 4h2v2h-2v-2zm-4 0h2v2H5v-2zm4 0h2v2H9v-2zm4 0h2v2h-2v-2z\' fill=\'%23ffffff\' fill-opacity=\'1\' fill-rule=\'evenodd\'/%3E%3C/svg%3E');">
                    </div>
                </div>

                <!-- Close Button -->
                <div class="absolute right-3 top-3 z-10">
                    <button type="button" onclick="closeKoesnoModal()"
                        class="rounded-full bg-black/20 p-1 text-white hover:bg-black/40 focus:outline-none backdrop-blur-sm transition-colors">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="relative px-6 pb-6 pt-16">
                    <!-- Avatar with adjusted positioning and styling -->
                    <div
                        class="mx-auto flex h-40 w-40 items-center justify-center rounded-full bg-white p-1.5 shadow-xl mb-6 relative z-10 -mt-12">
                        <div class="h-full w-full rounded-full overflow-hidden border border-gray-100">
                            <!-- object-top-center to align face better, increased size -->
                            <img src="../assets/images/koesno.png" alt="Koesno"
                                class="h-full w-full object-cover object-[center_15%] transform hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="absolute bottom-2 right-2 h-8 w-8 bg-green-500 rounded-full border-4 border-white flex items-center justify-center shadow-sm"
                            title="Online">
                            <div class="h-2.5 w-2.5 bg-white rounded-full animate-pulse"></div>
                        </div>
                    </div>

                    <div class="text-center">
                        <h3 class="text-2xl font-bold text-gray-900 mb-1 tracking-tight" id="modal-title">Koesno</h3>
                        <p class="text-emerald-600 font-medium text-sm mb-4">@WebDeveloper</p>

                        <div class="bg-emerald-50/50 rounded-xl p-4 mb-6 border border-emerald-100/50">
                            <p class="text-gray-600 italic text-sm leading-relaxed relative">
                                <span class="text-emerald-300 text-4xl absolute -top-4 -left-2 select-none">"</span>
                                Bapak2 iseng yang selalu ingin tahu bagaimana dunia bekerja.
                                <span class="text-emerald-300 text-4xl absolute -bottom-6 -right-2 select-none">"</span>
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <p class="text-xs font-semibold text-gray-400 text-center uppercase tracking-wider">Ada
                            Keperluan?</p>
                        <a href="https://wa.me/6289699470625" target="_blank" rel="noopener noreferrer"
                            class="group relative flex w-full justify-center items-center gap-3 rounded-xl bg-gradient-to-r from-green-500 to-emerald-600 px-4 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-500/30 hover:shadow-emerald-500/40 hover:translate-y-[-1px] transition-all duration-300 overflow-hidden">

                            <!-- Shine effect -->
                            <div
                                class="absolute inset-0 -translate-x-full group-hover:animate-[shimmer_1.5s_infinite] bg-gradient-to-r from-transparent via-white/20 to-transparent z-0">
                            </div>

                            <svg class="h-6 w-6 relative z-10" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.008-.57-.008-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                            </svg>
                            <span class="relative z-10">Chat via WhatsApp</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Modal Functions
    function openKoesnoModal() {
        const modal = document.getElementById('koesnoModal');
        const backdrop = document.getElementById('koesnoBackdrop');
        const panel = document.getElementById('koesnoPanel');

        modal.classList.remove('hidden');
        // Small delay to allow display:block to apply before opacity transition
        setTimeout(() => {
            backdrop.classList.remove('opacity-0');
            panel.classList.remove('scale-95', 'opacity-0');
            panel.classList.add('scale-100', 'opacity-100');
        }, 10);

        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }

    function closeKoesnoModal() {
        const modal = document.getElementById('koesnoModal');
        const backdrop = document.getElementById('koesnoBackdrop');
        const panel = document.getElementById('koesnoPanel');

        backdrop.classList.add('opacity-0');
        panel.classList.remove('scale-100', 'opacity-100');
        panel.classList.add('scale-95', 'opacity-0');

        // Wait for transition to finish
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }, 300);
    }

    // Close on backdrop click
    document.getElementById('koesnoModal').addEventListener('click', function (e) {
        if (e.target === this || e.target.closest('.fixed.inset-0.bg-gray-900')) {
            //  closeKoesnoModal(); // Optional
        }
    });

    // Close on escape key
    });
</script>

<script>
    // Global Delete Confirmation
    function confirmDelete(url) {
        Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#059669', // Emerald-600
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            background: '#fff',
            borderRadius: '1rem'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        })
    }
</script>
</body>

</html>