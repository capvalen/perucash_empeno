<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$filas=array();
$log = mysqli_query($conection,"call listarSoloCompras();");// ".$_POST['idSucursal']."

while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
{
	$filas[]= array('idProducto' => $row['idProducto'],
		'prodNombre' => $row['prodNombre'],
		'prodMontoEntregado' => $row['prodMontoEntregado'],
		'idCliente' => 699,
		'cliNombres' => 'Compra',
		'prodFechaInicial' => $row['prodFechaInicial'],
		'desFechaContarInteres' => '',
		'diasDeuda' => ''
	);
	
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexion */
mysqli_close($conection);
echo json_encode($filas);
?>
