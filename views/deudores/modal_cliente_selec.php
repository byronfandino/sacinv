<header class="header">
    <img src="/build/img/sistema/user.svg" alt="Icono de cuentas por cobrar" width="27px" height="27px">
    <h1>Cliente</h1>
</header>

<main class="main">

    <form class="form" action="" id="form_cliente">
        <fieldset>

            <legend>Filtra o Agrega un Cliente</legend>

            <div class="contenedor__campos">
                <div class="form__campo t-sm">
                    <label for="cedula_nit_agregar">Cédula / Nit</label>
                    <input type="text" name="cedula_nit" id="cedula_nit_agregar" class="campo__input" require autofocus>
                    <p class="label__error ocultar"></p>
                </div>
    
                <div class="form__campo t-md">
                    <label for="nombre_agregar">Nombre del Cliente</label>
                    <input type="text" name="nombre" id="nombre_agregar" class="campo__input" require>
                    <p class="label__error ocultar"></p>
                </div>
                
                <div class="form__campo t-md">
                    <label for="telefono_agregar">Teléfono</label>
                    <input type="text" name="telefono" id="telefono_agregar" class="campo__input" require>
                    <p class="label__error ocultar"></p>
                </div>

                <div class="form__campo t-md">
                    <label for="direccion_agregar">Direccion</label>
                    <input type="text" name="direccion" id="direccion_agregar" class="campo__input" require>
                    <p class="label__error ocultar"></p>
                </div>              
    
                <div class="form__campo t-md ">
                    <label for="nombre_depart_agregar">Departamento</label>
                    <select id="nombre_depart_agregar" class="campo__input" require>
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
                    <label for="fk_ciudad_agregar">Ciudad</label>
                    <select id="fk_ciudad_agregar" name="fk_ciudad" class="campo__input" require>
                        <option value="" selected disabled>-- Seleccione una opción --</option>
                    </select>
                    <p class="label__error ocultar"></p>
                </div>
            </div>
            <div class="contenedor__botones">
                <input type="submit" class="boton boton--primario" value="Agregar">     
                <button type="reset" class="boton boton--secundario" id="reset_cliente_select">Reset</button>
            </div>
        </fieldset>
      
    </form>

    <h2 id="registrosCliente">Registros encontrados</h2>

    <div class="contenedor-tabla">
        <table class="tabla" id="tabla_select_cliente">
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
        $script .= '<script src="/build/js/cxc/modal_cliente.js" type="module"></script>';
    }else{
        $script = '<script src="/build/js/cxc/modal_cliente.js" type="module"></script>';
    }
?>


