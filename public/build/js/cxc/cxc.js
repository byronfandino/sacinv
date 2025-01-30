import { Deuda } from "../global/class/deuda.js";
import { cierreManualModal, mostrarErrorCampo, mostrarModal } from "../global/parametros.js";

document.addEventListener('DOMContentLoaded', () => { 
    
    cargarFechaHoraActual();
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

function cargarFechaHoraActual(){
     // Obtener los elementos de fecha y hora
     const dateField = document.getElementById('fecha');
     const timeField = document.getElementById('hora');
 
     // Crear un objeto de fecha con la fecha actual
     const now = new Date();
 
     // Formatear la fecha en el formato YYYY-MM-DD requerido por el input date
     const formattedDate = now.toISOString().split('T')[0];
     dateField.value = formattedDate;
 
     // Formatear la hora en el formato HH:MM requerido por el input time
     const hours = now.getHours().toString().padStart(2, '0');
     const minutes = now.getMinutes().toString().padStart(2, '0');
     timeField.value = `${hours}:${minutes}`;
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
            apiConsultar: '/deuda/api',
        },

        idVentanaModal: 'modal-deudor',

        tabla : {
            estructura : [
                {fecha: 'Fecha', posicion: 1, class:[]},
                {hora: 'Hora', posicion: 2, class:[]},
                {tipo_mov: 'Tipo Movimiento', posicion:3, class: []},
                {descripcion: 'Descripcion', posicion:4, class: []},
                {valor: 'Valor', posicion:5, class: []}
            ],
    
            columnaModificar: false,
            columnaEliminar: true
        },

        validacionCampos : [
            //id del campo : 'expresión regular', mensaje de error: 'XXXXX', estado(Cumple con la expresion regular : true | false)
            // {fk_cliente_deudor: '^[0-9]{1,10}$', message: 'No se ha seleccionado ningún cliente', estado: false},
            {fecha: '^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$', message: 'Debe seleccionar una fecha', estado: true},
            {hora: '^(0[0-9]|1[0-9]|2[0-3]):([0-5][0-9])$', message: 'Debe seleccionar una hora', estado: true},
            {descripcion: '^[a-zA-Z0-9#.\-áéíóúÁÉÍÓÚñÑ -]{3,500}$', message: 'Está digitando caracteres inválidos', estado: false},
            {tipo_mov: '^[AD]{1}$', message: 'Debe seleccionar un tipo de movimiento', estado: false},
            {valor: '^[0-9]{2,10}$', message: 'Debe digitar un valor numérico', estado: false}
        ]
    }

    const deudor = new Deuda(objetoDeudor);
    deudor.asignarValidacionCampos();
    deudor.formularioAgregar('form_deuda');
}


//# sourceMappingURL=cxc.js.map
