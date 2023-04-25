<div class="title">
    <img class="title__img" src="/build/img/sistema/panelmarcas-ng.svg" width="30px" height="30px"/>
    <h1 class="title__h1">Marcas</h1>
</div>

<!-- Se muestran las alertas a nivel general -->
<?php if(isset($alertas['error-marca']['general'])){ ?>
        <p class="alerta error ocultar"><?php echo $alertas['error-marca']['general']; ?></p>
<?php }else if(isset($alertas['exito-marca']['general'])){ ?>
        <p class="alerta exito ocultar"><?php echo $alertas['exito-marca']['general']; ?></p>
<?php } ?>

<form method="POST" data-form="marca" class="form">

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
