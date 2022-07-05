<?php
//$conn = new mysqli("ip_host","user","pass_user","dbname");
// $conn = new mysqli("localhost","root","","oxa");
$conn = new mysqli("localhost","oxas","oxas_meli_app","oxa");
if ($conn->connect_error){die("Connection failed: ".$conn->connect_error);} ?>
