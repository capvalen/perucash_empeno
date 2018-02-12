<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
<h3>Creacion de licencia</h3>
<?php 

$letras=['A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','1','2','3','4','5','6','7','8','9','0' ];
$licencia=str_replace('-','',substr(exec('getmac'),10,7));

for ($i=0; $i <17 ; $i++) { 
	if($i==0 || $i==5 || $i==11 || $i==17 ){
		$licencia=$licencia. '-';
	}else{
		$licencia=$licencia. $letras[rand(0, count($letras)-1)];
	}
}
echo $licencia;
echo '<br>';
/*str_replace('-','',substr(exec('getmac'),0,17));*/
if( substr($licencia,0,5) == str_replace('-','',substr(exec('getmac'),10,7))){
	echo 'Licencia correcta en una pc';
}else{
	echo 'Licencia nop válida para ésta pc';

}
 ?>
 