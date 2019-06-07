<?php 
include 'conkarl.php';

$sql="SELECT idProducto  FROM producto where prodActivo =1;";
$resultado=$cadena->query($sql);
while($row=$resultado->fetch_assoc()){ 
   echo "UPDATE `cubicaje` SET `cuaVigente`=1 WHERE idProducto = {$row['idProducto']} ORDER BY cubFecha DESC LIMIT 1; "."<br>";
}
?>