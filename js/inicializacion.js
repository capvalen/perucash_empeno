$('.txtNumeroDecimal').change(function(){
	$(this).val(parseFloat($(this).val()).toFixed(2));
});
$('#agregarBarra').click(function(){
	//console.log('Se hizo clic en el boton agregar barra');
	if($('#txtBarras').val()!=''){
	$('#listBarras').show('normal');
	$('#listBarras').append('<li class="collection-item">'+$('#txtBarras').val()+'<a href="#!" class="secondary-content"><i class="material-icons red-text">close</i></a></li>')
	$('#txtBarras').val('');}
});
$(document).ready(function(){
});
$.fn.modal.prototype.constructor.Constructor.DEFAULTS.backdrop = 'static'; //Para que no cierre el modal, cuando hacen clic en cualquier parte

function esNumero(cadena) //true para si es número sólo
{
	if (cadena.match(/^[0-9]+$/))
	{
		return true;}
	else
	{
		return false;	}
}

$(".ocultar-mostrar-menu").click(function() {
	ocultar()
});
function ocultar(){//console.log('oc')
	$("#wrapper").toggleClass("toggled");
	//$('.navbar-fixed-top').css('left','0');
	$('.navbar-fixed-top').toggleClass('encoger');
	$('#btnColapsador').addClass('collapsed');
	$('#btnColapsador').attr('aria-expanded','false');
	$('#navbar').removeClass('in');
}
$('.has-clear').mouseenter(function(){$(this).find('input').focus();})

$('.has-clear input[type="text"]').on('input propertychange', function() {
	var $this = $(this);
	var visible = Boolean($this.val());
	$this.siblings('.form-control-clear').toggleClass('hidden', !visible);
}).trigger('propertychange');

$('.form-control-clear').click(function() {
	$(this).siblings('input[type="text"]').val('')
		.trigger('propertychange').focus();
});

$("input").focus(function(){
  this.select();
});
$('#btnVolverIniciarSesion').click(function () {
	if( $('#btnVolverIniciarSesion').hasClass('') ){
		$('.modal-iniciarSesion .divError').addClass('hidden');

		if($('#txtVolverUsuario').val()==''){
			$('.modal-iniciarSesion .spanError').text('Falta rellenar tu usuario');
			$('.modal-iniciarSesion .divError').removeClass('hidden');
		}
		else if($('#txtVolverPasw').val()==''){
			$('.modal-iniciarSesion .spanError').text('Falta rellenar tu usuario');
			$('.modal-iniciarSesion .divError').removeClass('hidden');
		}
		else{
			$('#btnVolverIniciarSesion').addClass('fa-spin');
			$.ajax({
			type:'POST',
			url: 'php/validarSesion.php',
			data: {user: $('#txtVolverUsuario').val(), pws: $('#txtVolverPasw').val()},
			success: function(iduser) {
				if (parseInt(iduser)>0){//console.log('el id es '+data)
					//console.log(iduser)
					location.reload();
				}
				else {
					$('.modal-iniciarSesion .spanError').text('Las credenciales no coinciden');
					$('.modal-iniciarSesion .divError').removeClass('hidden');
				}
			}
		});
		}
	}

});
$('#txtVolverPasw').keypress(function(event){
	if (event.keyCode === 10 || event.keyCode === 13) 
		{event.preventDefault();
		$('#btnVolverIniciarSesion').click();
	 }
});
$('.soloLetras').keypress(function (e) {//|| 
	if(!(e.which >= 97 /* a */ && e.which <= 122 /* z */) && !(e.which >= 48 /* 0 */ && e.which <= 90 /* 9 */)  ) {
        e.preventDefault();
    }
});
$('.modal-iniciarSesion').on('shown.bs.modal', function () {
  $('#txtVolverUsuario').focus();
})