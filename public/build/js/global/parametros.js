export const url = 'http://127.0.0.1:3000'
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

    if (!label.className.includes('text__error')){
        label.classList.add('text__error');
    }

    if (msgError.className.includes('ocultar') && msg != ''){
        msgError.classList.remove('ocultar');
        msgError.textContent = msg;
    }
}

//Se para por parámetro el nombre de la clase input__error 
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

export function limpiarFormulario(arrayCampos, campoFocus){
    arrayCampos.forEach(nombreCampo => {
        const campo = document.querySelector(`#${nombreCampo}`);
        campo.value='';

        if (nombreCampo == campoFocus){
            campo.focus();
        }

    });
}

//# sourceMappingURL=parametros.js.map
