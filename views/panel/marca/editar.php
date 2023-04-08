<div class="title">
    <img class="title__img" src="/build/img/sistema/panelmarcas-ng.svg" width="30px" height="30px"/>
    <h1 class="title__h1">Editar Marca</h1>
</div>

<!-- Se muestran las alertas a nivel general -->
<?php if(isset($alertas['error-marca']['general'])){ ?>
        <p class="alerta error ocultar"><?php echo $alertas['error-marca']['general']; ?></p>
<?php }else if(isset($alertas['exito-marca']['general'])){ ?>
        <p class="alerta exito ocultar"><?php echo $alertas['exito-marca']['general']; ?></p>
<?php } ?>

<form method="POST" action="" class="form">

    <?php include_once __DIR__ . '/formulario.php'; ?>
    
    <div class="form__campo campo--button t-xxl">
        <input type="submit" 
                value="Actualizar"
                data-button="btn-envio" 
                class="form__btn btn-primario">

        <a href="/marca" class="form__btn btn-secundario">Cancelar</a>
    </div>
</form>

<?php
    if(isset($script) && $script != ''){
        $script .= '<script src="/build/js/panel/marca.js" type="module"></script>';
    }else{
        $script = '<script src="/build/js/panel/marca.js" type="module"></script>';
    }
?>