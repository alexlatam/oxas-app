<?php
session_start();
require '../common/conexion.php';require '../common/callback.php';require '../common/take_at.php';
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,'https://api.mercadolibre.com/questions/search?item='.$_GET['item']."&from=".$_GET['id_cliente'].'&status=ANSWERED&access_token='.$AccessToken.'&callback='.$callback);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);$result=curl_exec($ch);curl_close($ch);$length=strlen($result)-6;
$string=substr($result,4,$length);$var=json_decode($string);$consulta=$var[2];$auxiliar=json_encode($consulta);echo $auxiliar;
?>
