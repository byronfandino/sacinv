<div class="title">
    <img class="title__img" src="/build/img/sistema/panelproductos-ng.svg" width="30px" height="30px"/>
    <h1 class="title__h1">Productos</h1>
</div>

<!-- Verificación de errores generales -->
<?php if(isset($alertas['error-producto']['general'])){ ?>
        <p class="alerta error ocultar"><?php echo $alertas['error-producto']['general']; ?></p>
<?php }else if(isset($alertas['exito-producto']['general'])){ ?>
        <p class="alerta exito ocultar"><?php echo $alertas['exito-producto']['general']; ?></p>
<?php } ?>

<!-- Ventana Modal -->
<div class="fondo-notificacion marca ocultar">
    <div class="contenedor-notificacion">
        <?php 
            include_once __DIR__ . '/../marca/modal.php';
        ?>
    </div>
</div>

<!-- Ventana Modal -->
<div class="fondo-notificacion categoria ocultar">
    <div class="contenedor-notificacion">
        <?php 
            include_once __DIR__ . '/../categoria/modal.php';
        ?>
    </div>
</div>

<div class="fondo-notificacion preview ocultar">
    <a class="cerrar-preview">X</a>

    <!-- <img src="" alt="Archivo multimedia del servidor"> -->
    
    <figure class="eliminar-imagen">
        <img src="/build/img/sistema/eliminar-bl.svg" alt="Icono para eliminar imagen">
    </figure>
</div>

<form method="POST" data-form="producto" class="form" enctype="multipart/form-data" data-cy="form-producto">

    <input type="hidden" 
            name="Prod_Id"
            data-id="prod_Id"
            data-cy="prodid"
            value="<?php echo isset($producto->Prod_Id) ? s($producto->Prod_Id) : ''; ?>"
    >
    <fieldset class="fieldset__campos">
        <legend>Datos generales</legend>
        <div class="form__campo t-xl">
            <label for="prodDescripcion" class="form__label--campo">Descripción</label>
            <input type="text" 
                    id="prodDescripcion"
                    name="Prod_Descripcion" 
                    data-tipo="prodDescripcion" 
                    data-cy="prodDescripcion"
                    value="<?php echo isset($producto->Prod_Descripcion) ? s($producto->Prod_Descripcion) : ''; ?>"
                    autocomplete="off"
                    class="form__input input--bl"
                    >
            <a href="#" class="form__limpiar">x</a>        
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <label class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-producto', 'descripcion');?></label>
        </div>

        <div class="form__campo t-md">
            <label for="categoria" class="form__label--campo">Categoría</label>
            <select id="categoria" 
                    name="Prod_FkCtg_Id"
                    data-tipo="categoria"
                    data-value-server="<?php echo isset($producto->Prod_FkCtg_Id) ? s($producto->Prod_FkCtg_Id) : ''; ?>" 
                    data-cy="categoria"
                    class="form__input input--bl"
                    >
                <option value="" selected disabled>--   --</option>
                <option value="" class="optionCustom" data-option="nuevo">Nueva Categoría</option>
            </select>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <label class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-producto', 'categoria');?></label>
        </div>

        <div class="form__campo t-md">
            <label for="marca" class="form__label--campo">Marca</label>
            <select id="marca" 
                    name="Prod_FkMc_Id"
                    data-tipo="marca" 
                    data-value-server="<?php echo isset($producto->Prod_FkMc_Id) ? s($producto->Prod_FkMc_Id) : ''; ?>" 
                    data-cy="marca"
                    class="form__input input--bl"
                    >
                <option value="" selected disabled>--   --</option>
                <option value="" class="optionCustom" data-option="nuevo">Nueva Marca</option>

            </select>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <label class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-producto', 'marca');?></label>
        </div>

        <div class="form__campo t-sm">
            <label for="cantStock" class="form__label--campo">Cant Min. Stock</label>
            <input type="number" 
                    id="cantStock"
                    name="Prod_CantMinStock" 
                    data-tipo="cantStock"
                    data-cy="cantStock"
                    value="<?php echo isset($producto->Prod_CantMinStock) ? s($producto->Prod_CantMinStock) : ''; ?>"
                    autocomplete="off"
                    class="form__input input--bl"
                    >
            <a href="#" class="form__limpiar">x</a> 
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <label class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-producto', 'cantidad-stock');?></label>
        </div>

        <div class="form__campo t-sm item-verde">
            <label for="valorVenta" class="form__label--campo">Valor de Venta</label>
            <input type="number" 
                    id="valorVenta"
                    name="Prod_ValorVenta" 
                    data-tipo="valorVenta"
                    data-cy="valorVenta"
                    value="<?php echo isset($producto->Prod_ValorVenta) ? s($producto->Prod_ValorVenta) : ''; ?>"
                    autocomplete="off"
                    class="form__input input--bl"
                    >
            <a href="#" class="form__limpiar">x</a> 
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <label class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-producto', 'valor-venta');?></label>
        </div>

        <div class="form__campo t-sm item-rojo">
            <label for="valorDescuento" class="form__label--campo">Valor Descuento</label>
            <input type="number" 
                    id="valorDescuento"
                    name="Prod_ValorDesc" 
                    data-tipo="valorDescuento"
                    data-cy="valorDescuento"
                    value="<?php echo isset($producto->Prod_ValorDesc) ? s($producto->Prod_ValorDesc) : ''; ?>"
                    autocomplete="off"
                    class="form__input input--bl"
                    >
            <a href="#" class="form__limpiar">x</a> 
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <label class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-producto', 'valor-descuento');?></label>
        </div>

        <div class="form__campo t-xxl">
            <label for="prodObservaciones" class="form__label--campo">Observaciones</label>
            <input type="text" 
                    id="prodObservaciones"
                    name="Prod_Observ" 
                    data-tipo="prodObservaciones"
                    data-cy="prodObservaciones" 
                    value="<?php echo isset($producto->Prod_Observ) ? s($producto->Prod_Observ) : ''; ?>"
                    autocomplete="off"
                    class="form__input input--bl">
            <a href="#" class="form__limpiar">x</a> 
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <label class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-producto', 'observaciones');?></label>
        </div>
    </fieldset>
    
    <fieldset class="form__multirregistros__tabla">
        <legend>Códigos de barras y manuales</legend>
        <div class="form__contenedor__multirregistros">

            <div class="form__campo t-md">
                <label for="codigoBarras" class="form__label--campo">Código de barras</label>
                <input type="text" 
                        id="Cod_Barras"
                        name="Cod_Barras" 
                        data-tipo="codigoBarras"
                        data-cy="codigoBarras" 
                        value=""
                        autocomplete="off"
                        class="form__input input--bl"
                >
                <a href="#" class="form__limpiar">x</a>
                <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
                <p class="form__labelSugerencia ocultar"></p>
                <label class="form__labelError  ocultar"><?php //echo tipoAlerta($alertas, 'error-producto', 'codigo-barras');?></label>
            </div>
        
            <div class="form__campo t-sm">
                <label for="codigoManual" class="form__label--campo">Código Manual</label>
                <input type="text" 
                        id="Cod_Manual"
                        name="Cod_Manual" 
                        data-tipo="codigoManual" 
                        data-cy="codigoManual" 
                        value=""
                        autocomplete="off"
                        class="form__input input--bl"
                        >
                <a href="#" class="form__limpiar">x</a>
                <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
                <p class="form__labelSugerencia ocultar"></p>
                <label class="form__labelError  ocultar"><?php //echo tipoAlerta($alertas, 'error-producto', 'codigo-manual');?></label>
            </div>

            <div class="form__campo">
                <input type="button" 
                        data-tipo="boton-codigo"
                        data-cy="botonCodigo"
                        value="Agregar código"
                        class="form__btn btn-cuaternario disabled" disabled>
            </div>

        </div>

        <h3>Listado de codigos</h3>
        <div class="contenedor-table">
            <table class="table-simple width-auto" data-tipo="tblCodigo">
                <thead class="thead">
                    <tr>
                        <th>COD. BARRAS</th>
                        <th>COD. MANUAL</th>
                        <th class="thead__th--icon">Eliminar</th>
                    </tr>
                </thead>
                <tbody class="tbody">
                    <!-- Aquí se cargan los registros -->
                </tbody>
            </table>
        </div>
    </fieldset> 

    <fieldset class="form__multirregistros__tabla">
        <legend>Ofertas</legend>
        <div class="form__contenedor__multirregistros">
                
            <div class="form__campo t-sm item-azul">
                <label for="PO_Cant" class="form__label--campo">Cant. x Oferta</label>
                <input type="number" 
                        id="PO_Cant"
                        name="PO_Cant" 
                        data-tipo="cantOferta"
                        data-cy="cantOferta"
                        value=""
                        autocomplete="off"
                        class="form__input input--bl"
                        >
                <a href="#" class="form__limpiar">x</a> 
                <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
                <p class="form__labelSugerencia ocultar"></p>
                <label class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-producto', 'oferta-cantidad');?></label>
            </div>

            <div class="form__campo t-sm item-azul">
                <label for="PO_ValorOferta" class="form__label--campo">Valor x Oferta</label>
                <input type="number" 
                        id="PO_ValorOferta"
                        name="PO_ValorOferta" 
                        data-tipo="valorOferta"
                        data-cy="valorOferta"
                        value=""
                        autocomplete="off"
                        class="form__input input--bl"
                        >
                <a href="#" class="form__limpiar">x</a> 
                <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
                <p class="form__labelSugerencia ocultar"></p>
                <label class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-producto', 'oferta-valor');?></label>
            </div>

            <div class="form__campo ">
                <input type="button" 
                        data-tipo="boton-oferta"
                        data-cy="botonOferta"
                        value="Agregar oferta"
                        class="form__btn btn-cuaternario disabled" disabled>
            </div>
        </div>

        <h3>Listado de ofertas</h3>
        
        <div class="contenedor-table width-auto">
            <table class="table-simple width-auto" data-tipo="tblOferta">
                <thead class="thead">
                    <tr>
                        <th>Cant. Oferta</th>
                        <th>Valor Oferta</th>
                        <th class="thead__th--icon">Eliminar</th>
                    </tr>
                </thead>
                <tbody class="tbody">
                    <!-- Aquí se cargan los registros -->
                </tbody>
            </table>
        </div>
    </fieldset> 

    <fieldset class="fieldset__archivos">
        <legend>Archivos</legend>

        <!-- Botón adjuntar archivo -->
        <div class="form__campo form__button t-xxl">
            <input type="button" 
                data-tipo="boton-adjuntar"
                data-cy="botonAdjuntar"
                value="Adjuntar Adchivo"
                class="form__btn btn-cuaternario">
        </div>

        <!-- Archivos multimedia -->
        <h3>Archivos multimedia</h3>
        <div class="file__content">
            <!-- Aquí se colocan los archivos multimedia del servidor -->
        </div>
    </fieldset>
    <div class="form__campo campo--button t-xxl">
        
        <input type="submit" 
                data-btn="btn-enviar"
                value="Actualizar" 
                class="form__btn btn-primario " 
        >
        
        <a href="/producto" class="form__btn btn-secundario">Cancelar</a>
    </div>
</form>

<?php
    if(isset($script) && $script != ''){
        // $script .= '<script src="/build/js/panel/marcaModal.js"></script>';
        $script .= '<script src="/build/js/panel/producto.js" type="module"></script>';
    }else{
        // $script = '<script src="/build/js/panel/marcaModal.js"></script>';
        $script = '<script src="/build/js/panel/producto.js" type="module"></script>';
    }
?>
