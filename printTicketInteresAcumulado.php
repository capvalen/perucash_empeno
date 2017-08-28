<?php
session_start();
/* Change to the correct path if you copy this example! */
require __DIR__ . '/vendor/mike42/escpos-php/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

/**
 * Assuming your printer is available at LPT1,
 * simpy instantiate a WindowsPrintConnector to it.
 *
 * When troubleshooting, make sure you can send it
 * data from the command-line first:
 *  echo "Hello World" > LPT1
 */
 
$montoInicial=0; $DiadeHoy=0;
$montoInicial=$_GET['inicial'];// $montoInicial =5001;
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
$textoInteres='';
if($DiadeHoy!=0){
	if($montoInicial<=5000){
		/*$textoInteres=$textoInteres."1 al 14:  S/. ".$filas[1][14-1]['intAcum']."\n";
		for ($i=14; $i <=31 ; $i++) {
			$textoInteres=$textoInteres.$i.":  S/. ".$filas[1][$i]['intAcum']."\n");
			}
	}else{
			$textoInteres=$textoInteres."1 al 24:  S/. ".$filas[1][24-1]['intAcum']."\n");
			for ($i=24; $i <=31 ; $i++) {
				$textoInteres=$textoInteres.$i.":  S/. ".$filas[1][$i]['intAcum']."\n");
			}*/
		$textoInteres=$textoInteres."1 al 14:  S/. ".$filas[1][14-1]['intAcum']."\n";
		for ($i=14; $i <=31 ; $i++) { 
			$textoInteres=$textoInteres.$i.":  S/. ".$filas[1][$i]['intAcum']."\n";
		}
	}
	else{
		$textoInteres=$textoInteres."1 al 24:  S/. ".$filas[1][24-1]['intAcum']."\n";
		for ($i=24; $i <=31 ; $i++) { 
			$textoInteres=$textoInteres.$i.":  S/. ".$filas[1][$i]['intAcum']."\n";
		}
	}

}else{$textoInteres='Vacio';}

	$connector = new WindowsPrintConnector("smb://127.0.0.1/TM-U220");
try {
	
	// A FilePrintConnector will also work, but on non-Windows systems, writes
	// to an actual file called 'LPT1' rather than giving a useful error.
	// $connector = new FilePrintConnector("LPT1");

	/* Print a "Hello world" receipt" */
	$printer = new Printer($connector);
	$printer -> text("       * Pago de interes acumulado *\n\n");
	$printer -> text("Monto inicial: S/. ".$_GET['inicial']."\n");
	$printer -> text("Dia      S/. \n");

	
	$printer -> text($textoInteres);
	$printer -> text("   ----------------------------------\n");
	$printer -> text("         Celular: # 943 798696\n");
	$printer -> text("         Web: www.perucash.com\n");
	$printer -> text("       Gracias por tu preferencia\n\n");
	$printer -> cut();

	/* Close printer */
	$printer -> close();
} catch (Exception $e) {
	echo "No se pudo imprimir en la impresora: " . $e -> getMessage() . "\n";
}
