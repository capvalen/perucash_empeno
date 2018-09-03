<?php 
$agencia = 'Las Retamas';
$compra= 'Empeño';
$dueno= 'Pillaca Lobaton Gianmarco Gabriel';
// if($row['esCompra']=='0'){ $compra = 'Empeño';}else{ $compra = 'Compra';}

?>
<!DOCTYPE html>
<html lang='es'>
<head>
   <meta charset="utf-8" />
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <title>Hoja Informativa</title>
   <meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
   
</head>
<body>
<style>
.mayuscula{text-transform: capitalize;}
.elemPrintGrande{font-size: 18px;}
.elemPrintMediano{font-size: 12px;}
@media print{
   .elemPrintMediano{font-size: 12px!important;}
   .elemPrintGrande{font-size: 18px!important;}
}
</style>
<div class="container">
   <div class="row">
      <div class="col-xs-4 col-sm-3" id="logoEmpresa"><img src="images/empresa.png?version=1.1" class="img-responsive" alt=""></div>
      <div class="col-xs-8 col-sm-9" ><h3 class=''>HOJA INFORMATIVA</h3>
      <h3 class=''>Agencia <?= $agencia; ?></h3></div>
   </div>
   <div class="row">
      <div class="col-xs-8"> <span>Dueño: </span><span class="elemPrintGrande"><?= $dueno; ?></span> </div>
      <div class="col-xs-4"><span>Adquirido por: <span class="elemPrintGrande"><?= $compra; ?></span></span></div>
   </div>
   <div class="row">
      <table class="table">
         <thead>
            <tr><th>Código</th><th>Cantidad</th><th>Tipo</th><th>Producto</th><th>Monto</th></tr>
         </thead>
         <tbody>
            <tr><td>#725</td><td>1 und.</td></tr>
         </tbody>
      </table>
   </div>
</div>
</body>
</html>