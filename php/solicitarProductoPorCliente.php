<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$filas=array();
$log = mysqli_query($conection,"call listarProductosPorCliente(".$_POST['idCli'].");");


while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
{
	$filas[]= array('idProducto' => $row['idProducto'],
		'prodNombre' => $row['prodNombre'],
		'prodMontoEntregado' => $row['prodMontoEntregado'],
		'prodInteres' => $row['prodInteres'],
		'prodFechaInicial' => $row['prodFechaInicial'],
		'prodFechaVencimiento' => $row['prodFechaVencimiento'],
		'prodObservaciones' => $row['prodObservaciones'],
		'prodMontoDado' => $row['prodMontoEntregado'],
		'prodMontoPagar' => $row['prodMontoPagar'],
		'prodFechaRegistro' => $row['prodFechaRegistro'],
		'prodActivo' => $row['prodActivo'],
		'prodAdelanto' => $row['prodAdelanto'],
		'idSucursal' => $row['idSucursal'],
		'sucNombre' => $row['sucNombre']


	);
	
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexi贸n */
mysqli_close($conection);
echo json_encode($filas);
?>