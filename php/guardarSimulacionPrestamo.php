<?
date_default_timezone_set('America/Lima');
include 'conkarl.php';
$fecha = new DateTime($_POST['sinFecha']);

//echo "Hoy es: ". $fecha->format('d/m/Y') . "<br>";

// $fecha->add($intervalo);
// echo $fecha->format('d/m/Y') . "<br>";

// $fecha->add($intervalo);
// echo $fecha->format('d/m/Y') . "<br>";

$idCliente='';
$cliente= $_POST['jsonCliente'];

$log = mysqli_query($conection,"SELECT idCliente from Cliente where cliDNI ='".$_POST['jsonCliente'][0]['dniCli']."';");
$row = mysqli_fetch_array($log, MYSQLI_ASSOC);

// Primero creamos o verificamos si el cliente ya se encuentra en las BD;
if( count($row)===1 ){
	$idCliente=$row['idCliente'];
}else{
	$newCliente= "INSERT INTO `Cliente`(`idCliente`, `cliApellidos`, `cliNombres`, `cliDni`, `cliDireccion`, `cliCorreo`, `cliCelular`, `cliFijo`, `cliCalificacion`) VALUES (null,'".$_POST['jsonCliente'][0]['apellidoCli']."','".$_POST['jsonCliente'][0]['nombresCli']."','".$_POST['jsonCliente'][0]['dniCli']."','".$_POST['jsonCliente'][0]['direccionCli']."','".$_POST['jsonCliente'][0]['correoCli']."','".$_POST['jsonCliente'][0]['celularCli']."','".$_POST['jsonCliente'][0]['fijoCli']."',0);";

	$conection->query($newCliente);

	$idCliente=$conection->insert_id;
}


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
		echo "Error en modo";
	break;
}

$sql="INSERT INTO `prestamo`(`idPrestamo`, `idCliente`, `preCapital`, `idModo`, `prePorcentaje`, `preFechaInicio`, `idUsuario`, `presActivo`, `presObservaciones`, `preIdEstado`, `prePlazo`)
value (null, {$idCliente}, {$_POST['monto']}, {$_POST['modo']}, {$interes}, now(), {$_COOKIE['ckidUsuario']}, 1, '', 78 , {$plazo});";

if($cadena->query($sql)){
	//$row = mysqli_fetch_array($log, MYSQLI_ASSOC);
	$idPrestamo = $cadena->insert_id;
	
	$sqlCaja= "INSERT INTO `caja`(`idCaja`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `idSucursal`) VALUES (null,0,78,now(),{$monto},'<a href=creditos.php?credito={$idPrestamo}>CR-{$idPrestamo}</a>',1,{$_COOKIE['ckidUsuario']},0,1);";
	//echo $sqlCaja;
	$cadena->query($sqlCaja);
	
}else{
	echo "hubo un error";
}

$cuota = round($monto*$interes/$plazo,2);
$sqlCuotas='';
for ($i=0; $i < $plazo ; $i++) {
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
         $sqlCuotas=$sqlCuotas."INSERT INTO `prestamo_cuotas`(`idCuota`, `idPrestamo`, `cuotFechaPago`, `cuotCuota`, `cuotFechaCancelacion`, `cuotPago`, `cuotObservaciones`, `idTipoPrestamo`) VALUES (null,{$idPrestamo},'{$fecha->format('Y-m-d')}',".round($cuota,2).",null,0,'', 79);";
		}
	}
}
//echo $sqlCuotas;
$esclavo->multi_query($sqlCuotas);



function esFeriado($feriados, $dia){
	foreach ($feriados as $llave => $valor) {
		if($valor["ferFecha"]==$dia){
			return $valor["ferDescripcion"]; break;
		}
	}
	return false;
}
echo $idPrestamo;

?>