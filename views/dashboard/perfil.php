<?php include_once __DIR__ . '/header-dashboard.php' ?>

    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php' ?>

        <a href="/cambiar-password" class="enlace">Cambiar Password</a></a>

        <form class="formulario" method="POST" action="/perfil">
            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" placeholder="Tu Nombre" name="nombre" value="<?php echo $usuario->nombre; ?>" />
            </div>
            <div class="campo">
                <label for="email">Email</label>
                <input type="text" id="email" placeholder="Tu Email" name="email" value="<?php echo $usuario->email; ?>" />
            </div>

            <input type="submit" value="Guardar cambios">
        </form>
    </div>


<?php include_once __DIR__ . '/footer-dashboard.php' ?>

