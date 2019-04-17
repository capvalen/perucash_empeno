<?
header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';
$idProd = $_POST['idProd'];

$sql="SELECT round(p.preCapital,2) as preCapital, preInteres,
case ps.presFechaCongelacion when '' then datediff( now(), desFechaContarInteres ) else datediff( ps.presFechaCongelacion, desFechaContarInteres ) end as `diferenciaDias`
FROM `prestamo_producto` p
left join prestamo ps on p.idPrestamo = ps.idPrestamo
where idProducto={$idProd};";

$filas = array();
$resultado=$cadena->query($sql);
$row=$resultado->fetch_assoc();

$sqlAdela="SELECT `sumarAdelantos`(".$_POST['idProd'].");";
$resultadoAdela=$esclavo->query($sqlAdela);
$rowAdela=$resultadoAdela->fetch_row();
$adelantos = $rowAdela[0];

$base = $row['preCapital'];
$dias = $row['diferenciaDias'];
$interes = $row['preInteres'];
$mora = 0;

if($dias==0){$dias++;}

if($dias>35){ //Cargar mora
   if( $base >=0 && $base<=200 ){      
		//echo 'entre 200';
		$mora = 5;
   }else if( $base >200 && $base<=1000 ){
		//echo 'entre 1000';
		$mora = 10;
   }else if( $base >1000 && $base<=3000 ){
		//echo 'entre 2000';
		$mora = 20;
   }else if( $base >3000  ){
		//echo 'entre 3000';
		$mora = 30;
   }
}else{
	//echo 'menos de 35 días';
	$mora = 0;
}


$cochera=0;
if($rowProducto['idTipoProducto']=="1" ):
	$cochera=2*$rowInteres['diferenciaDias'];
elseif( $rowProducto['idTipoProducto']=="11" ):
	$cochera=2*$rowInteres['diferenciaDias'];
elseif( $rowProducto['idTipoProducto']=="42" ):
	$cochera=1*$rowInteres['diferenciaDias'];
endif;





if( $dias <=7){
	$razonInteres= round($interes,2);
}else{
	$razonInteres= round(($dias * ($interes/7)),2);
}
$intCalculado= round((($base*$razonInteres)/100)-$adelantos,1,PHP_ROUND_HALF_UP );
$sumTotal = $base + $cochera + $mora + $intCalculado;

$filas[0] = array( "capital"=> $row['preCapital'], "penalizacion"=> $mora , "cochera"=> $cochera, "soloInteres"=> $interes , "soloDias"=> $dias, "interes"=> str_replace( ',', '', number_format($intCalculado,2)), "total"=> str_replace( ',', '', number_format($sumTotal,2)) ) ;



echo json_encode($filas);
?>