<?php
session_start();
if ($_SESSION['Sucursal']) {
	session_destroy();
	
}
header("location:..\index.php");
?>