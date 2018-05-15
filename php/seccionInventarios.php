<?php 
require('conkarl.php');
header('Content-Type: text/html; charset=utf8');


$sqlInventario="call listarInventarioPorId( {$_GET['idProd']} );";
$llamadoInventarios = $conection->query($sqlInventario);
$numRow = $llamadoInventarios->num_rows;
if($numRow>0){
while($rowInventa = $llamadoInventarios->fetch_assoc()){
	echo "<li>Inventariado el <span class='spanFechaFormat'>{$rowInventa['invFechaInventario']}</span>: <strong style='color: #ab47bc;'>«{$rowInventa['caso']}»</strong> <span class='mayuscula'>$comentario</span>. <em><strong>{$rowInventa['usuNombres']}</strong></em></li>";

}
}else{
	echo '<li>No se encontraron inventarios todavía en almacén con éste producto.</li>';
}

$llamadoInventarios->close();

?>