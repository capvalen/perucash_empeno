<?php 
require("conkarl.php");


$sql = mysqli_query($conection,"call reporteCajaDia('".$_GET['fecha']."');");
$totalRow= mysqli_num_rows($sql);
$sumaIngr=0;

$i=0;

if($totalRow==0){
	echo "<tr> <th scope='row'></th> <td >No se encontraron resultados en ésta fecha.</td> <td class='mayuscula'></td> <td></td> <td>S/. <span id='strSumaClientes'>0.00</span></td></tr>";
}else{

	while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
	{
		$i++;
		$sumaIngr+=floatval($row['cajaValor']);
		echo "<tr> <th scope='row'>{$row['idCaja']}</th> <td class='mayuscula'>{$row['idProducto']}. {$row['prodNombre']}</td> <td class='mayuscula'>{$row['cliNombres']}</td> <td>{$row['tipoDescripcion']}<i class='icofont icofont-long-arrow-right'></i> <em class='mayuscula'>{$row['usuNombres']}</em></td> <td>S/. {$row['cajaValor']}</td><tr>";
		if($totalRow==$i){
			echo '<tr> <th scope="row"  style="border-top: transparent;"></th> <td style="border-top: transparent;"></td> <td style="border-top: transparent;"></td> <td class="text-center" style="border-top: 1px solid #989898; color: #636363"><strong >Total</strong></td> <td style="border-top: 1px solid #989898; color: #636363"><strong>S/. <span id="strSumaClientes">'.number_format(round($sumaIngr,1,PHP_ROUND_HALF_UP),2, ',', '').'</span></strong></td><tr>';
		}
	}
}
mysqli_close($conection); //desconectamos la base de datos

?>