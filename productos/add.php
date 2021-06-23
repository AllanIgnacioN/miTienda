<?php
//visualizar errores en php en tiempo ejecucion
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require("../class/conexion.php");
require("../class/rutas.php");

//lista de marca
$res = $mbd->query("SELECT id, nombre FROM marcas ORDER BY nombre");
$marcas = $res->fetchall();

//lista de producto_tipo
$res = $mbd->query("SELECT id, nombre FROM producto_tipos ORDER BY nombre");
$producto_tipos = $res->fetchall();

//validamos el formulario
if (isset($_POST["confirm"]) && $_POST["confirm"] == 1) {
    // print_r($_POST);exit;
    $sku = trim(strip_tags($_POST["sku"]));
    $nombre = trim(strip_tags($_POST["nombre"]));
    $precio = (int) $_POST["precio"];
    $marca = (int) $_POST["marca"];
    $tipo = (int) $_POST["tipo"];

    //Ingresamos los productos
    //procedemos a registrar los datos de los productos
    $res = $mbd->prepare("INSERT INTO productos VALUES(null, ?, ?, ?, 2, ?, ?, now(), now())");
    $res->bindParam(1, $sku);
    $res->bindParam(2, $nombre);
    $res->bindParam(3, $precio);
    $res->bindParam(4, $marca);
    $res->bindParam(5, $tipo);
    $res->execute();

    $row = $res->rowCount();

    if ($row) {
        $_SESSION['success'] = 'Se ha registrado correctamente el producto';
        header('Location: index.php');
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
    <title>Productos</title>
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
                <h1>Nuevo Producto</h1>

                <!-- mensaje de validacion y errores -->
                <?php if (isset($msg)) : ?>
                    <p class="alert alert-danger">
                        <?php echo $msg; ?>
                    </p>
                <?php endif; ?>

                <form action="" method="post">
                    <div class="form-group mb-3">
                        <label for="">Sku<span class="text-danger">*</span></label>
                        <input type="text" name="sku" value="<?php if (isset($_POST["sku"])) echo $_POST["sku"]; ?>" class="form-control" placeholder="Ingrese el sku del producto">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Nombre<span class="text-danger">*</span></label>
                        <input type="text" name="nombre" value="<?php if (isset($_POST["nombre"])) echo $_POST["nombre"]; ?>" class="form-control" placeholder="Ingrese el nombre del producto">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Precio<span class="text-danger">*</span></label>
                        <input type="precio" name="precio" value="<?php if (isset($_POST["precio"])) echo $_POST["precio"]; ?>" class="form-control" placeholder="Ingrese el precio del producto">
                    </div>
                    <div class="form-group mb-3">
                        <label for="">Marcas <span class="text-danger">*</span></label>
                        <select name="marca" class="form-control">
                            <option value="">Seleccione...</option>

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
                            <option value="">Seleccione...</option>

                            <?php foreach ($producto_tipos as $tipo) : ?>
                                <option value="<?php echo $tipo['id']; ?>">
                                    <?php echo $tipo["nombre"]; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
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