<?
header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';

$sql="SELECT idUsuario FROM `usuario`
where usuNick = lower('{$_POST['texto']}');
";
$conjunto=$cadena->query($sql);
$lineas = $conjunto->num_rows;
if($lineas>=1){
   ?>
   <p class="red-text text-darken-1">
      El nick que intentas ingresar ya existe
   </p>
   <?
}else{
   ?>
   <p class="light-green-text"> El nick está libre </p>
   <?
}

?>