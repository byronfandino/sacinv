import { Ciudad } from "../global/class/ciudad.js";
import { Usuario } from "../global/class/usuario.js";
import { botonResetFormulario, cierreManualModal, expandirContenedor } from "../global/parametros.js";

let usuarioGlobal = '';

document.addEventListener('DOMContentLoaded', async ()=>{

    comboBoxCiudades();
    usuarioGlobal = await guardarUsuario();
    actualizarUsuario();
    botonCerrarModal();
    //Botón toogle cliente
    expandirContenedor('toggleButton', 'contenedor__campos', 10, 20);

});

function botonCerrarModal(){
    // Botón Cerrar Modal
    // Hay varias ventanas modal, y por lo tanto es necesario programar cada botón
    const btnCerraModal = document.querySelector('.cerrar__modal');
    btnCerraModal.addEventListener('click', e => cierreManualModal(e));
}

// Cargar ciudades en el comboBox cada vez que se cambie de departamento
function comboBoxCiudades(){

    const ciudadGuardar = new Ciudad();
    ciudadGuardar.cargarCiudades('nombre_depart', 'fk_ciudad_us');

    const ciudadActualizar = new Ciudad();
    ciudadActualizar.cargarCiudades('nombre_depart_modal', 'fk_ciudad_us_modal');
}

async function guardarUsuario(){
    //Definir el objeto Cliente para enviarlo por parámetro al constructor
    const objetoUsuario = {

        modal:{
            isModal:false
        },

        url : {
            agregar : '/usuario/guardar',
            eliminar : '/usuario/eliminar',
            apiConsultar : '/usuario/api'
        },
        
        idVentanaModal: 'modal_usuario_actualizar',

        //Id del texto donde se muestra la totalidad de los registros
        idTotalRegistros : 'registros_usuario',

        //Propiedades locales de la clase usuario para cargar las ciudades en ventana Modal desde una tabla
        idComboDepartModal : 'nombre_depart_modal',
        idComboCiudadModal : 'fk_ciudad_us_modal',
        
        // Son los campos que deben ir en la tabla al momento de consultar el servidor
        tabla : {
            idTabla: 'tabla_usuario',
            estructura : [
                {cedula_us: 'Cédula / Nit', posicion: 1, class:[]},
                {nombre: 'Nombre del Cliente', posicion: 2, class:[]},
                {telefono: 'Celular', posicion:3, class: []},
                {direccion: 'Dirección', posicion:4, class: []},
                {email: 'Email', posicion:5, class: []},
                {nombre_ciudad: 'Ciudad', posicion:6, class: []},
                {nombre_depart: 'Departamento', posicion:7, class: []}
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
            {email: 'email_modal'},
            {cod_depart: 'cod_depart_modal'},
            {fk_ciudad: 'fk_ciudad_modal'}
        ],
        
        //Se verifica que los campos diligenciados cumplan con estos registros
        validacionCampos : [
            {cedula_nit: '^(?!.*--)[0-9]{4,15}$|^(?!.*--)[0-9-]{4,15}$', message: 'Caracteres aceptados: números (0-9) y un solo guión', estado: false},
            {nombre: '^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{2,100}$', message: 'Solo acepta números y/o letras', estado: false},
            {telefono: '^[0-9]{10}$', message: 'Se permite 10 números', estado: false},
            {direccion: '^[a-zA-Z0-9#.\-áéíóúÁÉÍÓÚñÑ -]{5,100}$', message: 'Se permiten letras, números, espacios y símbolos como: # -', estado: false},
            {email: '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$', message: 'No cumple con los requisitos de un correo electrónico', estado: false},
            {nombre_depart: '^[0-9]{2}$', message: 'Debe seleccionar un departamento', estado: false},
            {fk_ciudad: '^[0-9]{1,5}$', message: 'Debe seleccionar una ciudad después de seleccionar el departamento', estado: false}
        ],

        //Filtra los resultados en la tabla de acuerdo a los valores que digite el usuario en los campos
        filtroBusqueda: true
    }

    const cliente = new Usuario(objetoUsuario);
    cliente.asignarValidacionCampos();
    cliente.formularioAgregar('form_cliente'); //id del formulario
    botonResetFormulario('reset_cliente', cliente, 'form_cliente'); //id_boton_reset, objeto para listar los registros
    await cliente.listarRegistros();
    return cliente;

}

function actualizarUsuario(){
    const objetoUsuario = {

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
            {email_modal: '^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$', message: 'No cumple con los requisitos de un correo electrónico', estado: true},
            {cod_depart_modal: '^[0-9]{2}$', message: 'Debe seleccionar un departamento', estado: true},
            {fk_ciudad_modal: '^[0-9]{1,5}$', message: 'Debe seleccionar una ciudad después de seleccionar el departamento', estado: true}
        ],
        //Filtra los resultados en la tabla de acuerdo a los valores que digite el usuario en los campos
        filtroBusqueda: false
    }

    // Se envia el id del formulario para el envio de registro
    const cliente = new Cliente(objetoUsuario);
    cliente.asignarValidacionCampos();
    cliente.formularioActualizar('form_cliente_actualizar', usuarioGlobal);
}