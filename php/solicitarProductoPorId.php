<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$filas=array();
$log = mysqli_query($conection,"call solicitarProductoPorId(".$_POST['idProd'].");");


while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
{
	$filas[]= array('idProducto' => $row['idProducto'],
		'prodNombre' => $row['prodNombre'],
		'prodMontoEntregado' => $row['prodMontoEntregado'],
		'prodInteres' => $row['prodInteres'],
		'prodFechaInicial' => $row['prodFechaInicial'],
		'prodFechaVencimiento' => $row['prodFechaVencimiento'],
		'prodObservaciones' => $row['prodObservaciones'],
		'prodMontoDado' => $row['prodMontoPagar'],
		'prodFechaRegistro' => $row['prodFechaRegistro'],
		'prodAdelanto' => $row['prodAdelanto'],

		'idCliente' => $row['idCliente'],
		'cliApellidos' => $row['cliApellidos'],
		'cliNombres' => $row['cliNombres'],
		'cliDni' => $row['cliDni'],
		'cliDireccion' => $row['cliDireccion'],
		'cliCorreo' => $row['cliCorreo'],
		'cliCelular' => $row['cliCelular'],
		'prodActivo' => $row['prodActivo'],
		'idSucursal' => $row['idSucursal'],
		'sucNombre' => $row['sucNombre'],
		'prodQuienFinaliza' => $row['prodQuienFinaliza'],
		'prodFechaFinaliza' => $row['prodFechaFinaliza'],
		'prodCuantoFinaliza' => $row['prodCuantoFinaliza']



		
	);
	
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexi贸n */
mysqli_close($conection);
echo json_encode($filas);
?>