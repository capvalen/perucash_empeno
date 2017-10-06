<?php 
require("conkarl.php");
$filas=array();
$sql = mysqli_query($conection,"call listarProductosPorClienteODni('".$_POST['texto']."');");

$i=0;
// if (!$sql) { ////codigo para ver donde esta el error
//     printf("Error: %s\n", mysqli_error($conection));
//     exit();
// }
while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{
	$filas[$i]= $row;
	$i++;
}
mysqli_close($conection); //desconectamos la base de datos
echo json_encode($filas);
?>