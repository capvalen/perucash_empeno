-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 09-11-2017 a las 15:40:14
-- Versión del servidor: 5.6.38
-- Versión de PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
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

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `coincidePass` (IN `texto` VARCHAR(200), IN `idUser` INT)  NO SQL
SELECT CASE md5(texto) WHEN usuPass THEN 1
 ELSE 0 END as result
 from usuario
 where idUsuario = idUser$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `contarNoFinalizados` (IN `idSuc` INT)  NO SQL
SELECT count(idproducto) as Num
FROM `producto` p 
where prodactivo = 1 and datediff( now(), prodfechainicial )<=30
and idSucursal =idSuc$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `contarVencidos` (IN `idSuc` INT)  NO SQL
SELECT count(p.idproducto) as Num
FROM `producto` p
inner join prestamo pre on pre.idProducto= p.idProducto
inner join desembolso d on d.idPrestamo= pre.idPrestamo
where prodactivo = 1 and datediff( now(), desFechaContarInteres )>30 and esCompra=0
and p.idSucursal =idSuc$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `datosBasicosUsuario` (IN `idUser` INT)  NO SQL
SELECT u.idUsuario, usunombres, usuapellido, usupoder, u.idsucursal, sucnombre
FROM usuario u inner join sucursal s on u.idSucursal=s.idSucursal
where u.idusuario=idUser and u.usuactivo=1$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `eliminarProductoBD` (IN `idProd` INT)  NO SQL
BEGIN
DELETE FROM `producto` WHERE
`idProducto`=idProd;
END$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `encontrarCliente` (IN `dni` VARCHAR(8))  NO SQL
SELECT * FROM `Cliente` WHERE clidni = dni$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `ingresarDineroEntrada` (IN `valor` FLOAT, IN `motivo` TEXT, IN `idUser` INT, IN `idSuc` INT)  NO SQL
begin
INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idSucursal`) VALUES
(null,0,6,now(),valor, motivo,1, idUser, idSuc);

set @caja=last_insert_id();
select @caja;
end$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `ingresarDineroSalida` (IN `valor` FLOAT, IN `motivo` TEXT, IN `idUser` INT, IN `idSuc` INT)  NO SQL
begin INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idSucursal`) VALUES (null,0,5,now(),valor, motivo,1, idUser, idSuc); set @caja=last_insert_id(); select @caja; end$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `ingresarPagoaCuenta` (IN `idPro` INT, IN `montoIn` FLOAT, IN `interesIn` FLOAT, IN `adelanto` FLOAT, IN `idUser` INT)  NO SQL
INSERT INTO `PagoaCuenta`(`idPago`, `idProducto`, `pagMontoInicial`, `pagInteresInicial`, `pagFechaRegistro`, `pagAdelanto`, `idUsuario`)
VALUES (null,idPro,montoIn,interesIn,now(),adelanto,idUser)$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarAdelantoAProducto` (IN `idProd` INT, IN `nuevoAdelanto` FLOAT, IN `idUser` VARCHAR(200))  NO SQL
begin
UPDATE `producto` SET 
`prodObservaciones` =concat('Se adelantó S/. ',round(nuevoAdelanto,2),' de S/. ' , round(`prodMontoEntregado`,2), ' el día ', DATE_FORMAT(now(), "%d/%m/%Y"), '<br>', `prodObservaciones`),
`prodFechaInicial`=now(),
`prodMontoEntregado`=`prodMontoEntregado`-nuevoAdelanto,
`prodAdelanto`=`prodAdelanto` +nuevoAdelanto

WHERE 
`idProducto`= idProd;


INSERT INTO `reportes_producto`(
    `idReporte`, `idProducto`, `idDetalleReporte`, `repoValorMonetario`, `repoFechaOcurrencia`, `repoUsuario`,
`repoUsuarioComentario`, `repoQueConfirma`, `repoQuienConfirma`) VALUES (
     null,idProd,2,nuevoAdelanto,now(),idUser,
    '',4,'Todavía sin aprobación');

end$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarAmortizacionMixto` (IN `idDesemb` INT, IN `monotoInicial` FLOAT, IN `montoInteres` FLOAT, IN `montoPago` FLOAT, IN `idUser` INT, IN `idProd` INT, IN `usuario` TEXT, IN `idSuc` INT, IN `sobra` FLOAT)  NO SQL
BEGIN
set @idPrest=(select idprestamo from prestamo where idProducto=idProd);

INSERT INTO `PagoaCuenta`(`idPago`, `idDesembolso`, `pagMonto`, `pagInteres`, `pagCantidadPagada`, `pagDebeInteres`, `pagFechaRegistro`, `idUsuario`, `idTipoPago`)
VALUES (null,idDesemb,monotoInicial,montoInteres,montoInteres,0,now(),idUser,10);

INSERT INTO `PagoaCuenta`(`idPago`, `idDesembolso`, `pagMonto`, `pagInteres`, `pagCantidadPagada`, `pagDebeInteres`, `pagFechaRegistro`, `idUsuario`, `idTipoPago`)
VALUES (null,idDesemb,monotoInicial,montoInteres,sobra,0,now(),idUser,7);

set @pago=last_insert_id();

INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) VALUES (null,idProd,10,now(),montoInteres,'',1,idUser,0,idSuc);

INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) VALUES (null,idProd,7,now(),sobra,'',1,idUser,0,idSuc);

INSERT INTO `reportes_producto`(
    `idReporte`, `idProducto`, `idDetalleReporte`, `repoValorMonetario`, `repoFechaOcurrencia`, `repoUsuario`,
`repoUsuarioComentario`, `repoQueConfirma`, `repoQuienConfirma`) VALUES (
     null,idProd,10,
     montoPago,now(),usuario,
    '',4,'Todavía sin aprobación');


UPDATE `prestamo` SET 
`preCapital`=`preCapital`-sobra
WHERE idProducto=idProd;

UPDATE `producto` SET
`prodMontoEntregado`=`prodMontoEntregado`-sobra,
`prodFechaInicial`=now(),
`prodUltimaFechaInteres`= DATE_FORMAT(now(), "%d/%m/%Y"),
`prodActivo`=0
WHERE 
`idProducto` = idProd;

UPDATE `desembolso` SET
/* No tomcamos en la tabla desembolso para que quede como historico
`descapital`=`descapital`-sobra,*/
`desFechaContarInteres`=now()
WHERE  `idPrestamo`=@idPrest;

select @pago;



END$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarAmortizacionPocoInteres` (IN `idDesemb` INT, IN `monotoInicial` FLOAT, IN `montoInteres` FLOAT, IN `montoPago` FLOAT, IN `idUser` INT, IN `idProd` INT, IN `usuario` TEXT, IN `idSuc` INT)  NO SQL
BEGIN
INSERT INTO `PagoaCuenta`(`idPago`, `idDesembolso`, `pagMonto`, `pagInteres`, `pagCantidadPagada`, `pagDebeInteres`, `pagFechaRegistro`, `idUsuario`, `idTipoPago`)
VALUES (null,idDesemb,monotoInicial,montoInteres,montoPago,0,now(),idUser,9);

set @pago=last_insert_id();


INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) VALUES (null,idProd,9,now(),montoPago,'',1,idUser,0,idSuc);

INSERT INTO `reportes_producto`(
    `idReporte`, `idProducto`, `idDetalleReporte`, `repoValorMonetario`, `repoFechaOcurrencia`, `repoUsuario`,
`repoUsuarioComentario`, `repoQueConfirma`, `repoQuienConfirma`) VALUES (
     null,idProd,2,
     montoPago,now(),usuario,
    '',4,'Todavía sin aprobación');




select @pago;

END$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarAmortizacionSoloInteres` (IN `idDesemb` INT, IN `monotoInicial` FLOAT, IN `montoInteres` FLOAT, IN `montoPago` FLOAT, IN `idUser` INT, IN `idProd` INT, IN `usuario` TEXT, IN `idSuc` INT)  NO SQL
BEGIN
INSERT INTO `PagoaCuenta`(`idPago`, `idDesembolso`, `pagMonto`, `pagInteres`, `pagCantidadPagada`, `pagDebeInteres`, `pagFechaRegistro`, `idUsuario`, `idTipoPago`)
VALUES (null,idDesemb,monotoInicial,montoInteres,montoPago,0,now(),idUser,10);

set @pago=last_insert_id();

INSERT INTO `PagoaCuenta`(`idPago`, `idDesembolso`, `pagMonto`, `pagInteres`, `pagCantidadPagada`, `pagDebeInteres`, `pagFechaRegistro`, `idUsuario`, `idTipoPago`)
VALUES (null,idDesemb,monotoInicial,montoInteres,montoPago,0,now(),idUser,10);


INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) VALUES (null,idProd,10,now(),montoPago,'',1,idUser,0,idSuc);

INSERT INTO `reportes_producto`(
    `idReporte`, `idProducto`, `idDetalleReporte`, `repoValorMonetario`, `repoFechaOcurrencia`, `repoUsuario`,
`repoUsuarioComentario`, `repoQueConfirma`, `repoQuienConfirma`) VALUES (
     null,idProd,1,
     montoPago,now(),usuario,
    '',4,'Todavía sin aprobación');

UPDATE `producto` SET 
`prodFechaInicial`=now(),
`prodUltimaFechaInteres`= DATE_FORMAT(now(), "%d/%m/%Y")
WHERE 
`idProducto` = idProd;

UPDATE `desembolso` SET
/* No tomcamos en la tabla desembolso para que quede como historico
`descapital`=`descapital`-sobra,*/
`desFechaContarInteres`=now()
WHERE  `idPrestamo`=@idPrest;

select @pago;

END$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarAmortizacionTodo` (IN `idDesemb` INT, IN `monotoInicial` FLOAT, IN `montoInteres` FLOAT, IN `montoPago` FLOAT, IN `idUser` INT, IN `idProd` INT, IN `usuario` TEXT, IN `idSuc` INT)  NO SQL
BEGIN
INSERT INTO `PagoaCuenta`(`idPago`, `idDesembolso`, `pagMonto`, `pagInteres`, `pagCantidadPagada`, `pagDebeInteres`, `pagFechaRegistro`, `idUsuario`, `idTipoPago`)
VALUES (null,idDesemb,monotoInicial,montoInteres,montoPago,0,now(),idUser,11);

set @pago=last_insert_id();

INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) VALUES (null,idProd,13,now(),montoPago,'',1,idUser,0,idSuc);

INSERT INTO `reportes_producto`(
    `idReporte`, `idProducto`, `idDetalleReporte`, `repoValorMonetario`, `repoFechaOcurrencia`, `repoUsuario`,
`repoUsuarioComentario`, `repoQueConfirma`, `repoQuienConfirma`) VALUES (
     null,idProd,9,
     montoPago,now(),usuario,
    '',4,'Todavía sin aprobación');

UPDATE `producto` SET 
`prodFechaInicial`=now(),
`prodUltimaFechaInteres`= DATE_FORMAT(now(), "%d/%m/%Y"),
`prodActivo`=0
WHERE 
`idProducto` = idProd;

UPDATE `prestamo`
SET 
`presActivo`=0,`preIdEstado`=11
WHERE `idProducto`=idProd;

select @pago;

END$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarCompraNew` (IN `cliNomb` VARCHAR(50), IN `cliApelli` VARCHAR(50), IN `cliDirec` VARCHAR(200), IN `dni` VARCHAR(50), IN `email` VARCHAR(50), IN `celular` VARCHAR(50), IN `nomProd` VARCHAR(200), IN `montoentregado` FLOAT, IN `fechainicial` DATE, IN `observaciones` TEXT, IN `usuario` INT, IN `idSuc` INT, IN `fechaRegistro` TEXT)  BEGIN
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

INSERT INTO `producto`(`idProducto`, `prodNombre`, `prodMontoEntregado`, `prodInteres`, `prodFechaInicial`, `prodFechaVencimiento`, `prodObservaciones`, `prodMontoPagar`, `idCliente`, `prodActivo`, `prodFechaRegistro`, `idUsuario`, `idSucursal`, `esCompra`)
VALUES
(null,
lower(trim(nomProd)),
montoentregado,0,
fechainicial,'',
observaciones,0,
@id,
1, fechaRegistro, usuario, idSuc,1);

set @comp=last_insert_id();

INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) VALUES (null,@id,3,now(),montoentregado,
'',1,usuario,0,idSuc);

select @comp;

END$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarCompraSolo` (IN `nomProd` VARCHAR(200), IN `montoentregado` FLOAT, IN `fechainicial` DATE, IN `observaciones` TEXT, IN `usuario` INT, IN `idCl` INT, IN `idSuc` INT, IN `fechaRegistro` TEXT)  NO SQL
BEGIN
INSERT INTO `producto`(`idProducto`, `prodNombre`, `prodMontoEntregado`, `prodInteres`, `prodFechaInicial`, `prodFechaVencimiento`, `prodObservaciones`, `prodMontoPagar`, `idCliente`, `prodActivo`, `prodFechaRegistro`, `idUsuario`, `idSucursal`, `esCompra`)
VALUES
(null,
lower(trim(nomProd)),
montoentregado, 0,
fechainicial,'',
observaciones,0,
idCl,
1, fechaRegistro, usuario, idSuc, 1);

set @compr=last_insert_id();
select @compr;

END$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarInteresAdelanto` (IN `idDesemb` INT, IN `monotoInicial` FLOAT, IN `montoInteres` FLOAT, IN `montoPago` FLOAT, IN `idUser` INT, IN `idProd` INT, IN `usuario` TEXT, IN `idSuc` INT)  NO SQL
BEGIN
INSERT INTO `PagoaCuenta`(`idPago`, `idDesembolso`, `pagMonto`, `pagInteres`, `pagCantidadPagada`, `pagDebeInteres`, `pagFechaRegistro`, `idUsuario`, `idTipoPago`)
VALUES (null,idDesemb,monotoInicial,montoInteres,montoPago,0,now(),idUser,9);

set @pago=last_insert_id();

INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) VALUES (null,idProd,9,now(),montoPago,
'',1,usuario,0,idSuc);

INSERT INTO `reportes_producto`(
    `idReporte`, `idProducto`, `idDetalleReporte`, `repoValorMonetario`, `repoFechaOcurrencia`, `repoUsuario`,
`repoUsuarioComentario`, `repoQueConfirma`, `repoQuienConfirma`) VALUES (
     null,idProd,9,
     montoPago,now(),usuario,
    '',4,'Todavía sin aprobación');
    
select @pago;

END$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarNuevoDesembolso` (IN `capital` FLOAT, IN `idProd` INT, IN `idUser` INT, IN `idSuc` INT)  NO SQL
BEGIN

UPDATE `producto` SET `prodMontoPagar` = `prodMontoPagar`+ capital WHERE `producto`.`idProducto` = idProd;

UPDATE `desembolso` SET `desCapital` = `desCapital`+ capital WHERE `desembolso`.`idDesembolso` = idProd;

UPDATE `prestamo` SET `preCapital` = `preCapital`+ capital WHERE `prestamo`.`idPrestamo` = idProd;

INSERT INTO `caja`
(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) VALUES
(null,idProd,14,now(),capital,'',1,idUser,0,idSuc);

END$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarProductoNew` (IN `cliNomb` VARCHAR(50), IN `cliApelli` VARCHAR(50), IN `cliDirec` VARCHAR(200), IN `dni` VARCHAR(50), IN `email` VARCHAR(50), IN `celular` VARCHAR(50), IN `nomProd` VARCHAR(200), IN `montoentregado` FLOAT, IN `interes` FLOAT, IN `montopagar` FLOAT, IN `fechainicial` DATE, IN `feachavencimiento` DATE, IN `observaciones` TEXT, IN `usuario` INT, IN `idSuc` INT, IN `fechaRegistro` TEXT)  BEGIN
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
1, fechaRegistro, usuario, idSuc);

set @prod=last_insert_id();

INSERT INTO `prestamo`(`idPrestamo`, `idProducto`, `idCliente`, `preCapital`, `preFechaInicio`, `idSucursal`, `idUsuario`, `presActivo`, `presObservaciones`, `preIdEstado`) VALUES (null,@prod,@id,montoentregado,fechaRegistro,idSuc,usuario,1,'',1);

set @prestamo=last_insert_id();

INSERT INTO `desembolso`(`idDesembolso`, `idPrestamo`, `desCapital`, `desDebeInteres`, `desFechaContarInteres`, `desFechaRegistro`, `desActivo`, `desUsuario`) VALUES (null,@prestamo,montoentregado,0,fechaRegistro,now(),1,usuario);


select @prod;

END$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarProductoSolo` (IN `nomProd` VARCHAR(200), IN `montoentregado` FLOAT, IN `interes` FLOAT, IN `montopagar` FLOAT, IN `fechainicial` DATE, IN `feachavencimiento` DATE, IN `observaciones` TEXT, IN `usuario` INT, IN `idCl` INT, IN `idSuc` INT, IN `fechaRegistro` TEXT)  NO SQL
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
1, fechaRegistro, usuario, idSuc);

set @prod=last_insert_id();

INSERT INTO `prestamo`(`idPrestamo`, `idProducto`, `idCliente`, `preCapital`, `preFechaInicio`, `idSucursal`, `idUsuario`, `presActivo`, `presObservaciones`, `preIdEstado`) VALUES (null,@prod,idCl,montoentregado,fechaRegistro,idSuc,usuario,1,'',1);

set @prestamo=last_insert_id();

INSERT INTO `desembolso`(`idDesembolso`, `idPrestamo`, `desCapital`, `desDebeInteres`, `desFechaContarInteres`, `desFechaRegistro`, `desActivo`, `desUsuario`) VALUES (null,@prestamo,montoentregado,0,fechaRegistro,now(),1,usuario);

INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) VALUES (null,@prod,3,now(),montoentregado,
'',1,usuario,0,idSuc);

select @prod;

END$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarSucursalNueva` (IN `sucNom` VARCHAR(200), IN `sucLug` VARCHAR(200))  NO SQL
INSERT INTO `sucursal`(`idSucursal`, `sucNombre`, `sucLugar`)
VALUES (null ,sucNom,sucLug)$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `insertarUsuario` (IN `nombre` VARCHAR(50), IN `apellido` VARCHAR(50), IN `nick` VARCHAR(50), IN `pass` VARCHAR(50), IN `poder` INT, IN `idSucur` INT)  NO SQL
INSERT INTO `usuario`(`idUsuario`, `usuNombres`, `usuApellido`,
                      `usuNick`, `usuPass`, `usuPoder`,
                      `idSucursal`, `usuActivo`) 
VALUES (null,apellido,nombre,nick,md5(pass),poder,idSucur,1)$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `inserttempo` (IN `fech` TEXT)  NO SQL
insert into tempo
(idtempo, fecha)
values(null, fech)$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarBuscarIdProducto` (IN `texto` TEXT)  NO SQL
select idproducto, prodnombre, cliapellidos, clinombres, prodMontoEntregado, prodfecharegistro
from producto p
inner join Cliente c on p.idcliente = c.idCliente
where idProducto =texto$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarBuscarNombreProducto` (IN `texto` TEXT)  NO SQL
select idproducto, prodnombre, cliapellidos, clinombres, prodMontoEntregado, prodfecharegistro
from producto p
inner join Cliente c on p.idcliente = c.idCliente
where prodNombre like concat( '%', texto, '%')
order by prodfecharegistro desc$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarDesembolsosPorPrestamos` (IN `idDesemb` TEXT)  NO SQL
SELECT d.*, u.usuNick, p.preFechaInicio FROM `desembolso` d
inner join prestamo p on p.idPrestamo=d.idPrestamo
inner join usuario u on u.idUsuario=d.desusuario

WHERE FIND_IN_SET( `iddesembolso`, idDesemb)$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarMontoPrestamoActual` (IN `idProd` INT)  NO SQL
BEGIN

set @idPrest=(select idprestamo from prestamo where idProducto=idProd);
SELECT desCapital, desFechaContarInteres, pr.preCapital FROM `desembolso` d
inner join prestamo pr on d.idPrestamo=pr.idPrestamo
where d.idPrestamo=@idPrest;

END$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarMovimientoFinal` (IN `idProd` INT)  NO SQL
SELECT rp.*, p.prodNombre, dr.repoDescripcion, dr1.repoDescripcion as 'estadoConfirmacion' 
FROM
producto p inner join 
`reportes_producto` rp on p.idProducto= rp.idProducto
inner join DetalleReporte dr on dr.idDetalleReporte= rp.idDetalleReporte
inner join DetalleReporte dr1 on dr1.idDetalleReporte= rp.repoQueConfirma
where rp.idproducto= idProd
order by repofechaocurrencia desc, repoUsuario asc$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarMovimientosCajaPorIdProducto` (IN `idProd` INT)  NO SQL
SELECT c.*, u.usuNombres, tipoDescripcion, p.idPrestamo FROM `caja` c
inner join tipoProceso tp on tp.idTipoProceso=c.idtipoProceso
inner join prestamo p on c.idproducto=p.idProducto
left join usuario u on u.idUsuario=c.idusuario
where c.idProducto=idProd and c.idtipoproceso<>3$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarMovimientosFechaDia` (IN `fecha` TEXT)  NO SQL
SELECT rp.idproducto, p.prodNombre, dr.repoDescripcion, repoUsuario, repoValorMonetario, repoFechaOcurrencia
FROM `reportes_producto` rp
inner join producto p on p.idproducto=rp.idproducto
inner join DetalleReporte dr on dr.idDetalleReporte=rp.iddetallereporte
where DATE_FORMAT(repoFechaOcurrencia,'%Y-%m-%d')= DATE_FORMAT(fecha, '%Y-%m-%d')
order by repoFechaOcurrencia asc$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarMovimientosRegistradosFechaDia` (IN `fecha` TEXT)  NO SQL
select idproducto, prodNombre, 'Nuevo crédito' as repoDescripcion, usuNombres, prodMontoEntregado, prodfecharegistro
from producto p inner join usuario u on u.idUsuario=p.idUsuario
where DATE_FORMAT(prodfecharegistro,'%Y-%m-%d')= DATE_FORMAT(fecha, '%Y-%m-%d')$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarMovimientosSinAprobar` (IN `idSuc` INT)  NO SQL
SELECT rp.*, p.prodNombre, dr.repoDescripcion, dr1.repoDescripcion as 'estadoConfirmacion' 
FROM
producto p inner join 
`reportes_producto` rp on p.idProducto= rp.idProducto
inner join DetalleReporte dr on dr.idDetalleReporte= rp.idDetalleReporte
inner join DetalleReporte dr1 on dr1.idDetalleReporte= rp.repoQueConfirma
where repofechaConfirma ='' and p.idSucursal= idSuc
order by repofechaocurrencia desc, repoUsuario asc$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarPrestamosPorIdProducto` (IN `idProd` INT)  NO SQL
SELECT p.`idPrestamo`,`preCapital`,`preFechaInicio`, `preIdEstado`, tp.tipoDescripcion, `usunick`,d.desFechaContarInteres
FROM `prestamo` p
inner join tipoProceso tp on p.preIdEstado=tp.idTipoProceso
inner join usuario u on u.idUsuario= p.idUsuario
inner join desembolso d on d.idPrestamo=p.idPrestamo
WHERE 
p.idproducto=idProd$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarProductoEspecificoCliente` (IN `idPro` INT)  NO SQL
SELECT * FROM `producto` p
INNER join Cliente c on p.idcliente = c.idcliente
WHERE 
idproducto= idPro
order by prodfechainicial desc$$

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

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarProductosPorAprobar` (IN `oficina` INT)  NO SQL
SELECT idProducto, prodNombre,
prodCuantoFinaliza, prodQuienFinaliza, prodFechaFinaliza

FROM `producto`
where prodAprobado=0  and prodActivo=0
and idSucursal=oficina
ORDER BY `producto`.`idProducto`  DESC$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarProductosPorCliente` (IN `idCli` INT)  BEGIN
select p.*, s.sucNombre from producto p
inner join sucursal s on p.idSucursal= s.idSucursal
where idcliente =idCli
order by prodFechaRegistro desc;
END$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarProductosPorClienteODni` (IN `campo` TEXT)  NO SQL
select p.idProducto, p.prodNombre, prodFechaRegistro, prodMontoEntregado,  `cliApellidos`,`cliNombres`,prodActivo
from producto p
inner join Cliente c on c.idCliente= p.idCliente
where concat(lower(cliApellidos), ' ', lower(cliNombres)) like concat('%', campo, '%')
or clidni = campo
order by prodActivo desc, cliApellidos asc$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarProductosVencidos` (IN `idSuc` INT)  NO SQL
SELECT p.idproducto, prodNombre,
prodMontoEntregado,
concat(cliapellidos, ', ', clinombres) as propietario,
prodFechainicial, desFechaContarInteres
FROM `producto` p inner join Cliente c
on c.idcliente = p.idcliente
inner join prestamo pre on pre.idProducto= p.idProducto
inner join desembolso d on d.idPrestamo= pre.idPrestamo
where prodactivo = 1 and datediff( now(), desFechaContarInteres )>30 and esCompra=0
and p.idSucursal = idSuc
order by desFechaContarInteres asc$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarTodoProductosPorSuc` (IN `desde` INT, IN `hasta` INT, IN `idSuc` INT)  NO SQL
begin
SELECT p.idProducto, prodNombre, format( prodMontoEntregado,2) as prodMontoEntregado, 
prodFechaInicial, sucNombre, usuNombres, concat(cliApellidos, ' ', cliNombres) as propietario,
prodActivo
FROM `producto` p inner join usuario u on u.idUsuario = p.idUsuario
inner join sucursal s on s.idSucursal = p.idSucursal
inner join Cliente c on c.idCliente = p.idCliente
where p.idSucursal=idSuc and prodActivo<>0
order by prodFechaInicial asc
limit desde,hasta;
end$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarTodoProductosSinSuc` (IN `desde` INT, IN `hasta` INT)  NO SQL
begin
SELECT p.idProducto, prodNombre, format( prodMontoEntregado,2) as prodMontoEntregado, 
prodFechaInicial, sucNombre, usuNombres,
concat(cliApellidos, ' ', cliNombres) as propietario, prodActivo
FROM `producto` p inner join usuario u on u.idUsuario = p.idUsuario
inner join sucursal s on s.idSucursal = p.idSucursal
inner join Cliente c on c.idCliente = p.idCliente
where prodActivo<>0
order by prodFechaInicial asc
limit desde,hasta;
end$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarTodosProductosEnBDSucursal` (IN `idSuc` INT)  NO SQL
SELECT p.idproducto, prodnombre, prodMontoEntregado, 
DATE_FORMAT(prodFechaRegistro,'%d/%m/%Y') AS prodFechaRegistro,
c.cliApellidos, c.cliNombres, u.usuNick as nick,
case prodActivo when 1 then 'Si' else 'No' end as 'prodActivo'

FROM `producto` p
inner join Cliente c on c.idCliente = p.idcliente
inner join usuario u on u.idUsuario= p.idusuario
where p.idSucursal=idSuc
order by idproducto desc$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarTodosProductosNoFinalizados` (IN `idSuc` INT)  NO SQL
SELECT c.*, p.*, u.usuNombres FROM `producto` p
inner join Cliente c
on p.idcliente = c.idcliente
inner join usuario u
on p.idusuario = u.idusuario
where prodactivo =1 and p.idSucursal = idSuc  and datediff( now(), prodFechainicial )<=30
order by prodfechainicial asc$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarTodosUsuarios` ()  NO SQL
SELECT u.`idUsuario`, concat( `usuNombres`, ' ',  `usuApellido` ) as nombre, p.`descripcion`,  sucLugar 
FROM `usuario` u inner join sucursal s
on u.`idSucursal`= s.`idSucursal`
inner join poder p on p.idPoder=usuPoder
WHERE `usuActivo`=1
order by usuNombres$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `listarUnUsuario` (IN `idUser` INT)  NO SQL
SELECT u.`idUsuario`, `usuNombres` , `usuApellido`, usuNick,
p.idPoder,  u.idSucursal
FROM `usuario` u inner join sucursal s
on u.`idSucursal`= s.`idSucursal`
inner join poder p on p.idPoder=usuPoder
WHERE idUsuario = idUser
order by usuNombres$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `returnTotalProductos` ()  NO SQL
begin

SELECT COUNT( * ) conteo
FROM  `producto` 
where prodActivo<>0;

end$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `returnTotalProductosPorId` (IN `idSuc` INT)  NO SQL
begin

SELECT COUNT( * ) conteo
FROM  `producto` 
where idSucursal = idSuc and prodActivo<>0;

end$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `solicitarConfiguraciones` ()  NO SQL
select * from configuraciones$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `solicitarDatosCompraCliente` (IN `idCompr` INT)  NO SQL
SELECT * FROM `Compras` co
 inner join Cliente c on c.idCliente=co.idCliente
WHERE idcompra =idCompr$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `solicitarHojaControl` (IN `idProd` INT)  NO SQL
select idProducto, c.cliApellidos, c.cliNombres, c.cliDireccion, c.cliDni, c.cliCelular, c.cliCorreo,
p.prodNombre, p.prodObservaciones, DATE_FORMAT(prodFechaRegistro,'%d/%m/%Y %h:%i %p') as prodFechaRegistro,
p.prodMontoEntregado, u.usuNombres, u.usuApellido
from producto p
inner join Cliente c on c.idCliente = p.idCliente
inner join usuario u on u.idUsuario=p.idUsuario
where p.idproducto=idProd$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `solicitarProductoPorId` (IN `idProd` INT)  BEGIN
set @idPrest=(select idprestamo from prestamo where idProducto=idProd);

select c.*, p.prodNombre, p.prodObservaciones, prodFechaRegistro, p.idSucursal, s.sucNombre, d.desFechaContarInteres
from Cliente c inner join producto p on c.idcliente = p.idcliente
inner join sucursal s on p.idSucursal= s.idSucursal
left join prestamo pr on pr.idProducto=p.idProducto
left join desembolso d on d.idPrestamo=pr.idPrestamo

where p.idproducto = idProd ;
END$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `temporalCASE` ()  NO SQL
begin 
DECLARE v INT DEFAULT 1;

    CASE v
      WHEN 2 THEN SELECT v;
      WHEN 3 THEN SELECT 0;
      ELSE
        select 'uno';
    END CASE;
end$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `ubicarPersonaProductos` (IN `campo` TEXT)  BEGIN
select `idCliente`, lower(`cliApellidos`) as cliApellidos, lower(`cliNombres`) as cliNombres, `cliDni`, lower(`cliDireccion`) as cliDireccion, `cliCorreo`, `cliCelular`
from Cliente c 
where concat(lower(cliApellidos), ' ', lower(cliNombres)) like concat('%', campo, '%')
or clidni = campo
order by cliApellidos
;
END$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `updateDesembolsar` (IN `idProd` INT, IN `nuevoDesembolso` FLOAT, IN `idUser` INT, IN `idSuc` INT, IN `comentExtra` TEXT)  NO SQL
BEGIN
set @idPrest=(select idprestamo from prestamo where idProducto=idProd);

UPDATE `producto` SET
`prodMontoEntregado`=`prodMontoEntregado`+nuevoDesembolso,
`prodObservaciones`=concat(`prodObservaciones`,comentExtra)
WHERE `idProducto`= idProd;

UPDATE `prestamo` SET
`preCapital`= `preCapital`+nuevoDesembolso
WHERE `idProducto`=idProd;

UPDATE `desembolso` set
`desCapital`=`desCapital`+nuevoDesembolso
WHERE `idPrestamo`=@idPrest;

INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) VALUES
(null,idProd,14,now(),nuevoDesembolso,comentExtra,1,idUser,0,idSuc);

Select idProd;




END$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `updateFinalizarEstado` (IN `idPro` INT, IN `usuar` VARCHAR(200), IN `monto` FLOAT)  NO SQL
BEGIN
UPDATE `producto` SET `prodActivo`=0,
`prodCuantoFinaliza`=monto,
`prodQuienFinaliza`=usuar,
`prodFechaFinaliza`=now()
WHERE 
`idProducto` = idPro;


INSERT INTO `reportes_producto`(
    `idReporte`, `idProducto`, `idDetalleReporte`, `repoValorMonetario`, `repoFechaOcurrencia`, `repoUsuario`,
`repoUsuarioComentario`, `repoQueConfirma`, `repoQuienConfirma`) VALUES (
     null,idPro,3,monto,now(),usuar,
    '',4,'Todavía sin aprobación');

END$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `updateFinalizarInteres` (IN `idPro` INT, IN `monto` FLOAT, IN `idUser` VARCHAR(200))  NO SQL
begin 
UPDATE `producto` SET 
`prodFechaInicial`=now(),
`prodObservaciones` =concat('Se canceló el interés S/. ',round(monto,2),' de el día ', DATE_FORMAT(now(), "%d/%m/%Y"), '<br>', `prodObservaciones`),
`prodUltimaFechaInteres`= DATE_FORMAT(now(), "%d/%m/%Y")
WHERE 
`idProducto` = idPro;


INSERT INTO `reportes_producto`(
    `idReporte`, `idProducto`, `idDetalleReporte`, `repoValorMonetario`, `repoFechaOcurrencia`, `repoUsuario`,
`repoUsuarioComentario`, `repoQueConfirma`, `repoQuienConfirma`) VALUES (
     null,idPro,1,
     monto,now(),idUser,
    '',4,'Todavía sin aprobación');

end$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `updateFinalizarPrestamo` (IN `idPro` INT, IN `monto` FLOAT, IN `idUser` VARCHAR(200), IN `valorizado` FLOAT, IN `idSuc` INT, IN `usuario` VARCHAR(200), IN `paga` FLOAT, IN `comentario` TEXT)  NO SQL
begin 
UPDATE `producto` SET 
`prodFechaInicial`=now(),
`prodUltimaFechaInteres`= DATE_FORMAT(now(), "%d/%m/%Y"),
`prodActivo`=0,
`prodObservaciones`=concat(`prodObservaciones`, comentario)
WHERE 
`idProducto` = idPro;

UPDATE `prestamo`
SET 
`presActivo`=0,`preIdEstado`=11
WHERE `idProducto`=idPro;


INSERT INTO `reportes_producto`(
    `idReporte`, `idProducto`, `idDetalleReporte`, `repoValorMonetario`, `repoFechaOcurrencia`, `repoUsuario`,
`repoUsuarioComentario`, `repoQueConfirma`, `repoQuienConfirma`) VALUES (
     null,idPro,8,
     paga,now(),usuario,
    '',4,'Todavía sin aprobación');

INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) VALUES (null,idPro,11,now(),paga,concat('Precio original: S/. ', monto), 1,idUser,0,idSuc);

end$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `updateFinalizarSucursal` (IN `idSuc` INT)  NO SQL
UPDATE `sucursal` SET `sucActivo`=0 WHERE idSucursal =idSuc$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `updateMovimientoAceptar` (IN `idRepo` INT, IN `nomUser` VARCHAR(200))  NO SQL
UPDATE `reportes_producto` SET 
`repoFechaConfirma`=now(),
`repoQueConfirma`=7,
`repoQuienConfirma`=nomUser
WHERE `idReporte`=idRepo$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `updateMovimientoRematar` (IN `idRepo` INT, IN `nomUser` VARCHAR(200))  NO SQL
UPDATE `reportes_producto` SET 
`repoFechaConfirma`=now(),
`repoQueConfirma`=6,
`repoQuienConfirma`=nomUser
WHERE `idReporte`=idRepo$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `updateMovimientoRetirar` (IN `idRepo` INT, IN `nomUser` VARCHAR(200))  NO SQL
UPDATE `reportes_producto` SET 
`repoFechaConfirma`=now(),
`repoQueConfirma`=5,
`repoQuienConfirma`=nomUser
WHERE `idReporte`=idRepo$$

CREATE DEFINER=`peruca5`@`localhost` PROCEDURE `updatePassSinDatos` (IN `texto` VARCHAR(200), IN `idUser` INT)  NO SQL
begin
UPDATE `usuario` SET
`usuPass` = md5(texto)
WHERE `idUsuario`=idUser;

select 1;
end$$

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
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE `caja` (
  `idCaja` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL COMMENT 'mandar 0 si no hay producto',
  `idTipoProceso` int(11) NOT NULL,
  `cajaFecha` datetime NOT NULL,
  `cajaValor` float NOT NULL,
  `cajaObservacion` text NOT NULL,
  `cajaActivo` bit(1) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idAprueba` int(11) NOT NULL,
  `idSucursal` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `caja`
--

INSERT INTO `caja` (`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) VALUES
(74, 434, 11, '2017-09-26 21:37:26', 900, 'Precio original: S/. 900', b'1', 8, 0, 1),
(2, 518, 3, '2017-09-19 19:48:17', 400, '', b'1', 8, 0, 1),
(3, 201, 11, '2017-09-19 20:04:25', 281, 'Precio original: S/. 150', b'1', 8, 0, 1),
(4, 204, 11, '2017-09-19 20:06:15', 375, 'Precio original: S/. 200', b'1', 8, 0, 1),
(5, 260, 11, '2017-09-19 20:06:52', 749, 'Precio original: S/. 400', b'1', 8, 0, 1),
(6, 522, 3, '2017-09-20 16:10:14', 100, '', b'1', 3, 0, 1),
(9, 526, 3, '2017-09-21 17:06:33', 170, '', b'1', 3, 0, 1),
(8, 316, 11, '2017-09-20 17:39:58', 244, 'Precio original: S/. 150', b'1', 9, 0, 2),
(10, 528, 3, '2017-09-21 18:36:36', 250, '', b'1', 3, 0, 1),
(11, 530, 3, '2017-09-22 10:34:49', 50, '', b'1', 3, 0, 1),
(12, 384, 3, '2017-09-22 16:51:05', 300, '', b'1', 3, 0, 1),
(13, 535, 13, '2017-09-22 17:43:54', 66.16, '', b'1', 0, 0, 2),
(14, 537, 3, '2017-09-22 18:42:05', 40, '', b'1', 1, 0, 1),
(15, 537, 11, '2017-09-22 18:42:18', 43.8, 'Precio original: S/. 40', b'1', 1, 0, 1),
(16, 513, 11, '2017-09-22 19:29:46', 27, 'Precio original: S/. 26', b'1', 9, 0, 2),
(17, 536, 11, '2017-09-22 19:32:09', 12.1, 'Precio original: S/. 11', b'1', 1, 0, 1),
(18, 536, 13, '2017-09-22 19:43:17', 12.13, '', b'1', 0, 0, 3),
(19, 477, 13, '2017-09-22 19:45:30', 22.1, '', b'1', 0, 0, 2),
(20, 419, 13, '2017-09-23 14:23:55', 349.8, '', b'1', 0, 0, 1),
(21, 293, 11, '2017-09-23 14:44:31', 2600, 'Precio original: S/. 2000', b'1', 8, 0, 1),
(22, 540, 3, '2017-09-23 14:46:07', 450, '', b'1', 12, 0, 1),
(23, 542, 3, '2017-09-23 16:55:54', 350, '', b'1', 13, 0, 1),
(24, 549, 3, '2017-09-26 16:47:56', 100, '', b'1', 9, 0, 2),
(25, 549, 11, '2017-09-26 16:48:15', 90, 'Precio original: S/. 100', b'1', 9, 0, 2),
(26, 550, 3, '2017-09-26 16:48:36', 150, '', b'1', 9, 0, 2),
(27, 550, 10, '2017-09-26 17:10:46', 165.39, '', b'1', 0, 0, 2),
(28, 551, 3, '2017-09-26 17:11:43', 100, '', b'1', 9, 0, 2),
(29, 551, 10, '2017-09-26 17:12:01', 110.26, '', b'1', 0, 0, 2),
(30, 552, 3, '2017-09-26 17:16:27', 30, '', b'1', 9, 0, 2),
(31, 553, 3, '2017-09-26 17:17:18', 80, '', b'1', 9, 0, 2),
(32, 554, 3, '2017-09-26 17:24:54', 80, '', b'1', 9, 0, 2),
(46, 531, 11, '2017-09-26 18:41:57', 440, 'Precio original: S/. 400', b'1', 3, 0, 1),
(34, 556, 3, '2017-09-26 17:31:11', 80, '', b'1', 3, 0, 1),
(35, 557, 3, '2017-09-26 17:38:42', 24, '', b'1', 9, 0, 2),
(36, 557, 10, '2017-09-26 17:39:06', 26.46, '', b'1', 0, 0, 2),
(37, 558, 3, '2017-09-26 17:41:46', 100, '', b'1', 9, 0, 2),
(38, 558, 10, '2017-09-26 17:42:24', 110.26, '', b'1', 0, 0, 2),
(39, 551, 10, '2017-09-26 17:47:19', 110.26, '', b'1', 0, 0, 2),
(40, 553, 10, '2017-09-26 17:53:02', 88.21, '', b'1', 0, 0, 2),
(41, 552, 11, '2017-09-26 18:07:13', 33.08, '', b'1', 0, 0, 2),
(43, 496, 11, '2017-09-26 18:15:31', 27513.7, 'Precio original: S/. 25000', b'1', 3, 0, 1),
(45, 554, 13, '2017-09-26 18:32:54', 8.21, '', b'1', 0, 0, 2),
(47, 420, 11, '2017-09-26 18:57:05', 833.36, 'Precio original: S/. 700', b'1', 3, 0, 1),
(48, 281, 11, '2017-09-26 19:07:28', 253.8, 'Precio original: S/. 200', b'1', 3, 0, 1),
(49, 559, 3, '2017-09-26 19:24:24', 80, '', b'1', 9, 0, 2),
(50, 559, 10, '2017-09-26 19:25:20', 8, '', b'1', 0, 0, 2),
(51, 508, 11, '2017-09-26 19:33:22', 165, 'Precio original: S/. 150', b'1', 3, 0, 1),
(52, 560, 3, '2017-09-26 19:39:14', 100, '', b'1', 1, 0, 1),
(53, 560, 10, '2017-09-26 19:39:55', 10, '', b'1', 0, 0, 3),
(54, 550, 13, '2017-09-26 19:42:54', 165, '', b'1', 0, 0, 3),
(55, 557, 10, '2017-09-26 20:15:54', 2.1, '', b'1', 0, 0, 2),
(56, 557, 10, '2017-09-26 20:16:45', 2, '', b'1', 0, 0, 2),
(57, 557, 10, '2017-09-26 20:17:34', 1, '', b'1', 0, 0, 2),
(58, 557, 13, '2017-09-26 20:18:26', 26.4, '', b'1', 0, 0, 2),
(59, 551, 13, '2017-09-26 20:21:23', 110, '', b'1', 0, 0, 2),
(60, 558, 13, '2017-09-26 20:21:49', 110, '', b'1', 0, 0, 2),
(61, 561, 3, '2017-09-26 20:23:55', 100, '', b'1', 9, 0, 2),
(62, 561, 13, '2017-09-26 20:24:14', 110, '', b'1', 0, 0, 2),
(63, 562, 3, '2017-09-26 20:33:10', 50, '', b'1', 9, 0, 2),
(64, 562, 10, '2017-09-26 20:50:43', 45, '', b'1', 0, 0, 2),
(65, 562, 10, '2017-09-26 20:52:26', 10, '', b'1', 0, 0, 2),
(66, 562, 10, '2017-09-26 20:56:19', 5, '', b'1', 0, 0, 2),
(67, 562, 7, '2017-09-26 20:56:19', 40, '', b'1', 0, 0, 2),
(68, 563, 3, '2017-09-26 20:59:46', 150, '', b'1', 9, 0, 2),
(69, 563, 10, '2017-09-26 21:00:00', 15, '', b'1', 0, 0, 2),
(70, 563, 10, '2017-09-26 21:02:26', 15, '', b'1', 0, 0, 2),
(71, 563, 10, '2017-09-26 21:06:04', 15, '', b'1', 0, 0, 2),
(72, 563, 10, '2017-09-26 21:06:26', 15, '', b'1', 0, 0, 2),
(75, 338, 11, '2017-09-26 22:09:48', 380, 'Precio original: S/. 380', b'1', 8, 0, 1),
(76, 467, 11, '2017-09-26 22:26:19', 79.2, 'Precio original: S/. 60', b'1', 8, 0, 1),
(77, 469, 11, '2017-09-26 22:33:03', 132, 'Precio original: S/. 100', b'1', 8, 0, 1),
(78, 304, 11, '2017-09-26 23:18:21', 230, 'Precio original: S/. 150', b'1', 8, 0, 1),
(79, 312, 11, '2017-09-26 23:22:43', 110, 'Precio original: S/. 40', b'1', 8, 0, 1),
(80, 468, 11, '2017-09-26 23:23:17', 120, 'Precio original: S/. 30', b'1', 8, 0, 1),
(81, 476, 11, '2017-09-26 23:34:44', 500, 'Precio original: S/. 300', b'1', 8, 0, 1),
(82, 473, 11, '2017-09-27 00:03:22', 400, 'Precio original: S/. 350', b'1', 8, 0, 1),
(83, 564, 3, '2017-09-27 09:43:51', 300, '', b'1', 13, 0, 1),
(84, 565, 3, '2017-09-27 12:28:45', 700, '', b'1', 3, 0, 1),
(85, 566, 3, '2017-09-27 12:30:36', 300, '', b'1', 3, 0, 1),
(86, 506, 11, '2017-09-27 16:01:38', 880, 'Precio original: S/. 800', b'1', 3, 0, 1),
(87, 569, 3, '2017-09-27 17:44:34', 200, '', b'1', 3, 0, 1),
(88, 396, 3, '2017-09-27 17:50:45', 350, '', b'1', 3, 0, 1),
(89, 413, 11, '2017-09-27 18:25:26', 348, 'Precio original: S/. 300', b'1', 3, 0, 1),
(90, 397, 10, '2017-09-28 11:35:03', 260.4, '', b'1', 0, 0, 3),
(91, 397, 7, '2017-09-28 11:35:03', -260, '', b'1', 0, 0, 3),
(92, 543, 11, '2017-09-28 16:23:07', 275, 'Precio original: S/. 250', b'1', 3, 0, 1),
(93, 574, 3, '2017-09-28 18:18:34', 200, '', b'1', 3, 0, 1),
(94, 529, 11, '2017-09-28 19:37:29', 22, 'Precio original: S/. 20', b'1', 3, 0, 1),
(95, 575, 3, '2017-09-28 19:38:00', 50, '', b'1', 3, 0, 1),
(96, 377, 10, '2017-09-28 19:56:50', 13.24, '', b'1', 0, 0, 1),
(97, 377, 7, '2017-09-28 19:56:50', -5, '', b'1', 0, 0, 1),
(98, 400, 10, '2017-09-28 20:04:33', 200, '', b'1', 0, 0, 1),
(99, 577, 3, '2017-09-29 10:23:54', 1000, '', b'1', 3, 0, 1),
(100, 578, 3, '2017-09-29 10:39:38', 200, '', b'1', 3, 0, 1),
(101, 580, 3, '2017-09-29 11:18:10', 150, '', b'1', 3, 0, 1),
(102, 581, 3, '2017-09-29 11:58:13', 200, '', b'1', 3, 0, 1),
(103, 511, 11, '2017-09-29 12:00:11', 220, 'Precio original: S/. 200', b'1', 3, 0, 1),
(104, 582, 3, '2017-09-29 13:44:51', 50, '', b'1', 3, 0, 1),
(105, 402, 3, '2017-09-29 14:14:59', 400, '', b'1', 3, 0, 1),
(106, 379, 10, '2017-09-29 14:25:30', 96.57, '', b'1', 0, 0, 1),
(107, 379, 7, '2017-09-29 14:25:30', 40, '', b'1', 0, 0, 1),
(108, 358, 11, '2017-09-29 14:44:40', 850, 'Precio original: S/. 250', b'1', 3, 0, 1),
(109, 515, 11, '2017-09-29 18:35:00', 275, 'Precio original: S/. 250', b'1', 3, 0, 1),
(110, 372, 10, '2017-09-29 18:40:08', 90, '', b'1', 0, 0, 1),
(111, 372, 10, '2017-09-29 18:41:00', 10, '', b'1', 0, 0, 1),
(112, 585, 3, '2017-09-29 19:18:15', 200, '', b'1', 3, 0, 1),
(113, 493, 10, '2017-09-30 13:13:02', 214.99, '', b'1', 0, 0, 1),
(114, 574, 11, '2017-09-30 15:00:44', 220, 'Precio original: S/. 200', b'1', 12, 0, 1),
(115, 500, 11, '2017-10-02 11:01:21', 609.1, 'Precio original: S/. 550', b'1', 3, 0, 1),
(116, 356, 11, '2017-10-02 12:34:26', 300, 'Precio original: S/. 250', b'1', 3, 0, 1),
(117, 379, 11, '2017-10-02 13:57:08', 110, 'Precio original: S/. 300', b'1', 3, 0, 1),
(118, 465, 11, '2017-10-02 14:41:44', 16508, 'Precio original: S/. 15000', b'1', 3, 0, 1),
(119, 405, 10, '2017-10-02 14:59:40', 134.5, '', b'1', 0, 0, 1),
(120, 589, 3, '2017-10-02 15:16:19', 3000, '', b'1', 3, 0, 1),
(121, 509, 10, '2017-10-02 17:17:02', 99.98, '', b'1', 0, 0, 1),
(122, 509, 7, '2017-10-02 17:17:02', 900, '', b'1', 0, 0, 1),
(125, 595, 3, '2017-10-03 13:18:53', 500, '', b'1', 3, 0, 1),
(124, 404, 11, '2017-10-02 19:15:07', 259, 'Precio original: S/. 200', b'1', 3, 0, 1),
(126, 585, 11, '2017-10-04 09:34:54', 220, 'Precio original: S/. 200', b'1', 3, 0, 1),
(127, 602, 3, '2017-10-04 09:36:51', 200, '', b'1', 3, 0, 1),
(128, 462, 11, '2017-10-04 10:51:30', 292.4, 'Precio original: S/. 250', b'1', 3, 0, 1),
(129, 603, 3, '2017-10-04 11:31:42', 250, '', b'1', 3, 0, 1),
(130, 372, 10, '2017-10-04 12:19:39', 58, '', b'1', 0, 0, 1),
(131, 604, 3, '2017-10-04 12:48:38', 550, '', b'1', 3, 0, 1),
(132, 605, 3, '2017-10-04 13:08:17', 200, '', b'1', 13, 0, 1),
(133, 507, 10, '2017-10-04 16:15:02', 13.81, '', b'1', 0, 0, 1),
(134, 507, 7, '2017-10-04 16:15:02', 105, '', b'1', 0, 0, 1),
(135, 391, 11, '2017-10-04 17:06:10', 998.2, 'Precio original: S/. 750', b'1', 3, 0, 1),
(136, 610, 3, '2017-10-05 14:22:22', 600, '', b'1', 3, 0, 1),
(137, 460, 11, '2017-10-05 15:34:05', 770, 'Precio original: S/. 700', b'1', 3, 0, 1),
(138, 426, 10, '2017-10-06 11:16:50', 300, '', b'1', 0, 0, 1),
(139, 421, 10, '2017-10-06 12:36:58', 390.6, '', b'1', 0, 0, 1),
(140, 373, 11, '2017-10-06 15:05:55', 120.5, 'Precio original: S/. 100', b'1', 3, 0, 1),
(141, 604, 13, '2017-10-06 19:26:41', 605, '', b'1', 0, 0, 3),
(142, 479, 11, '2017-10-07 13:17:42', 470.99, 'Precio original: S/. 400', b'1', 12, 0, 1),
(143, 611, 3, '2017-10-07 13:53:52', 300, '', b'1', 12, 0, 1),
(144, 495, 11, '2017-10-07 17:07:43', 464.62, 'Precio original: S/. 400', b'1', 12, 0, 1),
(145, 45, 11, '2017-10-07 17:36:39', 1, 'Precio original: S/. 180', b'1', 13, 0, 1),
(146, 475, 11, '2017-10-07 17:36:58', 1, 'Precio original: S/. 1100', b'1', 13, 0, 1),
(147, 203, 11, '2017-10-07 17:37:46', 1, 'Precio original: S/. 200', b'1', 13, 0, 1),
(148, 248, 11, '2017-10-07 17:38:49', 1, 'Precio original: S/. 50', b'1', 13, 0, 1),
(149, 326, 11, '2017-10-07 17:39:51', 1, 'Precio original: S/. 70', b'1', 13, 0, 1),
(150, 328, 11, '2017-10-07 17:40:24', 1, 'Precio original: S/. 130', b'1', 13, 0, 1),
(151, 343, 11, '2017-10-07 17:41:03', 1, 'Precio original: S/. 80', b'1', 13, 0, 1),
(152, 362, 11, '2017-10-08 11:18:33', 581.63, 'Precio original: S/. 400', b'1', 13, 0, 1),
(153, 614, 3, '2017-10-09 10:01:47', 450, '', b'1', 3, 0, 1),
(154, 617, 3, '2017-10-09 12:37:03', 600, '', b'1', 3, 0, 1),
(155, 481, 10, '2017-10-09 17:26:30', 94, '', b'1', 0, 0, 1),
(156, 619, 3, '2017-10-10 10:04:29', 700, '', b'1', 13, 0, 1),
(157, 620, 3, '2017-10-10 10:21:03', 550, '', b'1', 3, 0, 1),
(158, 412, 11, '2017-10-10 11:02:03', 1, 'Precio original: S/. 70', b'1', 13, 0, 1),
(159, 417, 11, '2017-10-10 11:02:40', 1, 'Precio original: S/. 900', b'1', 13, 0, 1),
(160, 444, 11, '2017-10-10 11:20:50', 1, 'Precio original: S/. 150', b'1', 13, 0, 1),
(161, 622, 3, '2017-10-10 14:01:16', 100, '', b'1', 9, 0, 2),
(162, 622, 11, '2017-10-10 14:02:11', 108, 'Precio original: S/. 100', b'1', 9, 0, 2),
(163, 623, 3, '2017-10-10 14:02:46', 3000, '', b'1', 9, 0, 2),
(164, 623, 10, '2017-10-10 14:02:59', 50, '', b'1', 0, 0, 2),
(165, 623, 10, '2017-10-10 14:03:29', 299.95, '', b'1', 0, 0, 2),
(166, 623, 13, '2017-10-10 14:03:47', 3299.95, '', b'1', 0, 0, 2),
(167, 624, 3, '2017-10-10 14:11:20', 500, '', b'1', 9, 0, 2),
(168, 580, 11, '2017-10-10 15:15:22', 165, 'Precio original: S/. 150', b'1', 3, 0, 1),
(169, 447, 10, '2017-10-10 16:15:22', 73, '', b'1', 0, 0, 1),
(170, 454, 10, '2017-10-10 16:27:37', 60, '', b'1', 0, 0, 1),
(171, 625, 3, '2017-10-11 09:13:38', 350, '', b'1', 3, 0, 1),
(172, 437, 11, '2017-10-11 10:59:28', 300, 'Precio original: S/. 400', b'1', 3, 0, 1),
(173, 364, 10, '2017-10-11 17:42:06', 300, '', b'1', 0, 0, 1),
(174, 627, 3, '2017-10-11 18:07:45', 40, '', b'1', 3, 0, 1),
(175, 352, 10, '2017-10-11 18:20:24', 50, '', b'1', 0, 0, 1),
(176, 544, 11, '2017-10-11 18:41:04', 278.8, 'Precio original: S/. 250', b'1', 3, 0, 1),
(177, 628, 3, '2017-10-12 11:23:56', 500, '', b'1', 13, 0, 1),
(178, 470, 10, '2017-10-12 11:42:32', 140, '', b'1', 0, 0, 1),
(179, 563, 10, '2017-10-12 12:16:21', 16.12, '', b'1', 0, 0, 2),
(180, 491, 11, '2017-10-12 15:42:23', 1, 'Precio original: S/. 500', b'1', 13, 0, 1),
(181, 364, 11, '2017-10-12 17:15:30', 308.8, 'Precio original: S/. 600', b'1', 3, 0, 1),
(182, 601, 11, '2017-10-12 17:30:40', 44, 'Precio original: S/. 40', b'1', 3, 0, 1),
(183, 634, 3, '2017-10-13 16:07:00', 600, '', b'1', 3, 0, 1),
(184, 517, 11, '2017-10-13 17:05:33', 409.4, 'Precio original: S/. 350', b'1', 3, 0, 1),
(185, 637, 3, '2017-10-14 11:15:46', 200, '', b'1', 12, 0, 1),
(186, 638, 3, '2017-10-14 12:06:44', 30, '', b'1', 12, 0, 1),
(187, 342, 10, '2017-10-14 12:11:59', 15.08, '', b'1', 12, 0, 1),
(188, 639, 3, '2017-10-14 13:03:06', 250, '', b'1', 12, 0, 1),
(189, 593, 13, '2017-10-14 13:59:49', 384.99, '', b'1', 12, 0, 1),
(190, 516, 13, '2017-10-14 14:21:05', 1177.46, '', b'1', 12, 0, 1),
(191, 634, 13, '2017-10-14 14:21:43', 659.99, '', b'1', 12, 0, 1),
(192, 367, 10, '2017-10-14 14:37:49', 30.27, '', b'1', 12, 0, 1),
(193, 367, 7, '2017-10-14 14:37:49', -18.27, '', b'1', 12, 0, 1),
(194, 367, 11, '2017-10-14 14:38:02', 90.27, 'Precio original: S/. 60', b'1', 12, 0, 1),
(195, 641, 3, '2017-10-16 09:46:01', 10000, '', b'1', 3, 0, 1),
(196, 599, 11, '2017-10-16 11:13:14', 1870, 'Precio original: S/. 1700', b'1', 3, 0, 1),
(197, 642, 3, '2017-10-16 12:09:50', 350, '', b'1', 13, 0, 1),
(198, 624, 10, '2017-10-16 13:09:23', 49.99, '', b'1', 9, 0, 2),
(199, 624, 7, '2017-10-16 13:09:23', 450, '', b'1', 9, 0, 2),
(200, 624, 10, '2017-10-16 13:09:49', 49.99, '', b'1', 9, 0, 2),
(201, 624, 7, '2017-10-16 13:09:49', 430, '', b'1', 9, 0, 2),
(202, 624, 10, '2017-10-16 13:10:01', 49.99, '', b'1', 9, 0, 2),
(203, 624, 7, '2017-10-16 13:10:01', 420, '', b'1', 9, 0, 2),
(204, 523, 11, '2017-10-16 13:50:26', 355.7, 'Precio original: S/. 300', b'1', 13, 0, 1),
(205, 644, 3, '2017-10-16 14:14:42', 20, '', b'1', 3, 0, 1),
(206, 621, 10, '2017-10-16 16:31:08', 29.99, '', b'1', 9, 0, 2),
(207, 621, 10, '2017-10-16 16:31:31', 29.99, '', b'1', 9, 0, 2),
(208, 621, 7, '2017-10-16 16:31:31', 220, '', b'1', 9, 0, 2),
(209, 621, 9, '2017-10-16 16:33:15', 10, '', b'1', 9, 0, 2),
(210, 621, 9, '2017-10-16 16:33:33', 28, '', b'1', 9, 0, 2),
(211, 621, 10, '2017-10-16 16:33:45', 29.99, '', b'1', 9, 0, 2),
(212, 621, 7, '2017-10-16 16:33:45', 270, '', b'1', 9, 0, 2),
(213, 621, 10, '2017-10-16 16:34:12', 29.99, '', b'1', 9, 0, 2),
(214, 621, 7, '2017-10-16 16:34:12', 50, '', b'1', 9, 0, 2),
(215, 621, 10, '2017-10-16 16:34:32', 29.99, '', b'1', 9, 0, 2),
(216, 621, 7, '2017-10-16 16:34:32', 50, '', b'1', 9, 0, 2),
(217, 621, 10, '2017-10-16 16:34:58', 29.99, '', b'1', 9, 0, 2),
(218, 621, 7, '2017-10-16 16:34:58', 50, '', b'1', 9, 0, 2),
(219, 621, 10, '2017-10-16 16:40:50', 29.99, '', b'1', 9, 0, 2),
(220, 621, 7, '2017-10-16 16:40:50', 220, '', b'1', 9, 0, 2),
(221, 645, 3, '2017-10-16 16:41:42', 300, '', b'1', 9, 0, 2),
(222, 645, 10, '2017-10-16 16:41:56', 29.99, '', b'1', 9, 0, 2),
(223, 645, 7, '2017-10-16 16:41:56', 260, '', b'1', 9, 0, 2),
(224, 645, 10, '2017-10-16 16:50:18', 29.99, '', b'1', 9, 0, 2),
(225, 645, 7, '2017-10-16 16:50:18', 250, '', b'1', 9, 0, 2),
(226, 645, 10, '2017-10-16 16:53:00', 29.99, '', b'1', 9, 0, 2),
(227, 645, 7, '2017-10-16 16:53:00', 20.01, '', b'1', 9, 0, 2),
(228, 563, 10, '2017-10-16 16:54:06', 20.71, '', b'1', 9, 0, 2),
(229, 563, 7, '2017-10-16 16:54:06', 29.29, '', b'1', 9, 0, 2),
(230, 64, 10, '2017-10-16 17:03:28', 34.07, '', b'1', 9, 0, 2),
(231, 64, 7, '2017-10-16 17:03:28', 15.93, '', b'1', 9, 0, 2),
(232, 355, 11, '2017-10-16 17:06:57', 900, 'Precio original: S/. 500', b'1', 8, 0, 1),
(233, 64, 10, '2017-10-16 17:48:04', 34.07, '', b'1', 9, 0, 2),
(234, 64, 7, '2017-10-16 17:48:04', 15.93, '', b'1', 9, 0, 2),
(235, 64, 13, '2017-10-16 17:50:46', 140, '', b'1', 9, 0, 2),
(236, 647, 3, '2017-09-16 17:51:11', 300, '', b'1', 9, 0, 2),
(240, 647, 7, '2017-10-16 18:18:44', 20.01, '', b'1', 9, 0, 2),
(239, 647, 10, '2017-10-16 18:18:44', 29.99, '', b'1', 9, 0, 2),
(241, 647, 10, '2017-10-16 18:23:03', 26.79, '', b'1', 9, 0, 2),
(242, 647, 7, '2017-10-16 18:23:03', 3.21, '', b'1', 9, 0, 2),
(243, 648, 3, '2017-10-16 18:24:15', 200, '', b'1', 9, 0, 2),
(244, 648, 10, '2017-10-16 18:24:27', 20, '', b'1', 9, 0, 2),
(245, 648, 7, '2017-10-16 18:24:27', 10, '', b'1', 9, 0, 2),
(246, 648, 10, '2017-10-16 18:24:52', 19, '', b'1', 9, 0, 2),
(247, 648, 7, '2017-10-16 18:24:52', 6, '', b'1', 9, 0, 2),
(248, 648, 9, '2017-10-16 18:33:14', 10, '', b'1', 9, 0, 2),
(249, 648, 10, '2017-10-16 18:33:26', 18.4, '', b'1', 9, 0, 2),
(250, 389, 11, '2017-10-16 19:24:08', 3298, 'Precio original: S/. 3000', b'1', 3, 0, 1),
(251, 503, 10, '2017-10-16 19:24:40', 130.9, '', b'1', 3, 0, 1),
(252, 521, 10, '2017-10-17 10:04:08', 116.16, '', b'1', 13, 0, 1),
(253, 650, 3, '2017-10-17 14:22:30', 600, '', b'1', 3, 0, 1),
(254, 632, 11, '2017-10-17 15:14:59', 1, 'Precio original: S/. 600', b'1', 13, 0, 1),
(255, 490, 11, '2017-10-17 18:38:10', 100.2, 'Precio original: S/. 80', b'1', 13, 0, 1),
(256, 614, 13, '2017-10-18 11:06:07', 495, '', b'1', 13, 0, 1),
(257, 633, 11, '2017-10-18 14:24:11', 351, 'Precio original: S/. 320', b'1', 13, 0, 1),
(258, 600, 11, '2017-10-18 16:32:00', 3850, 'Precio original: S/. 3500', b'1', 13, 0, 1),
(259, 567, 11, '2017-10-18 16:32:38', 4583, 'Precio original: S/. 4000', b'1', 13, 0, 1),
(260, 654, 3, '2017-10-18 17:37:10', 1200, '', b'1', 13, 0, 1),
(261, 655, 3, '2017-10-18 17:53:01', 850, '', b'1', 13, 0, 1),
(262, 656, 9, '2017-10-18 18:12:13', 20, '', b'1', 13, 0, 1),
(263, 575, 11, '2017-10-18 18:16:49', 56, 'Precio original: S/. 50', b'1', 13, 0, 1),
(264, 658, 3, '2017-10-18 18:42:48', 1000, '', b'1', 13, 0, 1),
(265, 620, 11, '2017-10-20 11:12:39', 605, 'Precio original: S/. 550', b'1', 3, 0, 1),
(266, 602, 11, '2017-10-20 11:13:47', 223, 'Precio original: S/. 200', b'1', 3, 0, 1),
(267, 533, 10, '2017-10-20 15:26:26', 60, '', b'1', 3, 0, 1),
(268, 507, 9, '2017-10-20 15:40:15', 10, '', b'1', 3, 0, 1),
(269, 507, 9, '2017-10-20 15:40:43', 20, '', b'1', 3, 0, 1),
(270, 592, 11, '2017-10-20 16:19:56', 898.1, 'Precio original: S/. 800', b'1', 3, 0, 1),
(271, 539, 10, '2017-10-20 19:02:12', 40, '', b'1', 3, 0, 1),
(272, 0, 5, '2017-10-21 10:18:16', 150, 'Para prestamo de la cortadora de pelo mascotas Wahl KM5', b'1', 8, 0, 1),
(273, 582, 13, '2017-10-21 11:52:31', 57.68, '', b'1', 12, 0, 1),
(274, 569, 13, '2017-10-21 18:10:48', 235.49, '', b'1', 12, 0, 1),
(275, 637, 11, '2017-10-23 11:24:43', 220, 'Precio original: S/. 200', b'1', 3, 0, 1),
(276, 664, 3, '2017-10-23 12:44:16', 300, '', b'1', 9, 0, 2),
(277, 664, 9, '2017-10-23 12:49:10', 10, '', b'1', 9, 0, 2),
(278, 666, 3, '2017-10-23 14:34:31', 250, '', b'1', 3, 0, 1),
(279, 405, 9, '2017-10-23 15:12:46', 97.5, '', b'1', 3, 0, 1),
(280, 418, 11, '2017-10-23 15:46:17', 174.7, 'Precio original: S/. 150', b'1', 3, 0, 1),
(281, 276, 10, '2017-10-24 11:19:59', 80, '', b'1', 3, 0, 1),
(282, 591, 10, '2017-10-24 12:12:41', 84.51, '', b'1', 14, 0, 1),
(283, 667, 3, '2017-10-24 12:52:07', 200, '', b'1', 3, 0, 1),
(284, 668, 3, '2017-10-24 12:53:23', 200, '', b'1', 3, 0, 1),
(285, 615, 11, '2017-10-24 13:39:51', 553.8, 'Precio original: S/. 500', b'1', 3, 0, 1),
(286, 669, 3, '2017-10-24 13:48:19', 150, '', b'1', 3, 0, 1),
(291, 672, 3, '2017-10-24 18:25:45', 250, '', b'1', 3, 0, 1),
(289, 382, 10, '2017-10-24 15:46:15', 272.97, '', b'1', 1, 0, 1),
(290, 382, 7, '2017-10-24 15:46:15', 300, '', b'1', 1, 0, 1),
(292, 369, 11, '2017-10-25 12:38:33', 8106.9, 'Precio original: S/. 5000', b'1', 1, 0, 1),
(293, 629, 11, '2017-10-25 13:05:14', 385, 'Precio original: S/. 350', b'1', 3, 0, 1),
(294, 463, 9, '2017-10-25 14:23:37', 50, '', b'1', 3, 0, 1),
(295, 588, 11, '2017-10-25 15:44:39', 533.5, 'Precio original: S/. 450', b'1', 3, 0, 1),
(296, 377, 11, '2017-10-26 09:45:34', 31.3, 'Precio original: S/. 40', b'1', 3, 0, 1),
(297, 664, 11, '2017-10-26 11:26:13', 0, 'Precio original: S/. 300', b'1', 9, 0, 2),
(298, 675, 3, '2017-10-26 11:30:42', 11, '', b'1', 9, 0, 2),
(299, 675, 11, '2017-10-26 11:30:53', 0, 'Precio original: S/. 11', b'1', 9, 0, 2),
(300, 676, 3, '2017-10-26 11:32:47', 22, '', b'1', 9, 0, 2),
(301, 676, 11, '2017-10-26 11:33:00', 0, 'Precio original: S/. 22', b'1', 9, 0, 2),
(302, 584, 11, '2017-10-26 11:53:35', 0, 'Precio original: S/. 200', b'1', 3, 0, 1),
(303, 578, 11, '2017-10-26 11:54:47', 0, 'Precio original: S/. 200', b'1', 3, 0, 1),
(304, 573, 11, '2017-10-26 11:57:10', 0, 'Precio original: S/. 400', b'1', 3, 0, 1),
(305, 571, 11, '2017-10-26 11:57:49', 0, 'Precio original: S/. 750', b'1', 3, 0, 1),
(306, 518, 11, '2017-10-26 12:00:56', 0, 'Precio original: S/. 400', b'1', 3, 0, 1),
(307, 472, 11, '2017-10-26 12:03:59', 0, 'Precio original: S/. 40', b'1', 3, 0, 1),
(308, 440, 11, '2017-10-26 12:07:12', 0, 'Precio original: S/. 350', b'1', 3, 0, 1),
(309, 438, 11, '2017-10-26 12:13:31', 0, 'Precio original: S/. 240', b'1', 3, 0, 1),
(310, 407, 11, '2017-10-26 12:14:15', 0, 'Precio original: S/. 700', b'1', 3, 0, 1),
(311, 403, 11, '2017-10-26 12:15:15', 0, 'Precio original: S/. 300', b'1', 3, 0, 1),
(312, 401, 11, '2017-10-26 12:22:46', 0, 'Precio original: S/. 100', b'1', 3, 0, 1),
(313, 399, 11, '2017-10-26 12:23:22', 0, 'Precio original: S/. 400', b'1', 3, 0, 1),
(314, 396, 11, '2017-10-26 12:23:47', 0, 'Precio original: S/. 350', b'1', 3, 0, 1),
(315, 390, 11, '2017-10-26 12:24:06', 0, 'Precio original: S/. 150', b'1', 3, 0, 1),
(316, 381, 11, '2017-10-26 12:24:36', 0, 'Precio original: S/. 200', b'1', 3, 0, 1),
(317, 380, 11, '2017-10-26 12:24:59', 0, 'Precio original: S/. 30', b'1', 3, 0, 1),
(318, 378, 11, '2017-10-26 12:25:52', 0, 'Precio original: S/. 500', b'1', 3, 0, 1),
(319, 375, 11, '2017-10-26 12:26:08', 0, 'Precio original: S/. 150', b'1', 3, 0, 1),
(320, 485, 11, '2017-10-26 12:26:31', 0, 'Precio original: S/. 200', b'1', 3, 0, 1),
(321, 423, 11, '2017-10-26 12:27:19', 0, 'Precio original: S/. 1000', b'1', 3, 0, 1),
(322, 471, 11, '2017-10-26 12:28:03', 0, 'Precio original: S/. 300', b'1', 3, 0, 1),
(323, 510, 11, '2017-10-26 12:34:28', 453.3, 'Precio original: S/. 350', b'1', 3, 0, 1),
(324, 370, 11, '2017-10-26 12:37:26', 0, 'Precio original: S/. 250', b'1', 3, 0, 1),
(325, 366, 11, '2017-10-26 12:37:54', 0, 'Precio original: S/. 80', b'1', 3, 0, 1),
(326, 350, 11, '2017-10-26 12:38:26', 0, 'Precio original: S/. 400', b'1', 3, 0, 1),
(327, 349, 11, '2017-10-26 12:38:42', 0, 'Precio original: S/. 170', b'1', 3, 0, 1),
(328, 341, 11, '2017-10-26 12:39:13', 0, 'Precio original: S/. 200', b'1', 3, 0, 1),
(329, 329, 11, '2017-10-26 12:40:13', 0, 'Precio original: S/. 120', b'1', 3, 0, 1),
(330, 238, 9, '2017-10-26 12:41:56', 72, '', b'1', 3, 0, 1),
(331, 677, 3, '2017-10-26 12:49:15', 300, '', b'1', 3, 0, 1),
(332, 238, 11, '2017-10-26 12:49:44', 0, 'Precio original: S/. 300', b'1', 3, 0, 1),
(333, 309, 11, '2017-10-26 12:51:57', 0, 'Precio original: S/. 104.4', b'1', 3, 0, 1),
(334, 297, 11, '2017-10-26 12:52:24', 0, 'Precio original: S/. 150', b'1', 3, 0, 1),
(335, 295, 11, '2017-10-26 12:52:49', 0, 'Precio original: S/. 220', b'1', 3, 0, 1),
(336, 289, 11, '2017-10-26 12:53:10', 0, 'Precio original: S/. 250', b'1', 3, 0, 1),
(337, 284, 11, '2017-10-26 12:53:47', 0, 'Precio original: S/. 150', b'1', 3, 0, 1),
(338, 649, 11, '2017-10-26 13:47:25', 0, 'Precio original: S/. 1000', b'1', 3, 0, 1),
(339, 666, 11, '2017-10-26 14:19:19', 275, 'Precio original: S/. 250', b'1', 3, 0, 1),
(340, 680, 3, '2017-10-26 18:02:35', 350, '', b'1', 3, 0, 1),
(341, 681, 3, '2017-10-26 18:19:16', 200, '', b'1', 3, 0, 1),
(342, 603, 11, '2017-10-26 18:30:45', 290.4, 'Precio original: S/. 250', b'1', 3, 0, 1),
(343, 682, 3, '2017-10-27 11:01:39', 111, '', b'1', 9, 0, 2),
(344, 667, 11, '2017-10-27 11:13:46', 220, 'Precio original: S/. 200', b'1', 3, 0, 1),
(345, 451, 11, '2017-10-27 12:35:49', 0, 'Precio original: S/. 600', b'1', 3, 0, 1),
(346, 493, 10, '2017-10-27 14:26:25', 446.89, '', b'1', 14, 0, 1),
(347, 463, 10, '2017-10-27 17:12:49', 78.69, '', b'1', 14, 0, 1),
(348, 463, 7, '2017-10-27 17:12:49', 250, '', b'1', 14, 0, 1),
(349, 463, 13, '2017-10-27 17:13:18', 100, '', b'1', 14, 0, 1),
(350, 684, 3, '2017-10-27 17:22:00', 43, '', b'1', 1, 0, 1),
(351, 410, 13, '2017-10-27 17:25:48', 142.47, '', b'1', 14, 0, 1),
(352, 458, 11, '2017-10-27 19:52:16', 1280, 'Precio original: S/. 1000', b'1', 13, 0, 1),
(353, 421, 11, '2017-10-27 19:53:55', 1590, 'Precio original: S/. 1500', b'1', 13, 0, 1),
(354, 681, 11, '2017-10-28 10:00:00', 220, 'Precio original: S/. 200', b'1', 13, 0, 1),
(355, 689, 3, '2017-10-28 11:06:32', 150, '', b'1', 13, 0, 1),
(356, 546, 10, '2017-10-28 11:20:51', 194, '', b'1', 13, 0, 1),
(357, 541, 9, '2017-10-28 18:10:39', 30, '', b'1', 13, 0, 1),
(373, 556, 11, '2017-10-30 12:52:19', 0, 'Precio original: S/. 80', b'1', 1, 0, 1),
(359, 492, 13, '2017-10-28 18:26:44', 2000, '', b'1', 13, 0, 1),
(360, 690, 3, '2017-09-15 17:00:07', 40, '', b'1', 13, 0, 1),
(361, 691, 3, '2017-10-30 09:34:43', 120, '', b'1', 13, 0, 1),
(362, 692, 3, '2017-10-30 09:41:17', 119, '', b'1', 13, 0, 1),
(363, 542, 10, '2017-10-30 09:56:30', 97.19, '', b'1', 3, 0, 1),
(364, 542, 7, '2017-10-30 09:56:30', 0.01, '', b'1', 3, 0, 1),
(365, 693, 3, '2017-10-30 10:09:44', 1100, '', b'1', 3, 0, 1),
(366, 565, 10, '2017-10-30 10:32:11', 170.35, '', b'1', 3, 0, 1),
(367, 566, 10, '2017-10-30 10:33:10', 73.01, '', b'1', 3, 0, 1),
(368, 547, 10, '2017-10-30 10:41:43', 125.93, '', b'1', 3, 0, 1),
(369, 459, 10, '2017-10-11 10:46:07', 78, '', b'1', 1, 0, 3),
(370, 449, 10, '2017-10-13 11:11:23', 79, '', b'1', 1, 0, 3),
(371, 694, 3, '2017-10-30 11:28:49', 450, '', b'1', 13, 0, 1),
(372, 695, 3, '2017-10-30 11:33:50', 200, '', b'1', 13, 0, 1),
(374, 560, 11, '2017-10-30 12:52:46', 0, 'Precio original: S/. 100', b'1', 1, 0, 1),
(375, 548, 11, '2017-10-30 13:13:50', 756.2, 'Precio original: S/. 600', b'1', 3, 0, 1),
(376, 698, 3, '2017-10-30 15:31:22', 300, '', b'1', 3, 0, 1),
(377, 682, 14, '2017-10-30 15:48:56', 50, '', b'1', 1, 0, 3),
(378, 682, 14, '2017-10-30 15:50:59', 10, '', b'1', 1, 0, 3),
(379, 682, 14, '2017-10-30 15:52:23', 10, 'prueba aumento<br>', b'1', 1, 0, 3),
(380, 346, 10, '2017-09-14 16:38:37', 42, '', b'1', 1, 0, 3),
(381, 319, 9, '2017-09-14 16:41:52', 25.2, '', b'1', 1, 0, 3),
(382, 322, 10, '2017-09-14 16:55:22', 36, '', b'1', 1, 0, 3),
(383, 305, 10, '2017-08-04 16:57:24', 140, '', b'1', 1, 0, 3),
(384, 199, 10, '2017-08-16 17:00:07', 300, '', b'1', 1, 0, 3),
(385, 636, 11, '2017-10-30 17:01:36', 669, 'Precio original: S/. 600', b'1', 14, 0, 1),
(386, 360, 9, '2017-09-15 17:01:54', 12, '', b'1', 1, 0, 3),
(387, 322, 10, '2017-10-30 17:04:12', 36.8, '', b'1', 14, 0, 1),
(388, 393, 9, '2017-09-04 17:17:38', 60, '', b'1', 1, 0, 3),
(389, 383, 10, '2017-09-04 17:23:43', 50, '', b'1', 1, 0, 3),
(390, 700, 3, '2017-10-30 17:53:59', 700, '', b'1', 14, 0, 1),
(391, 702, 3, '2017-10-30 18:28:49', 500, '', b'1', 1, 0, 1),
(392, 680, 13, '2017-10-30 18:31:20', 385, '', b'1', 14, 0, 1),
(393, 705, 3, '2017-10-31 10:46:49', 5000, '', b'1', 3, 0, 1),
(394, 706, 3, '2017-10-31 10:58:39', 200, '', b'1', 3, 0, 1),
(395, 650, 11, '2017-10-31 12:17:30', 660, 'Precio original: S/. 600', b'1', 13, 0, 1),
(396, 707, 3, '2017-10-31 13:41:35', 500, '', b'1', 14, 0, 1),
(397, 501, 9, '2017-10-31 18:14:40', 60, '', b'1', 3, 0, 1),
(398, 710, 3, '2017-10-31 19:27:26', 300, '', b'1', 13, 0, 1),
(399, 519, 9, '2017-11-02 09:11:05', 560, '', b'1', 14, 0, 1),
(400, 494, 11, '2017-11-02 10:25:04', 300, 'Precio original: S/. 200', b'1', 3, 0, 1),
(401, 512, 11, '2017-11-02 10:30:16', 1358.4, 'Precio original: S/. 1000', b'1', 3, 0, 1),
(402, 454, 11, '2017-11-02 11:54:59', 755, 'Precio original: S/. 650', b'1', 3, 0, 1),
(403, 528, 11, '2017-11-02 12:26:29', 450, 'Precio original: S/. 250', b'1', 3, 0, 1),
(404, 452, 11, '2017-11-02 12:37:44', 1040, 'Precio original: S/. 400', b'1', 3, 0, 1),
(405, 522, 11, '2017-11-02 14:37:47', 140, 'Precio original: S/. 100', b'1', 3, 0, 1),
(406, 711, 3, '2017-11-02 16:36:43', 100, '', b'1', 3, 0, 1),
(407, 532, 11, '2017-11-02 16:53:36', 120, 'Precio original: S/. 100', b'1', 3, 0, 1),
(408, 470, 3, '2017-11-02 17:00:44', 150, '', b'1', 3, 0, 1),
(409, 653, 13, '2017-11-02 17:47:19', 770, '', b'1', 13, 0, 1),
(410, 507, 11, '2017-11-02 17:51:28', 109.8, 'Precio original: S/. 120', b'1', 3, 0, 1),
(411, 497, 10, '2017-11-03 10:25:51', 77.3, '', b'1', 3, 0, 1),
(412, 581, 10, '2017-11-03 10:26:26', 52.1, '', b'1', 3, 0, 1),
(413, 530, 10, '2017-11-03 10:26:54', 16.1, '', b'1', 3, 0, 1),
(414, 360, 9, '2017-11-03 10:29:29', 15.2, '', b'1', 3, 0, 1),
(415, 545, 11, '2017-11-03 11:33:43', 620, 'Precio original: S/. 350', b'1', 3, 0, 1),
(416, 464, 11, '2017-11-03 11:44:12', 450, 'Precio original: S/. 180', b'1', 3, 0, 1),
(417, 538, 11, '2017-11-03 12:17:41', 600, 'Precio original: S/. 400', b'1', 3, 0, 1),
(418, 715, 11, '2017-11-03 13:06:56', 650, 'Precio original: S/. 650', b'1', 3, 0, 1),
(419, 598, 11, '2017-11-03 14:42:17', 360, 'Precio original: S/. 300', b'1', 13, 0, 1),
(420, 708, 11, '2017-11-03 15:15:13', 110, 'Precio original: S/. 100', b'1', 3, 0, 1),
(421, 716, 3, '2017-11-03 16:32:30', 200, '', b'1', 3, 0, 1),
(422, 718, 3, '2017-11-03 18:46:27', 1800, '', b'1', 14, 0, 1),
(423, 533, 11, '2017-11-03 18:53:12', 330, 'Precio original: S/. 300', b'1', 3, 0, 1),
(424, 424, 11, '2017-11-04 10:32:04', 1504.4, 'Precio original: S/. 1000', b'1', 13, 0, 1),
(425, 590, 13, '2017-11-04 11:21:01', 87, '', b'1', 12, 0, 1),
(426, 526, 13, '2017-11-04 19:05:23', 246, '', b'1', 13, 0, 1),
(427, 722, 3, '2017-11-06 09:34:35', 1000, '', b'1', 13, 0, 1),
(428, 566, 11, '2017-11-06 11:57:43', 330, 'Precio original: S/. 300', b'1', 3, 0, 1),
(429, 565, 11, '2017-11-06 11:58:15', 770, 'Precio original: S/. 700', b'1', 3, 0, 1),
(430, 723, 3, '2017-11-06 12:32:05', 350, '', b'1', 3, 0, 1),
(431, 489, 11, '2017-11-06 15:44:07', 600, 'Precio original: S/. 400', b'1', 14, 0, 1),
(432, 699, 9, '2017-11-06 16:59:04', 48, '', b'1', 13, 0, 1),
(433, 597, 9, '2017-11-06 18:16:23', 30, '', b'1', 3, 0, 1),
(434, 605, 9, '2017-11-06 18:16:50', 30, '', b'1', 3, 0, 1),
(435, 725, 3, '2017-11-07 11:20:31', 300, '', b'1', 13, 0, 1),
(436, 617, 11, '2017-11-07 12:24:50', 726, 'Precio original: S/. 600', b'1', 14, 0, 1),
(437, 426, 10, '2017-11-07 13:13:10', 365, '', b'1', 3, 0, 1),
(438, 607, 11, '2017-11-07 15:28:51', 693.2, 'Precio original: S/. 550', b'1', 3, 0, 1),
(439, 673, 11, '2017-11-07 16:07:12', 100, 'Precio original: S/. 70', b'1', 3, 0, 1),
(440, 729, 3, '2017-11-08 10:54:48', 900, '', b'1', 3, 0, 1),
(441, 520, 11, '2017-11-08 11:31:56', 250, 'Precio original: S/. 200', b'1', 3, 0, 1),
(442, 732, 3, '2017-11-08 17:12:44', 250, '', b'1', 3, 0, 1),
(443, 605, 9, '2017-11-08 18:12:54', 20, '', b'1', 3, 0, 1),
(444, 597, 9, '2017-11-08 18:13:27', 20, '', b'1', 3, 0, 1),
(445, 568, 11, '2017-11-08 18:30:02', 0, 'Precio original: S/. 10000', b'1', 3, 0, 1),
(446, 679, 10, '2017-11-08 18:46:18', 35, '', b'1', 3, 0, 1),
(447, 457, 11, '2017-11-08 18:54:50', 0, 'Precio original: S/. 500', b'1', 1, 0, 1),
(448, 635, 9, '2017-11-09 09:50:58', 135, '', b'1', 13, 0, 1),
(449, 718, 14, '2017-11-09 09:55:11', 500, 'se deposito a bbva<br>', b'1', 3, 0, 1),
(450, 400, 11, '2017-11-09 13:04:50', 1240, 'Precio original: S/. 1000', b'1', 13, 0, 1),
(451, 735, 3, '2017-11-09 13:33:00', 70, '', b'1', 13, 0, 1),
(452, 352, 11, '2017-11-09 13:46:52', 300, 'Precio original: S/. 250', b'1', 13, 0, 1),
(453, 498, 11, '2017-11-09 14:25:14', 528, 'Precio original: S/. 400', b'1', 13, 0, 1),
(454, 625, 11, '2017-11-09 14:26:37', 420, 'Precio original: S/. 350', b'1', 13, 0, 1),
(455, 540, 11, '2017-11-09 14:27:07', 576, 'Precio original: S/. 450', b'1', 13, 0, 1),
(456, 504, 11, '2017-11-09 14:30:14', 330, 'Precio original: S/. 250', b'1', 13, 0, 1),
(457, 446, 11, '2017-11-09 14:45:34', 0, 'Precio original: S/. 500', b'1', 14, 0, 1),
(458, 505, 11, '2017-11-09 14:48:44', 0, 'Precio original: S/. 400', b'1', 14, 0, 1),
(459, 594, 11, '2017-11-09 15:28:41', 250, 'Precio original: S/. 200', b'1', 13, 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Cliente`
--

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
(47, 'mendoza castro', '  aldhair', '73074117', 'jr. inca ripac mz k lt 5', '', '994774316/992709086'),
(48, 'gomez cairo ', 'saledad', '45154227', 'jr. tumi #215- el tambo', '', '990008789'),
(51, 'zuñiga', 'miguel', '11111140', 'c30', '', '999999996'),
(52, 'reyes sauñi', 'lucia', '45798915', 'san martin#193- palian', '', '933080901'),
(53, 'palomino basilio ', 'marco antonio', '45797542', 'prog calixto #697-hyo', '', '950950537'),
(54, 'Sistema de prueba', 'Infocat', '12345678', 'manzanos 1545', 'dan_11@gmail.com', '245252'),
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
(180, 'picho peña', 'jesus piter', '76586840', 'psj don bosco 147 - tambo', '', '923380326'),
(181, 'oropeza lazo', 'royer michael', '45976224', 'av huancavelica 1860-hyo', '', '924051359'),
(182, 'bullon garcia', 'jan marco', '70332469', 'jr jose galvez #107', '', '966190430'),
(183, 'castro sedano', 'jhon clis', '76359670', 'av. 2 de mayo s/n chilca ', '', '651785962'),
(184, 'vidal reyes', 'jhon cesar', '40435219', 'jr. pedro galvez 1326 pio pata', '', '936537219'),
(185, 'zavala santana', 'katheryn pamela', '46674544', 'jr. los nevados mz b lt 11', '', '921036990'),
(186, 'tueros lara', 'nadia elizabeth', '46497788', 'av. san carlos cuadra 14 ', '', '959512983'),
(187, 'vergara alfaro', 'joseph stiven', '48189332', 'jr. huari 285 ', '', '064-413377'),
(188, 'cano lazo', 'bruce antonio', '77663378', 'av. hvca 1860', '', '997873064'),
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
(202, 'aguirre valero', 'tulio dardo', '42483910', 'jr arequipa 870 - tarma', '', '942901003'),
(203, 'gonzales paucar', 'enrique', '48300990', 'pj aguila- san jeronimo ', '', '941897161'),
(204, 'flores arias', 'javier guzman', '71614857', 'proceres y toledo - chilca ', '', '943953760'),
(205, 'flores huayra', 'conthia miluska', '43433744', 'jr los cosmos 213-hyo', '', '924403627'),
(206, 'grados espiritu', 'luis antonio', '47124282', 'jr callao 862', '', '985022489'),
(207, 'ramos eugenio', 'jean francis eliel', '72620882', 'av. filomena #155-chilca ', '', '940602586'),
(208, 'quispe aguirre', 'juan jose', '20051307', 'av. 13 de noviembre 1081 - el tambo', '', '988196027'),
(209, 'wissar rivas', 'cristhian', '43890942', 'jr. manchego muchos #280-el tambo', '', '936102844'),
(210, 'acevedo farge', 'edith regina', '20115562', 'los gladiolos 132 la rivera ', '', '923475541'),
(211, 'merlo chero', 'javier enrique', '76242802', 'san carlos y san judas 726', '', '984174286'),
(212, 'ñaupari dionicio', 'delmer vladimir', '48425538', 'psj. tacna 128- el tambo', '', '955208443'),
(213, 'carhuallanqui escobar', 'dennis paul', '70415131', 'pj los angeles s/n - huancan', '', '927657856'),
(214, 'camani quispe', 'eduardo', '72098808', 'calle alisos s/n - la rivera', '', '972245901'),
(215, 'vitor cerron', 'katherine yadiara', '70322609', 'av ferrocarril 773 -hyo', '', '939388810'),
(216, 'luchsinger sahahun', 'samuel eduardo', '48830370', 'prolg. abancay 110 int 101 apr 002', '', '980624965-992443551'),
(217, 'uribe carbajal', 'angel nicolas', '42509022', 'jr san carlos 557', 'awc.gest@hotmail.com', '992580583'),
(218, 'vila joaquin', 'eddy margoth', '41073546', 'calle real 1252', 'EDDYMARGOTH.VILA@ferreyros.com.pe', '964689057'),
(219, 'chavez cochachi', 'jennifer paola', '71048867', 'jr. estibina 107c.v. de mayo', 'CHAVEZ.JPCC@HOTMAIL.COM', '943086227'),
(220, 'untiveros palomino', 'fiorela', '76802027', 'jr loreto 850', 'fio.untiveros.14@gmail.com', '930019016'),
(221, 'surichaqui gutierrez', 'lincol', '43531992', 'pje. las montañas # 136 - el tambo', '', '949341103'),
(222, 'portilla rosales', 'marco', '73018284', 'calle pakras 936 hyo', 'marco_10@hotmail.com', '952125544'),
(223, 'chavez curi', 'bryan jesus', '71070612', 'av tahuantinsuyo sn', 'bryan12_jesus5@hotmail.com', '921862356'),
(224, 'angulo cardenas', 'ernesto', '20076463', 'av. huancavelica 1460  - hyo', '', '954138105'),
(225, 'sotelo solis', 'juan javier', '44313047', 'av. taylor 1373', '', '931413717'),
(226, 'odontologa odon', 'odontologia', '98753642', 'odon 123 odon', '', '987652341'),
(227, 'osores davila', 'claudio jacob', '44938701', 'jr tumbes mz a lote 9 _ el tambo ', '', '985983521'),
(228, 'inca ticllacuri', 'wilmer jhon', '47917052', 'av.huacar s/n barrio miraflores', '', '952837644'),
(229, 'panduro jumachi', 'walter', '62272823', 'jr. yaullos s/n pucara', '', '945318194'),
(230, 'monroy quiroz', 'miguel', '46491417', 'calle 1 urb. san antonio', '', '978586077'),
(231, 'morales romero', 'deysi fresia', '76662645', 'jr. pedro galves #1360', '', '920060614'),
(232, 'garces quispe', 'michelle kelly', '76201835', 'jr augusto b leguia 641', 'michellegarces98@gmail.com', '977783224'),
(233, 'espeza de la cruz', 'jaime', '44493863', 'pasaje angulo 127', 'jaime_speza@hotmail.com', '990196740'),
(234, 'iriarte curo', 'flor yessenia', '75166888', 'av. 2 de mayo 480 - chilca', '', '974032653'),
(235, 'jesus miranda', 'ruth izela', '20051479', 'av. fidel miranda 1528-sapallanga', '', '978004258'),
(236, 'suarez torres', 'luis enrique', '71438072', 'jr montani 348', 'suares@hotmail.com', '936260637'),
(237, 'garcia malpartida', 'jeny', '44946120', 'av mariscal castilla 4336', 'jenygm3112@hotmail.com', '964303178'),
(238, 'orellana jimenez', ' cristian Ernesto', '76668020', '-', '-', '925027680'),
(239, 'saenz moy', 'giuseppe francisco', '73093485', 'av. centenario y olaya', '', '937503277'),
(240, 'valera cardenas', 'herman', '19881059', 'av. cayma  608 urb. chavez', '', '944452777 - 974685296'),
(241, 'martinez ojeda', 'cesar luis', '20020053', 'santa isabel 138 - el tambo', '', '964942699'),
(242, 'ludeña hernandez', 'paul antonio', '45384027', 'jr ayacucho 579 - huancayo', 'tonio_18@hotmail.com', '980506639'),
(243, 'cosme ore', 'eliana leslie', '71777185', 'jr. pachacutec s/n saños chico', '', '987498750'),
(244, 'chavez tarazona', 'cesar clodomiro', '46768804', 'prol tujillo 1042 hyo', '', '995564586'),
(245, 'lopez diaz', 'simon enrique', '46034641', 'mz g lt 7 urb ambrosio salazar', '', '937012312'),
(246, 'aroni mansilla', 'yanet', '42184902', 'pje. huaytapallana 294', '', '999536820'),
(247, 'marallano altez', 'ronald timothy', '48509866', 'jr. las casuarinas mz e lt 14 - florida', '', '963056302'),
(248, 'pilares zevallos', 'juan jose', '45394611', 'calle los fresnos n°17 huancayo', '', '923272229'),
(249, 'lavajos milla', 'marco antonio', '40087128', 'a matista 126', '', '969267770'),
(250, 'panduro sinti', 'raul', '46617945', 'pje los diamantes 265-millotingo', '', '938474738'),
(251, 'palpa zevallos', 'enguiel luis', '47069191', 'calle jupiter 131-hyo', '', '938770770'),
(252, 'flores diaz', 'jose', '73971276', 'psje. san lucas 114-jauja', '', '947891115'),
(253, 'laura gomez', 'jose luis', '42074830', '26 de nov', 'amigos_jota@hotmail.com', '997898989'),
(254, 'panduro sinto', 'raul', '45789856', 'huancayo', 'huancayo', 'huancayo'),
(255, 'rivera menendez', 'jose miguel', '78989889', 'jr hipolito hunanue 500 el tambo', 'jose miguel', '979898989'),
(256, 'torre manrique', 'dayana lisseth', '75279957', 'av 9 octubre 350', 'dayana', '987533366'),
(257, 'paucar gabriel', 'lorena maricruz', '70150711', 'jr las retamas y las flores ', 'lorena', '954164747'),
(258, 'sandoval acosta', 'edson ramiro', '19964233', 'pj. sebastian lorente #115-pio pata', '', '964745226'),
(259, 'gamarra rivera', 'patricia elizabeth', '42176611', 'pj. 5 de agosto s/n - pio pata', '', '991087853'),
(260, 'machacuay chuco', 'liz jazmin', '70916286', 'ciudad universitaria ', '', '960088098'),
(261, 'flores chocca', 'willian', '42544934', 'jr. los bosques 689- el tambo', '', '987553013'),
(262, 'pomacarhua victorio', 'yasmin estefani', '48298618', 'pedro galves con huancavelica 926', '', '927687846'),
(263, 'garcia shanki', 'elvis luis', '44524300', 'jr. cuzco 531-hyo', '', '931742829'),
(264, 'chocca crispin', 'nilo jersy', '47238282', 'nicolas de pierola 301 chilca', '', '937142527'),
(265, 'hilario ladera', 'gumercindo nicolas', '10603484', 'pj abel martinez 381 - chilca', '', '951192596'),
(266, 'torrejon limpias', 'ronald josue', '7761511', 'jr. puno - hyo', '', '990633224'),
(267, 'huaire aranda', 'abel bacilio', '21260994', 'pj jesus maria 351- el tambo', '', '991838715'),
(268, 'rojas camasca', 'lito', '43645132', 'psje diego ferrer 170 san isidro', 'lito_rojas@hotmail.com', '975009299'),
(269, 'derenzin solari', 'victoria yazminc', '70092609', 'manchego quiñones 161', '', '994350978'),
(270, 'moron cifuentes', 'lorenzo', '19863528', 'pj santa sofia 195', '', '959262111'),
(271, 'perez lazo', 'samuel', '48326491', 'pj. blanca ahuray s7n huancan', '', '975291888'),
(272, 'villanueva osorio', 'brayan guillermo', '72373729', 'manzanos y arequipa 659 - el tambo', '', '939572410'),
(273, 'lolo roberto', 'shirly jasmin', '60391721', 'dos de mayo ', '', '924161593'),
(274, 'ramos quincho', 'bety caty', '42241682', 'jr. las retamas y pje trebol 499', '', '925462286'),
(275, 'adauto crisostomo', 'mery mercedes', '20115978', 'jv. circumvalacion 419-el tambo', '', '964002021'),
(276, 'leiva diaz', 'jhonny anthony', '48556676', 'av. daniel alcides carrion 2330-hyo', '', '964825802'),
(277, 'perez maldonado', 'kevin mijael', '45334613', 'jr. antonio lobato 1229-el tambo', '', '939477700'),
(278, 'chileno romero', 'cristian clemente', '70812800', 'jr loreto 1951- hyo', '', '923827735'),
(279, 'estrada espinal', 'bruno edu', '77477284', 'pj san santiago 205-san carlos', '', '975934343'),
(280, 'terreros galarza', 'javier jimmy', '70399195', 'psj.umuto 328 sector 07 -. el tambo', '', '966099302'),
(281, 'huancauqui rojas', 'rolan russe', '47430162', 'av. leopoldo peña s/n ', '', '987659611'),
(282, 'sandoval peña', 'aracely susy', '71668277', 'prrol. tarapaca 109-hyo', '', '941429070'),
(283, 'luque monago', 'armando efrain', '09697669', 'av arequipa s/n ex reniec', '', '963723176'),
(284, 'perez bustamante', 'silvana mariela', '20054648', 'psj. porvenir - el tambo', '', '936407425'),
(285, 'leon cerron', 'erick richarr', '71700318', 'jr. iquitos 248-concepción ', '', '927995946'),
(286, 'huaypar quispe', 'patricia', '48412312', 'pj. ignacio baldeon 135', '', '933916863'),
(287, 'huaypar quispe', 'patricia', '48412312', 'pj. ignacio baldeon 135', '', '933916863'),
(288, 'dolorier alvarez', 'yeffry felix', '42299246', 'pj mercedes cabello 160', '', '975869689'),
(289, 'avellaneda ramos', 'hoana', '45352759', 'jr. lima 449 -hyo', '', '964282370'),
(290, 'orihuela perez', 'jordan daniel', '75416184', 'pj porvenir 202', '', '979920501'),
(291, 'vivas melgar', 'monica angela', '48610296', 'jr. galcilazo de la vega 262 el tambo', '', '944442901'),
(292, 'rojas basilo', 'dither rafael', '44043666', 'jr. husares de junin 760', '', '971711346'),
(293, 'buendia urbina', 'juan jose', '41287116', 'pj. los alamos 130-el tambo', '', '964004099'),
(294, 'torres sovero', 'luz maribel', '40739487', 'calle las begonias 091-el tambo', '', '933239824'),
(295, 'herrera aguilar', 'betty paola', '41166299', 'calle los heroes 348-sicaya', '', '999518066'),
(296, 'matos koo', 'catherine', '46487677', 'av. huancavelica #599', '', '988488885'),
(297, 'valentin huanuco', 'alioshka eduardo', '41608750', 'jr oswaldo barreto 1041 - el tambo', '', '955655053'),
(298, 'cornejo vidal', 'jotam reynaldo', '73739650', 'jr. cuzco 1962 la ribera', '', '923700607'),
(299, 'perez pinedo', 'pedro paul', '45264701', 'jr. los gladiolos 197', '', '950662910'),
(300, 'guerrero meza', 'liz vanessa', '75802152', 'real 551- el tambo', '', '998653785'),
(301, 'romero paucar', 'jose luis', '42412923', 'pj 17 de setiembre mz p block 14', '', '989477942'),
(302, 'soto bruno', 'cristhian roler', '44826974', 'jr. alan garcia 240 el tambo', '', '927467220'),
(303, 'rojas diaz', 'lincoln raul', '20115487', 'av. centenario #965 - hyo', '', '987540553'),
(304, 'yauli orozco', 'anali', '45128595', 'jr las retamas 557', 'anali@hotmail.com', '975869689'),
(305, 'huaman chavez', 'julian emilio', '72216511', 'jr. los manzanos 1175 el tambo', '', '943787137'),
(306, 'bendezu eulogio', 'jesus millagui', '72696602', 'jr. progreso 1203 el tambo', '', '967599562'),
(307, 'bazan rojas', 'eludio', '77680205', 'prol. atalaya 2556 el tambo', '', '954716526'),
(308, 'estrella pianto', 'daryl stefanie', '47235131', 'jr. miguel grau 1310 pilcomayo', '', '964320932'),
(309, 'paredes peinado', 'jhonatan joel', '73601170', 'jr. trujillo 889 - el tambo', '', '930475923'),
(310, 'avila uceda', 'hansel daniel', '73600004', '28 de julio 587 chilca', '', '964744288'),
(311, 'bendezu esteban', 'catherine vanesa', '77282601', 'jr hipolito unanue 297', '', '963344138'),
(312, 'perez torres', 'jorge luis', '72449774', 'av 13 de noviembre 306', '', '985555503'),
(313, 'garcia canales', 'ana isabel', '19851069', 'jr. libertad 441 el tambo', '', '964444485'),
(314, 'povis damian', 'miguel angel', '70294199', 'calle venecia mz c lt 15', '', '964710711'),
(315, 'ticse aranda', 'luis carlos', '43577829', 'av. leoncio prado 107', '', '956762326'),
(316, 'duran contreras', 'luis angel', '46855876', 'av. la esperanza 773 el tambo', '', '954976041'),
(317, 'mucha rivera', 'diego', '47051288', 'psje san fernando 251', 'forever_ciatisesa@hotmail.com', '982444406'),
(318, 'rivera paitan', 'gladis teresa', '41372707', 'jr antonio razuri 115-piopata', '', '947558755'),
(319, 'sotomayor chirinos', 'raul', '29636784', 'calle real plaza vea', 'rkhanac@gmail.com', '960926759'),
(320, 'auqui benito', 'alejandro', '42992326', 'psje francisco de zela 259', 'abauqui@hotmail.com', '964143264'),
(321, 'arroyo flores', 'carlos daniel', '72743978', 'psje marticorena 111', 'carlosdx21@hotmail.com', '937597396'),
(322, 'canales puente', 'luis fernando', '47580115', 'jorge chaves pilcomayo sta rosa', 'luis_16_500@hotmail.com', '945513123'),
(323, 'aguirre sulca', 'laime', '72785249', 'gonzales prada 636', 'luchoar_2@hotmail.com', '939032393'),
(324, 'cordova torres', 'miriam', '19870743', 'jr las violetas mzd lt15', 'miriam@hotmail.com', '965047013'),
(325, 'gamboa cardenas', 'jimmy arthur', '77080979', 'ica antigua 1420', 'gamboa_cardenasj@gmail.com', '931247646'),
(326, 'palacios meza', 'jeunisse merylin', '45426800', 'prol. san antonio 112 - san carlos', '', '976129898'),
(327, 'huaypar quispe', 'patricia', '48412322', 'pj. ignacio baldeon 135', '', '933716863'),
(328, 'aroni mansilla', 'magno', '40250634', 'jr. huaytapallana 294', '', '995566887'),
(329, 'bravo lozano', 'jose antonio', '42105676', 'jr. moquegua 485 - hyo', '', '932036194'),
(330, 'pariona', 'carlos', '99998888', '', '', ''),
(331, 'de la cruz', 'fiorella', '43163395', 'av. 13 de noviembre #1148', '', '990867971'),
(332, 'quilca gabriel', 'romulo florencio', '43748307', 'jr. san juda tadeo #1320', '', '936577638'),
(333, 'espinoza grados', ' augusto cesar', '41648919', 'jr revolucion 200 - pilcomayo', 'ousugaraser@hotmail.com', '994637555'),
(334, 'carhuallanqui alvarez', 'teodoro', '60584574', 'av. heroes de la breña #303', '', '982953378'),
(335, 'anchiraico olivares', 'dennis enrique', '41410740', 'urb. ambrosio salazar g-2', '', '989226802'),
(336, 'yudy del pilar estrada silvia/sacramento estrada', 'juan diego', '73901668', 'macchupuchu 221 -el tambo', '', '931930679'),
(337, 'cordova torres', 'phill', '78018886', 'av.palian 839', 'elbimt879@hotmail.com', '962218183'),
(338, 'acuña peñaloza', 'alejandro', '43640603', 'cooperativa santa isabel mz r l 5 ', '', '972593550'),
(339, 'poma cayllahua', 'denis', '43597679', 'pj grau 106-hyo', '', '995559738'),
(340, 'millan camposano', 'hector vicehich', '20051577', 'jr. panama 1707 el tambo', '', '929283262'),
(341, 'salvatierra', 'caincela', '73737373', 'calle', '', '922442106'),
(342, 'hinostrosa rosales', 'jheymy', '78787887', 'calle1', '', '952358129'),
(343, 'vila tinoco', 'marco antonio', '48578986', 'jr las brisas', 'marc_27_148@hotmail.com', '964550659'),
(344, 'escobar huanay', 'jhordan', '89452378', 'calle3', '', '971000173'),
(345, 'lifonso mercado', 'yordan ancelmo', '69587412', 'calle 3', '', '997886272'),
(346, 'jhanpool raul', 'calderon cordova', '85962374', 'calle4', '', '930171567'),
(347, 'sin nombre', 'anonimo', '10101010', 'calle5', '', '99999998'),
(348, 'prueba', 'prueba', '00001111', '', '', ''),
(349, 'llantoy  paucar', 'anyelo', '48343200', 'jr las brisas', 'ANYELO@HOTMAIL.COM', '932390675'),
(350, 'marcos fabian', 'alfredo wilder', '20054219', 'pj. saul muños menacho 135', '', '939789603'),
(351, 'marcelo marcelo', 'erick javier', '47118310', 'jr. amazonas (parq 15 de junio)', '', '928956007'),
(352, 'torres esteban', 'yessenia leisbith', '70344104', 'pj los geranios ', '', '931798874'),
(353, 'bravo lozano', 'luis cesar', '40309611', 'jr. moquegua 485 hyo', '', '964192342'),
(354, 'perez huaman', 'carlos anselmo', '41476156', 'jr. abancay 157 san carlos ', '', '944084855'),
(355, 'martinez dueñas', 'victor jesus', '44234034', 'prl cuszco 364 - hyo', '', '945901020'),
(356, 'vilcapoma mireya', 'mirella', '20121197', 'av catalina huanca 465', '', '970832599'),
(357, 'victoria flores', 'jonathan gustavo', '44607634', 'calle c mz r lt 12 hyo', '', '999977691'),
(358, 'nonalaya landa', 'erick', '20003739', 'san martin 149', '', '966056362'),
(359, 'apolinarez robles', 'eli jhonel', '41938540', 'calle rosario 189', '', '954005658'),
(360, 'galindez romero', 'rudy wilfredo', '40764600', 'pasj.los tulipanes 178 b -tambo', '', '982243322'),
(361, 'dorregaray flores', 'yasira ivette', '48246342', 'jr. rosa perz 460 - chupaca', '', '987859342'),
(362, 'gamarra palomino de coz', 'luz esther', '19822507', 'av.ferrocarril 1675 - huancayo ', '', '950602397'),
(363, 'sosa cerron', 'anthony', '48801771', 'jr. jose olaya 258', 'anthonyperu@gmail.com', '936894557'),
(364, 'rivera zamudio', 'nicelio', '19833634', 'corel santivañez 949 san carlos', '', '947513600'),
(365, 'orellana diaz', 'yanina jeydi', '77231172', 'jr. faustino quispe 176', '', '934477215'),
(366, 'tovar quispe', 'elizabeth norma', '80009658', 'calle alejandro o destua 107 el tambo', '', '983914898'),
(367, 'encalada picho', 'natali', '70781897', 'urb. mirian de salas 354-el tambo', '', '949380302'),
(368, 'bravo morales', 'jhonson rodney', '41597718', 'av leandro torres 551', '', '954080904'),
(369, 'tello cardenas', 'giovanni edward', '40974870', 'psj. los diamantes 265', '', '999043221'),
(370, 'vilcahuaman rodriguez', 'feliz henry', '43053023', 'jr. nemesio raez 540', '', '971548775'),
(371, 'manturano huamanchao', 'keelvin orlando', '48286891', 'jr. maria arguedas 647, el tambo', '', '948230640'),
(372, 'sosa valverde', 'jorge luis', '41234465', 'av progreso 439-el tambo', '', '967310270'),
(373, 'martinez vitor', 'manuel david', '19812410', 'jr libertad 552', '', '964743279'),
(374, 'espinoza alzamora', 'maribel', '20084755', 'pj el pinar 157- la rivera', '', '963835624'),
(375, 'areche zapata', 'edith janeth', '80005845', 'prol jorge chavez 343-el tambo', '', '931098428'),
(376, 'breña via y rada', 'katiuska susan', '42060102', 'jr. wari 325 - el tambo', '', '945087060'),
(377, 'bazan pagan', 'angello jorginho', '48486209', 'av. tahuantinsuyo 1031', '', '993941152'),
(378, 'carhuancho valdez', 'brandon italo', '76796799', 'jr. huancavelica 1851', '', '989262836'),
(379, 'lauro cangahuala', 'luis ananias', '42929335', 'jr. aguirre 600- el tambo', '', '938139550'),
(380, 'de la cruz coronel', 'carlos', '71494286', 'av santivañez 1248', '', '927794372'),
(381, 'palomino medina', 'sally', '70346865', 'av jose olaya 134 - 34', '', '920435507'),
(382, 'baldeon vega', 'luis enrique', '73127203', 'jr alejandro deustua 1200- el tambo', '', '997368573'),
(383, 'flores terrazos', 'oscar nicolas', '45360349', 'las gemas 109-covica', '', '930701828'),
(384, 'antoya palacios', 'ana', '19843244', 'junin 578 el tambo', '', '999245193'),
(385, 'nuñez quincho', 'david', '48823392', 'huamamarca sn ', '', '954802553'),
(386, 'bujaico orihuela', 'rosario rebeca', '41029001', 'jr. trujillo 1551', '', '978128024'),
(387, 'espeza tello', 'lingher', '70289381', 'psje 8 nov 229 el tambo', '', '995801249'),
(388, 'castro sedano', 'bruno', '76758576', 'av. fidel miranda-sapallanga', 'mama', '966403875/989737464'),
(389, 'carranza moran', 'gerardo emerson', '44617076', 'av. yanama 1601', '', '978949121'),
(390, 'egoavil perez', 'paul arturo', '46295083', 'prolg. manzanos 232', '', '988532074'),
(391, 'bullon torres', 'ahmed jesus', '47569176', 'jr. libertad 1141 el tambo', '', '945889746'),
(392, 'ramirez huaranga', 'manuel', '20114110', 'jr. andres avelino caceres 218', '', '976849573'),
(393, 'orejon alanya', 'yuli mariela', '74608255', 'jr. ayacucho y moquegua ', '', '982908980'),
(394, 'condezo espejo', 'jerson cesar', '43884757', 'calle arequipa 409- el tambo', '', '972099576'),
(395, 'payhua rosel', 'marco antonio', '20079253', 'pj fidel co 115- chilca', '', '996644390'),
(396, 'sedano rojas', 'jesus', '73627163', 'pj. ramon castilla 121', '', '975921521'),
(397, 'torres sanchez', 'dora luz', '72884111', 'jr. los gladiolos 197- incho ', '', '950662910'),
(398, 'toribio de la cruz', 'kevin edwin', '72423503', 'av. circumbalacion204-el tambo', '', '942831894'),
(399, 'sr ipad', 'mini', '12345687', 'en caja ', '', '987654312'),
(400, 'salcedo orellada', 'cristina soledad', '42923830', 'jr. los manzanos 448', '', '936678821'),
(401, 'ponce vilcahuaman', 'luis miguel', '42997322', 'calle real 738 sicaya', '', '923404708'),
(402, 'rosales orihuela', 'eduardo lyw', '43151677', 'jr. piura 221- el tambo', '', '994752820'),
(403, 'merino samaniego', 'carlos augusto', '41892430', 'av circumvalacion 399- hyo', '', '954043479'),
(404, 'flores zuta', 'jaime', '44253119', 'jr. los angeles n°205', 'jaime.flores@tndkay.com', '910888510'),
(405, 'apolinario huaman', 'dante', '47828323', 'paseo la breña 280', '', '954873699'),
(406, 'aguirre sulca', 'jaime', '72785244', 'gonzales prada 636', '', '939032393'),
(407, 'sandoval paucar', 'paola angelina', '46714147', 'pj. el pinar 104-la  rivera', '', '933735494'),
(408, 'pecho caqui', 'omar luis', '45061513', 'jr. ricardo palma 265-el tambo', '', '984309361'),
(409, 'mendoza torres', 'meizon jhoel', '44391394', 'jr. dos de mayo 430', '', '928878820'),
(410, 'chancha calderon', 'cristhian plinio', '46200203', 'jr. ucayali mz 42 lt10 el tambo', '', '995682524'),
(411, 'ticona lopez', 'victor alexander', '40772215', 'parq. los heroes -hospedaje anita', '', '959709253'),
(412, 'castañeda ramos', 'braen', '72000040', 'av auquimarca 2823 chilca', '', '930711954'),
(413, 'martinez clemente', 'kelvin  dennis', '47008986', 'jr huaytapallana 570', '', '988568920'),
(414, 'nuñez montes', 'alexander', '74156024', 'av hvca 1201', '', '947717201'),
(415, 'sinche pariona', 'yomel', '48420541', 'av los incas br ', '', '982926737'),
(416, 'archi lozano', 'teodoro', '73500332', 'jr. jose maria flores 419', '', '997241997-942622051'),
(417, 'rey sanchez alejandro', 'nadia fabiola', '72502952', 'jr. yauyos s/n pucara', '', '968286816'),
(418, 'salazar gomez', 'giovana', '40441930', '', '', ''),
(419, 'rios huaman', 'mckevin', '76349009', 'jr. integración - chilca', '', '954861833'),
(420, 'chamorro asorza', 'amadeo', '19913358', 'jr. 9 de diciembre 150', '', '958522030'),
(421, 'vergara peñaranda', 'jesus geoffryt', '29237540', 'pedro galvez 1390', '', '983360445'),
(422, 'curilla mendoza', 'giancarlos javier', '47753416', 'jr .loreto 1538 - huancayo ', '', '964000202'),
(423, 'pillaca lobaton', 'gianmarco gabriel', '72258402', 'jr. palian 285', '', '929239027'),
(424, 'zuñiga sanchez', 'anthony jose', '70071349', 'pj galaxias # 160', '', '924851695'),
(425, 'arcos alanya', 'michael jhon', '41171047', 'jr. cuzco 1235 ', '', '950801559'),
(426, 'villa torres', 'eduardo', '70295667', 'jr parra del riego ', '', '972581093'),
(427, 'huaycuch valenzuela', 'yuri paola', '77704076', 'pj. union # 147 - chilca', '', '930274737'),
(428, 'santos gutierrez', 'consuelo', '77164662', 'catalina huanca 385', '', '936598437'),
(429, 'blaz millan', 'martin jefferson', '74090655', 'av. leoncio prado 1945- chilca', '', '993011193'),
(430, 'blaz millan', 'martin jefferson', '74090655', 'av. leoncio prado 1945- chilca', '', '993011193'),
(431, 'ventura paulino', 'rick astly', '74365578', 'jr. andres rasu lt 7 s/n ciudad universitaria ', '', '981318894'),
(432, 'marin cadenas', 'rolando david', '40325169', 'juan parra del riego mz n lt6 1770', '', '996865091'),
(433, 'madueño pariona', 'cesar augusto', '71130474', 'av. huancavelica 1320', '', '973255701'),
(434, 'rivera zamudio', 'raul angel', '19815453', 'av santivañez 949', '', '940044240'),
(435, 'zacarias romero', 'michael', '42173684', 'psje santa rosa ', '', '958784579'),
(436, 'marallano lazo', 'rebeca', '44051980', 'av. huancavelica 1183- tambo', '', '933111165'),
(437, 'lopez gonzales', 'eliana', '44127871', 'jr incaripac 678', '', '964479545'),
(438, 'gonzalo ccarita de vera', 'maria', '21076099', 'jr. tumbes 500 - el tambo', '', '974955946'),
(439, 'torres tunque', 'jerry robert', '41032219', 'jr. los lirios mz l lt 14 - el tambo', '', '988121916'),
(440, 'barrientos chocata', 'jhoseph', '72261571', 'jr santa cruz sn ', '', '957305409'),
(441, 'cabrera lopez', 'fernando', '73190748', 'manchego muñoz', '', '945927304'),
(442, 'balvin ticse', 'raul eusebio', '20037625', 'av panamericana 627 huancan', '', '998455810'),
(443, 'garcia limas', 'noi', '48478086', 'jr federico galvez 257 ', '', '964519965'),
(444, 'montano placeres', 'jose', '70310371', 'psje san lorenzo 270', '', '979999408'),
(445, 'granados caso', 'jhon heyne', '77427034', 'jr. junin 1307 - san jeronimo', '', '971747750'),
(446, 'esteban ambrosio', 'nataly gabriela', '72134413', 'jr. florida 337 - chilca', '', '922627793'),
(447, 'machari huaman', 'luzmary enriqueta', '42857034', 'galaxia 175-carmen del solar', '', '969330256'),
(448, 'ramos auris', 'juan jose', '71244522', 'jr neptuno - jardines de san carlos', '', '940916369'),
(449, 'huaman huamani', 'christian antonio', '76240240', 'psje tupac amaru 309 chica (por el pque peñaloza)', 'Christian Antonio H', '976840219'),
(450, 'campos villegas', 'manuel jesus', '45922693', 'av. san carlos 1320', '', '943941103'),
(451, 'quispe paredes', 'elin ray', '73063751', 'cam. los incas 267', '', '973817990'),
(452, 'inga hilario', 'carlos guillermo', '41858664', 'jr carcia calderon 296', '', '934589974'),
(453, 'chenet vilchez', 'valery', '74981004', 'calle pichcus 680', '', '951082852'),
(454, 'ramos quincho', 'elizabeth norma', '99997856', 'retamas', '', '993988863'),
(455, 'zamudio valenzuela', 'miguel angel', '71937367', 'hospital regional ', '', '992050311-985350967'),
(456, 'pachas levano', 'oscar eduardo', '42734865', 'pj san miguel mz b lte 11 - el tambo', '', '997542160'),
(457, 'paucar villar', 'genrry', '47411512', 'jr manuel fuentes el tambo', '', '925895021/981923670'),
(458, 'blas yupanqui', 'marlon', '46960619', 'av ferrocarril 221', '', '959104410/929240298'),
(459, 'vasquez robles', 'antuaneth', '75152062', 'jr san juan y san marcos hyo', '', '927083680/929250478'),
(460, 'contreras mallqui', 'jhonatan edgar', '44145445', 'jr juan velasco 480 tambo', '', '931928887/252689'),
(461, 'santillan quispe', 'luis antonio', '45756284', 'psje caprcornio 120 umuto', '', '964904220/'),
(462, 'inga ramos', 'angie kinverlin', '71105627', 'mariscal castilla 1909', 'amiga fiorela', '956297531-980078718'),
(463, 'zacarias zacarias', 'jorge luis', '72430351', 'jr piura 439', '', '964717161/925740651'),
(464, 'vitancio perez', 'eli esau', '45526370', 'jr. san francisco de asis', '', '982867374'),
(465, 'artica bernardo', 'zarela', '71454172', 'psje los rosales 130', '', '992744984/966139794'),
(466, 'escobar ricapa', 'rodrigo', '73655562', 'av calmell del solar 1970', '', '939514236/964035808'),
(467, 'muñico cardenas', 'miguel angel', '41611808', 'balle los gladiolos mz 1 lt 5, tambo', 'miguelkuzinsky@gmail.com', '948827453'),
(468, 'pacheco carrasco', 'victor raul', '45258813', 'jr. 28 de julio 528 - chilca', 'mama', '954607000-979370856'),
(469, 'sullcaray aponce', 'clinton', '76928787', 'jr. mariscal castilla 286', '', '987863654'),
(470, 'laptop compra', 'hp', '92993948', 'g4', '', '123456785'),
(471, 'aire rojas', 'lincohol daniel', '74203672', 'av general cordova 2144', '', '064-432145/928754621'),
(472, 'huaylas leon', 'julio teodoro', '06667152', 'calle real 261- int 15 y 28', '', '954693779'),
(473, 'blaz millan', 'martin jefferson', '74090655', 'av. leoncio prado 1945- chilca', '', '993011193/924851695'),
(474, 'ropas', 'zapatos', '123123', '', '', ''),
(475, 'fernandez clemente', 'nilton', '48653322', 'jr ayacucho 638', '', '930906792/232728'),
(476, 'reyes tapia', 'charlie jordy', '75980255', 'jr victor campos 195 hualhuas c.culturall', '', '995406478/964462118'),
(477, 'laura camposano', 'wehner fredy', '47520682', 'pj. la breña 739', '', '949238101/967612062'),
(478, 'minaya reynoso', 'eduardo cirilo', '45534462', 'los cedros maz g lt16-san carlos', 'primo aldo', '985204681/958458521'),
(479, 'hilario camarena', 'keving dafne', '46668941', 'alle manzanita 215-parque industrial', '', '978803547/064-245117'),
(480, 'colonio corroy', 'william humberto', '41808340', 'jr los manzanos 830 hyo', '', '957357881'),
(481, 'salgado espinoza', 'victor adrian', '71910502', 'jr. san carlos (dos cuadras abajo de la continental)', 'hermana', '975298607/996041263'),
(482, 'ramos leon', 'julian', '46056102', 'pj. san juan sector huancayo sector 9', 'primo', '930780830/976836093'),
(483, 'blaz millan', 'martin jefferson', '74090655', 'av. leoncio prado 1945 -chilca', '', '993011193/924851695'),
(484, 'jimenez rafael', 'lucila yaqueline', '70071345', 'av. esperanza 825-el tambo', '', '947466433');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Compras`
--

CREATE TABLE `Compras` (
  `idCompra` int(11) NOT NULL,
  `compNombre` varchar(200) NOT NULL,
  `compMontoInicial` float NOT NULL,
  `compFechaInicial` date NOT NULL,
  `compObservaciones` text NOT NULL,
  `idCliente` int(11) NOT NULL,
  `compActivo` bit(1) NOT NULL,
  `compFechaRegistro` datetime NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idSucursal` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuraciones`
--

CREATE TABLE `configuraciones` (
  `interes1` float NOT NULL,
  `interes2` float NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `configuraciones`
--

INSERT INTO `configuraciones` (`interes1`, `interes2`) VALUES
(0.00683, 0.004);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `desembolso`
--

CREATE TABLE `desembolso` (
  `idDesembolso` int(11) NOT NULL,
  `idPrestamo` int(11) NOT NULL,
  `desCapital` float NOT NULL,
  `desDebeInteres` float NOT NULL DEFAULT '0',
  `desFechaContarInteres` datetime NOT NULL,
  `desFechaRegistro` datetime NOT NULL,
  `desActivo` int(11) NOT NULL,
  `desUsuario` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `desembolso`
--

INSERT INTO `desembolso` (`idDesembolso`, `idPrestamo`, `desCapital`, `desDebeInteres`, `desFechaContarInteres`, `desFechaRegistro`, `desActivo`, `desUsuario`) VALUES
(1, 1, 50, 0, '2017-01-07 00:00:00', '2017-01-07 00:00:00', 1, 2),
(2, 2, 200, 0, '2017-01-13 00:00:00', '2017-01-13 00:00:00', 1, 2),
(3, 3, 26, 0, '2017-02-16 00:00:00', '2017-02-16 00:00:00', 1, 2),
(4, 4, 252, 0, '2017-02-23 00:00:00', '2017-02-23 00:00:00', 1, 2),
(5, 5, 60, 0, '2017-01-19 00:00:00', '2017-01-19 00:00:00', 1, 2),
(6, 6, 500, 0, '2017-01-19 00:00:00', '2017-01-19 00:00:00', 1, 2),
(7, 7, 350, 0, '2017-01-20 00:00:00', '2017-01-20 00:00:00', 1, 2),
(8, 8, 48, 0, '2017-02-24 00:00:00', '2017-02-24 00:00:00', 1, 2),
(9, 9, 400, 0, '2017-01-26 00:00:00', '2017-01-26 00:00:00', 1, 2),
(10, 10, 51.2, 0, '2017-04-08 00:00:00', '2017-04-08 00:00:00', 1, 2),
(11, 11, 500, 0, '2017-02-03 00:00:00', '2017-02-03 00:00:00', 1, 2),
(12, 12, 150, 0, '2017-02-06 00:00:00', '2017-02-06 00:00:00', 1, 2),
(13, 13, 100, 0, '2017-01-10 00:00:00', '2017-01-10 00:00:00', 1, 2),
(14, 14, 105, 0, '2017-01-11 00:00:00', '2017-01-11 00:00:00', 1, 2),
(15, 15, 25.2, 0, '2017-03-25 00:00:00', '2017-03-25 00:00:00', 1, 2),
(16, 16, 100, 0, '2017-02-07 00:00:00', '2017-02-07 00:00:00', 1, 2),
(17, 17, 336, 0, '2017-03-06 00:00:00', '2017-03-06 00:00:00', 1, 2),
(18, 18, 170, 0, '2017-02-07 00:00:00', '2017-02-07 00:00:00', 1, 2),
(19, 19, 168, 0, '2017-03-13 00:00:00', '2017-03-13 00:00:00', 1, 2),
(20, 20, 200, 0, '2017-02-09 00:00:00', '2017-02-09 00:00:00', 1, 2),
(21, 21, 80, 0, '2017-02-09 00:00:00', '2017-02-09 00:00:00', 1, 2),
(22, 22, 450, 0, '2016-09-05 00:00:00', '2016-09-05 00:00:00', 1, 2),
(23, 23, 700, 0, '2016-10-28 00:00:00', '2016-10-28 00:00:00', 1, 2),
(51, 51, 300, 0, '2016-09-30 00:00:00', '2016-09-30 00:00:00', 1, 2),
(26, 26, 80, 0, '2016-10-04 00:00:00', '2016-10-04 00:00:00', 1, 2),
(27, 27, 590, 0, '2016-10-11 00:00:00', '2016-10-11 00:00:00', 1, 2),
(28, 28, 520, 0, '2016-10-13 00:00:00', '2016-10-13 00:00:00', 1, 2),
(29, 29, 200, 0, '2016-10-25 00:00:00', '2016-10-25 00:00:00', 1, 2),
(30, 30, 200, 0, '2016-10-26 00:00:00', '2016-10-26 00:00:00', 1, 2),
(31, 31, 200, 0, '2016-11-11 00:00:00', '2016-11-11 00:00:00', 1, 2),
(32, 32, 200, 0, '2016-11-14 00:00:00', '2016-11-14 00:00:00', 1, 2),
(33, 33, 1000, 0, '2016-11-15 00:00:00', '2016-11-15 00:00:00', 1, 2),
(34, 34, 150, 0, '2016-11-15 00:00:00', '2016-11-15 00:00:00', 1, 2),
(35, 35, 70, 0, '2016-11-16 00:00:00', '2016-11-16 00:00:00', 1, 2),
(36, 36, 100, 0, '2016-11-17 00:00:00', '2016-11-17 00:00:00', 1, 2),
(37, 37, 500, 0, '2016-11-19 00:00:00', '2016-11-19 00:00:00', 1, 2),
(38, 38, 60, 0, '2016-11-28 00:00:00', '2016-11-28 00:00:00', 1, 2),
(50, 50, 600, 0, '2016-09-28 00:00:00', '2016-09-28 00:00:00', 1, 2),
(40, 40, 350, 0, '2016-12-03 00:00:00', '2016-12-03 00:00:00', 1, 2),
(41, 41, 80, 0, '2016-12-12 00:00:00', '2016-12-12 00:00:00', 1, 2),
(42, 42, 68, 0, '2017-02-15 00:00:00', '2017-02-15 00:00:00', 1, 2),
(43, 43, 550, 0, '2016-12-14 00:00:00', '2016-12-14 00:00:00', 1, 2),
(44, 44, 400, 0, '2016-12-15 00:00:00', '2016-12-15 00:00:00', 1, 2),
(45, 45, 180, 0, '2016-12-16 00:00:00', '2016-12-16 00:00:00', 1, 2),
(46, 46, 60, 0, '2016-12-01 00:00:00', '2016-12-01 00:00:00', 1, 2),
(47, 47, 800, 0, '2017-01-06 00:00:00', '2017-01-06 00:00:00', 1, 2),
(48, 48, 500, 0, '2017-01-07 00:00:00', '2017-01-07 00:00:00', 1, 2),
(49, 49, 500, 0, '2017-02-10 00:00:00', '2017-02-10 00:00:00', 1, 2),
(52, 52, 300, 0, '2016-12-01 00:00:00', '2016-12-01 00:00:00', 1, 2),
(53, 53, 60, 0, '2017-02-10 00:00:00', '2017-02-10 00:00:00', 1, 2),
(54, 54, 80, 0, '2017-02-11 00:00:00', '2017-02-11 00:00:00', 1, 2),
(55, 55, 19, 0, '2017-03-03 00:00:00', '2017-03-03 00:00:00', 1, 9),
(56, 56, 160, 0, '2017-02-24 00:00:00', '2017-02-24 00:00:00', 1, 9),
(57, 57, 50, 0, '2017-02-13 00:00:00', '2017-02-13 00:00:00', 1, 2),
(58, 58, 700, 0, '2017-02-13 00:00:00', '2017-02-13 00:00:00', 1, 2),
(59, 59, 170, 0, '2017-02-20 00:00:00', '2017-02-20 00:00:00', 1, 2),
(60, 60, 300, 0, '2017-02-13 00:00:00', '2017-02-13 00:00:00', 1, 2),
(61, 61, 300, 0, '2017-02-13 00:00:00', '2017-02-13 00:00:00', 1, 2),
(62, 62, 107, 0, '2017-02-13 00:00:00', '2017-02-13 00:00:00', 1, 2),
(63, 63, 100, 0, '2017-02-14 00:00:00', '2017-02-14 00:00:00', 1, 2),
(64, 64, 140, 0, '2017-09-14 00:00:00', '2017-09-14 00:00:00', 1, 9),
(65, 65, 100, 0, '2017-02-14 00:00:00', '2017-02-14 00:00:00', 1, 10),
(66, 66, 80, 0, '2017-02-15 00:00:00', '2017-02-15 00:00:00', 1, 2),
(67, 67, 40, 0, '2017-02-16 00:00:00', '2017-02-16 00:00:00', 1, 2),
(68, 68, 200, 0, '2017-02-20 00:00:00', '2017-02-20 00:00:00', 1, 2),
(69, 69, 100, 0, '2017-02-20 00:00:00', '2017-02-20 00:00:00', 1, 2),
(70, 70, 101, 0, '2017-04-03 00:00:00', '2017-04-03 00:00:00', 1, 2),
(71, 71, 100, 0, '2017-02-20 00:00:00', '2017-02-20 00:00:00', 1, 2),
(72, 72, 500, 0, '2017-02-21 00:00:00', '2017-02-21 00:00:00', 1, 2),
(73, 73, 100, 0, '2017-02-22 00:00:00', '2017-02-22 00:00:00', 1, 2),
(74, 74, 1000, 0, '2017-02-23 00:00:00', '2017-02-23 00:00:00', 1, 2),
(75, 75, 300, 0, '2017-02-24 00:00:00', '2017-02-24 00:00:00', 1, 2),
(76, 76, 160, 0, '2017-04-28 00:00:00', '2017-04-28 00:00:00', 1, 2),
(77, 77, 2000, 0, '2017-02-24 00:00:00', '2017-02-24 00:00:00', 1, 2),
(78, 78, 2000, 0, '2017-02-24 00:00:00', '2017-02-24 00:00:00', 1, 2),
(79, 79, 400, 0, '2017-02-28 00:00:00', '2017-02-28 00:00:00', 1, 2),
(80, 80, 150, 0, '2017-02-28 00:00:00', '2017-02-28 00:00:00', 1, 2),
(81, 81, 600, 0, '2017-03-01 00:00:00', '2017-03-01 00:00:00', 1, 2),
(82, 82, 1500, 0, '2017-03-02 00:00:00', '2017-03-02 00:00:00', 1, 2),
(83, 83, 300, 0, '2017-03-04 00:00:00', '2017-03-04 00:00:00', 1, 2),
(84, 84, 300, 0, '2017-03-04 00:00:00', '2017-03-04 00:00:00', 1, 2),
(85, 85, 400, 0, '2017-03-06 00:00:00', '2017-03-06 00:00:00', 1, 2),
(86, 86, 1200, 0, '2017-03-07 00:00:00', '2017-03-07 00:00:00', 1, 2),
(87, 87, 756, 0, '2017-04-11 00:00:00', '2017-04-11 00:00:00', 1, 2),
(88, 88, 30, 0, '2017-03-09 00:00:00', '2017-03-09 00:00:00', 1, 2),
(89, 89, 850, 0, '2017-03-10 00:00:00', '2017-03-10 00:00:00', 1, 2),
(90, 90, 50, 0, '2017-03-10 00:00:00', '2017-03-10 00:00:00', 1, 2),
(91, 91, 300, 0, '2017-03-10 00:00:00', '2017-03-10 00:00:00', 1, 2),
(92, 92, 150, 0, '2017-03-11 00:00:00', '2017-03-11 00:00:00', 1, 2),
(93, 93, 140, 0, '2017-03-11 00:00:00', '2017-03-11 00:00:00', 1, 2),
(94, 94, 350, 0, '2017-03-13 00:00:00', '2017-03-13 00:00:00', 1, 2),
(95, 95, 230, 0, '2017-03-14 00:00:00', '2017-03-14 00:00:00', 1, 2),
(96, 96, 1100, 0, '2017-03-17 00:00:00', '2017-03-17 00:00:00', 1, 2),
(97, 97, 500, 0, '2017-03-17 00:00:00', '2017-03-17 00:00:00', 1, 2),
(98, 98, 400, 0, '2017-03-18 00:00:00', '2017-03-18 00:00:00', 1, 2),
(99, 99, 150, 0, '2017-03-20 00:00:00', '2017-03-20 00:00:00', 1, 2),
(100, 100, 1000, 0, '2017-03-20 00:00:00', '2017-03-20 00:00:00', 1, 2),
(101, 101, 350, 0, '2017-03-21 00:00:00', '2017-03-21 00:00:00', 1, 2),
(102, 102, 200, 0, '2017-03-22 00:00:00', '2017-03-22 00:00:00', 1, 2),
(103, 103, 200, 0, '2017-03-23 00:00:00', '2017-03-23 00:00:00', 1, 2),
(104, 104, 280, 0, '2017-03-24 00:00:00', '2017-03-24 00:00:00', 1, 2),
(105, 105, 300, 0, '2017-03-24 00:00:00', '2017-03-24 00:00:00', 1, 2),
(106, 106, 1200, 0, '2017-03-25 00:00:00', '2017-03-25 00:00:00', 1, 2),
(107, 107, 130, 0, '2017-03-28 00:00:00', '2017-03-28 00:00:00', 1, 2),
(108, 108, 200, 0, '2017-03-28 00:00:00', '2017-03-28 00:00:00', 1, 2),
(109, 109, 70, 0, '2017-03-28 00:00:00', '2017-03-28 00:00:00', 1, 2),
(110, 110, 2500, 0, '2017-03-29 00:00:00', '2017-03-29 00:00:00', 1, 2),
(111, 111, 800, 0, '2017-03-31 00:00:00', '2017-03-31 00:00:00', 1, 2),
(112, 112, 500, 0, '2017-03-31 00:00:00', '2017-03-31 00:00:00', 1, 2),
(113, 113, 300, 0, '2017-03-31 00:00:00', '2017-03-31 00:00:00', 1, 2),
(114, 114, 250, 0, '2017-03-31 00:00:00', '2017-03-31 00:00:00', 1, 2),
(115, 115, 300, 0, '2017-04-01 00:00:00', '2017-04-01 00:00:00', 1, 2),
(116, 116, 300, 0, '2017-04-01 00:00:00', '2017-04-01 00:00:00', 1, 2),
(117, 117, 300, 0, '2017-04-01 00:00:00', '2017-04-01 00:00:00', 1, 2),
(118, 118, 500, 0, '2017-04-03 00:00:00', '2017-04-03 00:00:00', 1, 2),
(119, 119, 2500, 0, '2017-04-04 00:00:00', '2017-04-04 00:00:00', 1, 2),
(120, 120, 600, 0, '2017-04-04 00:00:00', '2017-04-04 00:00:00', 1, 2),
(121, 121, 80, 0, '2017-04-17 00:00:00', '2017-04-17 00:00:00', 1, 9),
(122, 122, -666, 0, '2017-07-06 00:00:00', '2017-07-06 00:00:00', 1, 2),
(123, 123, 70, 0, '2017-04-07 00:00:00', '2017-04-07 00:00:00', 1, 2),
(124, 124, 250, 0, '2017-04-07 00:00:00', '2017-04-07 00:00:00', 1, 2),
(125, 125, 350, 0, '2017-04-09 00:00:00', '2017-04-09 00:00:00', 1, 2),
(126, 126, 150, 0, '2017-04-10 00:00:00', '2017-04-10 00:00:00', 1, 2),
(127, 127, 64, 0, '2017-05-11 00:00:00', '2017-05-11 00:00:00', 1, 2),
(128, 128, 0, 0, '2017-04-17 00:00:00', '2017-04-17 00:00:00', 1, 2),
(129, 129, 200, 0, '2017-07-17 00:00:00', '2017-07-17 00:00:00', 1, 2),
(130, 130, 40, 0, '2017-06-19 00:00:00', '2017-06-19 00:00:00', 1, 2),
(131, 131, 1500, 0, '2017-04-11 00:00:00', '2017-04-11 00:00:00', 1, 2),
(132, 132, 300, 0, '2017-04-08 00:00:00', '2017-04-08 00:00:00', 1, 2),
(133, 133, 200, 0, '2017-04-11 00:00:00', '2017-04-11 00:00:00', 1, 2),
(134, 134, 200, 0, '2017-04-12 00:00:00', '2017-04-12 00:00:00', 1, 2),
(135, 135, 150, 0, '2017-04-12 00:00:00', '2017-04-12 00:00:00', 1, 2),
(136, 136, 200, 0, '2017-04-12 00:00:00', '2017-04-12 00:00:00', 1, 2),
(137, 137, 300, 0, '2017-04-12 00:00:00', '2017-04-12 00:00:00', 1, 2),
(138, 138, 150, 0, '2017-04-12 00:00:00', '2017-04-12 00:00:00', 1, 2),
(139, 139, 350, 0, '2017-04-13 00:00:00', '2017-04-13 00:00:00', 1, 2),
(140, 140, 90, 0, '2017-04-17 00:00:00', '2017-04-17 00:00:00', 1, 9),
(141, 141, 300, 0, '2017-04-17 00:00:00', '2017-04-17 00:00:00', 1, 9),
(142, 142, 200, 0, '2017-04-17 00:00:00', '2017-04-17 00:00:00', 1, 9),
(143, 143, 150, 0, '2017-04-17 00:00:00', '2017-04-17 00:00:00', 1, 2),
(144, 144, 70, 0, '2017-04-17 00:00:00', '2017-04-17 00:00:00', 1, 2),
(145, 145, 400, 0, '2017-04-17 00:00:00', '2017-04-17 00:00:00', 1, 2),
(146, 146, 200, 0, '2017-04-18 00:00:00', '2017-04-18 00:00:00', 1, 2),
(147, 147, 300, 0, '2017-04-19 00:00:00', '2017-04-19 00:00:00', 1, 2),
(148, 148, 350, 0, '2017-04-20 00:00:00', '2017-04-20 00:00:00', 1, 2),
(149, 149, 300, 0, '2017-04-20 00:00:00', '2017-04-20 00:00:00', 1, 2),
(150, 150, 300, 0, '2017-04-20 00:00:00', '2017-04-20 00:00:00', 1, 2),
(151, 151, 70, 0, '2017-04-21 00:00:00', '2017-04-21 00:00:00', 1, 2),
(152, 152, 300, 0, '2017-04-21 00:00:00', '2017-04-21 00:00:00', 1, 2),
(153, 153, 1700, 0, '2017-04-22 00:00:00', '2017-04-22 00:00:00', 1, 2),
(154, 154, 150, 0, '2017-04-24 00:00:00', '2017-04-24 00:00:00', 1, 2),
(155, 155, 200, 0, '2017-04-26 00:00:00', '2017-04-26 00:00:00', 1, 2),
(156, 156, 300, 0, '2017-04-27 00:00:00', '2017-04-27 00:00:00', 1, 2),
(157, 157, 240, 0, '2017-04-27 00:00:00', '2017-04-27 00:00:00', 1, 2),
(158, 158, 50, 0, '2017-04-27 00:00:00', '2017-04-27 00:00:00', 1, 2),
(159, 159, 90, 0, '2017-04-27 00:00:00', '2017-04-27 00:00:00', 1, 2),
(160, 160, 4000, 0, '2017-04-28 00:00:00', '2017-04-28 00:00:00', 1, 2),
(161, 161, 300, 0, '2017-04-28 00:00:00', '2017-04-28 00:00:00', 1, 2),
(162, 162, 100, 0, '2017-04-28 00:00:00', '2017-04-28 00:00:00', 1, 2),
(163, 163, 200, 0, '2017-04-29 00:00:00', '2017-04-29 00:00:00', 1, 2),
(164, 164, 250, 0, '2017-04-02 00:00:00', '2017-04-02 00:00:00', 1, 2),
(165, 165, -20, 0, '2017-05-06 00:00:00', '2017-05-06 00:00:00', 1, 2),
(166, 166, 50, 0, '2017-05-04 00:00:00', '2017-05-04 00:00:00', 1, 9),
(167, 167, 15, 0, '2017-05-04 00:00:00', '2017-05-04 00:00:00', 1, 9),
(168, 168, 300, 0, '2017-05-08 00:00:00', '2017-05-08 00:00:00', 1, 2),
(169, 169, 800, 0, '2017-07-10 00:00:00', '2017-07-10 00:00:00', 1, 2),
(170, 170, 500, 0, '2017-05-09 00:00:00', '2017-05-09 00:00:00', 1, 2),
(171, 171, 500, 0, '2017-05-10 00:00:00', '2017-05-10 00:00:00', 1, 2),
(172, 172, 15, 0, '2017-06-14 00:00:00', '2017-06-14 00:00:00', 1, 9),
(173, 173, 400, 0, '2017-05-11 00:00:00', '2017-05-11 00:00:00', 1, 2),
(174, 174, 400, 0, '2017-05-11 00:00:00', '2017-05-11 00:00:00', 1, 2),
(175, 175, 130, 0, '2017-06-20 00:00:00', '2017-06-20 00:00:00', 1, 9),
(176, 176, 150, 0, '2017-05-11 00:00:00', '2017-05-11 00:00:00', 1, 9),
(177, 177, 4500, 0, '2017-05-12 00:00:00', '2017-05-12 00:00:00', 1, 2),
(178, 178, 170, 0, '2017-05-12 00:00:00', '2017-05-12 00:00:00', 1, 2),
(179, 179, 250, 0, '2017-05-12 00:00:00', '2017-05-12 00:00:00', 1, 2),
(180, 180, 100, 0, '2017-05-12 00:00:00', '2017-05-12 00:00:00', 1, 2),
(181, 181, 350, 0, '2017-05-13 00:00:00', '2017-05-13 00:00:00', 1, 2),
(182, 182, 300, 0, '2017-05-15 00:00:00', '2017-05-15 00:00:00', 1, 2),
(183, 183, 400, 0, '2017-05-15 00:00:00', '2017-05-15 00:00:00', 1, 2),
(184, 184, 300, 0, '2017-07-18 00:00:00', '2017-07-18 00:00:00', 1, 2),
(185, 185, 200, 0, '2017-06-19 00:00:00', '2017-06-19 00:00:00', 1, 3),
(186, 186, 60, 0, '2017-05-17 00:00:00', '2017-05-17 00:00:00', 1, 3),
(187, 187, 600, 0, '2017-05-18 00:00:00', '2017-05-18 00:00:00', 1, 2),
(188, 188, 200, 0, '2017-05-18 00:00:00', '2017-05-18 00:00:00', 1, 2),
(189, 189, 250, 0, '2017-05-19 00:00:00', '2017-05-19 00:00:00', 1, 3),
(190, 190, 300, 0, '2017-05-19 00:00:00', '2017-05-19 00:00:00', 1, 3),
(191, 191, 300, 0, '2017-05-19 00:00:00', '2017-05-19 00:00:00', 1, 3),
(192, 192, 2500, 0, '2017-05-19 00:00:00', '2017-05-19 00:00:00', 1, 3),
(193, 193, 350, 0, '2017-05-19 00:00:00', '2017-05-19 00:00:00', 1, 3),
(194, 194, 300, 0, '2017-05-19 00:00:00', '2017-05-19 00:00:00', 1, 3),
(195, 195, 150, 0, '2017-05-20 00:00:00', '2017-05-20 00:00:00', 1, 3),
(196, 196, 70, 0, '2017-05-20 00:00:00', '2017-05-20 00:00:00', 1, 3),
(197, 197, 300, 0, '2017-07-06 00:00:00', '2017-07-06 00:00:00', 1, 3),
(198, 198, 6000, 0, '2017-05-22 00:00:00', '2017-05-22 00:00:00', 1, 3),
(199, 199, 1500, 0, '2017-08-16 00:00:00', '2017-08-16 00:00:00', 1, 3),
(200, 200, 50, 0, '2017-05-22 00:00:00', '2017-05-22 00:00:00', 1, 3),
(201, 201, 150, 0, '2017-05-23 00:00:00', '2017-05-23 00:00:00', 1, 3),
(202, 202, 100, 0, '2017-05-24 00:00:00', '2017-05-24 00:00:00', 1, 3),
(203, 203, 200, 0, '2017-07-05 00:00:00', '2017-07-05 00:00:00', 1, 3),
(204, 204, 200, 0, '2017-05-25 00:00:00', '2017-05-25 00:00:00', 1, 3),
(205, 205, 400, 0, '2017-05-25 00:00:00', '2017-05-25 00:00:00', 1, 3),
(206, 206, 100, 0, '2017-05-25 00:00:00', '2017-05-25 00:00:00', 1, 3),
(207, 207, 450, 0, '2017-05-26 00:00:00', '2017-05-26 00:00:00', 1, 3),
(208, 208, 500, 0, '2017-06-23 00:00:00', '2017-06-23 00:00:00', 1, 3),
(209, 209, 50, 0, '2017-05-26 00:00:00', '2017-05-26 00:00:00', 1, 3),
(210, 210, 500, 0, '2017-05-27 00:00:00', '2017-05-27 00:00:00', 1, 9),
(211, 211, 1000, 0, '2017-05-29 00:00:00', '2017-05-29 00:00:00', 1, 3),
(212, 212, 6000, 0, '2017-05-29 00:00:00', '2017-05-29 00:00:00', 1, 3),
(213, 213, 100, 0, '2017-05-30 00:00:00', '2017-05-30 00:00:00', 1, 3),
(214, 214, 200, 0, '2017-05-30 00:00:00', '2017-05-30 00:00:00', 1, 3),
(215, 215, 200, 0, '2017-05-30 00:00:00', '2017-05-30 00:00:00', 1, 3),
(216, 216, 250, 0, '2017-05-30 00:00:00', '2017-05-30 00:00:00', 1, 3),
(217, 217, 200, 0, '2017-06-02 00:00:00', '2017-06-02 00:00:00', 1, 3),
(218, 218, 300, 0, '2017-06-02 00:00:00', '2017-06-02 00:00:00', 1, 3),
(219, 219, 150, 0, '2017-06-18 00:00:00', '2017-06-18 00:00:00', 1, 3),
(220, 220, 200, 0, '2017-06-02 00:00:00', '2017-06-02 00:00:00', 1, 3),
(221, 221, 180, 0, '2017-06-03 00:00:00', '2017-06-03 00:00:00', 1, 12),
(222, 222, 350, 0, '2017-06-03 00:00:00', '2017-06-03 00:00:00', 1, 12),
(223, 223, 400, 0, '2017-08-07 00:00:00', '2017-08-07 00:00:00', 1, 3),
(224, 224, 200, 0, '2017-06-05 00:00:00', '2017-06-05 00:00:00', 1, 3),
(225, 225, 120, 0, '2017-06-05 00:00:00', '2017-06-05 00:00:00', 1, 3),
(226, 226, 350, 0, '2017-06-05 00:00:00', '2017-06-05 00:00:00', 1, 3),
(227, 227, 70, 0, '2017-06-05 00:00:00', '2017-06-05 00:00:00', 1, 3),
(228, 228, 50, 0, '2017-06-05 00:00:00', '2017-06-05 00:00:00', 1, 3),
(229, 229, 3200, 0, '2017-06-05 00:00:00', '2017-06-05 00:00:00', 1, 3),
(230, 230, 150, 0, '2017-06-06 00:00:00', '2017-06-06 00:00:00', 1, 3),
(231, 231, 300, 0, '2017-06-06 00:00:00', '2017-06-06 00:00:00', 1, 3),
(232, 232, 450, 0, '2017-06-07 00:00:00', '2017-06-07 00:00:00', 1, 3),
(233, 233, 8000, 0, '2017-05-29 00:00:00', '2017-05-29 00:00:00', 1, 3),
(234, 234, 450, 0, '2017-06-07 00:00:00', '2017-06-07 00:00:00', 1, 3),
(235, 235, 450, 0, '2017-06-08 00:00:00', '2017-06-08 00:00:00', 1, 3),
(236, 236, 300, 0, '2017-06-08 00:00:00', '2017-06-08 00:00:00', 1, 3),
(237, 237, 100, 0, '2017-06-08 00:00:00', '2017-06-08 00:00:00', 1, 3),
(238, 238, 300, 0, '2017-08-17 00:00:00', '2017-08-17 00:00:00', 1, 3),
(239, 239, 40, 0, '2017-06-09 00:00:00', '2017-06-09 00:00:00', 1, 12),
(240, 240, 800, 0, '2017-06-09 00:00:00', '2017-06-09 00:00:00', 1, 12),
(241, 241, 60, 0, '2017-06-10 00:00:00', '2017-06-10 00:00:00', 1, 3),
(242, 242, 100, 0, '2017-06-18 00:00:00', '2017-06-18 00:00:00', 1, 3),
(243, 243, 400, 0, '2017-06-10 00:00:00', '2017-06-10 00:00:00', 1, 3),
(244, 244, 80, 0, '2017-06-12 00:00:00', '2017-06-12 00:00:00', 1, 3),
(245, 245, 4100, 0, '2017-06-12 00:00:00', '2017-06-12 00:00:00', 1, 3),
(246, 246, 170, 0, '2017-06-13 00:00:00', '2017-06-13 00:00:00', 1, 3),
(247, 247, 150, 0, '2017-06-13 00:00:00', '2017-06-13 00:00:00', 1, 3),
(248, 248, 50, 0, '2017-07-17 00:00:00', '2017-07-17 00:00:00', 1, 3),
(249, 249, 450, 0, '2017-06-13 00:00:00', '2017-06-13 00:00:00', 1, 3),
(251, 251, 4000, 0, '2017-06-14 00:00:00', '2017-06-14 00:00:00', 1, 3),
(252, 252, 50, 0, '2017-06-14 00:00:00', '2017-06-14 00:00:00', 1, 3),
(253, 253, 600, 0, '2017-07-10 00:00:00', '2017-07-10 00:00:00', 1, 3),
(254, 254, 150, 0, '2017-06-15 00:00:00', '2017-06-15 00:00:00', 1, 3),
(255, 255, 600, 0, '2017-06-15 00:00:00', '2017-06-15 00:00:00', 1, 3),
(256, 256, 228, 0, '2017-07-22 00:00:00', '2017-07-22 00:00:00', 1, 3),
(257, 257, 330, 0, '2017-06-15 00:00:00', '2017-06-15 00:00:00', 1, 13),
(258, 258, 400, 0, '2017-06-16 00:00:00', '2017-06-16 00:00:00', 1, 3),
(259, 259, 300, 0, '2017-06-16 00:00:00', '2017-06-16 00:00:00', 1, 3),
(260, 260, 400, 0, '2017-06-16 00:00:00', '2017-06-16 00:00:00', 1, 3),
(261, 261, 150, 0, '2017-06-16 00:00:00', '2017-06-16 00:00:00', 1, 3),
(262, 262, 500, 0, '2017-06-16 00:00:00', '2017-06-16 00:00:00', 1, 3),
(263, 263, 400, 0, '2017-06-19 00:00:00', '2017-06-19 00:00:00', 1, 3),
(264, 264, 350, 0, '2017-06-19 00:00:00', '2017-06-19 00:00:00', 1, 13),
(265, 265, 450, 0, '2017-06-28 00:00:00', '2017-06-28 00:00:00', 1, 3),
(266, 266, 30, 0, '2017-06-21 00:00:00', '2017-06-21 00:00:00', 1, 3),
(267, 267, 150, 0, '2017-06-21 00:00:00', '2017-06-21 00:00:00', 1, 9),
(268, 268, 136, 0, '2017-06-21 00:00:00', '2017-06-21 00:00:00', 1, 9),
(269, 269, 55, 0, '2017-06-21 00:00:00', '2017-06-21 00:00:00', 1, 9),
(270, 270, 20, 0, '2017-06-21 00:00:00', '2017-06-21 00:00:00', 1, 9),
(271, 271, 15, 0, '2017-06-21 00:00:00', '2017-06-21 00:00:00', 1, 9),
(272, 272, 400, 0, '2017-06-22 00:00:00', '2017-06-22 00:00:00', 1, 3),
(273, 273, 800, 0, '2017-07-20 00:00:00', '2017-07-20 00:00:00', 1, 3),
(274, 274, 400, 0, '2017-06-23 00:00:00', '2017-06-23 00:00:00', 1, 3),
(275, 275, 90, 0, '2017-06-23 00:00:00', '2017-06-23 00:00:00', 0, 3),
(276, 276, 400, 0, '2017-10-24 11:19:00', '2017-08-28 00:00:00', 1, 3),
(277, 277, 80, 0, '2017-06-23 00:00:00', '2017-06-23 00:00:00', 1, 3),
(278, 278, 500, 0, '2017-06-23 00:00:00', '2017-06-23 00:00:00', 1, 3),
(279, 279, 200, 0, '2017-06-23 00:00:00', '2017-06-23 00:00:00', 1, 13),
(280, 280, 600, 0, '2017-06-23 00:00:00', '2017-06-23 00:00:00', 1, 13),
(281, 281, 200, 0, '2017-08-22 00:00:00', '2017-08-22 00:00:00', 1, 12),
(282, 282, 300, 0, '2017-06-26 00:00:00', '2017-06-26 00:00:00', 1, 3),
(283, 283, 100, 0, '2017-06-26 00:00:00', '2017-06-26 00:00:00', 1, 3),
(284, 284, 150, 0, '2017-06-24 00:00:00', '2017-06-24 00:00:00', 1, 13),
(285, 285, 70, 0, '2017-06-24 00:00:00', '2017-06-24 00:00:00', 1, 13),
(286, 286, 10000, 0, '2017-07-25 00:00:00', '2017-07-25 00:00:00', 1, 3),
(287, 287, 100, 0, '2017-06-28 00:00:00', '2017-06-28 00:00:00', 1, 3),
(288, 288, 600, 0, '2017-07-12 00:00:00', '2017-07-12 00:00:00', 1, 3),
(289, 289, 250, 0, '2017-06-30 00:00:00', '2017-06-30 00:00:00', 1, 13),
(290, 290, 210, 0, '2017-06-30 00:00:00', '2017-06-30 00:00:00', 1, 13),
(291, 291, 250, 0, '2017-06-30 00:00:00', '2017-06-30 00:00:00', 1, 3),
(292, 292, 1300, 0, '2017-06-30 00:00:00', '2017-06-30 00:00:00', 1, 3),
(293, 293, 2000, 0, '2017-06-30 00:00:00', '2017-06-30 00:00:00', 1, 3),
(294, 294, 250, 0, '2017-07-01 00:00:00', '2017-07-01 00:00:00', 1, 12),
(295, 295, 220, 0, '2017-07-01 00:00:00', '2017-07-01 00:00:00', 1, 12),
(296, 296, 350, 0, '2017-07-03 00:00:00', '2017-07-03 00:00:00', 1, 3),
(297, 297, 150, 0, '2017-07-03 00:00:00', '2017-07-03 00:00:00', 1, 3),
(298, 298, 600, 0, '2017-06-09 00:00:00', '2017-06-09 00:00:00', 1, 3),
(299, 299, 443, 0, '2017-07-03 00:00:00', '2017-07-03 00:00:00', 1, 9),
(300, 300, 750, 0, '2017-07-04 00:00:00', '2017-07-04 00:00:00', 1, 3),
(301, 301, 200, 0, '2017-07-04 00:00:00', '2017-07-04 00:00:00', 1, 3),
(302, 302, 60, 0, '2017-07-01 00:00:00', '2017-07-01 00:00:00', 1, 13),
(303, 303, 1300, 0, '2017-07-01 00:00:00', '2017-07-01 00:00:00', 1, 13),
(304, 304, 150, 0, '2017-07-04 00:00:00', '2017-07-04 00:00:00', 1, 3),
(305, 305, 700, 0, '2017-08-04 00:00:00', '2017-08-04 00:00:00', 1, 3),
(306, 306, 200, 0, '2017-07-05 00:00:00', '2017-07-05 00:00:00', 1, 13),
(307, 307, 230, 0, '2017-07-05 00:00:00', '2017-07-05 00:00:00', 1, 13),
(308, 308, 500, 0, '2017-06-23 00:00:00', '2017-06-23 00:00:00', 1, 9),
(309, 309, 104.4, 0, '2017-07-07 00:00:00', '2017-07-07 00:00:00', 1, 14),
(310, 310, 280, 0, '2017-07-08 00:00:00', '2017-07-08 00:00:00', 1, 12),
(311, 311, 450, 0, '2017-07-21 00:00:00', '2017-07-21 00:00:00', 1, 3),
(312, 312, 40, 0, '2017-07-10 00:00:00', '2017-07-10 00:00:00', 1, 3),
(313, 313, 1000, 0, '2017-07-11 00:00:00', '2017-07-11 00:00:00', 1, 8),
(314, 314, 80, 0, '2017-07-12 00:00:00', '2017-07-12 00:00:00', 1, 3),
(315, 315, 500, 0, '2017-07-12 00:00:00', '2017-07-12 00:00:00', 1, 3),
(316, 316, 150, 0, '2017-07-12 00:00:00', '2017-07-12 00:00:00', 1, 9),
(317, 317, 120, 0, '2017-07-12 00:00:00', '2017-07-12 00:00:00', 1, 9),
(318, 318, 400, 0, '2017-07-14 00:00:00', '2017-07-14 00:00:00', 1, 3),
(319, 319, 70, 0, '2017-09-14 00:00:00', '2017-07-15 00:00:00', 1, 12),
(320, 320, 500, 0, '2017-07-16 00:00:00', '2017-07-16 00:00:00', 1, 13),
(321, 321, 200, 0, '2017-06-07 00:00:00', '2017-06-07 00:00:00', 1, 3),
(322, 322, 100, 0, '2017-09-14 00:00:00', '2017-07-18 00:00:00', 1, 3),
(323, 323, 300, 0, '2017-07-19 00:00:00', '2017-07-19 00:00:00', 1, 3),
(324, 324, 200, 0, '2017-07-19 00:00:00', '2017-07-19 00:00:00', 1, 3),
(325, 325, 40, 0, '2017-07-19 00:00:00', '2017-07-19 00:00:00', 1, 3),
(326, 326, 70, 0, '2017-07-20 00:00:00', '2017-07-20 00:00:00', 1, 3),
(327, 327, 400, 0, '2017-07-20 00:00:00', '2017-07-20 00:00:00', 1, 3),
(328, 328, 130, 0, '2017-07-21 00:00:00', '2017-07-21 00:00:00', 1, 3),
(329, 329, 120, 0, '2017-07-22 00:00:00', '2017-07-22 00:00:00', 1, 12),
(330, 330, 200, 0, '2017-07-22 00:00:00', '2017-07-22 00:00:00', 1, 9),
(331, 331, 80, 0, '2017-07-25 00:00:00', '2017-07-25 00:00:00', 1, 3),
(332, 332, 150, 0, '2017-07-25 00:00:00', '2017-07-25 00:00:00', 1, 3),
(333, 333, 150, 0, '2017-07-28 00:00:00', '2017-07-28 00:00:00', 1, 3),
(334, 334, 200, 0, '2017-07-28 00:00:00', '2017-07-28 00:00:00', 1, 3),
(335, 335, 250, 0, '2017-07-28 00:00:00', '2017-07-28 00:00:00', 1, 3),
(336, 336, 250, 0, '2017-07-28 00:00:00', '2017-07-28 00:00:00', 1, 3),
(337, 337, 50, 0, '2017-07-27 00:00:00', '2017-07-27 00:00:00', 1, 13),
(338, 338, 380, 0, '2017-07-27 00:00:00', '2017-07-27 00:00:00', 1, 13),
(339, 339, 180, 0, '2017-07-27 00:00:00', '2017-07-27 00:00:00', 1, 13),
(340, 340, 1000, 0, '2017-07-27 00:00:00', '2017-07-27 00:00:00', 1, 13),
(341, 341, 200, 0, '2017-07-26 00:00:00', '2017-07-26 00:00:00', 1, 13),
(342, 342, 40, 0, '2017-10-14 12:11:00', '2017-08-28 00:00:00', 1, 13),
(343, 343, 80, 0, '2017-07-27 00:00:00', '2017-07-27 00:00:00', 1, 13),
(344, 344, 300, 0, '2017-07-25 00:00:00', '2017-07-25 00:00:00', 1, 13),
(345, 345, 15000, 0, '2017-07-31 00:00:00', '2017-07-31 00:00:00', 1, 3),
(346, 346, 150, 0, '2017-09-14 00:00:00', '2017-08-01 00:00:00', 1, 3),
(347, 347, 150, 0, '2017-08-02 00:00:00', '2017-08-02 00:00:00', 1, 3),
(348, 348, 150, 0, '2017-08-02 00:00:00', '2017-08-02 00:00:00', 1, 3),
(349, 349, 170, 0, '2017-08-02 00:00:00', '2017-08-02 00:00:00', 1, 3),
(350, 350, 400, 0, '2017-08-03 00:00:00', '2017-08-03 00:00:00', 1, 3),
(351, 351, 2000, 0, '2017-08-03 00:00:00', '2017-08-03 00:00:00', 1, 3),
(352, 352, 250, 0, '2017-10-11 18:20:00', '2017-09-08 00:00:00', 1, 3),
(353, 353, 1200, 0, '2017-08-07 00:00:00', '2017-08-07 00:00:00', 1, 3),
(354, 354, 300, 0, '2017-08-07 00:00:00', '2017-08-07 00:00:00', 1, 3),
(355, 355, 500, 0, '2017-08-08 00:00:00', '2017-08-08 00:00:00', 1, 3),
(356, 356, 250, 0, '2017-08-08 00:00:00', '2017-08-08 00:00:00', 1, 3),
(357, 357, 8000, 0, '2017-08-08 00:00:00', '2017-08-08 00:00:00', 1, 3),
(358, 358, 250, 0, '2017-08-08 00:00:00', '2017-08-08 00:00:00', 1, 3),
(359, 359, 250, 0, '2017-08-09 00:00:00', '2017-08-09 00:00:00', 1, 3),
(360, 360, 50, 0, '2017-08-10 00:00:00', '2017-08-10 00:00:00', 1, 3),
(361, 361, 5000, 0, '2017-08-11 00:00:00', '2017-08-11 00:00:00', 1, 3),
(362, 362, 400, 0, '2017-08-14 00:00:00', '2017-08-14 00:00:00', 1, 3),
(363, 363, 1000, 0, '2017-08-12 00:00:00', '2017-08-12 00:00:00', 1, 13),
(364, 364, 600, 0, '2017-08-12 00:00:00', '2017-08-12 00:00:00', 1, 13),
(365, 365, 200, 0, '2017-08-15 00:00:00', '2017-08-15 00:00:00', 1, 3),
(366, 366, 80, 0, '2017-08-15 00:00:00', '2017-08-15 00:00:00', 1, 3),
(367, 367, 60, 0, '2017-08-15 00:00:00', '2017-08-15 00:00:00', 1, 3),
(368, 368, 2000, 0, '2017-08-15 00:00:00', '2017-08-15 00:00:00', 1, 3),
(369, 369, 5000, 0, '2017-08-15 00:00:00', '2017-08-15 00:00:00', 1, 3),
(370, 370, 250, 0, '2017-08-16 00:00:00', '2017-08-16 00:00:00', 1, 3),
(371, 371, 1000, 0, '2017-08-16 00:00:00', '2017-08-16 00:00:00', 1, 3),
(372, 372, 450, 0, '2017-10-04 12:19:00', '2017-08-16 00:00:00', 1, 3),
(373, 373, 100, 0, '2017-08-17 00:00:00', '2017-08-17 00:00:00', 1, 3),
(374, 374, 50, 0, '2017-08-17 00:00:00', '2017-08-17 00:00:00', 1, 3),
(375, 375, 150, 0, '2017-08-17 00:00:00', '2017-08-17 00:00:00', 1, 3),
(376, 376, 250, 0, '2017-08-17 00:00:00', '2017-08-17 00:00:00', 1, 3),
(377, 377, 40, 0, '2017-08-17 00:00:00', '2017-08-17 00:00:00', 1, 3),
(378, 378, 500, 0, '2017-08-18 00:00:00', '2017-08-18 00:00:00', 1, 3),
(379, 379, 300, 0, '2017-08-19 00:00:00', '2017-08-19 00:00:00', 1, 12),
(380, 380, 30, 0, '2017-08-19 00:00:00', '2017-08-19 00:00:00', 1, 12),
(381, 381, 200, 0, '2017-08-21 00:00:00', '2017-08-21 00:00:00', 1, 3),
(382, 382, 500, 0, '2017-10-24 15:46:15', '2017-10-24 15:46:15', 1, 3),
(383, 383, 500, 0, '2017-09-04 00:00:00', '2017-09-04 00:00:00', 1, 3),
(384, 384, 700, 0, '2017-08-21 00:00:00', '2017-08-21 00:00:00', 1, 3),
(385, 385, 250, 0, '2017-08-21 00:00:00', '2017-08-21 00:00:00', 1, 3),
(386, 386, 850, 0, '2017-08-21 00:00:00', '2017-08-21 00:00:00', 1, 3),
(387, 387, 850, 0, '2017-08-21 00:00:00', '2017-08-21 00:00:00', 1, 3),
(388, 388, 450, 0, '2017-09-04 00:00:00', '2017-08-17 00:00:00', 1, 3),
(389, 389, 3000, 0, '2017-08-22 00:00:00', '2017-08-22 00:00:00', 1, 3),
(390, 390, 150, 0, '2017-08-23 00:00:00', '2017-08-23 00:00:00', 1, 3),
(391, 391, 750, 0, '2017-08-23 00:00:00', '2017-08-23 00:00:00', 1, 3),
(392, 392, 100, 0, '2017-08-24 00:00:00', '2017-08-24 00:00:00', 1, 3),
(393, 393, 600, 0, '2017-09-04 00:00:00', '2017-09-04 00:00:00', 1, 3),
(394, 394, 550, 0, '2017-08-17 00:00:00', '2017-08-17 00:00:00', 1, 3),
(395, 395, 400, 0, '2017-08-24 00:00:00', '2017-08-24 00:00:00', 1, 3),
(396, 396, 350, 0, '2017-08-24 00:00:00', '2017-08-24 00:00:00', 1, 3),
(397, 397, 1000, 0, '2017-09-28 11:35:00', '2017-08-25 00:00:00', 1, 8),
(398, 398, 1000, 0, '2017-08-25 00:00:00', '2017-08-25 00:00:00', 1, 3),
(399, 399, 400, 0, '2017-08-25 00:00:00', '2017-08-25 00:00:00', 1, 3),
(400, 400, 1000, 0, '2017-09-28 08:34:00', '2017-08-25 00:00:00', 1, 3),
(401, 401, 100, 0, '2017-08-25 00:00:00', '2017-08-25 00:00:00', 1, 3),
(402, 402, 300, 0, '2017-08-25 00:00:00', '2017-08-25 00:00:00', 1, 3),
(403, 403, 300, 0, '2017-08-25 00:00:00', '2017-08-25 00:00:00', 1, 3),
(404, 404, 200, 0, '2017-08-25 00:00:00', '2017-08-25 00:00:00', 1, 3),
(405, 405, 500, 0, '2017-10-02 14:59:00', '2017-08-28 00:00:00', 1, 3),
(406, 406, 18000, 0, '2017-08-28 00:00:00', '2017-08-28 00:00:00', 1, 3),
(407, 407, 700, 0, '2017-08-28 00:00:00', '2017-08-28 00:00:00', 1, 13),
(408, 408, 150, 0, '2017-08-28 00:00:00', '2017-08-28 00:00:00', 1, 13),
(409, 409, 100, 0, '2017-08-29 00:00:00', '2017-08-29 00:00:00', 1, 3),
(410, 410, 100, 0, '2017-09-05 00:00:00', '2017-09-05 00:00:00', 1, 3),
(411, 411, 200, 0, '2017-08-29 00:00:00', '2017-08-29 00:00:00', 1, 3),
(412, 412, 70, 0, '2017-08-29 00:00:00', '2017-08-29 00:00:00', 1, 3),
(413, 413, 300, 0, '2017-08-30 00:00:00', '2017-08-30 00:00:00', 1, 3),
(414, 414, 80, 0, '2017-08-30 00:00:00', '2017-08-30 00:00:00', 1, 3),
(415, 415, 300, 0, '2017-08-31 00:00:00', '2017-08-31 00:00:00', 1, 3),
(416, 416, 100, 0, '2017-08-31 00:00:00', '2017-08-31 00:00:00', 1, 9),
(417, 417, 900, 0, '2017-09-01 00:00:00', '2017-09-01 00:00:00', 1, 3),
(418, 418, 150, 0, '2017-09-01 00:00:00', '2017-09-01 00:00:00', 1, 3),
(419, 419, 300, 0, '2017-09-01 00:00:00', '2017-09-01 00:00:00', 1, 3),
(420, 420, 700, 0, '2017-09-01 00:00:00', '2017-09-01 00:00:00', 1, 3),
(421, 421, 1500, 0, '2017-09-02 00:00:00', '2017-09-02 00:00:00', 1, 12),
(422, 422, 450, 0, '2017-09-04 00:00:00', '2017-09-04 00:00:00', 1, 3),
(423, 423, 1000, 0, '2017-09-04 00:00:00', '2017-09-04 00:00:00', 1, 3),
(424, 424, 1000, 0, '2017-09-04 00:00:00', '2017-09-04 00:00:00', 1, 3),
(425, 425, 3000, 0, '2017-09-04 00:00:00', '2017-09-04 00:00:00', 1, 13),
(426, 426, 1500, 0, '2017-10-06 11:26:00', '2017-09-04 00:00:00', 1, 3),
(427, 427, 150, 0, '2017-09-05 00:00:00', '2017-09-05 00:00:00', 1, 3),
(428, 428, 500, 0, '2017-09-05 00:00:00', '2017-09-05 00:00:00', 1, 3),
(429, 429, 100, 0, '2017-09-05 00:00:00', '2017-09-05 00:00:00', 1, 13),
(430, 430, 100, 0, '2017-08-23 00:00:00', '2017-08-23 00:00:00', 1, 13),
(442, 442, 500, 0, '2017-09-05 00:00:00', '2017-09-05 00:00:00', 1, 3),
(433, 433, 350, 0, '2017-08-17 00:00:00', '2017-08-17 00:00:00', 1, 13),
(434, 434, 900, 0, '2017-09-01 00:00:00', '2017-09-01 00:00:00', 1, 13),
(435, 435, 250, 0, '2017-08-31 00:00:00', '2017-08-31 00:00:00', 1, 13),
(436, 436, 3000, 0, '2017-09-03 00:00:00', '2017-09-03 00:00:00', 1, 13),
(437, 437, 400, 0, '2017-08-19 00:00:00', '2017-08-19 00:00:00', 1, 13),
(438, 438, 240, 0, '2017-08-19 00:00:00', '2017-08-19 00:00:00', 1, 13),
(439, 439, 100, 0, '2017-08-23 00:00:00', '2017-08-23 00:00:00', 1, 13),
(440, 440, 350, 0, '2017-08-19 00:00:00', '2017-08-19 00:00:00', 1, 13),
(441, 441, 500, 0, '2017-08-11 00:00:00', '2017-08-11 00:00:00', 1, 13),
(443, 443, 350, 0, '2017-09-07 00:00:00', '2017-09-07 00:00:00', 1, 3),
(444, 444, 150, 0, '2017-09-07 00:00:00', '2017-09-07 00:00:00', 1, 3),
(445, 445, 450, 0, '2017-09-07 00:00:00', '2017-09-07 00:00:00', 1, 3),
(446, 446, 500, 0, '2017-09-06 00:00:00', '2017-09-06 00:00:00', 1, 3),
(447, 447, 300, 0, '2017-10-10 16:15:00', '2017-09-08 00:00:00', 1, 3),
(448, 448, 200, 0, '2017-09-08 00:00:00', '2017-09-08 00:00:00', 1, 3),
(449, 449, 300, 0, '2017-10-13 11:17:18', '2017-09-08 00:00:00', 1, 13),
(452, 452, 400, 0, '2017-09-08 00:00:00', '2017-09-08 00:00:00', 1, 3),
(451, 451, 600, 0, '2017-09-07 00:00:00', '2017-09-07 00:00:00', 1, 3),
(453, 453, 280, 0, '2017-09-09 00:00:00', '2017-09-09 00:00:00', 1, 12),
(454, 454, 650, 0, '2017-10-10 16:27:00', '2017-09-09 00:00:00', 1, 12),
(455, 455, 1200, 0, '2017-09-09 00:00:00', '2017-09-09 00:00:00', 1, 12),
(456, 456, 1300, 0, '2017-09-09 00:00:00', '2017-09-09 00:00:00', 1, 12),
(457, 457, 500, 0, '2017-09-09 00:00:00', '2017-09-09 00:00:00', 1, 16),
(458, 458, 1000, 0, '2017-09-09 00:00:00', '2017-09-09 00:00:00', 1, 16),
(459, 459, 400, 0, '2017-10-11 10:15:17', '2017-09-11 00:00:00', 1, 3),
(460, 460, 700, 0, '2017-09-11 00:00:00', '2017-09-11 00:00:00', 1, 3),
(461, 461, 400, 0, '2017-09-11 00:00:00', '2017-09-11 00:00:00', 1, 3),
(462, 462, 250, 0, '2017-09-11 00:00:00', '2017-09-11 00:00:00', 1, 3),
(463, 463, 350, 0, '2017-10-27 17:12:49', '2017-10-27 17:12:49', 1, 3),
(464, 464, 180, 0, '2017-09-11 00:00:00', '2017-09-11 00:00:00', 1, 3),
(465, 465, 15000, 0, '2017-09-11 00:00:00', '2017-09-11 00:00:00', 1, 3),
(466, 466, 80, 0, '2017-09-11 00:00:00', '2017-09-11 00:00:00', 1, 3),
(467, 467, 60, 0, '2017-07-24 00:00:00', '2017-07-24 00:00:00', 1, 3),
(468, 468, 30, 0, '2017-06-21 00:00:00', '2017-06-21 00:00:00', 1, 3),
(469, 469, 100, 0, '2017-07-24 00:00:00', '2017-07-24 00:00:00', 1, 13),
(470, 470, 700, 0, '2017-10-12 11:42:00', '2017-09-12 00:00:00', 1, 3),
(471, 471, 300, 0, '2017-07-31 00:00:00', '2017-07-31 00:00:00', 1, 3),
(472, 472, 40, 0, '2017-09-12 00:00:00', '2017-09-12 00:00:00', 1, 3),
(473, 473, 350, 0, '2017-07-10 00:00:00', '2017-07-10 00:00:00', 1, 3),
(474, 474, 150, 0, '2017-07-31 00:00:00', '2017-07-31 00:00:00', 1, 3),
(475, 475, 1100, 0, '2017-03-17 00:00:00', '2017-03-17 00:00:00', 1, 3),
(476, 476, 300, 0, '2017-06-10 00:00:00', '2017-06-10 00:00:00', 1, 3),
(477, 477, 20, 0, '2017-09-12 13:34:36', '2017-09-12 13:34:36', 1, 9),
(478, 478, 100, 0, '2017-09-12 14:26:00', '2017-09-12 14:26:00', 1, 13),
(479, 479, 400, 0, '2017-09-12 18:07:59', '2017-09-12 18:07:59', 1, 3),
(480, 480, 12, 0, '2017-09-12 20:11:25', '2017-09-12 20:11:25', 1, 9),
(481, 481, 400, 0, '2017-10-09 17:26:06', '2017-08-07 11:09:06', 1, 3),
(482, 482, 150, 0, '2017-09-13 11:55:26', '2017-09-13 11:55:26', 1, 3),
(483, 483, 200, 0, '2017-09-11 18:43:38', '2017-09-11 18:43:38', 1, 3),
(484, 484, 250, 0, '2017-09-13 19:14:33', '2017-09-13 19:14:33', 1, 3),
(485, 485, 12000, 0, '2017-09-14 11:27:52', '2017-09-14 11:27:52', 1, 3),
(486, 486, 50, 0, '2017-09-14 12:02:40', '2017-09-14 12:02:40', 1, 3),
(487, 487, 400, 0, '2017-09-14 12:29:23', '2017-09-14 12:29:23', 1, 3),
(488, 488, 80, 0, '2017-09-14 12:38:27', '2017-09-14 12:38:27', 1, 3),
(489, 489, 500, 0, '2017-09-14 17:59:25', '2017-09-14 17:59:25', 1, 3),
(490, 490, 2000, 0, '2017-09-15 10:27:37', '2017-09-15 10:27:37', 1, 3),
(491, 491, 2000, 0, '2017-10-27 14:26:30', '2017-09-15 12:17:30', 1, 3),
(492, 492, 200, 0, '2017-09-15 15:55:38', '2017-09-15 15:55:38', 1, 13),
(493, 493, 400, 0, '2017-09-15 16:52:32', '2017-09-15 16:52:32', 1, 13),
(494, 494, 25000, 0, '2017-09-15 17:05:04', '2017-09-15 17:05:04', 1, 13),
(495, 495, 200, 0, '2017-09-15 19:48:55', '2017-09-15 19:48:55', 1, 13),
(496, 496, 400, 0, '2017-09-16 11:53:43', '2017-09-16 11:53:43', 1, 12),
(497, 497, 250, 0, '2017-09-16 12:33:40', '2017-09-16 12:33:40', 1, 12),
(498, 498, 550, 0, '2017-09-16 12:45:34', '2017-09-16 12:45:34', 1, 12),
(499, 499, 250, 0, '2017-09-16 12:54:26', '2017-09-16 12:54:26', 1, 12),
(500, 500, 450, 0, '2017-09-16 16:24:32', '2017-09-16 16:24:32', 1, 12),
(501, 501, 600, 0, '2017-09-16 18:06:03', '2017-09-16 18:06:03', 1, 13),
(502, 502, 250, 0, '2017-09-18 10:12:35', '2017-09-18 10:12:35', 1, 3),
(503, 503, 400, 0, '2017-09-18 11:17:18', '2017-09-18 11:17:18', 1, 3),
(504, 504, 800, 0, '2017-09-18 12:04:28', '2017-09-18 12:04:28', 1, 3),
(505, 505, 120, 0, '2017-10-04 16:15:35', '2017-09-18 12:21:35', 1, 3),
(506, 506, 150, 0, '2017-09-18 12:26:50', '2017-09-18 12:26:50', 1, 3),
(507, 507, 1000, 0, '2017-09-18 00:52:04', '2017-09-18 00:52:04', 1, 3),
(508, 508, 350, 0, '2017-09-18 02:45:47', '2017-09-18 02:45:47', 1, 3),
(509, 509, 200, 0, '2017-09-18 03:15:55', '2017-09-18 03:15:55', 1, 3),
(510, 510, 1000, 0, '2017-09-18 05:05:44', '2017-09-18 05:05:44', 1, 3),
(511, 511, 26, 0, '2017-09-18 23:49:57', '2017-09-18 23:49:57', 1, 9),
(512, 512, 150, 0, '2017-09-19 13:24:58', '2017-09-19 13:24:58', 1, 3),
(513, 513, 250, 0, '2017-09-19 14:13:11', '2017-09-19 14:13:11', 1, 3),
(514, 514, 1000, 0, '2017-09-19 15:43:26', '2017-09-19 15:43:26', 1, 3),
(515, 515, 350, 0, '2017-09-19 17:08:59', '2017-09-19 17:08:59', 1, 3),
(516, 516, 400, 0, '2017-09-19 19:48:11', '2017-09-19 19:48:11', 1, 8),
(517, 517, 3500, 0, '2017-09-20 09:50:38', '2017-09-20 09:50:38', 1, 3),
(518, 518, 200, 0, '2017-09-20 11:37:46', '2017-09-20 11:37:46', 1, 3),
(519, 519, 600, 0, '2017-10-17 10:04:50', '2017-09-20 11:57:50', 1, 3),
(520, 520, 100, 0, '2017-09-20 16:10:12', '2017-09-20 16:10:12', 1, 3),
(521, 521, 300, 0, '2017-09-20 16:17:30', '2017-09-20 16:17:30', 1, 3),
(522, 522, 300, 0, '2017-09-20 18:58:37', '2017-09-20 18:58:37', 1, 3),
(523, 523, 900, 0, '2017-09-21 10:25:37', '2017-09-21 10:25:37', 1, 3),
(524, 524, 170, 0, '2017-09-21 17:06:32', '2017-09-21 17:06:32', 1, 3),
(525, 525, 350, 0, '2017-09-21 18:02:25', '2017-09-21 18:02:25', 1, 3),
(526, 526, 250, 0, '2017-09-21 18:36:34', '2017-09-21 18:36:34', 1, 3),
(527, 527, 20, 0, '2017-09-22 09:14:46', '2017-09-22 09:14:46', 1, 13),
(528, 528, 50, 0, '2017-09-22 10:34:47', '2017-09-22 10:34:47', 1, 3),
(529, 529, 400, 0, '2017-09-22 14:27:27', '2017-09-22 14:27:27', 1, 3),
(530, 530, 100, 0, '2017-09-22 16:04:01', '2017-09-22 16:04:01', 1, 3),
(531, 531, 300, 0, '2017-10-20 15:26:27', '2017-09-22 16:31:27', 1, 3),
(532, 532, 60, 0, '2017-09-22 17:24:00', '2017-09-22 17:24:00', 1, 9),
(533, 533, 11, 0, '2017-09-22 18:02:09', '2017-09-22 18:02:09', 1, 1),
(534, 534, 40, 0, '2017-09-22 18:42:05', '2017-09-22 18:42:05', 1, 1),
(535, 535, 400, 0, '2017-09-22 18:42:28', '2017-09-22 18:42:28', 1, 13),
(536, 536, 200, 0, '2017-10-20 19:02:12', '2017-09-22 19:13:48', 1, 3),
(537, 537, 450, 0, '2017-09-23 14:46:07', '2017-09-23 14:46:07', 1, 12),
(538, 538, 150, 0, '2017-09-23 15:16:30', '2017-09-23 15:16:30', 1, 13),
(539, 539, 350, 0, '2017-10-30 09:56:30', '2017-10-30 09:56:30', 1, 13),
(540, 540, 250, 0, '2017-09-25 12:04:01', '2017-09-25 12:04:01', 1, 3),
(541, 541, 250, 0, '2017-09-25 15:41:12', '2017-09-25 15:41:12', 1, 3),
(542, 542, 350, 0, '2017-09-25 16:58:50', '2017-09-25 16:58:50', 1, 3),
(543, 543, 800, 0, '2017-10-28 11:20:10', '2017-09-25 19:24:36', 1, 3),
(544, 544, 500, 0, '2017-10-30 10:41:10', '2017-09-26 10:35:10', 1, 3),
(545, 545, 600, 0, '2017-09-26 12:02:29', '2017-09-26 12:02:29', 1, 3),
(546, 546, 100, 0, '2017-09-26 16:47:57', '2017-09-26 16:47:57', 1, 9),
(547, 547, 150, 0, '2017-09-26 16:48:36', '2017-09-26 16:48:36', 1, 9),
(548, 548, 100, 0, '2017-09-26 17:11:42', '2017-09-26 17:11:42', 1, 9),
(549, 549, 30, 0, '2017-09-26 17:16:26', '2017-09-26 17:16:26', 1, 9),
(550, 550, 80, 0, '2017-09-26 17:17:18', '2017-09-26 17:17:18', 1, 9),
(551, 551, 80, 0, '2017-09-26 17:24:54', '2017-09-26 17:24:54', 1, 9),
(552, 552, 80, 0, '2017-09-26 17:25:13', '2017-09-26 17:25:13', 1, 9),
(553, 553, 80, 0, '2017-09-26 17:31:11', '2017-09-26 17:31:11', 1, 3),
(554, 554, 24, 0, '2017-09-26 17:38:41', '2017-09-26 17:38:41', 1, 9),
(555, 555, 100, 0, '2017-09-26 17:41:45', '2017-09-26 17:41:45', 1, 9),
(556, 556, 80, 0, '2017-09-26 19:24:24', '2017-09-26 19:24:24', 1, 9),
(557, 557, 100, 0, '2017-09-26 19:39:13', '2017-09-26 19:39:13', 1, 1),
(558, 558, 100, 0, '2017-09-26 20:23:54', '2017-09-26 20:23:54', 1, 9),
(559, 559, 50, 0, '2017-10-30 10:32:10', '2017-09-26 20:33:10', 1, 9),
(560, 560, 150, 0, '2017-09-26 20:59:45', '2017-09-26 20:59:45', 1, 9),
(561, 561, 300, 0, '2017-09-27 09:43:50', '2017-09-27 09:43:50', 1, 13),
(562, 562, 700, 0, '2017-10-30 10:32:44', '2017-09-27 12:28:44', 1, 3),
(563, 563, 300, 0, '2017-10-30 10:33:35', '2017-09-27 12:30:35', 1, 3),
(564, 564, 4000, 0, '2017-09-27 16:55:23', '2017-09-27 16:55:23', 1, 3),
(565, 565, 10000, 0, '2017-09-27 17:27:54', '2017-09-27 17:27:54', 1, 3),
(566, 566, 200, 0, '2017-09-27 17:44:33', '2017-09-27 17:44:33', 1, 3),
(567, 567, 750, 0, '2017-09-27 17:53:18', '2017-09-27 17:53:18', 1, 3),
(568, 568, 20, 0, '2017-09-27 18:42:27', '2017-09-27 18:42:27', 1, 3),
(569, 569, 400, 0, '2017-09-28 12:03:31', '2017-09-28 12:03:31', 1, 3),
(570, 570, 200, 0, '2017-09-28 18:18:33', '2017-09-28 18:18:33', 1, 3),
(571, 571, 50, 0, '2017-09-28 19:37:59', '2017-09-28 19:37:59', 1, 3),
(572, 572, 300, 0, '2017-09-29 10:03:42', '2017-09-29 10:03:42', 1, 3),
(573, 573, 1000, 0, '2017-09-29 10:23:52', '2017-09-29 10:23:52', 1, 3),
(574, 574, 200, 0, '2017-09-29 10:39:36', '2017-09-29 10:39:36', 1, 3),
(575, 575, 80, 0, '2017-09-29 10:55:52', '2017-09-29 10:55:52', 1, 3),
(576, 576, 150, 0, '2017-09-29 11:18:08', '2017-09-29 11:18:08', 1, 3),
(577, 577, 200, 0, '2017-09-29 11:58:11', '2017-09-29 11:58:11', 1, 3),
(578, 578, 50, 0, '2017-09-29 13:44:50', '2017-09-29 13:44:50', 1, 3),
(579, 579, 200, 0, '2017-09-29 16:59:25', '2017-09-29 16:59:25', 1, 3),
(580, 580, 200, 0, '2017-09-29 19:18:15', '2017-09-29 19:18:15', 1, 3),
(581, 581, 800, 0, '2017-09-30 10:09:20', '2017-09-30 10:09:20', 1, 13),
(582, 582, 450, 0, '2017-09-30 13:39:57', '2017-09-30 13:39:57', 1, 13),
(583, 583, 450, 0, '2017-09-30 13:41:30', '2017-09-30 13:41:30', 1, 13),
(584, 584, 3600, 0, '2017-10-02 15:16:15', '2017-10-02 15:16:15', 1, 3),
(585, 585, 70, 0, '2017-10-02 18:24:31', '2017-10-02 18:24:31', 1, 3),
(586, 586, 550, 0, '2017-10-03 10:01:45', '2017-10-03 10:01:45', 1, 3),
(587, 587, 800, 0, '2017-10-03 10:30:20', '2017-10-03 10:30:20', 1, 3),
(588, 588, 350, 0, '2017-10-03 11:15:34', '2017-10-03 11:15:34', 1, 3),
(589, 589, 200, 0, '2017-10-03 11:57:05', '2017-10-03 11:57:05', 1, 3),
(590, 590, 500, 0, '2017-10-03 13:18:52', '2017-10-03 13:18:52', 1, 3),
(591, 591, 300, 0, '2017-10-03 13:31:22', '2017-10-03 13:31:22', 1, 13),
(592, 592, 300, 0, '2017-10-03 14:45:28', '2017-10-03 14:45:28', 1, 13),
(593, 593, 300, 0, '2017-10-03 14:52:27', '2017-10-03 14:52:27', 1, 13),
(594, 594, 1700, 0, '2017-10-03 15:45:07', '2017-10-03 15:45:07', 1, 13),
(595, 595, 3500, 0, '2017-10-03 16:52:07', '2017-10-03 16:52:07', 1, 13),
(596, 596, 40, 0, '2017-10-03 19:14:02', '2017-10-03 19:14:02', 1, 3),
(597, 597, 200, 0, '2017-10-04 09:36:47', '2017-10-04 09:36:47', 1, 3),
(598, 598, 250, 0, '2017-10-04 11:31:42', '2017-10-04 11:31:42', 1, 3),
(599, 599, 550, 0, '2017-10-04 12:48:38', '2017-10-04 12:48:38', 1, 3),
(600, 600, 200, 0, '2017-10-04 13:08:16', '2017-10-04 13:08:16', 1, 13),
(601, 601, 700, 0, '2017-10-04 13:39:15', '2017-10-04 13:39:15', 1, 3),
(602, 602, 550, 0, '2017-10-04 13:56:07', '2017-10-04 13:56:07', 1, 3),
(603, 603, 400, 0, '2017-10-05 12:15:11', '2017-10-05 12:15:11', 1, 3),
(604, 604, 100, 0, '2017-10-05 13:39:57', '2017-10-05 13:39:57', 1, 3),
(605, 605, 600, 0, '2017-10-05 14:22:22', '2017-10-05 14:22:22', 1, 3),
(606, 606, 300, 0, '2017-10-07 13:53:52', '2017-10-07 13:53:52', 1, 12),
(607, 607, 100, 0, '2017-10-07 16:04:49', '2017-10-07 16:04:49', 1, 12),
(608, 608, 150, 0, '2017-10-09 09:54:48', '2017-10-09 09:54:48', 1, 3),
(609, 609, 450, 0, '2017-10-09 10:01:46', '2017-10-09 10:01:46', 1, 3),
(610, 610, 500, 0, '2017-10-09 10:20:33', '2017-10-09 10:20:33', 1, 3),
(611, 611, 200, 0, '2017-10-09 10:33:46', '2017-10-09 10:33:46', 1, 3),
(612, 612, 600, 0, '2017-10-09 12:37:02', '2017-10-09 12:37:02', 1, 3),
(613, 613, 231, 0, '2017-10-09 14:41:19', '2017-10-09 14:41:19', 1, 13),
(614, 614, 700, 0, '2017-10-10 10:04:31', '2017-10-10 10:04:31', 1, 13),
(615, 615, 550, 0, '2017-10-10 10:21:03', '2017-10-10 10:21:03', 1, 3),
(616, 616, 300, 0, '2017-10-10 13:08:47', '2017-10-10 13:08:47', 1, 9),
(617, 617, 100, 0, '2017-10-10 14:01:16', '2017-10-10 14:01:16', 1, 9),
(618, 618, 3000, 0, '2017-10-10 14:02:46', '2017-10-10 14:02:46', 1, 9),
(619, 619, 500, 0, '2017-10-10 14:11:20', '2017-10-10 14:11:20', 1, 9),
(620, 620, 350, 0, '2017-10-11 09:13:36', '2017-10-11 09:13:36', 1, 3),
(621, 621, 180, 0, '2017-10-11 12:24:57', '2017-10-11 12:24:57', 1, 3),
(622, 622, 40, 0, '2017-10-11 18:07:44', '2017-10-11 18:07:44', 1, 3),
(623, 623, 500, 0, '2017-10-12 11:23:55', '2017-10-12 11:23:55', 1, 13),
(624, 624, 350, 0, '2017-10-12 15:23:37', '2017-10-12 15:23:37', 1, 3),
(625, 625, 350, 0, '2017-10-12 15:23:51', '2017-10-12 15:23:51', 1, 3),
(626, 626, 60, 0, '2017-10-12 15:56:57', '2017-10-12 15:56:57', 1, 13),
(627, 627, 600, 0, '2017-10-12 16:02:45', '2017-10-12 16:02:45', 1, 13),
(628, 628, 320, 0, '2017-10-13 09:53:05', '2017-10-13 09:53:05', 1, 3),
(629, 629, 600, 0, '2017-10-13 16:06:59', '2017-10-13 16:06:59', 1, 3),
(630, 630, 700, 0, '2017-10-13 16:56:06', '2017-10-13 16:56:06', 1, 3),
(631, 631, 600, 0, '2017-10-13 18:13:57', '2017-10-13 18:13:57', 1, 13),
(632, 632, 200, 0, '2017-10-14 11:15:45', '2017-10-14 11:15:45', 1, 12),
(633, 633, 30, 0, '2017-10-14 12:06:44', '2017-10-14 12:06:44', 1, 12),
(634, 634, 250, 0, '2017-10-14 13:03:05', '2017-10-14 13:03:05', 1, 12),
(635, 635, 90, 0, '2017-10-14 16:01:07', '2017-10-14 16:01:07', 1, 12),
(636, 636, 10000, 0, '2017-10-16 09:46:00', '2017-10-16 09:46:00', 1, 3),
(637, 637, 350, 0, '2017-10-16 12:09:49', '2017-10-16 12:09:49', 1, 13),
(638, 638, 200, 0, '2017-10-16 13:30:47', '2017-10-16 13:30:47', 1, 13),
(639, 639, 20, 0, '2017-10-16 14:14:41', '2017-10-16 14:14:41', 1, 3),
(640, 640, 300, 0, '2017-10-16 16:41:43', '2017-10-16 16:41:43', 1, 9),
(641, 641, 50, 0, '2017-10-16 16:50:27', '2017-10-16 16:50:27', 1, 3),
(642, 642, 300, 0, '2017-10-16 18:23:03', '2017-10-16 18:23:03', 1, 9),
(643, 643, 200, 0, '2017-10-16 18:24:52', '2017-10-16 18:24:52', 1, 9),
(644, 644, 1000, 0, '2017-10-17 09:11:13', '2017-10-17 09:11:13', 1, 3),
(645, 645, 600, 0, '2017-10-17 14:22:29', '2017-10-17 14:22:29', 1, 3),
(646, 646, 250, 0, '2017-10-17 16:53:14', '2017-10-17 16:53:14', 1, 13),
(647, 647, 150, 0, '2017-10-18 13:44:48', '2017-10-18 13:44:48', 1, 13),
(648, 648, 700, 0, '2017-10-18 14:35:18', '2017-10-18 14:35:18', 1, 13),
(649, 649, 1200, 0, '2017-10-18 17:37:11', '2017-10-18 17:37:11', 1, 13),
(650, 650, 850, 0, '2017-10-18 17:53:01', '2017-10-18 17:53:01', 1, 13),
(651, 651, 1500, 0, '2017-09-19 18:11:54', '2017-09-19 18:11:54', 1, 13),
(652, 652, 450, 0, '2017-10-18 18:26:47', '2017-10-18 18:26:47', 1, 13),
(653, 653, 1000, 0, '2017-10-18 18:42:49', '2017-10-18 18:42:49', 1, 13),
(654, 654, 150, 0, '2017-10-20 10:17:02', '2017-10-20 10:17:02', 1, 3),
(655, 655, 60, 0, '2017-10-20 17:03:34', '2017-10-20 17:03:34', 1, 3),
(656, 656, 100, 0, '2017-10-20 18:10:33', '2017-10-20 18:10:33', 1, 3),
(657, 657, 50, 0, '2017-10-20 19:14:14', '2017-10-20 19:14:14', 1, 3),
(658, 658, 150, 0, '2017-10-21 10:17:13', '2017-10-21 10:17:13', 1, 8),
(659, 659, 300, 0, '2017-10-20 12:44:11', '2017-10-20 12:44:11', 1, 9),
(660, 660, 200, 0, '2017-10-23 13:10:19', '2017-10-23 13:10:19', 1, 3),
(661, 661, 250, 0, '2017-10-23 14:34:31', '2017-10-23 14:34:31', 1, 3),
(662, 662, 200, 0, '2017-10-24 12:52:05', '2017-10-24 12:52:05', 1, 3),
(663, 663, 200, 0, '2017-10-24 12:53:22', '2017-10-24 12:53:22', 1, 3),
(664, 664, 150, 0, '2017-09-19 13:48:18', '2017-09-19 13:48:18', 1, 3),
(665, 665, 300, 0, '2017-10-24 15:28:13', '2017-10-24 15:28:13', 1, 3),
(666, 666, 600, 0, '2017-10-19 17:45:16', '2017-10-19 17:45:16', 1, 3),
(667, 667, 250, 0, '2017-10-24 18:25:43', '2017-10-24 18:25:43', 1, 3),
(668, 668, 70, 0, '2017-09-19 17:25:01', '2017-09-19 17:25:01', 1, 3),
(669, 669, 850, 0, '2017-09-19 11:03:54', '2017-09-19 11:03:54', 1, 3),
(670, 670, 11, 0, '2017-10-26 11:30:41', '2017-10-26 11:30:41', 1, 9),
(671, 671, 22, 0, '2017-10-26 11:32:46', '2017-10-26 11:32:46', 1, 9),
(672, 672, 300, 0, '2017-10-26 12:49:15', '2017-10-26 12:49:15', 1, 3),
(673, 673, 900, 0, '2017-10-26 14:13:12', '2017-10-26 14:13:12', 1, 3),
(674, 674, 350, 0, '2017-10-26 14:54:21', '2017-10-26 14:54:21', 1, 3),
(675, 675, 350, 0, '2017-10-26 18:02:34', '2017-10-26 18:02:34', 1, 3),
(676, 676, 200, 0, '2017-10-26 18:19:15', '2017-10-26 18:19:15', 1, 3),
(677, 677, 181, 0, '2017-10-27 11:01:39', '2017-10-27 11:01:39', 1, 9),
(678, 678, 4600, 0, '2017-10-27 17:17:07', '2017-10-27 17:17:07', 1, 13),
(679, 679, 43, 0, '2017-10-27 17:21:59', '2017-10-27 17:21:59', 1, 1),
(680, 680, 500, 0, '2017-10-27 18:35:48', '2017-10-27 18:35:48', 1, 13),
(681, 681, 250, 0, '2017-10-28 10:39:57', '2017-10-28 10:39:57', 1, 13),
(682, 682, 100, 0, '2017-10-28 10:57:07', '2017-10-28 10:57:07', 1, 13),
(683, 683, 149, 0, '2017-10-28 11:05:08', '2017-10-28 11:05:08', 1, 13),
(684, 684, 150, 0, '2017-10-28 11:06:31', '2017-10-28 11:06:31', 1, 13),
(685, 685, 40, 0, '2017-10-28 09:16:24', '2017-10-28 09:16:24', 1, 13),
(686, 686, 120, 0, '2017-10-28 09:34:43', '2017-10-28 09:34:43', 1, 13),
(687, 687, 119, 0, '2017-10-30 09:41:17', '2017-10-30 09:41:17', 1, 13),
(688, 688, 1100, 0, '2017-03-17 10:09:43', '2017-03-17 10:09:43', 1, 3),
(689, 689, 450, 0, '2017-10-30 11:28:48', '2017-10-30 11:28:49', 1, 13),
(690, 690, 200, 0, '2017-10-30 11:33:49', '2017-10-30 11:33:50', 1, 13),
(691, 691, 500, 0, '2017-10-30 09:57:18', '2017-10-30 11:39:57', 1, 3),
(692, 692, 1800, 0, '2017-10-30 12:05:46', '2017-10-30 12:05:46', 1, 13),
(693, 693, 300, 0, '2017-10-30 15:31:22', '2017-10-30 15:31:22', 1, 3),
(694, 694, 200, 0, '2017-09-29 16:17:46', '2017-10-30 16:17:46', 1, 3),
(695, 695, 700, 0, '2017-10-30 17:53:58', '2017-10-30 17:53:59', 1, 14),
(696, 696, 60, 0, '2017-10-30 18:20:51', '2017-10-30 18:20:51', 1, 13),
(697, 697, 500, 0, '2017-09-03 18:28:48', '2017-10-30 18:28:49', 1, 1),
(698, 698, 60, 0, '2017-10-30 18:34:28', '2017-10-30 18:34:28', 1, 13),
(699, 699, 500, 0, '2017-10-30 18:40:23', '2017-10-30 18:40:24', 1, 14),
(700, 700, 5000, 0, '2017-10-31 10:46:50', '2017-10-31 10:46:49', 1, 3),
(701, 701, 200, 0, '2017-10-31 10:58:40', '2017-10-31 10:58:39', 1, 3),
(702, 702, 500, 0, '2017-10-31 13:41:34', '2017-10-31 13:41:35', 1, 14),
(703, 703, 100, 0, '2017-10-31 15:57:24', '2017-10-31 15:57:23', 1, 3),
(704, 704, 300, 0, '2017-10-31 19:27:27', '2017-10-31 19:27:26', 1, 13),
(705, 705, 100, 0, '2017-11-02 16:36:44', '2017-11-02 16:36:43', 1, 3),
(706, 706, 280, 0, '2017-11-02 16:39:46', '2017-11-02 16:39:46', 1, 3),
(707, 707, 150, 0, '2017-11-03 10:00:58', '2017-11-03 10:00:58', 1, 3),
(708, 708, 650, 0, '2017-11-03 12:56:29', '2017-11-03 12:56:29', 1, 3),
(709, 709, 200, 0, '2017-11-03 16:32:30', '2017-11-03 16:32:30', 1, 3),
(710, 710, 400, 0, '2017-11-03 17:33:05', '2017-11-03 17:33:05', 1, 3),
(711, 711, 2300, 0, '2017-11-03 18:46:26', '2017-11-03 18:46:27', 1, 14),
(712, 712, 260, 0, '2017-11-04 10:07:13', '2017-11-04 10:07:15', 1, 14),
(713, 713, 120, 0, '2017-11-04 19:32:03', '2017-11-04 19:32:02', 1, 13),
(714, 714, 400, 0, '2017-11-06 08:30:34', '2017-11-06 08:30:32', 1, 13),
(715, 715, 1000, 0, '2017-11-06 09:34:34', '2017-11-06 09:34:35', 1, 13),
(716, 716, 350, 0, '2017-11-06 12:32:04', '2017-11-06 12:32:05', 1, 3),
(717, 717, 300, 0, '2017-11-06 14:57:07', '2017-11-06 14:57:08', 1, 3),
(718, 718, 300, 0, '2017-11-07 11:20:30', '2017-11-07 11:20:31', 1, 13),
(719, 719, 300, 0, '2017-11-07 13:49:30', '2017-11-07 13:49:30', 1, 3),
(720, 720, 450, 0, '2017-11-07 18:27:05', '2017-11-07 18:27:06', 1, 3);
INSERT INTO `desembolso` (`idDesembolso`, `idPrestamo`, `desCapital`, `desDebeInteres`, `desFechaContarInteres`, `desFechaRegistro`, `desActivo`, `desUsuario`) VALUES
(721, 721, 550, 0, '2017-11-08 09:19:13', '2017-11-08 09:19:13', 1, 13),
(722, 722, 900, 0, '2017-11-08 10:54:48', '2017-11-08 10:54:48', 1, 3),
(723, 723, 200, 0, '2017-11-08 12:05:35', '2017-11-08 12:05:36', 1, 3),
(724, 724, 1200, 0, '2017-11-08 14:05:52', '2017-11-08 14:05:53', 1, 3),
(725, 725, 250, 0, '2017-11-08 17:12:43', '2017-11-08 17:12:44', 1, 3),
(726, 726, 40, 0, '2017-11-08 18:22:15', '2017-11-08 18:22:16', 1, 3),
(727, 727, 150, 0, '2017-11-09 10:24:44', '2017-11-09 10:24:44', 1, 3),
(728, 728, 70, 0, '2017-11-09 13:33:00', '2017-11-09 13:33:00', 1, 13);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DetalleReporte`
--

CREATE TABLE `DetalleReporte` (
  `idDetalleReporte` int(11) NOT NULL,
  `repoDescripcion` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `DetalleReporte`
--

INSERT INTO `DetalleReporte` (`idDetalleReporte`, `repoDescripcion`) VALUES
(1, 'Se canceló el interés'),
(2, 'Se adelantó una parte interés'),
(3, 'Crédito finalizado'),
(4, 'Aún no hay ninguna confirmación'),
(5, '- Administrador, estado: Recogido'),
(6, '- Adminsitrador, estado: Rematar.'),
(9, 'Cancela capital y retira el producto'),
(7, '- Aceptó el movimiento'),
(8, 'Retiro de artículo'),
(10, 'Amortiza parte capital');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PagoaCuenta`
--

CREATE TABLE `PagoaCuenta` (
  `idPago` int(11) NOT NULL,
  `idDesembolso` int(11) NOT NULL,
  `pagMonto` float NOT NULL,
  `pagInteres` float NOT NULL,
  `pagCantidadPagada` int(11) NOT NULL,
  `pagDebeInteres` float NOT NULL DEFAULT '0',
  `pagFechaRegistro` datetime NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `idTipoPago` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `PagoaCuenta`
--

INSERT INTO `PagoaCuenta` (`idPago`, `idDesembolso`, `pagMonto`, `pagInteres`, `pagCantidadPagada`, `pagDebeInteres`, `pagFechaRegistro`, `idUsuario`, `idTipoPago`) VALUES
(18, 552, 80, 8.21, 88, 0, '2017-09-26 18:13:01', 9, 11),
(17, 549, 30, 3.08, 33, 0, '2017-09-26 18:07:13', 9, 11),
(16, 550, 80, 8.21, 88, 0, '2017-09-26 17:53:02', 9, 10),
(15, 548, 100, 10.26, 110, 0, '2017-09-26 17:47:19', 9, 10),
(14, 555, 100, 10.26, 110, 0, '2017-09-26 17:42:24', 9, 10),
(13, 554, 24, 2.46, 26, 0, '2017-09-26 17:39:06', 9, 10),
(12, 548, 100, 10.26, 110, 0, '2017-09-26 17:12:01', 9, 10),
(11, 547, 150, 15.39, 165, 0, '2017-09-26 17:10:46', 9, 10),
(9, 419, 300, 49.76, 350, 0, '2017-09-23 14:23:55', 12, 10),
(10, 419, 300, 49.76, 350, 0, '2017-09-23 14:23:55', 12, 13),
(19, 552, 80, 8.21, 88, 0, '2017-09-26 18:15:35', 9, 11),
(20, 551, 80, 8.21, 8, 0, '2017-09-26 18:32:54', 9, 10),
(21, 551, 80, 8.21, 8, 0, '2017-09-26 18:32:54', 9, 13),
(22, 556, 80, 8, 8, 0, '2017-09-26 19:25:20', 9, 10),
(23, 556, 80, 8, 8, 0, '2017-09-26 19:25:20', 9, 10),
(24, 557, 100, 10, 10, 0, '2017-09-26 19:39:55', 1, 10),
(25, 557, 100, 10, 10, 0, '2017-09-26 19:39:55', 1, 10),
(26, 547, 150, 15, 165, 0, '2017-09-26 19:42:54', 1, 11),
(27, 554, 24, 2.4, 2, 0, '2017-09-26 20:15:54', 9, 10),
(28, 554, 24, 2.4, 2, 0, '2017-09-26 20:15:54', 9, 10),
(29, 554, 24, 2.4, 2, 0, '2017-09-26 20:16:45', 9, 10),
(30, 554, 24, 2.4, 2, 0, '2017-09-26 20:16:45', 9, 10),
(31, 554, 24, 2.4, 1, 0, '2017-09-26 20:17:34', 9, 10),
(32, 554, 24, 2.4, 1, 0, '2017-09-26 20:17:34', 9, 10),
(33, 554, 24, 2.4, 26, 0, '2017-09-26 20:18:26', 9, 11),
(34, 548, 100, 10, 110, 0, '2017-09-26 20:21:23', 9, 11),
(35, 555, 100, 10, 110, 0, '2017-09-26 20:21:49', 9, 11),
(36, 558, 100, 10, 110, 0, '2017-09-26 20:24:14', 9, 11),
(37, 559, 50, 5, 45, 0, '2017-09-26 20:50:43', 9, 10),
(38, 559, 50, 5, 45, 0, '2017-09-26 20:50:43', 9, 10),
(39, 559, 50, 5, 10, 0, '2017-09-26 20:52:26', 9, 10),
(40, 559, 50, 5, 10, 0, '2017-09-26 20:52:26', 9, 10),
(41, 559, 50, 5, 5, 0, '2017-09-26 20:56:19', 9, 10),
(42, 559, 50, 5, 40, 0, '2017-09-26 20:56:19', 9, 7),
(43, 560, 150, 15, 15, 0, '2017-09-26 21:00:00', 9, 10),
(44, 560, 150, 15, 15, 0, '2017-09-26 21:00:00', 9, 10),
(45, 560, 150, 15, 15, 0, '2017-09-26 21:02:26', 9, 10),
(46, 560, 150, 15, 15, 0, '2017-09-26 21:02:26', 9, 10),
(47, 560, 150, 15, 15, 0, '2017-09-26 21:06:04', 9, 10),
(48, 560, 150, 15, 15, 0, '2017-09-26 21:06:04', 9, 10),
(49, 560, 150, 15, 15, 0, '2017-09-26 21:06:26', 9, 10),
(50, 560, 150, 15, 15, 0, '2017-09-26 21:06:26', 9, 10),
(51, 397, 1000, 260.4, 260, 0, '2017-09-28 11:35:03', 8, 10),
(52, 397, 1000, 260.4, -1000, 0, '2017-09-28 11:35:03', 8, 7),
(53, 377, 40, 13.24, 13, 0, '2017-09-28 19:56:50', 3, 10),
(54, 377, 40, 13.24, -32, 0, '2017-09-28 19:56:50', 3, 7),
(55, 400, 1000, 260.4, 200, 0, '2017-09-28 20:04:33', 3, 10),
(56, 400, 1000, 260.4, 200, 0, '2017-09-28 20:04:33', 3, 10),
(57, 379, 300, 96.57, 97, 0, '2017-09-29 14:25:30', 3, 10),
(58, 379, 300, 96.57, -163, 0, '2017-09-29 14:25:30', 3, 7),
(59, 372, 450, 157.13, 90, 0, '2017-09-29 18:40:08', 3, 10),
(60, 372, 450, 157.13, 90, 0, '2017-09-29 18:40:08', 3, 10),
(61, 372, 450, 157.13, 10, 0, '2017-09-29 18:41:00', 3, 10),
(62, 372, 450, 157.13, 10, 0, '2017-09-29 18:41:00', 3, 10),
(63, 491, 2000, 214.99, 215, 0, '2017-09-30 13:13:02', 12, 10),
(64, 491, 2000, 214.99, 215, 0, '2017-09-30 13:13:02', 12, 10),
(65, 405, 500, 134.51, 134, 0, '2017-10-02 14:59:40', 3, 10),
(66, 405, 500, 134.51, 134, 0, '2017-10-02 14:59:40', 3, 10),
(67, 507, 1000, 99.98, 100, 0, '2017-10-02 17:17:02', 3, 10),
(68, 507, 1000, 99.98, 0, 0, '2017-10-02 17:17:02', 3, 7),
(69, 560, 150, 15, -90, 0, '2017-10-02 19:05:38', 9, 10),
(70, 560, 150, 15, -90, 0, '2017-10-02 19:05:38', 9, 10),
(71, 372, 450, 178.15, 58, 0, '2017-10-04 12:19:39', 3, 10),
(72, 372, 450, 178.15, 58, 0, '2017-10-04 12:19:39', 3, 10),
(73, 505, 120, 13.81, 14, 0, '2017-10-04 16:15:02', 3, 10),
(74, 505, 120, 13.81, -1, 0, '2017-10-04 16:15:02', 3, 7),
(75, 426, 1500, 365.04, 300, 0, '2017-10-06 11:16:50', 3, 10),
(76, 426, 1500, 365.04, 300, 0, '2017-10-06 11:16:50', 3, 10),
(77, 421, 1500, 390.6, 391, 0, '2017-10-06 12:36:58', 3, 10),
(78, 421, 1500, 390.6, 391, 0, '2017-10-06 12:36:58', 3, 10),
(79, 599, 550, 54.99, 605, 0, '2017-10-06 19:26:41', 8, 11),
(80, 481, 400, 214.18, 94, 0, '2017-10-09 17:26:30', 3, 10),
(81, 481, 400, 214.18, 94, 0, '2017-10-09 17:26:30', 3, 10),
(82, 618, 3000, 299.95, 50, 0, '2017-10-10 14:02:59', 9, 10),
(83, 618, 3000, 299.95, 50, 0, '2017-10-10 14:02:59', 9, 10),
(84, 618, 3000, 299.95, 300, 0, '2017-10-10 14:03:29', 9, 10),
(85, 618, 3000, 299.95, 300, 0, '2017-10-10 14:03:29', 9, 10),
(86, 618, 3000, 299.95, 3300, 0, '2017-10-10 14:03:47', 9, 11),
(87, 447, 300, 73.01, 73, 0, '2017-10-10 16:15:22', 3, 10),
(88, 447, 300, 73.01, 73, 0, '2017-10-10 16:15:22', 3, 10),
(89, 454, 650, 152.7, 60, 0, '2017-10-10 16:27:37', 3, 10),
(90, 454, 650, 152.7, 60, 0, '2017-10-10 16:27:37', 3, 10),
(91, 364, 600, 302.65, 300, 0, '2017-10-11 17:42:06', 3, 10),
(92, 364, 600, 302.65, 300, 0, '2017-10-11 17:42:06', 3, 10),
(93, 352, 250, 62.96, 50, 0, '2017-10-11 18:20:24', 3, 10),
(94, 352, 250, 62.96, 50, 0, '2017-10-11 18:20:24', 3, 10),
(95, 470, 700, 158.58, 140, 0, '2017-10-12 11:42:32', 13, 10),
(96, 470, 700, 158.58, 140, 0, '2017-10-12 11:42:32', 13, 10),
(97, 560, 150, 16.12, 16, 0, '2017-10-12 12:16:21', 9, 10),
(98, 560, 150, 16.12, 16, 0, '2017-10-12 12:16:21', 9, 10),
(99, 342, 40, 15.08, 15, 0, '2017-10-14 12:11:59', 12, 10),
(100, 342, 40, 15.08, 15, 0, '2017-10-14 12:11:59', 12, 10),
(101, 588, 350, 34.99, 385, 0, '2017-10-14 13:59:49', 12, 11),
(102, 514, 1000, 177.46, 1177, 0, '2017-10-14 14:21:05', 12, 11),
(103, 629, 600, 59.99, 660, 0, '2017-10-14 14:21:43', 12, 11),
(104, 367, 60, 30.27, 30, 0, '2017-10-14 14:37:49', 12, 10),
(105, 367, 60, 30.27, -48, 0, '2017-10-14 14:37:49', 12, 7),
(106, 619, 500, 49.99, 50, 0, '2017-10-16 13:09:23', 9, 10),
(107, 619, 500, 49.99, 0, 0, '2017-10-16 13:09:23', 9, 7),
(108, 619, 500, 49.99, 50, 0, '2017-10-16 13:09:49', 9, 10),
(109, 619, 500, 49.99, -20, 0, '2017-10-16 13:09:49', 9, 7),
(110, 619, 500, 49.99, 50, 0, '2017-10-16 13:10:01', 9, 10),
(111, 619, 500, 49.99, -30, 0, '2017-10-16 13:10:01', 9, 7),
(112, 616, 300, 29.99, 30, 0, '2017-10-16 16:31:08', 9, 10),
(113, 616, 300, 29.99, 30, 0, '2017-10-16 16:31:08', 9, 10),
(114, 616, 300, 29.99, 30, 0, '2017-10-16 16:31:31', 9, 10),
(115, 616, 300, 29.99, -50, 0, '2017-10-16 16:31:31', 9, 7),
(116, 616, 300, 29.99, 10, 0, '2017-10-16 16:33:15', 9, 9),
(117, 616, 300, 29.99, 28, 0, '2017-10-16 16:33:33', 9, 9),
(118, 616, 300, 29.99, 30, 0, '2017-10-16 16:33:45', 9, 10),
(119, 616, 300, 29.99, 0, 0, '2017-10-16 16:33:45', 9, 7),
(120, 616, 300, 29.99, 30, 0, '2017-10-16 16:34:12', 9, 10),
(121, 616, 300, 29.99, -220, 0, '2017-10-16 16:34:12', 9, 7),
(122, 616, 300, 29.99, 30, 0, '2017-10-16 16:34:32', 9, 10),
(123, 616, 300, 29.99, -220, 0, '2017-10-16 16:34:32', 9, 7),
(124, 616, 300, 29.99, 30, 0, '2017-10-16 16:34:58', 9, 10),
(125, 616, 300, 29.99, -220, 0, '2017-10-16 16:34:58', 9, 7),
(126, 616, 300, 29.99, 30, 0, '2017-10-16 16:40:50', 9, 10),
(127, 616, 300, 29.99, -50, 0, '2017-10-16 16:40:50', 9, 7),
(128, 640, 300, 29.99, 30, 0, '2017-10-16 16:41:56', 9, 10),
(129, 640, 300, 29.99, -10, 0, '2017-10-16 16:41:56', 9, 7),
(130, 640, 300, 29.99, 30, 0, '2017-10-16 16:50:18', 9, 10),
(131, 640, 300, 29.99, 20, 0, '2017-10-16 16:50:18', 9, 7),
(132, 640, 300, 29.99, 30, 0, '2017-10-16 16:53:00', 9, 10),
(133, 640, 300, 29.99, 20, 0, '2017-10-16 16:53:00', 9, 7),
(134, 560, 150, 20.71, 21, 0, '2017-10-16 16:54:06', 9, 10),
(135, 560, 150, 20.71, 29, 0, '2017-10-16 16:54:06', 9, 7),
(136, 64, 140, 34.07, 34, 0, '2017-10-16 17:03:28', 9, 10),
(137, 64, 140, 34.07, 16, 0, '2017-10-16 17:03:28', 9, 7),
(138, 64, 105.93, 34.07, 34, 0, '2017-10-16 17:48:04', 9, 10),
(139, 64, 105.93, 34.07, 16, 0, '2017-10-16 17:48:04', 9, 7),
(140, 64, 105.93, 34.07, 140, 0, '2017-10-16 17:50:46', 9, 11),
(141, 642, 232.04, 67.96, 68, 0, '2017-10-16 18:06:20', 9, 10),
(142, 642, 232.04, 67.96, 12, 0, '2017-10-16 18:06:20', 9, 7),
(143, 642, 270.01, 29.99, 30, 0, '2017-10-16 18:18:44', 9, 10),
(144, 642, 270.01, 29.99, 20, 0, '2017-10-16 18:18:44', 9, 7),
(145, 642, 241.16, 26.79, 27, 0, '2017-10-16 18:23:03', 9, 10),
(146, 642, 241.16, 26.79, 3, 0, '2017-10-16 18:23:03', 9, 7),
(147, 643, 180, 20, 20, 0, '2017-10-16 18:24:27', 9, 10),
(148, 643, 180, 20, 10, 0, '2017-10-16 18:24:27', 9, 7),
(149, 643, 171, 19, 19, 0, '2017-10-16 18:24:52', 9, 10),
(150, 643, 171, 19, 6, 0, '2017-10-16 18:24:52', 9, 7),
(151, 643, 165.6, 18.4, 10, 0, '2017-10-16 18:33:14', 9, 9),
(152, 643, 165.6, 18.4, 18, 0, '2017-10-16 18:33:26', 9, 10),
(153, 643, 165.6, 18.4, 18, 0, '2017-10-16 18:33:26', 9, 10),
(154, 501, 464.07, 135.93, 131, 0, '2017-10-16 19:24:40', 3, 9),
(155, 519, 483.84, 116.16, 116, 0, '2017-10-17 10:04:08', 13, 10),
(156, 519, 483.84, 116.16, 116, 0, '2017-10-17 10:04:08', 13, 10),
(157, 609, 405.01, 44.99, 495, 0, '2017-10-18 11:06:07', 13, 11),
(158, 651, 1172.66, 327.34, 20, 0, '2017-10-18 18:12:13', 13, 9),
(159, 531, 239.47, 60.53, 60, 0, '2017-10-20 15:26:26', 3, 9),
(160, 505, 90.8, 29.2, 10, 0, '2017-10-20 15:40:15', 3, 9),
(161, 505, 90.8, 29.2, 20, 0, '2017-10-20 15:40:43', 3, 9),
(162, 536, 159.65, 40.35, 40, 0, '2017-10-20 19:02:12', 3, 9),
(163, 578, 42.32, 7.68, 58, 0, '2017-10-21 11:52:31', 12, 11),
(164, 566, 164.51, 35.49, 235, 0, '2017-10-21 18:10:48', 12, 11),
(165, 659, 270.01, 29.99, 10, 0, '2017-10-23 12:49:10', 9, 9),
(166, 405, 267.99, 232.01, 98, 0, '2017-10-23 15:12:46', 3, 9),
(167, 276, 210.4, 189.6, 160, 0, '2017-10-24 11:19:59', 3, 9),
(168, 586, 465.49, 84.51, 85, 0, '2017-10-24 12:12:41', 14, 10),
(169, 586, 465.49, 84.51, 85, 0, '2017-10-24 12:12:41', 14, 10),
(170, 382, 227.03, 272.97, 573, 0, '2017-10-24 15:36:21', 14, 11),
(171, 382, 227.03, 272.97, 273, 0, '2017-10-24 15:44:24', 1, 10),
(172, 382, 227.03, 272.97, 273, 0, '2017-10-24 15:44:24', 1, 10),
(173, 382, 500, 272.97, 273, 0, '2017-10-24 15:46:15', 1, 10),
(174, 382, 500, 272.97, 200, 0, '2017-10-24 15:46:15', 1, 7),
(175, 463, 227.79, 122.21, 50, 0, '2017-10-25 14:23:37', 3, 9),
(176, 238, 116.88, 183.12, 72, 0, '2017-10-26 12:41:56', 3, 9),
(177, 491, 1553.11, 446.89, 447, 0, '2017-10-27 14:26:25', 14, 10),
(178, 491, 1553.11, 446.89, 447, 0, '2017-10-27 14:26:25', 14, 10),
(179, 463, 271.31, 78.69, 79, 0, '2017-10-27 17:12:49', 14, 10),
(180, 463, 271.31, 78.69, 250, 0, '2017-10-27 17:12:49', 14, 7),
(181, 463, 100, 0, 100, 0, '2017-10-27 17:13:18', 14, 11),
(182, 410, 57.53, 42.47, 142, 0, '2017-10-27 17:25:48', 14, 11),
(183, 543, 605.31, 194.69, 194, 0, '2017-10-28 11:20:51', 13, 9),
(184, 538, 109.65, 40.35, 30, 0, '2017-10-28 18:10:39', 13, 9),
(185, 436, 1637.76, 1362.24, 3000, 0, '2017-10-28 18:26:04', 13, 11),
(186, 490, 1319.94, 680.06, 2000, 0, '2017-10-28 18:26:44', 13, 11),
(187, 539, 252.81, 97.19, 97, 0, '2017-10-30 09:56:30', 3, 10),
(188, 539, 252.81, 97.19, 0, 0, '2017-10-30 09:56:30', 3, 7),
(189, 562, 529.65, 170.35, 170, 0, '2017-10-30 10:32:11', 3, 10),
(190, 562, 529.65, 170.35, 170, 0, '2017-10-30 10:32:11', 3, 10),
(191, 563, 226.99, 73.01, 73, 0, '2017-10-30 10:33:10', 3, 10),
(192, 563, 226.99, 73.01, 73, 0, '2017-10-30 10:33:10', 3, 10),
(193, 544, 374.07, 125.93, 126, 0, '2017-10-30 10:41:43', 3, 10),
(194, 544, 374.07, 125.93, 126, 0, '2017-10-30 10:41:43', 3, 10),
(195, 459, 241.64, 158.36, 78, 0, '2017-10-30 10:46:07', 1, 9),
(196, 449, 172.59, 127.41, 79, 0, '2017-10-30 11:11:23', 1, 9),
(197, 346, 23.2, 126.8, 42, 0, '2017-10-30 16:38:37', 1, 9),
(198, 319, 44.3, 25.7, 25, 0, '2017-10-30 16:41:52', 1, 9),
(199, 322, 63.2, 36.8, 36, 0, '2017-10-30 16:55:22', 1, 9),
(200, 305, 134.4, 565.6, 140, 0, '2017-10-30 16:57:24', 1, 9),
(201, 199, 500.8, 999.2, 300, 0, '2017-10-30 17:00:07', 1, 9),
(202, 360, 13.2, 36.8, 12, 0, '2017-10-30 17:01:54', 1, 9),
(203, 322, 63.2, 36.8, 37, 0, '2017-10-30 17:04:12', 14, 10),
(204, 322, 63.2, 36.8, 37, 0, '2017-10-30 17:04:12', 14, 10),
(205, 393, 321.6, 278.4, 60, 0, '2017-10-30 17:17:38', 1, 9),
(206, 383, 268, 232, 50, 0, '2017-10-30 17:23:43', 1, 9),
(207, 675, 315, 35, 385, 0, '2017-10-30 18:31:20', 14, 11),
(208, 499, 160.4, 89.6, 60, 0, '2017-10-31 18:14:40', 3, 9),
(209, 517, 2341.7, 1158.3, 560, 0, '2017-11-02 09:11:05', 14, 9),
(210, 648, 624.8, 75.2, 770, 0, '2017-11-02 17:47:19', 13, 11),
(211, 495, 122.7, 77.3, 77, 0, '2017-11-03 10:25:51', 3, 10),
(212, 495, 122.7, 77.3, 77, 0, '2017-11-03 10:25:51', 3, 10),
(213, 577, 147.9, 52.1, 52, 0, '2017-11-03 10:26:26', 3, 10),
(214, 577, 147.9, 52.1, 52, 0, '2017-11-03 10:26:26', 3, 10),
(215, 528, 33.9, 16.1, 16, 0, '2017-11-03 10:26:54', 3, 10),
(216, 528, 33.9, 16.1, 16, 0, '2017-11-03 10:26:54', 3, 10),
(217, 360, 22.8, 27.2, 15, 0, '2017-11-03 10:29:29', 3, 9),
(218, 585, 53, 17, 87, 0, '2017-11-04 11:21:01', 12, 11),
(219, 524, 110.6, 59.4, 246, 0, '2017-11-04 19:05:23', 13, 11),
(220, 694, 141, 59, 48, 0, '2017-11-06 16:59:04', 13, 9),
(221, 592, 221.9, 78.1, 30, 0, '2017-11-06 18:16:23', 3, 9),
(222, 600, 149.6, 50.4, 30, 0, '2017-11-06 18:16:50', 3, 9),
(223, 426, 1135, 365, 365, 0, '2017-11-07 13:13:10', 3, 10),
(224, 426, 1135, 365, 365, 0, '2017-11-07 13:13:10', 3, 10),
(225, 600, 176.2, 23.8, 20, 0, '2017-11-08 18:12:54', 3, 9),
(226, 592, 246.7, 53.3, 20, 0, '2017-11-08 18:13:27', 3, 9),
(227, 674, 315, 35, 35, 0, '2017-11-08 18:46:18', 3, 10),
(228, 674, 315, 35, 35, 0, '2017-11-08 18:46:18', 3, 10),
(229, 630, 564.5, 135.5, 135, 0, '2017-11-09 09:50:58', 13, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `poder`
--

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
-- Estructura de tabla para la tabla `prestamo`
--

CREATE TABLE `prestamo` (
  `idPrestamo` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `preCapital` float NOT NULL,
  `preFechaInicio` datetime NOT NULL,
  `idSucursal` int(11) NOT NULL,
  `idUsuario` int(11) NOT NULL,
  `presActivo` bit(1) NOT NULL,
  `presObservaciones` text NOT NULL,
  `preIdEstado` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `prestamo`
--

INSERT INTO `prestamo` (`idPrestamo`, `idProducto`, `idCliente`, `preCapital`, `preFechaInicio`, `idSucursal`, `idUsuario`, `presActivo`, `presObservaciones`, `preIdEstado`) VALUES
(1, 1, 1, 50, '2017-02-06 12:29:21', 1, 2, b'0', '', 11),
(2, 2, 2, 200, '2017-02-06 12:42:39', 1, 2, b'0', '', 11),
(3, 3, 3, 26, '2017-02-06 13:00:15', 1, 2, b'0', '', 11),
(4, 4, 4, 252, '2017-02-06 13:04:22', 1, 2, b'0', '', 11),
(5, 5, 5, 60, '2017-02-06 13:08:30', 1, 2, b'0', '', 11),
(6, 6, 6, 500, '2017-02-06 13:26:03', 1, 2, b'0', '', 11),
(7, 7, 7, 350, '2017-02-06 13:30:46', 1, 2, b'0', '', 11),
(8, 8, 8, 48, '2017-02-06 13:36:28', 1, 2, b'0', '', 11),
(9, 9, 9, 400, '2017-02-06 13:45:32', 1, 2, b'0', '', 11),
(10, 10, 10, 51.2, '2017-02-06 13:52:49', 1, 2, b'0', '', 11),
(11, 11, 11, 500, '2017-02-06 13:56:58', 1, 2, b'0', '', 11),
(12, 12, 12, 150, '2017-02-06 14:03:55', 1, 2, b'0', '', 11),
(13, 13, 13, 100, '2017-02-06 14:19:15', 1, 2, b'0', '', 11),
(14, 14, 14, 105, '2017-02-06 14:38:37', 1, 2, b'0', '', 11),
(15, 15, 15, 25.2, '2017-02-07 17:46:08', 1, 2, b'0', '', 11),
(16, 16, 16, 100, '2017-02-07 17:51:17', 1, 2, b'0', '', 11),
(17, 17, 17, 336, '2017-02-07 17:54:08', 1, 2, b'0', '', 11),
(18, 18, 18, 170, '2017-02-07 17:57:25', 1, 2, b'0', '', 11),
(19, 19, 19, 168, '2017-02-08 15:20:29', 1, 2, b'0', '', 11),
(20, 20, 20, 200, '2017-02-09 16:39:09', 1, 2, b'0', '', 11),
(21, 21, 15, 80, '2017-02-09 18:34:00', 1, 2, b'0', '', 11),
(22, 22, 21, 450, '2017-02-09 19:12:54', 1, 2, b'0', '', 11),
(23, 23, 22, 700, '2017-02-09 19:16:56', 1, 2, b'0', '', 11),
(51, 51, 50, 300, '2017-02-10 16:27:41', 1, 2, b'0', '', 11),
(26, 26, 25, 80, '2017-02-09 19:30:32', 1, 2, b'0', '', 11),
(27, 27, 26, 590, '2017-02-09 19:33:28', 1, 2, b'0', '', 11),
(28, 28, 27, 520, '2017-02-09 19:35:55', 1, 2, b'0', '', 11),
(29, 29, 28, 200, '2017-02-09 19:41:13', 1, 2, b'0', '', 11),
(30, 30, 29, 200, '2017-02-09 19:43:04', 1, 2, b'0', '', 11),
(31, 31, 30, 200, '2017-02-09 19:46:47', 1, 2, b'0', '', 11),
(32, 32, 31, 200, '2017-02-09 19:50:07', 1, 2, b'0', '', 11),
(33, 33, 32, 1000, '2017-02-09 19:52:48', 1, 2, b'0', '', 11),
(34, 34, 33, 150, '2017-02-09 19:54:32', 1, 2, b'0', '', 11),
(35, 35, 34, 70, '2017-02-09 19:56:18', 1, 2, b'0', '', 11),
(36, 36, 35, 100, '2017-02-09 19:58:05', 1, 2, b'0', '', 11),
(37, 37, 36, 500, '2017-02-09 19:59:57', 1, 2, b'0', '', 11),
(38, 38, 37, 60, '2017-02-09 20:01:48', 1, 2, b'0', '', 11),
(50, 50, 49, 600, '2017-02-10 16:25:09', 1, 2, b'0', '', 11),
(40, 40, 39, 350, '2017-02-09 20:05:36', 1, 2, b'0', '', 11),
(41, 41, 40, 80, '2017-02-09 20:07:48', 1, 2, b'0', '', 11),
(42, 42, 41, 68, '2017-02-09 20:09:44', 1, 2, b'0', '', 11),
(43, 43, 42, 550, '2017-02-09 20:11:52', 1, 2, b'0', '', 11),
(44, 44, 43, 400, '2017-02-09 20:13:30', 1, 2, b'0', '', 11),
(45, 45, 44, 180, '2017-02-09 20:15:14', 1, 2, b'0', '', 11),
(46, 46, 45, 60, '2017-02-09 20:17:13', 1, 2, b'0', '', 11),
(47, 47, 46, 800, '2017-02-09 20:19:06', 1, 2, b'0', '', 11),
(48, 48, 47, 500, '2017-02-09 20:20:40', 1, 2, b'0', '', 11),
(49, 49, 48, 500, '2017-02-10 12:33:53', 1, 2, b'0', '', 11),
(52, 52, 51, 300, '2017-02-10 16:47:31', 1, 2, b'0', '', 11),
(53, 53, 52, 60, '2017-02-10 18:18:09', 1, 2, b'0', '', 11),
(54, 54, 53, 80, '2017-02-11 12:10:09', 1, 2, b'0', '', 11),
(55, 55, 54, 19, '2017-02-13 12:43:31', 2, 9, b'0', '', 11),
(56, 56, 54, 160, '2017-02-13 12:44:43', 2, 9, b'0', '', 11),
(57, 57, 55, 50, '2017-02-13 15:32:14', 1, 2, b'0', '', 11),
(58, 58, 56, 700, '2017-02-13 16:03:28', 1, 2, b'0', '', 11),
(59, 59, 57, 170, '2017-02-13 16:34:48', 1, 2, b'0', '', 11),
(60, 60, 58, 300, '2017-02-13 17:27:00', 1, 2, b'0', '', 11),
(61, 61, 59, 300, '2017-02-13 17:49:57', 1, 2, b'0', '', 11),
(62, 62, 57, 107, '2017-02-13 19:00:44', 1, 2, b'0', '', 11),
(63, 63, 60, 100, '2017-02-14 11:06:32', 1, 2, b'0', '', 11),
(64, 64, 54, 140, '2017-09-14 14:05:50', 2, 9, b'0', '', 11),
(65, 65, 61, 100, '2017-02-14 14:31:23', 3, 10, b'1', '', 1),
(66, 66, 62, 80, '2017-02-15 13:30:53', 1, 2, b'0', '', 11),
(67, 67, 63, 40, '2017-02-16 13:58:46', 1, 2, b'0', '', 11),
(68, 68, 64, 200, '2017-02-20 10:27:08', 1, 2, b'0', '', 11),
(69, 69, 65, 100, '2017-02-20 12:20:53', 1, 2, b'0', '', 11),
(70, 70, 66, 101, '2017-02-20 12:57:52', 1, 2, b'1', '', 1),
(71, 71, 67, 100, '2017-02-20 14:08:25', 1, 2, b'0', '', 11),
(72, 72, 68, 500, '2017-02-21 09:52:04', 1, 2, b'0', '', 11),
(73, 73, 69, 100, '2017-02-22 16:50:08', 1, 2, b'0', '', 11),
(74, 74, 4, 1000, '2017-02-23 19:01:40', 1, 2, b'0', '', 11),
(75, 75, 70, 300, '2017-02-24 09:49:29', 1, 2, b'0', '', 11),
(76, 76, 71, 160, '2017-02-24 18:07:38', 1, 2, b'0', '', 11),
(77, 77, 72, 2000, '2017-02-24 19:26:10', 1, 2, b'0', '', 11),
(78, 78, 73, 2000, '2017-02-24 19:26:11', 1, 2, b'0', '', 11),
(79, 79, 65, 400, '2017-02-28 12:02:23', 1, 2, b'0', '', 11),
(80, 80, 74, 150, '2017-02-28 13:56:43', 1, 2, b'0', '', 11),
(81, 81, 75, 600, '2017-03-01 13:09:24', 1, 2, b'0', '', 11),
(82, 82, 76, 1500, '2017-03-02 17:55:57', 1, 2, b'0', '', 11),
(83, 83, 77, 300, '2017-03-04 12:37:50', 1, 2, b'0', '', 11),
(84, 84, 78, 300, '2017-03-04 13:31:52', 1, 2, b'0', '', 11),
(85, 85, 79, 400, '2017-03-06 11:56:47', 1, 2, b'0', '', 11),
(86, 86, 80, 1200, '2017-03-07 15:37:02', 1, 2, b'1', '', 1),
(87, 87, 81, 756, '2017-03-09 12:46:02', 1, 2, b'0', '', 11),
(88, 88, 82, 30, '2017-03-09 17:25:37', 1, 2, b'0', '', 11),
(89, 89, 83, 850, '2017-03-10 11:51:58', 1, 2, b'0', '', 11),
(90, 90, 84, 50, '2017-03-10 14:36:47', 1, 2, b'0', '', 11),
(91, 91, 85, 300, '2017-03-10 15:03:28', 1, 2, b'0', '', 11),
(92, 92, 86, 150, '2017-03-11 10:06:40', 1, 2, b'0', '', 11),
(93, 93, 87, 140, '2017-03-11 11:06:00', 1, 2, b'0', '', 11),
(94, 94, 88, 350, '2017-03-13 20:37:08', 1, 2, b'0', '', 11),
(95, 95, 89, 230, '2017-03-14 14:05:24', 1, 2, b'0', '', 11),
(96, 96, 90, 1100, '2017-03-17 13:13:35', 1, 2, b'0', '', 11),
(97, 97, 91, 500, '2017-03-17 14:00:39', 1, 2, b'0', '', 11),
(98, 98, 92, 400, '2017-03-18 13:03:20', 1, 2, b'0', '', 11),
(99, 99, 3, 150, '2017-03-20 15:47:54', 1, 2, b'0', '', 11),
(100, 100, 93, 1000, '2017-03-20 20:19:35', 1, 2, b'0', '', 11),
(101, 101, 94, 350, '2017-03-21 14:24:41', 1, 2, b'0', '', 11),
(102, 102, 95, 200, '2017-03-22 12:26:06', 1, 2, b'0', '', 11),
(103, 103, 96, 200, '2017-03-23 16:48:58', 1, 2, b'0', '', 11),
(104, 104, 97, 280, '2017-03-24 18:18:45', 1, 2, b'0', '', 11),
(105, 105, 75, 300, '2017-03-24 19:36:17', 1, 2, b'0', '', 11),
(106, 106, 98, 1200, '2017-03-25 16:09:31', 1, 2, b'0', '', 11),
(107, 107, 99, 130, '2017-03-28 11:39:31', 1, 2, b'0', '', 11),
(108, 108, 100, 200, '2017-03-28 13:39:52', 1, 2, b'0', '', 11),
(109, 109, 101, 70, '2017-03-28 17:17:27', 1, 2, b'0', '', 11),
(110, 110, 102, 2500, '2017-03-29 12:58:02', 1, 2, b'0', '', 11),
(111, 111, 103, 800, '2017-03-31 16:59:12', 1, 2, b'0', '', 11),
(112, 112, 104, 500, '2017-03-31 17:15:52', 1, 2, b'0', '', 11),
(113, 113, 105, 300, '2017-03-31 17:29:07', 1, 2, b'0', '', 11),
(114, 114, 106, 250, '2017-03-31 19:43:38', 1, 2, b'0', '', 11),
(115, 115, 70, 300, '2017-04-01 09:20:44', 1, 2, b'0', '', 11),
(116, 116, 107, 300, '2017-04-01 15:56:38', 1, 2, b'0', '', 11),
(117, 117, 107, 300, '2017-04-01 15:59:14', 1, 2, b'0', '', 11),
(118, 118, 108, 500, '2017-04-03 18:56:12', 1, 2, b'0', '', 11),
(119, 119, 102, 2500, '2017-04-04 17:20:33', 1, 2, b'0', '', 11),
(120, 120, 109, 600, '2017-04-05 13:02:52', 1, 2, b'0', '', 11),
(121, 121, 54, 80, '2017-04-05 16:57:12', 2, 9, b'0', '', 11),
(122, 122, 110, -666, '2017-04-07 10:14:24', 1, 2, b'0', '', 11),
(123, 123, 111, 70, '2017-04-07 12:12:28', 1, 2, b'0', '', 11),
(124, 124, 112, 250, '2017-04-07 12:38:52', 1, 2, b'0', '', 11),
(125, 125, 113, 350, '2017-04-09 14:03:07', 1, 2, b'0', '', 11),
(126, 126, 114, 150, '2017-04-10 17:44:43', 1, 2, b'0', '', 11),
(127, 127, 115, 64, '2017-04-10 18:37:40', 1, 2, b'0', '', 11),
(128, 128, 116, 0, '2017-04-11 10:27:05', 1, 2, b'0', '', 11),
(129, 129, 69, 200, '2017-04-11 10:50:20', 1, 2, b'0', '', 11),
(130, 130, 117, 40, '2017-04-11 11:07:14', 1, 2, b'0', '', 11),
(131, 131, 118, 1500, '2017-04-11 14:57:54', 1, 2, b'0', '', 11),
(132, 132, 119, 300, '2017-04-11 17:25:14', 1, 2, b'0', '', 11),
(133, 133, 120, 200, '2017-04-11 18:27:59', 1, 2, b'0', '', 11),
(134, 134, 121, 200, '2017-04-12 11:53:44', 1, 2, b'0', '', 11),
(135, 135, 122, 150, '2017-04-12 12:02:53', 1, 2, b'0', '', 11),
(136, 136, 123, 200, '2017-04-12 12:24:15', 1, 2, b'0', '', 11),
(137, 137, 124, 300, '2017-04-12 16:28:45', 1, 2, b'0', '', 11),
(138, 138, 125, 150, '2017-04-12 17:35:50', 1, 2, b'0', '', 11),
(139, 139, 126, 350, '2017-04-13 10:58:40', 1, 2, b'0', '', 11),
(140, 140, 54, 90, '2017-04-17 11:20:34', 2, 9, b'0', '', 11),
(141, 141, 54, 300, '2017-04-17 12:09:16', 2, 9, b'0', '', 11),
(142, 142, 127, 200, '2017-04-17 12:33:11', 2, 9, b'0', '', 11),
(143, 143, 128, 150, '2017-04-17 12:44:39', 1, 2, b'0', '', 11),
(144, 144, 129, 70, '2017-04-17 13:10:48', 1, 2, b'0', '', 11),
(145, 145, 130, 400, '2017-04-17 18:52:03', 1, 2, b'0', '', 11),
(146, 146, 95, 200, '2017-04-18 15:50:25', 1, 2, b'0', '', 11),
(147, 147, 131, 300, '2017-04-19 16:54:45', 1, 2, b'0', '', 11),
(148, 148, 132, 350, '2017-04-20 11:26:55', 1, 2, b'0', '', 11),
(149, 149, 133, 300, '2017-04-20 16:37:59', 1, 2, b'0', '', 11),
(150, 150, 134, 300, '2017-04-20 18:29:19', 1, 2, b'0', '', 11),
(151, 151, 116, 70, '2017-04-21 09:57:44', 1, 2, b'0', '', 11),
(152, 152, 109, 300, '2017-04-21 17:42:29', 1, 2, b'0', '', 11),
(153, 153, 135, 1700, '2017-04-22 12:33:52', 1, 2, b'0', '', 11),
(154, 154, 136, 150, '2017-04-24 14:03:59', 1, 2, b'0', '', 11),
(155, 155, 137, 200, '2017-04-26 16:42:52', 1, 2, b'0', '', 11),
(156, 156, 138, 300, '2017-04-27 09:54:04', 1, 2, b'0', '', 11),
(157, 157, 139, 240, '2017-04-27 10:01:44', 1, 2, b'0', '', 11),
(158, 158, 140, 50, '2017-04-27 12:06:04', 1, 2, b'0', '', 11),
(159, 159, 141, 90, '2017-04-27 17:09:21', 1, 2, b'0', '', 11),
(160, 160, 102, 4000, '2017-04-28 13:30:45', 1, 2, b'0', '', 11),
(161, 161, 142, 300, '2017-04-28 14:50:03', 1, 2, b'0', '', 11),
(162, 162, 143, 100, '2017-04-28 18:55:43', 1, 2, b'0', '', 11),
(163, 163, 144, 200, '2017-04-29 12:24:16', 1, 2, b'0', '', 11),
(164, 164, 70, 250, '2017-05-02 09:20:19', 1, 2, b'0', '', 11),
(165, 165, 145, -20, '2017-05-03 14:26:13', 1, 2, b'0', '', 11),
(166, 166, 54, 50, '2017-05-04 13:43:19', 2, 9, b'0', '', 11),
(167, 167, 54, 15, '2017-05-04 13:44:50', 2, 9, b'0', '', 11),
(168, 168, 146, 300, '2017-05-08 11:24:29', 1, 2, b'0', '', 11),
(169, 169, 147, 800, '2017-05-08 14:43:09', 1, 2, b'1', '', 1),
(170, 170, 148, 500, '2017-05-09 18:53:24', 1, 2, b'0', '', 11),
(171, 171, 149, 500, '2017-05-10 17:17:11', 1, 2, b'0', '', 11),
(172, 172, 54, 15, '2017-05-11 11:17:08', 2, 9, b'0', '', 11),
(173, 173, 150, 400, '2017-05-11 13:37:13', 1, 2, b'0', '', 11),
(174, 174, 151, 400, '2017-05-11 15:09:36', 1, 2, b'0', '', 11),
(175, 175, 54, 130, '2017-05-11 17:54:02', 2, 9, b'0', '', 11),
(176, 176, 54, 150, '2017-05-11 18:38:01', 2, 9, b'0', '', 11),
(177, 177, 102, 4500, '2017-05-12 14:55:11', 1, 2, b'0', '', 11),
(178, 178, 106, 170, '2017-05-12 16:58:56', 1, 2, b'0', '', 11),
(179, 179, 152, 250, '2017-05-12 17:32:41', 1, 2, b'0', '', 11),
(180, 180, 153, 100, '2017-05-12 18:10:03', 1, 2, b'0', '', 11),
(181, 181, 154, 350, '2017-05-13 12:56:26', 1, 2, b'0', '', 11),
(182, 182, 155, 300, '2017-05-15 09:44:20', 1, 2, b'0', '', 11),
(183, 183, 156, 400, '2017-05-15 12:25:44', 1, 2, b'0', '', 11),
(184, 184, 157, 300, '2017-05-15 17:26:59', 1, 2, b'0', '', 11),
(185, 185, 158, 200, '2017-05-16 14:44:46', 1, 3, b'0', '', 11),
(186, 186, 159, 60, '2017-05-17 18:28:56', 1, 3, b'0', '', 11),
(187, 187, 160, 600, '2017-05-18 15:35:53', 1, 2, b'0', '', 11),
(188, 188, 161, 200, '2017-05-18 16:05:05', 1, 2, b'0', '', 11),
(189, 189, 59, 250, '2017-05-19 11:30:52', 1, 3, b'0', '', 11),
(190, 190, 156, 300, '2017-05-19 12:49:21', 1, 3, b'0', '', 11),
(191, 191, 162, 300, '2017-05-19 14:15:00', 1, 3, b'0', '', 11),
(192, 192, 163, 2500, '2017-05-19 15:40:26', 1, 3, b'0', '', 11),
(193, 193, 164, 350, '2017-05-19 16:30:14', 1, 3, b'0', '', 11),
(194, 194, 91, 300, '2017-05-19 17:08:42', 1, 3, b'0', '', 11),
(195, 195, 165, 150, '2017-05-20 10:17:37', 1, 3, b'0', '', 11),
(196, 196, 166, 70, '2017-05-20 13:59:32', 1, 3, b'0', '', 11),
(197, 197, 143, 300, '2017-05-22 12:16:32', 1, 3, b'0', '', 11),
(198, 198, 102, 6000, '2017-05-22 16:39:56', 1, 3, b'0', '', 11),
(199, 199, 167, 1500, '2017-05-22 18:13:17', 1, 3, b'1', '', 1),
(200, 200, 168, 50, '2017-05-22 19:07:09', 1, 3, b'0', '', 11),
(201, 201, 169, 150, '2017-05-23 18:09:44', 1, 3, b'0', '', 11),
(202, 202, 170, 100, '2017-05-24 14:50:47', 1, 3, b'0', '', 11),
(203, 203, 171, 200, '2017-05-24 15:04:50', 1, 3, b'0', '', 11),
(204, 204, 149, 200, '2017-05-25 16:47:11', 1, 3, b'0', '', 11),
(205, 205, 172, 400, '2017-05-25 17:27:36', 1, 3, b'0', '', 11),
(206, 206, 173, 100, '2017-05-25 18:59:01', 1, 3, b'0', '', 11),
(207, 207, 156, 450, '2017-05-26 09:33:03', 1, 3, b'0', '', 11),
(208, 208, 174, 500, '2017-05-26 10:29:05', 1, 3, b'0', '', 11),
(209, 209, 175, 50, '2017-05-26 15:40:53', 1, 3, b'0', '', 11),
(210, 210, 54, 500, '2017-05-27 09:15:30', 2, 9, b'0', '', 11),
(211, 211, 176, 1000, '2017-05-29 11:56:44', 1, 3, b'0', '', 11),
(212, 212, 102, 6000, '2017-05-29 18:21:35', 1, 3, b'0', '', 11),
(213, 213, 177, 100, '2017-05-30 09:26:58', 1, 3, b'0', '', 11),
(214, 214, 178, 200, '2017-05-30 15:41:29', 1, 3, b'0', '', 11),
(215, 215, 179, 200, '2017-05-30 17:18:04', 1, 3, b'0', '', 11),
(216, 216, 106, 250, '2017-05-30 19:15:08', 1, 3, b'0', '', 11),
(217, 217, 180, 200, '2017-06-02 10:18:34', 1, 3, b'0', '', 11),
(218, 218, 68, 300, '2017-06-02 13:09:13', 1, 3, b'0', '', 11),
(219, 219, 181, 150, '2017-06-02 17:54:37', 1, 3, b'0', '', 11),
(220, 220, 182, 200, '2017-06-02 18:11:22', 1, 3, b'0', '', 11),
(221, 221, 183, 180, '2017-06-03 11:49:01', 1, 12, b'0', '', 11),
(222, 222, 104, 350, '2017-06-03 12:16:19', 1, 12, b'0', '', 11),
(223, 223, 184, 400, '2017-06-05 10:50:27', 1, 3, b'0', '', 11),
(224, 224, 185, 200, '2017-06-05 11:50:28', 1, 3, b'0', '', 11),
(225, 225, 186, 120, '2017-06-05 12:17:00', 1, 3, b'0', '', 11),
(226, 226, 187, 350, '2017-06-05 15:11:28', 1, 3, b'0', '', 11),
(227, 227, 188, 70, '2017-06-05 17:22:02', 1, 3, b'0', '', 11),
(228, 228, 189, 50, '2017-06-05 18:31:09', 1, 3, b'0', '', 11),
(229, 229, 190, 3200, '2017-06-05 18:47:18', 1, 3, b'0', '', 11),
(230, 230, 112, 150, '2017-06-06 14:24:55', 1, 3, b'0', '', 11),
(231, 231, 191, 300, '2017-06-06 16:05:21', 1, 3, b'0', '', 11),
(232, 232, 156, 450, '2017-06-07 18:04:01', 1, 3, b'0', '', 11),
(233, 233, 102, 8000, '2017-06-07 18:47:14', 1, 3, b'0', '', 11),
(234, 234, 102, 450, '2017-06-07 18:56:53', 1, 3, b'0', '', 11),
(235, 235, 192, 450, '2017-06-08 10:45:43', 1, 3, b'0', '', 11),
(236, 236, 192, 300, '2017-06-08 11:20:13', 1, 3, b'0', '', 11),
(237, 237, 193, 100, '2017-06-08 13:11:20', 1, 3, b'0', '', 11),
(238, 238, 194, 300, '2017-06-08 17:34:34', 1, 3, b'0', '', 11),
(239, 239, 195, 40, '2017-06-09 14:01:01', 1, 12, b'0', '', 11),
(240, 240, 196, 800, '2017-06-09 14:38:29', 1, 12, b'0', '', 11),
(241, 241, 197, 60, '2017-06-12 09:37:51', 1, 3, b'0', '', 11),
(242, 242, 188, 100, '2017-06-12 09:39:53', 1, 3, b'0', '', 11),
(243, 243, 198, 400, '2017-06-12 09:51:42', 1, 3, b'0', '', 11),
(244, 244, 197, 80, '2017-06-12 10:00:16', 1, 3, b'0', '', 11),
(245, 245, 190, 4100, '2017-06-12 18:52:55', 1, 3, b'0', '', 11),
(246, 246, 199, 170, '2017-06-13 10:43:05', 1, 3, b'0', '', 11),
(247, 247, 200, 150, '2017-06-13 11:02:18', 1, 3, b'0', '', 11),
(248, 248, 201, 50, '2017-06-13 16:25:50', 1, 3, b'0', '', 11),
(249, 249, 156, 450, '2017-06-13 18:31:21', 1, 3, b'0', '', 11),
(251, 251, 202, 4000, '2017-06-14 11:37:24', 1, 3, b'0', '', 11),
(252, 252, 195, 50, '2017-06-14 17:06:36', 1, 3, b'0', '', 11),
(253, 253, 106, 600, '2017-06-15 09:44:33', 1, 3, b'0', '', 11),
(254, 254, 203, 150, '2017-06-15 11:27:05', 1, 3, b'0', '', 11),
(255, 255, 204, 600, '2017-06-15 12:41:47', 1, 3, b'0', '', 11),
(256, 256, 205, 228, '2017-06-15 12:50:49', 1, 3, b'0', '', 11),
(257, 257, 206, 330, '2017-06-15 17:08:38', 1, 13, b'0', '', 11),
(258, 258, 207, 400, '2017-06-16 09:52:43', 1, 3, b'0', '', 11),
(259, 259, 146, 300, '2017-06-16 10:42:30', 1, 3, b'0', '', 11),
(260, 260, 48, 400, '2017-06-16 11:06:37', 1, 3, b'0', '', 11),
(261, 261, 208, 150, '2017-06-16 11:24:23', 1, 3, b'0', '', 11),
(262, 262, 209, 500, '2017-06-16 17:14:49', 1, 3, b'0', '', 11),
(263, 263, 210, 400, '2017-06-19 19:31:54', 1, 3, b'0', '', 11),
(264, 264, 211, 350, '2017-06-19 20:18:15', 1, 13, b'0', '', 11),
(265, 265, 156, 450, '2017-06-20 10:26:49', 1, 3, b'0', '', 11),
(266, 266, 212, 30, '2017-06-21 10:55:53', 1, 3, b'0', '', 11),
(267, 267, 54, 150, '2017-06-21 14:06:52', 2, 9, b'0', '', 11),
(268, 268, 54, 136, '2017-06-21 14:29:41', 2, 9, b'0', '', 11),
(269, 269, 54, 55, '2017-06-21 14:42:05', 2, 9, b'0', '', 11),
(270, 270, 54, 20, '2017-06-21 16:55:04', 2, 9, b'0', '', 11),
(271, 271, 54, 15, '2017-06-21 17:48:24', 2, 9, b'0', '', 11),
(272, 272, 213, 400, '2017-06-22 10:27:58', 1, 3, b'0', '', 11),
(273, 273, 214, 800, '2017-06-22 13:36:39', 1, 3, b'0', '', 11),
(274, 274, 187, 400, '2017-06-23 15:19:30', 1, 3, b'0', '', 11),
(275, 275, 215, 90, '2017-06-23 16:23:03', 1, 3, b'0', '', 11),
(276, 276, 216, 400, '2017-06-23 16:44:51', 1, 3, b'1', '', 11),
(277, 277, 182, 80, '2017-06-23 18:35:02', 1, 3, b'0', '', 11),
(278, 278, 202, 500, '2017-06-23 19:26:31', 1, 3, b'0', '', 11),
(279, 279, 217, 200, '2017-06-24 09:58:09', 1, 13, b'0', '', 11),
(280, 280, 218, 600, '2017-06-24 10:01:19', 1, 13, b'0', '', 11),
(281, 281, 219, 200, '2017-06-24 11:47:31', 1, 12, b'0', '', 11),
(282, 282, 156, 300, '2017-06-26 09:51:12', 1, 3, b'0', '', 11),
(283, 283, 188, 100, '2017-06-26 17:40:20', 1, 3, b'0', '', 11),
(284, 284, 170, 150, '2017-06-26 20:08:10', 1, 13, b'0', '', 11),
(285, 285, 220, 70, '2017-06-26 20:10:39', 1, 13, b'0', '', 11),
(286, 286, 221, 10000, '2017-06-27 13:25:31', 1, 3, b'0', '', 11),
(287, 287, 209, 100, '2017-06-28 16:09:58', 1, 3, b'0', '', 11),
(288, 288, 204, 600, '2017-06-28 17:44:34', 1, 3, b'0', '', 11),
(289, 289, 222, 250, '2017-06-30 15:48:40', 1, 13, b'0', '', 11),
(290, 290, 223, 210, '2017-06-30 15:50:59', 1, 13, b'0', '', 11),
(291, 291, 224, 250, '2017-06-30 17:31:11', 1, 3, b'0', '', 11),
(292, 292, 225, 1300, '2017-06-30 18:18:37', 1, 3, b'0', '', 11),
(293, 293, 226, 2000, '2017-06-30 19:37:53', 1, 3, b'0', '', 11),
(294, 294, 227, 250, '2017-07-01 13:39:02', 1, 12, b'0', '', 11),
(295, 295, 228, 220, '2017-07-01 14:23:38', 1, 12, b'0', '', 11),
(296, 296, 229, 350, '2017-07-03 10:57:34', 1, 3, b'0', '', 11),
(297, 297, 230, 150, '2017-07-03 14:41:54', 1, 3, b'0', '', 11),
(298, 298, 102, 600, '2017-07-03 16:40:03', 1, 3, b'0', '', 11),
(299, 299, 54, 443, '2017-07-03 19:18:29', 2, 9, b'0', '', 11),
(300, 300, 156, 750, '2017-07-04 09:58:39', 1, 3, b'0', '', 11),
(301, 301, 231, 200, '2017-07-04 11:54:25', 1, 3, b'0', '', 11),
(302, 302, 232, 60, '2017-07-04 12:20:12', 1, 13, b'0', '', 11),
(303, 303, 233, 1300, '2017-07-04 12:23:30', 1, 13, b'0', '', 11),
(304, 304, 234, 150, '2017-07-04 18:12:36', 1, 3, b'0', '', 11),
(305, 305, 235, 700, '2017-07-05 17:43:32', 1, 3, b'1', '', 1),
(306, 306, 236, 200, '2017-07-05 21:09:13', 1, 13, b'0', '', 11),
(307, 307, 237, 230, '2017-07-05 21:11:58', 1, 13, b'0', '', 11),
(308, 308, 54, 500, '2017-07-07 12:09:14', 2, 9, b'0', '', 11),
(309, 309, 238, 104.4, '2017-07-07 14:07:54', 1, 14, b'0', '', 11),
(310, 310, 121, 280, '2017-07-08 12:33:01', 1, 12, b'0', '', 11),
(311, 311, 156, 450, '2017-07-10 10:39:28', 1, 3, b'0', '', 11),
(312, 312, 239, 40, '2017-07-10 10:49:27', 1, 3, b'0', '', 11),
(313, 313, 240, 1000, '2017-07-11 11:24:29', 3, 8, b'0', '', 11),
(314, 314, 241, 80, '2017-07-12 14:39:33', 1, 3, b'0', '', 11),
(315, 315, 191, 500, '2017-07-12 16:32:33', 1, 3, b'0', '', 11),
(316, 316, 54, 150, '2017-07-12 17:05:02', 2, 9, b'0', '', 11),
(317, 317, 54, 120, '2017-07-12 18:45:48', 2, 9, b'0', '', 11),
(318, 318, 206, 400, '2017-07-14 15:04:12', 1, 3, b'0', '', 11),
(319, 319, 242, 70, '2017-07-15 11:28:41', 1, 12, b'1', '', 1),
(320, 320, 213, 500, '2017-07-17 11:58:58', 1, 13, b'0', '', 11),
(321, 321, 188, 200, '2017-07-17 20:10:07', 1, 3, b'0', '', 11),
(322, 322, 242, 100, '2017-07-18 19:35:23', 1, 3, b'1', '', 1),
(323, 323, 47, 300, '2017-07-19 10:35:59', 1, 3, b'0', '', 11),
(324, 324, 243, 200, '2017-07-19 17:52:26', 1, 3, b'0', '', 11),
(325, 325, 244, 40, '2017-07-19 18:09:41', 1, 3, b'0', '', 11),
(326, 326, 245, 70, '2017-07-20 18:27:51', 1, 3, b'0', '', 11),
(327, 327, 246, 400, '2017-07-20 18:51:17', 1, 3, b'0', '', 11),
(328, 328, 247, 130, '2017-07-21 13:30:52', 1, 3, b'0', '', 11),
(329, 329, 248, 120, '2017-07-22 12:48:51', 1, 12, b'0', '', 11),
(330, 330, 54, 200, '2017-07-22 13:51:53', 2, 9, b'0', '', 11),
(331, 331, 59, 80, '2017-07-25 10:55:41', 1, 3, b'0', '', 11),
(332, 332, 249, 150, '2017-07-25 12:38:31', 1, 3, b'0', '', 11),
(333, 333, 250, 150, '2017-07-28 11:00:37', 1, 3, b'0', '', 11),
(334, 334, 251, 200, '2017-07-28 11:28:13', 1, 3, b'0', '', 11),
(335, 335, 252, 250, '2017-07-28 16:03:03', 1, 3, b'0', '', 11),
(336, 336, 227, 250, '2017-07-28 18:09:42', 1, 3, b'0', '', 11),
(337, 337, 253, 50, '2017-07-29 11:03:31', 1, 13, b'1', '', 11),
(338, 338, 254, 380, '2017-07-29 11:04:44', 1, 13, b'0', '', 11),
(339, 339, 255, 180, '2017-07-29 11:09:32', 1, 13, b'0', '', 11),
(340, 340, 256, 1000, '2017-07-29 11:12:43', 1, 13, b'0', '', 11),
(341, 341, 257, 200, '2017-07-29 11:16:28', 1, 13, b'0', '', 11),
(342, 342, 15, 40, '2017-07-29 11:18:55', 1, 13, b'1', '', 1),
(343, 343, 16, 80, '2017-07-29 11:26:20', 1, 13, b'0', '', 11),
(344, 344, 250, 300, '2017-07-29 11:33:15', 1, 13, b'0', '', 11),
(345, 345, 258, 15000, '2017-07-31 14:05:52', 1, 3, b'0', '', 11),
(346, 346, 259, 150, '2017-08-01 17:45:39', 1, 3, b'1', '', 1),
(347, 347, 251, 150, '2017-08-02 12:21:30', 1, 3, b'0', '', 11),
(348, 348, 260, 150, '2017-08-02 17:02:07', 1, 3, b'0', '', 11),
(349, 349, 261, 170, '2017-08-02 19:14:08', 1, 3, b'0', '', 11),
(350, 350, 262, 400, '2017-08-03 16:38:57', 1, 3, b'0', '', 11),
(351, 351, 118, 2000, '2017-08-03 18:08:52', 1, 3, b'0', '', 11),
(352, 352, 224, 250, '2017-08-07 17:40:34', 1, 3, b'0', '', 11),
(353, 353, 263, 1200, '2017-08-07 17:53:56', 1, 3, b'0', '', 11),
(354, 354, 250, 300, '2017-08-07 18:31:49', 1, 3, b'0', '', 11),
(355, 355, 264, 500, '2017-08-08 15:05:42', 1, 3, b'0', '', 11),
(356, 356, 227, 250, '2017-08-08 15:18:49', 1, 3, b'0', '', 11),
(357, 357, 265, 8000, '2017-08-08 15:29:28', 1, 3, b'0', '', 11),
(358, 358, 266, 250, '2017-08-08 17:45:03', 1, 3, b'0', '', 11),
(359, 359, 119, 250, '2017-08-09 16:26:11', 1, 3, b'0', '', 11),
(360, 360, 156, 50, '2017-08-10 10:36:23', 1, 3, b'1', '', 1),
(361, 361, 267, 5000, '2017-08-11 12:06:58', 1, 3, b'0', '', 11),
(362, 362, 198, 400, '2017-08-14 15:55:20', 1, 3, b'0', '', 11),
(363, 363, 268, 1000, '2017-08-14 18:22:36', 1, 13, b'0', '', 11),
(364, 364, 115, 600, '2017-08-14 18:24:19', 1, 13, b'0', '', 11),
(365, 365, 250, 200, '2017-08-15 10:13:33', 1, 3, b'0', '', 11),
(366, 366, 269, 80, '2017-08-15 10:52:12', 1, 3, b'0', '', 11),
(367, 367, 270, 60, '2017-08-15 11:59:05', 1, 3, b'0', '', 11),
(368, 368, 265, 2000, '2017-08-15 12:18:41', 1, 3, b'0', '', 11),
(369, 369, 271, 5000, '2017-08-15 16:11:33', 1, 3, b'0', '', 11),
(370, 370, 272, 250, '2017-08-16 10:13:16', 1, 3, b'0', '', 11),
(371, 371, 273, 1000, '2017-08-16 10:36:17', 1, 3, b'1', '', 1),
(372, 372, 274, 450, '2017-08-16 18:42:56', 1, 3, b'1', '', 1),
(373, 373, 69, 100, '2017-08-17 10:20:23', 1, 3, b'0', '', 11),
(374, 374, 275, 50, '2017-08-17 11:17:23', 1, 3, b'0', '', 11),
(375, 375, 276, 150, '2017-08-17 12:25:59', 1, 3, b'0', '', 11),
(376, 376, 277, 250, '2017-08-17 15:50:03', 1, 3, b'0', '', 11),
(377, 377, 278, 40, '2017-08-17 19:18:29', 1, 3, b'0', '', 11),
(378, 378, 279, 500, '2017-08-18 18:01:11', 1, 3, b'0', '', 11),
(379, 379, 280, 300, '2017-08-19 09:54:07', 1, 12, b'0', '', 11),
(380, 380, 281, 30, '2017-08-19 11:52:03', 1, 12, b'0', '', 11),
(381, 381, 282, 200, '2017-08-21 16:35:37', 1, 3, b'0', '', 11),
(382, 382, 283, 200, '2017-08-21 16:39:58', 1, 3, b'1', '', 12),
(383, 383, 284, 500, '2017-08-21 16:53:39', 1, 3, b'1', '', 1),
(384, 384, 285, 700, '2017-08-21 17:35:54', 1, 3, b'0', '', 11),
(385, 385, 250, 250, '2017-08-21 19:07:43', 1, 3, b'0', '', 11),
(386, 386, 286, 850, '2017-08-21 19:14:23', 1, 3, b'0', '', 11),
(387, 387, 287, 850, '2017-08-21 19:14:27', 1, 3, b'0', '', 11),
(388, 388, 288, 450, '2017-08-22 10:19:04', 1, 3, b'0', '', 11),
(389, 389, 289, 3000, '2017-08-22 11:43:13', 1, 3, b'0', '', 11),
(390, 390, 290, 150, '2017-08-23 12:43:00', 1, 3, b'0', '', 11),
(391, 391, 291, 750, '2017-08-23 18:54:11', 1, 3, b'0', '', 11),
(392, 392, 292, 100, '2017-08-24 10:16:09', 1, 3, b'0', '', 11),
(393, 393, 293, 600, '2017-08-24 11:54:18', 1, 3, b'1', '', 1),
(394, 394, 288, 550, '2017-08-24 12:04:23', 1, 3, b'0', '', 11),
(395, 395, 250, 400, '2017-08-24 14:10:18', 1, 3, b'0', '', 11),
(396, 396, 294, 350, '2017-08-24 16:34:48', 1, 3, b'0', '', 11),
(397, 397, 240, 1000, '2017-08-25 09:35:04', 1, 8, b'1', '', 1),
(398, 398, 295, 1000, '2017-08-25 13:14:57', 1, 3, b'1', '', 1),
(399, 399, 296, 400, '2017-08-25 15:00:09', 1, 3, b'0', '', 11),
(400, 400, 297, 1000, '2017-08-25 16:49:25', 1, 3, b'0', '', 11),
(401, 401, 298, 100, '2017-08-25 18:57:06', 1, 3, b'0', '', 11),
(402, 402, 299, 300, '2017-08-25 19:00:25', 1, 3, b'0', '', 11),
(403, 403, 300, 300, '2017-08-25 19:09:52', 1, 3, b'0', '', 11),
(404, 404, 301, 200, '2017-08-25 19:19:29', 1, 3, b'0', '', 11),
(405, 405, 302, 500, '2017-08-28 09:46:35', 1, 3, b'1', '', 1),
(406, 406, 303, 18000, '2017-08-28 10:01:22', 1, 3, b'1', '', 1),
(407, 407, 304, 700, '2017-08-28 12:06:25', 1, 13, b'0', '', 11),
(408, 408, 250, 150, '2017-08-28 12:09:27', 1, 13, b'0', '', 11),
(409, 409, 305, 100, '2017-08-29 10:51:28', 1, 3, b'0', '', 11),
(410, 410, 306, 100, '2017-08-29 10:58:57', 1, 3, b'0', '', 11),
(411, 411, 307, 200, '2017-08-29 11:18:14', 1, 3, b'0', '', 11),
(412, 412, 308, 70, '2017-08-29 13:49:46', 1, 3, b'0', '', 11),
(413, 413, 309, 300, '2017-08-30 10:04:15', 1, 3, b'0', '', 11),
(414, 414, 310, 80, '2017-08-30 18:51:21', 1, 3, b'0', '', 11),
(415, 415, 311, 300, '2017-08-31 10:48:37', 1, 3, b'0', '', 11),
(416, 416, 54, 100, '2017-08-31 16:51:37', 2, 9, b'0', '', 11),
(417, 417, 312, 900, '2017-09-01 10:40:28', 1, 3, b'0', '', 11),
(418, 418, 313, 150, '2017-09-01 12:18:25', 1, 3, b'0', '', 11),
(419, 419, 252, 300, '2017-09-01 16:01:19', 1, 3, b'0', '', 11),
(420, 420, 314, 700, '2017-09-01 18:11:15', 1, 3, b'0', '', 11),
(421, 421, 268, 1500, '2017-09-02 12:54:57', 1, 12, b'0', '', 11),
(422, 422, 156, 450, '2017-09-04 10:26:13', 1, 3, b'0', '', 11),
(423, 423, 315, 1000, '2017-09-04 15:43:19', 1, 3, b'0', '', 11),
(424, 424, 316, 1000, '2017-09-04 17:43:38', 1, 3, b'0', '', 11),
(425, 425, 317, 3000, '2017-09-04 18:01:29', 1, 13, b'0', '', 11),
(426, 426, 299, 1500, '2017-09-04 18:53:34', 1, 3, b'1', '', 1),
(427, 427, 156, 150, '2017-09-05 12:54:50', 1, 3, b'0', '', 11),
(428, 428, 318, 500, '2017-09-05 15:08:06', 1, 3, b'0', '', 11),
(429, 429, 319, 100, '2017-09-05 17:39:07', 1, 13, b'0', '', 11),
(430, 430, 319, 100, '2017-09-05 17:40:14', 1, 13, b'1', '', 1),
(442, 442, 326, 500, '2017-09-05 19:45:17', 1, 3, b'0', '', 11),
(433, 433, 288, 350, '2017-08-17 17:46:13', 1, 13, b'0', '', 11),
(434, 434, 312, 900, '2017-09-05 17:48:54', 1, 13, b'0', '', 11),
(435, 435, 251, 250, '2017-09-05 17:50:51', 1, 13, b'0', '', 11),
(436, 436, 317, 3000, '2017-09-03 17:52:03', 1, 13, b'0', '', 11),
(437, 437, 321, 400, '2017-08-19 17:57:16', 1, 13, b'0', '', 11),
(438, 438, 322, 240, '2017-08-19 18:01:17', 1, 13, b'0', '', 11),
(439, 439, 323, 100, '2017-09-05 18:16:52', 1, 13, b'0', '', 11),
(440, 440, 324, 350, '2017-09-05 18:20:35', 1, 13, b'0', '', 11),
(441, 441, 325, 500, '2017-08-11 18:23:31', 1, 13, b'0', '', 11),
(443, 443, 327, 350, '2017-09-07 09:39:27', 1, 3, b'0', '', 11),
(444, 444, 328, 150, '2017-09-07 09:47:16', 1, 3, b'0', '', 11),
(445, 445, 250, 450, '2017-09-07 09:56:01', 1, 3, b'0', '', 11),
(446, 446, 329, 500, '2017-09-06 11:17:17', 1, 3, b'0', '', 11),
(447, 447, 332, 300, '2017-09-08 13:43:24', 1, 3, b'1', '', 1),
(448, 448, 250, 200, '2017-09-08 16:20:56', 1, 3, b'0', '', 11),
(449, 449, 333, 300, '2017-09-08 17:39:37', 1, 13, b'1', '', 1),
(452, 452, 334, 400, '2017-09-08 18:51:20', 1, 3, b'0', '', 11),
(451, 451, 331, 600, '2017-09-07 12:35:02', 1, 3, b'0', '', 11),
(453, 453, 335, 280, '2017-09-09 12:17:49', 1, 12, b'0', '', 11),
(454, 454, 318, 650, '2017-09-09 12:23:01', 1, 12, b'0', '', 11),
(455, 455, 336, 1200, '2017-09-09 13:01:16', 1, 12, b'0', '', 11),
(456, 456, 336, 1300, '2017-09-09 13:09:55', 1, 12, b'1', '', 1),
(457, 457, 337, 500, '2017-09-09 16:01:50', 1, 16, b'0', '', 11),
(458, 458, 268, 1000, '2017-09-09 16:56:21', 1, 16, b'0', '', 11),
(459, 459, 333, 400, '2017-09-11 09:37:03', 1, 3, b'1', '', 1),
(460, 460, 338, 700, '2017-09-11 11:47:14', 1, 3, b'0', '', 11),
(461, 461, 150, 400, '2017-09-11 14:11:43', 1, 3, b'0', '', 11),
(462, 462, 339, 250, '2017-09-11 14:23:10', 1, 3, b'0', '', 11),
(463, 463, 85, 100, '2017-09-11 14:41:56', 1, 3, b'0', '', 11),
(464, 464, 12, 180, '2017-09-11 15:38:18', 1, 3, b'0', '', 11),
(465, 465, 340, 15000, '2017-09-11 16:08:06', 1, 3, b'0', '', 11),
(466, 466, 341, 80, '2017-09-11 20:04:42', 1, 3, b'0', '', 11),
(467, 467, 342, 60, '2017-07-24 20:18:00', 1, 3, b'0', '', 11),
(468, 468, 212, 30, '2017-06-21 20:19:52', 1, 3, b'0', '', 11),
(469, 469, 343, 100, '2017-07-24 20:33:21', 1, 13, b'0', '', 11),
(470, 470, 275, 700, '2017-09-12 10:49:00', 1, 3, b'1', '', 1),
(471, 471, 344, 300, '2017-07-31 11:37:46', 1, 3, b'0', '', 11),
(472, 472, 345, 40, '2017-09-12 11:43:00', 1, 3, b'0', '', 11),
(473, 473, 346, 350, '2017-07-10 11:45:20', 1, 3, b'0', '', 11),
(474, 474, 15, 150, '2017-07-31 11:58:17', 1, 3, b'1', '', 1),
(475, 475, 90, 1100, '2017-03-17 12:00:32', 1, 3, b'0', '', 11),
(476, 476, 347, 300, '2017-06-10 12:37:00', 1, 3, b'0', '', 11),
(477, 477, 348, 20, '2017-09-12 13:34:36', 2, 9, b'0', '', 11),
(478, 478, 349, 100, '2017-09-12 14:26:00', 1, 13, b'1', '', 1),
(479, 479, 350, 400, '2017-09-12 18:07:59', 1, 3, b'0', '', 11),
(480, 480, 54, 12, '2017-09-12 20:11:25', 2, 9, b'0', '', 11),
(481, 481, 184, 400, '2017-08-07 11:09:06', 1, 3, b'1', '', 1),
(482, 482, 351, 150, '2017-09-13 11:55:26', 1, 3, b'1', '', 1),
(483, 485, 352, 200, '2017-09-11 18:43:38', 1, 3, b'0', '', 11),
(484, 486, 353, 250, '2017-09-13 19:14:33', 1, 3, b'1', '', 1),
(485, 487, 265, 12000, '2017-09-14 11:27:52', 1, 3, b'1', '', 1),
(486, 488, 354, 50, '2017-09-14 12:02:40', 1, 3, b'1', '', 1),
(487, 489, 355, 400, '2017-09-14 12:29:23', 1, 3, b'0', '', 11),
(488, 490, 181, 80, '2017-09-14 12:38:27', 1, 3, b'0', '', 11),
(489, 491, 356, 500, '2017-09-14 17:59:25', 1, 3, b'0', '', 11),
(490, 492, 317, 2000, '2017-09-15 10:27:37', 1, 3, b'0', '', 11),
(491, 493, 357, 2000, '2017-09-15 12:17:30', 1, 3, b'1', '', 1),
(492, 494, 351, 200, '2017-09-15 15:55:38', 1, 13, b'0', '', 11),
(493, 495, 358, 400, '2017-09-15 16:52:32', 1, 13, b'0', '', 11),
(494, 496, 359, 25000, '2017-09-15 17:05:04', 1, 13, b'0', '', 11),
(495, 497, 156, 200, '2017-09-15 19:48:55', 1, 13, b'1', '', 1),
(496, 498, 250, 400, '2017-09-16 11:53:43', 1, 12, b'0', '', 11),
(497, 499, 360, 250, '2017-09-16 12:33:40', 1, 12, b'1', '', 1),
(498, 500, 361, 550, '2017-09-16 12:45:34', 1, 12, b'0', '', 11),
(499, 501, 71, 250, '2017-09-16 12:54:26', 1, 12, b'1', '', 1),
(500, 502, 362, 450, '2017-09-16 16:24:32', 1, 12, b'1', '', 1),
(501, 503, 363, 600, '2017-09-16 18:06:03', 1, 13, b'1', '', 1),
(502, 504, 250, 250, '2017-09-18 10:12:35', 1, 3, b'0', '', 11),
(503, 505, 113, 400, '2017-09-18 11:17:18', 1, 3, b'0', '', 11),
(504, 506, 364, 800, '2017-09-18 12:04:28', 1, 3, b'0', '', 11),
(505, 507, 365, 120, '2017-09-18 12:21:35', 1, 3, b'0', '', 11),
(506, 508, 305, 150, '2017-09-18 12:26:50', 1, 3, b'0', '', 11),
(507, 509, 366, 1000, '2017-09-18 00:52:04', 1, 3, b'1', '', 1),
(508, 510, 367, 350, '2017-09-18 02:45:47', 1, 3, b'0', '', 11),
(509, 511, 156, 200, '2017-09-18 03:15:55', 1, 3, b'0', '', 11),
(510, 512, 368, 1000, '2017-09-18 05:05:44', 1, 3, b'0', '', 11),
(511, 513, 54, 26, '2017-09-18 23:49:57', 2, 9, b'0', '', 11),
(512, 514, 369, 150, '2017-09-19 13:24:58', 1, 3, b'1', '', 1),
(513, 515, 370, 250, '2017-09-19 14:13:11', 1, 3, b'0', '', 11),
(514, 516, 371, 1000, '2017-09-19 15:43:26', 1, 3, b'0', '', 11),
(515, 517, 372, 350, '2017-09-19 17:08:59', 1, 3, b'0', '', 11),
(516, 518, 206, 400, '2017-09-19 19:48:11', 1, 8, b'0', '', 11),
(517, 519, 373, 3500, '2017-09-20 09:50:38', 1, 3, b'1', '', 1),
(518, 520, 374, 200, '2017-09-20 11:37:46', 1, 3, b'0', '', 11),
(519, 521, 375, 600, '2017-09-20 11:57:50', 1, 3, b'1', '', 1),
(520, 522, 180, 100, '2017-09-20 16:10:12', 1, 3, b'0', '', 11),
(521, 523, 376, 300, '2017-09-20 16:17:30', 1, 3, b'0', '', 11),
(522, 524, 377, 300, '2017-09-20 18:58:37', 1, 3, b'1', '', 1),
(523, 525, 378, 900, '2017-09-21 10:25:37', 1, 3, b'1', '', 1),
(524, 526, 47, 170, '2017-09-21 17:06:32', 1, 3, b'0', '', 11),
(525, 527, 379, 350, '2017-09-21 18:02:25', 1, 3, b'1', '', 1),
(526, 528, 106, 250, '2017-09-21 18:36:34', 1, 3, b'0', '', 11),
(527, 529, 380, 20, '2017-09-22 09:14:46', 1, 13, b'0', '', 11),
(528, 530, 156, 50, '2017-09-22 10:34:47', 1, 3, b'1', '', 1),
(529, 531, 381, 400, '2017-09-22 14:27:27', 1, 3, b'0', '', 11),
(530, 532, 382, 100, '2017-09-22 16:04:01', 1, 3, b'0', '', 11),
(531, 533, 383, 300, '2017-09-22 16:31:27', 1, 3, b'0', '', 11),
(532, 535, 54, 60, '2017-09-22 17:24:00', 2, 9, b'0', '', 11),
(533, 536, 54, 11, '2017-09-22 18:02:09', 1, 1, b'0', '', 11),
(534, 537, 54, 40, '2017-09-22 18:42:05', 1, 1, b'0', '', 11),
(535, 538, 385, 400, '2017-09-22 18:42:28', 1, 13, b'0', '', 11),
(536, 539, 386, 200, '2017-09-22 19:13:48', 1, 3, b'1', '', 1),
(537, 540, 250, 450, '2017-09-23 14:46:07', 1, 12, b'0', '', 11),
(538, 541, 387, 150, '2017-09-23 15:16:30', 1, 13, b'1', '', 1),
(539, 542, 326, 349.99, '2017-09-23 16:55:54', 1, 13, b'1', '', 1),
(540, 543, 388, 250, '2017-09-25 12:04:01', 1, 3, b'0', '', 11),
(541, 544, 389, 250, '2017-09-25 15:41:12', 1, 3, b'0', '', 11),
(542, 545, 390, 350, '2017-09-25 16:58:50', 1, 3, b'0', '', 11),
(543, 546, 391, 800, '2017-09-25 19:24:36', 1, 3, b'1', '', 1),
(544, 547, 392, 500, '2017-09-26 10:35:10', 1, 3, b'1', '', 1),
(545, 548, 393, 600, '2017-09-26 12:02:29', 1, 3, b'0', '', 11),
(546, 549, 54, 100, '2017-09-26 16:47:57', 2, 9, b'0', '', 11),
(547, 550, 54, 150, '2017-09-26 16:48:36', 2, 9, b'0', '', 11),
(548, 551, 54, 100, '2017-09-26 17:11:42', 2, 9, b'0', '', 11),
(549, 552, 54, 30, '2017-09-26 17:16:26', 2, 9, b'0', '', 1),
(550, 553, 54, 80, '2017-09-26 17:17:18', 2, 9, b'0', '', 1),
(551, 554, 54, 80, '2017-09-26 17:24:54', 2, 9, b'0', '', 11),
(552, 555, 54, 80, '2017-09-26 17:25:13', 2, 9, b'0', '', 11),
(553, 556, 54, 80, '2017-09-26 17:31:11', 1, 3, b'0', '', 11),
(554, 557, 54, 24, '2017-09-26 17:38:41', 2, 9, b'0', '', 11),
(555, 558, 54, 100, '2017-09-26 17:41:45', 2, 9, b'0', '', 11),
(556, 559, 54, 80, '2017-09-26 19:24:24', 2, 9, b'0', '', 11),
(557, 560, 54, 100, '2017-09-26 19:39:13', 1, 1, b'0', '', 11),
(558, 561, 54, 100, '2017-09-26 20:23:54', 2, 9, b'0', '', 11),
(559, 562, 54, 50, '2017-09-26 20:33:10', 2, 9, b'0', '', 11),
(560, 563, 54, 120.71, '2017-09-26 20:59:45', 2, 9, b'1', '', 1),
(561, 564, 335, 300, '2017-09-27 09:43:50', 1, 13, b'1', '', 1),
(562, 565, 314, 700, '2017-09-27 12:28:44', 1, 3, b'0', '', 11),
(563, 566, 314, 300, '2017-09-27 12:30:35', 1, 3, b'0', '', 11),
(564, 567, 394, 4000, '2017-09-27 16:55:23', 1, 3, b'0', '', 11),
(565, 568, 395, 10000, '2017-09-27 17:27:54', 1, 3, b'0', '', 11),
(566, 569, 307, 200, '2017-09-27 17:44:33', 1, 3, b'0', '', 11),
(567, 571, 397, 750, '2017-09-27 17:53:18', 1, 3, b'0', '', 11),
(568, 572, 398, 20, '2017-09-27 18:42:27', 1, 3, b'1', '', 1),
(569, 573, 399, 400, '2017-09-28 12:03:31', 1, 3, b'0', '', 11),
(570, 574, 328, 200, '2017-09-28 18:18:33', 1, 3, b'0', '', 11),
(571, 575, 380, 50, '2017-09-28 19:37:59', 1, 3, b'0', '', 11),
(572, 576, 400, 300, '2017-09-29 10:03:42', 1, 3, b'1', '', 1),
(573, 577, 110, 1000, '2017-09-29 10:23:52', 1, 3, b'1', '', 1),
(574, 578, 5, 200, '2017-09-29 10:39:36', 1, 3, b'0', '', 11),
(575, 579, 401, 80, '2017-09-29 10:55:52', 1, 3, b'1', '', 1),
(576, 580, 137, 150, '2017-09-29 11:18:08', 1, 3, b'0', '', 11),
(577, 581, 156, 200, '2017-09-29 11:58:11', 1, 3, b'1', '', 1),
(578, 582, 119, 50, '2017-09-29 13:44:50', 1, 3, b'0', '', 11),
(579, 584, 403, 200, '2017-09-29 16:59:25', 1, 3, b'0', '', 11),
(580, 585, 156, 200, '2017-09-29 19:18:15', 1, 3, b'0', '', 11),
(581, 586, 404, 800, '2017-09-30 10:09:20', 1, 13, b'1', '', 1),
(582, 587, 405, 450, '2017-09-30 13:39:57', 1, 13, b'1', '', 1),
(583, 588, 406, 450, '2017-09-30 13:41:30', 1, 13, b'0', '', 11),
(584, 589, 137, 3600, '2017-10-02 15:16:15', 1, 3, b'1', '', 1),
(585, 590, 407, 70, '2017-10-02 18:24:31', 1, 3, b'0', '', 11),
(586, 591, 408, 550, '2017-10-03 10:01:45', 1, 3, b'1', '', 1),
(587, 592, 409, 800, '2017-10-03 10:30:20', 1, 3, b'0', '', 11),
(588, 593, 410, 350, '2017-10-03 11:15:34', 1, 3, b'0', '', 11),
(589, 594, 411, 200, '2017-10-03 11:57:05', 1, 3, b'0', '', 11),
(590, 595, 190, 500, '2017-10-03 13:18:52', 1, 3, b'1', '', 1),
(591, 596, 412, 300, '2017-10-03 13:31:22', 1, 13, b'1', '', 1),
(592, 597, 413, 300, '2017-10-03 14:45:28', 1, 13, b'1', '', 1),
(593, 598, 414, 300, '2017-10-03 14:52:27', 1, 13, b'0', '', 11),
(594, 599, 415, 1700, '2017-10-03 15:45:07', 1, 13, b'0', '', 11),
(595, 600, 416, 3500, '2017-10-03 16:52:07', 1, 13, b'0', '', 11),
(596, 601, 417, 40, '2017-10-03 19:14:02', 1, 3, b'0', '', 11),
(597, 602, 156, 200, '2017-10-04 09:36:47', 1, 3, b'0', '', 11),
(598, 603, 251, 250, '2017-10-04 11:31:42', 1, 3, b'0', '', 11),
(599, 604, 156, 550, '2017-10-04 12:48:38', 1, 3, b'0', '', 11),
(600, 605, 413, 200, '2017-10-04 13:08:16', 1, 13, b'1', '', 1),
(601, 606, 418, 700, '2017-10-04 13:39:15', 1, 3, b'1', '', 1),
(602, 607, 419, 550, '2017-10-04 13:56:07', 1, 3, b'0', '', 11),
(603, 608, 420, 400, '2017-10-05 12:15:11', 1, 3, b'1', '', 1),
(604, 609, 421, 100, '2017-10-05 13:39:57', 1, 3, b'1', '', 1),
(605, 610, 420, 600, '2017-10-05 14:22:22', 1, 3, b'1', '', 1),
(606, 611, 305, 300, '2017-10-07 13:53:52', 1, 12, b'1', '', 1),
(607, 612, 422, 100, '2017-10-07 16:04:49', 1, 12, b'1', '', 1),
(608, 613, 423, 150, '2017-10-09 09:54:48', 1, 3, b'1', '', 1),
(609, 614, 156, 450, '2017-10-09 10:01:46', 1, 3, b'0', '', 11),
(610, 615, 424, 500, '2017-10-09 10:20:33', 1, 3, b'0', '', 11),
(611, 616, 425, 200, '2017-10-09 10:33:46', 1, 3, b'1', '', 1),
(612, 617, 121, 600, '2017-10-09 12:37:02', 1, 3, b'0', '', 11),
(613, 618, 426, 231, '2017-10-09 14:41:19', 1, 13, b'1', '', 1),
(614, 619, 336, 700, '2017-10-10 10:04:31', 1, 13, b'1', '', 1),
(615, 620, 156, 550, '2017-10-10 10:21:03', 1, 3, b'0', '', 11),
(616, 621, 427, 300, '2017-10-10 13:08:47', 2, 9, b'1', '', 1),
(617, 622, 54, 100, '2017-10-10 14:01:16', 2, 9, b'0', '', 11),
(618, 623, 54, 3000, '2017-10-10 14:02:46', 2, 9, b'0', '', 11),
(619, 624, 54, 500, '2017-10-10 14:11:20', 2, 9, b'1', '', 1),
(620, 625, 250, 350, '2017-10-11 09:13:36', 1, 3, b'0', '', 11),
(621, 626, 428, 180, '2017-10-11 12:24:57', 1, 3, b'1', '', 1),
(622, 627, 412, 40, '2017-10-11 18:07:44', 1, 3, b'1', '', 1),
(623, 628, 174, 500, '2017-10-12 11:23:55', 1, 13, b'1', '', 1),
(624, 629, 429, 350, '2017-10-12 15:23:37', 1, 3, b'0', '', 11),
(625, 630, 430, 350, '2017-10-12 15:23:51', 1, 3, b'1', '', 1),
(626, 631, 431, 60, '2017-10-12 15:56:57', 1, 13, b'1', '', 1),
(627, 632, 432, 600, '2017-10-12 16:02:45', 1, 13, b'0', '', 11),
(628, 633, 433, 320, '2017-10-13 09:53:05', 1, 3, b'0', '', 11),
(629, 634, 371, 600, '2017-10-13 16:06:59', 1, 3, b'0', '', 11),
(630, 635, 434, 700, '2017-10-13 16:56:06', 1, 3, b'1', '', 1),
(631, 636, 435, 600, '2017-10-13 18:13:57', 1, 13, b'0', '', 11),
(632, 637, 370, 200, '2017-10-14 11:15:45', 1, 12, b'0', '', 11),
(633, 638, 15, 30, '2017-10-14 12:06:44', 1, 12, b'1', '', 1),
(634, 639, 227, 250, '2017-10-14 13:03:05', 1, 12, b'1', '', 1),
(635, 640, 436, 90, '2017-10-14 16:01:07', 1, 12, b'1', '', 1),
(636, 641, 415, 10000, '2017-10-16 09:46:00', 1, 3, b'1', '', 1),
(637, 642, 309, 350, '2017-10-16 12:09:49', 1, 13, b'1', '', 1),
(638, 643, 437, 200, '2017-10-16 13:30:47', 1, 13, b'1', '', 1),
(639, 644, 15, 20, '2017-10-16 14:14:41', 1, 3, b'1', '', 1),
(640, 645, 54, 259.98, '2017-10-16 16:41:43', 2, 9, b'1', '', 1),
(641, 646, 438, 50, '2017-10-16 16:50:27', 1, 3, b'1', '', 1),
(642, 647, 54, 264.74, '2017-09-16 17:51:12', 2, 9, b'1', '', 1),
(643, 648, 54, 184, '2017-10-16 18:24:14', 2, 9, b'1', '', 1),
(644, 649, 439, 1000, '2017-10-17 09:11:13', 1, 3, b'0', '', 11),
(645, 650, 361, 600, '2017-10-17 14:22:29', 1, 3, b'0', '', 11),
(646, 651, 440, 250, '2017-10-17 16:53:14', 1, 13, b'1', '', 1),
(647, 652, 441, 150, '2017-10-18 13:44:48', 1, 13, b'1', '', 1),
(648, 653, 442, 700, '2017-10-18 14:35:18', 1, 13, b'0', '', 11),
(649, 654, 263, 1200, '2017-10-18 17:37:11', 1, 13, b'1', '', 1),
(650, 655, 387, 850, '2017-10-18 17:53:01', 1, 13, b'1', '', 1),
(651, 656, 443, 1500, '2017-09-19 18:11:54', 1, 13, b'1', '', 1),
(652, 657, 444, 450, '2017-10-18 18:26:47', 1, 13, b'1', '', 1),
(653, 658, 436, 1000, '2017-10-18 18:42:49', 1, 13, b'1', '', 1),
(654, 659, 445, 150, '2017-10-20 10:17:02', 1, 3, b'1', '', 1),
(655, 660, 446, 60, '2017-10-20 17:03:34', 1, 3, b'1', '', 1),
(656, 661, 447, 100, '2017-10-20 18:10:33', 1, 3, b'1', '', 1),
(657, 662, 448, 50, '2017-10-20 19:14:14', 1, 3, b'1', '', 1),
(658, 663, 449, 150, '2017-10-21 10:17:13', 1, 8, b'1', '', 1),
(659, 664, 54, 300, '2017-10-20 12:44:11', 2, 9, b'0', '', 11),
(660, 665, 450, 200, '2017-10-23 13:10:19', 1, 3, b'1', '', 1),
(661, 666, 288, 250, '2017-10-23 14:34:31', 1, 3, b'0', '', 11),
(662, 667, 156, 200, '2017-10-24 12:52:05', 1, 3, b'0', '', 11),
(663, 668, 156, 200, '2017-10-24 12:53:22', 1, 3, b'1', '', 1),
(664, 669, 69, 150, '2017-09-19 13:48:18', 1, 3, b'1', '', 1),
(665, 670, 451, 300, '2017-10-24 15:28:13', 1, 3, b'1', '', 1),
(666, 671, 452, 600, '2017-10-19 17:45:16', 1, 3, b'1', '', 1),
(667, 672, 389, 250, '2017-10-24 18:25:43', 1, 3, b'1', '', 1),
(668, 673, 453, 70, '2017-09-19 17:25:01', 1, 3, b'0', '', 11),
(669, 674, 454, 850, '2017-09-19 11:03:54', 1, 3, b'1', '', 1),
(670, 675, 54, 11, '2017-10-26 11:30:41', 2, 9, b'0', '', 11),
(671, 676, 54, 22, '2017-10-26 11:32:46', 2, 9, b'0', '', 11),
(672, 677, 194, 300, '2017-10-26 12:49:15', 1, 3, b'1', '', 1),
(673, 678, 455, 900, '2017-10-26 14:13:12', 1, 3, b'1', '', 1),
(674, 679, 456, 350, '2017-10-26 14:54:21', 1, 3, b'1', '', 1),
(675, 680, 356, 350, '2017-10-26 18:02:34', 1, 3, b'0', '', 11),
(676, 681, 222, 200, '2017-10-26 18:19:15', 1, 3, b'0', '', 11),
(677, 682, 54, 181, '2017-10-27 11:01:39', 2, 9, b'1', '', 1),
(678, 683, 457, 4600, '2017-10-27 17:17:07', 1, 13, b'1', '', 1),
(679, 684, 54, 43, '2017-10-27 17:21:59', 1, 1, b'1', '', 1),
(680, 685, 458, 500, '2017-10-27 18:35:48', 1, 13, b'1', '', 1),
(681, 686, 459, 250, '2017-10-28 10:39:57', 1, 13, b'1', '', 1),
(682, 687, 460, 100, '2017-10-28 10:57:07', 1, 13, b'1', '', 1),
(683, 688, 461, 149, '2017-10-28 11:05:08', 1, 13, b'1', '', 1),
(684, 689, 461, 150, '2017-10-28 11:06:31', 1, 13, b'1', '', 1),
(685, 690, 181, 40, '2017-10-28 09:16:24', 1, 13, b'1', '', 1),
(686, 691, 256, 120, '2017-10-28 09:34:43', 1, 13, b'1', '', 1),
(687, 692, 256, 119, '2017-10-30 09:41:17', 1, 13, b'1', '', 1),
(688, 693, 90, 1100, '2017-03-17 10:09:43', 1, 3, b'1', '', 1),
(689, 694, 156, 450, '2017-10-30 11:28:48', 1, 13, b'1', '', 1),
(690, 695, 156, 200, '2017-10-30 11:33:49', 1, 13, b'1', '', 1),
(691, 696, 462, 500, '2017-10-30 09:57:18', 1, 3, b'1', '', 1),
(692, 697, 463, 1800, '2017-10-30 12:05:46', 1, 13, b'1', '', 1),
(693, 698, 406, 300, '2017-10-30 15:31:22', 1, 3, b'1', '', 1),
(694, 699, 464, 200, '2017-09-29 16:17:46', 1, 3, b'1', '', 1),
(695, 700, 424, 700, '2017-10-30 17:53:58', 1, 14, b'1', '', 1),
(696, 701, 465, 60, '2017-10-30 18:20:51', 1, 13, b'1', '', 1),
(697, 702, 54, 500, '2017-09-03 18:28:48', 1, 1, b'1', '', 1),
(698, 703, 466, 60, '2017-10-30 18:34:28', 1, 13, b'1', '', 1),
(699, 704, 467, 500, '2017-10-30 18:40:23', 1, 14, b'1', '', 1),
(700, 705, 416, 5000, '2017-10-31 10:46:50', 1, 3, b'1', '', 1),
(701, 706, 367, 200, '2017-10-31 10:58:40', 1, 3, b'1', '', 1),
(702, 707, 251, 500, '2017-10-31 13:41:34', 1, 14, b'1', '', 1),
(703, 708, 468, 100, '2017-10-31 15:57:24', 1, 3, b'0', '', 11),
(704, 710, 312, 300, '2017-10-31 19:27:27', 1, 13, b'1', '', 1),
(705, 711, 323, 100, '2017-11-02 16:36:44', 1, 3, b'1', '', 1),
(706, 712, 469, 280, '2017-11-02 16:39:46', 1, 3, b'1', '', 1),
(707, 714, 471, 150, '2017-11-03 10:00:58', 1, 3, b'1', '', 1),
(708, 715, 472, 650, '2017-11-03 12:56:29', 1, 3, b'0', '', 11),
(709, 716, 403, 200, '2017-11-03 16:32:30', 1, 3, b'1', '', 1),
(710, 717, 473, 400, '2017-11-03 17:33:05', 1, 3, b'1', '', 1),
(711, 718, 439, 2300, '2017-11-03 18:46:26', 1, 14, b'1', '', 1),
(712, 719, 474, 260, '2017-11-04 10:07:13', 1, 14, b'1', '', 1),
(713, 720, 475, 120, '2017-11-04 19:32:03', 1, 13, b'1', '', 1),
(714, 721, 476, 400, '2017-11-06 08:30:34', 1, 13, b'1', '', 1),
(715, 722, 268, 1000, '2017-11-06 09:34:34', 1, 13, b'1', '', 1),
(716, 723, 156, 350, '2017-11-06 12:32:04', 1, 3, b'1', '', 1),
(717, 724, 477, 300, '2017-11-06 14:57:07', 1, 3, b'1', '', 1),
(718, 725, 223, 300, '2017-11-07 11:20:30', 1, 13, b'1', '', 1),
(719, 726, 478, 300, '2017-11-07 13:49:30', 1, 3, b'1', '', 1),
(720, 727, 479, 450, '2017-11-07 18:27:05', 1, 3, b'1', '', 1),
(721, 728, 480, 550, '2017-11-08 09:19:13', 1, 13, b'1', '', 1),
(722, 729, 312, 900, '2017-11-08 10:54:48', 1, 3, b'1', '', 1),
(723, 730, 481, 200, '2017-11-08 12:05:35', 1, 3, b'1', '', 1),
(724, 731, 482, 1200, '2017-11-08 14:05:52', 1, 3, b'1', '', 1),
(725, 732, 388, 250, '2017-11-08 17:12:43', 1, 3, b'1', '', 1),
(726, 733, 483, 40, '2017-11-08 18:22:15', 1, 3, b'1', '', 1),
(727, 734, 484, 150, '2017-11-09 10:24:44', 1, 3, b'1', '', 1),
(728, 735, 315, 70, '2017-11-09 13:33:00', 1, 13, b'1', '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

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
  `prodQuienAprueba` varchar(50) NOT NULL DEFAULT '',
  `esCompra` bit(1) NOT NULL DEFAULT b'0' COMMENT '1 para compra si, 0 para no'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`idProducto`, `prodNombre`, `prodMontoEntregado`, `prodInteres`, `prodFechaInicial`, `prodFechaVencimiento`, `prodObservaciones`, `prodMontoPagar`, `prodAdelanto`, `idCliente`, `prodDioAdelanto`, `prodActivo`, `prodFechaRegistro`, `idUsuario`, `idSucursal`, `prodUltimaFechaInteres`, `prodCuantoFinaliza`, `prodQuienFinaliza`, `prodFechaFinaliza`, `prodAprobado`, `prodQuienAprueba`, `esCompra`) VALUES
(1, 'tripode (hansa)', 50, 4, '2017-01-07', '2017-02-07', '', 60, 0, 1, b'0', b'0', '2017-02-06 12:29:21', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(2, 'camara panasonic con cargador + calular huawei cun-lo3', 200, 4, '2017-01-13', '2017-02-07', '', 232, 0, 2, b'0', b'0', '2017-02-06 12:42:39', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(3, 'laptop hp 455', 26, 4, '2017-02-16', '2017-02-18', 'Se adelantó S/. 20.80 de S/. 46.80 el día 16/02/2017<br>Se adelantó S/. 20.80 de S/. 67.60 el día 16/02/2017<br>Se adelantó S/. 20.80 de S/. 88.40 el día 16/02/2017<br>Se adelantó S/. 20.80 de S/. 109.20 el día 16/02/2017<br>Se adelantó S/. 20.80 de S/. 130.00 el día 16/02/2017<br>', 156, 0, 3, b'0', b'0', '2017-02-06 13:00:15', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(4, 'monitor led de 20\" + aspiradora vlast pro', 252, 4, '2017-02-23', '2017-02-18', 'Se adelantó S/. 48.00 de S/. 300.00 el día 23/02/2017<br>', 360, 0, 4, b'0', b'0', '2017-02-06 13:04:22', 2, 1, '', 413.28, 'Yuri Paola', '2017-06-14 18:54:58', b'0', '0', b'0'),
(5, 'monitor lG flatron 17\"', 60, 4, '2017-01-19', '2017-02-19', '', 72, 0, 5, b'0', b'0', '2017-02-06 13:08:30', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(6, 'cocina 5 hornillas bosh + blueray philips ', 500, 4, '2017-01-19', '2017-02-19', '', 600, 0, 6, b'0', b'0', '2017-02-06 13:26:03', 2, 1, '', 900, 'manrique', '2017-06-05 21:14:31', b'0', '0', b'0'),
(7, 'laptop hp pavilion ', 350, 4, '2017-01-20', '2017-02-20', '', 420, 0, 7, b'0', b'0', '2017-02-06 13:30:46', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(8, 'back-ups cs 650 apc', 48, 4, '2017-02-24', '2017-02-23', 'Se adelantó S/. 2.00 de S/. 50.00 el día 24/02/2017<br>transformador blanco', 60, 0, 8, b'0', b'0', '2017-02-06 13:36:28', 2, 1, '', 82.56, 'Yuri Paola', '2017-06-28 20:05:24', b'0', '0', b'0'),
(9, 'cubiertos rena ware + cortinas', 400, 4, '2017-01-26', '2017-02-26', '26 cubiertos ', 480, 0, 9, b'0', b'0', '2017-02-06 13:45:32', 2, 1, '', 832, 'Aumbbel', '2017-07-31 23:58:02', b'0', '0', b'0'),
(10, 'celular huawei g6 l33', 51.2, 4, '2017-04-08', '2017-02-28', 'Se adelantó S/. 16.00 de S/. 67.20 el día 08/04/2017<br>Se adelantó S/. 12.80 de S/. 80.00 el día 02/03/2017<br>', 92.8, 0, 10, b'0', b'0', '2017-02-06 13:52:49', 2, 1, '', 73.73, 'Pilar Maria', '2017-06-24 09:11:27', b'0', '0', b'0'),
(11, 'laptop lenovo b50-80', 500, 4, '2017-02-03', '2017-03-03', 'nuevo+cargador+boleta (en caja)', 580, 0, 11, b'0', b'0', '2017-02-06 13:56:58', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(12, 'celular lg g3 + dvd', 150, 4, '2017-02-06', '2017-03-06', '', 174, 0, 12, b'0', b'0', '2017-02-06 14:03:55', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(13, 'reloj casio 5468', 100, 4, '2017-01-10', '2017-02-10', 'en caja', 120, 0, 13, b'0', b'0', '2017-02-06 14:19:15', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(14, 'horno microondas color negro', 105, 4, '2017-01-11', '2017-02-11', '', 126, 0, 14, b'0', b'0', '2017-02-06 14:38:37', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(15, 'camara digital finefix ', 25.2, 4, '2017-03-25', '2017-03-07', 'Se adelantó S/. 4.80 de S/. 30.00 el día 25/03/2017<br>con memoria 8gb ', 34.8, 0, 15, b'0', b'0', '2017-02-07 17:46:08', 2, 1, '', 44.35, 'Aumbbel', '2017-08-01 00:48:17', b'0', '0', b'0'),
(16, 'licuadora taurus + celular huawey', 100, 4, '2017-02-07', '2017-03-07', '', 116, 0, 16, b'0', b'0', '2017-02-07 17:51:17', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(17, 'laptop dell inspiracion 14 serie 3000', 336, 4, '2017-03-06', '2017-03-06', 'Se adelantó S/. 64.00 de S/. 400.00 el día 06/03/2017<br>funda+cargador+mause', 464, 0, 17, b'0', b'0', '2017-02-07 17:54:08', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(18, '2 incubadoras automaticas', 170, 4, '2017-02-07', '2017-03-07', '', 197.2, 0, 18, b'0', b'0', '2017-02-07 17:57:25', 2, 1, '', 285.6, 'manrique', '2017-06-05 21:14:51', b'0', '0', b'0'),
(19, 'frigobar daewoo color negro', 168, 4, '2017-03-13', '2017-03-08', 'Se adelantó S/. 32.00 de S/. 200.00 el día 13/03/2017<br>', 232, 0, 19, b'0', b'0', '2017-02-08 15:20:29', 2, 1, '', 248.64, 'manrique', '2017-06-05 21:15:06', b'0', '0', b'0'),
(20, 'laptop acer', 200, 4, '2017-02-09', '2017-03-09', 'le faltan tres teclas ', 232, 0, 20, b'0', b'0', '2017-02-09 16:39:09', 2, 1, '', 360, 'Yuri Paola', '2017-06-23 09:56:14', b'0', '0', b'0'),
(21, 'celular huawei ascend g7 blanco', 80, 4, '2017-02-09', '2017-03-09', 'pantalla rota ', 92.8, 0, 15, b'0', b'0', '2017-02-09 18:34:00', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(22, 'bluray sony', 450, 4, '2016-09-05', '2016-10-05', 'laptop hpi3 con cargador (vendido)', 540, 0, 21, b'0', b'0', '2017-02-09 19:12:54', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(23, 'camara profesional canon', 700, 4, '2016-10-28', '2016-11-28', '', 840, 0, 22, b'0', b'0', '2017-02-09 19:16:56', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(51, 'olla renaware', 300, 4, '2016-09-30', '2016-10-30', '', 360, 0, 50, b'0', b'0', '2017-02-10 16:27:41', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(26, 'calculadora hp', 80, 4, '2016-10-04', '2016-11-04', '', 96, 0, 25, b'0', b'0', '2017-02-09 19:30:32', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(27, '2 mochilas', 590, 4, '2016-10-11', '2016-11-11', 'hom dvd+camara+laptop', 708, 0, 26, b'0', b'0', '2017-02-09 19:33:28', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(28, 'equipo de sonido+monitor+blueray+computador i5', 520, 4, '2016-10-13', '2016-11-13', '', 624, 0, 27, b'0', b'0', '2017-02-09 19:35:55', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(29, 'laptop hp', 200, 4, '2016-10-25', '2016-11-25', '', 240, 0, 28, b'0', b'0', '2017-02-09 19:41:13', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(30, 'equipo de sonido lg', 200, 4, '2016-10-26', '2016-11-26', '', 240, 0, 29, b'0', b'0', '2017-02-09 19:43:04', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(31, 'plancha +equipo', 200, 4, '2016-11-11', '2016-12-11', '', 240, 0, 30, b'0', b'0', '2017-02-09 19:46:47', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(32, 'cpu intel i3+monitor advance', 200, 4, '2016-11-14', '2016-12-14', '', 240, 0, 31, b'0', b'0', '2017-02-09 19:50:07', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(33, 'laptop i7+impresora hp nueva', 1000, 4, '2016-11-15', '2016-12-15', '', 1200, 0, 32, b'0', b'0', '2017-02-09 19:52:48', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(34, 'tv samsung', 150, 4, '2016-11-15', '2016-12-15', '', 180, 0, 33, b'0', b'0', '2017-02-09 19:54:32', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(35, 'olla arrocera 6l', 70, 4, '2016-11-16', '2016-12-16', '', 84, 0, 34, b'0', b'0', '2017-02-09 19:56:18', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(36, 'guitarra electronica', 100, 4, '2016-11-17', '2016-12-17', '', 120, 0, 35, b'0', b'0', '2017-02-09 19:58:05', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(37, 'terma solar', 500, 4, '2016-11-19', '2016-12-19', '', 600, 0, 36, b'0', b'0', '2017-02-09 19:59:57', 2, 1, '', 1120, 'manrique', '2017-06-23 19:38:48', b'0', '0', b'0'),
(38, 'impresora canon hg 2410', 60, 4, '2016-11-28', '2016-12-28', '', 72, 0, 37, b'0', b'0', '2017-02-09 20:01:48', 2, 1, '', 124.8, 'manrique', '2017-06-05 21:14:06', b'0', '0', b'0'),
(50, '2 tiquetera epson', 600, 4, '2016-09-28', '2016-10-28', '', 720, 0, 49, b'0', b'0', '2017-02-10 16:25:09', 2, 1, '', 1464, 'manrique', '2017-06-05 21:13:51', b'0', '0', b'0'),
(40, 'laptop toshiba core i3', 350, 4, '2016-12-03', '2017-01-03', '', 420, 0, 39, b'0', b'0', '2017-02-09 20:05:36', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(41, 'tablet acer', 80, 4, '2016-12-12', '2017-01-12', '', 96, 0, 40, b'0', b'0', '2017-02-09 20:07:48', 2, 1, '', 147.2, 'Yuri Pahola', '2017-05-04 18:59:03', b'0', '0', b'0'),
(42, 'gps', 68, 4, '2017-02-15', '2017-01-12', 'Se adelantó S/. 32.00 de S/. 100.00 el día 15/02/2017<br>', 120, 0, 41, b'0', b'0', '2017-02-09 20:09:44', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(43, 'laptop i5 con cargador y estuche azul', 550, 4, '2016-12-14', '2017-01-14', '', 660, 0, 42, b'0', b'0', '2017-02-09 20:11:52', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(44, 'laptop amd a8 con cargador y estuche', 400, 4, '2016-12-15', '2017-01-15', '', 480, 0, 43, b'0', b'0', '2017-02-09 20:13:30', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(45, 'camara sony lens g', 180, 4, '2017-10-07', '2017-01-16', '', 216, 0, 44, b'0', b'0', '2017-02-09 20:15:14', 2, 1, '07/10/2017', 0, '', '', b'0', '0', b'0'),
(46, 'tablet teraware', 60, 4, '2016-12-01', '2017-01-01', '', 72, 0, 45, b'0', b'0', '2017-02-09 20:17:13', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(47, 'laptop con mochila ploma', 800, 4, '2017-01-06', '2017-02-06', '', 960, 0, 46, b'0', b'0', '2017-02-09 20:19:06', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(48, 'laptop hp pavilion', 500, 4, '2017-01-07', '2017-02-07', '', 600, 0, 47, b'0', b'0', '2017-02-09 20:20:40', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(49, 'Tv 3d Smart Samsung 40 6400', 500, 4, '2017-02-10', '2017-03-10', 'Accesorios completos(en caja)', 580, 0, 48, b'0', b'0', '2017-02-10 12:33:53', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(52, 'laptop hp i3', 300, 4, '2016-12-01', '2017-02-01', '', 408, 0, 51, b'0', b'0', '2017-02-10 16:47:31', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(53, 'camara canon powershota2600', 60, 4, '2017-02-10', '2017-02-11', 'estuche y cargador', 62.4, 0, 52, b'0', b'0', '2017-02-10 18:18:09', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(54, 'tablet advance con cargador', 80, 4, '2017-02-11', '2017-03-11', '', 92.8, 0, 53, b'0', b'0', '2017-02-11 12:10:09', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(55, 'celular mod XD', 19, 4, '2017-09-19', '2017-02-14', 'Se adelantó S/. 1.00 de S/. 20.00 el día 03/03/2017<br>Se adelantó S/. 1.00 de S/. 21.00 el día 03/03/2017<br>Se adelantó S/. 2.00 de S/. 23.00 el día 03/03/2017<br>Se adelantó S/. 1.00 de S/. 24.00 el día 24/02/2017<br>Se adelantó S/. 1.00 de S/. 25.00 el día 24/02/2017<br>', 26, 71.5, 54, b'0', b'0', '2017-02-13 12:43:31', 9, 2, '19/09/2017', 0, '', '', b'0', '0', b'0'),
(56, 'taza chocolate', 160, 4, '2017-09-19', '2017-01-11', 'Se adelantó S/. 30.00 de S/. -30.00 el día 24/02/2017<br>Se adelantó S/. 10.00 de S/. -20.00 el día 24/02/2017<br>Se adelantó S/. 15.00 de S/. -5.00 el día 24/02/2017<br>Se adelantó S/. 1.00 de S/. -4.00 el día 24/02/2017<br>Se adelantó S/. 1.00 de S/. -3.00 el día 24/02/2017<br>Se adelantó S/. 1.00 de S/. -2.00 el día 24/02/2017<br>Se adelantó S/. 2.00 de S/. 0.00 el día 24/02/2017<br>Se adelantó S/. 1 del monto S/. 1.00el día 15/02/2017<br>Se adelantó S/. 1 del monto S/. 2.00el día 15/02/2017<br>', 2.08, 0, 54, b'0', b'0', '2017-02-13 12:44:43', 9, 2, '19/09/2017', 224, '9', '2017-05-04 12:54:14', b'0', '0', b'0'),
(57, 'bicicleta bianss', 50, 4, '2017-02-13', '2017-03-13', '', 58, 0, 55, b'0', b'0', '2017-02-13 15:32:14', 2, 1, '', 82, 'manrique', '2017-06-05 21:16:16', b'0', '0', b'0'),
(58, 'laptop hp envy i7', 700, 4, '2017-02-13', '2017-03-13', 'laptop con cargador', 812, 0, 56, b'0', b'0', '2017-02-13 16:03:28', 2, 1, '', 1316, 'Aumbbel', '2017-07-11 12:52:23', b'0', '0', b'0'),
(59, 'pc +pantalla lg+parlantes+transformador', 170, 4, '2017-02-20', '2017-03-13', 'Se adelantó S/. 30.00 de S/. 200.00 el día 20/02/2017<br>', 232, 0, 57, b'0', b'0', '2017-02-13 16:34:48', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(60, 'tv lg 32ln540b', 300, 4, '2017-02-13', '2017-03-13', '', 348, 0, 58, b'0', b'0', '2017-02-13 17:27:00', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(61, 'laptop hp pavilion 14 notebook con cargador', 300, 4, '2017-02-13', '2017-03-13', '', 348, 0, 59, b'0', b'0', '2017-02-13 17:49:57', 2, 1, '', 444, 'Yuri Pahola', '2017-05-04 18:44:44', b'0', '0', b'0'),
(62, 'laptop advance i3 con cargador', 107, 4, '2017-02-13', '2017-03-13', '', 124.12, 0, 57, b'0', b'0', '2017-02-13 19:00:44', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(63, 'televisor noc le19w037', 100, 4, '2017-02-14', '2017-03-14', 'con su control', 116, 0, 60, b'0', b'0', '2017-02-14 11:06:32', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(64, 'Celular modelo zte', 140, 4, '2017-10-16', '2017-04-08', '', 237.6, 71, 54, b'0', b'0', '2017-09-14 14:05:50', 9, 2, '16/10/2017', 0, '', '', b'0', '0', b'0'),
(565, 'bajo electronico ibanez sr506 6 cuerdas + llanta nueva w2h-429', 700, 4, '2017-11-06', '2017-09-28', 'recogio', 700, 0, 314, b'0', b'0', '2017-09-27 12:28:44', 3, 1, '06/11/2017', 0, '', '', b'0', '', b'0'),
(566, 'laptop toshiba l45-b4205fl', 300, 4, '2017-11-06', '2017-09-28', 'recogio', 300, 0, 314, b'0', b'0', '2017-09-27 12:30:35', 3, 1, '06/11/2017', 0, '', '', b'0', '', b'0'),
(567, 'auto geely ck 2012 (plata)', 4000, 4, '2017-10-18', '2017-09-28', '', 4000, 0, 394, b'0', b'0', '2017-09-27 16:55:23', 3, 1, '18/10/2017', 0, '', '', b'0', '', b'0'),
(568, 'camión dutro hino w5r-821 (blanco)', 10000, 4, '2017-11-08', '2017-09-28', 'no se dio el prestamo', 10000, 0, 395, b'0', b'0', '2017-09-27 17:27:54', 3, 1, '08/11/2017', 0, '', '', b'0', '', b'0'),
(65, 'Deja su cubo antiesess', 100, 4, '2017-02-14', '2017-02-15', 'Deja su cubo antiesess', 104, 44, 61, b'0', b'1', '2017-02-14 14:31:23', 10, 3, '', 0, '', '', b'0', '0', b'0'),
(66, 'pc advance intel core duo', 80, 4, '2017-02-15', '2017-03-15', '', 92.8, 0, 62, b'0', b'0', '2017-02-15 13:30:53', 2, 1, '', 131.2, 'manrique', '2017-06-05 21:16:38', b'0', '0', b'0'),
(67, 'taladro repli. bosch(verde)', 40, 4, '2017-02-16', '2017-03-16', '', 46.4, 0, 63, b'0', b'0', '2017-02-16 13:58:46', 2, 1, '', 65.6, 'Yuri Paola', '2017-06-08 19:44:36', b'0', '0', b'0'),
(68, 'maquina vibradora para piso marca honda G200', 200, 4, '2017-02-20', '2017-03-20', '', 232, 0, 64, b'0', b'0', '2017-02-20 10:27:08', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(69, 'celular huawei cun l03', 100, 4, '2017-02-20', '2017-03-20', '', 116, 0, 65, b'0', b'0', '2017-02-20 12:20:53', 2, 1, '', 172, 'Aumbbel', '2017-06-26 20:22:24', b'0', '0', b'0'),
(70, 'equipo de sonido l6-3420 + olla arrocera rc 6', 101, 4, '2017-04-03', '2017-02-14', 'Se adelantó S/. 24.00 de S/. 125.00 el día 03/04/2017<br>Se adelantó S/. 25.00 de S/. 150.00 el día 04/03/2017<br>', 180, 0, 66, b'0', b'1', '2017-02-20 12:57:52', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(71, 'guitarra electroacustica villalta', 100, 4, '2017-02-20', '2017-04-20', '', 136, 0, 67, b'0', b'0', '2017-02-20 14:08:25', 2, 1, '', 144, 'Yuri Pahola', '2017-05-04 18:41:46', b'0', '0', b'0'),
(72, '3 celulares htc desire 626s', 500, 4, '2017-02-21', '2017-03-21', '', 580, 0, 68, b'0', b'0', '2017-02-21 09:52:04', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(73, 'llanta+gata+triangulo+estuche (5 herramientas) ', 100, 4, '2017-02-22', '2017-03-22', '', 116, 0, 69, b'0', b'0', '2017-02-22 16:50:08', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(74, '2 cpu + 2 monitor samsung', 1000, 4, '2017-02-23', '2017-03-23', '', 1160, 0, 4, b'0', b'0', '2017-02-23 19:01:40', 2, 1, '', 1600, 'Yuri Paola', '2017-06-08 19:46:49', b'0', '0', b'0'),
(75, 'laptop hp probook 4440 con escuche', 300, 4, '2017-02-24', '2017-03-24', '', 348, 0, 70, b'0', b'0', '2017-02-24 09:49:29', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(76, 'camara canon sx50hs', 160, 4, '2017-04-28', '2017-03-24', 'Se adelantó S/. 90.00 de S/. 250.00 el día 28/04/2017<br>', 290, 90, 71, b'0', b'0', '2017-02-24 18:07:38', 2, 1, '', 230.4, 'Yuri Paola', '2017-07-14 10:39:57', b'0', '0', b'0'),
(77, 'moto pulsar rs 200 amarillo+casco+tarjeta+soat', 2000, 4, '2017-02-24', '2017-03-24', '', 2320, 0, 72, b'0', b'0', '2017-02-24 19:26:10', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(78, 'moto pulsar rs 200 amarillo+casco+tarjeta+soat', 2000, 4, '2017-02-24', '2017-03-24', '', 2320, 0, 73, b'0', b'0', '2017-02-24 19:26:11', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(79, 'computador de cabina', 400, 4, '2017-02-28', '2017-03-28', '', 464, 0, 65, b'0', b'0', '2017-02-28 12:02:23', 2, 1, '', 640, 'Yuri Paola', '2017-06-08 19:47:46', b'0', '0', b'0'),
(80, 'Playstation 3 Cech-4001b con comando y 5 juegos', 150, 4, '2017-02-28', '2017-03-28', 'comando Genérico ', 174, 0, 74, b'0', b'0', '2017-02-28 13:56:43', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(81, '40 pares de zapatos', 600, 4, '2017-03-01', '2017-05-01', '', 816, 0, 75, b'0', b'0', '2017-03-01 13:09:24', 2, 1, '', 888, 'Yuri Paola', '2017-05-22 10:01:57', b'0', '0', b'0'),
(82, 'auto daewoo lanos se- verde : placa. a4N-580', 1500, 4, '2017-03-02', '2017-03-09', '', 1560, 0, 76, b'0', b'0', '2017-03-02 17:55:57', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(83, 'laptop levono con cargador y estuche', 300, 4, '2017-03-04', '2017-04-04', '', 360, 0, 77, b'0', b'0', '2017-03-04 12:37:50', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(84, 'televisor lg 32lh570b', 300, 4, '2017-03-04', '2017-04-04', '', 360, 0, 78, b'0', b'0', '2017-03-04 13:31:52', 2, 1, '', 468, 'Yuri Paola', '2017-06-08 19:48:15', b'0', '0', b'0'),
(85, 'filtro de agua rena ware', 400, 4, '2017-03-06', '2017-04-06', '', 480, 0, 79, b'0', b'0', '2017-03-06 11:56:47', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(86, '3 carritos emolienteros', 1200, 4, '2017-03-07', '2017-04-07', '', 1440, 0, 80, b'0', b'1', '2017-03-07 15:37:02', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(87, 'laptop lenovo ideapad 510 i7', 756, 4, '2017-04-11', '2017-04-09', 'Se adelantó S/. 144.00 de S/. 900.00 el día 11/04/2017<br>', 1080, 0, 81, b'0', b'0', '2017-03-09 12:46:02', 2, 1, '', 1028.16, 'Yuri Paola', '2017-06-12 10:11:51', b'0', '0', b'0'),
(88, 'microondas daewoo kor-190 n', 30, 4, '2017-03-09', '2017-04-09', '', 36, 0, 82, b'0', b'0', '2017-03-09 17:25:37', 2, 1, '', 45.6, 'manrique', '2017-06-05 21:17:05', b'0', '0', b'0'),
(89, 'laptop asus m:x70i s:8220', 850, 4, '2017-03-10', '2017-04-10', '', 1020, 0, 83, b'0', b'0', '2017-03-10 11:51:58', 2, 1, '', 1394, 'Yuri Paola', '2017-06-28 20:05:55', b'0', '0', b'0'),
(90, 'cocina de mesa visioneer', 50, 4, '2017-03-10', '2017-04-10', '', 60, 0, 84, b'0', b'0', '2017-03-10 14:36:47', 2, 1, '', 78, 'Yuri Paola', '2017-06-14 18:56:13', b'0', '0', b'0'),
(91, '2 congas con funda-lpy y 1 parche remo', 300, 4, '2017-03-10', '2017-04-10', '', 360, 0, 85, b'0', b'0', '2017-03-10 15:03:28', 2, 1, '', 396, 'Yuri Pahola', '2017-05-04 18:42:06', b'0', '0', b'0'),
(92, 'televisor samsung un40jh5005g', 150, 4, '2017-03-11', '2017-04-11', '', 180, 0, 86, b'0', b'0', '2017-03-11 10:06:40', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(93, 'laptop toshiba c55-b5117km', 140, 4, '2017-03-11', '2017-04-11', '', 168, 0, 87, b'0', b'0', '2017-03-11 11:06:00', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(94, 'fotocopiadora hp laser jet m5035 mfp', 350, 4, '2017-03-13', '2017-04-13', '', 420, 0, 88, b'0', b'0', '2017-03-13 20:37:08', 2, 1, '', 532, 'Yuri Paola', '2017-06-08 19:48:32', b'0', '0', b'0'),
(95, 'chocolatera rena ware, thomas th-135, imaco grill ig2815a', 230, 4, '2017-03-14', '2017-04-14', '', 276, 0, 89, b'0', b'0', '2017-03-14 14:05:24', 2, 1, '', 340.4, 'Yuri Paola', '2017-06-05 14:47:13', b'0', '0', b'0'),
(96, 'laptop lenovo z50', 1100, 4, '2017-03-17', '2017-04-17', '', 1320, 0, 90, b'0', b'0', '2017-03-17 13:13:35', 2, 1, '', 1760, 'Yuri Paola', '2017-06-28 20:06:18', b'0', '0', b'0'),
(97, 'televisor samsung + celular sony c5', 500, 4, '2017-03-17', '2017-04-17', '', 600, 0, 91, b'0', b'0', '2017-03-17 14:00:39', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(98, 'laptop hp pavilion amd 8', 400, 4, '2017-03-18', '2017-04-18', '', 480, 0, 92, b'0', b'0', '2017-03-18 13:03:20', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(99, 'laptop hp 455 + impresora hp 3545', 150, 4, '2017-03-20', '2017-04-20', '', 180, 0, 3, b'0', b'0', '2017-03-20 15:47:54', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(100, 'teodolito nikon ne-20h + nivel sokkia 262358', 1000, 4, '2017-03-20', '2017-04-20', '', 1200, 0, 93, b'0', b'0', '2017-03-20 20:19:35', 2, 1, '', 1760, 'Aumbbel', '2017-07-31 23:58:24', b'0', '0', b'0'),
(101, 'laptop hp 15 i3', 350, 4, '2017-03-21', '2017-04-21', '', 420, 0, 94, b'0', b'0', '2017-03-21 14:24:41', 2, 1, '', 560, 'Yuri Paola', '2017-06-28 20:13:50', b'0', '0', b'0'),
(102, 'Camara Nikon Coolpix S9900', 200, 4, '2017-03-22', '2017-04-22', '', 240, 0, 95, b'0', b'0', '2017-03-22 12:26:06', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(103, 'televisor lg 24MT47A-pm', 200, 4, '2017-03-23', '2017-04-23', '', 240, 0, 96, b'0', b'0', '2017-03-23 16:48:58', 2, 1, '', 312, 'Yuri Paola', '2017-06-28 20:07:03', b'0', '0', b'0'),
(104, 'camara canon eos 40d', 280, 4, '2017-03-24', '2017-04-24', '', 336, 0, 97, b'0', b'0', '2017-03-24 18:18:45', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(105, 'zapatos 43', 300, 4, '2017-03-24', '2017-04-24', '', 360, 0, 75, b'0', b'0', '2017-03-24 19:36:17', 2, 1, '', 408, 'Yuri Paola', '2017-05-22 10:01:32', b'0', '0', b'0'),
(106, 'laptop toshiba satellite p55t-c5114', 1200, 10, '2017-03-25', '2017-03-27', '', 1320, 0, 98, b'0', b'0', '2017-03-25 16:09:31', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(107, 'televisor panasonic', 130, 4, '2017-03-28', '2017-03-30', '', 135.2, 0, 99, b'0', b'0', '2017-03-28 11:39:31', 2, 1, '', 192.4, 'Yuri Paola', '2017-06-14 18:55:53', b'0', '0', b'0'),
(108, 'toshiba laptop satelite c55 b5115km', 200, 4, '2017-03-28', '2017-04-28', '', 240, 0, 100, b'0', b'0', '2017-03-28 13:39:52', 2, 1, '', 312, 'Yuri Paola', '2017-06-28 20:07:31', b'0', '0', b'0'),
(109, 'lavadora samsung 5k', 70, 4, '2017-03-28', '2017-04-28', '', 84, 0, 101, b'0', b'0', '2017-03-28 17:17:27', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(110, 'Estacion Total  Es-105 Incluye Tripode Prisma Baston', 2500, 4, '2017-03-29', '2017-04-29', '', 3000, 0, 102, b'0', b'0', '2017-03-29 12:58:02', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(111, 'laotop lenovo g40-80(con cargador)+ lenovo g580', 800, 4, '2017-03-31', '2017-04-30', '', 960, 0, 103, b'0', b'0', '2017-03-31 16:59:12', 2, 1, '', 1152, 'Yuri Paola', '2017-06-14 18:59:48', b'0', '0', b'0'),
(112, 'Martillo Bosch Gsh 16', 500, 4, '2017-03-31', '2017-04-30', '', 600, 0, 104, b'0', b'0', '2017-03-31 17:15:52', 2, 1, '', 620, 'Yuri Paola', '2017-05-11 10:23:14', b'0', '0', b'0'),
(113, 'play 3 cech-4011b (mando+juego+2cables)', 300, 4, '2017-03-31', '2017-04-30', '', 360, 0, 105, b'0', b'0', '2017-03-31 17:29:07', 2, 1, '', 456, 'Yuri Paola', '2017-06-28 20:07:51', b'0', '0', b'0'),
(114, 'celular samsung j7', 250, 4, '2017-03-31', '2017-04-14', '', 270, 0, 106, b'0', b'0', '2017-03-31 19:43:38', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(115, 'Laptop HP Probook 4440 Core i5', 300, 4, '2017-04-01', '2017-04-29', 'Laptop sin cargador, solo se le entrega S/. 250. En espera de que entregue cargador lunes 4 de abril.', 348, 0, 70, b'0', b'0', '2017-04-01 09:20:44', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(116, 'Laptop Core i3 Lenovo - SN CB25877919', 300, 4, '2017-04-01', '2017-04-29', 'Con cargador', 348, 0, 107, b'0', b'0', '2017-04-01 15:56:38', 2, 1, '', 432, 'Yuri Paola', '2017-06-14 18:59:11', b'0', '0', b'0'),
(117, 'Laptop Core i3 lenovo con cargador', 300, 4, '2017-04-01', '2017-04-29', '', 348, 0, 107, b'0', b'0', '2017-04-01 15:59:14', 2, 1, '', 420, 'Yuri Paola', '2017-06-08 19:51:05', b'0', '0', b'0'),
(118, 'hp pavilion x360', 500, 10, '2017-04-03', '2017-04-05', '', 550, 0, 108, b'0', b'0', '2017-04-03 18:56:12', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(119, 'estacion total sokkia cx-105', 2500, 4, '2017-04-04', '2017-04-11', '', 2600, 0, 102, b'0', b'0', '2017-04-04 17:20:33', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(120, 'camara canon revel t5', 600, 4, '2017-04-04', '2017-05-04', '', 720, 0, 109, b'0', b'0', '2017-04-05 13:02:52', 2, 1, '', 816, 'Yuri Paola', '2017-05-31 12:40:38', b'0', '0', b'0'),
(674, 'xboom', 850, 4, '2017-09-19', '2017-10-27', 'no registrado por bea', 850, 0, 454, b'0', b'1', '2017-09-19 11:03:54', 3, 1, '', 0, '', '', b'0', '', b'0'),
(675, 'prueba 01', 11, 4, '2017-10-26', '2017-10-27', '', 11, 0, 54, b'0', b'0', '2017-10-26 11:30:41', 9, 2, '26/10/2017', 0, '', '', b'0', '', b'0'),
(122, 'motocicleta yamaha fz negro(b4-2306)', -666, 4, '2017-07-06', '2017-05-07', 'Se adelantó S/. 110.00 de S/. -556.00 el día 06/07/2017<br>Se adelantó S/. 550.00 de S/. -6.00 el día 06/07/2017<br>Se adelantó S/. 550.00 de S/. 544.00 el día 06/07/2017<br>Se adelantó S/. 128.00 de S/. 672.00 el día 07/06/2017<br>Se adelantó S/. 128.00 de S/. 800.00 el día 04/05/2017<br>', 960, 1466, 110, b'0', b'0', '2017-04-07 10:14:24', 2, 1, '', -799.2, 'Yuri Paola', '2017-08-09 13:08:01', b'0', '0', b'0'),
(123, 'calculadora hp', 70, 4, '2017-04-07', '2017-05-07', '', 84, 0, 111, b'0', b'0', '2017-04-07 12:12:28', 2, 1, '', 103.6, 'Yuri Paola', '2017-06-28 20:04:53', b'0', '0', b'0'),
(124, 'televisor 42\" lg', 250, 4, '2017-04-07', '2017-05-07', '', 300, 0, 112, b'0', b'0', '2017-04-07 12:38:52', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(125, 'una motocicleta marca barsha modelo lamger r17 placa 1028-0w', 350, 4, '2017-04-09', '2017-05-04', 'DEJA TARJETA DE PROPIEDAD, CASCO Y LLAVE.', 406, 0, 113, b'0', b'0', '2017-04-09 14:03:07', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(126, 'cpu + monitor + teclado + mause', 150, 4, '2017-04-10', '2017-05-10', '', 180, 0, 114, b'0', b'0', '2017-04-10 17:44:43', 2, 1, '', 222, 'Yuri Paola', '2017-06-28 20:08:05', b'0', '0', b'0'),
(127, 'mini componente samsung mx-j630 230', 64, 4, '2017-05-11', '2017-04-24', 'Se adelantó S/. 16.00 de S/. 80.00 el día 11/05/2017<br>', 86.4, 16, 115, b'0', b'0', '2017-04-10 18:37:40', 2, 1, '', 87.04, 'Beatriz', '2017-07-08 18:02:46', b'0', '0', b'0'),
(128, 'bicicleta de niño + bluray + reloj seiko', 0, 18, '2017-04-17', '2017-05-11', 'Se adelantó S/. 200.00 de S/. 200.00 el día 17/04/2017<br>', 380, 0, 116, b'0', b'0', '2017-04-11 10:27:05', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(129, 'celular j3 + llanta + 5 herramientas', 200, 4, '2017-07-17', '2017-05-11', 'Se canceló el interés S/. 40.00 de el día 17/07/2017<br>Se canceló el interés S/. 80.00 de el día 14/06/2017<br>Se canceló el interés S/. 40.00 de el día 12/05/2017<br>', 240, 0, 69, b'0', b'0', '2017-04-11 10:50:20', 2, 1, '17/07/2017', 224, 'Yuri Paola', '2017-08-02 16:36:08', b'0', '0', b'0'),
(130, 'tablet acer', 40, 4, '2017-06-19', '2017-05-11', 'Se adelantó S/. 10.00 de S/. 50.00 el día 19/06/2017<br>Se canceló el interés S/. 20.00 de el día 19/06/2017<br>', 60, 10, 117, b'0', b'0', '2017-04-11 11:07:14', 2, 1, '19/06/2017', 44.8, 'Yuri Paola', '2017-07-10 16:15:42', b'0', '0', b'0'),
(131, 'moto r15', 1500, 4, '2017-04-11', '2017-05-11', '', 1800, 0, 118, b'0', b'0', '2017-04-11 14:57:54', 2, 1, '', 2220, 'Yuri Paola', '2017-06-28 20:08:55', b'0', '0', b'0'),
(132, 'laptop hp +cargador', 300, 4, '2017-04-08', '2017-05-08', '', 360, 0, 119, b'0', b'0', '2017-04-11 17:25:14', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(133, 'laptop hp 14 r003la', 200, 4, '2017-04-11', '2017-05-11', '', 240, 0, 120, b'0', b'0', '2017-04-11 18:27:59', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(134, 'guitarra eléctrica jt 690 -fr', 200, 4, '2017-04-12', '2017-04-26', '', 216, 0, 121, b'0', b'0', '2017-04-12 11:53:44', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(135, 'laptop toshiba satellite con cargador', 150, 4, '2017-04-12', '2017-04-26', '', 162, 0, 122, b'0', b'0', '2017-04-12 12:02:53', 2, 1, '', 216, 'Yuri Paola', '2017-06-28 20:10:59', b'0', '0', b'0'),
(136, 'gpsmap 62s garmin', 200, 4, '2017-04-12', '2017-04-30', '', 224, 0, 123, b'0', b'0', '2017-04-12 12:24:15', 2, 1, '', 288, 'Yuri Paola', '2017-06-28 20:10:19', b'0', '0', b'0'),
(137, 'laptop lenovo + disco duro 1tb', 300, 4, '2017-04-12', '2017-05-12', '', 360, 0, 124, b'0', b'0', '2017-04-12 16:28:45', 2, 1, '', 432, 'Yuri Paola', '2017-06-28 20:09:58', b'0', '0', b'0'),
(138, 'laptop hp blanco con cargador', 150, 4, '2017-04-12', '2017-05-12', '', 180, 0, 125, b'0', b'0', '2017-04-12 17:35:50', 2, 1, '', 216, 'Yuri Paola', '2017-06-28 20:14:49', b'0', '0', b'0'),
(139, 'laptop asus x540l i3', 350, 4, '2017-04-13', '2017-05-13', '', 420, 0, 126, b'0', b'0', '2017-04-13 10:58:40', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(140, 'caja con herramientas', 90, 4, '2017-09-19', '2017-05-01', 'Se adelantó S/. 10.00 de S/. 100.00 el día 17/04/2017<br>', 108, 0, 54, b'0', b'0', '2017-04-17 11:20:34', 9, 2, '19/09/2017', 101, '', '2017-05-04 17:56:38', b'0', '0', b'0'),
(141, 'consola de videojuegos', 300, 4, '2017-04-17', '2017-04-18', '', 330, 0, 54, b'0', b'0', '2017-04-17 12:09:16', 9, 2, '', 336, '', '2017-05-04 13:55:54', b'0', '0', b'0'),
(142, 'celular motorola x1', 200, 4, '2017-04-17', '2017-04-18', 'Se adelantó S/. 10.00 de S/. 210.00 el día 17/04/2017<br>Se adelantó S/. 5.00 de S/. 215.00 el día 17/04/2017<br>Se adelantó S/. 3.00 de S/. 218.00 el día 17/04/2017<br>Se adelantó S/. 6.00 de S/. 224.00 el día 17/04/2017<br>Se adelantó S/. 11.00 de S/. 235.00 el día 17/04/2017<br>Se adelantó S/. 5.00 de S/. 240.00 el día 17/04/2017<br>Se adelantó S/. 10.00 de S/. 250.00 el día 17/04/2017<br>', 275, 35, 127, b'0', b'0', '2017-04-17 12:33:11', 9, 2, '', 224, '', '2017-05-04 18:10:34', b'0', '0', b'0'),
(143, 'ps 3 cech4211a + bluray lg bp450', 150, 4, '2017-04-17', '2017-04-18', '', 165, 0, 128, b'0', b'0', '2017-04-17 12:44:39', 2, 1, '', 216, 'Yuri Paola', '2017-06-28 20:11:32', b'0', '0', b'0'),
(144, 'horno microondas ge jes700pgk', 70, 4, '2017-04-17', '2017-04-18', '', 77, 0, 129, b'0', b'0', '2017-04-17 13:10:48', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(145, 'laptop hp amd a8', 400, 4, '2017-04-17', '2017-04-18', '', 440, 0, 130, b'0', b'0', '2017-04-17 18:52:03', 2, 1, '', 656, 'Aumbbel', '2017-08-01 00:48:57', b'0', '0', b'0'),
(146, 'camara filmadora nikon + cargador', 200, 4, '2017-04-18', '2017-04-19', '', 220, 0, 95, b'0', b'0', '2017-04-18 15:50:25', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(147, 'laptop toshiba satellite ci5 u940 - sp4301gl', 300, 4, '2017-04-19', '2017-04-20', '', 330, 0, 131, b'0', b'0', '2017-04-19 16:54:45', 2, 1, '', 348, 'Yuri Paola', '2017-05-17 18:38:45', b'0', '0', b'0'),
(148, 'laptop toshiba s55t-a', 350, 4, '2017-04-20', '2017-04-21', '', 385, 0, 132, b'0', b'0', '2017-04-20 11:26:55', 2, 1, '', 420, 'Yuri Paola', '2017-05-25 10:21:18', b'0', '0', b'0'),
(149, 'tv samsung 45\"', 300, 4, '2017-04-20', '2017-04-21', '', 330, 0, 133, b'0', b'0', '2017-04-20 16:37:59', 2, 1, '', 420, 'Yuri Paola', '2017-06-28 20:10:41', b'0', '0', b'0'),
(150, 'mini componente lg mc 8360', 300, 4, '2017-04-20', '2017-04-21', '', 330, 0, 134, b'0', b'0', '2017-04-20 18:29:19', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(151, 'bluray bdp 1300', 70, 4, '2017-04-21', '2017-04-22', '', 77, 0, 116, b'0', b'0', '2017-04-21 09:57:44', 2, 1, '', 78.4, 'Yuri Pahola', '2017-05-12 16:17:02', b'0', '0', b'0'),
(152, 'camara canon t5', 300, 4, '2017-04-21', '2017-04-22', '', 330, 0, 109, b'0', b'0', '2017-04-21 17:42:29', 2, 1, '', 372, 'Yuri Paola', '2017-05-31 12:41:25', b'0', '0', b'0'),
(153, 'moto honda cb190r', 1700, 4, '2017-04-22', '2017-04-23', '', 1870, 0, 135, b'0', b'0', '2017-04-22 12:33:52', 2, 1, '', 2040, 'Yuri Pahola', '2017-05-23 14:27:49', b'0', '0', b'0'),
(154, 'laptop hp mas cargador', 150, 4, '2017-04-24', '2017-04-25', 'Se canceló el interés S/. 36.00 de el día 02/06/2017<br>Se canceló el interés S/. 36.00 de el día 02/06/2017<br>', 165, 0, 136, b'0', b'0', '2017-04-24 14:03:59', 2, 1, '02/06/2017', 198, 'Yuri Paola', '2017-06-19 19:05:29', b'0', '0', b'0'),
(155, 'cpu amd atlon + monitor aoc', 200, 4, '2017-04-26', '2017-04-27', '', 220, 0, 137, b'0', b'0', '2017-04-26 16:42:52', 2, 1, '', 272, 'Yuri Paola', '2017-06-28 20:12:42', b'0', '0', b'0'),
(156, 'laptop hp', 300, 4, '2017-04-27', '2017-04-28', '', 330, 0, 138, b'0', b'0', '2017-04-27 09:54:04', 2, 1, '', 408, 'Yuri Paola', '2017-06-28 20:16:15', b'0', '0', b'0'),
(157, 'pc 4 con 2 monitor', 240, 4, '2017-04-27', '2017-04-28', '', 264, 0, 139, b'0', b'0', '2017-04-27 10:01:44', 2, 1, '', 326.4, 'Yuri Paola', '2017-06-28 20:15:48', b'0', '0', b'0'),
(158, 'microondas samsung cheff', 50, 4, '2017-04-27', '2017-04-28', '', 55, 0, 140, b'0', b'0', '2017-04-27 12:06:04', 2, 1, '', 68, 'Yuri Paola', '2017-06-28 20:11:48', b'0', '0', b'0'),
(159, 'guitarra electroacustica godin + calculadora hp 50g', 90, 4, '2017-04-27', '2017-04-28', '', 99, 0, 141, b'0', b'0', '2017-04-27 17:09:21', 2, 1, '', 104.4, 'Yuri Paola', '2017-05-25 12:43:59', b'0', '0', b'0'),
(160, 'estación total leica ts06 plush', 4000, 4, '2017-04-28', '2017-04-29', '', 4400, 0, 102, b'0', b'0', '2017-04-28 13:30:45', 2, 1, '', 4400, 'Yuri Paola', '2017-05-11 10:23:42', b'0', '0', b'0'),
(161, 'pedal soul food, pedal bluesky, balanza, amplificador', 300, 4, '2017-04-28', '2017-04-29', '', 330, 0, 142, b'0', b'0', '2017-04-28 14:50:03', 2, 1, '', 0, '', '', b'0', '0', b'0'),
(162, 'celular sony m4 aqua', 100, 4, '2017-04-28', '2017-04-29', '', 110, 0, 143, b'0', b'0', '2017-04-28 18:55:43', 2, 1, '', 110, 'Yuri Paola', '2017-05-11 10:24:10', b'0', '0', b'0'),
(163, 'una extractora y una computadora', 200, 4, '2017-04-29', '2017-04-30', '', 220, 0, 144, b'0', b'0', '2017-04-29 12:24:16', 2, 1, '', 312, 'Aumbbel', '2017-08-05 13:48:52', b'0', '0', b'0'),
(164, 'laptop hp i5 con estuche (sin cargador)', 250, 4, '2017-04-02', '2017-05-03', '', 275, 0, 70, b'0', b'0', '2017-05-02 09:20:19', 2, 1, '', 330, 'Yuri Paola', '2017-05-24 11:14:01', b'0', '0', b'0'),
(165, 'celular samsung j7', -20, 4, '2017-05-06', '2017-05-04', 'Se adelantó S/. 220.00 de S/. 200.00 el día 06/05/2017<br>', 220, 220, 145, b'0', b'0', '2017-05-03 14:26:13', 2, 1, '', 220, 'Mateo Quincho', '2017-05-27 10:33:49', b'0', '0', b'0'),
(166, 'mouse gamer', 50, 4, '2017-05-04', '2017-05-05', '', 55, 0, 54, b'0', b'0', '2017-05-04 13:43:19', 9, 2, '', 55, 'demo', '2017-05-10 12:24:27', b'0', '0', b'0'),
(167, 'cd de musica', 15, 4, '2017-05-04', '2017-05-05', '', 16.5, 0, 54, b'0', b'0', '2017-05-04 13:44:50', 9, 2, '', 16.5, '', '2017-05-04 18:02:16', b'0', '0', b'0'),
(168, 'laptop hp amd a8', 300, 4, '2017-05-08', '2017-05-09', '', 330, 0, 146, b'0', b'0', '2017-05-08 11:24:29', 2, 1, '', 330, 'Yuri Paola', '2017-05-15 10:21:10', b'0', '', b'0'),
(169, '2 laptop lenovo b40-80 corei3', 800, 4, '2017-07-10', '2017-05-09', 'Se canceló el interés S/. 96.00 de el día 10/07/2017<br>Se canceló el interés S/. 192.00 de el día 19/06/2017<br>', 880, 0, 147, b'0', b'1', '2017-05-08 14:43:09', 2, 1, '10/07/2017', 0, '', '', b'0', '', b'0'),
(170, 'laptop hp 14-am012la', 500, 4, '2017-05-09', '2017-05-10', '', 550, 0, 148, b'0', b'0', '2017-05-09 18:53:24', 2, 1, '', 560, 'Mateo Quincho', '2017-05-27 11:20:09', b'0', '', b'0'),
(171, 'celular s7 edge samsung', 500, 4, '2017-05-10', '2017-05-11', '', 550, 0, 149, b'0', b'0', '2017-05-10 17:17:11', 2, 1, '', 550, 'Yuri Paola', '2017-05-20 12:26:23', b'0', '', b'0'),
(172, 'celular abc', 15, 4, '2017-06-14', '2017-05-12', 'Se canceló el interés S/. 3.60 de el día 14/06/2017<br>Se canceló el interés S/. 3.60 de el día 14/06/2017<br>Se canceló el interés S/. 3.60 de el día 14/06/2017<br>Se canceló el interés S/. 3.60 de el día 14/06/2017<br>Se canceló el interés S/. 3.60 de el día 14/06/2017<br>Se canceló el interés S/. 3.60 de el día 14/06/2017<br>Se canceló el interés S/. 1.50 de el día 11/05/2017<br>Se canceló el interés S/. 16.50 de el día 11/05/2017<br>', 16.5, 0, 54, b'0', b'0', '2017-05-11 11:17:08', 9, 2, '14/06/2017', 16.5, 'demo', '2017-06-20 18:25:09', b'0', '', b'0'),
(173, 'laptop tosbiba satellite l755', 400, 4, '2017-05-11', '2017-05-12', '', 440, 0, 150, b'0', b'0', '2017-05-11 13:37:13', 2, 1, '', 512, 'Yuri Paola', '2017-06-28 20:18:20', b'0', '', b'0'),
(174, 'tv l 43\" 4k', 400, 4, '2017-05-11', '2017-05-12', '', 440, 0, 151, b'0', b'0', '2017-05-11 15:09:36', 2, 1, '', 608, 'Aumbbel', '2017-08-05 13:50:07', b'0', '', b'0'),
(175, 'mochila', 130, 4, '2017-06-20', '2017-05-12', 'Se adelantó S/. 20.00 de S/. 150.00 el día 20/06/2017<br>Se canceló el interés S/. 15.00 de el día 20/06/2017<br>Se canceló el interés S/. 30.00 de el día 14/06/2017<br>', 165, 20, 54, b'0', b'0', '2017-05-11 17:54:02', 9, 2, '20/06/2017', 143, 'demo', '2017-06-21 14:17:57', b'0', '', b'0'),
(176, 'pantalla vieja', 150, 4, '2017-05-11', '2017-05-12', 'Se canceló el interés S/. 15.00 de el día 15/05/2017<br>', 165, 0, 54, b'0', b'0', '2017-05-11 18:38:01', 9, 2, '15/05/2017', 186, 'demo', '2017-06-21 14:18:24', b'0', '', b'0'),
(177, 'estación total leica ts06 plush', 4500, 4, '2017-05-12', '2017-05-13', '', 4950, 0, 102, b'0', b'0', '2017-05-12 14:55:11', 2, 1, '', 5220, 'Yuri Paola', '2017-06-07 18:51:30', b'0', '', b'0'),
(178, 'samsung j7', 170, 4, '2017-05-12', '2017-05-13', '', 187, 0, 106, b'0', b'0', '2017-05-12 16:58:56', 2, 1, '', 187, 'Yuri Paola', '2017-05-22 13:02:14', b'0', '', b'0'),
(179, 'camara canon vixia', 250, 4, '2017-05-12', '2017-05-13', '', 275, 0, 152, b'0', b'0', '2017-05-12 17:32:41', 2, 1, '', 380, 'Aumbbel', '2017-08-05 13:50:26', b'0', '', b'0'),
(180, 'laptop compaq', 100, 4, '2017-05-12', '2017-05-13', '', 110, 0, 153, b'0', b'0', '2017-05-12 18:10:03', 2, 1, '', 128, '', '2017-06-27 16:42:40', b'0', '', b'0'),
(181, 'parlante amplicador', 350, 4, '2017-05-13', '2017-05-14', '', 385, 0, 154, b'0', b'0', '2017-05-13 12:56:26', 2, 1, '', 448, 'Yuri Paola', '2017-06-28 20:17:14', b'0', '', b'0'),
(182, 'laptop toshiba satellite l505', 300, 4, '2017-05-15', '2017-05-16', '', 330, 0, 155, b'0', b'0', '2017-05-15 09:44:20', 2, 1, '', 330, 'Yuri Paola', '2017-05-23 17:58:17', b'0', '', b'0'),
(183, 'tv smart lg 42lb5800', 400, 4, '2017-05-15', '2017-05-16', '', 440, 0, 156, b'0', b'0', '2017-05-15 12:25:44', 2, 1, '', 440, 'Yuri Paola', '2017-05-19 18:10:41', b'0', '', b'0'),
(184, 'laptop y40-70 lenovo con cargador y estuche', 300, 4, '2017-07-18', '2017-05-16', 'Se canceló el interés S/. 60.00 de el día 18/07/2017<br>Se adelantó S/. 300.00 de S/. 600.00 el día 19/06/2017<br>Se canceló el interés S/. 120.00 de el día 19/06/2017<br>', 660, 300, 157, b'0', b'0', '2017-05-15 17:26:59', 2, 1, '18/07/2017', 372, 'Yuri Paola', '2017-08-29 18:59:11', b'0', '', b'0'),
(185, 'laptop sony vaio i5', 200, 4, '2017-06-19', '2017-06-16', 'Se canceló el interés S/. 40.00 de el día 19/06/2017<br>', 240, 0, 158, b'0', b'0', '2017-05-16 14:44:46', 3, 1, '19/06/2017', 220, 'Yuri Paola', '2017-06-27 12:06:53', b'0', '', b'0'),
(186, 'laptop toshiba', 60, 4, '2017-05-17', '2017-05-25', '', 66, 0, 159, b'0', b'0', '2017-05-17 18:28:56', 3, 1, '', 86.4, 'Aumbbel', '2017-08-01 00:49:08', b'0', '', b'0'),
(187, 'lg xboom cm 8460', 600, 4, '2017-05-18', '2017-05-19', 'TOTALMENTE NUEVO SELLADO', 660, 0, 160, b'0', b'0', '2017-05-18 15:35:53', 2, 1, '', 864, 'Aumbbel', '2017-07-31 23:58:52', b'0', '', b'0'),
(188, 'ps2 sony y bluray sony', 200, 4, '2017-05-18', '2017-05-19', '', 220, 0, 161, b'0', b'0', '2017-05-18 16:05:05', 2, 1, '', 296, 'Aumbbel', '2017-08-05 13:50:45', b'0', '', b'0'),
(189, 'laptop hp pavilion 14', 250, 4, '2017-05-19', '2017-06-19', '', 300, 0, 59, b'0', b'0', '2017-05-19 11:30:52', 3, 1, '', 275, '', '2017-06-01 15:25:05', b'0', '', b'0'),
(190, 'televisor lg 47ln5400', 300, 4, '2017-05-19', '2017-06-19', '', 360, 0, 156, b'0', b'0', '2017-05-19 12:49:21', 3, 1, '', 330, 'Yuri Paola', '2017-05-22 16:53:16', b'0', '', b'0'),
(191, 'maquina soldar miller maxstar 200', 300, 4, '2017-05-19', '2017-06-19', '', 360, 0, 162, b'0', b'0', '2017-05-19 14:15:00', 3, 1, '', 360, 'Mateo Quincho', '2017-06-17 12:00:50', b'0', '', b'0'),
(192, 'moto pulsar 200 amarillo', 2500, 4, '2017-05-19', '2017-06-19', '', 3000, 0, 163, b'0', b'0', '2017-05-19 15:40:26', 3, 1, '', 2750, 'Yuri Paola', '2017-05-31 19:32:01', b'0', '', b'0'),
(193, 'laptop lenovo g50-45', 350, 4, '2017-05-19', '2017-06-19', '', 420, 0, 164, b'0', b'0', '2017-05-19 16:30:14', 3, 1, '', 385, 'Yuri Paola', '2017-05-29 17:33:06', b'0', '', b'0'),
(194, 'celulares (sony c5,samdung j5)', 300, 4, '2017-05-19', '2017-06-19', '', 360, 0, 91, b'0', b'0', '2017-05-19 17:08:42', 3, 1, '', 330, 'Yuri Paola', '2017-05-25 10:40:49', b'0', '', b'0'),
(195, 'tv led nex 32\"', 150, 4, '2017-05-20', '2017-05-21', '', 165, 0, 165, b'0', b'0', '2017-05-20 10:17:37', 3, 1, '', 186, 'Aumbbel', '2017-06-28 20:32:09', b'0', '', b'0'),
(196, 'laptop toshiba', 70, 4, '2017-05-20', '2017-05-21', '', 77, 0, 166, b'0', b'0', '2017-05-20 13:59:32', 3, 1, '', 100.8, 'Aumbbel', '2017-08-01 00:49:24', b'0', '', b'0'),
(197, 'laptop lenovo z51', 300, 4, '2017-07-06', '2017-06-22', 'Se canceló el interés S/. 84.00 de el día 06/07/2017<br>', 360, 0, 143, b'0', b'0', '2017-05-22 12:16:32', 3, 1, '06/07/2017', 372, 'Yuri Paola', '2017-08-15 11:28:47', b'0', '', b'0'),
(198, '2 estación total ...', 6000, 4, '2017-05-22', '2017-06-22', '', 7200, 0, 102, b'0', b'0', '2017-05-22 16:39:56', 3, 1, '', 8640, 'Yuri Paola', '2017-08-01 12:07:02', b'0', '', b'0'),
(199, 'teodolito topcon dt200+ nivel topcon+ campana de cocina', 1500, 4, '2017-08-16', '2017-06-22', 'Se canceló el interés S/. 300.00 de el día 16/08/2017<br>Se canceló el interés S/. 300.00 de el día 18/07/2017<br>Se canceló el interés S/. 240.00 de el día 19/06/2017<br>7 objetos ', 1800, 0, 167, b'0', b'1', '2017-05-22 18:13:17', 3, 1, '16/08/2017', 0, '', '', b'0', '', b'0'),
(200, 'tv aoc 19\"', 50, 4, '2017-05-22', '2017-06-22', '', 60, 0, 168, b'0', b'0', '2017-05-22 19:07:09', 3, 1, '', 55, '', '2017-05-29 17:31:05', b'0', '', b'0'),
(201, 'calculadora texas instruments nspire ti nspire cx.', 150, 4, '2017-09-19', '2017-06-23', '', 180, 0, 169, b'0', b'0', '2017-05-23 18:09:44', 3, 1, '19/09/2017', 0, '', '', b'0', '', b'0'),
(202, 'tablet samsung gt p7300', 100, 4, '2017-05-24', '2017-06-24', '', 120, 0, 170, b'0', b'0', '2017-05-24 14:50:47', 3, 1, '', 110, 'Yuri Paola', '2017-06-02 18:13:54', b'0', '', b'0'),
(203, 'demoledor master tools mt 90k', 200, 4, '2017-10-07', '2017-06-24', 'Se canceló el interés S/. 48.00 de el día 05/07/2017<br>', 240, 0, 171, b'0', b'0', '2017-05-24 15:04:50', 3, 1, '07/10/2017', 0, '', '', b'0', '', b'0'),
(204, 'pcu i5 + monitor lg 19\"', 200, 4, '2017-09-19', '2017-06-25', '', 240, 0, 149, b'0', b'0', '2017-05-25 16:47:11', 3, 1, '19/09/2017', 0, '', '', b'0', '', b'0'),
(205, 'laptop toshiba i3 + iphone 5c', 400, 4, '2017-05-25', '2017-06-25', '', 480, 0, 172, b'0', b'0', '2017-05-25 17:27:36', 3, 1, '', 512, 'Aumbbel', '2017-07-11 19:28:08', b'0', '', b'0'),
(206, 'tablet chuwi + mouse', 100, 4, '2017-05-25', '2017-06-25', '', 120, 0, 173, b'0', b'0', '2017-05-25 18:59:01', 3, 1, '', 124, '', '2017-07-03 17:27:27', b'0', '', b'0'),
(207, 'tv smart lg 42lb5800', 450, 4, '2017-05-26', '2017-06-26', '', 540, 0, 156, b'0', b'0', '2017-05-26 09:33:03', 3, 1, '', 495, 'Yuri Paola', '2017-05-29 18:08:13', b'0', '', b'0'),
(208, 'saxo hamaha yas 23', 500, 4, '2017-06-23', '2017-06-26', 'Se canceló el interés S/. 80.00 de el día 23/06/2017<br>', 600, 0, 174, b'0', b'0', '2017-05-26 10:29:05', 3, 1, '23/06/2017', 580, 'Yuri Paola', '2017-07-17 09:03:55', b'0', '', b'0'),
(209, 'guitarra electrica freeman', 50, 4, '2017-05-26', '2017-06-26', '', 60, 0, 175, b'0', b'0', '2017-05-26 15:40:53', 3, 1, '', 72, 'Aumbbel', '2017-08-05 13:51:41', b'0', '', b'0'),
(210, 'pc', 500, 4, '2017-05-27', '2017-05-28', '', 550, 0, 54, b'0', b'0', '2017-05-27 09:15:30', 9, 2, '', 550, 'demo', '2017-05-27 09:15:41', b'0', '', b'0'),
(211, 'juego de 5 ollas rena ware', 1000, 4, '2017-05-29', '2017-06-29', '', 1200, 0, 176, b'0', b'0', '2017-05-29 11:56:44', 3, 1, '', 1160, 'Pilar Maria', '2017-06-24 12:07:05', b'0', '', b'0'),
(212, 'estación total leica ts06 plush', 6000, 4, '2017-05-29', '2017-06-29', '', 7200, 0, 102, b'0', b'0', '2017-05-29 18:21:35', 3, 1, '', 6600, '', '2017-06-07 18:41:17', b'0', '', b'0'),
(213, 'pieza d emano de alta luz lez + celular lg k10', 100, 4, '2017-05-30', '2017-06-30', '', 120, 0, 177, b'0', b'0', '2017-05-30 09:26:58', 3, 1, '', 140, 'Aumbbel', '2017-08-05 13:52:15', b'0', '', b'0'),
(214, 'tv lg 32lh500b', 200, 4, '2017-05-30', '2017-06-30', '', 240, 0, 178, b'0', b'0', '2017-05-30 15:41:29', 3, 1, '', 280, 'Aumbbel', '2017-08-05 13:52:52', b'0', '', b'0'),
(215, 'hp todo en uno 20 b403la', 200, 4, '2017-05-30', '2017-06-30', '', 240, 0, 179, b'0', b'0', '2017-05-30 17:18:04', 3, 1, '', 220, 'Yuri Paola', '2017-06-06 17:43:35', b'0', '', b'0'),
(216, 'celular j7', 250, 4, '2017-05-30', '2017-06-30', '', 300, 0, 106, b'0', b'0', '2017-05-30 19:15:08', 3, 1, '', 280, 'Yuri Paola', '2017-06-15 09:43:14', b'0', '', b'0'),
(217, 'ps 3 con 1 mando y cables', 200, 4, '2017-06-02', '2017-07-02', '', 240, 0, 180, b'0', b'0', '2017-06-02 10:18:34', 3, 1, '', 224, 'Yuri Paola', '2017-06-23 18:58:39', b'0', '', b'0'),
(218, 'laptop lenovo b40-80', 300, 4, '2017-06-02', '2017-07-02', '', 360, 0, 68, b'0', b'0', '2017-06-02 13:09:13', 3, 1, '', 360, 'Yuri Paola', '2017-07-05 09:42:23', b'0', '', b'0'),
(219, 'taladro 20v dewalt dcd776-026022', 150, 4, '2017-06-18', '2017-07-02', 'Se canceló el interés S/. 18.00 de el día 18/06/2017<br>', 180, 0, 181, b'0', b'0', '2017-06-02 17:54:37', 3, 1, '18/06/2017', 168, 'Manrique', '2017-06-18 14:12:05', b'0', '', b'0'),
(220, 'calculadora hp g50+audifono sony+dni+celular xperia', 200, 4, '2017-06-02', '2017-07-02', '', 240, 0, 182, b'0', b'0', '2017-06-02 18:11:22', 3, 1, '', 240, '', '2017-07-06 13:23:33', b'0', '', b'0'),
(221, 'celular samsung j5', 180, 4, '2017-06-03', '2017-06-04', '', 198, 0, 183, b'0', b'0', '2017-06-03 11:49:01', 12, 1, '', 198, '', '2017-06-12 16:40:41', b'0', '', b'0'),
(222, 'televisor  lg smart 42\"', 350, 4, '2017-06-03', '2017-06-04', 'CONTROL ', 385, 0, 104, b'0', b'0', '2017-06-03 12:16:19', 12, 1, '', 476, 'Aumbbel', '2017-08-05 13:53:52', b'0', '', b'0'),
(223, 'tv lg 42lb65', 400, 4, '2017-08-07', '2017-07-05', 'Se canceló el interés S/. 80.00 de el día 07/08/2017<br>Se canceló el interés S/. 80.00 de el día 05/07/2017<br>', 480, 0, 184, b'0', b'0', '2017-06-05 10:50:27', 3, 1, '07/08/2017', 496, 'Yuri Paola', '2017-09-12 13:13:36', b'0', '', b'0'),
(224, 'laptop compaq amd', 200, 4, '2017-06-05', '2017-07-05', '', 240, 0, 185, b'0', b'0', '2017-06-05 11:50:28', 3, 1, '', 272, 'Aumbbel', '2017-08-01 00:50:16', b'0', '', b'0'),
(225, 'impresora + maquina de ejercicio', 120, 4, '2017-06-05', '2017-07-05', '', 144, 0, 186, b'0', b'0', '2017-06-05 12:17:00', 3, 1, '', 148.8, 'Yuri Paola', '2017-07-11 10:17:04', b'0', '', b'0'),
(226, 'laptop hp envy dv6 notebook pc', 350, 4, '2017-06-05', '2017-07-05', '', 420, 0, 187, b'0', b'0', '2017-06-05 15:11:28', 3, 1, '', 385, 'Yuri Paola', '2017-06-13 17:35:35', b'0', '', b'0'),
(227, 'celular zte blade l5', 70, 4, '2017-06-05', '2017-07-05', '', 84, 0, 188, b'0', b'0', '2017-06-05 17:22:02', 3, 1, '', 78.4, '', '2017-06-26 17:35:04', b'0', '', b'0'),
(228, 'camara+cargador+estuche', 50, 4, '2017-06-05', '2017-07-05', '', 60, 0, 189, b'0', b'0', '2017-06-05 18:31:09', 3, 1, '', 55, '', '2017-06-19 14:47:26', b'0', '', b'0'),
(229, 'auto kia rio placa w2z-360', 3200, 4, '2017-06-05', '2017-06-19', '', 3520, 0, 190, b'0', b'0', '2017-06-05 18:47:18', 3, 1, '', 3520, 'Yuri Paola', '2017-06-14 18:22:29', b'0', '', b'0'),
(230, 'celular samsung galaxy j2', 150, 4, '2017-06-06', '2017-07-06', '', 180, 0, 112, b'0', b'0', '2017-06-06 14:24:55', 3, 1, '', 174, '', '2017-07-04 18:01:37', b'0', '', b'0'),
(231, 'tv. sony kdl 40w605b', 300, 4, '2017-06-06', '2017-07-06', '', 360, 0, 191, b'0', b'0', '2017-06-06 16:05:21', 3, 1, '', 408, 'Aumbbel', '2017-08-05 13:54:21', b'0', '', b'0'),
(232, 'tv smart lg 42lb5800', 450, 4, '2017-06-07', '2017-07-07', '', 540, 0, 156, b'0', b'0', '2017-06-07 18:04:01', 3, 1, '', 495, '', '2017-06-13 18:30:08', b'0', '', b'0'),
(233, 'estacion total sokkia cx-105', 8000, 4, '2017-05-29', '2017-06-07', '', 8800, 0, 102, b'0', b'0', '2017-06-07 18:47:14', 3, 1, '', 11200, 'Yuri Paola', '2017-08-01 12:07:19', b'0', '', b'0'),
(234, 'gps garmin oregon 650', 450, 4, '2017-06-07', '2017-07-07', '', 540, 0, 102, b'0', b'0', '2017-06-07 18:56:53', 3, 1, '', 594, 'Yuri Paola', '2017-08-01 12:05:57', b'0', '', b'0'),
(235, 'equipo de sonido 7 parlantes panasonic + celular huawei lua u03', 450, 4, '2017-06-08', '2017-07-08', '', 540, 0, 192, b'0', b'0', '2017-06-08 10:45:43', 3, 1, '', 495, 'Yuri Paola', '2017-06-08 11:19:24', b'0', '', b'0'),
(236, 'equipo de sonido 7 parlantes panasonic + celular huawei lua u03', 300, 4, '2017-06-08', '2017-07-08', '', 360, 0, 192, b'0', b'0', '2017-06-08 11:20:13', 3, 1, '', 408, 'Aumbbel', '2017-08-05 13:55:07', b'0', '', b'0'),
(237, 'celular lg x screen', 100, 4, '2017-06-08', '2017-07-08', '', 120, 0, 193, b'0', b'0', '2017-06-08 13:11:20', 3, 1, '', 110, 'Yuri Paola', '2017-06-20 15:44:28', b'0', '', b'0'),
(238, '2 guitarras+ps2 con tres mandos+dos timon+teclado', 300, 4, '2017-10-26', '2017-07-08', 'Se canceló el interés S/. 48.00 de el día 14/09/2017<br>Se canceló el interés S/. 60.00 de el día 17/08/2017<br>Se canceló el interés S/. 36.00 de el día 17/07/2017<br>Se canceló el interés S/. 36.00 de el día 29/06/2017<br>nueva modalidad ', 360, 0, 194, b'0', b'0', '2017-06-08 17:34:34', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(239, 'cpu', 40, 4, '2017-06-09', '2017-06-10', '', 44, 0, 195, b'0', b'0', '2017-06-09 14:01:01', 12, 1, '', 46.4, 'manrique Mendoza', '2017-07-07 13:48:54', b'0', '', b'0'),
(240, 'detector de metales y detector de bullyser', 800, 4, '2017-06-09', '2017-06-10', '', 880, 0, 196, b'0', b'0', '2017-06-09 14:38:29', 12, 1, '', 1184, 'Aumbbel', '2017-08-30 13:11:03', b'0', '', b'0'),
(241, 'celular lg spidit', 60, 4, '2017-06-10', '2017-07-10', '', 72, 0, 197, b'0', b'0', '2017-06-12 09:37:51', 3, 1, '', 66, 'Yuri Paola', '2017-06-22 12:34:58', b'0', '', b'0'),
(242, 'roto martillo percutor kd975ka-b2c', 100, 4, '2017-06-18', '2017-07-10', 'Se canceló el interés S/. 10.00 de el día 18/06/2017<br>', 120, 0, 188, b'0', b'0', '2017-06-12 09:39:53', 3, 1, '18/06/2017', 110, 'Manrique', '2017-06-18 14:11:23', b'0', '', b'0'),
(243, 'moto negro smaach125', 400, 4, '2017-06-10', '2017-07-10', '', 480, 0, 198, b'0', b'0', '2017-06-12 09:51:42', 3, 1, '', 528, 'Yuri Paola', '2017-08-05 11:56:58', b'0', '', b'0'),
(244, 'celular lg k8', 80, 4, '2017-06-12', '2017-07-12', '', 96, 0, 197, b'0', b'0', '2017-06-12 10:00:16', 3, 1, '', 88, '', '2017-06-22 12:34:23', b'0', '', b'0');
INSERT INTO `producto` (`idProducto`, `prodNombre`, `prodMontoEntregado`, `prodInteres`, `prodFechaInicial`, `prodFechaVencimiento`, `prodObservaciones`, `prodMontoPagar`, `prodAdelanto`, `idCliente`, `prodDioAdelanto`, `prodActivo`, `prodFechaRegistro`, `idUsuario`, `idSucursal`, `prodUltimaFechaInteres`, `prodCuantoFinaliza`, `prodQuienFinaliza`, `prodFechaFinaliza`, `prodAprobado`, `prodQuienAprueba`, `esCompra`) VALUES
(245, 'carro kia rio rojo', 4100, 4, '2017-06-12', '2017-06-19', '', 4510, 0, 190, b'0', b'0', '2017-06-12 18:52:55', 3, 1, '', 4510, 'Yuri Paola', '2017-06-14 18:23:07', b'0', '', b'0'),
(246, 'tablet ipad md369e/a', 170, 4, '2017-06-13', '2017-07-07', '', 197.2, 0, 199, b'0', b'0', '2017-06-13 10:43:05', 3, 1, '', 187, 'Yuri Paola', '2017-06-27 10:25:43', b'0', '', b'0'),
(247, 'celular huawei vns l23', 150, 4, '2017-06-13', '2017-07-13', '', 180, 0, 200, b'0', b'0', '2017-06-13 11:02:18', 3, 1, '', 165, 'Yuri Paola', '2017-06-14 10:44:05', b'0', '', b'0'),
(248, 'guitarra acústica + celular azumi', 50, 4, '2017-10-07', '2017-07-13', 'Se canceló el interés S/. 10.00 de el día 17/07/2017<br>', 60, 0, 201, b'0', b'0', '2017-06-13 16:25:50', 3, 1, '07/10/2017', 0, '', '', b'0', '', b'0'),
(249, 'tv smart lg 42lb5800', 450, 4, '2017-06-13', '2017-07-13', '', 540, 0, 156, b'0', b'0', '2017-06-13 18:31:21', 3, 1, '', 495, 'Yuri Paola', '2017-06-16 17:43:31', b'0', '', b'0'),
(251, 'estacion total topcon cygnus ks-102', 4000, 4, '2017-06-14', '2017-07-14', '', 4800, 0, 202, b'0', b'0', '2017-06-14 11:37:24', 3, 1, '', 5120, 'Yuri Paola', '2017-08-01 12:08:16', b'0', '', b'0'),
(252, 'monitor aoc e960s', 50, 4, '2017-06-14', '2017-07-14', '', 60, 0, 195, b'0', b'0', '2017-06-14 17:06:36', 3, 1, '', 58, 'manrique Mendoza', '2017-07-07 13:47:41', b'0', '', b'0'),
(253, 'tv lg 49uh6030', 600, 4, '2017-07-10', '2017-07-15', 'Se canceló el interés S/. 96.00 de el día 10/07/2017<br>', 720, 0, 106, b'0', b'0', '2017-06-15 09:44:33', 3, 1, '10/07/2017', 672, 'Yuri Paola', '2017-07-31 09:47:03', b'0', '', b'0'),
(254, 'laptop samsung basico', 150, 4, '2017-06-15', '2017-07-15', '', 180, 0, 203, b'0', b'0', '2017-06-15 11:27:05', 3, 1, '', 165, 'Yuri Paola', '2017-06-19 12:46:24', b'0', '', b'0'),
(255, 'moto rojo con blanco sunshine', 600, 4, '2017-06-15', '2017-07-15', '', 720, 0, 204, b'0', b'0', '2017-06-15 12:41:47', 3, 1, '', 696, 'Yuri Paola', '2017-07-12 13:24:10', b'0', '', b'0'),
(256, 'laptop hp basico', 228, 4, '2017-07-22', '2017-07-15', 'Se adelantó S/. 72.00 de S/. 300.00 el día 22/07/2017<br>', 360, 72, 205, b'0', b'0', '2017-06-15 12:50:49', 3, 1, '', 0, '', '', b'0', '', b'0'),
(257, 'laptop toshiba corei3 4ta generacion', 330, 4, '2017-06-15', '2017-07-15', 'MALETIN Y CALCULADORA Y CARGADOR', 396, 0, 206, b'0', b'0', '2017-06-15 17:08:38', 13, 1, '', 363, '', '2017-06-27 14:45:02', b'0', '', b'0'),
(258, 'televisor lg 49uh65', 400, 4, '2017-06-16', '2017-07-16', '', 480, 0, 207, b'0', b'0', '2017-06-16 09:52:43', 3, 1, '', 448, 'manrique Mendoza', '2017-07-07 17:51:40', b'0', '', b'0'),
(259, 'laptop hp amd a8 con cargador', 300, 4, '2017-06-16', '2017-07-16', 'estuche hello kitty ', 360, 0, 146, b'0', b'0', '2017-06-16 10:42:30', 3, 1, '', 384, 'Aumbbel', '2017-08-01 01:26:00', b'0', '', b'0'),
(260, 'tv 3d smart samsung 40 6400', 400, 4, '2017-09-19', '2017-07-16', '', 480, 0, 48, b'0', b'0', '2017-06-16 11:06:37', 3, 1, '19/09/2017', 0, '', '', b'0', '', b'0'),
(261, 'celular moto x play', 150, 4, '2017-06-16', '2017-07-16', '', 180, 0, 208, b'0', b'0', '2017-06-16 11:24:23', 3, 1, '', 174, '', '2017-07-10 15:28:41', b'0', '', b'0'),
(262, 'celular iphone 7 en caja', 500, 4, '2017-06-16', '2017-07-16', '', 600, 0, 209, b'0', b'0', '2017-06-16 17:14:49', 3, 1, '', 550, '', '2017-06-26 18:21:50', b'0', '', b'0'),
(263, 'tv lg 29\" + celar sony xperia', 400, 4, '2017-06-19', '2017-07-19', '', 480, 0, 210, b'0', b'0', '2017-06-19 19:31:54', 3, 1, '', 464, 'Yuri Paola', '2017-07-12 09:51:25', b'0', '', b'0'),
(264, 'tv sony mas control', 350, 4, '2017-06-19', '2017-08-19', 'tv sony mas control', 476, 0, 211, b'0', b'0', '2017-06-19 20:18:15', 13, 1, '', 406, 'Yuri Paola', '2017-07-13 16:02:19', b'0', '', b'0'),
(265, 'tv smart lg 42lb5800', 450, 4, '2017-06-28', '2017-07-20', 'Se canceló el interés S/. 45.00 de el día 28/06/2017<br>', 540, 0, 156, b'0', b'0', '2017-06-20 10:26:49', 3, 1, '28/06/2017', 495, 'Yuri Paola', '2017-06-28 18:42:26', b'0', '', b'0'),
(266, 'taladro bauker', 30, 4, '2017-06-21', '2017-07-21', '', 36, 0, 212, b'0', b'0', '2017-06-21 10:55:53', 3, 1, '', 42, 'Aumbbel', '2017-08-30 13:30:08', b'0', '', b'0'),
(267, 'cd antiguo', 150, 4, '2017-06-21', '2017-06-22', '', 165, 0, 54, b'0', b'0', '2017-06-21 14:06:52', 9, 2, '', 165, 'demo', '2017-06-21 14:17:37', b'0', '', b'0'),
(268, 'cd antiguo', 136, 4, '2017-06-21', '2017-06-22', 'Se canceló el interés S/. 0.00 de el día 21/06/2017<br>Se canceló el interés S/. 0.00 de el día 21/06/2017<br>Se canceló el interés S/. 13.60 de el día 21/06/2017<br>Se canceló el interés S/. 0.00 de el día 21/06/2017<br>Se canceló el interés S/. 0.00 de el día 21/06/2017<br>Se canceló el interés S/. 0.00 de el día 21/06/2017<br>Se canceló el interés S/. 13.60 de el día 21/06/2017<br>Se canceló el interés S/. 13.60 de el día 21/06/2017<br>Se adelantó S/. 14.00 de S/. 150.00 el día 21/06/2017<br>', 165, 14, 54, b'0', b'0', '2017-06-21 14:29:41', 9, 2, '21/06/2017', 136, 'demo', '2017-06-21 17:46:47', b'0', '', b'0'),
(269, 'producto de prueba', 55, 4, '2017-06-21', '2017-06-22', '', 60.5, 0, 54, b'0', b'0', '2017-06-21 14:42:05', 9, 2, '', 60.5, 'demo', '2017-06-21 15:11:27', b'0', '', b'0'),
(270, 'prueba', 20, 4, '2017-06-21', '2017-06-22', 'Se adelantó S/. 4.50 de S/. 24.50 el día 21/06/2017<br>Se adelantó S/. 6.50 de S/. 31.00 el día 21/06/2017<br>Se adelantó S/. 4.00 de S/. 35.00 el día 21/06/2017<br>Se adelantó S/. 9.00 de S/. 44.00 el día 21/06/2017<br>Se adelantó S/. 8.00 de S/. 52.00 el día 21/06/2017<br>Se adelantó S/. 8.00 de S/. 60.00 el día 21/06/2017<br>Se adelantó S/. 9.00 de S/. 69.00 el día 21/06/2017<br>Se adelantó S/. 1.00 de S/. 70.00 el día 21/06/2017<br>Se adelantó S/. 5.00 de S/. 75.00 el día 21/06/2017<br>Se adelantó S/. 5.00 de S/. 80.00 el día 21/06/2017<br>Se adelantó S/. 5.00 de S/. 85.00 el día 21/06/2017<br>Se adelantó S/. 15.00 de S/. 100.00 el día 21/06/2017<br>Se adelantó S/. 9.00 de S/. 109.00 el día 21/06/2017<br>Se canceló el interés S/. 10.90 de el día 21/06/2017<br>Se canceló el interés S/. 11.00 de el día 21/06/2017<br>Se canceló el interés S/. 11.00 de el día 21/06/2017<br>', 121, 89, 54, b'0', b'0', '2017-06-21 16:55:04', 9, 2, '21/06/2017', 20, 'demo', '2017-06-21 17:43:35', b'0', '', b'0'),
(271, 'libro nuevo', 15, 4, '2017-06-21', '2017-06-22', '', 16.5, 0, 54, b'0', b'0', '2017-06-21 17:48:24', 9, 2, '', 16.5, 'demo', '2017-06-21 17:48:29', b'0', '', b'0'),
(272, 'moto  advance', 400, 4, '2017-06-22', '2017-07-22', '', 480, 0, 213, b'0', b'0', '2017-06-22 10:27:58', 3, 1, '', 464, 'Yuri Paola', '2017-07-14 13:48:46', b'0', '', b'0'),
(273, 'motocicleta hero passion pro tr', 800, 4, '2017-07-20', '2017-07-22', 'Se canceló el interés S/. 128.00 de el día 20/07/2017<br>', 960, 0, 214, b'0', b'0', '2017-06-22 13:36:39', 3, 1, '20/07/2017', 896, 'Yuri Paola', '2017-08-08 11:17:06', b'0', '', b'0'),
(274, 'laptop hp envy dv6 notebook pc', 400, 4, '2017-06-23', '2017-07-23', '', 480, 0, 187, b'0', b'0', '2017-06-23 15:19:30', 3, 1, '', 0, '', '', b'0', '', b'0'),
(275, 'cpu+teclado+monitor samsung', 90, 4, '2017-06-23', '2017-07-23', '', 108, 0, 215, b'0', b'0', '2017-06-23 16:23:03', 3, 1, '', 0, '', '', b'0', '', b'0'),
(276, 'todo en uno lenovo amd e540', 400, 4, '2017-08-28', '2017-07-23', 'Se canceló el interés S/. 80.00 de el día 28/09/2017<br>Se canceló el interés S/. 80.00 de el día 28/08/2017<br>Se canceló el interés S/. 80.00 de el día 24/07/2017<br>', 480, 0, 216, b'0', b'0', '2017-06-23 16:44:51', 3, 1, '28/08/2017', 0, '', '', b'0', '', b'0'),
(277, 'celular sony xperia', 80, 4, '2017-06-23', '2017-07-23', '', 96, 0, 182, b'0', b'0', '2017-06-23 18:35:02', 3, 1, '', 0, '', '', b'0', '', b'0'),
(278, 'agregar a las estaciones', 500, 4, '2017-06-23', '2017-07-23', '', 600, 0, 202, b'0', b'0', '2017-06-23 19:26:31', 3, 1, '', 620, 'Yuri Paola', '2017-08-01 12:07:58', b'0', '', b'0'),
(279, 'tablet', 200, 4, '2017-06-23', '2017-06-30', 'TABLET MAS CARGADOR', 220, 0, 217, b'0', b'0', '2017-06-24 09:58:09', 13, 1, '', 220, 'Yuri Paola', '2017-07-04 17:01:09', b'0', '', b'0'),
(280, 'laptop toshiba corei7', 600, 4, '2017-06-23', '2017-07-23', 'MOCHILA CARGADOR MOUSE LAPTOP', 720, 0, 218, b'0', b'0', '2017-06-24 10:01:19', 13, 1, '', 672, 'Yuri Paola', '2017-07-14 10:39:25', b'0', '', b'0'),
(281, 'laptop toshiba', 200, 4, '2017-09-26', '2017-06-25', 'Se canceló el interés S/. 40.00 de el día 22/08/2017<br>Se canceló el interés S/. 32.00 de el día 21/07/2017<br>CON CARGADOR', 220, 0, 219, b'0', b'0', '2017-06-24 11:47:31', 12, 1, '26/09/2017', 0, '', '', b'0', '', b'0'),
(282, 'televisor lg 47ln5400', 300, 4, '2017-06-26', '2017-07-26', '', 360, 0, 156, b'0', b'0', '2017-06-26 09:51:12', 3, 1, '', 330, 'Yuri Paola', '2017-06-28 18:42:47', b'0', '', b'0'),
(283, 'dewalt dcb107', 100, 4, '2017-06-26', '2017-07-26', '', 120, 0, 188, b'0', b'0', '2017-06-26 17:40:20', 3, 1, '', 120, 'Yuri Paola', '2017-07-31 21:10:58', b'0', '', b'0'),
(284, 'tablet samsung', 150, 4, '2017-10-26', '2017-07-24', 'tablet mas CargadorRecogió ', 180, 0, 170, b'0', b'0', '2017-06-26 20:08:10', 13, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(285, 'laptop toshiba', 70, 4, '2017-06-24', '2017-07-24', 'TOSHIBA AMD', 84, 0, 220, b'0', b'0', '2017-06-26 20:10:39', 13, 1, '', 81.2, 'Yuri Paola', '2017-07-18 13:46:40', b'0', '', b'0'),
(286, 'auto toyora yaris', 10000, 4, '2017-07-25', '2017-07-27', 'Se canceló el interés S/. 1600.00 de el día 25/07/2017<br>', 12000, 0, 221, b'0', b'0', '2017-06-27 13:25:31', 3, 1, '25/07/2017', 11000, 'Aumbbel', '2017-07-31 23:53:21', b'0', '', b'0'),
(287, 'celular lg x style', 100, 4, '2017-06-28', '2017-07-28', '', 120, 0, 209, b'0', b'0', '2017-06-28 16:09:58', 3, 1, '', 110, 'Pilar Maria', '2017-07-01 11:07:47', b'0', '', b'0'),
(288, 'moto rojo con blanco sunshine', 600, 4, '2017-07-12', '2017-07-28', 'Se canceló el interés S/. 60.00 de el día 12/07/2017<br>', 720, 0, 204, b'0', b'0', '2017-06-28 17:44:34', 3, 1, '12/07/2017', 660, '', '2017-07-21 12:54:06', b'0', '', b'0'),
(289, 'samsung celular j7', 250, 4, '2017-10-26', '2017-07-30', 'solo celularno hay', 300, 0, 222, b'0', b'0', '2017-06-30 15:48:40', 13, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(290, 'toshiba corei5 satelitall', 210, 4, '2017-06-30', '2017-07-30', 'laptop mas cargador', 252, 0, 223, b'0', b'0', '2017-06-30 15:50:59', 13, 1, '', 235.2, 'Yuri Paola', '2017-07-20 12:13:02', b'0', '', b'0'),
(291, 'equipo de sonido sony ss wgr88p', 250, 4, '2017-06-30', '2017-07-30', '', 300, 0, 224, b'0', b'0', '2017-06-30 17:31:11', 3, 1, '', 300, 'Yuri Paola', '2017-07-31 15:08:43', b'0', '', b'0'),
(292, 'moto bajaj pulsar + celular sony xperia', 1300, 4, '2017-06-30', '2017-07-30', '', 1560, 0, 225, b'0', b'0', '2017-06-30 18:18:37', 3, 1, '', 1430, 'Beatriz', '2017-07-05 19:16:07', b'0', '', b'0'),
(293, 'equipo de odontologia', 2000, 4, '2017-09-23', '2017-07-15', 'Se canceló el interés S/. 240.00 de el día 30/06/2017<br>', 2400, 0, 226, b'0', b'0', '2017-06-30 19:37:53', 3, 1, '23/09/2017', 0, '', '', b'0', '', b'0'),
(294, 'laptop advance', 250, 4, '2017-07-01', '2017-07-02', 'CARGADOR ', 275, 0, 227, b'0', b'0', '2017-07-01 13:39:02', 12, 1, '', 275, 'Yuri Paola', '2017-07-03 15:49:01', b'0', '', b'0'),
(295, 'taladro radio celular lg', 220, 4, '2017-10-26', '2017-07-02', 'no hay', 242, 0, 228, b'0', b'0', '2017-07-01 14:23:38', 12, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(296, 'televisor lg 42lb5610 + microondas ms1140s', 350, 4, '2017-07-03', '2017-08-03', '', 420, 0, 229, b'0', b'0', '2017-07-03 10:57:34', 3, 1, '', 406, 'Beatriz', '2017-07-27 12:48:47', b'0', '', b'0'),
(297, 'celular g4', 150, 4, '2017-10-26', '2017-08-03', 'vendido', 180, 0, 230, b'0', b'0', '2017-07-03 14:41:54', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(298, 'nivele + 2 radios', 600, 4, '2017-06-09', '2017-07-09', '', 720, 0, 102, b'0', b'0', '2017-07-03 16:40:03', 3, 1, '', 792, 'Yuri Paola', '2017-08-01 12:06:29', b'0', '', b'0'),
(299, 'bombones', 443, 4, '2017-07-03', '2017-07-04', 'Se canceló el interés S/. 44.30 de el día 03/07/2017<br>', 487.3, 0, 54, b'0', b'0', '2017-07-03 19:18:29', 9, 2, '03/07/2017', 443, 'demo', '2017-07-03 19:19:27', b'0', '', b'0'),
(300, 'tv smart lg 42lb5800 + televisor lg 47ln5400', 750, 4, '2017-07-04', '2017-08-04', '', 900, 0, 156, b'0', b'0', '2017-07-04 09:58:39', 3, 1, '', 840, '', '2017-07-21 17:29:36', b'0', '', b'0'),
(301, 'laptop vaio hp color plomo con cargador', 200, 4, '2017-07-04', '2017-08-04', '', 240, 0, 231, b'0', b'0', '2017-07-04 11:54:25', 3, 1, '', 232, 'Beatriz', '2017-07-27 17:16:53', b'0', '', b'0'),
(302, 'smartwacht , camara digital samsung', 60, 4, '2017-07-01', '2017-08-01', '', 72, 0, 232, b'0', b'0', '2017-07-04 12:20:12', 13, 1, '', 66, 'Beatriz', '2017-07-15 17:36:08', b'0', '', b'0'),
(303, 'moto lineal pulsar  5251-1w', 1300, 4, '2017-07-01', '2017-08-01', 'docs y moto casco', 1560, 0, 233, b'0', b'0', '2017-07-04 12:23:30', 13, 1, '', 1664, '', '2017-08-14 15:17:34', b'0', '', b'0'),
(304, 'celular samsung galaxy j2', 150, 4, '2017-09-26', '2017-08-04', '', 180, 0, 234, b'0', b'0', '2017-07-04 18:12:36', 3, 1, '26/09/2017', 0, '', '', b'0', '', b'0'),
(305, 'licuadora rena ware', 700, 4, '2017-08-04', '2017-08-05', 'Se canceló el interés S/. 140.00 de el día 04/08/2017<br>', 840, 0, 235, b'0', b'1', '2017-07-05 17:43:32', 3, 1, '04/08/2017', 0, '', '', b'0', '', b'0'),
(306, 'piano yamaha', 200, 4, '2017-07-05', '2017-08-05', 'piano costal', 240, 0, 236, b'0', b'0', '2017-07-05 21:09:13', 13, 1, '', 288, 'Beatriz', '2017-09-14 21:40:23', b'0', '', b'0'),
(307, 'plancha thomas + moto3g', 230, 4, '2017-07-05', '2017-08-05', '', 276, 0, 237, b'0', b'0', '2017-07-05 21:11:58', 13, 1, '', 312.8, 'Aumbbel', '2017-09-05 17:19:05', b'0', '', b'0'),
(308, 'celular de prueba', 500, 4, '2017-06-23', '2017-07-08', '', 560, 0, 54, b'0', b'0', '2017-07-07 12:09:14', 9, 2, '', 560, 'demo', '2017-07-12 17:05:58', b'0', '', b'0'),
(309, 'laptop hp 240 g1', 104.4, 4, '2017-10-26', '2017-08-07', 'Dejó la laptop como pago del interés generado por sus 2 Artículos empeñados anteriormente ( monitor aoC E960 S/.48 y cpu micronics S/. 56.4 )no hay', 125.28, 0, 238, b'0', b'0', '2017-07-07 14:07:54', 14, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(310, 'guitarra eléctrica jt 690 -fr', 280, 4, '2017-07-08', '2017-07-09', '', 308, 0, 121, b'0', b'0', '2017-07-08 12:33:01', 12, 1, '', 358.4, 'Yuri Paola', '2017-08-25 17:31:45', b'0', '', b'0'),
(311, 'home theater bh5140s, bluray lg bp140, orbitrek, abcoaster', 450, 4, '2017-07-21', '2017-08-10', 'Se canceló el interés S/. 45.00 de el día 21/07/2017<br>', 540, 0, 156, b'0', b'0', '2017-07-10 10:39:28', 3, 1, '21/07/2017', 450, 'Yuri Paola', '2017-07-21 17:30:34', b'0', '', b'0'),
(312, 'taladro bauker id550e3 kit 50', 40, 4, '2017-09-26', '2017-08-10', '', 48, 0, 239, b'0', b'0', '2017-07-10 10:49:27', 3, 1, '26/09/2017', 0, '', '', b'0', '', b'0'),
(313, 'chocolatera renaware, filtro renaware, 2 licuadoras, una cocina repsol de mesa.', 1000, 4, '2017-07-11', '2017-08-11', 'Solo al 10%', 1200, 0, 240, b'0', b'0', '2017-07-11 11:24:29', 8, 3, '', 1280, 'Aumbbel', '2017-08-25 09:29:11', b'0', '', b'0'),
(314, 'guitarra electrica vozzex expresate', 80, 4, '2017-07-12', '2017-08-12', '', 96, 0, 241, b'0', b'0', '2017-07-12 14:39:33', 3, 1, '', 88, 'Beatriz', '2017-07-24 10:44:34', b'0', '', b'0'),
(315, 'celulares (sony c5,samdung j5) + extractora rena ware', 500, 4, '2017-07-12', '2017-08-12', '', 600, 0, 191, b'0', b'0', '2017-07-12 16:32:33', 3, 1, '', 600, 'Yuri Paola', '2017-08-16 10:02:48', b'0', '', b'0'),
(316, 'camara', 150, 4, '2017-09-20', '2017-07-13', '', 165, 0, 54, b'0', b'0', '2017-06-12 17:05:02', 9, 2, '20/09/2017', 165, 'demo', '2017-07-12 17:05:12', b'0', '', b'0'),
(317, 'celular demo', 120, 4, '2017-07-12', '2017-07-13', '', 132, 0, 54, b'0', b'0', '2017-07-12 18:45:48', 9, 2, '', 132, 'demo', '2017-07-12 18:45:57', b'0', '', b'0'),
(318, 'laptop toshiba i3 con calculadora en maleta', 400, 4, '2017-07-14', '2017-08-14', '', 480, 0, 206, b'0', b'0', '2017-07-14 15:04:12', 3, 1, '', 448, '', '2017-08-02 15:46:14', b'0', '', b'0'),
(319, '4 perfumes', 70, 4, '2017-09-14', '2017-07-16', 'Se canceló el interés S/. 25.20 de el día 14/09/2017<br>', 77, 0, 242, b'0', b'1', '2017-07-15 11:28:41', 12, 1, '14/09/2017', 0, '', '', b'0', '', b'0'),
(320, 'moto advance', 500, 4, '2017-07-16', '2017-08-17', '', 600, 0, 213, b'0', b'0', '2017-07-17 11:58:58', 13, 1, '', 620, 'Yuri Paola', '2017-08-21 10:36:37', b'0', '', b'0'),
(321, 'pcu+monitor+teclado+mouse+ 2 cables', 200, 4, '2017-06-07', '2017-07-07', '', 240, 0, 188, b'0', b'0', '2017-07-17 20:10:07', 3, 1, '', 248, 'Yuri Paola', '2017-07-17 20:10:18', b'0', '', b'0'),
(322, 'cpu antryx', 100, 4, '2017-10-30', '2017-08-18', 'Se canceló el interés S/. 36.00 de el día 14/09/2017<br>', 120, 0, 242, b'0', b'1', '2017-07-18 19:35:23', 3, 1, '30/10/2017', 0, '', '', b'0', '', b'0'),
(323, 'laptop hp', 300, 4, '2017-07-19', '2017-08-19', '', 360, 0, 47, b'0', b'0', '2017-07-19 10:35:59', 3, 1, '', 360, 'Aumbbel', '2017-08-19 20:04:32', b'0', '', b'0'),
(324, 'celular s6 edge', 200, 4, '2017-07-19', '2017-08-19', '', 240, 0, 243, b'0', b'0', '2017-07-19 17:52:26', 3, 1, '', 240, 'Aumbbel', '2017-08-19 20:05:21', b'0', '', b'0'),
(325, 'licuadora oster classic+dni', 40, 4, '2017-07-19', '2017-08-19', '', 48, 0, 244, b'0', b'0', '2017-07-19 18:09:41', 3, 1, '', 51.2, 'Aumbbel', '2017-09-05 19:17:10', b'0', '', b'0'),
(326, 'bluray samsung + multitester', 70, 4, '2017-10-07', '2017-08-20', '', 84, 0, 245, b'0', b'0', '2017-07-20 18:27:51', 3, 1, '07/10/2017', 0, '', '', b'0', '', b'0'),
(327, 'moto scuter pleasure', 400, 4, '2017-07-20', '2017-08-20', '', 480, 0, 246, b'0', b'0', '2017-07-20 18:51:17', 3, 1, '', 440, 'Yuri Paola', '2017-08-03 18:59:33', b'0', '', b'0'),
(328, 'skil+bauker+dremel3000', 130, 4, '2017-10-07', '2017-08-21', '', 156, 0, 247, b'0', b'0', '2017-07-21 13:30:52', 3, 1, '07/10/2017', 0, '', '', b'0', '', b'0'),
(329, 'laptop hp', 120, 4, '2017-10-26', '2017-07-23', 'CON CARGADOR no hay', 132, 0, 248, b'0', b'0', '2017-07-22 12:48:51', 12, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(330, 'celular', 200, 4, '2017-07-22', '2017-07-23', '', 220, 0, 54, b'0', b'0', '2017-07-22 13:51:53', 9, 2, '', 264, '', '2017-09-12 12:52:49', b'0', '', b'0'),
(331, 'celular huawei cun-l03', 80, 4, '2017-07-25', '2017-08-25', '', 96, 0, 59, b'0', b'0', '2017-07-25 10:55:41', 3, 1, '', 88, 'Yuri Paola', '2017-08-01 13:37:12', b'0', '', b'0'),
(332, 'equipo lg dos parlantes', 150, 4, '2017-07-25', '2017-08-25', '', 180, 0, 249, b'0', b'0', '2017-07-25 12:38:31', 3, 1, '', 198, 'Yuri Paola', '2017-09-14 11:28:53', b'0', '', b'0'),
(333, 'olla arrocera imaco + microondas lg  + bluray philips', 150, 4, '2017-07-28', '2017-08-28', '', 180, 0, 250, b'0', b'0', '2017-07-28 11:00:37', 3, 1, '', 165, 'Pilar Maria', '2017-08-05 14:25:38', b'0', '', b'0'),
(334, 'parlante sony', 200, 4, '2017-07-28', '2017-08-28', '', 240, 0, 251, b'0', b'0', '2017-07-28 11:28:13', 3, 1, '', 232, 'Yuri Paola', '2017-08-22 18:31:59', b'0', '', b'0'),
(335, 'celular v10- lg h901', 250, 4, '2017-07-28', '2017-08-28', '', 300, 0, 252, b'0', b'0', '2017-07-28 16:03:03', 3, 1, '', 275, 'Yuri Paola', '2017-07-31 19:30:55', b'0', '', b'0'),
(336, 'laptop advance i3', 250, 4, '2017-07-28', '2017-08-28', '', 300, 0, 227, b'0', b'0', '2017-07-28 18:09:42', 3, 1, '', 275, 'Yuri Paola', '2017-08-02 12:39:37', b'0', '', b'0'),
(337, 'celular moto g play', 50, 4, '2017-07-27', '2017-08-27', '', 60, 0, 253, b'0', b'0', '2017-07-29 11:03:31', 13, 1, '', 55, 'Yuri Paola', '2017-08-02 11:12:18', b'0', '', b'0'),
(338, 'tv lg 42 pulgadas smartv', 380, 4, '2017-09-26', '2017-08-27', '', 456, 0, 254, b'0', b'0', '2017-07-29 11:04:44', 13, 1, '26/09/2017', 0, '', '', b'0', '', b'0'),
(339, 'equipo de sonido', 180, 4, '2017-07-27', '2017-08-27', '', 216, 0, 255, b'0', b'0', '2017-07-29 11:09:32', 13, 1, '', 223.2, 'Yuri Paola', '2017-09-05 15:50:00', b'0', '', b'0'),
(340, 'laptop sony, reloj casio, iphone 4, smartwacht', 1000, 4, '2017-07-27', '2017-08-27', '', 1200, 0, 256, b'0', b'0', '2017-07-29 11:12:43', 13, 1, '', 1100, 'Yuri Paola', '2017-08-02 11:07:29', b'0', '', b'0'),
(341, 'laptop', 200, 4, '2017-10-26', '2017-08-26', 'no hay', 240, 0, 257, b'0', b'0', '2017-07-29 11:16:28', 13, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(342, 'camara canon', 40, 4, '2017-10-14', '2017-08-28', 'Se canceló el interés S/. 8.00 de el día 28/08/2017<br>', 48, 0, 15, b'0', b'1', '2017-07-29 11:18:55', 13, 1, '14/10/2017', 0, '', '', b'0', '', b'0'),
(343, 'microondas oster 20litros', 80, 4, '2017-10-07', '2017-08-27', '', 96, 0, 16, b'0', b'0', '2017-07-29 11:26:20', 13, 1, '07/10/2017', 0, '', '', b'0', '', b'0'),
(344, 'equipo sony 2parlantes', 300, 4, '2017-07-25', '2017-08-25', '', 360, 0, 250, b'0', b'0', '2017-07-29 11:33:15', 13, 1, '', 330, 'Pilar Maria', '2017-08-05 14:25:52', b'0', '', b'0'),
(345, 'camioneta toyota pick up- hilux año 2008', 15000, 4, '2017-07-31', '2017-08-31', '', 18000, 0, 258, b'0', b'0', '2017-07-31 14:05:52', 3, 1, '', 18000, 'Yuri Paola', '2017-08-29 12:47:20', b'0', '', b'0'),
(346, 'camara sony cyber shot dsc w730 + camara vucal', 150, 4, '2017-09-14', '2017-09-01', 'Se canceló el interés S/. 42.00 de el día 14/09/2017<br>', 180, 0, 259, b'0', b'1', '2017-08-01 17:45:39', 3, 1, '14/09/2017', 0, '', '', b'0', '', b'0'),
(347, 'laptop toshiba satellite l745 i3', 150, 4, '2017-08-02', '2017-09-02', 'sin cargador', 180, 0, 251, b'0', b'0', '2017-08-02 12:21:30', 3, 1, '', 168, 'Yuri Paola', '2017-08-22 18:31:45', b'0', '', b'0'),
(348, 'laptop toshiba skullcandy i5', 150, 4, '2017-08-02', '2017-09-02', 'pantalla rajada', 180, 0, 260, b'0', b'0', '2017-08-02 17:02:07', 3, 1, '', 186, 'Aumbbel', '2017-09-13 18:19:00', b'0', '', b'0'),
(349, 'celular sony xperia z3 + celular sony xperia e4', 170, 4, '2017-10-26', '2017-09-02', 'no hay', 204, 0, 261, b'0', b'0', '2017-08-02 19:14:08', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(350, 'laptop lenovo g50-45 con cargador', 400, 4, '2017-10-26', '2017-09-03', 'no hay', 480, 0, 262, b'0', b'0', '2017-08-03 16:38:57', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(351, 'moto yamaha yzf-r15', 2000, 4, '2017-08-03', '2017-09-03', '', 2400, 0, 118, b'0', b'0', '2017-08-03 18:08:52', 3, 1, '', 2240, '', '2017-08-24 12:50:13', b'0', '', b'0'),
(352, 'equipo de sonido sony ss wgr88p', 250, 4, '2017-11-09', '2017-09-07', 'Se canceló el interés S/. 50.00 de el día 08/09/2017<br>', 300, 0, 224, b'0', b'0', '2017-08-07 17:40:34', 3, 1, '09/11/2017', 0, '', '', b'0', '', b'0'),
(353, 'macbook pro i5 2015 - c02pc ay7fvh3', 988, 4, '2017-09-14', '2017-09-07', 'Se adelantó S/. 212.00 de S/. 1200.00 el día 14/09/2017<br>Se canceló el interés S/. 288.00 de el día 14/09/2017<br>', 1440, 212, 263, b'0', b'0', '2017-08-07 17:53:56', 3, 1, '14/09/2017', 1086.8, 'Pilar Maria', '2017-09-16 14:21:58', b'0', '', b'0'),
(354, 'equipo sonido sony 2 parlantes', 300, 4, '2017-08-07', '2017-09-07', '', 360, 0, 250, b'0', b'0', '2017-08-07 18:31:49', 3, 1, '', 330, 'Yuri Paola', '2017-08-17 17:45:07', b'0', '', b'0'),
(355, 'caja china', 500, 4, '2017-10-16', '2017-09-08', '', 600, 0, 264, b'0', b'0', '2017-08-08 15:05:42', 3, 1, '16/10/2017', 0, '', '', b'0', '', b'0'),
(356, 'laptop advance i3', 250, 4, '2017-10-02', '2017-09-08', 'Se canceló el interés S/. 50.00 de el día 12/09/2017<br>', 300, 0, 227, b'0', b'0', '2017-08-08 15:18:49', 3, 1, '02/10/2017', 0, '', '', b'0', '', b'0'),
(357, 'carro chevrolet sail - asq293', 8000, 4, '2017-08-08', '2017-10-08', '', 10880, 0, 265, b'0', b'0', '2017-08-08 15:29:28', 3, 1, '', 9600, '', '2017-09-08 12:13:15', b'0', '', b'0'),
(358, 'proyector epson h719a', 250, 4, '2017-09-29', '2017-09-08', '', 300, 0, 266, b'0', b'0', '2017-08-08 17:45:03', 3, 1, '29/09/2017', 0, '', '', b'0', '', b'0'),
(359, 'laptop sony vaio amd + camara canon', 250, 4, '2017-08-09', '2017-09-09', '', 300, 0, 119, b'0', b'0', '2017-08-09 16:26:11', 3, 1, '', 310, 'Yuri Paola', '2017-09-15 12:50:50', b'0', '', b'0'),
(360, 'extractora oster, batidora imaco', 50, 4, '2017-09-15', '2017-09-10', 'Se canceló el interés S/. 12.00 de el día 15/09/2017<br>', 60, 0, 156, b'0', b'1', '2017-08-10 10:36:23', 3, 1, '15/09/2017', 0, '', '', b'0', '', b'0'),
(361, 'auto nissan versa (plomo)', 5000, 4, '2017-08-11', '2017-09-11', '', 6000, 0, 267, b'0', b'0', '2017-08-11 12:06:58', 3, 1, '', 5500, 'Yuri Paola', '2017-08-15 11:03:13', b'0', '', b'0'),
(362, 'moto scuter jettor smaash 125 color rojo', 400, 4, '2017-10-08', '2017-09-14', '', 480, 0, 198, b'0', b'0', '2017-08-14 15:55:20', 3, 1, '08/10/2017', 0, '', '', b'0', '', b'0'),
(363, 'pistola mas generador', 1000, 4, '2017-08-12', '2017-09-12', '', 1200, 0, 268, b'0', b'0', '2017-08-14 18:22:36', 13, 1, '', 1200, '', '2017-09-11 16:33:49', b'0', '', b'0'),
(364, 'computadora corei3', 600, 4, '2017-10-12', '2017-09-12', 'Se canceló el interés S/. 120.00 de el día 14/09/2017<br>cel 959992364', 720, 0, 115, b'0', b'0', '2017-08-14 18:24:19', 13, 1, '12/10/2017', 0, '', '', b'0', '', b'0'),
(365, 'equipo de sonido samsung + zapatilla nike', 200, 4, '2017-08-15', '2017-09-15', '', 240, 0, 250, b'0', b'0', '2017-08-15 10:13:33', 3, 1, '', 220, '', '2017-08-17 17:44:35', b'0', '', b'0'),
(366, 'parlante + camara panasonic', 80, 4, '2017-10-26', '2017-09-15', 'no hay', 96, 0, 269, b'0', b'0', '2017-08-15 10:52:12', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(367, 'celular eksx x4+stil + reloj', 60, 4, '2017-10-14', '2017-09-15', 'Se canceló el interés S/. 12.00 de el día 13/09/2017<br>', 72, 0, 270, b'0', b'0', '2017-08-15 11:59:05', 3, 1, '14/10/2017', 0, '', '', b'0', '', b'0'),
(368, 'carro chevrolet sail - asq293 (agrego sobre empeño)', 2000, 4, '2017-08-15', '2017-09-15', '', 2400, 0, 265, b'0', b'0', '2017-08-15 12:18:41', 3, 1, '', 2320, '', '2017-09-08 12:13:01', b'0', '', b'0'),
(369, 'auto toyota yaris rojo f8e-518', 5000, 4, '2017-10-25', '2017-09-15', '', 6000, 0, 271, b'0', b'0', '2017-08-15 16:11:33', 3, 1, '25/10/2017', 0, '', '', b'0', '', b'0'),
(370, 'laptop hp 14 ac103la', 250, 4, '2017-10-26', '2017-09-16', 'recogio', 300, 0, 272, b'0', b'0', '2017-08-16 10:13:16', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(371, 'motocard azul 18566d', 1000, 4, '2017-08-16', '2017-09-16', '', 1200, 0, 273, b'0', b'1', '2017-08-16 10:36:17', 3, 1, '', 0, '', '', b'0', '', b'0'),
(372, 'equipo panasonic  sa max 5000', 450, 4, '2017-10-04', '2017-09-16', '', 540, 0, 274, b'0', b'1', '2017-08-16 18:42:56', 3, 1, '04/10/2017', 0, '', '', b'0', '', b'0'),
(373, 'llanta+gata+triangulo+estuche (5 herramientas)', 100, 4, '2017-10-06', '2017-09-17', 'Se canceló el interés S/. 20.00 de el día 18/09/2017<br>', 120, 0, 69, b'0', b'0', '2017-08-17 10:20:23', 3, 1, '06/10/2017', 0, '', '', b'0', '', b'0'),
(374, 'blu ray lg bp250', 50, 4, '2017-08-17', '2017-08-31', '', 55, 0, 275, b'0', b'0', '2017-08-17 11:17:23', 3, 1, '', 56, 'Yuri Paola', '2017-09-01 10:13:56', b'0', '', b'0'),
(375, 'guitarra electrica freeman', 150, 4, '2017-10-26', '2017-09-17', 'Vendido', 180, 0, 276, b'0', b'0', '2017-08-17 12:25:59', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(376, 'laptop hp 14 r002la', 250, 4, '2017-08-17', '2017-09-17', '', 300, 0, 277, b'0', b'0', '2017-08-17 15:50:03', 3, 1, '', 275, 'Yuri Paola', '2017-08-23 15:47:38', b'0', '', b'0'),
(377, 'camara samsung st72', 40, 4, '2017-10-26', '2017-09-17', '', 48, 0, 278, b'0', b'0', '2017-08-17 19:18:29', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(378, 'laptop lenovo g50-45 amd a8', 500, 4, '2017-10-26', '2017-09-18', 'Vendido', 600, 0, 279, b'0', b'0', '2017-08-18 18:01:11', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(379, 'laptop toshiba', 300, 4, '2017-10-02', '2017-09-19', 'Con cargador ', 360, 0, 280, b'0', b'0', '2017-08-19 09:54:07', 12, 1, '02/10/2017', 0, '', '', b'0', '', b'0'),
(380, 'impresora canon', 30, 4, '2017-10-26', '2017-08-20', 'llevo bea', 33, 0, 281, b'0', b'0', '2017-08-19 11:52:03', 12, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(381, 'laptop toshiba satellite l55', 200, 4, '2017-10-26', '2017-09-21', 'vendido', 240, 0, 282, b'0', b'0', '2017-08-21 16:35:37', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(382, 'revolver ranger cal 38', 200, 4, '2017-10-24', '2017-09-21', '', 600, 0, 283, b'0', b'0', '2017-08-21 16:39:58', 3, 1, '24/10/2017', 0, '', '', b'0', '', b'0'),
(383, 'celular iphone 6s', 500, 4, '2017-09-04', '2017-09-21', 'Se canceló el interés S/. 50.00 de el día 04/09/2017<br>', 600, 0, 284, b'0', b'1', '2017-08-21 16:53:39', 3, 1, '04/09/2017', 0, '', '', b'0', '', b'0'),
(384, 'moto pulsar negro 3700-8w', 700, 4, '2017-08-21', '2017-09-21', '', 840, 0, 285, b'0', b'0', '2017-08-21 17:35:54', 3, 1, '', 770, 'Beatriz', '2017-08-22 13:40:13', b'0', '', b'0'),
(385, 'lavadora samsung', 250, 4, '2017-08-21', '2017-09-21', '', 300, 0, 250, b'0', b'0', '2017-08-21 19:07:43', 3, 1, '', 280, 'Aumbbel', '2017-09-06 21:14:11', b'0', '', b'0'),
(386, 'laptop lenovo core i 7', 850, 4, '2017-08-21', '2017-09-21', '', 1020, 0, 286, b'0', b'0', '2017-08-21 19:14:23', 3, 1, '', 986, '', '2017-09-12 09:54:44', b'0', '', b'0'),
(387, 'laptop lenovo core i 7', 850, 4, '2017-08-21', '2017-09-21', '', 1020, 0, 287, b'0', b'0', '2017-08-21 19:14:27', 3, 1, '', 952, 'Aumbbel', '2017-09-06 19:06:18', b'0', '', b'0'),
(388, 'laptop i5 hp', 450, 4, '2017-08-17', '2017-09-17', '', 540, 0, 288, b'0', b'0', '2017-08-22 10:19:04', 3, 1, '', 495, 'Yuri Paola', '2017-08-24 12:02:40', b'0', '', b'0'),
(389, '23 piezas en rena ware(ollas)', 3000, 4, '2017-10-16', '2017-09-23', '', 3600, 0, 289, b'0', b'0', '2017-08-22 11:43:13', 3, 1, '16/10/2017', 0, '', '', b'0', '', b'0'),
(390, 'celular j5', 150, 4, '2017-10-26', '2017-09-23', 'vendido', 180, 0, 290, b'0', b'0', '2017-08-23 12:43:00', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(391, 'monitor+mini notebook+ 2 tajeta de video+disco duro 1tb+2 lectora interna+bateria para laptop', 750, 4, '2017-10-04', '2017-09-23', '', 900, 0, 291, b'0', b'0', '2017-08-23 18:54:11', 3, 1, '04/10/2017', 0, '', '', b'0', '', b'0'),
(392, 'laptop toshiba basico', 100, 4, '2017-08-24', '2017-09-24', '', 120, 0, 292, b'0', b'0', '2017-08-24 10:16:09', 3, 1, '', 110, 'Yuri Paola', '2017-08-28 10:16:10', b'0', '', b'0'),
(393, 'filmadora jvc gy hmz1u', 600, 4, '2017-09-04', '2017-09-24', 'Se canceló el interés S/. 60.00 de el día 04/09/2017<br>', 720, 0, 293, b'0', b'1', '2017-08-24 11:54:18', 3, 1, '04/09/2017', 0, '', '', b'0', '', b'0'),
(394, 'laptop i5 hp', 550, 4, '2017-08-17', '2017-09-17', '', 660, 0, 288, b'0', b'0', '2017-08-24 12:04:23', 3, 1, '', 605, 'Yuri Paola', '2017-08-28 11:38:10', b'0', '', b'0'),
(395, 'lavadora samsung wa15j5730ls', 400, 4, '2017-08-24', '2017-09-24', '', 480, 0, 250, b'0', b'0', '2017-08-24 14:10:18', 3, 1, '', 440, 'Aumbbel', '2017-09-06 21:14:31', b'0', '', b'0'),
(396, 'computador de escritorio completo', 350, 4, '2017-10-26', '2017-09-24', 'vendido', 420, 0, 294, b'0', b'0', '2017-08-24 16:34:48', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(397, 'chocolatera renaware, filtro renaware, 2 licuadoras, una cocina repsol de mesa.', 1000, 4, '2017-09-28', '2017-09-25', 'solo al 10%', 1200, 0, 240, b'0', b'1', '2017-08-25 09:35:04', 8, 1, '28/09/2017', 0, '', '', b'0', '', b'0'),
(398, 'slyn gym body massager', 1000, 4, '2017-08-25', '2017-09-25', '', 1200, 0, 295, b'0', b'1', '2017-08-25 13:14:57', 3, 1, '', 0, '', '', b'0', '', b'0'),
(399, 'laptop asus', 400, 4, '2017-10-26', '2017-09-25', 'no hay', 480, 0, 296, b'0', b'0', '2017-08-25 15:00:09', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(400, 'soldadora sol andina + motosierra husqvarna 365', 1000, 4, '2017-11-09', '2017-09-25', '', 1200, 0, 297, b'0', b'0', '2017-08-25 16:49:25', 3, 1, '09/11/2017', 0, '', '', b'0', '', b'0'),
(401, 'laptop toshiba + celular', 100, 4, '2017-10-26', '2017-09-25', 'venta', 120, 0, 298, b'0', b'0', '2017-08-25 18:57:06', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(402, 'laptop acer aspire e14 i5', 300, 4, '2017-08-25', '2017-09-25', '', 360, 0, 299, b'0', b'0', '2017-08-25 19:00:25', 3, 1, '', 330, 'Yuri Paola', '2017-08-28 11:44:47', b'0', '', b'0'),
(403, 'laptop hp + camara', 300, 4, '2017-10-26', '2017-08-24', 'Se canceló el interés S/. 60.00 de el día 25/08/2017<br>no hay en Almacén ', 360, 0, 300, b'0', b'0', '2017-08-25 19:09:52', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(404, 'laptop hp i3 (bloqueado)', 200, 4, '2017-10-02', '2017-09-25', '', 240, 0, 301, b'0', b'0', '2017-08-25 19:19:29', 3, 1, '02/10/2017', 0, '', '', b'0', '', b'0'),
(405, 'laptop hp 14 r011la i5', 500, 4, '2017-10-02', '2017-09-28', '', 600, 0, 302, b'0', b'1', '2017-08-28 09:46:35', 3, 1, '02/10/2017', 0, '', '', b'0', '', b'0'),
(406, 'camioneta hilux w4w-726', 18000, 4, '2017-08-28', '2017-09-28', '', 21600, 0, 303, b'0', b'1', '2017-08-28 10:01:22', 3, 1, '', 0, '', '', b'0', '', b'0'),
(407, 'saxo prof', 700, 4, '2017-10-26', '2017-09-28', 'se llevo beatriz ', 840, 0, 304, b'0', b'0', '2017-08-28 12:06:25', 13, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(408, 'zapatilla mas microondas', 150, 4, '2017-08-28', '2017-09-28', 'ZAPATILLAS MAS MICROONDAS', 180, 0, 250, b'0', b'0', '2017-08-28 12:09:27', 13, 1, '', 165, 'Aumbbel', '2017-09-06 21:15:13', b'0', '', b'0'),
(409, 'celular j3', 100, 4, '2017-08-29', '2017-09-29', '', 120, 0, 305, b'0', b'0', '2017-08-29 10:51:28', 3, 1, '', 112, 'Yuri Paola', '2017-09-14 15:35:27', b'0', '', b'0'),
(410, 'laptop toshiba l755 sin cargador', 100, 4, '2017-10-27', '2017-09-29', 'Se adelantó S/. 300.00 de S/. 400.00 el día 05/09/2017<br>Se canceló el interés S/. 40.00 de el día 05/09/2017<br>', 480, 300, 306, b'0', b'0', '2017-08-29 10:58:57', 3, 1, '27/10/2017', 0, '', '', b'0', '', b'0'),
(411, 'laptop hp 14', 200, 4, '2017-08-29', '2017-09-29', '', 240, 0, 307, b'0', b'0', '2017-08-29 11:18:14', 3, 1, '', 220, '', '2017-09-12 09:48:56', b'0', '', b'0'),
(412, 'tablet samsung t210', 70, 4, '2017-10-10', '2017-09-29', '', 84, 0, 308, b'0', b'0', '2017-08-29 13:49:46', 3, 1, '10/10/2017', 0, '', '', b'0', '', b'0'),
(413, 'laptop lenovo i3', 300, 4, '2017-09-27', '2017-09-30', '', 360, 0, 309, b'0', b'0', '2017-08-30 10:04:15', 3, 1, '27/09/2017', 0, '', '', b'0', '', b'0'),
(414, 'hp pavilion g4-1071 la notebook pc', 80, 4, '2017-08-30', '2017-09-09', '', 88, 0, 310, b'0', b'0', '2017-08-30 18:51:21', 3, 1, '', 88, '', '2017-09-11 16:40:19', b'0', '', b'0'),
(415, 'objetos odontologicos', 300, 4, '2017-08-31', '2017-09-30', '', 360, 0, 311, b'0', b'0', '2017-08-31 10:48:37', 3, 1, '', 336, 'Yuri Paola', '2017-09-15 10:32:08', b'0', '', b'0'),
(416, 'celular demo', 100, 4, '2017-08-31', '2017-09-01', '', 110, 0, 54, b'0', b'0', '2017-08-31 16:51:37', 9, 2, '', 110, 'demo', '2017-08-31 16:51:51', b'0', '', b'0'),
(417, 'laptop hp con cargador', 900, 4, '2017-10-10', '2017-10-01', '', 1080, 0, 312, b'0', b'0', '2017-09-01 10:40:28', 3, 1, '10/10/2017', 0, '', '', b'0', '', b'0'),
(418, 'laptop lenovo g550', 150, 4, '2017-10-23', '2017-10-01', '', 180, 0, 313, b'0', b'0', '2017-09-01 12:18:25', 3, 1, '23/10/2017', 0, '', '', b'0', '', b'0'),
(419, 'celular lg v10', 300, 4, '2017-09-23', '2017-10-01', '', 360, 0, 252, b'0', b'0', '2017-09-01 16:01:19', 3, 1, '23/09/2017', 0, '', '', b'0', '', b'0'),
(420, 'bajo electroco+ llanta', 700, 4, '2017-09-26', '2017-09-02', '', 770, 0, 314, b'0', b'0', '2017-09-01 18:11:15', 3, 1, '26/09/2017', 770, '', '2017-09-08 17:34:49', b'0', '', b'0'),
(421, 'laptop toshhiba,pistola, generador electrico', 1500, 4, '2017-10-27', '2017-09-03', 'laptop sin cargador autorizado papa ya habia pagado 390', 1650, 0, 268, b'0', b'0', '2017-09-02 12:54:57', 12, 1, '27/10/2017', 0, '', '', b'0', '', b'0'),
(422, 'tv smart lg 42lb5800', 450, 4, '2017-09-04', '2017-09-05', '', 495, 0, 156, b'0', b'0', '2017-09-04 10:26:13', 3, 1, '', 495, 'Beatriz', '2017-09-15 19:04:58', b'0', '', b'0'),
(423, 'moto yamaha 2757-4b sin casco', 1000, 4, '2017-10-26', '2017-09-05', 'recogio', 1100, 0, 315, b'0', b'0', '2017-09-04 15:43:19', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(424, 'guitarra prs + pedal pod hd 500x', 1000, 4, '2017-11-04', '2017-09-05', '', 1100, 0, 316, b'0', b'0', '2017-09-04 17:43:38', 3, 1, '04/11/2017', 0, '', '', b'0', '', b'0'),
(683, 'estacion total mas otros equipos', 4600, 4, '2017-10-27', '2017-10-28', 'Aumbbel aprobó cobrarle 16% mensual', 4600, 0, 457, b'0', b'1', '2017-10-27 17:17:07', 13, 1, '', 0, '', '', b'0', '', b'0'),
(426, 'Trimoto kamax ka250w-e', 1500, 4, '2017-11-07', '2017-09-05', '', 1650, 0, 299, b'0', b'1', '2017-09-04 18:53:34', 3, 1, '07/11/2017', 0, '', '', b'0', '', b'0'),
(427, 'bluray philips wifi + bluray lg', 150, 4, '2017-09-05', '2017-09-06', '', 165, 0, 156, b'0', b'0', '2017-09-05 12:54:50', 3, 1, '', 165, 'Beatriz', '2017-09-15 19:53:25', b'0', '', b'0'),
(428, 'laptop hp i5 - 15 bs032la', 500, 4, '2017-09-05', '2017-09-06', '', 550, 0, 318, b'0', b'0', '2017-09-05 15:08:06', 3, 1, '', 550, '', '2017-09-09 12:22:06', b'0', '', b'0'),
(429, 'ipod 16gb 5ta generacion', 100, 4, '2017-09-05', '2017-09-06', '', 110, 0, 319, b'0', b'0', '2017-09-05 17:39:07', 13, 1, '', 110, 'Aumbbel', '2017-09-13 13:56:43', b'0', '', b'0'),
(673, 'casaca anaranjada + zapatillas adidas azul', 70, 4, '2017-11-07', '2017-10-26', 'no fue ingresadovendido', 70, 0, 453, b'0', b'0', '2017-09-19 17:25:01', 3, 1, '07/11/2017', 0, '', '', b'0', '', b'0'),
(442, 'celular k10 + celular huawei', 500, 4, '2017-09-05', '2017-09-06', '', 550, 0, 326, b'0', b'0', '2017-09-05 19:45:17', 3, 1, '', 550, '', '2017-09-08 12:12:40', b'0', '', b'0'),
(433, 'laptop hp corei5', 350, 4, '2017-08-17', '2017-09-06', '', 616, 0, 288, b'0', b'0', '2017-08-17 17:46:13', 13, 1, '', 406, 'Yuri Paola', '2017-09-13 13:33:16', b'0', '', b'0'),
(434, 'laptop hp negra', 900, 4, '2017-09-26', '2017-09-06', '', 990, 0, 312, b'0', b'0', '2017-09-05 17:48:54', 13, 1, '26/09/2017', 0, '', '', b'0', '', b'0'),
(435, 'laptop reloj adidas', 250, 4, '2017-08-31', '2017-09-06', '', 275, 0, 251, b'0', b'0', '2017-09-05 17:50:51', 13, 1, '', 275, '', '2017-09-11 19:26:41', b'0', '', b'0'),
(437, 'laptop hp pavilion amd a8', 210, 4, '2017-10-11', '2017-09-06', 'Se adelantó S/. 190.00 de S/. 400.00 el día 18/09/2017<br>Se canceló el interés S/. 80.00 de el día 18/09/2017<br>', 448, 190, 321, b'0', b'0', '2017-08-19 17:57:16', 13, 1, '11/10/2017', 0, '', '', b'0', '', b'0'),
(438, 'celuyular j2', 240, 4, '2017-10-26', '2017-09-06', 'vendido', 268.8, 0, 322, b'0', b'0', '2017-08-19 18:01:17', 13, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(439, 'calculadora programable', 100, 4, '2017-08-23', '2017-09-06', '', 110, 0, 323, b'0', b'0', '2017-09-05 18:16:52', 13, 1, '', 112, '', '2017-09-12 12:41:58', b'0', '', b'0'),
(440, 'laptop hp corei3', 350, 4, '2017-10-26', '2017-09-06', 'no hay en almacen', 392, 0, 324, b'0', b'0', '2017-09-05 18:20:35', 13, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(441, 'camara canon negro', 500, 4, '2017-08-11', '2017-09-06', 'se revisara en la hoja ', 580, 0, 325, b'0', b'0', '2017-08-11 18:23:31', 13, 1, '', 600, 'Aumbbel', '2017-09-13 18:43:54', b'0', '', b'0'),
(443, 'tv samsung 32\" j4300', 350, 4, '2017-09-07', '2017-09-08', '', 385, 0, 327, b'0', b'0', '2017-09-07 09:39:27', 3, 1, '', 385, '', '2017-09-08 09:38:57', b'0', '', b'0'),
(444, 'celular p9 lite', 150, 4, '2017-10-10', '2017-09-08', '', 165, 0, 328, b'0', b'0', '2017-09-07 09:47:16', 3, 1, '10/10/2017', 0, '', '', b'0', '', b'0'),
(445, 'tv lg 42lb5610 + bluray philips', 450, 4, '2017-09-07', '2017-09-08', '', 495, 0, 250, b'0', b'0', '2017-09-07 09:56:01', 3, 1, '', 495, 'Yuri Paola', '2017-09-13 11:25:26', b'0', '', b'0'),
(446, 'laptop toshiba marron i5', 500, 4, '2017-11-09', '2017-09-08', 'usando en oficina2', 550, 0, 329, b'0', b'0', '2017-09-06 11:17:17', 3, 1, '09/11/2017', 0, '', '', b'0', '', b'0'),
(447, 'lavadora lg t1265tp', 300, 4, '2017-10-10', '2017-09-09', '', 330, 0, 332, b'0', b'1', '2017-09-08 13:43:24', 3, 1, '10/10/2017', 0, '', '', b'0', '', b'0'),
(448, 'celular j7', 200, 4, '2017-09-08', '2017-09-09', '', 220, 0, 250, b'0', b'0', '2017-09-08 16:20:56', 3, 1, '', 220, 'Yuri Paola', '2017-09-13 11:26:14', b'0', '', b'0'),
(449, 'celular huawei', 300, 4, '2017-09-08', '2017-09-09', 'con cargador', 330, 0, 333, b'0', b'1', '2017-09-08 17:39:37', 13, 1, '', 0, '', '', b'0', '', b'0'),
(452, 'celular huawei p9 lite + celular samsung s6 edge', 400, 4, '2017-11-02', '2017-09-09', 'vendidos 690 - 350', 440, 0, 334, b'0', b'0', '2017-09-08 18:51:20', 3, 1, '02/11/2017', 0, '', '', b'0', '', b'0'),
(451, 'camara nikon + filmadora canon', 600, 0, '2017-10-27', '0000-00-00', 'compramos', 0, 0, 331, b'0', b'0', '2017-09-07 12:35:02', 3, 1, '27/10/2017', 0, '', '', b'0', '', b'1'),
(453, 'tv smart samsumg', 280, 4, '2017-09-09', '2017-09-10', '', 308, 0, 335, b'0', b'0', '2017-09-09 12:17:49', 12, 1, '', 308, 'Yuri Paola', '2017-09-13 15:22:02', b'0', '', b'0'),
(454, 'laptop hp i5-15 bs0321a', 650, 4, '2017-11-02', '2017-09-10', 'recogio', 715, 0, 318, b'0', b'0', '2017-09-09 12:23:01', 12, 1, '02/11/2017', 0, '', '', b'0', '', b'0'),
(455, 'auto daewo lanos s.e. d90-023', 1200, 4, '2017-09-09', '2017-09-10', '', 1320, 0, 336, b'0', b'0', '2017-09-09 13:01:16', 12, 1, '', 0, '', '2017-09-09 13:08:41', b'0', '', b'0'),
(456, 'carro daewoo lanos d9q-023', 1300, 4, '2017-09-09', '2017-09-10', '', 1430, 0, 336, b'0', b'1', '2017-09-09 13:09:55', 12, 1, '', 0, '', '', b'0', '', b'0'),
(457, 'ps4 sony uni mando 3 juegos', 500, 4, '2017-11-08', '2017-09-10', 'esta a nombre de dos personas También de chis salas.vendido a 800 soles el 4/11', 550, 0, 337, b'0', b'0', '2017-09-09 16:01:50', 16, 1, '08/11/2017', 0, '', '', b'0', '', b'0'),
(458, 'abcoaster abdominals + impresora epson', 1000, 4, '2017-10-27', '2017-09-10', 'El cliente posee otro credito vigente, se debe verificar que retire tODOS los creditos, con esa condicion se le otorga 1000 soles masautorizado papa', 1100, 0, 268, b'0', b'0', '2017-09-09 16:56:21', 16, 1, '27/10/2017', 0, '', '', b'0', '', b'0'),
(459, 'celular p9 lite smart - nuevo', 400, 4, '2017-09-11', '2017-09-12', '', 440, 0, 333, b'0', b'1', '2017-09-11 09:37:03', 3, 1, '', 0, '', '', b'0', '', b'0'),
(460, 'tv samsung mu6100', 700, 4, '2017-10-05', '2017-09-12', '', 770, 0, 338, b'0', b'0', '2017-09-11 11:47:14', 3, 1, '05/10/2017', 0, '', '', b'0', '', b'0'),
(461, 'laptop tosbiba satellite l755', 400, 0, '2017-09-11', '0000-00-00', '', 0, 0, 150, b'0', b'0', '2017-09-11 14:11:43', 3, 1, '', 440, 'Yuri Paola', '2017-09-13 14:38:23', b'0', '', b'1'),
(462, 'tv lg 32lh30frcon control y cable', 250, 4, '2017-10-04', '2017-09-12', '', 275, 0, 339, b'0', b'0', '2017-09-11 14:23:10', 3, 1, '04/10/2017', 0, '', '', b'0', '', b'0'),
(463, '2 congas con funda-lpy y 1 parche remo', 100, 4, '2017-10-27', '2017-09-12', '', 385, 0, 85, b'0', b'0', '2017-09-11 14:41:56', 3, 1, '27/10/2017', 0, '', '', b'0', '', b'0'),
(464, 'equipo de sonido samsung', 180, 4, '2017-11-03', '2017-09-12', 'VENDIDO', 198, 0, 12, b'0', b'0', '2017-09-11 15:38:18', 3, 1, '03/11/2017', 0, '', '', b'0', '', b'0'),
(465, 'auto mazda 6 w3u 559', 15000, 4, '2017-10-02', '2017-09-12', '', 16500, 0, 340, b'0', b'0', '2017-09-11 16:08:06', 3, 1, '02/10/2017', 0, '', '', b'0', '', b'0'),
(466, 'laptop compac', 80, 4, '2017-09-11', '2017-09-12', '', 88, 0, 341, b'0', b'0', '2017-09-11 20:04:42', 3, 1, '', 88, 'Yuri Paola', '2017-09-14 13:17:23', b'0', '', b'0'),
(467, 'camara fotografica lumix + audifono', 60, 0, '2017-09-26', '0000-00-00', '', 0, 0, 342, b'0', b'0', '2017-07-24 20:18:00', 3, 1, '26/09/2017', 0, '', '', b'0', '', b'1'),
(468, 'taladro bauker', 30, 4, '2017-09-26', '2017-09-12', '', 44.4, 0, 212, b'0', b'0', '2017-06-21 20:19:52', 3, 1, '26/09/2017', 0, '', '', b'0', '', b'0'),
(469, 'celular samsung s5', 100, 4, '2017-09-26', '2017-09-12', '', 132, 0, 343, b'0', b'0', '2017-07-24 20:33:21', 13, 1, '26/09/2017', 0, '', '', b'0', '', b'0'),
(470, 'tv 49\" lg + camara canon', 700, 4, '2017-10-12', '2017-09-13', '', 770, 0, 275, b'0', b'1', '2017-09-12 10:49:00', 3, 1, '12/10/2017', 0, '', '', b'0', '', b'0'),
(471, 'laptop amd', 300, 0, '2017-10-26', '0000-00-00', 'vendido', 0, 0, 344, b'0', b'0', '2017-07-31 11:37:46', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'1'),
(472, 'micro cpu con cargador', 40, 0, '2017-10-26', '0000-00-00', 'vendido bea', 0, 0, 345, b'0', b'0', '2017-09-12 11:43:00', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'1'),
(473, 'laptop i5 thinkpad', 350, 4, '2017-09-27', '2017-09-13', '', 490, 0, 346, b'0', b'0', '2017-07-10 11:45:20', 3, 1, '27/09/2017', 0, '', '', b'0', '', b'0'),
(474, 'celular microsoft lamia 9604', 150, 4, '2017-07-31', '2017-09-13', '', 192, 0, 15, b'0', b'1', '2017-07-31 11:58:17', 3, 1, '', 0, '', '', b'0', '', b'0'),
(475, 'laptop lenovo 250', 1100, 4, '2017-10-07', '2017-09-13', '500 soles de Interés ', 2244, 0, 90, b'0', b'0', '2017-03-17 12:00:32', 3, 1, '07/10/2017', 0, '', '', b'0', '', b'0'),
(476, 'laptop hp i3', 300, 0, '2017-09-26', '0000-00-00', '', 0, 0, 347, b'0', b'0', '2017-06-10 12:37:00', 3, 1, '26/09/2017', 0, '', '', b'0', '', b'1'),
(477, 'articulo prueba', 20, 4, '2017-09-22', '2017-09-13', '', 22, 0, 348, b'0', b'0', '2017-09-12 13:34:36', 9, 2, '22/09/2017', 0, '', '', b'0', '', b'0'),
(478, 'rotomartillo sin marca', 100, 4, '2017-09-12', '2017-09-13', '', 110, 0, 349, b'0', b'1', '2017-09-12 14:26:00', 13, 1, '', 0, '', '', b'0', '', b'0'),
(479, 'ps 4 cuh-1115a un mando', 400, 4, '2017-10-07', '2017-09-13', '', 440, 0, 350, b'0', b'0', '2017-09-12 18:07:59', 3, 1, '07/10/2017', 0, '', '', b'0', '', b'0'),
(480, 'prueba', 12, 4, '2017-09-12', '2017-09-13', '', 13.2, 0, 54, b'0', b'0', '2017-09-12 20:11:25', 9, 2, '', 13.2, 'demo', '2017-09-12 20:11:48', b'0', '', b'0'),
(481, 'tv lg 42lb65', 400, 4, '2017-10-09', '2017-09-14', '', 496, 0, 184, b'0', b'1', '2017-08-07 11:09:06', 3, 1, '09/10/2017', 0, '', '', b'0', '', b'0'),
(482, '2 parlantes micronics neon - spirit', 150, 4, '2017-09-13', '2017-09-14', '', 165, 0, 351, b'0', b'1', '2017-09-13 11:55:26', 3, 1, '', 0, '', '', b'0', '', b'0'),
(564, 'tv samsung 32', 300, 4, '2017-09-27', '2017-09-28', '', 300, 0, 335, b'0', b'1', '2017-09-27 09:43:50', 13, 1, '', 0, '', '', b'0', '', b'0'),
(484, 'laptop toshiba satellite l755', 400, 0, '2017-05-11', '0000-00-00', '', 0, 0, 150, b'0', b'1', '2017-05-11 14:40:14', 3, 1, '', 0, '', '', b'0', '', b'1'),
(485, 'celular huawei cun-u29', 200, 4, '2017-10-26', '2017-09-14', 'Vendido', 220, 0, 352, b'0', b'0', '2017-09-11 18:43:38', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(486, 'microondas lg+horno thomas+extractora thomas', 250, 4, '2017-09-13', '2017-09-14', '', 275, 0, 353, b'0', b'1', '2017-09-13 19:14:33', 3, 1, '', 0, '', '', b'0', '', b'0'),
(487, 'auto chevrolet asq-293', 12000, 4, '2017-09-14', '2017-09-15', '', 13200, 0, 265, b'0', b'1', '2017-09-14 11:27:52', 3, 1, '', 0, '', '', b'0', '', b'0'),
(488, '2 radios + impresora canon', 50, 4, '2017-09-14', '2017-09-15', '', 55, 0, 354, b'0', b'1', '2017-09-14 12:02:40', 3, 1, '', 0, '', '', b'0', '', b'0'),
(489, 'lavadora lg fuzzy ligic 10.5', 400, 4, '2017-11-06', '2017-09-15', 'vendido', 440, 0, 355, b'0', b'0', '2017-09-14 12:29:23', 3, 1, '06/11/2017', 0, '', '', b'0', '', b'0'),
(490, 'taladro 20v dewalt dcd776-026022', 80, 4, '2017-10-17', '2017-09-15', '', 88, 0, 181, b'0', b'0', '2017-09-14 12:38:27', 3, 1, '17/10/2017', 0, '', '', b'0', '', b'0'),
(491, 'celular s7dorazo', 500, 4, '2017-10-12', '2017-09-15', '', 550, 0, 356, b'0', b'0', '2017-09-14 17:59:25', 3, 1, '12/10/2017', 0, '', '', b'0', '', b'0'),
(493, 'laptop  hp probook 450 g4 + proyector epson x36', 2000, 4, '2017-10-27', '2017-09-16', '', 2200, 0, 357, b'0', b'1', '2017-09-15 12:17:30', 3, 1, '27/10/2017', 0, '', '', b'0', '', b'0'),
(494, 'celular lg x', 200, 4, '2017-11-02', '2017-09-16', 'vendido', 220, 0, 351, b'0', b'0', '2017-09-15 15:55:38', 13, 1, '02/11/2017', 0, '', '', b'0', '', b'0'),
(495, 'moto g 64', 400, 4, '2017-10-07', '2017-09-16', 'en caja nuevo', 440, 0, 358, b'0', b'0', '2017-09-15 16:52:32', 13, 1, '07/10/2017', 0, '', '', b'0', '', b'0'),
(496, 'camioneta toyota hilux  2015', 25000, 4, '2017-09-26', '2017-09-16', 'carro soat vencido', 27500, 0, 359, b'0', b'0', '2017-09-15 17:05:04', 13, 1, '26/09/2017', 0, '', '', b'0', '', b'0'),
(497, '20 carteras', 200, 4, '2017-11-03', '2017-09-16', '', 220, 0, 156, b'0', b'1', '2017-09-15 19:48:55', 13, 1, '03/11/2017', 0, '', '', b'0', '', b'0'),
(498, 'tv lg de 42\"', 400, 4, '2017-11-09', '2017-09-17', '', 440, 0, 250, b'0', b'0', '2017-09-16 11:53:43', 12, 1, '09/11/2017', 0, '', '', b'0', '', b'0'),
(499, 'tablet yoga lenovo', 250, 4, '2017-09-16', '2017-09-17', '', 275, 0, 360, b'0', b'1', '2017-09-16 12:33:40', 12, 1, '', 0, '', '', b'0', '', b'0'),
(500, 'laptop toshiba', 550, 4, '2017-10-02', '2017-09-17', '', 605, 0, 361, b'0', b'0', '2017-09-16 12:45:34', 12, 1, '02/10/2017', 0, '', '', b'0', '', b'0'),
(501, 'camara canon', 250, 4, '2017-09-16', '2017-09-17', '', 275, 0, 71, b'0', b'1', '2017-09-16 12:54:26', 12, 1, '', 0, '', '', b'0', '', b'0'),
(502, 'extractora renaware', 450, 4, '2017-09-16', '2017-09-17', '', 495, 0, 362, b'0', b'1', '2017-09-16 16:24:32', 12, 1, '', 0, '', '', b'0', '', b'0'),
(503, 'tv hyundai led tv 32\" y camara canon power shot sx 41015', 600, 4, '2017-09-16', '2017-09-17', '', 660, 0, 363, b'0', b'1', '2017-09-16 18:06:03', 13, 1, '', 0, '', '', b'0', '', b'0'),
(504, 'celular samsung j7', 250, 4, '2017-11-09', '2017-09-19', '', 275, 0, 250, b'0', b'0', '2017-09-18 10:12:35', 3, 1, '09/11/2017', 0, '', '', b'0', '', b'0');
INSERT INTO `producto` (`idProducto`, `prodNombre`, `prodMontoEntregado`, `prodInteres`, `prodFechaInicial`, `prodFechaVencimiento`, `prodObservaciones`, `prodMontoPagar`, `prodAdelanto`, `idCliente`, `prodDioAdelanto`, `prodActivo`, `prodFechaRegistro`, `idUsuario`, `idSucursal`, `prodUltimaFechaInteres`, `prodCuantoFinaliza`, `prodQuienFinaliza`, `prodFechaFinaliza`, `prodAprobado`, `prodQuienAprueba`, `esCompra`) VALUES
(505, 'una motocicleta marca barsha modelo lamger r17 placa 1028-0w', 400, 4, '2017-11-09', '2017-09-19', 'no hay en almacen', 440, 0, 113, b'0', b'0', '2017-09-18 11:17:18', 3, 1, '09/11/2017', 0, '', '', b'0', '', b'0'),
(506, 'lavadora daewoo+minicomponente mx js5500', 800, 4, '2017-09-27', '2017-09-19', '', 880, 0, 364, b'0', b'0', '2017-09-18 12:04:28', 3, 1, '27/09/2017', 0, '', '', b'0', '', b'0'),
(507, 'laptop hp g4 + dni', 120, 4, '2017-11-02', '2017-09-19', 'recogio', 132, 0, 365, b'0', b'0', '2017-09-18 12:21:35', 3, 1, '02/11/2017', 0, '', '', b'0', '', b'0'),
(508, 'celular j3', 150, 4, '2017-09-26', '2017-09-19', '', 165, 0, 305, b'0', b'0', '2017-09-18 12:26:50', 3, 1, '26/09/2017', 0, '', '', b'0', '', b'0'),
(509, 'moto ronco aggressor 200- 0930-0w', 1000, 4, '2017-10-02', '2017-09-19', '', 1100, 0, 366, b'0', b'0', '2017-09-18 00:52:04', 3, 1, '02/10/2017', 0, '', '', b'0', '', b'0'),
(510, 'laptop toshiba i5 3g', 350, 4, '2017-10-26', '2017-09-19', 'recogio', 385, 0, 367, b'0', b'0', '2017-09-18 02:45:47', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(511, 'bluray philips wifi + bluray lg', 200, 4, '2017-09-29', '2017-09-19', '', 220, 0, 156, b'0', b'0', '2017-09-18 03:15:55', 3, 1, '29/09/2017', 0, '', '', b'0', '', b'0'),
(512, 'camara canon eos rebel t7 i', 1000, 4, '2017-11-02', '2017-09-19', 'recogio', 1100, 0, 368, b'0', b'0', '2017-09-18 05:05:44', 3, 1, '02/11/2017', 0, '', '', b'0', '', b'0'),
(513, 'vajilla nueva', 26, 4, '2017-09-22', '2017-09-19', '', 28.6, 0, 54, b'0', b'0', '2017-09-18 23:49:57', 9, 2, '22/09/2017', 0, '', '', b'0', '', b'0'),
(514, 'laptop lenovo g460 con cargador', 150, 4, '2017-09-19', '2017-09-20', '', 165, 0, 369, b'0', b'1', '2017-09-19 13:24:58', 3, 1, '', 0, '', '', b'0', '', b'0'),
(515, 'laptop hp amd a8', 250, 4, '2017-09-29', '2017-09-20', '', 275, 0, 370, b'0', b'0', '2017-09-19 14:13:11', 3, 1, '29/09/2017', 0, '', '', b'0', '', b'0'),
(516, 'tv lg 49uj6510', 1000, 4, '2017-10-14', '2017-09-20', '', 1100, 0, 371, b'0', b'0', '2017-09-19 15:43:26', 3, 1, '14/10/2017', 0, '', '', b'0', '', b'0'),
(517, 'laptop i3 samsung (media pantalla borrasa) + calculadora hp 50g', 350, 4, '2017-10-13', '2017-09-20', '', 385, 0, 372, b'0', b'0', '2017-09-19 17:08:59', 3, 1, '13/10/2017', 0, '', '', b'0', '', b'0'),
(518, 'laptop core i3 intel', 400, 4, '2017-10-26', '2017-09-20', 'ojo:: Nuevo num Celular 966514548recogio', 440, 0, 206, b'0', b'0', '2017-09-19 19:48:11', 8, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(519, 'auto nissan sentra ack-188', 3500, 4, '2017-09-20', '2017-09-21', '', 3850, 0, 373, b'0', b'1', '2017-09-20 09:50:38', 3, 1, '', 0, '', '', b'0', '', b'0'),
(520, 'cpu amd athlon ii', 200, 4, '2017-11-08', '2017-09-21', 'vendido', 220, 0, 374, b'0', b'0', '2017-09-20 11:37:46', 3, 1, '08/11/2017', 0, '', '', b'0', '', b'0'),
(521, 'refrigerador samsung', 600, 4, '2017-10-17', '2017-09-21', '', 660, 0, 375, b'0', b'1', '2017-09-20 11:57:50', 3, 1, '17/10/2017', 0, '', '', b'0', '', b'0'),
(522, 'ps3 con un solo mando y un cable', 100, 4, '2017-11-02', '2017-09-21', 'Recogió 133.1', 110, 0, 180, b'0', b'0', '2017-09-20 16:10:12', 3, 1, '02/11/2017', 0, '', '', b'0', '', b'0'),
(523, 'olla presión rena ware', 300, 4, '2017-10-16', '2017-09-21', '', 330, 0, 376, b'0', b'0', '2017-09-20 16:17:30', 3, 1, '16/10/2017', 0, '', '', b'0', '', b'0'),
(524, 'aspiradora stanley ipx4', 300, 4, '2017-09-20', '2017-09-21', '', 330, 0, 377, b'0', b'1', '2017-09-20 18:58:37', 3, 1, '', 0, '', '', b'0', '', b'0'),
(525, 'armamento girsan t636814y00603', 900, 4, '2017-09-21', '2017-09-22', '', 990, 0, 378, b'0', b'1', '2017-09-21 10:25:37', 3, 1, '', 0, '', '', b'0', '', b'0'),
(526, 'laptop advance 5444', 170, 4, '2017-11-04', '2017-09-22', '', 187, 0, 47, b'0', b'0', '2017-09-21 17:06:32', 3, 1, '04/11/2017', 0, '', '', b'0', '', b'0'),
(527, 'tablet olitec 808 + impresora pixma', 350, 4, '2017-09-21', '2017-09-22', '', 385, 0, 379, b'0', b'1', '2017-09-21 18:02:25', 3, 1, '', 0, '', '', b'0', '', b'0'),
(528, 'celular j7', 250, 4, '2017-11-02', '2017-09-22', 'vendido', 275, 0, 106, b'0', b'0', '2017-09-21 18:36:34', 3, 1, '02/11/2017', 0, '', '', b'0', '', b'0'),
(529, 'audifono mas dni', 20, 4, '2017-09-28', '2017-09-23', '', 22, 0, 380, b'0', b'0', '2017-09-22 09:14:46', 13, 1, '28/09/2017', 0, '', '', b'0', '', b'0'),
(530, 'maletines puma', 50, 4, '2017-11-03', '2017-09-23', '', 55, 0, 156, b'0', b'1', '2017-09-22 10:34:47', 3, 1, '03/11/2017', 0, '', '', b'0', '', b'0'),
(531, 'televisor samsung 5005 40\"', 400, 4, '2017-09-26', '2017-09-23', '', 440, 0, 381, b'0', b'0', '2017-09-22 14:27:27', 3, 1, '26/09/2017', 0, '', '', b'0', '', b'0'),
(532, 'bluray sin control lg bp450', 100, 4, '2017-11-02', '2017-09-23', 'vedido', 110, 0, 382, b'0', b'0', '2017-09-22 16:04:01', 3, 1, '02/11/2017', 0, '', '', b'0', '', b'0'),
(533, 'video camara sony hdr cx405', 300, 4, '2017-11-03', '2017-09-23', 'recogio', 330, 0, 383, b'0', b'0', '2017-09-22 16:31:27', 3, 1, '03/11/2017', 0, '', '', b'0', '', b'0'),
(534, 'canon powershot sx700 hs', 300, 0, '2017-09-22', '0000-00-00', '', 0, 0, 384, b'0', b'1', '2017-09-22 16:51:02', 3, 1, '', 0, '', '', b'0', '', b'1'),
(535, 'teclado viejo', 60, 4, '2017-09-22', '2017-09-23', '', 66, 0, 54, b'0', b'0', '2017-09-22 17:24:00', 9, 2, '22/09/2017', 0, '', '', b'0', '', b'0'),
(536, 'mousepad', 11, 4, '2017-09-22', '2017-09-23', '', 12.1, 0, 54, b'0', b'0', '2017-09-22 18:02:09', 1, 1, '22/09/2017', 0, '', '', b'0', '', b'0'),
(695, '2 blurays', 200, 4, '2017-10-30', '2017-10-31', '', 200, 0, 156, b'0', b'1', '2017-10-30 11:33:49', 13, 1, '', 0, '', '', b'0', '', b'0'),
(538, 'videocamara canon', 400, 4, '2017-11-03', '2017-09-23', 'vendido', 440, 0, 385, b'0', b'0', '2017-09-22 18:42:28', 13, 1, '03/11/2017', 0, '', '', b'0', '', b'0'),
(539, 'laptop hp amd', 200, 4, '2017-09-22', '2017-09-23', '', 220, 0, 386, b'0', b'1', '2017-09-22 19:13:48', 3, 1, '', 0, '', '', b'0', '', b'0'),
(540, 'lavadora samsumg', 450, 4, '2017-11-09', '2017-09-24', '', 450, 0, 250, b'0', b'0', '2017-09-23 14:46:07', 12, 1, '09/11/2017', 0, '', '', b'0', '', b'0'),
(541, 'parlante, reloj y calculadora', 150, 4, '2017-09-23', '2017-09-24', '', 150, 0, 387, b'0', b'1', '2017-09-23 15:16:30', 13, 1, '', 0, '', '', b'0', '', b'0'),
(542, 'laptop hp core3', 349.99, 4, '2017-10-30', '2017-09-24', '', 350, 0, 326, b'0', b'0', '2017-09-23 16:55:54', 13, 1, '30/10/2017', 0, '', '', b'0', '', b'0'),
(543, 'computador de escritorio + equipo sony', 250, 4, '2017-09-28', '2017-09-26', '', 250, 0, 388, b'0', b'0', '2017-09-25 12:04:01', 3, 1, '28/09/2017', 0, '', '', b'0', '', b'0'),
(544, 'delular moto g4', 250, 4, '2017-10-11', '2017-09-26', '', 250, 0, 389, b'0', b'0', '2017-09-25 15:41:12', 3, 1, '11/10/2017', 0, '', '', b'0', '', b'0'),
(545, 'gps garmin 78s', 350, 4, '2017-11-03', '2017-09-26', 'venido', 350, 0, 390, b'0', b'0', '2017-09-25 16:58:50', 3, 1, '03/11/2017', 0, '', '', b'0', '', b'0'),
(546, 'nivel topcon at-b3  + moto bomba honda', 800, 4, '2017-09-25', '2017-09-26', '', 800, 0, 391, b'0', b'1', '2017-09-25 19:24:36', 3, 1, '', 0, '', '', b'0', '', b'0'),
(547, 'televisor lg 42lm6200', 500, 4, '2017-10-30', '2017-09-27', '', 500, 0, 392, b'0', b'1', '2017-09-26 10:35:10', 3, 1, '30/10/2017', 0, '', '', b'0', '', b'0'),
(548, 'televisor lg 49lh5700', 600, 4, '2017-10-30', '2017-09-27', 'recogio', 600, 0, 393, b'0', b'0', '2017-09-26 12:02:29', 3, 1, '30/10/2017', 0, '', '', b'0', '', b'0'),
(549, 'celular modelo antiguo', 100, 4, '2017-09-26', '2017-09-27', '', 100, 0, 54, b'0', b'0', '2017-09-26 16:47:57', 9, 2, '26/09/2017', 0, '', '', b'0', '', b'0'),
(550, 'celular antiguo mod 123', 150, 4, '2017-09-26', '2017-09-27', '', 150, 0, 54, b'0', b'0', '2017-09-26 16:48:36', 9, 2, '26/09/2017', 0, '', '', b'0', '', b'0'),
(551, 'televisor lg 47ln5400', 100, 4, '2017-09-26', '2017-09-27', '', 100, 0, 54, b'0', b'0', '2017-09-26 17:11:42', 9, 2, '26/09/2017', 0, '', '', b'0', '', b'0'),
(552, 'mouse inalambrico', 30, 4, '2017-09-26', '2017-09-27', '', 30, 0, 54, b'0', b'0', '2017-09-26 17:16:26', 9, 2, '26/09/2017', 0, '', '', b'0', '', b'0'),
(553, 'set de camisas', 80, 4, '2017-09-26', '2017-09-27', '', 80, 0, 54, b'0', b'0', '2017-09-26 17:17:18', 9, 2, '26/09/2017', 0, '', '', b'0', '', b'0'),
(554, 'pantalla rota', 80, 4, '2017-09-26', '2017-09-27', '', 80, 0, 54, b'0', b'0', '2017-09-26 17:24:54', 9, 2, '26/09/2017', 0, '', '', b'0', '', b'0'),
(555, 'modulo abc', 80, 4, '2017-09-26', '2017-09-27', '', 80, 0, 54, b'0', b'0', '2017-09-26 17:25:13', 9, 2, '26/09/2017', 0, '', '', b'0', '', b'0'),
(556, 'celular 123', 80, 4, '2017-10-30', '2017-09-27', 'demo', 80, 0, 54, b'0', b'0', '2017-09-26 17:31:11', 3, 1, '30/10/2017', 0, '', '', b'0', '', b'0'),
(557, 'objeto nuevo', 24, 4, '2017-09-26', '2017-09-27', '', 24, 0, 54, b'0', b'0', '2017-09-26 17:38:41', 9, 2, '26/09/2017', 0, '', '', b'0', '', b'0'),
(558, 'producto abc', 100, 4, '2017-09-26', '2017-09-27', '', 100, 0, 54, b'0', b'0', '2017-09-26 17:41:45', 9, 2, '26/09/2017', 0, '', '', b'0', '', b'0'),
(559, 'laptop vieja', 80, 4, '2017-09-26', '2017-09-27', '', 80, 0, 54, b'0', b'0', '2017-09-26 19:24:24', 9, 2, '26/09/2017', 0, '', '', b'0', '', b'0'),
(560, 'equipo 1', 100, 4, '2017-10-30', '2017-09-27', 'demo', 100, 0, 54, b'0', b'0', '2017-09-26 19:39:13', 1, 1, '30/10/2017', 0, '', '', b'0', '', b'0'),
(561, 'bicicleta bianss', 100, 4, '2017-09-26', '2017-09-27', '', 100, 0, 54, b'0', b'0', '2017-09-26 20:23:54', 9, 2, '26/09/2017', 0, '', '', b'0', '', b'0'),
(562, 'caja 1', 50, 4, '2017-09-26', '2017-09-27', '', 50, 0, 54, b'0', b'0', '2017-09-26 20:33:10', 9, 2, '26/09/2017', 0, '', '', b'0', '', b'0'),
(563, 'caja 2', 120.71, 4, '2017-10-16', '2017-09-27', '', 150, 0, 54, b'0', b'0', '2017-09-26 20:59:45', 9, 2, '16/10/2017', 0, '', '', b'0', '', b'0'),
(569, 'laptop hp 14 (plomo)', 200, 4, '2017-10-21', '2017-09-28', '', 200, 0, 307, b'0', b'0', '2017-09-27 17:44:33', 3, 1, '21/10/2017', 0, '', '', b'0', '', b'0'),
(570, 'laptop gateway i5', 350, 0, '2017-09-27', '0000-00-00', '', 0, 0, 396, b'0', b'1', '2017-09-27 17:50:44', 3, 1, '', 0, '', '', b'0', '', b'1'),
(571, 'televisor sony 48\" bravia', 750, 4, '2017-10-26', '2017-09-28', 'no hay en Almacén ', 750, 0, 397, b'0', b'0', '2017-09-27 17:53:18', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(572, 'guitarra nacional acustica', 20, 4, '2017-09-27', '2017-09-28', '', 20, 0, 398, b'0', b'1', '2017-09-27 18:42:27', 3, 1, '', 0, '', '', b'0', '', b'0'),
(573, 'ipad mini - wi-fi + celular - 32gb - negro modelo: md535e/a', 400, 4, '2017-10-26', '2017-09-29', 'no hay en Almacén ', 400, 0, 399, b'0', b'0', '2017-09-28 12:03:31', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(574, 'moto honda hero', 200, 4, '2017-09-30', '2017-09-29', '', 200, 0, 328, b'0', b'0', '2017-09-28 18:18:33', 3, 1, '30/09/2017', 0, '', '', b'0', '', b'0'),
(575, 'camara digital sony syber shot dsc wx80', 50, 4, '2017-10-18', '2017-09-29', '', 50, 0, 380, b'0', b'0', '2017-09-28 19:37:59', 3, 1, '18/10/2017', 0, '', '', b'0', '', b'0'),
(576, 'laptop lenovo b40', 300, 4, '2017-09-29', '2017-09-30', '', 300, 0, 400, b'0', b'1', '2017-09-29 10:03:42', 3, 1, '', 0, '', '', b'0', '', b'0'),
(577, 'motocicleta yamaha fz negro(b4-2306)', 1000, 4, '2017-09-29', '2017-09-30', '', 1000, 0, 110, b'0', b'1', '2017-09-29 10:23:52', 3, 1, '', 0, '', '', b'0', '', b'0'),
(578, 'televisor lg 32lh30', 200, 4, '2017-10-26', '2017-09-30', 'recogio', 200, 0, 5, b'0', b'0', '2017-09-29 10:39:36', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(579, 'cpu pentium4+monitor samsung+teclado', 80, 4, '2017-09-29', '2017-09-30', '', 80, 0, 401, b'0', b'1', '2017-09-29 10:55:52', 3, 1, '', 0, '', '', b'0', '', b'0'),
(580, 'nivel south nl32+tripode+mira', 150, 4, '2017-10-10', '2017-09-30', '', 150, 0, 137, b'0', b'0', '2017-09-29 11:18:08', 3, 1, '10/10/2017', 0, '', '', b'0', '', b'0'),
(581, '23 piezas de bolsos variados', 200, 4, '2017-11-03', '2017-09-30', '', 200, 0, 156, b'0', b'1', '2017-09-29 11:58:11', 3, 1, '03/11/2017', 0, '', '', b'0', '', b'0'),
(582, 'filmadora canon fs 400', 50, 4, '2017-10-21', '2017-09-30', '', 50, 0, 119, b'0', b'0', '2017-09-29 13:44:50', 3, 1, '21/10/2017', 0, '', '', b'0', '', b'0'),
(583, 'celular sony x peria z5', 400, 0, '2017-09-29', '0000-00-00', '', 0, 0, 402, b'0', b'1', '2017-09-29 14:14:58', 3, 1, '', 0, '', '', b'0', '', b'1'),
(584, 'laptop dell i5', 200, 4, '2017-10-26', '2017-09-30', 'recogio', 200, 0, 403, b'0', b'0', '2017-09-29 16:59:25', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(585, 'laptop hp core duo', 200, 4, '2017-10-04', '2017-09-30', '', 200, 0, 156, b'0', b'0', '2017-09-29 19:18:15', 3, 1, '04/10/2017', 0, '', '', b'0', '', b'0'),
(586, 'laptop asus core i5', 800, 4, '2017-09-30', '2017-10-01', '', 800, 0, 404, b'0', b'1', '2017-09-30 10:09:20', 13, 1, '', 0, '', '', b'0', '', b'0'),
(587, 'ipad', 450, 4, '2017-09-30', '2017-10-03', '', 450, 0, 405, b'0', b'1', '2017-09-30 13:39:57', 13, 1, '', 0, '', '', b'0', '', b'0'),
(588, 'calculadora hp mas laptop corei5', 450, 4, '2017-10-25', '2017-10-03', '', 450, 0, 406, b'0', b'0', '2017-09-30 13:41:30', 13, 1, '25/10/2017', 0, '', '', b'0', '', b'0'),
(589, 'auto chevrolet sail alr-596 (negro)', 3000, 4, '2017-10-02', '2017-10-03', '', 3600, 0, 137, b'0', b'1', '2017-10-02 15:16:15', 3, 1, '', 0, '', '', b'0', '', b'0'),
(590, 'cocina eléctrica+plancha+tostadora+radio', 70, 4, '2017-11-04', '2017-10-03', '', 70, 0, 407, b'0', b'0', '2017-10-02 18:24:31', 3, 1, '04/11/2017', 0, '', '', b'0', '', b'0'),
(591, 'prensa de zapatos+comprensor de aire', 550, 4, '2017-10-24', '2017-10-04', '', 550, 0, 408, b'0', b'1', '2017-10-03 10:01:45', 3, 1, '24/10/2017', 0, '', '', b'0', '', b'0'),
(592, 'hidrolavadora karcher 5/17', 800, 4, '2017-10-20', '2017-10-04', '', 800, 0, 409, b'0', b'0', '2017-10-03 10:30:20', 3, 1, '20/10/2017', 0, '', '', b'0', '', b'0'),
(593, 'cpu core i7 halion', 350, 4, '2017-10-14', '2017-10-04', '', 350, 0, 410, b'0', b'0', '2017-10-03 11:15:34', 3, 1, '14/10/2017', 0, '', '', b'0', '', b'0'),
(594, 'amoladora bosch+taladro truper+camara canon+brujula', 200, 4, '2017-11-09', '2017-10-04', 'recogio', 200, 0, 411, b'0', b'0', '2017-10-03 11:57:05', 3, 1, '09/11/2017', 0, '', '', b'0', '', b'0'),
(595, 'play station 4', 500, 4, '2017-10-03', '2017-10-04', '', 500, 0, 190, b'0', b'1', '2017-10-03 13:18:52', 3, 1, '', 0, '', '', b'0', '', b'0'),
(596, 'laptop lenovo corei3', 300, 4, '2017-10-03', '2017-10-04', '', 300, 0, 412, b'0', b'1', '2017-10-03 13:31:22', 13, 1, '', 0, '', '', b'0', '', b'0'),
(597, 'laptop sony vaio', 300, 4, '2017-10-03', '2017-10-04', '', 300, 0, 413, b'0', b'1', '2017-10-03 14:45:28', 13, 1, '', 0, '', '', b'0', '', b'0'),
(598, 'laptop core2duo toshiba', 300, 4, '2017-11-03', '2017-10-04', '', 300, 0, 414, b'0', b'0', '2017-10-03 14:52:27', 13, 1, '03/11/2017', 0, '', '', b'0', '', b'0'),
(599, 'moto susuki gipsa', 1700, 4, '2017-10-16', '2017-10-04', '', 1700, 0, 415, b'0', b'0', '2017-10-03 15:45:07', 13, 1, '16/10/2017', 0, '', '', b'0', '', b'0'),
(600, 'auto toyota corolla - acm-420', 3500, 4, '2017-10-18', '2017-10-04', '', 3500, 0, 416, b'0', b'0', '2017-10-03 16:52:07', 13, 1, '18/10/2017', 0, '', '', b'0', '', b'0'),
(601, 'zapatillas walon+reebok', 40, 4, '2017-10-12', '2017-10-04', '', 40, 0, 417, b'0', b'0', '2017-10-03 19:14:02', 3, 1, '12/10/2017', 0, '', '', b'0', '', b'0'),
(602, 'laptop hp core duo', 200, 4, '2017-10-20', '2017-10-05', '', 200, 0, 156, b'0', b'0', '2017-10-04 09:36:47', 3, 1, '20/10/2017', 0, '', '', b'0', '', b'0'),
(603, 'parlante sony', 250, 4, '2017-10-26', '2017-10-05', 'recogio', 250, 0, 251, b'0', b'0', '2017-10-04 11:31:42', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(604, 'tv smart lg 42lb5800 + bluray philips 3d wifi', 550, 4, '2017-10-06', '2017-10-05', '', 550, 0, 156, b'0', b'0', '2017-10-04 12:48:38', 3, 1, '06/10/2017', 0, '', '', b'0', '', b'0'),
(605, 'tablet samsung', 200, 4, '2017-10-04', '2017-10-05', '', 200, 0, 413, b'0', b'1', '2017-10-04 13:08:16', 13, 1, '', 0, '', '', b'0', '', b'0'),
(606, 'olla rena ware max cooker de 27 lts+sartén grill clásica', 700, 4, '2017-10-04', '2017-10-05', '', 700, 0, 418, b'0', b'1', '2017-10-04 13:39:15', 3, 1, '', 0, '', '', b'0', '', b'0'),
(607, 'celular samsung s8', 550, 4, '2017-11-07', '2017-10-05', 'recogio', 550, 0, 419, b'0', b'0', '2017-10-04 13:56:07', 3, 1, '07/11/2017', 0, '', '', b'0', '', b'0'),
(608, 'laptop asus amd a8 x540y', 400, 4, '2017-10-05', '2017-10-06', '', 400, 0, 420, b'0', b'1', '2017-10-05 12:15:11', 3, 1, '', 0, '', '', b'0', '', b'0'),
(609, 'woo pad11641iwl', 100, 4, '2017-10-05', '2017-10-06', '', 100, 0, 421, b'0', b'1', '2017-10-05 13:39:57', 3, 1, '', 0, '', '', b'0', '', b'0'),
(610, 'laptop asus i3 6g nueva', 600, 4, '2017-10-05', '2017-10-06', '', 600, 0, 420, b'0', b'1', '2017-10-05 14:22:22', 3, 1, '', 0, '', '', b'0', '', b'0'),
(611, 'mezclador dj', 300, 4, '2017-10-07', '2017-10-08', '', 300, 0, 305, b'0', b'1', '2017-10-07 13:53:52', 12, 1, '', 0, '', '', b'0', '', b'0'),
(612, 'guitarra acustica', 100, 4, '2017-10-07', '2017-10-08', '', 100, 0, 422, b'0', b'1', '2017-10-07 16:04:49', 12, 1, '', 0, '', '', b'0', '', b'0'),
(613, 'laptop compaq cq43', 150, 4, '2017-10-09', '2017-10-10', '', 150, 0, 423, b'0', b'1', '2017-10-09 09:54:48', 3, 1, '', 0, '', '', b'0', '', b'0'),
(614, 'tv smart lg 42lb5800', 450, 4, '2017-10-18', '2017-10-10', '', 450, 0, 156, b'0', b'0', '2017-10-09 10:01:46', 3, 1, '18/10/2017', 0, '', '', b'0', '', b'0'),
(615, 'macbook air a1466', 500, 4, '2017-10-24', '2017-10-10', '', 500, 0, 424, b'0', b'0', '2017-10-09 10:20:33', 3, 1, '24/10/2017', 0, '', '', b'0', '', b'0'),
(616, 'computador de escritorio', 200, 4, '2017-10-09', '2017-10-10', '', 200, 0, 425, b'0', b'1', '2017-10-09 10:33:46', 3, 1, '', 0, '', '', b'0', '', b'0'),
(617, 'consola pioneer + guitarra', 600, 4, '2017-11-07', '2017-10-10', '', 600, 0, 121, b'0', b'0', '2017-10-09 12:37:02', 3, 1, '07/11/2017', 0, '', '', b'0', '', b'0'),
(618, 'tv smart tv 32', 231, 4, '2017-10-09', '2017-10-10', '', 231, 0, 426, b'0', b'1', '2017-10-09 14:41:19', 13, 1, '', 0, '', '', b'0', '', b'0'),
(619, 'carro daewoo aumento', 700, 4, '2017-10-10', '2017-10-11', 'se le aumento al carro daewoo', 700, 0, 336, b'0', b'1', '2017-10-10 10:04:31', 13, 1, '', 0, '', '', b'0', '', b'0'),
(620, 'tv lg 47ln5400+bluray philips wifi+bluray lg', 550, 4, '2017-10-20', '2017-10-11', '', 550, 0, 156, b'0', b'0', '2017-10-10 10:21:03', 3, 1, '20/10/2017', 0, '', '', b'0', '', b'0'),
(621, 'laptop', 300, 4, '2017-10-16', '2017-10-11', '', 300, 0, 427, b'0', b'0', '2017-10-10 13:08:47', 9, 2, '16/10/2017', 0, '', '', b'0', '', b'0'),
(622, 'audifonos', 100, 4, '2017-10-10', '2017-10-11', '', 100, 0, 54, b'0', b'0', '2017-10-10 14:01:16', 9, 2, '10/10/2017', 0, '', '', b'0', '', b'0'),
(623, 'audifono', 3000, 4, '2017-10-10', '2017-10-11', '', 3000, 0, 54, b'0', b'0', '2017-10-10 14:02:46', 9, 2, '10/10/2017', 0, '', '', b'0', '', b'0'),
(624, 'celular prueba', 500, 4, '2017-10-16', '2017-10-11', '', 500, 0, 54, b'0', b'0', '2017-10-10 14:11:20', 9, 2, '16/10/2017', 0, '', '', b'0', '', b'0'),
(625, 'laptop sony i3', 350, 4, '2017-11-09', '2017-10-12', '', 350, 0, 250, b'0', b'0', '2017-10-11 09:13:36', 3, 1, '09/11/2017', 0, '', '', b'0', '', b'0'),
(626, 'home theater lg', 180, 4, '2017-10-11', '2017-10-12', '', 180, 0, 428, b'0', b'1', '2017-10-11 12:24:57', 3, 1, '', 0, '', '', b'0', '', b'0'),
(627, 'microondas miray 800w', 40, 4, '2017-10-11', '2017-10-12', '', 40, 0, 412, b'0', b'1', '2017-10-11 18:07:44', 3, 1, '', 0, '', '', b'0', '', b'0'),
(628, 'saxo yamaha', 500, 4, '2017-10-12', '2017-10-13', '', 500, 0, 174, b'0', b'1', '2017-10-12 11:23:55', 13, 1, '', 0, '', '', b'0', '', b'0'),
(629, 'ps 4 con dos mandos y juego', 350, 4, '2017-10-25', '2017-10-13', '', 350, 0, 429, b'0', b'0', '2017-10-12 15:23:37', 3, 1, '25/10/2017', 0, '', '', b'0', '', b'0'),
(631, 'celular huawei p8 lite', 60, 4, '2017-10-12', '2017-10-13', '', 60, 0, 431, b'0', b'1', '2017-10-12 15:56:57', 13, 1, '', 0, '', '', b'0', '', b'0'),
(632, 'auto daewoo bgk-062', 600, 4, '2017-10-17', '2017-10-13', '', 600, 0, 432, b'0', b'0', '2017-10-12 16:02:45', 13, 1, '17/10/2017', 0, '', '', b'0', '', b'0'),
(633, 'iphone 5s + huawei p8 lite', 320, 4, '2017-10-18', '2017-10-14', '', 320, 0, 433, b'0', b'0', '2017-10-13 09:53:05', 3, 1, '18/10/2017', 0, '', '', b'0', '', b'0'),
(634, 'tv samsung curvo de 49\"', 600, 4, '2017-10-14', '2017-10-14', '', 600, 0, 371, b'0', b'0', '2017-10-13 16:06:59', 3, 1, '14/10/2017', 0, '', '', b'0', '', b'0'),
(635, 'extractora rena ware', 700, 4, '2017-10-13', '2017-10-14', '', 700, 0, 434, b'0', b'1', '2017-10-13 16:56:06', 3, 1, '', 0, '', '', b'0', '', b'0'),
(636, 'laptop mcbook', 600, 4, '2017-10-30', '2017-10-14', 'con cargador', 600, 0, 435, b'0', b'0', '2017-10-13 18:13:57', 13, 1, '30/10/2017', 0, '', '', b'0', '', b'0'),
(637, 'laptop hp', 200, 4, '2017-10-23', '2017-10-15', 'la pantalla tiene una raya horizontal', 200, 0, 370, b'0', b'0', '2017-10-14 11:15:45', 12, 1, '23/10/2017', 0, '', '', b'0', '', b'0'),
(638, 'plancha de cabello remington', 30, 4, '2017-10-14', '2017-10-15', 'con adaptador de tomacorriente', 30, 0, 15, b'0', b'1', '2017-10-14 12:06:44', 12, 1, '', 0, '', '', b'0', '', b'0'),
(639, 'laptop advance', 250, 4, '2017-10-14', '2017-10-15', '', 250, 0, 227, b'0', b'1', '2017-10-14 13:03:05', 12, 1, '', 0, '', '', b'0', '', b'0'),
(640, 'bluray sony', 90, 4, '2017-10-14', '2017-10-15', 'control y cable', 90, 0, 436, b'0', b'1', '2017-10-14 16:01:07', 12, 1, '', 0, '', '', b'0', '', b'0'),
(641, 'auto toyota yaris akg-061 (plateado)', 10000, 4, '2017-10-16', '2017-10-17', '', 10000, 0, 415, b'0', b'1', '2017-10-16 09:46:00', 3, 1, '', 0, '', '', b'0', '', b'0'),
(642, 'lenovo corei3', 350, 4, '2017-10-16', '2017-10-17', 'laptop mas cargador', 350, 0, 309, b'0', b'1', '2017-10-16 12:09:49', 13, 1, '', 0, '', '', b'0', '', b'0'),
(643, 'juego de ollas + cocina electrica', 200, 4, '2017-10-16', '2017-10-17', '2 meses quiere', 200, 0, 437, b'0', b'1', '2017-10-16 13:30:47', 13, 1, '', 0, '', '', b'0', '', b'0'),
(644, 'cierra caladora karson js350k', 20, 4, '2017-10-16', '2017-10-17', '', 20, 0, 15, b'0', b'1', '2017-10-16 14:14:41', 3, 1, '', 0, '', '', b'0', '', b'0'),
(645, 'laptop demo', 259.98, 4, '2017-10-16', '2017-10-17', '', 300, 0, 54, b'0', b'0', '2017-10-16 16:41:43', 9, 2, '16/10/2017', 0, '', '', b'0', '', b'0'),
(646, 'juego de ducha+extractora+sandwich', 50, 4, '2017-10-16', '2017-10-17', '', 50, 0, 438, b'0', b'1', '2017-10-16 16:50:27', 3, 1, '', 0, '', '', b'0', '', b'0'),
(647, 'caja prueba', 264.74, 4, '2017-10-16', '2017-10-17', '', 300, 0, 54, b'0', b'0', '2017-10-16 17:51:12', 9, 2, '16/10/2017', 0, '', '', b'0', '', b'0'),
(648, 'prueba 02', 184, 4, '2017-10-16', '2017-10-17', '', 200, 0, 54, b'0', b'0', '2017-10-16 18:24:14', 9, 2, '16/10/2017', 0, '', '', b'0', '', b'0'),
(649, 'carro ford ecosport / aby-143 (blanco)', 1000, 4, '2017-10-26', '2017-10-18', '', 1000, 0, 439, b'0', b'0', '2017-10-17 09:11:13', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(650, 'laptop toshiba i7 satellite s855-s5381', 600, 4, '2017-10-31', '2017-10-18', '', 600, 0, 361, b'0', b'0', '2017-10-17 14:22:29', 3, 1, '31/10/2017', 0, '', '', b'0', '', b'0'),
(651, 'smattv lg 32 pulgadaas', 250, 4, '2017-10-17', '2017-10-18', '', 250, 0, 440, b'0', b'1', '2017-10-17 16:53:14', 13, 1, '', 0, '', '', b'0', '', b'0'),
(652, 'laptop asus  x453m', 150, 4, '2017-10-18', '2017-10-19', '', 150, 0, 441, b'0', b'1', '2017-10-18 13:44:48', 13, 1, '', 0, '', '', b'0', '', b'0'),
(653, 'laptop hp amd a8 mas celular p9', 700, 4, '2017-11-02', '2017-10-19', 'sin cargadores', 700, 0, 442, b'0', b'0', '2017-10-18 14:35:18', 13, 1, '02/11/2017', 0, '', '', b'0', '', b'0'),
(654, 'mcbook corei5', 1200, 4, '2017-10-18', '2017-10-19', '', 1200, 0, 263, b'0', b'1', '2017-10-18 17:37:11', 13, 1, '', 0, '', '', b'0', '', b'0'),
(655, 'asus corei7', 850, 4, '2017-10-18', '2017-10-19', '', 850, 0, 387, b'0', b'1', '2017-10-18 17:53:01', 13, 1, '', 0, '', '', b'0', '', b'0'),
(656, 'mcbook', 1500, 4, '2017-09-19', '2017-10-19', '', 1500, 0, 443, b'0', b'1', '2017-09-19 18:11:54', 13, 1, '', 0, '', '', b'0', '', b'0'),
(657, 'asus corei7', 450, 4, '2017-10-18', '2017-10-19', 'con cargador', 450, 0, 444, b'0', b'1', '2017-10-18 18:26:47', 13, 1, '', 0, '', '', b'0', '', b'0'),
(658, 'moto honda 2014', 1000, 4, '2017-10-18', '2017-10-19', '', 1000, 0, 436, b'0', b'1', '2017-10-18 18:42:49', 13, 1, '', 0, '', '', b'0', '', b'0'),
(659, 'laptop hp i3 2g', 150, 4, '2017-10-20', '2017-10-21', '', 150, 0, 445, b'0', b'1', '2017-10-20 10:17:02', 3, 1, '', 0, '', '', b'0', '', b'0'),
(660, 'celular huawei y5ii', 60, 4, '2017-10-20', '2017-10-21', '', 60, 0, 446, b'0', b'1', '2017-10-20 17:03:34', 3, 1, '', 0, '', '', b'0', '', b'0'),
(661, '6 productos unique', 100, 4, '2017-10-20', '2017-10-21', '', 100, 0, 447, b'0', b'1', '2017-10-20 18:10:33', 3, 1, '', 0, '', '', b'0', '', b'0'),
(662, 'monitor lg flatron w1941s', 50, 4, '2017-10-20', '2017-10-21', '', 50, 0, 448, b'0', b'1', '2017-10-20 19:14:14', 3, 1, '', 0, '', '', b'0', '', b'0'),
(663, 'cortadora de pelo para mascotas wahl km5', 150, 4, '2017-10-21', '2017-10-22', 'Sin Caja * Sin Bol', 150, 0, 449, b'0', b'1', '2017-10-21 10:17:13', 8, 1, '', 0, '', '', b'0', '', b'0'),
(664, 'celular prueba', 300, 4, '2017-10-26', '2017-10-24', '', 300, 0, 54, b'0', b'0', '2017-10-23 12:44:11', 9, 2, '26/10/2017', 0, '', '', b'0', '', b'0'),
(665, 'todo en uno lenovo blanco', 200, 4, '2017-10-23', '2017-10-24', '', 200, 0, 450, b'0', b'1', '2017-10-23 13:10:19', 3, 1, '', 0, '', '', b'0', '', b'0'),
(666, 'laptop hp i5', 250, 4, '2017-10-26', '2017-10-24', 'recogio', 250, 0, 288, b'0', b'0', '2017-10-23 14:34:31', 3, 1, '26/10/2017', 0, '', '', b'0', '', b'0'),
(667, 'bluray philips wifi + bluray lg', 200, 4, '2017-10-27', '2017-10-25', 'recogio', 200, 0, 156, b'0', b'0', '2017-10-24 12:52:05', 3, 1, '27/10/2017', 0, '', '', b'0', '', b'0'),
(668, 'laptop hp core duo', 200, 4, '2017-10-24', '2017-10-25', '', 200, 0, 156, b'0', b'1', '2017-10-24 12:53:22', 3, 1, '', 0, '', '', b'0', '', b'0'),
(669, 'llanta + gata', 150, 4, '2017-09-19', '2017-10-25', '', 150, 0, 69, b'0', b'1', '2017-09-19 13:48:18', 3, 1, '', 0, '', '', b'0', '', b'0'),
(670, 'laptop sony vaio i5 2g', 300, 4, '2017-10-24', '2017-10-25', '', 300, 0, 451, b'0', b'1', '2017-10-24 15:28:13', 3, 1, '', 0, '', '', b'0', '', b'0'),
(671, 'laptop lenovo amd a8', 600, 4, '2017-10-19', '2017-10-25', '', 600, 0, 452, b'0', b'1', '2017-10-19 17:45:16', 3, 1, '', 0, '', '', b'0', '', b'0'),
(672, 'celular moto g4 plus', 250, 4, '2017-10-24', '2017-10-25', '', 250, 0, 389, b'0', b'1', '2017-10-24 18:25:43', 3, 1, '', 0, '', '', b'0', '', b'0'),
(676, 'prueba 2', 22, 4, '2017-10-26', '2017-10-27', 'retiro prueba', 22, 0, 54, b'0', b'0', '2017-10-26 11:32:46', 9, 2, '26/10/2017', 0, '', '', b'0', '', b'0'),
(677, '2 guitarras+ps2 con tres mandos+dos timon+teclado', 300, 4, '2017-10-26', '2017-10-27', 'nueva modalidad ', 300, 0, 194, b'0', b'1', '2017-10-26 12:49:15', 3, 1, '', 0, '', '', b'0', '', b'0'),
(678, 'teodolito cts-berger dgt10', 900, 4, '2017-10-26', '2017-10-27', '', 900, 0, 455, b'0', b'1', '2017-10-26 14:13:12', 3, 1, '', 0, '', '', b'0', '', b'0'),
(679, 'laptop toshiba c45 i5', 350, 4, '2017-11-08', '2017-10-27', '', 350, 0, 456, b'0', b'1', '2017-10-26 14:54:21', 3, 1, '08/11/2017', 0, '', '', b'0', '', b'0'),
(680, 'tv lg 42lb5800', 350, 4, '2017-10-30', '2017-10-27', '', 350, 0, 356, b'0', b'0', '2017-10-26 18:02:34', 3, 1, '30/10/2017', 0, '', '', b'0', '', b'0'),
(681, 'calular sm j7', 200, 4, '2017-10-28', '2017-10-27', '', 200, 0, 222, b'0', b'0', '2017-10-26 18:19:15', 3, 1, '28/10/2017', 0, '', '', b'0', '', b'0'),
(682, 'prueba', 181, 4, '2017-10-27', '2017-10-28', 'prueba aumento<br>', 111, 0, 54, b'0', b'1', '2017-10-27 11:01:39', 9, 2, '', 0, '', '', b'0', '', b'0'),
(684, 'fgwedgdsf', 43, 4, '2017-10-27', '2017-10-28', '', 43, 0, 54, b'0', b'1', '2017-10-27 17:21:59', 1, 1, '', 0, '', '', b'0', '', b'0'),
(685, 'cpu ninja amd a10 r7', 500, 4, '2017-10-27', '2017-10-28', 'sin cables', 500, 0, 458, b'0', b'1', '2017-10-27 18:35:48', 13, 1, '', 0, '', '', b'0', '', b'0'),
(686, 'laptops sony vaio corei3', 250, 4, '2017-10-28', '2017-10-29', 'con cargador ', 250, 0, 459, b'0', b'1', '2017-10-28 10:39:57', 13, 1, '', 0, '', '', b'0', '', b'0'),
(687, 'cortador ceramico mas rasurador electrico', 100, 4, '2017-10-28', '2017-10-29', '', 100, 0, 460, b'0', b'1', '2017-10-28 10:57:07', 13, 1, '', 0, '', '', b'0', '', b'0'),
(694, 'smartv lg 42', 450, 4, '2017-10-30', '2017-10-31', '', 450, 0, 156, b'0', b'1', '2017-10-30 11:28:48', 13, 1, '', 0, '', '', b'0', '', b'0'),
(689, 'rotomartillo pitbull', 150, 4, '2017-10-28', '2017-10-29', '', 150, 0, 461, b'0', b'1', '2017-10-28 11:06:31', 13, 1, '', 0, '', '', b'0', '', b'0'),
(734, 'laptop samsung i3 2g (azul)', 150, 4, '2017-11-09', '2017-11-10', '', 150, 0, 484, b'0', b'1', '2017-11-09 10:24:44', 3, 1, '', 0, '', '', b'0', '', b'0'),
(690, 'rotmartillo stanley', 40, 4, '2017-10-28', '2017-10-31', '', 40, 0, 181, b'0', b'1', '2017-10-28 09:16:24', 13, 1, '', 0, '', '', b'0', '', b'0'),
(691, 'laptop lenovo mas iphone 4', 120, 4, '2017-10-28', '2017-10-31', '', 120, 0, 256, b'0', b'1', '2017-10-28 09:34:43', 13, 1, '', 0, '', '', b'0', '', b'0'),
(696, 'laptop lenovo ideapad s400 touch', 500, 4, '2017-10-30', '2017-10-31', '', 500, 0, 462, b'0', b'1', '2017-10-30 09:57:18', 3, 1, '', 0, '', '', b'0', '', b'0'),
(693, 'laptop lenovo z50', 1100, 4, '2017-03-17', '2017-10-31', '500 soles de Interés ', 1100, 0, 90, b'0', b'1', '2017-03-17 10:09:43', 3, 1, '', 0, '', '', b'0', '', b'0'),
(697, 'guitarra eletrica gibson', 1800, 4, '2017-10-30', '2017-10-31', '', 1800, 0, 463, b'0', b'1', '2017-10-30 12:05:46', 13, 1, '', 0, '', '', b'0', '', b'0'),
(698, 'laptop toshiba i5 3g', 300, 4, '2017-10-30', '2017-10-31', '', 300, 0, 406, b'0', b'1', '2017-10-30 15:31:22', 3, 1, '', 0, '', '', b'0', '', b'0'),
(699, 'cpu + monitor + teclado', 200, 4, '2017-09-29', '2017-10-31', 'beatriz no registro ', 200, 0, 464, b'0', b'1', '2017-09-29 16:17:46', 3, 1, '', 0, '', '', b'0', '', b'0'),
(700, 'macbook air a1466', 700, 4, '2017-10-30', '2017-10-31', '', 700, 0, 424, b'0', b'1', '2017-10-30 17:53:58', 14, 1, '', 0, '', '', b'0', '', b'0'),
(701, 'dvd mas guitarra mas woofer', 60, 4, '2017-10-30', '2017-10-31', '', 60, 0, 465, b'0', b'1', '2017-10-30 18:20:51', 13, 1, '', 0, '', '', b'0', '', b'0'),
(703, 'batidora nueva philips', 60, 4, '2017-10-30', '2017-10-31', '', 60, 0, 466, b'0', b'1', '2017-10-30 18:34:28', 13, 1, '', 0, '', '', b'0', '', b'0'),
(704, 'ipad pro 32gb', 500, 4, '2017-10-30', '2017-10-31', '', 500, 0, 467, b'0', b'1', '2017-10-30 18:40:23', 14, 1, '', 0, '', '', b'0', '', b'0'),
(705, 'auto toyota corolla acm-420 (negro)', 5000, 4, '2017-10-31', '2017-11-01', '', 5000, 0, 416, b'0', b'1', '2017-10-31 10:46:50', 3, 1, '', 0, '', '', b'0', '', b'0'),
(706, 'impresora mas ps vita', 200, 4, '2017-10-31', '2017-11-01', '949380302/986210607', 200, 0, 367, b'0', b'1', '2017-10-31 10:58:40', 3, 1, '', 0, '', '', b'0', '', b'0'),
(707, 'fotocopiadora konica minolta bizhub 215', 500, 4, '2017-10-31', '2017-11-01', '', 500, 0, 251, b'0', b'1', '2017-10-31 13:41:34', 14, 1, '', 0, '', '', b'0', '', b'0'),
(708, 'bicicleta montañera profesional 27.5', 100, 4, '2017-11-03', '2017-11-01', 'recogio', 100, 0, 468, b'0', b'0', '2017-10-31 15:57:24', 3, 1, '03/11/2017', 0, '', '', b'0', '', b'0'),
(709, 'lg k10', 220, 0, '2017-10-31', '0000-00-00', '', 0, 0, 453, b'0', b'1', '2017-10-31 16:11:16', 13, 1, '', 0, '', '', b'0', '', b'1'),
(710, 'ps 3 blanco', 300, 4, '2017-10-31', '2017-11-01', '', 300, 0, 312, b'0', b'1', '2017-10-31 19:27:27', 13, 1, '', 0, '', '', b'0', '', b'0'),
(711, 'calculadora grafica hp50g', 100, 4, '2017-11-02', '2017-11-03', '', 100, 0, 323, b'0', b'1', '2017-11-02 16:36:44', 3, 1, '', 0, '', '', b'0', '', b'0'),
(712, 'tv samsung 4300 32\"', 280, 4, '2017-11-02', '2017-11-03', '', 280, 0, 469, b'0', b'1', '2017-11-02 16:39:46', 3, 1, '', 0, '', '', b'0', '', b'0'),
(713, 'laptop hp g4', 150, 0, '2017-10-20', '0000-00-00', 'falto registrar bea', 0, 0, 470, b'0', b'1', '2017-10-20 17:00:44', 3, 1, '', 0, '', '', b'0', '', b'1'),
(714, 'tv 32\"aoc led', 150, 4, '2017-11-03', '2017-11-04', '', 150, 0, 471, b'0', b'1', '2017-11-03 10:00:58', 3, 1, '', 0, '', '', b'0', '', b'0'),
(715, 'monedas de colección en soles 750', 650, 4, '2017-11-03', '2017-11-04', 'anulado', 650, 0, 472, b'0', b'0', '2017-11-03 12:56:29', 3, 1, '03/11/2017', 0, '', '', b'0', '', b'0'),
(716, 'laptop dell i5', 200, 4, '2017-11-03', '2017-11-04', '', 200, 0, 403, b'0', b'1', '2017-11-03 16:32:30', 3, 1, '', 0, '', '', b'0', '', b'0'),
(717, 'ps 4 con dos mandos', 400, 4, '2017-11-03', '2017-11-04', '', 400, 0, 473, b'0', b'1', '2017-11-03 17:33:05', 3, 1, '', 0, '', '', b'0', '', b'0'),
(718, 'carro ford ecosport / aby-143 (blanco)', 2300, 4, '2017-11-03', '2017-11-04', 'se deposito a bbva<br>', 1800, 0, 439, b'0', b'1', '2017-11-03 18:46:26', 14, 1, '', 0, '', '', b'0', '', b'0'),
(719, 'compra de ropas y zapatos', 260, 4, '2017-11-04', '2017-11-05', '', 260, 0, 474, b'0', b'1', '2017-11-04 10:07:13', 14, 1, '', 0, '', '', b'0', '', b'0'),
(720, 'laptop  toshiba mas dni', 120, 4, '2017-11-04', '2017-11-05', '', 120, 0, 475, b'0', b'1', '2017-11-04 19:32:03', 13, 1, '', 0, '', '', b'0', '', b'0'),
(721, 'bateria electrica alesia', 400, 4, '2017-11-06', '2017-11-07', '', 400, 0, 476, b'0', b'1', '2017-11-06 08:30:34', 13, 1, '', 0, '', '', b'0', '', b'0'),
(722, 'arma mas ab coaster (vendido)', 1000, 4, '2017-11-06', '2017-11-07', 'ab coaster ya fue vendido , pero no se le entrega aun el dinero', 1000, 0, 268, b'0', b'1', '2017-11-06 09:34:34', 13, 1, '', 0, '', '', b'0', '', b'0'),
(723, 'televisor lg 47ln5400', 350, 4, '2017-11-06', '2017-11-07', '', 350, 0, 156, b'0', b'1', '2017-11-06 12:32:04', 3, 1, '', 0, '', '', b'0', '', b'0'),
(724, 'televisor lg 43lh5100', 300, 4, '2017-11-06', '2017-11-07', '', 300, 0, 477, b'0', b'1', '2017-11-06 14:57:07', 3, 1, '', 0, '', '', b'0', '', b'0'),
(725, 'laptop toshiba corei5', 300, 4, '2017-11-07', '2017-11-08', 'con cargador', 300, 0, 223, b'0', b'1', '2017-11-07 11:20:30', 13, 1, '', 0, '', '', b'0', '', b'0'),
(726, 'camara digital nikon coopix p530', 300, 4, '2017-11-07', '2017-11-08', '', 300, 0, 478, b'0', b'1', '2017-11-07 13:49:30', 3, 1, '', 0, '', '', b'0', '', b'0'),
(727, 'tv lg smart 43lh60', 450, 4, '2017-11-07', '2017-11-08', 'sin soporte (patitas)', 450, 0, 479, b'0', b'1', '2017-11-07 18:27:05', 3, 1, '', 0, '', '', b'0', '', b'0'),
(728, '2 guitarras electroacusticas mas celu huawei y5 mas reloj guess', 550, 4, '2017-11-08', '2017-11-09', '', 550, 0, 480, b'0', b'1', '2017-11-08 09:19:13', 13, 1, '', 0, '', '', b'0', '', b'0'),
(729, 'laptop hp con cargador', 900, 4, '2017-11-08', '2017-11-09', '', 900, 0, 312, b'0', b'1', '2017-11-08 10:54:48', 3, 1, '', 0, '', '', b'0', '', b'0'),
(730, 'camara de video canon vixia hf r500', 200, 4, '2017-11-08', '2017-11-09', '', 200, 0, 481, b'0', b'1', '2017-11-08 12:05:35', 3, 1, '', 0, '', '', b'0', '', b'0'),
(731, 'motocicleta artsun as250gz (rojo) 0228-9w', 1200, 4, '2017-11-08', '2017-11-09', '', 1200, 0, 482, b'0', b'1', '2017-11-08 14:05:52', 3, 1, '', 0, '', '', b'0', '', b'0'),
(732, '2 celulares j5 / k10', 250, 4, '2017-11-08', '2017-11-09', '', 250, 0, 388, b'0', b'1', '2017-11-08 17:12:43', 3, 1, '', 0, '', '', b'0', '', b'0'),
(733, 'filmadora video hi8', 40, 4, '2017-11-08', '2017-11-09', '', 40, 0, 483, b'0', b'1', '2017-11-08 18:22:15', 3, 1, '', 0, '', '', b'0', '', b'0'),
(735, 'monitor master g', 70, 4, '2017-11-09', '2017-11-10', '980299851', 70, 0, 315, b'0', b'1', '2017-11-09 13:33:00', 13, 1, '', 0, '', '', b'0', '', b'0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes_producto`
--

CREATE TABLE `reportes_producto` (
  `idReporte` int(11) NOT NULL,
  `idProducto` int(11) NOT NULL,
  `idDetalleReporte` int(11) NOT NULL,
  `repoValorMonetario` float NOT NULL,
  `repoFechaOcurrencia` datetime NOT NULL,
  `repoUsuario` varchar(200) NOT NULL,
  `repoUsuarioComentario` varchar(200) NOT NULL,
  `repoFechaConfirma` varchar(200) NOT NULL,
  `repoQueConfirma` int(11) NOT NULL,
  `repoQuienConfirma` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `reportes_producto`
--

INSERT INTO `reportes_producto` (`idReporte`, `idProducto`, `idDetalleReporte`, `repoValorMonetario`, `repoFechaOcurrencia`, `repoUsuario`, `repoUsuarioComentario`, `repoFechaConfirma`, `repoQueConfirma`, `repoQuienConfirma`) VALUES
(18, 227, 3, 78.4, '2017-06-26 17:35:04', '', '', '2017-06-26 20:04:55', 5, 'Aumbbel'),
(17, 211, 3, 1160, '2017-06-24 12:07:05', 'Pilar Maria', '', '2017-06-26 20:05:10', 5, 'Aumbbel'),
(16, 10, 3, 73.73, '2017-06-24 09:11:27', 'Pilar Maria', '', '2017-06-26 20:05:28', 5, 'Aumbbel'),
(15, 37, 3, 1120, '2017-06-23 19:38:48', 'manrique', '', '2017-06-23 19:39:04', 6, 'manrique'),
(14, 217, 3, 224, '2017-06-23 18:58:39', 'Yuri Paola', '', '2017-06-23 19:36:52', 6, 'manrique'),
(12, 20, 3, 360, '2017-06-23 09:56:14', 'Yuri Paola', '', '2017-06-23 19:36:41', 6, 'manrique'),
(13, 208, 1, 80, '2017-06-23 15:44:34', ' Yuri Paola', '', '2017-06-23 19:37:42', 7, 'manrique'),
(19, 262, 3, 550, '2017-06-26 18:21:50', '', '', '2017-06-26 20:08:26', 5, 'Aumbbel'),
(20, 69, 3, 172, '2017-06-26 20:22:24', 'Aumbbel', '', '2017-06-27 11:30:00', 5, 'Aumbbel'),
(21, 246, 3, 187, '2017-06-27 10:25:43', 'Yuri Paola', '', '2017-06-27 11:10:48', 5, 'Aumbbel'),
(22, 185, 3, 220, '2017-06-27 12:06:53', 'Yuri Paola', '', '2017-06-27 17:28:51', 5, 'Aumbbel'),
(23, 257, 3, 363, '2017-06-27 14:45:02', '', '', '2017-06-27 17:28:54', 5, 'Aumbbel'),
(24, 180, 3, 128, '2017-06-27 16:42:40', '', '', '2017-06-27 17:01:15', 5, 'Aumbbel'),
(25, 265, 1, 45, '2017-06-28 18:41:39', ' ', '', '2017-06-28 20:03:50', 7, 'Aumbbel'),
(26, 265, 3, 495, '2017-06-28 18:42:26', 'Yuri Paola', '', '2017-06-28 20:03:15', 5, 'Aumbbel'),
(27, 282, 3, 330, '2017-06-28 18:42:47', 'Yuri Paola', '', '2017-06-28 20:02:56', 5, 'Aumbbel'),
(28, 123, 3, 103.6, '2017-06-28 20:04:53', 'Yuri Paola', '', '2017-06-28 20:25:30', 6, 'Aumbbel'),
(29, 8, 3, 82.56, '2017-06-28 20:05:24', 'Yuri Paola', '', '2017-06-28 20:25:27', 6, 'Aumbbel'),
(30, 89, 3, 1394, '2017-06-28 20:05:55', 'Yuri Paola', '', '2017-06-28 20:26:25', 6, 'Aumbbel'),
(31, 96, 3, 1760, '2017-06-28 20:06:18', 'Yuri Paola', '', '2017-06-28 20:26:28', 6, 'Aumbbel'),
(32, 103, 3, 312, '2017-06-28 20:07:03', 'Yuri Paola', '', '2017-06-28 20:26:19', 6, 'Aumbbel'),
(33, 108, 3, 312, '2017-06-28 20:07:31', 'Yuri Paola', '', '2017-06-28 20:26:40', 6, 'Aumbbel'),
(34, 113, 3, 456, '2017-06-28 20:07:51', 'Yuri Paola', '', '2017-06-28 20:25:11', 6, 'Aumbbel'),
(35, 126, 3, 222, '2017-06-28 20:08:05', 'Yuri Paola', '', '2017-06-28 20:26:44', 6, 'Aumbbel'),
(36, 131, 3, 2220, '2017-06-28 20:08:55', 'Yuri Paola', '', '2017-06-28 20:24:19', 5, 'Aumbbel'),
(37, 137, 3, 432, '2017-06-28 20:09:58', 'Yuri Paola', '', '2017-06-28 20:25:04', 6, 'Aumbbel'),
(38, 136, 3, 288, '2017-06-28 20:10:19', 'Yuri Paola', '', '2017-06-28 20:24:09', 6, 'Aumbbel'),
(39, 149, 3, 420, '2017-06-28 20:10:41', 'Yuri Paola', '', '2017-06-28 20:24:56', 6, 'Aumbbel'),
(40, 135, 3, 216, '2017-06-28 20:10:59', 'Yuri Paola', '', '2017-06-28 20:26:53', 6, 'Aumbbel'),
(41, 143, 3, 216, '2017-06-28 20:11:32', 'Yuri Paola', '', '2017-06-28 20:24:50', 6, 'Aumbbel'),
(42, 158, 3, 68, '2017-06-28 20:11:48', 'Yuri Paola', '', '2017-06-28 20:23:28', 6, 'Aumbbel'),
(43, 155, 3, 272, '2017-06-28 20:12:42', 'Yuri Paola', '', '2017-06-28 20:24:45', 6, 'Aumbbel'),
(44, 101, 3, 560, '2017-06-28 20:13:50', 'Yuri Paola', '', '2017-06-28 20:26:59', 6, 'Aumbbel'),
(45, 138, 3, 216, '2017-06-28 20:14:49', 'Yuri Paola', '', '2017-06-28 20:27:08', 5, 'Aumbbel'),
(46, 157, 3, 326.4, '2017-06-28 20:15:48', 'Yuri Paola', '', '2017-06-28 20:27:28', 5, 'Aumbbel'),
(47, 156, 3, 408, '2017-06-28 20:16:15', 'Yuri Paola', '', '2017-06-28 20:27:48', 5, 'Aumbbel'),
(48, 181, 3, 448, '2017-06-28 20:17:14', 'Yuri Paola', '', '2017-06-28 20:24:36', 6, 'Aumbbel'),
(49, 173, 3, 512, '2017-06-28 20:18:20', 'Yuri Paola', '', '2017-06-28 20:27:43', 6, 'Aumbbel'),
(50, 195, 3, 186, '2017-06-28 20:32:09', 'Aumbbel', '', '2017-06-28 20:32:16', 5, 'Aumbbel'),
(51, 238, 1, 36, '2017-06-29 15:57:12', ' Yuri Paola', '', '2017-06-30 19:50:45', 7, 'Aumbbel'),
(52, 293, 1, 240, '2017-06-30 19:39:11', ' Yuri Paola', '', '2017-06-30 19:50:50', 7, 'Aumbbel'),
(53, 287, 3, 110, '2017-07-01 11:07:47', 'Pilar Maria', '', '2017-07-03 10:43:35', 5, 'Aumbbel'),
(54, 294, 3, 275, '2017-07-03 15:49:01', 'Yuri Paola', '', '2017-07-05 18:58:05', 5, 'Aumbbel'),
(55, 206, 3, 124, '2017-07-03 17:27:27', '', '', '2017-07-05 18:58:23', 5, 'Aumbbel'),
(58, 279, 3, 220, '2017-07-04 17:01:09', 'Yuri Paola', '', '2017-07-05 19:14:05', 5, 'Aumbbel'),
(59, 230, 3, 174, '2017-07-04 18:01:37', '', '', '2017-07-05 19:13:28', 5, 'Aumbbel'),
(60, 218, 3, 360, '2017-07-05 09:42:23', 'Yuri Paola', '', '2017-07-05 19:14:45', 5, 'Aumbbel'),
(61, 203, 1, 48, '2017-07-05 16:54:25', ' Yuri Paola', '', '2017-07-05 19:15:28', 7, 'Aumbbel'),
(62, 292, 3, 1430, '2017-07-05 19:16:07', 'Beatriz', '', '2017-07-05 19:16:53', 5, 'Aumbbel'),
(63, 223, 1, 80, '2017-07-05 19:52:13', ' Beatriz', '', '2017-07-10 19:51:59', 7, 'Aumbbel'),
(64, 197, 1, 84, '2017-07-06 12:32:08', ' Beatriz', '', '2017-07-10 19:52:01', 7, 'Aumbbel'),
(65, 220, 3, 240, '2017-07-06 13:23:33', '', '', '', 4, 'Todavía sin aprobación'),
(66, 122, 2, 550, '2017-07-06 17:47:31', ' ', '', '2017-07-12 19:00:29', 7, 'Carlos Alex'),
(67, 122, 2, 550, '2017-07-06 17:48:04', ' Beatriz', '', '', 4, 'Todavía sin aprobación'),
(68, 122, 2, 110, '2017-07-06 17:49:36', ' Beatriz', '', '', 4, 'Todavía sin aprobación'),
(69, 252, 3, 58, '2017-07-07 13:47:41', 'manrique Mendoza', '', '2017-07-07 18:28:19', 5, 'Aumbbel'),
(70, 239, 3, 46.4, '2017-07-07 13:48:54', 'manrique Mendoza', '', '2017-07-07 18:28:16', 5, 'Aumbbel'),
(71, 258, 3, 448, '2017-07-07 17:51:40', 'manrique Mendoza', '', '2017-07-07 18:28:44', 5, 'Aumbbel'),
(72, 127, 3, 87.04, '2017-07-08 18:02:46', 'Beatriz', '', '2017-07-10 19:47:07', 5, 'Aumbbel'),
(73, 253, 1, 96, '2017-07-10 12:14:56', ' Yuri Paola', '', '2017-07-10 19:44:09', 7, 'Aumbbel'),
(74, 261, 3, 174, '2017-07-10 15:28:41', '', '', '2017-07-10 19:46:42', 5, 'Aumbbel'),
(75, 130, 3, 44.8, '2017-07-10 16:15:42', 'Yuri Paola', '', '2017-07-10 19:43:43', 5, 'Aumbbel'),
(76, 169, 1, 96, '2017-07-10 19:18:53', ' ', '', '2017-07-10 19:46:15', 7, 'Aumbbel'),
(77, 225, 3, 148.8, '2017-07-11 10:17:04', 'Yuri Paola', '', '2017-07-11 19:26:57', 6, 'Aumbbel'),
(78, 58, 3, 1316, '2017-07-11 12:52:23', 'Aumbbel', '', '2017-07-11 12:52:33', 6, 'Aumbbel'),
(79, 205, 3, 512, '2017-07-11 19:28:08', 'Aumbbel', '', '2017-07-11 19:28:17', 6, 'Aumbbel'),
(80, 263, 3, 464, '2017-07-12 09:51:25', 'Yuri Paola', '', '2017-07-12 09:56:19', 5, 'Aumbbel'),
(81, 255, 3, 696, '2017-07-12 13:24:10', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(82, 288, 1, 60, '2017-07-12 13:24:30', ' Yuri Paola', '', '2017-07-31 23:20:19', 7, 'Aumbbel'),
(83, 316, 3, 165, '2017-07-12 17:05:12', 'demo', '', '', 4, 'Todavía sin aprobación'),
(84, 308, 3, 560, '2017-07-12 17:05:58', 'demo', '', '', 4, 'Todavía sin aprobación'),
(85, 317, 3, 132, '2017-07-12 18:45:57', 'demo', '', '', 4, 'Todavía sin aprobación'),
(86, 264, 3, 406, '2017-07-13 16:02:19', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(87, 280, 3, 672, '2017-07-14 10:39:25', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(88, 76, 3, 230.4, '2017-07-14 10:39:57', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(89, 272, 3, 464, '2017-07-14 13:48:46', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(90, 302, 3, 66, '2017-07-15 17:36:08', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(91, 208, 3, 580, '2017-07-17 09:03:55', 'Yuri Paola', '', '2017-08-07 19:48:36', 5, 'Aumbbel'),
(92, 129, 1, 40, '2017-07-17 09:58:42', ' Yuri Paola', '', '2017-07-31 23:20:00', 7, 'Aumbbel'),
(93, 238, 1, 36, '2017-07-17 12:32:55', ' Yuri Paola', '', '2017-07-31 23:19:57', 7, 'Aumbbel'),
(94, 248, 1, 10, '2017-07-17 15:42:23', ' Yuri Paola', '', '2017-07-31 23:19:55', 7, 'Aumbbel'),
(95, 321, 3, 248, '2017-07-17 20:10:18', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(96, 285, 3, 81.2, '2017-07-18 13:46:40', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(97, 199, 1, 300, '2017-07-18 17:24:00', ' ', '', '2017-07-31 23:19:52', 7, 'Aumbbel'),
(98, 184, 1, 60, '2017-07-18 18:41:24', ' Yuri Paola', '', '2017-07-31 23:19:49', 7, 'Aumbbel'),
(99, 290, 3, 235.2, '2017-07-20 12:13:02', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(100, 273, 1, 128, '2017-07-20 17:52:48', ' ', '', '2017-07-31 23:20:04', 7, 'Aumbbel'),
(101, 281, 1, 32, '2017-07-21 11:59:55', ' Yuri Paola', '', '2017-07-31 23:20:06', 7, 'Aumbbel'),
(102, 288, 3, 660, '2017-07-21 12:54:06', '', '', '2017-08-02 20:13:10', 5, 'Aumbbel'),
(103, 300, 3, 840, '2017-07-21 17:29:36', '', '', '', 4, 'Todavía sin aprobación'),
(104, 311, 1, 45, '2017-07-21 17:30:05', ' Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(105, 311, 3, 450, '2017-07-21 17:30:34', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(106, 256, 2, 72, '2017-07-22 13:42:20', ' ', '', '', 4, 'Todavía sin aprobación'),
(107, 314, 3, 88, '2017-07-24 10:44:34', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(108, 276, 1, 80, '2017-07-24 15:53:51', ' Beatriz', '', '2017-07-31 23:20:10', 7, 'Aumbbel'),
(109, 286, 1, 1600, '2017-07-25 16:54:46', ' ', '', '2017-07-31 23:20:12', 7, 'Aumbbel'),
(110, 296, 3, 406, '2017-07-27 12:48:47', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(111, 301, 3, 232, '2017-07-27 17:16:53', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(112, 253, 3, 672, '2017-07-31 09:47:03', 'Yuri Paola', '', '2017-07-31 23:50:20', 5, 'Aumbbel'),
(113, 291, 3, 300, '2017-07-31 15:08:43', 'Yuri Paola', '', '2017-07-31 23:50:18', 5, 'Aumbbel'),
(114, 335, 3, 275, '2017-07-31 19:30:55', 'Yuri Paola', '', '2017-07-31 23:50:07', 5, 'Aumbbel'),
(115, 283, 3, 120, '2017-07-31 21:10:58', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(116, 286, 3, 11000, '2017-07-31 23:53:21', 'Aumbbel', '', '2017-07-31 23:53:46', 5, 'Aumbbel'),
(117, 9, 3, 832, '2017-07-31 23:58:02', 'Aumbbel', '', '2017-08-01 00:00:31', 6, 'Aumbbel'),
(118, 100, 3, 1760, '2017-07-31 23:58:24', 'Aumbbel', '', '2017-08-01 00:00:28', 6, 'Aumbbel'),
(119, 187, 3, 864, '2017-07-31 23:58:52', 'Aumbbel', '', '2017-08-01 00:00:23', 6, 'Aumbbel'),
(120, 15, 3, 44.35, '2017-08-01 00:48:17', 'Aumbbel', '', '2017-08-02 20:11:49', 6, 'Aumbbel'),
(121, 145, 3, 656, '2017-08-01 00:48:57', 'Aumbbel', '', '2017-08-02 20:11:54', 6, 'Aumbbel'),
(122, 186, 3, 86.4, '2017-08-01 00:49:08', 'Aumbbel', '', '2017-08-02 20:11:58', 6, 'Aumbbel'),
(123, 196, 3, 100.8, '2017-08-01 00:49:24', 'Aumbbel', '', '2017-08-02 20:12:01', 6, 'Aumbbel'),
(124, 224, 3, 272, '2017-08-01 00:50:16', 'Aumbbel', '', '2017-08-02 20:12:05', 6, 'Aumbbel'),
(125, 259, 3, 384, '2017-08-01 01:26:00', 'Aumbbel', '', '2017-08-02 20:12:09', 6, 'Aumbbel'),
(126, 234, 3, 594, '2017-08-01 12:05:57', 'Yuri Paola', '', '2017-08-02 19:35:08', 5, 'Aumbbel'),
(127, 298, 3, 792, '2017-08-01 12:06:29', 'Yuri Paola', '', '2017-08-02 19:33:17', 5, 'Aumbbel'),
(128, 198, 3, 8640, '2017-08-01 12:07:02', 'Yuri Paola', '', '2017-08-02 19:33:13', 5, 'Aumbbel'),
(129, 233, 3, 11200, '2017-08-01 12:07:19', 'Yuri Paola', '', '2017-08-02 19:33:07', 5, 'Aumbbel'),
(130, 278, 3, 620, '2017-08-01 12:07:58', 'Yuri Paola', '', '2017-08-02 19:33:04', 5, 'Aumbbel'),
(131, 251, 3, 5120, '2017-08-01 12:08:16', 'Yuri Paola', '', '2017-08-02 19:33:00', 5, 'Aumbbel'),
(132, 331, 3, 88, '2017-08-01 13:37:12', 'Yuri Paola', '', '2017-08-02 20:12:25', 5, 'Aumbbel'),
(133, 340, 3, 1100, '2017-08-02 11:07:29', 'Yuri Paola', '', '2017-08-02 20:10:36', 5, 'Aumbbel'),
(134, 337, 3, 55, '2017-08-02 11:12:18', 'Yuri Paola', '', '2017-08-02 20:10:40', 5, 'Aumbbel'),
(135, 336, 3, 275, '2017-08-02 12:39:37', 'Yuri Paola', '', '2017-08-02 20:10:46', 5, 'Aumbbel'),
(136, 318, 3, 448, '2017-08-02 15:46:14', '', '', '2017-08-02 20:10:51', 5, 'Aumbbel'),
(137, 129, 3, 224, '2017-08-02 16:36:08', 'Yuri Paola', '', '2017-08-02 20:10:56', 5, 'Aumbbel'),
(138, 327, 3, 440, '2017-08-03 18:59:33', 'Yuri Paola', '', '2017-08-30 13:16:04', 5, 'Aumbbel'),
(139, 305, 1, 140, '2017-08-04 19:04:54', ' Carlos Alex', '', '2017-08-07 19:46:08', 7, 'Aumbbel'),
(140, 243, 3, 528, '2017-08-05 11:56:58', 'Yuri Paola', '', '2017-08-07 19:39:08', 5, 'Aumbbel'),
(141, 163, 3, 312, '2017-08-05 13:48:52', 'Aumbbel', '', '2017-08-07 19:45:18', 6, 'Aumbbel'),
(142, 174, 3, 608, '2017-08-05 13:50:07', 'Aumbbel', '', '2017-08-07 19:45:13', 6, 'Aumbbel'),
(143, 179, 3, 380, '2017-08-05 13:50:26', 'Aumbbel', '', '2017-08-07 19:45:10', 6, 'Aumbbel'),
(144, 188, 3, 296, '2017-08-05 13:50:45', 'Aumbbel', '', '2017-08-07 19:45:07', 6, 'Aumbbel'),
(145, 209, 3, 72, '2017-08-05 13:51:41', 'Aumbbel', '', '2017-08-07 19:45:05', 6, 'Aumbbel'),
(146, 213, 3, 140, '2017-08-05 13:52:15', 'Aumbbel', '', '2017-08-07 19:39:20', 5, 'Aumbbel'),
(147, 214, 3, 280, '2017-08-05 13:52:52', 'Aumbbel', '', '2017-08-07 19:45:02', 6, 'Aumbbel'),
(148, 222, 3, 476, '2017-08-05 13:53:52', 'Aumbbel', '', '2017-08-05 13:58:50', 6, 'Aumbbel'),
(149, 231, 3, 408, '2017-08-05 13:54:21', 'Aumbbel', '', '2017-08-07 19:44:59', 6, 'Aumbbel'),
(150, 236, 3, 408, '2017-08-05 13:55:07', 'Aumbbel', '', '2017-08-07 19:39:52', 6, 'Aumbbel'),
(151, 333, 3, 165, '2017-08-05 14:25:38', 'Pilar Maria', '', '2017-08-07 19:40:55', 5, 'Aumbbel'),
(152, 344, 3, 330, '2017-08-05 14:25:52', 'Pilar Maria', '', '2017-08-07 19:41:28', 5, 'Aumbbel'),
(153, 223, 1, 80, '2017-08-07 11:35:39', ' ', '', '2017-08-07 20:03:09', 7, 'Aumbbel'),
(154, 273, 3, 896, '2017-08-08 11:17:06', 'Yuri Paola', '', '2017-08-30 12:20:32', 5, 'Aumbbel'),
(155, 122, 3, -799.2, '2017-08-09 13:08:01', 'Yuri Paola', '', '2017-08-31 09:53:22', 5, 'Aumbbel'),
(156, 303, 3, 1664, '2017-08-14 15:17:34', '', '', '2017-08-31 09:55:46', 5, 'Aumbbel'),
(157, 361, 3, 5500, '2017-08-15 11:03:13', 'Yuri Paola', '', '2017-08-31 14:05:12', 5, 'Aumbbel'),
(158, 197, 3, 372, '2017-08-15 11:28:47', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(159, 315, 3, 600, '2017-08-16 10:02:48', 'Yuri Paola', '', '2017-09-06 20:13:21', 5, 'Aumbbel'),
(160, 199, 1, 300, '2017-08-16 17:09:51', ' Yuri Paola', '', '2017-09-06 19:47:43', 7, 'Aumbbel'),
(161, 238, 1, 60, '2017-08-17 17:13:56', ' ', '', '2017-09-06 19:47:40', 7, 'Aumbbel'),
(162, 365, 3, 220, '2017-08-17 17:44:35', '', '', '', 4, 'Todavía sin aprobación'),
(163, 354, 3, 330, '2017-08-17 17:45:07', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(164, 323, 3, 360, '2017-08-19 20:04:32', 'Aumbbel', '', '2017-09-06 19:47:15', 5, 'Aumbbel'),
(165, 324, 3, 240, '2017-08-19 20:05:21', 'Aumbbel', '', '', 4, 'Todavía sin aprobación'),
(166, 320, 3, 620, '2017-08-21 10:36:37', 'Yuri Paola', '', '2017-09-06 19:07:56', 5, 'Aumbbel'),
(167, 384, 3, 770, '2017-08-22 13:40:13', 'Beatriz', '', '2017-09-06 19:19:07', 5, 'Aumbbel'),
(168, 281, 1, 40, '2017-08-22 18:16:54', ' Yuri Paola', '', '2017-09-06 19:23:50', 7, 'Aumbbel'),
(169, 347, 3, 168, '2017-08-22 18:31:45', 'Yuri Paola', '', '2017-09-06 19:21:01', 5, 'Aumbbel'),
(170, 334, 3, 232, '2017-08-22 18:31:59', 'Yuri Paola', '', '2017-09-06 19:27:02', 5, 'Aumbbel'),
(171, 376, 3, 275, '2017-08-23 15:47:38', 'Yuri Paola', '', '2017-09-06 19:34:26', 5, 'Aumbbel'),
(172, 388, 3, 495, '2017-08-24 12:02:40', 'Yuri Paola', '', '2017-09-06 19:55:43', 5, 'Aumbbel'),
(173, 351, 3, 2240, '2017-08-24 12:50:13', '', '', '2017-09-06 19:46:43', 5, 'Aumbbel'),
(174, 313, 3, 1280, '2017-08-25 09:29:11', 'Aumbbel', '', '', 4, 'Todavía sin aprobación'),
(175, 310, 3, 358.4, '2017-08-25 17:31:45', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(176, 403, 1, 60, '2017-08-25 19:10:10', ' Yuri Paola', '', '2017-09-06 20:07:11', 7, 'Aumbbel'),
(177, 392, 3, 110, '2017-08-28 10:16:10', 'Yuri Paola', '', '2017-09-06 20:28:19', 5, 'Aumbbel'),
(178, 276, 1, 80, '2017-08-28 11:20:37', ' ', '', '2017-09-06 20:28:28', 7, 'Aumbbel'),
(179, 394, 3, 605, '2017-08-28 11:38:10', 'Yuri Paola', '', '2017-09-06 20:28:43', 5, 'Aumbbel'),
(180, 402, 3, 330, '2017-08-28 11:44:47', 'Yuri Paola', '', '2017-09-06 20:29:43', 5, 'Aumbbel'),
(181, 342, 1, 8, '2017-08-28 13:23:12', ' Yuri Paola', '', '2017-09-08 19:44:06', 7, ''),
(182, 345, 3, 18000, '2017-08-29 12:47:20', 'Yuri Paola', '', '2017-08-30 13:16:20', 5, 'Aumbbel'),
(183, 184, 3, 372, '2017-08-29 18:59:11', 'Yuri Paola', '', '2017-09-06 20:44:20', 5, 'Aumbbel'),
(184, 240, 3, 1184, '2017-08-30 13:11:03', 'Aumbbel', '', '2017-08-30 13:12:22', 6, 'Aumbbel'),
(185, 266, 3, 42, '2017-08-30 13:30:08', 'Aumbbel', '', '2017-09-06 20:49:42', 5, 'Aumbbel'),
(186, 416, 3, 110, '2017-08-31 16:51:51', 'demo', '', '', 4, 'Todavía sin aprobación'),
(187, 374, 3, 56, '2017-09-01 10:13:56', 'Yuri Paola', '', '2017-09-08 19:23:55', 5, ''),
(188, 393, 1, 60, '2017-09-04 13:08:54', ' Yuri Paola', '', '2017-09-08 19:44:38', 7, ''),
(189, 383, 1, 50, '2017-09-04 16:38:36', ' Yuri Paola', '', '2017-09-08 19:44:45', 7, ''),
(190, 410, 1, 40, '2017-09-05 14:16:10', ' Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(191, 410, 2, 300, '2017-09-05 14:16:33', ' Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(192, 339, 3, 223.2, '2017-09-05 15:50:00', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(193, 307, 3, 312.8, '2017-09-05 17:19:05', 'Aumbbel', '', '2017-09-05 19:17:28', 5, 'Aumbbel'),
(194, 325, 3, 51.2, '2017-09-05 19:17:10', 'Aumbbel', '', '2017-09-05 19:17:20', 5, 'Aumbbel'),
(195, 387, 3, 952, '2017-09-06 19:06:18', 'Aumbbel', '', '2017-09-06 19:06:29', 5, 'Aumbbel'),
(196, 385, 3, 280, '2017-09-06 21:14:11', 'Aumbbel', '', '2017-09-06 21:15:29', 5, 'Aumbbel'),
(197, 395, 3, 440, '2017-09-06 21:14:31', 'Aumbbel', '', '2017-09-06 21:15:31', 5, 'Aumbbel'),
(198, 408, 3, 165, '2017-09-06 21:15:13', 'Aumbbel', '', '2017-09-06 21:15:34', 5, 'Aumbbel'),
(199, 443, 3, 385, '2017-09-08 09:38:57', '', '', '', 4, 'Todavía sin aprobación'),
(200, 442, 3, 550, '2017-09-08 12:12:40', '', '', '', 4, 'Todavía sin aprobación'),
(201, 368, 3, 2320, '2017-09-08 12:13:01', '', '', '', 4, 'Todavía sin aprobación'),
(202, 357, 3, 9600, '2017-09-08 12:13:15', '', '', '', 4, 'Todavía sin aprobación'),
(203, 420, 3, 770, '2017-09-08 17:34:49', '', '', '', 4, 'Todavía sin aprobación'),
(204, 352, 1, 50, '2017-09-08 17:42:58', ' Beatriz', '', '', 4, 'Todavía sin aprobación'),
(205, 428, 3, 550, '2017-09-09 12:22:06', '', '', '', 4, 'Todavía sin aprobación'),
(206, 455, 3, 0, '2017-09-09 13:08:41', '', '', '2017-09-10 10:31:06', 5, ''),
(207, 425, 3, 3300, '2017-09-10 10:20:46', '', '', '2017-09-10 10:21:01', 5, ''),
(208, 363, 3, 1200, '2017-09-11 16:33:49', '', '', '2017-09-11 16:34:35', 5, ''),
(209, 414, 3, 88, '2017-09-11 16:40:19', '', '', '2017-09-26 21:33:08', 5, ''),
(210, 435, 3, 275, '2017-09-11 19:26:41', '', '', '2017-09-13 14:24:57', 5, ''),
(211, 411, 3, 220, '2017-09-12 09:48:56', '', '', '2017-09-12 18:35:12', 5, ''),
(212, 386, 3, 986, '2017-09-12 09:54:44', '', '', '2017-09-12 18:35:15', 5, ''),
(213, 439, 3, 112, '2017-09-12 12:41:58', 'Yuri Paola', '', '2017-09-12 18:35:27', 5, ''),
(214, 330, 3, 264, '2017-09-12 12:52:49', '', '', '', 4, 'Todavía sin aprobación'),
(215, 223, 3, 496, '2017-09-12 13:13:36', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(216, 356, 1, 50, '2017-09-12 15:11:18', ' Yuri Paola', '', '2017-09-13 14:42:21', 7, ''),
(217, 480, 3, 13.2, '2017-09-12 20:11:48', 'demo', '', '', 4, 'Todavía sin aprobación'),
(218, 367, 1, 12, '2017-09-13 10:41:42', ' Beatriz', '', '2017-09-13 14:42:18', 7, ''),
(219, 445, 3, 495, '2017-09-13 11:25:26', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(220, 448, 3, 220, '2017-09-13 11:26:14', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(221, 433, 3, 406, '2017-09-13 13:33:16', 'Yuri Paola', '', '2017-09-13 13:33:28', 5, ''),
(222, 429, 3, 110, '2017-09-13 13:56:43', 'Aumbbel', '', '2017-09-13 13:56:51', 5, ''),
(223, 461, 3, 440, '2017-09-13 14:38:23', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(224, 453, 3, 308, '2017-09-13 15:22:02', 'Yuri Paola', '', '2017-09-26 22:12:44', 5, ''),
(225, 348, 3, 186, '2017-09-13 18:19:00', 'Aumbbel', '', '', 4, 'Todavía sin aprobación'),
(226, 441, 3, 600, '2017-09-13 18:43:54', 'Aumbbel', '', '', 4, 'Todavía sin aprobación'),
(227, 364, 1, 120, '2017-09-14 10:51:46', ' Yuri Paola', '', '2017-09-26 22:47:44', 7, ''),
(228, 332, 3, 198, '2017-09-14 11:28:53', 'Yuri Paola', '', '2017-09-26 22:34:58', 5, ''),
(229, 238, 1, 48, '2017-09-14 11:56:01', ' Yuri Paola', '', '2017-09-26 22:47:04', 7, ''),
(230, 346, 1, 42, '2017-09-14 12:20:23', ' Yuri Paola', '', '2017-09-26 22:58:19', 7, ''),
(231, 466, 3, 88, '2017-09-14 13:17:23', 'Yuri Paola', '', '2017-09-26 22:38:11', 5, ''),
(232, 409, 3, 112, '2017-09-14 15:35:27', 'Yuri Paola', '', '2017-09-26 22:39:49', 5, ''),
(233, 353, 1, 288, '2017-09-14 16:03:40', ' Yuri Paola', '', '2017-09-26 22:40:20', 7, ''),
(234, 353, 2, 212, '2017-09-14 16:04:43', ' Yuri Paola', '', '2017-09-26 22:40:23', 7, ''),
(235, 319, 1, 25.2, '2017-09-14 16:44:33', ' Yuri Paola', '', '2017-09-26 22:41:38', 7, ''),
(236, 322, 1, 36, '2017-09-14 16:44:54', ' Yuri Paola', '', '2017-09-26 22:41:40', 7, ''),
(237, 306, 3, 288, '2017-09-14 21:40:23', 'Beatriz', '', '2017-09-26 22:46:24', 5, ''),
(238, 415, 3, 336, '2017-09-15 10:32:08', 'Yuri Paola', '', '2017-09-26 23:07:25', 5, ''),
(239, 359, 3, 310, '2017-09-15 12:50:50', 'Yuri Paola', '', '2017-09-26 23:09:48', 5, ''),
(240, 422, 3, 495, '2017-09-15 19:04:58', 'Beatriz', '', '2017-09-26 23:16:07', 5, ''),
(241, 427, 3, 165, '2017-09-15 19:53:25', 'Beatriz', '', '2017-09-26 23:16:23', 5, ''),
(242, 360, 1, 12, '2017-09-15 19:53:53', ' Beatriz', '', '', 4, 'Todavía sin aprobación'),
(243, 353, 3, 1086.8, '2017-09-16 14:21:58', 'Pilar Maria', '', '2017-09-26 23:30:03', 5, ''),
(244, 373, 1, 20, '2017-09-18 11:40:27', ' Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(245, 437, 1, 80, '2017-09-18 17:39:35', ' Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(246, 437, 2, 190, '2017-09-18 17:40:00', ' Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(247, 55, 8, 19, '2017-09-19 18:03:19', '9', '', '', 4, 'Todavía sin aprobación'),
(248, 140, 8, 90, '2017-09-19 19:15:52', '0', '', '', 4, 'Todavía sin aprobación'),
(249, 56, 8, 160, '2017-09-19 19:18:56', '0', '', '', 4, 'Todavía sin aprobación'),
(251, 201, 8, 150, '2017-09-19 20:04:25', 'Aumbbel', '', '', 4, 'Todavía sin aprobación'),
(252, 204, 8, 200, '2017-09-19 20:06:15', 'Aumbbel', '', '', 4, 'Todavía sin aprobación'),
(253, 260, 8, 400, '2017-09-19 20:06:52', 'Aumbbel', '', '', 4, 'Todavía sin aprobación'),
(255, 316, 8, 150, '2017-09-20 17:39:58', 'demo', '', '', 4, 'Todavía sin aprobación'),
(256, 535, 9, 66.16, '2017-09-22 17:41:41', 'demo', '', '', 4, 'Todavía sin aprobación'),
(257, 535, 9, 66.16, '2017-09-22 17:43:54', 'demo', '', '', 4, 'Todavía sin aprobación'),
(258, 537, 8, 43.8, '2017-09-22 18:42:18', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(259, 513, 8, 27, '2017-09-22 19:29:46', 'demo', '', '', 4, 'Todavía sin aprobación'),
(260, 536, 8, 12.1, '2017-09-22 19:32:09', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(261, 536, 9, 12.13, '2017-09-22 19:43:17', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(262, 477, 9, 22.1, '2017-09-22 19:45:30', 'demo', '', '', 4, 'Todavía sin aprobación'),
(263, 419, 9, 349.8, '2017-09-23 14:23:55', 'Pilar Maria', '', '', 4, 'Todavía sin aprobación'),
(264, 293, 8, 2600, '2017-09-23 14:44:31', 'Aumbbel', '', '', 4, 'Todavía sin aprobación'),
(265, 549, 8, 90, '2017-09-26 16:48:15', 'demo', '', '', 4, 'Todavía sin aprobación'),
(266, 550, 1, 165.39, '2017-09-26 17:10:46', 'demo', '', '', 4, 'Todavía sin aprobación'),
(267, 551, 1, 110.26, '2017-09-26 17:12:01', 'demo', '', '', 4, 'Todavía sin aprobación'),
(268, 557, 1, 26.46, '2017-09-26 17:39:06', 'demo', '', '', 4, 'Todavía sin aprobación'),
(269, 558, 1, 110.26, '2017-09-26 17:42:24', 'demo', '', '', 4, 'Todavía sin aprobación'),
(270, 551, 1, 110.26, '2017-09-26 17:47:19', 'demo', '', '', 4, 'Todavía sin aprobación'),
(271, 553, 1, 88.21, '2017-09-26 17:53:02', 'demo', '', '', 4, 'Todavía sin aprobación'),
(272, 552, 9, 33.08, '2017-09-26 18:07:13', 'demo', '', '', 4, 'Todavía sin aprobación'),
(276, 554, 9, 8.21, '2017-09-26 18:32:54', 'demo', '', '', 4, 'Todavía sin aprobación'),
(274, 496, 8, 27513.7, '2017-09-26 18:15:31', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(275, 555, 9, 88.21, '2017-09-26 18:15:35', 'demo', '', '', 4, 'Todavía sin aprobación'),
(277, 531, 8, 441.03, '2017-09-26 18:41:57', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(278, 420, 8, 833.36, '2017-09-26 18:57:05', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(279, 281, 8, 253.8, '2017-09-26 19:07:28', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(280, 559, 1, 8, '2017-09-26 19:25:20', 'demo', '', '', 4, 'Todavía sin aprobación'),
(281, 508, 8, 165, '2017-09-26 19:33:22', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(282, 560, 1, 10, '2017-09-26 19:39:55', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(283, 550, 9, 165, '2017-09-26 19:42:54', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(284, 557, 1, 2.1, '2017-09-26 20:15:54', 'demo', '', '', 4, 'Todavía sin aprobación'),
(285, 557, 1, 2, '2017-09-26 20:16:45', 'demo', '', '', 4, 'Todavía sin aprobación'),
(286, 557, 1, 1, '2017-09-26 20:17:34', 'demo', '', '', 4, 'Todavía sin aprobación'),
(287, 557, 9, 26.4, '2017-09-26 20:18:26', 'demo', '', '', 4, 'Todavía sin aprobación'),
(288, 551, 9, 110, '2017-09-26 20:21:23', 'demo', '', '', 4, 'Todavía sin aprobación'),
(289, 558, 9, 110, '2017-09-26 20:21:49', 'demo', '', '', 4, 'Todavía sin aprobación'),
(290, 561, 9, 110, '2017-09-26 20:24:14', 'demo', '', '', 4, 'Todavía sin aprobación'),
(291, 562, 1, 45, '2017-09-26 20:50:43', 'demo', '', '', 4, 'Todavía sin aprobación'),
(292, 562, 1, 10, '2017-09-26 20:52:26', 'demo', '', '', 4, 'Todavía sin aprobación'),
(293, 562, 10, 10, '2017-09-26 20:56:19', 'demo', '', '', 4, 'Todavía sin aprobación'),
(294, 563, 1, 15, '2017-09-26 21:00:00', 'demo', '', '', 4, 'Todavía sin aprobación'),
(295, 563, 1, 15, '2017-09-26 21:02:26', 'demo', '', '', 4, 'Todavía sin aprobación'),
(296, 563, 1, 15, '2017-09-26 21:06:04', 'demo', '', '', 4, 'Todavía sin aprobación'),
(297, 563, 1, 15, '2017-09-26 21:06:26', 'demo', '', '', 4, 'Todavía sin aprobación'),
(299, 434, 8, 900, '2017-09-26 21:37:26', 'Aumbbel', '', '', 4, 'Todavía sin aprobación'),
(300, 338, 8, 380, '2017-09-26 22:09:48', 'Aumbbel', '', '', 4, 'Todavía sin aprobación'),
(301, 467, 8, 79.2, '2017-09-26 22:26:19', 'Aumbbel', '', '', 4, 'Todavía sin aprobación'),
(302, 469, 8, 132, '2017-09-26 22:33:03', 'Aumbbel', '', '', 4, 'Todavía sin aprobación'),
(303, 304, 8, 230, '2017-09-26 23:18:21', 'Aumbbel', '', '', 4, 'Todavía sin aprobación'),
(304, 312, 8, 110, '2017-09-26 23:22:43', 'Aumbbel', '', '', 4, 'Todavía sin aprobación'),
(305, 468, 8, 120, '2017-09-26 23:23:17', 'Aumbbel', '', '', 4, 'Todavía sin aprobación'),
(306, 476, 8, 500, '2017-09-26 23:34:44', 'Aumbbel', '', '', 4, 'Todavía sin aprobación'),
(307, 473, 8, 400, '2017-09-27 00:03:22', 'Aumbbel', '', '', 4, 'Todavía sin aprobación'),
(308, 506, 8, 880, '2017-09-27 16:01:38', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(309, 413, 8, 348, '2017-09-27 18:25:26', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(310, 397, 10, 1260, '2017-09-28 11:35:03', 'Aumbbel', '', '', 4, 'Todavía sin aprobación'),
(311, 543, 8, 275, '2017-09-28 16:23:07', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(312, 529, 8, 22, '2017-09-28 19:37:29', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(313, 377, 10, 45, '2017-09-28 19:56:50', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(314, 400, 1, 200, '2017-09-28 20:04:33', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(315, 511, 8, 220, '2017-09-29 12:00:11', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(316, 379, 10, 260, '2017-09-29 14:25:30', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(317, 358, 8, 850, '2017-09-29 14:44:40', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(318, 515, 8, 275, '2017-09-29 18:35:00', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(319, 372, 1, 90, '2017-09-29 18:40:08', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(320, 372, 1, 10, '2017-09-29 18:41:00', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(321, 493, 1, 214.99, '2017-09-30 13:13:02', 'Pilar Maria', '', '', 4, 'Todavía sin aprobación'),
(322, 574, 8, 220, '2017-09-30 15:00:44', 'Pilar Maria', '', '', 4, 'Todavía sin aprobación'),
(323, 500, 8, 609.1, '2017-10-02 11:01:21', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(324, 356, 8, 300, '2017-10-02 12:34:26', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(325, 379, 8, 110, '2017-10-02 13:57:08', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(326, 465, 8, 16508, '2017-10-02 14:41:44', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(327, 405, 1, 134.5, '2017-10-02 14:59:40', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(328, 509, 10, 100, '2017-10-02 17:17:02', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(329, 563, 1, -90, '2017-10-02 19:05:38', 'demo', '', '', 4, 'Todavía sin aprobación'),
(330, 404, 8, 259, '2017-10-02 19:15:07', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(331, 585, 8, 220, '2017-10-04 09:34:54', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(332, 462, 8, 292.4, '2017-10-04 10:51:30', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(333, 372, 1, 58, '2017-10-04 12:19:39', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(334, 507, 10, 15, '2017-10-04 16:15:02', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(335, 391, 8, 998.2, '2017-10-04 17:06:10', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(336, 460, 8, 770, '2017-10-05 15:34:05', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(337, 426, 1, 300, '2017-10-06 11:16:50', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(338, 421, 1, 390.6, '2017-10-06 12:36:58', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(339, 373, 8, 120.5, '2017-10-06 15:05:55', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(340, 604, 9, 605, '2017-10-06 19:26:41', 'Aumbbel', '', '', 4, 'Todavía sin aprobación'),
(341, 479, 8, 470.99, '2017-10-07 13:17:42', 'Pilar Maria', '', '', 4, 'Todavía sin aprobación'),
(342, 495, 8, 464.62, '2017-10-07 17:07:43', 'Pilar Maria', '', '', 4, 'Todavía sin aprobación'),
(343, 45, 8, 1, '2017-10-07 17:36:39', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(344, 475, 8, 1, '2017-10-07 17:36:58', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(345, 203, 8, 1, '2017-10-07 17:37:46', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(346, 248, 8, 1, '2017-10-07 17:38:49', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(347, 326, 8, 1, '2017-10-07 17:39:51', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(348, 328, 8, 1, '2017-10-07 17:40:24', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(349, 343, 8, 1, '2017-10-07 17:41:03', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(350, 362, 8, 581.63, '2017-10-08 11:18:33', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(351, 481, 1, 94, '2017-10-09 17:26:30', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(352, 412, 8, 1, '2017-10-10 11:02:03', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(353, 417, 8, 1, '2017-10-10 11:02:40', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(354, 444, 8, 1, '2017-10-10 11:20:50', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(355, 622, 8, 108, '2017-10-10 14:02:11', 'demo', '', '', 4, 'Todavía sin aprobación'),
(356, 623, 1, 50, '2017-10-10 14:02:59', 'demo', '', '', 4, 'Todavía sin aprobación'),
(357, 623, 1, 299.95, '2017-10-10 14:03:29', 'demo', '', '', 4, 'Todavía sin aprobación'),
(358, 623, 9, 3299.95, '2017-10-10 14:03:47', 'demo', '', '', 4, 'Todavía sin aprobación'),
(359, 580, 8, 165, '2017-10-10 15:15:22', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(360, 447, 1, 73, '2017-10-10 16:15:22', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(361, 454, 1, 60, '2017-10-10 16:27:37', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(362, 437, 8, 300, '2017-10-11 10:59:28', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(363, 364, 1, 300, '2017-10-11 17:42:06', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(364, 352, 1, 50, '2017-10-11 18:20:24', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(365, 544, 8, 278.8, '2017-10-11 18:41:04', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(366, 470, 1, 140, '2017-10-12 11:42:32', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(367, 563, 1, 16.12, '2017-10-12 12:16:21', 'demo', '', '', 4, 'Todavía sin aprobación'),
(368, 491, 8, 1, '2017-10-12 15:42:23', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(369, 364, 8, 308.8, '2017-10-12 17:15:30', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(370, 601, 8, 44, '2017-10-12 17:30:40', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(371, 517, 8, 409.4, '2017-10-13 17:05:33', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(372, 342, 1, 15.08, '2017-10-14 12:11:59', 'Pilar Maria', '', '', 4, 'Todavía sin aprobación'),
(373, 593, 9, 384.99, '2017-10-14 13:59:49', 'Pilar Maria', '', '', 4, 'Todavía sin aprobación'),
(374, 516, 9, 1177.46, '2017-10-14 14:21:05', 'Pilar Maria', '', '', 4, 'Todavía sin aprobación'),
(375, 634, 9, 659.99, '2017-10-14 14:21:43', 'Pilar Maria', '', '', 4, 'Todavía sin aprobación'),
(376, 367, 10, 78.27, '2017-10-14 14:37:49', 'Pilar Maria', '', '', 4, 'Todavía sin aprobación'),
(377, 367, 8, 90.27, '2017-10-14 14:38:02', 'Pilar Maria', '', '', 4, 'Todavía sin aprobación'),
(378, 599, 8, 1870, '2017-10-16 11:13:14', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(379, 624, 10, 50, '2017-10-16 13:09:23', 'demo', '', '', 4, 'Todavía sin aprobación'),
(380, 624, 10, 70, '2017-10-16 13:09:49', 'demo', '', '', 4, 'Todavía sin aprobación'),
(381, 624, 10, 80, '2017-10-16 13:10:01', 'demo', '', '', 4, 'Todavía sin aprobación'),
(382, 523, 8, 355.7, '2017-10-16 13:50:26', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(383, 621, 1, 29.99, '2017-10-16 16:31:08', 'demo', '', '', 4, 'Todavía sin aprobación'),
(384, 621, 10, 80, '2017-10-16 16:31:31', 'demo', '', '', 4, 'Todavía sin aprobación'),
(385, 621, 2, 10, '2017-10-16 16:33:15', 'demo', '', '', 4, 'Todavía sin aprobación'),
(386, 621, 2, 28, '2017-10-16 16:33:33', 'demo', '', '', 4, 'Todavía sin aprobación'),
(387, 621, 10, 30, '2017-10-16 16:33:45', 'demo', '', '', 4, 'Todavía sin aprobación'),
(388, 621, 10, 250, '2017-10-16 16:34:12', 'demo', '', '', 4, 'Todavía sin aprobación'),
(389, 621, 10, 250, '2017-10-16 16:34:32', 'demo', '', '', 4, 'Todavía sin aprobación'),
(390, 621, 10, 250, '2017-10-16 16:34:58', 'demo', '', '', 4, 'Todavía sin aprobación'),
(391, 621, 10, 80, '2017-10-16 16:40:50', 'demo', '', '', 4, 'Todavía sin aprobación'),
(392, 645, 10, 40, '2017-10-16 16:41:56', 'demo', '', '', 4, 'Todavía sin aprobación'),
(393, 645, 10, 50, '2017-10-16 16:50:18', 'demo', '', '', 4, 'Todavía sin aprobación'),
(394, 645, 10, 50, '2017-10-16 16:53:00', 'demo', '', '', 4, 'Todavía sin aprobación'),
(395, 563, 10, 50, '2017-10-16 16:54:06', 'demo', '', '', 4, 'Todavía sin aprobación'),
(396, 64, 10, 50, '2017-10-16 17:03:28', 'demo', '', '', 4, 'Todavía sin aprobación'),
(397, 355, 8, 900, '2017-10-16 17:06:57', 'Aumbbel', '', '', 4, 'Todavía sin aprobación'),
(398, 64, 10, 50, '2017-10-16 17:48:04', 'demo', '', '', 4, 'Todavía sin aprobación'),
(399, 64, 9, 140, '2017-10-16 17:50:46', 'demo', '', '', 4, 'Todavía sin aprobación'),
(400, 647, 10, 80, '2017-10-16 18:06:20', 'demo', '', '', 4, 'Todavía sin aprobación'),
(401, 647, 10, 50, '2017-10-16 18:18:44', 'demo', '', '', 4, 'Todavía sin aprobación'),
(402, 647, 10, 30, '2017-10-16 18:23:03', 'demo', '', '', 4, 'Todavía sin aprobación'),
(403, 648, 10, 30, '2017-10-16 18:24:27', 'demo', '', '', 4, 'Todavía sin aprobación'),
(404, 648, 10, 25, '2017-10-16 18:24:52', 'demo', '', '', 4, 'Todavía sin aprobación'),
(405, 648, 2, 10, '2017-10-16 18:33:14', 'demo', '', '', 4, 'Todavía sin aprobación'),
(406, 648, 1, 18.4, '2017-10-16 18:33:26', 'demo', '', '', 4, 'Todavía sin aprobación'),
(407, 389, 8, 3298, '2017-10-16 19:24:08', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(408, 503, 2, 130.9, '2017-10-16 19:24:40', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(409, 521, 1, 116.16, '2017-10-17 10:04:08', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(410, 632, 8, 1, '2017-10-17 15:14:59', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(411, 490, 8, 100.2, '2017-10-17 18:38:10', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(412, 614, 9, 495, '2017-10-18 11:06:07', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(413, 633, 8, 351, '2017-10-18 14:24:11', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(414, 600, 8, 3850, '2017-10-18 16:32:00', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(415, 567, 8, 4583, '2017-10-18 16:32:38', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(416, 656, 2, 20, '2017-10-18 18:12:13', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(417, 575, 8, 56, '2017-10-18 18:16:49', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(418, 620, 8, 605, '2017-10-20 11:12:39', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(419, 602, 8, 223, '2017-10-20 11:13:47', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(420, 533, 2, 60, '2017-10-20 15:26:26', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(421, 507, 2, 10, '2017-10-20 15:40:15', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(422, 507, 2, 20, '2017-10-20 15:40:43', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(423, 592, 8, 898.1, '2017-10-20 16:19:56', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(424, 539, 2, 40, '2017-10-20 19:02:12', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(425, 582, 9, 57.68, '2017-10-21 11:52:31', 'Pilar Maria', '', '', 4, 'Todavía sin aprobación'),
(426, 569, 9, 235.49, '2017-10-21 18:10:48', 'Pilar Maria', '', '', 4, 'Todavía sin aprobación'),
(427, 637, 8, 220, '2017-10-23 11:24:43', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(428, 664, 2, 10, '2017-10-23 12:49:10', 'demo', '', '', 4, 'Todavía sin aprobación'),
(429, 405, 2, 97.5, '2017-10-23 15:12:46', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(430, 418, 8, 174.7, '2017-10-23 15:46:17', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(431, 276, 2, 160, '2017-10-24 11:19:59', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(432, 591, 1, 84.51, '2017-10-24 12:12:41', 'manrique Mendoza', '', '', 4, 'Todavía sin aprobación'),
(433, 615, 8, 553.8, '2017-10-24 13:39:51', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(434, 382, 9, 573, '2017-10-24 15:36:21', 'manrique Mendoza', '', '', 4, 'Todavía sin aprobación'),
(435, 382, 1, 272.97, '2017-10-24 15:44:24', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(436, 382, 10, 573, '2017-10-24 15:46:15', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(437, 369, 8, 8106.9, '2017-10-25 12:38:33', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(438, 629, 8, 385, '2017-10-25 13:05:14', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(439, 463, 2, 50, '2017-10-25 14:23:37', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(440, 588, 8, 533.5, '2017-10-25 15:44:39', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(441, 377, 8, 31.3, '2017-10-26 09:45:34', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(442, 664, 8, 0, '2017-10-26 11:26:13', 'demo', '', '', 4, 'Todavía sin aprobación'),
(443, 675, 8, 0, '2017-10-26 11:30:53', 'demo', '', '', 4, 'Todavía sin aprobación'),
(444, 676, 8, 0, '2017-10-26 11:33:00', 'demo', '', '', 4, 'Todavía sin aprobación'),
(445, 584, 8, 0, '2017-10-26 11:53:35', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(446, 578, 8, 0, '2017-10-26 11:54:47', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(447, 573, 8, 0, '2017-10-26 11:57:10', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(448, 571, 8, 0, '2017-10-26 11:57:49', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(449, 518, 8, 0, '2017-10-26 12:00:56', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(450, 472, 8, 0, '2017-10-26 12:03:59', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(451, 440, 8, 0, '2017-10-26 12:07:12', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(452, 438, 8, 0, '2017-10-26 12:13:31', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(453, 407, 8, 0, '2017-10-26 12:14:15', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(454, 403, 8, 0, '2017-10-26 12:15:15', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(455, 401, 8, 0, '2017-10-26 12:22:46', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(456, 399, 8, 0, '2017-10-26 12:23:22', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(457, 396, 8, 0, '2017-10-26 12:23:47', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(458, 390, 8, 0, '2017-10-26 12:24:06', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(459, 381, 8, 0, '2017-10-26 12:24:36', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(460, 380, 8, 0, '2017-10-26 12:24:59', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(461, 378, 8, 0, '2017-10-26 12:25:52', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(462, 375, 8, 0, '2017-10-26 12:26:08', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(463, 485, 8, 0, '2017-10-26 12:26:31', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(464, 423, 8, 0, '2017-10-26 12:27:19', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(465, 471, 8, 0, '2017-10-26 12:28:03', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(466, 510, 8, 453.3, '2017-10-26 12:34:28', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(467, 370, 8, 0, '2017-10-26 12:37:26', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(468, 366, 8, 0, '2017-10-26 12:37:54', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(469, 350, 8, 0, '2017-10-26 12:38:26', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(470, 349, 8, 0, '2017-10-26 12:38:42', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(471, 341, 8, 0, '2017-10-26 12:39:13', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(472, 329, 8, 0, '2017-10-26 12:40:13', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(473, 238, 2, 72, '2017-10-26 12:41:56', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(474, 238, 8, 0, '2017-10-26 12:49:44', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(475, 309, 8, 0, '2017-10-26 12:51:57', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(476, 297, 8, 0, '2017-10-26 12:52:24', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(477, 295, 8, 0, '2017-10-26 12:52:49', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(478, 289, 8, 0, '2017-10-26 12:53:10', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(479, 284, 8, 0, '2017-10-26 12:53:47', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(480, 649, 8, 0, '2017-10-26 13:47:25', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(481, 666, 8, 275, '2017-10-26 14:19:19', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(482, 603, 8, 290.4, '2017-10-26 18:30:45', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(483, 667, 8, 220, '2017-10-27 11:13:46', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(484, 451, 8, 0, '2017-10-27 12:35:49', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(485, 493, 1, 446.89, '2017-10-27 14:26:25', 'Giordan Neil', '', '', 4, 'Todavía sin aprobación'),
(486, 463, 10, 328.69, '2017-10-27 17:12:49', 'Giordan Neil', '', '', 4, 'Todavía sin aprobación'),
(487, 463, 9, 100, '2017-10-27 17:13:18', 'Giordan Neil', '', '', 4, 'Todavía sin aprobación'),
(488, 410, 9, 142.47, '2017-10-27 17:25:48', 'Giordan Neil', '', '', 4, 'Todavía sin aprobación'),
(489, 458, 8, 1280, '2017-10-27 19:52:16', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(490, 421, 8, 1590, '2017-10-27 19:53:55', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(491, 681, 8, 220, '2017-10-28 10:00:00', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(492, 546, 2, 194, '2017-10-28 11:20:51', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(493, 541, 2, 30, '2017-10-28 18:10:39', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(494, 436, 9, 3000, '2017-10-28 18:26:04', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(495, 492, 9, 2000, '2017-10-28 18:26:44', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(496, 542, 10, 97.2, '2017-10-30 09:56:30', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(497, 565, 1, 170.35, '2017-10-30 10:32:11', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(498, 566, 1, 73.01, '2017-10-30 10:33:10', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(499, 547, 1, 125.93, '2017-10-30 10:41:43', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(500, 459, 2, 78, '2017-10-11 10:46:07', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(501, 449, 2, 79, '2017-10-13 11:11:23', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(502, 556, 8, 0, '2017-10-30 12:52:19', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(503, 560, 8, 0, '2017-10-30 12:52:46', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(504, 548, 8, 756.2, '2017-10-30 13:13:50', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(505, 346, 2, 42, '2017-10-30 16:38:37', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(506, 319, 2, 25.2, '2017-10-30 16:41:52', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(507, 322, 2, 36, '2017-10-30 16:55:22', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(508, 305, 2, 140, '2017-10-30 16:57:24', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(509, 199, 2, 300, '2017-10-30 17:00:07', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(510, 636, 8, 669, '2017-10-30 17:01:36', 'Giordan Neil', '', '', 4, 'Todavía sin aprobación'),
(511, 360, 2, 12, '2017-10-30 17:01:54', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(512, 322, 1, 36.8, '2017-10-30 17:04:12', 'Giordan Neil', '', '', 4, 'Todavía sin aprobación'),
(513, 393, 2, 60, '2017-10-30 17:17:38', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(514, 383, 2, 50, '2017-10-30 17:23:43', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(515, 680, 9, 385, '2017-10-30 18:31:20', 'Giordan Neil', '', '', 4, 'Todavía sin aprobación'),
(516, 650, 8, 660, '2017-10-31 12:17:30', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(517, 501, 2, 60, '2017-10-31 18:14:40', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(518, 519, 2, 560, '2017-11-02 09:11:05', 'Giordan Neil', '', '', 4, 'Todavía sin aprobación'),
(519, 494, 8, 300, '2017-11-02 10:25:04', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(520, 512, 8, 1358.4, '2017-11-02 10:30:16', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(521, 454, 8, 755, '2017-11-02 11:54:59', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(522, 528, 8, 450, '2017-11-02 12:26:29', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(523, 452, 8, 1040, '2017-11-02 12:37:44', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(524, 522, 8, 140, '2017-11-02 14:37:47', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(525, 532, 8, 120, '2017-11-02 16:53:36', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(526, 653, 9, 770, '2017-11-02 17:47:19', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(527, 507, 8, 109.8, '2017-11-02 17:51:28', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(528, 497, 1, 77.3, '2017-11-03 10:25:51', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(529, 581, 1, 52.1, '2017-11-03 10:26:26', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(530, 530, 1, 16.1, '2017-11-03 10:26:54', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(531, 360, 2, 15.2, '2017-11-03 10:29:29', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(532, 545, 8, 620, '2017-11-03 11:33:43', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(533, 464, 8, 450, '2017-11-03 11:44:12', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(534, 538, 8, 600, '2017-11-03 12:17:41', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(535, 715, 8, 650, '2017-11-03 13:06:56', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(536, 598, 8, 360, '2017-11-03 14:42:17', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(537, 708, 8, 110, '2017-11-03 15:15:13', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(538, 533, 8, 330, '2017-11-03 18:53:12', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(539, 424, 8, 1504.4, '2017-11-04 10:32:04', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(540, 590, 9, 87, '2017-11-04 11:21:01', 'Pilar Maria', '', '', 4, 'Todavía sin aprobación'),
(541, 526, 9, 246, '2017-11-04 19:05:23', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(542, 566, 8, 330, '2017-11-06 11:57:43', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(543, 565, 8, 770, '2017-11-06 11:58:15', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(544, 489, 8, 600, '2017-11-06 15:44:07', 'Giordan Neil', '', '', 4, 'Todavía sin aprobación'),
(545, 699, 2, 48, '2017-11-06 16:59:04', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(546, 597, 2, 30, '2017-11-06 18:16:23', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(547, 605, 2, 30, '2017-11-06 18:16:50', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(548, 617, 8, 726, '2017-11-07 12:24:49', 'Giordan Neil', '', '', 4, 'Todavía sin aprobación'),
(549, 426, 1, 365, '2017-11-07 13:13:10', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(550, 607, 8, 693.2, '2017-11-07 15:28:51', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(551, 673, 8, 100, '2017-11-07 16:07:12', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(552, 520, 8, 250, '2017-11-08 11:31:56', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(553, 605, 2, 20, '2017-11-08 18:12:54', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(554, 597, 2, 20, '2017-11-08 18:13:27', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(555, 568, 8, 0, '2017-11-08 18:30:02', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(556, 679, 1, 35, '2017-11-08 18:46:18', 'Yuri Paola', '', '', 4, 'Todavía sin aprobación'),
(557, 457, 8, 0, '2017-11-08 18:54:50', 'Carlos Alex', '', '', 4, 'Todavía sin aprobación'),
(558, 635, 2, 135, '2017-11-09 09:50:58', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(559, 400, 8, 1240, '2017-11-09 13:04:50', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(560, 352, 8, 300, '2017-11-09 13:46:52', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(561, 498, 8, 528, '2017-11-09 14:25:14', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(562, 625, 8, 420, '2017-11-09 14:26:37', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(563, 540, 8, 576, '2017-11-09 14:27:07', 'Beatriz', '', '', 4, 'Todavía sin aprobación'),
(564, 504, 8, 330, '2017-11-09 14:30:14', 'Beatriz', '', '', 4, 'Todavía sin aprobación');
INSERT INTO `reportes_producto` (`idReporte`, `idProducto`, `idDetalleReporte`, `repoValorMonetario`, `repoFechaOcurrencia`, `repoUsuario`, `repoUsuarioComentario`, `repoFechaConfirma`, `repoQueConfirma`, `repoQuienConfirma`) VALUES
(565, 446, 8, 0, '2017-11-09 14:45:34', 'Giordan Neil', '', '', 4, 'Todavía sin aprobación'),
(566, 505, 8, 0, '2017-11-09 14:48:44', 'Giordan Neil', '', '', 4, 'Todavía sin aprobación'),
(567, 594, 8, 250, '2017-11-09 15:28:41', 'Beatriz', '', '', 4, 'Todavía sin aprobación');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

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
(3, 'Todas las oficinas', 'Todas las oficinas', b'1'),
(7, 'Agencia El Tambo', 'El Tambo', b'1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tempo`
--

CREATE TABLE `tempo` (
  `idtempo` int(11) NOT NULL,
  `fecha` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `tempo`
--

INSERT INTO `tempo` (`idtempo`, `fecha`) VALUES
(1, '2017-09-07 11:37:18'),
(2, '0000-00-00 00:00:00'),
(3, '0000-00-00 00:00:00'),
(4, '0000-00-00 00:00:00'),
(5, '2017-09-07 11:41:50'),
(6, '2017-09-12 20:08:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipoProceso`
--

CREATE TABLE `tipoProceso` (
  `idTipoProceso` int(11) NOT NULL,
  `tipoDescripcion` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `tipoProceso`
--

INSERT INTO `tipoProceso` (`idTipoProceso`, `tipoDescripcion`) VALUES
(1, 'Entrada stock'),
(2, 'Venta'),
(3, 'Crédito nuevo'),
(4, 'Pérdida'),
(5, 'Egreso'),
(6, 'Ingreso'),
(7, 'Amortización'),
(8, 'Sin acción'),
(9, 'Adelantó parte de interés'),
(10, 'Cancela interés'),
(11, 'Artículo retirado'),
(12, 'Artículo vigente'),
(13, 'Capital cancelado'),
(14, 'Desembolso');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

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
(2, 'Yuri Paola', 'Huaycuch Valenzuela', 'phuaycuch', '69447f927b74151c1aeb626f31202e0a', 2, 1, b'1'),
(3, 'Yuri Paola', 'Huaycuch Valenzuela', 'yhuaycuch', 'ce3ae197dd86341cbe2ed2ae9e30e145', 2, 1, b'1'),
(10, 'Aumbbel', 'Manrique', 'amo', '43e423ee04be24b417b0c5eb71ad4464', 1, 3, b'1'),
(9, 'demo', 'demo', 'demo', 'fe01ce2a7fbac8fafaed7c982a04e229', 2, 2, b'1'),
(8, 'Aumbbel', 'Manrique', 'aumbbel', 'ef6299c9e7fdae6d775819ce1e2620b8', 1, 3, b'1'),
(13, 'Beatriz', 'Manrique', 'bmanrique', '938bde16c106ec10453dcdf051fb0d49', 2, 1, b'1'),
(12, 'Pilar Maria', 'Mateo Quincho', 'pmateo', '787dab1236880babb61463fffd4bc784', 2, 1, b'1'),
(14, 'Giordan Neil', 'Manrique', 'gmanrique', '4b5c6abc26d0e2431c3e432a1d53d25a', 2, 1, b'1'),
(15, 'Beatriz', 'Manrique', 'betzabe', '96e6383126d8d4fd1a9112766d4b1fca', 2, 7, b'1'),
(16, 'Cardenas Pineda', 'Mariza', 'mariza', '351587c4bdae9b1ac951117ba9ae7685', 2, 1, b'1'),
(17, 'Nakasone Guzman', 'Edgard Yoshio', 'Yoshio', '202cb962ac59075b964b07152d234b70', 2, 1, b'1');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`idCaja`);

--
-- Indices de la tabla `Cliente`
--
ALTER TABLE `Cliente`
  ADD PRIMARY KEY (`idCliente`),
  ADD UNIQUE KEY `idCliente` (`idCliente`);

--
-- Indices de la tabla `Compras`
--
ALTER TABLE `Compras`
  ADD PRIMARY KEY (`idCompra`);

--
-- Indices de la tabla `desembolso`
--
ALTER TABLE `desembolso`
  ADD PRIMARY KEY (`idDesembolso`);

--
-- Indices de la tabla `DetalleReporte`
--
ALTER TABLE `DetalleReporte`
  ADD PRIMARY KEY (`idDetalleReporte`);

--
-- Indices de la tabla `PagoaCuenta`
--
ALTER TABLE `PagoaCuenta`
  ADD PRIMARY KEY (`idPago`);

--
-- Indices de la tabla `poder`
--
ALTER TABLE `poder`
  ADD PRIMARY KEY (`idPoder`);

--
-- Indices de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  ADD PRIMARY KEY (`idPrestamo`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`idProducto`);

--
-- Indices de la tabla `reportes_producto`
--
ALTER TABLE `reportes_producto`
  ADD PRIMARY KEY (`idReporte`);

--
-- Indices de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  ADD PRIMARY KEY (`idSucursal`);

--
-- Indices de la tabla `tempo`
--
ALTER TABLE `tempo`
  ADD PRIMARY KEY (`idtempo`);

--
-- Indices de la tabla `tipoProceso`
--
ALTER TABLE `tipoProceso`
  ADD PRIMARY KEY (`idTipoProceso`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idUsuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `idCaja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=460;
--
-- AUTO_INCREMENT de la tabla `Cliente`
--
ALTER TABLE `Cliente`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=485;
--
-- AUTO_INCREMENT de la tabla `Compras`
--
ALTER TABLE `Compras`
  MODIFY `idCompra` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `desembolso`
--
ALTER TABLE `desembolso`
  MODIFY `idDesembolso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=729;
--
-- AUTO_INCREMENT de la tabla `DetalleReporte`
--
ALTER TABLE `DetalleReporte`
  MODIFY `idDetalleReporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT de la tabla `PagoaCuenta`
--
ALTER TABLE `PagoaCuenta`
  MODIFY `idPago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=230;
--
-- AUTO_INCREMENT de la tabla `poder`
--
ALTER TABLE `poder`
  MODIFY `idPoder` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `prestamo`
--
ALTER TABLE `prestamo`
  MODIFY `idPrestamo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=729;
--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `idProducto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=736;
--
-- AUTO_INCREMENT de la tabla `reportes_producto`
--
ALTER TABLE `reportes_producto`
  MODIFY `idReporte` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=568;
--
-- AUTO_INCREMENT de la tabla `sucursal`
--
ALTER TABLE `sucursal`
  MODIFY `idSucursal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT de la tabla `tempo`
--
ALTER TABLE `tempo`
  MODIFY `idtempo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `tipoProceso`
--
ALTER TABLE `tipoProceso`
  MODIFY `idTipoProceso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
