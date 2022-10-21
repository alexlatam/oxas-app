<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
$ch = curl_init();
curl_setopt($ch,CURLOPT_URL,'https://s3.amazonaws.com/dolartoday/data.json');
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
$result = curl_exec($ch);
curl_close($ch);
$consulta = json_decode($result);

$dolar = 0;
if($consulta->USD->transferencia){
    $dolar = $consulta->USD->transferencia;
}
