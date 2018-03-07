<?php 

include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');

$sql= "call insertarProductov3 ('".$_POST['jdata'][0]['descripProd']."', ".$_POST['jdata'][0]['capitalProd'].", ".$_POST['jdata'][0]['intereProd'].", '".$_POST['jdata'][0]['fechaIngProd']."', '".$_POST['jdata'][0]['extraProd']."', ".$_POST['idCliente'].", ".$_POST['idUser'].", ".$_POST['idPrestamo'].", ".$_POST['jdata'][0]['cantProd']." );";
//echo $sql;
echo $_POST['idCliente'];
$conection->query($sql);
/*if ($llamadoSQL = $conection->query($sql)) { 
	while ($resultado = $llamadoSQL->fetch_row()) {
		echo $resultado[0]; //Retorna los resultados via post del procedure
	}
	$llamadoSQL->close();
}else{echo mysql_error( $conection);}*/
 ?>