<?php 
// session_start();
include 'conkarl.php';

$log = mysqli_query($conection,"call recuperacionAnteriorMes();");

while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
{
	$suma= $row['sumaMes'];
}


$frog = mysqli_query($cadena,"call recuperacionSalidaAnteriorMes();");

while($fila = mysqli_fetch_array($frog, MYSQLI_ASSOC))
{
	$resta= $fila['restaMes'];
}

echo number_format($suma-$resta,2);

/* liberar la serie de resultados */
mysqli_free_result($log);
mysqli_free_result($frog);

/* cerrar la conexi贸n */
mysqli_close($conection);
mysqli_close($cadena);

?>