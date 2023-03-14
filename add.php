<?php
spl_autoload_register(fn($clase) => require "$clase.php");

require "iniciar.php";

// estado inicial
$sesion = Sesion::get_instance();
$bd = new BaseDatos();
$tabla = Sesion::get_param(Sesion::PARAM_TABLE);

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
        case "Añadir":
            $campos = $bd->obtener_columnas($tabla);
            $inputs = Interfaz::generar_inputs_add($campos);
            break;
        case "Confirmar cambios":
            try {
                $valores = $_POST['campos'];
                $bd->add_fila($valores, $tabla);
                header("Location:sitio.php?param=added");
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
    <title>Añadir campo</title>
</head>
<body>
<!-- /////// CABECERA ///////-->
<?=Header::create($sesion, "sitio.php")?>
<fieldset>
    <legend>Añadir</legend>
    <form action="add.php" method="post">
        <?="$inputs"?>
        <hr>
        <input class="button action" type="submit" value="Confirmar cambios" name="submit">
    </form>
</fieldset>
</body>
</html>
