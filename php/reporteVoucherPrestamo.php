<?php 
session_start();
header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';
// header("Content-type: application/vnd.ms-excel");
// header("Content-Disposition: attachment; filename=Comprobante de Prestamo.xls");


$log = mysqli_query($conection,"call listarProductoPorId(".$_GET['idProd'].")");

$row = mysqli_fetch_array($log, MYSQLI_ASSOC);
if ($row['idProducto']>=1){
	// echo $row['propietario'];
	// echo $row['prodNombre'];
	// echo $row['prodMontoEntregado'];
	// echo $row['prodInteres'];
	// echo $row['prodFechaInicial'];
	// echo $row['prodObservaciones'];
	// echo $row['sucNombre'];
	
}


/* liberar la serie de resultados */
mysqli_free_result($log);

/* cerrar la conexión */
mysqli_close($conection);

 ?>
 <!DOCTYPE html>
 <html lang="es">
 <head>
 	<meta charset="UTF-8">
 	<title>Comprobante de Préstamo</title>

 	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
 </head>
 <body>
 <style>
 .mayuscula{text-transform: capitalize;}
 .tablaImpr{border: 1px solid #c74919; padding: 10px;}
 .parteNoDato{border-right: 1px solid #c74919;}
 .parteDatos .row{margin-top: 7px;}
 .vistoBueno{margin-top: 40px; border-top: 1px solid black;}
 h4{margin-bottom: 0px;}
 hr{    margin-top: 15px; margin-bottom: 15px;}
 </style>

	<div class="container">
		<div class="row tablaImpr">
			<div class="col-xs-4 parteNoDato">
				<div class="row container-fluid text-center ">
					<img src="https://perucash.com/app/images/logo.png" alt="">
					<div class="row"><h4>Comprobante de empeño a Perú Cash</h4> 
					<small>Generado el día <small id="spanDia"></small>,</small> 
					<small>por «<?php echo $row['atendio']; ?>»</small> 
					<small> en la oficina <?php echo $row['sucNombre']; ?> </small><br>
					<small>Celular: # 943 798696</small><br>
					<small>Web: www.perucash.com</small><br>
					<small>Gracias por tu preferencia</small>
					</div>
				</div>
			</div>
			<div class="col-xs-8 parteDatos">
				<div class="row">
					<div class="col-xs-4  text-right"><strong>Propietario:</strong> </div>
					<div class="col-xs-8 mayuscula"><?php echo $row['propietario']; ?></div>
				</div>
				<div class="row">
					<div class="col-xs-4  text-right"><strong>Objeto:</strong> </div>
					<div class="col-xs-8 mayuscula"><?php echo $row['prodNombre']; ?></div>
				</div>
				<div class="row">
					<div class="col-xs-4  text-right"><strong>Monto inicial:  </strong> </div>
					<div class="col-xs-8">S/. <?php echo $row['prodMontoEntregado']; ?></div>
				</div>
				<div class="row">
					<div class="col-xs-4  text-right"><strong>Interés:  </strong> </div>
					<div class="col-xs-8"><?php echo $row['prodInteres']; ?> % semanal</div>
				</div>
				<div class="row">
					<div class=" col-xs-4  text-right"><strong>Observaciones:  </strong> </div>
					<div class="col-xs-8"><em><?php echo $row['prodObservaciones']; ?></em></div>
				</div>
				<div class="row">
					<div class=" col-xs-5 vistoBueno text-center"><small class="mayuscula"><?php echo $row['propietario']; ?></small> <br><span>Cliente</span></div>
					<div class="col-xs-offset-2 col-xs-4 vistoBueno text-center"><small class="mayuscula"><?php echo $row['atendio']; ?></small> <br><span>Asistente</span></div>
				</div>
			</div>
		</div>
		
	</div>
	<hr>
	<div class="container">
		<div class="row tablaImpr">
			<div class="col-xs-4 parteNoDato">
				<div class="row container-fluid text-center ">
					<img src="https://perucash.com/app/images/logo.png" alt="">
					<div class="row"><h4>Comprobante de empeño a Perú Cash</h4> 
					<small>Generado el día <small id="spanDia"></small>,</small> 
					<small>por «<?php echo $row['atendio']; ?>»</small> 
					<small> en la oficina <?php echo $row['sucNombre']; ?> </small><br>
					<small>Celular: # 943 798696</small><br>
					<small>Web: www.perucash.com</small><br>
					<small>Gracias por tu preferencia</small>
					</div>
				</div>
			</div>
			<div class="col-xs-8 parteDatos">
				<div class="row">
					<div class="col-xs-4  text-right"><strong>Propietario:</strong> </div>
					<div class="col-xs-8 mayuscula"><?php echo $row['propietario']; ?></div>
				</div>
				<div class="row">
					<div class="col-xs-4  text-right"><strong>Objeto:</strong> </div>
					<div class="col-xs-8 mayuscula"><?php echo $row['prodNombre']; ?></div>
				</div>
				<div class="row">
					<div class="col-xs-4  text-right"><strong>Monto inicial:  </strong> </div>
					<div class="col-xs-8">S/. <?php echo $row['prodMontoEntregado']; ?></div>
				</div>
				<div class="row">
					<div class="col-xs-4  text-right"><strong>Interés:  </strong> </div>
					<div class="col-xs-8"><?php echo $row['prodInteres']; ?> % semanal</div>
				</div>
				<div class="row">
					<div class=" col-xs-4  text-right"><strong>Observaciones:  </strong> </div>
					<div class="col-xs-8"><em><?php echo $row['prodObservaciones']; ?></em></div>
				</div>
				<div class="row">
					<div class=" col-xs-5 vistoBueno text-center"><small class="mayuscula"><?php echo $row['propietario']; ?></small> <br><span>Cliente</span></div>
					<div class="col-xs-offset-2 col-xs-4 vistoBueno text-center"><small class="mayuscula"><?php echo $row['atendio']; ?></small> <br><span>Asistente</span></div>
				</div>
			</div>
		</div>
		
	</div>



	<!--< table class="table">
	 <thead>
	 	<tr> <th>#</th> <th>First Name</th> <th>Last Name</th> <th>Username</th> </tr> </thead>
	 	<tbody>
	 		<tr> <th scope="row">1</th> <td>Mark</td> <td>Otto</td> <td>@mdo</td> </tr> <tr>
	 		<th scope="row">2</th> <td>Jacob</td> <td>Thornton</td> <td>@fat</td> </tr> <tr>
	 		<th scope="row">3</th> <td>Larry</td> <td>the Bird</td> <td>@twitter</td> </tr>
	 	</tbody>
	 </table> -->

<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script type="text/javascript" src="../js/moment.js"></script>

<script>

moment.locale('es');
$('#spanDia').text( moment( '<?php echo $row['prodFechaInicial']; ?>').format('dddd, DD [de] MMMM [de] YYYY'));
</script>
 </body>
 </html>