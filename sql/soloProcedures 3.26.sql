DELIMITER ;
DROP PROCEDURE IF EXISTS `actualizarCaja`;
DELIMITER ;;
CREATE PROCEDURE `actualizarCaja`(IN `idCaj` INT, IN `pproceso` INT, IN `ffecha` TEXT, IN `vvalor` FLOAT, IN `oobs` TEXT, IN `mmoneda` INT, IN `aactivo` INT)
    NO SQL
UPDATE `caja` SET
`idTipoProceso`=pproceso,
`cajaFecha`=ffecha,
`cajaValor`=vvalor,
`cajaObservacion`=oobs,
`cajaMoneda`=mmoneda,
`cajaActivo`=aactivo
WHERE `idCaja`=idCaj ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `actualizarDatosCliente`;

DELIMITER ;;
CREATE  PROCEDURE `actualizarDatosCliente`(IN `apel` VARCHAR(100), IN `nomb` VARCHAR(200), IN `ddni` VARCHAR(8), IN `direc` VARCHAR(100), IN `corr` VARCHAR(100), IN `celu` VARCHAR(100), IN `telef` TEXT, IN `idCli` INT)
    NO SQL
begin 
UPDATE `Cliente` SET 
`cliApellidos`=apel,
`cliNombres`=nomb,
`cliDni`=ddni,
`cliDireccion`=direc,
`cliCorreo`=corr,
`cliCelular`=celu,
`cliFijo`=telef
where `idCliente`= idCli;
end ;;
DELIMITER ;

DROP PROCEDURE IF EXISTS `actualizarProducto`;

DELIMITER ;;
CREATE  PROCEDURE `actualizarProducto`(IN `idProd` INT, IN `nombre` TEXT, IN `monto` FLOAT, IN `intereses` FLOAT, IN `idCLi` INT, IN `activo` INT, IN `cantidad` INT)
    NO SQL
BEGIN
UPDATE `producto` SET `prodNombre`=nombre,`prodMontoEntregado`=monto,`prodInteres`=intereses,

`idCliente`=idCLi,
`prodActivo`=activo,
`prodCantidad`=cantidad
WHERE `idProducto`=idProd;

UPDATE `prestamo_producto` SET
`preInteres`=intereses,
`prActivo`=activo
WHERE `idProducto`=idProd;

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `cajaActivaHoy`;
DELIMITER ;;
CREATE  PROCEDURE `cajaActivaHoy`()
    NO SQL
SELECT cu.*, u.usuNombres FROM `cuadre` cu
inner join usuario u on u.idUsuario = cu.idUsuario
where  cu.cuaVigente =1 ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `cajaAperturar` ;
DELIMITER ;;
CREATE  PROCEDURE `cajaAperturar`(IN `idUser` INT, IN `monto` FLOAT, IN `obs` TEXT)
    NO SQL
INSERT INTO `cuadre`(`idCuadre`, `idUsuario`, `fechaInicio`, `fechaFin`, `cuaApertura`, `cuaCierre`, `cuaVigente`, `cuaObs`)
VALUES (null,idUser, now(),'0000-00-00',monto,0,1,obs) ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `cajaCierreHoy`;
DELIMITER ;;
CREATE  PROCEDURE `cajaCierreHoy`(IN `idUser` INT, IN `monto` FLOAT, IN `obs` TEXT)
    NO SQL
UPDATE `cuadre` SET
`fechaFin`=now(),
`cuaCierre` = monto,
`cuaObsCierre`= obs,
`cuaVigente`=0
WHERE  `cuaVigente`=1 ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `cambiarEstadoProducto`;
DELIMITER ;;
CREATE  PROCEDURE `cambiarEstadoProducto`(IN `estado` INT, IN `idProd` INT, IN `usuario` TEXT, IN `comentario` TEXT)
    NO SQL
BEGIN

UPDATE `producto` SET 
`prodQueEstado` = estado
where idProducto=idProd;

UPDATE `prestamo` SET 
`preIdEstado`=estado WHERE `idProducto`=idProd;

UPDATE `prestamo_producto` SET `presidTipoProceso`=estado WHERE `idProducto`=idProd;

INSERT INTO `reportes_producto`(`idReporte`, `idProducto`, `idDetalleReporte`, `repoValorMonetario`, `repoFechaOcurrencia`, `repoUsuario`, `repoUsuarioComentario`, `repoFechaConfirma`, `repoQueConfirma`, `repoQuienConfirma`) VALUES (null,idProd,estado,0,now(),usuario,comentario,'','','');

select idProd;
END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `coincidePass`;
DELIMITER ;;
CREATE  PROCEDURE `coincidePass`(IN `texto` VARCHAR(200), IN `idUser` INT)
    NO SQL
SELECT CASE md5(texto) WHEN usuPass THEN 1
 ELSE 0 END as result
 from usuario
 where idUsuario = idUser ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `contarNoFinalizados` ;
DELIMITER ;;
CREATE  PROCEDURE `contarNoFinalizados`(IN `idSuc` INT)
    NO SQL
SELECT count(idproducto) as Num
FROM `producto` p 
where prodactivo = 1 and datediff( now(), prodfechainicial )<=30
and idSucursal =idSuc ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `contarObservaciones` ;
DELIMITER ;;
CREATE  PROCEDURE `contarObservaciones`(IN `idProd` INT)
    NO SQL
begin
declare contador int;
declare contador2 int;
declare comentario varchar(200);
select prodObservaciones into comentario from producto where idProducto = idProd;

case comentario
when '' then 
 set contador =0;
else 
 set contador =1;
end CASE;

select count(*) into contador2 from avisos where idProducto = idProd;

select contador + contador2 as total;

end ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `contarVencidos` ;
DELIMITER ;;
CREATE  PROCEDURE `contarVencidos`(IN `idSuc` INT)
    NO SQL
SELECT count(p.idproducto) as Num
FROM `producto` p
inner join prestamo pre on pre.idProducto= p.idProducto
inner join desembolso d on d.idPrestamo= pre.idPrestamo
where prodactivo = 1 and datediff( now(), desFechaContarInteres )>30 and esCompra=0
and p.idSucursal =idSuc ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `crearTicket` ;
DELIMITER ;;
CREATE  PROCEDURE `crearTicket`(IN `idProd` INT, IN `tipoProc` INT, IN `monto` FLOAT, IN `obs` TEXT, IN `idUser` INT)
    NO SQL
BEGIN
SET @@session.time_zone='-05:00';

INSERT INTO `tickets`(`idTicket`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`) VALUES
(null,idProd,tipoProc,now(),monto,obs,0,idUser,0);

select last_insert_id() as idTicket;

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `crearTicketDepositar` ;
DELIMITER ;;
CREATE  PROCEDURE `crearTicketDepositar`(IN `idProd` INT, IN `tipoProc` INT, IN `monto` FLOAT, IN `obs` TEXT, IN `idUser` INT)
    NO SQL
BEGIN
SET @@session.time_zone='-05:00';

INSERT INTO `tickets`(`idTicket`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`) VALUES
(null,idProd,tipoProc,now(),monto,obs,0,idUser,0);

select last_insert_id() as idTicket;

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `datosBasicosUsuario` ;
DELIMITER ;;
CREATE  PROCEDURE `datosBasicosUsuario`(IN `idUser` INT)
    NO SQL
SELECT u.idUsuario, usunombres, usuapellido, usupoder, u.idsucursal, sucnombre
FROM usuario u inner join sucursal s on u.idSucursal=s.idSucursal
where u.idusuario=idUser and u.usuactivo=1 ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `dejarMensaje` ;
DELIMITER ;;
CREATE  PROCEDURE `dejarMensaje`(IN `mensaje` TEXT, IN `idProd` INT, IN `idUser` INT, IN `tipoMensaje` INT)
    NO SQL
INSERT INTO `avisos`(`idAviso`, `aviMensaje`, `idUsuario`, `aviFechaAutomatica`, `idProducto`, `tipoComentario`) VALUES (null,mensaje,idUser,now(), idProd, tipoMensaje) ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `eliminarProductoBD` ;
DELIMITER ;;
CREATE  PROCEDURE `eliminarProductoBD`(IN `idProd` INT)
    NO SQL
BEGIN
DELETE FROM `producto` WHERE
`idProducto`=idProd;
END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `encontrarCliente` ;
DELIMITER ;;
CREATE  PROCEDURE `encontrarCliente`(IN `dni` VARCHAR(8))
    NO SQL
SELECT c.*, ifnull(date_format(b.banFecha, '%d/%m/%Y'), '') as banFecha, ifnull(b.banMotivo, '') as banMotivo, ifnull(b.tipoBan, '') as tipoBan, nombreUsuario(b.idUsuario) as reportador, b.idBaneado FROM `Cliente` c 
	left join baneados b on b.idCliente = c.idCliente WHERE clidni = dni order by c.idCliente desc limit 1 ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `feriadosProximos` ;
DELIMITER ;;
CREATE  PROCEDURE `feriadosProximos`()
    NO SQL
SELECT * FROM `feriados`
where ferFecha between curdate() and DATE_ADD(curdate(), INTERVAL 1 MONTH) ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `ingresarDineroEntrada` ;
DELIMITER ;;
CREATE  PROCEDURE `ingresarDineroEntrada`(IN `valor` FLOAT, IN `motivo` TEXT, IN `idUser` INT)
    NO SQL
begin
INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idSucursal`, `idAprueba`) VALUES
(null,0,27,now(),valor, motivo,1, idUser, 1, 0);

set @caja=last_insert_id();
select @caja;
end ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `ingresarDineroSalida` ;
DELIMITER ;;
CREATE  PROCEDURE `ingresarDineroSalida`(IN `valor` FLOAT, IN `motivo` TEXT, IN `idUser` INT)
    NO SQL
begin INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idSucursal`, `idAprueba`) VALUES (null,0,28,now(),valor, motivo,1, idUser, 1, 0); set @caja=last_insert_id(); select @caja; end ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `ingresarPagoaCuenta`;
DELIMITER ;;
CREATE  PROCEDURE `ingresarPagoaCuenta`(IN `idPro` INT, IN `montoIn` FLOAT, IN `interesIn` FLOAT, IN `adelanto` FLOAT, IN `idUser` INT)
    NO SQL
INSERT INTO `PagoaCuenta`(`idPago`, `idProducto`, `pagMontoInicial`, `pagInteresInicial`, `pagFechaRegistro`, `pagAdelanto`, `idUsuario`)
VALUES (null,idPro,montoIn,interesIn,now(),adelanto,idUser) ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `ingresarPagoMaestro` ;
DELIMITER ;;
CREATE  PROCEDURE `ingresarPagoMaestro`(IN `idProd` INT, IN `quePago` INT, IN `cuanto` FLOAT, IN `fecha` TEXT, IN `idUser` INT, IN `obs` TEXT, IN `moneda` INT, IN `canti` INT)
    NO SQL
BEGIN
declare precioUnit float;

SET @@session.time_zone='-05:00';


INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, cajaMoneda, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) VALUES 
(null,idProd,quePago,fecha,cuanto,obs, moneda,1,idUser,0,1);
set @pago=last_insert_id();

CASE quePago
WHEN 20 THEN
	UPDATE `producto` SET 
    `prodCantidad`=`prodCantidad`-canti
    WHERE `idProducto`=idProd;
WHEN 44 THEN
	UPDATE `prestamo_producto` SET 
    `desFechaContarInteres`=fecha
    WHERE `idProducto`=idProd;
WHEN 45 THEN
	UPDATE `prestamo_producto` SET 
    `preCapital`=`preCapital`-cuanto,
    `desFechaContarInteres`=fecha
    WHERE `idProducto`=idProd;
when 32 THEN
	UPDATE `prestamo_producto` SET 
    `prActivo`=0
    WHERE `idProducto`=idProd;
    UPDATE `producto` SET 
    `prodActivo`=0
    where idProducto=idProd;
    UPDATE `cubicaje` SET `cuaVigente`=0 WHERE idProducto=idProd;
when 21 THEN
	UPDATE `caja` ca, producto p SET
	cajCantidad = canti,
    ca.`cajPrecioInicial`= (select p.prodMontoEntregado/ p.prodCantidad 	from producto where `idProducto`=idProd)
	where idCaja =  @pago;
    UPDATE `producto` SET 
    `prodMontoEntregado` = round(prodMontoEntregado -prodMontoEntregado/prodCantidad*canti,1),
    `prodCantidad`= prodCantidad - canti
    where idProducto=idProd;
    UPDATE `cubicaje` SET `cuaVigente`=0 WHERE idProducto=idProd;
when 43 THEN
	UPDATE `prestamo_producto` SET
    `preCapital`=`preCapital`+cuanto
    WHERE `idProducto`=idProd;
ELSE BEGIN END;
END CASE;

UPDATE `producto` SET
`prodActivo`=0
where `prodCantidad` <= 0 and `idProducto` = idProd;

UPDATE `prestamo_producto` SET
    `presidTipoProceso`=quePago
    WHERE `idProducto`=idProd;


select @pago;
END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `inicializarPrestamoV3` ;
DELIMITER ;;
CREATE  PROCEDURE `inicializarPrestamoV3`(IN `idCli` INT, IN `capital` FLOAT, IN `fecha` TEXT, IN `interes` FLOAT, IN `idUser` INT)
    NO SQL
BEGIN
SET @@session.time_zone='-05:00';
INSERT INTO `prestamo`(`idPrestamo`, `idCliente`, `preCapital`, `preFechaInicio`,  `prePorcentaje`,   `idUsuario`, `presActivo`, `presObservaciones`, `preIdEstado`, `idModo`)
	VALUES (null, idCli, capital,fecha,interes, idUser,1,'',3, 4);

set @idNewPrestamo=last_insert_id();


select @idNewPrestamo as idnuevoPrestamo;
END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertAlmacenv3` ;
DELIMITER ;;
CREATE  PROCEDURE `insertAlmacenv3`(IN `idProd` INT, IN `estantte` INT, IN `pisso` INT, IN `zonna` VARCHAR(2), IN `idUser` INT, IN `obs` TEXT)
    NO SQL
BEGIN
declare nidPiso int;
declare nidZona int;

select idPiso into nidPiso from piso where numpiso = pisso ;
select idZona into nidZona from seccion where zonna = zonaDescripcion ;

SET @@session.time_zone='-05:00';

INSERT INTO `cubicaje`(`idCubicaje`, `idProducto`, `idTipoProceso`, `idUsuario`, `cubFecha`, `cubObservacion`, `Idestante`, `idPiso`, `idZona`) 
VALUES (null,idProd, 23 ,idUser, now() ,obs,estantte,nidPiso,nidZona);

UPDATE `prestamo_producto` SET
`idCubicajeEstado`=23
WHERE `idProducto`=idProd;

set @idCub=last_insert_id();
select @idCub as 'idCubicaje';

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarAdelantoAProducto` ;
DELIMITER ;;
CREATE  PROCEDURE `insertarAdelantoAProducto`(IN `idProd` INT, IN `nuevoAdelanto` FLOAT, IN `idUser` VARCHAR(200))
    NO SQL
begin
UPDATE `producto` SET 
`prodObservaciones` =concat('Se adelant� S/. ',round(nuevoAdelanto,2),' de S/. ' , round(`prodMontoEntregado`,2), ' el d�a ', DATE_FORMAT(now(), "%d/%m/%Y"), '<br>', `prodObservaciones`),
`prodFechaInicial`=now(),
`prodMontoEntregado`=`prodMontoEntregado`-nuevoAdelanto,
`prodAdelanto`=`prodAdelanto` +nuevoAdelanto

WHERE 
`idProducto`= idProd;


INSERT INTO `reportes_producto`(
    `idReporte`, `idProducto`, `idDetalleReporte`, `repoValorMonetario`, `repoFechaOcurrencia`, `repoUsuario`,
`repoUsuarioComentario`, `repoQueConfirma`, `repoQuienConfirma`) VALUES (
     null,idProd,2,nuevoAdelanto,now(),idUser,
    '',4,'Todav�a sin aprobaci�n');

end ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarAmortizacionMixto` ;
DELIMITER ;;
CREATE  PROCEDURE `insertarAmortizacionMixto`(IN `idDesemb` INT, IN `monotoInicial` FLOAT, IN `montoInteres` FLOAT, IN `montoPago` FLOAT, IN `idUser` INT, IN `idProd` INT, IN `usuario` TEXT, IN `idSuc` INT, IN `sobra` FLOAT)
    NO SQL
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
    '',4,'Todav�a sin aprobaci�n');


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

`desFechaContarInteres`=now()
WHERE  `idPrestamo`=@idPrest;

select @pago;



END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarAmortizacionPocoInteres` ;
DELIMITER ;;
CREATE  PROCEDURE `insertarAmortizacionPocoInteres`(IN `idDesemb` INT, IN `monotoInicial` FLOAT, IN `montoInteres` FLOAT, IN `montoPago` FLOAT, IN `idUser` INT, IN `idProd` INT, IN `usuario` TEXT, IN `idSuc` INT)
    NO SQL
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
    '',4,'Todav�a sin aprobaci�n');




select @pago;

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarAmortizacionSoloInteres`;
DELIMITER ;;
CREATE  PROCEDURE `insertarAmortizacionSoloInteres`(IN `idDesemb` INT, IN `monotoInicial` FLOAT, IN `montoInteres` FLOAT, IN `montoPago` FLOAT, IN `idUser` INT, IN `idProd` INT, IN `usuario` TEXT, IN `idSuc` INT)
    NO SQL
BEGIN
set @idPrest=(select idprestamo from prestamo where idProducto=idProd);

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
    '',4,'Todav�a sin aprobaci�n');

UPDATE `producto` SET 
`prodFechaInicial`=now(),
`prodUltimaFechaInteres`= DATE_FORMAT(now(), "%d/%m/%Y")
WHERE 
`idProducto` = idProd;


UPDATE `desembolso` SET
`desFechaContarInteres`=now()
WHERE  `idPrestamo`=@idPrest;

select @pago;

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarAmortizacionTodo` ;
DELIMITER ;;
CREATE  PROCEDURE `insertarAmortizacionTodo`(IN `idDesemb` INT, IN `monotoInicial` FLOAT, IN `montoInteres` FLOAT, IN `montoPago` FLOAT, IN `idUser` INT, IN `idProd` INT, IN `usuario` TEXT, IN `idSuc` INT)
    NO SQL
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
    '',4,'Todav�a sin aprobaci�n');

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

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarCocheraMovimiento` ;
DELIMITER ;;
CREATE  PROCEDURE `insertarCocheraMovimiento`(IN `idPla` INT, IN `precio` DOUBLE, IN `fecha` DATETIME, IN `idUser` INT)
    NO SQL
BEGIN
INSERT INTO `cocheraregistros`(`idCocheraReg`, `idPlaca`, `movPrecio`, `movFecha`, `idUser`, `idProceso`) 
VALUES (null,idPla,precio,fecha,idUser,25);

INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idSucursal`, `idAprueba`) VALUES
(null,0,27,now(),precio, 27,1, idUser, 1, 0);

set @caja=last_insert_id();
select @caja;
END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarCompraNew` ;
DELIMITER ;;
CREATE  PROCEDURE `insertarCompraNew`(IN `cliNomb` VARCHAR(50), IN `cliApelli` VARCHAR(50), IN `cliDirec` VARCHAR(200), IN `dni` VARCHAR(50), IN `email` VARCHAR(50), IN `celular` VARCHAR(50), IN `nomProd` VARCHAR(200), IN `montoentregado` FLOAT, IN `fechainicial` DATE, IN `observaciones` TEXT, IN `usuario` INT, IN `idSuc` INT, IN `fechaRegistro` TEXT)
BEGIN
INSERT INTO `Cliente`
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

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarCompraSolo` ;
DELIMITER ;;
CREATE  PROCEDURE `insertarCompraSolo`(IN `nomProd` VARCHAR(200), IN `montoentregado` FLOAT, IN `fechainicial` DATE, IN `observaciones` TEXT, IN `usuario` INT, IN `idCl` INT, IN `idSuc` INT, IN `fechaRegistro` TEXT)
    NO SQL
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

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarCompraSoloV3`;
DELIMITER ;;
CREATE  PROCEDURE `insertarCompraSoloV3`(IN `nomProd` VARCHAR(200), IN `montoentregado` FLOAT, IN `fechainicial` DATE, IN `observaciones` TEXT, IN `usuario` INT, IN `fechaRegistro` TEXT, IN `tipoProd` INT, IN `cantProd` INT)
    NO SQL
BEGIN
SET @@session.time_zone='-05:00';
INSERT INTO `producto`(`idProducto`, `prodNombre`, `prodMontoEntregado`, `prodInteres`, `prodFechaInicial`, `prodFechaVencimiento`, `prodObservaciones`, `prodMontoPagar`, `idCliente`, `prodActivo`, `prodFechaRegistro`, `idUsuario`, `idSucursal`, `esCompra`, `idTipoProducto`, prodCantidad)
VALUES
(null,
lower(trim(nomProd)),
montoentregado, 0, fechainicial,
fechaRegistro, observaciones,0,
699,
1, fechaRegistro, usuario, 1, 1, tipoProd, cantProd);

set @compr=last_insert_id();

INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) VALUES (null,@compr,38,now(),montoentregado,observaciones,1,usuario,0,1);

select @compr;

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarCubicaje` ;
DELIMITER ;;
CREATE  PROCEDURE `insertarCubicaje`(IN `idProd` INT, IN `tipoProc` INT, IN `idUser` INT, IN `obs` TEXT, IN `estant` INT, IN `pis` INT, IN `zon` INT)
    NO SQL
BEGIN
SET @@session.time_zone='-05:00';

INSERT INTO `cubicaje`(`idCubicaje`, `idProducto`, `idTipoProceso`, `idUsuario`, `cubFecha`, `cubObservacion`, `Idestante`, `idPiso`, `idZona`) 
VALUES (null,idProd, tipoProc,idUser, now() ,obs,estant,pis,zon);

UPDATE `prestamo_producto` SET
`idCubicajeEstado`=tipoProc
WHERE `idProducto`=idProd;

set @idCub=last_insert_id();
select @idCub as 'idCubicaje';
END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarInteresAdelanto` ;
DELIMITER ;;
CREATE  PROCEDURE `insertarInteresAdelanto`(IN `idDesemb` INT, IN `monotoInicial` FLOAT, IN `montoInteres` FLOAT, IN `montoPago` FLOAT, IN `idUser` INT, IN `idProd` INT, IN `usuario` TEXT, IN `idSuc` INT)
    NO SQL
BEGIN
INSERT INTO `PagoaCuenta`(`idPago`, `idDesembolso`, `pagMonto`, `pagInteres`, `pagCantidadPagada`, `pagDebeInteres`, `pagFechaRegistro`, `idUsuario`, `idTipoPago`)
VALUES (null,idDesemb,monotoInicial,montoInteres,montoPago,0,now(),idUser,9);

set @pago=last_insert_id();

INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) VALUES (null,idProd,9,now(),montoPago,
'',1,idUser,0,idSuc);

INSERT INTO `reportes_producto`(
    `idReporte`, `idProducto`, `idDetalleReporte`, `repoValorMonetario`, `repoFechaOcurrencia`, `repoUsuario`,
`repoUsuarioComentario`, `repoQueConfirma`, `repoQuienConfirma`) VALUES (
     null,idProd,9,
     montoPago,now(),usuario,
    '',4,'Todav�a sin aprobaci�n');
    
select @pago;

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarInventarioNegativo`;
DELIMITER ;;
CREATE  PROCEDURE `insertarInventarioNegativo`(IN `idProd` INT, IN `idUser` INT, IN `obs` TEXT)
    NO SQL
begin 
SET @@session.time_zone='-05:00';
INSERT INTO `inventario`(`idInventario`, `idProducto`, `invFechaInventario`, `idUsuario`, `invObservaciones`, `invExiste`)
VALUES (null,idProd,now(),idUser,obs,0);

UPDATE `producto` SET `prodActivo` = b'0' WHERE `producto`.`idProducto` = idProd;

set @idInv=last_insert_id();
select @idInv as idInvent;

end ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarInventarioPositivo` ;
DELIMITER ;;
CREATE  PROCEDURE `insertarInventarioPositivo`(IN `idProd` INT, IN `idUser` INT, IN `obs` TEXT)
    NO SQL
begin 
SET @@session.time_zone='-05:00';
INSERT INTO `inventario`(`idInventario`, `idProducto`, `invFechaInventario`, `idUsuario`, `invObservaciones`, `invExiste`)
VALUES (null,idProd,now(),idUser,obs,1);

UPDATE `producto` SET `prodActivo` = b'1' WHERE `producto`.`idProducto` = idProd;

set @idInv=last_insert_id();
select @idInv as idInvent;

end ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarNuevoDesembolso` ;
DELIMITER ;;
CREATE  PROCEDURE `insertarNuevoDesembolso`(IN `capital` FLOAT, IN `idProd` INT, IN `idUser` INT, IN `idSuc` INT)
    NO SQL
BEGIN

UPDATE `producto` SET `prodMontoPagar` = `prodMontoPagar`+ capital WHERE `producto`.`idProducto` = idProd;

UPDATE `desembolso` SET `desCapital` = `desCapital`+ capital WHERE `desembolso`.`idDesembolso` = idProd;

UPDATE `prestamo` SET `preCapital` = `preCapital`+ capital WHERE `prestamo`.`idPrestamo` = idProd;

INSERT INTO `caja`
(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) VALUES
(null,idProd,14,now(),capital,'',1,idUser,0,idSuc);

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarPlaca`;
DELIMITER ;;
CREATE  PROCEDURE `insertarPlaca`(IN `placa` TEXT, IN `tipo` INT, IN `idProd` INT)
    NO SQL
BEGIN
INSERT INTO `placas`(`idPlaca`, `placaSerie`, `idTipoVehiculo`, `idProducto`) VALUES (null,placa,tipo, idProd);
select idProd;
END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarProcesoOmiso` ;
DELIMITER ;;
CREATE  PROCEDURE `insertarProcesoOmiso`(IN `idProd` INT, IN `tipo` INT, IN `valor` FLOAT, IN `obs` TEXT, IN `idUser` INT, IN `moneda` INT, IN `porcExtra` FLOAT)
    NO SQL
BEGIN
SET @@session.time_zone='-05:00';
INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`, cajaMoneda, `cajPorcentaje`)
VALUES
(null,idProd,tipo,now(),valor,obs,1,idUser,0,1, moneda, porcExtra);
end ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarProductoNew` ;
DELIMITER ;;
CREATE  PROCEDURE `insertarProductoNew`(IN `cliNomb` VARCHAR(50), IN `cliApelli` VARCHAR(50), IN `cliDirec` VARCHAR(200), IN `dni` VARCHAR(50), IN `email` VARCHAR(50), IN `celular` VARCHAR(50), IN `nomProd` VARCHAR(200), IN `montoentregado` FLOAT, IN `interes` FLOAT, IN `montopagar` FLOAT, IN `fechainicial` DATE, IN `feachavencimiento` DATE, IN `observaciones` TEXT, IN `usuario` INT, IN `idSuc` INT, IN `fechaRegistro` TEXT)
BEGIN
INSERT INTO `Cliente`
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

INSERT INTO `producto`
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

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarProductoSolo` ;
DELIMITER ;;
CREATE  PROCEDURE `insertarProductoSolo`(IN `nomProd` VARCHAR(200), IN `montoentregado` FLOAT, IN `interes` FLOAT, IN `montopagar` FLOAT, IN `fechainicial` DATE, IN `feachavencimiento` DATE, IN `observaciones` TEXT, IN `usuario` INT, IN `idCl` INT, IN `idSuc` INT, IN `fechaRegistro` TEXT)
    NO SQL
BEGIN

INSERT INTO `producto`
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

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarProductov3`;
DELIMITER ;;
CREATE  PROCEDURE `insertarProductov3`(IN `nomProd` TEXT, IN `montoentregado` FLOAT, IN `interes` FLOAT, IN `fechainicial` TEXT, IN `observaciones` TEXT, IN `idCli` INT, IN `idUser` INT, IN `idPrest` INT, IN `cantProd` INT, IN `idTipo` INT)
    NO SQL
BEGIN
SET @@session.time_zone='-05:00';
INSERT INTO `producto`
(`idProducto`,
`prodNombre`,
`prodMontoEntregado`,
`prodInteres`,
`prodFechaInicial`,
`prodFechaVencimiento`,
`prodObservaciones`,
`prodMontoPagar`,
`idCliente`,
`prodActivo`, `prodFechaRegistro`, `IdUsuario`, `idSucursal`, `prodCantidad`, `idTipoProducto` )
VALUES
(null,
lower(trim(nomProd)),
montoentregado,
interes,
fechainicial,
now(),
observaciones,
0,
idCli,
1, now(), idUser, 1, cantProd, idTipo);

set @prod=last_insert_id();

INSERT INTO `prestamo_producto`(`idPrestamo`, `idProducto`, `presidTipoProceso`, `desFechaContarInteres`, `preInteres`, `preCapital` )
VALUES (idPrest,@prod,28,fechainicial,interes, montoentregado );

INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) VALUES (null,@prod,28,now(),montoentregado,observaciones,1,idUser,0,1);


select @prod as idProd;

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarSucursalNueva` ;
DELIMITER ;;
CREATE  PROCEDURE `insertarSucursalNueva`(IN `sucNom` VARCHAR(200), IN `sucLug` VARCHAR(200))
    NO SQL
INSERT INTO `sucursal`(`idSucursal`, `sucNombre`, `sucLugar`)
VALUES (null ,sucNom,sucLug) ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertarUsuario` ;
DELIMITER ;;
CREATE  PROCEDURE `insertarUsuario`(IN `nombre` VARCHAR(50), IN `apellido` VARCHAR(50), IN `nick` VARCHAR(50), IN `pass` VARCHAR(50), IN `poder` INT)
    NO SQL
BEGIN
INSERT INTO `usuario`(`idUsuario`, `usuNombres`, `usuApellido`,
                      `usuNick`, `usuPass`, `usuPoder`,
                      `idSucursal`, `usuActivo`) 
VALUES (null,apellido,nombre,nick,md5(pass),poder,1,1);

set @id = (select LAST_INSERT_ID());
select @id;

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `insertClienteV3` ;
DELIMITER ;;
CREATE  PROCEDURE `insertClienteV3`(IN `apellido` TEXT, IN `nombre` TEXT, IN `dni` TEXT, IN `direccion` TEXT, IN `correo` TEXT, IN `celular` TEXT, IN `fijo` TEXT)
    NO SQL
BEGIN

INSERT INTO `Cliente`(`idCliente`, `cliApellidos`, `cliNombres`, `cliDni`, `cliDireccion`, `cliCorreo`, `cliCelular`, `cliFijo`, `cliCalificacion`, `cliActivo`)
VALUES (null, apellido, nombre, dni, direccion, correo, celular, fijo, 0,1 );

set @idCLi=last_insert_id();
select @idCli;

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `inserttempo` ;
DELIMITER ;;
CREATE  PROCEDURE `inserttempo`(IN `fech` TEXT)
    NO SQL
insert into tempo
(idtempo, fecha)
values(null, fech) ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarBuscarIdProducto`;
DELIMITER ;;
CREATE  PROCEDURE `listarBuscarIdProducto`(IN `texto` TEXT)
    NO SQL
select idproducto, prodnombre, cliapellidos, clinombres, prodMontoEntregado, prodfecharegistro
from producto p
inner join Cliente c on p.idcliente = c.idCliente
where idProducto =texto ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarBuscarNombreCliente`;
DELIMITER ;;
CREATE  PROCEDURE `listarBuscarNombreCliente`(IN `texto` TEXT)
    NO SQL
SELECT * FROM `Cliente`
where (concat(cliApellidos, ' ', cliNombres) like concat('%',texto,'%')
and cliActivo =1) or (cliDni = texto and cliActivo =1)
order by cliApellidos asc ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarBuscarNombreProducto` ;
DELIMITER ;;
CREATE  PROCEDURE `listarBuscarNombreProducto`(IN `texto` TEXT)
    NO SQL
select idproducto, prodnombre, cliapellidos, clinombres, prodMontoEntregado, prodfecharegistro, c.idCliente, p.prodActivo
from producto p inner join Cliente c on p.idcliente = c.idCliente where prodNombre like concat( '%', texto, '%')
order by p.prodActivo desc, prodnombre asc ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarCategoriaEnProductos` ;
DELIMITER ;;
CREATE  PROCEDURE `listarCategoriaEnProductos`(IN `idCat` TEXT)
    NO SQL
SELECT pp.*, concat( c.cliApellidos, ' ', c.cliNombres ) as cliNombres, p.prodNombre, round(pp.preCapital,2 ) as pCapital, pr.idCliente
FROM `prestamo_producto` pp
inner join producto p on p.idProducto = pp.idProducto
inner join prestamo pr on pr.idPrestamo = pp.idPrestamo
inner join cliente c on c.idCliente = pr.idCliente
where presidTipoProceso in (idCat) ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarDesembolsosPorPrestamos`;
DELIMITER ;;
CREATE  PROCEDURE `listarDesembolsosPorPrestamos`(IN `idDesemb` TEXT)
    NO SQL
SELECT d.*, u.usuNick, p.preFechaInicio FROM `desembolso` d
inner join prestamo p on p.idPrestamo=d.idPrestamo
inner join usuario u on u.idUsuario=d.desusuario

WHERE FIND_IN_SET( d.`idPrestamo`, idDesemb) ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarInventarioPorEstado` ;
DELIMITER ;;
CREATE  PROCEDURE `listarInventarioPorEstado`(IN `tipo` INT)
    NO SQL
SELECT i.*, concat( c.cliApellidos, ' ', c.cliNombres) as cliNombres, c.idCliente, p.prodNombre 
FROM `inventario` i
inner join producto p on p.idProducto = i.idProducto
inner join Cliente c on c.idCliente = p.idCliente
where invExiste = tipo ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarInventarioPorId` ;
DELIMITER ;;
CREATE  PROCEDURE `listarInventarioPorId`(IN `idProd` INT)
    NO SQL
SELECT i.*, u.usuNombres,
case invExiste when 1 then 'Presente en almac�n' else 'No se encontr� la existencia' end as caso
FROM `inventario` i
inner join `usuario` u on u.idUsuario = i.idUsuario
where idProducto=idProd
order BY idInventario DESC ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarMontoPrestamoActual` ;
DELIMITER ;;
CREATE  PROCEDURE `listarMontoPrestamoActual`(IN `idProd` INT)
    NO SQL
BEGIN

set @idPrest=(select idprestamo from prestamo where idProducto=idProd);
SELECT desCapital, desFechaContarInteres, pr.preCapital FROM `desembolso` d
inner join prestamo pr on d.idPrestamo=pr.idPrestamo
where d.idPrestamo=@idPrest;

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarMovimientoFinal` ;
DELIMITER ;;
CREATE  PROCEDURE `listarMovimientoFinal`(IN `idProd` INT)
    NO SQL
SELECT rp.*, p.prodNombre, dr.repoDescripcion, dr1.repoDescripcion as 'estadoConfirmacion' 
FROM
producto p inner join 
`reportes_producto` rp on p.idProducto= rp.idProducto
inner join DetalleReporte dr on dr.idDetalleReporte= rp.idDetalleReporte
inner join DetalleReporte dr1 on dr1.idDetalleReporte= rp.repoQueConfirma
where rp.idproducto= idProd
order by repofechaocurrencia desc, repoUsuario asc ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarMovimientosAlmacen` ;
DELIMITER ;;
CREATE  PROCEDURE `listarMovimientosAlmacen`(IN `idProd` INT)
    NO SQL
SELECT cu.*, es.estDescripcion, pi.pisoDescripcion, se.zonaDescripcion, tp.tipoDescripcion, u.usuNombres FROM `cubicaje` cu
inner join tipoProceso tp on tp.idTipoProceso = cu.idTipoProceso
inner join estante es on es.idEstante = cu.idEstante
inner join piso pi on pi.idPiso = cu.idPiso
inner join seccion se on se.idZona = cu.idZona
inner join usuario u on u.idUsuario = cu.idUsuario
where cu.idProducto =idProd
order by cu.idCubicaje ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarMovimientosCajaPorIdProducto` ;
DELIMITER ;;
CREATE  PROCEDURE `listarMovimientosCajaPorIdProducto`(IN `idProd` INT)
    NO SQL
SELECT c.*, u.usuNombres, tipoDescripcion, p.idPrestamo FROM `caja` c
inner join tipoProceso tp on tp.idTipoProceso=c.idtipoProceso
inner join prestamo p on c.idproducto=p.idProducto
left join usuario u on u.idUsuario=c.idusuario
where c.idProducto=idProd and c.idtipoproceso<>3 ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarMovimientosFechaDia`;
DELIMITER ;;
CREATE  PROCEDURE `listarMovimientosFechaDia`(IN `fecha` TEXT)
    NO SQL
SELECT rp.idproducto, p.prodNombre, dr.repoDescripcion, repoUsuario, repoValorMonetario, repoFechaOcurrencia
FROM `reportes_producto` rp
inner join producto p on p.idproducto=rp.idproducto
inner join DetalleReporte dr on dr.idDetalleReporte=rp.iddetallereporte
where DATE_FORMAT(repoFechaOcurrencia,'%Y-%m-%d')= DATE_FORMAT(fecha, '%Y-%m-%d')
order by repoFechaOcurrencia asc ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarMovimientosRegistradosFechaDia` ;
DELIMITER ;;
CREATE  PROCEDURE `listarMovimientosRegistradosFechaDia`(IN `fecha` TEXT)
    NO SQL
select idproducto, prodNombre, 'Nuevo cr�dito' as repoDescripcion, usuNombres, prodMontoEntregado, prodfecharegistro
from producto p inner join usuario u on u.idUsuario=p.idUsuario
where DATE_FORMAT(prodfecharegistro,'%Y-%m-%d')= DATE_FORMAT(fecha, '%Y-%m-%d') ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarMovimientosSinAprobar`;
DELIMITER ;;
CREATE  PROCEDURE `listarMovimientosSinAprobar`(IN `idSuc` INT)
    NO SQL
SELECT rp.*, p.prodNombre, dr.repoDescripcion, dr1.repoDescripcion as 'estadoConfirmacion' 
FROM
producto p inner join 
`reportes_producto` rp on p.idProducto= rp.idProducto
inner join DetalleReporte dr on dr.idDetalleReporte= rp.idDetalleReporte
inner join DetalleReporte dr1 on dr1.idDetalleReporte= rp.repoQueConfirma
where repofechaConfirma ='' and p.idSucursal= idSuc
order by repofechaocurrencia desc, repoUsuario asc ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarPrestamosPorIdProducto` ;
DELIMITER ;;
CREATE  PROCEDURE `listarPrestamosPorIdProducto`(IN `idProd` INT)
    NO SQL
SELECT p.`idPrestamo`,`preCapital`,`preFechaInicio`, `preIdEstado`, tp.tipoDescripcion, `usunick`,d.desFechaContarInteres
FROM `prestamo` p
inner join tipoProceso tp on p.preIdEstado=tp.idTipoProceso
inner join usuario u on u.idUsuario= p.idUsuario
inner join desembolso d on d.idPrestamo=p.idPrestamo
WHERE 
p.idproducto=idProd ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarProductoEspecificoCliente` ;
DELIMITER ;;
CREATE  PROCEDURE `listarProductoEspecificoCliente`(IN `idPro` INT)
    NO SQL
SELECT * FROM `producto` p
INNER join Cliente c on p.idcliente = c.idcliente
WHERE 
idproducto= idPro
order by prodfechainicial desc ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarProductoPorId` ;
DELIMITER ;;
CREATE  PROCEDURE `listarProductoPorId`(IN `idProd` INT)
    NO SQL
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
END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarProductosActivos`;
DELIMITER ;;
CREATE  PROCEDURE `listarProductosActivos`()
    NO SQL
SELECT pp.*, concat( c.cliApellidos, ' ', c.cliNombres ) as cliNombres, p.prodNombre, round(pp.preCapital,2 ) as pCapital, pr.idCliente
FROM `prestamo_producto` pp
inner join producto p on p.idProducto = pp.idProducto
inner join prestamo pr on pr.idPrestamo = pp.idPrestamo
inner join cliente c on c.idCliente = pr.idCliente
where prodactivo =1 ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarProductosEmpenosv3` ;
DELIMITER ;;
CREATE  PROCEDURE `listarProductosEmpenosv3`()
    NO SQL
SELECT p.idProducto, prodNombre,
prodMontoEntregado,
concat(cliapellidos, ', ', clinombres) as cliNombres,
prodFechainicial, desFechaContarInteres, ultimoPagoDelCliente(p.idproducto) as ultimoPago, c.idCliente, datediff( now(), desFechaContarInteres ) as diasDeuda, p.prodCantidad, pre.preCapital
FROM `producto` p inner join Cliente c
on c.idcliente = p.idcliente
inner join prestamo_producto pre on pre.idProducto= p.idProducto

where prodactivo = 1 and esCompra=0
order by desFechaContarInteres asc ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarProductosPorAprobar` ;
DELIMITER ;;
CREATE  PROCEDURE `listarProductosPorAprobar`(IN `oficina` INT)
    NO SQL
SELECT idProducto, prodNombre,
prodCuantoFinaliza, prodQuienFinaliza, prodFechaFinaliza

FROM `producto`
where prodAprobado=0  and prodActivo=0
and idSucursal=oficina
ORDER BY `producto`.`idProducto`  DESC ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarProductosPorCliente` ;
DELIMITER ;;
CREATE  PROCEDURE `listarProductosPorCliente`(IN `idCli` INT)
BEGIN
select p.*, s.sucNombre from producto p
inner join sucursal s on p.idSucursal= s.idSucursal
where idcliente =idCli
order by prodFechaRegistro desc;
END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarProductosPorClienteODni` ;
DELIMITER ;;
CREATE  PROCEDURE `listarProductosPorClienteODni`(IN `campo` TEXT)
    NO SQL
select p.idProducto, p.prodNombre, prodFechaRegistro, prodMontoEntregado,  `cliApellidos`,`cliNombres`,prodActivo
from producto p
inner join Cliente c on c.idCliente= p.idCliente
where concat(lower(cliApellidos), ' ', lower(cliNombres)) like concat('%', campo, '%')
or clidni = campo
order by prodActivo desc, cliApellidos asc ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarProductosProrrogav3` ;
DELIMITER ;;
CREATE  PROCEDURE `listarProductosProrrogav3`()
    NO SQL
SELECT p.idProducto, prodNombre,
prodMontoEntregado,
concat(cliapellidos, ', ', clinombres) as cliNombres,
prodFechainicial, desFechaContarInteres, ultimoPagoDelCliente(p.idproducto) as ultimoPago, c.idCliente, datediff( now(), desFechaContarInteres ) as diasDeuda, p.prodCantidad, pre.preCapital
FROM `producto` p inner join Cliente c
on c.idcliente = p.idcliente
inner join prestamo_producto pre on pre.idProducto= p.idProducto

where prodactivo = 1 and datediff( now(), desFechaContarInteres )>=25 and datediff( now(), desFechaContarInteres )<=35 and esCompra=0
order by desFechaContarInteres asc ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarProductosTipos` ;
DELIMITER ;;
CREATE  PROCEDURE `listarProductosTipos`()
    NO SQL
SELECT * FROM `tipoProducto`
order by tipopDescripcion asc ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarProductosVencidos` ;
DELIMITER ;;
CREATE  PROCEDURE `listarProductosVencidos`(IN `idSuc` INT)
    NO SQL
SELECT p.idproducto, prodNombre,
prodMontoEntregado,
concat(cliapellidos, ', ', clinombres) as propietario,
prodFechainicial, desFechaContarInteres, ultimoPagoDelCliente(p.idproducto) as ultimoPago
FROM `producto` p inner join Cliente c
on c.idcliente = p.idcliente
inner join prestamo pre on pre.idProducto= p.idProducto
inner join desembolso d on d.idPrestamo= pre.idPrestamo
where prodactivo = 1 and datediff( now(), desFechaContarInteres )>30 and esCompra=0
and p.idSucursal = idSuc
order by desFechaContarInteres asc ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarProductosVencidosv3` ;
DELIMITER ;;
CREATE  PROCEDURE `listarProductosVencidosv3`()
    NO SQL
SELECT p.idProducto, prodNombre,
prodMontoEntregado,
concat(cliapellidos, ', ', clinombres) as cliNombres,
prodFechainicial, desFechaContarInteres, ultimoPagoDelCliente(p.idproducto) as ultimoPago, c.idCliente,
case when prs.presFechaCongelacion <>'' then datediff( presFechaCongelacion, desFechaContarInteres ) else datediff( now(), desFechaContarInteres ) end as diasDeuda,
p.prodCantidad,
concat(c.cliCelular, '/', c.cliFijo) as cliCelular, pre.preCapital
FROM `producto` p inner join Cliente c
on c.idcliente = p.idcliente
left join prestamo_producto pre on pre.idProducto= p.idProducto
left join prestamo prs on prs.idPrestamo = pre.idPrestamo

where prodactivo = 1 and datediff( now(), desFechaContarInteres )>35 and esCompra=0
order by desFechaContarInteres asc ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarProductosVigentesv3` ;
DELIMITER ;;
CREATE  PROCEDURE `listarProductosVigentesv3`()
    NO SQL
SELECT p.idProducto, prodNombre,
prodMontoEntregado,
concat(cliapellidos, ', ', clinombres) as cliNombres,
prodFechainicial, desFechaContarInteres, ultimoPagoDelCliente(p.idproducto) as ultimoPago, c.idCliente, datediff( now(), desFechaContarInteres ) as diasDeuda, p.prodCantidad, pre.preCapital
FROM `producto` p inner join Cliente c
on c.idcliente = p.idcliente
inner join prestamo_producto pre on pre.idProducto= p.idProducto

where prodactivo = 1 and datediff( now(), desFechaContarInteres )<=35 and esCompra=0
order by desFechaContarInteres asc ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarSoloCompras` ;
DELIMITER ;;
CREATE  PROCEDURE `listarSoloCompras`()
    NO SQL
SELECT *
FROM 
producto p
where prodactivo =1  and p.escompra =1 ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarTicketsNoAprobado` ;
DELIMITER ;;
CREATE  PROCEDURE `listarTicketsNoAprobado`(IN `fecha` TEXT)
    NO SQL
SELECT t.*, p.prodNombre, u.usuNombres, tp.tipoDescripcion FROM `tickets` t
inner join producto p on t.idProducto = p.idProducto
inner join usuario u on u.idUsuario = t.idUsuario
inner join tipoProceso tp on tp.idTipoProceso = t.idTipoProceso
where date_format(cajaFecha, '%Y-%m-%d') = date_format(fecha, '%Y-%m-%d')
and cajaActivo =2
and idAprueba<>0 ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarTicketsSinAprobar` ;
DELIMITER ;;
CREATE  PROCEDURE `listarTicketsSinAprobar`(IN `fecha` TEXT)
    NO SQL
SELECT t.*, p.prodNombre, u.usuNombres, tp.tipoDescripcion FROM `tickets` t
inner join producto p on t.idProducto = p.idProducto
inner join usuario u on u.idUsuario = t.idUsuario
inner join tipoProceso tp on tp.idTipoProceso = t.idTipoProceso
where ( date_format(cajaFecha, '%Y-%m-%d') = date_format(fecha, '%Y-%m-%d') or t.idTipoProceso in ( 18, 19) )
and cajaActivo in (0, 1)

order by t.idTicket asc ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarTipoProductoRecomendaciones` ;
DELIMITER ;;
CREATE  PROCEDURE `listarTipoProductoRecomendaciones`(IN `tipo` INT)
    NO SQL
select t.tipopRecomendacion
from tipoProducto t
where t.idTipoProducto=tipo ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarTodoProductosPorSuc` ;
DELIMITER ;;
CREATE  PROCEDURE `listarTodoProductosPorSuc`(IN `desde` INT, IN `hasta` INT, IN `idSuc` INT)
    NO SQL
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
end ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarTodoProductosSinSuc` ;
DELIMITER ;;
CREATE  PROCEDURE `listarTodoProductosSinSuc`(IN `desde` INT, IN `hasta` INT)
    NO SQL
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
end ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarTodosProductosEnBDSucursal` ;
DELIMITER ;;
CREATE  PROCEDURE `listarTodosProductosEnBDSucursal`(IN `idSuc` INT)
    NO SQL
SELECT p.idproducto, prodnombre, prodMontoEntregado, 
DATE_FORMAT(prodFechaRegistro,'%d/%m/%Y') AS prodFechaRegistro,
c.cliApellidos, c.cliNombres, u.usuNick as nick,
case prodActivo when 1 then 'Si' else 'No' end as 'prodActivo'

FROM `producto` p
inner join Cliente c on c.idCliente = p.idcliente
inner join usuario u on u.idUsuario= p.idusuario
where p.idSucursal=idSuc
order by idproducto desc ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarTodosProductosNoFinalizados` ;
DELIMITER ;;
CREATE  PROCEDURE `listarTodosProductosNoFinalizados`(IN `idSuc` INT)
    NO SQL
SELECT c.*, p.*, u.usuNombres FROM `producto` p
inner join Cliente c
on p.idcliente = c.idcliente
inner join usuario u
on p.idusuario = u.idusuario
where prodactivo =1 and p.idSucursal = idSuc  and datediff( now(), prodFechainicial )<=30
order by prodfechainicial asc ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarTodosUsuarios` ;
DELIMITER ;;
CREATE  PROCEDURE `listarTodosUsuarios`()
    NO SQL
SELECT u.`idUsuario`, concat( `usuNombres`, ' ',  `usuApellido` ) as nombre, p.`descripcion`,  sucLugar 
FROM `usuario` u inner join sucursal s
on u.`idSucursal`= s.`idSucursal`
inner join poder p on p.idPoder=usuPoder
WHERE `usuActivo`=1
order by usuNombres ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `listarUnUsuario`;
DELIMITER ;;
CREATE  PROCEDURE `listarUnUsuario`(IN `idUser` INT)
    NO SQL
SELECT u.`idUsuario`, `usuNombres` , `usuApellido`, usuNick,
p.idPoder,  u.idSucursal
FROM `usuario` u inner join sucursal s
on u.`idSucursal`= s.`idSucursal`
inner join poder p on p.idPoder=usuPoder
WHERE idUsuario = idUser
order by usuNombres ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `prueba`;
DELIMITER ;;
CREATE  PROCEDURE `prueba`()
    NO SQL
BEGIN


set @idPro=1381;
set @fechaRegistro='2018-04-02';
set @fechaRegistroNow='2018-04-02 09:56:00';
set @montoentregado= 1300;
set @usuario = 3;
INSERT INTO `Cliente`
(`idCliente`,
`cliApellidos`,
`cliNombres`,
`cliDni`,
`cliDireccion`,
`cliCorreo`,
`cliCelular`)
VALUES
(null,
'Linares Miranda Liliana',
'43015034',
'Av Leoncio prado 762',
'',
'978218948');
set @id=last_insert_id();
INSERT INTO `producto`(`idProducto`, `prodNombre`, `prodMontoEntregado`, `prodInteres`, `prodFechaInicial`, `prodFechaVencimiento`, `prodObservaciones`, `prodMontoPagar`, `idCliente`, `prodActivo`, `prodFechaRegistro`, `idUsuario`, `idSucursal`, `esCompra`)
VALUES (@idPro,
'Motocicleta Rayban Zirus 200 Color Negro (9232-9w)',
@montoentregado,4,
@fechaRegistro,'',
observaciones,0,
@id,
1, @fechaRegistroNow, @usuario, 1,1);
INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) 
VALUES (null,@idPro,3,@fechaRegistroNow, @montoentregado,'',1,@usuario,0,1);


END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `puntarCliente`;
DELIMITER ;;
CREATE  PROCEDURE `puntarCliente`(IN `estrellas` INT, IN `idCli` INT)
    NO SQL
UPDATE `Cliente` SET `cliCalificacion`=estrellas WHERE `idCliente`=idCli ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `quitarAmortizacion`;
DELIMITER ;;
CREATE  PROCEDURE `quitarAmortizacion`(IN `idProd` INT, IN `idCaj` INT)
    NO SQL
BEGIN
SELECT @valor:=cajaValor FROM `caja` where idcaja=idCaj;
select @valor;
UPDATE `prestamo` SET `preCapital`=`preCapital`+@valor WHERE idProducto=idProd;
UPDATE `producto` SET `prodMontoEntregado`=`prodMontoEntregado`+@valor WHERE idProducto=idProd;
DELETE FROM `caja` WHERE idcaja=idCaj;

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `recuperacionAnteriorMes`;
DELIMITER ;;
CREATE  PROCEDURE `recuperacionAnteriorMes`()
    NO SQL
BEGIN

declare fechaAnt varchar(200);

SELECT DATE_SUB(CURDATE(), INTERVAL 1 MONTH) into fechaAnt;


SELECT round(sum(cajaValor),2) as sumaMes FROM `caja` where date_format(cajaFecha, '%Y-%m-%d') BETWEEN DATE_FORMAT(fechaAnt,'%Y-%m-01') and LAST_DAY(fechaAnt) AND idTipoProceso IN (45, 44, 32, 34, 33, 36, 20, 21, 75);

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `recuperacionEsteMes`;
DELIMITER ;;
CREATE  PROCEDURE `recuperacionEsteMes`()
    NO SQL
SELECT round(sum(cajaValor),2) as sumaMes FROM `caja` where date_format(cajaFecha, '%Y-%m-%d') BETWEEN DATE_FORMAT(now(),'%Y-%m-01') and LAST_DAY(now()) AND idTipoProceso IN (45, 44, 32, 34, 33, 36, 20, 21, 75) ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `recuperacionSalidaAnteriorMes`;
DELIMITER ;;
CREATE  PROCEDURE `recuperacionSalidaAnteriorMes`()
    NO SQL
BEGIN

declare fechaAnt varchar(200);

SELECT DATE_SUB(CURDATE(), INTERVAL 1 MONTH) into fechaAnt;


SELECT round(sum(cajaValor),2) as restaMes FROM `caja` where date_format(cajaFecha, '%Y-%m-%d') BETWEEN DATE_FORMAT(fechaAnt,'%Y-%m-01') and LAST_DAY(fechaAnt) AND idTipoProceso IN (38, 43, 37, 42, 28);

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `recuperacionSalidaEsteMes`;
DELIMITER ;;
CREATE  PROCEDURE `recuperacionSalidaEsteMes`()
    NO SQL
SELECT round(sum(cajaValor),2) as restaMes FROM `caja` 
where date_format(cajaFecha, '%Y-%m-%d') BETWEEN DATE_FORMAT(now(),'%Y-%m-01') and LAST_DAY(now()) AND
idTipoProceso IN ( 38, 43, 37, 42, 28 ) ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `reporteCajaDia`;
DELIMITER ;;
CREATE  PROCEDURE `reporteCajaDia`(IN `fechaIni` TEXT)
    NO SQL
begin 
SELECT ca.idCaja, ca.idProducto, ca.idTipoProceso, cajaFecha,
round( ca.cajaValor,2) as cajaValor,
concat( c.cliApellidos, ' ', c.cliNombres) as cliNombres,
dr.tipoDescripcion, u.usuNombres,idAprueba, p.prodNombre
FROM `caja` ca
inner join producto p on p.idProducto=ca.idProducto
inner join Cliente c on c.idCliente= p.idCliente
inner join tipoProceso dr on dr.idTipoProceso= ca.idTipoProceso
inner join usuario u on u.idUsuario =ca.idUsuario
where DATE_FORMAT(`cajafecha`,'%Y-%m-%d') = fechaIni
 and ca.idTipoProceso in (2, 3, 9, 10, 11, 15, 16, 26, 28);
end ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `reporteEgresoDia`;
DELIMITER ;;
CREATE  PROCEDURE `reporteEgresoDia`(IN `fechaIni` TEXT)
    NO SQL
BEGIN
SET FOREIGN_KEY_CHECKS=0;
SELECT
c.idCaja, ROUND(cajaValor,2) as pagoMonto, cajaFecha, replace(cajaObservacion, 'Ingreso extra: ', '') as cajaObservacion, 
u.usuNombres as usuNick, tp.tipoDescripcion, c.idProducto, p.prodNombre, m.moneDescripcion, c.cajaActivo, c.cajaMoneda, tpr.tipopDescripcion
FROM `caja` c
inner join tipoProceso tp on tp.idTipoProceso = c.idTipoProceso
left join producto p on p.idProducto = c.idProducto
LEFT JOIN usuario u on u.idUsuario=c.idUsuario
inner join moneda m on m.idMoneda = c.cajaMoneda
LEFT JOIN tipoProducto tpr on tpr.idTipoProducto=p.idTipoProducto
where DATE_FORMAT(`cajaFecha`,'%Y-%m-%d')=fechaIni
and tp.idTipoProceso in (39, 38, 43, 37, 42, 28, 40, 26, 41, 74)
and cajaActivo=1;
END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `reporteEgresoDiaxCuadre`;
DELIMITER ;;
CREATE  PROCEDURE `reporteEgresoDiaxCuadre`(IN `cuadre` INT)
    NO SQL
BEGIN
DECLARE fecha1 DATETIME ;
DECLARE fecha2 varchar(100) ;
SET FOREIGN_KEY_CHECKS=0;

SELECT `fechaInicio`, `fechaFin` into fecha1, fecha2 
FROM `cuadre`
where idCuadre=cuadre;


if fecha2='0000-00-00 00:00:00' then set fecha2=now(); end if;

SELECT
c.idCaja, ROUND(cajaValor,2) as pagoMonto, cajaFecha, lower(replace(cajaObservacion, 'Ingreso extra: ', '')) as cajaObservacion, 
u.usuNombres as usuNick, tp.tipoDescripcion, c.idProducto, p.prodNombre, m.moneDescripcion, c.cajaActivo, c.cajaMoneda, tpr.tipopDescripcion, c.idTipoProceso, cajPorcentaje
FROM `caja` c
inner join tipoProceso tp on tp.idTipoProceso = c.idTipoProceso
left join producto p on p.idProducto = c.idProducto
LEFT JOIN usuario u on u.idUsuario=c.idUsuario
LEFT JOIN tipoProducto tpr on tpr.idTipoProducto=p.idTipoProducto
inner join moneda m on m.idMoneda = c.cajaMoneda
where date_format(`cajaFecha`, "%Y-%m-%d %H:%i") BETWEEN date_format(fecha1, "%Y-%m-%d %H:%i") and fecha2
and tp.idTipoProceso in (39, 38, 43, 37, 42, 28, 40, 26, 41, 74, 78, 87, 88)
and cajaActivo=1;
END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `reporteIngresoDia`;
DELIMITER ;;
CREATE  PROCEDURE `reporteIngresoDia`(IN `fechaIni` TEXT)
    NO SQL
BEGIN
SET FOREIGN_KEY_CHECKS=0;
SELECT
c.idCaja, ROUND(cajaValor,2) as pagoMonto, cajaFecha, replace(cajaObservacion, 'Ingreso extra: ', '') as cajaObservacion, 
u.usuNombres as usuNick, tp.tipoDescripcion, c.idProducto, p.prodNombre, m.moneDescripcion, c.cajaActivo, c.cajaMoneda, tpr.tipopDescripcion
FROM `caja` c
inner join tipoProceso tp on tp.idTipoProceso = c.idTipoProceso
left join producto p on p.idProducto = c.idProducto
LEFT JOIN usuario u on u.idUsuario=c.idUsuario
LEFT JOIN tipoProducto tpr on tpr.idTipoProducto=p.idTipoProducto
inner join moneda m on m.idMoneda = c.cajaMoneda
where DATE_FORMAT(`cajaFecha`,'%Y-%m-%d')=fechaIni
and c.idTipoProceso in (45, 44, 32, 31, 34, 33, 36, 20, 21, 75, 76)
and cajaActivo=1;
END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `reporteIngresoDiaxCuadre`;
DELIMITER ;;
CREATE  PROCEDURE `reporteIngresoDiaxCuadre`(IN `cuadre` INT)
    NO SQL
BEGIN
DECLARE fecha1 DATETIME ;
DECLARE fecha2 varchar(100) ;
SET FOREIGN_KEY_CHECKS=0;

SELECT `fechaInicio`, `fechaFin` into fecha1 , fecha2 FROM `cuadre`
where idCuadre=cuadre;

if fecha2='0000-00-00 00:00:00' then set fecha2=now(); end if;

SELECT
c.idCaja, ROUND(cajaValor,2) as pagoMonto, cajaFecha, lower(replace(cajaObservacion, 'Ingreso extra: ', '')) as cajaObservacion, 
u.usuNombres as usuNick, tp.tipoDescripcion, c.idProducto, p.prodNombre, m.moneDescripcion, c.cajaActivo, c.cajaMoneda, tpr.tipopDescripcion
FROM `caja` c
inner join tipoProceso tp on tp.idTipoProceso = c.idTipoProceso
left join producto p on p.idProducto = c.idProducto
LEFT JOIN usuario u on u.idUsuario=c.idUsuario
LEFT JOIN tipoProducto tpr on tpr.idTipoProducto=p.idTipoProducto
inner join moneda m on m.idMoneda = c.cajaMoneda
where date_format(`cajaFecha`, "%Y-%m-%d %H:%i") BETWEEN date_format(fecha1, "%Y-%m-%d %H:%i") and fecha2
and c.idTipoProceso in (45, 44, 32, 31, 34, 33, 36, 20, 21, 75, 76, 80, 81, 82, 83, 84, 86, 89, 90, 91)
and cajaActivo=1;

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `reporteInteresesCobrados`;
DELIMITER ;;
CREATE  PROCEDURE `reporteInteresesCobrados`(IN `fecha` TEXT)
    NO SQL
BEGIN
SET FOREIGN_KEY_CHECKS=0;
SELECT ca.*, p.prodNombre, u.usuNombres, tp.tipoDescripcion
FROM `caja` ca
LEFT join producto p on p.idProducto = ca.idProducto
inner join usuario u on u.idUsuario = ca.idUsuario
inner join tipoProceso tp on tp.idTipoProceso = ca.idTipoProceso
where ca.idTipoProceso in (44, 33, 36) and date_format(ca.cajaFecha, '%Y-%m') like fecha;
END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `reporteInyeccionDia`;
DELIMITER ;;
CREATE  PROCEDURE `reporteInyeccionDia`(IN `fechaIni` TEXT)
    NO SQL
SELECT ca.idCaja, ca.idTipoProceso, cajaFecha, round( ca.cajaValor,2) as cajaValor, dr.repoDescripcion, u.usuNombres,idAprueba
FROM `caja` ca
inner join detallereporte dr on dr.idDetalleReporte= ca.idTipoProceso
inner join usuario u on u.idUsuario =ca.idUsuario
where DATE_FORMAT(`cajafecha`,'%Y-%m-%d')  =fechaIni and idTipoProceso in (27) ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `reportePagosFinalizados`;
DELIMITER ;;
CREATE  PROCEDURE `reportePagosFinalizados`(IN `fecha` TEXT, IN `proceso` INT)
    NO SQL
BEGIN
SET FOREIGN_KEY_CHECKS=0;
SELECT ca.*, p.prodNombre, u.usuNombres, tp.tipoDescripcion, prodMontoEntregado, sumarCierreFinalxProducto(ca.idProducto, fecha) as valorAcum
FROM `caja` ca
LEFT join producto p on p.idProducto = ca.idProducto
inner join usuario u on u.idUsuario = ca.idUsuario
inner join tipoProceso tp on tp.idTipoProceso = ca.idTipoProceso
where ca.idTipoProceso in (proceso) and date_format(ca.cajaFecha, '%Y-%m') like fecha and ca.cajaActivo=1;
END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `reportePrestamosMes`;
DELIMITER ;;
CREATE  PROCEDURE `reportePrestamosMes`(IN `fecha` TEXT)
    NO SQL
BEGIN
SET FOREIGN_KEY_CHECKS=0;
SELECT ca.*, p.prodNombre, u.usuNombres, concat( c.cliApellidos, ' ', c.cliNombres ) as cliNombres, p.idCliente
FROM `caja` ca
LEFT join producto p on p.idProducto = ca.idProducto
inner join usuario u on u.idUsuario = ca.idUsuario
inner join Cliente c on c.idCliente = p.idCliente
where idTipoProceso in (43, 28) and date_format(ca.cajaFecha, '%Y-%m') like fecha;
END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `reporteRetiroSocios`;
DELIMITER ;;
CREATE  PROCEDURE `reporteRetiroSocios`(IN `fecha` TEXT, IN `proceso` INT)
    NO SQL
BEGIN
SET FOREIGN_KEY_CHECKS=0;
SELECT ca.*, u.usuNombres, tp.tipoDescripcion
FROM `caja` ca
inner join usuario u on u.idUsuario = ca.idUsuario
inner join tipoProceso tp on tp.idTipoProceso = ca.idTipoProceso
where ca.idTipoProceso in (proceso) and date_format(ca.cajaFecha, '%Y-%m') like fecha and ca.cajaActivo=1;
END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `reporteSalidaDia`;
DELIMITER ;;
CREATE  PROCEDURE `reporteSalidaDia`(IN `fechaIni` TEXT)
    NO SQL
SELECT ca.idCaja, ca.idTipoProceso, cajaFecha, round( ca.cajaValor,2) as cajaValor, dr.repoDescripcion, u.usuNombres,idAprueba
FROM `caja` ca
inner join detallereporte dr on dr.idDetalleReporte= ca.idTipoProceso
inner join usuario u on u.idUsuario =ca.idUsuario
where DATE_FORMAT(`cajafecha`,'%Y-%m-%d')  =fechaIni and idTipoProceso in (28) ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `returnIdPrestamo`;
DELIMITER ;;
CREATE  PROCEDURE `returnIdPrestamo`(IN `cod` INT)
    NO SQL
select d.idPrestamo
from prestamo pr
inner join desembolso d on d.idPrestamo= pr.idPrestamo
where pr.idproducto=cod ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `returnTotalProductos`;
DELIMITER ;;
CREATE  PROCEDURE `returnTotalProductos`()
    NO SQL
begin

SELECT COUNT( * ) conteo
FROM  `producto` 
where prodActivo<>0;

end ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `returnTotalProductosPorId`;
DELIMITER ;;
CREATE  PROCEDURE `returnTotalProductosPorId`(IN `idSuc` INT)
    NO SQL
begin

SELECT COUNT( * ) conteo
FROM  `producto` 
where idSucursal = idSuc and prodActivo<>0;

end ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `salidaAlmacen` ;
DELIMITER ;;
CREATE  PROCEDURE `salidaAlmacen`(IN `idProd` INT, IN `idUser` INT, IN `obs` TEXT, IN `cubo` INT)
    NO SQL
BEGIN

SET @@session.time_zone='-05:00';

UPDATE `cubicaje` SET `cuaVigente` = '0' WHERE `idCubicaje` = cubo;

INSERT INTO `cubicaje`(`idCubicaje`, `idProducto`, `idTipoProceso`, `idUsuario`, `cubFecha`, `cubObservacion`, `Idestante`, `idPiso`, `idZona`, `cuaVigente`) 
VALUES (null,idProd, 47 ,idUser, now() ,obs,1,1,1,0);

UPDATE `prestamo_producto` SET
`idCubicajeEstado`= 47
WHERE `idProducto`=idProd;


END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `selectDatosProductov4` ;
DELIMITER ;;
CREATE  PROCEDURE `selectDatosProductov4`(IN `idProd` INT)
    NO SQL
begin 
select p.*, concat (c.cliApellidos, ' ' , c.cliNombres,' ' , c.cliNombres) as cliNombres, tp.tipoDescripcion, tp.tipColorMaterial, prodActivo, esCompra, u.usuNombres FROM producto p inner join Cliente c on c.idCliente=p.idCliente inner join prestamo pre on pre.idProducto=p.idProducto inner join tipoProceso tp on tp.idTipoProceso=pre.preIdEstado 
inner join usuario u on u.idUsuario=p.idUsuario
WHERE p.idProducto=idProd;
end ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `solicitarConfiguraciones` ;
DELIMITER ;;
CREATE  PROCEDURE `solicitarConfiguraciones`()
    NO SQL
select * from configuraciones ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `solicitarDatosCompraCliente` ;
DELIMITER ;;
CREATE  PROCEDURE `solicitarDatosCompraCliente`(IN `idCompr` INT)
    NO SQL
SELECT * FROM `Compras` co
 inner join Cliente c on c.idCliente=co.idCliente
WHERE idcompra =idCompr ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `solicitarHojaControl` ;
DELIMITER ;;
CREATE  PROCEDURE `solicitarHojaControl`(IN `idProd` INT)
    NO SQL
select idProducto, c.cliApellidos, c.cliNombres, c.cliDireccion, c.cliDni, c.cliCelular, c.cliCorreo,
p.prodNombre, p.prodObservaciones, DATE_FORMAT(prodFechaRegistro,'%d/%m/%Y %h:%i %p') as prodFechaRegistro,
p.prodMontoEntregado, u.usuNombres, u.usuApellido, esCompra
from producto p
inner join Cliente c on c.idCliente = p.idCliente
inner join usuario u on u.idUsuario=p.idUsuario
where p.idproducto=idProd ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `solicitarProductoPorId` ;
DELIMITER ;;
CREATE  PROCEDURE `solicitarProductoPorId`(IN `idProd` INT)
BEGIN
set @idPrest=(select idprestamo from prestamo where idProducto=idProd);

select c.*, p.prodNombre, p.prodObservaciones, prodFechaRegistro, p.idSucursal, s.sucNombre, d.desFechaContarInteres
from Cliente c inner join producto p on c.idcliente = p.idcliente
inner join sucursal s on p.idSucursal= s.idSucursal
left join prestamo pr on pr.idProducto=p.idProducto
left join desembolso d on d.idPrestamo=pr.idPrestamo

where p.idproducto = idProd ;
END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `temporalCASE` ;
DELIMITER ;;
CREATE  PROCEDURE `temporalCASE`()
    NO SQL
begin 
DECLARE v INT DEFAULT 1;

    CASE v
      WHEN 2 THEN SELECT v;
      WHEN 3 THEN SELECT 0;
      ELSE
        select 'uno';
    END CASE;
end ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `temporalCASE2` ;
DELIMITER ;;
CREATE  PROCEDURE `temporalCASE2`(IN `v` INT)
    NO SQL
BEGIN
CASE v
      WHEN 2 THEN
      	SELECT * from tickets;
        select 2 as 'dos';
      WHEN 3 THEN SELECT 0;
      ELSE
        select 'uno';
    END CASE;
    
END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `ubicarPersonaProductos` ;
DELIMITER ;;
CREATE  PROCEDURE `ubicarPersonaProductos`(IN `campo` TEXT)
BEGIN
select `idCliente`, lower(`cliApellidos`) as cliApellidos, lower(`cliNombres`) as cliNombres, `cliDni`, lower(`cliDireccion`) as cliDireccion, `cliCorreo`, `cliCelular`
from Cliente c 
where concat(lower(cliApellidos), ' ', lower(cliNombres)) like concat('%', campo, '%')
or clidni = campo
order by cliApellidos
;
END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `updateAnularTicket` ;
DELIMITER ;;
CREATE  PROCEDURE `updateAnularTicket`(IN `idUser` INT, IN `ticket` INT, IN `obs` TEXT)
    NO SQL
begin
UPDATE `tickets` SET 
`cajaActivo`=2,
`idAprueba`=idUser,
`cajaObservacion` = concat( `cajaObservacion`, obs)
WHERE `idTicket`=ticket;

select ticket;
end ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `updateAprobarTicket` ;
DELIMITER ;;
CREATE  PROCEDURE `updateAprobarTicket`(IN `ticket` INT, IN `idUser` INT)
    NO SQL
BEGIN
SET @@session.time_zone='-05:00';

UPDATE `tickets` SET 
`cajaFecha`=now(),
`cajaActivo`=1,
`idAprueba`=idUser
WHERE `idTicket` = ticket;

select ticket;


END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `updateCobrarTicket` ;
DELIMITER ;;
CREATE  PROCEDURE `updateCobrarTicket`(IN `ticket` INT, IN `idUser` INT, IN `obs` TEXT, IN `proceso` INT)
    NO SQL
BEGIN
SET @@session.time_zone='-05:00';

UPDATE `tickets` SET
`cajaActivo`=3
WHERE `idTicket` = ticket;


INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`)
SELECT null, `idProducto`, `idTipoProceso`, now(), `cajaValor`, obs, 1, `idUsuario`, `idAprueba`, 1 FROM `tickets` WHERE `idTicket`=ticket;


set @idProd = ( select idProducto from tickets where idTicket = ticket);
case proceso
 WHEN 44 THEN
    UPDATE `producto` SET 
    `prodFechaInicial`=now(),
    `prodUltimaFechaInteres`= DATE_FORMAT(now(), "%d/%m/%Y")
    WHERE 
    `idProducto` = idProd;
 	
    UPDATE `prestamo_producto` SET
    `desFechaContarInteres`=now()
    WHERE `idProducto`=idProd;
 END CASE;
 
select ticket;
END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `updateDesembolsar` ;
DELIMITER ;;
CREATE  PROCEDURE `updateDesembolsar`(IN `idProd` INT, IN `nuevoDesembolso` FLOAT, IN `idUser` INT, IN `idSuc` INT, IN `comentExtra` TEXT)
    NO SQL
BEGIN
set @idPrest=(select idprestamo from prestamo where idProducto=idProd);

UPDATE `producto` SET
`prodMontoEntregado`=`prodMontoEntregado`+nuevoDesembolso,
`prodObservaciones`=concat(`prodObservaciones`,comentExtra)
WHERE `idProducto`= idProd;

UPDATE `prestamo` SET
`preCapital`= `preCapital`+nuevoDesembolso
WHERE `idProducto`=idProd;



INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) VALUES
(null,idProd,14,now(),nuevoDesembolso,comentExtra,1,idUser,0,idSuc);

Select idProd;




END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `updateFinalizarEstado` ;
DELIMITER ;;
CREATE  PROCEDURE `updateFinalizarEstado`(IN `idPro` INT, IN `usuar` VARCHAR(200), IN `monto` FLOAT)
    NO SQL
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
    '',4,'Todav�a sin aprobaci�n');

END ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `updateFinalizarInteres` ;
DELIMITER ;;
CREATE  PROCEDURE `updateFinalizarInteres`(IN `idPro` INT, IN `monto` FLOAT, IN `idUser` VARCHAR(200))
    NO SQL
begin 
UPDATE `producto` SET 
`prodFechaInicial`=now(),
`prodObservaciones` =concat('Se cancel� el inter�s S/. ',round(monto,2),' de el d�a ', DATE_FORMAT(now(), "%d/%m/%Y"), '<br>', `prodObservaciones`),
`prodUltimaFechaInteres`= DATE_FORMAT(now(), "%d/%m/%Y")
WHERE 
`idProducto` = idPro;


INSERT INTO `reportes_producto`(
    `idReporte`, `idProducto`, `idDetalleReporte`, `repoValorMonetario`, `repoFechaOcurrencia`, `repoUsuario`,
`repoUsuarioComentario`, `repoQueConfirma`, `repoQuienConfirma`) VALUES (
     null,idPro,1,
     monto,now(),idUser,
    '',4,'Todav�a sin aprobaci�n');

end ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `updateFinalizarPrestamo` ;
DELIMITER ;;
CREATE  PROCEDURE `updateFinalizarPrestamo`(IN `idPro` INT, IN `monto` FLOAT, IN `idUser` VARCHAR(200), IN `valorizado` FLOAT, IN `idSuc` INT, IN `usuario` VARCHAR(200), IN `paga` FLOAT, IN `comentario` TEXT)
    NO SQL
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
    '',4,'Todav�a sin aprobaci�n');

INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) VALUES (null,idPro,11,now(),paga,concat('Precio original: S/. ', monto), 1,idUser,0,idSuc);

end ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `updateFinalizarSucursal` ;
DELIMITER ;;
CREATE  PROCEDURE `updateFinalizarSucursal`(IN `idSuc` INT)
    NO SQL
UPDATE `sucursal` SET `sucActivo`=0 WHERE idSucursal =idSuc ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `updateMovimientoAceptar` ;
DELIMITER ;;
CREATE  PROCEDURE `updateMovimientoAceptar`(IN `idRepo` INT, IN `nomUser` VARCHAR(200))
    NO SQL
UPDATE `reportes_producto` SET 
`repoFechaConfirma`=now(),
`repoQueConfirma`=7,
`repoQuienConfirma`=nomUser
WHERE `idReporte`=idRepo ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `updateMovimientoRematar` ;
DELIMITER ;;
CREATE  PROCEDURE `updateMovimientoRematar`(IN `idRepo` INT, IN `nomUser` VARCHAR(200))
    NO SQL
UPDATE `reportes_producto` SET 
`repoFechaConfirma`=now(),
`repoQueConfirma`=6,
`repoQuienConfirma`=nomUser
WHERE `idReporte`=idRepo ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `updateMovimientoRetirar` ;
DELIMITER ;;
CREATE  PROCEDURE `updateMovimientoRetirar`(IN `idRepo` INT, IN `nomUser` VARCHAR(200))
    NO SQL
UPDATE `reportes_producto` SET 
`repoFechaConfirma`=now(),
`repoQueConfirma`=5,
`repoQuienConfirma`=nomUser
WHERE `idReporte`=idRepo ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `updatePassSinDatos` ;
DELIMITER ;;
CREATE  PROCEDURE `updatePassSinDatos`(IN `texto` VARCHAR(200), IN `idUser` INT)
    NO SQL
begin
UPDATE `usuario` SET
`usuPass` = md5(texto)
WHERE `idUsuario`=idUser;

select 1;
end ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `updateTicketPorAprobar` ;
DELIMITER ;;
CREATE  PROCEDURE `updateTicketPorAprobar`(IN `idTick` INT, IN `valor` FLOAT, IN `obs` TEXT)
    NO SQL
begin

UPDATE `tickets` SET `cajaFecha`=now(),`cajaValor`=valor,`cajaObservacion`= concat(`cajaObservacion`, obs) 
WHERE `idTicket`=idTick;

select idTick;

end ;;
DELIMITER ;
DROP PROCEDURE IF EXISTS `updateUserDatosConPass` ;
DELIMITER ;;
CREATE  PROCEDURE `updateUserDatosConPass`(IN `nombre` VARCHAR(200), IN `apellido` VARCHAR(200), IN `nick` VARCHAR(200), IN `pass` VARCHAR(200), IN `poder` INT, IN `sucursal` INT, IN `idUser` INT)
    NO SQL
UPDATE `usuario` SET 
`usuNombres`=nombre,
`usuApellido`=apellido,
`usuNick`=nick,
`usuPass`=md5(pass),
`usuPoder`=poder,
`idSucursal`=sucursal
WHERE `idUsuario`=idUser ;;
DELIMITER ;

