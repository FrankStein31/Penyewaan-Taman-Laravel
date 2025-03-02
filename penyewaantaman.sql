/*
SQLyog Enterprise v13.1.1 (64 bit)
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*Data for the table `fasilitas` */

insert  into `fasilitas`(`id_fasilitas`,`nama_fasilitas`) values 
(1,'Gajebo'),
(2,'Air Mancur'),
(3,'Toilet Umum'),
(5,'Aula'),
(6,'Air Minum');

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
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bukti_pembayaran` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jumlah` decimal(10,2) NOT NULL,
  `status` enum('pending','diverifikasi','ditolak') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `payment_data` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pembayaran_pemesanan_id_foreign` (`pemesanan_id`),
  CONSTRAINT `pembayaran_pemesanan_id_foreign` FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `pembayaran` */

insert  into `pembayaran`(`id`,`pemesanan_id`,`transaction_id`,`order_id`,`payment_type`,`bukti_pembayaran`,`jumlah`,`status`,`catatan`,`payment_data`,`created_at`,`updated_at`) values 
(1,10,'95d8a9ea-2798-494f-befb-e565d05300e5','SPT-10-1740627530','bank_transfer',NULL,41666.00,'diverifikasi',NULL,'{\"status_code\":\"200\",\"status_message\":\"Success, transaction is found\",\"transaction_id\":\"95d8a9ea-2798-494f-befb-e565d05300e5\",\"order_id\":\"SPT-10-1740627530\",\"gross_amount\":\"41666.00\",\"payment_type\":\"bank_transfer\",\"transaction_time\":\"2025-02-27 10:39:46\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"13667693860626348746739\"}],\"bca_va_number\":\"13667693860626348746739\",\"pdf_url\":\"https://app.sandbox.midtrans.com/snap/v1/transactions/488ee4e3-4865-428d-a677-6526788d1ea4/pdf\",\"finish_redirect_url\":\"http://example.com?order_id=SPT-10-1740627530&status_code=200&transaction_status=settlement\"}','2025-02-27 03:39:13','2025-02-27 03:40:05'),
(2,12,NULL,NULL,NULL,'bukti_pembayaran/57fpJxdRBRKWdx5GfoXxrr5RMQDkXbEzaUDxKYrP.png',694.44,'ditolak','bukti pembayaran tidak sesuai',NULL,'2025-02-27 03:56:29','2025-02-27 04:47:57'),
(3,12,NULL,NULL,NULL,'bukti_pembayaran/zDjpx0xW0nxY4RmOf5sDnajedhmg15OipMC8Kp5e.jpg',694.44,'diverifikasi',NULL,NULL,'2025-02-27 04:48:51','2025-02-27 04:49:31'),
(6,13,NULL,NULL,NULL,'bukti_pembayaran/xLT74NgFm3ljP4AmEl2NdOfqx37v9ARF99rB3U7z.png',250000.00,'ditolak','bukti tidak valid',NULL,'2025-03-02 01:58:47','2025-03-02 01:59:39'),
(7,13,'210b32f1-9caa-4e58-a913-7a048db96e25','SPT-13-1740880816','bank_transfer',NULL,250000.00,'diverifikasi',NULL,'{\"status_code\":\"200\",\"status_message\":\"Success, transaction is found\",\"transaction_id\":\"210b32f1-9caa-4e58-a913-7a048db96e25\",\"order_id\":\"SPT-13-1740880816\",\"gross_amount\":\"250000.00\",\"payment_type\":\"bank_transfer\",\"transaction_time\":\"2025-03-02 09:00:23\",\"transaction_status\":\"settlement\",\"fraud_status\":\"accept\",\"va_numbers\":[{\"bank\":\"bca\",\"va_number\":\"13667042480833644535908\"}],\"bca_va_number\":\"13667042480833644535908\",\"pdf_url\":\"https://app.sandbox.midtrans.com/snap/v1/transactions/b1ba8ae4-6e5d-4d7e-8d08-2ceb19263ef5/pdf\",\"finish_redirect_url\":\"http://example.com?order_id=SPT-13-1740880816&status_code=200&transaction_status=settlement\"}','2025-03-02 02:00:50','2025-03-02 02:00:50');

/*Table structure for table `pemesanan` */

DROP TABLE IF EXISTS `pemesanan`;

CREATE TABLE `pemesanan` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `taman_id` bigint unsigned NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date NOT NULL,
  `waktu_mulai` datetime NOT NULL,
  `waktu_selesai` datetime NOT NULL,
  `keperluan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `jumlah_orang` int DEFAULT NULL,
  `total_hari` int unsigned NOT NULL,
  `total_jam` int NOT NULL,
  `status` enum('pending','disetujui','ditolak','dibayar','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `total_harga` decimal(10,2) NOT NULL,
  `catatan_admin` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kode` (`kode`),
  KEY `pemesanan_user_id_foreign` (`user_id`),
  KEY `pemesanan_taman_id_foreign` (`taman_id`),
  CONSTRAINT `pemesanan_taman_id_foreign` FOREIGN KEY (`taman_id`) REFERENCES `taman` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pemesanan_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `pemesanan` */

insert  into `pemesanan`(`id`,`kode`,`user_id`,`taman_id`,`tanggal_mulai`,`tanggal_selesai`,`waktu_mulai`,`waktu_selesai`,`keperluan`,`jumlah_orang`,`total_hari`,`total_jam`,`status`,`total_harga`,`catatan_admin`,`created_at`,`updated_at`) values 
(5,'PSN-20250105-TJ8SL',2,4,'2025-01-06','2025-01-06','2025-01-06 10:41:00','2025-01-06 12:40:00','gabut',500,1,2,'ditolak',37187.50,'alasannya apa','2025-01-05 03:41:19','2025-01-05 04:42:13'),
(8,'PSN-20250105-6GXKZ',2,3,'2025-01-06','2025-01-10','2025-01-06 11:55:00','2025-01-10 11:55:00','manten',500,4,96,'selesai',2000000.00,NULL,'2025-01-05 04:55:17','2025-01-12 00:53:50'),
(9,'PSN-20250112-HA5EA',3,9,'2025-01-13','2025-01-13','2025-01-13 10:00:00','2025-01-13 13:00:00','gabut',100,1,3,'selesai',31250.00,NULL,'2025-01-12 01:01:37','2025-02-17 00:24:48'),
(10,'PSN-20250227-50TT5',3,9,'2025-03-01','2025-03-01','2025-02-27 10:00:00','2025-02-27 11:00:00','acara pensi',3000,1,4,'selesai',41666.67,NULL,'2025-02-27 02:56:15','2025-02-27 04:41:20'),
(12,'PSN-20250227-WI9L1',2,7,'2025-02-27','2025-02-27','2025-02-27 10:55:00','2025-02-27 11:05:00','bukber',10,1,0,'selesai',694.44,NULL,'2025-02-27 03:51:30','2025-03-02 02:02:34'),
(13,'PSN-20250302-7MKMF',4,3,'2025-03-03','2025-03-03','2025-03-03 09:00:00','2025-03-03 21:00:00','Bukber',500,1,12,'selesai',250000.00,NULL,'2025-03-02 01:41:52','2025-03-02 02:01:45');

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `taman` */

insert  into `taman`(`id`,`nama`,`deskripsi`,`lokasi`,`kapasitas`,`harga_per_hari`,`fasilitas`,`gambar`,`status`,`created_at`,`updated_at`) values 
(2,'Jus Mangga','ssssssss','sssssssssss',12,150000.00,'[\"Aula\",\"Toilet Umum\"]','taman/1740192574_Logo Polinema.png',1,'2025-01-04 01:33:25','2025-02-27 03:58:07'),
(3,'Sakartaji','apa lah gitu ya','Kediri',1000,500000.00,'[\"Air Mancur\",\"Aula\",\"Gajebo\",\"Toilet Umum\"]','taman/1740192547_Logo Polinema.png',1,'2025-01-05 02:14:00','2025-03-02 02:01:45'),
(4,'Brantas','Samping sungai brantas','Kediri',2000,450000.00,'[\"Aula\",\"Gajebo\",\"Toilet Umum\"]','taman/1740192560_Logo Polinema.png',1,'2025-01-05 02:34:32','2025-02-27 03:50:15'),
(5,'aaaaaaaaaa','aaaaaaaaaaaaa','aaaaaaaaaaaaaa',11111,900000.00,'[\"Air Mancur\",\"Aula\",\"Gajebo\",\"Toilet Umum\"]','taman/1740192526_back hitam.jpg',1,'2025-01-05 02:52:30','2025-02-22 02:48:46'),
(6,'bbbbbbbbbbbb','bbbbbbbbbbbbbbbbb','bbbbbbbbbbbbbbb',2000,800000.00,'[\"Aula\",\"Toilet Umum\"]','taman/1740192515_back hitam 3.jpg',1,'2025-01-05 02:52:51','2025-02-22 02:48:35'),
(7,'cccccccccccccc','cqweqwras','asasdqwdq',50,100000.00,'[\"Toilet Umum\"]','taman/1740192503_back hitam.jpg',1,'2025-01-05 02:53:17','2025-03-02 02:02:34'),
(9,'Jayabaya','Taman apa gitu','Kediri Kota',5000,250000.00,'[\"Air Mancur\",\"Air Minum\",\"Aula\",\"Gajebo\",\"Toilet Umum\"]','taman/1740192484_back hitam 2.jpg',1,'2025-01-12 00:58:59','2025-02-27 04:41:20');

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`name`,`email`,`email_verified_at`,`password`,`phone`,`remember_token`,`created_at`,`updated_at`,`role`) values 
(1,'admin','admin@gmail.com',NULL,'$2y$12$b3lbjH0MBwTGSF/XFpBk/.Ju2SPiYSz2Xyq89S.N6hqVNouRf4TBq','08512345678',NULL,'2025-01-04 01:04:00','2025-01-04 01:16:49','admin'),
(2,'frankie','frankie.steinlie@gmail.com',NULL,'$2y$12$LgQvNXsuRaCpxDf2raK2je2VK74FvmweAm7QsJ8Q8V.C.wmygpbeC','08883866931',NULL,'2025-01-04 01:22:53','2025-01-05 02:38:29','user'),
(3,'steinlie','frankie.intern24slides@gmail.com',NULL,'$2y$12$HbchNm3Q6yXxhnfmbMz4Xed/F.BqvaX1CtDX8FlRZQK/5TNcZ9xO.',NULL,NULL,'2025-01-12 01:00:23','2025-01-12 01:00:23','user'),
(4,'Coba','bukudigital41@gmail.com',NULL,'$2y$12$ejV/AdvXcXEIeElPXY7VYuv5y0EbWsJW.ejYssEFvmSstHZR5QJ/a',NULL,NULL,'2025-03-02 01:41:01','2025-03-02 01:41:01','user');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
