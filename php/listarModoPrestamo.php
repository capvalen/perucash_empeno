<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT * FROM `modoPrestamo`;");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo '<option class="optModo mayuscula" data-tokens="'.$row['idModoPrestamo'].'" value="'.$row['modInteres'].'">'.$row['modDescripcion'].'</option>';

}
mysqli_close($conection); //desconectamos la base de datos

?>