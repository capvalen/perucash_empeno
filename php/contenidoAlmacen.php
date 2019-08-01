<?php 
require("conkarl.php");
$filas=array();
$sql = mysqli_query($conection,"SELECT cu.*, s.zonaDescripcion, pi.numPiso, p.prodNombre FROM `cubicaje` cu
inner join seccion s on s.idZona = cu.idZona
inner join piso pi on pi.idPiso = cu.idPiso
inner join producto p on p.idProducto = cu.idProducto
where Idestante = {$_POST['almacen']} and idTipoProceso=23 and cuaVigente=1
order by cu.idProducto desc;");
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