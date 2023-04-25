<div class="title">
    <img class="title__img" src="/build/img/sistema/panelcategorias-ng.svg" width="30px" height="30px"/>
    <h1 class="title__h1">Categorías</h1>
</div>

<!-- Se muestran las alertas a nivel general -->
<?php if(isset($alertas['error-categoria']['general'])){ ?>
        <p class="alerta error ocultar"><?php echo $alertas['error-categoria']['general']; ?></p>
<?php }else if(isset($alertas['exito-categoria']['general'])){ ?>
        <p class="alerta exito ocultar"><?php echo $alertas['exito-categoria']['general']; ?></p>
<?php } ?>

<form method="POST" data-form="categoria" class="form">

    <?php include_once __DIR__ . '/formulario.php'; ?>

    <div class="form__campo campo--button t-xxl">
        <input type="submit"
                data-button="btn-envio" 
                value="Guardar" 
                class="form__btn btn-primario " 
                >
        <button class="form__btn btn-secundario "
                data-button="btn-cancelar-modal">Cancelar</button>
    </div>
</form>
