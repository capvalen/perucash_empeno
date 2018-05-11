<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT * FROM `DetalleReporte` where idDetalleReporte>=15 and idDetalleReporte<>22 ORDER BY repoDescripcion ASC");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo '<option class="optProducto mayuscula" data-tokens="'.$row['idDetalleReporte'].'">'.$row['repoDescripcion'].'</option>';

}
//mysqli_close($conection); //desconectamos la base de datos

?>