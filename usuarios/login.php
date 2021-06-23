<?php
//visualizar errores en php en tiempo de ejecucion
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require('../class/conexion.php');
require('../class/rutas.php');
require('../class/config.php');


if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {

    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $clave = trim(strip_tags($_POST['clave']));

    if (!$email) {
        $msg = 'Ingrese su email';
    } elseif (!$clave) {
        $msg = 'Ingrese su password';
    } else {
        $clave = HASH . sha1($clave);

        //consultamos por el email y password ingresados
        $res = $mbd->prepare("SELECT u.id, p.nombre, r.id as rol FROM usuarios as u INNER JOIN personas as p ON u.persona_id = p.id INNER JOIN roles as r ON p.rol_id = r.id WHERE p.email = ? AND u.clave = ? AND u.activo = 1");
        $res->bindParam(1, $email);
        $res->bindParam(2, $clave);
        $res->execute();

        $usuario = $res->fetch();

        if (!$usuario) {
            $msg = 'El email o el password no están registrados';
        } else {
            //iniciamos la sesion del usuario a traves de variables de sesion
            $_SESSION['autenticado'] = true;
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nombre'] = $usuario['nombre'];
            $_SESSION['usuario_rol'] = $usuario['rol'];

            header('Location: ../index.php');
        }
    }
}

?>
<?php if (!isset($_SESSION["autenticado"])) : ?>

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
                    <?php if (isset($msg)) : ?>
                        <p class="alert alert-danger">
                            <?php echo $msg; ?>
                        </p>
                    <?php endif; ?>

                    <h3>Iniciar Sesión</h3>
                    <form action="" method="post">
                        <div class="form-group mb-3">
                            <label for="">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" placeholder="Ingrese su email">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Password <span class="text-danger">*</span></label>
                            <input type="password" name="clave" class="form-control" placeholder="Ingrese su password">
                        </div>
                        <div class="form-group mb-3">
                            <input type="hidden" name="confirm" value="1">
                            <button type="submit" class="btn btn-primary">Guardar</button>
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
<?php else : ?>
    <script>
        alert("Debe cerrar sesióm para volver a ingresar");
        window.location = "<?php echo BASE_URL; ?>";
    </script>
<?php endif; ?>