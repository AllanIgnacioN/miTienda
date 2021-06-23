<?php
//visualizar errores en php en tiempo ejecucion
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//llamnada al archivo conexion para disponer de la base de datos  
require("../class/conexion.php");
require("../class/rutas.php");
//print_r($_GET);exit;

//validar lla varaible GET id
if (isset($_GET["id"])) {

    //recuperer el dato que viene en la variable id
    $id = (int) $_GET["id"]; //transforma el dato GET a entero

    //print_r($id);exit;

    //consultar si hay una marca con el id enviado por GET
    $res = $mbd->prepare("SELECT id, nombre, created_at, updated_at FROM marcas WHERE id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $marca = $res->fetch();
    //print_r($marca);exit;
}

?>
<?php if (isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] != 1) : ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Marcas</title>

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
                    <h1>Marcas</h1>
                    <!-- mensaje de registro de marcas -->
                    <?php include("../partials/mensajes.php");  ?>
                    <!-- listar las marcas que estan registrados -->
                    <?php
                    if ($marca) : ?>
                        <table class="table table-hover">
                            <tr>
                                <th>Id:</th>
                                <td><?php echo $marca["id"]; ?></td>
                            </tr>
                            <tr>
                                <th>Marca:</th>
                                <td><?php echo $marca["nombre"]; ?></td>
                            </tr>
                            <tr>
                                <th>Creado:</th>
                                <td>
                                    <?php
                                    $fecha = new DateTime($marca["created_at"]);
                                    echo $fecha->format("d-m-y h:i:s");
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Actualizado:</th>
                                <td>
                                    <?php
                                    $fecha = new DateTime($marca["updated_at"]);
                                    echo $fecha->format("d-m-y h:i:s");
                                    ?>
                                </td>
                            </tr>
                        </table>
                        <p>
                            <a href="index.php" class="btn btn-link">Volver</a>
                            <?php if ($_SESSION['usuario_rol'] == 3) : ?>
                                <a href="edit.php?id=<?php echo $marca["id"] ?>" class="btn btn-primary">Editar</a>
                                <a href="delete.php?id=<?php echo $marca["id"] ?>" class="btn btn-warning">Eliminar</a>
                            <?php endif; ?>
                        </p>
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
<?php else : ?>
    <script>
        alert('Acceso indebido');
        window.location = "<?php echo BASE_URL; ?>";
    </script>

<?php endif; ?>