<?php
session_start();
$bandera = $_GET['band'];
if (isset($_GET['item'])) {
    $id_publicacion = $_GET['item'];
    if ($bandera == 1) {
        if (@$_SESSION['array_publicaciones'][0] == 1) {
            $indice = array_search($id_publicacion, $_SESSION['array_publicaciones']);
            unset($_SESSION['array_publicaciones'][$indice]);
        } else {
            array_push($_SESSION['array_publicaciones'], $id_publicacion);
        }
    } else {
        if (@$_SESSION['array_publicaciones'][0] == 1) {
            array_push($_SESSION['array_publicaciones'], $id_publicacion);
        } else {
            $indice = array_search($id_publicacion, $_SESSION['array_publicaciones']);
            unset($_SESSION['array_publicaciones'][$indice]);
        }
    }
} else {
    if ($bandera == 1) {
        $_SESSION['array_publicaciones'] = array();
        array_push($_SESSION['array_publicaciones'], 1);
    } else {
        $_SESSION['array_publicaciones'] = array();
    }
}
print_r($_SESSION['array_publicaciones']);
