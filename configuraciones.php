<?php session_start();
if( !isset($_COOKIE['ckidUsuario'])){header('Location: index.php');}else{
	if( $_COOKIE['ckPower']=="7"){ header('Location: bienvenido.php'); } }
include 'php/conkarl.php';
include 'php/variablesGlobales.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
	<title>Configuraciones: PeruCash</title>
	<?php include "header.php"; ?>
</head>

<body>

<style>
td .form-control{
	margin-bottom: 5px;
}
</style>
<div id="wrapper">
	<!-- Sidebar -->
	<?php include 'menu-wrapper.php' ?>
	
<!-- Page Content -->
<div id="page-content-wrapper">
	<div class="container-fluid ">
		<div class="row noselect">
			<div class="col-lg-12 contenedorDeslizable ">
			<!-- Empieza a meter contenido principal -->
			<h2 class="purple-text text-lighten-1">Registro de Clientes, Productos y Compras <small><?php print $_COOKIE["ckAtiende"]; ?></small></h2><hr>
			<div class="panel panel-default">
				<div class="panel-body">
					<div class="row">
						<div class="col-sm-6 col-md-3">
							<p><strong>IP de Caja (Ticketera):</strong></p>
						</div>
						<div class="col-sm-6 col-md-4">
							<input type="text" class="form-control" value="<?= $ipServer;?>" id="txtIpServidor">
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6 col-md-3">
							<p><strong>Clave para abrir gaveta automática:</strong></p>
						</div>
						<div class="col-sm-6 col-md-4">
							<input type="text" class="form-control" value="" id="txtIpServidor">
						</div>
					</div>
					
					<p><strong>Inventario</strong></p>
			<?php $sql = mysqli_query($conection,"SELECT `inventarioActivo` FROM `configuraciones` WHERE 1");
				$row = mysqli_fetch_array($sql, MYSQLI_ASSOC);
				$activo= $row['inventarioActivo'];
				if($activo=='1'){ $activSi='checked';$activNo=''; }else{ $activSi='';$activNo='checked'; }
			?>
					<div class="row">
						<div class="col-sm-12 col-md-6"><p>Habilitar la opción para realizar un inventario el inventario</p></div>
						<div class="col-sm-12 col-md-6"><form action=""><label class="radio-inline">
						  <input type="radio" id="rdbActivarInventario" name="inlineRadioOptions" id="inlineRadio1" value="option1" <?php echo $activSi; ?>> Activo
						</label>
						<label class="radio-inline">
						  <input type="radio" id="rdbDesactivarInventario" name="inlineRadioOptions" id="inlineRadio2" value="option2" <?php echo $activNo; ?>> Desactivado
						</label></form></div>
					</div>
				</div>
			</div>

			<h2 class="purple-text text-lighten-1">Configuración de usuarios</h2>
			<div class="panel panel-default">
				<div class="panel-body">
				<button class="btn btn-azul btn-outline " id="btnAgregarNuevoUsuario"><i class="icofont icofont-ui-add"></i> Agregar nuevo usuario</button>
				<ul class="nav nav-tabs" id="ulTabs">
					<li role="presentation" class="active"><a href="#tabUsuarios" aria-controls="home" role="tab" data-toggle="tab">Usuarios con privilegios</a></li>
					<li role="presentation"><a href="#tabInvitados" aria-controls="home" role="tab" data-toggle="tab">Invitados</a></li>
				</ul>
				<div class="tab-content">
					<div role="tabpanel" class="tab-pane fade in active" id="tabUsuarios">
						<h4 style="margin: 30px 0;">Usuarios con privilegios</h4>
						<table class='table'>
							<!-- <caption>Listado de todos los usuarios registrados activos</caption> -->
							<thead>
							<tr>
							<th>N°</th> <th>Nombres y apellidos</th> <th>Nivel</th> <th>Activo</th></tr>
							</thead>
							<tbody>
						<?php
						$i=0;
						$sentencia = mysqli_query($esclavo,"SELECT `idUsuario`, `usuNombres`, `usuApellido`,	`usuPoder`, `usuActivo`, faceMiniatura FROM `usuario` where usuPoder <>7 and  usuActivo =1 order by usuNombres asc");
						while($ussers= mysqli_fetch_array($sentencia, MYSQLI_ASSOC)){ ?>
							<tr data-id="<?= $ussers['idUsuario'];?>">  <th><?= $i+1; ?></th>
							<td class='mayuscula' > <img src="<?= $ussers['faceMiniatura']; ?>" alt="" width="60" height="auto"> <span class="spanNombreFace"> <?= $ussers['usuNombres'];?></span> <span class="spanApellidoFace"><?= $ussers['usuApellido']; ?></span></td>
							<td> 
							<select class="form-control sltNivelUser" data-power='<?= $ussers['usuPoder'];?>'>
								<?php $sqlNivel= mysqli_query($cadena, 'select * from poder where podActivo = 1');
								while($nivell= mysqli_fetch_array($sqlNivel, MYSQLI_ASSOC)){
									?>
									<option value="<?= $nivell['idPoder']; ?>" <?php if($nivell['idPoder']==$ussers['usuPoder']){echo 'selected';} ?>><?= $nivell['Descripcion']; ?></option>
									<?
								}
								?>
							</select>
							
							</td>
							<td>
							
							<select name="" id="" class="form-control sltActivo">
								<option value="1" <?php if($ussers['usuActivo']==1 ){echo 'selected';}?> >Activo</option>
								<option value="0" <?php if($ussers['usuActivo']==0 ){echo 'selected';}?> >Deshabilitado</option>
							</select>
							
							</td>
							<td><button class="btn btn-infocat btn-outline miToolTip btnEditarUsuario" data-id="<?= $ussers['idUsuario'];?>" title="Editar usuario" ><i class="icofont icofont-pencil-alt-2"></i></button></td>
							</tr>
						<? $i++; }

						
						?>
							</tbody>
							</table>
					</div>
					<div role="tabpanel" class="tab-pane" id="tabInvitados">
						<h4 style="margin: 30px 0;">Invitados que desean ingresar al sistema</h4>
						<table class='table'>
							<!-- <caption>Listado de todos los usuarios registrados activos</caption> -->
							<thead>
							<tr>
							<th>N°</th> <th>Nombres y apellidos</th> <th>Nivel</th> <th>Activo</th></tr>
							</thead>
							<tbody>
						<?php
						$i=0;
						$sentenciaInv = mysqli_query($esclavo,"SELECT `idUsuario`, `usuNombres`, `usuApellido`,	`usuPoder`, `usuActivo`, faceMiniatura FROM `usuario` where usuPoder =7 and  usuActivo =1 order by usuNombres asc");
						while($ussersInv= mysqli_fetch_array($sentenciaInv, MYSQLI_ASSOC)){ ?>
							<tr data-id="<?= $ussersInv['idUsuario'];?>">  <th><?= $i+1; ?></th> 
							<td class='mayuscula' > <img src="<?= $ussers['faceMiniatura']; ?>" alt="" width="60" height="auto">  <span class="spanNombreFace"><?= $ussersInv['usuNombres'];?></span> <span class="spanApellidoFace"><?= $ussersInv['usuApellido']; ?></span></td>
							<td> 
							<select class="form-control sltNivelUser" data-power='<?= $ussers['usuPoder'];?>'>
								<?php $sqlNivel= mysqli_query($cadena, 'select * from poder where podActivo = 1');
								while($nivell= mysqli_fetch_array($sqlNivel, MYSQLI_ASSOC)){
									?>
									<option value="<?= $nivell['idPoder']; ?>" <?php if($nivell['idPoder']==$ussers['usuPoder']){echo 'selected';} ?>><?= $nivell['Descripcion']; ?></option>
									<?
								}
								?>
							</select>
							
							</td>
							<td>
							
							<select name="" id="" class="form-control sltActivo">
								<option value="1" <?php if($ussersInv['usuActivo']==1 ){echo 'selected';}?> >Activo</option>
								<option value="0" <?php if($ussersInv['usuActivo']==0 ){echo 'selected';}?> >Deshabilitado</option>
							</select>
							
							</td>
							<td><button class="btn btn-infocat btn-outline miToolTip btnEditarUsuario" data-id="<?= $ussers['idUsuario'];?>" title="Editar usuario" ><i class="icofont icofont-pencil-alt-2"></i></button></td>
							</tr>
						<? $i++; }

						
						?>
							</tbody>
							</table>
					</div>
				</div>
				
				</div>
			</div>

				
			<!-- Fin de contenido principal -->
		</div> <!-- col-lg-12 contenedorDeslizable -->
    </div><!-- row noselect -->
    </div> <!-- container-fluid -->
</div><!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->

<!-- Modal para agregar producto nuevo a la BD -->
<div class="modal fade" id="modalAddUserBD" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
<div class="modal-dialog modal-sm" role="document">
	<div class="modal-content">
		<div class="modal-header-infocat">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			<h4 class="modal-title" id="myModalLabel"><i class="icofont icofont-help-robot"></i> Agregar nuevo usuario</h4>
		</div>
		<div class="modal-body">
			<div class="container-fluid">
			<div class="row">
				<label for="">Apellidos:</label> <input type="text" class="form-control text-center mayuscula" id="txtModalApellidUser">
				<label for="">Nombres:</label>
				<input type="text" class="form-control text-center mayuscula" id="txtModalNombUser">
				<!-- <label for="">D.N.I.:</label>
				<input type="text" class="form-control text-center mayuscula" id="txtModalDniUser"> -->
				<label for="">Nick: </label> <span id="spanRespDuplicado"></span>
				<input type="text" class="form-control text-center" id="txtModalNickUser">
				<label for="">Contraseña.:</label>
				<input type="text" class="form-control text-center" id="txtModalPassUser">
				<label for="">Nivel:</label>
				<div  id="divSelectNivelListNew">
					<select class="selectpicker mayuscula" title="Nivel de usuario..."  data-width="100%" data-live-search="true">
						<?php require 'php/listarNivelesOption.php'; ?>
					</select>
				</div>
			</div>
			</div>
			<label class="red-text text-darken-1 labelError hidden" for=""><i class="icofont icofont-animal-squirrel"></i> Lo siento! <span class=mensaje></span></label>
		</div>
		
		<div class="modal-footer">
			<button class="btn btn-infocat btn-outline" id="btnGuardarAddUser"><i class="icofont icofont-save"></i> Guardar</button>
		</div>
	</div>
</div>
</div>

<?php if( $_COOKIE['ckPower']==1): ?>
<!-- Modal para: cambiar datos basicos de usuario -->
<div class='modal fade' id="modalEditarUsuario" tabindex='-1' role='dialog' aria-hidden='true'>
	<div class='modal-dialog modal-sm' >
	<div class='modal-content '>
		<div class='modal-header-primary'>
			<button type='button' class='close' data-dismiss='modal' aria-label='Close' ><span aria-hidden='true'>&times;</span></button>
			<h4 class='modal-tittle'> Editar datos de usuario</h4>
		</div>
		<div class='modal-body'>
			<label for="">Nombres:</label>
			<input type="text" class="form-control" id="txtFaceNombre">
			<label for="">Apellidos:</label>
			<input type="text" class="form-control" id="txtFaceApellidos">
		</div>
		<div class='modal-footer'>
			<button type='button' class='btn btn-primary btn-outline' id="btnActualizarFaceDatos"><i class="icofont icofont-refresh"></i> Actualizar datos</button>
		</div>
		</div>
	</div>
</div>
<?php endif; ?>



<?php include 'footer.php'; ?>
<?php include 'php/modals.php'; ?>
<?php include 'php/existeCookie.php'; ?>

<?php if ( isset($_COOKIE['ckidUsuario']) && $_COOKIE['ckPower']=='1'  ){?>

<script>
$.interesGlobal=4;
datosUsuario();

$(document).ready(function(){
	$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
	$('.sandbox-container input').datepicker({language: "es", autoclose: true, todayBtn: "linked"}); //para activar las fechas
	$('.miToolTip').tooltip();
	// $.each( $('.sltNivelUser') , function(i, objeto){
	// 	$(objeto).val($(objeto).attr('data-power'))
	// });
	//$('#tabInvitados .sltNivelUser').selectpicker('val', 7 ).selectpicker('refresh');
	$('#tabInvitados .sltNivelUser').val(7);
});
$('#ulTabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})
$('#rdbActivarInventario').click(function () {
	$.ajax({url: 'php/activarInventario.php', type: 'POST'}).done(function (resp) { console.log(resp);
		if(resp=='1'){
			$('#spanBien').text('Se activó el inventario.');
			$('.modal-GuardadoCorrecto').modal('show');
		}else{
			$('#spanMalo').text('Hubo un error actualizando.');
			$('.modal-GuardadoError').modal('show');
		}
	});
});
$('#rdbDesactivarInventario').click(function () {
	$.ajax({url: 'php/desactivarInventario.php', type: 'POST'}).done(function (resp) { console.log(resp);
		if(resp=='1'){
			$('#spanBien').text('Se activó el inventario.');
			$('.modal-GuardadoCorrecto').modal('show');
		}else{
			$('#spanMalo').text('Hubo un error actualizando.');
			$('.modal-GuardadoError').modal('show');
		}
	});
});
$('.sltNivelUser').change(function() {
	var padre= $(this).parent().parent();
	var poder= $(this).val();
	
	$.ajax({url: 'php/actualizarPowerUser.php', type: 'POST', data: { 
		poder: poder,
		idUser: padre.attr('data-id')
	}}).done(function(resp) {
		console.log(resp)
	});
});
$('.sltActivo').change(function() {
	var padre= $(this).parent().parent();
	var estado= $(this).val();
	
	$.ajax({url: 'php/actualizarEstadoUser.php', type: 'POST', data: { 
		estado: estado,
		idUser: padre.attr('data-id')
	}}).done(function(resp) {
		console.log(resp)
	});
});
$('#btnAgregarNuevoUsuario').click(function() {
	$('#modalAddUserBD').modal('show');
});
$('#txtModalNickUser').focusout(function () {
	$.ajax({url: 'php/contarNickRepetidos.php', type: 'POST', data: { texto:$('#txtModalNickUser').val()  }}).done(function(resp) {
		console.log(resp)
		$('#spanRespDuplicado').html(resp);
	});
});
$('#btnGuardarAddUser').click(function () {
	var idNivel=$('#divSelectNivelListNew').find('li.selected a').attr('data-tokens');// console.log(idNivel)
	if($('#txtModalApellidUser').val()==''){$('.modal-addUserBD .labelError').removeClass('hidden').find('.mensaje').text('Debe ingresar los apellidos.');}
	else if($('#txtModalNombUser').val()==''){$('.modal-addUserBD .labelError').removeClass('hidden').find('.mensaje').text('Debe ingresar los nombres.');}
	/*else if($('#txtModalDniUser').val()==''){$('.modal-addUserBD .labelError').removeClass('hidden').find('.mensaje').text('Debe ingresar un Dni.');}*/
	else if($('#txtModalNickUser').val()==''){$('.modal-addUserBD .labelError').removeClass('hidden').find('.mensaje').text('Debe ingresar un nick para iniciar sesión.');}
	else if($('#txtModalPassUser').val()==''){$('.modal-addUserBD .labelError').removeClass('hidden').find('.mensaje').text('Debe ingresar una contraseña.');}
	else if(idNivel === null || idNivel === undefined  ){ $('.modal-addUserBD .labelError').removeClass('hidden').find('.mensaje').text('Debe selecionar un nivel.');}
	else{
		$('.modal-addUserBD .labelError').addClass('hidden');
		$.ajax({url:'php/insertarUsuario.php', type:'POST', data:{nombres:$('#txtModalNombUser').val(), apellidos:$('#txtModalApellidUser').val(), nick: $('#txtModalNickUser').val(), pass: $('#txtModalPassUser').val(), poder: idNivel }}).done(function (resp) { console.log(resp)
			if(resp>0){ location.reload();/* window.location.href = 'usuarios.php'; */}
		});
	}
});
$('.btnEditarUsuario').click(function() {
	var padre = $(this).parent().parent();
	$('#txtFaceNombre').val( padre.find('.spanNombreFace').text() );
	$('#txtFaceApellidos').val( padre.find('.spanApellidoFace').text() );
	$('#btnActualizarFaceDatos').attr('data-id', $(this).attr('data-id'));
	$('#btnActualizarFaceDatos').attr('data-power', padre.find('.sltNivelUser').attr('data-power'));

	$('#modalEditarUsuario').modal('show');
});
$('#btnActualizarFaceDatos').click(function() {
	if( $('#txtFaceNombre').val()!='' && $('#txtFaceApellidos').val()!='' ){
		$.ajax({url: 'php/updateUserDatosConPass.php', type: 'POST', data: { nombre: $('#txtFaceNombre').val(), apellido: $('#txtFaceApellidos').val(), 
		nick: '', pass:'', poder: $('#btnActualizarFaceDatos').attr('data-power'), sucursal: 1, idUser: $('#btnActualizarFaceDatos').attr('data-id') }}).done(function(resp) {
			console.log(resp)
		});
	}
	$('#modalEditarUsuario').modal('hide');
});

$('#txtIpServidor').keypress(function (e) { 
	if(e.keyCode == 13){ 
		$.ajax({url: 'php/updateIpCaja.php', type: 'POST', data: { ipServer : $('#txtIpServidor').val() }}).done(function(resp) {
			console.log(resp)
		});
	}
});

</script>
<?php } ?>
</body>

</html>