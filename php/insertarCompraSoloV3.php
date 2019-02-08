<?php 

include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');

$sql = '';
$j=0;
$productos= $_POST['jsonProductos'];
//echo count($productos);
for ($i=0; $i < count($productos) ; $i++) { 
	$sql= $sql. "call insertarCompraSoloV3 ('".$productos[$i]['nombre']."', ".$productos[$i]['montoDado'].", '".$productos[$i]['fechaIngreso']."', '".$productos[$i]['observaciones']."' , ".$_COOKIE['ckidUsuario'].", '".$productos[$i]['fechaRegistro']."', ".$productos[$i]['tipoProducto'].");";
	
}
//echo $sql;

if (mysqli_multi_query($conection, $sql)) {
	do { 
		 /* store first result set */
		 if ($result = mysqli_store_result($conection)) {
			  while ($row = mysqli_fetch_row($result)) {
					//printf("%s\n", $row[0]);
					$idProd=$row[0];
					$dinero=$productos[$j]['montoDado'];
					if ($_POST['observaciones']==''){
						$obs= "";
					}else{
						$obs= '<p>'.$_COOKIE['ckAtiende'].' dice: '.$_POST['observaciones'].'</p>';

					}

					$tipoProc=38;
					// $sqlTicket= "call crearTicket ({$idProd}, {$tipoProc}, {$dinero} , '{$obs}', ".$_COOKIE['ckidUsuario'].");";
					// //echo $sqlTicket;
					// $consultaDepos = $cadena->prepare($sqlTicket);
					// $consultaDepos ->execute();
					// $resultadoDepos = $consultaDepos->get_result();
					// //$numLineaDeposs=$resultadoDepos->num_rows;
					// $rowDepos = $resultadoDepos->fetch_array(MYSQLI_ASSOC);
					// $ticket = $rowDepos['idTicket'];
					// $consultaDepos->fetch();
					// $consultaDepos->close();


					//echo "<h3><a href='productos.php?idProducto={$row[0]}&ticket={$ticket}' target='_blank'>#{$row[0]} - Ticket: {$ticket}</a></h3>"; //Retorna los resultados via post del procedure
					echo "<h3><a href='productos.php?idProducto={$row[0]}' >#{$row[0]}</a></h3>"; //Retorna los resultados via post del procedure
			  }
			  mysqli_free_result($result);
		 }
		 /* print divider */
		 if (mysqli_more_results($conection)) { $i++;
			//  printf("-----------------\n");
		 }
	} while  (mysqli_more_results($conection) && mysqli_next_result($conection) );
}

 ?>
 