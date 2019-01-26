<?
header('Content-Type: text/html; charset=utf8');
include 'conkarl.php';
$idProd = $_POST['idProd'];

$sql="SELECT round(p.preCapital,2) as preCapital, p.desFechaContarInteres,datediff( now(), desFechaContarInteres ) as diferenciaDias, preInteres FROM `prestamo_producto` p
where idProducto={$idProd};";

$resultado=$cadena->query($sql);
$row=$resultado->fetch_assoc();

$base = $row['preCapital'];
$dias = $row['diferenciaDias'];
$interes = $row['preInteres'];
$mora = 0;

if($dias>=35){ //Cargar mora
   if( $base >=0 && $base<=200 ){      
      echo 'entre 200';
   }else if( $base >200 && $base<=1000 ){
      echo 'entre 1000';
   }else if( $base >1000 && $base<=3000 ){
      echo 'entre 2000';
   }else if( $base >3000  ){
      echo 'entre 3000';
   }
}else{
   echo 'menos 35';
}
?>