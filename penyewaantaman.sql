/*
SQLyog Professional v13.1.1 (64 bit)
MySQL - 8.0.30 : Database - penyewaantaman
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`penyewaantaman` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `penyewaantaman`;

/*Table structure for table `fasilitas` */

DROP TABLE IF EXISTS `fasilitas`;

CREATE TABLE `fasilitas` (
  `id_fasilitas` int NOT NULL AUTO_INCREMENT,
  `nama_fasilitas` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_fasilitas`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `fasilitas` */

insert  into `fasilitas`(`id_fasilitas`,`nama_fasilitas`) values 
(1,'Gajebo'),
(2,'Air Mancur'),
(3,'Toilet Umum'),
(5,'Aula');

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

/*Table structure for table `pembayaran` */

DROP TABLE IF EXISTS `pembayaran`;

CREATE TABLE `pembayaran` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `pemesanan_id` bigint unsigned NOT NULL,
  `bukti_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `status` enum('pending','diverifikasi','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pembayaran_pemesanan_id_foreign` (`pemesanan_id`),
  CONSTRAINT `pembayaran_pemesanan_id_foreign` FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `pembayaran` */

/*Table structure for table `pemesanan` */

DROP TABLE IF EXISTS `pemesanan`;

CREATE TABLE `pemesanan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `taman_id` bigint unsigned NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `keperluan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_orang` int DEFAULT NULL,
  `status` enum('pending','disetujui','ditolak','dibayar','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `total_harga` decimal(10,2) NOT NULL,
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pemesanan_user_id_foreign` (`user_id`),
  KEY `pemesanan_taman_id_foreign` (`taman_id`),
  CONSTRAINT `pemesanan_taman_id_foreign` FOREIGN KEY (`taman_id`) REFERENCES `taman` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pemesanan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `pemesanan` */

/*Table structure for table `taman` */

DROP TABLE IF EXISTS `taman`;

CREATE TABLE `taman` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kapasitas` int NOT NULL,
  `harga_per_hari` decimal(10,2) NOT NULL,
  `fasilitas` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `taman` */

insert  into `taman`(`id`,`nama`,`deskripsi`,`lokasi`,`kapasitas`,`harga_per_hari`,`fasilitas`,`gambar`,`status`,`created_at`,`updated_at`) values 
(2,'Jus Mangga','ssssssss','sssssssssss',12,150000.00,'[\"Aula\",\"Toilet Umum\"]','taman/1735954405_Surat Keterangan Lulus - Frankie Steinlie.jpg',1,'2025-01-04 01:33:25','2025-01-05 03:05:32'),
(3,'Sakartaji','apa lah gitu ya','Kediri',1000,500000.00,'[\"Air Mancur\",\"Aula\",\"Gajebo\",\"Toilet Umum\"]','taman/1736043240_Logo Polinema.png',1,'2025-01-05 02:14:00','2025-01-05 02:15:56'),
(4,'Brantas','Samping sungai brantas','Kediri',2000,450000.00,'[\"Aula\",\"Gajebo\",\"Toilet Umum\"]','taman/1736044472_Logo Polinema.png',1,'2025-01-05 02:34:32','2025-01-05 02:34:32'),
(5,'aaaaaaaaaa','aaaaaaaaaaaaa','aaaaaaaaaaaaaa',11111,900000.00,'[\"Air Mancur\",\"Aula\",\"Gajebo\",\"Toilet Umum\"]','taman/1736045550_Logo Polinema.png',1,'2025-01-05 02:52:30','2025-01-05 02:52:30'),
(6,'bbbbbbbbbbbb','bbbbbbbbbbbbbbbbb','bbbbbbbbbbbbbbb',2000,800000.00,'[\"Aula\",\"Toilet Umum\"]','taman/1736045571_Logo Polinema.png',1,'2025-01-05 02:52:51','2025-01-05 02:52:51'),
(7,'cccccccccccccc','cqweqwras','asasdqwdq',50,100000.00,'[\"Toilet Umum\"]','taman/1736045597_Logo Polinema.png',1,'2025-01-05 02:53:17','2025-01-05 02:53:17');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`password`,`phone`,`remember_token`,`created_at`,`updated_at`,`role`) values 
(1,'admin','admin@gmail.com',NULL,'$2y$12$b3lbjH0MBwTGSF/XFpBk/.Ju2SPiYSz2Xyq89S.N6hqVNouRf4TBq','08512345678',NULL,'2025-01-04 01:04:00','2025-01-04 01:16:49','admin'),
(2,'frankie','frankie.steinlie@gmail.com',NULL,'$2y$12$LgQvNXsuRaCpxDf2raK2je2VK74FvmweAm7QsJ8Q8V.C.wmygpbeC','08883866931',NULL,'2025-01-04 01:22:53','2025-01-05 02:38:29','user');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
