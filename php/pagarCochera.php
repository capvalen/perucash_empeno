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

$sqlPago='';
if( $paga< $deuda){
   //solo adelantó
   $sqlPago ="UPDATE `cocheraregistros` SET `movPrecio`= `movPrecio`+ {$paga} WHERE `idCocheraReg`= {$idReg}; ";
}
if( $paga == $deuda){
   //pagó completo
   $sqlPago ="UPDATE `cocheraregistros` SET `movPrecio`= `movPrecio`+ {$paga}, `idProceso`=26 WHERE `idCocheraReg`= {$idReg}; ";
}
   echo $sqlPago;
   $cadena->query($sqlPago);

?>