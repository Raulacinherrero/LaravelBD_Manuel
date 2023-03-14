<?php
spl_autoload_register(fn($clase) => require "$clase.php");

require "iniciar.php";

// estado inicial de la página
$sesion = Sesion::get_instance();
$bd = new BaseDatos();
$array_tablas = $bd -> obtener_listado_tablas();
$inputs = Interfaz::generar_botones_tablas($array_tablas);

// control de los parametros de get para notificacion al usuario
if(isset($_GET['error'])) {
    $mensaje = $_GET['error'];
}
else if (isset($_GET['param'])) {
    $param = $_GET['param'];
    switch ($param) {
        case "editado": $mensaje = "Cambios aplicados con éxito";
            break;
        case "borrado": $mensaje = "Borrado con éxito";
            //TODO implementar posibles opciones
    }
}

// control de las opciones del input seleccionado por el usuario
$submit = $_POST['submit'] ?? null;
if (isset($submit)) {
    switch ($submit) {
        case "Cerrar sesión":  // destruye la sesion actual y redirige a index.php
            if (Header::cerrar_sesion($sesion)) {
                header("Location:index.php?param=logout");
                exit();
            };
            break;
        case "Borrar":  // borra el objeto seleccionado si es posible
            $codigo = $_POST["cod"] ?? null;
            $tabla = Sesion::get_param(Sesion::PARAM_TABLE);
            try {
                $bd->borrar_fila($codigo, $tabla);
                header("Location:sitio.php?param=borrado");
            } catch (Exception $error) {
                header("Location:sitio.php?error={$error->getMessage()}");
            }
            break;
        default:  // selección de una tabla
            if (in_array($submit, $array_tablas)) {
                $valores = $bd->obtener_todo($submit);
                $tabla = Interfaz::generar_tabla($valores, $submit);
                Sesion::set_param(Sesion::PARAM_TABLE, $submit);
            }
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="style.css" />
    <title>Página principal</title>
</head>
<body>
    <!-- /////// CABECERA ///////-->
    <?=Header::create($sesion, "sitio.php")?>
    <fieldset>
        <legend class="title">Tablas disponibles</legend>
        <form action="sitio.php" method="post">
            <?="$inputs"?>
        </form>
    </fieldset>
    <?php
    if (isset($mensaje)) {
        echo "<span class='error'>$mensaje</span>";
    }
    if (isset($tabla)) {
        echo $tabla;
    }
    ?>
</body>
</html>