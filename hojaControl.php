<?php 
include 'php/conkarl.php';
header('Content-Type: text/html; charset=utf8');


$filas=array();
$log = mysqli_query($conection,"call solicitarHojaControl(".$_GET['idProd']." );");

$row = mysqli_fetch_array($log, MYSQLI_ASSOC);
//echo $row['cliApellidos'];


/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexi贸n */
mysqli_close($conection);

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Hoja Control - Peru Cash</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<style>
.mayuscula{text-transform: capitalize;}
.labelPrintGrande{display:inline-block;}
.elemPrintGrande{
	display:inline-block;
	padding: 0px 14px;
}
/*.elemRecuadro, */
.cajaRecuadro{
	border: 2px solid #b5b5b5;
	display:inline-block;

}
.cajaRecuadro{width: 80%;
	height: 36px;}
hr{
	margin-top: 2px;
    margin-bottom: 2px;
    border: 0;
    border-top: 1px solid #6d5e5e;
}
@media print {
	.container {
	  width: auto;
	}
	.labelPrintGrande, .elemPrintGrande{font-size: 18px!important;}
	.elemPrintMediano{font-size: 14px!important;}
}

</style>
<div class="container">
	<div class="row">
		<div class="col-xs-2"><img src="images/logo.png" class="img-responsive" alt=""></div>
	<div class="col-xs-9 text-center"><h4>Hoja informativa - Agencia "San Carlos"</h4></div>
	</div>
	<div class="row">
		<p class="mayuscula"><strong class="elemPrintGrande">Código:</strong> <span class="elemPrintGrande elemRecuadro"><?php echo $row['idProducto']; ?></span>
		<strong class="elemPrintGrande">Cliente:</strong> <span class="elemPrintGrande elemRecuadro"><?php echo $row['cliApellidos'].' '.$row['cliNombres']; ?></span></p>
	</div>
	<div class="row">
		<p class="mayuscula"><strong class="elemPrintGrande">D.N.I.:</strong> <span class="elemPrintGrande elemRecuadro"><?php echo $row['cliDni']; ?></span>
		<strong class="elemPrintGrande">Dirección:</strong> <span class="elemPrintGrande elemRecuadro"><?php echo $row['cliDireccion']; ?></span></p>
	</div>
	<div class="row">
		<div class="col-xs-6">
			<p class="mayuscula"><strong class="labelPrintGrande">Teléfono:</strong> <span class="elemPrintGrande elemRecuadro"><?php echo $row['cliCelular']; ?></span></p>
		</div>
		<div class="col-xs-6">
			<p class="mayuscula"><strong class="elemPrintGrande">E-mail:</strong> <span class="elemPrintGrande elemRecuadro"><?php echo $row['cliCorreo']; ?></span></p>
		</div>
	</div>
	<div class="row">
		<p class="mayuscula"><strong class="elemPrintGrande">Descripción del Bien:</strong> <span class="elemPrintGrande elemRecuadro"><?php echo $row['prodNombre']; ?></span></p>
	</div>
	<div class="row">
		<div class="col-xs-4" style="padding-left: 0px;">
			<p><label>Registro:</label> <span class="elemPrintMediano"><?php echo $row['prodFechaRegistro']; ?></span></p>
		</div>
		<div class="col-xs-4" >
			<p><label>Monto Dado:</label> <span class="elemPrintMediano" >S/. <?php echo number_format($row['prodMontoEntregado'], 2) ; ?></span></p>
		</div>
		<div class="col-xs-4" style="padding-left: 0px; padding-right: 0px;">
			<p><label>Atendido por:</label> <?php echo $row['usuNombres'].' '.substr($row['usuApellido'],0,5).'.'; ?></p>
		</div>
	</div>
	<div class="row">
		<p class="mayuscula"><strong class="">Comentarios / Observaciones:</strong> <span class="elemRecuadro cajaRecuadro" style="width: 100%"><?php echo $row['prodObservaciones']; ?></span></p>
	</div>
	<div class="row">
		<div class="col-xs-4">
			<p><label>Vence:</label> <span class="cajaRecuadro" style="width: 70%"></span></p>
			
		</div>
		<div class="col-xs-4">
			<p><label>V° B°:</label> <span class="cajaRecuadro" style="width: 70%;"></span></p>
		</div>
	</div>
	<div class="row">
		<p class="mayuscula"><strong class="">Ubicación en almacén:</strong> <span class="cajaRecuadro" style="width: 70%;"></span></p>
	</div>
	<div class="row">
		<div class="col-xs-3"><p>Llamada <span class="cajaRecuadro"></span></p></div>
		<div class="col-xs-3"><p>SMS <span class="cajaRecuadro"></span></p></div>
		<div class="col-xs-3"><p>Facebook <span class="cajaRecuadro"></span></p></div>
		<div class="col-xs-3"><p>WhatsAPP <span class="cajaRecuadro"></span></p></div>
	</div>
	<div class="row">
		<p><label>Respuesta:</label> <span class="cajaRecuadro"></span></p>
	</div>



	<hr> <!-- divisor de ambas caras -->



	<div class="row">
		<div class="col-xs-2"><img src="images/logo.png" class="img-responsive" alt=""></div>
	<div class="col-xs-9 text-center"><h4>Hoja informativa - Agencia "San Carlos"</h4></div>
	</div>
	<div class="row">
		<p class="mayuscula"><strong class="elemPrintGrande">Código:</strong> <span class="elemPrintGrande elemRecuadro"><?php echo $row['idProducto']; ?></span>
		<strong class="elemPrintGrande">Cliente:</strong> <span class="elemPrintGrande elemRecuadro"><?php echo $row['cliApellidos'].' '.$row['cliNombres']; ?></span></p>
	</div>
	<div class="row">
		<p class="mayuscula"><strong class="elemPrintGrande">D.N.I.:</strong> <span class="elemPrintGrande elemRecuadro"><?php echo $row['cliDni']; ?></span>
		<strong class="elemPrintGrande">Dirección:</strong> <span class="elemPrintGrande elemRecuadro"><?php echo $row['cliDireccion']; ?></span></p>
	</div>
	<div class="row">
		<div class="col-xs-6">
			<p class="mayuscula"><strong class="labelPrintGrande">Teléfono:</strong> <span class="elemPrintGrande elemRecuadro"><?php echo $row['cliCelular']; ?></span></p>
		</div>
		<div class="col-xs-6">
			<p class="mayuscula"><strong class="elemPrintGrande">E-mail:</strong> <span class="elemPrintGrande elemRecuadro"><?php echo $row['cliCorreo']; ?></span></p>
		</div>
	</div>
	<div class="row">
		<p class="mayuscula"><strong class="elemPrintGrande">Descripción del Bien:</strong> <span class="elemPrintGrande elemRecuadro"><?php echo $row['prodNombre']; ?></span></p>
	</div>
	<div class="row">
		<div class="col-xs-4" style="padding-left: 0px;">
			<p><label>Registro:</label> <span class="elemPrintMediano"><?php echo $row['prodFechaRegistro']; ?></span></p>
		</div>
		<div class="col-xs-4" >
			<p><label>Monto Dado:</label> <span class="elemPrintMediano" >S/. <?php echo number_format($row['prodMontoEntregado'], 2) ; ?></span></p>
		</div>
		<div class="col-xs-4" style="padding-left: 0px; padding-right: 0px;">
			<p><label>Atendido por:</label> <?php echo $row['usuNombres'].' '.substr($row['usuApellido'],0,5).'.'; ?></p>
		</div>
	</div>
	<div class="row">
		<p class="mayuscula"><strong class="">Comentarios / Observaciones:</strong> <span class="elemRecuadro cajaRecuadro" style="width: 100%"><?php echo $row['prodObservaciones']; ?></span></p>
	</div>
	<div class="row">
		<div class="col-xs-4">
			<p><label>Vence:</label> <span class="cajaRecuadro" style="width: 70%"></span></p>
			
		</div>
		<div class="col-xs-4">
			<p><label>V° B°:</label> <span class="cajaRecuadro" style="width: 70%;"></span></p>
		</div>
	</div>
	<div class="row">
		<p class="mayuscula"><strong class="">Ubicación en almacén:</strong> <span class="cajaRecuadro" style="width: 70%;"></span></p>
	</div>
	<div class="row">
		<div class="col-xs-3"><p>Llamada <span class="cajaRecuadro"></span></p></div>
		<div class="col-xs-3"><p>SMS <span class="cajaRecuadro"></span></p></div>
		<div class="col-xs-3"><p>Facebook <span class="cajaRecuadro"></span></p></div>
		<div class="col-xs-3"><p>WhatsAPP <span class="cajaRecuadro"></span></p></div>
	</div>
	<div class="row">
		<p><label>Respuesta:</label> <span class="cajaRecuadro"></span></p>
	</div>

</div> <!-- Fin de container -->
	
</body>
</html>