<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT * FROM `piso`;");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo '<option class="optPiso mayuscula" data-tokens="'.$row['idPiso'].'">'.$row['pisoDescripcion'].'</option>';

}
mysqli_close($conection); //desconectamos la base de datos

?>