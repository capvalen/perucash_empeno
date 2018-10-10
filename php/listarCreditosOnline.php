<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$filas=array();
//echo "call reportePrestamosMes('{$_POST["fecha"]}');";
$log = mysqli_query($conection,"call reporteInteresesCobrados('{$_POST["fecha"]}');");// ".$_POST['idSucursal']."
$i=0;
while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
{
	$filas[$i]= $row;
	$i++;
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexion */
mysqli_close($conection);
echo json_encode($filas);
?>