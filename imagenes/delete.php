<?php
require("../class/conexion.php");
require("../class/rutas.php");

session_start();
if (isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 3) {
    if (isset($_POST["confirm"]) && $_POST["confirm"] == 1) {
        $id = (int) $_POST["id"];

        //verificamos si hay una imagen con el id recibido
        $res = $mbd->prepare("SELECT id FROM imagenes WHERE id = ?");
        $res->bindParam(1, $id);
        $res->execute();

        $imagen = $res->fetch();

        if ($imagen) {
            //eliminamos la imagen
            $res = $mbd->prepare("DELETE FROM imagenes WHERE id = ?");
            $res->bindParam(1, $id);
            $res->execute();

            $row = $res->rowCount();

            if ($row) {
                $_SESSION["success"] = "La imagen se ha eliminado correctamente";
                header('Location: index.php');
            }
        } else {
            $_SESSION["danger"] = "La imagen no se ha podido eliminar... intente nuevamente";
            header('Location: show.php?id=' . $id);
        }
    }
} else {
    echo "<script>  
        alert('Acceso indebido');
        window.location = 'http://localhost/miTienda/';
    </script>";
}
