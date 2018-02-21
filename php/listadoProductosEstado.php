<?php 
require("conkarl.php");
$filas=array();

$i=0;

$sql = mysqli_query($conection,"SELECT p.idProducto, p.prodNombre, p.prodMontoEntregado, concat(c.cliApellidos, ' ', c.cliNombres) as cliNombres, c.idCliente FROM `producto` p inner join Cliente c on c.idCliente=p.idCliente
where prodQueEstado= ".$_POST['estado']." ;");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{
	$filas[$i]= $row;
	$i++;
}
mysqli_close($conection); //desconectamos la base de datos
echo json_encode($filas);
?>