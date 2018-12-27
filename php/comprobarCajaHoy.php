<?php 
require("conkarl.php");


$fechaCompr=date('Y-m-d');
$sql = mysqli_query($esclavo,"SELECT idCuadre FROM `cuadre` cu
where cu.cuaVigente =1"); // DATE_FORMAT(`fechaInicio`, '%Y-%m-%d') = '{$fechaCompr}' and 

$numRow = mysqli_num_rows($sql);
$row = mysqli_fetch_array($sql, MYSQLI_ASSOC);

//echo date("Y-m-d H:i:s");
if($numRow>=1){ $idCaja =  $row['idCuadre']; /* $_POST['cajaActiva']=true; */ }
else { $idCaja =0; /* $_POST['cajaActiva']=false; */}

return $idCaja; // $_POST['cajaActiva'];
mysqli_close($esclavo); //desconectamos la base de datos


?>
