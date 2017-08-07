<?php

session_start();
require 'php/conkarl.php';
if(isset($_SESSION['Atiende'])){?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Demo</title>

	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>
<body>
<h1>Hola <?php echo $_SESSION['Atiende']; ?> <small>Vienes de <span id="spanViene"></span></small></h1>
<button class="btn btn-primary" id="btnClick">Click aca</button>
	<p>dasd &shy; &shy; &shy; &shy; &shy; &shy; &shy; &shy; &shy; &shy; &shy; &shy; &shy; &shy; mmm </p>
</body>
</html>
<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
<script>
$(document).ready(function () {
	$.JsonUsuario=[];
	$.JsonUsuario.push({'sucursal':'<?php echo $_SESSION['Sucursal']; ?>', 'nombre': '<?php echo $_SESSION['Atiende']; ?>', 'idusuario': '<?php echo $_SESSION['idUsuario']; ?>',
		'idoficina': '<?php echo $_SESSION['oficina']; ?>'
	 });
});



$('#btnClick').click(function () {
	$('#spanViene').text( '<?php echo $_SESSION['Sucursal']; ?>');
	console.log($.JsonUsuario);
});
</script>
<?php	
} else{
	echo '<script> window.location="php/desconectar.php"; </script>';
}
?>