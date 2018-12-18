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
      $sqlPagar="UPDATE `prestamo_cuotas` SET
      `cuotFechaCancelacion` = now(),
      `cuotPago` = $dinero,
      `idTipoPrestamo` = 81
      where idPrestamo = {$_POST['idPre']} and idTipoPrestamo in (79, 33) and cuotFechaPago<=curdate();";
      if($cadena->query($sqlPagar)) { echo 'reto cumplido completo';}
      
   }else if( $dinero <= $debe ){
      // cambiar estado semi pago #33
      $sqlPagar="UPDATE `prestamo_cuotas` SET
      `cuotFechaCancelacion` = now(),
      `cuotPago` = $dinero,
      `idTipoPrestamo` = 33
      where idPrestamo = {$_POST['idPre']} and idTipoPrestamo in (79, 33) and cuotFechaPago<=curdate();";
      //echo $sqlPagar;
      if($cadena->query($sqlPagar)){ echo 'reto cumplido parcial';}
      
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
}else{
	echo "hubo un error";
}
?>