<?
header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

$sql="INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaMoneda`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`)
select null, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, 1, `cajaActivo`, `idUsuario`, `idAprueba`, 1
from tickets
where idTicket = {$_POST['ticket']};
UPDATE `prestamo_cuotas` pc, tickets t SET
pc.`cuotFechaCancelacion`= t.cajaFecha,
pc.`cuotPago`= t.cajaValor,
pc.`idTipoPrestamo`=t.idTipoProceso
WHERE t.subCuota = pc.idCuota and  t.idTicket = {$_POST['ticket']};
UPDATE `tickets` SET
cajaActivo = 3,
`idAprueba` = {$_COOKIE['ckidUsuario']}
where `idTicket` = {$_POST['ticket']};
";

//echo $sql;
if($cadena->multi_query($sql)){
  echo 1;
}else{
   echo "Hay un error interno de sentencias";
}

   


?>