<div class="form__campo t-xxl">
    <label for="tabla" class="form__label--campo">Nombre de la tabla</label>
    <input type="text"
            id="tabla"
            name="Tb_Nombre"
            data-tipo="nombre"
            value="<?php echo isset($tabla->Tb_Nombre) ? s($tabla->Tb_Nombre) : ''; ?>"
            autocomplete="off"
            class="form__input input--bl"
            required>
    <a href="#" class="form__limpiar">x</a>
    <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
    <label class="form__labelSugerencia ocultar"></label>
    
    <label class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-tabla', 'nombre'); ?></label>
    
</div>
