<header class="header__simple">
    <img src="/build/img/sistema/tabla.svg" alt="Icono de cuentas por cobrar" width="33px" height="30px">
    <h1>Editar o Eliminar Tabla</h1>
</header>

<main class="main">

    <form class="form" action="" method="post" id="form_tabla_actualizar">
        <fieldset>

            <legend></legend>

            <input type="hidden" id="id_tb_modal" name="id_tb">

            <div class="contenedor__campos">
                <div class="form__campo t-xl">
                    <label for="nombre_tb_modal">Nombre de la tabla</label>
                    <input type="text" name="nombre_tb" id="nombre_tb_modal" class="campo__input">
                    <p class="label__error ocultar"></p>
                </div>              
            </div>

            <div class="contenedor__botones">
                <input type="submit" class="boton boton--primario" value="Actualizar" id="botonActualizar">
            </div>

        </fieldset>
      
    </form>

</main>