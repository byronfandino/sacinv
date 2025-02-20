
<header class="header">
    <img src="/build/img/cxc.svg" alt="Icono de cuentas por cobrar" width="30px" height="30px">
    <h1>Cuentas por cobrar</h1>
</header>
    
<form class="form" id="form_deuda_actualizar">

    <fieldset>
        <legend>Movimiento</legend>
        
        <div class="contenedor__campos">

            <input type="hidden" id="id_mov_actualizar" name="id_mov">

            <div class="form__campo t-md">
                <label for="tipo_mov_actualizar">Tipo de movimiento</label>
                <select name="tipo_mov" id="tipo_mov_actualizar" class="campo__input">
                    <option value="" selected disabled>-Seleccione una opción-</option>
                    <option value="D">Debe</option>
                    <option value="A">Abona</option>
                    <option value="R">Devolución</option>
                </select>
                <p class="label__error ocultar"></p>
            </div>
            
            <div class="form__campo t-sm">
                <label for="cant_actualizar">Cantidad</label>
                <input type="number" name="cant" id="cant_actualizar" value = "1" class="campo__input">
                <p class="label__error ocultar"></p>
            </div>

            <div class="form__campo t-sm">
                <label for="valor_unit_actualizar">Valor Unitario</label>
                <input type="number" name="valor_unit" id="valor_unit_actualizar" class="campo__input">
                <p class="label__error ocultar"></p>
            </div>

            <div class="form__campo t-sm">
                <label for="valor_total_actualizar">Valor Total</label>
                <input type="number" name="valor_total" id="valor_total_actualizar" class="campo__input disabled">
                <p class="label__error ocultar"></p>
            </div>
            
            <div class="form__campo t-xl">
                <label for="descripcion_actualizar">Observaciones</label>
                <input type="text" name="descripcion" id="descripcion_actualizar" class="campo__input">
                <p class="label__error ocultar"></p>
            </div>
        </div>

        <div class="contenedor__botones">
            <input type="submit" value="Actualizar" class="boton boton--primario">
        </div>
    </fieldset>                
</form>  
