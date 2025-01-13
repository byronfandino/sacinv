<header class="header">
    <img src="/build/img/cxc.svg" alt="Icono de cuentas por cobrar" width="30px" height="30px">
    <h1>Cliente</h1>
</header>

<main class="main">

    <form class="form" action="" id="form_cliente_modal">
        <fieldset>

            <legend>Datos del cliente</legend>

            <input type="hidden" id="id_cliente_modal" name="id_cliente_modal">
            <div class="contenedor__campos">

                <div class="form__campo t-sm">
                    <label for="cedula_nit_modal">Cédula / Nit</label>
                    <input type="text" name="cedula_nit_modal" id="cedula_nit_modal" class="campo__input">
                    <p class="label__error ocultar"></p>
                </div>
                
                <div class="form__campo t-md">
                    <label for="nombre_modal">Nombre del Cliente</label>
                    <input type="text" name="nombre_modal" id="nombre_modal" class="campo__input">
                    <p class="label__error ocultar"></p>
                </div>              
    
                <div class="form__campo t-sm">
                    <label for="telefono_modal">Teléfono</label>
                    <input type="number" name="telefono_modal" id="telefono_modal" class="campo__input">
                    <p class="label__error ocultar"></p>
                </div>
    
                <div class="form__campo t-md">
                    <label for="direccion_modal">Dirección</label>
                    <input type="text" name="direccion_modal" id="direccion_modal" class="campo__input">
                    <p class="label__error ocultar"></p>
                </div>
    
                <div class="form__campo t-md ">
                    <label for="cod_depart_modal">Departamento</label>
                    <select id="cod_depart_modal" class="campo__input">
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
                    <label for="fk_ciudad_modal">Ciudad</label>
                    <select name="fk_ciudad_modal" id="fk_ciudad_modal" class="campo__input">
                        <option value="" selected disabled>-- Seleccione una opción --</option>
                    </select>
                    <p class="label__error ocultar"></p>
                </div>
            </div>
            <div>
                <input type="submit" class="boton boton--primario" value="Actualizar">
                <button class="boton boton--secundario" id="cerrar-modal">Cancelar</button>     
            </div>
        </fieldset>
      
    </form>

</main>
<?php
    if(isset($script) && $script != ''){
        $script .= '<script src="/build/js/cliente/cliente_modal.js" type="module"></script>';
    }else{
        $script = '<script src="/build/js/cliente/cliente_modal.js" type="module"></script>';
    }
?>


