<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$filas=array();
$log = mysqli_query($conection,"call listarProductosPorAprobar(".$_POST['oficina'].");");


while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
{
	$filas[]= array('idProducto' => $row['idProducto'],
		'prodNombre' => $row['prodNombre'],
		'prodCuantoFinaliza' => $row['prodCuantoFinaliza'],
		'prodQuienFinaliza' => $row['prodQuienFinaliza'],
		'prodFechaFinaliza' => $row['prodFechaFinaliza']


	);
	
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexi贸n */
mysqli_close($conection);
echo json_encode($filas);
?>