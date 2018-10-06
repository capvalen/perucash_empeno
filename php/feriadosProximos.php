<?php 

//header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$filas=array();
$log = mysqli_query($conection,"call feriadosProximos();");// ".$_POST['idSucursal']."

while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
{
	$filas[]= array('idFeriado' => $row['idFeriado'],
		'ferFecha' => $row['ferFecha'],
		'ferDescripcion' => $row['ferDescripcion']
	);
	
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexion */
mysqli_close($conection);
//return json_encode($filas);
return $filas;
?>
