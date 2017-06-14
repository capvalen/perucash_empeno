-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 14-06-2017 a las 13:42:33
-- Versión del servidor: 5.6.35
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `peruca5_app`
--
CREATE DATABASE IF NOT EXISTS `peruca5_app` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `peruca5_app`;

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `actualizarDatosCliente`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `actualizarDatosCliente` (IN `apel` VARCHAR(100), IN `nomb` VARCHAR(100), IN `ddni` VARCHAR(8), IN `direc` VARCHAR(100), IN `corr` VARCHAR(100), IN `celu` VARCHAR(100), IN `idCli` INT)  NO SQL
begin 
UPDATE `Cliente` SET 
`cliApellidos`=apel,
`cliNombres`=nomb,
`cliDni`=ddni,
`cliDireccion`=direc,
`cliCorreo`=corr,
`cliCelular`=celu
where `idCliente`= idCli;
end$$

DROP PROCEDURE IF EXISTS `contarVencidos`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `contarVencidos` (IN `idSuc` INT)  NO SQL
SELECT count(idproducto) as Num
FROM `producto` p 
where prodactivo = 1 and datediff( now(), prodfechavencimiento )>=30
and idSucursal =idSuc$$

DROP PROCEDURE IF EXISTS `encontrarCliente`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `encontrarCliente` (IN `dni` VARCHAR(8))  NO SQL
SELECT * FROM `Cliente` WHERE clidni = dni$$

DROP PROCEDURE IF EXISTS `insertarAdelantoAProducto`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarAdelantoAProducto` (IN `idProd` INT, IN `nuevoAdelanto` FLOAT)  NO SQL
begin
UPDATE `producto` SET 
`prodObservaciones` =concat('Se adelantó S/. ',round(nuevoAdelanto,2),' de S/. ' , round(`prodMontoEntregado`,2), ' el día ', DATE_FORMAT(now(), "%d/%m/%Y"), '<br>', `prodObservaciones`),
`prodFechaInicial`=now(),
`prodMontoEntregado`=`prodMontoEntregado`-nuevoAdelanto,
`prodAdelanto`=`prodAdelanto` +nuevoAdelanto

WHERE 
`idProducto`= idProd;
end$$

DROP PROCEDURE IF EXISTS `insertarProductoNew`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarProductoNew` (IN `cliNomb` VARCHAR(50), IN `cliApelli` VARCHAR(50), IN `cliDirec` VARCHAR(200), IN `dni` VARCHAR(50), IN `email` VARCHAR(50), IN `celular` VARCHAR(50), IN `nomProd` VARCHAR(200), IN `montoentregado` FLOAT, IN `interes` FLOAT, IN `montopagar` FLOAT, IN `fechainicial` DATE, IN `feachavencimiento` DATE, IN `observaciones` TEXT, IN `usuario` INT, IN `idSuc` INT)  BEGIN
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
lower(trim(cliApelli)), lower(trim(cliNomb)),
dni,
lower(cliDirec),
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
lower(trim(nomProd)),
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
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarProductoSolo` (IN `nomProd` VARCHAR(200), IN `montoentregado` FLOAT, IN `interes` FLOAT, IN `montopagar` FLOAT, IN `fechainicial` DATE, IN `feachavencimiento` DATE, IN `observaciones` TEXT, IN `usuario` INT, IN `idCl` INT, IN `idSuc` INT)  NO SQL
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
lower(trim(nomProd)),
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
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarSucursalNueva` (IN `sucNom` VARCHAR(200), IN `sucLug` VARCHAR(200))  NO SQL
INSERT INTO `sucursal`(`idSucursal`, `sucNombre`, `sucLugar`)
VALUES (null ,sucNom,sucLug)$$

DROP PROCEDURE IF EXISTS `insertarUsuario`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarUsuario` (IN `nombre` VARCHAR(50), IN `apellido` VARCHAR(50), IN `nick` VARCHAR(50), IN `pass` VARCHAR(50), IN `poder` INT, IN `idSucur` INT)  NO SQL
INSERT INTO `usuario`(`idUsuario`, `usuNombres`, `usuApellido`,
                      `usuNick`, `usuPass`, `usuPoder`,
                      `idSucursal`, `usuActivo`) 
VALUES (null,apellido,nombre,nick,md5(pass),poder,idSucur,1)$$

DROP PROCEDURE IF EXISTS `listarProductoPorId`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarProductoPorId` (IN `idProd` INT)  NO SQL
BEGIN
select lower( concat(cliApellidos ,', ', cliNombres)) as propietario,
p.`idProducto`, `prodNombre`, 
format( `prodMontoEntregado`,2) as prodMontoEntregado, 
`prodInteres`, `prodFechaInicial`, `prodObservaciones`,
s.sucNombre,  usuNombres as atendio
from producto p
inner join sucursal s on p.idSucursal= s.idSucursal
inner join Cliente c on c.idCliente = p.idCliente
inner join usuario u on u.idUsuario= p.idUsuario
where idProducto = idProd;
END$$

DROP PROCEDURE IF EXISTS `listarProductosPorAprobar`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarProductosPorAprobar` (IN `oficina` INT)  NO SQL
SELECT idProducto, prodNombre,
prodCuantoFinaliza, prodQuienFinaliza, prodFechaFinaliza

FROM `producto`
where prodAprobado=0  and prodActivo=0
and idSucursal=oficina
ORDER BY `producto`.`idProducto`  DESC$$

DROP PROCEDURE IF EXISTS `listarProductosPorCliente`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarProductosPorCliente` (IN `idCli` INT)  BEGIN
select p.*, s.sucNombre from producto p
inner join sucursal s on p.idSucursal= s.idSucursal
where idcliente =idCli
order by prodFechaRegistro desc;
END$$

DROP PROCEDURE IF EXISTS `listarProductosVencidos`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarProductosVencidos` (IN `idSuc` INT)  NO SQL
SELECT idproducto, prodNombre,
prodMontoEntregado,
concat(cliapellidos, ', ', clinombres) as propietario,
prodFechaVencimiento
FROM `producto` p inner join Cliente c
on c.idcliente = p.idcliente
where prodactivo = 1 and datediff( now(), prodfechavencimiento )>=30
and p.idSucursal = idSuc
order by prodfechavencimiento desc$$

DROP PROCEDURE IF EXISTS `listarTodoProductosPorSuc`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarTodoProductosPorSuc` (IN `desde` INT, IN `hasta` INT, IN `idSuc` INT)  NO SQL
begin
SELECT p.idProducto, prodNombre, format( prodMontoEntregado,2) as prodMontoEntregado, 
prodFechaInicial, sucNombre, usuNombres, concat(cliApellidos, ' ', cliNombres) as propietario,
prodActivo
FROM `producto` p inner join usuario u on u.idUsuario = p.idUsuario
inner join sucursal s on s.idSucursal = p.idSucursal
inner join Cliente c on c.idCliente = p.idCliente
where p.idSucursal=idSuc
order by prodFechaInicial asc
limit desde,hasta;
end$$

DROP PROCEDURE IF EXISTS `listarTodoProductosSinSuc`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarTodoProductosSinSuc` (IN `desde` INT, IN `hasta` INT)  NO SQL
begin
SELECT p.idProducto, prodNombre, format( prodMontoEntregado,2) as prodMontoEntregado, 
prodFechaInicial, sucNombre, usuNombres,
concat(cliApellidos, ' ', cliNombres) as propietario, prodActivo
FROM `producto` p inner join usuario u on u.idUsuario = p.idUsuario
inner join sucursal s on s.idSucursal = p.idSucursal
inner join Cliente c on c.idCliente = p.idCliente
order by prodFechaInicial asc
limit desde,hasta;
end$$

DROP PROCEDURE IF EXISTS `listarTodosProductosNoFinalizados`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarTodosProductosNoFinalizados` (IN `idSuc` INT)  NO SQL
SELECT c.*, p.*, u.usuNombres FROM `producto` p
inner join Cliente c
on p.idcliente = c.idcliente
inner join usuario u
on p.idusuario = u.idusuario
where prodactivo =1 and p.idSucursal = idSuc
order by prodfechainicial asc$$

DROP PROCEDURE IF EXISTS `listarTodosUsuarios`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarTodosUsuarios` ()  NO SQL
SELECT u.`idUsuario`, concat( `usuNombres`, ' ',  `usuApellido` ) as nombre, p.`descripcion`,  sucLugar 
FROM `usuario` u inner join sucursal s
on u.`idSucursal`= s.`idSucursal`
inner join poder p on p.idPoder=usuPoder
WHERE `usuActivo`=1
order by usuNombres$$

DROP PROCEDURE IF EXISTS `listarUnUsuario`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarUnUsuario` (IN `idUser` INT)  NO SQL
SELECT u.`idUsuario`, `usuNombres` , `usuApellido`, usuNick,
p.idPoder,  u.idSucursal
FROM `usuario` u inner join sucursal s
on u.`idSucursal`= s.`idSucursal`
inner join poder p on p.idPoder=usuPoder
WHERE idUsuario = idUser
order by usuNombres$$

DROP PROCEDURE IF EXISTS `returnTotalProductos`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `returnTotalProductos` ()  NO SQL
begin

SELECT COUNT( * ) conteo
FROM  `producto` ;

end$$

DROP PROCEDURE IF EXISTS `returnTotalProductosPorId`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `returnTotalProductosPorId` (IN `idSuc` INT)  NO SQL
begin

SELECT COUNT( * ) conteo
FROM  `producto` 
where idSucursal = idSuc;

end$$

DROP PROCEDURE IF EXISTS `solicitarProductoPorId`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `solicitarProductoPorId` (IN `idProd` INT)  BEGIN
select c.*, p.*, s.sucNombre
from Cliente c inner join producto p on c.idcliente = p.idcliente
inner join sucursal s on p.idSucursal= s.idSucursal

where idproducto = idProd ;
END$$

DROP PROCEDURE IF EXISTS `ubicarPersonaProductos`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `ubicarPersonaProductos` (IN `campo` TEXT)  BEGIN
select `idCliente`, lower(`cliApellidos`) as cliApellidos, lower(`cliNombres`) as cliNombres, `cliDni`, lower(`cliDireccion`) as cliDireccion, `cliCorreo`, `cliCelular`
from Cliente c 
where concat(lower(cliApellidos), ' ', lower(cliNombres)) like concat('%', campo, '%')
or clidni = campo
order by cliApellidos
;
END$$

DROP PROCEDURE IF EXISTS `updateFinalizarEstado`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `updateFinalizarEstado` (IN `idPro` INT, IN `usuar` VARCHAR(50), IN `monto` FLOAT)  NO SQL
UPDATE `producto` SET `prodActivo`=0,
`prodCuantoFinaliza`=monto,
`prodQuienFinaliza`=usuar,
`prodFechaFinaliza`=now()
WHERE 
`idProducto` = idPro$$

DROP PROCEDURE IF EXISTS `updateFinalizarInteres`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `updateFinalizarInteres` (IN `idPro` INT, IN `monto` FLOAT)  NO SQL
UPDATE `producto` SET 
`prodFechaInicial`=now(),
`prodObservaciones` =concat('Se canceló el interés S/. ',round(monto,2),' de el día ', DATE_FORMAT(now(), "%d/%m/%Y"), '<br>', `prodObservaciones`),
`prodUltimaFechaInteres`= DATE_FORMAT(now(), "%d/%m/%Y")
WHERE 
`idProducto` = idPro$$

DROP PROCEDURE IF EXISTS `updateFinalizarSucursal`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `updateFinalizarSucursal` (IN `idSuc` INT)  NO SQL
UPDATE `sucursal` SET `sucActivo`=0 WHERE idSucursal =idSuc$$

DROP PROCEDURE IF EXISTS `updateUserDatosConPass`$$
CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `updateUserDatosConPass` (IN `nombre` VARCHAR(200), IN `apellido` VARCHAR(200), IN `nick` VARCHAR(200), IN `pass` VARCHAR(200), IN `poder` INT, IN `sucursal` INT, IN `idUser` INT)  NO SQL
UPDATE `usuario` SET 
`usuNombres`=nombre,
`usuApellido`=apellido,
`usuNick`=nick,
`usuPass`=md5(pass),
`usuPoder`=poder,
`idSucursal`=sucursal
WHERE `idUsuario`=idUser$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Cliente`
--

DROP TABLE IF EXISTS `Cliente`;
CREATE TABLE `Cliente` (
  `idCliente` int(11) NOT NULL,
  `cliApellidos` varchar(50) NOT NULL,
  `cliNombres` varchar(50) NOT NULL,
  `cliDni` varchar(8) NOT NULL,
  `cliDireccion` varchar(200) NOT NULL,
  `cliCorreo` varchar(50) NOT NULL,
  `cliCelular` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `Cliente`
--

INSERT INTO `Cliente` (`idCliente`, `cliApellidos`, `cliNombres`, `cliDni`, `cliDireccion`, `cliCorreo`, `cliCelular`) VALUES
(1, 'madrid vargas', 'luis fernando', '48211897', 'jr. san martin #247-huancayo', 'vanslove15@gmail.com', '934958237 '),
(2, 'milagros sarapura', 'sharon jenifer', '46573028', 'calle apata s/n - matahuasi (paradero muruhuay)', 'sharonsahe_10@hotmail.com', '948003747'),
(3, 'najera daza', 'raul alex', '47800688', 'jr. santa rasa 1357 - el tambo', '', '944843829'),
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
(54, 'romero aliaga', 'juro dan', '14253600', 'manzanos 1545', 'dan_11@gmail.com', '245252'),
(55, 'rivera rojas', 'enrique phileas', '46162078', 'pj. los husares #135-el tambo', '', '970575516'),
(56, 'balbin barrera', 'diestefano jj romario', '71695974', 'prol. julio llanos #145 sector 20', '', '955939828'),
(57, 'Anccasi paucar', 'clisman mayer', '72675890', 'av. evitamiento y ferrocarril  #825- el tambo', '', '942643000'),
(58, 'trujillo flores', 'roy edgar', '42272948', 'pj giraldes s/n- palian (parque)', '', '920453873'),
(59, 'gaytan huaynalaya', ' kevin david', '73983699', 'jr. progreso # 345 ', '', '929043438'),
(60, 'santana santivañez', 'rocio Del Pilar', '42445125', 'jr. raul porras barrenechea #173- el tambo', '', '979097036'),
(61, 'Manrique', 'Giordan', '72235379', 'Jr Retamas', 'giordan@gmail.com', '986986403'),
(62, 'perez guinea', 'hilda', '19868845', 'jr. agusto b leguia #1171 -chilca', '', '983658558'),
(63, 'meza janampa', 'gian marco', '76067543', 'nicolas  alcazar lt 20 (col. ramiro)-hyo', '', '975924917'),
(64, 'ilizarbe bendezu', 'moises alejandro', '19823558', 'pje. mariscal caceres # 155- piopata', '', '923293150'),
(65, 'cordova campos', ' Jose Luis', '47362090', 'av. palian #645-hyo', '', '991483443'),
(66, 'paz castellares', 'paul vladimir', '44724568', 'av. huancavelica #153 -hyo', '', '971710120'),
(67, 'calla bruno', 'juan carlos', '41660785', 'jr. alan garcia #241- el tambo', '', '962216786'),
(68, 'egoavil arriola', ' jesus miguel', '43715671', 'av. la esperanza #128- chilca', '', '979730815'),
(69, 'arana peña', 'alberto', '20015135', 'grau #1062- el tambo', '', '964009483'),
(70, 'huaynate meza', 'jhosy joel', '45848360', 'jr. manuel scorza #468- el tambo', '', '938198669'),
(71, 'vila joaquin ', 'eddy margoth', '41073246', 'calle real #1252-hyo', '', '964689057'),
(72, 'cano aliaga', 'linton brayan', '76432131', 'jr. coronel pedro aliaga #326- chupaca', '', '944271671'),
(73, 'cano aliaga', 'linton brayan', '76432131', 'jr. coronel pedro aliaga #326- chupaca', '', '944271671'),
(74, 'cabezas meza', 'maycol ralp', '72569108', 'prolg. tarapaca #551- hyo', '', '961500023'),
(75, 'robles arrieta', 'margarita marivel', '44766389', 'parq. industrial #105- el tambo', '', '958823182'),
(76, 'sanchez casas', 'julio pedro', '20056709', 'calle los jasmines#170- saños el tambo -hyo', '', '999479600'),
(77, 'chupurgo mendizabal', 'jose luis', '43334418', 'jr. tarapaca #334-hyo', '', '988889241'),
(78, 'miliano barrantes', 'cristian pholl', '71221679', 'jr. angaraes #783 con hcvca-hyo', '', '923526741'),
(79, 'muñoz laurente', 'jorge luis', '20026651', 'jr. miguel grau s/n - pilcomayo', '', '942956575'),
(80, 'asto gutarra', 'ida rosalinda', '23648081', 'jr. lima #433 con arequipa-hyo', '', '943507745'),
(81, 'huaman alfonso', 'alfredo carlos', '41703203', 'jr. tupac amaru maz. D Lte. 9- chilca', '', '920443687'),
(82, 'nieto tabra', 'cinthya lisset', '46284919', 'jr. los  claveles #896- hyo', '', '934731827'),
(83, 'alarcon porras', 'luis gilberto', '20121075', 'av. huancavelica #1198-hyo', '', '984840275'),
(84, 'villar perez', 'elias', '01153032', 'jr. 28 de julio #1071-huancan', '', '988230081'),
(85, 'ancieta esteban', 'angel bernardino', '42936723', 'av. los minerales #201- hyo', '', '985039312'),
(86, 'retamozo sanchez', 'kenia laleshca', '73088564', 'jr. lima #2509- la rivera', '', '064-411420'),
(87, 'diaz mendieta', 'juan antonio', '70845274', 'pje. huracio gutierrez #316- el tambo', '', '963656637'),
(88, 'huaman rivas', 'rocio', '04220411', 'jr. los cardos #312- la rivera ', '', '064-413283'),
(89, 'oscanoa marcelo del carrillo', 'rocio betty', '20084708', 'pj. santa rosa #158- san carlos', '', '945588076'),
(90, 'asturimac oscanoa', 'richard manuel', '41523555', 'pj antonio paredes #205', '', '959899344'),
(91, 'londoñe soto', 'gabriela', '45119498', 'pj. virgen de guadalupe #225 el tambo', '', '995593498'),
(92, 'simon villanueva', 'stefany karolay', '72266804', 'giraldes y ferrocarril #573-hyo', '', '920451153'),
(93, 'zapata palomino', 'jose francisco', '80688822', 'jr. federico galvez duran. #193-hyo', '', '953913040'),
(94, 'buendia sulca', 'saul arturo', '42683690', 'pj, la breña #110- el tambo', '', '989893510'),
(95, 'rivera cotera', 'patrick nick miguel', '70417368', 'jv. las retamas #648', '', '935075367'),
(96, 'romero lobo', 'alexis julinho', '72672131', 'san carlos y pj santa lucia ', '', '980861537'),
(97, 'julian daga', 'edward jonathan', '70328352', 'pj los olivos l3 mc - saños chico-el tambo', '', '933391941'),
(98, 'ulloa peralta', 'gino alan', '43294847', 'jr. tarapaca #329, 3er y 4to piso', '', '986968162'),
(99, 'romero serpa', 'felix fernando', '19993916', 'jr. los claveles #637-hyo', '', '984093533'),
(100, 'calderon pucuhuaranga', 'yoshiro fortunato', '47891986', 'jr. Panamá #1557- tambo', '', '960814688'),
(101, 'melgar ibarra', 'luis antonio', '07476973', 'pj. san pedro #608 urb. la molina -el tambo', '', '988852260'),
(102, 'reyes castillo', 'janeth', '41237486', 'jr. arequipa #870 - tarma', '', '956454011'),
(103, 'osco castillo', 'eliovera', '70018821', 'jr. manco capac mz d lt 29 - chilca', '', '926046084'),
(104, 'orihuela iriante', 'yanina rosa', '72406957', 'jr. monte carlos #386- cerrito de la libertad ', '', '974137757'),
(105, 'palomino poma', 'josseph gustavo', '47207309', 'jr. union #321-palian', '', '957811667'),
(106, 'bustamante romero', 'paola', '73072689', 'jr. parra del riego #1963-pio pata', '', '963321038'),
(107, 'galvan Rojas', 'Misael Jonatan', '45490853', 'Av. Universitaria 558 - Ciudad Univ. Saños chico', 'jgalvan.colpaingenieros@gmail.com', '928304291'),
(108, 'artica torres', 'maria gladys', '19838758', 'pj. bernaola #174-chilca', '', '961610103'),
(109, 'martinez alvarado', ' carlos', '70764655', 'jr. puno 348 - tarma', '', '958696353'),
(110, 'clemente avila', 'luis miguel', '42855461', 'pj. chavin 349- el tambo', '', '976082982'),
(111, 'egoavil villegas', 'luis fernando', '72196085', 'jr. micaela bastidas 442- pio pata', '', '943257736'),
(112, 'panduro jumachi', 'edvin', '48068324', 'pj los diamantes con santa rosa s/n', '', '945678437'),
(113, 'acosta santamaria', 'jhon manuel', '76260498', 'san jose de umuto 290 el tambo huancayo', 'jmas_271994@hotmail.com', '941823928'),
(114, 'matos briceño', 'lizzet fiorella', '45535998', 'pje. mariategui #150- tambo', '', '934800030'),
(115, 'quispe toscano', 'erick', '76651918', 'jr. diamante azul 211- el tambo ', '', '988944849'),
(116, 'cifuentes rivera', 'jose francisco', '19997114', 'jr. cuzco 442', '', '960585773'),
(117, 'abasto flor', 'fiorella isabel', '71229398', 'jr. julio c tello 356', '', '931153850'),
(118, 'salazar soto', 'saul alberto', '48644308', 'pje. el sol 191 la florida', '', '950560270'),
(119, 'martinez diaz', 'dhayans esther', '70146068', 'carretera central km 115 san pedro de saños', '', '920060614'),
(120, 'lloclla najarro', 'cristian', '60635736', 'jr. dos de mayo 1528 huancan ', '', '994887061'),
(121, 'romero gonsales', 'jim william', '73955379', 'c h \"la breña\" block c departamento 203- san carlos', '', '978819376'),
(122, 'trinidad marcelo', 'gustavo daniel', '70825125', 'juan chipana s/n san agustín de cajas ', '', '938662017'),
(123, 'anccasi cunya', 'fredy cesar', '23272489', 'av. orion mz n lt4 ', '', '963503384'),
(124, 'gonzales armas', 'vladimir ernesto', '40012029', 'pj. chalco 121 millotingo', '', '966011902'),
(125, 'pisco tovar', 'michael cristian', '41651673', 'jr. cuzco 2110- ', '', '997593801'),
(126, 'matos calderon', 'yosip andy', '45340425', 'jr. ayacucho 130-hyo', '', '928045171'),
(127, 'gene ramos', 'aurelio', '00001234', 'av. real 441', 'mm.s@gg.com', '92536111'),
(128, 'galindo auris', 'ghesvil anhy', '71503114', 'pj nueva esperanza 190 el tambo', '', '940666834'),
(129, 'reyes salvador', 'brigida jackeline', '74623661', 'av. huaytapallana mz e lt 17 urb las retamas de san luis', '', '972888202'),
(130, 'pimentel montero', 'juvencio', '23702786', 'av. circumvalacion  835- cerrito de la libertad ', '', '964003509'),
(131, 'solis quispe', 'daniel royer', '70223885', 'diamante azul 107 - el tambo', '', '943036227'),
(132, 'huaynate mosquera', 'denis dione', '46542706', 'pj arana s/n palian ', '', '949772529'),
(133, 'perea sanchez', 'victor gabriel', '80260242', 'circuito huaytapallana 318-el tambo', '', '960244716'),
(134, 'ortiz huatuco', 'christian nicolas', '45696740', 'jr. cahuide sec. sur s/n jauja', '', '956439341'),
(135, 'valdivia vasquez', 'elias amos', '77377576', 'jr.la marina #795 - el tambo', 'AMOS_VALDIVIA@HOTMAIL.COM', '940651731'),
(136, 'torres cueva', 'susan lizbeth', '70306669', 'ayacucho y moquegua', '', '987245102'),
(137, 'sulla ortiz', 'jorge luis', '41526508', 'prolg. ancash #842- chilca', '', '954932424'),
(138, 'nakasone guzman', 'edgard yoshio', '43396763', 'urb. salazar mz d lt. 23 - el tambo', '', '985900566'),
(139, 'coronel bonifacio', 'jim junir', '12121212', 'manuel alonso 5 piso stan 18', '', '987654321'),
(140, 'melgar ibarra', 'luis antonio', '07476973', 'psj. san pedro 608', '', '988852260'),
(141, 'vidal huaman', 'isaias efer', '70210452', 'av. parra 1150 - pilcomayo', '', '970150570'),
(142, 'zuñiga calderon', 'eduardo daniel bradley', '76903715', 'av. carmen del solar y marte', '', '991288058'),
(143, 'chihuan quispe', 'anthoni', '70242138', 'jr. rancas 231- hyo', '', '954804326'),
(144, 'mallma davila', 'carlos alberto', '42842120', 'jr.delfin levano 549', '', '951388898'),
(145, 'payano camarena', 'gustavo', '43336782', 'av. jose carlos mariategui 2030', '', '967920725'),
(146, 'flores romero', 'cintya pamela', '42172977', 'jr. parra del riego 466', '', '962600720'),
(147, 'camarena leon', 'max alberto', '20089382', 'jr. miguel grau km1.5 carretera centarl margen derecha pilcomayo', '', '963638025'),
(148, 'reyes artica', 'clementina', '23719263', 'pje. los nevados #155-hyo', '', '959711502'),
(149, 'galvez huaira', 'cristian', '48089833', 'jr. sebastian lorente #2022', '', '941697875'),
(150, 'hilario corilla', 'annika gessela', '72393147', 'coperativa santa isabel av. alameda #18', '', '964064727'),
(151, 'ruiz varillas', 'ana cecilia', '46270292', 'jr. santa rosa #414 chilca', '', '993648084'),
(152, 'gonzalez cardenas', 'paulo  cesar igor', '42704198', 'pj los jazmines mz  n blok r4', '', '942463346'),
(153, 'llallico garcia', 'joselo', '47802658', 'jr. tacna #550-el tambo ', '', '933309305'),
(154, 'hurtado surichaqui', 'leonarda jessica', '44518202', 'diamantes azul #268 - cooperativa 1 de mayo ', '', '995999511'),
(155, 'veliz de villa aguirre', 'carlos alberto', '20073613', 'av. los andes 885', '', '954001083'),
(156, 'cardenas pampas', 'giovanna', '20113349', 'las retamas #476- la rivera', '', '980944523'),
(157, 'rojas soto', 'sonia germana', '19870042', 'pje. flemnj 228 gonsales iv etapa- el tambo ', '', '940160575'),
(158, 'bermudez alarcon', 'dolly judith', '41086251', 'pje nuñez 192- chilca', '', '993565835'),
(159, 'huansi vasquez', 'magaly', '77474210', 'pj pinar 157- goyzueta ', '', '996213322'),
(160, 'gonzales lopez', 'liz margot', '20105217', 'jr, humbolt 108 - chilca', 'lizi28@hotmail.com', '997895668'),
(161, 'parimango carhuas', 'julio cesar', '47495352', 'jr. antonio de zela 078 chilca alt de proceres', '', '954917765 - 962511591'),
(162, 'espinoza anquipa', 'freddy', '41166332', 'av alfonso ugarte s/n huancan hyo', '', '938255368'),
(163, 'cano aliaga', 'linton brayan', '76432131', 'jr. coronel pedro aliaga 326', '', '944271671'),
(164, 'zambrano rojas', 'rodolfo jordan', '71809815', 'loreto 1472 -hyo', '', '973236480'),
(165, 'rafael mucha', 'cyndi', '46936121', 'av. la marina # 728', '', '979008012'),
(166, 'almonacid ccoriñaupa', 'christofer jhonatan', '77350211', 'jr.bolognesi 2032', '', '993650352'),
(167, 'garcia chacalcaje', 'julio armando', '24577025', 'conjunto av juan parra del riego blo 1 dep 301 2da etapa (hospital ramiro priale priale)', '', '945299772'),
(168, 'lauro sosa', 'carola natery', '74303580', 'jr. libertad y bolognesi #1314', '', '991559854'),
(169, 'ore meza', 'sharon rouse', '76370927', 'av. palian #925 - hyo', '', '989109120'),
(170, 'papuico carhuamaca', 'vanessa olga', '75276808', 'jr. huancas #319 -hyo', '', '925253740'),
(171, 'paniagua uscata', 'luis miguel', '47247878', 'calle san jose 406-umuto', '', '910887816'),
(172, 'valera nuñez', 'holenka ariadna', '73961279', 'al frende de u continetal', '', '952945226'),
(173, 'ojeda rodriguez', 'erick martin', '72154864', 'jr. moquegua #154', '', '955190037'),
(174, 'flores palian', 'yony', '47660312', 'jr. maria resh #144 palian', '', '934147817'),
(175, 'clemente tueros', 'joseph kevin', '72071968', 'jr mariscal castilla s/n - chilca', '', '956618885'),
(176, 'peñares castro', 'lidia gladys', '44035322', 'calle los  rubies 105- el tambo', '', '961618880'),
(177, 'gonsalves perez', 'catherine kelly', '20114814', 'jr. panama n°745-el tambo', '', '994680646'),
(178, 'llacua rodriguez', 'santiago alejandro', '76248824', 'pje. los jardines #435-el tambo', '', '993656698'),
(179, 'pecho caqui', 'miguel angel', '42507760', 'av. tahantinsuyo 004-el tambo', '', '999765417'),
(180, 'picho peña', 'jesus piter', '76586840', 'psj don bosco 147 - tambo', '', '923380323'),
(181, 'oropeza lazo', 'royer michael', '45976224', 'av huancavelica 1860-hyo', '', '924051359'),
(182, 'bullon garcia', 'jan marco', '70332469', 'jr jose galvez #107', '', '966190430'),
(183, 'castro sedano', 'jhon clis', '76359670', 'av. 2 de mayo s/n chilca ', '', '651785962'),
(184, 'vidal reyes', 'jhon cesar', '40435219', 'jr. pedro galvez 1326 pio pata', '', '964012721'),
(185, 'zavala santana', 'katheryn pamela', '46674544', 'jr. los nevados mz b lt 11', '', '921036990'),
(186, 'tueros lara', 'nadia elizabeth', '46497788', 'av. san carlos cuadra 14 ', '', '959512983'),
(187, 'vergara alfaro', 'joseph stiven', '48189332', 'jr. huari 285 ', '', '064-413377'),
(188, 'cano lazo', 'blade l5', '77663378', 'av. hvca 1860', '', '997873064'),
(189, 'martinez diaz', 'dhayans esther', '70146088', 'carretera central 11.5 san pedro de saño', '', '920060614'),
(190, 'nuñez marti', ' jhimy horsson', '45087776', 'jr. 28 de julio 971 - chilca', '', '954788025'),
(191, 'nuñez londoñe', 'kerly jajayra', '77242186', 'jr. virgen de guadalupe 225', '', '972026483'),
(192, 'miranda salome', 'elva yanyna', '44636269', 'av. los incas s/n-quilcas', '', '931910767'),
(193, 'vidal romero', 'fernando', '60382979', 'av. tahuantinsuyo s/n el tambo', '', '970949799'),
(194, 'collachagua leyva', 'jhony alberto', '20042705', 'jr. santos chocano 809-chilca', '', '979486236'),
(195, 'orellana jimenez', 'cristiam ernesto', '76668020', 'jr.arequipa 1808 -el tambo', '', '925027680'),
(196, 'velarde ponce', 'omar enrique', '45064608', 'los acantilados 196 - el tambo', '', '993258428'),
(197, 'sinche cierto', 'beatriz rosario', '72909190', 'jr. lima #177-hyo', '', '924507883'),
(198, 'torres caceres', 'morgoth liliana', '20064910', 'ambrocio salasar mg12 parra del riego', '', '933356258'),
(199, 'nima guerra', 'lizeth elena', '70033207', 'calle junin mz 3lt2 el tambo', '', '976316259'),
(200, 'huamani tornero', 'marco antonio', '45617724', 'jr. pueblo union 307. chilca', '', '938810490'),
(201, 'arancel aparco', 'brandon', '76245848', 'av circunvalacion  559 - 387 ', '', '969390866'),
(202, 'aguirre valero', 'tulio dardo', '42483910', 'jr arequipa 870 - tarma', '', '942901003');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `poder`
--

DROP TABLE IF EXISTS `poder`;
CREATE TABLE `poder` (
  `idPoder` int(11) NOT NULL,
  `Descripcion` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
CREATE TABLE `producto` (
  `idProducto` int(11) NOT NULL,
  `prodNombre` varchar(200) NOT NULL,
  `prodMontoEntregado` float NOT NULL,
  `prodInteres` int(11) NOT NULL,
  `prodFechaInicial` date NOT NULL,
  `prodFechaVencimiento` date NOT NULL,
  `prodObservaciones` text NOT NULL,
  `prodMontoPagar` float NOT NULL,
  `prodAdelanto` float NOT NULL DEFAULT '0',
  `idCliente` int(11) NOT NULL,
  `prodDioAdelanto` bit(1) NOT NULL DEFAULT b'0',
  `prodActivo` bit(1) NOT NULL,
  `prodFechaRegistro` datetime DEFAULT NULL,
  `idUsuario` int(11) NOT NULL,
  `idSucursal` int(11) NOT NULL,
  `prodUltimaFechaInteres` varchar(100) NOT NULL DEFAULT '',
  `prodCuantoFinaliza` float NOT NULL DEFAULT '0',
  `prodQuienFinaliza` varchar(50) NOT NULL DEFAULT '',
  `prodFechaFinaliza` varchar(200) NOT NULL DEFAULT '',
  `prodAprobado` bit(1) NOT NULL DEFAULT b'0',
  `prodQuienAprueba` varchar(50) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`idProducto`, `prodNombre`, `prodMontoEntregado`, `prodInteres`, `prodFechaInicial`, `prodFechaVencimiento`, `prodObservaciones`, `prodMontoPagar`, `prodAdelanto`, `idCliente`, `prodDioAdelanto`, `prodActivo`, `prodFechaRegistro`, `idUsuario`, `idSucursal`, `prodUltimaFechaInteres`, `prodCuantoFinaliza`, `prodQuienFinaliza`, `prodFechaFinaliza`, `prodAprobado`, `prodQuienAprueba`) VALUES
(1, 'tripode (hansa)', 50, 4, '2017-01-07', '2017-02-07', '', 60, 0, 1, b'0', b'0', '2017-02-06 12:29:21', 2, 1, '', 0, '', '', b'0', '0'),
(2, 'camara panasonic con cargador + calular huawei cun-lo3', 200, 4, '2017-01-13', '2017-02-07', '', 232, 0, 2, b'0', b'0', '2017-02-06 12:42:39', 2, 1, '', 0, '', '', b'0', '0'),
(3, 'laptop hp 455', 26, 4, '2017-02-16', '2017-02-18', 'Se adelantó S/. 20.80 de S/. 46.80 el día 16/02/2017<br>Se adelantó S/. 20.80 de S/. 67.60 el día 16/02/2017<br>Se adelantó S/. 20.80 de S/. 88.40 el día 16/02/2017<br>Se adelantó S/. 20.80 de S/. 109.20 el día 16/02/2017<br>Se adelantó S/. 20.80 de S/. 130.00 el día 16/02/2017<br>', 156, 0, 3, b'0', b'0', '2017-02-06 13:00:15', 2, 1, '', 0, '', '', b'0', '0'),
(4, 'monitor led de 20\" + aspiradora vlast pro', 252, 4, '2017-02-23', '2017-02-18', 'Se adelantó S/. 48.00 de S/. 300.00 el día 23/02/2017<br>', 360, 0, 4, b'0', b'1', '2017-02-06 13:04:22', 2, 1, '', 0, '', '', b'0', '0'),
(5, 'monitor lG flatron 17\"', 60, 4, '2017-01-19', '2017-02-19', '', 72, 0, 5, b'0', b'0', '2017-02-06 13:08:30', 2, 1, '', 0, '', '', b'0', '0'),
(6, 'cocina 5 hornillas bosh + blueray philips ', 500, 4, '2017-01-19', '2017-02-19', '', 600, 0, 6, b'0', b'0', '2017-02-06 13:26:03', 2, 1, '', 900, 'manrique', '2017-06-05 21:14:31', b'0', '0'),
(7, 'laptop hp pavilion ', 350, 4, '2017-01-20', '2017-02-20', '', 420, 0, 7, b'0', b'0', '2017-02-06 13:30:46', 2, 1, '', 0, '', '', b'0', '0'),
(8, 'back-ups cs 650 apc', 48, 4, '2017-02-24', '2017-02-23', 'Se adelantó S/. 2.00 de S/. 50.00 el día 24/02/2017<br>transformador blanco', 60, 0, 8, b'0', b'1', '2017-02-06 13:36:28', 2, 1, '', 0, '', '', b'0', '0'),
(9, 'cubiertos rena ware + cortinas', 400, 4, '2017-01-26', '2017-02-26', '26 cubiertos ', 480, 0, 9, b'0', b'1', '2017-02-06 13:45:32', 2, 1, '', 0, '', '', b'0', '0'),
(10, 'celular huawei g6 l33', 51.2, 4, '2017-04-08', '2017-02-28', 'Se adelantó S/. 16.00 de S/. 67.20 el día 08/04/2017<br>Se adelantó S/. 12.80 de S/. 80.00 el día 02/03/2017<br>', 92.8, 0, 10, b'0', b'1', '2017-02-06 13:52:49', 2, 1, '', 0, '', '', b'0', '0'),
(11, 'laptop lenovo b50-80', 500, 4, '2017-02-03', '2017-03-03', 'nuevo+cargador+boleta (en caja)', 580, 0, 11, b'0', b'0', '2017-02-06 13:56:58', 2, 1, '', 0, '', '', b'0', '0'),
(12, 'celular lg g3 + dvd', 150, 4, '2017-02-06', '2017-03-06', '', 174, 0, 12, b'0', b'0', '2017-02-06 14:03:55', 2, 1, '', 0, '', '', b'0', '0'),
(13, 'reloj casio 5468', 100, 4, '2017-01-10', '2017-02-10', 'en caja', 120, 0, 13, b'0', b'0', '2017-02-06 14:19:15', 2, 1, '', 0, '', '', b'0', '0'),
(14, 'horno microondas color negro', 105, 4, '2017-01-11', '2017-02-11', '', 126, 0, 14, b'0', b'0', '2017-02-06 14:38:37', 2, 1, '', 0, '', '', b'0', '0'),
(15, 'camara digital finefix ', 25.2, 4, '2017-03-25', '2017-03-07', 'Se adelantó S/. 4.80 de S/. 30.00 el día 25/03/2017<br>con memoria 8gb ', 34.8, 0, 15, b'0', b'1', '2017-02-07 17:46:08', 2, 1, '', 0, '', '', b'0', '0'),
(16, 'licuadora taurus + celular huawey', 100, 4, '2017-02-07', '2017-03-07', '', 116, 0, 16, b'0', b'0', '2017-02-07 17:51:17', 2, 1, '', 0, '', '', b'0', '0'),
(17, 'laptop dell inspiracion 14 serie 3000', 336, 4, '2017-03-06', '2017-03-06', 'Se adelantó S/. 64.00 de S/. 400.00 el día 06/03/2017<br>funda+cargador+mause', 464, 0, 17, b'0', b'0', '2017-02-07 17:54:08', 2, 1, '', 0, '', '', b'0', '0'),
(18, '2 incubadoras automaticas', 170, 4, '2017-02-07', '2017-03-07', '', 197.2, 0, 18, b'0', b'0', '2017-02-07 17:57:25', 2, 1, '', 285.6, 'manrique', '2017-06-05 21:14:51', b'0', '0'),
(19, 'frigobar daewoo color negro', 168, 4, '2017-03-13', '2017-03-08', 'Se adelantó S/. 32.00 de S/. 200.00 el día 13/03/2017<br>', 232, 0, 19, b'0', b'0', '2017-02-08 15:20:29', 2, 1, '', 248.64, 'manrique', '2017-06-05 21:15:06', b'0', '0'),
(20, 'laptop acer', 200, 4, '2017-02-09', '2017-03-09', 'le faltan tres teclas ', 232, 0, 20, b'0', b'1', '2017-02-09 16:39:09', 2, 1, '', 0, '', '', b'0', '0'),
(21, 'celular huawei ascend g7 blanco', 80, 4, '2017-02-09', '2017-03-09', 'pantalla rota ', 92.8, 0, 15, b'0', b'0', '2017-02-09 18:34:00', 2, 1, '', 0, '', '', b'0', '0'),
(22, 'bluray sony', 450, 4, '2016-09-05', '2016-10-05', 'laptop hpi3 con cargador (vendido)', 540, 0, 21, b'0', b'0', '2017-02-09 19:12:54', 2, 1, '', 0, '', '', b'0', '0'),
(23, 'camara profesional canon', 700, 4, '2016-10-28', '2016-11-28', '', 840, 0, 22, b'0', b'0', '2017-02-09 19:16:56', 2, 1, '', 0, '', '', b'0', '0'),
(51, 'olla renaware', 300, 4, '2016-09-30', '2016-10-30', '', 360, 0, 50, b'0', b'0', '2017-02-10 16:27:41', 2, 1, '', 0, '', '', b'0', '0'),
(26, 'calculadora hp', 80, 4, '2016-10-04', '2016-11-04', '', 96, 0, 25, b'0', b'0', '2017-02-09 19:30:32', 2, 1, '', 0, '', '', b'0', '0'),
(27, '2 mochilas', 590, 4, '2016-10-11', '2016-11-11', 'hom dvd+camara+laptop', 708, 0, 26, b'0', b'0', '2017-02-09 19:33:28', 2, 1, '', 0, '', '', b'0', '0'),
(28, 'equipo de sonido+monitor+blueray+computador i5', 520, 4, '2016-10-13', '2016-11-13', '', 624, 0, 27, b'0', b'0', '2017-02-09 19:35:55', 2, 1, '', 0, '', '', b'0', '0'),
(29, 'laptop hp', 200, 4, '2016-10-25', '2016-11-25', '', 240, 0, 28, b'0', b'0', '2017-02-09 19:41:13', 2, 1, '', 0, '', '', b'0', '0'),
(30, 'equipo de sonido lg', 200, 4, '2016-10-26', '2016-11-26', '', 240, 0, 29, b'0', b'0', '2017-02-09 19:43:04', 2, 1, '', 0, '', '', b'0', '0'),
(31, 'plancha +equipo', 200, 4, '2016-11-11', '2016-12-11', '', 240, 0, 30, b'0', b'0', '2017-02-09 19:46:47', 2, 1, '', 0, '', '', b'0', '0'),
(32, 'cpu intel i3+monitor advance', 200, 4, '2016-11-14', '2016-12-14', '', 240, 0, 31, b'0', b'0', '2017-02-09 19:50:07', 2, 1, '', 0, '', '', b'0', '0'),
(33, 'laptop i7+impresora hp nueva', 1000, 4, '2016-11-15', '2016-12-15', '', 1200, 0, 32, b'0', b'0', '2017-02-09 19:52:48', 2, 1, '', 0, '', '', b'0', '0'),
(34, 'tv samsung', 150, 4, '2016-11-15', '2016-12-15', '', 180, 0, 33, b'0', b'0', '2017-02-09 19:54:32', 2, 1, '', 0, '', '', b'0', '0'),
(35, 'olla arrocera 6l', 70, 4, '2016-11-16', '2016-12-16', '', 84, 0, 34, b'0', b'0', '2017-02-09 19:56:18', 2, 1, '', 0, '', '', b'0', '0'),
(36, 'guitarra electronica', 100, 4, '2016-11-17', '2016-12-17', '', 120, 0, 35, b'0', b'0', '2017-02-09 19:58:05', 2, 1, '', 0, '', '', b'0', '0'),
(37, 'terma solar', 500, 4, '2016-11-19', '2016-12-19', '', 600, 0, 36, b'0', b'1', '2017-02-09 19:59:57', 2, 1, '', 0, '', '', b'0', '0'),
(38, 'impresora canon hg 2410', 60, 4, '2016-11-28', '2016-12-28', '', 72, 0, 37, b'0', b'0', '2017-02-09 20:01:48', 2, 1, '', 124.8, 'manrique', '2017-06-05 21:14:06', b'0', '0'),
(50, '2 tiquetera epson', 600, 4, '2016-09-28', '2016-10-28', '', 720, 0, 49, b'0', b'0', '2017-02-10 16:25:09', 2, 1, '', 1464, 'manrique', '2017-06-05 21:13:51', b'0', '0'),
(40, 'laptop toshiba core i3', 350, 4, '2016-12-03', '2017-01-03', '', 420, 0, 39, b'0', b'0', '2017-02-09 20:05:36', 2, 1, '', 0, '', '', b'0', '0'),
(41, 'tablet acer', 80, 4, '2016-12-12', '2017-01-12', '', 96, 0, 40, b'0', b'0', '2017-02-09 20:07:48', 2, 1, '', 147.2, 'Yuri Pahola', '2017-05-04 18:59:03', b'0', '0'),
(42, 'gps', 68, 4, '2017-02-15', '2017-01-12', 'Se adelantó S/. 32.00 de S/. 100.00 el día 15/02/2017<br>', 120, 0, 41, b'0', b'0', '2017-02-09 20:09:44', 2, 1, '', 0, '', '', b'0', '0'),
(43, 'laptop i5 con cargador y estuche azul', 550, 4, '2016-12-14', '2017-01-14', '', 660, 0, 42, b'0', b'0', '2017-02-09 20:11:52', 2, 1, '', 0, '', '', b'0', '0'),
(44, 'laptop amd a8 con cargador y estuche', 400, 4, '2016-12-15', '2017-01-15', '', 480, 0, 43, b'0', b'0', '2017-02-09 20:13:30', 2, 1, '', 0, '', '', b'0', '0'),
(45, 'camara sony lens g', 180, 4, '2016-12-16', '2017-01-16', '', 216, 0, 44, b'0', b'1', '2017-02-09 20:15:14', 2, 1, '', 0, '', '', b'0', '0'),
(46, 'tablet teraware', 60, 4, '2016-12-01', '2017-01-01', '', 72, 0, 45, b'0', b'0', '2017-02-09 20:17:13', 2, 1, '', 0, '', '', b'0', '0'),
(47, 'laptop con mochila ploma', 800, 4, '2017-01-06', '2017-02-06', '', 960, 0, 46, b'0', b'0', '2017-02-09 20:19:06', 2, 1, '', 0, '', '', b'0', '0'),
(48, 'laptop hp pavilion', 500, 4, '2017-01-07', '2017-02-07', '', 600, 0, 47, b'0', b'0', '2017-02-09 20:20:40', 2, 1, '', 0, '', '', b'0', '0'),
(49, 'Tv 3d Smart Samsung 40 6400', 500, 4, '2017-02-10', '2017-03-10', 'Accesorios completos(en caja)', 580, 0, 48, b'0', b'0', '2017-02-10 12:33:53', 2, 1, '', 0, '', '', b'0', '0'),
(52, 'laptop hp i3', 300, 4, '2016-12-01', '2017-02-01', '', 408, 0, 51, b'0', b'0', '2017-02-10 16:47:31', 2, 1, '', 0, '', '', b'0', '0'),
(53, 'camara canon powershota2600', 60, 4, '2017-02-10', '2017-02-11', 'estuche y cargador', 62.4, 0, 52, b'0', b'0', '2017-02-10 18:18:09', 2, 1, '', 0, '', '', b'0', '0'),
(54, 'tablet advance con cargador', 80, 4, '2017-02-11', '2017-03-11', '', 92.8, 0, 53, b'0', b'0', '2017-02-11 12:10:09', 2, 1, '', 0, '', '', b'0', '0'),
(55, 'celular mod XD', 19, 4, '2017-03-03', '2017-02-14', 'Se adelantó S/. 1.00 de S/. 20.00 el día 03/03/2017<br>Se adelantó S/. 1.00 de S/. 21.00 el día 03/03/2017<br>Se adelantó S/. 2.00 de S/. 23.00 el día 03/03/2017<br>Se adelantó S/. 1.00 de S/. 24.00 el día 24/02/2017<br>Se adelantó S/. 1.00 de S/. 25.00 el día 24/02/2017<br>', 26, 71.5, 54, b'0', b'0', '2017-02-13 12:43:31', 9, 2, '', 0, '', '', b'0', '0'),
(56, 'taza chocolate', 160, 4, '2017-02-24', '2017-01-11', 'Se adelantó S/. 30.00 de S/. -30.00 el día 24/02/2017<br>Se adelantó S/. 10.00 de S/. -20.00 el día 24/02/2017<br>Se adelantó S/. 15.00 de S/. -5.00 el día 24/02/2017<br>Se adelantó S/. 1.00 de S/. -4.00 el día 24/02/2017<br>Se adelantó S/. 1.00 de S/. -3.00 el día 24/02/2017<br>Se adelantó S/. 1.00 de S/. -2.00 el día 24/02/2017<br>Se adelantó S/. 2.00 de S/. 0.00 el día 24/02/2017<br>Se adelantó S/. 1 del monto S/. 1.00el día 15/02/2017<br>Se adelantó S/. 1 del monto S/. 2.00el día 15/02/2017<br>', 2.08, 0, 54, b'0', b'0', '2017-02-13 12:44:43', 9, 2, '', 224, '9', '2017-05-04 12:54:14', b'0', '0'),
(57, 'bicicleta bianss', 50, 4, '2017-02-13', '2017-03-13', '', 58, 0, 55, b'0', b'0', '2017-02-13 15:32:14', 2, 1, '', 82, 'manrique', '2017-06-05 21:16:16', b'0', '0'),
(58, 'laptop hp envy i7', 700, 4, '2017-02-13', '2017-03-13', 'laptop con cargador', 812, 0, 56, b'0', b'1', '2017-02-13 16:03:28', 2, 1, '', 0, '', '', b'0', '0'),
(59, 'pc +pantalla lg+parlantes+transformador', 170, 4, '2017-02-20', '2017-03-13', 'Se adelantó S/. 30.00 de S/. 200.00 el día 20/02/2017<br>', 232, 0, 57, b'0', b'0', '2017-02-13 16:34:48', 2, 1, '', 0, '', '', b'0', '0'),
(60, 'tv lg 32ln540b', 300, 4, '2017-02-13', '2017-03-13', '', 348, 0, 58, b'0', b'0', '2017-02-13 17:27:00', 2, 1, '', 0, '', '', b'0', '0'),
(61, 'laptop hp pavilion 14 notebook con cargador', 300, 4, '2017-02-13', '2017-03-13', '', 348, 0, 59, b'0', b'0', '2017-02-13 17:49:57', 2, 1, '', 444, 'Yuri Pahola', '2017-05-04 18:44:44', b'0', '0'),
(62, 'laptop advance i3 con cargador', 107, 4, '2017-02-13', '2017-03-13', '', 124.12, 0, 57, b'0', b'0', '2017-02-13 19:00:44', 2, 1, '', 0, '', '', b'0', '0'),
(63, 'televisor noc le19w037', 100, 4, '2017-02-14', '2017-03-14', 'con su control', 116, 0, 60, b'0', b'0', '2017-02-14 11:06:32', 2, 1, '', 0, '', '', b'0', '0'),
(64, 'Celular modelo zte', 140, 4, '2017-02-15', '2017-04-08', 'Se adelantó S/. 10.00 de S/. 150.00 el día 15/02/2017<br>Se adelantó S/. 1.00 de S/. 151.00 el día 15/02/2017<br>Se adelantó S/. 3.00 de S/. 154.00 el día 15/02/2017<br>Se adelantó S/. 4.00 de S/. 158.00 el día 15/02/2017<br>Se adelantó S/. 2.00 de S/. 160.00 el día 15/02/2017<br>Se adelantó S/. 4.00 de S/. 164.00 el día 15/02/2017<br>Se adelantó S/. 1.00 de S/. 165.00 el día 15/02/2017<br>Se adelantó S/. 1.00 de S/. 166.00 el día 15/02/2017<br>Se adelantó S/. 5.00 de S/. 171.00 el día 15/02/2017<br>Se adelantó S/. 3.00 de S/. 174.00 el día 15/02/2017<br>Se adelantó S/. 3.00 de S/. 177.00 el día 15/02/2017<br>Se adelantó S/. 2.00 del monto S/. 179.00 el día 15/02/2017<br>Se adelantó S/. 1 del monto S/. 180.00el día 15/02/2017<br>', 237.6, 71, 54, b'0', b'0', '2017-02-14 14:05:50', 9, 2, '', 0, '', '', b'0', '0'),
(65, 'Deja su cubo antiesess', 100, 4, '2017-02-14', '2017-02-15', 'Deja su cubo antiesess', 104, 44, 61, b'0', b'1', '2017-02-14 14:31:23', 10, 3, '', 0, '', '', b'0', '0'),
(66, 'pc advance intel core duo', 80, 4, '2017-02-15', '2017-03-15', '', 92.8, 0, 62, b'0', b'0', '2017-02-15 13:30:53', 2, 1, '', 131.2, 'manrique', '2017-06-05 21:16:38', b'0', '0'),
(67, 'taladro repli. bosch(verde)', 40, 4, '2017-02-16', '2017-03-16', '', 46.4, 0, 63, b'0', b'0', '2017-02-16 13:58:46', 2, 1, '', 65.6, 'Yuri Paola', '2017-06-08 19:44:36', b'0', '0'),
(68, 'maquina vibradora para piso marca honda G200', 200, 4, '2017-02-20', '2017-03-20', '', 232, 0, 64, b'0', b'0', '2017-02-20 10:27:08', 2, 1, '', 0, '', '', b'0', '0'),
(69, 'celular huawei cun l03', 100, 4, '2017-02-20', '2017-03-20', '', 116, 0, 65, b'0', b'1', '2017-02-20 12:20:53', 2, 1, '', 0, '', '', b'0', '0'),
(70, 'equipo de sonido l6-3420 + olla arrocera rc 6', 101, 4, '2017-04-03', '2017-02-14', 'Se adelantó S/. 24.00 de S/. 125.00 el día 03/04/2017<br>Se adelantó S/. 25.00 de S/. 150.00 el día 04/03/2017<br>', 180, 0, 66, b'0', b'1', '2017-02-20 12:57:52', 2, 1, '', 0, '', '', b'0', '0'),
(71, 'guitarra electroacustica villalta', 100, 4, '2017-02-20', '2017-04-20', '', 136, 0, 67, b'0', b'0', '2017-02-20 14:08:25', 2, 1, '', 144, 'Yuri Pahola', '2017-05-04 18:41:46', b'0', '0'),
(72, '3 celulares htc desire 626s', 500, 4, '2017-02-21', '2017-03-21', '', 580, 0, 68, b'0', b'0', '2017-02-21 09:52:04', 2, 1, '', 0, '', '', b'0', '0'),
(73, 'llanta+gata+triangulo+estuche (5 herramientas) ', 100, 4, '2017-02-22', '2017-03-22', '', 116, 0, 69, b'0', b'0', '2017-02-22 16:50:08', 2, 1, '', 0, '', '', b'0', '0'),
(74, '2 cpu + 2 monitor samsung', 1000, 4, '2017-02-23', '2017-03-23', '', 1160, 0, 4, b'0', b'0', '2017-02-23 19:01:40', 2, 1, '', 1600, 'Yuri Paola', '2017-06-08 19:46:49', b'0', '0'),
(75, 'laptop hp probook 4440 con escuche', 300, 4, '2017-02-24', '2017-03-24', '', 348, 0, 70, b'0', b'0', '2017-02-24 09:49:29', 2, 1, '', 0, '', '', b'0', '0'),
(76, 'camara canon sx50hs', 160, 4, '2017-04-28', '2017-03-24', 'Se adelantó S/. 90.00 de S/. 250.00 el día 28/04/2017<br>', 290, 90, 71, b'0', b'1', '2017-02-24 18:07:38', 2, 1, '', 0, '', '', b'0', '0'),
(77, 'moto pulsar rs 200 amarillo+casco+tarjeta+soat', 2000, 4, '2017-02-24', '2017-03-24', '', 2320, 0, 72, b'0', b'0', '2017-02-24 19:26:10', 2, 1, '', 0, '', '', b'0', '0'),
(78, 'moto pulsar rs 200 amarillo+casco+tarjeta+soat', 2000, 4, '2017-02-24', '2017-03-24', '', 2320, 0, 73, b'0', b'0', '2017-02-24 19:26:11', 2, 1, '', 0, '', '', b'0', '0'),
(79, 'computador de cabina', 400, 4, '2017-02-28', '2017-03-28', '', 464, 0, 65, b'0', b'0', '2017-02-28 12:02:23', 2, 1, '', 640, 'Yuri Paola', '2017-06-08 19:47:46', b'0', '0'),
(80, 'Playstation 3 Cech-4001b con comando y 5 juegos', 150, 4, '2017-02-28', '2017-03-28', 'comando Genérico ', 174, 0, 74, b'0', b'0', '2017-02-28 13:56:43', 2, 1, '', 0, '', '', b'0', '0'),
(81, '40 pares de zapatos', 600, 4, '2017-03-01', '2017-05-01', '', 816, 0, 75, b'0', b'0', '2017-03-01 13:09:24', 2, 1, '', 888, 'Yuri Paola', '2017-05-22 10:01:57', b'0', '0'),
(82, 'auto daewoo lanos se- verde : placa. a4N-580', 1500, 4, '2017-03-02', '2017-03-09', '', 1560, 0, 76, b'0', b'0', '2017-03-02 17:55:57', 2, 1, '', 0, '', '', b'0', '0'),
(83, 'laptop levono con cargador y estuche', 300, 4, '2017-03-04', '2017-04-04', '', 360, 0, 77, b'0', b'0', '2017-03-04 12:37:50', 2, 1, '', 0, '', '', b'0', '0'),
(84, 'televisor lg 32lh570b', 300, 4, '2017-03-04', '2017-04-04', '', 360, 0, 78, b'0', b'0', '2017-03-04 13:31:52', 2, 1, '', 468, 'Yuri Paola', '2017-06-08 19:48:15', b'0', '0'),
(85, 'filtro de agua rena ware', 400, 4, '2017-03-06', '2017-04-06', '', 480, 0, 79, b'0', b'0', '2017-03-06 11:56:47', 2, 1, '', 0, '', '', b'0', '0'),
(86, '3 carritos emolienteros', 1200, 4, '2017-03-07', '2017-04-07', '', 1440, 0, 80, b'0', b'1', '2017-03-07 15:37:02', 2, 1, '', 0, '', '', b'0', '0'),
(87, 'laptop lenovo ideapad 510 i7', 756, 4, '2017-04-11', '2017-04-09', 'Se adelantó S/. 144.00 de S/. 900.00 el día 11/04/2017<br>', 1080, 0, 81, b'0', b'0', '2017-03-09 12:46:02', 2, 1, '', 1028.16, 'Yuri Paola', '2017-06-12 10:11:51', b'0', '0'),
(88, 'microondas daewoo kor-190 n', 30, 4, '2017-03-09', '2017-04-09', '', 36, 0, 82, b'0', b'0', '2017-03-09 17:25:37', 2, 1, '', 45.6, 'manrique', '2017-06-05 21:17:05', b'0', '0'),
(89, 'laptop asus m:x70i s:8220', 850, 4, '2017-03-10', '2017-04-10', '', 1020, 0, 83, b'0', b'1', '2017-03-10 11:51:58', 2, 1, '', 0, '', '', b'0', '0'),
(90, 'cocina de mesa visioneer', 50, 4, '2017-03-10', '2017-04-10', '', 60, 0, 84, b'0', b'1', '2017-03-10 14:36:47', 2, 1, '', 0, '', '', b'0', '0'),
(91, '2 congas con funda-lpy y 1 parche remo', 300, 4, '2017-03-10', '2017-04-10', '', 360, 0, 85, b'0', b'0', '2017-03-10 15:03:28', 2, 1, '', 396, 'Yuri Pahola', '2017-05-04 18:42:06', b'0', '0'),
(92, 'televisor samsung un40jh5005g', 150, 4, '2017-03-11', '2017-04-11', '', 180, 0, 86, b'0', b'0', '2017-03-11 10:06:40', 2, 1, '', 0, '', '', b'0', '0'),
(93, 'laptop toshiba c55-b5117km', 140, 4, '2017-03-11', '2017-04-11', '', 168, 0, 87, b'0', b'0', '2017-03-11 11:06:00', 2, 1, '', 0, '', '', b'0', '0'),
(94, 'fotocopiadora hp laser jet m5035 mfp', 350, 4, '2017-03-13', '2017-04-13', '', 420, 0, 88, b'0', b'0', '2017-03-13 20:37:08', 2, 1, '', 532, 'Yuri Paola', '2017-06-08 19:48:32', b'0', '0'),
(95, 'chocolatera rena ware, thomas th-135, imaco grill ig2815a', 230, 4, '2017-03-14', '2017-04-14', '', 276, 0, 89, b'0', b'0', '2017-03-14 14:05:24', 2, 1, '', 340.4, 'Yuri Paola', '2017-06-05 14:47:13', b'0', '0'),
(96, 'laptop lenovo z50', 1100, 4, '2017-03-17', '2017-04-17', '', 1320, 0, 90, b'0', b'1', '2017-03-17 13:13:35', 2, 1, '', 0, '', '', b'0', '0'),
(97, 'televisor samsung + celular sony c5', 500, 4, '2017-03-17', '2017-04-17', '', 600, 0, 91, b'0', b'0', '2017-03-17 14:00:39', 2, 1, '', 0, '', '', b'0', '0'),
(98, 'laptop hp pavilion amd 8', 400, 4, '2017-03-18', '2017-04-18', '', 480, 0, 92, b'0', b'0', '2017-03-18 13:03:20', 2, 1, '', 0, '', '', b'0', '0'),
(99, 'laptop hp 455 + impresora hp 3545', 150, 4, '2017-03-20', '2017-04-20', '', 180, 0, 3, b'0', b'0', '2017-03-20 15:47:54', 2, 1, '', 0, '', '', b'0', '0'),
(100, 'teodolito nikon ne-20h + nivel sokkia 262358', 1000, 4, '2017-03-20', '2017-04-20', '', 1200, 0, 93, b'0', b'1', '2017-03-20 20:19:35', 2, 1, '', 0, '', '', b'0', '0'),
(101, 'laptop hp 15 i3', 350, 4, '2017-03-21', '2017-04-21', '', 420, 0, 94, b'0', b'1', '2017-03-21 14:24:41', 2, 1, '', 0, '', '', b'0', '0'),
(102, 'Camara Nikon Coolpix S9900', 200, 4, '2017-03-22', '2017-04-22', '', 240, 0, 95, b'0', b'0', '2017-03-22 12:26:06', 2, 1, '', 0, '', '', b'0', '0'),
(103, 'televisor lg 24MT47A-pm', 200, 4, '2017-03-23', '2017-04-23', '', 240, 0, 96, b'0', b'1', '2017-03-23 16:48:58', 2, 1, '', 0, '', '', b'0', '0'),
(104, 'camara canon eos 40d', 280, 4, '2017-03-24', '2017-04-24', '', 336, 0, 97, b'0', b'0', '2017-03-24 18:18:45', 2, 1, '', 0, '', '', b'0', '0'),
(105, 'zapatos 43', 300, 4, '2017-03-24', '2017-04-24', '', 360, 0, 75, b'0', b'0', '2017-03-24 19:36:17', 2, 1, '', 408, 'Yuri Paola', '2017-05-22 10:01:32', b'0', '0'),
(106, 'laptop toshiba satellite p55t-c5114', 1200, 10, '2017-03-25', '2017-03-27', '', 1320, 0, 98, b'0', b'0', '2017-03-25 16:09:31', 2, 1, '', 0, '', '', b'0', '0'),
(107, 'televisor panasonic', 130, 4, '2017-03-28', '2017-03-30', '', 135.2, 0, 99, b'0', b'1', '2017-03-28 11:39:31', 2, 1, '', 0, '', '', b'0', '0'),
(108, 'toshiba laptop satelite c55 b5115km', 200, 4, '2017-03-28', '2017-04-28', '', 240, 0, 100, b'0', b'1', '2017-03-28 13:39:52', 2, 1, '', 0, '', '', b'0', '0'),
(109, 'lavadora samsung 5k', 70, 4, '2017-03-28', '2017-04-28', '', 84, 0, 101, b'0', b'0', '2017-03-28 17:17:27', 2, 1, '', 0, '', '', b'0', '0'),
(110, 'Estacion Total  Es-105 Incluye Tripode Prisma Baston', 2500, 4, '2017-03-29', '2017-04-29', '', 3000, 0, 102, b'0', b'0', '2017-03-29 12:58:02', 2, 1, '', 0, '', '', b'0', '0'),
(111, 'laotop lenovo g40-80(con cargador)+ lenovo g580', 800, 4, '2017-03-31', '2017-04-30', '', 960, 0, 103, b'0', b'1', '2017-03-31 16:59:12', 2, 1, '', 0, '', '', b'0', '0'),
(112, 'Martillo Bosch Gsh 16', 500, 4, '2017-03-31', '2017-04-30', '', 600, 0, 104, b'0', b'0', '2017-03-31 17:15:52', 2, 1, '', 620, 'Yuri Paola', '2017-05-11 10:23:14', b'0', '0'),
(113, 'play 3 cech-4011b (mando+juego+2cables)', 300, 4, '2017-03-31', '2017-04-30', '', 360, 0, 105, b'0', b'1', '2017-03-31 17:29:07', 2, 1, '', 0, '', '', b'0', '0'),
(114, 'celular samsung j7', 250, 4, '2017-03-31', '2017-04-14', '', 270, 0, 106, b'0', b'0', '2017-03-31 19:43:38', 2, 1, '', 0, '', '', b'0', '0'),
(115, 'Laptop HP Probook 4440 Core i5', 300, 4, '2017-04-01', '2017-04-29', 'Laptop sin cargador, solo se le entrega S/. 250. En espera de que entregue cargador lunes 4 de abril.', 348, 0, 70, b'0', b'0', '2017-04-01 09:20:44', 2, 1, '', 0, '', '', b'0', '0'),
(116, 'Laptop Core i3 Lenovo - SN CB25877919', 300, 4, '2017-04-01', '2017-04-29', 'Con cargador', 348, 0, 107, b'0', b'1', '2017-04-01 15:56:38', 2, 1, '', 0, '', '', b'0', '0'),
(117, 'Laptop Core i3 lenovo con cargador', 300, 4, '2017-04-01', '2017-04-29', '', 348, 0, 107, b'0', b'0', '2017-04-01 15:59:14', 2, 1, '', 420, 'Yuri Paola', '2017-06-08 19:51:05', b'0', '0'),
(118, 'hp pavilion x360', 500, 10, '2017-04-03', '2017-04-05', '', 550, 0, 108, b'0', b'0', '2017-04-03 18:56:12', 2, 1, '', 0, '', '', b'0', '0'),
(119, 'estacion total sokkia cx-105', 2500, 4, '2017-04-04', '2017-04-11', '', 2600, 0, 102, b'0', b'0', '2017-04-04 17:20:33', 2, 1, '', 0, '', '', b'0', '0'),
(120, 'camara canon revel t5', 600, 4, '2017-04-04', '2017-05-04', '', 720, 0, 109, b'0', b'0', '2017-04-05 13:02:52', 2, 1, '', 816, 'Yuri Paola', '2017-05-31 12:40:38', b'0', '0'),
(121, 'terma', 80, 10, '2017-04-17', '2017-04-06', 'Se adelantó S/. 10.00 de S/. 90.00 el día 17/04/2017<br>Se adelantó S/. 10.00 de S/. 100.00 el día 17/04/2017<br>', 110, 0, 54, b'0', b'0', '2017-04-05 16:57:12', 9, 2, '', 88, 'Yuri Pahola', '2017-05-04 18:57:00', b'0', '0'),
(122, 'motocicleta yamaha fz negro(b4-2306)', 544, 4, '2017-06-07', '2017-05-07', 'Se adelantó S/. 128.00 de S/. 672.00 el día 07/06/2017<br>Se adelantó S/. 128.00 de S/. 800.00 el día 04/05/2017<br>', 960, 256, 110, b'0', b'1', '2017-04-07 10:14:24', 2, 1, '', 0, '', '', b'0', '0'),
(123, 'calculadora hp', 70, 4, '2017-04-07', '2017-05-07', '', 84, 0, 111, b'0', b'1', '2017-04-07 12:12:28', 2, 1, '', 0, '', '', b'0', '0'),
(124, 'televisor 42\" lg', 250, 4, '2017-04-07', '2017-05-07', '', 300, 0, 112, b'0', b'0', '2017-04-07 12:38:52', 2, 1, '', 0, '', '', b'0', '0'),
(125, 'una motocicleta marca barsha modelo lamger r17 placa 1028-0w', 350, 4, '2017-04-09', '2017-05-04', 'DEJA TARJETA DE PROPIEDAD, CASCO Y LLAVE.', 406, 0, 113, b'0', b'0', '2017-04-09 14:03:07', 2, 1, '', 0, '', '', b'0', '0'),
(126, 'cpu + monitor + teclado + mause', 150, 4, '2017-04-10', '2017-05-10', '', 180, 0, 114, b'0', b'1', '2017-04-10 17:44:43', 2, 1, '', 0, '', '', b'0', '0'),
(127, 'mini componente samsung mx-j630 230', 64, 4, '2017-05-11', '2017-04-24', 'Se adelantó S/. 16.00 de S/. 80.00 el día 11/05/2017<br>', 86.4, 16, 115, b'0', b'1', '2017-04-10 18:37:40', 2, 1, '', 0, '', '', b'0', '0'),
(128, 'bicicleta de niño + bluray + reloj seiko', 0, 18, '2017-04-17', '2017-05-11', 'Se adelantó S/. 200.00 de S/. 200.00 el día 17/04/2017<br>', 380, 0, 116, b'0', b'0', '2017-04-11 10:27:05', 2, 1, '', 0, '', '', b'0', '0'),
(129, 'celular j3 + llanta + 5 herramientas', 200, 4, '2017-04-11', '2017-05-11', 'Se canceló el interés S/. 40.00 de el día 12/05/2017<br>', 240, 0, 69, b'0', b'1', '2017-04-11 10:50:20', 2, 1, '12/05/2017', 0, '', '', b'0', '0'),
(130, 'tablet acer', 50, 4, '2017-04-11', '2017-05-11', '', 60, 0, 117, b'0', b'1', '2017-04-11 11:07:14', 2, 1, '', 0, '', '', b'0', '0'),
(131, 'moto r15', 1500, 4, '2017-04-11', '2017-05-11', '', 1800, 0, 118, b'0', b'1', '2017-04-11 14:57:54', 2, 1, '', 0, '', '', b'0', '0'),
(132, 'laptop hp +cargador', 300, 4, '2017-04-08', '2017-05-08', '', 360, 0, 119, b'0', b'0', '2017-04-11 17:25:14', 2, 1, '', 0, '', '', b'0', '0'),
(133, 'laptop hp 14 r003la', 200, 4, '2017-04-11', '2017-05-11', '', 240, 0, 120, b'0', b'0', '2017-04-11 18:27:59', 2, 1, '', 0, '', '', b'0', '0'),
(134, 'guitarra eléctrica jt 690 -fr', 200, 4, '2017-04-12', '2017-04-26', '', 216, 0, 121, b'0', b'0', '2017-04-12 11:53:44', 2, 1, '', 0, '', '', b'0', '0'),
(135, 'laptop toshiba satellite con cargador', 150, 4, '2017-04-12', '2017-04-26', '', 162, 0, 122, b'0', b'1', '2017-04-12 12:02:53', 2, 1, '', 0, '', '', b'0', '0'),
(136, 'gpsmap 62s garmin', 200, 4, '2017-04-12', '2017-04-30', '', 224, 0, 123, b'0', b'1', '2017-04-12 12:24:15', 2, 1, '', 0, '', '', b'0', '0'),
(137, 'laptop lenovo + disco duro 1tb', 300, 4, '2017-04-12', '2017-05-12', '', 360, 0, 124, b'0', b'1', '2017-04-12 16:28:45', 2, 1, '', 0, '', '', b'0', '0'),
(138, 'laptop hp blanco con cargador', 150, 4, '2017-04-12', '2017-05-12', '', 180, 0, 125, b'0', b'1', '2017-04-12 17:35:50', 2, 1, '', 0, '', '', b'0', '0'),
(139, 'laptop asus x540l i3', 350, 4, '2017-04-13', '2017-05-13', '', 420, 0, 126, b'0', b'0', '2017-04-13 10:58:40', 2, 1, '', 0, '', '', b'0', '0'),
(140, 'caja con herramientas', 90, 4, '2017-04-17', '2017-05-01', 'Se adelantó S/. 10.00 de S/. 100.00 el día 17/04/2017<br>', 108, 0, 54, b'0', b'0', '2017-04-17 11:20:34', 9, 2, '', 101, '', '2017-05-04 17:56:38', b'0', '0'),
(141, 'consola de videojuegos', 300, 4, '2017-04-17', '2017-04-18', '', 330, 0, 54, b'0', b'0', '2017-04-17 12:09:16', 9, 2, '', 336, '', '2017-05-04 13:55:54', b'0', '0'),
(142, 'celular motorola x1', 200, 4, '2017-04-17', '2017-04-18', 'Se adelantó S/. 10.00 de S/. 210.00 el día 17/04/2017<br>Se adelantó S/. 5.00 de S/. 215.00 el día 17/04/2017<br>Se adelantó S/. 3.00 de S/. 218.00 el día 17/04/2017<br>Se adelantó S/. 6.00 de S/. 224.00 el día 17/04/2017<br>Se adelantó S/. 11.00 de S/. 235.00 el día 17/04/2017<br>Se adelantó S/. 5.00 de S/. 240.00 el día 17/04/2017<br>Se adelantó S/. 10.00 de S/. 250.00 el día 17/04/2017<br>', 275, 35, 127, b'0', b'0', '2017-04-17 12:33:11', 9, 2, '', 224, '', '2017-05-04 18:10:34', b'0', '0'),
(143, 'ps 3 cech4211a + bluray lg bp450', 150, 4, '2017-04-17', '2017-04-18', '', 165, 0, 128, b'0', b'1', '2017-04-17 12:44:39', 2, 1, '', 0, '', '', b'0', '0'),
(144, 'horno microondas ge jes700pgk', 70, 4, '2017-04-17', '2017-04-18', '', 77, 0, 129, b'0', b'0', '2017-04-17 13:10:48', 2, 1, '', 0, '', '', b'0', '0'),
(145, 'laptop hp amd a8', 400, 4, '2017-04-17', '2017-04-18', '', 440, 0, 130, b'0', b'1', '2017-04-17 18:52:03', 2, 1, '', 0, '', '', b'0', '0'),
(146, 'camara filmadora nikon + cargador', 200, 4, '2017-04-18', '2017-04-19', '', 220, 0, 95, b'0', b'1', '2017-04-18 15:50:25', 2, 1, '', 0, '', '', b'0', '0'),
(147, 'laptop toshiba satellite ci5 u940 - sp4301gl', 300, 4, '2017-04-19', '2017-04-20', '', 330, 0, 131, b'0', b'0', '2017-04-19 16:54:45', 2, 1, '', 348, 'Yuri Paola', '2017-05-17 18:38:45', b'0', '0'),
(148, 'laptop toshiba s55t-a', 350, 4, '2017-04-20', '2017-04-21', '', 385, 0, 132, b'0', b'0', '2017-04-20 11:26:55', 2, 1, '', 420, 'Yuri Paola', '2017-05-25 10:21:18', b'0', '0'),
(149, 'tv samsung 45\"', 300, 4, '2017-04-20', '2017-04-21', '', 330, 0, 133, b'0', b'1', '2017-04-20 16:37:59', 2, 1, '', 0, '', '', b'0', '0'),
(150, 'mini componente lg mc 8360', 300, 4, '2017-04-20', '2017-04-21', '', 330, 0, 134, b'0', b'0', '2017-04-20 18:29:19', 2, 1, '', 0, '', '', b'0', '0'),
(151, 'bluray bdp 1300', 70, 4, '2017-04-21', '2017-04-22', '', 77, 0, 116, b'0', b'0', '2017-04-21 09:57:44', 2, 1, '', 78.4, 'Yuri Pahola', '2017-05-12 16:17:02', b'0', '0'),
(152, 'camara canon t5', 300, 4, '2017-04-21', '2017-04-22', '', 330, 0, 109, b'0', b'0', '2017-04-21 17:42:29', 2, 1, '', 372, 'Yuri Paola', '2017-05-31 12:41:25', b'0', '0'),
(153, 'moto honda cb190r', 1700, 4, '2017-04-22', '2017-04-23', '', 1870, 0, 135, b'0', b'0', '2017-04-22 12:33:52', 2, 1, '', 2040, 'Yuri Pahola', '2017-05-23 14:27:49', b'0', '0'),
(154, 'laptop hp mas cargador', 150, 4, '2017-04-24', '2017-04-25', 'Se canceló el interés S/. 36.00 de el día 02/06/2017<br>Se canceló el interés S/. 36.00 de el día 02/06/2017<br>', 165, 0, 136, b'0', b'1', '2017-04-24 14:03:59', 2, 1, '02/06/2017', 0, '', '', b'0', '0'),
(155, 'cpu amd atlon + monitor aoc', 200, 4, '2017-04-26', '2017-04-27', '', 220, 0, 137, b'0', b'1', '2017-04-26 16:42:52', 2, 1, '', 0, '', '', b'0', '0'),
(156, 'laptop hp', 300, 4, '2017-04-27', '2017-04-28', '', 330, 0, 138, b'0', b'1', '2017-04-27 09:54:04', 2, 1, '', 0, '', '', b'0', '0'),
(157, 'pc 4 con 2 monitor', 240, 4, '2017-04-27', '2017-04-28', '', 264, 0, 139, b'0', b'1', '2017-04-27 10:01:44', 2, 1, '', 0, '', '', b'0', '0'),
(158, 'microondas samsung cheff', 50, 4, '2017-04-27', '2017-04-28', '', 55, 0, 140, b'0', b'1', '2017-04-27 12:06:04', 2, 1, '', 0, '', '', b'0', '0'),
(159, 'guitarra electroacustica godin + calculadora hp 50g', 90, 4, '2017-04-27', '2017-04-28', '', 99, 0, 141, b'0', b'0', '2017-04-27 17:09:21', 2, 1, '', 104.4, 'Yuri Paola', '2017-05-25 12:43:59', b'0', '0'),
(160, 'estación total leica ts06 plush', 4000, 4, '2017-04-28', '2017-04-29', '', 4400, 0, 102, b'0', b'0', '2017-04-28 13:30:45', 2, 1, '', 4400, 'Yuri Paola', '2017-05-11 10:23:42', b'0', '0'),
(161, 'pedal soul food, pedal bluesky, balanza, amplificador', 300, 4, '2017-04-28', '2017-04-29', '', 330, 0, 142, b'0', b'0', '2017-04-28 14:50:03', 2, 1, '', 0, '', '', b'0', '0'),
(162, 'celular sony m4 aqua', 100, 4, '2017-04-28', '2017-04-29', '', 110, 0, 143, b'0', b'0', '2017-04-28 18:55:43', 2, 1, '', 110, 'Yuri Paola', '2017-05-11 10:24:10', b'0', '0'),
(163, 'una extractora y una computadora', 200, 4, '2017-04-29', '2017-04-30', '', 220, 0, 144, b'0', b'1', '2017-04-29 12:24:16', 2, 1, '', 0, '', '', b'0', '0'),
(164, 'laptop hp i5 con estuche (sin cargador)', 250, 4, '2017-04-02', '2017-05-03', '', 275, 0, 70, b'0', b'0', '2017-05-02 09:20:19', 2, 1, '', 330, 'Yuri Paola', '2017-05-24 11:14:01', b'0', '0'),
(165, 'celular samsung j7', -20, 4, '2017-05-06', '2017-05-04', 'Se adelantó S/. 220.00 de S/. 200.00 el día 06/05/2017<br>', 220, 220, 145, b'0', b'0', '2017-05-03 14:26:13', 2, 1, '', 220, 'Mateo Quincho', '2017-05-27 10:33:49', b'0', '0'),
(166, 'mouse gamer', 50, 4, '2017-05-04', '2017-05-05', '', 55, 0, 54, b'0', b'0', '2017-05-04 13:43:19', 9, 2, '', 55, 'demo', '2017-05-10 12:24:27', b'0', '0'),
(167, 'cd de musica', 15, 4, '2017-05-04', '2017-05-05', '', 16.5, 0, 54, b'0', b'0', '2017-05-04 13:44:50', 9, 2, '', 16.5, '', '2017-05-04 18:02:16', b'0', '0'),
(168, 'laptop hp amd a8', 300, 4, '2017-05-08', '2017-05-09', '', 330, 0, 146, b'0', b'0', '2017-05-08 11:24:29', 2, 1, '', 330, 'Yuri Paola', '2017-05-15 10:21:10', b'0', ''),
(169, '2 laptop lenovo b40-80 corei3', 800, 4, '2017-05-08', '2017-05-09', '', 880, 0, 147, b'0', b'1', '2017-05-08 14:43:09', 2, 1, '', 0, '', '', b'0', ''),
(170, 'laptop hp 14-am012la', 500, 4, '2017-05-09', '2017-05-10', '', 550, 0, 148, b'0', b'0', '2017-05-09 18:53:24', 2, 1, '', 560, 'Mateo Quincho', '2017-05-27 11:20:09', b'0', ''),
(171, 'celular s7 edge samsung', 500, 4, '2017-05-10', '2017-05-11', '', 550, 0, 149, b'0', b'0', '2017-05-10 17:17:11', 2, 1, '', 550, 'Yuri Paola', '2017-05-20 12:26:23', b'0', ''),
(172, 'celular abc', 15, 4, '2017-06-14', '2017-05-12', 'Se canceló el interés S/. 3.60 de el día 14/06/2017<br>Se canceló el interés S/. 3.60 de el día 14/06/2017<br>Se canceló el interés S/. 3.60 de el día 14/06/2017<br>Se canceló el interés S/. 3.60 de el día 14/06/2017<br>Se canceló el interés S/. 3.60 de el día 14/06/2017<br>Se canceló el interés S/. 3.60 de el día 14/06/2017<br>Se canceló el interés S/. 1.50 de el día 11/05/2017<br>Se canceló el interés S/. 16.50 de el día 11/05/2017<br>', 16.5, 0, 54, b'0', b'1', '2017-05-11 11:17:08', 9, 2, '14/06/2017', 0, '', '', b'0', ''),
(173, 'laptop tosbiba satellite l755', 400, 4, '2017-05-11', '2017-05-12', '', 440, 0, 150, b'0', b'1', '2017-05-11 13:37:13', 2, 1, '', 0, '', '', b'0', ''),
(174, 'tv l 43\" 4k', 400, 4, '2017-05-11', '2017-05-12', '', 440, 0, 151, b'0', b'1', '2017-05-11 15:09:36', 2, 1, '', 0, '', '', b'0', ''),
(175, 'mochila', 150, 4, '2017-06-14', '2017-05-12', 'Se canceló el interés S/. 30.00 de el día 14/06/2017<br>', 165, 0, 54, b'0', b'1', '2017-05-11 17:54:02', 9, 2, '14/06/2017', 0, '', '', b'0', ''),
(176, 'pantalla vieja', 150, 4, '2017-05-11', '2017-05-12', 'Se canceló el interés S/. 15.00 de el día 15/05/2017<br>', 165, 0, 54, b'0', b'1', '2017-05-11 18:38:01', 9, 2, '15/05/2017', 0, '', '', b'0', ''),
(177, 'estación total leica ts06 plush', 4500, 4, '2017-05-12', '2017-05-13', '', 4950, 0, 102, b'0', b'0', '2017-05-12 14:55:11', 2, 1, '', 5220, 'Yuri Paola', '2017-06-07 18:51:30', b'0', ''),
(178, 'samsung j7', 170, 4, '2017-05-12', '2017-05-13', '', 187, 0, 106, b'0', b'0', '2017-05-12 16:58:56', 2, 1, '', 187, 'Yuri Paola', '2017-05-22 13:02:14', b'0', ''),
(179, 'camara canon vixia', 250, 4, '2017-05-12', '2017-05-13', '', 275, 0, 152, b'0', b'1', '2017-05-12 17:32:41', 2, 1, '', 0, '', '', b'0', ''),
(180, 'laptop compaq', 100, 4, '2017-05-12', '2017-05-13', '', 110, 0, 153, b'0', b'1', '2017-05-12 18:10:03', 2, 1, '', 0, '', '', b'0', ''),
(181, 'parlante amplicador', 350, 4, '2017-05-13', '2017-05-14', '', 385, 0, 154, b'0', b'1', '2017-05-13 12:56:26', 2, 1, '', 0, '', '', b'0', ''),
(182, 'laptop toshiba satellite l505', 300, 4, '2017-05-15', '2017-05-16', '', 330, 0, 155, b'0', b'0', '2017-05-15 09:44:20', 2, 1, '', 330, 'Yuri Paola', '2017-05-23 17:58:17', b'0', ''),
(183, 'tv smart lg 42lb5800', 400, 4, '2017-05-15', '2017-05-16', '', 440, 0, 156, b'0', b'0', '2017-05-15 12:25:44', 2, 1, '', 440, 'Yuri Paola', '2017-05-19 18:10:41', b'0', ''),
(184, 'laptop y40-70 lenovo con cargador y estuche', 600, 4, '2017-05-15', '2017-05-16', '', 660, 0, 157, b'0', b'1', '2017-05-15 17:26:59', 2, 1, '', 0, '', '', b'0', ''),
(185, 'laptop sony vaio i5', 200, 4, '2017-05-16', '2017-06-16', '', 240, 0, 158, b'0', b'1', '2017-05-16 14:44:46', 3, 1, '', 0, '', '', b'0', ''),
(186, 'laptop toshiba', 60, 4, '2017-05-17', '2017-05-25', '', 66, 0, 159, b'0', b'1', '2017-05-17 18:28:56', 3, 1, '', 0, '', '', b'0', ''),
(187, 'lg xboom cm 8460', 600, 4, '2017-05-18', '2017-05-19', 'TOTALMENTE NUEVO SELLADO', 660, 0, 160, b'0', b'1', '2017-05-18 15:35:53', 1, 3, '', 0, '', '', b'0', ''),
(188, 'ps2 sony y bluray sony', 200, 4, '2017-05-18', '2017-05-19', '', 220, 0, 161, b'0', b'1', '2017-05-18 16:05:05', 1, 3, '', 0, '', '', b'0', ''),
(189, 'laptop hp pavilion 14', 250, 4, '2017-05-19', '2017-06-19', '', 300, 0, 59, b'0', b'0', '2017-05-19 11:30:52', 3, 1, '', 275, '', '2017-06-01 15:25:05', b'0', ''),
(190, 'televisor lg 47ln5400', 300, 4, '2017-05-19', '2017-06-19', '', 360, 0, 156, b'0', b'0', '2017-05-19 12:49:21', 3, 1, '', 330, 'Yuri Paola', '2017-05-22 16:53:16', b'0', ''),
(191, 'maquina soldar miller maxstar 200', 300, 4, '2017-05-19', '2017-06-19', '', 360, 0, 162, b'0', b'1', '2017-05-19 14:15:00', 3, 1, '', 0, '', '', b'0', ''),
(192, 'moto pulsar 200 amarillo', 2500, 4, '2017-05-19', '2017-06-19', '', 3000, 0, 163, b'0', b'0', '2017-05-19 15:40:26', 3, 1, '', 2750, 'Yuri Paola', '2017-05-31 19:32:01', b'0', ''),
(193, 'laptop lenovo g50-45', 350, 4, '2017-05-19', '2017-06-19', '', 420, 0, 164, b'0', b'0', '2017-05-19 16:30:14', 3, 1, '', 385, 'Yuri Paola', '2017-05-29 17:33:06', b'0', ''),
(194, 'celulares (sony c5,samdung j5)', 300, 4, '2017-05-19', '2017-06-19', '', 360, 0, 91, b'0', b'0', '2017-05-19 17:08:42', 3, 1, '', 330, 'Yuri Paola', '2017-05-25 10:40:49', b'0', ''),
(195, 'tv led nex 32\"', 150, 4, '2017-05-20', '2017-05-21', '', 165, 0, 165, b'0', b'1', '2017-05-20 10:17:37', 3, 1, '', 0, '', '', b'0', ''),
(196, 'laptop toshiba', 70, 4, '2017-05-20', '2017-05-21', '', 77, 0, 166, b'0', b'1', '2017-05-20 13:59:32', 3, 1, '', 0, '', '', b'0', ''),
(197, 'laptop lenovo z51', 300, 4, '2017-05-22', '2017-06-22', '', 360, 0, 143, b'0', b'1', '2017-05-22 12:16:32', 3, 1, '', 0, '', '', b'0', ''),
(198, '2 estación total ...', 6000, 4, '2017-05-22', '2017-06-22', '', 7200, 0, 102, b'0', b'1', '2017-05-22 16:39:56', 3, 1, '', 0, '', '', b'0', ''),
(199, 'teodolito topcon dt200+ nivel topcon+ campana de cocina', 1500, 4, '2017-05-22', '2017-06-22', '7 objetos ', 1800, 0, 167, b'0', b'1', '2017-05-22 18:13:17', 3, 1, '', 0, '', '', b'0', ''),
(200, 'tv aoc 19\"', 50, 4, '2017-05-22', '2017-06-22', '', 60, 0, 168, b'0', b'0', '2017-05-22 19:07:09', 3, 1, '', 55, '', '2017-05-29 17:31:05', b'0', ''),
(201, 'calculadora texas instruments nspire ti nspire cx.', 150, 4, '2017-05-23', '2017-06-23', '', 180, 0, 169, b'0', b'1', '2017-05-23 18:09:44', 3, 1, '', 0, '', '', b'0', ''),
(202, 'tablet samsung gt p7300', 100, 4, '2017-05-24', '2017-06-24', '', 120, 0, 170, b'0', b'0', '2017-05-24 14:50:47', 3, 1, '', 110, 'Yuri Paola', '2017-06-02 18:13:54', b'0', ''),
(203, 'demoledor master tools mt 90k', 200, 4, '2017-05-24', '2017-06-24', '', 240, 0, 171, b'0', b'1', '2017-05-24 15:04:50', 3, 1, '', 0, '', '', b'0', ''),
(204, 'pcu i5 + monitor lg 19\"', 200, 4, '2017-05-25', '2017-06-25', '', 240, 0, 149, b'0', b'1', '2017-05-25 16:47:11', 3, 1, '', 0, '', '', b'0', ''),
(205, 'laptop toshiba i3 + iphone 5c', 400, 4, '2017-05-25', '2017-06-25', '', 480, 0, 172, b'0', b'1', '2017-05-25 17:27:36', 3, 1, '', 0, '', '', b'0', ''),
(206, 'tablet chuwi + mouse', 100, 4, '2017-05-25', '2017-06-25', '', 120, 0, 173, b'0', b'1', '2017-05-25 18:59:01', 3, 1, '', 0, '', '', b'0', ''),
(207, 'tv smart lg 42lb5800', 450, 4, '2017-05-26', '2017-06-26', '', 540, 0, 156, b'0', b'0', '2017-05-26 09:33:03', 3, 1, '', 495, 'Yuri Paola', '2017-05-29 18:08:13', b'0', ''),
(208, 'saxo hamaha yas 23', 500, 4, '2017-05-26', '2017-06-26', '', 600, 0, 174, b'0', b'1', '2017-05-26 10:29:05', 3, 1, '', 0, '', '', b'0', ''),
(209, 'guitarra electrica freeman', 50, 4, '2017-05-26', '2017-06-26', '', 60, 0, 175, b'0', b'1', '2017-05-26 15:40:53', 3, 1, '', 0, '', '', b'0', ''),
(210, 'pc', 500, 4, '2017-05-27', '2017-05-28', '', 550, 0, 54, b'0', b'0', '2017-05-27 09:15:30', 9, 2, '', 550, 'demo', '2017-05-27 09:15:41', b'0', ''),
(211, 'juego de 5 ollas rena ware', 1000, 4, '2017-05-29', '2017-06-29', '', 1200, 0, 176, b'0', b'1', '2017-05-29 11:56:44', 3, 1, '', 0, '', '', b'0', ''),
(212, 'estación total leica ts06 plush', 6000, 4, '2017-05-29', '2017-06-29', '', 7200, 0, 102, b'0', b'0', '2017-05-29 18:21:35', 3, 1, '', 6600, '', '2017-06-07 18:41:17', b'0', ''),
(213, 'pieza d emano de alta luz lez + celular lg k10', 100, 4, '2017-05-30', '2017-06-30', '', 120, 0, 177, b'0', b'1', '2017-05-30 09:26:58', 3, 1, '', 0, '', '', b'0', ''),
(214, 'tv lg 32lh500b', 200, 4, '2017-05-30', '2017-06-30', '', 240, 0, 178, b'0', b'1', '2017-05-30 15:41:29', 3, 1, '', 0, '', '', b'0', ''),
(215, 'hp todo en uno 20 b403la', 200, 4, '2017-05-30', '2017-06-30', '', 240, 0, 179, b'0', b'0', '2017-05-30 17:18:04', 3, 1, '', 220, 'Yuri Paola', '2017-06-06 17:43:35', b'0', ''),
(216, 'celular j7', 250, 4, '2017-05-30', '2017-06-30', '', 300, 0, 106, b'0', b'1', '2017-05-30 19:15:08', 3, 1, '', 0, '', '', b'0', ''),
(217, 'ps 3 con 1 mando y cables', 200, 4, '2017-06-02', '2017-07-02', '', 240, 0, 180, b'0', b'1', '2017-06-02 10:18:34', 3, 1, '', 0, '', '', b'0', ''),
(218, 'laptop lenovo b40-80', 300, 4, '2017-06-02', '2017-07-02', '', 360, 0, 68, b'0', b'1', '2017-06-02 13:09:13', 3, 1, '', 0, '', '', b'0', ''),
(219, 'taladro 20v dewalt dcd776-026022', 150, 4, '2017-06-02', '2017-07-02', '', 180, 0, 181, b'0', b'1', '2017-06-02 17:54:37', 3, 1, '', 0, '', '', b'0', ''),
(220, 'calculadora hp g50+audifono sony+dni+celular xperia', 200, 4, '2017-06-02', '2017-07-02', '', 240, 0, 182, b'0', b'1', '2017-06-02 18:11:22', 3, 1, '', 0, '', '', b'0', ''),
(221, 'celular samsung j5', 180, 4, '2017-06-03', '2017-06-04', '', 198, 0, 183, b'0', b'0', '2017-06-03 11:49:01', 12, 1, '', 198, '', '2017-06-12 16:40:41', b'0', ''),
(222, 'televisor  lg smart 42\"', 350, 4, '2017-06-03', '2017-06-04', 'CONTROL ', 385, 0, 104, b'0', b'1', '2017-06-03 12:16:19', 12, 1, '', 0, '', '', b'0', ''),
(223, 'tv lg 42lb65', 400, 4, '2017-06-05', '2017-07-05', '', 480, 0, 184, b'0', b'1', '2017-06-05 10:50:27', 3, 1, '', 0, '', '', b'0', ''),
(224, 'laptop compaq amd', 200, 4, '2017-06-05', '2017-07-05', '', 240, 0, 185, b'0', b'1', '2017-06-05 11:50:28', 3, 1, '', 0, '', '', b'0', ''),
(225, 'impresora + maquina de ejercicio', 120, 4, '2017-06-05', '2017-07-05', '', 144, 0, 186, b'0', b'1', '2017-06-05 12:17:00', 3, 1, '', 0, '', '', b'0', ''),
(226, 'laptop hp envy dv6 notebook pc', 350, 4, '2017-06-05', '2017-07-05', '', 420, 0, 187, b'0', b'0', '2017-06-05 15:11:28', 3, 1, '', 385, 'Yuri Paola', '2017-06-13 17:35:35', b'0', ''),
(227, 'celular zte blade l5', 70, 4, '2017-06-05', '2017-07-05', '', 84, 0, 188, b'0', b'1', '2017-06-05 17:22:02', 3, 1, '', 0, '', '', b'0', ''),
(228, 'camara+cargador+estuche', 50, 4, '2017-06-05', '2017-07-05', '', 60, 0, 189, b'0', b'1', '2017-06-05 18:31:09', 3, 1, '', 0, '', '', b'0', ''),
(229, 'auto kia rio placa w2z-360', 3200, 4, '2017-06-05', '2017-06-19', '', 3520, 0, 190, b'0', b'1', '2017-06-05 18:47:18', 3, 1, '', 0, '', '', b'0', ''),
(230, 'celular samsung galaxy j2', 150, 4, '2017-06-06', '2017-07-06', '', 180, 0, 112, b'0', b'1', '2017-06-06 14:24:55', 3, 1, '', 0, '', '', b'0', ''),
(231, 'tv. sony kdl 40w605b', 300, 4, '2017-06-06', '2017-07-06', '', 360, 0, 191, b'0', b'1', '2017-06-06 16:05:21', 3, 1, '', 0, '', '', b'0', ''),
(232, 'tv smart lg 42lb5800', 450, 4, '2017-06-07', '2017-07-07', '', 540, 0, 156, b'0', b'0', '2017-06-07 18:04:01', 3, 1, '', 495, '', '2017-06-13 18:30:08', b'0', ''),
(233, 'estacion total sokkia cx-105', 8000, 4, '2017-05-29', '2017-06-07', '', 8800, 0, 102, b'0', b'1', '2017-06-07 18:47:14', 3, 1, '', 0, '', '', b'0', ''),
(234, 'gps garmin oregon 650', 450, 4, '2017-06-07', '2017-07-07', '', 540, 0, 102, b'0', b'1', '2017-06-07 18:56:53', 3, 1, '', 0, '', '', b'0', ''),
(235, 'equipo de sonido 7 parlantes panasonic + celular huawei lua u03', 450, 4, '2017-06-08', '2017-07-08', '', 540, 0, 192, b'0', b'0', '2017-06-08 10:45:43', 3, 1, '', 495, 'Yuri Paola', '2017-06-08 11:19:24', b'0', ''),
(236, 'equipo de sonido 7 parlantes panasonic + celular huawei lua u03', 300, 4, '2017-06-08', '2017-07-08', '', 360, 0, 192, b'0', b'1', '2017-06-08 11:20:13', 3, 1, '', 0, '', '', b'0', ''),
(237, 'celular lg x screen', 100, 4, '2017-06-08', '2017-07-08', '', 120, 0, 193, b'0', b'1', '2017-06-08 13:11:20', 3, 1, '', 0, '', '', b'0', ''),
(238, '2 guitarras+ps2 con tres mandos+dos timon+teclado', 300, 4, '2017-06-08', '2017-07-08', '', 360, 0, 194, b'0', b'1', '2017-06-08 17:34:34', 3, 1, '', 0, '', '', b'0', ''),
(239, 'cpu', 40, 4, '2017-06-09', '2017-06-10', '', 44, 0, 195, b'0', b'1', '2017-06-09 14:01:01', 12, 1, '', 0, '', '', b'0', ''),
(240, 'detector de metales y detector de bullyser', 800, 4, '2017-06-09', '2017-06-10', '', 880, 0, 196, b'0', b'1', '2017-06-09 14:38:29', 12, 1, '', 0, '', '', b'0', ''),
(241, 'celular lg spidit', 60, 4, '2017-06-10', '2017-07-10', '', 72, 0, 197, b'0', b'1', '2017-06-12 09:37:51', 3, 1, '', 0, '', '', b'0', ''),
(242, 'roto martillo percutor kd975ka-b2c', 100, 4, '2017-06-10', '2017-07-10', '', 120, 0, 188, b'0', b'1', '2017-06-12 09:39:53', 3, 1, '', 0, '', '', b'0', ''),
(243, 'moto negro smaach125', 400, 4, '2017-06-10', '2017-07-10', '', 480, 0, 198, b'0', b'1', '2017-06-12 09:51:42', 3, 1, '', 0, '', '', b'0', ''),
(244, 'celular lg k8', 80, 4, '2017-06-12', '2017-07-12', '', 96, 0, 197, b'0', b'1', '2017-06-12 10:00:16', 3, 1, '', 0, '', '', b'0', ''),
(245, 'carro kia rio rojo', 4100, 4, '2017-06-12', '2017-06-19', '', 4510, 0, 190, b'0', b'1', '2017-06-12 18:52:55', 3, 1, '', 0, '', '', b'0', ''),
(246, 'tablet ipad md369e/a', 170, 4, '2017-06-13', '2017-07-07', '', 197.2, 0, 199, b'0', b'1', '2017-06-13 10:43:05', 3, 1, '', 0, '', '', b'0', ''),
(247, 'celular huawei vns l23', 150, 4, '2017-06-13', '2017-07-13', '', 180, 0, 200, b'0', b'0', '2017-06-13 11:02:18', 3, 1, '', 165, 'Yuri Paola', '2017-06-14 10:44:05', b'0', ''),
(248, 'guitarra acústica + celular azumi', 50, 4, '2017-06-13', '2017-07-13', '', 60, 0, 201, b'0', b'1', '2017-06-13 16:25:50', 3, 1, '', 0, '', '', b'0', ''),
(249, 'tv smart lg 42lb5800', 450, 4, '2017-06-13', '2017-07-13', '', 540, 0, 156, b'0', b'1', '2017-06-13 18:31:21', 3, 1, '', 0, '', '', b'0', ''),
(251, 'estacion total topcon cygnus ks-102', 4000, 4, '2017-06-14', '2017-07-14', '', 4800, 0, 202, b'0', b'1', '2017-06-14 11:37:24', 3, 1, '', 0, '', '', b'0', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

DROP TABLE IF EXISTS `sucursal`;
CREATE TABLE `sucursal` (
  `idSucursal` int(11) NOT NULL,
  `sucNombre` varchar(50) NOT NULL,
  `sucLugar` varchar(200) NOT NULL,
  `sucActivo` bit(1) NOT NULL DEFAULT b'1' COMMENT '1 para activo'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

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
CREATE TABLE `usuario` (
  `idUsuario` int(11) NOT NULL,
  `usuNombres` varchar(100) NOT NULL,
  `usuApellido` varchar(100) NOT NULL,
  `usuNick` varchar(50) NOT NULL,
  `usuPass` varchar(100) NOT NULL,
  `usuPoder` int(11) NOT NULL,
  `idSucursal` int(11) NOT NULL,
  `usuActivo` bit(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `usuNombres`, `usuApellido`, `usuNick`, `usuPass`, `usuPoder`, `idSucursal`, `usuActivo`) VALUES
(1, 'Carlos Alex', 'Pariona Valencia', 'cpariona', 'b84d8185d9fc5d64de366cc8a06d8ef1', 1, 3, b'1'),
(2, 'Yuri Paola', 'Huaycuch Valenzuela', 'sucursal1', '69447f927b74151c1aeb626f31202e0a', 2, 1, b'1'),
(3, 'Yuri Paola', 'Huaycuch Valenzuela', 'yhuaycuch', 'a47d9e83b186dfe6f6d2c792c1fda2a0', 2, 1, b'1'),
(10, 'Manrique o', 'aumbbel', 'amo', '43e423ee04be24b417b0c5eb71ad4464', 1, 3, b'1'),
(9, 'demo', 'demo', 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 2, 2, b'1'),
(8, 'manrique', 'Aumbbel', 'aumbbel', 'ef6299c9e7fdae6d775819ce1e2620b8', 1, 3, b'1'),
(12, 'Mateo Quincho', 'Pilar Maria', 'pmateo', '787dab1236880babb61463fffd4bc784', 2, 1, b'1');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Cliente`
--
ALTER TABLE `Cliente`
  ADD PRIMARY KEY (`idCliente`),
  ADD UNIQUE KEY `idCliente` (`idCliente`);

--
-- Indices de la tabla `poder`
--
ALTER TABLE `poder`
  ADD PRIMARY KEY (`idPoder`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`idProducto`);

--
-- Indices de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  ADD PRIMARY KEY (`idSucursal`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idUsuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Cliente`
--
ALTER TABLE `Cliente`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=203;
--
-- AUTO_INCREMENT de la tabla `poder`
--
ALTER TABLE `poder`
  MODIFY `idPoder` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `idProducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=252;
--
-- AUTO_INCREMENT de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  MODIFY `idSucursal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
