<?php 
session_start();

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

$filas=array();

$log = mysqli_query($conection,"call listarUnUsuario(".$_POST['idUs']." );");



while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
{
	$filas[]= $row;
	
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexi贸n */
mysqli_close($conection);
echo json_encode($filas);
?>