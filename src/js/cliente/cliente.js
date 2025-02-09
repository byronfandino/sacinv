import { Ciudad } from "../global/class/ciudad.js";
import { Cliente } from "../global/class/cliente.js";
import { botonResetFormulario } from "../global/parametros.js";

document.addEventListener('DOMContentLoaded', ()=>{

    cargarComboBoxCiudades();
    cargarCliente();
    
});

// Cargar ciudades en el comboBox cada vez que se cambie de departamento
function cargarComboBoxCiudades(){
    const ciudad = new Ciudad();
    ciudad.cargarCiudades('nombre_depart', 'fk_ciudad');
}

async function cargarCliente(){
    //Definir el objeto Cliente para enviarlo por parámetro al constructor
    const objetoCliente = {

        modal:{
            isModal:false
        },

        url : {
            agregar : '/cliente/guardar',
            eliminar : '/cliente/eliminar',
            apiConsultar: '/cliente/api',
        },
        
        idVentanaModal: 'modal_cliente_actualizar',

        //Id del texto donde se muestra la totalidad de los registros
        idTotalRegistros : 'registrosCliente',
        
        // Son los campos que deben ir en la tabla al momento de consultar el servidor
        tabla : {
            idTabla: 'tabla_cliente',
            estructura : [
                {cedula_nit: 'Cédula / Nit', posicion: 1, class:[]},
                {nombre: 'Nombre del Cliente', posicion: 2, class:[]},
                {telefono: 'Celular', posicion:3, class: []},
                {direccion: 'Dirección', posicion:4, class: []},
                {nombre_ciudad: 'Ciudad', posicion:5, class: []},
                {nombre_depart: 'Departamento', posicion:6, class: []}
            ],
    
            columnaModificar: true,
            columnaEliminar: true
        },

        // Se crea esta propiedad porque es necesario pasar los datos de un registro de la tabla al formulario modal para actualizar los datos, por lo tanto es necesario saber cual es el equivalente del nombre del campo de la array de datos con el nombre del campo al cual se pasa los datos
        equivalenciaCamposModal : [
        //id del campo del fomulario principal : 'id del campo de la ventana modal'
            {id_cliente: 'id_cliente_modal'},
            {cedula_nit: 'cedula_nit_modal'},
            {nombre: 'nombre_modal'},
            {telefono: 'telefono_modal'},
            {direccion: 'direccion_modal'},
            {cod_depart: 'cod_depart_modal'},
            {fk_ciudad: 'fk_ciudad_modal'}
        ],

        //Se verifica que los campos diligenciados cumplan con estos registros
        validacionCampos : [
            {cedula_nit: '^(?!.*--)[0-9]{4,15}$|^(?!.*--)[0-9-]{4,15}$', message: 'Caracteres aceptados: números (0-9) y un solo guión', estado: false},
            {nombre: '^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{2,100}$', message: 'Solo acepta números y/o letras', estado: false},
            {telefono: '^[0-9]{10}$', message: 'Se permite 10 números', estado: false},
            {direccion: '^[a-zA-Z0-9#.\-áéíóúÁÉÍÓÚñÑ -]{5,100}$', message: 'Se permiten letras, números, espacios y símbolos como: # -', estado: false},
            {nombre_depart: '^[0-9]{2}$', message: 'Debe seleccionar un departamento', estado: false},
            {fk_ciudad: '^[0-9]{1,5}$', message: 'Debe seleccionar una ciudad después de seleccionar el departamento', estado: false}
        ],

        //Filtra los resultados en la tabla de acuerdo a los valores que digite el usuario en los campos
        filtroBusqueda: true
    }

    const cliente = new Cliente(objetoCliente);
    cliente.asignarValidacionCampos();
    cliente.formularioAgregar('form_cliente'); //id del formulario
    botonResetFormulario('reset_cliente', cliente); //id_boton_reset, objeto para listar los registros
    cliente.listarRegistros();

}

