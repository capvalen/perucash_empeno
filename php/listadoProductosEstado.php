<?php 
require("conkarl.php");
$filas=array();

$i=0;

switch ($_POST['estado']) {
	case 37:
		$sql = mysqli_query($conection,"SELECT pp.*, p.prodNombre, p.prodMontoEntregado, concat(c.cliApellidos, ' ', c.cliNombres) as cliNombres, c.idCliente 
			FROM `prestamo_producto` pp
			inner join `producto` p on pp.idProducto = p.idProducto
			inner join Cliente c on c.idCliente=p.idCliente
			where prActivo = 1 and presidTipoProceso in (22, 28, 37);");

		while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
		{
			$filas[$i]= $row;
			$i++;
		}
		mysqli_close($conection); //desconectamos la base de datos
		echo json_encode($filas);
		break;
	
	default:
		$sql = mysqli_query($conection,"SELECT p.idProducto, p.prodNombre, p.prodMontoEntregado, concat(c.cliApellidos, ' ', c.cliNombres) as cliNombres, c.idCliente FROM `producto` p inner join Cliente c on c.idCliente=p.idCliente
		where prodQueEstado= ".$_POST['estado']." ;");

		while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
		{
			$filas[$i]= $row;
			$i++;
		}
		mysqli_close($conection); //desconectamos la base de datos
		echo json_encode($filas);
		break;
}




?>