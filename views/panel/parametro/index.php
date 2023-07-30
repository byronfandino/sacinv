<div class="title">
    <img class="title__img" src="/build/img/sistema/panelparametros-ng.svg" width="30px" height="30px"/>
    <h1 class="title__h1">Parámetros</h1>
</div>

<!-- Se muestran las alertas a nivel general -->
<?php if(isset($alertas['error-parametro']['general'])){ ?>
        <p class="alerta error ocultar"><?php echo $alertas['error-parametro']['general']; ?></p>
<?php }else if(isset($alertas['exito-parametro']['general'])){ ?>
        <p class="alerta exito ocultar"><?php echo $alertas['exito-parametro']['general']; ?></p>
<?php } ?>

<form method="POST" action="/parametro" data-form="parametro"  class="form" enctype="multipart/form-data">
    <fieldset class="fieldset__campos">
        <legend>Datos Generales de la Empresa</legend>
        <div class="form__campo t-sm">
            <label for="nit" class="form__label--campo">Nit<span class="obligatorio">*</span></label>
            <input type="text" 
                    id="nit"
                    name="Pm_Nit" 
                    data-tipo="nit" 
                    value="<?php echo isset($parametro->Pm_Nit) ? $parametro->Pm_Nit : ''; ?>"
                    autocomplete="on"
                    class="form__input input--bl"
                    required>
            <a href="#" class="form__limpiar">x</a>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-parametro', 'nit'); ?></p>
        </div>

        <div class="form__campo t-xl">
            <label for="razonSocial" class="form__label--campo">Razón Social<span class="obligatorio">*</span></label>
            <input type="text" 
                    id="razonSocial"
                    name="Pm_RazonSocial"
                    data-tipo="razonsocial"
                    value="<?php echo isset($parametro->Pm_RazonSocial) ? $parametro->Pm_RazonSocial : ''; ?>"
                    autocomplete="on"
                    class="form__input input--bl"
                    required>
            <a href="#" class="form__limpiar">x</a>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-parametro', 'razonsocial'); ?></p>
        </div>

        <div class="form__campo t-md">
            <label for="direccion" class="form__label--campo">Dirección<span class="obligatorio">*</span></label>
            <input type="text" 
                    id="direccion"
                    name="Pm_Direccion" 
                    data-tipo="direccion" 
                    value="<?php echo isset($parametro->Pm_Direccion) ? $parametro->Pm_Direccion : ''; ?>"
                    autocomplete="on"
                    class="form__input input--bl"
                    required>
            <a href="#" class="form__limpiar">x</a>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-parametro', 'direccion'); ?></p>
        </div>

        <div class="form__campo t-sm">
            <label for="telefono" class="form__label--campo">Celular<span class="obligatorio">*</span></label>
            <input type="tel" 
                    id="telefono"
                    name="Pm_Tel" 
                    data-tipo="tel" 
                    value="<?php echo isset($parametro->Pm_Tel) ? $parametro->Pm_Tel : ''; ?>"
                    autocomplete="on"
                    class="form__input input--bl"
                    required>
            <a href="#" class="form__limpiar">x</a>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-parametro', 'telefono'); ?></p>
        </div>

        <div class="form__campo t-sm">
            <label for="whatsapp" class="form__label--campo">Whastapp</label>
            <input type="tel" 
                    id="whatsapp"
                    name="Pm_Whatsapp" 
                    data-tipo="whatsapp" 
                    value="<?php echo isset($parametro->Pm_Whatsapp) && $parametro->Pm_Whatsapp != '' ? $parametro->Pm_Whatsapp : 'N/A'; ?>"
                    autocomplete="on"
                    class="form__input input--bl"
                    required>
            <a href="#" class="form__limpiar">x</a>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-parametro', 'whatsapp'); ?></p>
        </div>

        <div class="form__campo t-xl">
            <label for="email" class="form__label--campo">E-mail<span class="obligatorio">*</span></label>
            <input type="email" 
                    id="email"
                    name="Pm_Email" 
                    data-tipo="email" 
                    value="<?php echo isset($parametro->Pm_Email) ? $parametro->Pm_Email : ''; ?>"
                    autocomplete="on"
                    class="form__input input--bl"
                    required>
            <a href="#" class="form__limpiar">x</a>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-parametro', 'email'); ?></p>
        </div>

        <div class="form__campo t-xxl">
            <label for="Slogan" class="form__label--campo">Slogan</label>
            <input type="text" 
                    id="Slogan"
                    name="Pm_Slogan" 
                    data-tipo="slogan" 
                    placeholder="Escribe N/A si no tiene ningun slogan"
                    value="<?php echo isset($parametro->Pm_Slogan) && $parametro->Pm_Slogan!='' ? $parametro->Pm_Slogan : 'N/A'; ?>"
                    autocomplete="on"
                    class="form__input input--bl"
                    required>
            <a href="#" class="form__limpiar">x</a>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-parametro', 'slogan'); ?></p>
        </div>

        <div class="form__campo campo--file t-xxl">
            <input type="file" 
                    data-tipo="logo"
                    name="Pm_Logo"
                    accept="image/png,image/jpg,image/jpeg"
                    >

            <input type="button" 
                    data-tipo="boton-logo"
                    value="Adjuntar Logo"
                    class="form__btn btn-terciario">

            <span class="textFileSelect"></span>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-parametro', 'imagen'); ?></p>
            <div class="contenedor-imagenes">
                <?php if (isset($parametro->Pm_Logo) && $parametro->Pm_Logo!==''){?>
                    <figure class="contenedor-logo">
                        <img src="/build/img/logo/<?php echo $parametro->Pm_Logo; ?>" width="10px" height="10px" alt="Imagen del logo guardado">
                    </figure>
                    <a href="#" class="eliminar-imagen"><img src="/build/img/sistema/eliminar.svg" alt="Icono Eliminar" width="15px" height="15px"></a>
                <?php } ?>
            </div>
        </div>
    </fieldset>

    <fieldset class="fieldset__campos">
        <legend>Información adicional</legend>

        <div class="form__campo t-sm">
            <label for="Pm_XjeVenta" class="form__label--campo">% de Ganancia<span class="obligatorio">*</span></label>
            <input type="number" 
                    id="Pm_XjeVenta"
                    name="Pm_XjeVenta" 
                    data-tipo="xjeventa" 
                    value="<?php echo isset($parametro->Pm_XjeVenta) ? $parametro->Pm_XjeVenta : ''; ?>"
                    autocomplete="on"
                    class="form__input input--bl"
                    required>
            <a href="#" class="form__limpiar">x</a>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-parametro', 'Pm_XjeVenta'); ?></p>
        </div>

        <div class="form__campo t-sm">
            <label for="Pm_XjeIVA" class="form__label--campo">% IVA<span class="obligatorio">*</span></label>
            <input type="number" 
                    id="Pm_XjeIVA"
                    name="Pm_XjeIVA" 
                    data-tipo="Pm_XjeIVA" 
                    value="<?php echo isset($parametro->Pm_XjeIVA) ? $parametro->Pm_XjeIVA : ''; ?>"
                    autocomplete="on"
                    class="form__input input--bl"
                    required>
            <a href="#" class="form__limpiar">x</a>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-parametro', 'Pm_XjeIVA'); ?></p>
        </div>

        <div class="form__campo t-md">
            <label for="tipoInv" class="form__label--campo">Tipo Inventario<span class="obligatorio">*</span></label>
            <select id="tipoInv" 
                    name="Pm_TipoInv"
                    data-tipo="inventario" 
                    class="form__input input--bl"
                    required>
                <option value="" selected disabled>Seleccione una opción</option>
                <option value="F" <?php echo isset($parametro->Pm_TipoInv) && $parametro->Pm_TipoInv == 'F' ? 'selected' : ''; ?> >FIFO</option>
                <option value="L" <?php echo isset($parametro->Pm_TipoInv) && $parametro->Pm_TipoInv == 'L' ? 'selected' : ''; ?> >LIFO</option>
                <option value="P" <?php echo isset($parametro->Pm_TipoInv) && $parametro->Pm_TipoInv == 'P' ? 'selected' : ''; ?> >Promedio Ponderado</option>
            </select>
            <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
            <p class="form__labelSugerencia ocultar"></p>
            <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-parametro', 'tipo-inventario');?></p>
        </div>
    </fieldset>

    <div class="form__campo campo--button t-xxl">
        <input type="submit"
                data-tipo="boton" 
                value="Guardar" 
                class="form__btn btn-primario disabled" 
                disabled>
    </div>
</form>

<?php
    if(isset($script) && $script != ''){
        $script .= '<script src="/build/js/panel/parametro.js" type="module"></script>';
    }else{
        $script = '<script src="/build/js/panel/parametro.js" type="module"></script>';
    }
?>

