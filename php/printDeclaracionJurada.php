<?php 
include "conkarl.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('America/Lima');
$sqlCliente="SELECT c.`idCliente`, `cliApellidos`, `cliNombres`, `cliDni`, `cliDireccion`, `cliCorreo`, `cliCelular`, `cliFijo` FROM `Cliente` c
inner join producto p on c.idCliente = p.idCliente
where p.idProducto = {$_GET['idProducto']}; ";
$resultadoCliente=$cadena->query($sqlCliente);
$rowCliente=$resultadoCliente->fetch_assoc();

$sqlProds="SELECT `idProducto`, `prodNombre` FROM `producto` p where prodFechaInicial = (select prodFechaInicial from producto where idProducto={$_GET['idProducto']}) and idCliente = (select idCliente from producto where idProducto={$_GET['idProducto']});";
$resultadoProds=$esclavo->query($sqlProds);

$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");


?>
<!DOCTYPE html>
<html lang="es">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <title>Declaracion Jurada - Perucash</title>
   <!-- Bootstrap Core CSS -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
   <link rel="shortcut icon" href="../images/favicon.png">
</head>
<body>
<style>
#recuadroPeque{
   border: 1px solid #000;
   width: 80px;
   height: 30px;
   display: inline-block;
}
#recuadroHuella{
   border: 1px solid #000;
   width: 100px;
   height: 150px;
   display: inline-block;
}
#recuadroFirma{
   border: 1px solid #000;
   width: 200px;
   height: 100px;
   display: inline-block;
}
#divCabecera p, #divContenidoTexto p{text-indent: 40px;}
#divCabecera, #divContenidoTexto{line-height: 2.5rem;}
</style>
   <img src="../images/empresa.png?version=1.1" alt="" style="width: 150px;">
   <div id="divCabecera" class="text-justify">
      <p class="text-center"><strong>Declaración Jurada</strong></p>
      <p>Por el presente documento, Yo: <strong class="text-capitalize"><?= $rowCliente['cliApellidos']." ".$rowCliente['cliNombres']; ?></strong> identificado con D.N.I. N° <?= $rowCliente['cliDni']; ?>, domiciliado en: <span class="text-capitalize"><?= $rowCliente['cliDireccion']; ?></span>, distrito de __________________, provincia de ________________, departamento de: _______________________, con celular/fijo N° <?= $rowCliente['cliCelular']." ". $rowCliente['cliFijo']; ?></p>
      <p>Productos:</p>
      <ul>
      <?php while($rowProds=$resultadoProds->fetch_assoc()){  ?>
         <li>#<?= $rowProds['idProducto']; ?> - <span class="text-capitalize"><?= $rowProds['prodNombre']; ?></span></li>
      <?php } ?>
      </ul>
   </div>
   <div id="divContenidoTexto" class="text-justify">
      <p><strong>DECLARO BAJO JURAMENTO</strong> que <strong>SOY PROPIETARIO EXCLUSIVO</strong> del(os) bien(es) descrito(s), que no forman parte, que NO ha(n) sido otorgado(s) como garantía, aporte de capital social a alguna empresa o sociedad conyugal, NO están afectos a restricciones legales de ninguna índole, total o parciamente que me impidan la libre disposición.</p>
      <p>Motivo por el cual, otorgo en calidad de <strong>GARANTÍA a PERUCASH</strong> y eximo de cualquier responsabilidad jurídica o penal que pudiera ocasionar a la empresa o cualquier persona vinculada a ella, asumiento YO toda la responsabilidad que podría generar.</p>
      <p>El Pignoraticio es por el plazo y monto previoamente pactado, luego de finalizado el plazo, se generarán gastos administrativos, intereses moratorios y penalidades, por o tanto, doy la autorización expresa a PERUCASH para la libre disposición del bien para que pueda ser <strong>VENDIDO, TRANSFERIDO, ENAJENADO; ÍNTEGRAMENTE O POR PARTES, SIN PERJUICIO NI RECLAMO ALGUNO.</strong></p>
      <p>En señal de conformidad firmo al pie, y adjunto la copia de mi DNI, pongo mi huela digital y/o un documento biométrico de la RENIEC para validar mi identidad.</p>
      <p>Huancayo, a los <?= date('d'); ?> días de <?= $meses[date('n')]; ?> de <?= date('Y'); ?>.</p>
   </div>
   <div class="row">
   <div class="col-xs-3 text-center">
      <p>Tasa Interés Semanal:</p>
      <span id="recuadroPeque"></span>
   </div>
   <div class="col-xs-5 text-center">
      <p><small>Firma</small></p>
      <span id="recuadroFirma"></span>
      <p class="text-capitalize">Nommbre: <?= $rowCliente['cliApellidos']." ".$rowCliente['cliNombres']; ?></p>
      <p>D.N.I.: <?= $rowCliente['cliDni']; ?></p>
   </div>
   <div class="col-xs-3 text-center">
      <p><small>Huella digital</small></p>
      <span id="recuadroHuella"></span>
      
   </div>

   </div>
</body>
</html>