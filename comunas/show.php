<?php
//visualizar errores en php en tiempo ejecucion
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

//llamnada al archivo conexion para disponer de la base de datos  
require("../class/conexion.php");
require("../class/rutas.php");

if (isset($_GET["id"])) {

    $id = (int) $_GET["id"];

    //creamos la consulta a la tabla comunas de acuerdo al id ingresado por el sistema
    $res = $mbd->prepare("SELECT c.id, c.nombre as comuna, c.created_at, c.updated_at, r.nombre as region FROM comunas as c INNER JOIN regiones as r ON c.region_id = r.id WHERE c.id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $comuna = $res->fetch(); //pido a PDO que disponibilice la comuna consultada por id

    // echo"<pre>";
    // print_r($comuna);exit;
    // echo"</pre>";
}



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Comunas</title>

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
                <h1>Comunas</h1>
                <!-- mensaje de registro de las comunas -->
                <?php include("../partials/mensajes.php");  ?>

                <?php if ($comuna) : ?>
                    <table class="table table-hover">
                        <tr>
                            <th>Comuna:</th>
                            <td> <?php echo $comuna['comuna']; ?> </td>
                        </tr>
                        <tr>
                            <th>Región:</th>
                            <td> <?php echo $comuna['region']; ?> </td>
                        </tr>
                        <tr>
                            <th>Creado:</th>
                            <td>
                                <?php
                                $created = new DateTime($comuna['created_at']);
                                echo $created->format('d-m-Y H:i:s');
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Actualizado:</th>
                            <td>
                                <?php
                                $updated = new DateTime($comuna['updated_at']);
                                echo $updated->format('d-m-Y H:i:s');
                                ?>
                            </td>
                        </tr>
                    </table>
                    <p>
                        <?php if ($_SESSION['usuario_rol'] == 3) : ?>
                            <a href="edit.php?id=<?php echo $id; ?>" class="btn btn-primary">Editar</a>
                        <?php endif; ?>
                        <a href="index.php" class="btn btn-link">Volver</a>
                    </p>
                <?php else : ?>
                    <p class="text-info">El dato no existe</p>
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