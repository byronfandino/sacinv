<div class="title">
    <img class="title__img" src="/build/img/sistema/panelcategorias-ng.svg" width="30px" height="30px"/>
    <h1 class="title__h1">Editar Categoria</h1>
</div>

<!-- Se muestran las alertas a nivel general -->
<?php if(isset($alertas['error-categoria']['general'])){ ?>
        <p class="alerta error ocultar"><?php echo $alertas['error-categoria']['general']; ?></p>
<?php }else if(isset($alertas['exito-categoria']['general'])){ ?>
        <p class="alerta exito ocultar"><?php echo $alertas['exito-categoria']['general']; ?></p>
<?php } ?>

<form method="POST" data-form="categoria" action="" class="form">

    <?php include_once __DIR__ . '/formulario.php'; ?>

    <div class="form__campo campo--button t-xxl">
        <input type="submit" 
                value="Actualizar" 
                data-button="btn-envio"
                class="form__btn btn-primario">

        <a href="/categoria" class="form__btn btn-secundario">Cancelar</a>
    </div>
</form>

<?php
    if(isset($script) && $script != ''){
        $script .= '<script src="/build/js/panel/categoria.js" type="module"></script>';
    }else{
        $script = '<script src="/build/js/panel/categoria.js" type="module"></script>';
    }
?>