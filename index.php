<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require('class/conexion.php');
require('class/rutas.php');

//lista de producto con sus imagenes con productos activos he imagines activas y en portadas
$res = $mbd->query("SELECT i.imagen, p.id, p.nombre, p.precio, m.nombre as marca, tp.nombre as tipo FROM imagenes as i INNER JOIN productos as p ON i.producto_id = p.id INNER JOIN marcas as m ON p.marca_id = m.id INNER JOIN producto_tipos as tp ON p.producto_tipo_id = tp.id WHERE i.activo = 1 AND i.portada = 1 AND p.activo = 1 ORDER BY p.precio");
$productos = $res->fetchall()


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Primera Tienda</title>
    <!--Enlaces CDM de Bootstrap-->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script> -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <script src="js/bootstrap.min.js"></script>
</head>

<body>

    <div class="container">
        <!-- Seccion de cabecera del sitio  -->
        <header>
            <!-- Navegador principal -->
            <?php include("partials/menu.php"); ?>
        </header>

        <!-- seccion de contenido principal -->
        <section>

            <?php if (isset($_SESSION["autenticado"])) : ?>
                <h4>Bienvenid@ <?php echo $_SESSION["usuario_nombre"]; ?></h4>
            <?php endif; ?>
            
            <div class="row">
                <?php foreach($productos as $producto): ?>
                    <div class="col-md-2 text-center m-2">
                    <p class="h6 text-primary  ">
                            <?php echo $producto["nombre"]; ?>
                        </p>
                        <img src="<?php echo PRODUCTOS . 'img/' . $producto['imagen']; ?>" alt="" width="190" >
                        <p class=" h4 text-primary mt-2">$ <?php echo number_format($producto["precio"],0,',','.'); ?> </p>
                        <p class="h5 text-primary ">
                            <?php echo $producto["marca"]; ?>
                        </p>
                        <p class="h5 text-primary">
                            <?php echo $producto["tipo"]; ?>
                        </p>
                    </div>
                    
                <?php endforeach ?>
            </div>
        </section>

        <!-- pie de pagina -->
        <footer>
            footer
        </footer>
    </div>
</body>

</html>