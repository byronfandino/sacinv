<div class="title">
    <img class="title__img" src="/build/img/sistema/panelproductos-ng.svg" width="30px" height="30px"/>
    <h1 class="title__h1">Productos</h1>
</div>

<!-- Verificación de errores generales -->
<?php if(isset($alertas['error-producto']['general'])){ ?>
        <p class="alerta error ocultar"><?php echo $alertas['error-producto']['general']; ?></p>
<?php }else if(isset($alertas['exito-producto']['general'])){ ?>
        <p class="alerta exito ocultar"><?php echo $alertas['exito-producto']['general']; ?></p>
<?php } ?>

<!-- Ventana Modal -->
<div class="fondo-notificacion marca ocultar">
    <div class="contenedor-notificacion">
        <?php 
            include_once __DIR__ . '/../marca/modal.php';
        ?>
    </div>
</div>

<!-- Ventana Modal -->
<div class="fondo-notificacion categoria ocultar">
    <div class="contenedor-notificacion">
        <?php 
            include_once __DIR__ . '/../categoria/modal.php';
        ?>
    </div>
</div>

<form method="POST" action="/producto" data-form="producto" class="form" enctype="multipart/form-data" data-cy="form-producto">

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
    <table class="table">
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
        // $script .= '<script src="/build/js/panel/marcaModal.js"></script>';
        $script .= '<script src="/build/js/panel/producto.js" type="module"></script>';
    }else{
        // $script = '<script src="/build/js/panel/marcaModal.js"></script>';
        $script = '<script src="/build/js/panel/producto.js" type="module"></script>';
    }
?>
