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

-- Volcando datos para la tabla iebz.permissions: ~14 rows (aproximadamente)
DELETE FROM `permissions`;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` (`id`, `name`, `slug`, `description`, `created_at`, `updated_at`) VALUES
	(10, 'Navegar tablero de trabajo', 'dashboard', 'Lista y Navega todos los Usuarios del Sistema', '2019-12-10 19:37:40', '2019-12-10 19:37:40'),
	(20, 'Miembros', 'miembros', 'Mantenimiento de Miembros', NULL, NULL),
	(30, 'Ministerios', 'ministerios', 'Mantenimieno de Ministerios', NULL, NULL),
	(40, 'Relacionar Generos', 'relacionar-generos', 'Asignar genero (Masculino o Femenino) a los miembros.', NULL, NULL),
	(50, 'Menú ', 'menu.reportes', 'Menú de Informes', NULL, NULL),
	(51, 'Informe de Miembros', 'informe-miembros', 'Informe de Miembros', NULL, NULL),
	(52, 'Informe de Ministerios', 'informe-ministerios', 'Informe de Ministerios', NULL, NULL),
	(53, 'Informe de Cumpleañeros', 'report-cumpleanos', 'Informe de Cumpleañeros', NULL, NULL),
	(54, 'Informe de Rango de Edades', 'report-rango-edades', 'Informe de Rango de Edades', NULL, NULL),
	(60, 'Menú Admnistración', 'menu.administracion', 'Menú Admnistración', NULL, NULL),
	(61, 'Mantenimiento de Usuarios', 'mantUsuarios', 'Mantenimiento de Usuarios', NULL, NULL),
	(62, 'Mantenimiento de Roles', 'roles', 'Mantenimiento de Roles', NULL, NULL),
	(63, 'Mantenimiento de Profesiones', 'profesiones', 'Mantenimiento de Profesiones', NULL, NULL),
	(64, 'Mantenimiento de Paises', 'paises', 'Mantenimiento de Paises', NULL, NULL);
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
