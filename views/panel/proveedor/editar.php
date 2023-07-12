<div class="title">
    <img class="title__img" src="/build/img/sistema/panelproveedores-ng.svg" width="30px" height="30px"/>
    <h1 class="title__h1">Proveedores</h1>
</div>

<!-- Verificación de errores generales -->
<?php if(isset($alertas['error-proveedor']['general'])){ ?>
        <p class="alerta error ocultar"><?php echo $alertas['error-proveedor']['general']; ?></p>
<?php }else if(isset($alertas['exito-proveedor']['general'])){ ?>
        <p class="alerta exito ocultar"><?php echo $alertas['exito-proveedor']['general']; ?></p>
<?php } ?>

<form method="POST" action="" data-form="proveedor" data-cy="form-proveedor" class="form">
     
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
        $script .= '<script src="/build/js/panel/proveedoreditar.js" type="module"></script>';
    }else{
        $script = '<script src="/build/js/panel/proveedoreditar.js" type="module"></script>';
    }
?>
