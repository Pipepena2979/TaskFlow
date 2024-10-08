<?php include_once __DIR__ . '/header-dashboard.php' ?>


    <div class="contenedor-sm">
        <div class="contenedor-nueva-tarea">
            <button type="button" class="agregar-tarea" id="agregar-tarea">&#43; Nueva Tarea</button>
        </div>

        <div class="filtros" id="filtros">
            <div class="filtros-inputs">
                <h2>Filtrar Tareas:</h2>
                <div class="campo">
                    <label for="todas">Todas</label>
                    <input type="radio" name="filtro" value="" id="todas" checked>
                </div>
                <div class="campo">
                    <label for="completadas">Completas</label>
                    <input type="radio" name="filtro" value="1" id="completadas">
                </div>
                <div class="campo">
                    <label for="pendientes">Pendientes</label>
                    <input type="radio" name="filtro" value="0" id="pendientes">
                </div>
            </div>
        </div>

        <ul id="listado-tareas"  class="listado-tareas"></ul>
    </div>


<?php include_once __DIR__ . '/footer-dashboard.php' ?>

<?php

$script .= '
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="build/js/tarea.js"></script>
';

?>

