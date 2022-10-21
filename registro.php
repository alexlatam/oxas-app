<?php
session_start();
session_destroy();
require 'administracion/common/meli.php';
require 'administracion/common/conexion.php';

if ( !isset($_GET['site']) ) {
  header("location: index.php");
}

$siteId      = $_GET['site'];

$sql="SELECT * FROM settings";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    
    if    ($row['field'] == 'https_url_app'){   $_SESSION["https_url_app"] = $row['https_url_app'];}
    elseif($row['field'] == 'secret_key_vzla'){ $_SESSION["secret_key"]    = $row['secret_key_vzla'];}
    elseif($row['field'] == 'redirect_url'){    $_SESSION["redirect_url"]  = $row['redirect_url'];}
    elseif($row['field'] == 'app_id_vzla'){     $_SESSION["app_id"]        = $row['app_id_vzla'];}
    
  }
}

$meli = new Meli($_SESSION['app_id'], $_SESSION['secret_key']);
header("location: " . $meli->getAuthUrl($_SESSION['redirect_url'], Meli::$AUTH_URL[$siteId]));