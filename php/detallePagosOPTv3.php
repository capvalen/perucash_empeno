<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT * FROM `tipoProceso` where idTipoProceso in (45, 44, 43, 32, 76, 36, 33, 21) order by tipoDescripcion asc");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo '<option class="optPagos mayuscula" data-tokens="'.$row['idTipoProceso'].'">'.$row['tipoDescripcion'].'</option>';

}
//mysqli_close($conection); //desconectamos la base de datos

?>