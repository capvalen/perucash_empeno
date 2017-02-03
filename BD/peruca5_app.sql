-- phpMyAdmin SQL Dump
-- version 4.0.10.14
-- http://www.phpmyadmin.net
--
-- Servidor: localhost:3306
-- Tiempo de generación: 03-02-2017 a las 11:45:15
-- Versión del servidor: 5.6.34
-- Versión de PHP: 5.6.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `peruca5_app`
--
CREATE DATABASE IF NOT EXISTS `peruca5_app` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `peruca5_app`;

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `contarVencidos`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `contarVencidos`()
    NO SQL
SELECT count(idproducto) as Num
FROM `producto` p inner join Cliente c
on c.idcliente = p.idproducto
where prodactivo = 1 and datediff( prodfechavencimiento , now())<=0$$

DROP PROCEDURE IF EXISTS `encontrarCliente`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `encontrarCliente`(IN `dni` VARCHAR(8))
    NO SQL
SELECT * FROM `Cliente` WHERE clidni = dni$$

DROP PROCEDURE IF EXISTS `insertarProductoNew`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarProductoNew`(IN `cliNomb` VARCHAR(50), IN `cliApelli` VARCHAR(50), IN `cliDirec` VARCHAR(200), IN `dni` VARCHAR(50), IN `email` VARCHAR(50), IN `celular` VARCHAR(50), IN `nomProd` VARCHAR(200), IN `montoentregado` FLOAT, IN `interes` FLOAT, IN `montopagar` FLOAT, IN `fechainicial` DATE, IN `feachavencimiento` DATE, IN `observaciones` TEXT, IN `usuario` INT)
BEGIN
INSERT INTO `peruca5_app`.`Cliente`
(`idCliente`,
`cliApellidos`,
`cliNombres`,
`cliDni`,
`cliDireccion`,
`cliCorreo`,
`cliCelular`)
VALUES
(null,
cliApelli, cliNomb,
dni,
cliDirec,
email,
celular);

set @id=last_insert_id();

INSERT INTO `peruca5_app`.`producto`
(`idProducto`,
`prodNombre`,
`prodMontoEntregado`,
`prodInteres`,
`prodFechaInicial`,
`prodFechaVencimiento`,
`prodObservaciones`,
`prodMontoPagar`,
`idCliente`,
`prodActivo`, `prodFechaRegistro`)
VALUES
(null,
nomProd,
montoentregado,
interes,
fechainicial,
feachavencimiento,
observaciones,
montopagar,
@id,
1, now());

set @prod=last_insert_id();
select @prod;

END$$

DROP PROCEDURE IF EXISTS `insertarProductoSolo`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarProductoSolo`(IN `nomProd` VARCHAR(200), IN `montoentregado` FLOAT, IN `interes` FLOAT, IN `montopagar` FLOAT, IN `fechainicial` DATE, IN `feachavencimiento` DATE, IN `observaciones` TEXT, IN `usuario` INT, IN `idCl` INT)
    NO SQL
BEGIN

INSERT INTO `peruca5_app`.`producto`
(`idProducto`,
`prodNombre`,
`prodMontoEntregado`,
`prodInteres`,
`prodFechaInicial`,
`prodFechaVencimiento`,
`prodObservaciones`,
`prodMontoPagar`,
`idCliente`,
`prodActivo`, `prodFechaRegistro`)
VALUES
(null,
nomProd,
montoentregado,
interes,
fechainicial,
feachavencimiento,
observaciones,
montopagar,
idCl,
1, now());

set @prod=last_insert_id();
select @prod;

END$$

DROP PROCEDURE IF EXISTS `listarProductosPorCliente`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarProductosPorCliente`(in idCli int)
BEGIN
select * from producto
where idcliente =idCli
order by prodFechaRegistro desc;
END$$

DROP PROCEDURE IF EXISTS `listarProductosVencidos`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarProductosVencidos`()
    NO SQL
SELECT idproducto, prodNombre,
prodMontoPagar,
concat(cliapellidos, ', ', clinombres) as propietario,
prodFechaVencimiento
FROM `producto` p inner join Cliente c
on c.idcliente = p.idproducto
where prodactivo = 1 and datediff( prodfechavencimiento , now())<=0$$

DROP PROCEDURE IF EXISTS `solicitarProductoPorId`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `solicitarProductoPorId`(IN `idProd` INT)
BEGIN
select *
from Cliente c inner join producto p
 on c.idcliente = p.idcliente
where idproducto = idProd ;
END$$

DROP PROCEDURE IF EXISTS `ubicarPersonaProductos`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `ubicarPersonaProductos`(IN `campo` TEXT)
BEGIN
select *
from Cliente c 
where concat(lower(cliApellidos), ' ', lower(cliNombres)) like concat('%', campo, '%')
or clidni = campo
;
END$$

DROP PROCEDURE IF EXISTS `updateFinalizarEstado`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `updateFinalizarEstado`(IN `idPro` INT)
    NO SQL
UPDATE `producto` SET `prodActivo`=0  WHERE 
`idProducto` = idPro$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Cliente`
--

DROP TABLE IF EXISTS `Cliente`;
CREATE TABLE IF NOT EXISTS `Cliente` (
  `idCliente` int(11) NOT NULL AUTO_INCREMENT,
  `cliApellidos` varchar(50) NOT NULL,
  `cliNombres` varchar(50) NOT NULL,
  `cliDni` varchar(8) NOT NULL,
  `cliDireccion` varchar(200) NOT NULL,
  `cliCorreo` varchar(50) NOT NULL,
  `cliCelular` varchar(50) NOT NULL,
  PRIMARY KEY (`idCliente`),
  UNIQUE KEY `idCliente` (`idCliente`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `Cliente`
--

INSERT INTO `Cliente` (`idCliente`, `cliApellidos`, `cliNombres`, `cliDni`, `cliDireccion`, `cliCorreo`, `cliCelular`) VALUES
(1, 'Valencia perez', 'Angel', '7345', 'ewq', '', '645'),
(2, 'La rosa sanchez', 'Melisa', '7345', 'ewq', '', '645'),
(3, 'Zuasnabar quispe', 'Clara', 'f55', '22', '', 'dda'),
(4, 'pariona valencia', 'carlos alex', '44475064', 'av hvca 435', '', '205090'),
(5, 'parna', 'delf', '55', 'mk', '', '225'),
(6, 'pardo gomez', 'roberto luis', '45896521', 'av real 421', '', '843261'),
(7, 'pariona', 'valencia', '32475064', 'mmmg', '', '514132'),
(8, 'cardenas pineda', 'mariza', '88015138', 'las retamas 555', 'mcarenasp', '96577778'),
(9, 'huaycuch valenzuela', 'yuri paola', '77704076', 'mpj. union', '', '931374171');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `poder`
--

DROP TABLE IF EXISTS `poder`;
CREATE TABLE IF NOT EXISTS `poder` (
  `idPoder` int(11) NOT NULL AUTO_INCREMENT,
  `Descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`idPoder`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `poder`
--

INSERT INTO `poder` (`idPoder`, `Descripcion`) VALUES
(1, 'Supremo'),
(2, 'Simple mortal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

DROP TABLE IF EXISTS `producto`;
CREATE TABLE IF NOT EXISTS `producto` (
  `idProducto` int(11) NOT NULL AUTO_INCREMENT,
  `prodNombre` varchar(200) NOT NULL,
  `prodMontoEntregado` float NOT NULL,
  `prodInteres` int(11) NOT NULL,
  `prodFechaInicial` date NOT NULL,
  `prodFechaVencimiento` date NOT NULL,
  `prodObservaciones` text NOT NULL,
  `prodMontoPagar` float NOT NULL,
  `idCliente` int(11) NOT NULL,
  `prodActivo` bit(1) NOT NULL,
  `prodFechaRegistro` datetime DEFAULT NULL,
  PRIMARY KEY (`idProducto`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`idProducto`, `prodNombre`, `prodMontoEntregado`, `prodInteres`, `prodFechaInicial`, `prodFechaVencimiento`, `prodObservaciones`, `prodMontoPagar`, `idCliente`, `prodActivo`, `prodFechaRegistro`) VALUES
(1, 'Radio grabadora modelo MG53', 32, 4, '2017-01-26', '2017-01-27', '', 33.28, 1, b'1', '2017-01-26 12:36:40'),
(2, 'Celular Galaxy s5', 32, 4, '2017-01-26', '2017-01-27', '', 33.28, 2, b'1', '2017-01-26 12:43:50'),
(3, 'Vajilla de losa', 44, 4, '2017-01-26', '2017-01-27', '', 45.76, 3, b'1', '2017-01-26 12:44:42'),
(4, 'pack de libros antiguos', 40, 4, '2017-01-26', '2017-01-27', '', 41.6, 4, b'1', '2017-01-26 12:56:49'),
(5, 'Casa de muñecas', 2, 4, '2017-01-26', '2017-01-27', '', 2.08, 5, b'0', '2017-01-26 13:29:29'),
(6, 'computadora intel core i5', 600, 4, '2017-01-26', '2017-01-30', '', 624, 6, b'1', '2017-01-26 14:04:22'),
(7, 'producto 1', 11, 4, '2017-01-30', '2017-02-05', '', 11.44, 7, b'1', '2017-01-30 10:57:15'),
(8, 'caja de herramientas', 10, 4, '2017-02-01', '2017-02-02', '', 10.4, 4, b'0', '2017-02-01 18:00:40'),
(9, 'monitor led 42"', 100, 4, '2017-02-01', '2017-02-02', 'sin cargador', 104, 4, b'1', '2017-02-01 20:40:19'),
(10, 'televisor', 500, 4, '2017-02-01', '2017-02-20', 'dejo pantalla dañada', 560, 8, b'1', '2017-02-01 20:51:42'),
(11, 'celular motorola ......', 70, 4, '2017-02-02', '2017-02-09', 'pantalla rota', 72.8, 9, b'1', '2017-02-02 14:33:29'),
(12, 'caja 1', 100, 4, '2017-02-03', '2017-03-10', '', 120, 4, b'1', '2017-02-03 11:02:31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `idUsuario` int(11) NOT NULL AUTO_INCREMENT,
  `usuNombres` varchar(100) NOT NULL,
  `usuApellido` varchar(100) NOT NULL,
  `usuNick` varchar(50) NOT NULL,
  `usuPass` varchar(100) NOT NULL,
  `usuPoder` int(11) NOT NULL,
  `usuActivo` bit(1) NOT NULL,
  PRIMARY KEY (`idUsuario`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `usuNombres`, `usuApellido`, `usuNick`, `usuPass`, `usuPoder`, `usuActivo`) VALUES
(1, 'Carlos Alex', 'Pariona Valencia', 'cpariona', 'b84d8185d9fc5d64de366cc8a06d8ef1', 1, b'1'),
(2, 'Sucursal', '01', 'sucursal1', '93585797569d208d914078d513c8c55a', 2, b'1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
