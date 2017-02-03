<?php 
session_start();
header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$log = mysqli_query($conection,"select * from  usuario where usuNick = '".$_POST['user']."' and usuPass='".md5($_POST['pws'])."' and usuActivo=1;");


$row = mysqli_fetch_array($log, MYSQLI_ASSOC);
if ($row['idUsuario']>=1){
	$_SESSION['Sucursal']=$_POST['user'];
	$_SESSION['Atiende']=$_POST['nomb'];
	$_SESSION['Power']=$row['usuPoder'];
	$_SESSION['idUsuario']=$row['idUsuario'];
	echo "Welcome guy!";
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexión */
mysqli_close($conection);

 ?>