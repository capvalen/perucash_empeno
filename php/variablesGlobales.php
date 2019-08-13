<?php

include "conkarl.php";

$admis=array(1,4,8);
$soloAdmis=array(1,4,8);
$soloDios=array(1);
$soloCaja=array(1,4);
$soloEspecial=array(8);

$folder = 'demo';
$local = '/'.$folder;


$sqlConf = mysqli_query( $conection,  "SELECT * FROM `configuraciones`");
$rowConf = mysqli_fetch_array($sqlConf, MYSQLI_ASSOC);

$ipServer = $rowConf['ipServidor'];
$serverLocal= "//{$ipServer}/perucash/";
$servidorLocal = $serverLocal;

$existeCaja = intval(require_once 'php/comprobarCajaHoy.php' );
//if( $existeCaja>0 ){echo 'numero'; }else{ echo 'no hay'; }

?>