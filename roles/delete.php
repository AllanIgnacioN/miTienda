<?php
require("../class/conexion.php");
require("../class/rutas.php");

session_start();

if (isset($_SESSION["autenticado"]) && $_SESSION["usuario_rol"] == 3) {

    if (isset($_GET["id"])) {

        $id = (int) $_GET["id"]; //guardamos el id que viene por GET en la variable id, obligandola a que sea numero entero

        //consultar a la tabla roles si existe un registro (fila) asociado al id recibido
        $res = $mbd->prepare("SELECT id FROM roles WHERE id = ?");
        $res->bindParam(1, $id); //sanitizamos la variable id antes de ejecutarse la consulta
        $res->execute(); //ejecutamos la consulta
        $rol = $res->fetch(); //recuperamos la fila si es que existe

        //validamos la existencia del rol que se desea eliminar
        if ($rol) {
            //verificamos que el rol no tenga una persona asociada
            $res = $mbd->prepare("SELECT id FROM personas WHERE rol_id = ?");
            $res->bindParam(1, $id);
            $res->execute();

            $persona = $res->fetch();

            if (!$persona) {
                //procesamos a eliminar el rol solicitado
                $res = $mbd->prepare("DELETE FROM roles WHERE id = ?"); //para eliminar datos
                $res->bindParam(1, $id); //sanitizamos la variable id
                $res->execute(); //ejecutamos la consulta

                $row = $res->rowCount(); //recuperamos el numero de la fila afectada (=1)


                if ($row) {
                    $_SESSION["success"] = "El rol se ha eliminado correctamente";
                    header("Location: index.php");
                }
            } else {
                $_SESSION["danger"] = "El rol no se a podido eliminar";
                header("Location: index.php");
            }
        }
    }
} else {
    echo "<script>  
        alert('Acceso indebido');
        window.location = 'http://localhost/miTienda/';
    </script>";
}
