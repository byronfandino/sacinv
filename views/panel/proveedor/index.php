<div class="title">
    <img class="title__img" src="/build/img/sistema/panelproveedores-ng.svg" width="30px" height="30px"/>
    <h1 class="title__h1">Proveedores</h1>
</div>

<!-- Verificación de errores generales -->
<?php if(isset($alertas['error-proveedor']['general'])){ ?>
        <p class="alerta error ocultar"><?php echo $alertas['error-proveedor']['general']; ?></p>
<?php }else if(isset($alertas['exito-proveedor']['general'])){ ?>
        <p class="alerta exito ocultar"><?php echo $alertas['exito-proveedor']['general']; ?></p>
<?php } ?>

<form method="POST" action="/proveedor" data-form="proveedor" data-cy="form-proveedor" class="form">
    
    <?php include_once 'formulario.php'; ?>
    
    <div class="form__campo campo--button t-xxl">
        <input type="submit" 
                data-btn="btn-enviar"
                value="Agregar" 
                class="form__btn btn-primario disabled"
                disabled 
        >
        <a href="#" class="form__btn btn-reset">Reset</a>
    </div>
</form>

<h2 class="panel__h2">Listado de registros</h2>
<!-- <p class="panel__p"><span>Total registros: </span></p> -->
<div class="contenedor-table">
    <!-- La propiedad data-tipo solo la tienen las tablas complejas, ya que pueden contener más tablas de entidades sencillas-->
    <table class="table" data-tipo="proveedor">
        <thead class="thead">
            <tr>
                <th>RazonSocial</th>
                <th>Nit</th>
                <th>Tel</th>
                <th>Email</th>
                <th>Dirección</th>
                <th>Ciudad</th>
                <th>Departamento</th>
                <th>Estado</th>
                <th class="thead__th--icon">Editar</th>
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
        $script .= '<script src="/build/js/panel/proveedor.js" type="module"></script>';
    }else{
        $script = '<script src="/build/js/panel/proveedor.js" type="module"></script>';
    }
?>
