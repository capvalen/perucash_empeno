<?php 

if(!isset($_COOKIE['ckidUsuario'])) {
?>
<script>
	//$('.modal-iniciarSesion').modal('show');
	window.location.href = 'index.php';
</script>
<?php 
}
?>