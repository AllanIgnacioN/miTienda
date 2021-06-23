<?php if (isset($_SESSION["success"])) : ?>
    <p class="alert alert-success">
        <?php
        //imprime el valor de la variable de session success
        echo $_SESSION["success"];
        //eliminar el contenido y la variable success
        unset($_SESSION["success"]);
        ?>
    </p>
<?php endif ?>

<?php if (isset($_SESSION["danger"])) : ?>
    <p class="alert alert-danger">
        <?php
        //imprime el valor de la variable de session danger
        echo $_SESSION["danger"];
        //eliminar el contenido y la variable danger
        unset($_SESSION["danger"]);
        ?>
    </p>
<?php endif ?>