<?php
require("conkarl.php");

$sql = mysqli_query($conection,"call listarBuscarNombreProducto('".$_POST['texto']."');");
$rows = mysqli_num_rows($sql);

$sqlCli=mysqli_query($cadena, "call listarBuscarNombreCliente('{$_POST['texto']}')");
$rowsCli = mysqli_num_rows($sqlCli);

?>
<ul class="nav nav-tabs">
		<li class="active"><a href="#miniTabProductos" aria-controls="home" role="tab" data-toggle="tab">Productos <span class="badge"><?= $rows;?></span></a></li>
		<li><a href="#miniTabClientes" aria-controls="home" role="tab" data-toggle="tab">Clientes <span class="badge"><?= $rowsCli;?></span></a></li>
	</ul>
	<div class="tab-content">
		
<?php 


//echo "call listarBuscarNombreProducto('".$_POST['texto']."');";


// if (!$sql) { ////codigo para ver donde esta el error
//     printf("Error: %s\n", mysqli_error($conection));
//     exit();
// }
 ?>
<div role="tabpanel" class="tab-pane active fade in" id="miniTabProductos">
<table class="table table-hover">
<?php
if($rows>0){ ?>
<thead>
	<tr>
		<th></th>
		<th>N°</th>
		<th>Descripción</th>
		<th>Dueño</th>
		<th>Capital</th>
	</tr>
</thead>
<tbody>
<?php $j=1;
	while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)){ 
		if($row['prodActivo']==1){$color = 'light-blue-text text-darken-1';}else{$color = 'grey-text text-darken-1';} ?>
	<tr>
		<td><?php if($row['prodActivo']==1)  {?> <span class="mitooltip <?=$color;?>" data-toggle="tooltip" title="Activo"><i class="icocat-circulo"></i></span> <?php }else{ ?> <span class="mitooltip <?=$color;?>" data-toggle="tooltip" title="Finalizado"><i class="icocat-circulo"></i></span> <?php } ?> </td>
		<td><?= $j;?></td>
		<td class="mayuscula"><a href="productos.php?idProducto=<?php echo $row['idproducto']; ?>" class="<?=$color;?>"><?php echo $row['tipopDescripcion'].' '.$row['prodnombre']; ?></a></td>
		<td class="eleNom mayuscula"><a href="cliente.php?idCliente=<?php echo $row['idCliente']; ?>" class="<?=$color;?>"><?php echo $row['cliapellidos'].' '.$row['clinombres']; ?></a></td>
		<td class="<?=$color;?>">S/ <?php echo number_format($row['prodMontoEntregado'],2); ?></td>
	</tr>
<?php $j++; } //fin de while ?>
</tbody>
<?php
}else{
	?><p  style="margin: 10px 0;">No se encontraron productos con ese dato</p> <?php 
}
?>
</table>
</div> <!-- fin de div miniTabProductos -->
<?php
mysqli_free_result($sql); //limpia los resultados
mysqli_next_result($conection); //prepara para el siguiente combo de consultas

 ?>

<div role="tabpanel" class="tab-pane fade in" id="miniTabClientes">
<table class="table table-hover">
<?php if($rowsCli>0){
	?>
<thead>
	<tr>
		<th>N°</th>
		<th>Cliente</th>
	</tr>
</thead>
<tbody>
	<?php $k=1;
	while($resCli = mysqli_fetch_array($sqlCli, MYSQLI_ASSOC)){
		?>
		<tr>
			<td><?= $k; ?></td>
			<td class='mayuscula eleNom' ><a href="cliente.php?idCliente=<?php echo $resCli['idCliente']; ?>" class="light-blue-text text-darken-1"><?php echo $resCli['cliApellidos'].' '.$resCli['cliNombres']; ?></a></td>
		</tr>
<?php $k++;
	} //fin de while 2 ?>
</tbody>
<?php
}else{?>
	<br><p>No se encontraron clientes con ese dato</p>
<? }
?>
</table>
</div> <!-- fin de div miniTabClientes -->
<?php



mysqli_close($conection); //desconectamos la base de datos

?>
</div> <!-- Fin de tab-content -->