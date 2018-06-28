 <!-- <select name="" id="" class="form-control" > -->
 <?php 
include 'conkarl.php';
$sql= "select * from cuadre";
$llamadoSQL = $conection->query($sql);
$resultado = $llamadoSQL->fetch_assoc();
var_dump($resultado);
  ?>
 <!-- </select> -->