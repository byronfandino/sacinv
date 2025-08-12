import { Deuda } from "../global/class/deuda.js";
import { botonResetFormulario, cierreManualModal, mostrarModal, expandirContenedor } from "../global/parametros.js";

let deudorGLobal = '';

document.addEventListener('DOMContentLoaded', async () => { 
    campoHidden();
    botones();
    guardarDeudor();
    actualizarDeudor();
    cajasTexto();
    
    //Contenedor para el formulario Deudores
    expandirContenedor('toggleButton', 'contenedor__campos', 60, 110);

    //Contenedor para el formulario Modal Seleccionar Cliente
    expandirContenedor('toggle__button--cliente', 'contenedor__campos--cliente', 60, 40);
    
});

function cajasTexto(){

    //Cajas de texto del formulario principal
    const inputCant = document.querySelector('#cant');
    const inputValorUnit = document.querySelector('#valor_unit');
    const inputValorTotal = document.querySelector('#valor_total');
    const selectMov = document.querySelector('#tipo_mov');
    const inputDescrip = document.querySelector('#descripcion');
    const eventInput = new Event ('input');

    selectMov.addEventListener('change', e => {
        if (e.target.value == "A"){
            inputDescrip.value = "Abono"
            inputDescrip.dispatchEvent(eventInput);
        }else if (e.target.value == "R"){
            inputDescrip.value = "Devuelve"
            inputDescrip.dispatchEvent(eventInput);
        }

        inputValorUnit.focus();
    });

    inputCant.addEventListener('input', e => {
        inputValorTotal.value = multiplicacion(e.target.value, inputValorUnit.value);

    })

    inputValorUnit.addEventListener('input', e => {
        inputValorTotal.value = multiplicacion(e.target.value, inputCant.value);
    })

    // -----------------------------------------------------------------------
    //Cajas de texto del formulario de actualización de datos
    const inputCant_actualizar = document.querySelector('#cant_actualizar');
    const inputValorUnit_actualizar = document.querySelector('#valor_unit_actualizar');
    const inputValorTotal_actualizar = document.querySelector('#valor_total_actualizar');
    const selectMov_actualizar = document.querySelector('#tipo_mov_actualizar');
    const inputDescrip_actualizar = document.querySelector('#descripcion_actualizar');

    selectMov_actualizar.addEventListener('change', e => {
        if (e.target.value == "A"){
            inputDescrip_actualizar.value = "Abono"
        }else if (e.target.value == "R"){
            inputDescrip_actualizar.value = "Devuelve"
        }
        inputValorUnit_actualizar.focus();
    });

    inputCant_actualizar.addEventListener('input', e => {
        inputValorTotal_actualizar.value = multiplicacion(e.target.value, inputValorUnit_actualizar.value);
    })

    inputValorUnit_actualizar.addEventListener('input', e => {
        inputValorTotal_actualizar.value = multiplicacion(e.target.value, inputCant_actualizar.value);
    })
}

function multiplicacion (valor1, valor2){
    let resultado = parseInt(valor1) * parseInt(valor2);
        if (resultado >= 0 ){
            return resultado;
        }else{
           return 0;
        }
}

function campoHidden(){
    const inputHidden = document.querySelector('#fk_cliente_deudor');
    inputHidden.addEventListener('change', e => {
        deudorGLobal.listarRegistros(e.target.value);
    });
}

function botones(){
    // Muestra la ventana modal de la tabla de registros 
    //Botón buscar
    const btnBuscarCliente = document.querySelector('#buscar_cliente');
    btnBuscarCliente.addEventListener('click', e => {
        e.preventDefault();
        mostrarModal('modal_cliente_select');
    });
    
    // Hay varias ventanas modal, y por lo tanto es necesario programar cada botón
    //Botón cerrar ventana Modal
    const btnCerraModal = document.querySelectorAll('.cerrar__modal');
    btnCerraModal.forEach( boton => {
        boton.addEventListener('click', e => {
            e.preventDefault();
            cierreManualModal(e);
        });
    });
}

function guardarDeudor(){
    
    const objetoDeudor = {

        idFormularioAgregar : 'form_deudores',
        
        modal:{
            isModal:false
        },

        url : {
            agregar : '/deuda/guardar',
            eliminar: '/deuda/eliminar',
            apiConsultar: '/deuda_mov/cliente/api'
        },

        idVentanaModal: 'modal_deuda_actualizar',

        //Id del texto donde se muestra la totalidad de los registros
        idTotalRegistros : 'registrosDeuda',

        tabla : {
            idTabla: 'tabla_deuda',
            estructura : [
                // nombre_campo_bd: Titulo campo modo Mobile, orden, arreglo de clases css, reemplazo de valores
                {fecha: 'Fecha', posicion: 1},
                {hora: 'Hora', posicion: 2},
                {tipo_mov: 'Tipo Movimiento', posicion:3, reemplazar : [
                                                                        {'D' : 'Debe', class: ''},
                                                                        {'A' : 'Abonó', class: 'td--amarillo'},
                                                                        {'R' : 'Devolución', class: 'td--rojo'}
                                                                    ]},
                {descripcion: 'Descripcion', posicion:4},
                {cant: 'Cantidad', posicion:5, class: ['tbody__td--center'], formato : "numero"},
                {valor_unit: 'V/Unit', posicion:6, class: ['tbody__td--right'], formato : "numero"},
                {valor_total: 'V/Total', posicion:7, class: ['tbody__td--right'], formato : "numero"},
                {saldo: 'Saldo', posicion:8, class: ['tbody__td--right'], formato : "numero"}
            ],
            columnaModificar: true,
            columnaEliminar: true
        },

        equivalenciaCamposModal : [
            //id del campo del fomulario principal : 'id del campo de la ventana modal'
            {id_mov: 'id_mov_actualizar'},
            {tipo_mov: 'tipo_mov_actualizar'},
            {cant: 'cant_actualizar'},
            {valor_unit: 'valor_unit_actualizar'},
            {valor_total: 'valor_total_actualizar'},
            {descripcion: 'descripcion_actualizar'}
        ],

        validacionCampos : [
            //id del campo : 'expresión regular', mensaje de error: 'XXXXX', estado(Cumple con la expresion regular : true | false)
            {descripcion: '^[a-zA-Z0-9#.\-áéíóúüÁÉÍÓÚñÑ -]{2,500}$', message: 'Este campo es obligatorio y puede digitar caracteres mayúsculas, minusculas, números y caracteres como (- . #)', estado: false},
            // {tipo_mov: '^[ADR]{1}$', message: 'Debe seleccionar un tipo de movimiento', estado: false},
            {cant: '^[0-9]{1,3}$', message: 'Debe digitar un valor numérico', estado: true},
            {valor_unit: '^[0-9]{2,10}$', message: 'Debe digitar un valor numérico', estado: false}
        ],

        //Filtra los resultados en la tabla de acuerdo a los valores que digite el usuario en los campos
        filtroBusqueda: false
    }

    const deudor = new Deuda(objetoDeudor);
    deudor.asignarValidacionCampos();
    deudor.formularioAgregar('form_deuda');
    botonResetFormulario('reset_deuda', deudor, 'form_deuda');
    deudorGLobal = deudor;
}

function actualizarDeudor(){

    const objeto = {

        url : {
            actualizar : '/deuda/actualizar',
        },

        // Es utilizado únicamente para mostrar los mensajes de error en los campos del formulario que contienen un nombre adicional, y que estos errores provienen del backend y del frontend 
        modal:{
            isModal:true,
            idVentanaModal : 'modal_deuda_actualizar',
            nombreCampoComplemento: '_actualizar'
        },

        validacionCampos : [
            //id del campo : 'expresión regular', mensaje de error: 'XXXXX', estado(Cumple con la expresion regular : true | false)
            {descripcion_actualizar: '^[a-zA-Z0-9#.\-áéíóúÁÉÍÓÚñÑ /-]{3,500}$', message: 'Este campo es obligatorio y puede digitar caracteres mayúsculas, minusculas, números y caracteres como (- . #)', estado: true},
            // {tipo_mov_actualizar: '^[ADR]{1}$', message: 'Debe seleccionar un tipo de movimiento', estado: true},
            {cant_actualizar: '^[0-9]{1,3}$', message: 'Debe digitar un valor numérico', estado: true},
            {valor_unit_actualizar: '^[0-9]{2,10}$', message: 'Debe digitar un valor numérico', estado: true}
        ],

        //Filtra los resultados en la tabla de acuerdo a los valores que digite el usuario en los campos
        filtroBusqueda: false
    }

    // Se envia el id del formulario para el envio de registro
    const deuda = new Deuda(objeto);
    deuda.asignarValidacionCampos();
    //                                id_formulario,   id_cliente_input_hidden, objeto global
    deuda.formularioActualizar('form_deuda_actualizar', 'fk_cliente_deudor', deudorGLobal);
}
