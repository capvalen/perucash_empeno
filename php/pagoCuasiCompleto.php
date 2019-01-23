<?
header('Content-Type: text/html; charset=utf8');
date_default_timezone_set('America/Lima');
include 'conkarl.php';

$sql="SELECT * FROM `prestamo_cuotas`
where idPrestamo= {$_POST['idPre']} and idTipoPrestamo in (79, 33) and cuotFechaPago<=curdate() 
order by cuotFechaPago asc;";
//echo $sql;
$dinero= floatval($_POST['dinero']);

if($log=$conection->query($sql)){
   
   $row = mysqli_fetch_array($log, MYSQLI_ASSOC);
   $debe = floatval($row['cuotCuota']);
   
   if($debe == $dinero ){
      // cambiar estado pagado # 81
      $sqlCuotaVerif= "INSERT INTO `tickets`(`idTicket`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `subCuota`) VALUES
      (null,0,81,now(),$dinero,'<a href=creditos.php?credito={$_POST['idPre']}>CR-{$_POST['idPre']}</a>',1,{$_COOKIE['ckidUsuario']},0, {$row['idCuota']});";
      $sqlPagar="UPDATE `prestamo_cuotas` SET
      `cuotFechaCancelacion` = now(),
      `cuotPago` = $dinero,
      `idTipoPrestamo` = 81
      where idPrestamo = {$_POST['idPre']} and idTipoPrestamo in (79, 33) and cuotFechaPago<=curdate();";
      $esclavo->query($sqlCuotaVerif);
      if($cadena->query($sqlPagar)) { /* echo 'reto cumplido completo'; */}
      
   }else if( $dinero <= $debe ){
      // cambiar estado semi pago #33
      $sqlCuotaVerif= "INSERT INTO `tickets`(`idTicket`, `idProducto`, `idTipoProceso`, `cajaFecha`, `cajaValor`, `cajaObservacion`, `cajaActivo`, `idUsuario`, `idAprueba`, `subCuota`) VALUES
      (null,0,81,now(),$dinero,'<a href=creditos.php?credito={$_POST['idPre']}>CR-{$_POST['idPre']}</a>',1,{$_COOKIE['ckidUsuario']},0, {$row['idCuota']});";
      $sqlPagar="UPDATE `prestamo_cuotas` SET
      `cuotFechaCancelacion` = now(),
      `cuotPago` = $dinero,
      `idTipoPrestamo` = 33
      where idPrestamo = {$_POST['idPre']} and idTipoPrestamo in (79, 33) and cuotFechaPago<=curdate();";
      //echo $sqlPagar;
      $esclavo->query($sqlCuotaVerif);
      if($cadena->query($sqlPagar)){ /* echo 'reto cumplido parcial'; */}
      
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
   echo true;
}else{
	echo "hubo un error";
}
?>