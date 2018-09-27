<?php session_start();
date_default_timezone_set('America/Lima');
require("php/conkarl.php");
?>
<!DOCTYPE html>
<html lang="es">


<head>
	<title>Bienvenido: PeruCash</title>
	<?php include "header.php"; ?>		
</head>

<body>
<style>
#page-content-wrapper { padding: 5px!important;}
#miniMenu{padding: 12px;background-color:white;}

.contenedorDeslizable{background-color:#f3f7f8;}
.contenedorDeslizable h2{margin-top: 10px;}
#rowConPeques{padding: 20px 0;}
#rowConPeques>div{margin-bottom: 10px;}
.contenedorDeslizable .row{padding:20px;}
.contenedorDeslizable .rowBlanco, .tarjeta{background-color:white; border-radius: .25rem;}
.cuadroPeque{padding: 20px;border: 1px solid rgba(0,0,0,.0625);}
.cuadroPeque i{font-size: 30px;}
.spanRelleno{font-size: 12px;color: #455a64;}
.spanValue{font-size: 30px;color: #455a64;}
.divContenedor{display:table;}
label{color: #99abb4;font-weight: 500;font-size: 14px;}
.divIcono{
	background: #1e88e5;
	line-height: 65px;
	width: 60px;
	height: 60px;
	color: #ffffff;
	display: inline-block;
	font-weight: 400;
	text-align: center;
	border-radius: 100%;
	align-self: center!important;
}
.round.round-info {
  background: #1e88e5; }

.round.round-warning {
  background: #ffb22b; }

.round.round-danger {
  background: #fc4b6c; }

.round.round-success {
  background: #26c6da; }

.round.round-primary {
  background: #7460ee; }
#divMontoMes{color: #1e88e5;}
#divMontoMesAnt{color: #7460ee;}
#miniMenu h4{display: inline-block;margin-top: 0px;}
#miniMenu p{margin-bottom: 4px;    color: #455a64; }
</style>
<div id="wrapper">

	<!-- Sidebar -->
	<?php include 'menu-wrapper.php' ?>
<!-- Page Content -->
<div id="page-content-wrapper">
	<div class="container-fluid " id="miniMenu">
		<div class="col-xs-7">
			<h3 class="purple-text text-lighten-1">Panel de administración</h3>
		</div>
		<div class="col-xs-5">
			<div class="col-xs-6" id="divMontoMes" data-toggle="tooltip" title="Suma de Recuperación - Inversión"><p>Éste mes</p><h4 id="montoMes">S/. <?php include 'php/recuperacionEsteMes.php'; ?></h4> <h4><i class="icofont icofont-chart-histogram-alt"></i></h4> </div>
			<div class="col-xs-6" id="divMontoMesAnt" data-toggle="tooltip" title="Suma de Recuperación - Inversión"><p>Anterior mes</p><h4 id="montoMesAnt">S/. <?php include 'php/recuperacionAnteriorMes.php'; ?></h4> <h4><i class="icofont icofont-chart-histogram-alt"></i></h4> </div>
		</div>
	</div>
	<div class="container-fluid contenedorDeslizable">
		<div class="container-fluid  ">
		<!-- Empieza a meter contenido principal dentro de estas etiquetas -->
			
			<div class="row" id="rowConPeques">
				<div class="col-xs-6 col-sm-4" style="padding-left: 0;"><div class="rowBlanco cuadroPeque">
					<div class="container-fluid">
						<div class="col-xs-3 divIcono round round-info">
							<i class="icofont icofont-support-faq"></i> 
						</div>
						<div class="col-xs-9">
							<span class="spanValue"><?php include 'php/contarPrestamosHoy.php'; ?></span><br>
							<label>Total préstamos</label>
						</div>
					</div>
				</div></div>
				<div class="col-xs-6 col-sm-4"><div class="rowBlanco cuadroPeque">
					<div class="container-fluid">
							<div class="col-xs-3 divIcono round round-info">
								<i class="icofont icofont-support-faq"></i> 
							</div>
							<div class="col-xs-9">
								<span class="spanValue "><?php include 'php/contarComprasHoy.php'; ?></span> <br>
								<label>Total compras</label>
							</div>
						</div>
				</div></div>
				<div class="col-xs-6 col-sm-4"  style="padding-right: 0;"><div class="rowBlanco cuadroPeque">
					<div class="container-fluid">
						<div class="col-xs-3 divIcono round round-primary">
							<i class="icofont icofont-money"></i> 
						</div>
						<div class="col-xs-9">
							<span class="spanRelleno ">S/</span> <span class="spanValue "><?php include 'php/sumarInyeccionHoy.php'; ?></span> <br>
							<label>Inyección</label>
						</div>
					</div>
				</div></div>
				<div class="col-xs-6 col-sm-4"  style="padding-left: 0;"><div class="rowBlanco cuadroPeque">
					<div class="container-fluid">
						<div class="col-xs-3 divIcono round round-primary">
							<i class="icofont icofont-money"></i> 
						</div>
						<div class="col-xs-9">
							<span class="spanRelleno ">S/</span> <span class="spanValue "><?php include 'php/sumarInversionHoy.php'; ?></span> <br>
							<label>Inversión</label>
						</div>
					</div>
				</div></div>
				<div class="col-xs-6 col-sm-4" ><div class="rowBlanco cuadroPeque">
					<div class="container-fluid">
						<div class="col-xs-3 divIcono round round-primary">
							<i class="icofont icofont-money"></i> 
						</div>
						<div class="col-xs-9">
							<span class="spanRelleno ">S/</span> <span class="spanValue "><?php include 'php/sumarRetornoHoy.php'; ?></span> <br>
							<label>Retorno</label>
						</div>
					</div>
				</div></div>
				<div class="col-xs-6 col-sm-4"  style="padding-right: 0;"><div class="rowBlanco cuadroPeque">
					<div class="container-fluid">
						<div class="col-xs-3 divIcono round round-warning">
							<i class="icofont icofont-card"></i> 
						</div>
						<div class="col-xs-9">
							<span class="spanRelleno ">S/</span> <span class="spanValue "><?php include 'php/sumarTarjetasHoy.php'; ?></span> <br>
							<label>Tarjetas</label>
						</div>
					</div>
				</div></div>
				<div class="col-xs-6 col-sm-4 hidden"  ><div class="rowBlanco cuadroPeque">
					<div class="container-fluid">
						<div class="col-xs-3 divIcono round round-success">
							<i class="icofont icofont-ui-rate-add"></i> 
						</div>
						<div class="col-xs-9">
							<span class="spanRelleno ">S/</span> <span class="spanValue ">0.00</span> <br>
							<label>Entradas extras</label>
						</div>
					</div>
				</div></div>
				<div class="col-xs-6 col-sm-4" style="padding-left: 0;"><div class="rowBlanco cuadroPeque">
					<div class="container-fluid">
						<div class="col-xs-3 divIcono round round-danger">
							<i class="icofont icofont-ui-rate-remove"></i> 
						</div>
						<div class="col-xs-9">
							<span class="spanRelleno ">S/</span> <span class="spanValue "><?php include 'php/sumarSalidasHoy.php' ?></span> <br>
							<label>Salidas extras</label>
						</div>
					</div>
				</div></div>
			</div>
			<div class="tarjeta">
				<div class="col-xs-12 col-sm-6  tarjeta"><h3>Record 15 productos</h3>
					<canvas id="myChart" width="400" height="400"></canvas>
				</div>
			</div>
			
		<!-- Fin de contenido principal -->
		</div>
	</div>
</div>
<!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->


<?php include 'footer.php'; ?>
<script src="js/stupidtable.min.js"></script>
<script src="js/bootstrap-material-datetimepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
<?php include 'php/modals.php'; ?>
<?php include 'php/existeCookie.php'; ?>

<!-- Menu Toggle Script -->
<?php if ( isset($_COOKIE['ckidUsuario']) ){?>
<script>
datosUsuario();


<?php 
$sqlCh = "SELECT count(p.idTipoProducto) as conteo, tp.tipopDescripcion FROM `producto` p
inner join tipoProducto tp on p.idTipoProducto = tp.idTipoProducto
where date_format(curdate(), '%Y-%m') = date_format(p.prodFechaRegistro, '%Y-%m')
group by p.idTipoProducto
order by conteo asc
limit 15;";

$llamadoCh = $cadena->query($sqlCh);



// liberar el conjunto de resultados
//$llamadoCh->close();
?>
$(document).ready(function(){
	$('[data-toggle="tooltip"]').tooltip(); 
});

var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
		  labels: [<?
			while ($resultadoCh = $llamadoCh->fetch_assoc()){ ?>
				"<?= $resultadoCh['tipopDescripcion']; ?>"<? echo ',';
			} ?>],
        datasets: [{
            label: 'Cantidad de préstamos',
            data: [<?
				$llamadoCh->data_seek(0); 
				while ($resultadoCh = $llamadoCh->fetch_assoc()){ ?>
					"<?= $resultadoCh['conteo']; ?>"<? echo ',';
				} ?>],
            backgroundColor: [
                'rgba(255, 99, 132, 0.6)',
                'rgba(54, 162, 235, 0.6)',
                'rgba(255, 206, 86, 0.6)',
                'rgba(75, 192, 192, 0.6)',
                'rgba(153, 102, 255, 0.6)',
                'rgba(255, 159, 64, 0.6)',
                'rgba(255, 99, 132, 0.6)',
                'rgba(54, 162, 235, 0.6)',
                'rgba(255, 206, 86, 0.6)',
                'rgba(75, 192, 192, 0.6)',
                'rgba(153, 102, 255, 0.6)',
                'rgba(255, 159, 64, 0.6)',
					 'rgba(255, 99, 132, 0.6)',
                'rgba(54, 162, 235, 0.6)',
                'rgba(255, 206, 86, 0.6)',
					 
            ],
            borderColor: [
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
                'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(255, 206, 86, 1)',
                'rgba(75, 192, 192, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)',
					 'rgba(255,99,132,1)',
                'rgba(54, 162, 235, 1)',
                'rgba(75, 192, 192, 1)',
            ],
            borderWidth: 0.5
        }]
    },
   options: {
		scales: {
      	yAxes: [{
				ticks: {
					beginAtZero:true,
					display: false
				}
			}]
		}
   },
	pieceLabel: { mode: 'percentage', precision: 2 } 
});
</script>
<?php } ?>
</body>

</html>
