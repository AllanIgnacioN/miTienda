<?php
//visualizar errores en php en tiempo de ejecucion
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require("../class/conexion.php");
require("../class/rutas.php");

if (isset($_GET["id"])) {

    $id = (int) $_GET["id"]; //guardamos el id que viene por GET en la variable id, obligandola a que sea numero entero

    //consultar a la tabla de marcas si existe un registro (fila) asociado al id recibido
    $res = $mbd->prepare("SELECT id FROM marcas WHERE id = ?");
    $res->bindParam(1, $id); //sanitizamos la variable id antes de ejecutarse la consulta
    $res->execute(); //ejecutamos la consulta
    $marca = $res->fetch(); //recuperamos la fila si es que existe

    //validamos la existencia de la marca que se desea eliminar
    if ($marca) {
        //procesamos a eliminar el rol solicitado
        $res = $mbd->prepare("DELETE FROM marcas WHERE id = ?"); //para eliminar datos
        $res->bindParam(1, $id); //sanitizamos la variable id
        $res->execute(); //ejecutamos la consulta

        $row = $res->rowCount(); //recuperamos el numero de la fila afectada (=1)

        if ($row) {
            $_SESSION["success"] = "La imagen se ha eliminado correctamente";
            header('Location: index.php');
        }
    } else {
        $_SESSION["danger"] = "La imagen no se ha podido eliminar... intente nuevamente";
        header('Location: show.php?id=' . $id);
    }
}
