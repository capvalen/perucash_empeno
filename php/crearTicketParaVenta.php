<?php 

include 'conkarl.php';
header('Content-Type: text/html; charset=utf8');

$tipoProc=19;

$sql= "call crearTicket (".$_POST['idProd'].", {$tipoProc}, ".$_POST['monto']." , '<p>{$_COOKIE['ckAtiende']} dice: ".$_POST['obs']."</p>' , ".$_POST['idUser'].")";
echo $sql;
if ($llamadoSQL = $conection->query($sql)) { //Ejecución mas compleja con retorno de dato de sql del procedure.
	/* obtener el array de objetos */
	while ($resultado = $llamadoSQL->fetch_row()) {
		echo $resultado[0]; //Retorna los resultados via post del procedure
	}
	/* liberar el conjunto de resultados */
	$llamadoSQL->close();
}else{echo mysql_error( $conection);}

/*
Procedimientos aprobados:
15 - Retiro
16 - Intención de pago
17 - Extraviado
18 - En remate
19 - En venta
20 - Rematado
21 - Vendido
22 - Sin acción
23 - En almacén
24 - En prórroga
25 - Esperando en cochera
26 - Retirado de cochera
27 - Entrada de cochera
28 - Ingreso de empeño
29 - No encontrado en almacén
30 - Inventariado en almacén
31 - Inyección de dinero
32 - Fin del préstamo
33 - Pago parcial de interés
34 - Pago de cochera
35 - Anticipo de ventas
36 - Penalización (Gasto Admin.)
37 - Empeño
38 - Compra
39 - Adelanto de personal
40 - Pago de personal
41 - Retiro por socios
42 - Gastos relacionados con la empresa
43 - Desembolso
44 - Cancelación de interés
45 - Amortización
46 - Entrada a almacén
47 - Salida de almacén
50 - Puntual
51 - Tardanza
52 - Falta
53 - Permiso
54 - Salida sin permiso
55 - Aumento
56 - Comisión
57 - Descuento
58 - Bonos
59 - Incidencias
60 - Extra
61 - Debe horario
*/
 ?>