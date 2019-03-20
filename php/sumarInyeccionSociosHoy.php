<?php 
require("conkarl.php");
$sql = mysqli_query($conection,"select sumarInyeccionSociosHoy();");
while($row = mysqli_fetch_array($sql))
{
echo number_format($row[0],2);
	
}
mysqli_close($conection); //desconectamos la base de datos
?>