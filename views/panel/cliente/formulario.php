<div class="form__campo t-md">
        <label for="Cli_TipoCliente" class="form__label--campo">Tipo Cliente</label>
        <select id="Cli_TipoCliente" 
                name="Cli_TipoCliente"
                data-cy="Cli_TipoCliente"
                class="form__input input--bl"
                >
            <option value="" selected disabled>-- Selecione una opción --</option>
            <option value="N" <?php echo $cliente->Cli_TipoCliente=="N" ? 'selected' : '' ?>>Persona Natural</option>
            <option value="C" <?php echo $cliente->Cli_TipoCliente=="C" ? 'selected' : '' ?>>Corporativo</option>
        </select>
        <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
        <p class="form__labelSugerencia ocultar"></p>
        <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-cliente', 'tipoCliente');?></p>
    </div>

    <div class="form__campo t-sm">
        <label for="Cli_Ced_Nit" class="form__label--campo">Cédula / Nit</label>
        <input type="text" 
                    id="Cli_Ced_Nit"
                    name="Cli_Ced_Nit"
                    data-cy="Cli_Ced_Nit"
                    value="<?php echo isset($cliente->Cli_Ced_Nit) ? s($cliente->Cli_Ced_Nit) : ''; ?>"
                    autocomplete="off"
                    class="form__input input--bl">
        <a href="#" class="form__limpiar">x</a>
        <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
        <p class="form__labelSugerencia ocultar"></p>
        <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-cliente', 'cedulaNit');?></p>
    </div>

    <div class="form__campo t-xl">
        <label for="Cli_RazonSocial" class="form__label--campo">Nombre / Razón Social</label>
        <input type="text" 
                id="Cli_RazonSocial"
                name="Cli_RazonSocial" 
                data-cy="Cli_RazonSocial" 
                value="<?php echo isset($cliente->Cli_RazonSocial) ? s($cliente->Cli_RazonSocial) : ''; ?>"
                autocomplete="off"
                class="form__input input--bl"
                >
        <a href="#" class="form__limpiar">x</a>
        <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
        <p class="form__labelSugerencia ocultar"></p>
        <p class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-cliente', 'razonsocial');?></p>
    </div>

    <div class="form__campo t-md">
        <label for="Cli_Tel" class="form__label--campo">Tel. / Celular</label>
        <input type="tel" 
                id="Cli_Tel"
                name="Cli_Tel" 
                data-cy="Cli_Tel"
                value="<?php echo isset($cliente->Cli_Tel) ? s($cliente->Cli_Tel) : ''; ?>"
                autocomplete="off"
                class="form__input input--bl"
                >
        <a href="#" class="form__limpiar">x</a> 
        <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
        <p class="form__labelSugerencia ocultar"></p>
        <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-cliente', 'tel');?></p>
    </div>

    <div class="form__campo t-xl">
        <label for="Cli_Email" class="form__label--campo">Email</label>
        <input type="email" 
                id="Cli_Email"
                name="Cli_Email" 
                data-cy="Cli_Email"
                value="<?php echo isset($cliente->Cli_Email) ? s($cliente->Cli_Email) : ''; ?>"
                autocomplete="off"
                class="form__input input--bl"
                >
        <a href="#" class="form__limpiar">x</a>
        <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
        <p class="form__labelSugerencia ocultar"></p>
        <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-cliente', 'email');?></p>
    </div>

    <div class="form__campo t-xl">
        <label for="Cli_Direccion" class="form__label--campo">Direccion</label>
        <input type="text" 
                id="Cli_Direccion"
                name="Cli_Direccion" 
                data-cy="Cli_Direccion"
                value="<?php echo isset($cliente->Cli_Direccion) ? s($cliente->Cli_Direccion) : ''; ?>"
                autocomplete="off"
                class="form__input input--bl"
        >
        <a href="#" class="form__limpiar">x</a> 
        <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
        <p class="form__labelSugerencia ocultar"></p>
        <p class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-cliente', 'direccion');?></p>
    </div>

    <div class="form__campo t-xl">
        <label for="Ciud_CodDepart" class="form__label--campo">Departamento</label>
        <select id="Ciud_CodDepart"
                name="Ciud_CodDepart"
                data-select ="departamento" 
                data-cy="departamento"
                class="form__input input--bl"
                >
            <option value="" selected disabled>-- Seleccione una opción --</option>   
            
            <?php foreach($departamentos as $departamento){ ?>        
                <option value="<?php echo $departamento->Depart_Codigo; ?>"><?php echo $departamento->Depart_Nombre; ?></option>
            <?php } ?>

        </select>
        <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
        <p class="form__labelSugerencia ocultar"></p>
        <p class="form__labelError  ocultar"></p>
    </div>
    
    <div class="form__campo t-xl">
        <label for="Cli_FkCiud_Id" class="form__label--campo">Ciudad</label>
        <select id="Cli_FkCiud_Id" 
                name="Cli_FkCiud_Id"
                data-select ="ciudad"
                data-cy="ciudad"
                class="form__input input--bl">

                <option value="" selected>-- Seleccione una opción --</option>     
                <?php 
                    if(!empty($ciudades)){
                        foreach ($ciudades as $ciudad){
                ?>
                    <option value="<?php echo $ciudad->Ciud_Id; ?>"><?php echo $ciudad->Ciud_Nombre; ?></option>
                <?php 
                        }
                    }
                ?>              

        </select>
        <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
        <p class="form__labelSugerencia ocultar"></p>
        <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-cliente', 'ciudad');?></p>
    </div>