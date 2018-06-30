<select name="" id="" class="form-control" >
<option value="0">Todos los movimientos</option>
<?php 
include 'conkarl.php';
$sql= "select cu.*, u.usuNombres from cuadre cu 
inner join usuario u on u.idUsuario=cu.idUsuario where date_format(fechaInicio,'%Y-%m-%d') =date_format('{$_GET['fecha']}','%Y-%m-%d') ";
echo $sql;
$llamadoSQL = $conection->query($sql);
while($row = $llamadoSQL->fetch_assoc()){ ?>
  <option value="1" data-cuadre = '<?php echo cu.idCuadre ?>'><?php echo $row['usuNombres'] ?></option>
<?php 
}
?>

</select> 