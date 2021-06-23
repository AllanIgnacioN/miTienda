<?php
//visualizar errores en php en tiempo ejecucion
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
//print_r($_SESSION);exit;

//llamnada al archivo conexion para disponer de la base de datos  
require("../class/conexion.php");
require("../class/rutas.php");
//print_r($_GET);exit;

//validar lla varaible GET id
if (isset($_GET["id"])) {

    //recuperer el dato que viene en la variable id
    $id = (int) $_GET["id"]; //transforma el dato GET a entero

    // print_r($id);exit;

    //consultar si hay una persona con el id enviado por GET
    $res = $mbd->prepare("SELECT p.id, p.nombre, p.rut, p.email, p.direccion, p.fecha_nac, p.telefono, r.nombre as rol, c.nombre as comuna, p.created_at, p.updated_at FROM personas as p INNER JOIN roles as r ON p.rol_id = r.id INNER JOIN comunas as c ON p.comuna_id = c.id WHERE p.id = ?");
    $res->bindParam(1, $id);
    $res->execute();

    $persona = $res->fetch();

    //preguntar si la persona tiene un usuario
    $res = $mbd->prepare("SELECT id, activo FROM usuarios WHERE persona_id = ?");
    $res->bindParam(1, $id);
    $res->execute();

    $usuario = $res->fetch();

    // echo"<pre>";
    // print_r($persona);exit;
    // echo"</pre>";
}

?>
<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] != 1): ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Personas</title>

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
                <h1>Personas</h1>
                <!-- mensaje de registro de la persona -->
                <?php include('../partials/mensajes.php'); ?>
             
                <!-- listar los roles que estan registrados -->
                <?php if($persona): ?>
                    <table class="table table-hover">
                        <tr>
                            <th>Nombre:</th>
                            <td><?php echo $persona['nombre']; ?></td>
                        </tr>
                        <tr>
                            <th>RUT:</th>
                            <td><?php echo $persona["rut"]; ?></td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td><?php echo $persona["email"]; ?></td>
                        </tr>
                        <tr>
                            <th>Direccion:</th>
                            <td><?php echo $persona["direccion"]; ?></td>
                        </tr>
                        <tr>
                            <th>Comuna:</th>
                            <td><?php echo $persona["fecha_nac"];

                                ?></td>
                        </tr>
                        <tr>
                            <th>Fecha de nacimiento:</th>
                            <td>
                                <?php
                                $fecha_nac = new DateTime($persona["created_at"]);
                                echo $fecha_nac->format("d-m-y h:i:s"); ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Telefono:</th>
                            <td><?php echo $persona["telefono"]; ?></td>
                        </tr>
                        <tr>
                            <th>Rol:</th>
                            <td><?php echo $persona["rol"]; ?></td>
                        </tr>
                        <tr>
                        <th>Estado:</th>
                            <td>
                                <?php if(!empty($usuario) && $usuario['activo'] == 1): ?>
                                    Activo
                                <?php else: ?>
                                    Inactivo
                                <?php endif; ?>
                                <?php if($_SESSION['usuario_rol'] == 2): ?>
                                    <?php if($usuario): ?>
                                        | <a href="../usuarios/edit.php?id=<?php echo $usuario['id'] ?>">Modificar</a>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Creado:</th>
                            <td>
                                <?php
                                $fecha = new DateTime($persona["created_at"]);
                                echo $fecha->format("d-m-y h:i:s");
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <th>Actualizado:</th>
                            <td>
                                <?php
                                $fecha = new DateTime($persona["updated_at"]);
                                echo $fecha->format("d-m-y h:i:s");
                                ?>
                            </td>
                        </tr>
                    </table>
                    <p>
                        <a href="index.php" class="btn btn-link">Volver</a>
                        <?php if($_SESSION['usuario_rol'] == 3): ?>
                            <a href="edit.php?id=<?php echo $id ?>" class="btn btn-primary">Editar</a>
                            
                            <?php if (!$usuario): ?>
                                <a href="../usuarios/add.php?persona=<?php echo $id; ?>" class="btn btn-success">Agregar Password</a>
                            <?php else: ?>
                                <a href="../usuarios/editPassword.php?persona=<?php echo $id; ?>" class="btn btn-warning">Modificar Password</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </p>
                <?php else: ?>
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
<?php else: ?>
    <script>
        alert('Acceso indebido');
        window.location = "<?php echo BASE_URL; ?>";
    </script>

<?php endif; ?>