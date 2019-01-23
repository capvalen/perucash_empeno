<?
header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';
$idProd = $_POST['idProd'];

$sql="SELECT round(p.preCapital,2) as preCapital, p.desFechaContarInteres,datediff( now(), desFechaContarInteres ) as diferenciaDias, preInteres FROM `prestamo_producto` p
where idProducto={$idProd};";

$resultado=$cadena->query($sql);
$row=$resultado->fetch_assoc();

$base = $row['preCapital'];
$dias = $row['diferenciaDias'];
$interes = $row['preInteres'];

if($dias>=35){
   echo 'mas 35';
}else{
   echo 'menos 35';
}
?>