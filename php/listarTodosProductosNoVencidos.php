<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$filas=array();
$log = mysqli_query($conection,"call listarTodosProductosNoFinalizados()");


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

		'idCliente' => $row['idCliente'],
		'cliApellidos' => $row['cliApellidos'],
		'cliNombres' => $row['cliNombres'],
		'cliDni' => $row['cliDni'],
		'cliDireccion' => $row['cliDireccion'],
		'cliCorreo' => $row['cliCorreo'],
		'cliCelular' => $row['cliCelular'],
		'usuNombres' => $row['usuNombres']
	);
	
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexi贸n */
mysqli_close($conection);
echo json_encode($filas);
?>