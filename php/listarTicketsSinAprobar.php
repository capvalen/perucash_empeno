<?
require("conkarl.php");

$sql = mysqli_query($conection,"SELECT t.*, u.usuNombres, tp.tipoDescripcion FROM `tickets` t
inner join usuario u on u.idUsuario = t.idUsuario
inner join tipoProceso tp on tp.idTipoProceso = t.idTipoProceso
where  date_format(t.cajaFecha, '%Y-%m-%d') = curdate() or t.idTipoProceso in (33, 81, 84)
and cajaActivo in (0, 1)
order by idTicket asc;"); //call listarTicketsSinAprobar('".date("Y-m-d")."')

$filas=mysqli_num_rows($sql);

//valores de caja para pagar directo
$directo = array(33, 36, 44, 32, 45, 19, 18);

if($filas==0){
	echo "<tr >
			<td colspan='5'>No hay registros para ".date("d/m/Y")." </td>
		</tr>";
}
else{
while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)){ 
//if($_COOKIE['ckPower']==1 || $_COOKIE['ckPower']==9 ){ ?>
	<tr>
		<td>T-<?= $row['idTicket'];?></td>
		<td class="hidden"><? $fNew = new DateTime($row['cajaFecha']); echo $fNew->format('h:i a');?></td>
		<td><?= $row['tipoDescripcion'];?></td>
		<td class=""><?= $row['cajaObservacion'];?></td>
		<td><?= number_format($row['cajaValor'],2);?></td>
		<td><?= $row['usuNombres'];?></td>
		<td data-ticket="<?= $row['idTicket'];?>"> <button class="btn btn-infocat btn-outline btnAprobarTicketCredito"><i class="icofont icofont-thumbs-up"></i> Aprobar</button> <button class="btn btn-danger btn-outline btnDesaprobarTicketCredito"><i class="icofont icofont-thumbs-down"></i> Denegar</button> </td>
	</tr>
<? }
}

?>
