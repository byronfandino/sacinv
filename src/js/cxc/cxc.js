import { Deuda } from "../global/class/deuda.js";
import { botonResetFormulario, cierreManualModal, mostrarModal } from "../global/parametros.js";

document.addEventListener('DOMContentLoaded', () => { 
    
    cargarBotones();
    cargarDeudor();

});

function cargarBotones(){
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

function cargarDeudor(){
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

        idVentanaModal: 'modal-deudor',

        //Id del texto donde se muestra la totalidad de los registros
        idTotalRegistros : 'registrosDeuda',


        tabla : {
            idTabla: 'tabla_deuda',
            estructura : [
                // nombre_campo_bd: Titulo campo modo Mobile, orden, arreglo de clases css
                {fecha: 'Fecha', posicion: 1, class:[]},
                {hora: 'Hora', posicion: 2, class:[]},
                {tipo_mov: 'Tipo Movimiento', posicion:3, class: []},
                {descripcion: 'Descripcion', posicion:4, class: []},
                {valor: 'Valor', posicion:5, class: []},
                {saldo: 'Saldo', posicion:6, class: []}
            ],
    
            columnaModificar: true,
            columnaEliminar: false
        },

        validacionCampos : [
            //id del campo : 'expresión regular', mensaje de error: 'XXXXX', estado(Cumple con la expresion regular : true | false)
            {descripcion: '^[a-zA-Z0-9#.\-áéíóúÁÉÍÓÚñÑ /-]{3,500}$', message: 'Este campo es obligatorio y puede digitar caracteres mayúsculas, minusculas, números y caracteres como (- . #)', estado: false},
            {tipo_mov: '^[ADR]{1}$', message: 'Debe seleccionar un tipo de movimiento', estado: false},
            {valor: '^[0-9]{2,10}$', message: 'Debe digitar un valor numérico', estado: false}
        ],

        //Filtra los resultados en la tabla de acuerdo a los valores que digite el usuario en los campos
        filtroBusqueda: false
    }

    const deudor = new Deuda(objetoDeudor);
    deudor.asignarValidacionCampos();
    deudor.formularioAgregar('form_deuda');
    botonResetFormulario('reset_deuda', deudor);
}

