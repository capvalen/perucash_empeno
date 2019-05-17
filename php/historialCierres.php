<select name="" id="sltHistorialCierres" class="form-control" >
<option class="optMovesBox" value="0">Historial de cuadres</option>
<?php 
include 'conkarl.php';
/* if ( ! isset ($_GET['cuadre'])){
  
}else{
  $sql= "select cu.*, u.usuNombres from cuadre cu 
inner join usuario u on u.idUsuario=cu.idUsuario where idCuadre = {$_GET['cuadre']}; ";
} */

$sql= "select cu.*, u.usuNombres from cuadre cu 
inner join usuario u on u.idUsuario=cu.idUsuario where date_format(fechaInicio,'%Y-%m-%d') =date_format('{$_GET['fecha']}','%Y-%m-%d') ";

echo "cuadre ".$_GET['cuadre'];
$llamadoSQL = $conection->query($sql);
while($row = $llamadoSQL->fetch_assoc()): 
  if( $row['fechaFin']=='0000-00-00 00:00:00' ){ $fechaFinal = " - ahora"; }
  else{ $fechaFinal = ' - '.date( 'g:i a', strtotime($row['fechaFin'])); }
  ?>
  
  <option class="optMovesBox" value="<?= $row['idCuadre'] ?>" ><?php if($_GET['cuadre']==$row['idCuadre']){echo "&#xeb27;";}?><?= $row['usuNombres'].' ('.date( 'g:i a', strtotime($row['fechaInicio'])).$fechaFinal.")"; ?></option>
<?php 
endwhile;
?>

</select> 