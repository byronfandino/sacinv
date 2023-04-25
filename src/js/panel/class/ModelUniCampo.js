import { rutaServidor, 
    formatearTexto, 
    mostrarOcultarSugerencias, 
    estadoBoton,
    alertaErrorCampo } from "./Parametros.js";

// Selectores de campos
const campo = document.querySelector('[data-tipo="nombre"]').parentElement;

const inputNombre = campo.querySelector('[data-tipo="nombre"]');
const labelSugr = campo.querySelector('.form__labelSugerencia');
const labelError = campo.querySelector('.form__labelError');
const iconoError = campo.querySelector('.form__iconError');
const iconoLimpiar = campo.querySelector('.form__limpiar');
const btnSubmitCampo = campo.parentElement.querySelector('[data-button="btn-envio"]');

export let objetoUniCampo =  {
    entidad:'',
    expresion : '', 
    msg : '', 
    stringDescripcion : '', 
    llaves : [], 
    arrayDescripcion : [],
    registrosAPI : '',
    registrosAPIEntidad : ''
}

export class EntidadUniCampo{

    eventListeners(){

        if(inputNombre){
            // Verificamos si la ruta corresponde a editar
            if (window.location.pathname.includes('/editar')){
                inputNombre.addEventListener('input', rutaEditar);
            }else{
                inputNombre.addEventListener('input', rutaPrincipal);
            }
        }
    }

    // Funciones de API
    managerAlert(){
        const alertaExito = document.querySelector('.alerta.exito');
        const alertaError = document.querySelector('.alerta.error');
        // Si existe una alerta de exito proveniente del servidor se da un tiempo de espera para que la alerta de cargar registros
        if (alertaExito){
            setTimeout(() => {
                this.cargaRegistros();
            },2000);
        }else if(!alertaError){
            this.cargaRegistros();
        }

        // Mostrar los mensajes de error que aparecen en el label cuando provienen del servidor
        if(labelError.textContent !== ''){
            // Mostramos el error general
            alertaErrorCampo(labelError, iconoError, iconoLimpiar, true);

            setTimeout(() => {
                borrarFilas();
                mostrarRegistrosAPI('input', formatearTexto(inputNombre.value));
                this.estadoRegistro();
            }, 300);
        }
    }

    verificarMensajeGeneral(){
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

    cargaRegistros(){
    
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

    cargarComboBox(){

        // Verificamos que exista una tabla en el body, ya que si no está... quiere decir que estamos utilizando este codigo en editar-categoria, y allí no existe la tabla de registros
        const comboBox = document.querySelector(`#${objetoUniCampo.entidad}`);
        let optionEntidad='';
        
        // Borramos las opciones que puedan existir
        const options = comboBox.querySelectorAll('option');
        options.forEach( option => {
            if (option.getAttribute('value') != "" ){
                option.remove();
            }
        });
    
        let resultado = obtenerRegistrosAPI(objetoUniCampo.entidad);
    
        resultado.then(result => {
    
            if (result){
    
                let keysObject = Object.keys(objetoUniCampo.registrosAPIEntidad[0]);
    
                if (objetoUniCampo.registrosAPIEntidad){
    
                    objetoUniCampo.registrosAPIEntidad.forEach( registro => {
    
                        if(registro[keysObject[2]] == 'E'){
            
                            optionEntidad = document.createElement('OPTION'); 
                            optionEntidad.setAttribute('value', registro[keysObject[0]]);
                            optionEntidad.textContent=registro[keysObject[1]];
                            comboBox.appendChild(optionEntidad);
                        }
                    });
                }   
            }
        });
    }

    estadoRegistro(){
        botonStatus();
    }

    estadoBotonSubmit(){
        estadoBoton(btnSubmitCampo, false);
    }

    // limpia el campo
    botonLimpiar(){
    const iconoLimpiar = document.querySelector('.form__limpiar');
    iconoLimpiar.addEventListener('click', (e) => {
        e.preventDefault();
        borrarFilas();
        limpiarCaja();
    });
    }
}

function botonStatus(){

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
                let nombre = labelNo.nextElementSibling.value;
                let id = labelNo.nextElementSibling.nextElementSibling.value;

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
 
                // Una vez asignado el valor se envia por el POST
                let resultado = postElemento('Modificar', id, nombre, valor.value, );
                resultado.then(result => {
                    if (result){
                        // como el resultado fue satisfactorio, procedemos a cambiar el estado en el arreglo de objetos cargado en memoria
                        objetoUniCampo.registrosAPI.forEach( registro => {
                            if(Object.values(registro)[0] == id){
                                let campo = Object.keys(registro)[2];
                                registro[campo]=valor.value;
                            }
                        });
                    }
                });
            });
        });
    }
}

function rutaEditar(e){

    if(e.target.value.match(objetoUniCampo.expresion)){
        mostrarOcultarSugerencias('', false);
        estadoBoton(btnSubmitCampo, true);
    }else{
        mostrarOcultarSugerencias(objetoUniCampo.msg, true);
        estadoBoton(btnSubmitCampo, false);
    }

    if(labelError && labelError.textContent != ''){
        // Se oculta el error porque el usuario empieza a digitar
        alertaErrorCampo(labelError, iconoError, iconoLimpiar, false);
    }
}

function rutaPrincipal(e){

    let expresionRegular;
    expresionRegular = e.target.value.match(objetoUniCampo.expresion);
    let texto = formatearTexto(e.target.value);
    let existeRegistro = objetoUniCampo.arrayDescripcion.includes(texto);

    if(expresionRegular && !existeRegistro){
        mostrarOcultarSugerencias('', false);
        estadoBoton(btnSubmitCampo, true);
        
    }else if(!expresionRegular && !existeRegistro){
        mostrarOcultarSugerencias(objetoUniCampo.msg, true);
        estadoBoton(btnSubmitCampo, false);
        
    }else if((!expresionRegular && existeRegistro)||(expresionRegular && existeRegistro)){
        mostrarOcultarSugerencias('Esta categoría ya se encuentra registrada', true);
        estadoBoton(btnSubmitCampo, false);
    }

    let coincidencia = objetoUniCampo.stringDescripcion.includes(texto);

    if(coincidencia){
        borrarFilas();
        mostrarRegistrosAPI('input', texto);
    }else{
        borrarFilas();
        mostrarRegistrosAPI();
    }

    if(labelError && labelError.textContent != ''){
        alertaErrorCampo(labelError, iconoError, iconoLimpiar, false);
    }
}  

function mostrarRegistrosAPI(evento = 'DOM', valor = ''){

    const tabla = document.querySelector('.table');

    if (objetoUniCampo.registrosAPI){
        
        objetoUniCampo.registrosAPI.forEach( registro => {
            
            if (objetoUniCampo.llaves.length === 0){
                objetoUniCampo.llaves = Object.keys(registro);
            }

            // Obtenemos el valor que se encuentra en la segunda posición del objeto mediante el object values retorna un arreglo y accedemos a la segunda posición
            const valorCampo  = Object.values(registro)[1];
            
            if(evento === 'DOM' && valor === ''){
                objetoUniCampo.arrayDescripcion.push(formatearTexto(valorCampo));
                if (tabla){
                    crearRegistro(registro);
                }
            }
            
            // Si lo que digitó el usuario se encuentra dentro de la descripción entonces se agrega el registro
            if(evento === 'input' && formatearTexto(valorCampo).includes(valor)){
                if(tabla){
                    crearRegistro(registro);
                }
            }

            if(evento === 'modal' && valor === ''){
                objetoUniCampo.arrayDescripcion.push(formatearTexto(valorCampo));
            }

        });
    }

    // Guardamos todos los nombres de las categorias en un solo string para utilizarlo en el filtro del campo
    objetoUniCampo.stringDescripcion="";
    objetoUniCampo.arrayDescripcion.forEach(nombre => {
        objetoUniCampo.stringDescripcion += nombre.toString() + ",";
    });

    botonStatus();
}


function limpiarVariables(){
    objetoUniCampo.registrosAPI = "";
    objetoUniCampo.stringDescripcion = '';
    objetoUniCampo.llaves = [];
    objetoUniCampo.arrayDescripcion = [];
}

// limpia el campo y los mensajes de error
function limpiarCaja(){

    // Ocultamos y vaciamos los errores
    if (!labelSugr.className.includes('ocultar')){
        labelSugr.classList.add('ocultar');
        labelSugr.textContent = '';
    }

    if(!labelError.className.includes('ocultar')){
        labelError.classList.add('ocultar');
        labelError.textContent = '';
    }

    if(!iconoError.className.includes('ocultar')){
        iconoError.classList.add('ocultar');
    }

    iconoLimpiar.style.right = "0.5rem";
    
    mostrarRegistrosAPI();

    inputNombre.value='';
    inputNombre.focus();
}

function borrarFilas(){
    const filas = document.querySelectorAll('.tbody tr');
    filas.forEach( fila => {
        fila.remove();
    });
}

function crearRegistro(registro){
    
    const id =  Object.values(registro)[0]; //Obtenemos el valor del id
    const descripcion =  Object.values(registro)[1]; //Obtenemos el valor de la descripcion
    const estado =  Object.values(registro)[2]; //Obtenemos el valor del estado

    // Descripcion
    const spanDescripcion = document.createElement('SPAN');    
    spanDescripcion.classList.add('tbody__td--titulo');
    spanDescripcion.textContent = "Descripción: ";
    
    const textoDescripcion = document.createElement('SPAN');
    textoDescripcion.textContent = descripcion;

    const tdNombre = document.createElement('TD');
    tdNombre.dataset.idItem=id;
    tdNombre.appendChild(spanDescripcion);
    tdNombre.appendChild(textoDescripcion);

    // Estado
    const spanStatus = document.createElement('SPAN');
    spanStatus.classList.add('tbody__td--nombre');
    spanStatus.textContent="Estado: ";

    const formStatus = document.createElement('FORM');
    formStatus.classList.add('display-inline');
    formStatus.setAttribute('method', 'POST');

    const divCampoEstado = document.createElement('DIV');         
    divCampoEstado.classList.add('form__campo', 'check');

    const divContent = document.createElement('DIV');         
    divContent.classList.add('check__content');

    const divCheck = document.createElement('DIV');         
    divCheck.classList.add('check__estado');
    
    const lbSi = document.createElement('LABEL');         
    lbSi.classList.add('check__label');
    lbSi.textContent='Si';

    const lbNo = document.createElement('LABEL');         
    lbNo.classList.add('check__label');
    lbNo.textContent='No';   

    const inputHiddenDescripcion = document.createElement('INPUT'); 
    inputHiddenDescripcion.setAttribute('type', 'hidden');
    inputHiddenDescripcion.setAttribute('value', descripcion);

    const inputHiddenId = document.createElement('INPUT'); 
    inputHiddenId.setAttribute('type', 'hidden');
    inputHiddenId.setAttribute('value', id);

    const inputHiddenStatus = document.createElement('INPUT'); 
    inputHiddenStatus.setAttribute('type', 'hidden');

    if (estado==='E'){
        if (lbSi.className.includes('ocultar')){
            lbSi.classList.remove('ocultar');
        }
        if (!lbNo.className.includes('ocultar')){
            lbNo.classList.add('ocultar');
        }
        inputHiddenStatus.setAttribute('value', 'E');
        
    }else if (estado==='D'){
        
        if (!lbSi.className.includes('ocultar')){
            lbSi.classList.add('ocultar');
        }
        if (lbNo.className.includes('ocultar')){
            lbNo.classList.remove('ocultar');
        }
        
        inputHiddenStatus.setAttribute('value', 'D');

        divCheck.classList.add('inactivo');
        divContent.classList.add('inactivo');
    }

    divContent.appendChild(divCheck);
    divContent.appendChild(lbSi);
    divContent.appendChild(lbNo);
    divContent.appendChild(inputHiddenDescripcion);
    divContent.appendChild(inputHiddenId);
    divContent.appendChild(inputHiddenStatus);
    
    divCampoEstado.appendChild(divContent);
    
    formStatus.appendChild(divCampoEstado);

    const tdEstado = document.createElement('TD');
    tdEstado.classList.add('tbody__td--estado');
    tdEstado.appendChild(spanStatus);
    tdEstado.appendChild(formStatus);

    // Modificar------------------------------------------
    const spanModificar = document.createElement('SPAN');
    spanModificar.textContent="Editar";

    const imgModificar = document.createElement('IMG');
    imgModificar.setAttribute('src','/build/img/sistema/editar-ng.svg');
    imgModificar.setAttribute('alt','Imagen Editar');

    const linkModificar = document.createElement('A');
    linkModificar.setAttribute('href', `/${objetoUniCampo.entidad}/editar?id=${id}`);
    linkModificar.appendChild(imgModificar);
    linkModificar.appendChild(spanModificar);

    const tdModificar = document.createElement('TD');
    tdModificar.classList.add('tbody__td--icon');
    tdModificar.appendChild(linkModificar);

    // Eliminar------------------------------------------
    const spanEliminar = document.createElement('SPAN');
    spanEliminar.textContent="Eliminar";
    spanEliminar.dataset.idItem = id;
    spanEliminar.onclick = eliminarItem;

    const imgEliminar = document.createElement('IMG');
    imgEliminar.setAttribute('src','/build/img/sistema/eliminar.svg');
    imgEliminar.setAttribute('alt','Imagen Eliminar');
    imgEliminar.dataset.idItem = id;
    imgEliminar.onclick = eliminarItem;
    
    const linkEliminar = document.createElement('A');
    linkEliminar.setAttribute('href', '#');
    linkEliminar.appendChild(imgEliminar);
    linkEliminar.appendChild(spanEliminar);


    const tdEliminar = document.createElement('TD');
    tdEliminar.classList.add('tbody__td--icon');
    tdEliminar.appendChild(linkEliminar);
    
    // Historial
    const spanHistorial = document.createElement('SPAN');
    spanHistorial.textContent="Historial";

    const imgHistorial = document.createElement('IMG');
    imgHistorial.setAttribute('src','/build/img/sistema/historial.svg');
    imgHistorial.setAttribute('alt','Imagen Historial');

    const linkHistorial = document.createElement('A');
    linkHistorial.setAttribute('href', `/historial?id=${id}`);
    linkHistorial.appendChild(imgHistorial);
    linkHistorial.appendChild(spanHistorial);

    const tdHistorial = document.createElement('TD');
    tdHistorial.classList.add('tbody__td--icon');
    tdHistorial.appendChild(linkHistorial);

    // 5. Creamos el <tr> donde irá todo el contenido creado anteriormente
    const tr = document.createElement('TR');
    tr.appendChild(tdNombre);
    tr.appendChild(tdEstado);
    tr.appendChild(tdModificar);
    tr.appendChild(tdHistorial);
    tr.appendChild(tdEliminar);

    // Por último se muestra en el HTML
    document.querySelector('.tbody').appendChild(tr);

}

function eliminarItem(e){
    confirmarEliminacion(e.target.dataset.idItem );
}

function confirmarEliminacion(id){

    Swal.fire({
        title: '¿Confirma que desea eliminar este registro?',
        text: "Después de eliminado no se puede recuperar",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '##3085d6',
        confirmButtonText: 'Si, eliminar'

    }).then((result) => {

        if (result.isConfirmed) {

            let respuesta = postElemento('Eliminar', id );
            respuesta.then(result => {

                if (result === true) {

                    Swal.fire(
                        'Registro eliminado!',
                        'El registro ha sido eliminado satisfactoriamente',
                        'success'
                    )

                    limpiarVariables();
                    limpiarCaja();
                    borrarFilas();
                    obtenerRegistrosAPI();
                    
                }else{
                    Swal.fire(
                        'Error de eliminación!',
                        'No fue posible eliminar este registro, posiblemente porque ya se encuentra indexada con otros registros',
                        'error'
                    )
                }
            });
        }
    })
}

// funciones asíncronas
async function obtenerRegistrosAPI(tabla = ''){

    try {
        
        const resultado = await fetch(rutaServidor + objetoUniCampo.entidad + "/api");
        if (tabla === '' ){
            // se cargaran los registros de la tabla principal
            objetoUniCampo.registrosAPI = await resultado.json();
            mostrarRegistrosAPI();
        }else{
            // Se cargarán los registros de la tabla incluida dentro de la otra tabla
            objetoUniCampo.registrosAPIEntidad = await resultado.json();
        }

        return true;

    } catch (error) {

        Swal.fire({
            icon:'error',
            title:'Error',
            text:error + '  "No fue posible cargar los registros de la base de datos"'
        });

        return false;

    }
}

// Se define como POST Elemento, porque es lo que ocurre en el POST
async function postElemento(proceso, id, nombre = '', valor = 0 ){

    //FormData es como el submit de los datos de un formulario HTML pero en JavaScript 
    const datos = new FormData();
    datos.append(objetoUniCampo.llaves[0], id);//Se agregan todos los datos con append
    datos.append(objetoUniCampo.llaves[1], nombre);
    datos.append(objetoUniCampo.llaves[2], valor);

    try {
        let direccion;
        if(proceso == 'Modificar'){
            direccion = rutaServidor + objetoUniCampo.entidad + '/estado';
        }else if (proceso == 'Eliminar'){
            direccion = rutaServidor + objetoUniCampo.entidad + '/eliminar';
        }
        // Petición hacia la API
        const respuesta = await fetch(direccion, {

            method: 'POST', //método por el que se envia la información al servidor
            body: datos //enviamos datos al servidor definidos anteriormente, solo se hace en el cuerpo de la petición

        });


        const resultado = await respuesta.json();

        if (proceso === 'Eliminar'){

            if(resultado === true){
                return true;
            }else{
                return false;
            }
        }

               
        if (proceso === 'Modificar'){
            return resultado;
        }


    } catch (error) {

        if (proceso === 'Modificar'){
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un error al momento de cambiar el estado del registro',
                button: 'OK' 
            });

        }else if (proceso == 'Eliminar'){
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un error al momento de eliminar el registro',
                button: 'OK' 
            });
        }
    }
}


