<?php 
require("conkarl.php");
//echo "call reporteEgresoDiaxCuadre('".$_GET['cuadre']."');";
$sql = mysqli_query($conection,"call reporteEgresoDiaxCuadre('".$_GET['cuadre']."');");
$totalRow= mysqli_num_rows($sql);
$sumaIngr=0;
$boton='';
 
$i=0;
$efectivo=0; $banco=0; $tarjeta=0;

if($totalRow==0){
	echo "<tr> <th scope='row'></th> <td >No se encontraron resultados en ésta fecha.</td> <td class='mayuscula'></td> <td></td> <td>S/ <span id='strSumaSalida' data-efectivo ='{$efectivo}' data-banco ='$banco' data-tarjeta ='{$tarjeta}'>0.00</span></td></tr>";
}else{
	while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
	{
		$i++;
		$sumaIngr+=floatval($row['pagoMonto']);
		switch($row['cajaMoneda']){
			case '1': 
				$efectivo = $efectivo + $row['pagoMonto'];
			break;
			case '2': 
				$banco = $banco + $row['pagoMonto'];
			break;
			case '3':
			case '4':
				$tarjeta = $tarjeta + $row['pagoMonto'];
			break;
			default:
			break;
		}

		if($_COOKIE['ckPower']==1 || $_COOKIE['ckPower']==8): $boton = "<button class='btn btn-sm btn-negro btn-outline btnEditarCajaMaestra'><i class='icofont icofont-edit'></i></button>"; else: $boton=''; endif;?>

		<tr data-id="<?= $row['idCaja']; ?>" data-activo="<?= $row['cajaActivo']; ?>">
			<th scope='row'> <?php if($row['idProducto']>0){ ?> <a href='productos.php?idProducto=<?= $row['idProducto'];?>#tabMovEstados'>#<?= $row['idProducto'];?></a> <?php } ?> </th>
			<td class='mayuscula'><?= $row['tipopDescripcion'].' '. $row['prodNombre'];?></td> <td class='mayuscula tpIdDescripcion'><?= $row['tipoDescripcion'];?></td> <td><i class="icofont icofont-bubble-right"></i> <em class="mayuscula"><?= $row['usuNick'];?></em></td> <td>S/ <span class='spanCantv3'><?= $row['pagoMonto'];?></span></td>  <td class='mayuscula tdMoneda' data-id="<?= $row['cajaMoneda'];?>"><?= $row['moneDescripcion'];?></td>
			<td class='mayuscula tdObservacion'><?= $row['cajaObservacion'];?><?php if($row['idTipoProceso']==74){ echo " ~".$row['cajPorcentaje']."%";} ?></td>
			<td><span class="sr-only fechaPagov3"><?= $row['cajaFecha'];  ?></span> <?= $boton;?></td> </tr>
		<?php 
		if($totalRow==$i){
			echo '<tr> <th scope="row"  style="border-top: transparent;"></th>  <td style="border-top: transparent;"></td> <td style="border-top: transparent;"></td> <td class="text-center" style="border-top: 1px solid #989898; color: #636363"><strong >Total</strong></td> <td style="border-top: 1px solid #989898; color: #636363"><strong >S/ <span id="strSumaSalida" data-efectivo ="'.$efectivo.'" data-banco ="'.$banco.'" data-tarjeta ="'.$tarjeta.'">'.number_format(round($sumaIngr,1,PHP_ROUND_HALF_UP),2, ',', '').'</span></strong></td><tr>';
		}
	}
}
mysqli_close($conection); //desconectamos la base de datos

?>
