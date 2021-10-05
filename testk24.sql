-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.3.9-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table testk24.admin
CREATE TABLE IF NOT EXISTS `admin` (
  `admin_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID admin',
  `admin_username` varchar(20) DEFAULT NULL COMMENT 'Username admin',
  `admin_name` varchar(100) DEFAULT NULL COMMENT 'Nama admin',
  `admin_password` text DEFAULT NULL COMMENT 'Password admin',
  `admin_email` varchar(250) DEFAULT NULL COMMENT 'Email admin',
  `admin_last_login_datetime` datetime DEFAULT NULL COMMENT 'Terakhir login',
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1 COMMENT='Tabel Admin';

-- Dumping data for table testk24.admin: ~1 rows (approximately)
DELETE FROM `admin`;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` (`admin_id`, `admin_username`, `admin_name`, `admin_password`, `admin_email`, `admin_last_login_datetime`) VALUES
	(1, 'superadmin', 'Super Admin', '$2y$10$udDQNYouOBxBYM6D3Yo.wuaOGxiRaqGs0bhUp0bEYLyFUdXVuMoRW', 'superadmin@admin.com', '2021-10-05 06:02:20');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;

-- Dumping structure for table testk24.member
CREATE TABLE IF NOT EXISTS `member` (
  `member_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID member',
  `member_name` varchar(100) DEFAULT NULL COMMENT 'Nama member',
  `member_password` text DEFAULT NULL COMMENT 'Password member',
  `member_email` varchar(255) DEFAULT NULL COMMENT 'Email member',
  `member_mobile_phone` varchar(20) DEFAULT NULL COMMENT 'No Hp',
  `member_birth_date` date DEFAULT NULL COMMENT 'Tgl lahir',
  `member_gender` enum('Laki-laki','Perempuan') DEFAULT NULL COMMENT 'Jenis kelamin',
  `member_id_number` varchar(50) DEFAULT NULL COMMENT 'No KTP',
  `member_image_url` varchar(500) DEFAULT NULL COMMENT 'Url image',
  `member_last_login_datetime` datetime DEFAULT NULL COMMENT 'Terakhir login',
  PRIMARY KEY (`member_id`),
  UNIQUE KEY `member_email` (`member_email`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Tabel member';

-- Dumping data for table testk24.member: ~0 rows (approximately)
DELETE FROM `member`;
/*!40000 ALTER TABLE `member` DISABLE KEYS */;
/*!40000 ALTER TABLE `member` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
