<?php session_start();
date_default_timezone_set('America/Lima');

if( !isset($_SESSION['access_token'])){header('Location: index.php');}else{
   if( $_COOKIE['ckPower']=="7"){ header('Location: bienvenido.php'); } }

require("php/conkarl.php");
require "php/variablesGlobales.php";
?>
<!DOCTYPE html>
<html lang="es">
<?php 
if( isset($_GET['idProducto'])){

   $sqlComrpa = $conection->query("SELECT escompra from producto where idProducto=".$_GET['idProducto']);
   $resCompra = $sqlComrpa->fetch_row();
   $esCompra=$resCompra[0];

   if($esCompra =='0'){
      $sql = mysqli_query($conection,"SELECT p.*, concat (c.cliApellidos, ' ' , c.cliNombres) as cliNombres, tp.tipoDescripcion, tp.tipColorMaterial, prodActivo, esCompra, u.usuNombres, date_format(pre.desFechaContarInteres, '%Y-%m-%d') as desFechaContarInteres, c.cliDni, tpr.tipopDescripcion, pe.presFechaCongelacion
      FROM producto p inner join Cliente c on c.idCliente=p.idCliente inner join prestamo_producto pre on pre.idProducto=p.idProducto inner join tipoProceso tp on tp.idTipoProceso=pre.presidTipoProceso
      left join prestamo pe on pe.idPrestamo = pre.idPrestamo
      inner join usuario u on u.idUsuario=p.idUsuario
      inner join tipoProducto tpr on tpr.idTipoProducto = p.idTipoProducto
      WHERE p.idProducto=".$_GET['idProducto'].";");
      $rowProducto = mysqli_fetch_array($sql, MYSQLI_ASSOC);
   }else{//Cuando es una compra
      $sql = mysqli_query($conection,"SELECT p.*, tp.tipoDescripcion, tp.tipColorMaterial, u.usuNombres, tpr.tipopDescripcion 
      FROM producto p inner join tipoProceso tp on tp.idTipoProceso=p.prodQueEstado
      inner join usuario u on u.idUsuario=p.idUsuario
      inner join tipoProducto tpr on tpr.idTipoProducto = p.idTipoProducto
      WHERE p.idProducto=".$_GET['idProducto'].";");
      $rowProducto = mysqli_fetch_array($sql, MYSQLI_ASSOC);
   }
   $sqlComrpa->close();
   $filas = mysqli_num_rows($sql);
}
/*Días para tener en cuenta*/
$iniGracia=0;
$finGracia=28;
$iniProrroga=29;
$finProrroga=57;
$iniRemate=58;
$finRemate=90;
$iniRecupero=91;
$finRecupero=160;

$cochera=0;
$adelantos=0;

$fecha1=$rowProducto['desFechaContarInteres'];
$cuenta= new DateTime($fecha1); ;
$hoy= new DateTime("now");
$diasLimite=  $cuenta->diff($hoy);
$limite=$diasLimite->days;

?>

<head>
   <title>Productos: PeruCash</title>
   <?php include "header.php"; ?>
   <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
</head>

<body>
<style>
.paPrestamo{
   margin: 10px 0;
   padding: 15px;
   border: 1px solid #e3e3e3;
   border-radius: 6px;
   cursor: pointer;
}
.paPrestamo:hover{
   background-color: #f5f5f5;
   transition: all 0.6s ease-in-out;
}
.h3Nombres{margin-top: 0px;}
.divDatosProducto p{font-size: 13px;transition: all 0.4s ease-in-out; /* cursor:default; */ color: #546e7a;}
/* .divDatosProducto p:hover{font-size: 16px; transition: all 0.4s ease-in-out; color:#2979ff; } */
.divImagen img{border-radius: 7px;}
.divBotonesAccion{margin: 15px 0;}
.tab-pane li{list-style: none;}
.tab-pane li{margin:5px 0;text-indent: -.7em;}
.tab-pane li::before {
   content: "• ";
   color: #ab47bc;
}
.contenedorDatosCliente a{
   color: #ab47bc;
}
#tabAdvertencias li{width: 85%;}
.mensaje{    float: left;    margin-bottom: 10px;
  width: 85%;
  border-radius: 5px;
  padding: 5px;
  display: flex;
  background: #a35bb4; color: white;box-shadow: 3px 3px 2px rgba(0, 0, 0, 0.13);
  /* background: whitesmoke; */}
.mensaje:before {
  width: 0;
  height: 0;
  content: "";
  top: -5px;
  left: -14px;
  position: relative;
  border-style: solid;
  border-width: 0 13px 13px 0;
  border-color: transparent #ab47bc transparent transparent;
  /*border-color: transparent whitesmoke transparent transparent;*/
}
.textoMensaje{ padding-left: 30px }
.mensaje small{color: #f7f7f7;/* color: #6b6b6b; */}
.rowFotos{margin: 0 auto;}
.divFotoGestion{border: dashed 2px #cecece;
   border-radius: 5px;
/*   width: 22%; */ min-height: 150px;
  margin: 0 10px; padding: 15px 10px;}
#tabMovEstados li, #tabAdvertencias li, #tabIntereses li{list-style-type: none;padding-bottom: 10px;}
.divFotoGestion i{font-size: 10rem; color: #cecece;}
.iEliminarFoto i{font-size: 20px;}
.iEliminarFoto i:hover{color:#d50000;cursor: pointer;}
.libreSubida span i{font-size: 16px; color: #337ab7;}
.upload-btn-wrapper {
  position: relative;
  overflow: hidden;
  display: inline-block;
}
.upload-btn-wrapper:hover{cursor: pointer;}
.upload-btn-wrapper input[type=file] {
  font-size: 100px;
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
}
.divSelectTipoInteres ul li{padding-bottom: 5px;}
.divSelectTipoInteres ul li strong, #spanInteresTipo{color: #5cb85c; padding-bottom: 5px; }
.dropdown-menu li::before{ content: "";}
.dropdown-menu li a {padding: 0px 32px;}
.dropdown-menu li:hover{background: #f3f3f3;}
.dropdown-menu>.active>a:hover{ background-color: #9658d0; }
.open>.dropdown-menu>li>a{color: #a35bb4;padding: 7px 45px;}
.open>.dropdown-menu{margin-top: -10px;}
.badge{background-color: #9658d0; font-size: 10px;}
.divImagen li{list-style-type: none;}
#txtMontoTicketIntereses, #txtNuevoCapital{font-size: 26px;}
#txtMontoTicketIntereses::placeholder, #txtNuevoCapital::placeholder{font-size: 12px;}
.open>.dropdown-menu>.active>a {
    color: #fefbff;
    padding: 7px 45px;
}
#modalPagoAutomatico h3, .modal-pagoMaestro h3{ margin-top: 0px;}
#modalPagoAutomatico .close, .modal-pagoMaestro .close { color: #6f5e5e; }
#modalPagoAutomatico .close:hover, .modal-pagoMaestro .close:hover{color: #ea1010;opacity: 0.7;}
#modalPagoAutomatico .modal-content, .modal-pagoMaestro .modal-content{box-shadow: 0 5px 15px rgba(0,0,0,.5);}
#modalPagoAutomatico .form-control{margin-bottom: 10px;}
/* .pEnLinea{display: inline-block;} */
#modalPagoAutomatico .checkbox>label, .modal-pagoMaestro .checkbox>label {/* color: inherit; */color: #a35bb4; font-size: 13px; font-weight: 700;}
</style>
<div class="" id="wrapper">

   <!-- Sidebar -->
   <?php include 'menu-wrapper.php' ?>
<!-- Page Content -->
<div id="page-content-wrapper">
<div class="container-fluid">				 

   <div class="row continer-fluid">
      <div class="col-xs-12 contenedorDeslizable contenedorDatosCliente ">
      <!-- Empieza a meter contenido 2.1 -->
      <? if($filas >0):?>
         <div class="container row" style="margin-bottom: 20px;">
         
            <div class="divBotonesEdicion" style="margin-bottom: 10px">
               <div class="btn-group">
                 <button type="button" class="btn btn-infocat btn-outline dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> 
                  <i class="icofont icofont-settings"></i> Acciones <span class="caret"></span>
                 </button>
                 <ul class="dropdown-menu">
                  <li><a href="#!" id="liAGestionrFotos"><i class="icofont icofont-shopping-cart"></i> Gestionar fotos</a></li>
                  <li><a href="#!" id="liHojaControl"><i class="icofont icofont-print"></i> Hoja de control</a></li>
                  <li><a href="php/printDeclaracionJurada.php?idProducto=<?= $_GET['idProducto']?>" id="" target="_blank"><i class="icofont icofont-paper"></i> Declaración jurada </a></li>
                  <li><a href="#!" id="liEditDescription"><i class="icofont icofont-exchange"></i> Edición de descripción</a></li>
                  <? if( $_COOKIE['ckPower']==='1' && $esCompra==0){ ?>
                  <li><a href="#!" id="liCongelar"><i class="icofont icofont-ice-cream"></i> Congelar crédito</a></li>
                  <? } ?>
                  <? if( in_array($_COOKIE['ckPower'], $soloCaja ) && $esCompra==0 && $existeCaja>0 ){ //&& $hayCaja>0 ?>
                  <li><a href="#!" id="btnDesembolsoAutomatico"><i class="icofont icofont-brand-tata-indicom"></i> Desembolso</a></li>
                  <li><a href="#!" id="btnPagoAutomatico"><i class="icofont icofont-chart-histogram"></i> Pago Automático </a></li>
                  <? } ?>
                  <? if( in_array($_COOKIE['ckPower'], $soloCaja ) && $esCompra==0 ){ ?>
                  <li><a href="#!" id="btnProyeccion"><i class="icofont icofont-telescope"></i> Proyectar a futuro </a></li>
                  <? } ?>
                  <? if( in_array($_COOKIE['ckPower'], $soloCaja ) && $esCompra==1  && $existeCaja>0 ){ //&& $hayCaja>0 ?>
                     <li><a href="#!" id="btnPagoVentav2"><i class="icofont icofont-flag"></i> Venta de producto</a></li>
                  <? } ?>
                  <? if( in_array($_COOKIE['ckPower'], $soloCaja ) && $limite>=36 && $esCompra==0  && $existeCaja>0 ){ //&& $hayCaja>0 ?>
                  <li><a href="#!" id="btnPagoRematev2"><i class="icofont icofont-flag"></i> Remate de producto</a></li>
                  <? } ?>
                 </ul>
               </div>
            </div>
            <div class="col-xs-12 col-sm-5 col-md-3 divImagen">
               <?php 
               $directorio = "images/productos/".$_GET['idProducto'];
               if(file_exists($directorio)){$ficheros  = scandir($directorio, 1);
               $cantImg=0; ?>
               <div class="flexslider">
                  <ul class="slides">
                     <?php 
                        foreach($ficheros as $archivo){
                           $cantImg++;/*".$directorio."/".$archivo."*/
                           if (preg_match("/jpeg/", $archivo) || preg_match("/jpg/", $archivo) || preg_match("/png/", $archivo)){
                                /*echo $directorio."/".$archivo;*/
                                echo "<li><a href='".$directorio."/".$archivo."' data-lightbox='image-1'><img src='".$directorio."/".$archivo."' class='img-responsive' ></a></li>";
                            }
                        }/*<img src="images/imgBlanca.png" class="img-responsive" alt="">*/
                     ?>
                  </ul>
               </div>
               <?php if($cantImg==2){ echo '<li><a href="images/imgBlanca.png" data-lightbox="image-1"><img src="images/imgBlanca.png" class="img-responsive" ></a></li>';}
               }else{echo '<li><a href="images/imgBlanca.png" data-lightbox="image-1"><img src="images/imgBlanca.png" class="img-responsive" ></a></li>';} ?>
            </div>
            <div class="col-xs-12 col-sm-5 divDatosProducto">
               <h2 class="mayuscula purple-text text-lighten-1"><span id="spanClase"><?= $rowProducto['tipopDescripcion']; ?></span>: <span class="h2Producto"><?php echo  $rowProducto['prodNombre']; ?></span> <span class="badge"><?php echo $rowProducto['tipoDescripcion'] ?></span> </h2>
               <h4 class="purple-text text-lighten-1">Código de producto: #<span><?php echo $rowProducto['idProducto']; ?></span></h4>
                     
               <p class="mayuscula <?php if($esCompra=='1'){ echo "hidden";}?>">Dueño: <span class="hidden" id="spanIdDueno"><? if($esCompra =='1'){echo '00000000';}else{echo $rowProducto['cliDni'];} ?></span>
                  <?php if($esCompra=='0'){ ?>
                  <a href="cliente.php?idCliente=<?php echo $rowProducto['idCliente']; ?>" class="spanDueno" data-dni="<?= $rowProducto['cliDni']; ?>" data-propietario="<?= $rowProducto['idCliente']; ?>"><?php echo $rowProducto['cliNombres']; ?></a>
                  <?php }else{ ?>
                     <a href=#" class="spanDueno" data-dni="00000000" data-propietario="compra Sin Dni"><?php echo "compra Sin Dni"; ?></a>
                  <?php } ?>
               </p>
            
               <p class="hidden">Registrado: <span><?php echo $rowProducto['prodFechaRegistro']; ?></span></p>
               <p style="display:inline-block">Préstamo inicial: </p><h4 style="display:inline-block; padding-left:5px; margin-top: 0px;margin-bottom: 0px;"> S/ <span id="spanPresInicial"><?= str_replace(',', '', number_format($rowProducto['prodMontoEntregado'],2)); ?></span>
               </h4>
               <p>Cantidad: <span id="spanCantp"><?php echo $rowProducto['prodCantidad']; ?> </span><?php echo $rowProducto['prodCantidad']==='1' ? 'Und.' : 'Unds.' ?></p>
            <?php if($esCompra=='0'){ ?>
               <p>Adquisición: <strong><span>Por empeño</span></strong></p>
            <?php }else { ?>
               <p>Adquisición: <strong><span>Por compra</span></strong></p>
            <?php } ?>
               <p class="hidden">Estado del producto: <strong class="<?php echo $rowProducto['tipColorMaterial']; ?> estadoProducto"><?php echo $rowProducto['tipoDescripcion'] ?></strong></p>
               <p>Estado del sub-préstamo: <strong class="<?php echo $rowProducto['tipColorMaterial']; ?>"><?php echo $rowProducto['prodActivo']==='0' ? 'No vigente' : 'Vigente' ?></strong></p>

               <?php 
               $sqlInvent = mysqli_query($conection,"SELECT `inventarioActivo` FROM `configuraciones` WHERE 1");
               $rowInvent = mysqli_fetch_array($sqlInvent, MYSQLI_ASSOC);
               $activoInvent= $rowInvent['inventarioActivo'];
            if ( ($_COOKIE['ckPower']=='1' || $_COOKIE['ckPower']=='2' || $_COOKIE['ckPower']=='4' || $_COOKIE['ckPower']=='4' ) && $activoInvent=='1'){
            ?>
               <div class="row">
                  <p><strong>Inventario:</strong></p>
               <div class="col-xs-12 col-sm-6">
                  <button class="btn btn-azul btn-outline btn-lg btn-block" id="btnInventariarPositivo"><i class="icofont icofont-chart-flow-alt-2"></i> Inventariar</button>
               </div>
               <div class="col-xs-12 col-sm-6">
                  <button class="btn btn-danger btn-outline btn-lg btn-block" id="btnInventariarNegativo"><i class="icofont icofont-bubble-down"></i> No existe</button>
               </div>
               </div>
            <?php
            }
         ?>
            <div class="row">
         <?php 
         

         
         $sql= "SELECT idTicket FROM `tickets` where cajaActivo in (0,1) and idProducto = {$_GET['idProducto']}"; // si tiene un ticket activo
         $consultaDepos = $conection->prepare($sql);
         $consultaDepos ->execute();
         $resultadoDepos = $consultaDepos->get_result();
         $numLineaDeposs=$resultadoDepos->num_rows;
         if ($numLineaDeposs >0){
            while($rowDepos = $resultadoDepos->fetch_array(MYSQLI_ASSOC)){
               echo '<h3 class="purple-text text-lighten-1">Ticket asignado #'.$rowDepos['idTicket'].'</h3>';
            }
         }else{
            if($existeCaja == 0){
               //echo '<h3 class="red-text text-darken-1" >No hay ninguna caja aperturada</h3>';
               echo '<div class="alert alert-morado container-fluid" role="alert">
               <div class="col-xs-4 col-sm-2 col-md-3">
                  <img src="images/ghost.png" alt="img-responsive" width="100%">
               </div>
               <div class="col-xs-8">
                  <strong>Alerta</strong> <p>No se encuentra ninguna caja aperturada.</p>
               </div>
            </div>';
            //fin de if de fallse
            }else{

            if( ($_COOKIE['ckPower']==1 || $_COOKIE['ckPower']==2 || $_COOKIE['ckPower']==5) && ($limite>=37) && $esCompra==0 ){ ?>
            <!-- <p style="margin-top: 10px; " class="hidden"><strong>Generar ticket:</strong></p>
            <button class="btn btn-morado btn-lg btn-block btn-outline hidden"><i class="icofont icofont-mathematical-alt-1"></i> Rematar producto</button>-->
         <?php } ?>

         <?php if($esCompra==1 && $rowProducto['prodActivo']==1 && ($_COOKIE['ckPower']==1 || $_COOKIE['ckPower']==2 || $_COOKIE['ckPower']==5) ){?>
           <!--  <p style="margin-top: 10px;"><strong>Generar ticket:</strong></p>
            <button class="btn btn-morado btn-lg btn-block btn-outline" id="btnLlamarTicketVenta" ><i class="icofont icofont-people"></i> Ticket de venta</button> -->
         <?php }
            if( $_COOKIE['ckPower']==1 || $_COOKIE['ckPower']==8 || $_COOKIE['ckPower']==4 ){ //zona de pago especial ?>
               <!-- <button class="btn btn-infocat btn-outline btn-block btn-lg hidden" id="">Automático</button>

               <p style="margin-top: 10px;" class="hidden" ><strong>Pago especial</strong></p>
               <button class="btn btn-morado btn-lg btn-block btn-outline hidden" id="btnLlamarTicketMaestro"><i class="icofont icofont-mathematical-alt-1"></i> Insertar pago maestro</button> -->
            <?php }
            if($esCompra==0 && $rowProducto['prodActivo']==1 ){
            
               
               /* if( $limite >= 50 && $limite <= 59 ){ //zona  venta de remate ?>
               <p style="margin-top: 10px;" ><strong>Zona prórroga</strong></p>
                  <button class="btn btn-morado btn-lg btn-block btn-outline" id="btnLlamarTicketIntereses"><i class="icofont icofont-mathematical-alt-1"></i> Ticket de prórroga</button>
                  <p style="margin-top: 10px;" ><strong>Zona remate</strong> Vender a 200%</p>
                  <button class="btn btn-morado btn-lg btn-block btn-outline" id="btnLlamarTicket"><i class="icofont icofont-mathematical-alt-1"></i> Ticket de remate</button>
               <?php }
               if( $limite >= 60 && $limite <= 69 ){ //zona  venta de remate ?>
               <p style="margin-top: 10px;" ><strong>Zona prórroga</strong></p>
                  <button class="btn btn-morado btn-lg btn-block btn-outline" id="btnLlamarTicketIntereses"><i class="icofont icofont-mathematical-alt-1"></i> Ticket de prórroga</button>
                  <p style="margin-top: 10px;" ><strong>Zona remate</strong> Vender a 150%</p>
                  <button class="btn btn-morado btn-lg btn-block btn-outline" id="btnLlamarTicket"><i class="icofont icofont-mathematical-alt-1"></i> Ticket de remate</button>
               <?php }
               if( $limite >= 70 && $limite <= 79 ){ //zona  venta de remate ?>
               <p style="margin-top: 10px;" ><strong>Zona prórroga</strong></p>
                  <button class="btn btn-morado btn-lg btn-block btn-outline" id="btnLlamarTicketIntereses"><i class="icofont icofont-mathematical-alt-1"></i> Ticket de prórroga</button>
                  <p style="margin-top: 10px;" ><strong>Zona remate</strong> Vender a 80%</p>
                  <button class="btn btn-morado btn-lg btn-block btn-outline" id="btnLlamarTicket"><i class="icofont icofont-mathematical-alt-1"></i> Ticket de remate</button>
               <?php }
               if( $limite >= 80 ){ //zona de venta de remate ?>
                  <p style="margin-top: 10px;" ><strong>Zona remate administrativo</strong> Proponer una solución. Hueso</p>
                  <button class="btn btn-morado btn-lg btn-block btn-outline" id="btnLlamarTicket"><i class="icofont icofont-mathematical-alt-1"></i> Ticket de remate admin.</button>
               <?php } */
               }
            }//fin de if de requireonce comproibar cajahoy
         }
      

         
         $consultaDepos->fetch();
         $consultaDepos->close();
         
         $sqlContador="call contarObservaciones({$_GET['idProducto']})";
         $consultaContar = $cadena->prepare($sqlContador);
         $consultaContar->execute();
         $resultadoContar = $consultaContar->get_result();
         $rowContar = $resultadoContar->fetch_array(MYSQLI_ASSOC);
         
         $consultaContar->fetch();
         $consultaContar->close();

          ?>
            </div>
            </div>
         
         </div>
         <div class="container row">
            <ul class="nav nav-tabs">
            <li class="active"><a href="#tabIntereses" data-toggle="tab">Intereses</a></li>
            <li><a href="#tabMovEstados" data-toggle="tab">Estados y movimientos</a></li>
            <li class="hidden"><a href="#tabMovFinancieros" data-toggle="tab">Financiero</a></li>
            <li><a href="#tabAdvertencias" data-toggle="tab">Observaciones y advertencias <span class="badge"><?php echo $rowContar['total']; ?></span></a></li>
            <li><a href="#tabInventario" data-toggle="tab">Almacén e Inventarios</a></li>
            
            </ul>
            <div class="tab-content">
            <!--tab content-->
               <div class="tab-pane fade in active container-fluid" id="tabIntereses">
               <!--Inicio de pestaña interior 01-->
                  <h4 class="purple-text text-lighten-1"><i class="icofont icofont-ui-clip"></i> Sección intereses</h4>
               <div class="row"> <!-- primer row en pestaña -->
               <div class="col-xs-12 col-sm-6">
               <?php 
               if($esCompra=='1'){
                  ?>
                  <ul><li>Las compras no generan intereses.</li></ul>
                  <?php
               }else{
                  if($rowProducto['prodActivo']==='0'){
                  ?><ul><li>El producto ya no genera intereses por haber finalizado.</li></ul><?php	
                  }else{
                     if ( $rowProducto['presFechaCongelacion']==''){
                        $sqlBaseInteres="SELECT round(p.preCapital,2) as preCapital, p.desFechaContarInteres,datediff( now(), desFechaContarInteres ) as diferenciaDias, preInteres FROM `prestamo_producto` p where idProducto=".$_GET['idProducto'];
                     }else{
                        $sqlBaseInteres="SELECT round(p.preCapital,2) as preCapital, p.desFechaContarInteres,datediff( '{$rowProducto['presFechaCongelacion']}', desFechaContarInteres ) as diferenciaDias, preInteres FROM `prestamo_producto` p where idProducto=".$_GET['idProducto'];
                     }
                     $sqlIntereses = $conection->query($sqlBaseInteres);
                     $rowInteres = $sqlIntereses->fetch_assoc();
                     $sqlAdela="SELECT `sumarAdelantos`(".$_GET['idProducto'].");";
                     $resultadoAdela=$esclavo->query($sqlAdela);
                     $rowAdela=$resultadoAdela->fetch_row();
                     $adelantos = $rowAdela[0];
                     $gastosAdmin=0;
                     ?>
                  <ul>
                     <li>Capital: S/ <span id="spanCapitalDefault"><?php echo $rowInteres['preCapital'];?></span> <?php if( in_array($_COOKIE['ckPower'], $soloDios) ): ?> <button class="btn btn-morado btn-outline btn-sinBorde btn-xs" id="btnChangeCapital"><i class="icofont icofont-bag-alt"></i> Cambiar capital</button> <? endif;?> </li>
                  
                  
                     <li>Tiempo de interés: <span><?php  if($rowInteres['diferenciaDias']=='0'){echo '1 día.';} else if($rowInteres['diferenciaDias']=='1'){echo '1 día.';}else{ echo  $rowInteres['diferenciaDias'].' días';} if($rowInteres['diferenciaDias']=='0'){$rowInteres['diferenciaDias']+=1;} ?> </span> <? if($rowProducto['presFechaCongelacion']<>''): echo '<strong>(	)</strong>'; endif; if( in_array($_COOKIE['ckPower'], $soloDios)){?> <button class="btn btn-morado btn-outline btn-sinBorde btn-xs" id="btnChangeFechaInt"><i class="icofont icofont-paper"></i> Cambiar fecha interés</button> <? } ?></li>
                     <?php if( $adelantos >0 ){ ?>
                     <li>Adelantos: S/ <span><?= number_format($adelantos,2); ?></span></li>
                     <?php } ?>
                  <?php
                  
                  if($rowInteres['diferenciaDias']>=1 && $rowInteres['diferenciaDias']<=7 ){ ?>
                     <li>Interés: <span><?php echo $rowInteres['preInteres']; ?>% = S/ <?php $interesJson= number_format(round($rowInteres['preCapital']*$rowInteres['preInteres']/100,1,PHP_ROUND_HALF_UP),2); echo $interesJson; ?></span></li>
                     <li>Razón del cálculo: <span><strong>Interés simple</strong> (del día 1 al 7)</span></li>
                  <?php }else if( $rowInteres['diferenciaDias']>=8 && $rowInteres['diferenciaDias']<=35 ){ 
                        $interesDiario= ($rowInteres['preInteres']/100)/7; ?>
                     <li>Interés: <span><?php echo $rowInteres['preInteres']; ?>% semanal = S/. <?php $interesJson= round($rowInteres['preCapital']*$interesDiario*$rowInteres['diferenciaDias'],1,PHP_ROUND_HALF_UP); echo number_format($interesJson, 2).' ('.number_format($interesDiario*$rowInteres['diferenciaDias']*100,2).'%)'; ?></span></li>
                     <li>Razón del cálculo: <span><strong>Interés Diario</strong> (del día 8 al 35)</span></li>
                  <?php }else {
                     $_GET['inicio']=floatval($rowInteres['preCapital']);

                     if($rowInteres['diferenciaDias']<=200){
                        $_GET['numhoy']=$rowInteres['diferenciaDias'];
                     }else{
                        $_GET['numhoy']=200;
                     }
                     $_GET['interes']=$rowInteres['preInteres'];
                     //$resultado=(require_once "php/calculoInteresAcumuladoDeValorv3.php");
                     // var_dump($resultado);
                     //$interesJson= $resultado[0]['soloInteres'];
                     //$razonInteres= $resultado[0]['intDiarioHoy']*100;
                     $razonInteres= $rowInteres['diferenciaDias']*($rowInteres['preInteres']/7);
                     $interesJson= round(($rowInteres['preCapital']*$razonInteres)/100,1,PHP_ROUND_HALF_UP );
                     ?>
                     <li>Interés <strong>simple</strong>: <span><?php echo $rowInteres['preInteres']; ?>% = S/. <?php  echo number_format($interesJson,2).' ('.number_format($razonInteres,2).'%)'; ?></span></li>
                     <li>Razón del cálculo: <span><strong>Interés simple diario</strong> (más de 29 días).</span></li>
                  <?php if($rowInteres['diferenciaDias']>=36 ){
                     if ($rowInteres['preCapital']<=200) { $gastosAdmin=5; }
                     else if ($rowInteres['preCapital']>200 && $rowInteres['preCapital']<=1000) { $gastosAdmin=10; }
                     else if ($rowInteres['preCapital']>1001 && $rowInteres['preCapital']<=3000) { $gastosAdmin=20; }
                     else if ($rowInteres['preCapital']>3000 ) { $gastosAdmin=30; }
                  ?>
                     <li>Gastos admnistrativos: <span>S/ <?= number_format($gastosAdmin,2); ?></span></li>
                  <?php }
                  } //fin de else antes de $_GET['inicio']
                  if($rowProducto['idTipoProducto']=="1" ):
                     $cochera=2*$rowInteres['diferenciaDias'];
                     echo "<li>Cochera: <span>S/ ". number_format($cochera,2) ."</span> (S/ 2.00 por día)</li>";
                  endif;
                  if( $rowProducto['idTipoProducto']=="11" ):
                     $cochera=2*$rowInteres['diferenciaDias'];
                     echo "<li>Cochera: <span>S/ ". number_format($cochera,2) ."</span> (S/ 2.00 por día)</li>";
                  endif;
                  if( $rowProducto['idTipoProducto']=="42" ):
                     $cochera=1*$rowInteres['diferenciaDias'];
                     echo "<li>Cochera: <span>S/ ". number_format($cochera,2) ."</span> (S/ 1.00 por día)</li>";
                  endif; //fin de 
                  ?>
                     <li>Deuda total para hoy: <span><strong>S/ <?php echo number_format($interesJson+$rowInteres['preCapital']+$gastosAdmin+$cochera,2);  ?></strong></span></li>
                     
                  </ul>
                  <?php 
               
                  } //Fin de else despues producto ya no genera intereses
               } ?>
               </div>
               <div class="col-xs-12 col-sm-6">
                  
                  <div class="conjuntoMensajes">
                  <?php
                  $sqlMensajes=mysqli_query($conection, "SELECT a.*, u.usuNombres, ( case when tp.tipoDescripcion is NULL then 'Mensaje simple' else tp.tipoDescripcion end ) as 'tipoDescripcion'
                     FROM `avisos` a
                     left join tipoProceso tp on tp.idTipoProceso = a.tipoComentario
                     inner join usuario u on u.idUsuario= a.idUsuario where idProducto=".$_GET['idProducto']." order by a.idAviso desc;");
                  while($rowMensajes = mysqli_fetch_array($sqlMensajes, MYSQLI_ASSOC)){ ?>
                     <div class="mensaje"><div class="texto"><p><strong><?php echo $rowMensajes['usuNombres']; ?></strong> <small><i class="icofont icofont-clock-time"></i> <span class="spanFechaFormat"><?php echo $rowMensajes['aviFechaAutomatica']; ?></span></small></p> <p class="textoMensaje mayuscula"><?php echo $rowMensajes['aviMensaje']; ?></p> </div></div>
                  <?php } ?>
                  </div>
                  <?php if($rowProducto['prodObservaciones']<>''){ ?>
                     <div class="mensaje"><div class="texto"><p><strong class="mayuscula"><?php echo $rowProducto['usuNombres']; ?>:</strong></p> <p class="textoMensaje"><?php echo $rowProducto['prodObservaciones']; ?></p> </div></div>
                  <?php } ?>
               </div>
               
               </div> <!-- fin de primer row  -->
               <!--Fin de pestaña interior 01-->
               </div>
               <div class="tab-pane fade container-fluid" id="tabMovEstados">
               <!--Inicio de pestaña interior 02-->
               
                  <h4 class="purple-text text-lighten-1"><i class="icofont icofont-ui-clip"></i> Sección de estados &amp; Movimientos</h4>
                  <div class="table-responsive">
                  <table class="table table-hover" id="tablita">
                     <thead><tr>
                        <th data-sort="string">Responsable <i class="icofont icofont-expand-alt"></i></th><th>Fecha / Hora <i class="icofont icofont-expand-alt"></i></th><th data-sort="string">Acción <i class="icofont icofont-expand-alt"></i></th><th data-sort="string">Tipo Pago <i class="icofont icofont-expand-alt"></i></th> <th>Intervalos</th> <th data-sort="float">Montos S/<i class="icofont icofont-expand-alt"></i></th><th>Observaciones</th><th>@</th>
                     </tr></thead>
                     <tbody>
                     <tr class="hidden">
                        <td class="spanQuienRegistra mayuscula"><?php echo $rowProducto['usuNombres']; ?></td><td class="spanFechaFormat"><?php echo $rowProducto['prodFechaInicial']; ?> </td><td>Registro de producto</td> <td></td> <td></td> <td><span class='spanCantv3'><?php echo number_format($rowProducto['prodMontoEntregado'],2) ?></span></td><td></td><td> <button class='btn btn-sm btn-azul btn-outline btnImprimirTicket' data-boton=<?php echo $rowProducto['idTipoProceso']; ?>><i class='icofont icofont-print'></i></button></td>
                     </tr>
                     <?php $i=0;
                     $fechaAnt= new DateTime($rowProducto['prodFechaInicial']);  $fechaAnt1= new DateTime($fechaAnt->format('Y-m-d'));
                     $sqlEstado=mysqli_query($conection, "SELECT ca.*, tp.tipoDescripcion, u.usuNombres, m.moneDescripcion FROM `caja` ca
                        inner join tipoProceso tp on tp.idTipoProceso= ca.idTipoProceso
                        inner join usuario u on u.idUsuario=ca.idUsuario
                        inner join moneda m on m.idMoneda = ca.cajaMoneda
                        where idProducto=".$_GET['idProducto']." and cajaActivo =1 order by cajaFecha asc;");
                     while($rowEstados = mysqli_fetch_array($sqlEstado, MYSQLI_ASSOC)){ ?>
                     <tr>
                        <td data-id="<?php echo $rowEstados['idCaja']; ?>" data-activo="<?php echo $rowEstados['cajaActivo']; ?>" class="spanQuienRegistra mayuscula"><?php echo $rowEstados['usuNombres']; ?></td><td class="spanFechaFormat"><?php echo $rowEstados['cajaFecha']; ?></td><td class="tpIdDescripcion" data-id="<?php echo $rowEstados['idTipoProceso']; ?>" ><?php echo $rowEstados['tipoDescripcion']; ?></td><td class="tdMoneda"><?= $rowEstados['moneDescripcion']; ?></td>
                        <? if($i>=0):  $fechaAct=new DateTime($rowEstados['cajaFecha']); $fechaAct1= new DateTime($fechaAct->format('Y-m-d')); $calculo = $fechaAct1->diff($fechaAnt1)->format('%a'); ?><td><i class="icofont icofont-arrow-up"></i> <? if($calculo =='0'){echo 'Mismo día';} if($calculo =='1'){echo '1 día';} if($calculo >'1'){echo $calculo.' días';} ?></td><? else: ?><td></td> <? endif;?>
                        <td><span class='spanCantv3'><?php echo number_format($rowEstados['cajaValor'],2) ?></span></td>
                        <td class="tdObservacion mayuscula"><?php echo $rowEstados['cajaObservacion']; if( $rowEstados['idTipoProceso']=="21" ){echo " ~".$rowEstados['cajCantidad']. " Und.";} ?></td> <td> <span class="sr-only fechaPagov3"><?= $rowEstados['cajaFecha'];  ?></span> <?php if($_COOKIE['ckPower']==1 || $_COOKIE['ckPower']==8): ?> <button class='btn btn-sm btn-success btn-outline btnEditarCajaMaestra'><i class='icofont icofont-edit'></i></button> <?php endif; ?> <button class='btn btn-sm btn-azul btn-outline btnImprimirTicket' data-boton=<?php echo $rowEstados['idTipoProceso']; ?>><i class='icofont icofont-print'></i></button></td>
                     </tr>
                     <?php 
                     $i++; $fechaAnt= new DateTime($rowEstados['cajaFecha']);  $fechaAnt1= new DateTime($fechaAnt->format('Y-m-d'));
                     } ?>
                     </tbody>
                  </table>
                  </div>
                  <!-- <ul>
                  <li>Registrado por <span class="spanQuienRegistra"><?php echo $rowProducto['usuNombres']; ?></span>: <span class="spanFechaFormat"><?php echo $rowProducto['prodFechaInicial']; ?></span> <button class="btn btn-sm btn-azul btn-outline btnImprimirTicket" data-boton="<?php echo 0;/*$rowProducto['idTipoProceso']*/ ?>"><i class="icofont icofont-print"></i></button></li>
                  
                     echo "<li>{$rowEstados['tipoDescripcion']} por  <span class='spanQuienRegistra'>{$rowEstados['usuNombres']}</span>, con S/. <span class='spanCantv3'>".number_format($rowEstados['cajaValor'],2)."</span>: <span class='spanFechaFormat'>{$rowEstados['cajaFecha']}</span> <button class='btn btn-sm btn-azul btn-outline btnImprimirTicket' data-boton={$rowEstados['idTipoProceso']}><i class='icofont icofont-print'></i></button></li>";
                  
               </ul> -->
               <!--Fin de pestaña interior 02-->
               </div>
               <div class="tab-pane fade container-fluid" id="tabMovFinancieros">
               <!--Inicio de pestaña interior 03-->
                  <h4 class="purple-text text-lighten-1"><i class="icofont icofont-ui-clip"></i> Sección Financiera</h4>
                  <ul>
                     <!-- <li>Capital o desembolso	13/01/2018 03:37 p.m.	S/. 700.00	bmanrique</li> -->
                  </ul>
               <!--Fin de pestaña interior 03-->
               </div>
               <div class="tab-pane fade container-fluid" id="tabAdvertencias">
               <!--Inicio de pestaña interior 04-->
                  <h4 class="purple-text text-lighten-1"><i class="icofont icofont-ui-clip"></i> Sección Observaciones y Advertencias antes de rematar</h4>
                  
                  <div class="dejarMensaje row"><div class="col-sm-4 hidden">
                  <select name="" id="sltTipoMensaje" class="form-control ">
                     <option value="0" selected>Mensaje simple</option>
               <?php 
               $sqlTPMensajes = "SELECT * FROM `tipoProceso` where idTipoProceso in(62, 63, 64, 65, 66, 67, 73) order by tipoDescripcion asc;";
               $sqlLlamadoTPMensajes =  $conection->query($sqlTPMensajes);
               while($rowTPMensajes = $sqlLlamadoTPMensajes->fetch_assoc()){ ?>
                  <option value="<?php echo $rowTPMensajes['idTipoProceso']; ?>"><?php echo $rowTPMensajes['tipoDescripcion']; ?></option>
               <?php }
               /*$consultaTPMensajes->fetch();
               $consultaTPMensajes->close();*/
               ?>
                  </select>
                  </div>
                  <div class="col-sm-8">
                     <input type="text" class="form-control mayuscula" id="txtDejarMensaje" placeholder="¿Qué mensaje dejó para el cliente?" autocomplete="off" style="width: 85%; display: inline-block;"> <button class="btn btn-default" id="btnDejarMensaje" style="margin-top: -3px;color: #ab47bc;"><i class="icofont icofont-location-arrow"></i></button>
                  </div>
                  </div>
                  
               <!--Fin de pestaña interior 04-->
               </div>

               <div class="tab-pane fade container-fluid" id="tabInventario">
               <!-- Inicio de pestaña interior 05 -->
               <h4 class="purple-text text-lighten-1"><i class="icofont icofont-ui-clip"></i> Sección Inventarios</h4>
               <ul>
                  <?php 
               $sqlInventario="call listarInventarioPorId( {$_GET['idProducto']} );";
               $llamadoInventarios = $conection->query($sqlInventario);
               $numRow = $llamadoInventarios->num_rows;
               if($numRow>0){
               while($rowInventa = $llamadoInventarios->fetch_assoc()){
                  echo "<li>Inventariado el <span class='spanFechaFormat'>{$rowInventa['invFechaInventario']}</span>: <strong style='color: #ab47bc;'>«{$rowInventa['caso']}»</strong> <span class='mayuscula'>$comentario</span>. <em><strong>{$rowInventa['usuNombres']}</strong></em></li>";
               }
               }else{
                  echo '<li>No se encontraron inventarios todavía en almacén con éste producto.</li>';
               }
               
               $sqlMovimientosAlmacen= "call listarMovimientosAlmacen (".$_GET['idProducto'].");";
               //echo $sqlMovimientosAlmacen;

               $consultaMovimiento = $cadena->prepare($sqlMovimientosAlmacen);
               $consultaMovimiento ->execute();
               $resultadoMovimiento = $consultaMovimiento->get_result();
               $numLineaMovimiento=$resultadoMovimiento->num_rows;
               if($numLineaMovimiento>0){
                  while($rowMovim = $resultadoMovimiento->fetch_assoc()){ ?>
                     <li><span class='spanFechaFormat'><?php echo $rowMovim['cubFecha']; ?></span>: <strong style='color: #ab47bc;'>«<?php echo '<span class="mayuscula">'.$rowMovim['estDescripcion'].'</span> '.$rowMovim['pisoDescripcion'].' <span>'.$rowMovim['zonaDescripcion'].'</span>'; ?>»</strong> <span class='mayuscula'><?php echo $rowMovim['cubObservacion']; ?></span>. <em ><strong><?php echo $rowMovim['usuNombres']; ?></strong></em></li>
                  <?php }
               }
               $consultaMovimiento->fetch();
               $consultaMovimiento->close();
               
               
                   ?>
               </ul>
               
               <div class="row">
               <div class="col-xs-12" id="divTipoMovAlmacen">
                  <select class="selectpicker mayuscula hidden" id="sltTipoMovimiento"  data-width="50%" data-live-search="false" data-size="15">
                     <option class="optPiso mayuscula" data-tokens="23">En almacén</option>
                     <option class="optPiso mayuscula" data-tokens="46">Entrada a almacén</option>
                     <option class="optPiso mayuscula" data-tokens="47">Salida de almacén</option>
                  </select>
               </div>
               <div class="col-xs-4" id="divSelectEstante">
                  <select class="selectpicker mayuscula" id="sltEstantes" title="Estante..."  data-width="100%" data-live-search="false" data-size="15">
                     <?php require 'php/listarEstantesOPT.php'; ?>
                  </select>
               </div>
               <div class="col-xs-4" id="divSelectPiso">
                  <select class="selectpicker mayuscula" id="sltPiso" title="Piso..."  data-width="100%" data-live-search="false" data-size="15">
                     <?php require 'php/listarPisosOPT.php'; ?>
                  </select>
               </div>
               <div class="col-xs-4" id="divSelectSeccion">
                  <select class="selectpicker mayuscula" id="sltSeccion" title="Sección..."  data-width="100%" data-live-search="false" data-size="15">
                     <?php require 'php/listarZonasOPT.php'; ?>
                  </select>
                  
               </div>
               <div class="col-xs-12">
                  <input type="text" class="form-control mayuscula" placeholder="Comentario extra" id="txtObservacionCubicaje">
                  <button class="btn btn-morado btn-outline" id="btnGuardarCubicaje"><i class="icofont icofont-box"></i> Guardar cubicaje</button>
               </div>
               </div>
               <!-- Fin de pestaña interior 05 -->
               </div>
            <!-- Fin de tab content -->
               </div>
         </div>
            <? else:
            ?> <h4 class="purple-text text-lighten-1"> <i class="icofont icofont-animal-cat-alt-4"></i> Éste código no pertenece a ningún producto en la Base de datos.</h4>
            
 <?
            endif; //fin de if de $filas >=0 ?>
      </div> <!-- col-lg-12 contenedorDeslizable -->
    </div><!-- row noselect -->
    </div> <!-- container-fluid -->
</div><!-- /#page-content-wrapper -->
</div><!-- /#wrapper -->


<!--Modal Para insertar ticket de venta a BD-->
<div class="modal fade modal-ticketZonaIntereses noselect" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-sm">
      <div class="modal-content">
         <div class="modal-header-success">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-tittle"><i class="icofont icofont-animal-cat-alt-3"></i> Generar ticket</h4>
         </div>
         <div class="modal-body">
            <div class="container-fluid">
               <label for="">Selecciona el tipo de ticket que se generará.</label><br>
               <div class="divSelectTipoInteres">
               <label for="">El cliente tiene pendiente:</label>
                  <ul>
                     <li><strong>Monto inicial:</strong> S/. <span id="spanInteresInicial"><?php echo $rowInteres['preCapital']; ?></span></li>
                     <li><strong>Interés:</strong> S/. <span id="spanInteresInt"><?php echo number_format($interesJson,2); ?></span></li>
                  <? if($gastosAdmin>0): ?>
                     <li><strong>Penalización:</strong> S/. <span id="spanInteresPena"><?php echo number_format($gastosAdmin,2); ?></span></li>
                  <? endif; ?>
                  <? if($cochera>0): ?>
                     <li><strong>Cochera:</strong> S/. <span id="spanCocheraPena"><?php echo number_format($cochera,2); ?></span></li>
                  <? endif;?>
                     <li><strong>Total:</strong> S/. <span id="spanInteresTotal"><?php echo number_format($rowInteres['preCapital']+$interesJson+$gastosAdmin+$cochera,2); ?></span></li>
                  </ul>
               </div>
                  
               
               <label for=""><span class="txtObligatorio">*</span> Monto S/.</label>
               <input type="number" class="form-control input-lg esDecimal text-center" id="txtMontoTicketIntereses" placeholder="S/.">
               <label for="">Comentario extra:</label>
               <input type="text" class="form-control input-lg mayuscula" id="txtRazonTicketIntereses" placeholder="Comentario" autocomplete="off">
               <label for="">El cliente está pagando:</label> <p id="spanInteresTipo">-</p>

            </div>
         </div>
         <div class="modal-footer">
            <div class="divError text-left hidden"><i class="icofont icofont-animal-cat-alt-4"></i> Lo sentimos, <span class="spanError"></span></div>
            <button class="btn btn-success btn-outline" id="btnCrearTicketPagoInteres" ><i class="icofont icofont-chart-flow-alt-2"></i> Crear ticket Interes</button>
      </div>
      </div>
   </div>
</div>

<!--Modal Para insertar ticket para pagar interes -->
<div class="modal fade modal-ticketVenta" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-sm">
      <div class="modal-content">
         <div class="modal-header-success">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-tittle"><i class="icofont icofont-animal-cat-alt-3"></i> Generar ticket</h4>
         </div>
         <div class="modal-body">
            <div class="container-fluid">
               <label for="">Estas generando un ticket.</label><br>
               <label for=""><span class="txtObligatorio">*</span> Monto S/.</label>
               <input type="number" class="form-control input-lg esDecimal" id="txtMontoTicketVenta" placeholder="S/.">
               <label for="">Comentario extra:</label>
               <input type="text" class="form-control input-lg mayuscula" id="txtRazonTicketVenta" placeholder="Comentario" autocomplete="off">

            </div>
         </div>
         <div class="modal-footer">
            <div class="divError text-left hidden"><i class="icofont icofont-animal-cat-alt-4"></i> Lo sentimos, <span class="spanError"></span></div>
            <button class="btn btn-success btn-outline" id="btnCrearTicketVenta" ><i class="icofont icofont-chart-flow-alt-2"></i> Crear ticket</button>
      </div>
      </div>
   </div>
</div>

<!--Modal Para insertar inventario a BD-->
<div class="modal fade modal-inventarioPositivo" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-sm">
      <div class="modal-content">
         <div class="modal-header-primary">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-tittle"><i class="icofont icofont-animal-cat-alt-3"></i> Inventariar</h4>
         </div>
         <div class="modal-body">
            <div class="container-fluid">
               <label for="">¿Tienes algún comentario extra?</label>
               <input type="text" class="form-control input-lg mayuscula" id="txtObsInvPositivo">
            </div>
         </div>
         <div class="modal-footer">
            <button class="btn btn-azul btn-outline" data-dismiss="modal" id="btnInsertPositivo" ><i class="icofont icofont-chart-flow-alt-2"></i> Hacer inventario</button>
      </div>
      </div>
   </div>
</div>

<!--Modal Para insertar inventario a BD-->
<div class="modal fade modal-inventarioNegativo" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-sm">
      <div class="modal-content">
         <div class="modal-header-danger">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-tittle"><i class="icofont icofont-animal-cat-alt-3"></i> No existe en almacén</h4>
         </div>
         <div class="modal-body">
            <div class="container-fluid">
               <label for="">¿Tienes algún comentario por qué no está en almacén?</label>
               <input type="text" class="form-control input-lg mayuscula" id="txtObsInvNegativo">
            </div>
         </div>
         <div class="modal-footer">
            <button class="btn btn-danger btn-outline" data-dismiss="modal" id="btnInsertNegativo" ><i class="icofont icofont-bubble-down"></i>  No existe en almacén</button>
      </div>
      </div>
   </div>
</div>



<?php if($_COOKIE['ckPower']==1 || $_COOKIE['ckPower']==8 || $_COOKIE['ckPower']==4) { ?>
<!--Modal Para insertar pago maestro -->
<div class="modal fade modal-pagoMaestro" tabindex="-1" role="dialog">
   <div class="modal-dialog">
      <div class="modal-content">
      
         <div class="modal-body">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <div class="container-fluid">
            <div class="row">
               <div class="hidden-xs col-sm-6">
               <img src="images/782a2fc7989fc1d244d2f7604a7d57ff.jpg?v=1.0.1" alt="" class="img-responsive">
               </div>
               <div class="col-xs-12 col-sm-6">
                  <h3 class="modal-tittle" style="color: #a35bb4;" id="h3PagoEspecial">Pago especial</h3>
                  
                  <label for="" class="hidden">Tipo de pago</label>
                  <div id="cmbEstadoPagos" class="hidden">
                  <select class="selectpicker mayuscula" id="cmbPagosOpt" title="Tipos de pago..."  data-width="100%" data-live-search="true" data-size="15">
                     <?php require 'php/detallePagosOPTv3.php'; ?>
                  </select></div>
                  <label for="" class="hidden">Fecha de pago</label>
                  <input id="dtpFechaPago" type="text" class="form-control input-lg text-center hidden" autocomplete="off">
                  <label for="">Método de pago</label>
                  <div id="divCmbMetodoPago">
                     <select class="form-control selectpicker" id="sltMetodopago" title="Métodos..."  data-width="100%" data-live-search="true" data-size="15">
                        <?php include 'php/listarMonedaOPT.php'; ?>
                     </select>
                  </div> <br>
                  <label for="">Cantidad de items vendidos</label>
                  <input type="number" class="form-control input-lg mayuscula text-center" id="txtCantPagos" value="1" max="<?= $rowProducto['prodCantidad']; ?>" autocomplete="off" style="font-size: 20px;">
                  <label for="">Monto</label>
                  <input type="number" class="form-control input-lg mayuscula text-center esMoneda" id="txtMontoPagos" autocomplete="off" style="font-size: 20px;">
                  <label for="">¿Observaciones?</label>
                  <input type="text" class="form-control input-lg mayuscula" id="txtObsPagos" autocomplete="off">
                  <div class="checkbox checkbox-infocat">
                     <input id="chImprTicketAuto2" class="styled" type="checkbox" checked="">
                     <label for="chImprTicketAuto2"><i class="icofont icofont-paper"></i> Imprimir Ticket</label>
                  </div>
                  <div class="divError text-left hidden"><i class="icofont icofont-animal-cat-alt-4"></i> Lo sentimos, <span class="spanError"></span></div>
                  <button class="btn btn-infocat btn-outline btn-block hidden" id="btnInsertRemate" data-dismiss="modal" ><i class="icofont icofont-save"></i> Insertar proceso</button>
                  <button class="btn btn-infocat btn-outline btn-block hidden" id="btnInsertVenta" ><i class="icofont icofont-save"></i> Insertar proceso</button>
                  <button class="btn btn-infocat btn-outline btn-block" id="btnInsertPagoMaestro" ><i class="icofont icofont-save"></i> Insertar proceso</button>
               </div>
            </div>
               
            </div>
         </div>
         
      </div>
   </div>
</div>

<!--Modal Para insertar pago cajamaestra -->
<div class="modal fade modal-cajaMaestra" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-sm">
      <div class="modal-content">
         <div class="modal-header-success">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-tittle"><i class="icofont icofont-animal-cat-alt-3"></i> Modificar pago caja</h4>
         </div>
         <div class="modal-body">
            <div class="container-fluid">
               <p>Rellene cuidadosamente la siguiente información</p>
               <label for="">Tipo de pago</label>
               <div id="cmbEstadoPagos2">
               <select class="selectpicker mayuscula" id="spTipoPago2" title="Tipos de pago..."  data-width="100%" data-live-search="true" data-size="15">
                  <?php require 'php/detallePagosOPT.php'; ?>
               </select></div>
               <label for="">Fecha de pago</label>
               <input id="dtpCajaFechaPago" type="text" class="form-control input-lg text-center" autocomplete="off">
               <label class="hidden" for="">Método de pago</label>
               <div class="hidden" id="divCmbMetodoPago">
                  <select class="form-control selectpicker" id="sltCajaMetodopago" title="Métodos..."  data-width="100%" data-live-search="true" data-size="15">
                     <?php include 'php/listarMonedaOPT.php'; ?>
                  </select>
               </div>
               <label for="">Métodos de pago</label>
               <div id="divCmbMetodoPago2">
                  <select class="form-control selectpicker" id="sltMetodopago2" title="Métodos..."  data-width="100%" data-live-search="true" data-size="15">
                     <?php include 'php/listarMonedaOPT.php'; ?>
                  </select>
               </div> <br>
               <label for="">Monto de pago S/</label>
               <input type="number" class="form-control input-lg mayuscula text-center " id="txtCajaMontoPagos" autocomplete="off" style="font-size: 20px;">
               <label for="">¿Observaciones?</label>
               <input type="text" class="form-control input-lg mayuscula" id="txtCajaObsPagos" autocomplete="off">
               <label for="">¿Activo?</label>
               <select name="" id="sltActivoV2" class="form-control">
                  <option value="0">Inactivo</option>
                  <option value="1">Activo</option>
               </select>
               <div class="divError text-left hidden"><i class="icofont icofont-animal-cat-alt-4"></i> Lo sentimos, <span class="spanError"></span></div>
            </div>
         </div>
         <div class="modal-footer">
            <button class="btn btn-success btn-outline" id="btnUpdateCajaMaestra" ><i class="icofont icofont-bubble-down"></i> Actualizar registro caja</button>
      </div>
      </div>
   </div>
</div>

<!-- Modal para: editar producto-->
<div class='modal fade modalEditarProducto' tabindex='-1' role='dialog' aria-hidden='true'>
   <div class='modal-dialog modal-sm' >
   <div class='modal-content '>
      <div class='modal-header-warning'>
         <button type='button' class='close' data-dismiss='modal' aria-label='Close' ><span aria-hidden='true'>&times;</span></button>
         <h4 class='modal-tittle'><i class="icofont icofont-focus"></i> Editar producto</h4>
      </div>
      <div class='modal-body'>
         <label for="">Nombre producto:</label>
         <input type="text" class='form-control mayuscula' id='txtModProdNombre' autocomplete="off">
         <label for="">Cantidad Unds.:</label>
         <input type="number" class='form-control' id='txtModPresCantidad' value="0" autocomplete="off">
         <label for="">Préstamo inicial S/:</label>
         <input type="number" class='form-control esMoneda' id='txtModPresInicial' value="0" autocomplete="off">
         <label for="">Interés base %</label>
         <input type="number" class='form-control esMoneda' id='txtModPresInteres' value="<?= $rowProducto['prodInteres']; ?>" autocomplete="off">
         <label for="">Cliente</label>
         <div id="divCmbClientes">
            <select class="form-control selectpicker" id="sltClienteMod" title="Clientes..."  data-width="100%" data-live-search="true" data-size="15">
               <?php include 'php/listarClienteOPT.php'; ?>
            </select>
         </div><br>
         <label for="">Estado:</label>
         <select class="form-control" id="sltEstadoMod">
            <option value="1">Vigente</option>
            <option value="0">Finalizado</option>
         </select>
         <div class="divError text-left hidden"><i class="icofont icofont-animal-cat-alt-4"></i> Lo sentimos, <span class="spanError"></span></div> <br>
      </div>
      <div class='modal-footer'>
         <button type='button' class='btn btn-warning btn-outline' id='btnActualizarMod'><i class="icofont icofont-exchange"></i> Actualizar</button>
      </div>
      </div>
   </div>
</div>

<? if( $_COOKIE['ckPower']=='1' ):?>
<!-- Modal para: editar producto-->
<div class='modal fade modalCongelarProducto' tabindex='-1' role='dialog' aria-hidden='true'>
   <div class='modal-dialog modal-sm' >
   <div class='modal-content '>
      <div class='modal-header-danger'>
         <button type='button' class='close' data-dismiss='modal' aria-label='Close' ><span aria-hidden='true'>&times;</span></button>
         <h4 class='modal-tittle'><i class="icofont icofont-focus"></i> Congelar producto</h4>
      </div>
      <div class='modal-body'>
         <label for="">Fecha en que se congelará el producto</label>
         <input type="date" class='form-control mayuscula text-center inputGrande' id='txtFechaCongelar' autocomplete="off">
         <div class="divError text-left hidden"><i class="icofont icofont-animal-cat-alt-4"></i> Lo sentimos, <span class="spanError"></span></div>
      </div>
      <div class='modal-footer'>
         <button type='button' class='btn btn-danger btn-outline' id='btnCongelar'><i class="icofont icofont-ice-cream"></i> Congelar</button>
      </div>
      </div>
   </div>
</div>
<? endif;?>

<!--Modal Para asignar nuevo capital al prestamo-->
<div class="modal animated fadeIn modal-nuevoCapital" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-sm">
      <div class="modal-content">
         <div class="modal-header-morado">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-tittle"><i class="icofont icofont-animal-cat-alt-3"></i> Nuevo capital</h4>
         </div>
         <div class="modal-body">
            <p>Ingrese el nuevo capital</p>
            <input type="number" class="form-control input-lg esMoneda text-center" id="txtNuevoCapital">
         </div>
         <div class="modal-footer">
         <button class="btn btn-morado btn-outline" data-dismiss="modal" id="btnActualizarCapital" ><i class="icofont icofont-check"></i> Actualizar</button>
      </div>
      </div>
   </div>
</div>

<!--Modal Para llamar pago automático-->
<div class="modal animated fadeIn " id="modalPagoAutomatico" tabindex="-1" role="dialog">
   <div class="modal-dialog ">
      <div class="modal-content">
   
         <div class="modal-body container-fluid">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
         
            <div class="row">
               <div class="hidden-xs col-sm-5">
                  <img src="images/1_XQdcsKkE_YPTrrif7rTnnQ.png?v=1.0.1" alt="" class="img-responsive">
               </div>
               <div class="col-xs-12 col-sm-6">
                  <h3 class="text-center" style="color: #a35bb4;">Pago automático</h3>
                  <p class="pEnLinea"  style="color: #a35bb4;"><strong>Método de pago:</strong></p>
                  <div id="divCmbMetodoPago3">
                     <select class="form-control selectpicker" id="sltMetodopago3" title="Métodos..."  data-width="100%" data-live-search="true" data-size="15">
                        <?php include 'php/listarMonedaOPT.php'; ?>
                     </select>
                  </div>
                  <p class="pEnLinea"  style="color: #a35bb4;"><strong>Dinero del cliente:</strong></p>
                  <input type="number" class="form-control input-lg inputGrande text-center esMoneda" autocomplete="nope" lang="en" id="txtAutoDinero" >
                  <div class="checkbox checkbox-infocat checkbox-circle">
                     <input class="chkInterno" id="checkbox1" type="checkbox" class="styled" checked>
                     <label for="checkbox1">Cochera: </label> <small></small>
                  </div>
                  <input type="number" class="form-control input-sm esMoneda inputModif" autocomplete="nope" lang="en" id="txtAutoCochera" readonly>
                  <div class="checkbox checkbox-infocat checkbox-circle">
                     <input class="chkInterno" id="checkbox2" type="checkbox" class="styled" checked>
                     <label for="checkbox2">Gastos administrativos: </label> <small></small>
                  </div>
                  <input type="number" class="form-control input-sm esMoneda inputModif" autocomplete="nope" lang="en" id="txtAutoGastos" readonly>
                  <div class="checkbox checkbox-infocat checkbox-circle">
                     <input class="chkInterno" id="checkbox3" type="checkbox" class="styled" checked>
                     <label for="checkbox3">Intereses: </label> <small></small>
                  </div>
                  <input type="number" class="form-control input-sm esMoneda inputModif" autocomplete="nope" lang="en" id="txtAutoInteres" readonly>
                  <div class="checkbox checkbox-infocat checkbox-circle">
                     <input class="chkInterno" id="checkbox4" type="checkbox" class="styled" checked>
                     <label for="checkbox4">Capital: </label> <small></small>
                  </div>
                  <input type="number" class="form-control input-sm esMoneda inputModif" autocomplete="nope" lang="en" id="txtAutoCapital" readonly>
                  <p class="pEnLinea"  style="color: #a35bb4;"><strong>Observaciones:</strong></p>
                  <input type="text" class="form-control input-lg " autocomplete="nope" id="txtAutoObserv" >
                  <div class="checkbox checkbox-infocat">
                     <input id="chImprTicketAuto" class="styled" type="checkbox" checked="">
                     <label for="chImprTicketAuto"><i class="icofont icofont-paper"></i> Imprimir Ticket</label>
                  </div>
               
                  <button class="btn btn-infocat btn-outline btn-block" data-dismiss="modal" id="btnPagarAutomatico" ><i class="icofont icofont-diskette"></i> Realizar pago</button>
               </div>
            </div>
         </div>
         
      </div>
   </div>
</div>

<?php } //fin de if power ?>

<!--Modal Para proyectar el producto en el futuro -->
<div class="modal animated fadeIn " id="modalProyectarFuturo" tabindex="-1" role="dialog">
   <div class="modal-dialog ">
      <div class="modal-content">
         <div class="modal-body container-fluid">
         <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #f91313;"><span aria-hidden="true">&times;</span></button>
         
         <div class="row">
            <div class="hidden-xs col-sm-5">
               <img src="images/ezgif-4-46d2b60e1163.png" alt="" class="img-responsive">
            </div>
            <div class="col-xs-12 col-sm-6">
               <h3 class="text-center" style="color: #a35bb4;">Proyección a futuro</h3>
               <p>Realiza una proyección para cuando el cliente desee hacer un pago en una fecha futura.</p>
               <div class="input-group">
                  <input type="text" class="form-control text-center" id="txtFechaFutura" placeholder="Fecha" style="height: 34px;">
                  <span class="input-group-btn"> <button class="btn btn-infocat " title="Realiza el cálculo" id="btnProyectarFuturo" type="button"><i class="icofont icofont-robot"></i></button> </span>
               </div>
               <div class="hidden" id="divCalculosFuturos">
                  <hr>
                  <p>Capital: S/ <span id="capitalFuturo"></span></p>
                  <p>Tiempo de interés: <span id="tiempoFuturo"></span> </p>
                  <p>Interés: 4% = S/ <span id="interesFuturo"></span></p>
                  <p>Cochera: S/ <span id="cocheraFuturo"></span></p>
                  <p>Gastos admnistrativos: S/ <span id="gastosFuturo"></span></p>
                  <p><strong>Deuda proyectada: S/ <span id="deudaFuturo"></span></strong></p>
               </div>
            </div>
            </div>
         </div>
         
      </div>
   </div>
</div>


<? if( in_array($_COOKIE['ckPower'], $admis)){ ?>
<!--Modal Para asignar nuevo capital al prestamo-->
<div class="modal animated fadeIn " id="modalUpdateFechaInt" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-sm">
      <div class="modal-content">
         <div class="modal-header-morado">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-tittle"><i class="icofont icofont-animal-cat-alt-3"></i> Nueva fecha</h4>
         </div>
         <div class="modal-body">
            <p>Seleccione una nueva para para cambiar el cálculo de intereses</p>
            <input type="text" class="form-control text-center inputGrande" id="txtNuevoFechaInt">
         </div>
         <div class="modal-footer">
         <button class="btn btn-morado btn-outline" data-dismiss="modal" id="btnActualizarFechaInt" ><i class="icofont icofont-check"></i> Actualizar</button>
      </div>
      </div>
   </div>
</div>
<? } //fin de if solo admis ?>


<!--Modal Para asignar nuevo estado al producto-->
<div class="modal fade modal-asignarEstado" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-sm">
      <div class="modal-content">
         <div class="modal-header-warning">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-tittle"><i class="icofont icofont-animal-cat-alt-3"></i> Asignar un nuevo estado</h4>
         </div>
         <div class="modal-body">
            <div id="cmbEstadoProd">
            <select class="selectpicker mayuscula" title="Nuevo estado..."  data-width="100%" data-live-search="true" data-size="15">
               <?php require 'php/detalleReporteOPT.php'; ?>
            </select></div>
            <input type="text" class="form-control" id="txtComentarioEstado" placeholder="Comentario extra">
         </div>
         <div class="modal-footer">
         <button class="btn btn-warning btn-outline" data-dismiss="modal" id="btnActualizarEstado" ><i class="icofont icofont-check"></i> Actualizar</button>
      </div>
      </div>
   </div>
</div>

<!--Modal Para gestionar fotos-->
<div class="modal fade modal-gestionarFotos" tabindex="-1" role="dialog">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header-primary">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close" ><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-tittle"><i class="icofont icofont-black-board"></i> Gestionar fotos</h4>
         </div>
         <div class="modal-body">
            <div>
               <div class="row rowFotos"> 
            <?php if($cantImg<=2){ ?>
               <!-- <div class="col-xs-6 col-sm-3 divFotoGestion text-center libreSubida" id="foto1"><i class="icofont icofont-cloud-upload"></i></div> -->

            <?php }else{
                  foreach($ficheros as $archivo)
                  {
                     $cantImg++;/*".$directorio."/".$archivo."*/
                     if (preg_match("/jpeg/", $archivo) || preg_match("/jpg/", $archivo) || preg_match("/png/", $archivo)){ ?>
                        <div class='col-xs-6 col-md-3  divFotoGestion' id='foto{$i}'><span class='iEliminarFoto pull-right'><i class='icofont icofont-close'></i></span> <img src='".$directorio."/".$archivo."' class='img-responsive' > </div>
                      <?php }
                  }/*<img src="images/imgBlanca.png" class="img-responsive" alt="">*/
                  } ?>
                  <div class="col-xs-6 col-md-3 divFotoGestion libreSubida text-center" ><i class="icofont icofont-cloud-upload"></i> <div class="upload-btn-wrapper">
                       <button class="btn btn-primary btn-outline"><span><i class="icofont icofont-upload"></i></span> Subir archivo</button>
                       <input type="file" id="txtSubirArchivo" />
                     </div> </div>
               </div>
               
            </div>
         </div>
      </div>
   </div>
</div>


<?php include 'footer.php'; ?>
<script type="text/javascript" src="js/jquery.flexslider.js"></script>
<script type="text/javascript" src="js/lightbox.js"></script>
<script type="text/javascript" src="js/jquery.printPage.js?version=1.0.12"></script>
<script type="text/javascript" src="js/stupidtable.min.js"></script>
<script type="text/javascript" src="js/moment-precise-range.js"></script>
<script type="text/javascript" src="js/bootstrap-material-datetimepicker.js?version=2.0.8"></script>
<?php include 'php/modals.php'; ?>
<?php include 'php/existeCookie.php'; ?>


<!-- Menu Toggle Script -->
<?php if ( isset($_COOKIE['ckidUsuario']) ){?>
<script>
datosUsuario();


$(document).ready(function(){
   moment.locale('es');
   //$('#dtpFechaInicio').val(moment().format('DD/MM/YYYY'));
   $('.sandbox-container input').datepicker({language: "es", autoclose: true, todayBtn: "linked"}); //para activar las fechas
   $('#txtFechaFutura').datepicker({language: "es", autoclose: true, todayHighlight: true, todayBtn: "linked"}).datepicker( 'update', moment().format('DD/MM/YYYY') );
   $('#tablita').stupidtable();
   $.each( $('.spanFechaFormat'), function (i, dato) {
      var nueFecha=moment($(dato).text());
      $(dato).text(nueFecha.format('ddd DD MMMM YYYY hh:mm a'));
      //$(dato).attr('data-sort');//
   });
$('#txtFechaCongelar').val(moment().format('YYYY-MM-DD'));
$('#dtpFechaPago').bootstrapMaterialDatePicker({
   format: 'DD/MM/YYYY h:m a',
   lang: 'es',
   time: true,
   weekStart: 1,
   cancelText : 'Cerrar',
   nowButton : true,
   switchOnClick : true,
   okText: 'Aceptar', nowText: 'Hoy'
});
$('#txtNuevoFechaInt').val(moment().format('DD/MM/YYYY'));
$('#txtNuevoFechaInt').datepicker({
    format: "dd/mm/yyyy",
    language: "es",
    autoclose: true
});
$('#sltEstantes').on('changed.bs.select', function (e) {
   var id= $('#divSelectEstante').find('.selected a').attr('data-tokens'); //console.log( id );
   if(id==1){
      $('#sltPiso').attr('disabled',true).selectpicker('refresh');
      $('#sltSeccion').attr('disabled',true).selectpicker('refresh');
   }else{
      $('#sltPiso').removeAttr('disabled').selectpicker('refresh');
      $('#sltSeccion').removeAttr('disabled').selectpicker('refresh');

      //$('#sltSeccion').find('option[data-tokens=5]').attr('disabled', true);
      $('#sltSeccion').find('option[data-tokens=6]').attr('disabled', true);
      $('#sltSeccion').find('option[data-tokens=7]').attr('disabled', true);

      if(id==4 || id==7){
         $('#sltPiso').find('option[data-tokens=6]').removeAttr('disabled', true);
      }
      if(id==3){
         $('#sltSeccion').find('option[data-tokens=5]').removeAttr('disabled', true);
         $('#sltSeccion').find('option[data-tokens=6]').removeAttr('disabled', true);
         $('#sltSeccion').find('option[data-tokens=7]').removeAttr('disabled', true);
      }
      if(id==8){
         $('#sltSeccion').find('option[data-tokens=5]').removeAttr('disabled', true);
      }
      $('#sltSeccion').selectpicker('refresh');
   }
});
});
/*$(window).load(function() {
    $('.flexslider').flexslider({
    animation: "slide"
   }); 
});*/
$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
   var target = $(e.target).attr("href");
   //console.log(target);
   if(target=='#tabIntereses'){
      //$.queMichiEs='nada'; console.log('tabnada')
   }
   if(target=='#tabMovEstados'){
   }
});
$('#btnDejarMensaje').click(function () {
   if( $('#txtDejarMensaje').val()!==''){
      $.ajax({
         url: 'php/dejarMensaje.php',
         type:'POST',
         data: {
            mensaje: $('#txtDejarMensaje').val(),
            idUser: $.JsonUsuario.idUsuario,
            idProducto: <?php echo $_GET['idProducto']; ?>,
            tipoMensaje: $('#sltTipoMensaje').val()
         }
      }).done(function (resp) { console.log(resp)
         moment.locale('es');
         $('.conjuntoMensajes').prepend(`<div class="mensaje"><div class="texto"><p><strong>${$.JsonUsuario.usunombres}</strong> <small><i class="icofont icofont-clock-time"></i> ${moment().format('LLLL')}</small></p> <p class="textoMensaje mayuscula">${ $('#txtDejarMensaje').val()}</p> </div></div>`);
         $('#txtDejarMensaje').val('');
      });
   }
});
$('#txtDejarMensaje').keyup(function (e) {
   var code = e.which;
   if(code==13 ){	e.preventDefault();
      $('#btnDejarMensaje').click();
   }
});
$('.estadoProducto').click(function () {
   $('.modal-asignarEstado').modal('show');
});
$('#btnActualizarEstado').click(function () {
   var idEstado=$('#cmbEstadoProd').find('li.selected a').attr('data-tokens');
   $.ajax({url: 'php/cambiarEstadoProducto.php', type: 'POST', data:{
      estado: idEstado,
      idProd: <?php echo $_GET['idProducto']; ?>,
      usuario: $.JsonUsuario.usunombres,
      comentario: $('#txtComentarioEstado').val()
   }}).done(function (resp) {
      location.reload();;
   });
});
$('#dtpCajaFechaPago').bootstrapMaterialDatePicker({
   format: 'DD/MM/YYYY hh:mm a',
   lang: 'es',
   shortTime: true,
   weekStart: 1,
   cancelText : 'Cerrar',
   nowButton : true,
   switchOnClick : true,
   okText: 'Aceptar', nowText: 'Hoy'
});
$('.btnImprimirTicket').click(function () {
   var queMonto, queTitulo;
   var queUser = $(this).parent().parent().find('.spanQuienRegistra').text();
   queMonto=$(this).parent().parent().find('.spanCantv3').text();
   var queArticulo =$('.h2Producto').text();
   var queDueno = <?php if($esCompra==1){ echo '`Compra directa`';}else { ?> $('.spanDueno').text() <?php } ?>;
   var queFecha = moment($(this).parent().parent().find('.spanFechaFormat').text(), 'dddd, DD [de] MMMM [de] YYYY h:mm a').format('dddd, DD/MMMM/YYYY h:mm a');
   if(queUser=='' || queUser==' '){
      queUser='Sistema PeruCash';
   }

   switch( $(this).attr('data-boton') ){
      case '0':
      case '28':
         queTitulo='      * Registro de Producto *\nGracias por registrar su producto';
         queMonto= $('#spanPresInicial').text(); 
         $.ajax({url: 'http://127.0.0.1/perucash/printTicketv3.php', type: 'POST', data: {
            codigo: "<?php echo $_GET['idProducto']; ?>",
            titulo: queTitulo,
            fecha: queFecha.replace('a las ', ''),
            cliente: queDueno,
            articulo: queArticulo,
            monto: queMonto,
            usuario: queUser
         }}).done(function (resp) { 	}); break;
         case '1':
         queTitulo='        * Venta de Producto *'; break;
      case '2':
         queTitulo='      * Adelanto de interés *'; break;
      case '3':
         queTitulo='      * Crédito finalizado *'; break;
      case '8':
         queTitulo='       * Retiro de artículo *'; break;
      case '9':
         queTitulo='       * Pago Parcial de Interés *';
         $.ajax({url: 'http://127.0.0.1/perucash/printTicketv3.php', type: 'POST', data: {
            codigo: "<?php echo $_GET['idProducto']; ?>",
            titulo: queTitulo,
            fecha: queFecha.replace('a las ', ''),
            cliente: queDueno,
            articulo: queArticulo,
            monto: queMonto,
            usuario: queUser
         }}).done(function (resp) { 	}); break;
      case '10':
      case '44':
         queTitulo='       * Cancelación de Interés *';
         $.ajax({url: 'http://127.0.0.1/perucash/printTicketv3.php', type: 'POST', data: {
            codigo: "<?php echo $_GET['idProducto']; ?>",
            titulo: queTitulo,
            fecha: queFecha.replace('a las ', ''),
            cliente: queDueno,
            articulo: queArticulo,
            monto: queMonto,
            usuario: queUser
         }}).done(function (resp) { 	}); break;
      case '20':
         queTitulo='       * Remate *';
         $.ajax({url: 'http://127.0.0.1/perucash/printTicketVenta.php', type: 'POST', data: {
            codigo: "<?php echo $_GET['idProducto']; ?>",
            titulo: queTitulo,
            fecha: queFecha.replace('a las ', ''),
            cliente: '-',
            articulo: queArticulo,
            monto: queMonto,
            usuario: queUser
         }}).done(function (resp) { 	}); break;
      case '21':
         queTitulo='       * Venta de producto *';
         $.ajax({url: 'http://127.0.0.1/perucash/printTicketVenta.php', type: 'POST', data: {
            codigo: "<?php echo $_GET['idProducto']; ?>",
            titulo: queTitulo,
            fecha: queFecha.replace('a las ', ''),
            cliente: '-',
            articulo: queArticulo,
            monto: queMonto,
            usuario: queUser
         }}).done(function (resp) { 	}); break;
      case '45':
         queTitulo='     * Amotización al préstamo *';
         $.ajax({url: 'http://127.0.0.1/perucash/printTicketv3.php', type: 'POST', data: {
            codigo: "<?php echo $_GET['idProducto']; ?>",
            titulo: queTitulo,
            fecha: queFecha.replace('a las ', ''),
            cliente: '-',
            articulo: queArticulo,
            monto: queMonto,
            usuario: queUser
         }}).done(function (resp) { 	}); break;
      case '32':
         queTitulo='       * Fin de préstamo *';
         $.ajax({url: 'http://127.0.0.1/perucash/printTicketv3.php', type: 'POST', data: {
            codigo: "<?php echo $_GET['idProducto']; ?>",
            titulo: queTitulo,
            fecha: queFecha.replace('a las ', ''),
            cliente: queDueno,
            articulo: queArticulo,
            monto: queMonto,
            usuario: queUser
         }}).done(function (resp) { 	}); break;
      case '33':
         queTitulo='       * Pago parcial *';
         $.ajax({url: 'http://127.0.0.1/perucash/printTicketv3.php', type: 'POST', data: {
            codigo: "<?php echo $_GET['idProducto']; ?>",
            titulo: queTitulo,
            fecha: queFecha.replace('a las ', ''),
            cliente: queDueno,
            articulo: queArticulo,
            monto: queMonto,
            usuario: queUser
         }}).done(function (resp) { 	}); break;
      case '34':
         queTitulo='    * Pago de cochera *';
         $.ajax({url: 'http://127.0.0.1/perucash/printTicketv3.php', type: 'POST', data: {
            codigo: "<?php echo $_GET['idProducto']; ?>",
            titulo: queTitulo,
            fecha: queFecha.replace('a las ', ''),
            cliente: queDueno,
            articulo: queArticulo,
            monto: queMonto,
            usuario: queUser
         }}).done(function (resp) { 	}); break;
      case '36':
         queTitulo='      * Gastos Adminitrativos *';
         $.ajax({url: 'http://127.0.0.1/perucash/printTicketGastos.php', type: 'POST', data: {
            codigo: "<?php echo $_GET['idProducto']; ?>",
            titulo: queTitulo,
            fecha: queFecha.replace('a las ', ''),
            cliente: queDueno,
            monto: queMonto,
            usuario: queUser
         }}).done(function (resp) { 	}); break;
      case '38':
         queTitulo='      * Compra *';
         $.ajax({url: 'http://127.0.0.1/perucash/printTicketCompra.php', type: 'POST', data: {
            codigo: "<?php echo $_GET['idProducto']; ?>",
            titulo: queTitulo,
            fecha: queFecha.replace('a las ', ''),
            monto: queMonto,
            usuario: queUser
         }}).done(function (resp) { 	}); break;
      case '43':
         queTitulo='    * Desembolso *';
         $.ajax({url: 'http://127.0.0.1/perucash/printTicketv3.php', type: 'POST', data: {
            codigo: "<?php echo $_GET['idProducto']; ?>",
            titulo: queTitulo,
            fecha: queFecha.replace('a las ', ''),
            cliente: queDueno,
            articulo: queArticulo,
            monto: queMonto,
            usuario: queUser
         }}).done(function (resp) { 	}); break;
      case '76':
         queTitulo='      * Pago de cochera *';
         $.ajax({url: 'http://127.0.0.1/perucash/printTicketGastos.php', type: 'POST', data: {
            codigo: "<?php echo $_GET['idProducto']; ?>",
            titulo: queTitulo,
            fecha: queFecha.replace('a las ', ''),
            cliente: queDueno,
            monto: queMonto,
            usuario: queUser
         }}).done(function (resp) { 	}); break;
   }
   
   
});
$('#liAGestionrFotos').click(function() {
   $('.modal-gestionarFotos').modal('show');
});
$('.divFotoGestion').click(function () {
   if( $(this).hasClass('libreSubida')){
      
   }
});
$('#txtSubirArchivo').change(function () {
   var archivo = $(this)[0].files[0];
   console.log(archivo.type);//.name: nombre, .size: tamaño archivo, .type: tipo de archivo
   if( archivo.type=='image/jpeg' || archivo.type=='image/png' ){
      //archivo correcto

      //Tutorial: https://www.uno-de-piera.com/subir-imagenes-con-php-y-jquery/
      console.log($('#txtSubirArchivo')[0])
      var formData= new FormData();
      formData.append('archivo',$('#txtSubirArchivo')[0].files[0]);
      formData.append('idProducto',<?php echo $_GET['idProducto']; ?>);
      $.ajax({
         url: 'php/subirArchivo.php',
         type: 'POST',
         data: formData,
         cache: false,
         contentType: false,
         processData: false,
         beforeSend: function () {
            //MOstrar algo para que se vea que esta subiendo
         },
         success: function (resp) { console.log(resp);
            //Mensaje que se subió
            location.reload();
         },
         error: function (error) {
            // Mostrar algo de error
         }
      })
   }else{
      //archivo incorrecto
   }

});
$('#btnInventariarPositivo').click(function () {
   $('.modal-inventarioPositivo').modal('show');
});
$('#btnInventariarNegativo').click(function () {
   $('.modal-inventarioNegativo').modal('show');
});
$('.modal-pagoMaestro').on('shown.bs.modal', function () {
   $('#dtpFechaPago').val( moment().format('DD/MM/YYYY h:mm a') );
   $('#sltMetodopago').selectpicker('val', 'Efectivo');
   $('#txtMontoPagos').val('0.00');
});
$('#btnInsertPositivo').click(function () {
   $.ajax({
      url: 'php/insertarInventarioPositivo.php',
      type: 'POST',
      data: {
         idProd: <?php echo $_GET['idProducto']; ?>,
         idUser: <?php echo $_COOKIE['ckidUsuario']; ?>,
         obs: $('#txtObsInvPositivo').val()
      }
   }).done(function (resp) {
      if( $.isNumeric(resp) ){
         location.reload();
      }else{
         $('.modal-GuardadoError').find('#spanMalo').text('El servidor dice: ' + resp);
         $('.modal-GuardadoError').modal('show');
      }
   });
});
$('#btnInsertNegativo').click(function () {
   $.ajax({
      url: 'php/insertarInventarioNegativo.php',
      type: 'POST',
      data: {
         idProd: <?php echo $_GET['idProducto']; ?>,
         idUser: <?php echo $_COOKIE['ckidUsuario']; ?>,
         obs: $('#txtObsInvNegativo').val()
      }
   }).done(function (resp) {
      if( $.isNumeric(resp) ){
         location.reload();
      }else{
         $('.modal-GuardadoError').find('#spanMalo').text('El servidor dice: ' + resp);
         $('.modal-GuardadoError').modal('show');
      }
   });
});
$('#liHojaControl').click(function () {
   var dataUrl="hojaControlv2.php?idProd="+<?= $_GET['idProducto'] ?>+"&compra=<?= $esCompra;?>"+"&producto="+encodeURIComponent($('.h2Producto').text())+"&dueno="+encodeURIComponent($('.spanDueno').text())+"&clase="+encodeURIComponent($('#spanClase').text())+"&und="+$('#spanCantp').text()+"&precio="+encodeURIComponent($('#spanPresInicial').text())+"&fecha="+encodeURIComponent($('#tablita tr').eq(1).find('.spanFechaFormat').text())+"&usuario="+encodeURIComponent($('#tablita tr').eq(1).find('.spanQuienRegistra').text())+"&dni="+$('#spanIdDueno').text();
   
   window.open(dataUrl, '_blank' );
   // loadPrintDocument(this,{
   // 	url: dataUrl,
   // 	attr: "url",
   // 	message:"Su documento se está creando"
   // });
   
});
$('#btnLlamarTicketVenta').click(function () {
   $('.modal-ticketVenta').modal('show');
});
$('#btnCrearTicketVenta').click(function () {
   if( $('#txtMontoTicketVenta').val()=='' || $('#txtMontoTicketVenta').val()<=0 ){
      $('.modal-ticketVenta .divError').removeClass('hidden').find('.spanError').text('Falta monto válido para una posible venta que tu área lo decide');
   }else{
      $.ajax({url:'php/crearTicketParaVenta.php', type: 'POST', data:{
         idProd: <?php echo $_GET['idProducto']; ?>,
         monto: $('#txtMontoTicketVenta').val(),
         obs: '- '+ $('#txtRazonTicketVenta').val()
      }}).done(function (resp) {
         $('.modal-ticketVenta').modal('hide');
         if(parseInt(resp)>0){
            $('.modal-GuardadoCorrecto #spanBien').text('Tu ticket es:');
            $('.modal-GuardadoCorrecto #h1Bien').text('#'+resp);
            $('.modal-GuardadoCorrecto').modal('show');
         }else{
            $('.modal-GuardadoError').find('#spanMalo').text('El servidor dice: \n' + resp);
            $('.modal-GuardadoError').modal('show');
         }
      });
   }
});
$('#btnLlamarTicketIntereses').click(function () { 
   $('#spanInteresInicial').text();
   $('#spanInteresInt').text();
   $('#spanInteresPena').text();
   $('#spanInteresTotal').text();
   $('.modal-ticketZonaIntereses').modal(); });
$('#btnCrearTicketPagoInteres').click(function () {
/*	if($('#txtMontoTicketIntereses').val().length==0){
      $('#btnCrearTicketPagoInteres').addClass('hidden');
      $('#spanInteresTipo').html('Debe pagar como mínimo los Gastos Administrativos');
   }
   else {
      
   }*/
   if($('#txtMontoTicketIntereses').val().length!=0){
      $.ajax({url: 'php/crearTicketParaDepositar.php', type: 'POST', data: { idProducto: <?php echo $_GET['idProducto'] ?>, dinero: $('#txtMontoTicketIntereses').val(), obs: $('#txtRazonTicketIntereses').val(), cochera: <?= $cochera; ?> }}).done(function (resp) { console.log(resp)
         if(resp.indexOf("#")>=0){
            $('.modal-ticketZonaIntereses').modal('hide');
            $('.modal-GuardadoCorrecto #spanBien').text('Ticket(s) a pagar:');
            $('.modal-GuardadoCorrecto #h1Bien').html(resp);
            $('.modal-GuardadoCorrecto').modal('show');
         }
      }).error(function (er) {console.log(er);
         // body...
      });
   }else{
      $('#btnCrearTicketPagoInteres').addClass('hidden');
      $('#spanInteresTipo').html('Debe pagar como mínimo los Gastos Administrativos');
   }
});
$('#liEditDescription').click(function() {
   $('#txtModProdNombre').val( $('.h2Producto').text() );
   $('#txtModPresCantidad').val(parseInt($('#spanCantp').text()));
   $('#txtModPresInicial').val($('#spanPresInicial').text());
   $('#txtModPresInteres').val(<?= $rowProducto['prodInteres']; ?>);
   $('#sltClienteMod').selectpicker('val', $('#spanIdDueno').text() +' - '+ $('.spanDueno').text());
   $('#sltEstadoMod').val(<?= $rowProducto['prodActivo']; ?>);
   $('.modalEditarProducto').modal('show');
});
$('#txtCantPagos').keyup(function(e) {
   if (parseInt($('#txtCantPagos').val())> parseInt($('#txtCantPagos').attr('max'))){
      $('#txtCantPagos').val($('#txtCantPagos').attr('max'));
   }
});

<? if( $_COOKIE['ckPower']=='1' ):?>
$('#liCongelar').click(function() {
   $('.modalCongelarProducto').modal('show');
});
$('#btnCongelar').click(function() {
   if( $('#txtFechaCongelar').val() ==''){}else{
      var fecha = $('#txtFechaCongelar').val();
   }
   
   $.ajax({url: 'php/productoCongelar.php', type: 'POST', data: { idProd: <?= $_GET['idProducto'];?>, fecha: fecha }}).done(function(resp) {
      console.log(resp)
      if( resp =='1'){
         location.reload();
      }
   });
});
<? endif;?>

<?php if( $rowProducto['prodActivo']==1 && $esCompra==0 ){ ?>
$('#txtMontoTicketIntereses').keyup(function(e) {
   var code = e.which;
   if(code==13){ e.preventDefault();
      var capital = <?php echo $rowInteres['preCapital']; ?>;
      var gastos = <?php echo $gastosAdmin; ?>;
      var cochera = <?= $cochera; ?>;
      var interes = <?  $interesFloat=str_replace(',', '', $interesJson); echo $interesFloat; ?>;
      var valor = parseFloat($(this).val()); 
      //console.log(cochera);
      var hayGasto='';
      if($('#txtMontoTicketIntereses').val().length!=0){
         $('#btnCrearTicketPagoInteres').removeClass('hidden');
      if(gastos >0){	hayGasto= '<br>Penalización de S/. <?= number_format($gastosAdmin,2); ?>';} 
         if(gastos==<?= $gastosAdmin; ?>  && valor <(gastos+ cochera) ){
            $('#btnCrearTicketPagoInteres').addClass('hidden');
            $('#spanInteresTipo').html('Debe pagar como mínimo los Gastos Administrativos y/o cochera');
         }else if(valor == <?= $cochera; ?>){
            $('#btnCrearTicketPagoInteres').removeClass('hidden');
            $('#spanInteresTipo').html('Pago de cochera S/ ' + parseFloat(valor-gastos - hayGasto ).toFixed(2) );
         }else if(valor < ( <?php echo $interesFloat + $gastosAdmin+ $cochera; ?> ) ){
            $('#btnCrearTicketPagoInteres').removeClass('hidden');
            $('#spanInteresTipo').html('<? if($cochera>0){echo 'Pago de cochera S/ '.number_format($cochera, 2).'<br>';} ?> Pago parcial de interés de S/ ' + parseFloat(valor-gastos- cochera ).toFixed(2) + hayGasto );
         }else if(valor == ( <?php  echo $interesFloat + $gastosAdmin + $cochera; ?> )   ){
            $('#btnCrearTicketPagoInteres').removeClass('hidden');
            $('#spanInteresTipo').html('<? if($cochera>0){echo 'Pago de cochera S/ '.number_format($cochera, 2).'<br>';} ?> Cancelación de interés de S/ ' + parseFloat(valor-gastos + hayGasto - cochera).toFixed(2) );
         }else if(valor >=  <?php echo $interesFloat + $gastosAdmin + $cochera + floatval($rowInteres['preCapital']) ; ?> ) {
            $('#btnCrearTicketPagoInteres').removeClass('hidden');
            $('#spanInteresTipo').html('<? if($cochera>0){echo 'Pago de cochera S/ '.number_format($cochera, 2).'<br>';} ?> Final de préstamo de S/ ' + parseFloat(valor-gastos- cochera).toFixed(2) + hayGasto );
         }else if((valor >  <?php echo $interesFloat + $gastosAdmin+ $cochera; ?> ) && (valor < <?php echo (float)$rowInteres['preCapital']+$interesFloat + $gastosAdmin+ $cochera; ?>) ){
            $('#btnCrearTicketPagoInteres').removeClass('hidden');
            $('#spanInteresTipo').html('<? if($cochera>0){echo 'Pago de cochera S/ '.number_format($cochera, 2).'<br>';} ?> Cancela Interés de S/ ' + parseFloat(interes).toFixed(2) + hayGasto + ' <br> ' + 'Amortización de S/ ' + parseFloat(valor-gastos-interes-cochera).toFixed(2) );
         }
      }else{
         $('#btnCrearTicketPagoInteres').addClass('hidden');
         $('#spanInteresTipo').html('Debe pagar como mínimo los Gastos Administrativos');
      }
   }
});
$('#txtMontoTicketIntereses').focusout(function () {
   var e = jQuery.Event("keyup");
   e.which = 13; //choose the one you want
   e.keyCode = 13;
   $("#txtMontoTicketIntereses").trigger(e);
});

<?php } if( in_array($_COOKIE['ckPower'], $soloCaja)){ ?>
$('#modalPagoAutomatico').on('shown.bs.modal', function () { 
   $('#sltMetodopago3').selectpicker('val', 'Efectivo');
});
$('#btnLlamarTicketMaestro').click(()=> {
   $('.modal-pagoMaestro').modal('show');
});
$('#btnPagoRematev2').click(function() {
   $('#btnInsertRemate').removeClass('hidden');
   $('#btnInsertPagoMaestro').addClass('hidden');
   $('#txtCantPagos').val(0).removeClass('hidden').prev().removeClass('hidden');
   $('#cmbPagosOpt').selectpicker('val', 'Rematado');
   $('#h3PagoEspecial').text('Remate');
   $('.modal-pagoMaestro').modal('show');
});
$('#btnPagoVentav2').click(function() {
   $('#btnInsertRemate').removeClass('hidden');
   $('#btnInsertPagoMaestro').addClass('hidden');
   $('#txtCantPagos').val(0).removeClass('hidden').prev().removeClass('hidden');
   $('#cmbPagosOpt').selectpicker('val', 'Vendido');
   $('#h3PagoEspecial').text('Venta');
   $('.modal-pagoMaestro').modal('show');
});
$('#btnDesembolsoAutomatico').click(function() {
   $('#btnInsertRemate').removeClass('hidden');
   $('#btnInsertPagoMaestro').addClass('hidden');
   $('#txtCantPagos').addClass('hidden').prev().addClass('hidden');
   $('#cmbPagosOpt').selectpicker('val', 'Desembolso');
   $('#h3PagoEspecial').text('Desembolso');
   $('.modal-pagoMaestro').modal('show');
});
$('#btnInsertRemate').click(function() {
   pantallaOver(true);
   var idMoneda= $('#divCmbMetodoPago').find('.selected a').attr('data-tokens');
   if( $('#txtMontoPagos').val()==0 ){
      pantallaOver(false);
      $('.modal-pagoMaestro .divError').removeClass('hidden').find('.spanError').text('Los pagos deben ser mayor a cero');
   }else if($('#cmbPagosOpt').selectpicker('val')=='Rematado' && $('#txtCantPagos').val()==0){
      pantallaOver(false);
      $('.modal-pagoMaestro .divError').removeClass('hidden').find('.spanError').text('La cantidad no puede ser cero');
   }else{ 
      var obs ='', linea = '';
      if($('#h3PagoEspecial').text()=='Remate' ){
         obs=$('#txtObsPagos').val()+' '+" Unds: -"+$('#txtCantPagos').val();
      }else{
         obs=$('#txtObsPagos').val();
      }
      linea = linea + $('#cmbPagosOpt').selectpicker('val')+ ": S/ "+ $('#txtMontoPagos').val() +"\n";
      $.ajax({url: 'php/ingresarPagoMaestro.php', type: 'POST', data: {
         idProd: <?php echo $_GET['idProducto']; ?>,
         quePago: $('#cmbEstadoPagos').find('.selected a').attr('data-tokens'),
         cuanto: $('#txtMontoPagos').val(),
         cantidad: $('#txtCantPagos').val(),
         moneda: idMoneda,
         fecha : moment().format('YYYY-MM-DD H:mm'),
         obs: obs
      }}).done((resp)=> { console.log (resp);
			pantallaOver(false);
         if( $.isNumeric(resp) ){
            $('.modal-pagoMaestro').modal('hide');
            $('#btnRefre2').removeClass('hidden'); $('#btnBien').addClass('hidden');
            $('.modal-GuardadoCorrecto #spanBien').text('Pago insertado');
            $('.modal-GuardadoCorrecto').modal('show');
            if( $('#chImprTicketAuto2').prop('checked') ){
               $.ajax({url: '<?= $servidorLocal; ?>printAutomatico.php', type: 'POST', data: {
                  codArt: "<?= $_GET['idProducto']; ?>",
                  hora: moment().format('DD/MM/YYYY hh:mm a'),
                  cliente: '',
                  articulo: $('.h2Producto').text(),
                  usuario: "<?= $_COOKIE['ckAtiende'];?>",
                  linea: linea
               }}).done(function(resp) {
                  console.log(resp)
               });
            }
         }
         if( $('#sltMetodopago3').val()=="Efectivo" ){
            $.post('http://127.0.0.1/perucash/soloAbrirCaja.php');
         }
		}).fail(function (params) { console.log(params.responseText);
			pantallaOver(false);
         listaBugs(params.responseText);
      });
      $('.modal-GuardadoCorrecto').on('hidden.bs.modal', function () { 
         location.reload();
      });
   }
});
$('#btnInsertPagoMaestro').click(function() {
   pantallaOver(true);
   var idMoneda= $('#divCmbMetodoPago').find('.selected a').attr('data-tokens');
   if(idMoneda == null ){
      pantallaOver(false);
      $('.modal-pagoMaestro .divError').removeClass('hidden').find('.spanError').text('Debes seleccionar un método de pago primero');
   }else if( $('#txtMontoPagos').val()==0 ){
      pantallaOver(false);
      $('.modal-pagoMaestro .divError').removeClass('hidden').find('.spanError').text('Los pagos deben ser mayor a cero');
   }else if($('#cmbPagosOpt').selectpicker('val')=='Rematado' && $('#txtCantPagos').val()==0){
      pantallaOver(false);
      $('.modal-pagoMaestro .divError').removeClass('hidden').find('.spanError').text('La cantidad no puede ser cero');
   }else{ 
      $.ajax({url: 'php/ingresarPagoMaestro.php', type: 'POST', data: {
         idProd: <?php echo $_GET['idProducto']; ?>,
         quePago: $('#cmbEstadoPagos').find('.selected a').attr('data-tokens'),
         cuanto: $('#txtMontoPagos').val(),
         moneda: idMoneda,
         fecha : moment($('#dtpFechaPago').val(), 'DD/MM/YYYY h:mm a').format('YYYY-MM-DD H:mm'),
         obs: $('#txtObsPagos').val()
      }}).done((resp)=> { console.log (resp);
         pantallaOver(false);
         if( $.isNumeric(resp) ){
            $('.modal-pagoMaestro').modal('hide');
            $('#btnRefre2').removeClass('hidden'); $('#btnBien').addClass('hidden');
            $('.modal-GuardadoCorrecto #spanBien').text('Pago insertado');
            $('.modal-GuardadoCorrecto').modal('show');
         }
      });
   }
});

$('.btnEditarCajaMaestra').click(function() {
   var padre = $(this).parent().parent();
   $('#txtCajaMontoPagos').val( padre.find('.spanCantv3').text() );
   $('#txtCajaObsPagos').val( padre.find('.tdObservacion').text() );
   $('#sltMetodopago2').selectpicker('val', padre.find('.tdMoneda').text() );
   $('#spTipoPago2').selectpicker('val', padre.find('.tpIdDescripcion').text() );
   $('#btnUpdateCajaMaestra').attr('data-caja', padre.find('.spanQuienRegistra').attr('data-id') );
   $('#sltActivoV2').val(padre.find('.spanQuienRegistra').attr('data-activo'));
   
   //console.log( moment(padre.find('.fechaPagov3').text(), 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY hh:mm a')) ;
   $('#dtpCajaFechaPago').bootstrapMaterialDatePicker('setDate',moment(padre.find('.fechaPagov3').text(), 'YYYY-MM-DD HH:mm:ss').format('DD/MM/YYYY hh:mm a'));

   $('.modal-cajaMaestra').modal('show');
});
$('#btnActualizarMod').click(function() {
   var idCli= $('#divCmbClientes').find('.selected a').attr('data-tokens');
   if(idCli == null ){
      $('.modalEditarProducto .divError').removeClass('hidden').find('.spanError').text('Debes seleccionar un método de pago primero');
   }else{
      $.ajax({url: 'php/actualizarProducto.php', type: 'POST', data: {
         idProd: <?= $_GET['idProducto'];  ?>,
         nombre: $('#txtModProdNombre').val(),
         monto: $('#txtModPresInicial').val(),
         intereses:  $('#txtModPresInteres').val(),
         idCLi:  idCli,
         activo: $('#sltEstadoMod').val(),
         cantidad: $('#txtModPresCantidad').val()
      }}).done(function(resp) {
         if(resp==1){
            location.reload();
         }else{
            $('.modal-GuardadoError').find('#spanMalo').text('El servidor dice: ' + resp);
            $('.modal-GuardadoError').modal('show');
         }
      });
   }
});
$('#btnUpdateCajaMaestra').click(function() {
   var idProc= $('#cmbEstadoPagos2').find('.selected a').attr('data-tokens');
   var mone = $('#divCmbMetodoPago2').find('.selected a').attr('data-tokens');
   var padre = $(this).parent().parent();
   $.ajax({url: 'php/actualizarCaja.php', type: 'POST', data: { 
      idCaj: $('#btnUpdateCajaMaestra').attr('data-caja'),
      pproceso: idProc,
      ffecha: moment($('#dtpCajaFechaPago').val(), 'DD/MM/YYYY hh:mm a').format('YYYY-MM-DD HH:mm'),
      vvalor: $('#txtCajaMontoPagos').val(),
      oobs: $('#txtCajaObsPagos').val(),
      mmoneda: mone,
      aactivo: $('#sltActivoV2').val()
    }}).done(function(resp) { //console.log(resp)
      $('.modal-cajaMaestra').modal('hide');
      if(resp=='1'){
         location.reload();
      }
   });
});
<?php } ?>

<?php  if(in_array($_COOKIE['ckPower'], $admis) ){ ?>
$('#btnChangeCapital').click(function() {
   $('#txtNuevoCapital').val( $('#spanCapitalDefault').text() );
   $('.modal-nuevoCapital').modal('show');
});
$('#btnActualizarCapital').click(function() {
   $.ajax({url: 'php/updateMontoCapital.php', type: 'POST', data: {
      monto: $('#txtNuevoCapital').val(),
      idProd: <?= $_GET['idProducto']; ?>
    }}).done(function(resp) {
      //console.log(resp)
      if( resp==true){ location.reload(); }
   });
});
$('#btnChangeFechaInt').click(function() {
   $('#modalUpdateFechaInt').modal('show');
});
$('#btnActualizarFechaInt').click(function() {
   if( $('#txtNuevoFechaInt').val()!='' ){
      pantallaOver(true);
      $.ajax({url: 'php/updateFechaInteresProducto.php', type: 'POST', data: { newFecha: moment($('#txtNuevoFechaInt').val(), 'DD/MM/YYYY').format('YYYY-MM-DD'), idProd: <?= $_GET['idProducto'];?> }}).done(function(resp) {
         //console.log(resp)
         location.reload();
      });
   }
});
$('#btnPagoAutomatico').click(function() {
   pantallaOver(true);
   $.ajax({url: 'php/calculoAutomaticoDebe.php', type: 'POST', data: { idProd: <?= $_GET['idProducto'];?> }}).done(function(resp) { console.log(resp)
      var data = JSON.parse(resp);
      console.log( data );
      if( data.length>=1 ){
         $('#txtAutoDinero').attr('data-valor', data[0].total ).val( "0.00").prev().find('small').text("S/ "+parseFloat(data[0].total).toFixed(2) );
         if(data[0].cochera ==0){
            $('#txtAutoCochera').addClass('hidden').prev().addClass('hidden');
         }else{
            $('#txtAutoCochera').removeClass('hidden').prev().removeClass('hidden');
         }
         if(data[0].penalizacion ==0){
            $('#txtAutoGastos').addClass('hidden').prev().addClass('hidden');
         }else{
            $('#txtAutoGastos').removeClass('hidden').prev().removeClass('hidden');
         }
         $('#txtAutoCochera').attr('data-valor', data[0].cochera ).val("0.00").prev().find('small').text("S/ "+parseFloat(data[0].cochera).toFixed(2) );
         $('#txtAutoGastos').attr('data-valor', data[0].penalizacion ).val("0.00").prev().find('small').text("S/ "+parseFloat(data[0].penalizacion).toFixed(2) );
         $('#txtAutoInteres').attr('data-valor', data[0].interes ).val("0.00").prev().find('small').text("S/ "+parseFloat(data[0].interes).toFixed(2) );
         $('#txtAutoCapital').attr('data-valor', data[0].capital ).val("0.00").prev().find('small').text("S/ "+parseFloat(data[0].capital).toFixed(2) );
      }
      pantallaOver(false);
      $('#modalPagoAutomatico').modal('show');
   });
});
$('.inputModif').keyup(function () {
   var suma = 0;
   suma = parseFloat($('#txtAutoCochera').val())+ parseFloat($('#txtAutoGastos').val())+ parseFloat($('#txtAutoInteres').val()) + parseFloat($('#txtAutoCapital').val());
   $('#txtAutoDinero').val(suma.toFixed(2));
});
$('#txtAutoDinero').keyup(function () {
   var total =0, dinero=0;
   if( parseFloat($('#txtAutoDinero').val())> parseFloat($('#txtAutoDinero').attr('data-valor')) ){
      $('#txtAutoDinero').val(parseFloat($('#txtAutoDinero').attr('data-valor')).toFixed(2));
   }
   
   $('#txtAutoCochera').val("0.00");
   $('#txtAutoGastos').val("0.00");
   $('#txtAutoInteres').val("0.00");
   $('#txtAutoCapital').val("0.00");

   if(  $('#txtAutoDinero').val()!='' && $('#txtAutoDinero').val()!=0 ){
      total = $('#txtAutoDinero').attr('data-valor');
      dinero = parseFloat($('#txtAutoDinero').val());
      
      if(  parseFloat($('#txtAutoCochera').attr('data-valor'))>0 && dinero>0 ){
         if( dinero >= parseFloat($('#txtAutoCochera').attr('data-valor')) ){
            $('#txtAutoCochera').val(parseFloat($('#txtAutoCochera').attr('data-valor')).toFixed(2) );
            dinero -= parseFloat($('#txtAutoCochera').attr('data-valor'));
         }else{
            $('#txtAutoCochera').val( dinero.toFixed(2) );
            dinero = 0 ;
         }
         
      }
      if( parseFloat($('#txtAutoGastos').attr('data-valor'))>0 && dinero>0 ){
         if( dinero >= parseFloat($('#txtAutoGastos').attr('data-valor')) ){
            $('#txtAutoGastos').val(parseFloat($('#txtAutoGastos').attr('data-valor')).toFixed(2) );
            dinero -= parseFloat($('#txtAutoGastos').attr('data-valor'));
         }else{
            $('#txtAutoGastos').val( dinero.toFixed(2) );
            dinero = 0 ;
         }
      }
      if( parseFloat($('#txtAutoInteres').attr('data-valor'))>0 && dinero>0 ){
         if( dinero >= parseFloat($('#txtAutoInteres').attr('data-valor')) ){
            $('#txtAutoInteres').val(parseFloat($('#txtAutoInteres').attr('data-valor')).toFixed(2) );
            dinero -= parseFloat($('#txtAutoInteres').attr('data-valor'));
         }else{
            $('#txtAutoInteres').val( dinero.toFixed(2) );
            dinero = 0 ;
         }
      }
      if( parseFloat($('#txtAutoCapital').attr('data-valor'))>0 && dinero>0 ){
         if( dinero >= parseFloat($('#txtAutoCapital').attr('data-valor')) ){
            $('#txtAutoCapital').val(parseFloat($('#txtAutoCapital').attr('data-valor')).toFixed(2) );
            dinero -= parseFloat($('#txtAutoCapital').attr('data-valor'));
         }else{
            $('#txtAutoCapital').val( dinero.toFixed(2) );
            dinero = 0 ;
         }
      }
      console.log("sobra "+dinero);

   }
});
$('#txtAutoInteres').keyup(function() {
   if( parseFloat($(this).val())< parseFloat($(this).attr('data-valor')) ){
      $('#txtAutoCapital').attr('readonly', true);
      $('#checkbox4').prop('checked', true);
      $('#txtAutoCapital').val('0.00');
   }
});
$('#btnPagarAutomatico').click(function() {
   pantallaOver(true);
   var quePaga = [];
   var idMoneda= $('#divCmbMetodoPago3').find('.selected a').attr('data-tokens');
   var todoInteres=0, todoMora=0, todoCapital=0;
   if( parseFloat($('#txtAutoGastos').val())== parseFloat($('#txtAutoGastos').attr('data-valor')) ){
      todoMora=1;
   }
   if( parseFloat($('#txtAutoInteres').val())== parseFloat($('#txtAutoInteres').attr('data-valor')) ){
      todoInteres=1;
   }
   if( parseFloat($('#txtAutoCapital').val())== parseFloat($('#txtAutoCapital').attr('data-valor')) ){
      todoCapital=1;
   }
   quePaga.push({ codProd: '<?= $_GET['idProducto'];?>', total: $('#txtAutoDinero').val(), cochera: $('#txtAutoCochera').val(), penalizacion: $('#txtAutoGastos').val(), interes: $('#txtAutoInteres').val(), amortizacion: $('#txtAutoCapital').val(), capital: $('#txtAutoCapital').val() });
   //console.log( quePaga );
   $.ajax({url: 'php/autoInsertar.php', type: 'POST', data: { idProd: '<?= $_GET['idProducto'];?>', quePaga: quePaga, todoMora: todoMora,
      todoInteres: todoInteres, todoCapital: todoCapital, moneda: idMoneda, obs: $('#txtAutoObserv').val() }}).done(function(resp) {
		console.log(resp)
		pantallaOver(false);
      var alma=JSON.parse(resp)[0];
      //console.log( alma.pagoAdelaInt );
      var linea = '';
      if( alma.pagoCoch =='1'){ linea = linea + "Cochera: S/ "+ $('#txtAutoCochera').val()+"\n"; }
      if( alma.pagoPartePena =='1'){ linea = linea + "Mora parcial: S/ "+ $('#txtAutoGastos').val()+"\n"; }
      if( alma.pagoPena =='1'){ linea = linea + "Mora: S/ "+ $('#txtAutoGastos').val()+"\n"; }
      if( alma.pagoAdelaInt =='1'){ linea = linea + "Adelanto de interés: S/ "+ $('#txtAutoInteres').val()+"\n"; }
      if( alma.pagoInt =='1'){ linea = linea + "Cancelación de interés: S/ "+ $('#txtAutoInteres').val()+"\n"; }
      if( alma.pagoAmor =='1'){ linea = linea + "Amortización: S/ "+ $('#txtAutoCapital').val()+"\n"; }
      if( alma.pagoFin =='1'){ linea = linea + "Fin de préstamo: S/ "+ $('#txtAutoCapital').val()+"\n"; }
      $('.modal-GuardadoCorrecto #spanBien').text('Ticket(s) a pagar:');
		$('.modal-GuardadoCorrecto #h1Bien').html( linea.replace(/\n/g, '<br>'));
      $('.modal-GuardadoCorrecto').modal('show');
      if( $('#chImprTicketAuto').prop('checked') ){
         $.ajax({url: '<?= $servidorLocal; ?>printAutomatico.php', type: 'POST', data: {
            codArt: "<?= $_GET['idProducto']; ?>",
            hora: moment().format('DD/MM/YYYY hh:mm a'),
            cliente: $('.spanDueno').text(),
            articulo: $('.h2Producto').text(),
            usuario: "<?= $_COOKIE['ckAtiende'];?>",
            linea: linea
         }}).done(function(resp) {
            console.log(resp)
         });
      }
      if( $('#sltMetodopago3').val()=="Efectivo" ){
         $.post('http://127.0.0.1/perucash/soloAbrirCaja.php');
      }
   }).fail(function (params) {
		pantallaOver(false);
      listaBugs(params.responseText);
   });
   $('.modal-GuardadoCorrecto').on('hidden.bs.modal', function () { 
      location.reload();
   });
});
<?php } ?>
$('#btnGuardarCubicaje').click( ()=> {
   pantallaOver(true);
   var proces = $('#divTipoMovAlmacen').find('.selected a').attr('data-tokens');
   var estante= $('#divSelectEstante').find('.selected a').attr('data-tokens');
   var piso= $('#divSelectPiso').find('.selected a').attr('data-tokens');
   var seccion = $('#divSelectSeccion').find('.selected a').attr('data-tokens');
   //console.log(proces);

   if( estante !=null ){
      if( piso ==null ){ piso = 1;}
      if( seccion ==null ){ seccion = 1;}
      $.ajax({url:'php/insertarCubicaje.php', type: 'POST', data: {
         idProducto: <?php echo $_GET['idProducto']; ?>,
         proceso: proces,
         estante: estante,
         piso: piso,
         seccion: seccion,
         obs: $('#txtObservacionCubicaje').val()
      }}).done((resp)=> { console.log(resp);
         if( $.isNumeric(resp)){
            location.reload();
         }
      });}
   pantallaOver(false);
});
$('.chkInterno').change(function() {

   if( $('#txtAutoDinero').val()>0 ){
      if($(this).prop('checked')==false ){
         
         if( $(this).parent().next().val()=='0.00' ){
            $(this).parent().next().attr('readonly', true);	
            $(this).prop('checked', true); //Bloquea el acceso al input
         }else{
            $(this).parent().next().attr('readonly', false);
            $(this).parent().next().focus();
         }

         switch ($(this).attr('id')) {
               case "checkbox4":
                  if( parseFloat($('#txtAutoInteres').val()) == parseFloat($('#txtAutoInteres').attr('data-valor')) ){
                     $('#txtAutoCapital').attr('readonly', false);
                     $(this).prop('checked', false);
                  }else{
                     $('#txtAutoCapital').attr('readonly', true);
                     $(this).prop('checked', true);
                     return false;
                  }		
                  break;
               default:
                  break;
            }
            
      }else{
         $(this).parent().next().attr('readonly', true);
      }

      $.each( $('.chkInterno'), function (i, elem) {
         if( !$(elem).prop('checked')==false ){
            $('#txtAutoDinero').attr('readonly', false);
         }else{
            $('#txtAutoDinero').attr('readonly', true);
            return false;
         }
      });
   }else{
      $(this).prop('checked', true);
      $('#txtAutoDinero').focus();
   }
});
$('#btnProyeccion').click(function() {
   $('#modalProyectarFuturo').modal('show');
});
$('#btnProyectarFuturo').click(function() {
   $('#divCalculosFuturos').addClass('hidden');
   
   $.ajax({url: 'php/calculoAutomaticoDebe.php', type: 'POST', data: { idProd:<?= $_GET['idProducto'];?> , limite: moment($('#txtFechaFutura').val(), 'DD/MM/YYYY').format('YYYY-MM-DD') }}).done(function(resp) { console.log(resp)
      var dato = JSON.parse(resp);
      if(dato.length>=1){
         $('#capitalFuturo').text(parseFloat(dato[0].capital).toFixed(2));
         $('#tiempoFuturo').text(parseFloat(dato[0].soloDias).toFixed(2));
         $('#interesFuturo').text(parseFloat(dato[0].interes).toFixed(2));
         if( parseFloat(dato[0].cochera) >0){
            $('#cocheraFuturo').text(parseFloat(dato[0].cochera).toFixed(2)).parent().removeClass('hidden');
         }else{
            $('#cocheraFuturo').parent().addClass('hidden');
         }
         if( parseFloat(dato[0].penalizacion) >0){
            $('#gastosFuturo').text(parseFloat(dato[0].penalizacion).toFixed(2)).parent().removeClass('hidden');
         }else{
            $('#gastosFuturo').parent().addClass('hidden');
         }
         $('#deudaFuturo').text(parseFloat(dato[0].total).toFixed(2));
      }
      $('#divCalculosFuturos').removeClass('hidden');
      pantallaOver(false);	
   });
});
</script>

<?php 
if( isset($_GET['ticket']) ){ ?>
<script>
$('.modal-GuardadoCorrecto #spanBien').text('Ticket(s) a pagar:');
$('.modal-GuardadoCorrecto #h1Bien').html( '<?php echo $_GET['ticket']; ?>');
$('.modal-GuardadoCorrecto').modal('show');
</script>
<?php  } ?>

<?php } ?>
</body>

</html>