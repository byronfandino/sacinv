<div class="title">
    <img class="title__img" src="/build/img/sistema/panelpago-ng.svg" width="30px" height="30px"/>
    <h1 class="title__h1">Editar Método de Pago</h1>
</div>

<!-- Se muestran las alertas a nivel general -->
<?php if(isset($alertas['error-metodoPago']['general'])){ ?>
        <p class="alerta error ocultar"><?php echo $alertas['error-metodoPago']['general']; ?></p>
<?php }else if(isset($alertas['exito-metodoPago']['general'])){ ?>
        <p class="alerta exito ocultar"><?php echo $alertas['exito-metodoPago']['general']; ?></p>
<?php } ?>

<form method="POST" action="" data-form="metodo-pago"  class="form">

    <?php include_once __DIR__ . '/formulario.php'; ?>

    <div class="form__campo check t-sm">
        <label for="estado" class="check__label--enunciado">Habilitado</label>
        <?php if(isset($metodoPago->MP_Status) && $metodoPago->MP_Status == "E"){?>
            <div class="check__content">
                <div class="check__estado"></div>
                <label for="" class="check__label">Si</label>
                <label for="" class="check__label ocultar">No</label>
                <input type="hidden" name="MP_Status" value="E">
            </div>
        <?php }else if(isset($metodoPago->MP_Status) && $metodoPago->MP_Status == "D"){?>
            <div class="check__content inactivo">
                <div class="check__estado inactivo"></div>
                <label for="" class="check__label ocultar">Si</label>
                <label for="" class="check__label ">No</label>
                <input type="hidden" name="MP_Status" value="D">
            </div>
        <?php } ?>
    </div>
    
    <div class="form__campo campo--button t-xxl">
        <input type="submit" 
                value="Actualizar" 
                data-button="btn-envio"
                class="form__btn btn-primario">

        <a href="/metodo-pago" class="form__btn btn-secundario">Cancelar</a>
    </div>
</form>

<?php
    if(isset($script) && $script != ''){
        $script .= '<script src="/build/js/panel/metodopago.js" type="module"></script>';
    }else{
        $script = '<script src="/build/js/panel/metodopago.js" type="module"></script>';
    }
?>