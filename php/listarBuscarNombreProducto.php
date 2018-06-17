<?php 
require("conkarl.php");

//echo "call listarBuscarNombreProducto('".$_POST['texto']."');";
$sql = mysqli_query($conection,"call listarBuscarNombreProducto('".$_POST['texto']."');");

// if (!$sql) { ////codigo para ver donde esta el error
//     printf("Error: %s\n", mysqli_error($conection));
//     exit();
// }
$rows = mysqli_num_rows($sql);
if($rows>0){
	?><p><strong>Productos:</strong></p><?php 
	while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC)){  ?>
	<div class="row">
		<div class="col-xs-5 mayuscula"><?php if($row['prodActivo']==1)  {?> <span class="prodSiActivo glyphicon glyphicon-map-marker"></span> <?php }else{ ?> <span class="prodNoActivo glyphicon glyphicon-record"></span> <?php } ?> 
		 <a href="productos.php?idProducto=<?php echo $row['idproducto']; ?>"><?php echo $row['prodnombre']; ?></a></div>
		<div class="col-xs-5 mayuscula eleNom"><a href="cliente.php?idCliente=<?php echo $row['idCliente']; ?>"><?php echo $row['cliapellidos'].' '.$row['clinombres']; ?></a></div>
		<div class="col-xs-2">S/. <?php echo number_format($row['prodMontoEntregado'],2); ?></div>
	</div>
<?php }
}else{
	?><p>No se encontraron productos con ese dato</p> <?php 
}
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
