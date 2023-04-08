<?php
    // debuguear($alertas);
?>
<h1>Recupera tu contraseña</h1>

<form method="POST" action="/olvide"  class="formulario formulario-negro">

    <div class="campo">
        <label for="email" class="campo-descripcion">Email</label>
        <input type="email" name="Us_Email" id="email" class="input input-negro" required>
        <img src="/build/img/sistema/error.svg" alt="icono de error" class="icono-error ocultar">
        <label class="msg-error ocultar"><?php echo tipoAlerta($alertas, 'error-olvide', 'email');?></label>
    </div>

    <input type="submit" value="Enviar" class="boton btn-primario">

</form>

<a href="/">Inicia sesión</a>

<?php
    $script="<script src='/build/js/login/loginForms.js'></script>"; 
?>