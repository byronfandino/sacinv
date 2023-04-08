<?php
    if($error){
        // Nos aseguramos que no cargue la página cuando el error es igual a true
        return;
    }
?>

<h1>Recuperar contraseña</h1>

<form method="POST" action=""  class="formulario formulario-negro">

    <div class="campo">
        <label for="password" class="campo-descripcion">Nueva contraseña</label>
        <input type="password" name="Us_Password" id="password" class="input input-negro" required>
        <img src="/build/img/sistema/error.svg" alt="icono de error" class="icono-error ocultar">
        <label class="msg-error ocultar"><?php echo tipoAlerta($alertas, 'error-recuperar', 'password');?></label>
    </div>

    <input type="submit" value="Renovar contraseña" class="boton btn-primario">

</form>

<a href="/">Inicia sesión</a>

<?php
    $script="<script src='/build/js/login/loginForms.js'></script>"; 
?>
 