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

<form method="POST" action="/categoria" class="form">

    <?php include_once __DIR__ . '/formulario.php'; ?>

    <div class="form__campo campo--button t-xxl">
        <input type="submit"
                data-button="btn-envio" 
                value="Guardar" 
                class="form__btn btn-primario " 
                >
    </div>
</form>

<h2 class="panel__h2">Listado de registros</h2>

<div class="contenedor-table">
    <table class="table">
        <thead class="thead">
            <tr>
                <th>Nombre de la categoría</th>
                <th>Habilitado</th>
                <th class="thead__th--icon">Modificar</th>
                <th class="thead__th--icon">Historial</th>
                <th class="thead__th--icon">Eliminar</th>
            </tr>
        </thead>
        <tbody class="tbody">
            
        </tbody>
    </table>
</div>

<?php
    if(isset($script) && $script != ''){
        $script .= '<script src="/build/js/panel/categoria.js" type="module"></script>';
    }else{
        $script = '<script src="/build/js/panel/categoria.js" type="module"></script>';
    }
?>

