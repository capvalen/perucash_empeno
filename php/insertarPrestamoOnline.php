<?
header('Content-Type: text/html; charset=utf8');
date_default_timezone_set('America/Lima');
include 'conkarl.php';

$idCliente='';
$log = mysqli_query($conection,"SELECT idCliente from Cliente where cliDNI ='".$_POST['jCliente'][0]['dniCli']."';");
$row = mysqli_fetch_array($log, MYSQLI_ASSOC);

// Primero creamos o verificamos si el cliente ya se encuentra en las BD;
if( count($row)===1 ){
	$idCliente=$row['idCliente'];
}else{
	$newCliente= "INSERT INTO `cliente`(`idCliente`, `cliApellidos`, `cliNombres`, `cliDni`, `cliDireccion`, `cliCorreo`, `cliCelular`, `cliFijo`, `cliCalificacion`) VALUES (null,'".$_POST['jCliente'][0]['apellidosCli']."','".$_POST['jCliente'][0]['nombreCli']."','".$_POST['jCliente'][0]['dniCli']."','".$_POST['jCliente'][0]['direccionCli']."','".$_POST['jCliente'][0]['correoCli']."','".$_POST['jCliente'][0]['celularCli']."','".$_POST['jCliente'][0]['cotroCelularCli']."',0)";
	$conection->query($newCliente);
	
	$log2 = mysqli_query($conection,"SELECT idCliente from Cliente where cliDNI ='".$_POST['jCliente'][0]['dniCli']."';");
	$row2 = mysqli_fetch_array($log2, MYSQLI_ASSOC);
	$idCliente=$row2['idCliente'];
}

$fecha = new DateTime();

$feriados = include "feriadosProximos.php";
$monto = $_POST['monto'];

//Para saber si es sábado(6) o domingo(0):  format('w') 
switch ($_POST['modo']){
	case "1":
		$plazo = 20;
		$interes = 1.12;
		$intervalo = new DateInterval('P1D'); //aumenta 1 día
		break;
	case "2":
		$plazo = 4;
		$interes = 1.14;
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
		$i--;
	}else{
		//echo "no es feriado";
		if( $fecha->format('w')=='0' ){
			//No hacer nada
			//echo "Domingo ".": ". $fecha->format('d/m/Y'). "<br>";
			$i--;
		}else if($fecha->format('w')=='6'){
			//echo "Sábado ".": ". $fecha->format('d/m/Y'). "<br>";
			$i--;
		}else{
			$suma+=$cuota;
			//echo "Día #".($i+1).": ". $fecha->format('d/m/Y') . "<br>";
			?><td class='grey-text text-darken-2'>Letra # <?= $i+1; ?></td> <td class='grey-text text-darken-2'><?= $fecha->format('d/m/Y'); ?></td> <td class='grey-text text-darken-2'>S/ <?= number_format($cuota, 2); ?></td><?
		}
	}
?></tr><?
}


function esFeriado($feriados, $dia){
	foreach ($feriados as $llave => $valor) {
		if($valor["ferFecha"]==$dia){
			return $valor["ferDescripcion"]; break;
		}
	}
	return false;
}

?>