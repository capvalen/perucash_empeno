<?php
date_default_timezone_set('America/Lima');
include 'conkarl.php';

/* agregar a la primera linea de SQLCAJA cuando se haya vaceado todo el registro al sistema
"INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) 
SELECT null,0,81,now(),cuotCuota, concat('<a href=creditos.php?credito=',idPrestamo,'>CR-',idPrestamo,'</a>'),1,{$_COOKIE['ckidUsuario']},0,1
FROM `prestamo_cuotas`
where idCuota = {$_POST['idCuo']}; */
$sqlCaja= "
UPDATE `prestamo_cuotas` SET
`cuotFechaCancelacion`=now(),
`cuotPago`=`cuotCuota`,
`cuotObservaciones`='Pago fuera de sistema',
`idTipoPrestamo`= 81
where idCuota = {$_POST['idCuo']};";
//echo $sqlCaja;
$resultado = $esclavo->multi_query($sqlCaja);


$sqlCuot="SELECT count(idCUota) as `restanCuotas` FROM `prestamo_cuotas`
where idPrestamo =  {$_POST['idPre']}
and idTipoPrestamo = 79;";

$resultadoCuot=$cadena->query($sqlCuot);
$rowCuot=$resultadoCuot->fetch_assoc();

if($rowCuot['restanCuotas']>0){
   //echo 'faltan';
}else{
   $sqlPres="UPDATE `prestamo` SET 
   preIdEstado = 82
   where `idPrestamo`={$_POST['idPre']}";
   $resultadoPres=$cadena->query($sqlPres);
}

if($resultado){echo true;}

?>