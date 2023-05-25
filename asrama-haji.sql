-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 25 Bulan Mei 2023 pada 05.47
-- Versi server: 10.4.25-MariaDB
-- Versi PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `asrama-haji`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_lengkap` varchar(255) NOT NULL,
  `email` varchar(25) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `last_login` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`, `nama_lengkap`, `email`, `token`, `last_login`) VALUES
(1, 'admin', '$2y$10$bzBsOlPgaQiWLyXnQfsKVu2iSL5ZF6yDkCcpVabx4s90MnQwFOzt6', 'Asrama Haji Medan', 'yt20062002@gmail.com', NULL, '2023-04-15 20:29:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `asrama`
--

CREATE TABLE `asrama` (
  `asrama_id` int(5) NOT NULL,
  `username` varchar(30) NOT NULL,
  `asrama_nama` varchar(30) NOT NULL,
  `asrama_title_seo` varchar(255) NOT NULL,
  `asrama_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `asrama_gambar` varchar(255) NOT NULL,
  `asrama_fasilitas` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `asrama`
--

INSERT INTO `asrama` (`asrama_id`, `username`, `asrama_nama`, `asrama_title_seo`, `asrama_status`, `asrama_gambar`, `asrama_fasilitas`) VALUES
(1, 'admin', 'Nurul', 'nurul', '', '1684501209_8bca8e56abf705946c07.jpg', '<ol><li>KAMAR 2</li><li>KIPAS 1</li></ol>');

-- --------------------------------------------------------

--
-- Struktur dari tabel `konfigurasi`
--

CREATE TABLE `konfigurasi` (
  `id` int(5) UNSIGNED NOT NULL,
  `konfigurasi_name` varchar(255) NOT NULL,
  `konfigurasi_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `konfigurasi`
--

INSERT INTO `konfigurasi` (`id`, `konfigurasi_name`, `konfigurasi_value`) VALUES
(1, 'set_socials_facebook', 'https://www.facebook.com/profile.php?id=100009228323133'),
(2, 'set_socials_instagram', 'https://www.instagram.com/dionie_adam/'),
(3, 'set_socials_whatsapp', 'https://api.whatsapp.com/send/?phone=6282197797919&amp;text&amp;type=phone_number&amp;app_absent=0'),
(4, 'set_halaman_depan', '5'),
(5, 'set_halaman_kontak', '6'),
(6, 'set_halaman_kontak', '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2023-04-15-201605', 'App\\Database\\Migrations\\Admin', 'default', 'App', 1681590251, 1),
(2, '2023-04-19-130948', 'App\\Database\\Migrations\\ModifyColumnTokenAdmin', 'default', 'App', 1681909888, 2),
(3, '2023-04-25-135611', 'App\\Database\\Migrations\\Posts', 'default', 'App', 1682431746, 3),
(4, '2023-05-17-144119', 'App\\Database\\Migrations\\About', 'default', 'App', 1684334779, 4),
(5, '2023-05-17-163522', 'App\\Database\\Migrations\\Konfigurasi', 'default', 'App', 1684341428, 5),
(6, '2023-05-19-114343', 'App\\Database\\Migrations\\Asrama', 'default', 'App', 1684497775, 6);

-- --------------------------------------------------------

--
-- Struktur dari tabel `posts`
--

CREATE TABLE `posts` (
  `post_id` int(5) NOT NULL,
  `username` varchar(30) NOT NULL,
  `post_title` varchar(255) NOT NULL,
  `post_title_seo` varchar(255) NOT NULL,
  `post_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `post_type` enum('article','page') NOT NULL DEFAULT 'article',
  `post_thumbnail` varchar(255) NOT NULL,
  `post_description` varchar(255) NOT NULL,
  `post_content` longtext NOT NULL,
  `post_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `posts`
--

INSERT INTO `posts` (`post_id`, `username`, `post_title`, `post_title_seo`, `post_status`, `post_type`, `post_thumbnail`, `post_description`, `post_content`, `post_time`) VALUES
(1, 'admin', 'Usai Lakukan Pembongkaran, Satpol PP Tinggalkan Ruko \'Makan Jalan\' di Pluit  Baca artikel detiknews, \"Usai Lakukan Pembongkaran, Satpol PP Tinggalkan Ruko \'Makan Jalan\' di Pluit', 'usai-lakukan-pembongkaran--satpol-pp-tinggalkan-ruko--makan-jalan--di-pluit--baca-artikel-detiknews---usai-lakukan-pembongkaran--satpol-pp-tinggalkan-ruko--makan-jalan--di-pluit', 'active', 'article', '1684929471_737a8ca180458c408cf0.jpg', 'Satpol PP DKI Jakarta membongkar bangunan yang \'memakan jalan\' di sejumlah ruko di Jalan Niaga, Pluit, Jakarta Utara (Jakut). Setelah melakukan pembongkaran, Satpol PP langsung meninggalkan lokasi.', '<p><span style=\"font-family:\'Helvetica-FF\', Arial, Tahoma, sans-serif;\">Beberapa karyawan dan tukang bangunan terlihat membersihkan area dari puing-puing tersebut. Kepala Satuan Polisi Pamong Praja (Sat Pol PP) Jakarta Utara Muhammadong mengatakan pihaknya akan memonitor pembongkaran itu lagi</span></p>', '2023-05-24 11:57:51'),
(2, 'admin', 'PSMS Tak Setuju Liga 2 Baru Start November', 'psms-tak-setuju-liga-2-baru-start-november', 'active', 'article', '1684929539_1f5bea1c80a443947444.jpg', 'Manajer PSMS Medan, Mulyadi Simatupang mengatakan Liga 2 musim 2023/2024 idealnya dimulai dua minggu setelah kick-off musim baru Liga 1, bukan pada November 2023 seperti dibahas dalam Sarasehan Sepak Bola pada Maret 2023.', '<p style=\"margin-right:0px;margin-bottom:10px;margin-left:0px;padding:0px 0px 10px;border:0px;font-size:15px;line-height:24px;font-family:Arial, sans-serif;vertical-align:baseline;letter-spacing:0.5px;color:rgb(51,51,51);\">Manajer <a style=\"margin:0px;padding:0px;border:0px;font-style:inherit;font-variant:inherit;font-weight:700;font-size:inherit;line-height:inherit;vertical-align:baseline;color:rgb(206,73,14);background-position:0px 0px;background-size:initial;text-decoration:none;\">PSMS Medan</a>, <a style=\"margin:0px;padding:0px;border:0px;font-style:inherit;font-variant:inherit;font-weight:700;font-size:inherit;line-height:inherit;vertical-align:baseline;color:rgb(206,73,14);background-position:0px 0px;background-size:initial;text-decoration:none;\">Mulyadi Simatupang</a> mengatakan <a style=\"margin:0px;padding:0px;border:0px;font-style:inherit;font-variant:inherit;font-weight:700;font-size:inherit;line-height:inherit;vertical-align:baseline;color:rgb(206,73,14);background-position:0px 0px;background-size:initial;text-decoration:none;\">Liga 2</a> musim 2023/2024 idealnya dimulai dua minggu setelah kick-off musim baru Liga 1, bukan pada November 2023 seperti dibahas dalam Sarasehan Sepak Bola pada Maret 2023.</p><p style=\"margin-right:0px;margin-bottom:10px;margin-left:0px;padding:0px 0px 10px;border:0px;font-size:15px;line-height:24px;font-family:Arial, sans-serif;vertical-align:baseline;letter-spacing:0.5px;color:rgb(51,51,51);\">\"November itu terlalu lama. Kalau dimulai pada bulan itu, berapa lagi dana operasional yang harus kami keluarkan,\" ujar Mulyadi seperti dimuat Antara.</p><div style=\"margin:0px;padding:0px;border:0px;font-size:15px;line-height:inherit;font-family:Arial, sans-serif;vertical-align:baseline;color:rgb(51,51,51);letter-spacing:0.5px;\"><center style=\"margin:0px;padding:0px;border:0px;font:inherit;vertical-align:baseline;\"></center></div><p style=\"margin-right:0px;margin-bottom:10px;margin-left:0px;padding:0px 0px 10px;border:0px;font-size:15px;line-height:24px;font-family:Arial, sans-serif;vertical-align:baseline;letter-spacing:0.5px;color:rgb(51,51,51);\">Menurut Mulyadi, November adalah saat yang tidak lazim untuk memulai kompetisi.</p><p style=\"margin-right:0px;margin-bottom:10px;margin-left:0px;padding:0px 0px 10px;border:0px;font-size:15px;line-height:24px;font-family:Arial, sans-serif;vertical-align:baseline;letter-spacing:0.5px;color:rgb(51,51,51);\">Oleh karena itu, jika Liga 1 2023/2024 bergulir mulai awal Juli 2023, dia melanjutkan, maka Liga 2 seharusnya dimulai setidak-tidaknya pada pertengahan bulan yang sama.</p>', '2023-05-24 11:59:00'),
(3, 'admin', 'Penuh Ketegangan, Inilah Rekomendasi Anime Genre Suspense dengan Subtitle Bahasa Indonesia', 'penuh-ketegangan--inilah-rekomendasi-anime-genre-suspense-dengan-subtitle-bahasa-indonesia', 'active', 'article', '1684929694_12a5185d44a5cb09bbe1.jpg', 'Anda bisa menyaksikan anime genre suspense dengan subtitle bahasa Indonesia di situs streaming seperti Netflix, Viu, Iflix, iQIYI, hingga WeTV.', '<h4 style=\"padding:0px;margin-right:0px;margin-bottom:0px;margin-left:0px;font-family:\'Open Sans\', arial, sans-serif;font-size:13px;\"><span style=\"background-color:rgb(255,255,255);font-family:\'Times New Roman\';\">Siswa sekolah menengah Yuuichi Katagiri menghargai teman dekatnya, yang terdiri dari empat teman sekelas.<br /></span><span style=\"background-color:rgb(255,255,255);font-family:\'Times New Roman\';\">Antara lain Yutori Kokorogi, Shiho Sawaragi, Makoto Shibe, dan Tenji Mikasa.<br /></span><span style=\"background-color:rgb(255,255,255);font-family:\'Times New Roman\';\">Namun, saat dana untuk perjalanan sekolah yang akan datang dicuri, Shiho dan Makot, yang ditugaskan untuk mengumpulkan uang, menjauhkan diri dari teman-teman sekelasnya.<br /></span><span style=\"background-color:rgb(255,255,255);font-family:\'Times New Roman\';\">Yuuichi dan teman-temannya lalu ditipu dan pingsan oleh penyerang tak dikenal.<br /></span><span style=\"background-color:rgb(255,255,255);font-family:\'Times New Roman\';\">Mereka terkurung di sebuah ruangan putih dengan tokoh kontroversial Manabu-kun, yang mengungkapkan bahwa salah satu dari lima siswa itu telah mengumpulkan mereka untuk melunasi hutang pribadi sebesar dua puluh juta yen.</span></h4>', '2023-05-24 12:01:34'),
(4, 'admin', 'ANIME TERBAIK', 'anime-terbaik', 'active', 'article', '1684933139_02a10d687c340b38946b.jpg', 'Anime adalah wibu', '<p>Wibu <span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span><span>Wibu </span></p>', '2023-05-24 12:58:59'),
(5, 'admin', 'ASRAMA HAJI', 'asrama-haji', 'active', 'page', '1684933259_0f0c73bbca01584cc67f.jpg', 'HARUS CUMLAUDE!', '<p><span style=\"font-family:arial, sans-serif;font-size:14px;\">GORONTALO (kemenag.go.id) – </span><span style=\"font-weight:bold;font-family:arial, sans-serif;font-size:14px;\">Asrama Haji</span><span style=\"font-family:arial, sans-serif;font-size:14px;\"> Gorontalo saat ini sudah membuka fasilitas untuk bisa dinikmati masyarakat. Mulai dari sarana hote</span><br /></p>', '2023-05-24 13:00:59'),
(6, 'admin', 'HUBUNGI KAMI', 'hubungi-kami', 'active', 'page', '1684933369_ba847b50a9f82faad0b9.png', 'Secara harfiah, kontak adalah bersama-sama menyentuh. Secara fisik, kontak baru terjadi apabila terjadi hubungan badaniah.', '<p><span style=\"font-family:arial, sans-serif;font-size:14px;\">Maksud dari </span><span style=\"font-weight:bold;font-family:arial, sans-serif;font-size:14px;\">kontak</span><span style=\"font-family:arial, sans-serif;font-size:14px;\"> sosial primer dan sekunder adalah </span><span style=\"font-weight:bold;font-family:arial, sans-serif;font-size:14px;\">kontak</span><span style=\"font-family:arial, sans-serif;font-size:14px;\"> sosial yang terjadi secara langsung (tatap muka) serta lewat perantara</span><br /></p>', '2023-05-24 13:02:49');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `asrama`
--
ALTER TABLE `asrama`
  ADD PRIMARY KEY (`asrama_id`),
  ADD KEY `asrama_username_foreign` (`username`);

--
-- Indeks untuk tabel `konfigurasi`
--
ALTER TABLE `konfigurasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `posts_username_foreign` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `asrama`
--
ALTER TABLE `asrama`
  MODIFY `asrama_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `konfigurasi`
--
ALTER TABLE `konfigurasi`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `asrama`
--
ALTER TABLE `asrama`
  ADD CONSTRAINT `asrama_username_foreign` FOREIGN KEY (`username`) REFERENCES `admin` (`username`);

--
-- Ketidakleluasaan untuk tabel `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_username_foreign` FOREIGN KEY (`username`) REFERENCES `admin` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
