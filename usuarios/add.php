<?php
//visualizar errores en php en tiempo de ejecucion
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require('../class/conexion.php');
require('../class/rutas.php');
require('../class/config.php');

if (isset($_GET['persona'])) {
    
    $id_persona = (int) $_GET['persona']; 

    //validamos que la persona exista en la tabla personas
    $res = $mbd->prepare("SELECT id, nombre FROM personas WHERE id = ?");
    $res->bindParam(1, $id_persona);
    $res->execute();

    $persona = $res->fetch();

    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
        
        $clave = trim(strip_tags($_POST['clave']));
        $reclave = trim(strip_tags($_POST['reclave']));

        if (!$clave || strlen($clave) < 8) {
            $msg = 'Ingrese un password de al menos ocho caracteres';
        }elseif($reclave != $clave){
            $msg = 'El password no coincide';
        }else{
            //encriptacion de password
            $clave = HASH . sha1($clave);
            //registramos al usuario con el id de persona enviado por GET
            //activo => 1 e inactivo => 2
            $res = $mbd->prepare("INSERT INTO usuarios VALUES(null, ?, 1, ?, now(), now() )");
            $res->bindParam(1, $clave);
            $res->bindParam(2, $id_persona);
            $res->execute();

            $row = $res->rowCount();

            if($row){
                $_SESSION['success'] = 'El password se ha creado correctamente';
                header('Location: ../personas/show.php?id=' . $id_persona );
            }

        }
    }

    //print_r($persona);exit;
}

?>

<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 3): ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios</title>
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
                <!-- mensajes de validacion y errores -->
                <?php if(isset($msg)): ?>
                    <p class="alert alert-danger">
                        <?php echo $msg; ?>
                    </p>
                <?php endif; ?>
                
                <?php if($persona): ?>
                    <h3>Agregando password a <?php echo $persona['nombre']; ?> </h3>
                    <form action="" method="post">
                        <div class="form-group mb-3">
                            <label for="">Password <span class="text-danger">*</span></label>
                            <input type="password" name="clave" class="form-control" placeholder="Ingrese el password del usuario">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Confirmar Password <span class="text-danger">*</span></label>
                            <input type="password" name="reclave" class="form-control" placeholder="Confirme el password del usuario">
                        </div>
                        <div class="form-group mb-3">
                            <input type="hidden" name="confirm" value="1">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="../personas/show.php?id=<?php echo $id_persona; ?>" class="btn btn-link">Volver</a>
                        </div>
                    </form>
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