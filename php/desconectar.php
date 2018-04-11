<?php
session_start();
setcookie("ckidSucursal", "", time() - 3600);
setcookie("ckSucursal", "", time() - 3600);
setcookie("ckAtiende", "", time() - 3600);
setcookie("cknomCompleto", "", time() - 3600);
setcookie("ckPower", "", time() - 3600);
setcookie("ckidUsuario", "", time() - 3600);
setcookie("ckoficina", "", time() - 3600);
if ($_SESSION['Sucursal']) {
	session_destroy();
	
}
header("location:..\index.php");
?>