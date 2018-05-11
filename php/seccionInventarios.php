<?php 
require('conkarl.php');
header('Content-Type: text/html; charset=utf8');


$sqlClienteExiste="call listarInventarioPorId( {$_GET['idProd']} );";
$llamadoCliente = $conection->query($sqlClienteExiste);
$numRow = $llamadoCliente->num_rows;
if($numRow>0){
while($rowInv = $llamadoCliente->fetch_assoc()){
	echo "<li>Inventariado el <span class='spanFechaFormat'>{$rowInv['invFechaInventario']}</span>: <strong style='color: #ab47bc;'>«{$rowInv['caso']}»</strong> <span class='mayuscula'>$comentario</span>. <em><strong>{$rowInv['usuNombres']}</strong></em></li>";

}
}else{
	echo '<li>No se encontraron inventarios todavía en almacén con éste producto.</li>';
}




$llamadoCliente->close();

 ?>