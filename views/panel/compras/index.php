<div class="title">
    <img class="title__img" src="/build/img/sistema/panelcompras-ng.svg" width="30px" height="30px"/>
    <h1 class="title__h1">Compras</h1>
</div>

<!-- Verificación de errores generales -->
<?php if(isset($alertas['error-compraMaster']['general'])){ ?>
        <p class="alerta error ocultar"><?php echo $alertas['error-compraMaster']['general']; ?></p>
<?php }else if(isset($alertas['exito-compraMaster']['general'])){ ?>
        <p class="alerta exito ocultar"><?php echo $alertas['exito-compraMaster']['general']; ?></p>
<?php } ?>

<!-- Ventana Modal -->
<div class="fondo-notificacion metodo-pago ocultar">
    <div class="contenedor-notificacion">
        <?php 
            include_once __DIR__ . '/../metodopago/modal.php';
        ?>
    </div>
</div>


<form method="POST" action="/compra" data-form="compra" class="form" enctype="multipart/form-data" data-cy="form-compra">

    <?php include_once 'formulario.php'; ?>

    <div class="form__campo campo--button t-xxl">
        <input type="submit" 
                data-btn="btn-enviar"
                value="Agregar" 
                class="form__btn btn-primario " 
        >
    </div>
</form>

<h2 class="panel__h2">Listado de registros</h2>

<div class="contenedor-table">
    <!-- La propiedad data-tipo solo la tienen las tablas complejas, ya que pueden contener más tablas de entidades sencillas-->
    <table class="table" data-tipo="compra">
        <thead class="thead">
            <tr>
                <th>DESCRIPCION</th>
                <th>COD. MANUAL</th>
                <th>CATEGORIA</th>
                <th>MARCA</th>
                <th>CANT MIN STOCK</th>
                <th>VALOR VENTA</th>
                <th>VALOR DESCUENTO</th>
                <th>CANT X OFERTA</th>
                <th>VALOR X OFERTA</th>
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
        $script .= '<script src="/build/js/panel/compra.js" type="module"></script>';
    }else{
        $script = '<script src="/build/js/panel/compra.js" type="module"></script>';
    }
?>
