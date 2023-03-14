<?php
spl_autoload_register(fn ($clase) => require "$clase.php");
class Header
{
    public static function create(Sesion $sesion, string $site): string {
        $username = $sesion::get_param(Sesion::PARAM_USERNAME);
        return "
            <div class='header'>
                <h1>Bienvenido $username</h1>
                <form action='$site' method='post'>
                    <input class='button warning' type='submit' value='Cerrar sesión' name='submit'>
                </form>
            </div>
        ";
    }

    public static function cerrar_sesion(Sesion $sesion): bool {
        return $sesion->destroy();
    }
}