<?php
session_start();
require 'meli.php';
include 'conexion.php';
include 'funciones.php';

date_default_timezone_set('America/Caracas');
$meli = new Meli($_SESSION['client_id'], $_SESSION['secret_key']);

// si existe un codigo(code), quiere decir que se esta registrando por 1 vez, 
// si existe un validate quiere decir q ya se habia registrado antes
if (@$_GET['code'] || @$_COOKIE['_validate']) { 

    if ($_GET['code']) { // If code exist and session is empty
        $user = $meli->authorize($_GET['code'], $_SESSION['redirect_url']);
        $AT   = $user['body']->access_token;
        $RT   = $user['body']->refresh_token;
        $expires_in = $user['body']->expires_in;
        #Creacion de Cookies
        $duracion = time() + 5 * 60 * 60; #Duracion de la COOKIE 6Hr
        $ruta = "/";
        #commando curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.mercadolibre.com/users/me?access_token=$AT");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($result);

        $user_id   = $response->user_id;
        $email     = $response->email;
        $telppal   = @$response->phone->area_code . $response->phone->number;
        $telsecond = @$response->alternative_phone->area_code . $response->alternative_phone->number;
        $site_id   = @$response->site_id;
        echo "EMAIL $email <br>";
        var_dump(userExist($user_id, $email));
        #existe el usuario?
        if (userExist($user_id, $email)) {
            echo "ENTRO ACA EXISTE EL USER<br>";
            #actualizar tokens
            setcookie("id_user", $user_id, $duracion, $ruta);
            updateTokens($user_id, $AT, $RT);
            //verifica la suscripcion
            $sql = "SELECT ESTATUS FROM suscripcion WHERE user_id=$user_id LIMIT 1;";
            $r = $conn->query($sql);
            if ($r->num_rows > 0) {
                $row = $r->fetch_assoc();
                $estatus = $row['ESTATUS'];
                setcookie("_validate", $estatus, $duracion, $ruta);
                setcookie("expires_in", time() + $expires_in, $duracion, $ruta);
            }
        } else {
            echo "ENTRO ACA NOO EXISTE EL USER<br>";
            #No existe el Usuario
            #guardar en base de datos user_id, Correo, AccessToken, refreshToken
            setcookie("id_user", $user_id, $duracion, $ruta);
            echo "ANTES de Insertar el user<br>";
            insertUser($user_id, $email, $AT, $RT, $response->first_name, $response->last_name, $telppal, $telsecond, $site_id);
            echo "Inserto el user<br>";
            die();
            #registra la suscripcion
            //Fecha Actual de Registro
            $fechaR = date("Y-m-d");
            //Fecha actual mas 21 día de membresia.
            $fechaV  = date("Y-m-d", strtotime($fechaR . "+ 360 days")); //Modificar de 30 dias Luego.
            $tipo    = 0; //tipo cero indica plan gratis
            $estatus = 0;
            suscribir($user_id, $fechaR, $fechaV, $tipo, $estatus);
            #crearmos cookies de acceso
            setcookie("_validate", $estatus, $duracion, $ruta);
            setcookie("expires_in", time() + $expires_in, $duracion, $ruta);
            //enviar correo a nuevo usuario
            // $destinatario = $email;
            // include('../correos/bienvenida.php');
        }
        // header('location:../');
    } else {
        // We can check if the access token in invalid checking the time
        if ($_COOKIE['expires_in'] < time()) {
            try {
                #Leer de la base de datos refreshToken
                $refresh = $meli->refreshAccessToken();
                $AT = $refresh['body']->access_token;
                $RT = $refresh['body']->refresh_token;
                $duracion = time() + 5 * 60 * 60;
                $ruta = "/";
                setcookie("_validate", true, $duracion, $ruta);
                setcookie("expires_in", time() + $refresh['body']->expires_in, $duracion, $ruta);
                #guardar en base de datos  AccessToken, refreshToken mediante
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://api.mercadolibre.com/users/me?access_token=$AT");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);
                $response = json_decode($result);
                if (userExist($user_id, $email)) {
                    #actualizar tokens
                    setcookie("id_user", $user_id, $duracion, $ruta);
                    updateTokens($user_id, $AT, $RT);
                }
            } catch (Exception $e) {
                echo "Exception: ", $e->getMessage(), "\n";
            }
        }
    }
} else {
    header('location:.../');
}
