<?php 

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

    class Email{

        public $nombre;
        public $email;
        public $token;

        public function __construct($nombre, $email, $token){
            $this->nombre = $nombre;
            $this->email = $email;
            $this->token = $token;
        }

        public function enviarConfirmacion(){

            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username ='feb872ea15d262';
            $mail->Password = '74308a06b3cb6a';
            
            $mail->setFrom('soporte@sacinv.com');
            $mail->addAddress('soporte@sacinv.com', 'Sacinv.com');
            $mail->Subject = 'Confirma tu cuenta';
            
            //Indicamos que usaremos HTML
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            $contenido = "<html>";
            $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta en <b>SACINV</b>, solo debes confirmarla presionando el siguiente enlace</p>";
            $contenido .= "<p>Presiona aquí: <a href='http://192.168.18.120:3000/confirmar-cuenta?token=". $this->token ."'>Confirmar Cuenta</a></p>";
            $contenido .= "<p><br><br></p>";
            $contenido .= "<p>Si tu no solicitaste esta cuenta, puedes ignorar este mensaje</p>";
            $contenido .= "</html>";

            //Asignamos el contenido al cuerpo del correo
            $mail->Body=$contenido;

            //Envamos el Email
            $mail->send();

        }

        public function enviarInstrucciones(){
            
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username ='feb872ea15d262';
            $mail->Password = '74308a06b3cb6a';
            
            $mail->setFrom('soporte@sacinv.com');
            $mail->addAddress('soporte@sacinv.com', 'Sacinv.com');
            $mail->Subject = 'Reestablece tu password';
            
            //Indicamos que usaremos HTML
            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';

            $contenido = "<html>";
            $contenido .= "<p><strong>Hola " . $this->nombre . "</strong>, Has solicitado reestablecer tu password, sigue el siguiente enlace para hacerlo</p>";
            $contenido .= "<p>Presiona aquí: <a href='http://192.168.18.120:3000/recuperar?token=". $this->token ."'>Recuperar password</a></p>";
            $contenido .= "<p><br><br></p>";
            $contenido .= "<p>Si tu no solicitaste este cambio, puedes ignorar este mensaje</p>";
            $contenido .= "</html>";

            //Asignamos el contenido al cuerpo del correo
            $mail->Body=$contenido;

            //Envamos el Email
            $mail->send();

        }
    }

?>