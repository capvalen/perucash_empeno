<?php 
include 'conkarl.php';

$sql="INSERT INTO `baneados`(`idBaneado`, `idCliente`, `banFecha`, `banMotivo`, `idUsuario`, `tipoBan`) 
VALUES (null, {$_POST['idCliente']},curdate(), '{$_POST['motivo']}', {$_COOKIE['ckidUsuario']}, {$_POST['tipoBan']} );";
$resultado=$cadena->query($sql);
echo true;          

?>