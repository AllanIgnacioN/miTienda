<?php
require("../class/conexion.php");
require("../class/rutas.php");

session_start();

if (isset($_GET["id"])) {
    $id = (int) $_GET["id"];

    $res = $mbd->prepare("SELECT id, producto_id FROM atributo_producto WHERE id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $atrib_prod = $res->fetch();

    if ($atrib_prod) {
        //eliminamos el registro
        $res = $mbd->prepare("DELETE FROM atributo_producto WHERE id = ?");
        $res->bindParam(1, $id);
        $res->execute();

        $row = $res->rowCount();

        if ($row) {
            $_SESSION["success"] = "El atributo se ha eliminado correctamente";
            header("Location: ../productos/show.php?id=" . $atrib_prod["producto_id"]);
        }else {
            $_SESSION["danger"] = "El atributo no existe";
            header("Location: ../productos/show.php?id=" . $atrib_prod["producto_id"]);
        }
    }
}