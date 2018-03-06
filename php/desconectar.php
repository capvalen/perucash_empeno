<?php
/*session_start();
if ($_SESSION['Sucursal']) {
	session_destroy();
	
}*/


unset($_COOKIE['ckidSucursal']);
unset($_COOKIE['ckSucursal']);
unset($_COOKIE['ckAtiende']);
unset($_COOKIE['cknomCompleto']);
unset($_COOKIE['ckPower']);
unset($_COOKIE['ckidUsuario']);
unset($_COOKIE['ckoficina']);
unset($_COOKIE['ckcliente']);
unset($_COOKIE['ckllave']);


setcookie('ckidSucursal', null);
setcookie('ckSucursal', null);
setcookie('ckAtiende', null);
setcookie('cknomCompleto', null);
setcookie('ckPower', null);
setcookie('ckidUsuario', null);
setcookie('ckoficina', null);
setcookie('ckcliente', null);
setcookie('ckllave', null);



header("location: ../index.php");
?>