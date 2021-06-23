<!-- comienza el codigo del servidor -->
<?php
    // codigo que permite mostrar errores en tiempo de ejecucion
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    session_start();
    
    require('../class/conexion.php');
    require('../class/rutas.php');

    //validar la existencia de la variable id que viene via GET (url)
    if (isset($_GET['id'])) {
        
        $id = (int) $_GET['id']; //parsear la variable id a numero entero

        //lista de productos
        $res = $mbd->query("SELECT id, nombre FROM productos ORDER BY nombre");
        $productos = $res->fetchall();

        $res = $mbd->prepare("SELECT i.id, i.titulo, i.imagen, i.descripcion, i.activo, i.portada, i.producto_id, i.created_at, i.updated_at, p.nombre as producto FROM imagenes as i INNER JOIN productos as p ON i.producto_id = p.id WHERE i.id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $imagen = $res->fetch();

        //print_r($imagen);exit;

        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            //almacena el nombre de la region desde el formulario
            //print_r($_POST);exit;
            $titulo = trim(strip_tags($_POST['titulo']));
            $descripcion = trim(strip_tags($_POST['descripcion']));
            $activo = filter_var($_POST['activo'], FILTER_VALIDATE_INT);
            $producto = filter_var($_POST['producto'], FILTER_VALIDATE_INT);

            if (strlen($titulo) < 5) {
                $msg = 'Ingrese un título de al menos 5 carateres';
            }elseif (strlen($descripcion) < 10) {
                $msg = 'Ingrese una descripción de al menos 10 caracteres';
            }elseif(!$activo){
                $msg = 'Seleccione un estado';
            }elseif (!$producto) {
                $msg = 'Seleccione un producto';
            }else{
                //modificar los atributos de la imagen
                $res = $mbd->prepare("UPDATE imagenes SET titulo = ?, descripcion = ?, activo = ?, producto_id = ?, updated_at = now() WHERE id = ?");
                $res->bindParam(1, $titulo);
                $res->bindParam(2, $descripcion);
                $res->bindParam(3, $activo);
                $res->bindParam(4, $producto);
                $res->bindParam(5, $id);
                $res->execute();

                $row = $res->rowCount();

                if ($row) {
                    $_SESSION['success'] = 'La imagen se ha modificado correctamente';
                    header('Location: show.php?id=' . $id);
                }
            }
        }

    }

?>

<?php if (isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] == 3): ?>

<!-- aqui comienza el codigo del cliente -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imagenes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <!-- llamada a naveador del sitio -->
        <?php include('../partials/menu.php'); ?>
    </header>
    <div class="container">
        <div class="col-md-6 offset-md-3">
            <h2 class="text-center mt-3 text-primary">Editar Imagen</h2>
           
            <?php if (isset($msg)) : ?>
                    <p class="alert alert-danger">
                        <?php echo $msg; ?>
                    </p>
                <?php endif; ?>

            <!-- validar que el rol existe     -->
            <?php if($imagen): ?>
                <form action="" method="post">
                    <div class="form-group mb-3">
                        <label for="titulo">Título <span class="text-danger">*</span></label>
                        <input type="text" name="titulo" value="<?php echo $imagen['titulo']; ?>" class="form-control" placeholder="Ingrese el título de la imagen">
                    </div>
                    <div class="form-group mb-3">
                        <label for="descripcion">descripción <span class="text-danger">*</span></label>
                        <textarea name="descripcion" class="form-control text-left" rows="4" placeholder="Ingrese la descripción de la imagen">
                            <?php echo $imagen['descripcion']; ?>
                        </textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label for="estado">Estado <span class="text-danger">*</span></label>
                        <select name="activo" class="form-control">
                            <option value="<?php echo $imagen['activo']; ?>">
                                <?php if($imagen['activo'] == 1): ?>
                                    Activo
                                <?php else: ?>
                                    Inactivo
                                <?php endif; ?>
                            </option>

                            <option value="1">Activar</option>
                            <option value="2">Desactivar</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="producto">Producto <span class="text-danger">*</span></label>
                        <select name="producto" class="form-control">
                            <option value="<?php echo $imagen['producto_id'] ?>">
                                    <?php echo $imagen['producto']; ?>
                            </option>

                            <?php foreach($productos as $producto): ?>
                                <option value="<?php echo $producto['id']; ?>">
                                    <?php echo $producto['nombre']; ?>
                                </option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                    
                    <div class="form-group">
                        <input type="hidden" name="confirm" value="1">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                        <a href="show.php?id=<?php echo $id; ?>" class="btn btn-link">Volver</a>
                    </div>
                </form>
            <?php else: ?>
                
                <p class="text-info">El dato no existe</p>
            
            <?php endif; ?>
           
        </div>
        
    </div>
    
</body>
</html>
<?php else: ?>
    <script>
        alert('Acceso indebido');
        window.location = "<?php echo BASE_URL; ?>";
    </script>
<?php endif; ?>