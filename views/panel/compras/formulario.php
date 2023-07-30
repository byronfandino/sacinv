<fieldset class="fieldset__campos">
    <legend >Datos del Proveedor</legend>

    <input type="hidden" 
            id="Prov_Id"
            name="CM_FkProvId" 
            value="<?php echo isset($compraMaster->CM_FkProvId) ? $compraMaster->CM_FkProvId : ''; ?>"
            >

    <div class="form__campo t-xl">
        <label for="Prov_RazonSocial" class="form__label--campo">Nombre Proveedor</label>
        <input type="text" 
                id="Prov_RazonSocial"
                data-cy="Prov_RazonSocial" 
                value=""
                autocomplete="off"
                class="form__input input--bl">
        <a href="#" class="form__limpiar">x</a>
        <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
        <p class="form__labelSugerencia ocultar"></p>
        <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-compraMaster', 'CM_FkProvId');?></p>
        <ul class="form__busqueda ocultar" data-sugerencia="Prov_RazonSocial">
            <li data-busqueda="nuevo"><a href="/proveedor">Agrega un nuevo proveedor</a></li>
        </ul>
    </div>

    <div class="form__campo t-sm">
        <label for="Prov_Nit" class="form__label--campo">Nit</label>
        <input type="text" 
                id="Prov_Nit"
                data-cy="Prov_Nit" 
                value=""
                autocomplete="off"
                class="form__input input--bl disabled"
                disabled>
        <a href="#" class="form__limpiar">x</a>
        <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
        <p class="form__labelSugerencia ocultar"></p>
        <p class="form__labelError ocultar"></p>
    </div>

    <div class="form__campo t-sm">
        <label for="Prov_Tel" class="form__label--campo">Telefono</label>
        <input type="tel" 
                id="Prov_Tel"
                data-cy="Prov_Tel" 
                value=""
                autocomplete="off"
                class="form__input input--bl disabled"
                disabled>
        <a href="#" class="form__limpiar">x</a>
        <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
        <p class="form__labelSugerencia ocultar"></p>
        <p class="form__labelError  ocultar"></p>
    </div>

    <div class="form__campo t-md">
        <label for="Ciud_Nombre" class="form__label--campo">Ciudad</label>
        <input type="text" 
                id="Ciud_Nombre"
                data-cy="Ciud_Nombre" 
                value=""
                autocomplete="off"
                class="form__input input--bl disabled"
                disabled>
        <a href="#" class="form__limpiar">x</a>
        <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
        <p class="form__labelSugerencia ocultar"></p>
        <p class="form__labelError  ocultar"></p>
    </div>

    <div class="form__campo t-md">
        <label for="Depart_Nombre" class="form__label--campo">Departamento</label>
        <input type="text" 
                id="Depart_Nombre"
                data-cy="Depart_Nombre" 
                value=""
                autocomplete="off"
                class="form__input input--bl disabled"
                disabled>
        <a href="#" class="form__limpiar">x</a>
        <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
        <p class="form__labelSugerencia ocultar"></p>
        <p class="form__labelError ocultar"></p>
    </div>
</fieldset>

<fieldset class="fieldset__campos">
    <legend>Datos de la Factura de Compra</legend>
    <div class="form__campo t-sm">
        <label for="CM_NumFactura" class="form__label--campo">Número Factura</label>
        <input type="text" 
                id="CM_NumFactura"
                name="CM_NumFactura"
                data-cy="CM_NumFactura" 
                value=""
                autocomplete="off"
                class="form__input input--bl">
        <a href="#" class="form__limpiar">x</a>
        <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
        <p class="form__labelSugerencia ocultar"></p>
        <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-compraMaster', 'CM_NumFactura');?></p>
    </div>

    <fieldset class="fieldset__campos">
        <legend>Datos producto</legend>

        <input type="hidden" 
            id="Prod_Id"
            name="CD_FkProd_Id" 
            data-cy="Prod_Id" 
            value="<?php echo isset($compraDetalle->CD_FkProd_Id) ? $compraDetalle->CD_FkProd_Id : ''; ?>"
        >

        <div class="form__campo t-sm">
            <label for="Cod_Barras" class="form__label--campo">Código de Barras</label>
            <input type="text" 
                    id="Cod_Barras"
                    data-cy="Cod_Barras" 
                    value=""
                    autocomplete="off"
                    class="form__input input--bl">
            <a href="#" class="form__limpiar">x</a>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError  ocultar"></p>
            <ul class="form__busqueda ocultar" data-sugerencia="Cod_Barras">
                <li data-busqueda="nuevo"><a href="/producto">Agrega un nuevo producto</a></li>
            </ul>
        </div>

        <div class="form__campo t-sm">
            <label for="Cod_Manual" class="form__label--campo">Código Manual</label>
            <input type="text" 
                    id="Cod_Manual"
                    data-cy="Cod_Manual" 
                    value=""
                    autocomplete="off"
                    class="form__input input--bl">
            <a href="#" class="form__limpiar">x</a>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError  ocultar"></p>
            <ul class="form__busqueda ocultar" data-sugerencia="Cod_Manual">
                <li data-busqueda="nuevo"><a href="/producto">Agrega un nuevo producto</a></li>
            </ul>
        </div>

        <div class="form__campo t-xl">
            <label for="Prod_Descripcion" class="form__label--campo">Nombre de producto</label>
            <input type="text" 
                    id="Prod_Descripcion"
                    data-cy="Prod_Descripcion" 
                    value=""
                    autocomplete="off"
                    class="form__input input--bl">
            <a href="#" class="form__limpiar">x</a>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-compraMaster', 'CM_FkMP_Id');?></p>
            <ul class="form__busqueda ocultar" data-sugerencia="Prod_Descripcion">
                <li data-busqueda="nuevo"><a href="/producto">Agrega un nuevo producto</a></li>
            </ul>
        </div>

        <div class="form__campo t-sm">
            <label for="CD_Cantidad" class="form__label--campo">Cantidad</label>
            <input type="number" 
                    id="CD_Cantidad"
                    name="CD_Cantidad"
                    data-cy="CD_Cantidad" 
                    value="<?php echo isset($compraDetalle->CD_Cantidad) ? $compraDetalle->CD_Cantidad : ''; ?>"
                    autocomplete="off"
                    class="form__input input--bl">
            <a href="#" class="form__limpiar">x</a>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-compraDetalle', 'CD_Cantidad');?></p>
        </div>

        <div class="form__campo t-sm">
            <label for="CD_ValorUnit" class="form__label--campo">Valor Unit con IVA</label>
            <input type="number" 
                    id="CD_ValorUnit"
                    name="CD_ValorUnit"
                    data-cy="CD_ValorUnit" 
                    value="<?php echo isset($compraDetalle->CD_ValorUnit) ? $compraDetalle->CD_ValorUnit : ''; ?>"
                    autocomplete="off"
                    class="form__input input--bl">
            <a href="#" class="form__limpiar">x</a>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-compraDetalle', 'CD_ValorUnit');?></p>
        </div>

        <div class="form__campo t-sm item-azul">
            <label for="CD_Total" class="form__label--campo">Valor Total</label>
            <input type="number" 
                    id="CD_Total"
                    name="CD_Total"
                    data-cy="CD_Total" 
                    value="<?php echo isset($compraDetalle->CD_Total) ? $compraDetalle->CD_Total : ''; ?>"
                    autocomplete="off"
                    class="form__input input--bl disabled"
                    disabled>
            <a href="#" class="form__limpiar">x</a>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-compraDetalle', 'CD_Total');?></p>
        </div>

        <div class="form__campo t-sm item-rojo">
            <label for="CD_XjeDesc" class="form__label--campo">% Descuento</label>
            <input type="number" 
                    id="CD_XjeDesc"
                    name="CD_XjeDesc"
                    data-cy="CD_XjeDesc" 
                    value="<?php echo isset($compraDetalle->CD_XjeDesc) ? $compraDetalle->CD_XjeDesc : '0'; ?>"
                    autocomplete="off"
                    class="form__input input--bl"
            >
            <a href="#" class="form__limpiar">x</a>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-compraDetalle', 'CD_XjeDesc');?></p>
        </div>

        <div class="form__campo t-sm item-rojo">
            <label for="CD_ValorDesc" class="form__label--campo">Valor Descuento</label>
            <input type="number" 
                    id="CD_ValorDesc"
                    name="CD_ValorDesc"
                    data-cy="CD_ValorDesc" 
                    value="<?php echo isset($compraDetalle->CD_ValorDesc) ? $compraDetalle->CD_ValorDesc : '0'; ?>"
                    autocomplete="off"
                    class="form__input input--bl disabled"
                    disabled
            >
            <a href="#" class="form__limpiar">x</a>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-compraDetalle', 'CD_ValorDesc');?></p>
        </div>

        <div class="form__campo t-sm item-verde">
            <label for="CD_XjeVenta" class="form__label--campo">% de Venta</label>
            <input type="number" 
                    id="CD_XjeVenta"
                    name="CD_XjeVenta"
                    data-cy="CD_XjeVenta" 
                    value="<?php echo isset($parametro->Pm_XjeVenta) ? $parametro->Pm_XjeVenta : '0'; ?>"
                    autocomplete="off"
                    class="form__input input--bl"
            >
            <a href="#" class="form__limpiar">x</a>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-compraMaster', 'nit');?></p>
        </div>

        <div class="form__campo t-sm item-verde">
            <label for="CD_ValorVenta" class="form__label--campo">Precio de Venta</label>
            <input type="number" 
                    id="CD_ValorVenta"
                    name="CD_ValorVenta"
                    data-cy="CD_ValorVenta" 
                    value="<?php echo isset($compraDetalle->CD_ValorVenta) ? $compraDetalle->CD_ValorVenta : '0'; ?>"
                    autocomplete="off"
                    class="form__input input--bl"
            >
            <a href="#" class="form__limpiar">x</a>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-compraDetalle', 'CD_ValorVenta');?></p>
        </div>
    </fieldset>

    <input type="hidden" 
            id="CM_Subtotal"
            name="CM_Subtotal" 
            data-cy="CM_Subtotal" 
            value="">

    <input type="hidden" 
            id="CM_IVA"
            name="CM_IVA" 
            data-cy="CM_IVA" 
            value="">

    <input type="hidden" 
            id="CM_Descuento"
            name="CM_Descuento"
            data-cy="CM_Descuento" 
            value="">

    <input type="hidden" 
            id="CM_TotalFactura"
            name="CM_TotalFactura" 
            data-cy="CM_TotalFactura" 
            value="">

    <div class="form__campo t-xxl">
        <label for="CM_Observaciones" class="form__label--campo">Observaciones</label>
        <textarea id="CM_Observaciones"
                    name="CM_Observaciones" 
                    class="form__input input--bl"
                    data-cy="CM_Observaciones" ></textarea>
        <a href="#" class="form__limpiar">x</a> 
        <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
        <p class="form__labelSugerencia ocultar"></p>
        <p class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-compraMaster', 'observaciones');?></p>
    </div>
    
    <div class="form__campo t-md">
        <label for="CM_EstadoFactura" class="form__label--campo">Estado factura</label>
        <select id="CM_EstadoFactura" 
                name="MP_Id"
                data-tipo="CM_EstadoFactura" 
                data-cy="CM_EstadoFactura"
                class="form__input input--bl"
                >
            <option value="C" selected>Cancelado</option>
            <option value="S" >Sin Cancelar</option>
        </select>
        <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
        <p class="form__labelSugerencia ocultar"></p>
        <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-compraMaster', 'categoria');?></p>
    </div>

    <div class="form__campo t-md">
        <label for="metodo-pago" class="form__label--campo">Método de Pago</label>
        <select id="metodo-pago" 
                name="MP_Id"
                data-tipo="metodo-pago" 
                data-cy="metodo-pago"
                class="form__input input--bl"
                >
            <option value="" selected disabled>--   --</option>
            <option value="" class="optionCustom" data-option="nuevo">Nuevo Método de Pago</option>
        </select>
        <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
        <p class="form__labelSugerencia ocultar"></p>
        <p class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-compraMaster', 'categoria');?></p>
    </div>


    <div class="form__campo form__button t-xxl">
        <input type="button" 
                data-tipo="boton-adjuntar"
                data-cy="botonAdjuntar"
                value="Adjuntar Factura"
                class="form__btn btn-cuaternario">
    </div>
</fieldset>