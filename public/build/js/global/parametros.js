export const url = 'http://192.168.18.90:3000'
export let clienteActualizado = false;

export async function consultarAPI(rutaComplemento) {
    const urlFetch = url + rutaComplemento

    try {
        // Esperar la respuesta del fetch
        const response = await fetch(urlFetch);
        
        // Comprobar si la respuesta es correcta
        if (!response.ok) {
            throw new Error('Error en la solicitud: ' + response.status);
        }

        // Esperar la conversión de la respuesta a JSON
        const data = await response.json();

        return data;

    } catch (error) {
        console.error('Error:', error);
        return 0;
    }
}

export function mostrarErrorCampo(input_text, msg = ''){

    const cajaTexto = document.querySelector(`#${input_text}`);
    const label = cajaTexto.previousElementSibling;
    const msgError = cajaTexto.nextElementSibling;

    if (!cajaTexto.className.includes('input__error')){
        cajaTexto.classList.add('input__error');
    }

    if (label){
        if (!label.className.includes('text__error')){
            label.classList.add('text__error');
        }

    }

    if (msgError.className.includes('ocultar') && msg != ''){
        msgError.classList.remove('ocultar');
        msgError.textContent = msg;
    }
}

//Se pasa por parámetro el nombre de la clase input__error 
//para obtener todos los campos a los que se necesita quitar el error 

export function quitarErrorCampo(objeto){
    
    if (objeto.inputDOM.className.includes('input__error')){
        objeto.inputDOM.classList.remove('input__error');

        const label = objeto.inputDOM.previousElementSibling
        label.classList.remove('text__error');

        const msgError = objeto.inputDOM.nextElementSibling;
        msgError.classList.add('ocultar');
        msgError.textContent = '';
    }
}

export function limpiarFormulario(arrayCampos){
    arrayCampos.forEach(nombreCampo => {
        const campo = document.querySelector(`#${nombreCampo}`);
        campo.value='';
    });
}

export function mostrarModal(idModal){
    const ventanaModal =  document.querySelector(`#${idModal}`);
    if (ventanaModal.className.includes('ocultar')){
         ventanaModal.classList.remove('ocultar');
    }
}

// Se ejecuta al presionar en el botón de la X 
export function cierreManualModal(e){
    const ventana = e.target.parentElement;
    if(!ventana.className.includes('ocultar')){
        ventana.classList.add('ocultar');
    }
}

// Se ejecuta cuando termina una acción en específico y se debe cerrar automáticamente
export function cierreAutModal(idModal){
    const clienteSectModal = document.querySelector(`#${idModal}`);
    clienteSectModal.classList.add('ocultar');
}

export function habilitarBotonSubmit(idFormulario){
    const btnSubmit = document.querySelector(`#${idFormulario}`).querySelector('input[type="submit"]');
    btnSubmit.classList.remove('boton--secundario');
    btnSubmit.classList.add('boton--primario');
    btnSubmit.removeAttribute('disabled');

}

export function cargarFechaHoraActual(){

    // Crear un objeto de fecha con la fecha actual
    const now = new Date();

    // Formatear la fecha en el formato YYYY-MM-DD requerido por el input date
    const formattedDate = now.toISOString().split('T')[0];

    // Formatear la hora en el formato HH:MM requerido por el input time
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');

    return {
        fecha : formattedDate,
        hora : `${hours}:${minutes}`
    }

}

export function botonResetFormulario(nombreBoton, objeto){
    const botonReset = document.querySelector(`#${nombreBoton}`);
    botonReset.addEventListener('click', ()=>{
        objeto.listarRegistros();
    })
}

//# sourceMappingURL=parametros.js.map
