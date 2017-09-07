<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Perucash: Listado de todos los productos en la BD</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<style>
	body{font-size: 11px;}
	.mayuscula{text-transform: capitalize;}
}
</style>

<table class="table table-condensed">
    <thead>
      <tr>
        <th>Cod.</th>
        <th class="col-xs-4">Descripcion del producto</th>
        <th>S/. Inic.</th>
        <th  class="col-xs-2">Fecha registro</th>
        <th class="col-xs-4">Dueño</th>
        <th>Usuario</th>
      </tr>
    </thead>
    <tbody>

<?php 
require("conkarl.php");


$filas=array();
$sql = mysqli_query($conection,"call listarTodosProductosEnBDSucursal(".$_GET['idSuc'].");");
$i=0;
// if (!$sql) { ////codigo para ver donde esta el error
//     printf("Error: %s\n", mysqli_error($conection));
//     exit();
// }
while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{
	//$filas[$i]= $row;
	echo '<tr> <td>'.$row['idproducto'].'</td>' .
		'<td class="mayuscula">'.$row['prodnombre'].'</td>'.
		'<td>'.$row['prodMontoEntregado'].'</td>'.
		'<td>'.$row['prodFechaRegistro'].'</td>'.
		'<td class="mayuscula">'.$row['cliApellidos'].', '.$row['cliNombres'].'</td>'.
		'<td class="mayuscula">'.$row['nick'].'</td></tr>';
	$i++;
}
mysqli_close($conection); //desconectamos la base de datos
//echo json_encode($filas);
?>

	</tbody>
  </table>
 </body>
</html>