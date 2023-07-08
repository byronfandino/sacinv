<div class="form__campo t-xxl">
    <label for="nombreMarca" class="form__label--campo">Nombre de la Marca</label>
    <input type="text"
            id="nombreMarca"
            name="Mc_Descripcion"
            data-tipo="nombre"
            value="<?php echo isset($marca->Mc_Descripcion) ? s($marca->Mc_Descripcion) : ''; ?>"
            autocomplete="off"
            class="form__input input--bl"
            required>
    <a href="#" class="form__limpiar">x</a>
    <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
    <p class="form__labelSugerencia label_marca ocultar"></p>
    <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-marca', 'nombre'); ?></p>
</div>
