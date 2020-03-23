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

-- Volcando estructura para tabla help-desk.empresas
CREATE TABLE IF NOT EXISTS `empresas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `NombreEmpresa` varchar(100) DEFAULT NULL,
  `ruc` varchar(45) DEFAULT NULL,
  `nameBd` varchar(45) DEFAULT NULL,
  `usuariosPermitidos` int(11) DEFAULT NULL,
  `telefono1` varchar(45) DEFAULT NULL,
  `telefono2` varchar(45) DEFAULT NULL,
  `direccion` varchar(45) DEFAULT NULL,
  `correo` varchar(45) DEFAULT NULL,
  `representante` varchar(45) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `userAdmin` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;

-- Volcando datos para la tabla help-desk.empresas: ~3 rows (aproximadamente)
/*!40000 ALTER TABLE `empresas` DISABLE KEYS */;
REPLACE INTO `empresas` (`id`, `NombreEmpresa`, `ruc`, `nameBd`, `usuariosPermitidos`, `telefono1`, `telefono2`, `direccion`, `correo`, `representante`, `status`, `userAdmin`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
	(1, 'Cayro Soluciones Sac', '20605135502', 'cayro', 5, '12345678', '87654321', 'Av. Principal surquillo', 'admi@cayro.com.pe', 'Carlos Salazar', 1, 1, NULL, '2019-12-09 16:26:54', NULL, NULL),
	(2, 'Bago', '12345678', 'bago', 20, NULL, NULL, 'Miraflores', 'admin@bago.com.pe', 'Juan E', 1, 7, NULL, '2019-12-16 14:59:42', NULL, NULL),
	(45, 'criocord', '1234567098', 'criocord', 10, '2232233', '1', 'Miraflores', 'admi@criocord.com.pe', 'Miguel Alfonzo', 1, 52, '2019-12-16 14:30:21', '2019-12-16 14:30:21', 1, NULL);
/*!40000 ALTER TABLE `empresas` ENABLE KEYS */;

-- Volcando estructura para tabla help-desk.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla help-desk.migrations: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Volcando estructura para tabla help-desk.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla help-desk.password_resets: ~1 rows (aproximadamente)
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
REPLACE INTO `password_resets` (`email`, `token`, `created_at`) VALUES
	('amarcano568@gmail.com', '$2y$10$X5VNvPWr6nv/s3C4gumQ3.pmNzpRiZcyaZiazIo4McS5KJ9VzAOi.', '2019-12-20 14:21:32');
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;

-- Volcando estructura para tabla help-desk.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla help-desk.permissions: ~14 rows (aproximadamente)
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
REPLACE INTO `permissions` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
	(10, 'Navegar tablero de trabajo', 'dashboard', 'Lista y Navega todos los Usuarios del Sistema', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(20, 'Opción Solicitud Soporte', 'solicitudSoporte', 'Puede Solicitar Soporte Técnico (Generar Tickert)', NULL, NULL),
	(30, 'Opción de Administración de Tickets', 'admin-Soporte', 'Administrar las Solicitudes de Soporte Técnico', NULL, NULL),
	(40, 'Menu Reportes del Sistema', 'menu.reportes', 'Ver Reportes del Sistema.', NULL, NULL),
	(41, 'Opción Reporte de Tickets Recibidos', 'report-ticket-enviados', 'Opción Reporte de Tickets Recibidos', NULL, NULL),
	(42, 'Opción Reporte Estadísticas', 'report-estadisticas', 'Opción Reporte Estadísticas', NULL, NULL),
	(50, 'Menu Administración', 'menu.administracion', 'Ver Menú deAdministración', NULL, NULL),
	(52, 'Opción mantenimiento de Empresa', 'mantEmpresa', 'Opción mantenimiento de Empresa', NULL, NULL),
	(53, 'Opción mantenimiento de Usuario', 'mantUsuarios', 'Opción mantenimiento de Usuario', NULL, NULL),
	(54, 'Opción mantenimiento de Tickets', 'mantTickets', 'Opción mantenimiento de tickets', NULL, NULL),
	(55, 'Opción mantenimiento de Categorías/Subcategorías', 'mantCategorias', 'Opción mantenimiento de Categorías/Subcategorías', NULL, NULL),
	(56, 'Opción mantenimiento de Áreas/Subareas', 'mantAreas', 'Opción mantenimiento de Áreas/Subareas', NULL, NULL),
	(57, 'Opción de configuración de Correo', 'config-correo', 'Opción de configuración de Correo', NULL, NULL);
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;

-- Volcando estructura para tabla help-desk.permission_role
CREATE TABLE IF NOT EXISTS `permission_role` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `permission_id` bigint(20) unsigned NOT NULL,
  `role_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permission_role_permission_id_index` (`permission_id`),
  KEY `permission_role_role_id_index` (`role_id`),
  CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla help-desk.permission_role: ~26 rows (aproximadamente)
/*!40000 ALTER TABLE `permission_role` DISABLE KEYS */;
REPLACE INTO `permission_role` (`id`, `permission_id`, `role_id`, `created_at`, `updated_at`) VALUES
	(57, 10, 9, '2019-12-12 17:16:12', '2019-12-12 17:16:12'),
	(72, 10, 7, '2019-12-12 19:36:53', '2019-12-12 19:36:53'),
	(73, 20, 7, '2019-12-12 19:36:53', '2019-12-12 19:36:53'),
	(74, 30, 7, '2019-12-12 19:36:53', '2019-12-12 19:36:53'),
	(75, 40, 7, '2019-12-12 19:36:53', '2019-12-12 19:36:53'),
	(76, 41, 7, '2019-12-12 19:36:53', '2019-12-12 19:36:53'),
	(77, 42, 7, '2019-12-12 19:36:53', '2019-12-12 19:36:53'),
	(78, 50, 7, '2019-12-12 19:36:53', '2019-12-12 19:36:53'),
	(79, 52, 7, '2019-12-12 19:36:53', '2019-12-12 19:36:53'),
	(80, 53, 7, '2019-12-12 19:36:53', '2019-12-12 19:36:53'),
	(81, 54, 7, '2019-12-12 19:36:53', '2019-12-12 19:36:53'),
	(82, 55, 7, '2019-12-12 19:36:53', '2019-12-12 19:36:53'),
	(83, 56, 7, '2019-12-12 19:36:53', '2019-12-12 19:36:53'),
	(84, 57, 7, '2019-12-12 19:36:53', '2019-12-12 19:36:53'),
	(85, 10, 8, '2019-12-12 19:37:56', '2019-12-12 19:37:56'),
	(86, 20, 8, '2019-12-12 19:37:56', '2019-12-12 19:37:56'),
	(87, 30, 8, '2019-12-12 19:37:56', '2019-12-12 19:37:56'),
	(88, 40, 8, '2019-12-12 19:37:56', '2019-12-12 19:37:56'),
	(89, 41, 8, '2019-12-12 19:37:56', '2019-12-12 19:37:56'),
	(90, 42, 8, '2019-12-12 19:37:56', '2019-12-12 19:37:56'),
	(91, 53, 8, '2019-12-12 20:07:34', '2019-12-12 20:07:34'),
	(92, 50, 8, '2019-12-12 20:08:09', '2019-12-12 20:08:09'),
	(93, 20, 9, '2019-12-12 21:20:55', '2019-12-12 21:20:55');
/*!40000 ALTER TABLE `permission_role` ENABLE KEYS */;

-- Volcando estructura para tabla help-desk.permission_user
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

-- Volcando datos para la tabla help-desk.permission_user: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `permission_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `permission_user` ENABLE KEYS */;

-- Volcando estructura para tabla help-desk.roles
CREATE TABLE IF NOT EXISTS `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `special` enum('all-access','no-access') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`),
  UNIQUE KEY `roles_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla help-desk.roles: ~4 rows (aproximadamente)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
REPLACE INTO `roles` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`, `special`) VALUES
	(1, 'Super Usuario', 'admin.superusuario', 'Usuario root del sistema..', NULL, '2019-12-12 17:08:09', 'all-access'),
	(7, 'Administrador', 'admin', 'Administrador de HelpDesk', NULL, '2019-12-11 20:59:25', NULL),
	(8, 'Agente', 'agente', 'Agente de Soporte Técnico', NULL, '2019-12-12 20:09:05', NULL),
	(9, 'Usuario', 'usuario', 'Usuario del Sistema Help Desk', NULL, NULL, NULL);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

-- Volcando estructura para tabla help-desk.role_user
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
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla help-desk.role_user: ~5 rows (aproximadamente)
/*!40000 ALTER TABLE `role_user` DISABLE KEYS */;
REPLACE INTO `role_user` (`id`, `role_id`, `user_id`, `created_at`, `updated_at`) VALUES
	(6, 9, 4, NULL, NULL),
	(8, 7, 5, NULL, NULL),
	(11, 1, 1, NULL, NULL),
	(13, 8, 5, '2019-12-12 15:57:04', '2019-12-12 15:57:04'),
	(25, 7, 7, '2019-12-16 13:33:18', '2019-12-16 13:33:18'),
	(27, 8, 8, '2019-12-16 13:34:15', '2019-12-16 13:34:15');
/*!40000 ALTER TABLE `role_user` ENABLE KEYS */;

-- Volcando estructura para tabla help-desk.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lastName` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_Empresa` int(11) DEFAULT NULL,
  `BaseDatos` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `BaseDatosAux` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `Telefono` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cargo` int(11) DEFAULT NULL,
  `idArea` int(11) DEFAULT NULL,
  `idSubArea` int(11) DEFAULT NULL,
  `rol` char(3) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `changePassword` char(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cayro` tinyint(1) DEFAULT NULL,
  `superUser` tinyint(4) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla help-desk.users: ~7 rows (aproximadamente)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
REPLACE INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `lastName`, `id_Empresa`, `BaseDatos`, `BaseDatosAux`, `status`, `Telefono`, `cargo`, `idArea`, `idSubArea`, `rol`, `changePassword`, `photo`, `cayro`, `superUser`, `created_at`, `updated_at`) VALUES
	(1, 'Alexander', 'amarcano568@gmail.com', NULL, '$2y$10$MXpPxoAVoNoC6ecRkLXuwOwDsNKf..YiCLTsVbsPZ0OZuZ4c6n7Yy', '3hg20dWpYsKeFWr7HEWRJq6zc5AiOYPpP7jRznEvrHpK1MFuNKACgIixpQin', 'Marcano A.', 1, 'cayro', '', 1, '931253265', 4, 22, 4, 'adm', NULL, 'Empresas\\cayro\\fotos\\foto-1.jpeg', 0, 1, '2019-11-04 21:51:46', '2019-12-16 20:14:45'),
	(4, 'Luriannys', 'lurisalazar3110@gmail.com', NULL, '$2y$10$TOsSVGHMgvtkvcJ7ToGkX.15jH8zDvPbq.sFU2BFqptUgPC924dI2', NULL, 'Salazar de Marcano', 1, 'cayro', NULL, 1, '973336289', 2, 11, 2, 'usu', 'S', 'Empresas\\cayro\\fotos\\foto-4.jpeg', NULL, NULL, '2019-11-06 22:00:29', '2019-12-04 19:34:38'),
	(5, 'Carlos', 'csalazar@cayro.com.pe', NULL, '$2y$10$Rj/nSYSB/eKi0c/sKgixPe9EADrHHJz6yaSuXJ/lNGxOjWr4bKiwK', NULL, 'Salazar Cuya', 1, 'cayro', NULL, 1, '9999999', 1, 22, 4, 'adm', 'S', NULL, NULL, NULL, '2019-11-07 14:57:37', '2019-11-07 16:41:24'),
	(6, 'Roxana', 'rmorales@cayro.com.pe', NULL, '$2y$10$61MO4owH4acZgaULIkwR0eqgHmb/OgcER/FpO/Y87cVNImLDEIany', NULL, 'Morales', 1, 'cayro', NULL, 1, '9999999', 2, 11, NULL, 'adm', 'S', NULL, NULL, NULL, '2019-11-27 17:23:30', '2019-11-27 17:23:30'),
	(7, 'Juan Enrique', 'jenriquez@bagoperu.com.pe', NULL, '$2y$10$MXpPxoAVoNoC6ecRkLXuwOwDsNKf..YiCLTsVbsPZ0OZuZ4c6n7Yy', NULL, 'Enrique', 2, 'bago', 'bago', 1, '9999999', 4, 22, 4, 'adm', NULL, 'Empresas\\cayro\\fotos\\foto-7.png', 1, NULL, NULL, '2019-12-16 13:33:18'),
	(8, 'Gerson', 'gerson@bagoperu.com.pe', NULL, '$2y$10$UmtNODROij2xcaHduwKyeOJFynS3HRzkd2NoLMvQZe1GDAwW9lMDq', NULL, 'Apellido', 2, 'bago', 'bago', 1, '9999999', 100, 22, 5, 'age', 'S', NULL, 1, NULL, '2019-12-06 14:04:02', '2019-12-16 13:34:15'),
	(52, 'Usuario criocord', 'admin@criocord.com.pe', NULL, '$2y$10$N9hXYjhq1JzHTuIlgqDv1us51B2Rk2z4IOzyEXTH6VTLv5JDQpHMa', NULL, 'criocord', 45, 'criocord', NULL, 1, NULL, NULL, NULL, NULL, 'adm', 'S', NULL, NULL, NULL, '2019-12-16 14:30:21', '2019-12-16 14:30:21');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
