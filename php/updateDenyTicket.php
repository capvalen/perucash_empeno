<?
header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

$sql="UPDATE `tickets` SET
cajaActivo = 2,
`idAprueba` = {$_COOKIE['ckidUsuario']}
where `idTicket` = {$_POST['ticket']};
";
if($cadena->query($sql)){
   echo 1;
}else{
   echo "Hay un error interno de sentencias";
}

?>