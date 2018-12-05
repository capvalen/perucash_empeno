<? 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$filas=array();
//echo "call reportePrestamosMes('{$_POST["fecha"]}');";
$log = mysqli_query($conection,"SELECT pre.idPrestamo, lower(`cliApellidos`) as `cliApellidos`, lower(`cliNombres`) as `cliNombres`, mo.modDescripcion, round(preCapital,2) as preCapital, prePorcentaje, preFechaInicio, c.idCliente
FROM `prestamo` pre
inner join Cliente c on c.idCliente = pre.idCliente
inner join modoPrestamo mo on pre.idModo = mo.idModoPrestamo
where preIdEstado = 78
order by preFechaInicio asc");// ".$_POST['idSucursal']."
$i=0; $sumTot=0;
while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
//var_dump($row);
{
   $sumTot+= $row['preCapital'];
   ?>
	<tr> <td data-sort-value="<?= $row['idPrestamo']; ?>"><a href="creditos.php?credito=<?= $row['idPrestamo']; ?>">#<?= $row['idPrestamo']; ?></a></td> <td class="hidden"></td> <td data-sort-value="<?= $row['modDescripcion'];?>"><?= $row['modDescripcion'];?></td> 
      <td class="mayuscula"><a href="cliente.php?idCliente=<?= $row['idCliente'];?>"><?= $row['cliApellidos'].', '.$row['cliNombres']; ?></a></td> <td data-sort-value="<?php $fecUnix= new DateTime($row['preFechaInicio']); echo $fecUnix->format('Ymd'); ?>" ><?php echo $fecUnix->format('d/m/Y'); ?></td> <td><?= $row['preCapital']; ?></td>
   </tr>
<? }

?>
   <tr> <td></td>
   <td></td>
   <td></td> <td><strong>Total:</strong></td> <td><strong>S/ <?=number_format($sumTot,2); ?></strong></td> </tr>
<?
/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexion */
mysqli_close($conection);
echo json_encode($filas);
?>