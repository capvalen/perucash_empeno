<?php 

include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');


$filas=array();
$log = mysqli_query($conection,"call coincidePass( '".$_POST['texto']."',".$_SESSION['idUsuario'].");");


while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
{
	$filas[]= array('result' => $row['result']

	);
	
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexi贸n */
mysqli_close($conection);
echo json_encode($filas);
?>