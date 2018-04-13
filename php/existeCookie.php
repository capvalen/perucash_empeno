<?php 
if(!isset($_COOKIE['ckidUsuario'])) {
?>
<script>
	$('.modal-iniciarSesion').modal('show');
</script>
<?php 
}
?>