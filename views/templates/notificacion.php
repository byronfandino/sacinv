<?php if( isset($_SERVER['PATH_INFO'])  && $_SERVER['PATH_INFO'] == '/mensaje') { ?>
    <div class="fondo-notificacion">
        <div class="contenedor-notificacion">
            <img src="/build/img/sistema/email.svg" alt="Icono de Email">
            <p class="notificacion-msg">Hemos enviado un mensaje a tu correo electrónico para confirmar tu cuenta</p>
            <a href="/" class="boton btn-primario cerrar-notificacion">Cerrar</a>
        </div>
    </div>
<?php } ?>

<?php if( isset($_SERVER['PATH_INFO'])  && $_SERVER['PATH_INFO'] == '/mensaje-enviado') { ?>
    <div class="fondo-notificacion">
        <div class="contenedor-notificacion">
            <img src="/build/img/sistema/email.svg" alt="Icono de Email">
            <p class="notificacion-msg">Mensaje enviado correctamente</p>
            <a href="/" class="boton btn-primario cerrar-notificacion">Cerrar</a>
        </div>
    </div>
<?php } ?>

<?php if( isset($_SERVER['PATH_INFO'])  && $_SERVER['PATH_INFO'] == '/confirmar-cuenta'){ ?>
        <div class="contenedor-mensaje">
            <img src="<?php echo isset($alertas['exito']['general']) ? 'build/img/success.svg' : 'build/img/mistake.svg'; ?>" alt="Icono de Email">
            <p class="notificacion-msg"><?php echo isset($alertas['exito']['general']) ? $alertas['exito']['general'] : $alertas['error-general']['general']; ?></p>
            <a href="/" class="boton btn-primario cerrar-notificacion">Volver</a>
        </div>
<?php } ?>

<?php if( isset($_SERVER['PATH_INFO'])  && $_SERVER['PATH_INFO'] == '/mensaje-recuperacion'){ ?>
    <div class="fondo-notificacion">
        <div class="contenedor-notificacion">
            <img src="/build/img/sistema/email.svg" alt="Icono de Email">
            <p class="notificacion-msg">Hemos enviado las instrucciones a tu correo para recuperar tu contraseña</p>
            <a href="/" class="boton btn-primario cerrar-notificacion">Cerrar</a>
        </div>
    </div>
<?php } ?>

<?php 
    if( isset($_SERVER['PATH_INFO'])  && $_SERVER['PATH_INFO'] == '/recuperar'){ 
        
        if($error){
?>
            <div class="fondo-notificacion">
                <div class="contenedor-notificacion">
                    <img src="/build/img/sistema/mistake.svg" alt="Imagen de error de token">
                    <p class="notificacion-msg"><?php echo tipoAlerta($alertas, 'error-general', 'general');?></p>
                    <a href="/" class="boton btn-primario cerrar-notificacion">Cerrar</a>
                </div>
            </div>
<?php 
            return;
        }else{
            if(!empty($alertas) && isset($alertas['exito']['general']) && $_SERVER['REQUEST_METHOD'] === "POST"){
?>
                <div class="fondo-notificacion">
                    <div class="contenedor-notificacion">
                        <img src="/build/img/sistema/success.svg" alt="Imagen de registro actualizado">
                        <p class="notificacion-msg"><?php echo tipoAlerta($alertas, 'exito', 'general');?></p>
                        <a href="/" class="boton btn-primario cerrar-notificacion">Cerrar</a>
                    </div>
                </div>
<?php
            }           
        }
    } 
?>

