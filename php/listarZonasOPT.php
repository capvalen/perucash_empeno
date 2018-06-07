<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT * FROM `seccion`;");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo '<option class="optSeccion mayuscula" data-tokens="'.$row['idZona'].'">'.$row['zonaDescripcion'].'</option>';

}
mysqli_close($conection); //desconectamos la base de datos

?>