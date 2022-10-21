<?php
if(isset($_COOKIE['id_user'],$_COOKIE['_validate'],$_COOKIE['expires_in'])){

  $id_user = $_COOKIE['id_user'];
  $sql="SELECT ACCESSTOKEN,CORREO FROM usuario where id='$id_user';";

  $res=$conn->query($sql);
  if($res->num_rows>0){
    while($row=$res->fetch_assoc()){
      $AccessToken=$row['ACCESSTOKEN'];
      $correoUser=$row['CORREO'];
    }
  }
  $appId       = $_SESSION["client_id"];
  $secretKey   = $_SESSION["secret_key"];
  $redirectURI = $_SESSION["redirect_url"];

}else{
  header('Location: '.$_SESSION['https_url_app']);
}
?>
