<!-- Menú de navegación -->
<div class="barra-navegacion">
    <figure class="fondo-menu">
        <a href="#">
            <img src="/build/img/sistema/menu.svg" alt="Icono de menú" id="iconoMenu" />
        </a>    
    </figure>
    <nav class="navegacion-menu">
        <a href="#" id="cerrar">X</a>
        <a href="#sacinv">¿Qué es SACINV?</a>
        <a href="#nosotros">Nosotros</a>
        <a href="#contactenos">Contáctenos</a>
    </nav>
</div>

<header class="header-inicio">
    <div class="seccion2-header contenedor">
        
        <div class="titulo">
            <img src="/build/img/sistema/logo-blanco.svg" alt="Logo">
            <h1>Software para la Administración y Control de Inventarios</h1>
        </div>
        
        <div class="formulario-menu-negro">
            
            <div class="form-menu">
                <a href="/" id="iniciaSesion">Inicia Sesión</a>
                <a href="/crear-cuenta" id="registrate" class="inactivo">Registrate</a>
            </div>

            <div class="form-content">

                <form method="POST" action="/" class="form-list" id="form-login" data-cy="form-login">
                    <div class="campo">
                        <label for="log-nickname" class="campo-descripcion">Nickname</label>
                        <input type="text" name="Us_NickName" id="log-nickname" data-tipo="nickname" data-cy="login-nickname" value="<?php echo isset($auth->Us_NickName) ? s($auth->Us_NickName) : ''; ?>" class="input input-negro" required>
                        <img src="/build/img/sistema/error.svg" alt="icono de error" class="icono-error ocultar">
                        <label class="msg-error ocultar"><?php echo tipoAlerta($alertas, 'error-log', 'nickname');?></label>
                    </div>

                    <div class="campo">                    
                        <label for="log-password" class="campo-descripcion">Contraseña</label>
                        <input type="password" name="Us_Password"  id="log-password" data-cy="login-password" class="input input-negro" required>
                        <img src="/build/img/sistema/error.svg" alt="icono de error" class="icono-error ocultar">
                        <label class="msg-error ocultar"><?php echo tipoAlerta($alertas, 'error-log', 'password');?></label>
                    </div>

                    <a href="/olvide" class="enlace-olvido" >Olvidé mi contraseña</a>
                    
                    <!-- <input type="hidden" name="tipo" value="login"> -->
                    
                    <input type="submit" value="Ingresar" class="boton btn-primario disabled">
                    
                </form>
                                
                <form method="POST" action="/crear-cuenta" class="form-list ocultar-form" id="form-registrate">

                    <div class="campo">
                        <label for="reg-nombre" class="campo-descripcion">Nombre Completo</label>
                        <input type="text" name="Us_Nombre" id="reg-nombre" data-tipo="nombre" value="<?php echo isset($usuario->Us_Nombre) ? s($usuario->Us_Nombre) : ''; ?>" class="input input-negro" required>
                        <img src="/build/img/sistema/error.svg" alt="icono de error" class="icono-error ocultar">
                        <label class="msg-error ocultar"><?php echo tipoAlerta($alertas, 'error-reg', 'nombre');?></label>
                    </div>

                    <div class="campo">
                        <label for="reg-nickname" class="campo-descripcion">NickName</label>
                        <input type="text" name="Us_NickName" id="reg-nickname" data-tipo="nickname" value="<?php echo isset($usuario->Us_NickName) ? s($usuario->Us_NickName) : ''; ?>" class="input input-negro" required>
                        <img src="/build/img/sistema/error.svg" alt="icono de error" class="icono-error ocultar">
                        <label class="msg-error ocultar"><?php echo tipoAlerta($alertas, 'error-reg', 'nickname'); ?></label>
                    </div>

                    <div class="campo">
                        <label for="reg-telefono" class="campo-descripcion">Teléfono</label>
                        <input type="tel" name="Us_Telefono" id="reg-telefono" value="<?php echo isset($usuario->Us_Telefono) ? s($usuario->Us_Telefono) : ''; ?>" class="input input-negro" required>
                        <img src="/build/img/sistema/error.svg" alt="icono de error" class="icono-error ocultar">
                        <label class="msg-error ocultar"><?php echo tipoAlerta($alertas, 'error-reg', 'telefono');?></label>
                    </div>

                    <div class="campo">
                        <label for="reg-email" class="campo-descripcion">Correo electrónico</label>
                        <input type="email" name="Us_Email" id="reg-email" value="<?php echo isset($usuario->Us_Email) ? s($usuario->Us_Email) : ''; ?>" class="input input-negro" required>
                        <img src="/build/img/sistema/error.svg" alt="icono de error" class="icono-error ocultar">
                        <label class="msg-error ocultar"><?php echo tipoAlerta($alertas, 'error-reg', 'email');?></label>
                    </div>

                    <div class="campo">
                        <label for="reg-password" class="campo-descripcion">Contraseña</label>
                        <input type="password" name="Us_Password"  id="reg-password" class="input input-negro" required>
                        <img src="/build/img/sistema/error.svg" alt="icono de error" class="icono-error ocultar">
                        <label class="msg-error ocultar"><?php echo tipoAlerta($alertas, 'error-reg', 'password');?></label>
                    </div>
                    
                    <!-- <input type="hidden" name="tipo" value="registro"> -->

                    <input type="submit" value="Registrar" data-button="registrar" class="boton btn-primario disabled">
                </form>        
            </div>
        </div>    
    </div>

</header>

<main id="sacinv" class="main contenedor">
    <h2>¿Qué es SACINV?</h2>
    <p>
        SACINV es un software web, que permite llevar el control del inventario de cada uno de los productos de tu negocio en tiempo real, lo que permite conocer de forma rápida y confiable no solo las cantidades de cada producto, sino verificar cuales son los que tienen más rotación, tomar decisiones con tiempo sobre su reabastecimiento, el valor de tu mercancía, etc.
    </p> 
    <p>
        Ten en cuenta que el inventario es la base para mover la economía de tu negocio ya que la idea es generar la mentalidad en tus clientes que siempre encontrarán los productos que ellos necesitan.
    </p>
    <p>
        Este software te ayudará a:
    </p>
    <div class="caracteristicas">
        <div class="caract-item">
            <img src="/build/img/sistema/stock.svg" alt="Icono de stock">
            <p>Conocer la cantidad en stock de cada producto en tiempo real.</p>
        </div>
        <div class="caract-item">
            <img src="/build/img/sistema/estadistica.svg" alt="Icono de stock">
            <p>Visualizar la estadística de venta o compra por día, mes o año.</p>
        </div>
        <div class="caract-item">
            <img src="/build/img/sistema/lista.svg" alt="Icono de stock">
            <p>Generar una lista automática de productos pendiente por comprar antes de que se acabe el stock.</p>
        </div>
        <div class="caract-item">
            <img src="/build/img/sistema/codigobarras.svg" alt="Icono de stock">
            <p>Generar códigos de barra para aquellos productos que no cuenten con este sistema de lectura.</p>
        </div>
        <div class="caract-item">
            <img src="/build/img/sistema/deudores.svg" alt="Icono de stock">
            <p>Registrar los clientes deudores, con la respectiva información de cada deuda, incluyendo el monto y registro de fecha y hora.</p>
        </div>
        <div class="caract-item">
            <img src="/build/img/sistema/pagos.svg" alt="Icono de stock">
            <p>Registrar los abonos de los clientes deudores, almacenando el soporte de pago de la transacción.</p>
        </div>   
    </div>
</main>

<section id="nosotros" class="seccion-nosotros">
    <h2 class="contenedor">Nosotros</h2>
    <p class="contenedor">
        Somos una empresa de desarrollo de software web a la medida, ubicada en el municipio de Guateque en el departamento de Boyacá. Estamos a su disposición y puede contar con nosotros para sistematizar procesos o procedimientos que permita ahorrar tiempo y esfuerzo para mejorar no solo la efectividad y la veracidad de la información sino garantizar la seguridad de la misma.
    </p>
</section>

<footer id="contactenos" class="footer-inicio">
    <h2 class="contenedor">Contáctenos</h2>
    <div class="footer-contenido contenedor">
        <div class="footer-logo">
            <img src="/build/img/sistema/logo-blanco.svg" alt="Logo Sacinv">
        </div>
        <div class="footer-contacto">
            <div class="linea">
                <img src="/build/img/sistema/phone.svg" alt="Icono de teléfono">
                <a href="tel:+573142965545">3142965545</a>
            </div>
            <div class="linea">
                <img src="/build/img/sistema/mail.svg" alt="Icono de correo">
                <a href="mailto:servicioalcliente@sacinv.com?subject=PQRS%20del%20cliente">servicioalcliente@sacinv.com</a>
            </div>
            <div class="linea">
                <img src="/build/img/sistema/direction.svg" alt="Icono de la Ubicación">
                <address>Calle 12 # 6 - 07, Guateque - Boyacá</address>
            </div>
            
            <iframe src="https://www.google.com/maps/embed?pb=!1m10!1m8!1m3!1d496.823399959248!2d-73.47236091771128!3d5.008010793419377!3m2!1i1024!2i768!4f13.1!5e0!3m2!1ses!2sco!4v1659613769376!5m2!1ses!2sco" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" class="mapa">
            </iframe>

        </div>

        <div class="footer-formulario">
            <form method="POST" action="/envio-mensaje" id="form-contacto" class="formulario formulario-negro">

                <h3>Envianos un mensaje</h3>

                <div class="campo">
                    <label for="msg-nombre" class="campo-descripcion">Nombre completo</label>
                    <input type="text" 
                            name="Us_Nombre" 
                            id="msg-nombre"
                            data-tipo="nombre" 
                            value="<?php echo isset($usuarioMsg->Us_Nombre) ? s($usuarioMsg->Us_Nombre) : '' ; ?>"
                            class="input input-negro" 
                            required>
                    <img src="/build/img/sistema/error.svg" alt="icono de error" class="icono-error ocultar">
                    <label class="msg-error ocultar"><?php echo tipoAlerta($alertas, 'error-msg', 'nombre');?></label>
                </div>

                <div class="campo">
                    <label for="msg-email" class="campo-descripcion">Email</label>
                    <input type="email" 
                            name="Us_Email" 
                            id="msg-email" 
                            value="<?php echo isset($usuarioMsg->Us_Email) ? s($usuarioMsg->Us_Email) : ''; ?>"
                            class="input input-negro" 
                            required>
                    <img src="/build/img/sistema/error.svg" alt="icono de error" class="icono-error ocultar">
                    <label class="msg-error ocultar"><?php echo tipoAlerta($alertas, 'error-msg', 'email');?></label>
                </div>
                <div class="campo">
                    <label for="msg-celular" class="campo-descripcion">Celular</label>
                    <input type="tel" 
                            name="Us_Celular" 
                            id="msg-celular" 
                            value="<?php echo isset($usuarioMsg->Us_Telefono) ? ($usuarioMsg->Us_Telefono) : ''; ?>"
                            maxlength="10" 
                            class="input input-negro"
                            required>
                    <img src="/build/img/sistema/error.svg" alt="icono de error" class="icono-error ocultar">
                    <label class="msg-error ocultar"><?php echo tipoAlerta($alertas, 'error-msg', 'telefono');?></label>
                </div>
                
                <div class="campo">
                    <label for="msg-mensaje" class="campo-descripcion lb-textarea">Mensaje</label>
                    <textarea name="Us_Mensaje" 
                                id="msg-mensaje" 
                                cols="30" 
                                rows="10"
                                class="input input-negro" 
                                required><?php echo isset($usuarioMsg->Us_Mensaje) ? ($usuarioMsg->Us_Mensaje) : ''; ?></textarea>
                    <img src="/build/img/sistema/error.svg" alt="icono de error" class="icono-error ocultar">
                    <label class="msg-error ocultar"><?php echo tipoAlerta($alertas, 'error-msg', 'mensaje');?></label>
                </div>                
                
                <input type="submit" 
                        value="Enviar" 
                        class="boton btn-primario disabled">

            </form>
        </div>
    </div>
</footer>

<?php
    $script='
        <script src="/build/js/login/loginForms.js"></script>
        <script src="/build/js/login/login.js"></script>
    '; 
?>
