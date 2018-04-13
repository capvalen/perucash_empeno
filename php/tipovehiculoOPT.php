<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT * FROM `tipovehiculo` order by vehDescripcion;");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo '<option class="optVehiculo mayuscula" data-tokens="'.$row['idTipoVehiculo'].'">'.$row['vehDescripcion'].'</option>';

}
mysqli_close($conection); //desconectamos la base de datos

?>