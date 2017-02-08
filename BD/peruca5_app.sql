-- phpMyAdmin SQL Dump
-- version 4.0.10.18
-- https://www.phpmyadmin.net
--
-- Servidor: localhost:3306
-- Tiempo de generación: 08-02-2017 a las 13:54:59
-- Versión del servidor: 5.6.35
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
`prodActivo`, `prodFechaRegistro`, `idUsuario` )
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
1, now(), usuario);

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
`prodActivo`, `prodFechaRegistro`, `IdUsuario` )
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
1, now(), usuario);

set @prod=last_insert_id();
select @prod;

END$$

DROP PROCEDURE IF EXISTS `insertarUsuario`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarUsuario`(IN `nombre` VARCHAR(50), IN `apellido` VARCHAR(50), IN `nick` VARCHAR(50), IN `pass` VARCHAR(50), IN `poder` INT, IN `idSucur` INT)
    NO SQL
INSERT INTO `usuario`(`idUsuario`, `usuNombres`, `usuApellido`,
                      `usuNick`, `usuPass`, `usuPoder`,
                      `idSucursal`, `usuActivo`) 
VALUES (null,apellido,nombre,nick,md5(pass),poder,idSucur,1)$$

DROP PROCEDURE IF EXISTS `listarProductosPorCliente`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarProductosPorCliente`(IN `idCli` INT)
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
where prodactivo = 1 and datediff( prodfechavencimiento , now())<=0
order by prodfechavencimiento desc$$

DROP PROCEDURE IF EXISTS `listarTodosProductosNoFinalizados`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarTodosProductosNoFinalizados`()
    NO SQL
SELECT c.*, p.*, u.usuNombres FROM `producto` p
inner join Cliente c
on p.idcliente = c.idcliente
inner join usuario u
on p.idusuario = u.idusuario
where prodactivo =1
order by c.cliApellidos asc$$

DROP PROCEDURE IF EXISTS `listarTodosUsuarios`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarTodosUsuarios`()
    NO SQL
SELECT u.`idUsuario`, concat( `usuNombres`, ' ',  `usuApellido` ) as nombre, p.`descripcion`,  sucLugar 
FROM `usuario` u inner join sucursal s
on u.`idSucursal`= s.`idSucursal`
inner join poder p on p.idPoder=usuPoder
WHERE `usuActivo`=1$$

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Volcado de datos para la tabla `Cliente`
--

INSERT INTO `Cliente` (`idCliente`, `cliApellidos`, `cliNombres`, `cliDni`, `cliDireccion`, `cliCorreo`, `cliCelular`) VALUES
(1, 'madrid vargas', 'luis fernando', '48211897', 'jr. san martin #247-huancayo', 'vanslove15@gmail.com', '934958237 '),
(2, 'milagros sarapura', 'sharon jenifer', '46573028', 'calle apata s/n - matahuasi (paradero muruhuay)', 'sharonsahe_10@hotmail.com', '948003747'),
(3, 'najera daza', 'raul alex', '47800688', 'jr. sausa rasa 1357 - el tambo', '', '944843829'),
(4, 'quispe berrocal', 'miguel', '48583422', 'av. daniel a. carrion 1337-hyo', 'mqberrocal@gmail.com', '995111142'),
(5, 'ycomedes echevarria', 'carlos', '45920195', 'jr. los bosques #440', 'carlos7lim@gmail.com', '956657898'),
(6, 'yancan canchanya', 'katerin', '44594241', 'jose olaya #1635 jPV', '', '961925224'),
(7, 'sedano sanchez', 'jhonnatan', '47508900', 'av. cordova, jr. progreso (chilca)', 'jlegendarios@gmail.com', '064-432241'),
(8, 'ninanya martel ', 'luigi harold', '71097809', 'jr. pachacutec #310 -  el tambo', 'luigi_sak07@hotmail.com', '939409382'),
(9, 'torres cardenas', 'elizabeth clarisa', '40771830', 'jr. hermilio valdizan', 'eliza-tc2406@hotmail.com', '941996175'),
(10, 'ortiz freyre', 'miguel angel', '19911874', 'jr. moquegua #1360 - el tambo', '', '988223369'),
(11, 'palomino gonzales ', 'milagros stefany', '76420947', 'prog. arequipa #993 - chilca', 'milili-11@hotmail.com', '964097073'),
(12, 'rivera menendez', 'jose miguel', '44370823', 'jr. hipolito unanue s/n', 'jose-123rivera@hotmail.com', '941430582'),
(13, 'hinostroza morales', 'moises', '44146810', 'av. los obreros #714-jPV', 'vithmor-eirl@hotmail.com', '984577740'),
(14, 'ames miranda ', 'cesar franshesco', '47585699', 'jr. 28 de julio #229-chilca', 'famesuncofim@gmail.com', '939797541'),
(15, 'peña palomino ', 'gianfranco', '75381486', 'prol. mariategui #328-el tambo', '75381486@continental.com.pe', '945929282'),
(16, 'castillo gaspar', 'jhover carlos', '73197213', 'jr. lima #2509-yauris', '', '998448261'),
(17, 'soto paredes', 'yomira gianella', '75316856', 'jr. san isidro #125- el tambo', 'solita_27_glo@hotmail.com', '979080363'),
(18, 'perez romero', 'michael', '46512606', 'pasaje 2 de mayo #114-chilca', 'michael910mnuc@hotmail.com', '988625355');

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
(1, 'Todopoderoso'),
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
  `idUsuario` int(11) NOT NULL,
  PRIMARY KEY (`idProducto`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`idProducto`, `prodNombre`, `prodMontoEntregado`, `prodInteres`, `prodFechaInicial`, `prodFechaVencimiento`, `prodObservaciones`, `prodMontoPagar`, `idCliente`, `prodActivo`, `prodFechaRegistro`, `idUsuario`) VALUES
(1, 'tripode (hansa)', 50, 4, '2017-01-07', '2017-02-07', '', 60, 1, b'1', '2017-02-06 12:29:21', 2),
(2, 'camara panasonic con cargador + calular huawei cun-lo3', 200, 4, '2017-01-13', '2017-02-07', '', 232, 2, b'1', '2017-02-06 12:42:39', 2),
(3, 'laptop hp 455', 130, 4, '2017-01-18', '2017-02-18', '', 156, 3, b'1', '2017-02-06 13:00:15', 2),
(4, 'monitor led de 20" + aspiradora vlast pro', 300, 4, '2017-01-18', '2017-02-18', '', 360, 4, b'1', '2017-02-06 13:04:22', 2),
(5, 'monitor lG flatron 17"', 60, 4, '2017-01-19', '2017-02-19', '', 72, 5, b'1', '2017-02-06 13:08:30', 2),
(6, 'cocina 5 hornillas bosh + blueray philips ', 500, 4, '2017-01-19', '2017-02-19', '', 600, 6, b'1', '2017-02-06 13:26:03', 2),
(7, 'laptop hp pavilion ', 350, 4, '2017-01-20', '2017-02-20', '', 420, 7, b'1', '2017-02-06 13:30:46', 2),
(8, 'back-ups cs 650 apc', 50, 4, '2017-01-23', '2017-02-23', 'transformador blanco', 60, 8, b'1', '2017-02-06 13:36:28', 2),
(9, 'cubiertos rena ware + cortinas', 400, 4, '2017-01-26', '2017-02-26', '26 cubiertos ', 480, 9, b'1', '2017-02-06 13:45:32', 2),
(10, 'celular huawei g6 l33', 80, 4, '2017-01-31', '2017-02-28', '', 92.8, 10, b'1', '2017-02-06 13:52:49', 2),
(11, 'laptop lenovo b50-80', 500, 4, '2017-02-03', '2017-03-03', 'nuevo+cargador+boleta (en caja)', 580, 11, b'1', '2017-02-06 13:56:58', 2),
(12, 'celular lg g3 + dvd', 150, 4, '2017-02-06', '2017-03-06', '', 174, 12, b'1', '2017-02-06 14:03:55', 2),
(13, 'reloj casio 5468', 100, 4, '2017-01-10', '2017-02-10', 'en caja', 120, 13, b'0', '2017-02-06 14:19:15', 2),
(14, 'horno microondas color negro', 105, 4, '2017-01-11', '2017-02-11', '', 126, 14, b'1', '2017-02-06 14:38:37', 2),
(15, 'camara digital finefix ', 30, 4, '2017-02-07', '2017-03-07', 'con memoria 8gb ', 34.8, 15, b'1', '2017-02-07 17:46:08', 2),
(16, 'licuadora taurus + celular huawey', 100, 4, '2017-02-07', '2017-03-07', '', 116, 16, b'1', '2017-02-07 17:51:17', 2),
(17, 'laptop dell inspiracion 14 serie 3000', 400, 4, '2017-02-07', '2017-03-06', 'funda+cargador+mause', 464, 17, b'1', '2017-02-07 17:54:08', 2),
(18, '2 incubadoras automaticas', 170, 4, '2017-02-07', '2017-03-07', '', 197.2, 18, b'1', '2017-02-07 17:57:25', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

DROP TABLE IF EXISTS `sucursal`;
CREATE TABLE IF NOT EXISTS `sucursal` (
  `idSucursal` int(11) NOT NULL AUTO_INCREMENT,
  `sucNombre` varchar(50) NOT NULL,
  `sucLugar` varchar(200) NOT NULL,
  PRIMARY KEY (`idSucursal`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`idSucursal`, `sucNombre`, `sucLugar`) VALUES
(1, 'Sucursal 1', 'Las Retamas');

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
  `idSucursal` int(11) NOT NULL,
  `usuActivo` bit(1) NOT NULL,
  PRIMARY KEY (`idUsuario`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `usuNombres`, `usuApellido`, `usuNick`, `usuPass`, `usuPoder`, `idSucursal`, `usuActivo`) VALUES
(1, 'Carlos Alex', 'Pariona Valencia', 'cpariona', 'b84d8185d9fc5d64de366cc8a06d8ef1', 1, 1, b'1'),
(2, 'Yuri Paola', 'Huaycuch Valenzuela', 'sucursal1', '93585797569d208d914078d513c8c55a', 2, 1, b'1'),
(3, 'Yuri Paola', 'Huaycuch Valenzuela', 'yhuaycuch', '93585797569d208d914078d513c8c55a', 2, 1, b'1'),
(4, 'bela', 'nova', 'nbela', 'c4ca4238a0b923820dcc509a6f75849b', 1, 1, b'1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
