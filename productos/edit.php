<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require('../class/conexion.php');
require('../class/rutas.php');

//lista de roles
$res = $mbd->query("SELECT id, nombre FROM marcas ORDER BY nombre");
$marcas = $res->fetchall();

//lista de comunas
$res = $mbd->query("SELECT id, nombre FROM producto_tipos ORDER BY nombre");
$producto_tipos = $res->fetchall();

if (isset($_GET['id'])) {

    $id = (int) $_GET['id'];

    $res = $mbd->prepare("SELECT p.id, p.sku, p.nombre, p.activo, p.precio, p.marca_id, producto_tipo_id, m.nombre as marca, pt.nombre as producto_tipo, p.created_at, p.updated_at FROM productos as p INNER JOIN marcas as m ON p.marca_id = m.id INNER JOIN producto_tipos as pt ON p.producto_tipo_id = pt.id WHERE p.id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $productos = $res->fetch();


    //validamos el formulario
    if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {

        $activo = (int) $_POST["activo"];

        if ($activo <= 0 ){
            $msg = "Seleccione una opcion de estado";
        }else{
            //
            //
            $res = $mbd->prepare("UPDATE productos SET activo = ?, updated_at = now() WHERE id = ?");
            $res->bindParam(1, $activo);
            $res->bindParam(2, $id);
            $res->execute();

            $row = $res->rowCount();

            if ($row) {
                $_SESSION["success"] = "Estado modificado correctamente";
                header("Location: ../productos/show.php?id=" . $productos["id"]);
            }
        }

        // print_r($_SESSION);exit;
        $sku = trim(strip_tags($_POST["sku"]));
        $nombre = trim(strip_tags($_POST["nombre"]));
        $precio = (int) $_POST["precio"];
        $marca = (int) $_POST["marca"];
        $tipo = (int) $_POST["tipo"];

        // print_r($_POST);exit;

        if (!$sku || strlen($sku) < 2) {
            $msg = 'Ingrese al menos 2 caracteres en el sku del producto';
        } elseif (!$nombre || strlen($nombre) < 4) {
            $msg = 'Ingrese un nombre válido';
        } elseif (!$precio > 1) {
            $msg = 'Ingrese un precio válido';
        } elseif (!$marca) {
            $msg = 'Ingrese la marca correcta';
        } elseif (!$tipo) {
            $msg = 'Seleccione un tipo de producto';
        } else {
            //actualizar la tabla personas
            $res = $mbd->prepare("UPDATE productos SET sku = ?, nombre = ?, precio = ?, marca_id = ?, producto_tipo_id = ?, updated_at = now() WHERE id = ?");
            $res->bindParam(1, $sku);
            $res->bindParam(2, $nombre);
            $res->bindParam(3, $precio);
            $res->bindParam(4, $marca);
            $res->bindParam(5, $tipo);
            $res->bindParam(6, $id);
            $res->execute();

            $row = $res->rowCount();

            if ($row) {
                $msg = 'ok';
                header('Location: show.php?id=' . $id . '&m=' . $msg);
            }
        }


        /* echo '<pre>';
        print_r($_POST);exit;
        echo '</pre>'; */
    }
}

?>
<?php if(isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 3): ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personas</title>
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
                <h1>Editar producto</h1>

                <!-- mensajes de validacion y errores -->
                <?php if (isset($msg)) : ?>
                    <p class="alert alert-danger">
                        <?php echo $msg; ?>
                    </p>
                <?php endif; ?>


                <?php if ($productos) : ?>
                    <form action="" method="post">
                        <div class="form-group mb-3">
                            <label for="">Sku<span class="text-danger">*</span></label>
                            <input type="text" name="sku" value="<?php echo $productos["sku"]; ?>" class="form-control" placeholder="Ingrese el sku del producto">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Nombre<span class="text-danger">*</span></label>
                            <input type="text" name="nombre" value="<?php echo $productos["nombre"]; ?>" class="form-control" placeholder="Ingrese el nombre del producto">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Precio<span class="text-danger">*</span></label>
                            <input type="number" name="precio" value="<?php echo $productos["precio"]; ?>" class="form-control" placeholder="Ingrese el precio del producto">
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Marcas <span class="text-danger">*</span></label>
                            <select name="marca" class="form-control">
                                <option value="<?php echo $productos['marca_id'] ?>">Seleccione...</option>

                                <?php foreach ($marcas as $marca) : ?>
                                    <option value="<?php echo $marca['id']; ?>">
                                        <?php echo $marca["nombre"]; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="">Producto tipos <span class="text-danger">*</span></label>
                            <select name="tipo" class="form-control">
                                <option value="<?php echo $productos['producto_tipo_id']?>">Seleccione...</option>

                                <?php foreach ($producto_tipos as $tipo) : ?>
                                    <option value="<?php echo $tipo['id']; ?>">
                                        <?php echo $tipo["nombre"]; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="">Estado<span class="text-danger">*</span></label>
                            <select name="activo" class="form-control" id="">
                                <option value="<?php echo $productos['activo'] ?> ">
                                    <?php if ($productos['activo'] == 1) : ?>
                                        Activo
                                    <?php else : ?>
                                        Inactivo
                                    <?php endif; ?>
                                </option>
                                <option value="1">Activar</option>
                                <option value="2">Desactivar</option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <input type="hidden" name="confirm" value="1">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a href="index.php" class="btn btn-link">Volver</a>
                        </div>
                    </form>
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
<?php else: ?>
    <script>
        alert('Acceso indebido');
        window.location = "<?php echo BASE_URL; ?>";
    </script>

<?php endif; ?>