import { Ciudad } from '../global/class/ciudad.js';
import { Cliente } from '../global/class/cliente.js';
import { clienteActualizado } from '../global/parametros.js';

document.addEventListener('DOMContentLoaded', () =>{
    // Cargar ciudades en el comboBox cada vez que se cambie de departamento
    const ciudad = new Ciudad();
    //Se pasa por parámetro el id del Departamento, y el id de la ciudad 
    ciudad.cargarCiudades('cod_depart_modal', 'fk_ciudad_modal');

    const objetoCliente = {
        urlApiListar: '/cliente/api',
        urlActualizar : '/cliente/actualizar',
        
        estructuraTabla : [
            {cedula_nit: 'Cédula / Nit', posicion: 1, class:[]},
            {nombre: 'Nombre del Cliente', posicion: 2, class:[]},
            {telefono: 'Celular', posicion:3, class: []},
            {direccion: 'Dirección', posicion:4, class: []},
            {nombre_ciudad: 'Ciudad', posicion:5, class: []},
            {nombre_depart: 'Departamento', posicion:6, class: []}
        ]
    }

    // Se envia el id del formulario para el envio de registro
    const cliente = new Cliente(objetoCliente);
    formularioActualizarRegistro(cliente);
    botonCerrarModal();

});

function formularioActualizarRegistro(cliente){

    const formulario = document.querySelector('#form_cliente_modal');

    formulario.addEventListener('submit', async (e) => {
        // Prevenir el comportamiento por defecto del formulario
        e.preventDefault();

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

        const rta = cliente.actualizarRegistro(nuevoFormData);
        if(rta){
            cliente.listarRegistro();
            cerrarModal();
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

//# sourceMappingURL=cliente-modal.js.map
