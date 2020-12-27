-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 27-12-2020 a las 23:25:42
-- Versión del servidor: 5.7.31
-- Versión de PHP: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `todo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `todo`
--

DROP TABLE IF EXISTS `todo`;
CREATE TABLE IF NOT EXISTS `todo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  `fecha_creacion` datetime NOT NULL,
  `fecha_tope` datetime DEFAULT NULL,
  `estado` enum('INICIADA','FINALIZADA','SIN EMPEZAR') COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5A0EB6A0A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `todo`
--

INSERT INTO `todo` (`id`, `nombre`, `fecha_creacion`, `fecha_tope`, `estado`, `user_id`) VALUES
(60, 'Hacer la compra', '2020-12-27 10:17:10', '0202-12-24 12:18:00', 'INICIADA', 1),
(62, 'Limpiar el coche', '2020-12-27 23:00:02', '0202-12-25 13:59:00', 'SIN EMPEZAR', 1),
(63, 'Arreglar el jardin', '2020-12-27 23:00:50', '0202-12-26 02:00:00', 'SIN EMPEZAR', 1),
(64, 'Salir a correr', '2020-12-27 23:01:40', '0202-12-27 16:39:00', 'FINALIZADA', 1),
(65, 'Salir a correr', '2020-12-27 23:06:21', '0202-12-25 05:11:00', 'SIN EMPEZAR', 25),
(66, 'Ir al cine', '2020-12-27 23:06:47', '0202-12-28 05:11:00', 'SIN EMPEZAR', 25),
(67, 'Leer el quijote', '2020-12-27 23:08:29', '0202-12-30 20:15:00', 'SIN EMPEZAR', 25),
(68, 'Recoger la habitacion', '2020-12-27 23:14:19', '0202-12-30 02:16:00', 'SIN EMPEZAR', 27),
(69, 'Ir al super', '2020-12-27 23:23:56', '0202-12-27 01:25:00', 'INICIADA', 1),
(70, 'Salir de compras', '2020-12-27 23:24:37', '0202-12-28 05:27:00', 'INICIADA', 27);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `apellidos` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `nombre` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `fechaAlta` datetime NOT NULL,
  `roles` enum('ROLE_USER','ROLE_ADMIN') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_2DA17977F85E0677` (`username`),
  UNIQUE KEY `UNIQ_2DA17977E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `username`, `apellidos`, `email`, `nombre`, `password`, `fechaAlta`, `roles`) VALUES
(1, 'admin', 'Delgado', 'jdelgado@gmail.com', 'Juanjo', '21232f297a57a5a743894a0e4a801fc3', '2020-12-22 16:36:16', 'ROLE_ADMIN'),
(25, 'user2', 'Fernandez', 'marta@gmail.com', 'Marta', '1a1dc91c907325c69271ddf0c944bc72', '2020-12-27 09:56:46', 'ROLE_USER'),
(27, 'user1', 'Ruiz', 'pedro@gmail.com', 'Pedro', '1a1dc91c907325c69271ddf0c944bc72', '2020-12-27 10:06:47', 'ROLE_USER');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `todo`
--
ALTER TABLE `todo`
  ADD CONSTRAINT `FK_5A0EB6A0A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
