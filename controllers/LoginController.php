<?php 

namespace Controllers;

use Classes\Email;
use MVC\Router;
use Model\Usuario;


class LoginController {
    public static function login (Router $router) {
        
        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarLogin();

            if(empty($alertas)) {
                //** VERIFICAR QUE EL USUARIO EXISTA */
                $usuario = Usuario::where('email', $usuario->email);

                if(!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El Usuario No Existe o No esta Confirmado');
                } else {
                    //** EL USUARIO EXISTE */
                    if( password_verify($_POST['password'], $usuario->password)) {
                        
                    //** INICIAR LA SESION */
                    session_start();
                    $_SESSION['id'] = $usuario->id;
                    $_SESSION['nombre'] = $usuario->nombre;
                    $_SESSION['email'] = $usuario->email;
                    $_SESSION['login'] = true;

                    //** REDIRECCIONAR AL USUARIO */
                    header('Location: /dashboard');
                } else {
                    Usuario::setAlerta('error', 'Password Incorrecto');
                }
            }
        }
    }

        $alertas = Usuario::getAlertas();

        //** RENDER A LA VISTA */
        $router->render('auth/login',[
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }

    public static function logout () {
        
        session_start();
        $_SESSION = [];

        header('Location: /');
    }

    public static function crear (Router $router) {

        $alertas = [];
        $usuario = new Usuario();

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);

            if($existeUsuario) {
                Usuario::setAlerta('error','El Usuario ya esta registrado');
                $alertas = Usuario::getAlertas();
                } else {

                    //** HASHEAR EL PASSWORD */
                    $usuario->hashPassword();

                    //** ELIMINAR PASSWORD2 */
                    unset($usuario->password2);

                    //** GENERAR EL TOKEN UNICO */
                    $usuario->crearToken();

                    //** CREAR UN NUEVO USUARIO */
                    $resultado = $usuario->guardar();

                    //** ENVIAR EMAIL */
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    if($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        //** RENDER A LA VISTA */
        $router->render('auth/crear',[
            'titulo' => 'Crear Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function olvide (Router $router) {

        $alertas = [];
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)) {
                //** BUSCAR EL USUARIO EN LA BD */
                $usuario = Usuario::where('email', $usuario->email);
                
                if($usuario && $usuario->confirmado) {
                    
                    //** GENERAR UN NUEVO TOKEN */
                    $usuario->crearToken();
                    unset($usuario->password2);
                    
                    //** ACTUALIZAR EL USUARIO */
                    $usuario->guardar();

                    //** ENVIAR EL EMAIL */
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    $email->enviarInstrucciones();

                    //** IMPRIMIR ALERTA */
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu Email');

                } else {
                    Usuario::setAlerta('error','El Usuario no existe o no esta confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        //** RENDER A LA VISTA */
        $router->render('auth/olvide',[
            'titulo' => 'Olvide mi Password',
            'alertas' => $alertas
        ]);
    }

    public static function reestablecer (Router $router) {

        $token = sanitizar($_GET['token']);
        $mostrar = true;

        if(!$token) {
            header('Location: /');
        }

        //** IDENTIFICAR EL USUARIO CON ESTE TOKEN */
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            Usuario::setAlerta('error','Token no Válido');
            $mostrar = false;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            //** AÑADIR EL NUEVO PASSWORD */
            $usuario->sincronizar($_POST);

            //** VALIDAR EL NUEVO PASSWORD */
            $alertas = $usuario->validarPassword();

            if(empty($alertas)) {
                //** HASHEAR EL NUEVO PASSWORD */
                $usuario->hashPassword();
                unset($usuario->password2);

                //** ELIMINAR EL TOKEN */
                $usuario->token = null;

                //** GUARDAR EL USUARIO EN LA BD */
                $resultado = $usuario->guardar();

                //** REDIRECCIONAR AL USUARIO */
                if($resultado) {
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        //** RENDER A LA VISTA */
        $router->render('auth/reestablecer',[
            'titulo' => 'Reestablecer Password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function mensaje (Router $router) {
        
        //** RENDER A LA VISTA */
        $router->render('auth/mensaje',[
            'titulo' => 'Cuenta Creada Correctamente'
        ]);
    }

    public static function confirmar (Router $router) {
        
        $token = sanitizar($_GET['token']);

        if(!$token) {
            header('Location: /');
        }

        //** ENCONTRAR AL USUARIO CON ESTE TOKEN */
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            //** NO SE ENCONTRO UN USUARIO CON ESE TOKEN */
            Usuario::setAlerta('error','Token no Válido');
        } else {
            //** CONFIRMAR LA CUENTA DEL USUARIO */
            $usuario->confirmado = '1';
            $usuario->token = null;
            unset($usuario->password2);
            
            //** GUARDAR USUARIO EN LA BASE DE DATOS */
            $usuario->guardar();

            Usuario::setAlerta('exito','Cuenta Comprobada Correctamente');
        }

        $alertas = Usuario::getAlertas();
        
        //** RENDER A LA VISTA */
        $router->render('auth/confirmar',[
            'titulo' => 'Confirma tu Cuenta TaskFlow',
            'alertas' => $alertas
        ]);
    }
}