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
 
    $connector = new WindowsPrintConnector("smb://ICARUS/TM-U220");
try {
    
    // A FilePrintConnector will also work, but on non-Windows systems, writes
    // to an actual file called 'LPT1' rather than giving a useful error.
    // $connector = new FilePrintConnector("LPT1");

    /* Print a "Hello world" receipt" */
    $printer = new Printer($connector);
    $printer -> text("                PeruCash\n");
    $printer -> text("      Casa de Préstamos y Empeños\n");
    $printer -> text("          Oficina de Apoyo N° 1\n");
    $printer -> text("   ----------------------------------\n\n");
    $printer -> text("   ".$_POST['hora']."\n\n");
    $printer -> text("Cliente: ".ucwords($_POST['cliente'])."\n\n");
    $printer -> text("Artículo: ".ucwords(strtolower($_POST['articulo']))."\n");
    $printer -> text("Obs. ".ucwords(strtolower($_POST['obs']))."\n\n");
    $printer -> text("Monto entregado: S/. ".$_POST['monto']."\n\n");
    //$printer -> text("Fecha límite Sábado, 4 Enero 2017. Posterior a ésta fecha el monto incrementará.\n");
    $printer -> text("Usuario: ".ucwords($_POST['usuario'])."\n");
    $printer -> text("   ----------------------------------\n");
    $printer -> text("      Celular: # 943 798696\n");
    $printer -> text("      Web: www.perucash.com\n");
    $printer -> text("   Gracias por tu preferencia\n\n");
    $printer -> cut();

    /* Close printer */
    $printer -> close();
} catch (Exception $e) {
    echo "No se pudo imprimir en la impresora: " . $e -> getMessage() . "\n";
}
