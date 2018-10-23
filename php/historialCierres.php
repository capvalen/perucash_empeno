<select name="" id="sltHistorialCierres" class="form-control" >
<option class="optMovesBox" value="0">Historial de cuadres</option>
<?php 
include 'conkarl.php';
if ( ! isset ($_GET['cuadre'])){
  $sql= "select cu.*, u.usuNombres from cuadre cu 
inner join usuario u on u.idUsuario=cu.idUsuario where date_format(fechaInicio,'%Y-%m-%d') =date_format('{$_GET['fecha']}','%Y-%m-%d') ";
}else{
  $sql= "select cu.*, u.usuNombres from cuadre cu 
inner join usuario u on u.idUsuario=cu.idUsuario where idCuadre = {$_GET['cuadre']}; ";
}
//echo $sql;
$llamadoSQL = $conection->query($sql);
while($row = $llamadoSQL->fetch_assoc()): 
  if( $row['fechaFin']=='0000-00-00 00:00:00' ){ $fechaFinal = " - ahora"; }
  else{ $fechaFinal = ' - '.date( 'g:i a', strtotime($row['fechaFin'])); }
  ?>
  
  <option class="optMovesBox" value="<?= $row['idCuadre'] ?>" ><?= $row['usuNombres'].' ('.date( 'g:i a', strtotime($row['fechaInicio'])).$fechaFinal.")"; ?></option>
<?php 
endwhile;
?>

</select> 