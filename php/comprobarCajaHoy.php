<?php 
require("conkarl.php");


$fecha=date('Y-m-d');
$sql = mysqli_query($esclavo,"call cajaActivaHoy('".$fecha."');");

$numRow = mysqli_num_rows($sql);
$row = mysqli_fetch_array($sql, MYSQLI_ASSOC);

if($numRow>=1){ $_POST['cajaActiva']=true;}
else { $_POST['cajaActiva']=false;}

return $_POST['cajaActiva'];
mysqli_close($esclavo); //desconectamos la base de datos


?>
