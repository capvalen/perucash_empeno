<?php 
require("conkarl.php");
$sql = mysqli_query($conection,"select contarComprasHoy();");
while($row = mysqli_fetch_array($sql))
{
echo $row[0];
	
}
mysqli_close($conection); //desconectamos la base de datos
?>