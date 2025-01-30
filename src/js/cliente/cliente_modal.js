import { Ciudad } from '../global/class/ciudad.js';
import { Cliente } from '../global/class/cliente.js';
import { cierreManualModal } from '../global/parametros.js';

document.addEventListener('DOMContentLoaded', () =>{

    cargarComboBoxCiudades();
    cargarCliente();
    botonesCerrarModal();

});

function cargarComboBoxCiudades(){
    // Cargar ciudades en el comboBox cada vez que se cambie de departamento
    const ciudad = new Ciudad();
    //                     (id del Departamento, id de la ciudad) 
    ciudad.cargarCiudades('cod_depart_modal', 'fk_ciudad_modal');
}

function cargarCliente(){
    const objetoCliente = {

        url : {
            actualizar : '/cliente/actualizar',
        },

        // Es utilizado únicamente para mostrar los mensajes de error en los campos del formulario que contienen un nombre adicional, y que estos errores provienen del backend y del frontend 
        modal:{
            isModal:true,
            idVentanaModal : 'modal_cliente_actualizar',
            nombreCampoComplemento: '_modal'
        },

        validacionCampos : [
            {cedula_nit_modal: '^(?!.*--)[0-9]{4,15}$|^(?!.*--)[0-9-]{4,15}$', message: 'Caracteres aceptados: números (0-9) y un solo guión', estado: true},
            {nombre_modal: '^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{2,100}$', message: 'Solo acepta números y/o letras', estado: true},
            {telefono_modal: '^[0-9]{10}$', message: 'Se permite 10 números', estado: true},
            {direccion_modal: '^[a-zA-Z0-9#.\-áéíóúÁÉÍÓÚñÑ -]{5,100}$', message: 'Se permiten letras, números, espacios y símbolos como: # -', estado: true},
            {cod_depart_modal: '^[0-9]{2}$', message: 'Debe seleccionar un departamento', estado: true},
            {fk_ciudad_modal: '^[0-9]{1,5}$', message: 'Debe seleccionar una ciudad después de seleccionar el departamento', estado: true}
        ]
    }

    // Se envia el id del formulario para el envio de registro
    const cliente = new Cliente(objetoCliente);
    cliente.asignarValidacionCampos();
    cliente.formularioActualizar('form_cliente_actualizar');
}

function botonesCerrarModal(){
    // Hay varias ventanas modal, y por lo tanto es necesario programar cada botón
    const btnCerraModal = document.querySelector('.cerrar__modal');
    btnCerraModal.addEventListener('click', e => cierreManualModal(e));
}