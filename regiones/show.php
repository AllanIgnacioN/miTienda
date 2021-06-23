<?php
//visualizar errores en php en tiempo de ejecucion
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//llamada al archivo conexion para disponer de los datos de la base de datos
require('../class/conexion.php');
require('../class/rutas.php');

//validar la variable GET id
if (isset($_GET['id'])) {

    //recuperar el dato que viene en la variable id
    $id = (int) $_GET['id']; //transforma el dato $_GET a entero

    //print_r($id);exit;

    //consultar si hay una region con el id enviado por GET
    $res = $mbd->prepare("SELECT id, nombre, created_at, updated_at FROM regiones WHERE id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $region = $res->fetch();

    /* echo '<pre>';
    print_r($region);exit;
    echo '</pre>'; */
}

?>

<?php if (isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] != 1) : ?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Regiones</title>
        <!--Enlaces CDN de Bootstrap-->
        <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script> -->
        <link rel="stylesheet" href="../css/bootstrap.min.css">
        <script src="../js/bootstrap.min.js"></script>
    </head>

    <body>

        <div class="container">
            <!-- seccion de cabecera del sitio -->
            <header>
                <!-- navegador principal -->
                <?php include('../partials/menu.php'); ?>
            </header>

            <!-- seccion de contenido principal -->
            <section>
                <div class="col-md-6 offset-md-3">
                    <h1>Regiones</h1>
                    <!-- mensaje de registro de roles -->
                    <?php include('../partials/mensajes.php'); ?>

                    <!-- listar los roles que estan registrados -->
                    <?php if ($region) : ?>
                        <table class="table table-hover">
                            <tr>
                                <th>Id:</th>
                                <td><?php echo $region['id']; ?></td>
                            </tr>
                            <tr>
                                <th>Región:</th>
                                <td><?php echo $region['nombre']; ?></td>
                            </tr>
                            <tr>
                                <th>Creado:</th>
                                <td>
                                    <?php
                                    $fecha = new DateTime($region['created_at']);
                                    echo $fecha->format('d-m-Y H:i:s');
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Actualizado:</th>
                                <td>
                                    <?php
                                    $fecha = new DateTime($region['updated_at']);
                                    echo $fecha->format('d-m-Y H:i:s');
                                    ?>
                                </td>
                            </tr>
                        </table>
                        <p>
                            <a href="index.php" class="btn btn-link">Volver</a>
                            <?php if ($_SESSION['usuario_rol'] == 3) : ?>
                                <a href="edit.php?id=<?php echo $region['id'] ?>" class="btn btn-primary">Editar</a>
                                <a href="../comunas/add.php?id=<?php echo $id; ?>" class="btn btn-success">Agregar Comuna</a>
                            <?php endif; ?>
                        </p>
                    <?php else : ?>
                        <p class="text-info">El dato solicitado no existe</p>
                    <?php endif; ?>
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