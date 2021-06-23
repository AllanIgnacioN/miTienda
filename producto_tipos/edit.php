<?php
//visualizar errores en php en tiempo ejecucion
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//llamnada al archivo conexion para disponer de la base de datos  
require("../class/conexion.php");
require("../class/rutas.php");

//validar la varaible GET id
if (isset($_GET["id"])) {

    //recuperer el dato que viene en la variable id
    $id = (int) $_GET["id"]; //transforma el dato GET a entero

    // print_r($id);exit;

    //consultar si hay una producto_tipo con el id enviado por GET
    $res = $mbd->prepare("SELECT id, nombre FROM producto_tipos WHERE id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $producto_tipo = $res->fetch();

    //validador formulario
    if (isset($_POST["confirm"]) && $_POST["confirm"] == 1) {
        $nombre = trim(strip_tags($_POST["nombre"]));

        if (!$nombre) {
            $msg = "Debe ingresar nombre ded producto";
        } else {
            //procedemos a actualizar el dato ingresado por el usuario en la tabla producto_tipo
            $res = $mbd->prepare("UPDATE producto_tipos SET nombre = ? WHERE id = ?");
            $res->bindParam(1, $nombre);
            $res->bindParam(2, $id);
            $res->execute();

            $row = $res->rowCount(); //recuperamos el numero de filas afectadas por la consulta

            if ($row) {
                $_SESSION['success'] = 'El producto tipo se ha modificado correctamente';
                header("Location: show.php?id=" . $id);
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

    <title>Producto Tipos</title>

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
            <div class="col-md-6 offset-md-3">
                <h1>Producto tipo</h1>
                <!-- mensaje de registro de producto_tipo -->
                <?php if (isset($msg)) : ?>
                    <p class="alert alert-danger">
                        <?php echo $smg ?>
                    </p>
            </div>
        <?php endif; ?>
        <!-- listar las producto_tipo que estan registrados -->
        <?php
        if ($producto_tipo) : ?>
            <form action="" method="post">
                <div class="form-group mb-3">
                    <label for="">Producto tipo <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" value="<?php echo $producto_tipo["nombre"]; ?>" class="form-control" placeholder="Ingrese el Nombre del producto">
                </div>
                <div class="form-group mb-3">
                    <input type="hidden" name="confirm" value="1">
                    <button type="submit" class="btn btn-primary">Editar</button>
                    <a href="show.php?id=<?php echo $producto_tipo["id"]; ?>" class="btn btn-link">Volver</a>
                </div>

            </form>
        <?php else : ?>
            <p class="text-info">El dato solicitado no existe</p>
        <?php endif ?>
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