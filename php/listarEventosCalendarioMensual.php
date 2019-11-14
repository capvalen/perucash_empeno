<?php 

include 'conkarl.php';


$filas=array();
$log = mysqli_query($conection,"SELECT `idCalendario`, `calFecha`, `idUsuario`, lower(`calTitulo`) as calTitulo, lower (`calDescripcion`) as calDescripcion, `idTipoProceso`, `calcEstado` FROM `calendario`
where date_format(calFecha, '%Y-%m') = date_format('{$_POST['mensual']}', '%Y-%m') 
order by calFecha asc;");

$i=0;
while($row = mysqli_fetch_array($log, MYSQLI_ASSOC)){
  $filas[$i]= $row;
	$i++;
}

/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexi贸n */
mysqli_close($conection);
echo json_encode($filas);

?>