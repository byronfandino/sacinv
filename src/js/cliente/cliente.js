import { Ciudad } from "../global/class/ciudad.js";
import { Cliente } from "../global/class/cliente.js";
import { limpiarFormulario } from "../global/parametros.js";

document.addEventListener('DOMContentLoaded', ()=>{

    // Cargar ciudades en el comboBox cada vez que se cambie de departamento
    const ciudad = new Ciudad();
    ciudad.cargarCiudades('nombre_depart', 'fk_ciudad');

    //Definir el objeto Cliente para enviarlo por parámetro al constructor
    const objetoCliente = {

        idformularioAgregar : 'form_cliente',

        urlAgregar : '/cliente/guardar',
        urlEliminar : '/cliente/eliminar',
        urlApiListar: '/cliente/api',
        idVentanaModal: 'modal-cliente',

        // Son los campos que deben ir en la tabla al momento de consultar el servidor
        estructuraTabla : [
            {cedula_nit: 'Cédula / Nit', posicion: 1, class:[]},
            {nombre: 'Nombre del Cliente', posicion: 2, class:[]},
            {telefono: 'Celular', posicion:3, class: []},
            {direccion: 'Dirección', posicion:4, class: []},
            {nombre_ciudad: 'Ciudad', posicion:5, class: []},
            {nombre_depart: 'Departamento', posicion:6, class: []}
        ],

        // Se crea esta propiedad porque es necesario pasar los datos de un registro de la tabla al formulario modal para actualizar los datos
        camposModalCliente : [
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
            {nombre: '^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{4,100}$', message: 'Solo acepta números y/o letras', estado: false},
            {telefono: '^[0-9]{10}$', message: 'Se permite 10 números', estado: false},
            {direccion: '^[a-zA-Z0-9#\-áéíóúÁÉÍÓÚñÑ ]{5,100}$', message: 'Se permiten letras, números, espacios y símbolos como: # -', estado: false},
            {nombre_depart: '^[0-9]{2}$', message: 'Debe seleccionar un departamento', estado: false},
            {fk_ciudad: '^[0-9]{1,5}$', message: 'Debe seleccionar una ciudad después de seleccionar el departamento', estado: false}
        ]
    }

    const cliente = new Cliente(objetoCliente);
    cliente.listarRegistros();
    formularioAgregarRegistro(cliente);
    botonResetFormulario(cliente);
    cliente.asignarValidacionCampos();
    
});

function formularioAgregarRegistro(cliente){

    const formulario = document.querySelector('#form_cliente');

    formulario.addEventListener('submit', async (e) => {
        // Prevenir el comportamiento por defecto del formulario
        e.preventDefault();

        // Si pasa la validación de los campos envie la petición al post
        if(cliente.revisarCampos()){

            const rta = await cliente.agregarRegistro(formulario);
            
            // Si se agregó el registro correctamente debe refrescar la tabla
            if (rta){
                //Obtenemos un arreglo de nombres de los campos a partir del array de objetos validacionCampos[];
                const camposFormulario = cliente.validacionCampos.map(obj => Object.keys(obj)[0]);
                limpiarFormulario(camposFormulario, 'cedula_nit');
                cliente.listarRegistros();
            }
        }
    });
}

function botonResetFormulario(cliente){
    const botonReset = document.querySelector('#reset');
    botonReset.addEventListener('click', ()=>{
        cliente.listarRegistros();
    })
}