<div class="title">
    <img class="title__img" src="/build/img/sistema/panelubicacion-ng.svg" width="30px" height="30px"/>
    <h1 class="title__h1">Editar Ubicación</h1>
</div>

<!-- Se muestran las alertas a nivel general -->
<?php if(isset($alertas['error-ubicacion']['general'])){ ?>
        <p class="alerta error ocultar"><?php echo $alertas['error-ubicacion']['general']; ?></p>
<?php }else if(isset($alertas['exito-ubicacion']['general'])){ ?>
        <p class="alerta exito ocultar"><?php echo $alertas['exito-ubicacion']['general']; ?></p>
<?php } ?>

<form method="POST" action="" data-form="ubicacion" class="form">

    <?php include_once __DIR__ . '/formulario.php'; ?>
    
    <div class="form__campo campo--button t-xxl">
        <input type="submit" 
                data-button="btn-envio" 
                value="Actualizar" 
                class="form__btn btn-primario">

        <a href="/ubicacion" class="form__btn btn-secundario">Cancelar</a>
    </div>
</form>

<?php
    if(isset($script) && $script != ''){
        $script .= '<script src="/build/js/panel/ubicacion.js" type="module"></script>';
    }else{
        $script = '<script src="/build/js/panel/ubicacion.js" type="module"></script>';
    }
?>