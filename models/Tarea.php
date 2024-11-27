<?php

namespace Model;

class Tarea extends ActiveRecord {

    //** PROPIEDADES DEL MODELO/CLASE */
    public $id;
    public $nombre;
    public $estado;
    public $proyectoId;
    
    //** NOMBRE DE LA TABLA Y CAMPOS DE LA BASE DE DATOS */
    protected static $tabla = 'tareas';
    protected static $columnasDB = ['id','nombre', 'estado', 'proyectoId'];

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->estado = $args['estado'] ?? 0;
        $this->proyectoId = $args['proyectoId'] ?? '';
    }
}