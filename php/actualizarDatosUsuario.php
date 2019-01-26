<?
header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

if( isset($_POST['correo']) ){
   $sql="UPDATE `usuario` SET `usuEMail` = TRIM('{$_POST['correo']}') WHERE `idUsuario` = {$_COOKIE['ckidUsuario']}";
   
   if($cadena->query($sql)){ 
      echo $_COOKIE['ckidUsuario'];
   }else{
      echo '-1';
   }
}

if( isset($_POST['passw']) ){
   $sql="UPDATE `usuario` SET `usuPass` = md5('{$_POST['passw']}') WHERE `idUsuario` = {$_COOKIE['ckidUsuario']}";
   
   if($cadena->query($sql)){ 
      echo $_COOKIE['ckidUsuario'];
   }else{
      echo '-1';
   }
}

?>