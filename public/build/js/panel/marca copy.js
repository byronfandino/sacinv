let registros;
let nombreMarcas=new Array();
let stringMarcas='';

document.addEventListener('DOMContentLoaded', () => {
    verificarMensajeGeneral();
    managerAlert();
    validarCampos();
    botonLimpiar();
    botonEstado();
});

// Valida las sugerencias y los errores
function validarCampos(){

    const inputNombre = document.querySelector(`[data-tipo="nombreMarca"]`);
    const labelSugr = document.querySelector('.form__labelSugerencia');
    const labelError = document.querySelector('.form__labelError');
    const iconoError = document.querySelector('.form__iconError');
    const btnSubmit = document.querySelector('.btn-primario');

    const expresion="^[A-ZÑa-züñáéíóúÁÉÍÓÚÜ'° 0-9]{3,50}$";
    const msg="Solo debe contener letras y números, mayor a 3 caracteres";
    let expresionRegular;

    if(inputNombre){

        // Verificamos si la ruta corresponde a editar
        if (window.location.pathname.includes('/editar')){

            inputNombre.addEventListener('input', e => {
                //En este caso NO verificamos que exista el registro en la base de datos 
                if(e.target.value.match(expresion)){
                    mostrarOcultarSugerencias(labelSugr, '', false);
                    estadoBoton(btnSubmit, true);
                }else{
                    mostrarOcultarSugerencias(labelSugr, msg, true);
                    estadoBoton(btnSubmit, false);
                }

                if(labelError && labelError.textContent != ''){
                    ocultarError(labelError, iconoError);
                }
            });

        }else{

            inputNombre.addEventListener('input', e => {
                
                // expresionRegular = validarSugerencias(expresion, e.target.value);
                expresionRegular = e.target.value.match(expresion);
                let stringFormateado = formatearTexto(e.target.value);
                let existeRegistro = nombreMarcas.includes(stringFormateado);

                if(expresionRegular && !existeRegistro){
                    mostrarOcultarSugerencias(labelSugr, '', false);
                    // Habilitar el botón
                    estadoBoton(btnSubmit, true);
                    
                }else if(!expresionRegular && !existeRegistro){
                    mostrarOcultarSugerencias(labelSugr, msg, true);
                    // Deshabilitar el botón
                    estadoBoton(btnSubmit, false);
                    
                }else if((!expresionRegular && existeRegistro)||(expresionRegular && existeRegistro)){
                    mostrarOcultarSugerencias(labelSugr, 'Esta Marca ya se encuentra registrada', true);
                    // Deshabilitamos el botón
                    estadoBoton(btnSubmit, false);
                }

                let coincidencia = stringMarcas.includes(stringFormateado);

                if(coincidencia){
                    borrarFilas();
                    mostrarRegistrosAPI(registros, 'input', stringFormateado);
                }else{
                    borrarFilas();
                    mostrarRegistrosAPI(registros);
                }

                if(labelError && labelError.textContent != ''){
                    ocultarError(labelError, iconoError);
                }
            });
        }
    }
}

function ocultarError(labelError, iconoError){
    labelError.textContent='';
    labelError.classList.add('ocultar');
    iconoError.classList.add('ocultar');
    // Obtenemos el icono limpiar para cambiarle la posición de desplazamiento
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

// Función dependiente de la función 'validarCampos()'
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

    const labelError = document.querySelector('.form__labelError');
    const iconoError = document.querySelector('.form__iconError');
    const iconoLimpiar = document.querySelector('.form__limpiar');
    const inputNombre = document.querySelector(`[data-tipo="nombreMarca"]`);
    let stringFormateado = formatearTexto(inputNombre.value);
    
    // Mostrar los mensajes de error que aparecen en el label cuando provienen del servidor
    if(labelError.textContent !== ''){
        labelError.classList.remove('ocultar');
        iconoError.classList.remove('ocultar');
        iconoLimpiar.style.right = "3rem";

        setTimeout(() => {
            borrarFilas();
            mostrarRegistrosAPI(registros, 'input', stringFormateado);
        }, 300);
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
        registros = await resultado.json();
        
        mostrarRegistrosAPI(registros);
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
function mostrarRegistrosAPI(registros, evento = 'DOM', valor = ''){
    // Verificamos que exista una tabla en el body, ya que si no está... quiere decir que estamos utilizando este codigo en editar-categoria, y allí no existe la tabla de registros
    const tabla = document.querySelector('.table');

    if (registros){

        registros.forEach( registro => {
            
            const {Mc_Descripcion} = registro;
            if(evento === 'DOM' && valor === ''){
    
                // Almacenamos en el arreglo global 'nombreMarcas' todos los nombres para usarlos en otra función
                nombreMarcas.push(formatearTexto(Mc_Descripcion));
    
                // Si la tabla si existe , entonces se procede a cargar los registros 
                if (tabla){
                    crearRegistro(registro);
                }
            }
            
            // Si lo que digitó el usuario se encuentra dentro de la descripción entonces se agrega el registro
            if(evento === 'input' && formatearTexto(Mc_Descripcion).includes(valor)){
                if(tabla){
                    crearRegistro(registro);
                }
            }
        });
    }

    // Guardamos todos los nombres de las categorias en un solo string para utilizarlo en el filtro del campo
    stringMarcas=nombreMarcas.toString();
}

// Se borra las filas para realizar el filtro de búsqueda
function borrarFilas(){
    const filas = document.querySelectorAll('.tbody tr');
    filas.forEach( fila => {
        fila.remove();
    });
}

function crearRegistro(registro){
    const {Mc_Id, Mc_Descripcion, Mc_Status} = registro;

    const tdNombre = document.createElement('TD');
    tdNombre.classList.add('tbody__td--nombre');
    tdNombre.dataset.idItem = Mc_Id;
    tdNombre.textContent = Mc_Descripcion;

    const tdEstado = document.createElement('TD');
    tdEstado.classList.add('tbody__td--estado');
    tdEstado.classList.add('center');
    if (Mc_Status==='E'){
        tdEstado.textContent = "Habilitado";
    }else if (Mc_Status==='D'){
        tdEstado.textContent = "Deshabilitado";
    }else{
        tdEstado.textContent = "";
    }

    //1. Creamos el bloque de editar, empezando por los hijos hasta el padre
    //1.1 Creamos el SPAN donde la se coloca la palabra Modificar
    const spanModificar = document.createElement('SPAN');
    spanModificar.textContent="Modificar";

    // 1.2.Creamos la imagen del icono editar
    const imgModificar = document.createElement('IMG');
    imgModificar.setAttribute('src','/build/img/sistema/editar-ng.svg');
    imgModificar.setAttribute('alt','Imagen Editar');

    // 1.3. Creamos el contenedor el cual es el enlace de la etiqueta <a>
    const linkModificar = document.createElement('A');
    linkModificar.setAttribute('href', `/marca/editar?id=${Mc_Id}`);
    linkModificar.appendChild(imgModificar);
    linkModificar.appendChild(spanModificar);

    //2. Creamos el bloque de editar, empezando por los hijos hasta el padre
    //2.1 Creamos el SPAN donde la se coloca la palabra Modificar
    const spanHistorial = document.createElement('SPAN');
    spanHistorial.textContent="Historial";

    // 2.2.Creamos la imagen del icono editar
    const imgHistorial = document.createElement('IMG');
    imgHistorial.setAttribute('src','/build/img/sistema/historial.svg');
    imgHistorial.setAttribute('alt','Imagen Historial');

    // 2.3. Creamos el contenedor el cual es el enlace de la etiqueta <a>
    const linkHistorial = document.createElement('A');
    linkHistorial.setAttribute('href', `/historial?id=${Mc_Id}`);
    linkHistorial.appendChild(imgHistorial);
    linkHistorial.appendChild(spanHistorial);

    // 3 Creamos el siguiente contenedor que es la celda de la tabla para los items de Modificar
    const tdModificar = document.createElement('TD');
    tdModificar.classList.add('tbody__td--icon');
    tdModificar.appendChild(linkModificar);

    // 4 Creamos el siguiente contenedor que es la celda de la tabla para los items de Historial
    const tdHistorial = document.createElement('TD');
    tdHistorial.classList.add('tbody__td--icon');
    tdHistorial.appendChild(linkHistorial);

    // 5. Creamos el <tr> donde irá todo el contenido creado anteriormente
    const tr = document.createElement('TR');
    tr.appendChild(tdNombre);
    tr.appendChild(tdEstado);
    tr.appendChild(tdModificar);
    tr.appendChild(tdHistorial);

    // Por último se muestra en el HTML
    document.querySelector('.tbody').appendChild(tr);

}

function formatearTexto(cadena){
    
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
                timer:1800,
                didOpen: () => {
                    // Verificamos si está en la página editar para realizar el redireccionamiento
                    if (window.location.href.includes('/editar')){
                            setTimeout(() => {
                                const pathAnterior = window.location.pathname.split('/');
                                // En la posición 1 se encuentra la ruta principal de la tabla, ya que en la posición 0 guarda una cadena vacía
                                window.location.href = '/' + pathAnterior[1];
                        }, 1000);
                    }
                  }
            }); 

            limpiarCaja();             
        }
    }
}

// limpia el campo de categoria
function botonLimpiar(){
    const btnLimpiar = document.querySelector('.form__limpiar');
    btnLimpiar.addEventListener('click', (e) => {
        e.preventDefault();
        borrarFilas();
        limpiarCaja();
    });
}

// limpia el campo y los mensajes de error
function limpiarCaja(){
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
    
    mostrarRegistrosAPI(registros);

    const inputNombre = document.querySelector(`[data-tipo="nombreMarca"]`);
    inputNombre.value='';
    inputNombre.focus();
}

function botonEstado(){
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
