<?php 

error_reporting(E_ALL);
ini_set('display_errors', 'on');
include "conkarl.php";

$fecha1= $_POST['fecha1'];
$fecha2= $_POST['fecha2'];

/*idcaja, ca.idProducto, tp.tipoDescripcion, ca.idTipoProceso, cajavalor */
   $sqlInteres="SELECT IFNULL(round(sum(cajavalor),2), 0) as suma  FROM `caja` ca
   inner join tipoProceso tp on tp.idTipoProceso = ca.idTipoProceso
   where date_format(cajaFecha, '%Y-%m-%d') between '{$fecha1}' and '{$fecha2}' and ca.idTipoProceso in (44, 33) 
   and not idProducto in (select idProducto from caja where idTipoProceso in (32) and  date_format(cajaFecha, '%Y-%m-%d') between '{$fecha1}' and '{$fecha2}' )"; //order by tipoDescripcion
$resultadoInteres=$cadena->query($sqlInteres);
$rowSumInt=$resultadoInteres->fetch_assoc();
$sumaIntereses= $rowSumInt['suma'];


$sqlFines="SELECT IFNULL(round(sum(cajavalor),2), 0) as suma FROM `caja` ca
inner join tipoProceso tp on tp.idTipoProceso = ca.idTipoProceso
where date_format(cajaFecha, '%Y-%m-%d') between '{$fecha1}' and '{$fecha2}' and ca.idTipoProceso in (44, 32) 
and ca.idProducto in (select idProducto from caja where idTipoProceso in (32) and date_format(cajaFecha, '%Y-%m-%d') between '{$fecha1}' and '{$fecha2}' )"; //order by tipoDescripcion
$resultadoFines=$cadena->query($sqlFines);
$rowFines=$resultadoFines->fetch_assoc();
$sumaFines= $rowFines['suma'];


$sqlCapital="SELECT IFNULL(round(sum( p.prodMontoEntregado ),2), 0) as suma  from caja c
inner join producto p on p.idProducto = c.idProducto
where idTipoProceso = 32 and date_format(cajaFecha, '%Y-%m-%d') between '{$fecha1}' and '{$fecha2}';";
$resultadoCapital=$cadena->query($sqlCapital);
$rowCapital=$resultadoCapital->fetch_assoc();
$sumaCapital= $rowCapital['suma'];


$sqlMora="SELECT IFNULL(round(sum(cajavalor),2), 0) as suma FROM `caja` ca
inner join tipoProceso tp on tp.idTipoProceso = ca.idTipoProceso
where date_format(cajaFecha, '%Y-%m-%d') between '{$fecha1}' and '{$fecha2}' and ca.idTipoProceso in (36, 83) "; 
$resultadoMora=$cadena->query($sqlMora);
$rowMora=$resultadoMora->fetch_assoc();
$sumaMora= $rowMora['suma'];


$sqlVendidos="SELECT IFNULL(round(sum(cajavalor),2), 0) as suma, IFNULL(round(sum(p.prodMontoEntregado ),2), 0) as sumaCompra FROM `caja` ca
inner join tipoProceso tp on tp.idTipoProceso = ca.idTipoProceso
inner join producto p on p.idProducto = ca.idProducto
where date_format(cajaFecha, '%Y-%m-%d') between '{$fecha1}' and '{$fecha2}' and ca.idTipoProceso in (21) "; 
$resultadoVendidos=$cadena->query($sqlVendidos);
$rowVendidos=$resultadoVendidos->fetch_assoc();
$sumaVendidos= $rowVendidos['suma'];
$sumaVendidosCompra= $rowVendidos['sumaCompra'];

?>
<h3>Reporte de liquidación</h3>
<table id="tablaGanancias" class="table table-hover">
<thead>
   <tr>
      <th>Estructura</th>
      <th>Cobro</th>
      <th>Capital</th>
      <th>Ganancia</th>
   </tr>
</thead>
<tbody>

<tr>
   <td>Préstamos Finalizados</td> <td><?= 'S/ '. $sumaFines;?></td> <td><?= 'S/ '. $sumaCapital; ?></td> <td><?= 'S/ '. number_format(round($sumaFines - $sumaCapital,1),2);  ?></td>
</tr>
<tr>
   <td>Intereses Cobrados</td> <td>-</td> <td>-</td> <td><?= 'S/ '. $sumaIntereses; ?></td>
</tr>
<tr>
   <td>Gastos Administrativos</td> <td></td> <td></td> <td><?= 'S/ '.$sumaMora;?></td>
</tr>
<tr>
   <td>Vendidos</td> <td><?= 'S/'.$sumaVendidos;?></td> <td><?= 'S/'.$sumaVendidosCompra;?></td> <td><?= 'S/ '. number_format(round($sumaVendidos - $sumaVendidosCompra,1),2);  ?></td>
</tr>
<tr>
   <td>Tarjetas</td> <td></td> <td></td> <td></td>
</tr>


</tbody>
</table>
<table id="tablaGastos" class="table table-hover">
<thead>
   <tr>
      <th>Estructura</th>
      <th>Inversión</th>
      <th>Gasto</th>
   </tr>
</thead>
<tbody>
<tr>
   <td>Préstamos</td>
   <td></td>
   <td></td>
</tr>
<tr>
   <td>Compras</td>
   <td></td>
   <td></td>
</tr>
<tr>
   <td>Servicios Básicos</td>
   <td></td>
   <td></td>
</tr>
<tr>
   <td>Caja Chica</td>
   <td></td>
   <td></td>
</tr>
<tr>
   <td>Personal</td>
   <td></td>
   <td></td>
</tr>

</tbody>
</table>
<h3>Ganancia Neta: S/ <span>0.00</span></h3>