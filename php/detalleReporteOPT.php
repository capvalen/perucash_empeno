<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT * FROM `tipoProceso` where idTipoProceso in (23, 24, 37, 38, 68, 72, 78) ORDER BY tipoDescripcion ASC");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo '<option class="optProducto mayuscula" data-tokens="'.$row['idTipoProceso'].'">'.$row['tipoDescripcion'].'</option>';

}
//mysqli_close($conection); //desconectamos la base de datos

?>