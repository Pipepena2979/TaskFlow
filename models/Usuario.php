<?php

namespace Model;

class Usuario extends ActiveRecord {

    //** PROPIEDADES DEL MODELO/CLASE */
    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $passwordActual;
    public $passwordNuevo;
    public $token;
    public $confirmado;
    
    //** NOMBRE DE LA TABLA Y CAMPOS DE LA BASE DE DATOS */
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public function __construct($args = []) 
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->passwordActual = $args['passwordActual'] ?? '';
        $this->passwordNuevo = $args['passwordNuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    //** METODO PARA VALIDAR EL LOGIN DE USUARIOS */
    public function validarLogin() : array {
        if(!$this->email) {
            self::$alertas['error'] [] = 'El Email del Usuario es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'] [] = 'Email no válido';
        }
        if(!$this->password) {
            self::$alertas['error'] [] = 'El Password del Usuario es Obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'] [] = 'El Password del Usuario debe tener al menos 6 caracteres';
        }
        return self::$alertas;
    }

    //** METODO DE VALIDACION PARA CUENTAS NUEVAS */
    public function validarNuevaCuenta() : array {
        if(!$this->nombre) {
            self::$alertas['error'] [] = 'El Nombre del Usuario es Obligatorio';
        }
        if(!$this->email) {
            self::$alertas['error'] [] = 'El Email del Usuario es Obligatorio';
        }
        if(!$this->password) {
            self::$alertas['error'] [] = 'El Password del Usuario es Obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'] [] = 'El Password del Usuario debe tener al menos 6 caracteres';
        }
        if($this->password !== $this->password2) {
            self::$alertas['error'] [] = 'Los Passwords son diferentes';
        }

        return self::$alertas;
    }

    //** METODO DE VALIDACION DE EMAIL */
    public function validarEmail() : array {
        if(!$this->email) {
            self::$alertas['error'] [] = 'El Email es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'] [] = 'Email no válido';
        }

        return self::$alertas;
    }

    //** METODO DE VALIDACION DE PASSWORD */
    public function validarPassword() : array {
        if(!$this->password) {
            self::$alertas['error'] [] = 'El Password del Usuario es Obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'] [] = 'El Password del Usuario debe tener al menos 6 caracteres';
        }
        
        return self::$alertas;
    }

    public function validarPerfil() : array {
        if(!$this->nombre) {
            self::$alertas['error'] [] = 'El Nombre del Usuario es Obligatorio';
        }
        if(!$this->email) {
            self::$alertas['error'] [] = 'El Email del Usuario es Obligatorio';
        }
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'] [] = 'Email no válido';
        }

        return self::$alertas;
    }

    public function nuevoPassword() : array {
        if(!$this->passwordActual) {
            self::$alertas['error'] [] = 'El Password Actual del Usuario no puede estar vacio';
        }
        if(!$this->passwordNuevo) {
            self::$alertas['error'] [] = 'El Password Nuevo del Usuario no puede estar vacio';
        }
        if(strlen($this->passwordNuevo) < 6) {
            self::$alertas['error'] [] = 'El Password Nuevo del Usuario debe tener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    //** METODO DE COMPROBACION DE PASSWORD */
    public function comprobarPassword() : bool {
        return password_verify($this->passwordActual, $this->password);
    }

    //** METODO DE HASHEO DEl PASSWORD */
    public function hashPassword() : void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    //** METODO PARA GENERAR UN TOKEN UNICO */
    public function crearToken() : void {
        $this->token = uniqid();
    }
}