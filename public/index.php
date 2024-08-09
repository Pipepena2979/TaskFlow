<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\LoginController;
use Controllers\DashboardController;
use Controllers\TareaController;

$router = new Router();

//** LOGIN */
$router->get('/', [LoginController::class, 'login']);
$router->post('/login', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

//** CREAR CUENTA */
$router->get('/crear', [LoginController::class, 'crear']);
$router->post('/crear', [LoginController::class, 'crear']);

//** FORMULARIO DE OLVIDE MI PASSWORD */
$router->get('/olvide', [LoginController::class, 'olvide']);
$router->post('/olvide', [LoginController::class, 'olvide']);

//** COLOCAR EL NUEVO PASSWORD */
$router->get('/reestablecer', [LoginController::class, 'reestablecer']);
$router->post('/reestablecer', [LoginController::class, 'reestablecer']);

//** CONFIRMACIÓN DE CUENTA */
$router->get('/mensaje', [LoginController::class, 'mensaje']);
$router->get('/confirmar', [LoginController::class, 'confirmar']);

//** ZONA DE PROYECTOS */
$router->get('/dashboard', [DashboardController::class, 'index']);

//** CREAR PROYECTO */
$router->get('/crear-proyecto', [DashboardController::class, 'crearProyecto']);
$router->post('/crear-proyecto', [DashboardController::class, 'crearProyecto']);

//** PROYECTO */
$router->get('/proyecto', [DashboardController::class, 'proyecto']);

//** API PARA LAS TAREAS */
$router->get('/api/tareas', [TareaController::class, 'index']);
$router->post('/api/tarea', [TareaController::class, 'crear']);
$router->post('/api/tarea/actualizar', [TareaController::class, 'actualizar']);
$router->post('/api/tarea/eliminar', [TareaController::class, 'eliminar']);

//** PERFIL */
$router->get('/perfil', [DashboardController::class, 'perfil']);
$router->post('/perfil', [DashboardController::class, 'perfil']);
$router->get('/cambiar-password', [DashboardController::class, 'cambiarPasword']);
$router->post('/cambiar-password', [DashboardController::class, 'cambiarPasword']);


//** COMPRUEBA Y VALIDA LAS RUTAS QUE EXISTAN Y LES ASIGNA LAS FUNCIONES DEL CONTROLADOR */
$router->comprobarRutas();