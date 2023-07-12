import { mostrarOcultarSugerencias, ocultarError, rutaServidor, estadoBoton, limpiarCaja} from "./Parametros.js";

export let objetoRegistroGeneral;

export class Persona{

    constructor(objeto){
        this.entidad = objeto.entidad;
        this.nombreTabla = objeto.nombreTabla;
        this.registrosAPI = '';
        this.arrayAPI = objeto.arrayAPI;
        this.camposTabla = objeto.camposTabla;
        this.stringAPI = objeto.stringAPI;
        this.convertirMayuscula = objeto.convertirMayuscula;
        this.convertirMinuscula = objeto.convertirMinuscula;
        this.existe = objeto.existe;

        // Id adicionales en la ruta GET para pasarlo por parámetro para MODIFICAR el registro
        this.idAdd = objeto.idAdd;
        this.adicionesId = '';

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
            const labelError = campoCompleto.querySelector('.form__labelError');
            const iconoError = campoCompleto.querySelector('.form__iconError');

            let expresionRegular='';
            
            let keysReglas = Object.keys(this.reglas);

            if (inputForm.tagName === 'INPUT'){

                inputForm.addEventListener('input', e => {3

                    // Convertir a mayusculas
                    if(this.convertirMayuscula){
                        this.convertirMayuscula.forEach(inputField => {
                            if (inputField == idInput){
                                inputForm.value = e.target.value.toUpperCase();
                            }
                        });
                    }
                    // Convertir a mayusculas
                    if(this.convertirMinuscula){
                        this.convertirMinuscula.forEach(inputField => {
                            if (inputField == idInput){
                                inputForm.value = e.target.value.toLowerCase();
                            }
                        });
                    }
                    
                    keysReglas.forEach( key => {
                        
                        if (key == idInput){
                            expresionRegular = inputForm.value.match(this.reglas[key]);
    
                            if(expresionRegular){
                                // Se coloca este codigo en la parte superior porque no se está evaluando cuando el campo no se encuentra como un campo de filtro
                                mostrarOcultarSugerencias(labelSugerencia, '', false);
                                this.estadoCampo(idInput, true);
    
                                if (this.stringAPI[key]){

                                    // Filtrar en la tabla de datos
                                    if(this.stringAPI[key].includes(e.target.value)){

                                        // Verificamos si conincide de forma total con el registro de la base de datos
                                        if(this.arrayAPI[key].includes(e.target.value) && this.existe.includes(key)){
                                            mostrarOcultarSugerencias(labelSugerencia, 'Este registro ya existe', true);
                                            this.estadoCampo(idInput, false);
        
                                        }else{
                                            mostrarOcultarSugerencias(labelSugerencia, '', false);
                                            this.estadoCampo(idInput, true);
                                        }
    
                                        // Reseteamos los datos de la tabla
                                        this.eliminarFilasTabla();
                                        this.cargarDatos(key, e.target.value);
    
                                    }else{
                                        mostrarOcultarSugerencias(labelSugerencia, '', false);
                                        this.eliminarFilasTabla();
                                    }
                                }
                                                                
                            }else{
    
                                mostrarOcultarSugerencias(labelSugerencia, this.sugerencias[key], true);
                                this.estadoCampo(idInput, false);
                                
                            }

                            if (e.target.value == ''){
                                this.eliminarFilasTabla();
                                this.cargarDatos();
                            }

                            if(labelError && labelError.textContent != ''){
                                ocultarError(labelError, iconoError);
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
                                this.estadoCampo(idInput, true);

                                if(this.arrayAPI[key].includes(inputForm.value)){
                                        // Reseteamos los datos de la tabla
                                        this.eliminarFilasTabla();
                                        this.cargarDatos(key, inputForm.value);
                                }else{
                                        this.eliminarFilasTabla();
                                }
                            }else{
                                mostrarOcultarSugerencias(labelSugerencia, this.sugerencias[key], true);
                                this.estadoCampo(idInput, false);
                            }

                            if(labelError && labelError.textContent != ''){
                                ocultarError(labelError, iconoError);
                            }
                        }
                    });

                    if (inputForm.value == ''){
                        this.eliminarFilasTabla();
                        this.cargarDatos();
                    }

                });
            }


        });
    }

    eliminarFilasTabla(){
        let tabla = document.querySelector(`table[data-tipo="${this.nombreTabla}"]`);
        let filas = '';

        if(tabla){

            filas = tabla.querySelectorAll(".tbody > tr");

            if(filas){
                // Eliminando todas las filas
                filas.forEach( fila => {
                    fila.remove();
                });
            }
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
                    // console.log(this.registrosAPI);
                    this.cargarDatos();
                    this.estadoRegistro();
                      setTimeout(() => {
                          Swal.close();
                      }, 500);
                  }            
              }); 
            }
        });
    }

    cargarDatos( campo = '', valor = ''){

        let tabla = document.querySelector(`table[data-tipo="${this.nombreTabla}"]`);
        let llavesArray = Object.keys(this.arrayAPI);
        
        if(campo == ''){

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
            
            // pasamos el array a una cadena string
            llavesArray.forEach(key => {
                this.stringAPI[key] = this.arrayAPI[key].toString();
            });    
        }else{
            if (valor != ''){
                this.registrosAPI.forEach( registro => {
                    if(registro[campo].includes(valor)){
                        if (tabla){
                            this.crearFilaTabla(registro);
                        }
                    }
                });
            } 
        }

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

                this.idAdd.forEach( idCampo => {
                    if (idCampo == llave){
                        this.adicionesId = this.adicionesId + '&' + idCampo + "=" + registro[idCampo]; 
                    }
                });
                
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
        linkModificar.setAttribute('href', `${rutaServidor}${this.entidad}/editar?id=${id}${this.adicionesId}`);
        linkModificar.appendChild(imgModificar);
        linkModificar.appendChild(spanModificar);

        let ultimo = this.idAdd.length - 1;
        if (this.adicionesId.includes(this.idAdd[ultimo])){
            this.adicionesId='';
        }

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

    estadoCampo(tipo, estado){
        
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
        
        if(cntEstado == this.validarCampos.length){

            estadoBoton(this.boton, true);
        }else{
            estadoBoton(this.boton, false);
        }
    
        cntEstado=0;
    }

    estadoAllCampos(){
        // console.log(this.estadoCampos);
        
        for (let llave in this.estadoCampos){
            
            const input = document.querySelector(`#${llave}`);
            const campo = input.parentElement;
            const lbsugr = campo.querySelector('.form__labelSugerencia');
            const lberror = campo.querySelector('.form__labelError');

            if (input.value != '' && lbsugr.textContent == '' && lberror.textContent == '' ){
                this.estadoCampos[llave]=true;
            }
        }
    }

    botonLimpiar() {

        const btnLimpiar = document.querySelectorAll('.form__limpiar');
    
        btnLimpiar.forEach(boton => {
    
            boton.addEventListener('click', (e) => {
                
                let campo = boton.parentElement;
    
                e.preventDefault();
                // limpiarmos la caja del texto y los mensajes
                limpiarCaja(campo);
                
                // cambiamos el estado a false del campo borrado
                let cajaInput = campo.querySelector('input');

                this.estadoCampo(cajaInput.id, false)

                // Obtenemos los campos por los cualse se hace el filtro
                let arrayKeys = Object.keys(this.arrayAPI);
                // validamos si los campos del filtro están vacios
                
                let cnt = false;
                arrayKeys.forEach( campo => {
                    const inputText = document.querySelector(`#${campo}`);
                    
                    if (inputText.value != '' ){
                        
                        cnt = true;

                        if(this.stringAPI[campo].includes(inputText.value)){
                            this.eliminarFilasTabla();
                            this.cargarDatos(campo, inputText.value);
                        }else{
                            this.eliminarFilasTabla();
                        }
                    }

                });

                if (!cnt){
                    this.eliminarFilasTabla();
                    this.cargarDatos();
                }
            });
        });
    }

    estadoRegistro(){
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
                    let inputId = labelNo.nextElementSibling;
                    let inputStatus = inputId.nextElementSibling;

                    if(boton.className.includes('inactivo')){

                        boton.classList.remove('inactivo');
                        labelSi.classList.remove('ocultar');
                        fondoBtn.classList.remove('inactivo');
                        labelNo.classList.add('ocultar');
                        inputStatus.setAttribute('value','E');

                    }else{

                        boton.classList.add('inactivo');
                        labelSi.classList.add('ocultar');
                        fondoBtn.classList.add('inactivo');
                        labelNo.classList.remove('ocultar');
                        inputStatus.setAttribute('value','D');

                    }

                    // Una vez asignado el inputStatus se envia por el POST
                    let resultado = cambiarEstado(this.entidad, inputId.value, inputStatus.value );

                    resultado.then(result => {
                        // console.log(result);
                        if (result){
                            // como el resultado fue satisfactorio, procedemos a cambiar el estado en el arreglo de objetos cargado en memoria
                            this.registrosAPI.forEach( registro => {

                                if(Object.values(registro)[0] == inputId.value){

                                    // Accedemos a la última posicion del arreglo donde se encuentra almacenado el status
                                    let posicion = Object.keys(registro).length - 1;
                                    let campos = Object.keys(registro);
                                    registro[campos[posicion]]=inputStatus.value;
                                }
                            });
                        }
                    });
                });
            });
        }
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
        let direccion = rutaServidor + entidad + '/eliminar';
        
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

// Se define como POST Elemento, porque es lo que ocurre en el POST
export async function cambiarEstado(entidad, id, valor){

    //FormData es como el submit de los datos de un formulario HTML pero en JavaScript 
    const datos = new FormData();
    datos.append('id', id);//Se agregan todos los datos con append
    datos.append('valor', valor);

    try {
        let direccion = rutaServidor + entidad + '/estado';
        console.log(direccion);
        // Petición hacia la API
        const respuesta = await fetch(direccion, {
            method: 'POST', //método por el que se envia la información al servidor
            body: datos //enviamos datos al servidor definidos anteriormente, solo se hace en el cuerpo de la petición
        });

        const resultado = await respuesta.json();
        return resultado;

    } catch (error) {

        if (proceso === 'estado'){
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un error al momento de cambiar el estado del registro',
                button: 'OK' 
            });

        }else if (proceso == 'eliminar'){
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un error al momento de eliminar el registro',
                button: 'OK' 
            });
        }
    }
}

