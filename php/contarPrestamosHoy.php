<?php 
require("conkarl.php");
$sql = mysqli_query($conection,"select contarPrestamosHoy();");
while($row = mysqli_fetch_array($sql))
{
echo $row[0];
	
}
mysqli_close($conection); //desconectamos la base de datos
?>