<div class="form__campo t-xxl">
    <label for="nombreCategoria" class="form__label--campo">Nombre de la Categoría</label>
    <input type="text"
            id="nombreCategoria"
            name="Ctg_Descripcion"
            data-tipo="nombre"
            value="<?php echo isset($categoria->Ctg_Descripcion) ? s($categoria->Ctg_Descripcion) : ''; ?>"
            autocomplete="off"
            class="form__input input--bl"
            required>
    <a href="#" class="form__limpiar">x</a>
    <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
    <label class="form__labelSugerencia ocultar"></label>
    <label class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-categoria', 'nombre'); ?></label>
</div>
