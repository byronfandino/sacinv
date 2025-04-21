<header class="header__menu">
    <div class="titulo">
        <img src="/build/img/sistema/user.svg" alt="Icono de cuentas por cobrar" width="27px" height="27px">
        <h1>Usuario</h1>
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
    <div class="fondo-notificacion ocultar" id="modal_usuario_actualizar">
        <div class="contenedor-notificacion auto">
            <?php 
                include_once __DIR__ . '/../usuario/modal_actualizar.php';
            ?>
        </div>
        <a href="#" class="cerrar__modal">X</a>
    </div>

    <form class="form" method="post" action="" id="form_usuario">
        <fieldset>

            <legend>Datos del Usuario</legend>

            
            <input type="hidden" name="id_us">
            
            <div class="contenedor__campos" id="contenedor__campos">

                <!-- botón toggle -->
                <a class="toggleButton" id="toggleButton">&#9660;</a>

                <div class="form__campo t-xl">
                    <label for="nombre_us">Nombre Completo</label>
                    <input type="text" name="nombre_us" id="nombre_us" class="campo__input" require autofocus>
                    <p class="label__error ocultar"></p>
                </div>   

                <div class="form__campo t-sm">
                    <label for="cedula_us">Cédula</label>
                    <input type="number" name="cedula_us" id="cedula_us" class="campo__input" require>
                    <p class="label__error ocultar"></p>
                </div>

                <div class="form__campo t-sm">
                    <label for="nickname_us">Nickname</label>
                    <input type="text" name="nickname_us" id="nickname_us" class="campo__input" require>
                    <p class="label__error ocultar"></p>
                </div>

                <div class="form__campo t-xl">
                    <label for="password_us">Contraseña</label>
                    <input type="password" name="password_us" id="password_us" class="campo__input" require>
                    <p class="label__error ocultar"></p>
                </div>

                <div class="form__campo t-xl">
                    <label for="password_conf">Confirme Contraseña</label>
                    <input type="password" id="password_conf" class="campo__input" require>
                    <p class="label__error ocultar"></p>
                </div>

                <div class="form__campo t-sm">
                    <label for="celular_us">Celular</label>
                    <input type="tel" name="celular_us" id="celular_us" class="campo__input" require>
                    <p class="label__error ocultar"></p>
                </div>

                <div class="form__campo t-md">
                    <label for="direccion_us">Dirección</label>
                    <input type="text" name="direccion_us" id="direccion_us" class="campo__input" require>
                    <p class="label__error ocultar"></p>
                </div>

                <div class="form__campo t-md">
                    <label for="email_us">Email</label>
                    <input type="text" name="email_us" id="email_us" class="campo__input" require>
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
                    <label for="fk_ciudad_us">Ciudad</label>
                    <select name="fk_ciudad_us" id="fk_ciudad_us" class="campo__input" require>
                        <option value="" selected disabled>-- Seleccione una opción --</option>
                    </select>
                    <p class="label__error ocultar"></p>
                </div>

                <div class="form__campo t-md">
                    <label for="tipo_us">Tipo de Usuario</label>
                    <select name="tipo_us" id="tipo_us" class="campo__input" require>
                        <option value="E" selected >Estandar</option>
                        <option value="A">Administrador</option>
                    </select>
                    <p class="label__error ocultar"></p>
                </div>

            </div>

            <div class="contenedor__botones">
                <input type="submit" class="boton boton--primario" value="Agregar">     
                <button type="reset" class="boton boton--secundario" id="reset_usuario">Reset</button>
            </div>
        </fieldset>
      
    </form>
    <h2 id="registros_usuario">Registros encontrados</h2>

    <div class="contenedor-tabla">
        <table class="tabla" id="tabla_usuario">
            <thead class="thead">
                <tr>
                    <th>Cédula</th>
                    <th>Nombre</th>
                    <th>Nickname</th>
                    <th>Teléfono</th>
                    <th>Dirección</th>
                    <th>Email</th>
                    <th>Departamento</th>
                    <th>Ciudad</th>
                    <th>Tipo</th>
                    <th class="thead__th--icon">Modificar</th>
                    <th class="thead__th--icon">Eliminar</th>
                </tr>
            </thead>
            <tbody class="tbody" data-id="tbody_main">
                
            </tbody>
        </table>
    </div>        

</main>
<?php
    if(isset($script) && $script != ''){
        $script .= '<script src="/build/js/usuario/usuario.js" type="module"></script>';
        $script .= '<script src="/build/js/global/menu.js" type="module"></script>';        
    }else{
        $script = '<script src="/build/js/usuario/usuario.js" type="module"></script>';
        $script .= '<script src="/build/js/global/menu.js" type="module"></script>';        
    }
?>


