<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$filas=array();
$log = mysqli_query($conection,"call listarProductosVencidos( ".$_POST['idSucursal'].");");


while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
{
	$filas[]= array('idproducto' => $row['idproducto'],
		'prodNombre' => $row['prodNombre'],
		'prodMontoPagar' => $row['prodMontoPagar'],		
		'propietario' => $row['propietario'],
		'prodFechaVencimiento' => $row['prodFechaVencimiento']
	);
	
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexi贸n */
mysqli_close($conection);
echo json_encode($filas);
?>