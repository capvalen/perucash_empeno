<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT * FROM Cliente where cliActivo =1 ORDER BY `cliApellidos` ASC");

while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

echo '<option class="optCliente mayuscula" data-tokens="'.$row['idCliente'].'">'.$row['cliDni'].' - '.$row['cliApellidos'].' '.$row['cliNombres'].'</option>';

}
mysqli_close($conection); //desconectamos la base de datos

?>