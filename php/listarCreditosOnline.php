<? 

header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';


$filas=array();
//echo "call reportePrestamosMes('{$_POST["fecha"]}');";
$log = mysqli_query($conection,"SELECT pre.idPrestamo, lower(`cliApellidos`) as `cliApellidos`, lower(`cliNombres`) as `cliNombres`, mo.modDescripcion, round(preCapital,2) as preCapital, prePorcentaje, date_format(preFechaInicio, '%d/%m/%Y') as preFechaInicio
FROM `prestamo` pre
inner join Cliente c on c.idCliente = pre.idCliente
inner join modoPrestamo mo on pre.idModo = mo.idModoPrestamo
where preIdEstado = 78
order by preFechaInicio asc");// ".$_POST['idSucursal']."
$i=0;
while($row = mysqli_fetch_array($log, MYSQLI_ASSOC))
{?>
	<tr> <td><?= $i+1; ?></td> <td><?= $row['modDescripcion'];?></td> 
      <td class="mayuscula"><?= $row['cliApellidos'].', '.$row['cliNombres']; ?></td> <td><?= $row['preFechaInicio']; ?></td> <td><?= $row['preCapital']; ?></td>
   </tr>
<? }

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexion */
mysqli_close($conection);
echo json_encode($filas);
?>