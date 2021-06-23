<?php
//visualizar errores en php en tiempo ejecucion
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require("../class/conexion.php");
require("../class/rutas.php");


if (isset($_GET["id_producto"])) {
    $id_producto = (int) $_GET["id_producto"];

    $res = $mbd->prepare("SELECT id, nombre FROM productos WHERE id = ?");
    $res->bindParam(1, $id_producto);
    $res->execute();

    $producto = $res->fetch();

    //lista de atributos
    $res = $mbd->query("SELECT id, nombre FROM atributos ORDER BY nombre");
    $atributos = $res->fetchall();

    if (isset($_POST["confirm"]) && $_POST["confirm"] == 1) {
        $atributo = filter_var($_POST["atributo"], FILTER_VALIDATE_INT);
        $valor = trim(strip_tags($_POST["valor"]));

        if (!$atributo) {
            $msg = "Seleccione un atributo";
        } elseif (!$valor) {
            $msg = "Ingrese un valor";
        } else {
            //validamos que el articulo seleccionado no este en el producto ingresado
            $res = $mbd->prepare("SELECT id  FROM atributo_producto WHERE atributo_id = ? AND producto_id = ?");
            $res->bindParam(1, $atributo);
            $res->bindParam(2, $id_producto);
            $res->execute();

            $atrib_prod = $res->fetch();

            if ($atrib_prod) {
                $msg = "El atributo seleccionado ya esxiste en este producto... intente con otro";
            } else {
                //registrar el atributo y el producto seleccionado
                $res = $mbd->prepare("INSERT INTO atributo_producto(atributo_id, producto_id, valor) VALUES(?, ?, ?)");
                $res->bindParam(1, $atributo);
                $res->bindParam(2, $id_producto);
                $res->bindParam(3, $valor);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION["success"] = "El atributo se ha registrado correctamente";
                    header("Location: " . PRODUCTOS . "show.php?id=" . $id_producto);
                }
            }
        }
    }
}

?>
<?php if (isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 3) : ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Atributos-Producto</title>
        <!--Enlaces CDM de Bootstrap-->
        <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script> -->
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <script src="../js/bootstrap.min.js"></script>
    </head>

    <body>

        <div class="container">
            <!-- Seccion de cabecera del sitio  -->
            <header>
                <!-- Navegador principal -->
                <?php include("../partials/menu.php"); ?>
            </header>

            <!-- seccion de contenido principal -->
            <section>

                <div class="cal-md-6 offset-md-3">
                    <h1>Nuevo atributo para <?php echo $producto["nombre"]; ?></h1>

                    <!-- mensaje de validacion y errores -->
                    <?php if (isset($msg)) : ?>
                        <p class="alert alert-danger">
                            <?php echo $msg; ?>
                        </p>
                    <?php endif; ?>
                    <?php if ($producto) : ?>
                        <form action="" method="post">
                            <div class="form-group mb-3">
                                <label for="atributo">Atributo <span class="text-danger">*</span></label>
                                <select name="atributo" class="form-control">
                                    <option value="">Seleccione...</option>

                                    <?php foreach ($atributos as $atributo) : ?>
                                        <option value="<?php echo $atributo['id']; ?>">
                                            <?php echo $atributo['nombre']; ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label for="valor">Valor <span class="text-danger">*</span></label>
                                <input type="text" name="valor" class="form-control" placeholder="Ingrese el valor del atributo">
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="confirm" value="1">
                                <button type="submit" class="btn btn-primary">Enviar</button>
                                <a href="index.php" class="btn btn-link">Volver</a>
                            </div>
                        </form>
                    <?php else : ?>
                        <p class="text-info">El dato no existe</p>
                    <?php endif; ?>
                </div>
        </div>

    </body>

    </html>
<?php else : ?>
    <script>
        alert('Acceso indebido');
        window.location = "<?php echo BASE_URL; ?>";
    </script>
<?php endif; ?>