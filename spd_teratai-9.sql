-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 23, 2020 at 01:31 PM
-- Server version: 10.0.38-MariaDB-0ubuntu0.16.04.1
-- PHP Version: 7.0.33-0ubuntu0.16.04.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spd_teratai`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_bank_account`
--

CREATE TABLE `tb_bank_account` (
  `id_bank_account` int(11) NOT NULL,
  `nama_bank` varchar(200) NOT NULL,
  `nomor_rekening` varchar(200) NOT NULL,
  `nama_pemilik_rekening` varchar(200) NOT NULL,
  `status_bank_account` int(11) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_bank_account`
--

INSERT INTO `tb_bank_account` (`id_bank_account`, `nama_bank`, `nomor_rekening`, `nama_pemilik_rekening`, `status_bank_account`, `created`, `updated`, `deleted`) VALUES
(3, 'BRI', '0141-01-000491-56-0', 'CV TOKO TERATAI KONTRAKTOR', 0, '2020-04-08 00:27:33', '2020-04-08 00:27:33', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_customer`
--

CREATE TABLE `tb_customer` (
  `id_customer` int(11) NOT NULL,
  `nama_customer` varchar(200) NOT NULL,
  `npwp_customer` varchar(200) NOT NULL,
  `perusahaan_customer` varchar(200) NOT NULL,
  `jenis_identitas_customer` varchar(200) NOT NULL,
  `no_identitas_customer` varchar(150) NOT NULL,
  `tempat_lahir_customer` varchar(150) NOT NULL,
  `tanggal_lahir_customer` date NOT NULL,
  `alamat_customer` text NOT NULL,
  `koordinat_customer` varchar(200) NOT NULL,
  `no_telp_customer` varchar(200) NOT NULL,
  `email_customer` varchar(200) NOT NULL,
  `pic1` varchar(200) NOT NULL,
  `pic1_phone` varchar(200) NOT NULL,
  `pic2` varchar(200) NOT NULL,
  `pic2_phone` varchar(200) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_diskon`
--

CREATE TABLE `tb_diskon` (
  `id_diskon` int(11) NOT NULL,
  `jenis_diskon` varchar(200) NOT NULL,
  `nama_diskon` varchar(200) NOT NULL,
  `nominal_diskon` varchar(200) NOT NULL,
  `status_diskon` int(11) NOT NULL DEFAULT '0' COMMENT '0 = aktif, 1 = tidak aktif',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_finance`
--

CREATE TABLE `tb_finance` (
  `id_finance` int(11) NOT NULL,
  `id_kategori_finance` int(11) NOT NULL,
  `no_ref` varchar(200) NOT NULL,
  `tanggal_finance` datetime NOT NULL,
  `nominal` float NOT NULL,
  `deskripsi` varchar(200) NOT NULL,
  `keterangan` text NOT NULL,
  `jenis_transaksi_bank` int(11) NOT NULL,
  `id_bank_account` int(11) NOT NULL,
  `bank_verification_status` int(11) NOT NULL,
  `jenis_transfer` enum('Internal','External') NOT NULL,
  `id_bank_account_tujuan` int(11) NOT NULL,
  `nama_bank` varchar(200) NOT NULL,
  `no_rekening_bank` varchar(200) NOT NULL,
  `nama_pemilik_rekening` varchar(200) NOT NULL,
  `creator` int(11) NOT NULL,
  `approved_1` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_gudang`
--

CREATE TABLE `tb_gudang` (
  `id_gudang` int(11) NOT NULL,
  `nama_gudang` varchar(200) NOT NULL,
  `lokasi_gudang` varchar(200) NOT NULL,
  `jenis_gudang` varchar(200) NOT NULL,
  `kode_gudang` varchar(200) NOT NULL,
  `status_gudang` int(11) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_history_action`
--

CREATE TABLE `tb_history_action` (
  `id_history_action` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `tanggal` datetime NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `flag` varchar(200) NOT NULL,
  `id` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_history_stok`
--

CREATE TABLE `tb_history_stok` (
  `id_history` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `mod_stok` double NOT NULL,
  `tanggal` datetime NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `flag` varchar(200) NOT NULL,
  `id` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_item`
--

CREATE TABLE `tb_item` (
  `id_item` int(11) NOT NULL,
  `sku_item` varchar(200) NOT NULL,
  `nama_item` varchar(200) NOT NULL,
  `harga_modal` float NOT NULL,
  `harga_jual` float NOT NULL,
  `satuan` varchar(100) NOT NULL,
  `id_item_kategori` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_item`
--

INSERT INTO `tb_item` (`id_item`, `sku_item`, `nama_item`, `harga_modal`, `harga_jual`, `satuan`, `id_item_kategori`, `created`, `updated`, `deleted`) VALUES
(12, 'BS000000001', 'Besi Holo 20x40', 15500, 25000, 'Btg', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(13, 'BS000000002', 'Besi Holo 40x40', 20000, 40000, 'Btg', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(14, 'BS000000003', 'Besi Reng 0.40', 29500, 37500, 'Btg', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(15, 'BS000000004', 'Paku 7 cm (3"x10)', 9500, 20000, 'Kg', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(16, 'BS000000005', 'Paku 10 cm (4"x8)', 9500, 20000, 'Kg', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(17, 'BS000000006', 'SNI Besi Beton Polos 6 mm x 12 meter ', 22000, 36000, 'Lonjor', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(18, 'BS000000007', 'SNI Besi Beton Polos 8 mm x 12 meter ', 34000, 60000, 'Lonjor', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(19, 'BS000000008', 'SNI Besi Beton Polos 10 mm x 12 meter ', 55500, 80000, 'Lonjor', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(20, 'BS000000009', 'SNI Besi Beton Polos 12 mm x 12 meter ', 79000, 110000, 'Lonjor', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(21, 'BS000000010', 'SNI Besi Beton Ulir 12 mm x 12 meter', 81500, 125000, 'Lonjor', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(22, 'BS000000011', 'SNI Besi Beton Ulir 13 mm x 12 meter', 88750, 130000, 'Lonjor', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(23, 'BS000000012', 'SNI Besi Beton Ulir 16 mm x 12 meter', 148500, 280000, 'Lonjor', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(24, 'BS000000013', 'SNI Besi Beton Polos 16 mm x 12 meter ', 138800, 270000, 'Lonjor', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(25, 'BS000000014', 'SNI BESI Beton Polos 19 mm x 12 meter', 206100, 290000, 'Lonjor', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(26, 'BS000000015', 'SNI BESI Beton Polos 22 mm x 12 meter', 278950, 370000, 'Lonjor', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(27, 'BS000000016', 'SNI BESI Beton Polos 25 mm x 12 meter', 365000, 547500, 'Lonjor', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(28, 'BS000000017', 'Banci SNI BESI Beton 6 mm x 12 meter', 19200, 28800, 'Lonjor', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(29, 'BS000000018', 'Banci SNI BESI Beton 8 mm x 12 meter', 31300, 46950, 'Lonjor', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(30, 'BS000000019', 'Banci SNI BESI Beton 10 mm x 12 meter', 49400, 74100, 'Lonjor', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(31, 'BS000000020', 'Banci SNI BESI Beton 12 mm x 12 meter', 72150, 108225, 'Lonjor', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(32, 'BS000000021', 'Kawat Ikat/ beton / bendrat', 13500, 25000, 'Kg', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(33, 'BS000000022', 'Kawat Duri', 50000, 75000, 'Roll', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(34, 'BS000000023', 'Seng Warna', 0, 55000, 'Lbr', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(35, 'BS000000024', 'Seng Biasa', 0, 50000, 'Lbr', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(36, 'BS000000025', 'Pelat Besi 6x4x8', 1325000, 2000000, 'Lbr', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(37, 'BS000000026', 'Angker D3/4x40', 45000, 67500, 'Bh', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(38, 'BS000000027', 'Pipa Gas 8" 40x65', 3975000, 6000000, 'Btg', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(39, 'BS000000028', 'Elbow 8" 50x40', 650000, 950000, 'Bh', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(40, 'BS000000029', 'Sekrup Reileigh 25', 155000, 240000, 'Dos', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(41, 'BS000000030', 'Sekrup Reileigh 50', 200000, 300000, 'Dos', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(42, 'BS000000031', 'Siku Lubang 40x60x3', 112500, 165000, 'Btg', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(43, 'BS000000032', 'Siku Lubang 40x40x3', 66000, 100000, 'Btg', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(44, 'BS000000033', 'Siku Lubang 37x37x3', 66000, 100000, 'Btg', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(45, 'BS000000034', 'Pipa GIP ? 3/4" - 6 meter', 165000, 247500, 'Btg', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(46, 'BS000000035', 'Pipa GIP ? 2" - 6 meter', 480000, 720000, 'Btg', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(47, 'BS000000036', 'Pipa GIP ? 1/2 " - 6 meter', 115000, 172500, 'Btg', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(48, 'BS000000037', 'Pipa GIP ? 1 " - 6 meter', 235000, 352500, 'Btg', 1, '2020-07-19 06:58:03', '2020-07-19 06:58:54', 0),
(49, 'BS000000038', 'Pipa GIP ? 11/2 " - 6 meter', 378000, 567000, 'Btg', 1, '2020-07-19 06:58:04', '2020-07-19 06:58:54', 0),
(50, 'BS000000039', 'Alumunium Jeruk', 78000, 120000, 'Btg', 1, '2020-07-19 06:58:04', '2020-07-19 06:58:54', 0),
(51, 'BS000000040', 'Baja Profil C truss 75/0.75', 67500, 105000, 'Btg', 1, '2020-07-19 06:58:04', '2020-07-19 06:58:54', 0),
(52, 'BS000000041', 'Reng Baja Ringan Profil V 25/0.45', 26000, 45000, 'Btg', 1, '2020-07-19 06:58:04', '2020-07-19 06:58:54', 0),
(53, 'BS000000042', 'Pipa Stainless 1 1/2" tebal 0.8 mm', 148500, 248500, 'Btg', 1, '2020-07-19 06:58:04', '2020-07-19 06:58:54', 0),
(54, 'BS000000043', 'Pipa Stainless 1" tebal 0,8 mm', 98500, 150000, 'Btg', 1, '2020-07-19 06:58:04', '2020-07-19 06:58:54', 0),
(55, 'BS000000044', 'Pipa Stainless 1/2" tebal 0,8 mm', 55500, 95500, 'Btg', 1, '2020-07-19 06:58:04', '2020-07-19 06:58:54', 0),
(56, 'BS000000045', 'Spandek 750 x 0.3', 31500, 48500, 'm', 1, '2020-07-19 06:58:04', '2020-07-19 06:58:54', 0),
(57, 'BS000000046', 'Beugel U Plat', 25000, 37500, 'buah', 1, '2020-07-19 06:58:04', '2020-07-19 06:58:54', 0),
(58, 'BS000000047', 'Beugel U Bulat', 28000, 42000, 'buah', 1, '2020-07-19 06:58:04', '2020-07-19 06:58:54', 0),
(59, 'BS000000048', 'Besi Siku L.5', 150000, 225000, 'batang', 1, '2020-07-19 06:58:04', '2020-07-19 06:58:54', 0),
(60, 'BS000000049', 'Baja IWF', 10700, 16050, 'kg', 1, '2020-07-19 06:58:04', '2020-07-19 06:58:54', 0),
(61, 'BS000000050', 'Paku Seng. Lurik (RRT)', 20000, 30000, 'kg', 1, '2020-07-19 06:58:04', '2020-07-19 06:58:54', 0),
(62, 'BS000000051', 'Wiremesh MB (2.1 x 5.4 m)', 515000, 772500, 'lembar', 1, '2020-07-19 06:58:04', '2020-07-19 06:58:54', 0),
(63, 'KY000000001', 'Tripleks 3 mm', 32000, 50000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(64, 'KY000000002', 'Tripleks 4 mm (3.6 mm)', 52000, 65000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(65, 'KY000000003', 'Tripleks 6 mm', 65000, 90000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(66, 'KY000000004', 'Tripleks 9 mm (4.8 mm)', 84000, 135000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(67, 'KY000000005', 'Tripleks 12 mm (8.5 mm)', 93000, 155000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(68, 'KY000000006', 'Tripleks 18 mm', 161000, 220000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(69, 'KY000000007', 'Tripleks 30 mm', 665000, 1000000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(70, 'KY000000008', 'Tripleks Melamine Putih', 75000, 120000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(71, 'KY000000009', 'Tripleks 3 mm (2,7mm) - Sengon', 30500, 50000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(72, 'KY000000010', 'Tripleks 4 mm (3.6mm) - Sengon', 41000, 65000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(73, 'KY000000011', 'Tripleks 5 mm (4.8mm) - Sengon', 50500, 80000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(74, 'KY000000012', 'Tripleks 8 mm (7.5mm) - Sengon', 66500, 100000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(75, 'KY000000013', 'Tripleks 9 mm (8.5mm) - Sengon', 78500, 135000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(76, 'KY000000014', 'Tripleks 3 mm (2,7mm) - Semi Meranti', 38500, 58000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(77, 'KY000000015', 'Tripleks 4 mm (3.6mm) - Semi Meranti', 48500, 75000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(78, 'KY000000016', 'Tripleks 8 mm (7.5mm) - Semi Meranti', 69000, 110000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(79, 'KY000000017', 'Tripleks 9 mm (8.5mm) - Semi Meranti', 81500, 140000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(80, 'KY000000018', 'Tripleks 12 mm - Semi Meranti', 110000, 165000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(81, 'KY000000019', 'Tripleks 15 mm - Semi Meranti', 137000, 210000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(82, 'KY000000020', 'Tripleks 18 mm - Semi Meranti', 161000, 250000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(83, 'KY000000021', 'Tripleks Melamine Cor - Papan Tulis', 78000, 120000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(84, 'KY000000022', 'Tripleks Melamine PVC', 71000, 110000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(85, 'KY000000023', 'Taekwood Besar', 70000, 100000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(86, 'KY000000024', 'Taekwood Kecil', 61000, 90000, 'Lbr', 2, '2020-07-19 07:01:24', '2020-07-19 07:19:01', 0),
(87, 'SP000000001', 'Pipa PVC ?4" D - Fuiji', 79200, 120000, 'Btg', 3, '2020-07-19 07:20:44', '2020-07-19 07:21:12', 0),
(88, 'SP000000002', 'Elbow PVC 90 (Power) ?4" D - Fuiji', 22825, 37500, 'Buah', 3, '2020-07-19 07:20:44', '2020-07-19 07:21:12', 0),
(89, 'SP000000003', 'Kloset duduk + tutupan (INA)', 500000, 750000, 'Unit', 3, '2020-07-19 07:20:44', '2020-07-19 07:21:12', 0),
(90, 'SP000000004', 'Kloset Jongkok (INA)', 145000, 217500, 'Unit', 3, '2020-07-19 07:20:44', '2020-07-19 07:21:12', 0),
(91, 'SP000000005', 'Wastafel Gantung Keramik (Kosongan)', 350000, 525000, 'Unit', 3, '2020-07-19 07:20:44', '2020-07-19 07:21:12', 0),
(92, 'SP000000006', 'Wastafel Gantung Keramik (Termasuk Alat)', 450000, 675000, 'Unit', 3, '2020-07-19 07:20:44', '2020-07-19 07:21:12', 0),
(93, 'SP000000007', 'Urinoar Keramik Standar (Kosongan)', 950000, 1425000, 'Unit', 3, '2020-07-19 07:20:44', '2020-07-19 07:21:12', 0),
(94, 'SP000000008', 'Urinoar Keramik Standar (Termasuk Alat)', 1300000, 1950000, 'Unit', 3, '2020-07-19 07:20:44', '2020-07-19 07:21:12', 0),
(95, 'SP000000009', 'Bak Mandi Fiber Glass 70x70x66 (Kotak)', 250000, 375000, 'Unit', 3, '2020-07-19 07:20:44', '2020-07-19 07:21:12', 0),
(96, 'SP000000010', 'Bak Mandi Fiber Glass 70x70x66 (Oval)', 350000, 525000, 'Unit', 3, '2020-07-19 07:20:44', '2020-07-19 07:21:12', 0),
(97, 'SP000000011', 'Pipa PVC ?1/2" AW - Trillium Moff - 4 meter', 17190, 25785, 'Lonjor', 3, '2020-07-19 07:20:44', '2020-07-19 07:21:12', 0),
(98, 'SP000000012', 'Pipa PVC ?3/4" AW - Trillium Moff - 4 meter', 21120, 31680, 'Lonjor', 3, '2020-07-19 07:20:44', '2020-07-19 07:21:12', 0),
(99, 'SP000000013', 'Pipa PVC ?1" AW - Trillium Moff - 4 meter', 27675, 41512.5, 'Lonjor', 3, '2020-07-19 07:20:44', '2020-07-19 07:21:12', 0),
(100, 'SP000000014', 'Pipa PVC ?2" AW - Trillium Moff - 4 meter', 70850, 106275, 'Lonjor', 3, '2020-07-19 07:20:44', '2020-07-19 07:21:12', 0),
(101, 'SP000000015', 'Pipa PVC ?3" AW - Trillium Moff - 4 meter', 129830, 194745, 'Lonjor', 3, '2020-07-19 07:20:44', '2020-07-19 07:21:12', 0),
(102, 'SP000000016', 'Pipa PVC ?4" AW - Trillium Moff - 4 meter', 200175, 300262, 'Lonjor', 3, '2020-07-19 07:20:44', '2020-07-19 07:21:12', 0),
(103, 'SP000000017', 'Stop Kran Onda ?1/2"', 60000, 90000, 'Unit', 3, '2020-07-19 07:20:44', '2020-07-19 07:21:12', 0),
(104, 'SP000000018', 'Kran Kuningan ?1/2" Onda', 21000, 31500, 'Unit', 3, '2020-07-19 07:20:44', '2020-07-19 07:21:12', 0),
(105, 'SP000000019', 'Kran Kuningan ?3/4" Onda', 46000, 69000, 'Unit', 3, '2020-07-19 07:20:44', '2020-07-19 07:21:12', 0),
(106, 'SP000000020', 'Kloset Jongkok (Chelsea)', 80000, 120000, 'Unit', 3, '2020-07-19 07:20:44', '2020-07-19 07:21:12', 0),
(224, 'LE000000001', 'Stop Kontak Lantai / Tanam', 211860, 300000, 'Unit', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(225, 'LE000000002', 'Bulb LED OPPLE 20 Watt', 57200, 87000, 'Bh', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(226, 'LE000000003', 'T8 LED OPPLE 18 Watt', 57000, 86000, 'Bh', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(227, 'LE000000004', 'Balak LED 120 Cm', 22500, 35000, 'Bh', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(228, 'LE000000005', 'Lampu SL 18 Watt Philips', 32000, 48000, '', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(229, 'LE000000006', 'Lampu SL 55 Watt Philips', 58450, 87675, '', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(230, 'LE000000007', 'Lampu Bambu 36 Watt Philips', 205000, 307500, '', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(231, 'LE000000008', 'Lampu RM 2 x 4 Watt', 325000, 487500, 'set', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(232, 'LE000000009', 'Lampu Downlight 18 Watt 4"', 27500, 41250, '', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(233, 'LE000000010', 'Lampu Downlight 18 Watt 5"', 30000, 45000, '', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(234, 'LE000000011', 'Kabel NYY 3x4 mm', 20658, 30987, 'meter', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(235, 'LE000000012', 'Kabel Telepon 2 Pair 4C Supreme', 2400, 3600, 'meter', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(236, 'LE000000013', 'Kabel CCTV Coaxial RG5', 3833.33, 5750, 'meter', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(237, 'LE000000014', 'Kabel CCTV Coaxial RG6', 3833.33, 5750, 'meter', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(238, 'LE000000015', 'Eterna NYM 3x2.5', 10680, 16020, 'Meter', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(239, 'LE000000016', 'Eterna NYM 2x1.5', 5838.5, 8757.75, 'Meter', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(240, 'LE000000017', 'Eterna NYY 2x1.5', 8259.5, 12389.2, 'Meter', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(241, 'LE000000018', 'Kawat BC 10mm', 11000, 16500, 'meter', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(242, 'LE000000019', 'Kawat BC 50 mm', 55000, 82500, 'meter', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(243, 'LE000000020', 'Saklar tunggal Broco', 9875, 14812.5, 'buah', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(244, 'LE000000021', 'Saklar Ganda Broco', 13425, 20137.5, 'Buah', 5, '2020-07-19 07:26:29', '2020-07-19 07:27:01', 0),
(245, 'CF000000001', 'Cat Tembok Matex 1 kg', 27500, 41250, 'Kaleng', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(246, 'CF000000002', 'Cat Tembok Matex 4.5 kg', 83000, 124500, 'Kaleng', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(247, 'CF000000003', 'Cat Tembok Matex 20 Kg', 340000, 510000, 'Kaleng', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(248, 'CF000000004', 'Cat Emco 1 kg', 72000, 108000, 'Kaleng', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(249, 'CF000000005', 'Cat Bidang outdoor (Weather Proof) 20 lt', 1345000, 2017500, 'Ember', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(250, 'CF000000006', 'Cat Avian 1 kg (0.9 kg)', 56150, 84225, 'Kaleng', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(251, 'CF000000007', 'Cat Kayu Glotex', 59000, 88500, 'Kaleng', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(252, 'CF000000008', 'Cat Kayu Mihatex', 59000, 88500, 'Kaleng', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(253, 'CF000000009', 'Meni Kayu Altex 1 kg', 34000, 51000, 'Kaleng', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(254, 'CF000000010', 'Kuas Cat 2"', 7000, 10500, 'Buah', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(255, 'CF000000011', 'Kuas Cat 3"', 11000, 16500, 'Buah', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(256, 'CF000000012', 'Kuas Cat 4"', 17500, 26250, 'Buah', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(257, 'CF000000013', 'Kuas Rol', 27500, 41250, 'Buah', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(258, 'CF000000014', 'Kertas Amplas', 3500, 5250, 'Lembar', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(259, 'CF000000015', 'Thiner B 1 Kg', 23000, 34500, 'Kaleng', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(260, 'CF000000016', 'Thiner Special A 1 kg', 33500, 50250, 'Kaleng', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(261, 'CF000000017', 'Plamir Tembok Boyo 25 kg', 160000, 240000, 'Kaleng', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(262, 'CF000000018', 'Dempul Kayu cap Pedang 1 kg', 26500, 39750, 'Kaleng', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(263, 'CF000000019', 'Dempul Kayu cap Pedang 5 kg', 120500, 180750, 'Kaleng', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(264, 'CF000000020', 'Lem Kayu Fox 600 gram', 46000, 69000, 'Kaleng', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(265, 'CF000000021', 'Lem Kayu Fox 2.5 kg', 165000, 247500, 'Kaleng', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(266, 'CF000000022', 'Cat Dasar Alkali Nippon 3 in 1 - 2.5 liter', 102500, 153750, 'Kaleng', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(267, 'CF000000023', 'Cat Dasar Alkali Nippon 3 in 1 - 20 liter', 777500, 1166250, 'Kaleng', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(268, 'CF000000024', 'Minyak Cat Tiner B 1 Liter', 25000, 37500, 'Liter', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(269, 'CF000000025', 'Spiritus', 17500, 26250, 'Liter', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(270, 'CF000000026', 'Plamir Kayu cap Pedang', 26500, 39750, 'Kg', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(271, 'CF000000027', 'Plamir Besi cap Pedang', 26500, 39750, 'kg', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(272, 'CF000000028', 'Waterproof Elaster Nippon 1 kg', 37500, 56250, 'Kaleng', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(273, 'CF000000029', 'Waterproof Elaster Nippon 4 kg', 135000, 202500, 'Kaleng', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(274, 'CF000000030', 'Waterproof Elaster Nippon 20 kg', 645000, 967500, 'Kaleng', 6, '2020-07-19 07:31:02', '2020-07-19 07:31:46', 0),
(275, 'AT000000001', 'Gerobak Alpha Premium 3.25 PL', 445125, 650000, 'Unit', 7, '2020-07-19 07:33:31', '2020-07-19 07:33:45', 0),
(276, 'AT000000002', 'Gerobak Alpha Premium 4.80 PL', 499875, 700000, 'Unit', 7, '2020-07-19 07:33:31', '2020-07-19 07:33:45', 0),
(277, 'AT000000003', 'Mesin Molen', 0, 0, '', 7, '2020-07-19 07:33:31', '2020-07-19 07:33:45', 0),
(278, 'AT000000004', 'Digital Measuring Wheel / Digital Walking Measure Wheel MarcDavis', 835850, 1200000, 'Unit', 7, '2020-07-19 07:33:31', '2020-07-19 07:33:45', 0),
(279, 'LL000000001', 'Mouse Logitech Cable', 66000, 110000, 'Unit', 8, '2020-07-19 07:35:08', '2020-07-19 07:35:22', 0),
(280, 'LL000000002', 'TP-LINK TL-WN725N : 150Mbps Wireless Nano USB Adapter / USB Wifi', 89600, 200000, 'Unit', 8, '2020-07-19 07:35:08', '2020-07-19 07:35:22', 0),
(281, 'LL000000003', 'RAMBUNCIS PANOLET 2 ARAH FARR / KUNCI GRENDEL SLOT JENDELA', 24820, 40000, 'Unit', 8, '2020-07-19 07:35:08', '2020-07-19 07:35:22', 0),
(282, 'LL000000004', 'BELOCCA Kunci Pintu Sliding Geser Dorong STAINLESS', 152700, 190000, 'Unit', 8, '2020-07-19 07:35:08', '2020-07-19 07:35:22', 0),
(283, 'LL000000005', 'Topi Kupluk Ninja / Topi Full Face / Topi Ronda 1 Lobang', 14676.7, 25000, 'Bh', 8, '2020-07-19 07:35:08', '2020-07-19 07:35:22', 0),
(284, 'LL000000006', 'Rompi Safety Bahan Dakron Hijau Atau Orange', 98145, 120000, 'Bh', 8, '2020-07-19 07:35:08', '2020-07-19 07:35:22', 0),
(285, 'LL000000007', 'Lem Silikon Serbaguna / Multi Purpose Silicone DEXTONE BENING / CLEAR', 34490, 45000, 'Bh', 8, '2020-07-19 07:35:08', '2020-07-19 07:35:22', 0),
(286, 'LL000000008', 'Rel Pintu Geser J3 / Rel Marathon / Rel Pintu Gantung Panjang 180cm', 245100, 320000, 'Unit', 8, '2020-07-19 07:35:08', '2020-07-19 07:35:22', 0),
(287, 'LL000000009', 'RAMBUNCIS JENDELA BAGUS-GRENDEL PINTU JENDELA-PENGAIT PINTU JENDELA BA', 24733.3, 40000, 'Unit', 8, '2020-07-19 07:35:08', '2020-07-19 07:35:22', 0),
(288, 'KR000000001', 'Asia Tiles-WT-Metro-Basic-20x25@20', 52000, 78000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(289, 'KR000000002', 'Asia Tiles-WT-Metro-Dark Red-20x25@20', 52000, 78000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(290, 'KR000000003', 'Asia Tiles-WT-Metro-Dark Blue-20x25@20', 52000, 78000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(291, 'KR000000004', 'Asia Tiles-WT-Metro-Dark Green-20x25@20', 52000, 78000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(292, 'KR000000005', 'Asia Tiles-WT-Zara-Basic-20x25@20', 46000, 69000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(293, 'KR000000006', 'Asia Tiles-WT-Zara-Red-20x25@20', 46000, 69000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(294, 'KR000000007', 'Asia Tiles-WT-Zara-Blue-20x25@20', 46000, 69000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(295, 'KR000000008', 'Asia Tiles-WT-Zara-Brown-20x25@20', 46000, 69000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(296, 'KR000000009', 'Asia Tiles-WT-Montana-Basic-20x25@20', 52000, 78000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(297, 'KR000000010', 'Asia Tiles-WT-Montana-Dark Red-20x25@20', 52000, 78000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(298, 'KR000000011', 'Asia Tiles-WT-Montana-Dark Blue-20x25@20', 52000, 78000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(299, 'KR000000012', 'Asia Tiles-WT-Montana-Dark Green-20x25@20', 52000, 78000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(300, 'KR000000013', 'Asia Tiles-WT-Montana-Dark Brown-20x25@20', 52000, 78000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(301, 'KR000000014', 'Asia Tiles-WT-Zola-Basic-20x25@20', 46000, 69000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(302, 'KR000000015', 'Asia Tiles-WT-Zola-Red-20x25@20', 46000, 69000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(303, 'KR000000016', 'Asia Tiles-WT-Zola-Blue-20x25@20', 46000, 69000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(304, 'KR000000017', 'Asia Tiles-WT-Zola-Brown-20x25@20', 46000, 69000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(305, 'KR000000018', 'Asia Tiles-WT-Zola-Grey-20x25@20', 46000, 69000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(306, 'KR000000019', 'Asia Tiles-FT-Zensa-Grey-40x40@6', 44000, 66000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(307, 'KR000000020', 'Asia Tiles-FT-Otto-Cream-40x40@6', 44000, 66000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(308, 'KR000000021', 'Asia Tiles-FT-Zigma-Cream-40x40@6', 44000, 66000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(309, 'KR000000022', 'Asia Tiles-FT-Monaco-Cream-40x40@6', 44000, 66000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(310, 'KR000000023', 'Asia Tiles-FT-Malibu-Green-40x40@6', 44000, 66000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(311, 'KR000000024', 'Asia Tiles-FT-Maldives-Brown-40x40@6', 44000, 66000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(312, 'KR000000025', 'Asia Tiles-FT-Zurich-Grey-40x40@6', 44000, 66000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(313, 'KR000000026', 'Asia Tiles-FT-Zurich-Green-40x40@6', 44000, 66000, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(314, 'KR000000027', 'Asia Tiles-FT-Oscar-Brown-40x40@6', 53000, 79500, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(315, 'KR000000028', 'Asia Tiles-FT-Oscar-Taupe-40x40@6', 53000, 79500, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(316, 'KR000000029', 'Asia Tiles-FT-Oscar-Grey-40x40@6', 53000, 79500, 'Dos', 4, '2020-07-21 08:29:16', '2020-07-21 08:29:39', 0),
(317, 'KR000000030', 'Asia Tiles-FT-Nirwana-Grey-30x30@11', 39000, 58500, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(318, 'KR000000031', 'Asia Tiles-FT-Nirwana-Green-30x30@11', 39000, 58500, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(319, 'KR000000032', 'Asia Tiles-FT-Zurich-Brown-30x30@11', 39000, 58500, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(320, 'KR000000033', 'Asia Tiles-FT-Zensa-Cream-30x30@11', 39000, 58500, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(321, 'KR000000034', 'Asia Tiles-FT-Murano--30x30@11', 38000, 57000, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(322, 'KR000000035', 'Asia Tiles-FT-Corsica--30x30@11', 47000, 70500, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(323, 'KR000000036', 'Asia Tiles-FT-Alpha-White-30x30@11', 47000, 70500, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(324, 'KR000000037', 'Asia Tiles-FT-Alpha-Grey-30x30@11', 47000, 70500, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(325, 'KR000000038', 'Asia Tiles-FT-Oscar-Red-30x30@11', 47000, 70500, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(326, 'KR000000039', 'Asia Tiles-FT-Oscar-Green-30x30@11', 47000, 70500, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(327, 'KR000000040', 'Asia Tiles-FT-Royce-Grey-25x25@16', 51500, 77300, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(328, 'KR000000041', 'Asia Tiles-FT-Regal-Blue-25x25@16', 51500, 77300, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(329, 'KR000000042', 'Asia Tiles-FT-Royal-Green-25x25@16', 51500, 77300, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(330, 'KR000000043', 'Asia Tiles-FT-Royal-Blue-25x25@16', 51500, 77300, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(331, 'KR000000044', 'Asia Tiles-FT-Roma-Grey-25x25@16', 51500, 77300, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(332, 'KR000000045', 'Asia Tiles-FT-Roma-Grey Décor-25x25@16', 51500, 77300, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(333, 'KR000000046', 'Asia Tiles-FT-Roma-Brown-25x25@16', 51500, 77300, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(334, 'KR000000047', 'Asia Tiles-FT-Roma-Brown Décor-25x25@16', 51500, 77300, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(335, 'KR000000048', 'Asia Tiles-FT-Alpha-Green-25x25@16', 51500, 77300, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(336, 'KR000000049', 'Asia Tiles-FT-Alpha-Blue-25x25@16', 51500, 77300, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(337, 'KR000000050', 'Asia Tiles-FT-Roca-Grey-25x25@16', 51500, 77300, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(338, 'KR000000051', 'Asia Tiles-FT-Roca-Bone-25x25@16', 51500, 77300, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(339, 'KR000000052', 'Asia Tiles-FT-Alpha-Green-20x20@25', 48500, 72800, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(340, 'KR000000053', 'Asia Tiles-FT-Alpha-Blue-20x20@25', 48500, 72800, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(341, 'KR000000054', 'Asia Tiles-FT-Roxy-Grey-20x20@25', 48500, 72800, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(342, 'KR000000055', 'Asia Tiles-FT-Topaz-Grey-20x20@25', 48500, 72800, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(343, 'KR000000056', 'Asia Tiles-FT-Topaz-Green-20x20@25', 48500, 72800, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(344, 'KR000000057', 'Asia Tiles-FT-Welco-Red-20x20@25', 48500, 72800, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(345, 'KR000000058', 'Asia Tiles-FT-Welco-Blue-20x20@25', 48500, 72800, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(346, 'KR000000059', 'Asia Tiles-FT-Welco-Green-20x20@25', 48500, 72800, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0),
(347, 'KR000000060', 'Asia Tiles-FT-Welco-Brown-20x20@25', 48500, 72800, 'Dos', 4, '2020-07-21 08:29:17', '2020-07-21 08:29:39', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_item_kategori`
--

CREATE TABLE `tb_item_kategori` (
  `id_item_kategori` int(11) NOT NULL,
  `nama_item_kategori` varchar(200) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_item_kategori`
--

INSERT INTO `tb_item_kategori` (`id_item_kategori`, `nama_item_kategori`, `created`, `updated`, `deleted`) VALUES
(1, 'Besi', '2020-07-19 06:48:12', '2020-07-19 06:48:12', 0),
(2, 'Kayu', '2020-07-19 06:48:17', '2020-07-19 06:48:17', 0),
(3, 'Sanitasi & PVC', '2020-07-19 06:48:20', '2020-07-19 06:48:20', 0),
(4, 'Keramik', '2020-07-19 06:48:25', '2020-07-19 06:48:25', 0),
(5, 'Listrik & Elektronik Lain', '2020-07-19 06:48:33', '2020-07-19 06:48:33', 0),
(6, 'Cat & Bahan Finishing Lain', '2020-07-19 06:48:39', '2020-07-19 06:48:39', 0),
(7, 'Alat Tukang', '2020-07-19 07:28:53', '2020-07-19 07:28:53', 0),
(8, 'Lain-Lain', '2020-07-19 07:28:58', '2020-07-19 07:28:58', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_item_keluar`
--

CREATE TABLE `tb_item_keluar` (
  `id_item_keluar` int(11) NOT NULL,
  `no_go` varchar(200) NOT NULL,
  `id_gudang` int(11) NOT NULL,
  `creator` int(11) NOT NULL,
  `approved_1` int(11) NOT NULL,
  `approved_2` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_item_keluar_detail`
--

CREATE TABLE `tb_item_keluar_detail` (
  `id_item_keluar_detail` int(11) NOT NULL,
  `id_item_keluar` int(11) NOT NULL,
  `id_penjualan` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `quantity` double NOT NULL,
  `satuan` varchar(200) NOT NULL,
  `catatan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_item_masuk`
--

CREATE TABLE `tb_item_masuk` (
  `id_item_masuk` int(11) NOT NULL,
  `no_gi` varchar(200) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `id_gudang` int(11) NOT NULL,
  `creator` int(11) NOT NULL,
  `approved_1` int(11) NOT NULL,
  `approved_2` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_item_masuk_detail`
--

CREATE TABLE `tb_item_masuk_detail` (
  `id_item_masuk_detail` int(11) NOT NULL,
  `id_item_masuk` int(11) NOT NULL,
  `id_pemesanan` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `quantity` double NOT NULL,
  `satuan` varchar(100) NOT NULL,
  `catatan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_jabatan`
--

CREATE TABLE `tb_jabatan` (
  `id_jabatan` int(11) NOT NULL,
  `nama_jabatan` varchar(100) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_jabatan`
--

INSERT INTO `tb_jabatan` (`id_jabatan`, `nama_jabatan`, `created`, `updated`, `deleted`) VALUES
(1, 'Owner', '2020-02-20 15:51:34', '2020-04-07 07:20:16', 0),
(2, 'Kepala Toko', '2020-02-27 01:18:51', '2020-04-07 07:20:22', 0),
(3, 'Kepala Stok', '2020-02-27 01:18:56', '2020-04-07 07:20:26', 0),
(4, 'Kepala Logistik', '2020-02-27 01:19:00', '2020-04-07 07:20:33', 0),
(5, 'Admin', '2020-02-27 01:19:05', '2020-04-07 07:20:36', 0),
(6, 'Kasir', '2020-02-27 01:19:08', '2020-04-07 07:20:40', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_kategori_finance`
--

CREATE TABLE `tb_kategori_finance` (
  `id_kategori_finance` int(11) NOT NULL,
  `nama_kategori_finance` varchar(200) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_kategori_finance`
--

INSERT INTO `tb_kategori_finance` (`id_kategori_finance`, `nama_kategori_finance`, `created`, `updated`, `deleted`) VALUES
(1, 'Transfer', '2020-03-05 14:19:00', '2020-03-20 01:32:58', 1),
(2, 'Pemasukan', '2020-03-20 01:33:08', '2020-03-20 01:33:08', 0),
(3, 'Pengeluaran', '2020-03-20 01:33:17', '2020-03-20 01:33:24', 0),
(4, 'Setor', '2020-03-22 11:32:41', '2020-03-22 11:32:41', 0),
(5, 'Penarikan', '2020-03-22 11:32:47', '2020-03-22 11:32:47', 0),
(6, 'Transfer', '2020-03-22 11:32:56', '2020-03-22 11:32:56', 0),
(7, 'Piutang', '2020-06-09 12:49:58', '2020-06-09 12:49:58', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_komponen_harga_jual`
--

CREATE TABLE `tb_komponen_harga_jual` (
  `id_komponen_harga_jual` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `harga_modal` float NOT NULL,
  `satuan` varchar(100) NOT NULL,
  `biaya_kirim` float NOT NULL,
  `total_hpp` float NOT NULL,
  `margin` float NOT NULL,
  `harga_jual` float NOT NULL,
  `margin_akhir` float NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_logistik`
--

CREATE TABLE `tb_logistik` (
  `id_logistik` int(11) NOT NULL,
  `jenis_logistik` int(11) NOT NULL COMMENT '0 = internal , 1 = external',
  `nama_perusahaan_logistik` varchar(200) NOT NULL,
  `jenis_kendaraan_logistik` varchar(200) NOT NULL,
  `rute_logistik` varchar(200) NOT NULL,
  `npwp_logistik` varchar(200) NOT NULL,
  `alamat_logistik` text NOT NULL,
  `koordinat_logistik` varchar(200) NOT NULL,
  `no_telp_logistik` varchar(200) NOT NULL,
  `email_logistik` varchar(200) NOT NULL,
  `bank_logistik` varchar(200) NOT NULL,
  `no_rekening_logistik` varchar(200) NOT NULL,
  `nama_pemilik_rekening_logistik` varchar(200) NOT NULL,
  `pic1` varchar(200) NOT NULL,
  `pic1_phone` varchar(200) NOT NULL,
  `pic2` varchar(200) NOT NULL,
  `pic2_phone` varchar(200) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_menu`
--

CREATE TABLE `tb_menu` (
  `id_menu` int(11) NOT NULL,
  `nama_menu` varchar(200) NOT NULL,
  `url_menu` varchar(200) NOT NULL,
  `menu_parent` int(11) NOT NULL DEFAULT '0',
  `icon_menu` varchar(200) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_menu`
--

INSERT INTO `tb_menu` (`id_menu`, `nama_menu`, `url_menu`, `menu_parent`, `icon_menu`, `created`, `updated`, `deleted`) VALUES
(1, 'Data Master', 'javascript:void(0)', 0, 'fa fa-user', '2020-02-20 14:19:42', '2020-02-20 14:24:55', 0),
(2, 'Gudang', 'master/gudang', 1, '', '2020-02-20 14:23:45', '2020-02-20 14:23:45', 0),
(3, 'Bank Account', 'master/bank_account', 1, '', '2020-02-20 14:26:07', '2020-02-20 14:26:07', 0),
(4, 'Diskon', 'master/diskon', 1, '', '2020-02-20 14:26:38', '2020-02-20 14:26:38', 0),
(5, 'Logistik ke Customer', 'master/logistik', 1, '', '2020-02-20 14:26:58', '2020-04-09 10:17:50', 0),
(6, 'Metode Pembayaran', 'master/metode_pembayaran', 1, '', '2020-02-20 14:27:26', '2020-02-20 14:27:31', 0),
(7, 'Customer', 'master/customer', 1, '', '2020-02-20 14:27:47', '2020-02-20 14:27:47', 0),
(8, 'User', 'master/user', 1, '', '2020-02-20 14:27:58', '2020-02-20 14:34:35', 0),
(9, 'Jabatan', 'master/jabatan', 1, '', '2020-02-20 14:28:28', '2020-02-20 15:51:22', 0),
(10, 'Menu', 'master/menu', 17, '', '2020-02-20 14:28:41', '2020-02-20 17:07:57', 0),
(11, 'Supplier', 'master/supplier', 1, '', '2020-02-20 16:06:23', '2020-02-20 16:06:23', 0),
(12, 'Komponen Harga Jual', 'item/komponen_harga_jual', 14, '', '2020-02-20 17:02:28', '2020-05-06 03:52:58', 0),
(13, 'Red Line', 'master/redline', 1, '', '2020-02-20 16:06:53', '2020-02-20 16:06:53', 0),
(14, 'Item', 'javascript:void(0)', 0, 'fa fa-archive', '2020-02-20 16:07:27', '2020-05-06 03:52:42', 0),
(15, 'Transaksi', 'javascript:void(0)', 0, 'fa fa-history', '2020-02-20 16:10:46', '2020-02-20 16:10:58', 0),
(16, 'Item', 'master/item', 14, '', '2020-02-20 16:06:36', '2020-05-06 03:53:00', 0),
(17, 'Pengaturan', 'javascript:void(0)', 0, 'fa fa-cog', '2020-02-20 16:18:57', '2020-02-20 16:18:57', 0),
(18, 'Ganti Password', 'user/change_password', 17, '', '2020-02-20 16:19:33', '2020-02-20 16:19:33', 0),
(19, 'Stok Gudang', 'item/stok_gudang', 14, '', '2020-02-27 05:47:15', '2020-05-06 03:51:51', 0),
(20, 'Mutasi', 'item/mutasi', 14, '', '2020-02-20 16:11:14', '2020-05-06 03:51:52', 0),
(21, 'Finance', 'javascript:void(0)', 0, 'fa fa-dollar', '2020-03-05 14:10:24', '2020-03-05 14:10:24', 0),
(22, 'Kategori Finance', 'master/kategori_finance', 21, '', '2020-03-05 14:11:02', '2020-03-05 14:11:02', 0),
(23, 'Purchase Order', 'po/purchase_order', 15, '', '2020-03-06 01:46:42', '2020-03-10 13:08:02', 0),
(24, 'Goods In', 'gi/goods_in', 15, '', '2020-03-11 01:27:35', '2020-03-11 01:27:35', 0),
(25, 'Sales Order', 'so/sales_order', 15, '', '2020-03-11 12:42:37', '2020-03-11 12:42:37', 0),
(26, 'Goods Out', 'go/goods_out', 15, '', '2020-03-11 18:08:55', '2020-03-11 18:08:55', 0),
(27, 'Cash', 'ci/cash', 21, '', '2020-03-20 01:56:50', '2020-03-20 02:24:28', 0),
(28, 'Setor', 'bank/setor', 21, '', '2020-03-20 08:58:28', '2020-03-22 11:48:16', 0),
(30, 'Penarikan', 'bank/penarikan', 21, '', '2020-03-22 11:48:38', '2020-03-22 11:48:38', 0),
(31, 'Transfer', 'bank/transfer', 21, '', '2020-03-22 11:48:53', '2020-03-22 11:48:53', 0),
(32, 'History Stok', 'item/history_stok', 14, '', '2020-04-07 07:44:08', '2020-04-07 07:44:08', 0),
(33, 'Retur Pembelian', 'retur_pembelian/list', 15, '', '2020-04-19 10:53:17', '2020-04-19 10:53:17', 0),
(34, 'Retur Penjualan', 'retur_penjualan/list', 15, '', '2020-04-19 10:53:43', '2020-04-19 10:53:54', 0),
(35, 'Stock Opname', 'item/stock_opname', 14, '', '2020-05-18 20:29:36', '2020-05-18 20:29:36', 0),
(36, 'Print Stock Sheet', 'item/stock_sheet', 14, 'fa_file', '2020-05-23 09:52:55', '2020-05-23 09:52:55', 0),
(37, 'Import List Item', 'item/import', 14, 'fa_file-upload', '2020-05-23 11:34:50', '2020-05-23 11:34:50', 0),
(38, 'Kategori Item', 'master/kategori_item', 14, 'fa_file', '2020-07-19 06:43:19', '2020-07-19 06:44:22', 0),
(39, 'History Action', 'master/history_action', 17, '', '2020-07-21 02:40:20', '2020-07-21 02:43:07', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_metode_pembayaran`
--

CREATE TABLE `tb_metode_pembayaran` (
  `id_metode_pembayaran` int(11) NOT NULL,
  `nama_metode_pembayaran` varchar(200) NOT NULL,
  `default_payment` int(11) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_metode_pembayaran`
--

INSERT INTO `tb_metode_pembayaran` (`id_metode_pembayaran`, `nama_metode_pembayaran`, `default_payment`, `created`, `updated`, `deleted`) VALUES
(1, 'Cash', 0, '2020-03-11 12:54:26', '2020-03-11 12:54:26', 0),
(2, 'Debit', 0, '2020-03-11 12:54:31', '2020-03-11 12:54:31', 0),
(3, 'Credit Card', 0, '2020-03-11 12:54:38', '2020-03-11 12:54:38', 0),
(4, 'Hutang', 0, '2020-03-11 12:54:46', '2020-03-11 12:54:46', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_mutasi`
--

CREATE TABLE `tb_mutasi` (
  `id_mutasi` int(11) NOT NULL,
  `tanggal_mutasi` datetime NOT NULL,
  `approval` int(11) NOT NULL COMMENT '0=pending, 1=Approved, 2= Rejected',
  `gudang_asal` int(11) NOT NULL,
  `gudang_tujuan` int(11) NOT NULL,
  `catatan` text NOT NULL,
  `creator` int(11) NOT NULL,
  `approved_by` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_mutasi_detail`
--

CREATE TABLE `tb_mutasi_detail` (
  `id_mutasi_detail` int(11) NOT NULL,
  `id_mutasi` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `quantity` double NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_pemesanan`
--

CREATE TABLE `tb_pemesanan` (
  `id_pemesanan` int(11) NOT NULL,
  `no_po` varchar(200) NOT NULL,
  `tipe_item` varchar(200) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `id_gudang` int(11) NOT NULL,
  `ppn` int(11) NOT NULL,
  `ppn_nominal` float NOT NULL,
  `subtotal` int(11) NOT NULL,
  `grand_total` float NOT NULL,
  `creator` int(11) NOT NULL,
  `approved_1` int(11) NOT NULL,
  `approved_2` int(11) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `id_bank_account` int(11) NOT NULL,
  `tanggal_jatuh_tempo` date NOT NULL,
  `tanggal_pelunasan` date NOT NULL,
  `status_lunas` int(11) NOT NULL DEFAULT '1',
  `dropship` int(11) NOT NULL COMMENT '0 = tidak , 1 = ya',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_pemesanan_detail`
--

CREATE TABLE `tb_pemesanan_detail` (
  `id_pemesanan_detail` int(11) NOT NULL,
  `id_pemesanan` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `quantity` double NOT NULL,
  `diskon` float NOT NULL,
  `nominal_diskon` double NOT NULL,
  `satuan` varchar(100) NOT NULL,
  `harga` float NOT NULL,
  `catatan` text NOT NULL,
  `biaya_logistik` float NOT NULL,
  `subtotal` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_penjualan`
--

CREATE TABLE `tb_penjualan` (
  `id_penjualan` int(11) NOT NULL,
  `no_so` varchar(200) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `id_gudang` int(11) NOT NULL,
  `id_logistik` int(11) NOT NULL,
  `conf_logistik` int(11) NOT NULL COMMENT '0 = internal , 1 = external, 2 = tanpa logistik',
  `status_logistik` int(11) NOT NULL COMMENT '0 = Pending, 1 = Approved',
  `dropship` int(11) NOT NULL COMMENT '0 = yes , 1 = no',
  `subtotal` float NOT NULL,
  `diskon` float NOT NULL,
  `ppn` int(11) NOT NULL,
  `ppn_nominal` float NOT NULL,
  `grand_total` float NOT NULL,
  `creator` int(11) NOT NULL,
  `status_penjualan` int(11) NOT NULL DEFAULT '0',
  `id_metode_pembayaran` int(11) NOT NULL,
  `catatan_pembayaran` text NOT NULL,
  `tanggal_jatuh_tempo` date NOT NULL,
  `tanggal_pelunasan` date NOT NULL,
  `status_pelunasan` int(11) NOT NULL DEFAULT '1',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_penjualan_detail`
--

CREATE TABLE `tb_penjualan_detail` (
  `id_penjualan_detail` int(11) NOT NULL,
  `id_penjualan` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `quantity` double NOT NULL,
  `satuan` varchar(200) NOT NULL,
  `harga` float NOT NULL,
  `catatan` text NOT NULL,
  `biaya_logistik` double NOT NULL,
  `id_diskon` int(11) NOT NULL DEFAULT '0',
  `nominal_diskon` double NOT NULL DEFAULT '0',
  `subtotal` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_redline`
--

CREATE TABLE `tb_redline` (
  `id_redline` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `id_gudang` int(11) NOT NULL,
  `stok_minimum` double NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_retur_pembelian`
--

CREATE TABLE `tb_retur_pembelian` (
  `id_retur_pembelian` int(11) NOT NULL,
  `no_retur_pembelian` varchar(200) NOT NULL,
  `id_supplier` int(11) NOT NULL,
  `id_gudang` int(11) NOT NULL,
  `id_pemesanan` int(11) NOT NULL,
  `creator` int(11) NOT NULL,
  `approved_1` int(11) NOT NULL,
  `approved_2` int(11) NOT NULL,
  `status_retur` int(11) NOT NULL DEFAULT '0',
  `tanggal_jatuh_tempo_retur` date NOT NULL,
  `status_nota_retur` int(11) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_retur_pembelian_detail`
--

CREATE TABLE `tb_retur_pembelian_detail` (
  `id_retur_pembelian_detail` int(11) NOT NULL,
  `id_retur_pembelian` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `quantity` double NOT NULL,
  `catatan` text NOT NULL,
  `status_pengembalian` int(11) NOT NULL DEFAULT '0',
  `approval_pengembalian` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_retur_penjualan`
--

CREATE TABLE `tb_retur_penjualan` (
  `id_retur_penjualan` int(11) NOT NULL,
  `no_retur_penjualan` varchar(200) NOT NULL,
  `id_customer` int(11) NOT NULL,
  `id_gudang` int(11) NOT NULL,
  `id_penjualan` int(11) NOT NULL,
  `creator` int(11) NOT NULL,
  `approved_1` int(11) NOT NULL,
  `status_retur` int(11) NOT NULL DEFAULT '1',
  `tanggal_jatuh_tempo_retur` date NOT NULL,
  `status_nota_retur` int(11) NOT NULL DEFAULT '1',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_retur_penjualan_detail`
--

CREATE TABLE `tb_retur_penjualan_detail` (
  `id_retur_penjualan_detail` int(11) NOT NULL,
  `id_retur_penjualan` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `catatan` text NOT NULL,
  `status_pengembalian` int(11) NOT NULL,
  `approval_pengembalian` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_role`
--

CREATE TABLE `tb_role` (
  `id_role` int(11) NOT NULL,
  `nama_role` varchar(200) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_role`
--

INSERT INTO `tb_role` (`id_role`, `nama_role`, `created`, `updated`, `deleted`) VALUES
(1, 'Owner', '2020-02-20 13:42:11', '2020-02-20 13:42:11', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_stock_opname`
--

CREATE TABLE `tb_stock_opname` (
  `id_stock_opname` int(11) NOT NULL,
  `tanggal_stock_opname` datetime NOT NULL,
  `status` varchar(200) NOT NULL,
  `id_user` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_stock_opname_detail`
--

CREATE TABLE `tb_stock_opname_detail` (
  `id_stock_opname_detail` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `id_gudang` int(11) NOT NULL,
  `sku_item` varchar(200) NOT NULL,
  `nama_item` varchar(200) NOT NULL,
  `stok_database` double NOT NULL,
  `stok_gudang` double NOT NULL,
  `catatan` varchar(200) NOT NULL,
  `id_stock_opname` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_stok_gudang`
--

CREATE TABLE `tb_stok_gudang` (
  `id_stok_gudang` int(11) NOT NULL,
  `id_item` int(11) NOT NULL,
  `id_gudang` int(11) NOT NULL,
  `stok` double NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_supplier`
--

CREATE TABLE `tb_supplier` (
  `id_supplier` int(11) NOT NULL,
  `nama_supplier` varchar(200) NOT NULL,
  `jenis_barang_supplier` varchar(200) NOT NULL,
  `npwp_supplier` varchar(200) NOT NULL,
  `alamat_supplier` text NOT NULL,
  `koordinat_supplier` varchar(200) NOT NULL,
  `no_telpon_supplier` varchar(200) NOT NULL,
  `email_supplier` varchar(200) NOT NULL,
  `bank_supplier` varchar(200) NOT NULL,
  `no_rekening_supplier` varchar(200) NOT NULL,
  `nama_pemilik_rekening` varchar(200) NOT NULL,
  `pic1` varchar(200) NOT NULL,
  `pic1_phone` varchar(200) NOT NULL,
  `pic2` varchar(200) NOT NULL,
  `pic2_phone` varchar(200) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_supplier`
--

INSERT INTO `tb_supplier` (`id_supplier`, `nama_supplier`, `jenis_barang_supplier`, `npwp_supplier`, `alamat_supplier`, `koordinat_supplier`, `no_telpon_supplier`, `email_supplier`, `bank_supplier`, `no_rekening_supplier`, `nama_pemilik_rekening`, `pic1`, `pic1_phone`, `pic2`, `pic2_phone`, `created`, `updated`, `deleted`) VALUES
(2, 'PT. BERDIKARI TIMUR PERKASA', 'Semen Tonasa', '84.733.932.2-605.000', '<p>\r\n	<span style="font-family: Helvetica; font-size: 10pt; caret-color: rgb(0, 0, 0); color: rgb(0, 0, 0);">TANJUNG BATU 22 BLOK B 9 RT 014 RW 004, PERAK BARAT , KOTA SURABAYA</span></p>\r\n', '', '081 2155 2389', 'berdikaritimurperkasa@gmail.com', 'BRI', '0172-01-002209-302', 'PT. BERDIKARI TIMUR PERKASA', '', '', '', '', '2020-04-08 00:37:36', '2020-04-08 00:37:36', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama_user` varchar(200) NOT NULL,
  `id_jabatan` int(11) NOT NULL,
  `foto_profil` varchar(200) NOT NULL,
  `foto_ktp` varchar(200) NOT NULL,
  `foto_kk` varchar(200) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `username`, `password`, `nama_user`, `id_jabatan`, `foto_profil`, `foto_ktp`, `foto_kk`, `created`, `updated`, `deleted`) VALUES
(1, 'owner', '579233b2c479241523cba5e3af55d0f50f2d6414', 'Owner', 1, '', '', '', '2020-02-19 14:03:32', '2020-04-07 08:53:39', 0),
(2, 'kasir', '8691e4fc53b99da544ce86e22acba62d13352eff', 'Kasir', 6, '', '', '', '2020-04-07 08:56:57', '2020-04-07 08:56:57', 0),
(3, 'logistik', 'f32de7d33fc7852aedaa8149320057fb028ada41', 'Logistik', 4, '', '', '', '2020-04-07 08:57:09', '2020-04-07 08:57:09', 0),
(4, 'stok', 'e4ca2609b3c50b5c18b969f5cff1373b22dd0bb3', 'Stok', 3, '', '', '', '2020-04-07 08:57:30', '2020-04-07 08:57:30', 0),
(5, 'kepalatoko', '2c52cf8cf60955519a12604feb54bc27e0215667', 'Kepala Toko', 2, '', '', '', '2020-04-07 08:57:42', '2020-04-07 08:57:42', 0),
(6, 'admin', 'd033e22ae348aeb5660fc2140aec35850c4da997', 'Admin', 5, '', '', '', '2020-04-07 08:57:50', '2020-04-07 08:57:50', 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_user_access`
--

CREATE TABLE `tb_user_access` (
  `id_access` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `id_jabatan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_user_access`
--

INSERT INTO `tb_user_access` (`id_access`, `id_menu`, `id_jabatan`) VALUES
(2, 2, 1),
(3, 3, 1),
(4, 4, 1),
(5, 5, 1),
(7, 6, 1),
(8, 7, 1),
(9, 8, 1),
(10, 9, 1),
(11, 10, 1),
(12, 11, 1),
(13, 12, 1),
(14, 13, 1),
(15, 14, 1),
(16, 15, 1),
(17, 16, 1),
(18, 17, 1),
(19, 18, 1),
(20, 19, 1),
(21, 1, 2),
(22, 9, 2),
(23, 8, 2),
(24, 7, 2),
(25, 11, 2),
(26, 5, 2),
(27, 6, 2),
(28, 4, 2),
(29, 3, 2),
(30, 2, 2),
(31, 12, 2),
(32, 14, 2),
(33, 1, 3),
(34, 12, 3),
(35, 14, 3),
(36, 2, 3),
(37, 1, 4),
(38, 5, 4),
(40, 7, 5),
(41, 5, 5),
(42, 12, 5),
(43, 14, 5),
(44, 20, 1),
(45, 21, 1),
(46, 22, 1),
(47, 23, 1),
(48, 24, 1),
(49, 25, 1),
(50, 26, 1),
(51, 27, 1),
(52, 28, 1),
(53, 30, 1),
(54, 31, 1),
(55, 13, 3),
(58, 1, 5),
(60, 1, 1),
(61, 23, 2),
(62, 24, 2),
(63, 15, 2),
(64, 32, 1),
(65, 33, 1),
(66, 34, 1),
(67, 35, 1),
(68, 35, 4),
(69, 36, 1),
(70, 36, 4),
(71, 37, 1),
(72, 38, 1),
(73, 39, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_bank_account`
--
ALTER TABLE `tb_bank_account`
  ADD PRIMARY KEY (`id_bank_account`);

--
-- Indexes for table `tb_customer`
--
ALTER TABLE `tb_customer`
  ADD PRIMARY KEY (`id_customer`);

--
-- Indexes for table `tb_diskon`
--
ALTER TABLE `tb_diskon`
  ADD PRIMARY KEY (`id_diskon`);

--
-- Indexes for table `tb_finance`
--
ALTER TABLE `tb_finance`
  ADD PRIMARY KEY (`id_finance`);

--
-- Indexes for table `tb_gudang`
--
ALTER TABLE `tb_gudang`
  ADD PRIMARY KEY (`id_gudang`);

--
-- Indexes for table `tb_history_action`
--
ALTER TABLE `tb_history_action`
  ADD PRIMARY KEY (`id_history_action`);

--
-- Indexes for table `tb_history_stok`
--
ALTER TABLE `tb_history_stok`
  ADD PRIMARY KEY (`id_history`);

--
-- Indexes for table `tb_item`
--
ALTER TABLE `tb_item`
  ADD PRIMARY KEY (`id_item`);

--
-- Indexes for table `tb_item_kategori`
--
ALTER TABLE `tb_item_kategori`
  ADD PRIMARY KEY (`id_item_kategori`);

--
-- Indexes for table `tb_item_keluar`
--
ALTER TABLE `tb_item_keluar`
  ADD PRIMARY KEY (`id_item_keluar`);

--
-- Indexes for table `tb_item_keluar_detail`
--
ALTER TABLE `tb_item_keluar_detail`
  ADD PRIMARY KEY (`id_item_keluar_detail`);

--
-- Indexes for table `tb_item_masuk`
--
ALTER TABLE `tb_item_masuk`
  ADD PRIMARY KEY (`id_item_masuk`);

--
-- Indexes for table `tb_item_masuk_detail`
--
ALTER TABLE `tb_item_masuk_detail`
  ADD PRIMARY KEY (`id_item_masuk_detail`);

--
-- Indexes for table `tb_jabatan`
--
ALTER TABLE `tb_jabatan`
  ADD PRIMARY KEY (`id_jabatan`);

--
-- Indexes for table `tb_kategori_finance`
--
ALTER TABLE `tb_kategori_finance`
  ADD PRIMARY KEY (`id_kategori_finance`);

--
-- Indexes for table `tb_komponen_harga_jual`
--
ALTER TABLE `tb_komponen_harga_jual`
  ADD PRIMARY KEY (`id_komponen_harga_jual`);

--
-- Indexes for table `tb_logistik`
--
ALTER TABLE `tb_logistik`
  ADD PRIMARY KEY (`id_logistik`);

--
-- Indexes for table `tb_menu`
--
ALTER TABLE `tb_menu`
  ADD PRIMARY KEY (`id_menu`);

--
-- Indexes for table `tb_metode_pembayaran`
--
ALTER TABLE `tb_metode_pembayaran`
  ADD PRIMARY KEY (`id_metode_pembayaran`);

--
-- Indexes for table `tb_mutasi`
--
ALTER TABLE `tb_mutasi`
  ADD PRIMARY KEY (`id_mutasi`);

--
-- Indexes for table `tb_mutasi_detail`
--
ALTER TABLE `tb_mutasi_detail`
  ADD PRIMARY KEY (`id_mutasi_detail`);

--
-- Indexes for table `tb_pemesanan`
--
ALTER TABLE `tb_pemesanan`
  ADD PRIMARY KEY (`id_pemesanan`);

--
-- Indexes for table `tb_pemesanan_detail`
--
ALTER TABLE `tb_pemesanan_detail`
  ADD PRIMARY KEY (`id_pemesanan_detail`);

--
-- Indexes for table `tb_penjualan`
--
ALTER TABLE `tb_penjualan`
  ADD PRIMARY KEY (`id_penjualan`);

--
-- Indexes for table `tb_penjualan_detail`
--
ALTER TABLE `tb_penjualan_detail`
  ADD PRIMARY KEY (`id_penjualan_detail`);

--
-- Indexes for table `tb_redline`
--
ALTER TABLE `tb_redline`
  ADD PRIMARY KEY (`id_redline`);

--
-- Indexes for table `tb_retur_pembelian`
--
ALTER TABLE `tb_retur_pembelian`
  ADD PRIMARY KEY (`id_retur_pembelian`);

--
-- Indexes for table `tb_retur_pembelian_detail`
--
ALTER TABLE `tb_retur_pembelian_detail`
  ADD PRIMARY KEY (`id_retur_pembelian_detail`);

--
-- Indexes for table `tb_retur_penjualan`
--
ALTER TABLE `tb_retur_penjualan`
  ADD PRIMARY KEY (`id_retur_penjualan`);

--
-- Indexes for table `tb_retur_penjualan_detail`
--
ALTER TABLE `tb_retur_penjualan_detail`
  ADD PRIMARY KEY (`id_retur_penjualan_detail`);

--
-- Indexes for table `tb_role`
--
ALTER TABLE `tb_role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexes for table `tb_stock_opname`
--
ALTER TABLE `tb_stock_opname`
  ADD PRIMARY KEY (`id_stock_opname`);

--
-- Indexes for table `tb_stock_opname_detail`
--
ALTER TABLE `tb_stock_opname_detail`
  ADD PRIMARY KEY (`id_stock_opname_detail`);

--
-- Indexes for table `tb_stok_gudang`
--
ALTER TABLE `tb_stok_gudang`
  ADD PRIMARY KEY (`id_stok_gudang`),
  ADD UNIQUE KEY `itemgudang` (`id_item`,`id_gudang`);

--
-- Indexes for table `tb_supplier`
--
ALTER TABLE `tb_supplier`
  ADD PRIMARY KEY (`id_supplier`);

--
-- Indexes for table `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `tb_user_access`
--
ALTER TABLE `tb_user_access`
  ADD PRIMARY KEY (`id_access`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_bank_account`
--
ALTER TABLE `tb_bank_account`
  MODIFY `id_bank_account` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tb_customer`
--
ALTER TABLE `tb_customer`
  MODIFY `id_customer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tb_diskon`
--
ALTER TABLE `tb_diskon`
  MODIFY `id_diskon` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tb_finance`
--
ALTER TABLE `tb_finance`
  MODIFY `id_finance` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tb_gudang`
--
ALTER TABLE `tb_gudang`
  MODIFY `id_gudang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tb_history_action`
--
ALTER TABLE `tb_history_action`
  MODIFY `id_history_action` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `tb_history_stok`
--
ALTER TABLE `tb_history_stok`
  MODIFY `id_history` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tb_item`
--
ALTER TABLE `tb_item`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=348;
--
-- AUTO_INCREMENT for table `tb_item_kategori`
--
ALTER TABLE `tb_item_kategori`
  MODIFY `id_item_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `tb_item_keluar`
--
ALTER TABLE `tb_item_keluar`
  MODIFY `id_item_keluar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tb_item_keluar_detail`
--
ALTER TABLE `tb_item_keluar_detail`
  MODIFY `id_item_keluar_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tb_item_masuk`
--
ALTER TABLE `tb_item_masuk`
  MODIFY `id_item_masuk` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tb_item_masuk_detail`
--
ALTER TABLE `tb_item_masuk_detail`
  MODIFY `id_item_masuk_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `tb_jabatan`
--
ALTER TABLE `tb_jabatan`
  MODIFY `id_jabatan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tb_kategori_finance`
--
ALTER TABLE `tb_kategori_finance`
  MODIFY `id_kategori_finance` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `tb_komponen_harga_jual`
--
ALTER TABLE `tb_komponen_harga_jual`
  MODIFY `id_komponen_harga_jual` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `tb_logistik`
--
ALTER TABLE `tb_logistik`
  MODIFY `id_logistik` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tb_menu`
--
ALTER TABLE `tb_menu`
  MODIFY `id_menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
--
-- AUTO_INCREMENT for table `tb_metode_pembayaran`
--
ALTER TABLE `tb_metode_pembayaran`
  MODIFY `id_metode_pembayaran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tb_mutasi`
--
ALTER TABLE `tb_mutasi`
  MODIFY `id_mutasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tb_mutasi_detail`
--
ALTER TABLE `tb_mutasi_detail`
  MODIFY `id_mutasi_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tb_pemesanan`
--
ALTER TABLE `tb_pemesanan`
  MODIFY `id_pemesanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `tb_pemesanan_detail`
--
ALTER TABLE `tb_pemesanan_detail`
  MODIFY `id_pemesanan_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
--
-- AUTO_INCREMENT for table `tb_penjualan`
--
ALTER TABLE `tb_penjualan`
  MODIFY `id_penjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `tb_penjualan_detail`
--
ALTER TABLE `tb_penjualan_detail`
  MODIFY `id_penjualan_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;
--
-- AUTO_INCREMENT for table `tb_redline`
--
ALTER TABLE `tb_redline`
  MODIFY `id_redline` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tb_retur_pembelian`
--
ALTER TABLE `tb_retur_pembelian`
  MODIFY `id_retur_pembelian` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `tb_retur_pembelian_detail`
--
ALTER TABLE `tb_retur_pembelian_detail`
  MODIFY `id_retur_pembelian_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tb_retur_penjualan`
--
ALTER TABLE `tb_retur_penjualan`
  MODIFY `id_retur_penjualan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tb_retur_penjualan_detail`
--
ALTER TABLE `tb_retur_penjualan_detail`
  MODIFY `id_retur_penjualan_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tb_role`
--
ALTER TABLE `tb_role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tb_stock_opname`
--
ALTER TABLE `tb_stock_opname`
  MODIFY `id_stock_opname` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `tb_stock_opname_detail`
--
ALTER TABLE `tb_stock_opname_detail`
  MODIFY `id_stock_opname_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `tb_stok_gudang`
--
ALTER TABLE `tb_stok_gudang`
  MODIFY `id_stok_gudang` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `tb_supplier`
--
ALTER TABLE `tb_supplier`
  MODIFY `id_supplier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tb_user_access`
--
ALTER TABLE `tb_user_access`
  MODIFY `id_access` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
