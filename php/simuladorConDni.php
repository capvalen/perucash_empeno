<?
date_default_timezone_set('America/Lima');

$fecha = new DateTime();

//echo "Hoy es: ". $fecha->format('d/m/Y') . "<br>";

// $fecha->add($intervalo);
// echo $fecha->format('d/m/Y') . "<br>";

// $fecha->add($intervalo);
// echo $fecha->format('d/m/Y') . "<br>";

$feriados = include "feriadosProximos.php";
$monto = $_POST['monto'];
$suma=0;

//Para saber si es sábado(6) o domingo(0):  format('w') 
switch ($_POST['modo']){
	case "1":
		$plazo = 20;
		$interes = 1.16;
		$intervalo = new DateInterval('P1D'); //aumenta 1 día
		break;
	case "2":
		$plazo = 4;
		$interes = 1.16;
		$intervalo = new DateInterval('P1W'); //aumenta 1 día
		break;
	case "3":
		$plazo = 1;
		$interes = 1.16;
		$intervalo = new DateInterval('P28D'); //aumenta 1 día
		break;
	default:
	?> <tr><td>Datos inválidos</td></tr><?
	break;
}


$cuota = round($monto*$interes/$plazo,2);
for ($i=0; $i < $plazo ; $i++) {
?> <tr> <?
	$fecha->add($intervalo);
	$razon = esFeriado($feriados, $fecha->format('Y-m-d'));
	if($razon!=false ){
		//echo "si es feriado";
		//echo "Feriado ".": ". $fecha->format('d/m/Y'). "<br>";
		?><td class='red-text text-lighten-1'>Feriado</td> <td class='red-text text-lighten-1'><?= $fecha->format('d/m/Y'); ?></td> <td><?= $razon; ?></td><?
		$i--;
	}else{
		//echo "no es feriado";
		if( $fecha->format('w')=='0' ){
			//No hacer nada
			//echo "Domingo ".": ". $fecha->format('d/m/Y'). "<br>";
			?><td class='red-text text-lighten-1'>Domingo</td> <td class='red-text text-lighten-1'><?= $fecha->format('d/m/Y'); ?></td> <td></td><?
			$i--;
		}else if($fecha->format('w')=='6'){
			//echo "Sábado ".": ". $fecha->format('d/m/Y'). "<br>";
			?><td class='red-text text-lighten-1'>Sábado</td> <td class='red-text text-lighten-1'><?= $fecha->format('d/m/Y'); ?></td> <td></td><?
			$i--;
		}else{
			$suma+=$cuota;
			//echo "Día #".($i+1).": ". $fecha->format('d/m/Y') . "<br>";
			?><td class='grey-text text-darken-2'>Letra # <?= $i+1; ?></td> <td class='grey-text text-darken-2'><?= $fecha->format('d/m/Y'); ?></td> <td class='grey-text text-darken-2'>S/ <?= number_format($cuota, 2); ?></td><?
		}
	}
?></tr><?
}
?><tr><td></td><td><strong>Total:</strong></td><td><strong>S/ <?= number_format($suma,2); ?></strong></td></tr><?


function esFeriado($feriados, $dia){
	foreach ($feriados as $llave => $valor) {
		if($valor["ferFecha"]==$dia){
			return $valor["ferDescripcion"]; break;
		}
	}
	return false;
}
?>