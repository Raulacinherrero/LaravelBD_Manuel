<?php
// control de errores
error_reporting(E_ALL);
ini_set("display_errors", true);

// comprobamos si existe sesion abierta, sino redirige a index.php
$sesion = Sesion::get_instance();
$usuario = Sesion::get_param(Sesion::PARAM_USERNAME);
if (is_null($usuario)) {
    header("Location:index.php");
    exit();
}