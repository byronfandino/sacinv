// let registrosMarcasAPI;
// let nombreMarcas=new Array();
// let stringMarcas='';

document.addEventListener('DOMContentLoaded', () => {
    verificarMensajeGeneralMarca();
    managerAlertMarca();
    validarCampoMarca();
    botonLimpiarMarca();
    botonEstadoMarca();
});

// Valida las sugerencias y los errores
function validarCampoMarca(){
    const inputMarca = document.querySelector(`[data-tipo="nombreMarca"]`);
    const campo = inputMarca.parentElement;
    const labelSugr = document.querySelector('.label_marca');
    const labelError = campo.parentElement.querySelector('.form__labelError');
    const iconoError = campo.parentElement.querySelector('.form__iconError');
    const btnSubmit = document.querySelector(`[data-button="btn-marca"]`);

    const expresionMarca="^[A-ZÑa-züñáéíóúÁÉÍÓÚÜ'° 0-9]{3,50}$";
    const msgMarca="Solo debe contener letras y números, mayor a 3 caracteres";

    // console.log(labelSugr);
    let expresionRegularMarca;
    
    if(inputMarca){
        
        inputMarca.addEventListener('input', e => {
            
            console.log(inputMarca.value);
            // expresionRegularMarca = validarSugerencias(expresionMarca, e.target.value);
            expresionRegularMarca = e.target.value.match(expresionMarca);
            let stringFormateado = formatearTextoMarca(e.target.value);
            let existeRegistro = nombreMarcas.includes(stringFormateado);

            console.log(expresionRegularMarca);

            if(expresionRegularMarca && !existeRegistro){
                mostrarOcultarSugerenciasMarca(labelSugr, '', false);
                // Habilitar el botón
                estadoBotonMarca(btnSubmit, true);
                
            }else if(!expresionRegularMarca && !existeRegistro){
                mostrarOcultarSugerenciasMarca(labelSugr, msgMarca, true);
                // Deshabilitar el botón
                estadoBotonMarca(btnSubmit, false);
                
            }else if((!expresionRegularMarca && existeRegistro)||(expresionRegularMarca && existeRegistro)){
                mostrarOcultarSugerenciasMarca(labelSugr, 'Esta Marca ya se encuentra registrada', true);
                // Deshabilitamos el botón
                estadoBotonMarca(btnSubmit, false);
            }

            if(labelError && labelError.textContent != ''){
                ocultarErrorMarca(labelError, iconoError);
            }
        });
        
    }
}

function ocultarErrorMarca(labelError, iconoError){
    labelError.textContent='';
    labelError.classList.add('ocultar');
    iconoError.classList.add('ocultar');
    // Obtenemos el icono limpiar para cambiarle la posición de desplazamiento
    iconoError.previousElementSibling.style.right = "0.5rem";
}

// Función dependiente de la función 'validarcampos()'
// muestra u oculta el error
function mostrarOcultarSugerenciasMarca(label, msg, estado){
    if (estado){
        label.classList.remove('ocultar');
        label.textContent=msg;
    }else{
        label.classList.add('ocultar');
        label.textContent="";
    }
}

// Función dependiente de la función 'validarCampos()'
// activa o desactiva el botón de Guardar
function estadoBotonMarca(boton, estado){
    if (estado){
        boton.classList.remove('disabled');
        boton.removeAttribute('disabled');                                
    }else{
        boton.classList.add('disabled');
        boton.setAttribute('disabled', '');                                
    }
}

// Muestra la alerta de carga de datos y ejecuta la función de consulta la APi de categorías
function managerAlertMarca(){

    const alertaExito = document.querySelector('.alerta.exito');
    const alertaError = document.querySelector('.alerta.error');
    
    // Si existe una alerta de exito proveniente del servidor se da un tiempo de espera para que la alerta de cargar registros
    if (alertaExito){
        setTimeout(() => {
            cargaRegistros();
        },2000);
    }else if(!alertaError){
        cargaRegistros();
    }

    // Verificamos el error local del campo
    
    const inputNombre = document.querySelector(`[data-tipo="nombreMarca"]`);
    const labelError = document.querySelector('.form__labelError');
    const iconoError = document.querySelector('.form__iconError');
    const iconoLimpiar = document.querySelector('.form__limpiar');
    let stringFormateado = formatearTextoMarca(inputNombre.value);
    
    // Mostrar los mensajes de error que aparecen en el label cuando provienen del servidor
    if(labelError.textContent !== ''){
        labelError.classList.remove('ocultar');
        iconoError.classList.remove('ocultar');
        iconoLimpiar.style.right = "3rem";

        // setTimeout(() => {
            // borrarFilas();
            // mostrarRegistrosAPIMarca(registrosMarcasAPI, 'input', stringFormateado);
        // }, 300);
    }
}

function cargaRegistros(){
    
    Swal.fire({
        title: 'Cargando registros...',
        showConfirmButton: false,
        didOpen: () => {
          Swal.showLoading();
          let resultado = obtenerRegistrosAPI();
          resultado.then((result) => {
              if (result){
                  setTimeout(() => {
                      Swal.close();
                  }, 500);
              }            
          }); 
        }
    });
}

//Función dependiente de la función cargaRegistros
// Consulta la API y ejecuta la función para crear los elementos
async function obtenerRegistrosAPI(){
    try {

        const url='http://192.168.18.120:3000/marca/api';
        const resultado = await fetch(url);
        registrosMarcasAPI = await resultado.json();
        
        // nombreMarcas = '';

        registrosMarcasAPI.forEach( registro => {
            const {Mc_Descripcion} = registro;
            nombreMarcas.push(formatearTextoMarca(Mc_Descripcion));
        });
        console.log(registrosMarcasAPI);
        console.log(stringMarcas);
        stringMarcas = nombreMarcas.toString();
        
        return true;

    } catch (error) {
        Swal.fire({
            icon:'error',
            title:'Error',
            text:error + ' No fue posible cargar los registros de la base de datos'
        });
        return false;
    }
}

// Función dependiente de la función 'obtenerRegistrosAPI()'
// Muestra en el HTML los resultados de la API
// Utilizamos el evento para evitar cargar el arreglo general de nombres en un evento diferente a la carga del formulario
// function mostrarRegistrosAPIMarca(registros, evento = 'DOM', valor = ''){
//     // Verificamos que exista una tabla en el body, ya que si no está... quiere decir que estamos utilizando este codigo en editar-categoria, y allí no existe la tabla de registros
//     const tabla = document.querySelector('.table');

//     if (registros){

//         registros.forEach( registro => {
            
//             const {Mc_Descripcion} = registro;
//             if(evento === 'DOM' && valor === ''){
    
//                 // Almacenamos en el arreglo global 'nombreMarcas' todos los nombres para usarlos en otra función
//                 nombreMarcas.push(formatearTextoMarca(Mc_Descripcion));
    
//                 // Si la tabla si existe , entonces se procede a cargar los registros 
//                 // if (tabla){
//                 //     crearRegistro(registro);
//                 // }
//             }
            
//             // Si lo que digitó el usuario se encuentra dentro de la descripción entonces se agrega el registro
//             if(evento === 'input' && formatearTextoMarca(Mc_Descripcion).includes(valor)){
//                 // if(tabla){
//                 //     crearRegistro(registro);
//                 // }
//             }
//         });
//     }

//     // Guardamos todos los nombres de las categorias en un solo string para utilizarlo en el filtro del campo
//     stringMarcas=nombreMarcas.toString();
// }

function formatearTextoMarca(cadena){
    
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

// Mostrar Alerta de error o Exito con Sweet Alert
function verificarMensajeGeneralMarca(){
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
                timer:1800,
                didOpen: () => {
                    // Verificamos si está en la página editar para realizar el redireccionamiento
                    // if (window.location.href.includes('/editar')){
                    //         setTimeout(() => {
                    //             const pathAnterior = window.location.pathname.split('/');
                    //             // En la posición 1 se encuentra la ruta principal de la tabla, ya que en la posición 0 guarda una cadena vacía
                    //             window.location.href = '/' + pathAnterior[1];
                    //     }, 1000);
                    // }
                  }
            }); 

            limpiarCajaMarca();             
        }
    }
}

// limpia el campo de categoria
function botonLimpiarMarca(){
    const btnLimpiar = document.querySelector('.form__limpiar');
    btnLimpiar.addEventListener('click', (e) => {
        e.preventDefault();
        borrarFilas();
        limpiarCajaMarca();
    });
}

// limpia el campo y los mensajes de error
function limpiarCajaMarca(){
    const labelSugerencia = document.querySelector('.form__labelSugerencia');
    const labelError = document.querySelector('.form__labelError');
    const iconoError = document.querySelector('.form__iconError');
    const iconoLimpiar = document.querySelector('.form__limpiar');
    
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
    
    // mostrarRegistrosAPIMarca(registrosMarcasAPI);

    const inputNombre = document.querySelector(`[data-tipo="nombreMarca"]`);
    inputNombre.value='';
    inputNombre.focus();
}

function botonEstadoMarca(){
    const checks = document.querySelectorAll('.check');
    if (checks){
        checks.forEach( check => {
            // Evento click
            check.addEventListener('click', () => {
                // Obtenemos el botón que se encuentra dentro del contenedor
                let fondoBtn = check.lastElementChild;
                let boton = check.lastElementChild.firstElementChild;
                let labelSi = check.lastElementChild.firstElementChild.nextElementSibling;
                let labelNo = check.lastElementChild.firstElementChild.nextElementSibling.nextElementSibling;
                let valor = check.lastElementChild.lastElementChild;
                
                if(boton.className.includes('inactivo')){
                    boton.classList.remove('inactivo');
                    labelSi.classList.remove('ocultar');
                    fondoBtn.classList.remove('inactivo');
                    labelNo.classList.add('ocultar');
                    valor.setAttribute('value','E');
                }else{
                    boton.classList.add('inactivo');
                    labelSi.classList.add('ocultar');
                    fondoBtn.classList.add('inactivo');
                    labelNo.classList.remove('ocultar');
                    valor.setAttribute('value','D');
                }
                // boton.classList.toggle('inactivo');
            });
        });
    }
}
