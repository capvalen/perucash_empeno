<?php 
$server="localhost";

$username="perucash_root";
$password="";
$db='perucash_app';


$esclavo= new mysqli($server, $username, $password, $db);
$esclavo->set_charset("utf8");



$sql="UPDATE `configuraciones` SET `claveCaja`= concat(ROUND(RAND() * (99-1) + 1) , CHAR(ROUND(RAND() * (122-97) + 97)), ROUND(RAND() * (9-1) + 1) ) WHERE 1";
$resultado=$esclavo->query($sql);
$esclavo->close();

echo "clave cambiada";

?>