<?
header('Content-Type: text/html; charset=utf8');
date_default_timezone_set('America/Lima');
include 'conkarl.php';

$sql="SELECT * FROM `prestamo_cuotas`
where idPrestamo= {$_POST['idPre']} and idTipoPrestamo in (79, 33) and cuotFechaPago<=curdate() 
order by cuotFechaPago asc;";
//echo $sql;
$dinero= floatval($_POST['dinero']);
$debe=0;

if($log=$conection->query($sql)){
   
   while($row = mysqli_fetch_array($log, MYSQLI_ASSOC)){
      $debe = $debe + floatval($row['cuotCuota']);
   }
   mysqli_data_seek($row, 0);
   echo $debe;

   $moraFuera = number_format($_POST['moraOrigen']-$_POST['cuantoPerdona'],2);

   if( $_POST['perdonaMora'] ){
      //Cancelar una parte de Mora reducida por post
      $sqlCuotaVerif= "INSERT INTO `tickets`(`idTicket`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`) VALUES
      (null,0,84,now(),{$_POST['cuantoPerdona']},'<a href=creditos.php={$_POST['idPre']}>CR-{$_POST['idPre']}</a> <span>Mora Exonerada: S/ {$moraFuera}</span>',1,{$_COOKIE['ckidUsuario']},0);";
      $esclavo->query($sqlCuotaVerif);
   }else{
      //Cancelar toda la mora.
      $sqlCuotaVerif= "INSERT INTO `tickets`(`idTicket`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`) VALUES
      (null,0,83,now(),{$_POST['cuantoPerdona']},'<a href=creditos.php={$_POST['idPre']}>CR-{$_POST['idPre']}</a>',1,{$_COOKIE['ckidUsuario']},0);";
      $esclavo->query($sqlCuotaVerif);
   }
   
   /*
   if($debe == $dinero ){
      // cambiar estado pagado # 81
      $sqlCuotaVerif= "INSERT INTO `tickets`(`idTicket`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`) VALUES
      (null,0,81,now(),$dinero,'<a href=creditos.php={$_POST['idPre']}>CR-{$_POST['idPre']}</a>',1,{$_COOKIE['ckidUsuario']},0);";
      $sqlPagar="UPDATE `prestamo_cuotas` SET
      `cuotFechaCancelacion` = now(),
      `cuotPago` = $dinero,
      `idTipoPrestamo` = 81
      where idPrestamo = {$_POST['idPre']} and idTipoPrestamo in (79, 33) and cuotFechaPago<=curdate();";
      $esclavo->query($sqlCuotaVerif);
      if($cadena->query($sqlPagar)) { } // echo 'reto cumplido completo'; 
      
   }else if( $dinero < $debe ){
      // cambiar estado semi pago #33
      $sqlCuotaVerif= "INSERT INTO `tickets`(`idTicket`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`) VALUES
      (null,0,81,now(),$dinero,'<a href=creditos.php={$_POST['idPre']}>CR-{$_POST['idPre']}</a>',1,{$_COOKIE['ckidUsuario']},0);";
      $sqlPagar="UPDATE `prestamo_cuotas` SET
      `cuotFechaCancelacion` = now(),
      `cuotPago` = $dinero,
      `idTipoPrestamo` = 33
      where idPrestamo = {$_POST['idPre']} and idTipoPrestamo in (79, 33) and cuotFechaPago<=curdate();";
      //echo $sqlPagar;
      $esclavo->query($sqlCuotaVerif);
      if($cadena->query($sqlPagar)){ } // echo 'reto cumplido parcial'; 
      
   }
	
   //Verificar si ya pagó todo
   $sqlCuot="SELECT count(idCUota) as `restanCuotas` FROM `prestamo_cuotas`
   where idPrestamo =  {$_POST['idPre']}
   and idTipoPrestamo in (33, 79);";

   $resultadoCuot=$cadena->query($sqlCuot);
   $rowCuot=$resultadoCuot->fetch_assoc();

   if($rowCuot['restanCuotas']>0){
      //echo 'faltan, no hacer nada';
   }else{
      $sqlPres="UPDATE `prestamo` SET 
      preIdEstado = 82
      where `idPrestamo`={$_POST['idPre']}";
      $resultadoPres=$cadena->query($sqlPres);
   } 
   echo true;*/
}else{
	echo "hubo un error";
}
?>