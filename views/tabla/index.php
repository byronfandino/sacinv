<header class="header__menu">
    <div class="titulo">
        <img src="/build/img/sistema/tabla.svg" alt="Icono de tabla" width="27px" height="27px">
        <h1>Tabla</h1>
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
    <div class="fondo-notificacion ocultar" id="modal_tabla_actualizar">
        <div class="contenedor-notificacion auto">
            <?php 
                include_once __DIR__ . '/../tabla/modal_actualizar.php';
            ?>
        </div>
        <a href="#" class="cerrar__modal">X</a>
    </div>

    <form class="form" method="post" action="" id="form_tabla">
        <fieldset>

            <legend></legend>

            
            <input type="hidden" name="id_tb" id="id_tb">
            
            <div class="contenedor__campos">

                <div class="form__campo t-xl">
                    <label for="nombre_tb">Nombre de la tabla</label>
                    <input type="text" name="nombre_tb" id="nombre_tb" class="campo__input" require autofocus>
                    <p class="label__error ocultar"></p>
                </div>

            </div>
            <div class="contenedor__botones">
                <input type="submit" class="boton boton--primario" value="Agregar">     
                <button type="reset" class="boton boton--secundario" id="reset_tabla">Reset</button>
            </div>
        </fieldset>
      
    </form>
    <h2 id="registrosTabla">Registros encontrados</h2>

    <div class="contenedor-tabla">
        <table class="tabla" id="tabla">
            <thead class="thead">
                <tr>
                    <th>Nombre de Tabla</th>
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
        $script .= '<script src="/build/js/tabla/tabla.js" type="module"></script>';
        $script .= '<script src="/build/js/global/menu.js" type="module"></script>';        
    }else{
        $script = '<script src="/build/js/tabla/tabla.js" type="module"></script>';
        $script .= '<script src="/build/js/global/menu.js" type="module"></script>';        
    }
?>


