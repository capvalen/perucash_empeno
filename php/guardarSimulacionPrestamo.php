<?
date_default_timezone_set('America/Lima');
include 'conkarl.php';
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
		echo "Error en modo";
	break;
}

$sql="INSERT INTO `prestamo`(`idPrestamo`, `idCliente`, `preCapital`, `idModo`, `prePorcentaje`, `preFechaInicio`, `idUsuario`, `presActivo`, `presObservaciones`, `preIdEstado`, `prePlazo`)
value (null, 942, {$_POST['monto']}, {$_POST['modo']}, {$interes}, now(), {$_COOKIE['ckidUsuario']}, 1, '', 78 , {$plazo});";

if($cadena->query($sql)){
	//$row = mysqli_fetch_array($log, MYSQLI_ASSOC);
	$idPrestamo = $cadena->insert_id;
	
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
echo $sqlCuotas;
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