<?php 

include "conexion.php";

$sql="SELECT claveCaja FROM `configuracion`;";
$resultado=$cadena->query($sql);
$row=$resultado->fetch_row();
echo $row[0];
?>