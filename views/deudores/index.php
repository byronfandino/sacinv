<header class="header">
    <img src="/build/img/cxc.svg" alt="Icono de cuentas por cobrar" width="30px" height="30px">
    <h1>Cuentas por cobrar</h1>
</header>

<main class="main">

    <!-- Ventana Modal -->
    <div class="fondo-notificacion ocultar" id="modal_cliente_select">
        <div class="contenedor-notificacion ">
            <?php 
                include_once __DIR__ . '/../deudores/modal_cliente_selec.php';
            ?>
        </div>

        <a href="#" class="cerrar__modal">X</a>
    </div>
    
    <!-- Ventana Modal -->
    <div class="fondo-notificacion ocultar" id="modal_cliente_actualizar">
        <div class="contenedor-notificacion ">
            <?php 
                include_once __DIR__ . '/../deudores/modal_cliente_actualizar.php';
            ?>
        </div>

        <a href="#" class="cerrar__modal">X</a>
    </div>
    
    <form class="form" action="" id="form_deudores">
        <fieldset>

            <legend>Datos del cliente</legend>

            <a href="#" class="boton boton--primario" id="buscar_cliente">Buscar</a>

            <div class="contenedor__campos">

                <input type="hidden" id="id_cliente_deudor" name="id_cliente">

                <div class="form__campo t-sm">
                    <label for="cedula_nit_deudor">Cédula / Nit</label>
                    <input type="text" id="cedula_nit_deudor" class="campo__input disabled">
                </div>
                <div class="form__campo t-xl">
                    <label for="nombre_deudor">Nombre del Cliente</label>
                    <input type="text" id="nombre_deudor" class="campo__input disabled">
                </div>
                <div class="form__campo t-sm">
                    <label for="telefono_deudor">Teléfono</label>
                    <input type="number" id="telefono_deudor" class="campo__input disabled">
                </div>
                <div class="form__campo t-md">
                    <label for="direccion_deudor">Dirección</label>
                    <input type="text" id="direccion_deudor" class="campo__input disabled">
                </div>
                <div class="form__campo t-md">
                    <label for="nombre_ciudad_deudor">Ciudad</label>
                    <input type="text" id="nombre_ciudad_deudor" class="campo__input disabled">
                </div>
                <div class="form__campo t-md">
                    <label for="nombre_depart_deudor">Departamento</label>
                    <input type="text" id="nombre_depart_deudor" class="campo__input disabled">
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Movimiento</legend>
            <div class="contenedor__campos">
                <div class="form__campo t-xxl">
                    <label for="descripcion">Descripción del producto</label>
                    <input type="text" name="descripcion" id="descripcion" class="campo__input">
                </div>
                <div class="form__campo t-md">
                    <label for="tipo_mov">Tipo de movimiento</label>
                    <select name="tipo_mov" id="tipo_mov" class="campo__input">
                        <option value="" selected disabled>-Seleccione una opción-</option>
                        <option value="c">Por cobrar</option>
                        <option value="a">Abonó</option>
                    </select>
                </div>
                <div class="form__campo t-sm">
                    <label for="valor_deuda">Valor del producto</label>
                    <input type="number" name="valor_deuda" id="valor_deuda" class="campo__input">
                </div>
            </div>
            <input type="submit" value="Agregar" class="boton boton--secundario" disabled>
        </fieldset>                
    </form>

    <h2 class="panel__h2">Listado de registros</h2>

    <div class="contenedor-tabla">
        <table class="tabla">
            <thead class="thead">
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Tipo Movimiento</th>
                    <th>Descrición</th>
                    <th>Valor</th>
                    <th class="thead__th--icon">Eliminar</th>
                </tr>
            </thead>
            <tbody class="tbody">
                
            </tbody>
        </table>
    </div>        

</main>
<?php
    if(isset($script) && $script != ''){
        $script .= '<script src="/build/js/cxc/cxc.js" type="module"></script>';        
    }else{
        $script = '<script src="/build/js/cxc/cxc.js" type="module"></script>';
    }
?>


