<?php

class Interfaz {
    public static function generar_tabla(array $valores, string $nombre_tabla): string {
        // generamos html de la tabla (también boton añadir)
        $tabla = "
            <form action='add.php' method='post'>
                <input class='button action' type='submit' value='Añadir' name='submit'>
            </form>
            <table>
                <caption>$nombre_tabla</caption>"
        ;
        $fila = $valores[0];  // seleccionamos la primera fila

        // comprobamos si existe campo cod en la tabla seleccionada
        $hasCod = array_key_exists("cod", $fila);

        // generamos los títulos
        $tabla.="<tr>";
        foreach ($fila as $campo => $valor) {  // obtenemos los key con los nombres de los campos
            $tabla.= "<th>$campo</th>";
        }
        if ($hasCod) {
            $tabla.="<th>Acciones</th>";
        }
        $tabla.="</tr>";

        // generamos el valor de los campos
        foreach ($valores as $fila => $campos) {
            $tabla.="<tr>";
            foreach ($campos as $valor) {
                $tabla.="<td>$valor</td>";
            }
            if ($hasCod) {  // si tenemos campo cod, habilitamos las opciones
                $tabla.= "
                <td>
                    <form class='button' action='editar.php' method='post'>
                        <input class='button action' type='submit' value='Editar' name='submit'>
                        <input type='hidden' value='{$campos['cod']}' name='cod'>
                    </form>
                    <form class='button' action='sitio.php' method='post'>
                        <input class='button warning' type='submit' value='Borrar' name='submit'>
                        <input type='hidden' value='{$campos['cod']}' name='cod'>
                    </form>
                </td>
                ";
            }
            $tabla.="</tr>";
        }

        // cerramos el html de la tabla
        $tabla.= "<table>";

        return $tabla;
    }

    public static function generar_inputs_add(array $campos): string {
        $inputs = "";
        foreach ($campos as $campo) {
            $inputs.= "
                <label for='$campo'>$campo</label>
                <input type='text' value='' name='campos[$campo]'>
                <br>";
        }

        return $inputs;
    }
    public static function generar_inputs_editables(array $fila): string {
        $inputs = "";
        foreach ($fila as $campo => $valor) {
            $inputs.= "
                <label for='$campo'>$campo</label>
                <input type='text' value='$valor' name='campos[$campo]'>
                <br>";
        }

        return $inputs;
    }

    public static function generar_botones_tablas(array $tablas): string {
        $inputs = "";
        foreach ($tablas as $nombre_tabla) {
            $inputs.= "<input class='button' type='submit' value='$nombre_tabla' name='submit'>";
        }
        return $inputs;
    }
}
