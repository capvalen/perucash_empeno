<?
header('Content-Type: text/html; charset=utf8');
date_default_timezone_set('America/Lima');
include 'conkarl.php';

$sql="SELECT * FROM `prestamo_cuotas`
where idPrestamo= {$_POST['idPre']} and idTipoPrestamo in (79, 33) and cuotFechaPago<=curdate() 
order by cuotFechaPago asc;";
//echo $sql;
$dinero= floatval($_POST['dinero']);
$debe=0;
$precioMora = 2;

if($log=$conection->query($sql)){
	
	while($row = mysqli_fetch_array($log, MYSQLI_ASSOC)){
		$debe = $debe + floatval($row['cuotCuota']);
	}
	//echo "debe fuera de mora ".$debe; //fuera de mora

	mysqli_data_seek($log, 0);

	

	$sqlMoraCalc= "SELECT datediff( curdate() , prc.cuotFechaPago ) as diasDeuda
	FROM `prestamo` pre
	inner join prestamo_cuotas prc on prc.idPrestamo = pre.idPrestamo
	where prc.idTipoPrestamo in (79, 33) and prc.cuotFechaPago<curdate() and pre.idPrestamo = {$_POST['idPre']}
	order by prc.cuotFechaPago asc
   limit 1";
	$resultadoMoraCalc=$cadena->query($sqlMoraCalc);
	while($rowMoraCalc=$resultadoMoraCalc->fetch_assoc()){
		$moraTotal = floatval($rowMoraCalc['diasDeuda'])*$precioMora;
		$moraFuera = floatval($moraTotal-$_POST['cuantoPerdona']);
		$moraPaga = $_POST['cuantoPerdona'];
		
		if( $_POST['perdonaMora']=='true' ){ //Si Perdona
			//Cancelar una parte de Mora reducida por post
			$sqlMoraVerif= "INSERT INTO `tickets`(`idTicket`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `subCuota`) VALUES
			(null,0,84,now(),{$moraPaga},'<a href=creditos.php?credito={$_POST['idPre']}>CR-{$_POST['idPre']}</a> <span>Mora Exonerada: S/ {$moraFuera}</span>',1,{$_COOKIE['ckidUsuario']},0, {$rowMoraCalc['idCuota']});";
			$dinero = $dinero-$moraPaga;
		}else{ //No Perdona ->Cobra todo
			//Cancelar toda la mora.
			$sqlMoraVerif= "INSERT INTO `tickets`(`idTicket`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `subCuota`) VALUES
			(null,0,83,now(),{$moraTotal},'<a href=creditos.php?credito={$_POST['idPre']}>CR-{$_POST['idPre']}</a>',1,{$_COOKIE['ckidUsuario']},0, {$rowMoraCalc['idCuota']});";
			$dinero = $dinero-$moraTotal;
		} 
		//echo $sqlMoraVerif;
		$esclavo->query($sqlMoraVerif);
	}

	
	$sqlCuotaVerifCabeza= "INSERT INTO `tickets`(`idTicket`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `subCuota`) VALUES ";
	
	while( $rowN = mysqli_fetch_array($log, MYSQLI_ASSOC) ) {
		
		if($dinero >=0 ){
			if( $dinero >= $rowN['cuotCuota']){
				//echo "\n".$rowN['idCuota'] . ' pago '. $rowN['cuotCuota'];

				$sqlCuotaVerif=$sqlCuotaVerif." (null,0,81,now(),{$rowN['cuotCuota']},'<a href=creditos.php?credito={$_POST['idPre']}>CR-{$_POST['idPre']}</a> <span>SP-{$rowN['idCuota']}</span>',1,{$_COOKIE['ckidUsuario']},0, {$rowN['idCuota']}),";
				
			}
			if($dinero<$rowN['cuotCuota']){
				$sqlCuotaVerif=$sqlCuotaVerif." (null,0,33,now(),{$dinero},'<a href=creditos.php?credito={$_POST['idPre']}>CR-{$_POST['idPre']}</a> <span>SP-{$rowN['idCuota']}</span>',1,{$_COOKIE['ckidUsuario']},0, {$rowN['idCuota']}),";
				//echo "\n".$rowN['idCuota'] . ' adelanta '. $dinero;
			}
		}else{
			break;
		}
		$dinero = $dinero-$rowN['cuotCuota'];
	}

	$sqlCuotaVerif = substr($sqlCuotaVerif,0,-1);
	//echo $sqlCuotaVerifCabeza.$sqlCuotaVerif;
	$esclavo->multi_query($sqlCuotaVerifCabeza.$sqlCuotaVerif);
	
	//Verificar si ya pagó todo
	$sqlCuot="SELECT count(idCUota) as `restanCuotas` FROM `prestamo_cuotas`
	where idPrestamo =  {$_POST['idPre']}
	and idTipoPrestamo in (33, 79);";

	$resultadoCuot=$cadena->query($sqlCuot);
	$rowCuot=$resultadoCuot->fetch_assoc();

	if($rowCuot['restanCuotas']>0){
		//echo 'faltan, no hacer nada';
	}else{
		$sqlPres="UPDATE `prestamo` SET 
		preIdEstado = 82
		where `idPrestamo`={$_POST['idPre']}";
		$resultadoPres=$cadena->query($sqlPres);
	} 
	echo true;
}else{
	echo "hubo un error";
}
?>