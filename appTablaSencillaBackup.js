// Variables Globales
let registrosAPI;
let arrayDescripcion=new Set();
let stringDescripcion='';
let llaves = []; //almacena las llaves del nombre de los campos de la base de datos
let url='http://192.168.18.120:3000/categoria';
const expresion="^[A-Z0-9Ña-züñáéíóúÁÉÍÓÚÜ'° ]{4,50}$";
const msg="Solo debe contener letras, mayor a 4 caracteres";

// Selectores de campos
const inputNombre = document.querySelector('[data-tipo="nombre"]');
const labelSugr = document.querySelector('.form__labelSugerencia');
const labelError = document.querySelector('.form__labelError');
const iconoError = document.querySelector('.form__iconError');
const iconoLimpiar = document.querySelector('.form__limpiar');
const btnSubmitCampo = document.querySelector('[data-button="btn-envio"]');

class UITablaSencilla{

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
            alertaErrorCampo(true);
    
            setTimeout(() => {
                this.borrarFilas();
                this.mostrarRegistrosAPI('input', formatearTexto(inputNombre.value));
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

    mostrarRegistrosAPI(evento = 'DOM', valor = ''){

        const tabla = document.querySelector('.table');
        
        if (registrosAPI){
            
            registrosAPI.forEach( registro => {
                
                if (llaves.length === 0){
                    llaves = Object.keys(registro);
                }
                // Obtenemos el valor que se encuentra en la segunda posición del objeto mediante el object values retorna un arreglo y accedemos a la segunda posición
                const valorCampo  = Object.values(registro)[1];
                
                if(evento === 'DOM' && valor === ''){
                    arrayDescripcion.add(formatearTexto(valorCampo));
                    if (tabla){
                        this.crearRegistro(registro);
                    }
                }
                
                // Si lo que digitó el usuario se encuentra dentro de la descripción entonces se agrega el registro
                if(evento === 'input' && formatearTexto(valorCampo).includes(valor)){
                    if(tabla){
                        this.crearRegistro(registro);
                    }
                }

            });
        }
    
        // Guardamos todos los nombres de las categorias en un solo string para utilizarlo en el filtro del campo
        stringDescripcion="";
        arrayDescripcion.forEach(categoria => {
            stringDescripcion += categoria.toString() + ",";
        });

        botonStatus();
    }

    borrarFilas(){
        const filas = document.querySelectorAll('.tbody tr');
        filas.forEach( fila => {
            fila.remove();
        });
    }

    crearRegistro(registro){
        
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
        spanModificar.textContent="Modificar";

        const imgModificar = document.createElement('IMG');
        imgModificar.setAttribute('src','/build/img/sistema/editar-ng.svg');
        imgModificar.setAttribute('alt','Imagen Editar');

        const linkModificar = document.createElement('A');
        linkModificar.setAttribute('href', `/categoria/editar?id=${id}`);
        linkModificar.appendChild(imgModificar);
        linkModificar.appendChild(spanModificar);

        const tdModificar = document.createElement('TD');
        tdModificar.classList.add('tbody__td--icon');
        tdModificar.appendChild(linkModificar);

        // Eliminar------------------------------------------
        const spanEliminar = document.createElement('SPAN');
        spanEliminar.textContent="Eliminar";
        spanEliminar.dataset.id = id;
        spanEliminar.onclick = eliminarItem;

        const imgEliminar = document.createElement('IMG');
        imgEliminar.setAttribute('src','/build/img/sistema/eliminar.svg');
        imgEliminar.setAttribute('alt','Imagen Eliminar');
        imgEliminar.dataset.id = id;
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
        tr.appendChild(tdEliminar);
        tr.appendChild(tdHistorial);
    
        // Por último se muestra en el HTML
        document.querySelector('.tbody').appendChild(tr);
    
    }
}

const uiCampo = new UITablaSencilla();
uiCampo.managerAlert();
uiCampo.verificarMensajeGeneral();
eventListeners();
botonStatus();
estadoBoton();
botonLimpiar();

async function obtenerRegistrosAPI(){
    try {
        const resultado = await fetch(url + "/api");
        registrosAPI = await resultado.json();
        uiCampo.mostrarRegistrosAPI();
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

// Eventos
function eventListeners(){

    if(inputNombre){
        // Verificamos si la ruta corresponde a editar
        if (window.location.pathname.includes('/editar')){
            inputNombre.addEventListener('input', rutaEditar);
        }else{
            inputNombre.addEventListener('input', rutaPrincipal);
        }
    }
}

// Funciones
function rutaEditar(e){
    if(e.target.value.match(expresion)){
        mostrarOcultarSugerencias(labelSugr, '', false);
        estadoBoton(true);
    }else{
        mostrarOcultarSugerencias(labelSugr, msg, true);
        estadoBoton(false);
    }

    if(labelError && labelError.textContent != ''){
        // Se oculta el error porque el usuario empieza a digitar
        alertaErrorCampo(false);
    }
}

function rutaPrincipal(e){
    let expresionRegular;
    expresionRegular = e.target.value.match(expresion);
    let stringFormateado = formatearTexto(e.target.value);
    let existeRegistro = arrayDescripcion.has(stringFormateado);

    if(expresionRegular && !existeRegistro){
        mostrarOcultarSugerencias('', false);
        estadoBoton(true);
        
    }else if(!expresionRegular && !existeRegistro){
        mostrarOcultarSugerencias(msg, true);
        estadoBoton(false);
        
    }else if((!expresionRegular && existeRegistro)||(expresionRegular && existeRegistro)){
        mostrarOcultarSugerencias('Esta categoría ya se encuentra registrada', true);
        estadoBoton(false);
    }

    let coincidencia = stringDescripcion.includes(stringFormateado);

    if(coincidencia){
        uiCampo.borrarFilas();
        uiCampo.mostrarRegistrosAPI('input', stringFormateado);
    }else{
        uiCampo.borrarFilas();
        uiCampo.mostrarRegistrosAPI();
    }

    if(labelError && labelError.textContent != ''){
       // Se oculta el error porque el usuario empieza a digitar
        alertaErrorCampo(false);
    }
}

// Esta función se dispara cuando se encuentran errores provenientes del backend
function alertaErrorCampo(estado){
    if (estado === true){
        labelError.classList.remove('ocultar');
        iconoError.classList.remove('ocultar');
        iconoLimpiar.style.right = "3rem";
    }else{
        labelError.textContent='';
        labelError.classList.add('ocultar');
        iconoError.classList.add('ocultar');
        iconoLimpiar.style.right = "0.5rem";
    }
}

function mostrarOcultarSugerencias(msg, estado){
    if (estado){
        labelSugr.classList.remove('ocultar');
        // labelSugr.textContent=msg;
    }else{
        labelSugr.classList.add('ocultar');
    }
    labelSugr.textContent=msg;
}

function estadoBoton(estado = false){
    if (estado){
        btnSubmitCampo.classList.remove('disabled');
        btnSubmitCampo.removeAttribute('disabled');                                
    }else{
        btnSubmitCampo.classList.add('disabled');
        btnSubmitCampo.setAttribute('disabled', '');                                
    }
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

// limpia el campo de categoria
function botonLimpiar(){
    const iconoLimpiar = document.querySelector('.form__limpiar');
    iconoLimpiar.addEventListener('click', (e) => {
        e.preventDefault();
        uiCampo.borrarFilas();
        limpiarCaja();
    });
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
    
    uiCampo.mostrarRegistrosAPI();

    inputNombre.value='';
    inputNombre.focus();
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
                postElemento('Modificar', id, nombre, valor.value, );
            });
        });
    }
}

function eliminarItem(e){
    confirmarEliminacion(e.target.dataset.id );
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
                    
                    uiCampo.borrarFilas();
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

// Se define como POST Elemento, porque es lo que ocurre en el POST
async function postElemento(proceso, id, nombre = '', valor = 0 ){

    //FormData es como el submit de los datos de un formulario HTML pero en JavaScript 
    const datos = new FormData();
    datos.append(llaves[0], id);//Se agregan todos los datos con append
    datos.append(llaves[1], nombre);
    datos.append(llaves[2], valor);

    try {
        let direccion;
        if(proceso == 'Modificar'){
            direccion = url + '/estado';
        }else if (proceso == 'Eliminar'){
            direccion = url + '/eliminar';
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