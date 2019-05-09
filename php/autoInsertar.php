<?php 
include 'conkarl.php';
$quePaga = $_POST['quePaga'][0];

$pagoCoch=0; $pagoPena=0; $pagoPartePena=0; $pagoInt=0; $pagoAdelaInt=0; $pagoAmor=0; $pagoFin=0;

if( floatval($quePaga["cochera"])>0 ){
   $sql="CALL ingresarPagoMaestro({$_POST['idProd']}, 34, {$quePaga["cochera"]}, now(), {$_COOKIE['ckidUsuario']}, '{$_POST['obs']}', {$_POST['moneda']}, 0)";
   $resultado=$cadena->query($sql);
   $pagoCoch=1;
}

if( floatval($quePaga["penalizacion"])>0 ){
   if( $_POST['todoMora']=="1"){
      $sql2="CALL ingresarPagoMaestro({$_POST['idProd']}, 83, {$quePaga["penalizacion"]}, now(), {$_COOKIE['ckidUsuario']}, '{$_POST['obs']}', {$_POST['moneda']}, 0);";
      $pagoPena=1;
   }else{
      $sql2="CALL ingresarPagoMaestro({$_POST['idProd']}, 84, {$quePaga["penalizacion"]}, now(), {$_COOKIE['ckidUsuario']}, '{$_POST['obs']}', {$_POST['moneda']}, 0);";
      $pagoPartePena=1;
   }
   //echo $sql2;
   $resultado2=$esclavo->query($sql2);
   
}

if( floatval($quePaga["interes"])>0 ){
   if( $_POST['todoInteres']=="1"){
      $sql3="CALL ingresarPagoMaestro({$_POST['idProd']}, 44, {$quePaga["interes"]}, now(), {$_COOKIE['ckidUsuario']}, '{$_POST['obs']}', {$_POST['moneda']}, 0);";
      $pagoInt=1;
  }else{
      $sql3="CALL ingresarPagoMaestro({$_POST['idProd']}, 33, {$quePaga["interes"]}, now(), {$_COOKIE['ckidUsuario']}, '{$_POST['obs']}', {$_POST['moneda']}, 0) ;";
      $pagoAdelaInt=1;
   }
   //echo $sql3;
   $resultado3=$cadena->query($sql3);
}

if( floatval($quePaga["capital"])>0 ){
   if( $_POST['todoCapital']=="1"){
      $sql4="CALL ingresarPagoMaestro({$_POST['idProd']}, 32, {$quePaga["capital"]}, now(), {$_COOKIE['ckidUsuario']}, '{$_POST['obs']}', {$_POST['moneda']}, 0);";
      $pagoFin=1;
   }else{
      $sql4="CALL ingresarPagoMaestro({$_POST['idProd']}, 45, {$quePaga["capital"]}, now(), {$_COOKIE['ckidUsuario']}, '{$_POST['obs']}', {$_POST['moneda']}, 0);";
      $pagoAmor=1;
   }
   //echo $sql4;
   $resultado4=$cautivo->query($sql4);
}

$filas = array();
$filas[0] = array("pagoCoch"=>$pagoCoch, "pagoPena"=>$pagoPena, "pagoPartePena"=>$pagoPartePena, "pagoInt"=>$pagoInt, "pagoAdelaInt"=>$pagoAdelaInt, "pagoAmor"=>$pagoAmor, "pagoFin"=>$pagoFin );

echo json_encode($filas);
 ?>