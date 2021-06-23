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

        $res = $mbd->prepare("SELECT id, portada, producto_id FROM imagenes WHERE id = ?");
        $res->bindParam(1, $id);
        $res->execute();
        $imagen = $res->fetch();

        //print_r($imagen);exit;

        if (isset($_POST['confirm']) && $_POST['confirm'] == 1) {
            //almacena el nombre de la region desde el formulario
            //print_r($_POST);exit;
            
            $portada = filter_var($_POST['portada'], FILTER_VALIDATE_INT);

            if (!$portada) {
                $msg = 'Seleccione una opción en portada';
            }else{
                //preguntar si hay otra imagen de portada en el producto al que pertenece la imagen

                if ($portada == 1) {
                    $producto = $imagen['producto_id'];
                    $res = $mbd->prepare("SELECT id FROM imagenes WHERE producto_id = ? AND portada = 1");
                    $res->bindParam(1, $producto);
                    $res->execute();

                    $img = $res->fetch();

                    if ($img) {
                        $_SESSION['danger'] = 'Ya existe una imagen de portada para este producto... desactive la portada actual';
                        header('Location: show.php?id=' . $id);
                    }else{
                        //actualizamos
                        $res = $mbd->prepare("UPDATE imagenes SET portada = ?, updated_at = now() WHERE id = ?");
                        $res->bindParam(1, $portada);
                        $res->bindParam(2, $id);
                        $res->execute();

                        $row = $res->rowCount();

                        if($row){
                            $_SESSION['success'] = 'La portada de la imagen se ha modificado correctamente';
                            header('Location: show.php?id=' . $id);
                        }
                    }
                }else{

                    //actualizamos
                    $res = $mbd->prepare("UPDATE imagenes SET portada = ?, updated_at = now() WHERE id = ?");
                    $res->bindParam(1, $portada);
                    $res->bindParam(2, $id);
                    $res->execute();

                    $row = $res->rowCount();

                    if($row){
                        $_SESSION['success'] = 'La portada de la imagen se ha modificado correctamente';
                        header('Location: show.php?id=' . $id);
                    }
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
            <h2 class="text-center mt-3 text-primary">Editar Portada</h2>

            <?php if(isset($msg)): ?>
                <p class="alert alert-danger">
                    <?php echo $msg; ?>
                </p>
            <?php endif; ?>

            <!-- validar que el rol existe     -->
            <?php if($imagen): ?>
                <form action="" method="post">   
                    <div class="form-group mb-3">
                        <label for="portada">Portada <span class="text-danger">*</span></label>
                        <select name="portada" class="form-control">
                            <option value="<?php echo $imagen['portada']; ?>">
                                <?php if($imagen['portada'] == 1): ?>
                                    Portada
                                <?php else: ?>
                                    No Portada
                                <?php endif; ?>
                            </option>

                            <option value="1">Portada</option>
                            <option value="2">No Portada</option>
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