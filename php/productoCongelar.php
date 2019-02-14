<?
include "conkarl.php";


$sql="UPDATE `prestamo` pre, `prestamo_producto` pro SET 
`presFechaCongelacion`= '{$_POST['fecha']}'
WHERE pro.idPrestamo = pre.idPrestamo and pro.idProducto = {$_POST['idProd']}";
$resultado=$cadena->query($sql);
//echo $sql;

echo 1;
?>