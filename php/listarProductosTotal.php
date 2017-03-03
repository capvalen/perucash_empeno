<?php 
session_start();

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

$filas=array();
if($_SESSION['Power']==1){
	$log = mysqli_query($conection,"call listarTodoProductosSinSuc(".$_POST['desdde']." , ".$_POST['hastta']." );");
}
else{
	$log = mysqli_query($conection,"call listarTodoProductosPorSuc(".$_POST['desdde']." , ".$_POST['hastta'].", ".$_POST['idSuc'].");");
}


while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
{
	$filas[]= array('idProducto' => $row['idProducto'],
		'prodNombre' => $row['prodNombre'],
		'prodMontoEntregado' => $row['prodMontoEntregado'],	
		'prodFechaInicial' => $row['prodFechaInicial'],
		'sucNombre' => $row['sucNombre'],
		'usuNombres' => $row['usuNombres'],
		'propietario' => $row['propietario'],
		'prodActivo' => $row['prodActivo']
	);
	
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexi贸n */
mysqli_close($conection);
echo json_encode($filas);
?>