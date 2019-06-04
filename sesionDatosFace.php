<?php
session_start();
ini_set('display_errors', 1);
require_once "php/facebookConect.php";
include 'php/conkarl.php';
include 'php/variablesGlobales.php';




try {
   $accessToken = $helper->getAccessToken();
} catch (\Facebook\Exceptions\FacebookResponseException $e) {
   echo "Facebook Exepcion: ".$e->getMessage();
} catch (\Facebook\Exceptions\FacebookSDKException $e) {
   echo "SDK Exepcion: ".$e->getMessage();
}

if( !$accessToken ){
   header('Location: sesionFacebook.php');
   exit();
}

$oAuth2Client = $FB->getOAuth2Client();
if(!$accessToken->isLongLived())
$accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);

$response = $FB -> get('/me?fields=id,first_name, last_name, email, picture.type(large)', $accessToken);

$userData = $response ->getGraphNode()->asArray();


$_SESSION['userData'] = $userData;
$_SESSION['access_token'] = (string) $accessToken;
//echo '<pre>'. var_dump($userData);
//print_r($_SESSION);


$sql="SELECT `idUsuario` from usuario WHERE `idFacebook` = '{$userData['id']}' and usuActivo=1";
$resultado=$cadena->query($sql);
if( $resultado->num_rows>=1 ){ //Ya existía
   $_POST['idFace'] = $userData['id'];
   require 'php/validarSesion.php';


   $sqlUpdate="INSERT INTO `usuario` set  `usuEMail` = '{$userData['email']}', `faceMiniatura` =  '{$userData['picture']['url']}' ;";;
   $resultadoUpdate=$cadena->query($sqlUpdate);

   header('Location: http://perucash.com/'.$folder.'/principal.php');
}else{ // su primer inicio
   $sqlInsert="INSERT INTO `usuario`(`idUsuario`, `usuNombres`, `usuApellido`, `usuNick`, `usuPass`, `usuPoder`, `idSucursal`, `usuActivo`, `usuEMail`, `idFacebook`, `faceMiniatura`) VALUES (null, '{$userData['first_name']}', '{$userData['last_name']}', '', '', 7, 1, 1, '{$userData['email']}', '{$userData['id']}', '{$userData['picture']['url']}');";;
   $resultadoInsert=$cadena->query($sqlInsert);
   
   header('Location: bienvenido.php');
}




?>