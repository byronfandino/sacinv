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

<form method="POST" action="/marca" class="form">

    <?php include_once __DIR__ . '/formulario.php'; ?>

    <div class="form__campo campo--button t-xxl">
        <input type="submit" 
                value="Guardar" 
                data-button="btn-envio"
                class="form__btn btn-primario " 
                >
    </div>
</form>

<h2 class="panel__h2">Listado de registros</h2>

<div class="contenedor-table">
    <table class="table">
        <thead class="thead">
            <tr>
                <th>Nombre de la marca</th>
                <th>Estado</th>
                <th class="thead__th--icon">Modificar</th>
                <th class="thead__th--icon">Historial</th>
                <th class="thead__th--icon">Eliminar</th>
            </tr>
        </thead>
        <tbody class="tbody">
            <!-- Aquí se cargan los registros -->
        </tbody>
    </table>
</div>

<?php
    if(isset($script) && $script != ''){
        $script .= '<script src="/build/js/panel/marca.js" type="module"></script>';
    }else{
        $script = '<script src="/build/js/panel/marca.js" type="module"></script>';
    }
?>

