<?
header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

$comentario = '';
if($_POST['nueObs']<>''){
   $comentario = '<p>'.$_POST['nueObs'].'</p>';
}
$sql="UPDATE `cuadre` SET `cuaCierre` = '{$_POST['nueVal']}', cuaObsCierre = concat( cuaObsCierre ,'{$comentario}') WHERE `idCuadre` = {$_POST['cuadre']};";

if($cadena->query($sql)){ 
   echo $_POST['cuadre'];
}else{
   echo '-1';
}

?>