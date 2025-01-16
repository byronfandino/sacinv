<header class="header">
    <img src="/build/img/cxc.svg" alt="Icono de cuentas por cobrar" width="30px" height="30px">
    <h1>Cuentas por cobrar</h1>
</header>

<main class="main">
    <form class="form" action="">
        <fieldset>

            <legend>Datos del cliente</legend>

            <div class="contenedor__campos">
                <input type="hidden" name="id_cliente">
                <div class="form__campo t-sm">
                    <label for="cedula_nit">Cédula / Nit</label>
                    <input type="text" id="cedula_nit" class="campo__input">
                </div>
                <div class="form__campo t-md">
                    <label for="nombre_cliente">Nombre del Cliente</label>
                    <input type="text" id="nombre_cliente" class="campo__input">
                </div>
                <button class="boton boton--primario" >Buscar</button>                    
            </div>

            <div class="contenedor__campos">
                <div class="form__campo t-sm">
                    <label for="telefono">Teléfono</label>
                    <input type="number" id="telefono" class="campo__input disabled">
                </div>
                <div class="form__campo t-md">
                    <label for="direccion">Dirección</label>
                    <input type="text" id="direccion" class="campo__input disabled">
                </div>
                <div class="form__campo t-md">
                    <label for="nombre_ciudad">Ciudad</label>
                    <input type="text" id="nombre_ciudad" class="campo__input disabled">
                </div>
                <div class="form__campo t-md">
                    <label for="nombre_depart">Departamento</label>
                    <input type="text" id="nombre_depart" class="campo__input disabled">
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


