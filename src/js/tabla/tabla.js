// import { Ciudad } from "../global/class/ciudad.js";
import { Tabla } from "../global/class/tabla.js";
import { botonResetFormulario, cierreManualModal } from "../global/parametros.js";

let tablaGlobal = '';

document.addEventListener('DOMContentLoaded', async ()=>{

    tablaGlobal = await guardarTabla();
    actualizarTabla();
    botonCerrarModal();
});

function botonCerrarModal(){
    // Botón Cerrar Modal
    // Hay varias ventanas modal, y por lo tanto es necesario programar cada botón
    const btnCerraModal = document.querySelector('.cerrar__modal');
    btnCerraModal.addEventListener('click', e => cierreManualModal(e));
}

async function guardarTabla(){
    //Definir el objeto Cliente para enviarlo por parámetro al constructor
    const objetoTabla = {

        modal:{
            isModal:false
        },

        url : {
            agregar : '/tabla/guardar',
            eliminar : '/tabla/eliminar',
            apiConsultar: '/tabla/api'
        },
        
        idVentanaModal: 'modal_tabla_actualizar',

        //Id del texto donde se muestra la totalidad de los registros
        idTotalRegistros : 'registrosTabla',
        
        // Son los campos que deben ir en la tabla al momento de consultar el servidor
        tabla : {
            idTabla: 'tabla',
            estructura : [
                {nombre_tb: 'Nombre de la Tabla', posicion: 1, class:[]}
            ],
            columnaModificar: true,
            columnaEliminar: true
        },

        // Se crea esta propiedad porque es necesario pasar los datos de un registro de la tabla al formulario modal para actualizar los datos, por lo tanto es necesario saber cual es el equivalente del nombre del campo de la array de datos con el nombre del campo al cual se pasa los datos
        equivalenciaCamposModal : [
        //id del campo del fomulario principal : 'id del campo de la ventana modal'
            {id_tb: 'id_tb_modal'},
            {nombre_tb: 'nombre_tb_modal'}
        ],
        
        //Se verifica que los campos diligenciados cumplan con estos registros
        validacionCampos : [
            {nombre_tb: '^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{2,100}$', message: 'Solo acepta números y/o letras', estado: false}
        ],

        //Filtra los resultados en la tabla de acuerdo a los valores que digite el usuario en los campos
        filtroBusqueda: true
    }

    const tabla = new Tabla (objetoTabla);
    tabla.asignarValidacionCampos();
    tabla.formularioAgregar('form_tabla'); //id del formulario
    botonResetFormulario('reset_tabla', tabla, 'form_tabla'); //id_boton_reset, objeto para listar los registros
    await tabla.listarRegistros();
    return tabla;
}

function actualizarTabla(){
    const objetoTabla = {

        url : {
            actualizar : '/tabla/actualizar',
        },

        // Es utilizado únicamente para mostrar los mensajes de error en los campos del formulario que contienen un nombre adicional, y que estos errores provienen del backend y del frontend 
        modal:{
            isModal:true,
            idVentanaModal : 'modal_tabla_actualizar',
            nombreCampoComplemento: '_modal'
        },

        validacionCampos : [
            {nombre_tb_modal: '^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{2,100}$', message: 'Solo acepta números y/o letras', estado: true}
        ],
        //Filtra los resultados en la tabla de acuerdo a los valores que digite el usuario en los campos
        filtroBusqueda: false
    }

    // Se envia el id del formulario para el envio de registro
    const tabla = new Tabla (objetoTabla);
    tabla.asignarValidacionCampos();
    tabla.formularioActualizar('form_tabla_actualizar', tablaGlobal);
}