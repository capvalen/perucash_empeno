<?php
require("conkarl.php");

$sql="UPDATE `prestamo_producto` SET 
`desFechaContarInteres`= '{$_POST['newFecha']}'
WHERE `idProducto`= {$_POST['idProd']};";

if($cadena->query($sql)){
   echo $_POST['idProd'];
}else{
   echo "Error con la sentencia";
}



?>