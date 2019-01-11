<?
header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

$sql="UPDATE `tickets` SET
cajaActivo = 3,
`idAprueba` = {$_COOKIE['ckidUsuario']}
where `idTicket` = {$_POST['ticket']};
INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaMoneda`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`)
select null, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, 1, `cajaActivo`, `idUsuario`, `idAprueba`, 1
from tickets
where idTicket = {$_POST['ticket']};
";
if($cadena->multi_query($sql)){
   echo 1;
}else{
   echo "Hay un error interno de sentencias";
}

   


?>