<?
include "conkarl.php";




$sql="UPDATE `prestamo` pre, `prestamo_producto` pro SET 
`presFechaCongelacion`= '{$_POST['fecha']}'
WHERE pro.idPrestamo = pre.idPrestamo and pro.idProducto = {$_POST['idProd']};

UPDATE `prestamo_producto` SET
`presidTipoProceso`=85
where idProducto={$_POST['idProd']};";
$resultado=$cadena->multi_query($sql);
//echo $sql;

echo 1;
?>