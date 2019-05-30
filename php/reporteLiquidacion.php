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


$sqlVendidos="SELECT IFNULL(round(sum(cajavalor),2), 0) as suma, IFNULL(round(sum(p.prodMontoEntregado),2), 0) as sumaCompra FROM `caja` ca
inner join tipoProceso tp on tp.idTipoProceso = ca.idTipoProceso
inner join producto p on p.idProducto = ca.idProducto
where date_format(cajaFecha, '%Y-%m-%d') between '{$fecha1}' and '{$fecha2}' and ca.idTipoProceso in (20, 21) "; 
$resultadoVendidos=$cadena->query($sqlVendidos);
$rowVendidos=$resultadoVendidos->fetch_assoc();
$sumaVendidos= $rowVendidos['suma'];
$sumaVendidosCompra= $rowVendidos['sumaCompra'];


$sqlTarjetas="SELECT IFNULL(round(sum(cajavalor),1), 0) as suma, IFNULL(round(sum((cajavalor/(100-cajPorcentaje)*100)*(cajPorcentaje-5)/100),1), 0) as suma2 FROM `caja` ca
inner join tipoProceso tp on tp.idTipoProceso = ca.idTipoProceso
where date_format(cajaFecha, '%Y-%m-%d') between '{$fecha1}' and '{$fecha2}' and ca.idTipoProceso in (74) "; 
$resultadoTarjetas=$cadena->query($sqlTarjetas);
$rowTarjetas=$resultadoTarjetas->fetch_assoc();
$sumaTarjetas= $rowTarjetas['suma'];
$sumaTarjetasGana= $rowTarjetas['suma2'];


$sqlPrestamos="SELECT IFNULL(round(sum(cajavalor),2), 0) as suma FROM `caja` ca
inner join tipoProceso tp on tp.idTipoProceso = ca.idTipoProceso
where date_format(cajaFecha, '%Y-%m-%d') between '{$fecha1}' and '{$fecha2}' and ca.idTipoProceso in (28) "; 
$resultadoPrestamos=$cadena->query($sqlPrestamos);
$rowPrestamos=$resultadoPrestamos->fetch_assoc();
$sumaPrestamos= $rowPrestamos['suma'];


$sqlComprass="SELECT IFNULL(round(sum(cajavalor),2), 0) as suma FROM `caja` ca
inner join tipoProceso tp on tp.idTipoProceso = ca.idTipoProceso
where date_format(cajaFecha, '%Y-%m-%d') between '{$fecha1}' and '{$fecha2}' and ca.idTipoProceso in (38) "; 
$resultadoComprass=$cadena->query($sqlComprass);
$rowComprass=$resultadoComprass->fetch_assoc();
$sumaComprass= $rowComprass['suma'];


$sqlServicios="SELECT IFNULL(round(sum(cajavalor),2), 0) as suma FROM `caja` ca
inner join tipoProceso tp on tp.idTipoProceso = ca.idTipoProceso
where date_format(cajaFecha, '%Y-%m-%d') between '{$fecha1}' and '{$fecha2}' and ca.idTipoProceso in (88) "; 
$resultadoServicios=$cadena->query($sqlServicios);
$rowServicios=$resultadoServicios->fetch_assoc();
$sumaServicios= $rowServicios['suma'];


$sqlGastos="SELECT IFNULL(round(sum(cajavalor),2), 0) as suma FROM `caja` ca
inner join tipoProceso tp on tp.idTipoProceso = ca.idTipoProceso
where date_format(cajaFecha, '%Y-%m-%d') between '{$fecha1}' and '{$fecha2}' and ca.idTipoProceso in (42) "; 
$resultadoGastos=$cadena->query($sqlGastos);
$rowGastos=$resultadoGastos->fetch_assoc();
$sumaGastos= $rowGastos['suma'];


$sqlPagos="SELECT IFNULL(round(sum(cajavalor),2), 0) as suma FROM `caja` ca
inner join tipoProceso tp on tp.idTipoProceso = ca.idTipoProceso
where date_format(cajaFecha, '%Y-%m-%d') between '{$fecha1}' and '{$fecha2}' and ca.idTipoProceso in (39, 40) "; 
$resultadoPagos=$cadena->query($sqlPagos);
$rowPagos=$resultadoPagos->fetch_assoc();
$sumaPagos= $rowPagos['suma'];

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
   <td>Intereses Cobrados</td> <td><?= 'S/ '. $sumaIntereses; ?></td> <td>-</td> <td><?= 'S/ '. $sumaIntereses; ?></td>
</tr>
<tr>
   <td>Gastos Administrativos</td> <td><?= 'S/ '.$sumaMora;?></td> <td>-</td> <td><?= 'S/ '.$sumaMora;?></td>
</tr>
<tr>
   <td>Vendidos</td> <td><?= 'S/ '.$sumaVendidos;?></td> <td><?= 'S/ '.$sumaVendidosCompra;?></td> <td><?= 'S/ '. number_format(round($sumaVendidos - $sumaVendidosCompra,1),2);  ?></td>
</tr>
<tr>
   <td>Tarjetas</td> <td><?= 'S/ '.$sumaTarjetas;?></td> <td>-</td> <td><?= 'S/ '. number_format($sumaTarjetasGana,2);?></td>
</tr>
</tbody>
<tfoot>
<tr>
<th></th>
<th>S/ <?= number_format($sumaFines+$sumaIntereses+$sumaMora+$sumaVendidos+$sumaTarjetas,2);?></th>
<th>S/ <?= number_format( $sumaCapital + $sumaVendidosCompra ,2);?></th>
<th>S/ <?php $sumaGanaciasT = ($sumaFines - $sumaCapital) +$sumaIntereses + $sumaMora + ($sumaVendidos - $sumaVendidosCompra) + $sumaTarjetasGana; echo number_format( $sumaGanaciasT ,2);?></th>

</tr>
</tfoot>
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
   <td><?= 'S/ '.$sumaPrestamos; ?></td>
   <td></td>
</tr>
<tr>
   <td>Compras</td>
   <td><?= 'S/ '.$sumaComprass; ?></td>
   
   <td></td>
</tr>
<tr>
   <td>Servicios Básicos</td>
   <td></td>
   <td><?= 'S/ '.$sumaServicios;?></td>
</tr>
<tr>
   <td>Caja Chica</td>
   <td></td>
   <td><?= 'S/ '.$sumaGastos; ?></td>
</tr>
<tr>
   <td>Personal</td>
   <td></td>
   <td><?= 'S/ '.$sumaPagos; ?></td>
</tr>
</tbody>
<tfoot>
<tr>
<th></th>
<th>S/ <?= number_format( $sumaPrestamos + $sumaComprass ,2);?></th>
<th>S/ <?php $sumaGastosT= $sumaServicios + $sumaGastos + $sumaPagos; echo number_format( $sumaGastosT ,2);?></th>
</tr>
</tfoot>
</table>
<h3>Ganancia Neta: S/ <span><?= number_format($sumaGanaciasT - $sumaGastosT,2); ?></span></h3>