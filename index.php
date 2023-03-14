<?php
spl_autoload_register(fn($clase) => require "$clase.php");

// control de errores
error_reporting(E_ALL);
ini_set("display_errors", true);

// si ya existe sesion iniciada redirigimos a sitio.php
$sesion = Sesion::get_instance();
if ($sesion->isset(Sesion::PARAM_USERNAME)) {
    header("Location:sitio.php");
    exit();
}

// estado inicial de la página
$submit = $_POST['submit'] ?? null;
$param = $_GET['param'] ?? null;

// leemos el valor del parametro en url
if (isset($param)) {
    switch ($param) {
        case "logout": $mensaje = "Hasta luego";
        break;
        case "error": $mensaje = "No existe el usuario o contraseña introducidos.";
    }
}

// comprobamos opcion seleccionada por el usuario
if (isset($submit)) {
    switch ($submit) {
        case "Acceder": iniciar_sesion($sesion) ?? null;  // el usuario desea iniciar sesion
            break;
        default:
            //TODO: posibles opciones a implementar como recuperar contraseña;
    }
}

/////// funciones internas ///////
// inicia la sesión en funcion de los datos introducidos
function iniciar_sesion($sesion) {
    $bd = new BaseDatos();
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($bd->validar_usuario($username, $password)) {  // exito al validar usuario
        $sesion::set_param(Sesion::PARAM_USERNAME, $username);
//        session_start();
//        $_SESSION['usuario'] = $username;
        header("Location:sitio.php");  // redirigimos a sitio.php
        exit();
    }

    // error al validar usuario
    header("Location:index.php?param=error");
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
    <title>Iniciar sesión</title>
</head>
<body>
<h1>Bienvenido</h1>
<p>Inicia sesión para continuar</p>
<fieldset>
    <legend class="title">Datos de conexión</legend>
    <form action="index.php" method="post">
        <label for="username">Usuario </label>
        <input type="text" name="username" id="">
        <br>
        <label for="password">Contraseña </label>
        <input type="text" name="password" id="">
        <hr>
        <input class="button action" type="submit" value="Acceder" name="submit">
    </form>
</fieldset>
<?php
if (isset($mensaje)) {
    echo "<span class='error'>$mensaje</span>";
}
?>
</body>
</html>
