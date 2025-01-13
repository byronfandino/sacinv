import { Ciudad } from '../global/class/ciudad.js';
import { Cliente } from '../global/class/cliente.js';

document.addEventListener('DOMContentLoaded', () =>{
    // Cargar ciudades en el comboBox cada vez que se cambie de departamento
    const ciudad = new Ciudad();
    //Se pasa por parámetro el id del Departamento, y el id de la ciudad 
    ciudad.cargarCiudades('cod_depart_modal', 'fk_ciudad_modal');

    const objetoCliente = {
        urlActualizar : '/cliente/actualizar',
        validacionCampos : [
            {cedula_nit_modal: '^(?!.*--)[0-9]{4,15}$|^(?!.*--)[0-9-]{4,15}$', message: 'Caracteres aceptados: números (0-9) y un solo guión', estado: true},
            {nombre_modal: '^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{4,100}$', message: 'Solo acepta números y/o letras', estado: true},
            {telefono_modal: '^[0-9]{10}$', message: 'Se permite 10 números', estado: true},
            {direccion_modal: '^[a-zA-Z0-9#\-áéíóúÁÉÍÓÚñÑ ]{5,100}$', message: 'Se permiten letras, números, espacios y símbolos como: # -', estado: true},
            {cod_depart_modal: '^[0-9]{2}$', message: 'Debe seleccionar un departamento', estado: true},
            {fk_ciudad_modal: '^[0-9]{1,5}$', message: 'Debe seleccionar una ciudad después de seleccionar el departamento', estado: true}
        ]
    }

    // Se envia el id del formulario para el envio de registro
    const cliente = new Cliente(objetoCliente);
    cliente.asignarValidacionCampos();
    formularioActualizarRegistro(cliente);
    botonCerrarModal();

});

function formularioActualizarRegistro(cliente){

    const formulario = document.querySelector('#form_cliente_modal');

    formulario.addEventListener('submit', async (e) => {
        // Prevenir el comportamiento por defecto del formulario
        e.preventDefault();

        // Si pasa la validación de los campos envie la petición al post
        if(cliente.revisarCampos()){

            let formData = new FormData(formulario); // Crear un objeto FormData con los datos del formulario
               
            // Crear un nuevo FormData
            let nuevoFormData = new FormData();
            
            // Modificar las keys
            for (let [key, value] of formData.entries()) {
                if (key === 'id_cliente_modal') {
                nuevoFormData.append('id_cliente', value);
                } else if (key === 'cedula_nit_modal') {
                nuevoFormData.append('cedula_nit', value);
                } else if (key === 'nombre_modal') {
                    nuevoFormData.append('nombre', value);
                } else if (key === 'telefono_modal') {
                    nuevoFormData.append('telefono', value); 
                }else if (key === 'direccion_modal') {
                    nuevoFormData.append('direccion', value); 
                }else if (key === 'fk_ciudad_modal') {
                    nuevoFormData.append('fk_ciudad', value);
                }else {
                     // Mantener las otras keys sin cambios
                    nuevoFormData.append(key, value); 
                }
            }
            
            const rta = await cliente.actualizarRegistro(nuevoFormData);
            if(rta){
                
                cerrarModal();

                // Refrescar la página
                setTimeout(()=>{
                    location.reload();
                },800);
            }
        }
    });
}

function botonCerrarModal(){
    const btnCancelar = document.querySelector('#cerrar-modal');
    btnCancelar.addEventListener('click', e =>{
        e.preventDefault();
        cerrarModal();
    });
}

function cerrarModal(){
    const modalCliente = document.querySelector('#modal-cliente');
    if (!modalCliente.className.includes('ocultar')){
        modalCliente.classList.add('ocultar');
    }
}

//# sourceMappingURL=cliente_modal.js.map
