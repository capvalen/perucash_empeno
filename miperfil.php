<?php session_start();
if( !isset($_COOKIE['ckidUsuario'])){header('Location: index.php');}else{
	if( $_COOKIE['ckPower']=="7"){ header('Location: bienvenido.php'); } }
include 'php/conkarl.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<title>Créditos: PeruCash</title>
	<?php include "header.php"; ?>
</head>

<body>

<style>

</style>
<div id="wrapper">
	<!-- Sidebar -->
	<?php include 'menu-wrapper.php' ?>
	<!-- /#sidebar-wrapper -->
<!-- Page Content -->
<div id="page-content-wrapper">
	<div class="container-fluid ">
		<div class="row noselect">
			<div class="col-lg-12 contenedorDeslizable  container-fluid">
			<!-- Empieza a meter contenido principal -->
			<h2 class="purple-text text-lighten-1">Perfil <small><?php print $_COOKIE["ckAtiende"]; ?></small></h2><hr>
			<div class="row">
				<div class="col-xs-12 col-sm-4 col-lg-3 container-fluid">
					<h4>Mis datos:</h4>
				<? /* $imagen = '../app/images/usuarios/'.$_COOKIE['ckidUsuario'].'.jpg';
					if( file_exists($imagen) ): */?>
					<!-- <img src="<?= $imagen; ?>" class="img-responsive img-circle" style=" display: inline-block; padding: 0 40px;"> -->
				<? /* else: */ ?>
					<!-- <img src="https://perucash.com/app/images/usuarios/noimg.svg?ver=1.1" class="img-responsive img-circle" style=" display: inline-block; padding: 0 40px;"> -->
				<? /* endif; */?>
				
				<img src="https://perucash.com/app/images/usuarios/noimg.jpg?ver=1.2" class="img-responsive img-circle" style=" display: inline-block; padding: 0 40px;">
				<br>
               <label for="">Mi nombre</label> <input type="text" id="txtNombre" class="form-control text-center" readonly value="<?= $_COOKIE['cknomCompleto'];?>">
               <label for="">Mi rol actual</label> <input type="text" id="txtNombre" class="form-control text-center" readonly value="<?= $_COOKIE['ckPower'];?>">
               <!-- <label for="">Mi correo</label> <input type="text" id="txtCorreo" class="form-control text-center" autocomplete="nope" value="<?= $_COOKIE['ckCorreo'];?>">
					<button class="btn btn-azul btn-outline btn-block btn.lg" id="btnCambiarCorrreo"><i class="icofont icofont-ui-email"></i> Actualizar mi correo</button><br>
               <label for="">Cambiar contraseña:</label> <input type="password" id="txtPassN" class="form-control text-center" autocomplete="nope" value="*****************">
					<input type="password" id="txtPassR" class="form-control text-center" autocomplete="nope" value="*****************">
					<p class="perror hidden" style="color: rgb(213, 0, 0);"></p>
					<button class="btn btn-azul btn-outline btn-block btn.lg" id="btnCambiarContrasena"><i class="icofont icofont-ui-fire-wall"></i> Actualizar mi contraseña</button> -->
            </div>
            <div class="col-xs-12 col-sm-7 col-lg-8 ">
					<h4>Mis comisiones</h4>
					<p>Pronto acá se mostrarán las comisiones de cada usuario</p>
					<h4>Nueva contraseña de acceso</h4>
					<button class="btn btn-primary btn-outline" data-toggle="modal" data-target="#modalClaveNueva">Cambiar mi clave</button>
				</div>
         </div>

				
			<!-- Fin de contenido principal -->
			</div> <!-- col-lg-12 contenedorDeslizable -->
    </div><!-- row noselect -->
    </div> <!-- container-fluid -->
</div><!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->

<!-- Modal para decir que hay una observación  -->
<div class="modal fade " id="modalClaveNueva" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header-warning">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-social-readernaut"></i> Cambiar clave</h4>
			</div>
			<div class="modal-body">
				<p>Ingrese su nueva contraseña a cambiar</p>
				<input type="text" class="form-control" id="txt_clave_nueva" autocomplete="off">
				<button class="btn btn-primary btn-outline" onclick="actualizarClave()">Actualizar contraseña</button>
			</div>
		</div>
		</div>
	</div>
</div>

<?php include 'footer.php'; ?>
<?php include 'php/modals.php'; ?>
<?php include 'php/existeCookie.php'; ?>

<?php if ( isset($_COOKIE['ckidUsuario']) ){?>
<script>
$.interesGlobal=4;
datosUsuario();

$(document).ready(function(){
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('.sandbox-container input').datepicker({language: "es", autoclose: true, todayBtn: "linked"}); //para activar las fechas
});

$('#btnCambiarCorrreo').click(function() {
	$.ajax({url: 'php/actualizarDatosUsuario.php', type: 'POST', data: { correo: $('#txtCorreo').val() }}).done(function(resp) {
		console.log(resp)
		if(resp>0){
			$('#h1Bien').text('Correo actualizado con éxito');
			$('.modal-GuardadoCorrecto').modal('show');
		}else{
			$('#spanMalo').text('Hubo un error interno');
			$('.modal-GuardadoError').modal('show');
		}
	});
});

$('#btnCambiarContrasena').click(function() {
	$('.perror').addClass('hidden');
	if( $('#txtPassN').val() ==  $('#txtPassR').val()){
		$.ajax({url: 'php/actualizarDatosUsuario.php', type: 'POST', data: { passw: $('#txtPassN').val() }}).done(function(resp) {
			console.log(resp)
			if(resp>0){
				$('#h1Bien').text('Contraseña actualizado con éxito');
				$('.modal-GuardadoCorrecto').modal('show');
			}else{
				$('#spanMalo').text('Hubo un error interno');
				$('.modal-GuardadoError').modal('show');
			}
		});
	}else{
		$('.perror').text('Las contraseñas no son iguales').removeClass('hidden');
	}
	
});
async function actualizarClave(){
	let datos = new FormData();
	datos.append('texto', $('#txt_clave_nueva').val() )
	fetch('php/updatePassSinDatos.php', {
		method:'POST', body:datos
	}).then(res=> res.text() )
	.then(respuesta =>{
		$('#modalClaveNueva').modal('hide');
		if(respuesta=='1'){
			$('#h1Bien').text('Contraseña actualizado con éxito');
			$('.modal-GuardadoCorrecto').modal('show');
		}
		
	})
}

</script>
<?php } ?>
</body>

</html>