<?php 
header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

$filas=array();
$i=0;
$consulta = $conection->prepare("call listarBuscarIdProducto(".$_GET['campo'].");");
$consulta->execute();
$resultado = $consulta->get_result();

while ($row = $resultado->fetch_array(MYSQLI_ASSOC))
{
	//echo $row['idproducto']."<br>";
	$filas[$i]= $row;
	$i++;
	
}
$consulta->fetch();
$consulta->close();



$consulta2 = $conection->prepare("call encontrarCliente('".$_GET['camp']."');");
$consulta2->execute();
$resultado2 = $consulta2->get_result();

while ($row2 = $resultado2->fetch_array(MYSQLI_ASSOC))
{
	$filas[$i]= $row2;
	$i++;
	
}
$consulta2->fetch();
$consulta2->close();

echo json_encode($filas);
?>