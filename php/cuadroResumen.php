<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Reporte Resumen mensual</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
	
</head>
<body>
	<table class="table table-hover">
	<thead>
	<tr>
		<th class="hidden">Tipo</th>
		<th class="hidden">Descrp</th>
		<th>Fecha</th>
		<th>Operación</th>
		<th>No Pr.</th>
		<th>Código</th>
		<th>Descripción</th>
		<th>Ingreso</th>
		<th>G.A.</th>
		<th>Cochera</th>
		<th>GA Banc</th>
		<th>Banco</th>
		<th>Coch Banc</th>
		<th>POS</th>
		<th>N° dias</th>
		<th>Capital</th>
		<th>Cajero</th>
	</tr>
	</thead>
	<tbody data-tipo=0>
	
	
<?php 
include "conkarl.php";

$sql="SELECT date_format(`cajaFecha`, '%d/%m/%Y') as cajaFecha, 
case c.idTipoProceso when 34 then 'COCHER' when 44 then 'PI' when 45 then 'AMORT' when 32 then 'FP' when 33 then 'ADIN' when 31 then 'INYEC' when 90 then 'INYEC' when 89 then 'INYEC' when 80 then 'INYEC' when 86 then 'ALMUERZO' else '' end as 'tipoMin',
p.prodMontoEntregado,
tp.tipoDescripcion, c.idProducto, p.prodNombre, c.cajaValor, cajaMoneda, c.idTipoProceso, retornarCajeroEnPasado(c.idCaja) as cajero
FROM `caja` c
inner join tipoProceso tp on c.idTipoProceso= tp.idTipoProceso
left join producto p on c.idProducto = p.idProducto
where cajaFecha between '2019-07-31 00:00:00' and now() and cajaActivo =1 AND
c.idTipoProceso in (83, 84, 44, 45, 33, 32, 31, 80, 86, 89, 90, 34)
order by cajaFecha, idProducto, c.idTipoProceso;";
$resultado=$cadena->query($sql);
while($row=$resultado->fetch_assoc()){ 
	?>
	<tr data-tipo="<?= $row['idTipoProceso'];?>" data-moneda="<?= $row['cajaMoneda'];?>">
		<td class="hidden"><?=$row['idTipoProceso']; ?></td>
		<td class="hidden"><?=$row['tipoDescripcion'] ?></td>
		<td><?= $row['cajaFecha'];?></td>
		<td><?= $row['tipoMin'] ;?></td>
		<td></td>
		<td class="tdidProduc"><?= $row['idProducto'];?></td>
		<td><?= $row['prodNombre'];?></td>
		<td class="tdValor"><?php if( $row['cajaMoneda']=="1"){echo $row['cajaValor'];}else{'0';}?></td>
		<td class="tdGA"></td>
		<td></td>
		<td class="tdGaBa"></td>
		<td class="tdValorBa"><?php if($row['cajaMoneda']!="1" ){echo $row['cajaValor'];}?></td>
		<td></td>
		<td></td>
		<td></td>
		<td><?= $row['prodMontoEntregado'];?></td>
		<td><?= $row['cajero'];?></td>
	</tr>
	<?php
}
/* SELECT * FROM `caja` where idProducto=3589 and idCaja<10697
order by idCaja desc
limit 1 */
?>
</tbody>
</table>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
$(document).ready(function () {
	$.each( $("tbody tr") ,function (i, elem) {
		
		if( $(elem).find('.tdidProduc').text() == $(elem).prev().find('.tdidProduc').text() && $(elem).attr('data-tipo') == "83" && $(elem).attr('data-moneda')==1 ){
			var sumar = parseFloat($(elem).find('.tdValor').text());
			/* var anterior = parseFloat($(elem).prev().find('.tdValor').text()); */
			$(elem).prev().find('.tdGA').text( sumar ).css('color', "red" );
			$(elem).remove();
		}
		if( $(elem).find('.tdidProduc').text() == $(elem).prev().find('.tdidProduc').text() && $(elem).attr('data-tipo') == "83" && ($(elem).attr('data-moneda')==2 || $(elem).attr('data-moneda')==3 || $(elem).attr('data-moneda')==4) ){
			var sumar = parseFloat($(elem).find('.tdValorBa').text());
			/* var anterior = parseFloat($(elem).prev().find('.tdValor').text()); */
			$(elem).prev().find('.tdGaBa').text( sumar ).css('color', "red" );
			$(elem).remove();
		}
	});

});
</script>
</body>
</html>