<?php 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


if($_POST['dniCli']<>''){
$filas=array();
$log = mysqli_query($conection,"call encontrarCliente('".$_POST['dniCli']."');");


while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
{
	$filas[]= array('idCliente' => $row['idCliente'],
		'cliApellidos' => $row['cliApellidos'],
		'cliNombres' => $row['cliNombres'],		
		'cliDni' => $row['cliDni'],
		'cliDireccion' => $row['cliDireccion'],
		'cliCorreo' => $row['cliCorreo'],
		'cliCelular' => $row['cliCelular'],
		'cliFijo' => $row['cliFijo']
	);
	
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexi贸n */
mysqli_close($conection);
echo json_encode($filas);
}else{
	echo '';
}
?>