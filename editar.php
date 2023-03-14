<?php
spl_autoload_register(fn($clase) => require "$clase.php");

require "iniciar.php";

// estado inicial
$sesion = Sesion::get_instance();
$bd = new BaseDatos();
$tabla = Sesion::get_param(Sesion::PARAM_TABLE);
$codigo = $_POST['cod'] ?? null;

// control de las opciones del input seleccionado por el usuario
$opcion = $_POST['submit'] ?? null;
if (isset($opcion)) {
    switch ($opcion) {
        case "Cerrar sesión":  // destruye la sesión actual y redirige a index.php
            if (Header::cerrar_sesion($sesion)) {
                header("Location:index.php?param=logout");
                exit();
            };
            break;
        case "Editar":
            $fila = $bd->obtener_fila($codigo, $tabla);
            $inputs = Interfaz::generar_inputs_editables($fila);
            break;
        case "Confirmar cambios":
            try {
                $campos = $_POST['campos'];
                $bd->update_fila($campos, $codigo, $tabla);
                header("Location:sitio.php?param=editado");
            } catch (Exception $error) {
                header("Location:sitio.php?error={$error->getMessage()}");
            } finally {
                exit();
            }
    }
}
else {
    header("Location:sitio.php");
    exit();
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
    <title>Editar campo</title>
</head>
<body>
    <!-- /////// CABECERA ///////-->
    <?=Header::create($sesion, "sitio.php")?>
    <fieldset>
        <legend>Editar</legend>
        <form action="editar.php" method="post">
            <?="$inputs"?>
            <input type='hidden' value='<?="$codigo"?>' name='cod'>
            <hr>
            <input class="button action" type="submit" value="Confirmar cambios" name="submit">
        </form>
    </fieldset>
</body>
</html>
