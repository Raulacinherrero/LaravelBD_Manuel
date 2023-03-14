<?php
require "credenciales.php";
class BaseDatos {
    private $conexion;

    public function __construct() {
        try {
            $this->conexion = new mysqli(HOST, USUARIO, PASS, NAME_BD);
            //var_dump($this);
        }
        catch (Exception $error) {
            die("Error conectando a la base de datoss ".$error->getMessage());
        }
    }

    public function validar_usuario(String $username, String $password): bool {
        // con mysqli_stmt
        $query = "SELECT * FROM usuarios WHERE nombre = ? AND password = ?" ;

        $statement = $this->conexion->stmt_init();
        $statement->prepare($query);
        $statement->bind_param("ss", $username, $password);
        $statement->execute();
        $statement->store_result();

        var_dump($statement);

        if ($statement->num_rows > 0)
            return true;
        else
            return false;

        // con myslqi
//        $query = "select * from usuarios where nombre = '$username' and password = '$password'" ;
//        $resultado = $this->conexion->query($query);
//        var_dump($resultado);
//        if ($resultado->num_rows > 0)
//            return true;
//        else
//            return false;
    }

    public function ejecutar_consulta(string $query, array $params) {
        $statement = $this->conexion->stmt_init();
        $statement->prepare($query);

        $types = "";
        foreach ($params as $param) {
            $types .= "s";
        };

        $statement->bind_param($types, ...$params);
        $statement->execute();
        $statement->store_result();

        //TODO pendiente Manuel
        return $statement;
    }

    // CREAR (INSERT)
    public function add_fila(array $valores, string $tabla) {
        $sql_columns = "";
        $sql_values = "";
        $counter = 0;
        foreach ($valores as $campo => $valor) {
            $counter++;
            if ($counter < sizeof($valores)) {
                $sql_columns.= "$campo, ";
                $sql_values.= "'$valor', ";
            } else {
                $sql_columns.= "$campo";
                $sql_values.= "'$valor'";
            }
        }

        $query = "INSERT INTO $tabla ($sql_columns) VALUES ($sql_values)";

        return $this->conexion->query($query);
    }

    //LEER (SELECT)
    public function obtener_listado_tablas() {
        $query = "SHOW TABLES";
        $resultado = $this->conexion->query($query);

        $valores = [];
        $fila = $resultado->fetch_array();
        while ($fila) {
            $valores[] = $fila[0];
            $fila = $resultado->fetch_array();
        }

        return $valores;
    }

    public function obtener_columnas(string $tabla) {
        $query = "SHOW COLUMNS FROM $tabla";
        $resultado = $this->conexion->query($query);

        $valores = [];
        $fila = $resultado->fetch_assoc();
        while ($fila) {
            $valores[] = $fila;
            $fila = $resultado->fetch_assoc();
        }

        $columnas = [];
        foreach ($valores as $columna) {
            $columnas[] = $columna["Field"];
        }
        return $columnas;
    }

    public function obtener_todo(string $nombre_tabla): array {
        $query = "SELECT * FROM $nombre_tabla";
        $resultado = $this->conexion->query($query);

        $valores = [];
        $fila = $resultado->fetch_assoc();
        while ($fila) {
            $valores[] = $fila;
            $fila = $resultado->fetch_assoc();
        }

        return $valores;
    }

    public function obtener_fila(string $codigo, string $tabla) {
        $query = "SELECT * FROM $tabla WHERE cod = '$codigo'";
        $resultado = $this->conexion->query($query);

        $fila = $resultado->fetch_assoc();

        return $fila;
    }

//    public function obtener_fila(string $codigo, string $tabla) {
//        $query = "SELECT * FROM $tabla WHERE cod = ?";
//        $resultado = $this->ejecutar_consulta($query, [$codigo]);
//
//        //TODO pendiente Manuel
//    }

    //ACTUALIZAR (UPDATE)
    public function update_fila(array $campos, string $codigo, string $tabla) {
        $query = "UPDATE $tabla SET ";
        $counter = 0;
        foreach ($campos as $campo => $valor) {
            $counter++;
            if ($counter < sizeof($campos))
                $query.= "$campo = '$valor', ";
            else
                $query.= "$campo = '$valor' ";
        }
        $query.= "WHERE cod = '$codigo'";

        return $this->conexion->query($query);
    }


    //BORRAR (DELETE)
    public function borrar_fila(string $codigo, string $tabla) {
        $query = "DELETE FROM $tabla WHERE cod = '$codigo'";
        return $this->conexion->query($query);
    }

}