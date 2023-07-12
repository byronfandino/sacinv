<div class="form__campo t-sm">
    <label for="Prov_Nit" class="form__label--campo">Nit</label>
    <input type="text" 
                id="Prov_Nit"
                name="Prov_Nit"
                data-cy="Prov_Nit"
                value="<?php echo isset($proveedor->Prov_Nit) ? s($proveedor->Prov_Nit) : ''; ?>"
                autocomplete="off"
                class="form__input input--bl">
    <a href="#" class="form__limpiar">x</a>
    <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
    <p class="form__labelSugerencia ocultar"></p>
    <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-proveedor', 'nit');?></p>
</div>

<div class="form__campo t-xl">
    <label for="Prov_RazonSocial" class="form__label--campo">Razón Social</label>
    <input type="text" 
            id="Prov_RazonSocial"
            name="Prov_RazonSocial" 
            data-cy="Prov_RazonSocial" 
            value="<?php echo isset($proveedor->Prov_RazonSocial) ? s($proveedor->Prov_RazonSocial) : ''; ?>"
            autocomplete="off"
            class="form__input input--bl"
            >
    <a href="#" class="form__limpiar">x</a>
    <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
    <p class="form__labelSugerencia ocultar"></p>
    <p class="form__labelError  ocultar"><?php echo tipoAlerta($alertas, 'error-proveedor', 'razonsocial');?></p>
</div>

<div class="form__campo t-md">
    <label for="Prov_Tel" class="form__label--campo">Tel. / Celular</label>
    <input type="tel" 
            id="Prov_Tel"
            name="Prov_Tel" 
            data-cy="Prov_Tel"
            value="<?php echo isset($proveedor->Prov_Tel) ? s($proveedor->Prov_Tel) : ''; ?>"
            autocomplete="off"
            class="form__input input--bl"
            >
    <a href="#" class="form__limpiar">x</a> 
    <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
    <p class="form__labelSugerencia ocultar"></p>
    <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-proveedor', 'tel');?></p>
</div>

<div class="form__campo t-xl">
    <label for="Prov_Email" class="form__label--campo">Email</label>
    <input type="email" 
            id="Prov_Email"
            name="Prov_Email" 
            data-cy="Prov_Email"
            value="<?php echo isset($proveedor->Prov_Email) ? s($proveedor->Prov_Email) : ''; ?>"
            autocomplete="off"
            class="form__input input--bl"
            >
    <a href="#" class="form__limpiar">x</a>
    <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
    <p class="form__labelSugerencia ocultar"></p>
    <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-proveedor', 'email');?></p>
</div>

<div class="form__campo t-xl">
    <label for="Prov_Direccion" class="form__label--campo">Direccion</label>
    <input type="text" 
            id="Prov_Direccion"
            name="Prov_Direccion" 
            data-cy="Prov_Direccion"
            value="<?php echo isset($proveedor->Prov_Direccion) ? s($proveedor->Prov_Direccion) : ''; ?>"
            autocomplete="off"
            class="form__input input--bl"
    >
    <a href="#" class="form__limpiar">x</a> 
    <img src="/build/img/sistema/error.svg" alt="icono de error" class="form__iconError ocultar">
    <p class="form__labelSugerencia ocultar"></p>
    <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-proveedor', 'direccion');?></p>
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
    <label for="Prov_FkCiud_Id" class="form__label--campo">Ciudad</label>
    <select id="Prov_FkCiud_Id" 
            name="Prov_FkCiud_Id"
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
    <p class="form__labelError ocultar"><?php echo tipoAlerta($alertas, 'error-proveedor', 'ciudad');?></p>
</div>