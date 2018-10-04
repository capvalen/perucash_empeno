<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"call listarTicketsSinAprobar('".date("Y-m-d")."')");
$filas=mysqli_num_rows($sql);

//valores de caja para pagar directo
$directo = array(33, 36, 44, 32, 45, 19, 18);



if($filas==0){
	echo "<tr >
			<td colspan='5'>No hay registros para ".date("d/m/Y")." </td>
		</tr>";
}
else{
while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{
	//if($_COOKIE['ckPower']==1 || $_COOKIE['ckPower']==9 ){
		
			$botones = "<button class='btn btn-warning btn-outline btnSinBorde btnEditTicket' data-toggle='tooltip' title='Editar ticket'><i class='icofont icofont-marker'></i></button> <button class='btn btn-azul btn-outline btnSinBorde btnApproveTicket' data-toggle='tooltip' title='Aprobar ticket'><i class='icofont icofont-like'></i></button> <button class='btn btn-danger btn-outline btnSinBorde btnDenyTicket' data-toggle='tooltip' title='Denegar ticket'><i class='icofont icofont-bug'></i></button>";
		
		if(in_array($row["idTipoProceso"], $directo)){
			$botones="<button class='btn btn-azul btn-outline btnSinBorde btnCobrarTicket' data-toggle='tooltip' title='Cobrar ticket'><i class='icofont icofont-money'></i></button>";
			if(($row["idTipoProceso"] == 19 || $row["idTipoProceso"]==18) && $row["idAprueba"]==0 ){
				$botones = "<button class='btn btn-warning btn-outline btnSinBorde btnEditTicket' data-toggle='tooltip' title='Editar ticket'><i class='icofont icofont-marker'></i></button> <button class='btn btn-azul btn-outline btnSinBorde btnApproveTicket' data-toggle='tooltip' title='Aprobar ticket'><i class='icofont icofont-like'></i></button> <button class='btn btn-danger btn-outline btnSinBorde btnDenyTicket' data-toggle='tooltip' title='Denegar ticket'><i class='icofont icofont-bug'></i></button>";
			}
		}
	// }else{
	// 	$botones='';
	// }
	

	echo "<tr data-id='{$row["idTicket"]}'>
			<th class='tkNum'>T-{$row["idTicket"]}</th>
			<td>{$row["usuNombres"]}</td>
			<td class='mayuscula tdDescip'>{$row["tipoDescripcion"]}</td>
			<td>S/. <span class='tdValor'>".number_format($row['cajaValor'], 2)."</span></td>
			<td class='mayuscula tdNombre'><a href='productos.php?idProducto={$row['idProducto']}'>{$row['prodNombre']}</a></td>
			<td class='mayuscula tdObser'>{$row['cajaObservacion']}</td>
			<td class='botonesTd'>{$botones}</td>
		</tr>";

}}
//mysqli_close($conection); //desconectamos la base de datos

//36 - Penalización (Gasto Admin.)
//44 - Cancelación de interés
//32 - Fin del préstamo
//45 - Amortización
//18 - En remate
//19 - En venta
?>
