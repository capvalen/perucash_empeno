<?php 

include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');

$tipoProc=19;

$dinero = $_POST['dinero'];

if ($_POST['obs']==''){
	$obs= "";
}else{
	$obs= '<p>'.$_COOKIE['ckAtiende'].' dice: '.$_POST['obs'].'</p>';

}

$sqlBaseInteres="SELECT round(p.preCapital,2) as preCapital, p.desFechaContarInteres, datediff( now(), desFechaContarInteres ) as diferenciaDias, preInteres FROM `prestamo_producto` p where idProducto=".$_POST['idProducto'];


$sqlIntereses = $conection->query($sqlBaseInteres);
$rowInteres = $sqlIntereses->fetch_assoc();

$capital= floatval($rowInteres['preCapital']);
$fechas= $rowInteres['desFechaContarInteres'];
$diasDebe=$rowInteres['diferenciaDias'];
$tasaInteres= $rowInteres['preInteres'];


if($diasDebe==0){$diasDebe=1;}
if($diasDebe>=1 && $diasDebe <=7){ //plazo de gracia
	$debe= round($capital * $tasaInteres/100, 1,PHP_ROUND_HALF_UP); //caso que interés es 4% primero

	if ( $dinero < $debe ){
		echo ' Pago parcial de interés';
	}else if( $dinero == $debe ){
		echo ' Cancelación de interés';
	}else if ( $dinero == $debe+$capital ){
		echo ' Final de préstamo';
	}else if ( $dinero > $debe ){
		echo ' Amortización';
	}
}else if( $diasDebe>=8 && $diasDebe <=28 ){ //caso normal fuera de gracia. Interes semanal
	$interesDiario= ($tasaInteres /100)/7;
	
	$debe = number_format(round($capital *$interesDiario*$diasDebe,1,PHP_ROUND_HALF_UP),2);
	//echo $debe;
	
	if ( $dinero < $debe ){
		echo ' Pago parcial de interés = S/. ' . number_format($dinero,2) ;
	}else if( $dinero == $debe ){
		echo ' Cancelación de interés S/. ' . number_format($dinero,2) ;
	}else if ( $dinero == $debe+$capital ){
		echo ' Final de préstamo S/. ' . number_format($dinero,2) ;
	}else if ( $dinero > $debe ){
		echo ' Amortización S/. ' . number_format($dinero-$debe,2) . ' e Interés: S/. '. number_format($debe,2);
	}
}else if($diasDebe >=29 && $diasDebe <=56){ // caso prórroga. Interes compuesto.
	$gastos =10;
	$_GET['inicio']=$capital;
	$_GET['numhoy']=$diasDebe;
	$_GET['interes']=$tasaInteres;
	$resultado=(require_once "calculoInteresAcumuladoDeValorv3.php");
	//var_dump($resultado);

	$interesAcumulado= $resultado[0]['pagarAHoy'];
	$dinero -=10;
	$debe = number_format(round( $interesAcumulado ,1,PHP_ROUND_HALF_UP),2);
	
	if ( $dinero < $debe ){
		echo ' Pago parcial de interés = S/. ' . number_format($dinero,2) . ' con penalización S/. 10.00';
		$sql= "call crearTicketDepositar (".$_POST['idProd'].", {$tipoProc}, ".$_POST['monto']." , {$obs}, ".$_COOKIE['ckidUsuario'].")";
		//echo $sql;
		if ($llamadoSQL = $conection->query($sql)) { //Ejecución mas compleja con retorno de dato de sql del procedure.
			while ($resultado = $llamadoSQL->fetch_row()) {
				echo $resultado[0]; //Retorna los resultados via post del procedure
			}
			// liberar el conjunto de resultados 
			$llamadoSQL->close();
		}else{echo mysql_error( $conection);}
	}else if( $dinero == $debe ){
		echo ' Cancelación de interés = S/. ' . number_format($dinero,2) . ' con penalización S/. 10.00';
	}else if ( $dinero == $debe+$capital ){
		echo ' Final de préstamo = S/. ' . number_format($dinero,2) . ' con penalización S/. 10.00';
	}else if ( $dinero > $debe ){
		echo ' Amortización = S/. ' . number_format($dinero-$debe,2) . ' e Interés: S/. '. number_format($debe,2). ' con penalización S/. 10.00';;
	}

}
//print_r($capital);





/*
Procedimientos aprobados:
15 - Retiro
16 - Intención de pago
17 - Extraviado
18 - En remate
19 - En venta
20 - Rematado
21 - Vendido
22 - Sin acción
23 - En almacén
24 - En prórroga
25 - Esperando en cochera
26 - Retirado de cochera
27 - Entrada de cochera
28 - Ingreso de empeño
29 - No encontrado en almacén
30 - Inventariado en almacén
31 - Inyección de dinero
32 - Fin del préstamo
33 - Pago parcial de interés
34 - Pago de cochera
35 - Anticipo de ventas
36 - Penalización (Gasto Admin.)
37 - Empeño
38 - Compra
39 - Adelanto de personal
40 - Pago de personal
41 - Retiro por socios
42 - Gastos relacionados con la empresa
43 - Desembolso
44 - Cancelación de interés
45 - Amortización
46 - Entrada a almacén
47 - Salida de almacén
50 - Puntual
51 - Tardanza
52 - Falta
53 - Permiso
54 - Salida sin permiso
55 - Aumento
56 - Comisión
57 - Descuento
58 - Bonos
59 - Incidencias
60 - Extra
61 - Debe horario
*/
 ?>