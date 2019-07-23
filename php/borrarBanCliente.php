<?php 
include 'conkarl.php';

$sql="DELETE FROM `baneados` WHERE `idBaneado` = {$_POST['idBan']}; ";
$resultado=$cadena->query($sql);
echo true;
?>