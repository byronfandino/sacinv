<header class="header__menu">
    <div class="titulo">
        <img src="/build/img/cxc.svg" alt="Icono de cuentas por cobrar" width="30px" height="30px">
        <h1>Deudores</h1>
    </div>
    <a id="icono__menu" class="icono__menu" href="#"><img src="/build/img/sistema/menu.svg" width="30px" height="32px" alt="Icono Menú"/></a>
    <div id="menu" class="menu">
        <?php
            include_once __DIR__ . '/../menu.php';
        ?>
    </div>
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
    <div class="fondo-notificacion ocultar " id="modal_cliente_actualizar">
        <div class="contenedor-notificacion auto">
            <?php 
                include_once __DIR__ . '/../deudores/modal_cliente_actualizar.php';
            ?>
        </div>

        <a href="#" class="cerrar__modal">X</a>
    </div>

    <!-- Ventana Modal -->
    <div class="fondo-notificacion ocultar " id="modal_deuda_actualizar">
        <div class="contenedor-notificacion auto">
            <?php 
                include_once __DIR__ . '/../deudores/modal_deuda_actualizar.php';
            ?>
        </div>

        <a href="#" class="cerrar__modal">X</a>
    </div>
    
    
    <form class="form" method="post" id="form_deuda">

        <fieldset>

            <legend>Datos del cliente</legend>

            <div class="contenedor__campos">

                <a href="#" class="boton boton--primario" id="buscar_cliente">Buscar</a>
                <input type="hidden" id="fk_cliente_deudor" name="fk_cliente">
                <p class="label__error ocultar"></p> 

                <div class="form__campo t-sm">
                    <label for="cedula_nit_deudor">Cédula / Nit</label>
                    <input type="text" id="cedula_nit_deudor" class="campo__input disabled" readonly>
                </div>
                <div class="form__campo t-xl">
                    <label for="nombre_deudor">Nombre del Cliente</label>
                    <input type="text" id="nombre_deudor" class="campo__input disabled" readonly>
                </div>
                <div class="form__campo t-sm">
                    <label for="telefono_deudor">Teléfono</label>
                    <input type="number" id="telefono_deudor" class="campo__input disabled" readonly>
                </div>
                <div class="form__campo t-md">
                    <label for="direccion_deudor">Dirección</label>
                    <input type="text" id="direccion_deudor" class="campo__input disabled" readonly>
                </div>
                <div class="form__campo t-md">
                    <label for="email_deudor">Email</label>
                    <input type="text" id="email_deudor" class="campo__input disabled" readonly>
                </div>
                <div class="form__campo t-md">
                    <label for="nombre_ciudad_deudor">Ciudad</label>
                    <input type="text" id="nombre_ciudad_deudor" class="campo__input disabled" readonly>
                </div>
                <div class="form__campo t-md">
                    <label for="nombre_depart_deudor">Departamento</label>
                    <input type="text" id="nombre_depart_deudor" class="campo__input disabled" readonly>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Movimiento</legend>
            
            <div class="contenedor__campos">

                <div class="form__campo t-md">
                    <label for="tipo_mov">Tipo de movimiento</label>
                    <select name="tipo_mov" id="tipo_mov" class="campo__input">
                        <option value="" selected disabled>-Seleccione una opción-</option>
                        <option value="D">Debe</option>
                        <option value="A">Abona</option>
                        <option value="R">Devolución</option>
                    </select>
                    <p class="label__error ocultar"></p>
                </div>
                
                <div class="form__campo t-xs">
                    <label for="cant">Cantidad</label>
                    <input type="number" name="cant" id="cant" value = "1" class="campo__input">
                    <p class="label__error ocultar"></p>
                </div>

                <div class="form__campo t-sm">
                    <label for="valor_unit">Valor Unitario</label>
                    <input type="number" name="valor_unit" id="valor_unit" class="campo__input">
                    <p class="label__error ocultar"></p>
                </div>

                <div class="form__campo t-sm">
                    <label for="valor_total">Valor Total</label>
                    <input type="number" name="valor_total" id="valor_total" class="campo__input disabled" readonly>
                    <p class="label__error ocultar"></p>
                </div>

                <div class="form__campo t-xl">
                    <label for="descripcion">Observaciones</label>
                    <input type="text" name="descripcion" id="descripcion" class="campo__input">
                    <p class="label__error ocultar"></p>
                </div>
            </div>

            <div class="contenedor__botones">
                <input type="submit" value="Agregar" class="boton boton--secundario">
                <button type="reset" class="boton boton--secundario" id="reset_deuda">Reset</button>
            </div>
        </fieldset>                
    </form>

    <div class="contenedor_grid_titulos">
        <h2 class="" id="registrosDeuda">Registros encontrados</h2>
        <h3 class="saldo" id="saldo">Saldo $0</h3>
    </div>

    <div class="contenedor-tabla">
        <table class="tabla" id="tabla_deuda">
            <thead class="thead">
                <tr>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Tipo Movimiento</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>V/Unit</th>
                    <th>V/Total</th>
                    <th>Saldo</th>
                    <th>Editar</th>
                    <th>Eliminar</th>
                </tr>
            </thead>
            <tbody class="tbody">
            </tbody>
        </table>
        <div class="contenedor__botones">
            <a class="boton boton--primario" id="reporte_deuda_cliente" href="#">Descargar</a>
        </div>
    </div>
</main>
<?php
    if(isset($script) && $script != ''){
        $script .= '<script src="/build/js/cxc/cxc.js" type="module"></script>';        
        $script .= '<script src="/build/js/global/menu.js" type="module"></script>';        
    }else{
        $script = '<script src="/build/js/cxc/cxc.js" type="module"></script>';
        $script .= '<script src="/build/js/global/menu.js" type="module"></script>';        
    }
?>


