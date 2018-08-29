<?php

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
 
    //$connector = new WindowsPrintConnector("smb://192.168.1.131/TM-U220");
$connectorV31 = new WindowsPrintConnector("smb://127.0.0.1/TM-U220");
try {
    
    // A FilePrintConnector will also work, but on non-Windows systems, writes
    // to an actual file called 'LPT1' rather than giving a useful error.
    // $connector = new FilePrintConnector("LPT1");
    /* Print a "Hello world" receipt" */
    $printer = new Printer($connectorV31);
    $printer -> text("                PeruCash\n");
    $printer -> text("      Casa de Préstamos y Empeños\n");
    $printer -> text("          Oficina de Apoyo N° 1\n");
    $printer -> text("   ----------------------------------\n");
    $printer -> setEmphasis(true);    
    $printer -> text("{$_POST['titulo']}\n");
    $printer -> setEmphasis(false);
    $printer -> text("{$_POST['fecha']}\n\n");
    $printer -> text("Código: ".ucwords($_POST['codigo'])."\n");
    $printer -> text("Monto: S/. {$_POST['monto']}\n");
    //$printer -> text("Fecha límite Sábado, 4 Enero 2017. Posterior a ésta fecha el monto incrementará.\n");
    $printer -> text("Usuario: {$_POST['usuario']}\n");
    $printer -> text("   ----------------------------------\n");
    $printer -> text("Consultas al # Whatsapp: 943 798696\n");
    $printer -> text("   ----------------------------------\n");
    $printer -> text("         Web: www.perucash.com\n");
    $printer -> text("       Gracias por tu preferencia\n\n");
    $printer -> cut();
    /* Close printer */
    $printer -> close();
} catch (Exception $e) {
    echo "No se pudo imprimir en la impresora: " . $e -> getMessage() . "\n";
}