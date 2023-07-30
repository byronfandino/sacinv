export const rutaServidor = 'http://192.168.18.90:3000/'; 

export function mostrarOcultarSugerencias(label, msg, estado){
    if (estado){
        label.classList.remove('ocultar');
    }else{
        label.classList.add('ocultar');
    }
    label.textContent=msg;
}

export function estadoBoton(boton, estado = false){
    if (estado){
        boton.classList.remove('disabled');
        boton.removeAttribute('disabled');                                
    }else{
        boton.classList.add('disabled');
        boton.setAttribute('disabled', '');                                
    }
}

export function ocultarError(labelError, iconoError){
    labelError.textContent='';
    labelError.classList.add('ocultar');
    iconoError.classList.add('ocultar');
    iconoError.previousElementSibling.style.right = "0.5rem";
}

export function formatearTexto(cadena){
    
    cadena=cadena.toLowerCase().trim();
    cadena=cadena.replaceAll('á', 'a');
    cadena=cadena.replaceAll('é', 'e');
    cadena=cadena.replaceAll('í', 'i');
    cadena=cadena.replaceAll('ó', 'o');
    cadena=cadena.replaceAll('ú', 'u');
    
    cadena=cadena.replaceAll('à', 'a');
    cadena=cadena.replaceAll('è', 'e');
    cadena=cadena.replaceAll('ì', 'i');
    cadena=cadena.replaceAll('ò', 'o');
    cadena=cadena.replaceAll('ù', 'u');
    return cadena;

}

// Esta función se dispara cuando se encuentran errores provenientes del backend
export function alertaErrorCampo(labelError, iconError, iconLimpiar, estado){
    if (estado === true){
        labelError.classList.remove('ocultar');
        iconError.classList.remove('ocultar');
        iconLimpiar.style.right = "3rem";
    }else{
        labelError.textContent='';
        labelError.classList.add('ocultar');
        iconError.classList.add('ocultar');
        iconLimpiar.style.right = "0.5rem";
    }
}
   
// limpia el campo y los mensajes de error
export function limpiarCaja(campoSelec, foco = true){
    const input = campoSelec.querySelector('.form__input');
    const labelSugerencia = campoSelec.querySelector('.form__labelSugerencia');
    const labelError = campoSelec.querySelector('.form__labelError');
    const iconoError = campoSelec.querySelector('.form__iconError');
    const iconoLimpiar = campoSelec.querySelector('.form__limpiar');
    // const tipo = input.getAttribute('data-tipo');

    // Ocultamos y vaciamos los errores
    if (!labelSugerencia.className.includes('ocultar')){
        labelSugerencia.classList.add('ocultar');
        labelSugerencia.textContent = '';
    }

    if(!labelError.className.includes('ocultar')){
        labelError.classList.add('ocultar');
        labelError.textContent = '';
    }
    
    if(!iconoError.className.includes('ocultar')){
        iconoError.classList.add('ocultar');
    }
    
    iconoLimpiar.style.right = "0.5rem";
    input.value='';

    if (foco){
        input.focus();
    }
}

export function showHideMensajeGeneral(){
    setTimeout(() => {
        
        const alerta = document.querySelector('.alerta');
    
        if (alerta){
            let textoAlerta = alerta.textContent;
    
            if(alerta.className.includes('error')){
                Swal.fire({
                    icon: 'error',
                    title: textoAlerta,
                    showConfirmButton: true
                });
            }
            
            if(alerta.className.includes('exito')){              
                Swal.fire({
                    icon: 'success',
                    title: textoAlerta,
                    timer:1500
                }); 
            }
        }
    }, 800);
}

export function limpiarFormulario(nombreForm){

    let errorCampo = false;
    // Verificamos si existen mensajes de error
    const labelsError = nombreForm.querySelectorAll('.form__labelError');
    labelsError.forEach(error => {
        if (error.textContent != ''){
            errorCampo = true;
        }
    });

    if (!errorCampo){
        const inputs = nombreForm.querySelectorAll('input:not([type="submit"],[type="button"])');
        inputs.forEach( input => {
            input.value="";
        });

        inputs[0].focus();
    }
}

export function showHideMensajesError(){
    
    // Verificamos el error local del campo
    let labelsError = document.querySelectorAll('.form__labelError');
    
    // Mostrar los mensajes de error que aparecen en el label cuando provienen del servidor
    labelsError.forEach( label => {
        const campo = label.parentElement;
        const iconoError = campo.querySelector('.form__iconError');
        const iconoLimpiar = campo.querySelector('.form__limpiar');
        
        if(label.textContent != ''){
    
            label.classList.remove('ocultar');
            iconoError.classList.remove('ocultar');
    
            if (iconoLimpiar){
                iconoLimpiar.style.right = "3rem";
            }
    
        }else{
    
            label.classList.add('ocultar');
            iconoError.classList.add('ocultar');
    
            if(iconoLimpiar){
                iconoLimpiar.style.right = "0.5rem";
            }
        }
    });

}

export function resetFormulario(){

    // Resetear los combosBox
    const selects = document.querySelectorAll('select');
    if (selects){
        selects.forEach( select => {
            select.value = "";
        });
    }    

    // Resetear los inputs de texto
    const inputs = document.querySelectorAll('input:not([type="submit"])');
    inputs.forEach( input => {
        input.value="";
    });

    // Verificamos el error local del campo
    const labelsError = document.querySelectorAll('.form__labelError');
    
    // Limpiar mensajes de sugerencias
    const labelsSugr = document.querySelectorAll('.form__labelSugerencia');
    labelsSugr.forEach( label => {

        if (!label.classList.add('ocultar')){
            label.classList.add('ocultar');
            label.textContent = '';
        }
    });

    // Limpiar mensajes de error
    labelsError.forEach( label => {
        const campo = label.parentElement;
        const iconoError = campo.querySelector('.form__iconError');
        const iconoLimpiar = campo.querySelector('.form__limpiar');

        if (!label.classList.add('ocultar')){
            label.classList.add('ocultar');
            iconoError.classList.add('ocultar');
            label.textContent = '';
    
        }
        
        if(iconoLimpiar){
            iconoLimpiar.style.right = "0.5rem";
        }

    });

    inputs[0].focus();
}
