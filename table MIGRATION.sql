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


-- Volcando estructura de base de datos para roles
CREATE DATABASE IF NOT EXISTS `roles` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `roles`;

-- Volcando estructura para tabla roles.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roles.migrations: ~9 rows (aproximadamente)
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(65, '2014_10_12_000000_create_users_table', 1),
	(66, '2014_10_12_100000_create_password_resets_table', 1),
	(67, '2015_01_20_084450_create_roles_table', 1),
	(68, '2015_01_20_084525_create_role_user_table', 1),
	(69, '2015_01_24_080208_create_permissions_table', 1),
	(70, '2015_01_24_080433_create_permission_role_table', 1),
	(71, '2015_12_04_003040_add_special_role_column', 1),
	(72, '2017_10_17_170735_create_permission_user_table', 1),
	(73, '2019_12_10_192340_create_products_table', 1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Volcando estructura para tabla roles.password_resets
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roles.password_resets: ~0 rows (aproximadamente)
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;

-- Volcando estructura para tabla roles.permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roles.permissions: ~14 rows (aproximadamente)
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
REPLACE INTO `permissions` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
	(1, 'Navegar usuarios', 'users.index', 'Lista y Navega todos los Usuarios del Sistema', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(2, 'Ver detalle de usuarios', 'users.show', 'Ver detalle de cada Usuarios del Sistema', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(3, 'Edición de usuarios', 'users.edit', 'Editar cualquier dato de un Usuarios del Sistema', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(4, 'Eliminar usuarios', 'users.destroy', 'Eliminar cualquier Usuarios del Sistema', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(5, 'Navegar roles', 'roles.index', 'Lista y Navega todos los Roles del Sistema', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(6, 'Ver detalle de roles', 'roles.show', 'Ver detalle de cada Roles del Sistema', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(7, 'Creación de rol', 'roles.create', 'Editar cualquier dato de un Roles del Sistema', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(8, 'Edición de rol', 'roles.edit', 'Editar cualquier dato de un Roles del Sistema', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(9, 'Eliminar rol', 'roles.destroy', 'Eliminar cualquier Productos del Sistema', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(10, 'Navegar Productos', 'products.index', 'Lista y Navega todos los Productos del Sistema', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(11, 'Ver detalle de Productos', 'products.show', 'Ver detalle de cada Productos del Sistema', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(12, 'Creación de producto', 'products.create', 'Editar cualquier dato de un Productos del Sistema', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(13, 'Edición de producto', 'products.edit', 'Editar cualquier dato de un Productos del Sistema', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(14, 'Eliminar producto', 'products.destroy', 'Eliminar cualquier Productos del Sistema', '2019-12-10 19:37:40', '2019-12-10 19:37:40');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;

-- Volcando estructura para tabla roles.permission_role
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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roles.permission_role: ~6 rows (aproximadamente)
/*!40000 ALTER TABLE `permission_role` DISABLE KEYS */;
REPLACE INTO `permission_role` (`id`, `permission_id`, `role_id`, `created_at`, `updated_at`) VALUES
	(7, 1, 6, '2019-12-11 16:19:36', '2019-12-11 16:19:36');
/*!40000 ALTER TABLE `permission_role` ENABLE KEYS */;

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

-- Volcando estructura para tabla roles.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roles.products: ~80 rows (aproximadamente)
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
REPLACE INTO `products` (`id`, `name`, `description`, `created_at`, `updated_at`) VALUES
	(4, 'Architecto quo qui id omnis.', 'Molestiae odio laboriosam qui illo fuga.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(7, 'Hola prueba', 'Ut fugiat dicta sunt ullam explicabo.', '2019-12-10 19:37:40', '2019-12-11 14:44:24'),
	(8, 'Soluta eos omnis ut veniam tenetur deserunt laborum.', 'Nobis ab provident aspernatur non natus dolorum.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(11, 'Nulla blanditiis dolore voluptas voluptatum eaque cupiditate tenetur.', 'Architecto eligendi sint distinctio impedit.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(12, 'Et illo minus minima.', 'Dicta nemo tempore ab et.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(15, 'Itaque et qui veniam veritatis.', 'Aperiam sit et aut accusantium ratione quos.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(16, 'Dolorum quis voluptas quasi delectus dolorem.', 'Dolorum aut ut et ea.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(17, 'Earum architecto eum quia et occaecati nam quo sint.', 'Doloremque cum sint molestiae a totam.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(18, 'Nihil rerum perferendis ut laudantium reiciendis fuga eos.', 'Nisi veritatis cumque culpa doloribus.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(19, 'Quo optio deserunt qui et officiis enim eum minus.', 'Cupiditate placeat incidunt reprehenderit voluptatum sed et porro.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(20, 'Neque occaecati aut illum ea et.', 'Animi dolorum ea deserunt.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(21, 'Eum sunt laudantium culpa.', 'Laboriosam provident omnis eum in.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(22, 'Rerum nisi tempora ab amet placeat consequuntur suscipit.', 'Deleniti esse commodi enim voluptatibus id dolorum culpa beatae.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(23, 'Maiores debitis officia odit magnam.', 'Eaque et unde hic dicta quasi consequatur ea sit.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(24, 'Qui praesentium eum ea ut.', 'Quisquam aliquam nihil ducimus voluptatem rerum explicabo sit.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(25, 'Cupiditate quia ducimus voluptatem voluptatem aliquam temporibus nostrum.', 'Molestiae delectus impedit labore sit error hic sit.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(26, 'Expedita et nam aut.', 'Quaerat voluptas eveniet et quae atque aliquid odio.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(27, 'Et repellat nihil deleniti sunt ullam iste.', 'Earum ut repudiandae et debitis voluptatem nemo et.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(28, 'Consequatur aperiam omnis sed natus perspiciatis maxime eum.', 'Alias animi doloribus temporibus veritatis porro dicta quis.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(29, 'Qui consectetur et quis.', 'Saepe debitis laboriosam quasi expedita placeat.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(30, 'Blanditiis est dicta velit quos voluptas qui sit.', 'Illum harum odit dolorem sunt.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(31, 'Voluptatibus numquam ut facilis quibusdam ut molestias.', 'Ipsum consequatur vel ea magni ut.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(32, 'Magni repellendus quisquam rem totam eaque iusto qui.', 'Ducimus consequatur maiores natus voluptas qui.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(34, 'Quo officiis est voluptatem aliquam.', 'Aliquid nisi qui quo alias.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(35, 'Beatae et eveniet minima accusantium repellat quo deleniti.', 'Unde maiores est aliquid autem.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(36, 'Dolorem non nihil recusandae alias odit nihil.', 'Itaque corrupti nihil et earum quia expedita qui.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(37, 'Dolorum rerum omnis labore qui.', 'Aut saepe saepe velit nobis occaecati.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(38, 'Fuga nisi totam voluptas a itaque.', 'Non aut itaque ab a ut aperiam veniam neque.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(39, 'Quia ab at aperiam eos.', 'Tempora praesentium expedita consequatur quo voluptatem sit.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(40, 'Ea minima sed fugit voluptatem rem non.', 'Culpa earum laborum tempore odit ad fuga ipsa.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(41, 'Qui adipisci doloribus assumenda nisi illum dolorum iusto.', 'Id qui voluptatem et ratione sed.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(42, 'Et consequatur nam perspiciatis velit incidunt molestiae.', 'Qui ut ipsam laboriosam qui quaerat.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(43, 'Dolorum quas eos velit consequatur aliquam modi.', 'Facilis aut dolorem animi consequatur aliquam ut tempore.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(44, 'Qui omnis non aut praesentium ea quas asperiores.', 'Quis voluptas quibusdam modi ipsam nemo facere.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(46, 'Velit labore atque non.', 'Corrupti illum aspernatur autem ut voluptas non atque.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(47, 'Perspiciatis culpa et ullam aspernatur.', 'Unde cumque ea quas sint ut dignissimos et est.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(48, 'Repellendus magni occaecati quaerat ea.', 'Alias rerum eius ut quae.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(49, 'Cupiditate nobis molestiae sit reiciendis.', 'Voluptas consequatur hic quibusdam asperiores porro eum voluptas perspiciatis.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(50, 'Id libero animi velit cumque qui.', 'Quasi repellendus expedita dolor deleniti.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(51, 'Et sapiente velit quasi eveniet natus quia.', 'Nemo molestiae voluptatem nisi qui.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(52, 'Suscipit commodi sed vero rerum rem accusantium.', 'Architecto nihil rerum suscipit similique.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(53, 'Eos dignissimos et aut voluptates.', 'Qui architecto perferendis suscipit saepe.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(54, 'Vitae omnis rerum blanditiis cumque consequatur magnam.', 'Magni aut adipisci voluptas aliquam qui.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(55, 'Qui ad temporibus qui rerum nostrum facere ea.', 'Est cupiditate unde ipsam qui.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(56, 'Et ab aut aut amet dolorem sit et.', 'Aut provident dicta odio aut.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(57, 'Incidunt ut doloremque incidunt aspernatur in sit cumque alias.', 'Molestiae possimus numquam nisi magni perspiciatis at aliquam.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(58, 'Omnis sed consequatur corrupti ut expedita eius dolor.', 'Dolorem veniam inventore sunt itaque quia totam.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(59, 'Voluptatem eveniet eligendi itaque velit quia a.', 'At debitis et qui illo.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(60, 'A et quo unde est ipsum.', 'Qui sequi nemo et vel vel.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(61, 'Voluptates non necessitatibus voluptates.', 'Sint earum dolore vitae quibusdam non.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(62, 'Officiis ex id quisquam voluptatem quisquam commodi quam suscipit.', 'Dignissimos aperiam rerum dolores est voluptatem.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(63, 'Ipsum aliquid iste odit rerum vitae.', 'Odit rerum dolores illum natus eum odio exercitationem.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(64, 'Dolorem dicta et et eaque temporibus beatae.', 'Quae quaerat ut laboriosam quod distinctio enim est ab.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(65, 'Quibusdam porro dolorum molestiae ipsum.', 'Quidem sequi beatae aut aperiam hic qui.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(66, 'Repudiandae numquam rem et aut recusandae tenetur quos.', 'Ipsam ut voluptatem nemo necessitatibus voluptatem sapiente eum.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(67, 'Architecto dolor consequatur blanditiis odio sit sit.', 'Voluptatum perferendis aliquam saepe architecto omnis exercitationem voluptatem.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(68, 'Et et blanditiis suscipit iste laborum explicabo.', 'Consequatur possimus adipisci dolores quasi qui qui.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(69, 'Quae voluptatum molestiae aliquam omnis error nemo.', 'Enim aliquid ut ad natus nisi rem eius.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(70, 'Omnis sed necessitatibus necessitatibus non.', 'Beatae magnam autem quia nulla sint.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(71, 'Sed aliquam delectus quae optio perferendis voluptatibus.', 'Tempora reiciendis sit quo enim ipsam eveniet mollitia numquam.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(72, 'Itaque consectetur maiores modi et et harum.', 'Dolore et incidunt ullam totam cupiditate doloremque.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(73, 'Et sed nisi qui blanditiis delectus repellendus soluta.', 'Sit exercitationem assumenda reprehenderit in quia.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(74, 'Sint inventore nesciunt consequatur qui.', 'Et accusantium sunt quia explicabo facilis voluptas.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(75, 'Itaque vel corrupti magnam quia doloremque.', 'Placeat nam est voluptas eos nulla eveniet odit ex.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(76, 'Cupiditate ut aut porro.', 'Molestias ipsum quae eius aspernatur ut et.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(77, 'Enim quia et impedit.', 'Eos vero enim et consequatur iste.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(78, 'Fuga maiores nostrum nisi maiores qui.', 'Aut qui temporibus aut magni laudantium nam expedita quasi.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(79, 'Quas eos tempore molestias voluptas rem sint.', 'Qui ut consectetur quasi illo commodi consequatur quia.', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(80, 'Numquam ut eveniet quaerat reiciendis explicabo sapiente nihil.', 'Provident aut quia doloremque vero eum dolores.', '2019-12-10 19:37:40', '2019-12-10 19:37:40');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;

-- Volcando estructura para tabla roles.roles
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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roles.roles: ~2 rows (aproximadamente)
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
REPLACE INTO `roles` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`, `special`) VALUES
	(1, 'Admin', 'admin', 'Administrador de Roles', '2019-12-10 19:37:40', '2019-12-10 19:37:40', 'all-access'),
	(6, 'Supervisor', 'supervisor.sistemas', 'Supervisor Sistemas', '2019-12-11 16:19:36', '2019-12-11 16:19:36', NULL);
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;

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

-- Volcando estructura para tabla roles.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Volcando datos para la tabla roles.users: ~20 rows (aproximadamente)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
REPLACE INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Mrs. Georgiana Beatty', 'jacobi.enoch@example.org', '2019-12-10 19:37:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'LDvu90hkcX', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(2, 'Arlene Dooley', 'ocollier@example.org', '2019-12-10 19:37:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'LNpxcnkD2K', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(3, 'Miss Haven Anderson IV', 'clakin@example.org', '2019-12-10 19:37:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'yvgpKJnYWc', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(5, 'Viola O\'Reilly', 'samantha81@example.net', '2019-12-10 19:37:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '7KlKbslHeN', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(6, 'Krystal Weissnat', 'timmy.cormier@example.net', '2019-12-10 19:37:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'CYiPi4REn2', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(7, 'Prof. Carol Spencer', 'hettinger.bradford@example.org', '2019-12-10 19:37:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jDTkGTwhNG', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(8, 'Dr. Junius Koepp', 'fcole@example.org', '2019-12-10 19:37:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'oJfy1VUuBs', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(9, 'Mr. Vicente Orn III', 'donny70@example.com', '2019-12-10 19:37:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ie0AF8ZZCP', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(10, 'Prof. Barrett Cremin', 'julia68@example.org', '2019-12-10 19:37:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'DrdDGmo9K8', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(11, 'Mr. Kevon Grimes', 'ephraim.adams@example.com', '2019-12-10 19:37:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dPhF5t3Pfh', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(12, 'Alden Flatley', 'kuphal.easton@example.net', '2019-12-10 19:37:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '7FJEz3VEfB', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(13, 'Aaliyah Boyer I', 'shayna08@example.net', '2019-12-10 19:37:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ao6psmPR6Q', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(14, 'Jamaal Larkin DDS', 'kfisher@example.org', '2019-12-10 19:37:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'KqCQdlxCP5', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(15, 'Dwight Swaniawski', 'soberbrunner@example.net', '2019-12-10 19:37:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'w5iEoPT0e2', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(16, 'Dina Bartoletti', 'ritchie.rachelle@example.com', '2019-12-10 19:37:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '1cPf0tHuSU', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(17, 'Dr. Faustino Gutkowski', 'boris38@example.org', '2019-12-10 19:37:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jb8w806llX', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(18, 'Ms. Madge Pacocha', 'rodger02@example.com', '2019-12-10 19:37:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'uMMDazGOCL', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(19, 'Buford Boyer', 'mable.zulauf@example.com', '2019-12-10 19:37:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'iB7kTntyOj', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(20, 'Lilly Balistreri', 'okautzer@example.com', '2019-12-10 19:37:40', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'pnJfWcZ1nM', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(21, 'Alexander', 'amarcano568@gmail.com', NULL, '$2y$10$mUVirfVp1jo99XzFmNPRFe2ZmTm4P6Zv3NNr2QDSxsRhgAynZieHG', NULL, '2019-12-10 20:15:57', '2019-12-10 20:15:57'),
	(22, 'Usuario prueba', 'prueba@prueba.com', NULL, '$2y$10$rEDgInD.T.UHgQsusvxSeukspeei..M6gGPX/Fh/rNfdUMpASuebm', NULL, '2019-12-11 16:22:49', '2019-12-11 16:22:49');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
