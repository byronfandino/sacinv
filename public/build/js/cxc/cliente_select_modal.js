import { Cliente } from "../global/class/cliente.js";

document.addEventListener('DOMContentLoaded', () => {
    cargarCliente();
});

function cargarCliente(){
    const objetoCliente = {
    
        // Es utilizado únicamente para mostrar los mensajes de error en los campos del formulario que contienen un nombre adicional, y que estos errores provienen del backend y del frontend 
        modal : {
            isModal : true,
            idVentanaModal: 'modal_cliente_select',
            nombreCampoComplemento: '_agregar',
            idFormularioPrincipal : 'form_deuda'
        },
    
        url : {
            agregar : '/cliente/guardar',
            eliminar : '/cliente/eliminar',
            apiConsultar: '/cliente/api',
        },

        // El idVentanaModal hace referencia no solamente a la ventana que se abre desde la tabla de registros para modificar un registro en particular, sino que allí se colocan en los respectivos campos cada dato del registro
        idVentanaModal: 'modal_cliente_actualizar',
    
        // Son los campos que deben ir en la tabla al momento de consultar el servidor
        tabla : {
            estructura : [
                {cedula_nit: 'Cédula / Nit', posicion: 1, class:['tbody__td--enlace']},
                {nombre: 'Nombre del Cliente', posicion: 2, class:['tbody__td--enlace']},
                {telefono: 'Celular', posicion:3, class: []},
                {direccion: 'Dirección', posicion:4, class: []},
                {nombre_ciudad: 'Ciudad', posicion:5, class: []},
                {nombre_depart: 'Departamento', posicion:6, class: []}
            ],
            columnaModificar: true,
            columnaEliminar: true,
        },
    
        // Se crea esta propiedad porque es necesario pasar los datos de un registro de la tabla al formulario modal para actualizar los datos, por lo tanto es necesario saber cual es el equivalente del nombre del campo de la array de datos con el nombre del campo al cual se pasa los datos
        equivalenciaCamposModal : [
        //id del campo del fomulario principal : 'id del campo de la ventana modal'
            {id_cliente: 'id_cliente_actualizar'},
            {cedula_nit: 'cedula_nit_actualizar'},
            {nombre: 'nombre_actualizar'},
            {telefono: 'telefono_actualizar'},
            {direccion: 'direccion_actualizar'},
            {cod_depart: 'cod_depart_actualizar'},
            {fk_ciudad: 'fk_ciudad_actualizar'}
        ],
    
        // Se utiliza para trasladar el registro de una tabla de una ventana modal al formulario principal
        equivalenciaTablaAForm :[
            // {id campo formulario principal : propiedad del objeto obtenido del JSON}
            {fk_cliente_deudor : 'id_cliente'},
            {cedula_nit_deudor : 'cedula_nit'},
            {nombre_deudor : 'nombre'},
            {telefono_deudor : 'telefono'},
            {direccion_deudor : 'direccion'},
            {nombre_ciudad_deudor : 'nombre_ciudad'},
            {nombre_depart_deudor : 'nombre_depart'}
        ],
    
        //Se verifica que los campos diligenciados cumplan con estos registros
        validacionCampos : [
            {cedula_nit_agregar: '^(?!.*--)[0-9]{4,15}$|^(?!.*--)[0-9-]{4,15}$', message: 'Caracteres aceptados: números (0-9) y un solo guión', estado: false},
            {nombre_agregar: '^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{2,100}$', message: 'Solo acepta números y/o letras', estado: false},
            {telefono_agregar: '^[0-9]{10}$', message: 'Se permite 10 números', estado: false},
            {direccion_agregar: '^[a-zA-Z0-9#.\-áéíóúÁÉÍÓÚñÑ -]{5,100}$', message: 'Se permiten letras, números, espacios y símbolos como: # -', estado: false},
            {nombre_depart_agregar: '^[0-9]{2}$', message: 'Debe seleccionar un departamento', estado: false},
            {fk_ciudad_agregar: '^[0-9]{1,5}$', message: 'Debe seleccionar una ciudad después de seleccionar el departamento', estado: false}
        ]
    }
    
    const cliente = new Cliente(objetoCliente);
    cliente.asignarValidacionCampos();
    cliente.listarRegistros();
}
//# sourceMappingURL=cliente_select_modal.js.map
