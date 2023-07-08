import { mostrarOcultarSugerencias, rutaServidor, estadoBoton} from "./Parametros.js";

export let objetoRegistroGeneral;

export class Persona{

    constructor(objeto){
        this.entidad = objeto.entidad;
        this.nombreTabla = objeto.nombreTabla;
        this.registrosAPI = '';
        this.arrayAPI = objeto.arrayAPI;
        this.camposTabla = objeto.camposTabla;
        // this.fkId = objeto.fkId;
        // this.stringAPI = objeto.stringAPI;

        this.reglas='';
        this.sugerencias='';
        this.validarCampos = objeto.validarCampos;
        this.estadoCampos = objeto.estadoCampos;

        if(objeto.reglas){
            this.reglas = objeto.reglas;
            this.sugerencias = objeto.sugerencias;
        }
        
        if(objeto.boton){
            this.boton=objeto.boton;
        }
    }

    asignarValidacion(){
        
        this.validarCampos.forEach( idInput => {
            const inputForm = document.querySelector(`#${idInput}`); //Necesitamos obtener el tagname
            const campoCompleto = inputForm.parentElement;
            

            const labelSugerencia = campoCompleto.querySelector('.form__labelSugerencia');
            let expresionRegular='';
            
            let keysReglas = Object.keys(this.reglas);

            if (inputForm.tagName === 'INPUT'){

                inputForm.addEventListener('input', () => {
            
                    keysReglas.forEach( key => {
                        
                        if (key == idInput){
                            expresionRegular = inputForm.value.match(this.reglas[key]);
    
                            if(expresionRegular){
    
                                mostrarOcultarSugerencias(labelSugerencia, '', false);
    
                                if(this.boton){
                                    this.onOffBoton(idInput, true);
                                }
    
                            }else{
    
                                mostrarOcultarSugerencias(labelSugerencia, this.sugerencias[key], true);
                                
                                if(this.boton){
                                    this.onOffBoton(idInput, false);
                                }
                            }
                        }
                    });
                });
            }

            if (inputForm.tagName === 'SELECT'){

                inputForm.addEventListener('change', () => {

                    keysReglas.forEach( key => {
                        
                        if (key == idInput){

                            expresionRegular = inputForm.value.match(this.reglas[key]);
    
                            if(expresionRegular){
    
                                mostrarOcultarSugerencias(labelSugerencia, '', false);
    
                                if(this.boton){
                                    this.onOffBoton(idInput, true);
                                }
    
                            }else{
    
                                mostrarOcultarSugerencias(labelSugerencia, this.sugerencias[key], true);
                                
                                if(this.boton){
                                    this.onOffBoton(idInput, false);
                                }
                            }
                        }
                    });
                });
            }
        });
    }

    eliminarFilasTabla(){
        let tabla = document.querySelector(`table[data-tipo="${this.nombreTabla}"]`);
        let filas = tabla.querySelectorAll(".tbody > tr");
        
        if(filas){
            // Eliminando todas las filas
            filas.forEach( fila => {
                fila.remove();
            });
        }
    }

    // Metodo para obtener los registros y mostrarlo en las tablas del HTML
    obtenerRegistros(){

        Swal.fire({
            title: 'Cargando registros...',
            showConfirmButton: false,
            didOpen: () => {
              Swal.showLoading();
              let resultado = getRegistrosAPI(this.entidad);
              resultado.then((result) => {
                  if (result){
                    this.registrosAPI = result;
                    this.cargarDatos();
                      setTimeout(() => {
                          Swal.close();
                      }, 500);
                  }            
              }); 
            }
        });
    }

    cargarDatos(){

        let tabla = document.querySelector(`table[data-tipo="${this.nombreTabla}"]`);
        let llavesArray = Object.keys(this.arrayAPI);
        
        // Limpiamos los registros almacenados en cada array
        if(this.registrosAPI){
            llavesArray.forEach( key => {
                this.arrayAPI[key]='';
            });
        }
        
        // 
        this.registrosAPI.forEach( registro => {
            
            llavesArray.forEach( key => {
                this.arrayAPI[key] = [...this.arrayAPI[key], registro[key]];
            });

            if (tabla){
                this.crearFilaTabla(registro);
            }
        });
    }

    crearFilaTabla(registro){
            
        // Obtenemos las llaves del objeto
        let llaves = Object.keys(registro);
        let id = registro[llaves[0]];
                        
        let arrayTD = [];

        llaves.forEach( (llave, index) => {

            // Verificamos que el index del registro exista en la tabla
            if(this.camposTabla[index]){

                const nombreCampo = Object.values(this.camposTabla[index])[0];
                const posicion = Object.values(this.camposTabla[index])[1];
                const clases = Object.values(this.camposTabla[index])[2];
                const td = document.createElement('TD');
                
                // Solo se crean los elementos que se van a mostrar en la tabla
                if (posicion !== null){
                    
                    const span = document.createElement('SPAN');
                    span.classList.add('tbody__td--titulo');
                    span.textContent = nombreCampo;
                    
                    const texto = document.createElement('SPAN');
                    
                    if(llave.includes('Status')){
                    
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
        
                        const inputHiddenId = document.createElement('INPUT'); 
                        inputHiddenId.setAttribute('type', 'hidden');
                        inputHiddenId.setAttribute('value', id);
        
                        const inputHiddenStatus = document.createElement('INPUT'); 
                        inputHiddenStatus.setAttribute('type', 'hidden');
        
                        if(registro[llave] === 'E'){
        
                            if (lbSi.className.includes('ocultar')){
                                lbSi.classList.remove('ocultar');
                            }
                            if (!lbNo.className.includes('ocultar')){
                                lbNo.classList.add('ocultar');
                            }
                            inputHiddenStatus.setAttribute('value', 'E');
                            
                        }else{
                            
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
                        divContent.appendChild(inputHiddenId);
                        divContent.appendChild(inputHiddenStatus);
                        
                        divCampoEstado.appendChild(divContent);
                        
                        formStatus.appendChild(divCampoEstado);
        
                        td.appendChild(span);
                        td.appendChild(formStatus);
        
                    }else{

                        texto.textContent = registro[llave];
                        td.appendChild(span);
                        td.appendChild(texto);
                        
                        if(clases.length !== 0){
                            clases.forEach( clase => {
                                td.classList.add(clase);
                            });
                        }
                    }

                    arrayTD[posicion] = td;
                }
            }    
        });

        // Creamos la celda de modificar el registro
        const spanModificar = document.createElement('SPAN');
        spanModificar.textContent="Editar";

        const imgModificar = document.createElement('IMG');
        imgModificar.setAttribute('src','/build/img/sistema/editar-ng.svg');
        imgModificar.setAttribute('alt','Imagen Editar');

        const linkModificar = document.createElement('A');
        linkModificar.setAttribute('href', `${rutaServidor}${this.entidad}/editar?id=${id}`);
        linkModificar.appendChild(imgModificar);
        linkModificar.appendChild(spanModificar);

        const tdModificar = document.createElement('TD');
        tdModificar.classList.add('tbody__td--icon');
        tdModificar.appendChild(linkModificar);

        //-Historial---------------------------------------------
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

        // Eliminar-----------------------------------------------
        const spanEliminar = document.createElement('SPAN');
        spanEliminar.textContent="Eliminar";
        spanEliminar.dataset.idRegistro = id;
        spanEliminar.dataset.entidad = this.entidad;
        spanEliminar.onclick=confirmarEliminacion;
    
        const imgEliminar = document.createElement('IMG');
        imgEliminar.setAttribute('src','/build/img/sistema/eliminar.svg');
        imgEliminar.setAttribute('alt','Imagen Eliminar');
        imgEliminar.dataset.idRegistro = id;
        imgEliminar.dataset.entidad = this.entidad;
        imgEliminar.onclick=confirmarEliminacion;
        
        const linkEliminar = document.createElement('A');
        linkEliminar.setAttribute('href', '#');
        linkEliminar.appendChild(imgEliminar);
        linkEliminar.appendChild(spanEliminar);
    
        const tdEliminar = document.createElement('TD');
        tdEliminar.classList.add('tbody__td--icon');
        tdEliminar.appendChild(linkEliminar);
        // --------------------------------------------------------

        const tr = document.createElement('TR');

        arrayTD.forEach( td => {
            tr.appendChild(td);
        });

        tr.appendChild(tdModificar);
        tr.appendChild(tdHistorial);
        tr.appendChild(tdEliminar);

        document.querySelector(`table[data-tipo="${this.nombreTabla}"] .tbody`).appendChild(tr);
    }

    // Método para 
    getFiles(id){
        let resultado = getRegistrosAPI(id, this.entidad);
        resultado.then(result => {
            // Devuelve el arreglo de la api
            this.registrosAPI = result;
            this.listarFiles();
        });
    }

    listarFiles(){

        // si existen registros
        if (this.registrosAPI != ''){

            let llaves = '';
            
            llaves = Object.keys(this.registrosAPI[0]);

            this.registrosAPI.forEach(registro => {
                
                let id = registro[llaves[0]];
                let archivo = registro[llaves[1]].split('.');
                let nombre = archivo[0];
                let extension = archivo[1];
                
                let imgMain = document.createElement('IMG');

                if (extension === 'mp4'){
                    imgMain.setAttribute('src',`/build/img/sistema/iconoVideo-ng.svg`);
                    imgMain.style.width="150px";
                    imgMain.style.height="100px";
                }else{
                    imgMain.setAttribute('src',`/build/img/productos/${nombre}-opt.${extension}`);    
                }

                imgMain.setAttribute('alt','Archivo multimedia del producto');
                imgMain.dataset.idArchivo = id;
                imgMain.dataset.nombre = nombre;
                imgMain.dataset.extension = extension;
                imgMain.onclick=mostrarArchivo;
                
                let iconoEye = document.createElement('IMG');
                iconoEye.setAttribute('src','/build/img/sistema/eye.svg');
                iconoEye.setAttribute('alt','Icono para ampliar imagen o reproducir video');
                iconoEye.dataset.idRegistro = id;
                iconoEye.dataset.nombre = nombre;
                iconoEye.dataset.extension = extension;
                iconoEye.onclick=mostrarArchivo;

                let iconoEliminar = document.createElement('IMG');
                iconoEliminar.setAttribute('src','/build/img/sistema/eliminar-bl.svg');
                iconoEliminar.setAttribute('alt','Icono para eliminar archivo');
                iconoEliminar.dataset.idRegistro = id;
                iconoEliminar.dataset.tipo = 'archivo';
                iconoEliminar.dataset.entidad = this.entidad;
                iconoEliminar.onclick=confirmarEliminacion;

                let divContentAcciones = document.createElement('DIV');
                divContentAcciones.appendChild(iconoEye);
                divContentAcciones.appendChild(iconoEliminar);
                divContentAcciones.classList.add('file__content__acciones');

                let divContentItem = document.createElement('DIV');
                divContentItem.appendChild(imgMain);
                divContentItem.appendChild(divContentAcciones);
                
                if (extension === 'mp4'){
                    divContentItem.style.border="1px solid #000000";
                    divContentItem.style.padding='2rem';
                }

                divContentItem.classList.add('file__content__item');

                let fileContent = document.querySelector('.file__content');
                fileContent.appendChild(divContentItem);
            });
        }
    }

    onOffBoton(tipo, estado){
        
        let cntEstado = 0;
        if (Object.keys(this.estadoCampos).includes(tipo)){
            this.estadoCampos[tipo]=estado;
        }
    
        for (let llave in this.estadoCampos){
            if(this.estadoCampos[llave] == false){
                cntEstado=0;
            }else{
                cntEstado++;
            }
        }
        
        if(cntEstado == 8){

            estadoBoton(this.boton, true);
        }else{
            estadoBoton(this.boton, false);
        }
    
        cntEstado=0;
    }

    guardarRegistro(objeto, fkKey){
        
        Swal.fire({
            title: 'Guardando registro...',
            showConfirmButton: false,
            didOpen: () => {
              Swal.showLoading();
              let resultado = guardar( objeto, this.entidad);

              resultado.then((result) => {

                if (result){

                    this.eliminarFilasTabla();
                    this.obtenerRegistros(fkKey);

                    // Cerramos esta ventana emergente
                    setTimeout(() => {
                        Swal.close();
                    }, 500);

                }else{
                    // Mantenemos esta ventana emergente
                    Swal.fire(
                        'Error!',
                        'No fue posible guardar este registro, posiblemente ya se encuentre registrado',
                        'error'
                    )
                }


              }); 
            }
        });
    }

    botonLimpiar(campos){
        campos.forEach( campo => {
            const txtCampo = document.querySelector(`#${campo}`);
            const btnLimpiar = txtCampo.parentElement.querySelector('.form__limpiar');
            btnLimpiar.addEventListener('click', () => {
                // Vaciamos el campo
                txtCampo.value = '';

                // Cambiamos el estado del campo a false
                this.onOffBoton(campo, false);
            });
        });
    }

}

// Se coloca como una función aparte, ya que NO tiene acceso a las propiedades del objeto
function confirmarEliminacion(e){

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

            let respuesta = eliminarAPI(e.target.dataset.idRegistro, e.target.dataset.entidad);
            respuesta.then(result => {
                
                if (result === true) {

                    Swal.fire(
                        'Registro eliminado!',
                        'El registro ha sido eliminado satisfactoriamente',
                        'success'
                    )

                    // Si es un archivo procedemos a eliminar el item del archivo
                    if (e.target.dataset.tipo === 'archivo'){
                        // Eliminamos el archivo
                        e.target.parentElement.parentElement.remove();

                    }else{
                        // Si no.... quiere decir que corresponde al icono eliminar de un registro de una tabla
                        let tr = e.target.parentElement.parentElement.parentElement;
                        if (tr){
                            tr.remove();
                        }
                    }
                    
                }else{
                    Swal.fire(
                        'Error de eliminación!',
                        'No fue posible eliminar este registro, posiblemente porque ya se encuentra indexada con otros registros',
                        'error'
                    );
                }
            });
        }
    });
}

async function eliminarAPI(id, entidad ){

    //FormData es como el submit de los datos de un formulario HTML pero en JavaScript 
    const datos = new FormData();
    datos.append('id', id);

    try {
        let direccion;
        direccion = rutaServidor + entidad + '/eliminar';
        
        // Petición hacia la API
        const respuesta = await fetch(direccion, {
            
            method: 'POST', //método por el que se envia la información al servidor
            body: datos //enviamos datos al servidor definidos anteriormente, solo se hace en el cuerpo de la petición
            
        });
        
        const resultado = await respuesta.json();

        if(resultado === true){
            return true;
        }else{
            return false;
        }
        
    } catch (error) {

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al momento de eliminar el registro',
            button: 'OK' 
        });
    }
}

async function getRegistrosAPI(entidad){

    try {

        let resultado = await fetch(rutaServidor + entidad + '/api');
        let registrosAPI = await resultado.json();

        return registrosAPI;

    } catch (error) {
        Swal.fire({
            icon:'error',
            title:'Error',
            text:error + ' No fue posible cargar los registros de la base de datos'
        });
        return false;
    }
}

export function cerrarPreview(){

    const contenedorPreview = document.querySelector('.preview');
    const cerrarVentana = document.querySelector('.cerrar-preview');

    if (cerrarVentana){
        
        cerrarVentana.addEventListener('click', () =>{
            let img = document.querySelector('.preview > img');
            let video = document.querySelector('.preview > video');

            if (img){
                img.remove();
            }else if(video){
                video.remove();
            }

            contenedorPreview.classList.add('ocultar');

        });
    }
}

function mostrarArchivo(e){

    const idArchivo = e.target.getAttribute('data-id-archivo');
    const nombre = e.target.getAttribute('data-nombre');
    const extension = e.target.getAttribute('data-extension');
   
    const fondoNotificacion = document.querySelector('.preview');

    let iconoEliminar = fondoNotificacion.querySelector('.eliminar-imagen > img');
    iconoEliminar.setAttribute('data-id-archivo', idArchivo);

    let elemento;

    if (e.target.getAttribute('data-extension') === 'mp4'){

        elemento = document.createElement('VIDEO');
        elemento.setAttribute('src', '/build/img/productos/' + nombre + '.' + extension);
        elemento.setAttribute('controls','');

    }else{
        
        elemento = document.createElement('IMG');
        elemento.setAttribute('src', '/build/img/productos/' + nombre + '.' + extension);

    }

    // Verificamos si existe una imagen o video previo para eliminarlo 
    const imgPrevio = document.querySelector('.preview > img');
    const videoPrevio = document.querySelector('.preview > video');

    if(imgPrevio){
        imgPrevio.remove();
    }else if (videoPrevio){
        videoPrevio.remove();
    }

    // Procedemos a insertar la imagen o video en el contenedor
    fondoNotificacion.insertBefore(elemento, fondoNotificacion.querySelector('.eliminar-imagen'));
    fondoNotificacion.classList.remove('ocultar');
    
}

// Se define como POST Elemento, porque es lo que ocurre en el POST
async function guardar(objeto, entidad){

    //FormData es como el submit de los datos de un formulario HTML pero en JavaScript
    const formulario = new FormData();
    
    for (const clave in objeto){
        formulario.append(clave, objeto[clave]);
    }

    try {
        let direccion = rutaServidor + entidad + '/api';
        // Petición hacia la API
        const respuesta = await fetch(direccion, {
            method: 'POST', //método por el que se envia la información al servidor
            body: formulario //enviamos datos al servidor definidos anteriormente, solo se hace en el cuerpo de la petición
        });

        const resultado = await respuesta.json();
        if (resultado){

            console.log(resultado);
            return resultado;
        }else{
            console.log(resultado);
            return false;
        }

    } catch (error) {

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al momento de guardar el registro',
            button: 'OK' 
        });
    }
}
