<!DOCTYPE html>
<html lang="es">

<head>
	<title>.:: Bienvenido a PeruCa$h ::.</title>
	<meta charset="UTF-8">
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="shortcut icon" href="images/favicon.png">
	<link rel="stylesheet" type="text/css" href="css/anatsunamun.css">
	<link rel="stylesheet" type="text/css" href="css/icofont.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">


</head>
<body>
<style type="text/css">
	body{background: linear-gradient(90deg, #192335 10%, #101722 90%);}
	.container{ margin-top:80px; padding:0 50px}
	.wello{padding:40px 50px; border-radius: 6px;}
</style>
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="wello login-box">
				<h2 class="text-center" style="font-weight:300;">PeruCash</h2>
				<legend><small>Casa de préstamos y empeños</small></legend>
				
			<div class="form-group">
				<label for="username">Usuario</label>
				<input class="form-control" value='' id="username" placeholder="Ingrese su nombre de usuario" type="text"  />
			</div>
			<div class="form-group">
				<label for="password">Contraseña</label>
				<input class="form-control" id="passw" value='' placeholder="Contraseña" type="password" />
			</div>
			<div class="form-group hidden">
				<label for="password">Su nombre</label>
				<input class="form-control mayuscula" id="nombr" value='' placeholder="Díme tu nombre, por favor" type="text" />
			</div>
			<div class="form-group">
				<label for="password">Elija una modalidad</label>
				<select name="" id="cmbMod" class="form-control">
					<option value="2">Asistente administrativo</option>
					<option value="1">Director operativo</option>
					
				</select>
			</div>
			<div class="form-group">
				<label for="password">Oficina</label>
				<select name="" id="office" class="form-control">
					<option value="0">Seleccione una oficina</option>
					<?php include 'php/listarSucursales.php'; ?>
					
				</select>
			</div>
			<div class="form-group text-center text-danger hidden" id="divError">Error en alguno de los datos, complételos todos cuidadosamente.</div>
			<div class="form-group text-center">
				<button class="btn btn-danger btn-outline" id="btnCancelar"><i class="icofont icofont-logout"></i> Cancelar</button>
				<button class="btn btn-success btn-outline" id="btnIniciar"><i class="icofont icofont-key"></i> Iniciar</button>
			</div>
			
			</div>
		</div>
	</div>
</div>

	<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/impotem.js"></script>
	<script type="text/javascript">
	$.ajax({url: 'php/desconectar.php'});
	$('#username').focus();
	$('#btnIniciar').click(function () {
		if($('#username').val()=='' || $('#passw').val()=='' || $('#office').val()==0){$('#divError').removeClass('hidden');}
		else{
			$.ajax({url: 'php/validarSesion.php', type: 'POST', data: {
			user: $('#username').val(),
			pws: $('#passw').val(),
			offi: $('#office').val(),
			modalidad: $('#cmbMod').val()
			 

			}}).done(function (resp) {
			console.log(resp);
			if(resp==''){
				$('#divError').removeClass('hidden');
			}
			if(resp=='Welcome guy!'){window.location.href = 'aplicativo.php'}
		}).error(function (err) {console.log(err);
			// body...
		});
		}
		
	});
	$('#btnCancelar').click(function(){
		//console.log($('#office').val())
	});
	</script>
</body>
</html>