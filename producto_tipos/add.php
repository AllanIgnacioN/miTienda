<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require("../class/conexion.php");
require("../class/rutas.php");

//validar que los datos del formulario lleguen via post
if (isset($_POST["confirm"]) && $_POST["confirm"] == 1) {

    //print_r($_POST);exit;

    $nombre = trim(strip_tags($_POST["nombre"]));

    if (!$nombre) {
        $msg = "Debe ingresar un producto";
    } else {
        //verificar que el atributo que se ingresa no exista en la tabla de producto_tipos
        $res = $mbd->prepare("SELECT id FROM producto_tipos WHERE nombre = ?");
        $res->bindParam(1, $nombre);
        $res->execute();
        $producto_tipo = $res->fetch();

        //print_r($nombre);exit;

        if ($producto_tipo) {
            $msg = "El producto tipo que a ingresado ya existe";
        } else {

            //preparamos la consulta antes de ser enviada a la base de datos
            $res = $mbd->prepare("INSERT INTO producto_tipos VALUES(null, ?)");
            //sanitizamos el dato indicando cual es la posicion del ? en el orden en el que aparece en la consulta anteriro
            $res->bindParam(1, $nombre);
            //ejecutamos la consulta sanitizada
            $res->execute();
            //rescatamos el numero de la filas insertadas en la tabla
            $row = $res->rowCount();

            // echo"<pre>";
            // print_r($row);exit;
            // echo"</pre>";

            if ($row) {
                $_SESSION['success'] = 'El producto tipo se ha registrado correctamente';
                header("Location: index.php?id=");
            }
        }
    }
}

?>
<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 3): ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Producto Tipo</title>
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
                <h1>Nuevo producto tipo</h1>

                <!-- mensaje de validacion y errores -->
                <?php if (isset($msg)) : ?>
                    <p class="alert alert-danger">
                        <?php echo $msg; ?>
                    </p>
                <?php endif; ?>

                <form action="" method="post">
                    <div class="form-group mb-3">
                        <label for="">Producto tipo <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" placeholder="Ingrese el Nombre del producto tipo">
                    </div>
                    <div class="form-group mb-3">
                        <input type="hidden" name="confirm" value="1">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <a href="index.php" class="btn btn-link">Volver</a>
                    </div>

                </form>
            </div>


        </section>

        <!-- pie de pagina -->
        <footer>
            footer
        </footer>
    </div>
</body>

</html>
<?php else: ?>
    <script>
        alert('Acceso indebido');
        window.location = "<?php echo BASE_URL; ?>";
    </script>

<?php endif; ?>