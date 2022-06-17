<?php
session_start();
session_destroy();
require 'administracion/common/meli.php';

if ( !isset($_GET['site']) ) {
  header("location: index.php");
}

$siteId      = $_GET['site'];

$appId       = '1153047962046613';
$secretKey   = 'i3RGdgCvJXrKT1ceMNOHs4YLNHdgZ9Mj';
$redirectURI = "https://app.oxas.tech/administracion/common/redirect.php";
  
if ($siteId == 'MLC') {
  
  $appId       = '884214179114152';
  $secretKey   = 'NpVPyfC6vtrFdS5EZ9Sr2DQe5sAOrXAK';
  $redirectURI = "https://app.oxas.tech/administracion/common/redirect2.php";
  
}

$meli = new Meli($appId, $secretKey);
header("location: " . $meli->getAuthUrl($redirectURI, Meli::$AUTH_URL[$siteId]));