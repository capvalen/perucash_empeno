<?php 

include "conexion.php";

$sql="SELECT `crearToken`() AS `crearToken`;";
$resultado=$cadena->query($sql);

$row=$resultado->fetch_assoc();
echo $row['crearToken'];

?>