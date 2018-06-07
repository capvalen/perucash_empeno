<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT * FROM `estante` order by estDescripcion asc;");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo '<option class="optEstante mayuscula" data-tokens="'.$row['idEstante'].'">'.$row['estDescripcion'].'</option>';

}
mysqli_close($conection); //desconectamos la base de datos

?>