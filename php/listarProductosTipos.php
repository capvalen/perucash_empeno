<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"call listarProductosTipos();");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo '<option class="optProducto mayuscula" data-tokens="'.$row['idTipoProducto'].'">'.$row['tipopDescripcion'].'</option>';

}
mysqli_close($conection); //desconectamos la base de datos

?>