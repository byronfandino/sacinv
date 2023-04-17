<div class="title">
    <img class="title__img" src="/build/img/sistema/panelpago-ng.svg" width="30px" height="30px"/>
    <h1 class="title__h1">Métodos de pago</h1>
</div>

<!-- Se muestran las alertas a nivel general -->
<?php if(isset($alertas['error-metodoPago']['general'])){ ?>
        <p class="alerta error ocultar"><?php echo $alertas['error-metodoPago']['general']; ?></p>
<?php }else if(isset($alertas['exito-metodoPago']['general'])){ ?>
        <p class="alerta exito ocultar"><?php echo $alertas['exito-metodoPago']['general']; ?></p>
<?php } ?>

<form method="POST" data-form="metodo-pago"  action="/metodo-pago" class="form">

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
                <th>Método de pago</th>
                <th>Estado</th>
                <th class="thead__th--icon">Modificar</th>
                <th class="thead__th--icon">Historial</th>
                <th class="thead__th--icon">Eliminar</th>
            </tr>
        </thead>
        <tbody class="tbody">
            <!--
                Modelo de las celdas de Categoría 
                <tr>
                    <td class="tbody__td--nombre" data-id-item="1">Almohadillas</td>
                    <td class="tbody__td--estado center">Habilitado</td>
                    <td class="tbody__td--icon"><a href="#"><img src="/build/img/sistema/editar-ng.svg" alt="Imagen Editar"><span>Modificar</span></a></td>
                    <td class="tbody__td--icon"><a href="#"><img src="/build/img/sistema/historial.svg" alt="Imagen Historial"><span>Ver historial</span></a></td>
                </tr>
            -->
        </tbody>
    </table>
</div>

<?php
    if(isset($script) && $script != ''){
        $script .= '<script src="/build/js/panel/metodopago.js" type="module"></script>';
    }else{
        $script = '<script src="/build/js/panel/metodopago.js" type="module"></script>';
    }
?>

