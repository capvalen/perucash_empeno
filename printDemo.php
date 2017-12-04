
<?php
session_start();

$variable= base64_encode('img');
echo $variable. "<br>";
echo base64_decode($variable);
