<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require "php/conkarl.php";
require_once('vendor/autoload.php');
$base58 = new StephenHill\Base58();
$correo=$base58->decode($_GET['solicita']);

$sql="UPDATE `usuario` SET 
`usuPass` = md5('".$_POST['nP']."')
where usuEmail = '".$correo."' and `usuActivo`=1";
$resultado=$cadena->query($sql);
if($resultado){
   echo true;
}else{ echo false;}
?>