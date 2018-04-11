<?php 
$campo= $_POST['jsonDe'];
print_r($campo);
//echo $campo[0]['campo1'];

for ($i=0; $i < count($campo) ; $i++) { 
	echo $campo[$i]['cantidad']."\n";
}

 ?>