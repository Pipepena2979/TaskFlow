<?php 

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController {
    public static function index(Router $router) {

        session_start();
        isAuth();

        $id = $_SESSION['id'];

        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        //** RENDER A LA VISTA */
        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function crearProyecto(Router $router) {

        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto($_POST);

            //** VALIDACIÓN */
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)) {
                //** GENERAR UNA URL UNICA */
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                //** ALMACENAR EL CREADOR DEL PROYECTO */
                $proyecto->propietarioId = $_SESSION['id'];

                //** GUARDAR EL PROYECTO */
                $proyecto->guardar();

                //** REDIRECCIONAR */
                header('Location: /proyecto?id=' . $proyecto->url);
            }
        }

        //** RENDER A LA VISTA */
        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router) 
    {
        session_start();
        isAuth();

        $token = $_GET['id'];
        if(!$token) header('Location: /dashboard');

        //** REVISAR QUE LA USUARIO QUE CREO EL PROYECTO, ES QUIEN LO CREO */
        $proyecto = Proyecto::where('url', $token);
        if($proyecto->propietarioId !== $_SESSION['id']) header('Location: /dashboard');


        //** RENDER A LA VISTA */
        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }

    public static function perfil(Router $router) 
    {

        session_start();
        isAuth();
        $alertas = []; 

        $usuario = Usuario::find($_SESSION['id']);

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPerfil();

            if(empty($alertas)) {

                $existeUsuario = Usuario::where('email', $usuario->email);

                if($existeUsuario && $existeUsuario->id !== $usuario->id) {
                    Usuario::setAlerta('error', 'Email no válido, la cuenta ya existe');
                $alertas = $usuario->getAlertas();
                } else {
                    //** GURADAR EL USUARIO */
                    $usuario->guardar();

                    Usuario::setAlerta('exito', 'Guardado   Correctamente');
                    $alertas = $usuario->getAlertas();

                    //** ASIGNAR EL NOMBRE NUEVO A LA BARRA DE NAVEGACIÓN */
                    $_SESSION['nombre'] = $usuario->nombre;
                }
            }        
        }

        //** RENDER A LA VISTA */
        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function cambiarPasword(Router $router) {

        session_start();
        isAuth();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);

            //** SINCRONIZAR CON LOS DATOS DEL USUARIO */
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevoPassword();

            if(empty($alertas)) {
                $resultado = $usuario->comprobarPassword();

                if($resultado) {
                    
                    $usuario->password = $usuario->passwordNuevo;
                    
                    //** ELIMINAR PROPIEDADES NO NECESARIAS  */
                    unset($usuario->passwordActual);
                    unset($usuario->passwordNuevo);

                    //** HASHEAR EL NUEVO PASSWORD */
                    $usuario->hashPassword();

                    //** ACTUALIZAR EL USUARIO */
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        Usuario::setAlerta('exito', 'Password Actualizado Correctamente');
                        $alertas = $usuario->getAlertas();
                    }
                } else {
                    Usuario::setAlerta('error', 'Pasword Incorrecto');
                    $alertas = $usuario->getAlertas();
                }
            }
        }

        //** RENDER A LA VISTA */
        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Password',
            'alertas' => $alertas
        ]);
    }
}