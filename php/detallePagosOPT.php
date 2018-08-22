<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT * FROM `tipoProceso` where idTipoProceso in (28, 33, 36, 44, 45, 32,21, 43, 76) order by tipoDescripcion asc");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo '<option class="optPagos mayuscula" data-tokens="'.$row['idTipoProceso'].'">'.$row['tipoDescripcion'].'</option>';

}
//mysqli_close($conection); //desconectamos la base de datos

?>