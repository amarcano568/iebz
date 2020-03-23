-- --------------------------------------------------------
-- Host:                         localhost
-- Versión del servidor:         5.7.24 - MySQL Community Server (GPL)
-- SO del servidor:              Win64
-- HeidiSQL Versión:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Volcando estructura de base de datos para help-desk
CREATE DATABASE IF NOT EXISTS `help-desk` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `help-desk`;

-- Volcando estructura para tabla help-desk.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `id_Empresa` int(11) DEFAULT NULL,
  `BaseDatos` varchar(45) DEFAULT NULL,
  `BaseDatosAux` varchar(45) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `Telefono` varchar(45) DEFAULT NULL,
  `cargo` int(11) DEFAULT NULL,
  `idArea` int(11) DEFAULT NULL,
  `idSubArea` int(11) DEFAULT NULL,
  `rol` char(3) DEFAULT NULL,
  `changePassword` char(1) DEFAULT NULL,
  `photo` varchar(150) DEFAULT NULL,
  `cayro` tinyint(1) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `superUser` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla help-desk.users: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
REPLACE INTO `users` (`id`, `name`, `lastName`, `email`, `email_verified_at`, `password`, `id_Empresa`, `BaseDatos`, `BaseDatosAux`, `status`, `Telefono`, `cargo`, `idArea`, `idSubArea`, `rol`, `changePassword`, `photo`, `cayro`, `created_at`, `updated_at`, `remember_token`, `superUser`) VALUES
	(21, 'Alexander', 'Marcano A.', 'amarcano568@gmail.com', NULL, '$2y$10$MXpPxoAVoNoC6ecRkLXuwOwDsNKf..YiCLTsVbsPZ0OZuZ4c6n7Yy', 1, 'cayro', NULL, 1, '931253265', 4, 22, 4, 'adm', NULL, 'Empresas\\cayro\\fotos\\foto-1.jpeg', 0, '2019-11-04 21:51:46', '2019-12-04 19:20:26', 'RHCgp2cigAUyUmJG8k4m7rnZZx1jb2e8lv0oOgUJ91x3TjxZbyXcMczGV9rC', 1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;


-- Volcando estructura de base de datos para roles
CREATE DATABASE IF NOT EXISTS `roles` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `roles`;

-- Volcando estructura para tabla roles.permission_user
CREATE TABLE IF NOT EXISTS `permission_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_user_permission_id_index` (`permission_id`),
  KEY `permission_user_user_id_index` (`user_id`),
  CONSTRAINT `permission_user_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roles.permission_user: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `permission_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `permission_user` ENABLE KEYS */;

-- Volcando estructura para tabla roles.role_user
CREATE TABLE IF NOT EXISTS `role_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` bigint(20) unsigned NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `role_user_role_id_index` (`role_id`),
  KEY `role_user_user_id_index` (`user_id`),
  CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roles.role_user: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `role_user` DISABLE KEYS */;
REPLACE INTO `role_user` (`id`, `role_id`, `user_id`, `created_at`, `updated_at`) VALUES
	(1, 1, 21, NULL, NULL),
	(3, 6, 22, '2019-12-11 16:23:43', '2019-12-11 16:23:43');
/*!40000 ALTER TABLE `role_user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
