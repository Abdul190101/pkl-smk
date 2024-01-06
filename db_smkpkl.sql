-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 07 Agu 2023 pada 10.23
-- Versi Server: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_smkpkl`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_bimbingan`
--

CREATE TABLE `tbl_bimbingan` (
  `kd_bimbingan` int(11) NOT NULL,
  `kd_tempat` int(11) NOT NULL,
  `nip` char(21) NOT NULL,
  `nis_siswa` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `judul` varchar(50) NOT NULL,
  `catatan` text NOT NULL,
  `file` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_bimbingan`
--

INSERT INTO `tbl_bimbingan` (`kd_bimbingan`, `kd_tempat`, `nip`, `nis_siswa`, `tanggal`, `judul`, `catatan`, `file`) VALUES
(4, 7, '0215151554', 123654, '2023-08-04', 'SISFO', 'ok', 'lampiran/bimbingan/UTS_Abdul_Kholik_200510011_Analisis_Strategi_Algoritma_D3_TIF2.pdf'),
(11, 7, '0215151554', 123654, '2023-08-04', 'SISFO', 'okkkkkkkkkk', 'lampiran/bimbingan_siswa/Bukti_Pendaftaran_Abdul_kholik_Junior_Network_Administrator.pdf'),
(12, 7, '0215151554', 123654, '2023-08-05', 'SISFO', 'okkkknchecygycg', 'lampiran/bimbingan_siswa/Silabus_Junior_Network_Administrator.pdf');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_industri`
--

CREATE TABLE `tbl_industri` (
  `kd_industri` int(11) NOT NULL,
  `nama_industri` varchar(50) NOT NULL,
  `bidang_kerja` varchar(50) NOT NULL,
  `deskripsi` text NOT NULL,
  `alamat_industri` text NOT NULL,
  `wilayah` varchar(50) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `kuota` int(20) NOT NULL,
  `foto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_industri`
--

INSERT INTO `tbl_industri` (`kd_industri`, `nama_industri`, `bidang_kerja`, `deskripsi`, `alamat_industri`, `wilayah`, `telepon`, `kuota`, `foto`) VALUES
(2, 'Astra 2000', 'Otomotif', 'Bengkel Mobil', 'Karawang', 'Karawang, Jawa Barat', '45574865522', 5, 'astra-new.png'),
(3, 'Epson', 'Teknologi', 'Perusahan yang sudah mendunia', 'Majalengka', 'Majalengka, Jawa Barat', '78784545465464', 2, 'epson-new.png'),
(4, 'BUMN', 'Pemasaran, Teknologi &amp; Informasi', 'Perusahan dibawah naungan Pemerintah', 'Jakarta', 'Jakarta Pusat', '12121554', 5, 'bumn-new.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_jurusan`
--

CREATE TABLE `tbl_jurusan` (
  `kd_jurusan` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_jurusan`
--

INSERT INTO `tbl_jurusan` (`kd_jurusan`, `nama`) VALUES
(2, 'TKRO'),
(3, 'TKJ'),
(4, 'AKL');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_kelas`
--

CREATE TABLE `tbl_kelas` (
  `kd_kelas` int(11) NOT NULL,
  `kd_jurusan` int(11) NOT NULL,
  `nama` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_kelas`
--

INSERT INTO `tbl_kelas` (`kd_kelas`, `kd_jurusan`, `nama`) VALUES
(2, 3, 'XII-B'),
(3, 2, 'XII-C'),
(4, 4, 'XII-A');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_laporan`
--

CREATE TABLE `tbl_laporan` (
  `kd_laporan` int(11) NOT NULL,
  `nis_siswa` int(11) NOT NULL,
  `kd_tempat` int(11) NOT NULL,
  `kd_industri` int(11) NOT NULL,
  `judul` text NOT NULL,
  `file` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_laporan`
--

INSERT INTO `tbl_laporan` (`kd_laporan`, `nis_siswa`, `kd_tempat`, `kd_industri`, `judul`, `file`) VALUES
(3, 123654, 7, 2, 'SISFO', 'lampiran/laporan_siswa/457-Article_Text-956-1-10-202209011.pdf'),
(4, 123654, 7, 2, 'SISFO', 'lampiran/laporan_siswa/UTS_Abdul_Kholik_200510011_Analisis_Strategi_Algoritma_D3_TIF.pdf');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_monitoring`
--

CREATE TABLE `tbl_monitoring` (
  `kd_monitoring` int(11) NOT NULL,
  `kd_tempat` int(11) NOT NULL,
  `nis_siswa` int(11) NOT NULL,
  `kd_industri` int(11) NOT NULL,
  `kegiatan` varchar(50) NOT NULL,
  `foto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_monitoring`
--

INSERT INTO `tbl_monitoring` (`kd_monitoring`, `kd_tempat`, `nis_siswa`, `kd_industri`, `kegiatan`, `foto`) VALUES
(17, 7, 123654, 2, 'Memasang Monitor', 'ee76c51d-7b3f-41df-8f12-7170432670b5.jpg'),
(18, 7, 123654, 2, 'melakukan cek CPU', 'hasil_tutorial.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_nilai`
--

CREATE TABLE `tbl_nilai` (
  `kd_nilai` int(11) NOT NULL,
  `kd_tempat` int(11) NOT NULL,
  `keterangan` enum('lulus','tidak-lulus','','') NOT NULL,
  `nilai` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_nilai`
--

INSERT INTO `tbl_nilai` (`kd_nilai`, `kd_tempat`, `keterangan`, `nilai`) VALUES
(1, 6, 'lulus', 90),
(2, 7, 'lulus', 85);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_pembimbing`
--

CREATE TABLE `tbl_pembimbing` (
  `kd_pembimbing` int(11) NOT NULL,
  `kd_jurusan` char(5) NOT NULL,
  `username` varchar(32) NOT NULL,
  `password` text NOT NULL,
  `nip` char(21) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `wilayah` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_pembimbing`
--

INSERT INTO `tbl_pembimbing` (`kd_pembimbing`, `kd_jurusan`, `username`, `password`, `nip`, `nama_lengkap`, `wilayah`) VALUES
(8, '4', 'abdul123', '428a78b4fee47253898d7918c0a09160', '0215151554', 'Abdul kholik', 'Majalengka'),
(9, '2', 'amad', '8563b29d53ea5bf15057f27d7cccedd5', '1515448', 'amad12', 'Majalengka, Jawa Barat');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_sidang`
--

CREATE TABLE `tbl_sidang` (
  `kd_sidang` int(11) NOT NULL,
  `nis_siswa` int(11) NOT NULL,
  `kd_tempat` int(11) NOT NULL,
  `kd_industri` int(11) NOT NULL,
  `judul` text NOT NULL,
  `file` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_sidang`
--

INSERT INTO `tbl_sidang` (`kd_sidang`, `nis_siswa`, `kd_tempat`, `kd_industri`, `judul`, `file`) VALUES
(14, 123654, 7, 2, 'SISFO', 'lampiran/sidang_siswa/Silabus_Junior_Network_Administrator1.pdf');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_siswa`
--

CREATE TABLE `tbl_siswa` (
  `nis_siswa` int(11) NOT NULL,
  `kd_kelas` int(11) NOT NULL,
  `nama_lengkap` varchar(500) NOT NULL,
  `telp` varchar(14) NOT NULL,
  `foto` text NOT NULL,
  `password` text NOT NULL,
  `kd_pembimbing` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_siswa`
--

INSERT INTO `tbl_siswa` (`nis_siswa`, `kd_kelas`, `nama_lengkap`, `telp`, `foto`, `password`, `kd_pembimbing`) VALUES
(123654, 4, 'Abdul Ajis', '02165489485132', 'user-siswa7.png', '733d7be2196ff70efaf6913fc8bdcabf', 8),
(456987, 2, 'amimah', '02165489485132', 'user-siswa14.png', 'd07907595ade6c5751d6e340dccbc7ac', 9);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_tempat`
--

CREATE TABLE `tbl_tempat` (
  `kd_tempat` int(11) NOT NULL,
  `nis_siswa` int(11) NOT NULL,
  `kd_pembimbing` int(11) NOT NULL,
  `kd_industri` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `wilayah` varchar(50) NOT NULL,
  `tahun` year(4) NOT NULL,
  `status` enum('-','proses','ditolak','diterima') NOT NULL,
  `surat` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_tempat`
--

INSERT INTO `tbl_tempat` (`kd_tempat`, `nis_siswa`, `kd_pembimbing`, `kd_industri`, `tanggal`, `wilayah`, `tahun`, `status`, `surat`) VALUES
(7, 123654, 8, 2, '2023-08-03', 'Majalengka, Jawa Barat', 2023, 'diterima', 'UTS_Abdul_Kholik_200510011_Analisis_Strategi_Algoritma_D3_TIF28.pdf');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_tolak_tempat`
--

CREATE TABLE `tbl_tolak_tempat` (
  `kd_tolak` int(11) NOT NULL,
  `kd_tempat` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `alasan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `nama_lengkap` varchar(50) NOT NULL,
  `identitas` varchar(32) NOT NULL,
  `password` text NOT NULL,
  `status` varchar(11) NOT NULL,
  `foto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tbl_user`
--

INSERT INTO `tbl_user` (`id_user`, `username`, `nama_lengkap`, `identitas`, `password`, `status`, `foto`) VALUES
(1, 'admin', 'Panitia PKL', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'admin', 'logo-new.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_bimbingan`
--
ALTER TABLE `tbl_bimbingan`
  ADD PRIMARY KEY (`kd_bimbingan`);

--
-- Indexes for table `tbl_industri`
--
ALTER TABLE `tbl_industri`
  ADD PRIMARY KEY (`kd_industri`);

--
-- Indexes for table `tbl_jurusan`
--
ALTER TABLE `tbl_jurusan`
  ADD PRIMARY KEY (`kd_jurusan`);

--
-- Indexes for table `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  ADD PRIMARY KEY (`kd_kelas`);

--
-- Indexes for table `tbl_laporan`
--
ALTER TABLE `tbl_laporan`
  ADD PRIMARY KEY (`kd_laporan`);

--
-- Indexes for table `tbl_monitoring`
--
ALTER TABLE `tbl_monitoring`
  ADD PRIMARY KEY (`kd_monitoring`);

--
-- Indexes for table `tbl_nilai`
--
ALTER TABLE `tbl_nilai`
  ADD PRIMARY KEY (`kd_nilai`);

--
-- Indexes for table `tbl_pembimbing`
--
ALTER TABLE `tbl_pembimbing`
  ADD PRIMARY KEY (`kd_pembimbing`);

--
-- Indexes for table `tbl_sidang`
--
ALTER TABLE `tbl_sidang`
  ADD PRIMARY KEY (`kd_sidang`);

--
-- Indexes for table `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  ADD PRIMARY KEY (`nis_siswa`);

--
-- Indexes for table `tbl_tempat`
--
ALTER TABLE `tbl_tempat`
  ADD PRIMARY KEY (`kd_tempat`);

--
-- Indexes for table `tbl_tolak_tempat`
--
ALTER TABLE `tbl_tolak_tempat`
  ADD PRIMARY KEY (`kd_tolak`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_bimbingan`
--
ALTER TABLE `tbl_bimbingan`
  MODIFY `kd_bimbingan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `tbl_industri`
--
ALTER TABLE `tbl_industri`
  MODIFY `kd_industri` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tbl_jurusan`
--
ALTER TABLE `tbl_jurusan`
  MODIFY `kd_jurusan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tbl_kelas`
--
ALTER TABLE `tbl_kelas`
  MODIFY `kd_kelas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tbl_laporan`
--
ALTER TABLE `tbl_laporan`
  MODIFY `kd_laporan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tbl_monitoring`
--
ALTER TABLE `tbl_monitoring`
  MODIFY `kd_monitoring` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `tbl_nilai`
--
ALTER TABLE `tbl_nilai`
  MODIFY `kd_nilai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbl_pembimbing`
--
ALTER TABLE `tbl_pembimbing`
  MODIFY `kd_pembimbing` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `tbl_sidang`
--
ALTER TABLE `tbl_sidang`
  MODIFY `kd_sidang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `tbl_siswa`
--
ALTER TABLE `tbl_siswa`
  MODIFY `nis_siswa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=456988;
--
-- AUTO_INCREMENT for table `tbl_tempat`
--
ALTER TABLE `tbl_tempat`
  MODIFY `kd_tempat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `tbl_tolak_tempat`
--
ALTER TABLE `tbl_tolak_tempat`
  MODIFY `kd_tolak` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
