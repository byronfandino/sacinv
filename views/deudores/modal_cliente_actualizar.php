<header class="header__simple">
    <img src="/build/img/sistema/editar_usuario.svg" alt="Icono de cuentas por cobrar" width="33px" height="30px">
    <h1>Cliente</h1>
</header>

<main class="main">

    <form class="form" action="" id="form_cliente_actualizar">

        <fieldset>

            <legend>Datos del cliente</legend>

            <input type="hidden" id="id_cliente_actualizar" name="id_cliente">
            <div class="contenedor__campos">

                <div class="form__campo t-sm">
                    <label for="cedula_nit_actualizar">Cédula / Nit</label>
                    <input type="text" name="cedula_nit" id="cedula_nit_actualizar" class="campo__input">
                    <p class="label__error ocultar"></p>
                </div>
                
                <div class="form__campo t-md">
                    <label for="nombre_actualizar">Nombre del Cliente</label>
                    <input type="text" name="nombre" id="nombre_actualizar" class="campo__input">
                    <p class="label__error ocultar"></p>
                </div>
    
                <div class="form__campo t-sm">
                    <label for="telefono_actualizar">Teléfono</label>
                    <input type="number" name="telefono" id="telefono_actualizar" class="campo__input">
                    <p class="label__error ocultar"></p>
                </div>
    
                <div class="form__campo t-md">
                    <label for="direccion_actualizar">Dirección</label>
                    <input type="text" name="direccion" id="direccion_actualizar" class="campo__input">
                    <p class="label__error ocultar"></p>
                </div>
    
                <div class="form__campo t-md ">
                    <label for="cod_depart_actualizar">Departamento</label>
                    <select id="cod_depart_actualizar" class="campo__input">
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
                    <label for="fk_ciudad_actualizar">Ciudad</label>
                    <select name="fk_ciudad" id="fk_ciudad_actualizar" class="campo__input">
                        <option value="" selected disabled>-- Seleccione una opción --</option>
                    </select>
                    <p class="label__error ocultar"></p>
                </div>
            </div>
            <div>
                <input type="submit" class="boton boton--primario" value="Actualizar">
            </div>
        </fieldset>
      
    </form>

</main>

