-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 02, 2026 at 11:27 AM
-- Server version: 8.4.3
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kkg`
--

-- --------------------------------------------------------

--
-- Table structure for table `agenda`
--

CREATE TABLE `agenda` (
  `id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `keterangan` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `file_lampiran` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `agenda`
--

INSERT INTO `agenda` (`id`, `judul`, `tanggal`, `lokasi`, `keterangan`, `created_at`, `file_lampiran`) VALUES
(1, 'rapat', '2025-12-30', 'cafe', 'sfdjkjsdf', '2025-12-30 18:23:59', NULL),
(2, 'asdfjhksadlfh', '2025-12-10', 'dfs', 'dsff', '2026-01-01 15:44:34', NULL),
(3, 'dsfadsfsf', '2025-11-11', 'asdfasf', 'sadffa', '2026-01-01 15:44:47', NULL),
(4, 'adfsadfds', '2026-01-16', 'adfa', 'asdf', '2026-01-01 15:45:32', NULL),
(5, 'ghfgj', '2026-01-23', 'hjgh', 'dvzv', '2026-01-01 17:01:24', '1767286884_6956a864c6891.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `penulis` varchar(100) DEFAULT 'Admin',
  `tanggal` date NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `isi` longtext NOT NULL,
  `is_featured` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id`, `judul`, `slug`, `kategori`, `penulis`, `tanggal`, `gambar`, `isi`, `is_featured`, `created_at`) VALUES
(1, 'Kementerian Agama Luncurkan Program Digitalisasi Madrasah 2025', 'kementerian-agama-luncurkan-program-digitalisasi-madrasah-2025', 'Berita', 'Admin Pusat', '2025-01-02', 'https://images.unsplash.com/photo-1497633762265-9d179a990aa6?ixlib=rb-4.0.3', '<p>Konten berita lengkap disini...</p>', 0, '2025-12-30 17:12:21'),
(2, 'Implementasi Kurikulum Merdeka di Tingkat Madrasah Ibtidaiyah', 'implementasi-kurikulum-merdeka-di-tingkat-madrasah-ibtidaiyah', 'Kurikulum', 'Admin', '2024-12-28', 'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?ixlib=rb-4.0.3', '<p>Panduan lengkap bagi guru...</p>', 1, '2025-12-30 17:12:21'),
(3, 'Siswa MI Kota Contoh Raih Medali Emas Olimpiade Sains Nasional', 'siswa-mi-medali-emas', 'Prestasi', 'Humas', '2024-12-15', 'https://images.unsplash.com/photo-1571260899304-425eee4c7efc?ixlib=rb-4.0.3', 'Prestasi membanggakan kembali ditorehkan...', 0, '2025-12-30 17:12:21');

-- --------------------------------------------------------

--
-- Table structure for table `galeri`
--

CREATE TABLE `galeri` (
  `id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `tanggal` date NOT NULL,
  `foto` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galeri`
--

INSERT INTO `galeri` (`id`, `judul`, `tanggal`, `foto`, `created_at`) VALUES
(1, 'Lomba Adzan Siswa MI', '2025-12-10', 'https://images.unsplash.com/photo-1599547525367-17eb48679cb1?ixlib=rb-4.0.3', '2026-01-01 14:42:55'),
(2, 'Pelatihan Guru Fiqih', '2025-11-20', 'assets/img/galeri/6956881014ed9_galeri.jpg', '2026-01-01 14:42:55'),
(3, 'Rapat Kerja Tahunan', '2025-10-15', 'https://images.unsplash.com/photo-1577962917302-cd874c4e31d2?ixlib=rb-4.0.3', '2026-01-01 14:42:55'),
(4, 'Kunjungan Kemenag', '2025-09-05', 'https://images.unsplash.com/photo-1558403194-611308249627?ixlib=rb-4.0.3', '2026-01-01 14:42:55'),
(5, 'dsafds', '2026-01-01', 'assets/img/galeri/6956a209108db_thumb.jpg', '2026-01-01 16:34:17');

-- --------------------------------------------------------

--
-- Table structure for table `galeri_foto`
--

CREATE TABLE `galeri_foto` (
  `id` int NOT NULL,
  `id_galeri` int NOT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galeri_foto`
--

INSERT INTO `galeri_foto` (`id`, `id_galeri`, `foto`) VALUES
(1, 2, 'assets/img/galeri/6956899537fe3_sub_0.jpg'),
(2, 2, 'assets/img/galeri/695689953c439_sub_1.jpg'),
(3, 2, 'assets/img/galeri/695689953ef8e_sub_2.jpg'),
(4, 2, 'assets/img/galeri/695689953fff3_sub_3.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `guru`
--

CREATE TABLE `guru` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `asal_madrasah` varchar(100) NOT NULL,
  `status` enum('Aktif','Non-Aktif') DEFAULT 'Aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `guru`
--

INSERT INTO `guru` (`id`, `nama`, `nip`, `asal_madrasah`, `status`, `created_at`) VALUES
(1, 'Guru 1', NULL, 'MI A', 'Aktif', '2026-01-01 15:04:06'),
(2, 'Guru 2', NULL, 'MI B', 'Aktif', '2026-01-01 15:04:06'),
(3, 'Budi Santoso S.Pd', NULL, 'MI Darul Ulum', 'Aktif', '2026-01-01 15:12:32'),
(4, 'Siti Aminah S.Pd.I', NULL, 'MI Nurul Huda', 'Aktif', '2026-01-01 15:12:32'),
(5, 'Ahmad Fauzi S.Pd', NULL, 'MI Al-Ikhlas', 'Aktif', '2026-01-01 15:31:31'),
(6, 'Siti Nurhaliza', NULL, 'MI Darussalam', 'Aktif', '2026-01-01 15:31:31'),
(8, 'Ahmad Fauzi S.Pd', NULL, 'MI Al-Ikhlas', 'Aktif', '2026-01-01 15:31:31'),
(9, 'Siti Nurhaliza', NULL, 'MI Darussalam', 'Aktif', '2026-01-01 15:31:31'),
(10, 'Budi Santoso', NULL, 'MI Nurul Huda', 'Non-Aktif', '2026-01-01 15:31:31'),
(11, 'Ahmad Fauzi S.Pd', NULL, 'MI Al-Ikhlas', 'Aktif', '2026-01-01 15:31:31'),
(12, 'Siti Nurhaliza', NULL, 'MI Darussalam', 'Aktif', '2026-01-01 15:31:31'),
(13, 'Budi Santoso', NULL, 'MI Nurul Huda', 'Non-Aktif', '2026-01-01 15:31:31'),
(14, 'Ahmad Fauzi S.Pd', NULL, 'MI Al-Ikhlas', 'Aktif', '2026-01-01 15:31:31'),
(15, 'Siti Nurhaliza', NULL, 'MI Darussalam', 'Aktif', '2026-01-01 15:31:31'),
(16, 'Budi Santoso', NULL, 'MI Nurul Huda', 'Non-Aktif', '2026-01-01 15:31:31');

-- --------------------------------------------------------

--
-- Table structure for table `identitas`
--

CREATE TABLE `identitas` (
  `id` int NOT NULL,
  `nama_website` varchar(100) DEFAULT 'KKG MI',
  `nama_organisasi` varchar(100) DEFAULT 'Kelompok Kerja Guru Madrasah Ibtidaiyah',
  `slogan_header` varchar(255) DEFAULT 'Wadah Profesionalisme Guru Madrasah',
  `deskripsi_header` text,
  `foto_hero` varchar(255) DEFAULT '',
  `file_logo` varchar(255) DEFAULT NULL,
  `file_favicon` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT 'info@kkgmi.org',
  `no_telp` varchar(50) DEFAULT '+62 812 3456 7890',
  `alamat` text,
  `facebook` varchar(255) DEFAULT '#',
  `instagram` varchar(255) DEFAULT '#'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `identitas`
--

INSERT INTO `identitas` (`id`, `nama_website`, `nama_organisasi`, `slogan_header`, `deskripsi_header`, `foto_hero`, `file_logo`, `file_favicon`, `email`, `no_telp`, `alamat`, `facebook`, `instagram`) VALUES
(1, 'KKG MI PAKET', 'Kelompok Kerja Guru Madrasah Ibtidaiyah', 'Wadah Profesionalisme Guru Madrasah', 'Membangun sinergi, meningkatkan kompetensi, dan mencetak generasi rabbani yang berprestasi melalui kolaborasi aktif Kelompok Kerja Guru.', 'hero_1767342759.png', NULL, NULL, 'kkgmipaket@gmail.com', '+62 896 9947 0625', 'Jl. KH.  Ma\'arif, No.45 - Ds. Grobog wetan - Kec. Pangkah - Kab. Tegal', '#', '#');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan`
--

CREATE TABLE `kegiatan` (
  `id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `tanggal` date NOT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `deskripsi` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kegiatan`
--

INSERT INTO `kegiatan` (`id`, `judul`, `slug`, `kategori`, `tanggal`, `lokasi`, `gambar`, `deskripsi`, `created_at`) VALUES
(1, 'Guru Madrasah Siap Menyongsong Kurikulum Merdeka: KKG MI Pangkah-Kedungbanteng Gelar Bimtek Kolaboratif ', 'guru-madrasah-siap-menyongsong-kurikulum-merdeka-kkg-mi-pangkah-kedungbanteng-gelar-bimtek-kolaboratif-', 'Workshop', '2025-06-04', 'MI Raden Fatah 01 Grobog Wetan', 'assets/img/kegiatan/69578da8349e1.jpeg', '<p style=\"text-align: justify;\"><strong><em>Grobog Wetan </em> &mdash; </strong>Suasana MI Raden Fatah 01 Grobog Wetan tampak berbeda dari biasanya. Kamis pagi itu, madrasah menjadi tuan rumah bagi sebuah langkah besar dalam transformasi pendidikan: Bimbingan Teknis (Bimtek) Kurikulum Merdeka bagi guru kelas 3 dan 6 dari Kecamatan Pangkah dan Kedungbanteng. Sebanyak 34 guru antusias mengikuti kegiatan ini sebagai bagian dari komitmen bersama untuk memajukan pendidikan madrasah.</p>\r\n<p style=\"text-align: justify;\">Kegiatan ini menjadi sinergi nyata antara Kelompok Kerja Guru (KKG) dua kecamatan yang ingin memastikan para pendidik siap menghadapi perubahan kurikulum. Fokus utama pelatihan adalah bagaimana Kurikulum Merdeka dapat diterapkan secara kontekstual dan kreatif di lingkungan madrasah ibtidaiyah, yang memiliki karakteristik unik.</p>\r\n<p style=\"text-align: justify;\"><img src=\"https://tegal.kemenag.go.id/wp-content/uploads/2025/05/WhatsApp-Image-2025-05-15-at-13.38.41.jpeg\" alt=\"\"></p>\r\n<p style=\"text-align: justify;\">Bimtek kali ini menghadirkan sosok istimewa sebagai narasumber: H. Shofar Sholahudin Bisri Instruktur Nasional dari Kemendikbudristek. Dalam pemaparannya, ia tak hanya menyajikan teori, tetapi juga membawa semangat filosofi &ldquo;merdeka belajar&rdquo; secara hidup. Mulai dari penyusunan modul ajar yang relevan, praktik asesmen formatif, hingga pendekatan pembelajaran berdiferensiasi, semua dibedah dengan cara yang aplikatif.</p>\r\n<p style=\"text-align: justify;\">Acara dibuka oleh Pengawas MI Kabupaten Tegal, H. Saekhun, yang dalam sambutannya memuji inisiatif KKG sebagai bentuk kepedulian nyata terhadap mutu pendidikan. &ldquo;Transformasi kurikulum harus dimulai dari guru yang siap, bukan hanya secara administratif, tapi juga secara mindset dan kompetensi,&rdquo; tegasnya di hadapan peserta.</p>\r\n<p style=\"text-align: justify;\"><img src=\"https://tegal.kemenag.go.id/wp-content/uploads/2025/05/WhatsApp-Image-2025-05-15-at-09.23.45-edited.jpeg\" alt=\"\"></p>\r\n<p style=\"text-align: justify;\">Ketua KKM MI Kecamatan Pangkah, Khuzaeni, juga menegaskan bahwa kegiatan ini bukan sekadar rutinitas, melainkan bagian dari gerakan kolektif untuk saling berbagi praktik baik. &ldquo;Kita ingin madrasah tidak tertinggal. Justru harus menjadi pelopor penerapan Kurikulum Merdeka yang berakar pada nilai dan karakter,&rdquo; ujarnya penuh semangat.</p>\r\n<p style=\"text-align: justify;\">Tak hanya mendengarkan materi, peserta Bimtek juga diajak langsung untuk praktik menyusun modul ajar dan menyimulasikan pembelajaran berdiferensiasi. Diskusi kelompok berjalan dinamis, guru-guru tampak antusias mengeksplorasi ide, saling bertanya, dan berbagi pengalaman. Energi perubahan tampak begitu terasa di ruang pelatihan sederhana itu.</p>\r\n<p style=\"text-align: justify;\">Banyak peserta mengaku mendapat wawasan baru dari sesi ini. &ldquo;Bimtek ini luar biasa, kami jadi lebih yakin menyusun pembelajaran yang sesuai dengan kebutuhan siswa di madrasah kami,&rdquo; ujar salah satu peserta dari MI di Kecamatan Kedungbanteng. Hal ini menunjukkan bahwa ketika guru diberi ruang belajar yang tepat, maka semangat inovasi pun akan tumbuh.</p>\r\n<p style=\"text-align: justify;\">Bimtek ini menjadi bukti bahwa madrasah bukan hanya pelaku pendidikan berbasis agama, tetapi juga bagian penting dari gerakan pembaruan pendidikan nasional. Langkah KKG MI Pangkah-Kedungbanteng patut diapresiasi sebagai contoh nyata kolaborasi guru dalam menghadapi dinamika kurikulum.</p>\r\n<p style=\"text-align: justify;\">Dengan bekal yang diperoleh dari kegiatan ini, diharapkan para guru MI siap menghadirkan kelas yang lebih hidup, ramah anak, dan adaptif terhadap kebutuhan zaman. Kurikulum Merdeka bukan lagi sekadar dokumen, tapi telah menjadi semangat yang menyatu dalam denyut nadi pembelajaran di madrasah.</p>', '2025-12-30 17:12:21'),
(2, 'Pengurus KKG-MI PAKET Gelar Rapat Kerja di MI Miftahul Ulum Kebandingan', 'pengurus-kkg-mi-paket-gelar-rapat-kerja-di-mi-miftahul-ulum-kebandingan', 'Rapat', '2025-01-20', 'MI Miftahul Ulum Kebandingan', 'assets/img/kegiatan/69578be8efce4.jpg', '<p style=\"text-align: justify;\"><strong>Kebandingan, 20 Januari 2025 &ndash; </strong>Kelompok Kerja Guru (KKG) Madrasah Ibtidaiyah (MI) Kecamatan Pangkah dan Kedungbanteng mengadakan rapat kerja di MI Miftahul Ulum, Desa Kebandingan, Kecamatan Kedungbanteng. Acara yang berlangsung pada pukul 10.00 hingga 13.00 ini membahas program kerja untuk tiga tahun mendatang dengan fokus utama pada peningkatan kompetensi guru di era digital.</p>\r\n<p style=\"text-align: justify;\">Dalam rapat tersebut, seluruh pengurus KKG hadir dan aktif berpartisipasi. Program kerja yang dirancang menitikberatkan pada upaya peningkatan kualitas pendidikan, khususnya melalui pengembangan keterampilan guru dalam memanfaatkan teknologi digital. Langkah ini diharapkan dapat menjawab tantangan dan kebutuhan pendidikan di era modern.</p>\r\n<p style=\"text-align: justify;\">Ketua KKG MI Kecamatan Pangkah menyampaikan bahwa program kerja ini dirancang sebagai respons terhadap perubahan yang semakin cepat di dunia pendidikan. \"Kami berkomitmen untuk mendukung guru-guru agar lebih adaptif dan inovatif dalam mengajar, terutama dengan pemanfaatan teknologi digital,\" ujarnya.</p>\r\n<p style=\"text-align: justify;\">Acara ini berjalan lancar dan diakhiri dengan kesepakatan bersama mengenai langkah-langkah strategis yang akan diterapkan. Dengan adanya program kerja ini, diharapkan kualitas pendidikan di wilayah Kecamatan Pangkah dan Kedingbanteng, khususnya di tingkat Madrasah Ibtidaiyah, dapat semakin meningkat dan mampu menghasilkan generasi yang unggul dan berdaya saing di masa depan.</p>\r\n<p style=\"text-align: justify;\"><img src=\"http://localhost/kkgpaket/assets/img/content/69578c09a2c96_586685fadf26403b5b319b456a1fd6f5.jpg\" alt=\"\" width=\"710\" height=\"460\"></p>', '2025-12-30 17:12:21'),
(3, 'Lengkapi kepengurusan. Ketua terpilih dan Pengurus harian adakan rapat di MI Raden fatah 01 ', 'lengkapi-kepengurusan-ketua-terpilih-dan-pengurus-harian-adakan-rapat-di-mi-raden-fatah-01-', 'Rapat', '2025-01-07', 'MI Raden Fatah 01 Grobog Wetan', 'assets/img/kegiatan/695789b47b7ce.jpeg', '<p style=\"text-align: justify;\">Grobog Wetan, 7 Januari 2025 &ndash; Untuk melengkapi struktur kepengurusan, Ketua Terpilih Moh. Khafidin bersama Pengurus Harian (PH) Kelompok Kerja Guru Madrasah Ibtidaiyah (KKGMI) Kecamatan Pangkah dan Kedungbanteng mengadakan rapat koordinasi di Madrasah Ibtidaiyah (MI) Raden Fatah, Grobog Wetan, pada Selasa, 7 Januari 2025.</p>\r\n<p style=\"text-align: justify;\"><img src=\"https://kkg.kkmpaket.com/assets/images/2722f1996e4505400ff7a4f1ca98b25c.jpeg\" alt=\"\">Rapat tersebut dihadiri oleh Ketua Terpilih Moh. Khafidin Pengurus Harian. Fokus utama rapat adalah untuk menyempurnakan kelengkapan struktur kepengurusan KKGMI, memastikan semua posisi dalam kepengurusan terisi dengan tepat, serta menentukan pembagian tugas dan tanggung jawab masing-masing pengurus.</p>\r\n<p style=\"text-align: justify;\">Dalam sambutannya, Ketua Terpilih Moh. Khafidin menekankan pentingnya keberlanjutan dan soliditas organisasi KKGMI dalam menjalankan tugas-tugasnya ke depan. &ldquo;Sebagai langkah awal, rapat ini bertujuan untuk memastikan bahwa setiap pengurus memiliki tanggung jawab yang jelas dan bisa menjalankan peranannya dengan efektif. Dengan struktur yang lengkap, kita bisa bergerak lebih cepat dalam mewujudkan tujuan pendidikan yang lebih baik,&rdquo; ujar Moh. Khafidin.</p>\r\n<p style=\"text-align: justify;\">Rapat ini berjalan dengan lancar dan menghasilkan keputusan penting mengenai pembagian tugas antar pengurus, serta penetapan beberapa posisi dalam struktur organisasi yang masih kosong. Di akhir rapat, seluruh pengurus berkomitmen untuk saling mendukung dan bekerja sama dalam menjalankan setiap program yang akan datang.</p>\r\n<p style=\"text-align: justify;\">MI Raden Fatah, yang menjadi tuan rumah rapat, berharap dapat terus berkontribusi dalam mendukung kegiatan KKGMI serta menjadi contoh bagi madrasah lain dalam pengelolaan organisasi yang efektif.</p>', '2025-12-30 17:12:21'),
(4, 'KKG MI Pangkah dan Kedungbanteng Gelar Sosialisasi Kurikulum 2025 dan Penerapan Kurikulum Berbasis Cinta', 'kkg-mi-pangkah-dan-kedungbanteng-gelar-sosialisasi-kurikulum-2025-dan-penerapan-kurikulum-berbasis-cinta', 'Workshop', '2025-11-26', 'MI Ma\'arif NU Bogares Kidul', 'assets/img/kegiatan/69578e3213146.jpeg', '<p><strong>Bogares Kidul, [26 Nopember 2025]</strong> &ndash; Kelompok Kerja Guru Madrasah Ibtidaiyah (KKG MI) Kecamatan Pangkah dan Kecamatan Kedungbanteng hari ini sukses melaksanakan kegiatan sosialisasi kurikulum yang sangat penting dalam menyongsong tahun ajaran mendatang. Acara ini berfokus pada <strong>Penerapan Kurikulum 2025 berdasarkan Keputusan Menteri Agama (KMA)</strong> dan penguatan implementasi <strong>Kurikulum Berbasis Cinta</strong>.</p>\r\n<p>Kegiatan berlangsung di <strong>MI Ma\'arif NU Bogares Kidul</strong>, dihadiri oleh puluhan guru MI dari dua wilayah kecamatan, yang menunjukkan komitmen tinggi dalam peningkatan mutu pendidikan madrasah.</p>\r\n<p>Ketua KKM&nbsp;MI Bapak Khuzaeni, S.Pd.I menyampaikan bahwa kegiatan ini merupakan respons cepat KKG dalam menyikapi kebijakan kurikulum terbaru dari Kementerian Agama.</p>\r\n<p>\"Kami menyadari pentingnya pemahaman yang utuh dan seragam mengenai KMA yang mengatur Kurikulum 2025. Barangkali ini adalah sosialisasi yang pertama kali di lakukan setelah Surat edaran muncul.\" ujar beliau.</p>\r\n<p>Narasumber utama dalam kegiatan ini adalah <strong>Bapak Drs. H. Saekhun</strong>, seorang tokoh dan pakar pendidikan yang kredibel. Dalam sesinya, Bapak Saekhun mengupas tuntas kerangka kerja Kurikulum 2025, memberikan panduan praktis agar guru dapat mempersiapkan diri secara optimal.</p>\r\n<p>Materi tentang <strong>Penerapan Kurikulum Berbasis Cinta</strong> menjadi sesi yang sangat menarik. Bapak Drs. H. Saekhun menekankan bahwa kualitas hasil belajar siswa tidak hanya ditentukan oleh transfer ilmu (kognitif), tetapi juga oleh iklim kelas yang dipenuhi kasih sayang, empati, dan penghargaan.</p>\r\n<blockquote>\r\n<p>\"Kurikulum Berbasis Cinta bukan sekadar mata pelajaran tambahan, melainkan filosofi mendasar dalam mendidik. Guru adalah figur cinta yang harus menumbuhkan rasa aman, percaya diri, dan motivasi intrinsik pada diri setiap anak,\" jelas Bapak Saekhun.</p>\r\n</blockquote>\r\n<p>Kegiatan diakhiri dengan sesi tanya jawab yang interaktif, menunjukkan antusiasme tinggi dari para guru untuk mengimplementasikan ilmu yang diperoleh di madrasah masing-masing. Diharapkan, sinergi antara kebijakan Kurikulum 2025 dan pendekatan berbasis cinta ini akan melahirkan generasi madrasah yang cerdas, berakhlak mulia, dan berkarakter kuat.</p>', '2025-12-30 18:10:08');

-- --------------------------------------------------------

--
-- Table structure for table `pengurus`
--

CREATE TABLE `pengurus` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `jabatan` varchar(100) NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `urutan` int DEFAULT '99',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengurus`
--

INSERT INTO `pengurus` (`id`, `nama`, `jabatan`, `foto`, `urutan`, `created_at`) VALUES
(1, 'Muhamad Khafiduddin, S.Sos', 'Ketua KKG', 'https://ui-avatars.com/api/?name=Ahmad+Fauzi&background=059669&color=fff', 1, '2025-12-30 17:12:21'),
(2, 'M. Ali Maliki', 'Sekretaris', 'https://ui-avatars.com/api/?name=Siti+Aminah&background=059669&color=fff', 3, '2025-12-30 17:12:21'),
(3, 'Eva Fauziyana, S.Pd.I', 'Bendahara', 'https://ui-avatars.com/api/?name=Rina+Wati&background=059669&color=fff', 4, '2025-12-30 17:12:21'),
(4, 'Diyah Karlita, AF', 'Wakil Bendahara', 'https://ui-avatars.com/api/?name=Budi+Santoso&background=059669&color=fff', 5, '2025-12-30 17:12:21'),
(5, 'Yusuf Asy\'Ari, S.Pd.I', 'Wakil Ketua', '', 2, '2026-01-01 14:17:31');

-- --------------------------------------------------------

--
-- Table structure for table `pesan`
--

CREATE TABLE `pesan` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subjek` varchar(100) DEFAULT NULL,
  `isi_pesan` text NOT NULL,
  `status` enum('baca','belum') DEFAULT 'belum',
  `tanggal` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rdm_links`
--

CREATE TABLE `rdm_links` (
  `id` int NOT NULL,
  `nama_madrasah` varchar(100) NOT NULL,
  `url_rdm` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `gambar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rdm_links`
--

INSERT INTO `rdm_links` (`id`, `nama_madrasah`, `url_rdm`, `created_at`, `gambar`) VALUES
(1, 'MI Raden Fatah 01 Grobog Wetan', 'https://rdm.mirafa01.web.id', '2026-01-01 15:46:28', 'rdm_1767282885.png'),
(2, 'MI Darul Ulum', 'https://rdm.kemenag.go.id', '2026-01-01 15:46:28', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `unduhan`
--

CREATE TABLE `unduhan` (
  `id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `kategori` varchar(50) NOT NULL,
  `nama_file` varchar(255) NOT NULL,
  `tipe_file` varchar(20) NOT NULL,
  `ukuran_file` varchar(20) DEFAULT NULL,
  `diunggah_oleh` varchar(100) DEFAULT 'Admin',
  `tanggal_upload` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `unduhan`
--

INSERT INTO `unduhan` (`id`, `judul`, `kategori`, `nama_file`, `tipe_file`, `ukuran_file`, `diunggah_oleh`, `tanggal_upload`) VALUES
(1, 'RPP Kurikulum Merdeka - Fiqih Kelas 1', 'Perangkat Ajar', 'rpp_fiqih_k1.pdf', 'pdf', '2.5 MB', 'Ustadz Amin', '2025-12-30 17:12:21'),
(2, 'Kalender Pendidikan Madrasah 2025/2026', 'Regulasi', 'kaldik_2025.docx', 'docx', '1.2 MB', 'Admin Pusat', '2025-12-30 17:12:21'),
(3, 'Modul Ajar Bahasa Arab Kelas 4', 'Perangkat Ajar', 'modul_b_arab_k4.xlsx', 'xlsx', '500 KB', 'Ustadzah Siti', '2025-12-30 17:12:21'),
(4, 'tes', 'Perangkat Ajar', '695687d06256d_1222-2754-1-SM.pdf', 'pdf', '978.88 KB', 'Administrator', '2026-01-01 14:42:24');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `role` enum('admin','editor') DEFAULT 'admin',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `nama_lengkap`, `role`, `created_at`) VALUES
(1, 'admin', '$2a$12$cMNLcd9RoGJI7PJ4sVCc6Ou4fBGNSZg4A6ZNvxQQCxf2r0SUUa55a', 'Administrator', 'admin', '2025-12-30 17:12:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agenda`
--
ALTER TABLE `agenda`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `galeri_foto`
--
ALTER TABLE `galeri_foto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_galeri` (`id_galeri`);

--
-- Indexes for table `guru`
--
ALTER TABLE `guru`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `identitas`
--
ALTER TABLE `identitas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pengurus`
--
ALTER TABLE `pengurus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pesan`
--
ALTER TABLE `pesan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rdm_links`
--
ALTER TABLE `rdm_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `unduhan`
--
ALTER TABLE `unduhan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agenda`
--
ALTER TABLE `agenda`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `galeri_foto`
--
ALTER TABLE `galeri_foto`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `guru`
--
ALTER TABLE `guru`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pengurus`
--
ALTER TABLE `pengurus`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pesan`
--
ALTER TABLE `pesan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rdm_links`
--
ALTER TABLE `rdm_links`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `unduhan`
--
ALTER TABLE `unduhan`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `galeri_foto`
--
ALTER TABLE `galeri_foto`
  ADD CONSTRAINT `galeri_foto_ibfk_1` FOREIGN KEY (`id_galeri`) REFERENCES `galeri` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
