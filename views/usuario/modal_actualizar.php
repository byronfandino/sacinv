<header class="header__simple">
    <img src="/build/img/sistema/editar_usuario.svg" alt="Icono de cuentas por cobrar" width="33px" height="30px">
    <h1>Usuario</h1>
</header>

<main class="main">

    <form class="form" action="" method="post" id="form_usuario_actualizar">
        <fieldset>

            <legend>Datos del usuario</legend>

            <input type="hidden" name="id_us">
            
            <div class="contenedor__campos" id="contenedor__campos">

                <!-- botón toggle -->
                <a class="toggleButton" id="toggleButton">&#9660;</a>

                <div class="form__campo t-xl">
                    <label for="nombre_us_modal">Nombre Completo</label>
                    <input type="text" name="nombre_us" id="nombre_us_modal" class="campo__input" require autofocus>
                    <p class="label__error ocultar"></p>
                </div>   

                <div class="form__campo t-sm">
                    <label for="cedula_us_modal">Cédula</label>
                    <input type="number" name="cedula_us" id="cedula_us_modal" class="campo__input" require>
                    <p class="label__error ocultar"></p>
                </div>

                <div class="form__campo t-sm">
                    <label for="nickname_us_modal">Nickname</label>
                    <input type="text" name="nickname_us" id="nickname_us_modal" class="campo__input" require>
                    <p class="label__error ocultar"></p>
                </div>

                <div class="form__campo t-xl">
                    <label for="password_us_modal">Contraseña</label>
                    <input type="password" name="password_us" id="password_us_modal" class="campo__input" require>
                    <p class="label__error ocultar"></p>
                </div>

                <div class="form__campo t-xl">
                    <label for="password_conf_modal">Confirme Contraseña</label>
                    <input type="password" id="password_conf_modal" class="campo__input" require>
                    <p class="label__error ocultar"></p>
                </div>

                <div class="form__campo t-sm">
                    <label for="celular_us_modal">Celular</label>
                    <input type="tel" name="celular_us" id="celular_us_modal" class="campo__input" require>
                    <p class="label__error ocultar"></p>
                </div>

                <div class="form__campo t-md">
                    <label for="direccion_us_modal">Dirección</label>
                    <input type="text" name="direccion_us" id="direccion_us_modal" class="campo__input" require>
                    <p class="label__error ocultar"></p>
                </div>

                <div class="form__campo t-md">
                    <label for="email_us_modal">Email</label>
                    <input type="text" name="email_us" id="email_us_modal" class="campo__input" require>
                    <p class="label__error ocultar"></p>
                </div>
    
                <div class="form__campo t-md ">
                    <label for="nombre_depart_modal">Departamento</label>
                    <select id="nombre_depart_modal" class="campo__input" require>
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
                    <label for="fk_ciudad_us_modal">Ciudad</label>
                    <select name="fk_ciudad_us" id="fk_ciudad_us_modal" class="campo__input" require>
                        <option value="" selected disabled>-- Seleccione una opción --</option>
                    </select>
                    <p class="label__error ocultar"></p>
                </div>

                <div class="form__campo t-md">
                    <label for="tipo_us_modal">Tipo de Usuario</label>
                    <select name="tipo_us" id="tipo_us_modal" class="campo__input" require>
                        <option value="E" selected >Estandar</option>
                        <option value="A">Administrador</option>
                    </select>
                    <p class="label__error ocultar"></p>
                </div>

            </div>

            <div class="contenedor__botones">
                <input type="submit" class="boton boton--primario" value="Actualizar" id="botonActualizar">
            </div>

        </fieldset>
      
    </form>

</main>