<div class="form__campo t-xxl">
    <label for="metodoDePago" class="form__label--campo">Nombre de la Ubicación</label>
    <input type="text"
            id="metodoDePago"
            name="MP_Nombre"
            data-tipo="nombre"
            value="<?php echo isset($metodoPago->MP_Nombre) ? s($metodoPago->MP_Nombre) : ''; ?>"
            autocomplete="off"
            class="form__input input--bl"
            required>
    <a href="#" class="form__limpiar">x</a>
    <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
    <p class="form__labelSugerencia ocultar"></p>
    <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-metodoPago', 'nombre'); ?></p>
</div>
