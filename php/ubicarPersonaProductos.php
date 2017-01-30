<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$filas=array();
$log = mysqli_query($conection,"call ubicarPersonaProductos('".$_POST['campo']."');");


while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
{
	$filas[]= array('idCliente' => $row['idCliente'],
		'cliApellidos' => $row['cliApellidos'],
		'cliNombres' => $row['cliNombres'],
		'cliDni' => $row['cliDni'],
		'cliDireccion' => $row['cliDireccion'],		
		'cliCorreo' => $row['cliCorreo'],		
		'cliCelular' => $row['cliCelular']
		
	);
	
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexi贸n */
mysqli_close($conection);
echo json_encode($filas);
?>