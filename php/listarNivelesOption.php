<?php 
require("conkarl.php");
$sql = mysqli_query($conection,"select * from poder;");
while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{
echo '<option class="optProducto mayuscula" data-tokens="'.$row['idPoder'].'">'.$row['Descripcion'].'</option>';
}
mysqli_close($conection); //desconectamos la base de datos
?>