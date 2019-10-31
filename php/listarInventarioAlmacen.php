<?php 
require("conkarl.php");
$filas=array();
$sql = mysqli_query($conection,"SELECT cb.*, p.prodNombre, p.idCliente, p.prodMontoEntregado, p.prodCantidad, pre.preCapital , tpr.tipopDescripcion
FROM `cubicaje` cb
inner join producto p on p.idProducto = cb.idProducto
inner join prestamo_producto pre on pre.idProducto = p.idProducto
inner join tipoProducto tpr on tpr.idTipoProducto = p.idTipoProducto
where cuaVigente=1 or ( p.esCompra =1 and p.prodActivo =1 )");
//echo "call listarInventarioPorEstado('".$_POST['estado']."');";
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