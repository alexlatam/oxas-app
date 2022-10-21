<?php
session_start();
require '../common/meli.php';
require '../common/conexion.php';
require '../common/take_at.php';
require '../common/account-off.php';
$_SESSION['array_publicaciones'] = array();
?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="Eutuxia">
  <link rel="shortcut icon" href="../../img/favicon.ico">
  <title>Oxas - Nueva Respuesta Automática</title>
  <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <script src="../vendor/jquery/jquery.min.js"></script>
  <link href="../css/sb-admin.min.css" rel="stylesheet">
  <link href="../css/style.css" rel="stylesheet">
  <link rel="icon" type="image/vnd.microsoft.icon" href="../../img/favicon.ico">
  <script>
    $(document).ready(function() {
      $('form').keypress(function(e) {
        if (e == 13) {
          return false;
        }
      });
      $('input').keypress(function(e) {
        if (e.which == 13) {
          return false;
        }
      });
    });
  </script>
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <?php include '../common/navbar.php'; ?>
  <div class="content-wrapper">
    <div class="container-fluid">
      <div class="breadcrumb mt-1">
        <div class="container-fluid mt-1">
          <div class="row justify-content-around">
            <div class="col-sm-8 mb-3">
              <div class="form-group">
                <textarea class="mb-2 form-control textarea2" name="respuesta" placeholder="Escriba aqui la respuesta automática" id="respuesta" required maxlength="2000"></textarea>
              </div>
              <input class="mb-3 col-sm-6 keywords" type="text" placeholder="Inserta aqui cada palabra filtro" id="filter" autocomplete="off" />
              <div class="mb-2 ml-auto">
                <button type="button" class="btn btn-sm btn-outline-success" name="adicionar" id="adicionar" title="Agregar a lista de palabras filtro" data-toggle="tooltip">Ingresar</button>
              </div>
              <div class="row justify-content-center">
                <div class="col-sm-12 bg-white rounded pt-1 keywords" id="keywords"></div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="container-fluid overflow bg-light border rounded" id="publicaciones_activas">
                <div class="row align-items-center pt-2 justify-content-center">
                  <label class="switch"><input type="checkbox" id="selectall"><span class="slider"></span></label>
                  <span class="ml-3 pb-1 text-muted">Seleccionar Todas</span>
                </div>
                <hr class="m-0 my-1" />
                <div id="contenedor_publicaciones">
                  <div id="contenedor_auxiliar">
                    <?php
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://api.mercadolibre.com/users/" . $id_user . "/items/search?status=active&access_token=" . $AccessToken);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    $result = curl_exec($ch);
                    curl_close($ch);
                    $consulta = json_decode($result);
                    $array_publicaciones = $consulta->results;
                    $items = $consulta->paging->total;
                    if ($items == 0) {
                    ?>
                      <div class="container mt-4">
                        <div class="alert alert-success"><strong>¡No tienes publicaciones activas!</strong></br></div>
                      </div>
                      <?php
                    } else {
                      $total_items = count($array_publicaciones);
                      $contador = 0;
                      while ($contador != $total_items) {
                        $array_publicaciones_veinte = array();
                        $aux = $contador + 19;
                        for ($i = $contador; $i <= $aux; $i++) {
                          if (isset($array_publicaciones[$i])) {
                            ++$contador;
                            array_push($array_publicaciones_veinte, $array_publicaciones[$i]);
                          }
                        }
                        $str_public = implode(",", $array_publicaciones_veinte);
                        $array_publicaciones_veinte = array();
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, "https://api.mercadolibre.com/items?ids=$str_public&attributes=id,title,thumbnail&access_token=" . $AccessToken);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        $result = curl_exec($ch);
                        curl_close($ch);
                        $result = json_decode($result);
                        foreach ($result as $valor) {
                          $id = $valor->body->id;
                          $title = $valor->body->title;
                          $thumbnail = $valor->body->thumbnail;
                      ?>
                          <div class="row align-items-center">
                            <div class="col-sm-2 text-center">
                              <label class="switch public"><input type="checkbox" name="publicaciones" value="<?php echo $id . '~¬' . $title; ?>"><span class="slider"></span></label>
                            </div>
                            <img class="col-sm-3 rounded img-fluid" src="<?php echo $thumbnail; ?>" alt="Imagen publicación" width="100%" height="auto" title="Imagen publicación" data-toggle="tooltip">
                            <small class="text-dark col-sm-7" title="Título publicación" data-toggle="tooltip"><?php echo $title; ?></small>
                          </div>
                          <hr class="m-0 my-05" />
                    <?php }
                      }
                    } ?>
                  </div>
                </div>
              </div>
              <div class="container mt-1">
                <div class="row justify-content-center">
                  <?php
                  if ($items > 50) {
                    $max_paginas = 8;
                    $NroPag = ceil($items / 50);
                  ?>
                    <div id="navegacion">
                      <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm" id="paginacion">
                          <?php
                          if ($NroPag > $max_paginas) {
                            for ($i = 0; $i < $max_paginas; $i++) {
                              if ($i == 0) {
                          ?>
                                <li class='page-item active'><button class='page-link'>1</button></li>
                              <?php } else { ?>
                                <li class='page-item'><button class='page-link' id="<?php echo $i + 1; ?>"><?php echo $i + 1; ?></button></li>
                              <?php }
                            }
                          } else {
                            for ($i = 0; $i < $NroPag; $i++) {
                              if ($i == 0) {
                              ?>
                                <li class="page-item disabled"><button class="page-link" tabindex="-1">1</button></li>
                              <?php } else { ?>
                                <li class="page-item"><button class="page-link" id="<?php echo $i + 1; ?>"><?php echo $i + 1; ?></button></li>
                            <?php }
                            }
                          }
                          if ($NroPag > $max_paginas) {
                            ?>
                            <li class="page-item" id="last_page">
                              <button class="page-link" href="" aria-label="Next">
                                <span aria-hidden="true">&raquo;</span>
                                <span class="sr-only">Next</span>
                              </button>
                            </li>
                          <?php } ?>
                        </ul>
                      </nav>
                    </div>
                  <?php } ?>
                </div>
              </div>
              <script>
                $(document).on("click", "#navegacion li button", function(e) {
                  var id = $(this).attr("id");
                  $(this).attr("disabled", true);
                  $("#contenedor_auxiliar").remove();
                  $.get("paginacion.php", {
                    page: id
                  }, page, "text");

                  function page(data) {
                    $("#contenedor_publicaciones").append("<div id='contenedor_auxiliar'></div>");
                    var result = data.split('|');
                    $.each(result, function(i, resultado) {
                      var items = resultado.split('¬'),
                        id = items[0],
                        title = items[1],
                        thumbnail = items[2],
                        condicional = items[3];
                      if (condicional == 1) {
                        $("#contenedor_auxiliar").append("<div class='row align-items-center'><div class='col-sm-2 text-center'><label class='switch public'><input type='checkbox' name='publicaciones' value='" + id + "~¬" + title + "' checked><span class='slider'></span></label></div><img class='col-sm-3 rounded img-fluid' src='" + thumbnail + "' alt='thumbnail' width='100%' height='auto'><small class='text-dark col-sm-7'>" + title + "</small></div><hr class='m-0 my-05'/>");
                      } else {
                        $("#contenedor_auxiliar").append("<div class='row align-items-center'><div class='col-sm-2 text-center'><label class='switch public'><input type='checkbox' name='publicaciones' value='" + id + "~¬" + title + "'><span class='slider'></span></label></div><img class='col-sm-3 rounded img-fluid' src='" + thumbnail + "' alt='thumbnail' width='100%' height='auto'><small class='text-dark col-sm-7'>" + title + "</small></div><hr class='m-0 my-05'/>");
                      }
                    });
                    var Nropage = <?php echo $NroPag; ?>,
                      max_pages = <?php echo $max_paginas; ?>;
                    id = parseInt(id);
                    $("#paginacion").empty();
                    if (id > (Math.ceil(max_pages / 2) + 1) && Nropage > (Math.ceil(max_pages / 2) + 1)) {
                      $("#paginacion").append("<li class='page-item'><button class='page-link' aria-label='Previous'><span aria-hidden='true'>&laquo;</span><span class='sr-only'>Previous</span></button></li>");
                    }
                    if (id > (Math.ceil(max_pages / 2) + 1)) {
                      for (var i = (id - Math.ceil(max_pages / 2)); i < (Math.ceil(max_pages / 2) + id); i++) {
                        if ((i - 1) == Nropage) {
                          break;
                        } else {
                          if (i == id) {
                            $("#paginacion").append("<li class='page-item active'><button class='page-link' id='" + i + "'>" + i + "</button></li>");
                          } else {
                            $("#paginacion").append("<li class='page-item'><button class='page-link' id='" + i + "'>" + i + "</button></li>");
                          }
                        }
                      }
                    } else {
                      for (var i = 1; i < (max_pages + 1); i++) {
                        if ((i - 1) == Nropage) {
                          break;
                        } else {
                          if (i == id) {
                            $("#paginacion").append("<li class='page-item active'><button class='page-link' id='" + i + "'>" + i + "</button></li>");
                          } else {
                            $("#paginacion").append("<li class='page-item'><button class='page-link' id='" + i + "'>" + i + "</button></li>");
                          }
                        }
                      }
                    }
                    if (Nropage > max_pages & id < (Nropage - Math.ceil(max_pages / 2) + 1)) {
                      $("#paginacion").append("<li class='page-item'><button class='page-link' aria-label='Next'><span aria-hidden='true'>&raquo;</span><span class='sr-only'>Next</span></button></li>");
                    }
                  }
                });
              </script>
            </div>
          </div>
          <div class="d-flex justify-content-center mt-1"><button type="button" class="btn btn-success btn-sm" id="agregar" name="agregar">Crear Respuesta</button></div>
          <div class="d-flex justify-content-center mt-2"><a class="btn btn-success btn-sm" href="index.php">Volver</a></div>
          <input type="hidden" data-toggle="modal" data-target="#loader_modal" id="loader_now">
          <div class="modal fade" id="loader_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false" id="loader_real">
            <div class="modal-dialog" role="document">
              <div class="modal-content bg-transparent no_border mt-5 pt-5">
                <button type="button" class="close bg-transparent" data-dismiss="modal" aria-label="Close" id="close_loader"></button>
                <div class="container mt-5">
                  <div class="row justify-content-center">
                    <div class="col-auto background_loader">
                      <div class="loader algin-self-middle"></div>
                    </div>
                  </div>
                  <b class="row justify-content-center text-white">¡¡Puede tardar unos segundos!!</b>
                </div>
              </div>
            </div>
          </div>
          <div hidden id="publicaciones"></div>
        </div>
      </div>
    </div>
    <script>
      //Seleccionar todas las publicaciones
      $("#selectall").change(function() {
        if ($(this).is(':checked')) {
          $("input[type=checkbox]").prop('checked', true);
        } else {
          $("input[type=checkbox]").prop('checked', false);
        }
        var band = 0;
        if ($(this).is(':checked')) {
          band = 1;
        } else {
          band = 0;
        }
        $.get("array_publicaciones.php", {
          band: band
        }, verificar, "text");

        function verificar(respuesta) {}
      });
      //apagar el checkbox general
      $(document).on('change', '.public input[type=checkbox]', function() {
        if ($(this).is(':checked')) {} else {
          $('#selectall').prop('checked', false);
        }
      });
      //guardar en el array de sesion el item que se ha seleccionado
      $(document).on("click", "#publicaciones_activas input[name='publicaciones']", function() {
        var item = $(this).val(),
          band = 0;
        if ($(this).is(':checked')) {
          band = 1;
        } else {
          band = 0;
        }
        $.get("array_publicaciones.php", {
          item: item,
          band: band
        }, verificar, "text");

        function verificar(respuesta) {
          // console.log(respuesta);
        }
      });
      //agregar palabra clave a las keywords con tecla enter
      $("#filter").keypress(function(e) {
        var band = 0;
        if (e.which == 13) {
          var key = $.trim($("#filter").val());
          if (key === "") {
            $("#filter").val("");
          } else {
            //antes de agregar la palabra debo verificar que no ha sido agregada ya.
            $("div#keywords input").each(function() {
              if ($(this).val() == key) {
                band = 1;
              }
            });
            if (band == 0) {
              var number = key.length;
              var key2 = '<div class="alert alert-success d-sm-inline-block mr-1 mb-1 p-2 rounded"><input type="text" class="input-alert-success" disabled name="' + key + '" value="' + key + '" size="' + number + '"><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>';
              $("div#keywords").append(key2);
              $("#filter").val("");
            }
          }
        }
      });
      //agregar palabra clave a las keywords con boton adicionar
      $("button#adicionar").click(function() {
        var band = 0;
        var key = $.trim($("#filter").val());
        if (key === "") {
          $("#filter").val("");
        } else {
          //antes de agregar la palabradebo verificar que no ha sido agregada ya.
          $("div#keywords input").each(function() {
            if ($(this).val() == key) {
              band = 1;
            }
          });
          if (band == 0) {
            var number = key.length;
            var key2 = '<div class="alert alert-success d-sm-inline-block mr-1 mb-1 p-2 rounded"><input type="text" class="input-alert-success" disabled name="' + key + '" value="' + key + '" size="' + number + '"><button type="button" class="close" data-dismiss="alert"><span>&times;</span></button></div>';
            $("div#keywords").append(key2);
            $("#filter").val("");
          }
        }
      });
      //Agregar respuesta Auto
      $("button#agregar").click(function() {
        $("#loader_now").click();
        var keywords2 = "",
          xk = 0;
        $("div#keywords input").each(function() {
          if (xk == 0) {
            keywords2 = $(this).val();
            xk = 1;
          } else {
            keywords2 = keywords2 + "," + $(this).val();
          }
        });
        var respuesta = $("textarea#respuesta").val();
        $.get("procesar.php", {
          respuestaauto: respuesta,
          palabrasclave: keywords2
        }, verificar, "text");

        function verificar(valor) {
          console.log(valor);
          if (valor == "0") {
            const toast = swal.mixin({
              toast: true,
              position: 'top-end',
              showConfirmButton: false,
              timer: 5500
            });
            toast({
              type: 'success',
              title: '¡La respuesta fue creada Exitosamente! \n ¡Ya puedes Agregar otra respuesta automática!'
            });
            $("#respuesta").val("");
            $("#filter").val("");
            $("div#keywords div").each(function() {
              $(this).remove();
            });
            $("input:checkbox:checked").each(function() {
              $(this).prop('checked', false);
            });
          } else if (valor == "1") {
            const toast = swal.mixin({
              toast: true,
              position: 'top',
              showConfirmButton: false,
              timer: 5500
            });
            toast({
              type: 'warning',
              title: '¡Respuesta Creada Exitosamente! \n ¡No funcionará hasta que no le asignes alguna publicación!'
            });
            $("#respuesta").val("");
            $("#filter").val("");
            $("div#keywords div").each(function() {
              $(this).remove();
            });
            $("input:checkbox:checked").each(function() {
              $(this).prop('checked', false);
            });
          } else {
            const toast = swal.mixin({
              toast: true,
              position: 'top',
              showConfirmButton: false,
              timer: 5500
            });
            toast({
              type: 'error',
              title: '¡Hubo un error! \n ¡Inténtalo de nuevo!'
            });
          }
          setTimeout("$('button#close_loader').click();", 500);
        }
      });
    </script>
    <?php include '../common/footer.php'; ?>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../js/sb-admin.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.29.0/dist/sweetalert2.all.min.js"></script>
  </div>
</body>

</html>