<header class="header">
    <img src="/build/img/cxc.svg" alt="Icono de cuentas por cobrar" width="30px" height="30px">
    <h1>Cliente</h1>
</header>

<main class="main">

    <!-- Ventana Modal -->
    <div class="fondo-notificacion ocultar" id="modal-cliente">
        <div class="contenedor-notificacion">
            <?php 
                include_once __DIR__ . '/../cliente/modal_actualizar.php';
            ?>
        </div>
    </div>

    <form class="form" action="" id="form_cliente">
        <fieldset>

            <legend>Datos del cliente</legend>

            <input type="hidden" name="id_cliente">

            <div class="contenedor__campos">
                <div class="form__campo t-sm">
                    <label for="cedula_nit">Cédula / Nit</label>
                    <input type="text" name="cedula_nit" id="cedula_nit" class="campo__input" require autofocus>
                    <p class="label__error ocultar"></p>
                </div>
    
                <div class="form__campo t-md">
                    <label for="nombre">Nombre del Cliente</label>
                    <input type="text" name="nombre" id="nombre" class="campo__input" require>
                    <p class="label__error ocultar"></p>
                </div>              
    
                <div class="form__campo t-sm">
                    <label for="telefono">Teléfono</label>
                    <input type="number" name="telefono" id="telefono" class="campo__input" require>
                    <p class="label__error ocultar"></p>
                </div>
    
                <div class="form__campo t-md">
                    <label for="direccion">Dirección</label>
                    <input type="text" name="direccion" id="direccion" class="campo__input" require>
                    <p class="label__error ocultar"></p>
                </div>
    
                <div class="form__campo t-md ">
                    <label for="nombre_depart">Departamento</label>
                    <select id="nombre_depart" class="campo__input" require>
                        <option value="" selected disabled>-- Seleccione una opción --</option>
                        <?php
                            foreach($departamentos as $departamento){
                                ?>
                                    <option value="<?php echo $departamento->cod_depart; ?>"><?php echo $departamento->nombre_depart; ?></option>
                                <?php
                            }
                        ?>
                    </select>
                    <p class="label__error ocultar"></p>
                </div>
                
                <div class="form__campo t-md">
                    <label for="fk_ciudad">Ciudad</label>
                    <select name="fk_ciudad" id="fk_ciudad" class="campo__input" require>
                        <option value="" selected disabled>-- Seleccione una opción --</option>
                    </select>
                    <p class="label__error ocultar"></p>
                </div>
            </div>
            <div class="contenedor__botones">
                <input type="submit" class="boton boton--primario" value="Agregar">     
                <button type="reset" class="boton boton--secundario" id="reset">Reset</button>
            </div>
        </fieldset>
      
    </form>

    <h2 class="panel__h2">Listado de registros</h2>

    <div class="contenedor-tabla">
        <table class="tabla">
            <thead class="thead">
                <tr>
                    <th>Cédula</th>
                    <th>Nombre del Cliente</th>
                    <th>Celular</th>
                    <th>Dirección</th>
                    <th>Ciudad</th>
                    <th>Departamento</th>
                    <th class="thead__th--icon">Modificar</th>
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
        $script .= '<script src="/build/js/cliente/cliente.js" type="module"></script>';
    }else{
        $script = '<script src="/build/js/cliente/cliente.js" type="module"></script>';
    }
?>


