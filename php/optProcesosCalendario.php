<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT * FROM `tipoProceso` where idTipoProceso in (92, 93, 94) order by tipoDescripcion asc");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo '<option class="optPagos mayuscula" value="'.$row['idTipoProceso'].'">'.$row['tipoDescripcion'].'</option>';


}
//mysqli_close($conection); //desconectamos la base de datos

?>