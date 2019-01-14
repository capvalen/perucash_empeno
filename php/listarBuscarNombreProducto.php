<ul class="nav nav-tabs">
		<li class="active"><a href="#miniTabProductos" aria-controls="home" role="tab" data-toggle="tab">Productos</a></li>
		<li><a href="#miniTabClientes" aria-controls="home" role="tab" data-toggle="tab">Clientes</a></li>
	</ul>
	<div class="tab-content">
		
		<div role="tabpanel" class="tab-pane fade" id="miniTabClientes">...2</div>
	
<?php 
require("conkarl.php");

//echo "call listarBuscarNombreProducto('".$_POST['texto']."');";
$sql = mysqli_query($conection,"call listarBuscarNombreProducto('".$_POST['texto']."');");

// if (!$sql) { ////codigo para ver donde esta el error
//     printf("Error: %s\n", mysqli_error($conection));
//     exit();
// }
$rows = mysqli_num_rows($sql); ?>
<div role="tabpanel" class="tab-pane active fade in" id="miniTabProductos">
<table class="table table-hover">
<?php
if($rows>0){ ?>
<p style="margin: 10px 0;">Se encontraron <strong><?= $rows;?> coincidencias.</strong></p>
<thead>
	<tr>
		<th></th>
		<th>Descripción</th>
		<th>Dueño</th>
		<th>Capital</th>
	</tr>
</thead>
<tbody>
<?php 
	while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)){ 
		if($row['prodActivo']==1){$color = 'light-blue-text text-darken-1';}else{$color = 'grey-text text-darken-1';} ?>
	<tr>
		<td><?php if($row['prodActivo']==1)  {?> <span class="mitooltip <?=$color;?>" data-toggle="tooltip" title="Activo"><i class="icocat-circulo"></i></span> <?php }else{ ?> <span class="mitooltip <?=$color;?>" data-toggle="tooltip" title="Finalizado"><i class="icocat-circulo"></i></span> <?php } ?> </td>
		<td class="mayuscula"><a href="productos.php?idProducto=<?php echo $row['idproducto']; ?>" class="<?=$color;?>"><?php echo $row['prodnombre']; ?></a></td>
		<td class="eleNom mayuscula"><a href="cliente.php?idCliente=<?php echo $row['idCliente']; ?>" class="<?=$color;?>"><?php echo $row['cliapellidos'].' '.$row['clinombres']; ?></a></td>
		<td class="<?=$color;?>">S/ <?php echo number_format($row['prodMontoEntregado'],2); ?></td>
	</tr>
<?php } //fin de while ?>
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

$sqlCli=mysqli_query($conection, "call listarBuscarNombreCliente('{$_POST['texto']}')");
$rowsCli = mysqli_num_rows($sqlCli);
if($rowsCli>0){
	?><p><strong>Clientes encontrados:</strong></p><?
	while($resCli = mysqli_fetch_array($sqlCli, MYSQLI_ASSOC)){
		?>
	<div class="row">
		<div class="col-xs-5 mayuscula eleNom"><a href="cliente.php?idCliente=<?php echo $resCli['idCliente']; ?>"><?php echo $resCli['cliApellidos'].' '.$resCli['cliNombres']; ?></a></div>
	</div>
<?php
	}
}else{?>
	<br><p>No se encontraron clientes con ese dato</p>
<? }



mysqli_close($conection); //desconectamos la base de datos

?>
</div> <!-- Fin de tab-content -->