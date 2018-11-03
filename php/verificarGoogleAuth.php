<?php 
require_once 'GoogleAuthenticator.php';

$ga = new PHPGangsta_GoogleAuthenticator();
//$secret = $ga->createSecret();
$secret = 'UNAQZXWXLGXBBQL2';



$qr= $ga->getQRCodeGoogleUrl('Perucash App', $secret);
echo '<img src="'.$qr.'" /><br />';

$mycode=$ga->getCode($secret);
$result = $ga->verifyCode($secret, $mycode, 3);


echo $result;

?>