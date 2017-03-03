<?php 

session_start();

include 'conkarl.php';


$filas=array();
if($_SESSION['Power']==1){
$log = mysqli_query($conection,"call returnTotalProductos();"); }
else{
$log = mysqli_query($conection,"call returnTotalProductosPorId(".$_SESSION['idSucursal'].");");
}


while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
{
	return $row['conteo'];
	
	
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexi贸n */
mysqli_close($conection);

?>