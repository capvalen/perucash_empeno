<?php
$server="localhost";

/* Net	*/
	$username="perucash_root";
	$password="pr6sTuFru5*ePHE#eq*";
	$db='perucash_app';
//*/
/* Local
	$username="root";
	$password="*123456*";
	$db='peru_app';
*/
	$conection= mysqli_connect($server,$username,$password)or die("No se ha podido establecer la conexion");
	$sdb= mysqli_select_db($conection,$db)or die("La base de datos no existe");
	$conection->set_charset("utf8");
	
?>