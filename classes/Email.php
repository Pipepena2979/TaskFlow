<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {

    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '50bc3ddcdfdbfb';
        $mail->Password = 'af2c8ae69260f3';

        $mail->setFrom('cuentas@TaskFlow.com');
        $mail->addAddress('cuentas@TaskFlow.com', 'TaskFlow.com');
        $mail->Subject = 'Confirma tu Cuenta';

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= '<p><strong>Hola ' . $this->nombre . '</strong> Has creado tu cuenta en TaskFlow, solo debes confirmarla presionando el siguiente enlace</p>';
        $contenido .= '<p>Presiona aqui: <a href="http://localhost:3000/confirmar?token=' . $this->token . '">Confirmar Cuenta</a></p>';
        $contenido .= '<p>Si tu no creaste esta cuenta, puedes ignorar el mensaje</p>';
        $contenido .= '</html>';

        $mail->Body = $contenido;

        //** ENVIAR EMAIL */
        $mail->send();
    }

    public function enviarInstrucciones() {

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '50bc3ddcdfdbfb';
        $mail->Password = 'af2c8ae69260f3';

        $mail->setFrom('cuentas@TaskFlow.com');
        $mail->addAddress('cuentas@TaskFlow.com', 'TaskFlow.com');
        $mail->Subject = 'Restablece tu Password';

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= '<p><strong>Hola ' . $this->nombre . '</strong> Parece que has olvidado tu password, puedes restablecerlo presionando el siguiente enlace</p>';
        $contenido .= '<p>Presiona aqui: <a href="http://localhost:3000/reestablecer?token=' . $this->token . '">Restablecer Password</a></p>';
        $contenido .= '<p>Si tu no creaste esta cuenta, puedes ignorar el mensaje</p>';
        $contenido .= '</html>';

        $mail->Body = $contenido;

        //** ENVIAR EMAIL */
        $mail->send();
    }
}