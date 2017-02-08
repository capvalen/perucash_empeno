<?php 

include 'conkarl.php';


$filas=array();
$log = mysqli_query($conection,"call listarTodosUsuarios();");


while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
{
	$filas[]= array('idUsuario' => $row['idUsuario'],
		'nombre' => $row['nombre'],
		'descripcion' => $row['descripcion'],
		'sucLugar' => $row['sucLugar']
	);
	
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexi贸n */
mysqli_close($conection);
echo json_encode($filas);
?>