<?php
session_start();
session_destroy();
require 'administracion/common/meli.php';

if ( !isset($_GET['site']) ) {
  header("location: index.php");
}

$siteId      = $_GET['site'];

$appId       = '6021623127840893';
$secretKey   = 'esZADLxWSqBWYf7gf0Fcr9JSCzYpR3OR';
$redirectURI = "https://asvzla.ml/administracion/common/redirect.php";
  
if ($siteId == 'MLC') {
  
  $appId       = '884214179114152';
  $secretKey   = 'NpVPyfC6vtrFdS5EZ9Sr2DQe5sAOrXAK';
  $redirectURI = "https://asvzla.ml/administracion/common/redirect2.php";
  
}

$meli = new Meli($appId, $secretKey);
header("location: " . $meli->getAuthUrl($redirectURI, Meli::$AUTH_URL[$siteId]));