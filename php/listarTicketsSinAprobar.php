<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"call listarTicketsSinAprobar('".date("Y-m-d")."')");
$filas=mysqli_num_rows($sql);

if($_COOKIE['ckPower']==1 || $_COOKIE['ckPower']==9 ){
	$botones = `<button class='btn btn-warning btn-outline btnSinBorde btnEditTicket' data-toggle='tooltip' title='Editar ticket'><i class='icofont icofont-marker'></i></button> <button class='btn btn-azul btn-outline btnSinBorde btnApproveTicket' data-toggle='tooltip' title='Aprobar ticket'><i class='icofont icofont-like'></i></button> <button class='btn btn-danger btn-outline btnSinBorde btnDenyTicket' data-toggle='tooltip' title='Denegar ticket'><i class='icofont icofont-bug'></i></button>`;
}else{
	$botones='';
}

if($filas==0){
	echo "<tr '>
			<td>No hay registros para ".date("d/m/Y")." </td>
		</tr>";
}
else{
while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

	echo "<tr data-id='{$row["idTicket"]}'>
			<th class='tkNum'>#{$row["idTicket"]}</th>
			<td>{$row["usuNombres"]}</td>
			<td class='mayuscula tdDescip'>{$row["tipoDescripcion"]}</td>
			<td>S/. <span class='tdValor'>".number_format($row['cajaValor'], 2)."</span></td>
			<td class='mayuscula tdNombre'><a href='productos.php?idProducto={$row['idProducto']}'>{$row['prodNombre']}</a></td>
			<td class='mayuscula tdObser'>{$row['cajaObservacion']}</td>
			<td>{$botones}</td>
		</tr>";

}}
//mysqli_close($conection); //desconectamos la base de datos

?>
