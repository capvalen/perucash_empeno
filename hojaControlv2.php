<?php 
$agencia = 'Las Retamas';
$compra= 'Artículo en garantía';
$dueno= 'Pillaca Lobaton Gianmarco Gabriel';
$code= '948';
$clase = 'Cámara';
$und = 1;
$producto = 'Canon Powershot G9';
$precio = '790';
$fecha = 'Martes, 3 de Septiembre de 2018';
$usuario= 'Yuri Paola';
$dni= '44758514';
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
.fuerte{font-weight: 700;}
.masJunto{margin-top: 5px;
    margin-bottom: 5px;}
img{margin: 0 auto;}
hr{
   margin-top: 10px;
   margin-bottom: 10px;
}
@media print{
   .elemPrintMediano{font-size: 12px!important;}
   .elemPrintGrande{font-size: 18px!important;}
}
</style>
<div class="container">
   <div class="row">
   <div class="col-xs-6 text-center">
      <div class="col-xs-12" id="logoEmpresa"><img src="images/empresa.png?version=1.1" class="img-responsive" alt="">
      <h4 class='fuerte'>Hoja Informativa «<?= $agencia; ?>»</h4>
      <hr>
      <h1 class="fuerte masJunto">#<?= $code; ?></h1>
      <h4 class='masJunto'><?= $und; ?><?php if($und==1){echo ' Und.';}else{ echo ' Unds.';} ?> <?= $clase; ?></h4>
      <h3 class='masJunto'><?= $producto; ?></h3>
      <h3 class='masJunto'>S/ <?= number_format($precio,2); ?></h3>
      <hr>
      <h4><?= $dueno; ?></h4>
      <h4><?= $dni; ?></h4>
      <hr>
      <h4><?= $compra; ?></h4>
      <h5><?= $fecha; ?></h5>
      <h5><?= $usuario; ?></h5>
      </div>
   </div>
   
   <div class="col-xs-6 text-center">
      <div class="col-xs-12" id="logoEmpresa"><img src="images/empresa.png?version=1.1" class="img-responsive" alt="">
      <h4 class='fuerte'>Hoja Informativa «<?= $agencia; ?>»</h4>
      <hr>
      <h1 class="fuerte masJunto">#<?= $code; ?></h1>
      <h4 class='masJunto'><?= $und; ?><?php if($und==1){echo ' Und.';}else{ echo ' Unds.';} ?> <?= $clase; ?></h4>
      <h3 class='masJunto'><?= $producto; ?></h3>
      <h3 class='masJunto'>S/ <?= number_format($precio,2); ?></h3>
      <hr>
      <h4><?= $dueno; ?></h4>
      <h4><?= $dni; ?></h4>
      <hr>
      <h4><?= $compra; ?></h4>
      <h5><?= $fecha; ?></h5>
      <h5><?= $usuario; ?></h5>
      </div>
   </div>

   </div>
  
</div>
</body>
</html>