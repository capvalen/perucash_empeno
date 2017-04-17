-- phpMyAdmin SQL Dump
-- version 4.0.10.18
-- https://www.phpmyadmin.net
--
-- Servidor: localhost:3306
-- Tiempo de generación: 24-02-2017 a las 13:03:52
-- Versión del servidor: 5.6.35
-- Versión de PHP: 5.6.30

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
DROP PROCEDURE IF EXISTS `actualizarCliente`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `actualizarCliente`(idCliente int,paterno text, materno text, nombre text, fecha date, civil int, sexo text,
ocupacion int,direccion text, telefono text, celular text, procedencia int, in usuario int,in grado int)
BEGIN
UPDATE `cliente`
SET
`cliApellidoPaterno` = UPPER(paterno),
`cliApellidoMaterno` = UPPER(materno),
`cliNombres` = UPPER(nombre),
`cliFechaNacimiento` = fecha,
`idEstadoCivil` = civil,
`cliSexo` = sexo,
`idOcupacion` = ocupacion,
`cliDireccion` = UPPER(direccion),
`cliTelefono` = telefono,
`cliCelular` = celular,
`idProcedencia` = procedencia,
`idGradoEstudios` = grado
WHERE `cliente`.`idCliente` = idCliente;

INSERT INTO `historialcambios`
(`idCliente`,`cambFecha`,idUsuario,idTipoCambio)
VALUES
(idCliente,
now(),idUsuario,6);
end$$

DROP PROCEDURE IF EXISTS `contarVencidos`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `contarVencidos`(IN `idSuc` INT)
    NO SQL
SELECT count(idproducto) as Num
FROM `producto` p 
where prodactivo = 1 and datediff( prodfechavencimiento , now())<=0
and idSucursal =idSuc$$

DROP PROCEDURE IF EXISTS `encontrarCliente`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `encontrarCliente`(IN `dni` VARCHAR(8))
    NO SQL
SELECT * FROM `Cliente` WHERE clidni = dni$$

DROP PROCEDURE IF EXISTS `insertarAdelantoAProducto`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarAdelantoAProducto`(IN `idProd` INT, IN `nuevoAdelanto` FLOAT)
    NO SQL
begin
UPDATE `producto` SET 
`prodObservaciones` =concat('Se adelantó S/. ',round(nuevoAdelanto,2),' de S/. ' , round(`prodMontoEntregado`,2), ' el día ', DATE_FORMAT(now(), "%d/%m/%Y"), '<br>', `prodObservaciones`),
`prodFechaInicial`=now(),
`prodMontoEntregado`=`prodMontoEntregado`-nuevoAdelanto

WHERE 
`idProducto`= idProd;
end$$

DROP PROCEDURE IF EXISTS `insertarProductoNew`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarProductoNew`(IN `cliNomb` VARCHAR(50), IN `cliApelli` VARCHAR(50), IN `cliDirec` VARCHAR(200), IN `dni` VARCHAR(50), IN `email` VARCHAR(50), IN `celular` VARCHAR(50), IN `nomProd` VARCHAR(200), IN `montoentregado` FLOAT, IN `interes` FLOAT, IN `montopagar` FLOAT, IN `fechainicial` DATE, IN `feachavencimiento` DATE, IN `observaciones` TEXT, IN `usuario` INT, IN `idSuc` INT)
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
`prodActivo`, `prodFechaRegistro`, `idUsuario`, `idSucursal` )
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
1, now(), usuario, idSuc);

set @prod=last_insert_id();
select @prod;

END$$

DROP PROCEDURE IF EXISTS `insertarProductoSolo`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarProductoSolo`(IN `nomProd` VARCHAR(200), IN `montoentregado` FLOAT, IN `interes` FLOAT, IN `montopagar` FLOAT, IN `fechainicial` DATE, IN `feachavencimiento` DATE, IN `observaciones` TEXT, IN `usuario` INT, IN `idCl` INT, IN `idSuc` INT)
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
`prodActivo`, `prodFechaRegistro`, `IdUsuario`, `idSucursal` )
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
1, now(), usuario, idSuc);

set @prod=last_insert_id();
select @prod;

END$$

DROP PROCEDURE IF EXISTS `insertarSucursalNueva`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarSucursalNueva`(IN `sucNom` VARCHAR(200), IN `sucLug` VARCHAR(200))
    NO SQL
INSERT INTO `sucursal`(`idSucursal`, `sucNombre`, `sucLugar`)
VALUES (null ,sucNom,sucLug)$$

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
select p.*, s.sucNombre from producto p
inner join sucursal s on p.idSucursal= s.idSucursal
where idcliente =idCli
order by prodFechaRegistro desc;
END$$

DROP PROCEDURE IF EXISTS `listarProductosVencidos`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarProductosVencidos`(IN `idSuc` INT)
    NO SQL
SELECT idproducto, prodNombre,
prodMontoEntregado,
concat(cliapellidos, ', ', clinombres) as propietario,
prodFechaVencimiento
FROM `producto` p inner join Cliente c
on c.idcliente = p.idcliente
where prodactivo = 1 and datediff( prodfechavencimiento , now())<=0
and p.idSucursal = idSuc
order by prodfechavencimiento desc$$

DROP PROCEDURE IF EXISTS `listarTodosProductosNoFinalizados`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarTodosProductosNoFinalizados`(IN `idSuc` INT)
    NO SQL
SELECT c.*, p.*, u.usuNombres FROM `producto` p
inner join Cliente c
on p.idcliente = c.idcliente
inner join usuario u
on p.idusuario = u.idusuario
where prodactivo =1 and p.idSucursal = idSuc
order by prodfechainicial asc$$

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
select c.*, p.*, s.sucNombre
from Cliente c inner join producto p on c.idcliente = p.idcliente
inner join sucursal s on p.idSucursal= s.idSucursal
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

DROP PROCEDURE IF EXISTS `updateFinalizarSucursal`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `updateFinalizarSucursal`(IN `idSuc` INT)
    NO SQL
UPDATE `sucursal` SET `sucActivo`=0 WHERE idSucursal =idSuc$$

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=71 ;

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
(18, 'perez romero', 'michael', '46512606', 'pasaje 2 de mayo #114-chilca', 'michael910mnuc@hotmail.com', '988625355'),
(19, 'aquino lazo', 'brian marco', '71984964', 'calle los quinuales mz. r lt 03 urb. los jardines de san carlos', '', '991455490'),
(20, 'ricse alvarado', 'maycol jhonathan ', '48407935', 'av. huancavelica #3082- el tambo', 'maycol_jra@hotmail.com', '949487983'),
(21, 'cabrera pozo', 'Lizbeth Ruth', '11111111', 'calle', '', '999999999'),
(22, 'rodriguez quincho ', 'jesus', '1111112', 'calle1', '', '999999998'),
(50, 'chucos calixto', 'arturo luis', '11111139', 'c29', '', '996001136'),
(25, 'hidalgo quiroz', 'pablo joel', '11111115', 'c5', '', '999999997'),
(26, 'cardenas pampas', 'geovana', '11111116', 'c6', '', '999999994'),
(27, 'delgado reyes', 'brihan', '11111117', 'c6', '', '999999993'),
(28, 'ruiz curi', 'brayan', '11111118', 'c7', '', '999999992'),
(29, 'cusipoma inga', 'guillermo', '11111119', 'c7', '', '999999991'),
(30, 'alva loayza ', 'manuel alejandro', '11111110', 'c9', '', '999999999'),
(31, 'suarez calcin', 'brihan', '11111120', 'c10', '', '999999999'),
(32, 'velasque gago', 'elvis', '11111121', 'c11', '', '999999999'),
(33, 'vega lino', 'joel', '11111122', 'c12', '', '999999996'),
(34, 'saenz rashuaman', 'luis', '11111123', 'c13', '', '999999996'),
(35, 'cano cusi', 'noe', '11111124', 'c14', '', '964690195'),
(36, 'paredes jilaja', 'juan carlos', '11111125', 'c15', '', '950761767'),
(37, 'rodriguez tananta', 'laura', '11111126', 'c16', '', '921293394'),
(49, 'calle campian', 'adolfo alejandro', '11111138', 'c28', '', '986773600'),
(39, 'javier aliaga', 'diana carolina', '11111128', 'c18', '', '931033865'),
(40, 'caparachin aguilar', 'eduardo', '11111129', 'c19', '', '972097669'),
(41, 'bravo tarazona', 'nino albertho', '11111130', 'c20', '', '999999996'),
(42, 'mendoza sotomayor', 'miguel enrrique', '11111131', 'c21', '', '999999996'),
(43, 'curimania puente', 'deysi rocio', '11111132', 'c22', '', '950187119'),
(44, 'espinoza oregon', 'marco antonio', '11111133', 'c23', '', '939710370'),
(45, 'cabrera perez  ', 'cristian jossian', '11111134', 'c24', '', '999999996'),
(46, 'palomas parac ', 'flori ', '11111135', 'c25', '', '978586133'),
(47, 'mendoza castro', 'aldair', '11111136', 'c26', '', '999999996'),
(48, 'gomez cairo ', 'saledad', '45154227', 'jr. tumi #215- el tambo', '', '990008789'),
(51, 'zuñiga', 'miguel', '11111140', 'c30', '', '999999996'),
(52, 'reyes sauñi', 'lucia', '45798915', 'san martin#193- palian', '', '933080901'),
(53, 'palomino basilio ', 'marco antonio', '45797542', 'prog calixto #697-hyo', '', '950950537'),
(54, 'romero aliaga', 'juro dan', '14253600', 'm.', '', '1'),
(55, 'rivera rojas', 'enrique phileas', '46162078', 'pj. los husares #135-el tambo', '', '970575516'),
(56, 'balbin barrera', 'diestefano jj romario', '71695974', 'prol. julio llanos #145 sector 20', '', '955939828'),
(57, 'Anccasi paucar', 'clisman mayer', '72675890', 'av. evitamiento y ferrocarril  #825- el tambo', '', '942643000'),
(58, 'trujillo flores', 'roy edgar', '42272948', 'pj giraldes s/n- palian (parque)', '', '920453873'),
(59, 'gaytan huaynalaya', 'kevin david', '73983699', 'jr. progreso # 345 ', '', '939138603'),
(60, 'santana santivañez', 'rocio Del Pilar', '42445125', 'jr. raul porras barrenechea #173- el tambo', '', '979097036'),
(61, 'Manrique', 'Giordan', '72235379', 'Jr Retamas', 'giordan@gmail.com', '986986403'),
(62, 'perez guinea', 'hilda', '19868845', 'jr. agusto b leguia #1171 -chilca', '', '983658558'),
(63, 'meza janampa', 'gian marco', '76067543', 'nicolas  alcazar lt 20 (col. ramiro)-hyo', '', '975924917'),
(64, 'ilizarbe bendezu', 'moises alejandro', '19823558', 'pje. mariscal caceres # 155- piopata', '', '923293150'),
(65, 'cordova campos', ' Jose Luis', '47362090', 'av. palian #645-hyo', '', '991483443'),
(66, 'paz castellares', 'paul vladimir', '44724568', 'av. huancavelica #153 -hyo', '', '971710120'),
(67, 'calla bruno', 'juan carlos', '41660785', 'jr. alan garcia #241- el tambo', '', '962216786'),
(68, 'egoavil arriola', 'jesus miguel', '43715671', 'av. la esperanza #128- chilca', '', '943890037'),
(69, 'arana peña', 'alberto', '20015135', 'grau #1062- el tambo', '', '964009483'),
(70, 'huaynate meza', 'jhosy joel', '45848360', 'jr. manuel scorza #468- el tambo', '', '938198669');

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
(1, 'Director operativo'),
(2, 'Colaborador administrativo');

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
  `prodAdelanto` float NOT NULL DEFAULT '0',
  `idCliente` int(11) NOT NULL,
  `prodActivo` bit(1) NOT NULL,
  `prodFechaRegistro` datetime DEFAULT NULL,
  `idUsuario` int(11) NOT NULL,
  `idSucursal` int(11) NOT NULL,
  PRIMARY KEY (`idProducto`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=76 ;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`idProducto`, `prodNombre`, `prodMontoEntregado`, `prodInteres`, `prodFechaInicial`, `prodFechaVencimiento`, `prodObservaciones`, `prodMontoPagar`, `prodAdelanto`, `idCliente`, `prodActivo`, `prodFechaRegistro`, `idUsuario`, `idSucursal`) VALUES
(1, 'tripode (hansa)', 50, 4, '2017-01-07', '2017-02-07', '', 60, 0, 1, b'1', '2017-02-06 12:29:21', 2, 1),
(2, 'camara panasonic con cargador + calular huawei cun-lo3', 200, 4, '2017-01-13', '2017-02-07', '', 232, 0, 2, b'1', '2017-02-06 12:42:39', 2, 1),
(3, 'laptop hp 455', 26, 4, '2017-02-16', '2017-02-18', 'Se adelantó S/. 20.80 de S/. 46.80 el día 16/02/2017<br>Se adelantó S/. 20.80 de S/. 67.60 el día 16/02/2017<br>Se adelantó S/. 20.80 de S/. 88.40 el día 16/02/2017<br>Se adelantó S/. 20.80 de S/. 109.20 el día 16/02/2017<br>Se adelantó S/. 20.80 de S/. 130.00 el día 16/02/2017<br>', 156, 0, 3, b'1', '2017-02-06 13:00:15', 2, 1),
(4, 'monitor led de 20" + aspiradora vlast pro', 252, 4, '2017-02-23', '2017-02-18', 'Se adelantó S/. 48.00 de S/. 300.00 el día 23/02/2017<br>', 360, 0, 4, b'1', '2017-02-06 13:04:22', 2, 1),
(5, 'monitor lG flatron 17"', 60, 4, '2017-01-19', '2017-02-19', '', 72, 0, 5, b'1', '2017-02-06 13:08:30', 2, 1),
(6, 'cocina 5 hornillas bosh + blueray philips ', 500, 4, '2017-01-19', '2017-02-19', '', 600, 0, 6, b'1', '2017-02-06 13:26:03', 2, 1),
(7, 'laptop hp pavilion ', 350, 4, '2017-01-20', '2017-02-20', '', 420, 0, 7, b'1', '2017-02-06 13:30:46', 2, 1),
(8, 'back-ups cs 650 apc', 48, 4, '2017-02-24', '2017-02-23', 'Se adelantó S/. 2.00 de S/. 50.00 el día 24/02/2017<br>transformador blanco', 60, 0, 8, b'1', '2017-02-06 13:36:28', 2, 1),
(9, 'cubiertos rena ware + cortinas', 400, 4, '2017-01-26', '2017-02-26', '26 cubiertos ', 480, 0, 9, b'1', '2017-02-06 13:45:32', 2, 1),
(10, 'celular huawei g6 l33', 80, 4, '2017-01-31', '2017-02-28', '', 92.8, 0, 10, b'1', '2017-02-06 13:52:49', 2, 1),
(11, 'laptop lenovo b50-80', 500, 4, '2017-02-03', '2017-03-03', 'nuevo+cargador+boleta (en caja)', 580, 0, 11, b'1', '2017-02-06 13:56:58', 2, 1),
(12, 'celular lg g3 + dvd', 150, 4, '2017-02-06', '2017-03-06', '', 174, 0, 12, b'0', '2017-02-06 14:03:55', 2, 1),
(13, 'reloj casio 5468', 100, 4, '2017-01-10', '2017-02-10', 'en caja', 120, 0, 13, b'0', '2017-02-06 14:19:15', 2, 1),
(14, 'horno microondas color negro', 105, 4, '2017-01-11', '2017-02-11', '', 126, 0, 14, b'0', '2017-02-06 14:38:37', 2, 1),
(15, 'camara digital finefix ', 30, 4, '2017-02-07', '2017-03-07', 'con memoria 8gb ', 34.8, 0, 15, b'1', '2017-02-07 17:46:08', 2, 1),
(16, 'licuadora taurus + celular huawey', 100, 4, '2017-02-07', '2017-03-07', '', 116, 0, 16, b'1', '2017-02-07 17:51:17', 2, 1),
(17, 'laptop dell inspiracion 14 serie 3000', 400, 4, '2017-02-07', '2017-03-06', 'funda+cargador+mause', 464, 0, 17, b'1', '2017-02-07 17:54:08', 2, 1),
(18, '2 incubadoras automaticas', 170, 4, '2017-02-07', '2017-03-07', '', 197.2, 0, 18, b'1', '2017-02-07 17:57:25', 2, 1),
(19, 'frigobar daewoo color negro', 200, 4, '2017-02-08', '2017-03-08', '', 232, 0, 19, b'1', '2017-02-08 15:20:29', 2, 1),
(20, 'laptop acer', 200, 4, '2017-02-09', '2017-03-09', 'le faltan tres teclas ', 232, 0, 20, b'1', '2017-02-09 16:39:09', 2, 1),
(21, 'celular huawei ascend g7 blanco', 80, 4, '2017-02-09', '2017-03-09', 'pantalla rota ', 92.8, 0, 15, b'1', '2017-02-09 18:34:00', 2, 1),
(22, 'bluray sony', 450, 4, '2016-09-05', '2016-10-05', 'laptop hpi3 con cargador (vendido)', 540, 0, 21, b'1', '2017-02-09 19:12:54', 2, 1),
(23, 'camara profesional canon', 700, 4, '2016-10-28', '2016-11-28', '', 840, 0, 22, b'1', '2017-02-09 19:16:56', 2, 1),
(51, 'olla renaware', 300, 4, '2016-09-30', '2016-10-30', '', 360, 0, 50, b'0', '2017-02-10 16:27:41', 2, 1),
(26, 'calculadora hp', 80, 4, '2016-10-04', '2016-11-04', '', 96, 0, 25, b'1', '2017-02-09 19:30:32', 2, 1),
(27, '2 mochilas', 590, 4, '2016-10-11', '2016-11-11', 'hom dvd+camara+laptop', 708, 0, 26, b'1', '2017-02-09 19:33:28', 2, 1),
(28, 'equipo de sonido+monitor+blueray+computador i5', 520, 4, '2016-10-13', '2016-11-13', '', 624, 0, 27, b'1', '2017-02-09 19:35:55', 2, 1),
(29, 'laptop hp', 200, 4, '2016-10-25', '2016-11-25', '', 240, 0, 28, b'1', '2017-02-09 19:41:13', 2, 1),
(30, 'equipo de sonido lg', 200, 4, '2016-10-26', '2016-11-26', '', 240, 0, 29, b'1', '2017-02-09 19:43:04', 2, 1),
(31, 'plancha +equipo', 200, 4, '2016-11-11', '2016-12-11', '', 240, 0, 30, b'1', '2017-02-09 19:46:47', 2, 1),
(32, 'cpu intel i3+monitor advance', 200, 4, '2016-11-14', '2016-12-14', '', 240, 0, 31, b'1', '2017-02-09 19:50:07', 2, 1),
(33, 'laptop i7+impresora hp nueva', 1000, 4, '2016-11-15', '2016-12-15', '', 1200, 0, 32, b'0', '2017-02-09 19:52:48', 2, 1),
(34, 'tv samsung', 150, 4, '2016-11-15', '2016-12-15', '', 180, 0, 33, b'1', '2017-02-09 19:54:32', 2, 1),
(35, 'olla arrocera 6l', 70, 4, '2016-11-16', '2016-12-16', '', 84, 0, 34, b'1', '2017-02-09 19:56:18', 2, 1),
(36, 'guitarra electronica', 100, 4, '2016-11-17', '2016-12-17', '', 120, 0, 35, b'1', '2017-02-09 19:58:05', 2, 1),
(37, 'terma solar', 500, 4, '2016-11-19', '2016-12-19', '', 600, 0, 36, b'1', '2017-02-09 19:59:57', 2, 1),
(38, 'impresora canon hg 2410', 60, 4, '2016-11-28', '2016-12-28', '', 72, 0, 37, b'1', '2017-02-09 20:01:48', 2, 1),
(50, '2 tiquetera epson', 600, 4, '2016-09-28', '2016-10-28', '', 720, 0, 49, b'1', '2017-02-10 16:25:09', 2, 1),
(40, 'laptop toshiba core i3', 350, 4, '2016-12-03', '2017-01-03', '', 420, 0, 39, b'1', '2017-02-09 20:05:36', 2, 1),
(41, 'tablet acer', 80, 4, '2016-12-12', '2017-01-12', '', 96, 0, 40, b'1', '2017-02-09 20:07:48', 2, 1),
(42, 'gps', 68, 4, '2017-02-15', '2017-01-12', 'Se adelantó S/. 32.00 de S/. 100.00 el día 15/02/2017<br>', 120, 0, 41, b'1', '2017-02-09 20:09:44', 2, 1),
(43, 'laptop i5 con cargador y estuche azul', 550, 4, '2016-12-14', '2017-01-14', '', 660, 0, 42, b'1', '2017-02-09 20:11:52', 2, 1),
(44, 'laptop amd a8 con cargador y estuche', 400, 4, '2016-12-15', '2017-01-15', '', 480, 0, 43, b'1', '2017-02-09 20:13:30', 2, 1),
(45, 'camara sony lens g', 180, 4, '2016-12-16', '2017-01-16', '', 216, 0, 44, b'1', '2017-02-09 20:15:14', 2, 1),
(46, 'tablet teraware', 60, 4, '2016-12-01', '2017-01-01', '', 72, 0, 45, b'1', '2017-02-09 20:17:13', 2, 1),
(47, 'laptop con mochila ploma', 800, 4, '2017-01-06', '2017-02-06', '', 960, 0, 46, b'1', '2017-02-09 20:19:06', 2, 1),
(48, 'laptop hp pavilion', 500, 4, '2017-01-07', '2017-02-07', '', 600, 0, 47, b'1', '2017-02-09 20:20:40', 2, 1),
(49, 'Tv 3d Smart Samsung 40 6400', 500, 4, '2017-02-10', '2017-03-10', 'Accesorios completos(en caja)', 580, 0, 48, b'1', '2017-02-10 12:33:53', 2, 1),
(52, 'laptop hp i3', 300, 4, '2016-12-01', '2017-02-01', '', 408, 0, 51, b'1', '2017-02-10 16:47:31', 2, 1),
(53, 'camara canon powershota2600', 60, 4, '2017-02-10', '2017-02-11', 'estuche y cargador', 62.4, 0, 52, b'0', '2017-02-10 18:18:09', 2, 1),
(54, 'tablet advance con cargador', 80, 4, '2017-02-11', '2017-03-11', '', 92.8, 0, 53, b'1', '2017-02-11 12:10:09', 2, 1),
(55, 'celular mod XD', 23, 4, '2017-02-24', '2017-02-14', 'Se adelantó S/. 1.00 de S/. 24.00 el día 24/02/2017<br>Se adelantó S/. 1.00 de S/. 25.00 el día 24/02/2017<br>', 26, 71.5, 54, b'1', '2017-02-13 12:43:31', 9, 2),
(56, 'taza chocolate', -20, 4, '2017-02-24', '2017-01-11', 'Se adelantó S/. 15.00 de S/. -5.00 el día 24/02/2017<br>Se adelantó S/. 1.00 de S/. -4.00 el día 24/02/2017<br>Se adelantó S/. 1.00 de S/. -3.00 el día 24/02/2017<br>Se adelantó S/. 1.00 de S/. -2.00 el día 24/02/2017<br>Se adelantó S/. 2.00 de S/. 0.00 el día 24/02/2017<br>Se adelantó S/. 1 del monto S/. 1.00el día 15/02/2017<br>Se adelantó S/. 1 del monto S/. 2.00el día 15/02/2017<br>', 2.08, 0, 54, b'1', '2017-02-13 12:44:43', 9, 2),
(57, 'bicicleta bianss', 50, 4, '2017-02-13', '2017-03-13', '', 58, 0, 55, b'1', '2017-02-13 15:32:14', 2, 1),
(58, 'laptop hp envy i7', 700, 4, '2017-02-13', '2017-03-13', 'laptop con cargador', 812, 0, 56, b'1', '2017-02-13 16:03:28', 2, 1),
(59, 'pc +pantalla lg+parlantes+transformador', 170, 4, '2017-02-20', '2017-03-13', 'Se adelantó S/. 30.00 de S/. 200.00 el día 20/02/2017<br>', 232, 0, 57, b'1', '2017-02-13 16:34:48', 2, 1),
(60, 'tv lg 32ln540b', 300, 4, '2017-02-13', '2017-03-13', '', 348, 0, 58, b'1', '2017-02-13 17:27:00', 2, 1),
(61, 'laptop hp pavilion 14 notebook con cargador', 300, 4, '2017-02-13', '2017-03-13', '', 348, 0, 59, b'1', '2017-02-13 17:49:57', 2, 1),
(62, 'laptop advance i3 con cargador', 107, 4, '2017-02-13', '2017-03-13', '', 124.12, 0, 57, b'0', '2017-02-13 19:00:44', 2, 1),
(63, 'televisor noc le19w037', 100, 4, '2017-02-14', '2017-03-14', 'con su control', 116, 0, 60, b'1', '2017-02-14 11:06:32', 2, 1),
(64, 'Celular modelo zte', 140, 4, '2017-02-15', '2017-04-08', 'Se adelantó S/. 10.00 de S/. 150.00 el día 15/02/2017<br>Se adelantó S/. 1.00 de S/. 151.00 el día 15/02/2017<br>Se adelantó S/. 3.00 de S/. 154.00 el día 15/02/2017<br>Se adelantó S/. 4.00 de S/. 158.00 el día 15/02/2017<br>Se adelantó S/. 2.00 de S/. 160.00 el día 15/02/2017<br>Se adelantó S/. 4.00 de S/. 164.00 el día 15/02/2017<br>Se adelantó S/. 1.00 de S/. 165.00 el día 15/02/2017<br>Se adelantó S/. 1.00 de S/. 166.00 el día 15/02/2017<br>Se adelantó S/. 5.00 de S/. 171.00 el día 15/02/2017<br>Se adelantó S/. 3.00 de S/. 174.00 el día 15/02/2017<br>Se adelantó S/. 3.00 de S/. 177.00 el día 15/02/2017<br>Se adelantó S/. 2.00 del monto S/. 179.00 el día 15/02/2017<br>Se adelantó S/. 1 del monto S/. 180.00el día 15/02/2017<br>', 237.6, 71, 54, b'1', '2017-02-14 14:05:50', 9, 2),
(65, 'Deja su cubo antiesess', 100, 4, '2017-02-14', '2017-02-15', 'Deja su cubo antiesess', 104, 44, 61, b'1', '2017-02-14 14:31:23', 10, 3),
(66, 'pc advance intel core duo', 80, 4, '2017-02-15', '2017-03-15', '', 92.8, 0, 62, b'1', '2017-02-15 13:30:53', 2, 1),
(67, 'taladro repli. bosch(verde)', 40, 4, '2017-02-16', '2017-03-16', '', 46.4, 0, 63, b'1', '2017-02-16 13:58:46', 2, 1),
(68, 'maquina vibradora para piso marca honda G200', 200, 4, '2017-02-20', '2017-03-20', '', 232, 0, 64, b'1', '2017-02-20 10:27:08', 2, 1),
(69, 'celular huawei cun l03', 100, 4, '2017-02-20', '2017-03-20', '', 116, 0, 65, b'1', '2017-02-20 12:20:53', 2, 1),
(70, 'equipo de sonido l6-3420 + olla arrocera rc 6', 150, 4, '2017-01-14', '2017-02-14', '', 180, 0, 66, b'1', '2017-02-20 12:57:52', 2, 1),
(71, 'guitarra electroacustica villalta', 100, 4, '2017-02-20', '2017-04-20', '', 136, 0, 67, b'1', '2017-02-20 14:08:25', 2, 1),
(72, '3 celulares htc desire 626s', 500, 4, '2017-02-21', '2017-03-21', '', 580, 0, 68, b'1', '2017-02-21 09:52:04', 2, 1),
(73, 'llanta+gata+triangulo+estuche (5 herramientas) ', 100, 4, '2017-02-22', '2017-03-22', '', 116, 0, 69, b'1', '2017-02-22 16:50:08', 2, 1),
(74, '2 cpu + 2 monitor samsung', 1000, 4, '2017-02-23', '2017-03-23', '', 1160, 0, 4, b'1', '2017-02-23 19:01:40', 2, 1),
(75, 'laptop hp probook 4440 con escuche', 300, 4, '2017-02-24', '2017-03-24', '', 348, 0, 70, b'1', '2017-02-24 09:49:29', 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

DROP TABLE IF EXISTS `sucursal`;
CREATE TABLE IF NOT EXISTS `sucursal` (
  `idSucursal` int(11) NOT NULL AUTO_INCREMENT,
  `sucNombre` varchar(50) NOT NULL,
  `sucLugar` varchar(200) NOT NULL,
  `sucActivo` bit(1) NOT NULL DEFAULT b'1' COMMENT '1 para activo',
  PRIMARY KEY (`idSucursal`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Volcado de datos para la tabla `sucursal`
--

INSERT INTO `sucursal` (`idSucursal`, `sucNombre`, `sucLugar`, `sucActivo`) VALUES
(1, 'Las Retamas', 'Las Retamas 555 - La Rivera', b'1'),
(2, 'Av. Real 321', 'Av. Real 321', b'1'),
(3, 'Todas las oficinas', 'Todas las oficinas', b'1');

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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `usuNombres`, `usuApellido`, `usuNick`, `usuPass`, `usuPoder`, `idSucursal`, `usuActivo`) VALUES
(1, 'Carlos Alex', 'Pariona Valencia', 'cpariona', 'b84d8185d9fc5d64de366cc8a06d8ef1', 1, 3, b'1'),
(2, 'Yuri Paola', 'Huaycuch Valenzuela', 'sucursal1', '93585797569d208d914078d513c8c55a', 2, 1, b'1'),
(3, 'Yuri Paola', 'Huaycuch Valenzuela', 'yhuaycuch', '93585797569d208d914078d513c8c55a', 2, 1, b'1'),
(10, 'Manrique o', 'aumbbel', 'amo', '43e423ee04be24b417b0c5eb71ad4464', 1, 3, b'1'),
(9, 'demo', 'demo', 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 2, 2, b'1'),
(8, 'manrique', 'Aumbbel', 'aumbbel', '3d5390642ff7a7fd9b7ab8bac4ec3ec5', 1, 3, b'1');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
