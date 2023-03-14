<?php
class Sesion {
    // declaracion de constantes
    const SESION_INICIADA = true;
    const SESION_NO_INICIADA = false;

    // variables de sesión
    const PARAM_USERNAME = "username";
    const PARAM_TABLE = "table";
    const PARAM_COD = "cod";

    // variables definidas
    private $session_state = Sesion::SESION_NO_INICIADA;
    private static $instance;

    public function __construct() {}

    public static function get_instance() {
        if (!isset(Sesion::$instance)) {
            self::$instance = new Sesion();
        }

        self::$instance->start();

        return self::$instance;
    }
    public function start() {
        if ($this->session_state == self::SESION_NO_INICIADA) {
            $this->session_state = session_start();
        }

        return $this->session_state;
    }
    public function destroy() {
        if ($this->session_state == self::SESION_INICIADA) {
            $this->session_state = !session_destroy();
            unset($_SESSION);

            return !$this->session_state;
        }
    }
    public static function set_param(string $param, string $value) {
        $_SESSION[$param] = $value;
    }
    public static function get_param(string $param) {
        if (isset($_SESSION[$param])) {
            return $_SESSION[$param];
        }
    }
    public function isset(string $param){
        return isset($_SESSION[$param]);
    }
    public function unset(string $param) {
        unset($_SESSION[$param]);
    }
}