<?php
session_start();
session_destroy();
setcookie("_validate", "", time() - 10, "/");
setcookie("expires_in", "", time() - 10, "/");
setcookie("id_user", "", time() - 10, "/");
header("Location: ".$_SESSION['https_url_app']);
die();
?>
