<?php 
/*--------- Reglas -------------
Monto: 1 - 5000 = 0.683% diario (0.00683)
Monto: 5001 - 15000 = 0.4 % diario  (0.004)
por defecto el mínimo 10%
--------- Reglas -------------*/

$montoInicial=floatval($_GET['inicio']);// $montoInicial =5001;
$DiadeHoy=$_GET['numhoy']; //$DiadeHoy=1;

$maximoDias=99;


if($montoInicial<=5000){$interesDiario=0.04/7;}
else{$interesDiario=0.04/7;}

$interesAcumulado=$montoInicial;
$intDiarioAcumulado=0;


$filas=array();

$filas[0][]=array(
		'montoInicial' => $montoInicial,
		'diaHoy'=> $DiadeHoy
		);
for ($i=1; $i <=$maximoDias ; $i++) {
	$interesAcumulado+= $interesAcumulado*$interesDiario ;
	$intDiarioAcumulado+=$interesDiario;
	//echo 'Dia: ' .$i.'. Interes Acu:'.number_format($interesAcumulado,2).'<br>';
	$filas[1][]= array(
		'numDia' => $i,
		'intAcum' => round($interesAcumulado,1, PHP_ROUND_HALF_UP),
		'intDiario' => $intDiarioAcumulado

	);
}
if($DiadeHoy==0){$filas[2][]=array('pagarAHoy' => 0);}
else if($DiadeHoy<=14){$filas[2][]=array('pagarAHoy' => round($filas[1][14-1]['intAcum'],1, PHP_ROUND_HALF_UP), 'intDiarioHoy' => $filas[1][14-1]['intDiario'] );} //cuando le monto es mejor a 5000 manda al dia 14 por defecto 0.1
else if($DiadeHoy<=$maximoDias ){$filas[2][]=array('pagarAHoy' => round( $filas[1][$DiadeHoy-1]['intAcum'],1, PHP_ROUND_HALF_UP), 'intDiarioHoy' => $filas[1][$DiadeHoy-1]['intDiario'] ); } //cuando le monto es mejor a 5000 calcula su deuda al dia

echo json_encode($filas);
//return json_encode($filas);


 ?>