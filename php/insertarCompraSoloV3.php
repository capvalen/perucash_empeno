<?php 

include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');

$productos= $_POST['jsonProductos'];
//echo count($productos);
for ($i=0; $i < count($productos) ; $i++) { 
	$sql= "call insertarCompraSoloV3 ('".$productos[$i]['nombre']."', ".$productos[$i]['montoDado'].", '".$productos[$i]['fechaIngreso']."', '".$productos[$i]['observaciones']."' , ".$_COOKIE['ckidUsuario'].", '".$productos[$i]['fechaRegistro']."', ".$productos[$i]['tipoProducto'].")";
	//echo $sql;
	if ($llamadoSQL = $conection->query($sql)) { //Ejecución mas compleja con retorno de dato de sql del procedure.
		// obtener el array de objetos 
		while ($resultado = $llamadoSQL->fetch_row()) {
			$idProd=$resultado[0];
			$dinero=$productos[$i]['montoDado'];
			if ($_POST['observaciones']==''){
				$obs= "";
			}else{
				$obs= '<p>'.$_COOKIE['ckAtiende'].' dice: '.$_POST['observaciones'].'</p>';

			}

			$tipoProc=38;
			$sqlTicket= "call crearTicket ({$idProd}, {$tipoProc}, {$dinero} , '{$obs}', ".$_COOKIE['ckidUsuario'].")";
			//echo $sqlTicket;
			$consultaDepos = $cadena->prepare($sqlTicket);
			$consultaDepos ->execute();
			$resultadoDepos = $consultaDepos->get_result();
			//$numLineaDeposs=$resultadoDepos->num_rows;
			$rowDepos = $resultadoDepos->fetch_array(MYSQLI_ASSOC);
			$ticket = $rowDepos['idTicket'];
			$consultaDepos->fetch();
			$consultaDepos->close();





			echo $resultado[0].'&ticket='.$ticket; //Retorna los resultados via post del procedure



		}
		// liberar el conjunto de resultados
		$llamadoSQL->close();
	}else{echo mysql_error( $conection);}
}

 ?>