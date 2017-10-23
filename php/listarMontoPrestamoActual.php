<?php 
require("conkarl.php");
$filas=array();

$i=0;

$sql = mysqli_query($conection,"call listarMontoPrestamoActual(".$_POST['idProd'].");");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{
	$filas[$i]= $row;
	$i++;
}
mysqli_close($conection); //desconectamos la base de datos
echo json_encode($filas);
?>