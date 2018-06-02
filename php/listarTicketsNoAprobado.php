<?php 
require("conkarl.php");

$sql = mysqli_query($conection,"call listarTicketsNoAprobado('".date("Y-m-d")."')");
$filas=mysqli_num_rows($sql);

if($filas==0){
	echo "<tr '>
			<td colspan='5'>No hay registros en ".date("d/m/Y")." </td>
		</tr>";
}
{while($row = mysqli_fetch_array($sql, MYSQLI_ASSOC))
{

	echo "<tr data-id='{$row["idTicket"]}'>
			<th>#{$row["idTicket"]}</th>
			<td>{$row["usuNombres"]}</td>
			<td class='mayuscula tdDescip'>{$row["tipoDescripcion"]}</td>
			<td>S/. <span class='tdValor'>".number_format($row['cajaValor'], 2)."</span></td>
			<td class='mayuscula tdNombre'><a href='productos.php?idProducto={$row['idProducto']}'>{$row['prodNombre']}</a></td>
			<td class='mayuscula tdObser'>{$row['cajaObservacion']}</td>
		</tr>";

}}
//mysqli_close($conection); //desconectamos la base de datos

?>
