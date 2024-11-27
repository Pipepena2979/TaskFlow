<?php include_once __DIR__ . '/header-dashboard.php' ?>

    <div class="contenedor-sm">
        <?php include_once __DIR__ . '/../templates/alertas.php' ?>

        <a href="/perfil" class="enlace contenedor-enlace">Volver a Perfil</a></a>

        <form class="formulario" method="POST" action="/cambiar-password">
            <div class="campo">
                <label for="password">Password Actual</label>
                <input type="password" placeholder="Tu Password Actual" name="passwordActual">
            </div>
            <div class="campo">
                <label for="passwordNuevo">Nuevo Password</label>
                <input type="password" placeholder="Tu Nuevo Password" name="passwordNuevo">
            </div>

            <input type="submit" value="Guardar cambios">
        </form>
    </div>


<?php include_once __DIR__ . '/footer-dashboard.php' ?>

