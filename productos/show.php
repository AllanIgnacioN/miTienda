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
    $res = $mbd->prepare("SELECT p.id, p.sku, p.nombre, p.precio, p.activo, m.nombre as marca, pt.nombre as producto_tipo, p.created_at, p.updated_at FROM productos as p INNER JOIN marcas as m ON p.marca_id = m.id INNER JOIN producto_tipos as pt ON p.producto_tipo_id = pt.id WHERE p.id = ?");
    $res->bindParam(1, $id);
    $res->execute();

    $producto = $res->fetch();

    //lista de atributos de este producto
    $res = $mbd->prepare("SELECT ap.id, a.nombre, ap.valor FROM atributos a INNER JOIN atributo_producto ap ON a.id = ap.atributo_id WHERE ap.producto_id = ?");
    $res->bindParam(1, $id);
    $res->execute();
    $atributos = $res->fetchall();
}

?>
<?php if (isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] != 1 || 3) : ?>

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
                <div class="col-md-6 offset-md-3">
                    <h1>Productos</h1>
                    <!-- mensaje de registro de la persona -->
                    <?php include('../partials/mensajes.php'); ?>

                    <!-- listar los roles que estan registrados -->
                    <?php if ($producto) : ?>
                        <table class="table table-hover">
                            <tr>
                                <th>Sku:</th>
                                <td><?php echo $producto['sku']; ?></td>
                            </tr>
                            <tr>
                                <th>Nombre:</th>
                                <td><?php echo $producto["nombre"]; ?></td>
                            </tr>
                            <tr>
                                <th>Precio:</th>
                                <td><?php echo $producto["precio"]; ?></td>
                            </tr>
                            <tr>
                                <th>Estado:</th>
                                <td>
                                    <?php if (!empty($producto) && $producto['activo'] == 1) : ?>
                                        Activo
                                    <?php else : ?>
                                        Inactivo
                                    <?php endif; ?>

                                    <?php if ($_SESSION['usuario_rol'] == 3) : ?>
                                        <?php if ($producto) : ?>
                                            | <a href="../productos/edit.php?id=<?php echo $producto['id'] ?>">Modificar</a>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Marca:</th>
                                <td><?php echo $producto["marca"]; ?></td>
                            </tr>
                            <tr>
                                <th>Producto tipo:</th>
                                <td><?php echo $producto["producto_tipo"]; ?></td>
                            </tr>
                            <tr>
                                <th>Creado:</th>
                                <td>
                                    <?php
                                    $fecha = new DateTime($producto["created_at"]);
                                    echo $fecha->format("d-m-y h:i:s");
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <th>Actualizado:</th>
                                <td>
                                    <?php
                                    $fecha = new DateTime($producto["updated_at"]);
                                    echo $fecha->format("d-m-y h:i:s");
                                    ?>
                                </td>
                            </tr>
                        </table>
                        <p>
                            <a href="index.php" class="btn btn-info">Volver</a>
                            <?php if ($_SESSION['usuario_rol'] == 3) : ?>
                                <a href="edit.php?id=<?php echo $id ?>" class="btn btn-primary">Editar</a>
                                <a href="../atributo_producto/add.php?id_producto=<?php echo $id ?>" class="btn btn-secondary">Agregar atributo</a>
                                <a href="../imagenes/add.php?id_producto=<?php echo $id; ?>" class="btn btn-success">Agregar Imagen</a>
                            <?php endif; ?>
                        </p>
                    <?php else : ?>
                        <p class="text-info">El dato solicitado no existe</p>
                    <?php endif; ?>
                </div>
                <div class="col-md-6 offset-md-3">
                    <h4 class="text-center mt-3 text-primary">Atributos de <?php echo $producto['nombre']; ?></h4>
                    <table class="table table-hover">
                        <tr>
                            <th>Atributo</th>
                            <th>Valor</th>
                            <th></th>
                        </tr>
                            <?php foreach ($atributos as $atributo) : ?>
                                <tr>
                                    <td><?php echo $atributo['nombre']; ?></td>
                                    <td><?php echo $atributo['valor']; ?></td>
                                    <?php if ($_SESSION['usuario_rol'] == 2) : ?>
                                        <td>
                                            <a href="<?php echo ATRIBUTO_PRODUCTO . "edit.php?id=" . $atributo["id"]; ?>" class="btn btn-primary btn-sm">Editar</a>
                                            <a href="<?php echo ATRIBUTO_PRODUCTO . "delete.php?id=" . $atributo["id"]; ?>" class="btn btn-warning btn-sm">Eliminar</a>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        
                    </table>
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