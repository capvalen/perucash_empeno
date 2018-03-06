<?php 
//require_once('php/desconectar.php');



/*if(isset($_SESSION['usuario'])){
	echo '<script> window.location="Cliente.php"; </script>';
}*/
 ?>

<!DOCTYPE html>
<html lang="es">
<head >
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/icofont.css">
	
	<title>Bienvenido: PeruCash</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="css/inicio.css?version=1.0" rel="stylesheet">
	<link href="css/animate.css" rel="stylesheet">
	<link rel="shortcut icon" href="images/favicon.png">


</head>

<body >
<style type="text/css">
.form-control:focus{    border-color: #FFEB3B;box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 6px rgba(255, 193, 7, 0.55);}
body{background: linear-gradient(90deg, #100b19 10%, #291c40 90%);}
main{ margin-top:80px; padding:0 50px}
.wello{padding:40px 50px; border-radius: 6px;padding-bottom: 58px;}
.noselect {
	-webkit-touch-callout: none; /* iOS Safari */
	-webkit-user-select: none;   /* Chrome/Safari/Opera */
	-khtml-user-select: none;    /* Konqueror */
	-moz-user-select: none;      /* Firefox */
	-ms-user-select: none;       /* Internet Explorer/Edge */
	user-select: none;           /* Non-prefixed version, currently not supported by any browser */
}
input{height: 40px!important;}
label{font-size: 14px!important}
input::placeholder{font-size: 14px!important;}
input{height: 45px!important; color: #673ab7!important;
display: inline-block!important; font-size: 18px!important;
    /* width: 95%!important; */}
.icoTransparent{display: inline-block; color: #555; font-size: 16px;
margin-left: -25px; opacity: 0.5}
a{    color: #6d3cca;
    font-weight: 700;}
a:hover{color:#462782;}
</style>
<main class="noselect">
	<div class="container-fluid">
		<div class="col-md-12">
			<div class="wello login-box " style="color: #673ab7"  >
				<div class="row">
					<div class="col-xs-4"><img src="images/VirtualCorto.png" class="img-responsive" alt=""></div>
					<div class="col-xs-8"><h3 class="text-center" style="margin-bottom: 0px;">Info-Cat </h3>
						<div class="text-center"><span >App para «PeruCash» - Retamas</span></div>
						<legend  style="color:#7956C1"><small style=" font-size: 70%;"></small></legend></div>
				</div>
				
			<div class="form-group">
				<label class="hidden" for="username"><i class="icofont icofont-user"></i> Usuario</label>
				<input class="form-control text-center" value='' id="txtUser_app" placeholder="Usuario" type="text"  /><div class="icoTransparent"><i class="icofont icofont-user"></i> </div>
			</div>
			<div class="form-group">
				<label class="hidden" for="password"><i class="icofont icofont-key"></i> Contraseña</label>
				<input class="form-control text-center" id="txtPassw" value='' placeholder="Contraseña" type="password" /><div class="icoTransparent"><i class="icofont icofont-ui-text-loading"></i>
			</div>
			
			<div class="form-group text-center"><br>
				<button class="btn btn-danger btn-outline hidden" id="btnCancelar"><i class="icofont icofont-logout"></i> Cancelar</button>
				<button class="btn btn-morado btn-outline btn-block btn-lg" id="btnAcceder"><div class="fa-spin sr-only"><i class="icofont icofont-spinner "></i> </div><i class="icofont icofont-key"></i> Iniciar sesión</button>
			</div>
			<div class="form-group text-center text-danger hidden" id="divError">Error en alguno de los datos, complételos todos cuidadosamente.</div>
			
			<div class="pull-left" ><small><?php include 'php/version.php' ?> | 2016 - <?php echo date("Y"); ?> <a href="!#"><br>®  Info-cat</a></small></div>
			</div>
		</div>
	</div>
</main>
</body>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>


<!-- Bootstrap Core JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<!-- <script src="./node_modules/socket.io/node_modules/socket.io-client/socket.io.js"></script> 
<script src="js/socketCliente.js"></script>-->
<script>
$(document).ready(function () {
	$('#txtUser_app').val('');
	$('#txtPassw').val('');
	$('#txtUser_app').focus();
	/*$('.wello').addClass('animated bounceIn');*/
	$('.fa-spin').addClass('sr-only');
	//$('body').css("background-image", "url(images/fondo.jpg)");		
});
$('#txtPassw').keypress(function(event){
	if (event.keyCode === 10 || event.keyCode === 13) 
		{event.preventDefault();
		$('#btnAcceder').click();
	 }
});
$('#btnAcceder').click(function() {
	$('.fa-spin').removeClass('sr-only');$('.icofont-key').addClass('sr-only');
	$('#divError').addClass('hidden');
	$.ajax({
		type:'POST',
		url: 'php/validarSesion.php',
		data: {user: $('#txtUser_app').val(), pws: $('#txtPassw').val()},
		success: function(iduser) {
			if (iduser!=0){//console.log('el id es '+data)
				//console.log(iduser)
				window.location="registro.php";
			}
			else {
				$('#divError').removeClass('hidden');
				//var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
				// $('#btnAcceder').addClass('animated wobble' ).one(animationEnd, function() {
				// 		$(this).removeClass('animated wobble');
				// });
				$('#txtUser_app').select();
				$('.fa-spin').addClass('sr-only');$('.icofont-key').removeClass('sr-only');
				//console.log(iduser);
				console.log('error en los datos')}
		}
	});
});
$('#btnCancelar').click(function(){
	window.location.href = 'www.google.com.pe';
});
</script>
</html>