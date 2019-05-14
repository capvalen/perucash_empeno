<?php 
include 'conkarl.php';
include "variablesGlobales.php";


$sql="SELECT `idCocheraReg`, upper(`movPlaca`) as movPlaca, `idVehiculo`, date_format(`movFecha`,'%d/%m/%Y') as movFecha, `idUser`, `idProceso`, `movActivo`, tp.tipoDescripcion,
tv.vehDescripcion, u.usuNombres, (datediff(date_format(now(),'%Y-%m-%d'), date_format(movFecha,'%Y-%m-%d') )+1)* tv.vehPrecio as vehPrecio FROM `cocheraregistros` co
inner join tipovehiculo tv on tv.idTipoVehiculo = co.idVehiculo
inner join tipoProceso tp on tp.idTipoProceso = co.idProceso
inner join usuario u on u.idUsuario = co.idUser
where co.idProceso = 27 and co.movActivo=1
order by movFecha desc;";
//echo $sql;
$resultado=$cadena->query($sql);
$i=1;
while($row=$resultado->fetch_assoc()){ ?>
<tr>
   <td><?=$i; ?></td>
   <td><?= $row['vehDescripcion']; ?></td>
   <td><?= $row['movPlaca']; ?></td>
   <td><?= $row['movFecha']; ?></td>
   <td><?= $row['tipoDescripcion']; ?></td>
   <td>S/ <?= number_format($row['vehPrecio'],2); ?></td>
   <td><?= $row['usuNombres']; ?></td>
   <?php if( in_array($_COOKIE['ckPower'], $soloCaja)): ?><td> <button class="btn btn-primary btn-outline btnPagarCochera" data-placa="<?=$row['movPlaca'];?>"><i class="icofont icofont-money"></i></button></td>
   <?php endif; ?>
</tr>

 <?php  $i++;
}
?>