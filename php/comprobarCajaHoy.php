<?php 
require("conkarl.php");


$fechaCompr=date('Y-m-d');
$sql = mysqli_query($esclavo,"SELECT idCuadre FROM `cuadre` cu
where DATE_FORMAT(`fechaInicio`, '%Y-%m-%d') = '{$fechaCompr}' and cu.cuaVigente =1");

$numRow = mysqli_num_rows($sql);
$row = mysqli_fetch_array($sql, MYSQLI_ASSOC);

//echo date("Y-m-d H:i:s");
if($numRow>=1){ $_POST['cajaActiva']=true;}
else { $_POST['cajaActiva']=false;}

return $_POST['cajaActiva'];
mysqli_close($esclavo); //desconectamos la base de datos


?>
