<?php 
include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');


$sql= "INSERT INTO `calendario`(`idCalendario`, `calFecha`, `idUsuario`, `calTitulo`, `calDescripcion`, `idTipoProceso`, `calcEstado`) VALUES (null, '{$_POST['fecha']}', '{$_COOKIE['ckidUsuario']}', '{$_POST['titulo']}', '{$_POST['descipcion']}', {$_POST['proceso']}, 1); ";

//echo $sql;
if ($llamadoSQL = $conection->query($sql)) { //Ejecución mas compleja con retorno de dato de sql del procedure.
  echo 'todo ok';
}else{echo mysql_error( $conection);}
?>