<?php
require("conkarl.php");
date_default_timezone_set('America/Lima');

$sql="SELECT `idPrestamo`, `idCliente`, `preCapital`, `idModo`, `prePorcentaje`, `preFechaInicio`, `idUsuario`, `presActivo`, `presObservaciones`, `preIdEstado`, `prePlazo` FROM `prestamo`
where idPrestamo ={$_POST['idCred']} /*Prestamo online*/ and presActivo =1 and preIdEstado=78;";
echo $sql;


$resultado=$cadena->query($sql);

while($row=$resultado->fetch_assoc()){ ?>
   
      <p><?= $row['preCapital']; ?></p>
   
<?php }
?>