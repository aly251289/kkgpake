<!-- Footer -->
<footer class="bg-gradient-to-b from-emerald-800 via-emerald-900 to-gray-900 text-white pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
            <!-- Brand -->
            <div data-aos="fade-up" data-aos-delay="0">
                <div class="flex items-center gap-3 mb-6">
                    <div
                        class="w-10 h-10 bg-emerald-500 rounded-lg flex items-center justify-center text-white font-bold text-xl">
                        K
                    </div>
                    <span class="text-2xl font-bold"><?= $d_identitas['nama_website'] ?? 'KKG MI' ?></span>
                </div>
                <p class="text-gray-400 leading-relaxed mb-6">
                    <?= $d_identitas['deskripsi_header'] ?? 'Wadah komunikasi dan pengembangan profesionalisme Guru Madrasah Ibtidaiyah yang modern dan berintegritas.' ?>
                </p>
                <div class="flex space-x-4">
                    <a href="<?= $d_identitas['facebook'] ?? '#' ?>"
                        class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-emerald-600 hover:text-white transition-all duration-300">
                        <span class="sr-only">Facebook</span>
                        <!-- Icon placeholder -->
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                        </svg>
                    </a>
                    <a href="<?= $d_identitas['instagram'] ?? '#' ?>"
                        class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-emerald-600 hover:text-white transition-all duration-300">
                        <span class="sr-only">Instagram</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2.163c3.204 0 3.584.012 4.85.072 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.36-.2 6.78-2.618 6.98-6.98.058-1.28-.072-1.689.072-4.948 0-3.259-.014-3.667-.072-4.947-.2-4.361-2.617-6.782-6.98-6.98-1.281-.059-1.689-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div data-aos="fade-up" data-aos-delay="100">
                <h3 class="text-lg font-bold mb-6 border-l-4 border-emerald-500 pl-3">Tautan Cepat</h3>
                <ul class="space-y-4">
                    <li><a href="profil.php"
                            class="text-gray-400 hover:text-emerald-400 transition flex items-center gap-2"><span
                                class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Profil Organisasi</a></li>
                    <li><a href="kegiatan.php"
                            class="text-gray-400 hover:text-emerald-400 transition flex items-center gap-2"><span
                                class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Agenda Kegiatan</a></li>
                    <li><a href="informasi.php"
                            class="text-gray-400 hover:text-emerald-400 transition flex items-center gap-2"><span
                                class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Berita & Informasi</a></li>
                    <li><a href="unduhan.php"
                            class="text-gray-400 hover:text-emerald-400 transition flex items-center gap-2"><span
                                class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Area Unduhan</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div data-aos="fade-up" data-aos-delay="200">
                <h3 class="text-lg font-bold mb-6 border-l-4 border-emerald-500 pl-3">Hubungi Kami</h3>
                <ul class="space-y-4 text-gray-400">
                    <li class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-emerald-500 mt-1" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span><?= $d_identitas['alamat'] ?? 'Alamat belum diatur' ?></span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span><?= $d_identitas['email'] ?? 'email@example.com' ?></span>
                    </li>
                    <li class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span><?= $d_identitas['no_telp'] ?? '-' ?></span>
                    </li>
                </ul>
            </div>
        </div>

        <div
            class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-center md:justify-between items-center gap-4">
            <p class="text-gray-500 text-sm text-center md:text-left flex items-center gap-1">
                &copy; 2025 - <?= date('Y') ?> KKG-MI PAKET. Dibuat dengan <span class="text-green-500">💚</span> oleh
                <button onclick="openKoesnoModal()"
                    class="text-emerald-400 hover:text-emerald-300 font-medium hover:underline focus:outline-none transition-colors">
                    Koesno
                </button>
            </p>
        </div>
    </div>
</footer>

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
                            <img src="assets/images/koesno.png" alt="Koesno"
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

<!-- Scripts -->
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
            // closeKoesnoModal(); // Optional: close when clicking outside
        }
    });

    // Close on escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && !document.getElementById('koesnoModal').classList.contains('hidden')) {
            closeKoesnoModal();
        }
    });

    // Lightweight Scroll Animation (IntersectionObserver)
    document.addEventListener('DOMContentLoaded', () => {
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1
        };

        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('aos-animate');
                    observer.unobserve(entry.target); // Animate once
                }
            });
        }, observerOptions);

        document.querySelectorAll('[data-aos]').forEach((el) => {
            observer.observe(el);
        });
    });

    // Navbar Scroll Effect
    const navbar = document.getElementById('navbar');
    const mobileMenu = document.getElementById('mobile-menu');
    const mobileBtn = document.getElementById('mobile-menu-btn');

    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) {
                navbar.classList.add('glass-nav', 'shadow-sm', 'py-2');
                navbar.classList.remove('bg-transparent', 'py-4');
            } else {
                navbar.classList.add('bg-transparent', 'py-4');
                navbar.classList.remove('glass-nav', 'shadow-sm', 'py-2');
            }
        });
    }

    // Mobile Menu Toggle
    if (mobileBtn) {
        mobileBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
</script>
</body>

</html>