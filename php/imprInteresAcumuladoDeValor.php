<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Cuadro de intereses proyectado</title>
</head>
<body>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<style>
*{font-size: 11px;}
tr, .table-condensed>tbody>tr>td{
	
	padding-top: 0px; padding-bottom: 0px;
}
</style>

<div class="col-xs-4">
<strong>Pago Intereses</strong>
	<p><strong>Monto inicial: S/. <?php echo $_GET['inicio']; ?></strong></p>
	<table class="table-condensed">
	<thead>
	  <tr>
		<th>Día</th>
		<th>Monto</th>
	  </tr>
	</thead>
	<tbody>




<?php 
/*--------- Reglas -------------
Monto: 1 - 5000 = 0.6% diario (0.006)
Monto: 5001 - 15000 = 0.4 % diario  (0.004)
por defecto el mínimo 10%
--------- Reglas -------------*/



$montoInicial=0; $DiadeHoy=0;
$montoInicial=$_GET['inicio'];// $montoInicial =5001;
$DiadeHoy=$_GET['numhoy']; //$DiadeHoy=1;

if($montoInicial<=5000){$interesDiario=0.006;}
else{$interesDiario=0.004;}

$interesAcumulado=$montoInicial;

//echo 'Monto incial: '. $montoInicial.'<br>';
$filas=array();

$filas[0][]=array(
		'montoInicial' => $montoInicial,
		'diaHoy'=> $DiadeHoy
		);
for ($i=1; $i <=60 ; $i++) { 
	$interesAcumulado+= $interesAcumulado*$interesDiario ;
	//echo 'Dia: ' .$i.'. Interes Acu:'.number_format($interesAcumulado,2).'<br>';
	$filas[1][]= array(
		'numDia' => $i,
		'intAcum' => number_format($interesAcumulado,2)

	);
}
//return  array($filas);

if($DiadeHoy!=0){
	if($montoInicial<=5000){
		/**/
		echo '<tr> <td> 1 al 14: </td> <td>S/. '.$filas[1][14-1]['intAcum'].'</td> </tr>';
		for ($i=15; $i <=30 ; $i++) { 
			echo '<tr> <td> '.$i.': </td> <td>S/. '.$filas[1][$i-1]['intAcum'].'</td> </tr>';
		}
	}else{
		echo '<tr> <td> 1 al 24: </td> <td>S/. '.$filas[1][24-1]['intAcum'].'</td> </tr>';
		for ($i=25; $i <=30 ; $i++) { 
			echo '<tr> <td> '.$i.': </td> <td>S/. '.$filas[1][$i-1]['intAcum'].'</td> </tr>';
		}
		
	}

}else{echo 'Vacio';}



//echo json_encode($filas);
 ?>
 
 	</tbody>
	</table>
	<p>www.perucash.com <br>#943798696</p>
</div>
</body>
</html>