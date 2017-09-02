<?php 
session_start();
include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');


$filas=array();
$log = mysqli_query($conection,"call listarMovimientoFinal(".$_POST['idProd']." );");

while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
{
	$filas[]= array('idReporte' => $row['idReporte'],
		'idProducto' => $row['idProducto'],
		'idDetalleReporte' => $row['idDetalleReporte'],
		'repoValorMonetario' => $row['repoValorMonetario'],
		'repoFechaOcurrencia' => $row['repoFechaOcurrencia'],
		'repoUsuario' => $row['repoUsuario'],
		'repoUsuarioComentario' => $row['repoUsuarioComentario'],
		'repoFechaConfirma' => $row['repoFechaConfirma'],
		'repoQueConfirma' => $row['repoQueConfirma'],
		'repoQuienConfirma' => $row['repoQuienConfirma'],
		'prodNombre' => $row['prodNombre'],
		'repoDescripcion' => $row['repoDescripcion'],
		'estadoConfirmacion' => $row['estadoConfirmacion']

	);
	
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexi贸n */
mysqli_close($conection);
echo json_encode($filas);
?>