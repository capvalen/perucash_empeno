<?php 

include 'conkarl.php';


$filas=array();
$log = mysqli_query($conection,"SELECT * FROM sucursal where idSucursal<>3 and sucActivo =1 order by sucNombre ;");

$filas=array();
while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
{
	$filas[]= array('idSucursal' => $row['idSucursal'],
		'sucNombre' => $row['sucNombre'],
		'sucLugar' => $row['sucLugar']		
	);
	
}

/* liberar la serie de resultados */
mysqli_free_result($log);
/* cerrar la conexión */
mysqli_close($conection);
echo json_encode($filas);

?>