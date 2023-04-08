<div class="form__campo t-xxl">
    <label for="nombreUbicacion" class="form__label--campo">Nombre de la Ubicación</label>
    <input type="text"
            id="nombreUbicacion"
            name="Ubicacion_Nombre"
            data-tipo="nombre"
            value="<?php echo isset($ubicacion->Ubicacion_Nombre) ? s($ubicacion->Ubicacion_Nombre) : ''; ?>"
            autocomplete="off"
            class="form__input input--bl"
            required>
    <a href="#" class="form__limpiar">x</a>
    <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
    <label class="form__labelSugerencia ocultar"></label>
    <label class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-ubicacion', 'nombre'); ?></label>
</div>
