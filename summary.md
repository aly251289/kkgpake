Saya ingin kamu bertindak sebagai full-stack web developer profesional.

Buatkan WEBSITE RESMI KKG MI (Kelompok Kerja Guru Madrasah Ibtidaiyah)
menggunakan PHP native dan MySQL
dengan desain modern, profesional, islami, dan responsive.

================================
1. KETENTUAN TEKNIS
================================
- Backend: PHP native (tanpa framework)
- Database: MySQL
- Frontend: HTML5, CSS3, JavaScript
- CSS Framework: Tailwind CSS
- Responsive: Mobile, Tablet, Desktop
- Gunakan struktur layout modular (header, footer, content)
- Gunakan prepared statement (mysqli / PDO)
- Gunakan session untuk login admin
- Kode rapi, mudah dikembangkan

================================
2. STRUKTUR MENU WEBSITE
================================
1. Beranda
2. Profil
3. Kegiatan
4. Informasi
5. Unduhan
6. Kontak

================================
3. DESAIN UI & LAYOUT
================================

A. GLOBAL UI
- Warna utama: hijau tua / emerald (islami & profesional)
- Warna pendukung: putih, abu-abu, aksen emas lembut
- Font: Sans-serif modern (Inter / Poppins)
- Header sticky, navbar responsive (hamburger menu mobile)
- Footer resmi dengan informasi lengkap

B. HEADER
- Logo KKG MI di kiri
- Menu navigasi di kanan
- Efek hover modern
- Tombol menu collapse di mobile

C. FOOTER
- Nama KKG MI
- Alamat sekretariat
- Kontak WhatsApp & Email
- Copyright

================================
4. DETAIL UI PER HALAMAN
================================

▶ BERANDA
- Hero section:
  - Judul besar: "Kelompok Kerja Guru MI"
  - Subjudul/tagline profesional
  - Background gradient atau foto kegiatan
- Section Sambutan Ketua
- Section Profil Singkat KKG
- Section Kegiatan Terbaru (card grid)
- Section Pengumuman Penting
- Tombol cepat: Kegiatan | Unduhan | Kontak

▶ PROFIL
- Halaman statis
- Section:
  - Tentang KKG MI
  - Visi dan Misi
  - Struktur Organisasi (card grid / tabel)
  - Program Kerja

▶ KEGIATAN
- List kegiatan dalam bentuk card
- Detail kegiatan:
  - Nama kegiatan
  - Tanggal
  - Deskripsi
  - Dokumentasi foto
  - Laporan PDF (jika ada)

▶ INFORMASI
- Halaman berita & pengumuman
- Tampilan list + detail
- Thumbnail gambar
- Pagination sederhana

▶ UNDUHAN
- Tabel / card daftar file
- Kategori:
  - Modul KKG
  - Administrasi Guru
  - Perangkat Pembelajaran
- Tombol download

▶ KONTAK
- Informasi alamat lengkap
- Nomor WhatsApp
- Email resmi
- Google Maps embed
- Form kontak (nama, email, pesan)

================================
5. STRUKTUR FOLDER
================================
/
├ index.php
├ profil.php
├ kegiatan.php
├ informasi.php
├ unduhan.php
├ kontak.php
├ /config
│  └ koneksi.php
├ /partials
│  ├ header.php
│  └ footer.php
├ /assets
│  ├ css
│  ├ js
│  └ img
├ /admin
│  ├ login.php
│  ├ dashboard.php
│  ├ berita.php
│  ├ kegiatan.php
│  ├ unduhan.php
│  ├ pengumuman.php
│  └ logout.php

================================
6. FITUR ADMIN PANEL
================================
- Login admin (session)
- Dashboard statistik sederhana
- CRUD Berita
- CRUD Kegiatan
- CRUD Unduhan
- Upload file (PDF, JPG, PNG)
- Validasi input
- Logout

================================
7. STRUKTUR DATABASE
================================
Buatkan SQL Database lengkap:

TABEL users:
- id (PK)
- username
- password (hash)
- role

TABEL berita:
- id
- judul
- isi
- thumbnail
- tanggal

TABEL kegiatan:
- id
- nama_kegiatan
- tanggal
- deskripsi

TABEL unduhan:
- id
- judul
- file
- kategori

TABEL pengumuman:
- id
- judul
- isi
- tanggal

================================
8. OUTPUT YANG DIMINTA
================================
- Struktur folder
- Kode HTML + Tailwind CSS
- Kode PHP (frontend & admin)
- Script SQL siap import
- UI modern dan responsive
- Contoh data dummy

================================
CATATAN:
Website ini digunakan untuk lembaga pendidikan (KKG MI),
tampilan harus formal, bersih, dan mudah diakses guru.
