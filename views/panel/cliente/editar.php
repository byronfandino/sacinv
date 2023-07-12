<div class="title">
    <img class="title__img" src="/build/img/sistema/panelclientes-ng.svg" width="30px" height="30px"/>
    <h1 class="title__h1">Clientes</h1>
</div>

<!-- Verificación de errores generales -->
<?php if(isset($alertas['error-cliente']['general'])){ ?>
        <p class="alerta error ocultar"><?php echo $alertas['error-cliente']['general']; ?></p>
<?php }else if(isset($alertas['exito-cliente']['general'])){ ?>
        <p class="alerta exito ocultar"><?php echo $alertas['exito-cliente']['general']; ?></p>
<?php } ?>

<form method="POST" action="" data-form="cliente" data-cy="form-cliente" class="form">
    
    <?php include_once 'formulario.php'; ?>
    
    <div class="form__campo campo--button t-xxl">
        <input type="submit" 
                data-btn="btn-enviar"
                value="Actualizar" 
                class="form__btn btn-primario disabled"
                disabled 
        >
        <a href="/cliente" class="form__btn btn-secundario">Cancelar</a>
    </div>
</form>

<?php
    if(isset($script) && $script != ''){
        $script .= '<script src="/build/js/panel/clienteeditar.js" type="module"></script>';
    }else{
        $script = '<script src="/build/js/panel/clienteeditar.js" type="module"></script>';
    }
?>
