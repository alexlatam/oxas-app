<?php
session_start();
session_destroy();
require 'administracion/common/meli.php';
require 'administracion/common/settings.php';

if ( !isset($_GET['site']) ) {
  header("location: index.php");
}

$siteId      = $_GET['site'];

$appId       = $GLOBALS['app_id_vzla'];
$secretKey   = $GLOBALS['secret_key_vzla'];
$redirectURI = $GLOBALS['redirect_url_vzla'];
  
if ($siteId == 'MLC') {
  
  $appId       = $GLOBALS['app_id_chile'];
  $secretKey   = $GLOBALS['secret_key_chile'];
  $redirectURI = $GLOBALS['redirect_url_chile'];
  
}

$GLOBALS['app_id']       = $appId;
$GLOBALS['secret_key']   = $secretKey;
$GLOBALS['redirect_url'] = $redirectURI;

$meli = new Meli($GLOBALS['app_id'], $GLOBALS['secret_key']);
header("location: " . $meli->getAuthUrl($GLOBALS['redirect_url'], Meli::$AUTH_URL[$siteId]));