<?php

namespace MVC;

class Router
{
    public array $getRoutes = [];
    public array $postRoutes = [];

    public function get($url, $fn)
    {
        $this->getRoutes[$url] = $fn;
    }

    public function post($url, $fn)
    {
        $this->postRoutes[$url] = $fn;
    }

    public function comprobarRutas()
    {

        $currentUrl = $_SERVER['PATH_INFO'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $fn = $this->getRoutes[$currentUrl] ?? null;
        } else {
            $fn = $this->postRoutes[$currentUrl] ?? null;
        }


        if ( $fn ) {
            //** CALL USER FN VA A LLAMAR UNA FUNCION CUANDO NO SABEMOS CUAL SERÁ */
            call_user_func($fn, $this); //** THIS SIRVE PARA PASAR PARAMETROS/ARGUMENTOS DINÁMICOS A LA FUNCIÓN */
        } else {
            echo "Página No Encontrada o Ruta no válida";
        }
    }

    public function render($view, $datos = [])
    {

        //** LEER LO QUE PASAMOS AL RENDER */
        foreach ($datos as $key => $value) {
            $$key = $value;
        }

        ob_start(); //** ALMACENAMIENTO EN MEMORIA DURANTE UN MOMENTO */

        //** INCLURIR LA VISTA EN EL LAYOUT */
        include_once __DIR__ . "/views/$view.php";
        $contenido = ob_get_clean(); //** LIMPIAR LA MEMORIA/BUFFER */
        include_once __DIR__ . '/views/layout.php';
    }
}
