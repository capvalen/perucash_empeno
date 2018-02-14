<?php 

setcookie("color2","fresa");

if (isset ($_COOKIE["color2"]) ){
echo $_COOKIE["color2"];	
}else{
	echo 'no hay cookie';
}



 ?>