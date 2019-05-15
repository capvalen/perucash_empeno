<?php 

include 'conkarl.php';

$sql="SELECT `idCocheraReg`, upper(`movPlaca`) as movPlaca, `idProceso`, 
(datediff(date_format(now(),'%Y-%m-%d'), date_format(movFecha,'%Y-%m-%d') )+1)* tv.vehPrecio as vehPrecio 
FROM `cocheraregistros` co
inner join tipovehiculo tv on tv.idTipoVehiculo = co.idVehiculo
where co.idProceso = 27 and co.movActivo=1 and movPlaca ='{$_POST['placa']}'";
$resultado=$cadena->query($sql);
$row=$resultado->fetch_assoc();

$idReg = $row['idCocheraReg'];
$placa = $row['movPlaca'];
$deuda = $row['vehPrecio'];
$paga = floatval($_POST['paga']);

$sqlRetirar='';

if( $_POST['retirar']=='true'){ $sqlRetirar="UPDATE `cocheraregistros` SET `idProceso` = '26' WHERE `cocheraregistros`.`idCocheraReg` = {$idReg};"; }


$sqlPago='';
if( $paga< $deuda){
   //solo adelantó
   $sqlPago ="UPDATE `cocheraregistros` SET `movPrecio`= `movPrecio`+ {$paga} WHERE `idCocheraReg`= {$idReg}; 
   INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaMoneda`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) 
   VALUES (null, 0, 76, now(), {$paga}, '{$_POST["obs"]} Placa: {$placa}', 1, 1, {$_COOKIE['ckidUsuario']}, 0, 1); "
   . $sqlRetirar;
}
if( $paga == $deuda){
   //pagó completo
   $sqlPago ="UPDATE `cocheraregistros` SET `movPrecio`= `movPrecio`+ {$paga}, `idProceso`=26 WHERE `idCocheraReg`= {$idReg}; 
   INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaMoneda`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) 
   VALUES (null, 0, 76, now(), {$paga}, '{$_POST["obs"]} Placa: {$placa}', 1, 1, {$_COOKIE['ckidUsuario']}, 0, 1); "
   . $sqlRetirar;
}
   //echo $sqlPago;
   $cadena->multi_query($sqlPago);
   echo "ok";

?>