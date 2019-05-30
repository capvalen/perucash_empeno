<?php 

ini_set('display_errors', 1);
require_once "../Facebook/autoload.php";

$FB = new \Facebook\Facebook([
   'app_id' => '2381050335500925',
   'app_secret'=>'9a45cd71fa995022cf28c32b82d2e95d',
   'default_graph_version' => 'v3.3'
]);

$helper = $FB->getRedirectLoginHelper();

$logoutUrl = $FB->getLogoutUrl(array("next" => "https://perucash.com/salida")); 
echo $logoutUrl;
//$FB->destroySession(); 
?>