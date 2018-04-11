<?php 
require("conkarl.php");
$filas=array();

$i=0;

$sql = mysqli_query($conection,"call listarTipoProductoRecomendaciones(".$_POST['idTipo'].");");
//echo "call listarTipoProductoRecomendaciones(".$_POST['idTipo'].");";
$row = mysqli_fetch_array($sql, MYSQLI_ASSOC);
print_r($row['tipopRecomendacion']);
/*while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{
	$filas[$i]= $row;
	$i++;
}
mysqli_close($conection); //desconectamos la base de datos
echo json_encode($filas);*/
?>