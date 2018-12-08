<?php 
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require "php/conkarl.php";
require_once('vendor/autoload.php');
$base58 = new StephenHill\Base58();
if(isset($_GET['solicita'])){$correo=$base58->decode($_GET['solicita']);}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Infocat Soluciones - App Login</title>
	<!-- Bootstrap Core CSS -->
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link rel="shortcut icon" href="images/favicon.png">
	<link rel="stylesheet" href="css/animate.css" >
	<link rel="stylesheet" href="css/icofont.css">
</head>
<body>
<style>
body{
	background-color: #cacaca;
}

body{
	background-color: #39bcf0;
	background: url('images/shutterstock_360655751.jpg?v=0.1');
	/*background-repeat: no-repeat;
	background-position: center;
	background-size: cover;*/
	background-attachment: fixed;
	
	position: absolute;
	display: block;
	width: 100%;
	height: 100%;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	background-repeat: no-repeat;
	background-position: top;
	background-size: inherit;
}
.puntos{ background: url(images/gridtile_3x3.png);
	position: fixed;
	display: block;
	width: 100%;
	height: 100%;
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
}
#conPrincipal{
 margin-top:100px; padding: 20px 60px;
}
#contSecundario{
	background: rgba(255, 255, 255, 0.472); border-radius: 10px;
	padding-top:80px;
	
	padding-bottom: 0px;
	background: linear-gradient(-45deg, rgba(255, 124, 124, 0.25), rgba(82, 74, 133, 0.8));
}
#contSubSecundario{
	padding-left:20px;padding-right:20px;
}
#subContCenter img{ width:128px;
	/*margin: 0 auto;
	margin-left: auto;
	margin-right: auto; */
}
.contenidoCambiante{color: white; margin-bottom: 60px ;}
.subText{font-size: 15px;}
#btnEmpezar{width: 60%; margin: 0 auto;
background-color: #6C56B8; margin-top: 40px ;  font-size: 16px;
border-color: transparent; color: white; padding: 15px 0; border-radius: 50px;letter-spacing: 0.2rem;
}
.subText i{font-size: 12px;}
#btnEmpezar:focus { outline: 0; }
#divIntCenter{
	background: white;
	width: 188px;
	padding: 30px;
	border-radius: 50%;margin-bottom: 20px;
}
#modalmostrarResetMail .form-control{color: #6C56B8;}
#txtCorreoElectronicoUs:focus{border-color: #ad66e9; box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(150, 159, 255,.6);}
#modalmostrarResetMail h4{color: #717171;}
@media only screen and (max-width: 1100px){
#rowGrande{
	
	background-position: center;
	background-size: cover;
}
}
.divAbajo{
	background-color: #8663ff;
	color: #ffffff99;
	padding: 20px 15px;
	border-radius: 0 0 10px 10px;
}
.divAbajo a{color: #fff; text-decoration: none;}
.divAbajo a:hover{color: #ffffffc7;}
.icoTransparent{
	display: inline-block;
	color: #dfd4f9d6;
	font-size: 16px;
	margin-left: 16px;
	margin-top: -30px;
	position: absolute;
}
.md-input {
    position: relative;
    /* margin-bottom: 30px; */
    
}
    .md-input .md-form-control {
        font-size: 16px;
        padding: 10px 10px 10px 5px;
        display: block;
        border: none;
        border-bottom: 2px solid #dfd4f9;
        box-shadow: none;
        width: 100%;outline: 0;
		  background: transparent;
    }

    .md-input label {
        color: #dfd4f9d6;
        font-size: 16px;
        font-weight: normal;
        position: absolute;
        pointer-events: none;
        left: 52px;
        top: 14px;
        transition: 0.2s ease all;
        -moz-transition: 0.2s ease all;
        -webkit-transition: 0.2s ease all;
    }

    .md-input .bar:before {
        left: 50%;
    }

    .md-input .bar:after {
        right: 50%;
    }

    .md-input .highlight {
        position: absolute;
        height: 60%;
        width: 100px;
        top: 25%;
        left: 0;
        pointer-events: none;
        opacity: 0.5;
    }
    .md-input .md-form-control:focus ~ label, .md-input .md-form-control:valid ~ label {
    top: -15px;
    font-size: 12px;
    color: #e8daff;
}
.md-input .bar:before, .md-input .bar:after {
    content: '';
    height: 2px;
    width: 0;
    bottom: 0px;
    position: absolute;
    background: #8663ff;
    transition: 0.2s ease all;
    -moz-transition: 0.2s ease all;
    -webkit-transition: 0.2s ease all;
}

.md-input .md-form-control:focus ~ .bar:before, .md-input .md-form-control:focus ~ .bar:after {
    width: 50%;
}
#btnAcceder, #btnResetContador, #btnReset{ color: white;
	background: linear-gradient(90deg,#A97EDA, #4C9BDD);
   border: transparent;
}
#btnAcceder:focus, #btnReset:focus{outline: 0;}
#secDiv{width: 80%; margin: 0 auto; margin-bottom: 60px;}
#divError{margin-top: 25px;}
.inputGrande{font-size: 21px;}
.inputGrande::placeholder{font-size: 12px;}
#imgNutria{max-width: 60%; margin: 0 auto; padding-top: 30px;}
.fa-spin {
-webkit-animation: spin 1000ms infinite linear;
animation: spin 1000ms infinite linear;
text-align: center;
display: inline-block;
}
.modal-backdrop{ background-color: #7f54bb; }
@-webkit-keyframes spin {
0% {-webkit-transform: rotate(0deg);transform: rotate(0deg);}
100% {-webkit-transform: rotate(359deg);transform: rotate(359deg);}
}
@keyframes spin {
0% {-webkit-transform: rotate(0deg);transform: rotate(0deg);}
100% {-webkit-transform: rotate(359deg);transform: rotate(359deg);}
}

@keyframes fa-spin {
0% {-webkit-transform: rotate(0deg);transform: rotate(0deg);}
100% {-webkit-transform: rotate(359deg);transform: rotate(359deg);}
}
</style>

<div class="row" style="margin:0;" id="rowGrande">
	<div class="puntos"></div>
	<div class="col-xs-12 col-sm-6 col-md-5 col-lg-4 col-sm-offset-3 col-md-offset-4" id="conPrincipal">
	<div id="contSecundario">
		<div class="contSubSecundario">
			<div id="subContCenter">
				<center>
					<div id="divIntCenter">
						<img src="images/peto.png" alt="" >
					</div>
				</center>
			</div>
			
			<div class="text-center contenidoCambiante animated " id="primDiv">
			<?php if( isset($_GET['solicita']) ): ?>
				<h2>Restablezca su contraseña</h2>
				<p class="subText">Hola continúe por favor para cambiar su contraseña.</p>
				<p>Procedencia de link: <?= $correo; ?></p>
			<?php else: ?>
				<h2>Bienvenido</h2>
				<p class="subText"><i class="icofont icofont-quote-left"></i> Es sencillo hacer que las cosas sean complicadas, pero difícil hacer que sean sencillas.<i class="icofont icofont-quote-right"></i></p>
				<p>Friedrich Nietzsche</p>
			<?php endif; ?>
				<button class="btn btn-default btn-block" id="btnEmpezar">INGRESAR</button>
				<a class="hidden" href="https://idevie.com/tutorials/designing-an-ios-app-in-sketch">Extraer de</a>
			</div>
			<?php if( isset($_GET['solicita']) ): ?>
			<div class="contenidoCambiante animated container-fluid hidden" id="secDiv">
				<div class="divF1">
					<div class="md-input">
						<input class="md-form-control text-center" type="text" required='' id="txtPrimContra">
						<span class="highlight"></span>
						<span class="bar"></span>
						<label>Contraseña nueva</label>
					</div>
					<div class="icoTransparent">
						<i class="icofont icofont-ui-text-loading"></i>
					</div>
				</div><br>
				<div class="divF2">
						<div class="md-input">
							<input class="md-form-control text-center" required='' type="password" id="txtSecContra">
							<span class="highlight"></span>
							<span class="bar"></span>
							<label>Repita su contraseña</label>
						</div>
					<div class="icoTransparent">
						<i class="icofont icofont-ui-text-loading"></i>
					</div>
				</div>
				<div class="divF3" style="padding-top:40px;">
					<button class="btn btn-block btn-primary btn-lg" id="btnReset"><div class="fa-spin sr-only"><i class="icofont icofont-spinner "></i></div> <i class="icofont icofont-ui-lock"></i> Resetear</button>
					<div class="text-center hidden divError" style="padding-top:20px;" ><p><i class="icofont icofont-exclamation-circle"></i> <span id="spanTextErr"></span></p></div>
				</div>
			</div>
			<?php else: ?>
			<div class="contenidoCambiante animated container-fluid hidden" id="secDiv">
				<div class="divF1">
					<div class="md-input">
						<input class="md-form-control text-center" type="text" required='' id="txtUser_app">
						<span class="highlight"></span>
						<span class="bar"></span>
						<label>Usuario</label>
					</div>
					<div class="icoTransparent">
						<i class="icofont icofont-ui-user"></i>
					</div>
				</div><br>
				<div class="divF2">
						<div class="md-input">
							<input class="md-form-control text-center" required='' type="password" id="txtPassw">
							<span class="highlight"></span>
							<span class="bar"></span>
							<label>Contraseña</label>
						</div>
					<div class="icoTransparent">
						<i class="icofont icofont-ui-text-loading"></i>
					</div>
				</div>
				<div class="divF3" style="padding-top:40px;">
					<button class="btn btn-block btn-primary btn-lg" id="btnAcceder"><div class="fa-spin sr-only"><i class="icofont icofont-spinner "></i></div> <i class="icofont icofont-ui-lock"></i> Iniciar sesión</button>
					<div class="text-center hidden" id="divError"><i class="icofont icofont-exclamation-circle"></i> Error en alguno de los datos, complételos todos cuidadosamente.</div>
				</div>
			</div>
			<?php endif; ?>
		</div>
		
		<div class="divAbajo text-center">
			<p style="margin: 0px;">¿No recuedas tu contraseña? <a href="#!" id="aClick">Click aquí</a></p>
		</div>
	</div>
	</div>

</div>


<!-- Modal para mostrar los clientes coincidentes -->
<div class="modal fade" id="modalmostrarResetMail" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-body">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<img src="images/nutritrans.png" alt="" class="img-responsive" id="imgNutria">
				<h4 class="text-center">Solicitar reseteo de contraseña</h4>
				<p class="text-center" style="color: #868686;">Por favor, ingrese su usuario para que podamos hacerle llegar un correo asociado a su cuenta:</p>
				<input type="text" class='form-control input-lg inputGrande text-center' id="txtCorreoElectronicoUs"><br>
				<button class="btn btn-azul btn-block btn-lg btn-outline" id="btnResetContador"><i class="icofont icofont-alarm"></i> Solicitar cambio</button>
			</div>
		</div>
	</div>
</div>

<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script>
$('#btnEmpezar').click(function() {
	$('#primDiv').addClass('fadeOut').one(animationEnd, function () {
		$('#primDiv').addClass('hidden');
		$('#secDiv').addClass('fadeIn').removeClass('hidden');
	});;
});
var animationEnd = (function(el) {
  var animations = {
    animation: 'animationend',
    OAnimation: 'oAnimationEnd',
    MozAnimation: 'mozAnimationEnd',
    WebkitAnimation: 'webkitAnimationEnd',
  };

  for (var t in animations) {
    if (el.style[t] !== undefined) {
      return animations[t];
    }
  }
})(document.createElement('div'));


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
	$('.fa-spin').removeClass('sr-only');$('.icofont-ui-lock').addClass('sr-only');
	$('#divError').addClass('hidden');
	$.ajax({
		type:'POST',
		url: 'php/validarSesion.php',
		data: {user: $('#txtUser_app').val(), pws: $('#txtPassw').val()},
		success: function(iduser) {
			if (parseInt(iduser)>0){//console.log('el id es '+data)
				console.log(iduser)
				window.location="principal.php";
			}
			else {
				$('#divError').removeClass('hidden');
				//var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
				// $('#btnAcceder').addClass('animated wobble' ).one(animationEnd, function() {
				// 		$(this).removeClass('animated wobble');
				// });
				$('#txtUser_app').select();
				$('.fa-spin').addClass('sr-only');$('.icofont-ui-lock').removeClass('sr-only');
				//console.log(iduser);
				$('#txtPassw').val(''); $('#txtPassw').focus();
				console.log('error en los datos')}
		}
	});
});
$('#aClick').click(function() {
	$('#modalmostrarResetMail').modal('show');
});
$('#btnResetContador').click(function() {
	$.ajax({url: 'php/enviarCorreo.php', type: 'POST', data: { queUser: $('#txtCorreoElectronicoUs').val() }}).done(function(resp) {
		console.log(resp)
	});
});
<?php if( isset($_GET['solicita']) ): ?>
$('#btnReset').click(function() {
	if($('#txtPrimContra').val() == '' || $('#txtSecContra').val()==''){
		$('#spanTextErr').text('Falta el rellenado')
		$('.divF3 .divError').removeClass('hidden');
	}else if($('#txtPrimContra').val() != $('#txtSecContra').val()){
		$('#spanTextErr').text('Las contraseñas son diferentes')
		$('.divF3 .divError').removeClass('hidden');
	}else{
		$.ajax({url: 'php/resetpass.php', type: 'POST', data: { solicita: '<? $_GET['solicita']?>' , nP: $('#txtPrimContra').val()  }}).done(function(resp) {
			console.log(resp)
		});
	}
});
<?php endif; ?>

</script>
	
</body>
</html>
