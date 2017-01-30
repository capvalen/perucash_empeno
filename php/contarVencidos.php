<?php 


include 'conkarl.php';


$filas=array();
$log = mysqli_query($conection,"call contarVencidos();");


while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
{
	echo $row['Num'];
	
	
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexi贸n */
mysqli_close($conection);

?>