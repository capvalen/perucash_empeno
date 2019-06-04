<?php 

session_start();
require_once "Facebook/autoload.php";
include 'variablesGlobales.php';

$FB = new \Facebook\Facebook([
   'app_id' => '2381050335500925',
   'app_secret'=>'',
   'default_graph_version' => 'v3.3'
]);

$helper = $FB->getRedirectLoginHelper();
$redirectURL = "https://perucash.com/{$folder}/sesionDatosFace.php";
$permissions = ['email'];
$loginURL = $helper->getLoginURL($redirectURL, $permissions);

//echo $loginURL;
?>