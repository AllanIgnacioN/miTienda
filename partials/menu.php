<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?php echo BASE_URL ?>">MiTienda</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- enlaces del sitio -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?php echo BASE_URL; ?>">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo BASE_URL . "nosotros.php" ?>">Nosotros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Contacto</a>
                </li>
                <?php if (isset($_SESSION['autenticado']) && $_SESSION['usuario_rol'] != 1 || 3): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Administración
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="<?php echo COMUNAS; ?>">Comunas</a></li>
                            <li><a class="dropdown-item" href="<?php echo REGIONES; ?>">Regiones</a></li>
                            <li><a class="dropdown-item" href="<?php echo MARCAS; ?>">Marcas</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="<?php echo ATRIBUTOS; ?>">Atributos</a></li>
                            <li><a class="dropdown-item" href="<?php echo PRODUCTOS_TIPOS; ?>">Producto tipos</a></li>
                            <li><a class="dropdown-item" href="<?php echo IMAGENES; ?>">Imagenes</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="<?php echo PERSONAS; ?>">Personas</a></li>
                            <li><a class="dropdown-item" href="<?php echo PRODUCTOS; ?>">Productos</a></li>
                            <li><a class="dropdown-item" href="<?php echo ROLES; ?>">Roles</a></li>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if (!isset($_SESSION["autenticado"])) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo USUARIOS . "login.php" ?>" tabindex="-1" aria-disabled="false">LogIn</a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo USUARIOS . "logout.php" ?>" tabindex="-1" aria-disabled="false">LogOut</a>
                    </li>
                <?php endif; ?>
            </ul>
            <!-- formulario del buscador -->
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Search">
                <button class="btn btn-outline-info" type="submit">Buscar</button>
            </form>
        </div>
    </div>
</nav>