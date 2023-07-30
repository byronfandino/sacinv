import { rutaServidor } from "./class/Parametros.js";

let registros;
let arrayNombres=new Array();
let stringNombres='';
let objeto = {
    nit:false,
    razonSocial:false,
    direccion:false,
    celular:false,
    whatsapp:true,
    email:false,
    inventario:false,
    slogan:true,
    xjeventa:false
}
let cnt = 0;

document.addEventListener('DOMContentLoaded', () => {
    verificarMensajeGeneral();
    managerAlert();
    asignarValidacion();
    botonLimpiar();
    campoFile();
    verificarCampos();
});

function verificarCampos(){
    const inputs = document.querySelectorAll('input:not([type="submit"], [type="button"], [type="button"])');
    const selects = document.querySelectorAll('select');
    const boton = document.querySelector('input[type="submit"]');

    inputs.forEach(input => {
        if(input.getAttribute('id') != 'slogan' && input.getAttribute('id') !== null && input.value !== '0'){
           estadoCampo(input.getAttribute('id'), true, boton); 
        }
    });

    selects.forEach(select => {
        if(select.value != ''){
           estadoCampo(select.getAttribute('datatipo'), true, boton); 
        }
    });

    console.log(selects);
}

// Valida las sugerencias y los errores
function asignarValidacion(){

    const campos = document.querySelectorAll('.form__campo');
    const btnSubmit = document.querySelector('.btn-primario');
    const btnEliminar = document.querySelector('.eliminar-imagen');

    campos.forEach(campo => {

        const input = campo.querySelector('input');
        const select = campo.querySelector('select');
        const labelSugr = campo.querySelector('.form__labelSugerencia');
        const labelError = campo.querySelector('.form__labelError');
        const iconoError = campo.querySelector('.form__iconError');
        let tipo;
        let regla;
        let msg;
        let expresionRegular;

        //Aquí se valida únicamente los inputs
        if(input){
            
            tipo = input.getAttribute('data-tipo');
            regla = reglaInput(tipo);
            msg = mensajeSugerencia(tipo);

            // Evento de carga de formulario---------
            expresionRegular = input.value.match(regla);

            if(expresionRegular){
                estadoCampo(tipo, true, btnSubmit);
            }else{
                estadoCampo(tipo, false, btnSubmit);
            }
            //---TERMINA LA EVALUACIÓN DE LOS CAMPOS

            // evento de cambios
            input.addEventListener('input', e => {
                
                // expresionRegular = validarSugerencias(regla, e.target.value);
                expresionRegular = e.target.value.match(regla);
                if(expresionRegular){

                    estadoCampo(tipo, true, btnSubmit);
                    mostrarOcultarSugerencias(labelSugr, '', false);
                
                }else{

                    estadoCampo(tipo, false, btnSubmit);
                    mostrarOcultarSugerencias(labelSugr, msg, true);
                }

                if(labelError && labelError.textContent != ''){
                    ocultarError(labelError, iconoError);
                }
            }); 
        }//Fin de las acciones de los inputs

        if(select){
            tipo = select.getAttribute('data-tipo');
            msg = mensajeSugerencia(tipo);

            // Se coloca esta condición para activar el botón cuando carga el formulario
            if(select.value != ''){
                estadoCampo(tipo, true, btnSubmit);
            }

            select.addEventListener('change', ()=>{
                if (select.value !== ''){
                    mostrarOcultarSugerencias(labelSugr, '', false);
                    estadoCampo(tipo, true, btnSubmit);
                    ocultarError(labelError, iconoError);
                }else{
                    mostrarOcultarSugerencias(labelSugr, msg, true);
                    estadoCampo(tipo, false, btnSubmit);
                }
            });
        }
    });

    if (btnEliminar){
        
        btnEliminar.addEventListener('click', () => {

            Swal.fire({
                title: '¿Está seguro(a) de eliminar la imagen?',
                // text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Si, Eliminar!',
                cancelButtonText: 'No, Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
    
                    eliminarLogo();
                }
            })
        })
    }
}

function reglaInput(tipo){
    let regla;

    if (tipo == 'nit'){
        regla="^[0-9-]{6,12}$";
    }

    if (tipo == 'razonsocial'){
        regla="^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ' ]{4,100}$";
    }

    if (tipo == 'direccion'){
        regla="^[#.A-Za-z0-9- ]{10,30}$";
    }

    if (tipo == 'tel'){
        regla="^[+0-9]{10,13}$";
    }

    if (tipo == 'whatsapp'){
        regla="^[+/NA0-9]{3,13}$";
    }

    if (tipo == 'email'){
        regla=/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/;
    }

    if (tipo == 'slogan'){
        regla="^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ/ ]{3,200}$";
    }

    if (tipo == 'xjeventa'){
        regla="^[0-9]{1,3}$";
    }
    return regla;
}

function mensajeSugerencia(tipo){
    let msg;
    if (tipo == 'nit'){
        msg="Solo debe contener números o guión (-), mayor a 5 caracteres";
    }

    if (tipo == 'razonsocial'){
        msg="Solo debe contener letras y números, mayor a 3 caracteres";
    }

    if (tipo == 'direccion'){
        msg="Este campo solo puede contener letras, números espacios y caracteres como (#-.), mayor a 10 caracteres";
    }

    if (tipo == 'tel'){
        msg="Solo debe contener números, de 10 caracteres";
    }

    if (tipo == 'whatsapp'){
        msg="Solo debe contener números, de 10 caracteres";
    }

    if (tipo == 'email'){
        msg="No cumple con los requisitos mínimos de un correo electrónico";
    }

    if (tipo == 'inventario'){
        msg="Debe seleccionar una opción";
    }

    if (tipo == 'slogan'){
        msg="Solo debe contener letras y números, mayor a 8 caracteres";
    }

    if (tipo == 'xjeventa'){
        msg="Solo debe contener números entre 1 y 100";
    }

    return msg;
}

// Se inicia en vacío ya que se utiliza en la carga del DOM
function estadoCampo(tipo, estado, boton){

    if (tipo == 'nit'){
        objeto['nit']=estado;
    }

    if (tipo == 'razonsocial'){
        objeto['razonSocial']=estado;
    }

    if (tipo == 'direccion'){
        objeto['direccion']=estado;
    }

    if (tipo == 'tel'){
        objeto['celular']=estado;
    }

    if (tipo == 'whatsapp'){
        objeto['whatsapp']=estado;
    }

    if (tipo == 'email'){
        objeto['email']=estado;
    }

    if (tipo == 'inventario'){
        objeto['inventario']=estado;
    }

    if (tipo == 'slogan'){
        objeto['slogan']=estado;
    }

    if (tipo == 'xjeventa'){
        objeto['xjeventa']=estado;
    }

    for (let llave in objeto){
        if(objeto[llave] == false){
            cnt=0;
        }else{
            cnt++;
        }
    }
    
    if(cnt == 9){
        estadoBoton(boton, true);
    }else{
        estadoBoton(boton, false);
    }

    cnt=0;
}

function ocultarError(labelError, iconoError){
    labelError.textContent='';
    labelError.classList.add('ocultar');
    iconoError.classList.add('ocultar');
    iconoError.previousElementSibling.style.right = "0.5rem";
}

// Función dependiente de la función 'validarcampos()'
// muestra u oculta el error
function mostrarOcultarSugerencias(label, msg, estado){
    if (estado){
        label.classList.remove('ocultar');
        label.textContent=msg;
    }else{
        label.classList.add('ocultar');
        label.textContent="";
    }
}

// Función dependiente de la función 'asignarValidacion()'
// activa o desactiva el botón de Guardar
function estadoBoton(boton, estado){
    if (estado){
        boton.classList.remove('disabled');
        boton.removeAttribute('disabled');                                
    }else{
        boton.classList.add('disabled');
        boton.setAttribute('disabled', '');                                
    }
}

// Muestra la alerta de carga de datos y ejecuta la función de consulta la APi de categorías
function managerAlert(){
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

// Mostrar Alerta de error o Exito con Sweet Alert
function verificarMensajeGeneral(){
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
                timer:1800
            }); 
        }
    }
}

// limpia el campo de categoria
function botonLimpiar(){
    const btnLimpiar = document.querySelectorAll('.form__limpiar');

    btnLimpiar.forEach( boton => {
        boton.addEventListener('click', (e) => {
            e.preventDefault();
            limpiarCaja(boton.parentElement);
        });
    });
}

// limpia el campo y los mensajes de error
function limpiarCaja(item){
    const input = item.querySelector('.form__input');
    const labelSugerencia = item.querySelector('.form__labelSugerencia');
    const labelError = item.querySelector('.form__labelError');
    const iconoError = item.querySelector('.form__iconError');
    const iconoLimpiar = item.querySelector('.form__limpiar');
    const tipo = input.getAttribute('data-tipo');

    if (tipo === 'whatsapp' || tipo === 'slogan'){
        input.value="N/A";
    }else{
        input.value="";
    }
    input.focus();

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
    
    // mostrarRegistrosAPI(registros);

}

function campoFile(){
    const inputFile = document.querySelector(`[type="file"]`);
    const fileSelect = document.querySelector('.textFileSelect');
    const inputbutton = document.querySelector(`[type="button"]`);
    const iconoError = document.querySelector('.campo--file > .form__iconError');
    const labelSugerencia = document.querySelector('.campo--file > .form__labelSugerencia');
    const labelError = document.querySelector('.campo--file > .form__labelError');

    if (inputFile && inputbutton){
        inputbutton.addEventListener('click', ()=>{
            inputFile.click();
            inputFile.addEventListener('input', ()=>{

                fileSelect.textContent = inputFile.value;

                if(fileSelect.textContent !== '' && !labelError.className.includes('ocultar')){
                    labelError.classList.add('ocultar');
                    iconoError.classList.add('ocultar');
                }

                console.log(inputFile.files[0]);
                let pesoArchivo = inputFile.files[0].size;

                let pesoMB = (pesoArchivo / 1000000).toFixed(2);
                // let pesoFormateado = pesoMB.toFixed(2);
                if(pesoArchivo > 500000){
                    labelSugerencia.textContent = `El archivo seleccionado tiene un peso de ${pesoMB} MB, y NO debe superar el peso de 500 KB`;
                    labelSugerencia.classList.remove('ocultar');
                }

                let tipoArchivo = inputFile.files[0].type;
                if(!(tipoArchivo === "image/jpeg" || tipoArchivo === "image/jpg" || tipoArchivo === "image/png")){
                    labelSugerencia.textContent = `Seleccionó un archivo que NO es permitido`;
                    labelSugerencia.classList.remove('ocultar');
                }

                // si se cumplen las condiciones del archivo, se muestra en el HTML
                if((tipoArchivo === "image/jpeg" || tipoArchivo === "image/jpg" || tipoArchivo === "image/png") && pesoArchivo < 500000 && fileSelect.textContent !== ''){
                    // const imagenSeleccionada = document.querySelector('.contenedor-logo-seleccionado > img');
                    // imagenSeleccionada.setAttribute('src', fileSelect.textContent);
                    // imagenSeleccionada.parentElement.classList.remove('ocultar');
                    
                    // labelSugerencia.textContent = `Seleccionó un archivo que NO es permitido`;
                    // labelSugerencia.classList.remove('ocultar');
                }
            });
        });
    }
}

async function eliminarLogo(){

    try{
        const url = rutaServidor + '/parametro/eliminar-logo';
        const respuesta = await fetch(url, {
            method:'POST'
        });

        const resultado = await respuesta.json();

        if (resultado.resultado) {   
            
            Swal.fire({
                icon: 'success',
                title: "El logo ha sido eliminado",
                timer:1500,
                showConfirmButton: true
            }).then( () => {
                window.location.href = "/parametro";
            }); 
        }

    } catch (error){
        Swal.fire({
            icon: 'error',
            title: "Error de eliminación de imagen. Detalle Error: " + error,
            showConfirmButton: true
        });
    }
}