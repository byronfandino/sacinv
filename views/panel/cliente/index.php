<div class="title">
    <img class="title__img" src="/build/img/sistema/panelclientes-ng.svg" width="30px" height="30px"/>
    <h1 class="title__h1">Clientes</h1>
</div>

<!-- Verificación de errores generales -->
<?php if(isset($alertas['error-cliente']['general'])){ ?>
        <p class="alerta error ocultar"><?php echo $alertas['error-cliente']['general']; ?></p>
<?php }else if(isset($alertas['exito-cliente']['general'])){ ?>
        <p class="alerta exito ocultar"><?php echo $alertas['exito-cliente']['general']; ?></p>
<?php } ?>

<form method="POST" action="/cliente" data-form="cliente" data-cy="form-cliente" class="form">
    
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
    <table class="table" data-tipo="cliente">
        <thead class="thead">
            <tr>
                <th>Tipo Cliente</th>
                <th>Razón Social</th>
                <th>Cédula / Nit</th>
                <th>Teléfono</th>
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
        $script .= '<script src="/build/js/panel/cliente.js" type="module"></script>';
    }else{
        $script = '<script src="/build/js/panel/cliente.js" type="module"></script>';
    }
?>
