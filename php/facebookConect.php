<?php 

session_start();
require_once "Facebook/autoload.php";

$FB = new \Facebook\Facebook([
   'app_id' => '2381050335500925',
   'app_secret'=>'9a45cd71fa995022cf28c32b82d2e95d',
   'default_graph_version' => 'v3.3'
]);

$helper = $FB->getRedirectLoginHelper();
$redirectURL = "http://perucash.com/chilca/sesionDatosFace.php";
$permissions = ['email'];
$loginURL = $helper->getLoginURL($redirectURL, $permissions);

//echo $loginURL;
?>