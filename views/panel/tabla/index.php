<div class="title">
    <img class="title__img" src="/build/img/sistema/paneltabla-ng.svg" width="30px" height="30px"/>
    <h1 class="title__h1">Tablas</h1>
</div>

<!-- Se muestran las alertas a nivel general -->
<?php if(isset($alertas['error-tabla']['general'])){ ?>
        <p class="alerta error ocultar"><?php echo $alertas['error-tabla']['general']; ?></p>
<?php }else if(isset($alertas['exito-tabla']['general'])){ ?>
        <p class="alerta exito ocultar"><?php echo $alertas['exito-tabla']['general']; ?></p>
<?php } ?>

<form method="POST" action="/tabla" class="form">

    <?php include_once __DIR__ . '/formulario.php'; ?>

    <div class="form__campo campo--button t-xxl">
        <input type="submit" 
                value="Guardar" 
                data-button="btn-envio"
                class="form__btn btn-primario disabled" 
                disabled>
    </div>
</form>

<h2 class="panel__h2">Listado de registros</h2>

<div class="contenedor-table">
    <table class="table">
        <thead class="thead">
            <tr>
                <th>Nombre de la Tabla</th>
                <th class="thead__th--icon">Modificar</th>
                <th class="thead__th--icon">Estado</th>
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
        $script .= '<script src="/build/js/panel/tabla.js" type="module"></script>';
    }else{
        $script = '<script src="/build/js/panel/tabla.js" type="module"></script>';
    }
?>

