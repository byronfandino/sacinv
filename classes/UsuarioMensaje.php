<?php 

namespace Classes;

use Model\ActiveRecord;
use PHPMailer\PHPMailer\PHPMailer;

class UsuarioMensaje extends ActiveRecord{

    public $Us_Nombre;
    public $Us_Email;
    public $Us_Celular;
    public $Us_Mensaje;

    // public function __construct($mensaje = []){
    public function __construct($args = []){
        $this->Us_Nombre =  $args['Us_Nombre'] ?? '';
        $this->Us_Email = $args['Us_Email'] ?? '';
        $this->Us_Celular = $args['Us_Celular'] ?? '';
        $this->Us_Mensaje = $args['Us_Mensaje'] ?? ''; 
        // debuguear($this);
    }

    public function validar(){
        if(!$this->Us_Nombre){
            self::$alertas['error-msg']['nombre'][]='El campo nombre es obligatorio';
        }
        if(!$this->Us_Email){
            self::$alertas['error-msg']['email'][]='El campo email es obligatorio';
        }
        if(!$this->Us_Celular){
            self::$alertas['error-msg']['celular'][]='El campo celular es obligatorio';
        }
        if(!$this->Us_Mensaje){
            self::$alertas['error-msg']['mensaje'][]='El campo mensaje es obligatorio';
        }
        return self::$alertas;
    }

    public function enviarMensaje(){

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username ='feb872ea15d262';
        $mail->Password = '74308a06b3cb6a';
        
        $mail->setFrom('solicitante@sacinv.com');
        $mail->addAddress('soporte@sacinv.com', 'Sacinv.com');
        $mail->Subject = 'PQRS';
        
        //Indicamos que usaremos HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p>" . $this->Us_Mensaje . "</p>";
        $contenido .= "<br><br>";
        $contenido .= "<p><strong>Datos del solicitante</strong>";
        $contenido .= "<br><br>";
        $contenido .= "<p>" . $this->Us_Nombre . "</p>";
        $contenido .= "<p>" . $this->Us_Email . "</p>";
        $contenido .= "<p>" . $this->Us_Celular . "</p>";
        $contenido .= "</html>";

        //Asignamos el contenido al cuerpo del correo
        $mail->Body=$contenido;

        //Envamos el Email
        $mail->send();
    }


}

?>