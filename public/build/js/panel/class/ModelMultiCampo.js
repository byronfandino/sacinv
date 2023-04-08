
// Guarda los registros de la tabla y de los combobox
export let globalVariables = {

    url : '',
    entidad : '',
    registrosAPI : '',
    camposIndividuales : [],
    tabla:[],
    
    // Se guarda en un array
    arrayDescripcion : new Array(),
    arrayCodigoBarras : new Array(),
    arrayCodigoManual : new Array(),
    stringDescripcion : '',
    stringCodigoBarras : '',
    stringCodigoManual : '',
    cntEstado:0
}

// Se personalizan las propiedades de los objetos
export let objeto = {};

// Se personalizan las reglas de cada campo
export let reglas = {};

// Se personalizan las reglas de cada campo
export let sugerencias = {};

// Valida las sugerencias y los errores
export function asignarValidacion(){

    const campos = document.querySelectorAll('.form__campo');
    // const btnEliminar = document.querySelector('.eliminar-imagen');

    campos.forEach(campo => {

        const input = campo.querySelector('input:not([data-tipo="nombre"])');
        const select = campo.querySelector('select');
        const labelSugr = campo.querySelector('.form__labelSugerencia');
        const labelError = campo.querySelector('.form__labelError');
        const iconoError = campo.querySelector('.form__iconError');
        
        console.log(input);

        let tipo;
        let regla;
        let msg;
        let expresionRegular;

        //Aquí se valida únicamente los inputs
        if(input){

            tipo = input.getAttribute('data-tipo');
            
            // Si el campo NO corresponde al que se encuentra guardado en las excepciones
            if(!globalVariables.camposIndividuales.includes(tipo)){
 
                regla = reglaInput(tipo);
                msg = mensajeSugerencia(tipo);

                // Evento de carga de formulario---------
                expresionRegular = input.value.match(regla);

                if(expresionRegular){
                    estadoCampo(tipo, true);
                }else{
                    estadoCampo(tipo, false);
                }

                //---TERMINA LA EVALUACIÓN DE LOS CAMPOS
                input.addEventListener('input', e => {
                    expresionRegular = e.target.value.match(regla);
                    if(expresionRegular){
                        estadoCampo(tipo, true);
                        mostrarOcultarSugerencias(labelSugr, '', false);
                    }else{
                        estadoCampo(tipo, false);
                        mostrarOcultarSugerencias(labelSugr, msg, true);
                    }
                    if(labelError && labelError.textContent != ''){
                        ocultarError(labelError, iconoError);
                    }
                });
            }
            // evento de cambios
        }//Fin de las acciones de los inputs

        if(select){
            tipo = select.getAttribute('data-tipo');
            msg = mensajeSugerencia(tipo);

            // Se coloca esta condición para activar el botón cuando carga el formulario
            if(select.value != ''){
                estadoCampo(tipo, true);
            }

            select.addEventListener('change', ()=>{
                if (select.value !== ''){
                    mostrarOcultarSugerencias(labelSugr, '', false);
                    estadoCampo(tipo, true);
                    ocultarError(labelError, iconoError);
                }else{
                    mostrarOcultarSugerencias(labelSugr, msg, true);
                    estadoCampo(tipo, false);
                }
            });
        }
    });

    // if (btnEliminar){
        
    //     btnEliminar.addEventListener('click', () => {

    //         Swal.fire({
    //             title: '¿Está seguro(a) de eliminar la imagen?',
    //             // text: "You won't be able to revert this!",
    //             icon: 'warning',
    //             showCancelButton: true,
    //             confirmButtonColor: '#d33',
    //             cancelButtonColor: '#3085d6',
    //             confirmButtonText: 'Si, Eliminar!',
    //             cancelButtonText: 'No, Cancelar'
    //         }).then((result) => {
    //             if (result.isConfirmed) {
    
    //                 eliminarLogo();
    //             }
    //         })
    //     })
    // }
}

export function btnSubmit(e){
    const formulario = e.target.parentElement.parentElement;
    e.preventDefault();
    Swal.fire({
        title: 'Guardando registro...',
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
            formulario.submit();
        }
    });

}

/* CAMPOS EN ESPECIFICO */
export function estadoCampo(tipo, estado = false){
    
    if (Object.keys(objeto).includes(tipo)){
        objeto[tipo]=estado;
    }

    for (let llave in objeto){
        if(objeto[llave] == false){
            globalVariables.cntEstado=0;
        }else{
            globalVariables.cntEstado++;
        }
    }
    
    if(globalVariables.cntEstado == 6){
        estadoBoton(true);
    }else{
        estadoBoton(false);
    }

    globalVariables.cntEstado=0;
}

/* FINALIZA CAMPOS */
export function reglaInput(tipo){
    if(Object.keys(reglas).includes(tipo)){
        return reglas[tipo];
    }
}

export function mensajeSugerencia(tipo){
    if(Object.keys(sugerencias).includes(tipo)){
        return sugerencias[tipo];
    }
}

export function ocultarError(labelError, iconoError){
    labelError.textContent='';
    labelError.classList.add('ocultar');
    iconoError.classList.add('ocultar');
    iconoError.previousElementSibling.style.right = "0.5rem";
}

// muestra u oculta el error
export function mostrarOcultarSugerencias(label, msg, estado){
    if (estado){
        label.classList.remove('ocultar');
        label.textContent=msg;
    }else{
        label.classList.add('ocultar');
        label.textContent="";
    }
}

// activa o desactiva el botón de Guardar
export function estadoBoton(estado){
    const boton = document.querySelector(`[data-btn="btn-enviar"]`);

    if (estado){
        boton.classList.remove('disabled');
        boton.removeAttribute('disabled');                                
    }else{
        boton.classList.add('disabled');
        boton.setAttribute('disabled', '');                                
    }
}

// Muestra la alerta de carga de datos y ejecuta la función de consulta la APi de categorías
export function managerAlert(){

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

        // const url='http://192.168.18.120:3000/producto/api';
        const resultado = await fetch(globalVariables.url + '/api');
        globalVariables.registrosAPI = await resultado.json();

        mostrarRegistrosAPI();

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
export function mostrarRegistrosAPI( evento = 'DOM', campo = '', valor = ''){
    // Verificamos que exista una tabla en el body, ya que si no está... quiere decir que estamos utilizando este codigo en editar-categoria, y allí no existe la tabla de registros
    const tabla = document.querySelector('.table');

    if (globalVariables.registrosAPI){


        if (evento === 'DOM'){

            globalVariables.arrayDescripcion=[];
            globalVariables.arrayCodigoBarras=[];
            globalVariables.arrayCodigoManual=[];

        }

        globalVariables.registrosAPI.forEach( registro => {
            
            const {Prod_Descripcion, Cod_Manual, Cod_Barras} = registro;
            
            if(evento === 'DOM' && valor === ''){

                // Almacenamos en el arreglo global 'nombreCategorias' todos los nombres para usarlos en otra función
                globalVariables.arrayDescripcion.push(formatearTexto(Prod_Descripcion));
                globalVariables.arrayCodigoManual.push(Cod_Manual);
                globalVariables.arrayCodigoBarras.push(Cod_Barras);
    
                // Si la tabla si existe, entonces se procede a cargar los registros 
                if (tabla){
                    crearRegistro(registro);
                }
            }
            
            // Si lo que digitó el usuario se encuentra dentro del codigo de barras entonces se agrega el registro
            if(evento === 'input' && campo === 'Cod_Barras' && Cod_Barras.includes(valor)){
                if(tabla){
                    crearRegistro(registro);
                }
            }

            // Si lo que digitó el usuario se encuentra dentro del codigo de barras entonces se agrega el registro
            if(evento === 'input' && campo === 'Cod_Manual' && Cod_Manual.includes(valor)){
                if(tabla){
                    crearRegistro(registro);
                }
            }

            // Si lo que digitó el usuario se encuentra dentro del codigo de barras entonces se agrega el registro
            if(evento === 'input' && campo === 'prodDescripcion' && formatearTexto(Prod_Descripcion.toLowerCase()).includes(valor)){
                if(tabla){
                    crearRegistro(registro);
                }
            }
        });
    }

    // Guardamos todos los nombres de las categorias en un solo string para utilizarlo en el filtro del campo
    globalVariables.stringDescripcion=globalVariables.arrayDescripcion.toString();
    globalVariables.stringCodigoManual=globalVariables.arrayCodigoManual.toString();
    globalVariables.stringCodigoBarras=globalVariables.arrayCodigoBarras.toString();

    botonStatus();

}

function crearRegistro(registro){

    // Obtenemos las llaves del objeto
    let llaves = Object.keys(registro);
    let id = registro[llaves[0]];
    
    let arrayTD = [];

    llaves.forEach( (llave, index) => {

        // let descripcion = '';

        // if(llave.includes('Descripcion') && descripcion === ''){
        //     descripcion = registro[llave];
        // }

        const nombreCampo = Object.values(globalVariables.tabla[index])[0];
        const posicion = Object.values(globalVariables.tabla[index])[1];
        const clases = Object.values(globalVariables.tabla[index])[2];
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
    });

    // Creamos la celda de modificar el registro
    const spanModificar = document.createElement('SPAN');
    spanModificar.textContent="Editar";

    const imgModificar = document.createElement('IMG');
    imgModificar.setAttribute('src','/build/img/sistema/editar-ng.svg');
    imgModificar.setAttribute('alt','Imagen Editar');

    const linkModificar = document.createElement('A');
    linkModificar.setAttribute('href', `${globalVariables.url}/editar?id=${id}`);
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
     spanEliminar.dataset.id = id;
     spanEliminar.onclick = eliminarItem;//Asignamos la función eliminarItem
 
     const imgEliminar = document.createElement('IMG');
     imgEliminar.setAttribute('src','/build/img/sistema/eliminar.svg');
     imgEliminar.setAttribute('alt','Imagen Eliminar');
     imgEliminar.dataset.id = id;
     imgEliminar.onclick = eliminarItem;//Asignamos la función eliminarItem
     
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

    document.querySelector('.tbody').appendChild(tr);
}

export function eliminarItem(e){
    confirmarEliminacion(e.target.dataset.id );
}

export function botonStatus(){

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
                // let nombre = labelNo.nextElementSibling.value;
                let inputId = labelNo.nextElementSibling;
                let inputStatus = inputId.nextElementSibling;
                // console.log(inputId);

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
                let resultado = postElemento('estado', inputId.value, inputStatus.value );

                resultado.then(result => {
                    // console.log(result);
                    if (result){
                        // como el resultado fue satisfactorio, procedemos a cambiar el estado en el arreglo de objetos cargado en memoria
                        globalVariables.registrosAPI.forEach( registro => {

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

export function confirmarEliminacion(id){

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

            let respuesta = postElemento('eliminar', id );
            
            console.log(respuesta);
            respuesta.then(result => {
                if (result === true) {

                    Swal.fire(
                        'Registro eliminado!',
                        'El registro ha sido eliminado satisfactoriamente',
                        'success'
                    )

                    // limpiarVariables();
                    // limpiarCaja();
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

// Se borra las filas para realizar el filtro de búsqueda
export function borrarFilas(){
    const filas = document.querySelectorAll('.tbody tr');
    filas.forEach( fila => {
        fila.remove();
    });
}

// Mostrar Alerta de error o Exito con Sweet Alert
export function verificarMensajeGeneral(){
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

// limpia el campo y los mensajes de error
export function limpiarCaja(item, foco = true){
    const input = item.querySelector('.form__input');
    const labelSugerencia = item.querySelector('.form__labelSugerencia');
    const labelError = item.querySelector('.form__labelError');
    const iconoError = item.querySelector('.form__iconError');
    const iconoLimpiar = item.querySelector('.form__limpiar');
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

export function botonAdjuntarArchivo(){

    const btnAdjuntar = document.querySelector(`[data-tipo="boton-adjuntar"]`);
    
    let crearBotonAdjuntar = true;
    
    btnAdjuntar.addEventListener('click', () =>{

        // Verificamos si se adjuntaron 
        const filesAttachment = document.querySelectorAll('.textFileSelect');
        if (filesAttachment){
            filesAttachment.forEach( file => {
                if (file.textContent!=''){
                    crearBotonAdjuntar = true;
                }else{
                    crearBotonAdjuntar = false;
                }
            });
        }

        if(crearBotonAdjuntar){
         
            const inputFile = document.createElement('INPUT');
            inputFile.setAttribute('type','file');
            inputFile.setAttribute('data-tipo','archivo');
            inputFile.setAttribute('name','ImVd_Nombre[]');
            inputFile.setAttribute('accept','image/png,image/jpg,image/jpeg,video/mp4');

            const inputBoton = document.createElement('INPUT');
            inputBoton.setAttribute('type', 'button');
            inputBoton.setAttribute('data-tipo', 'boton-archivo');
            inputBoton.setAttribute('value', 'Seleccionar archivo');
            inputBoton.classList.add('form__btn');
            inputBoton.classList.add('btn-terciario');

            const imgError = document.createElement('IMG');
            imgError.setAttribute('src','/build/img/sistema/error.svg');
            imgError.setAttribute('alt','icono de error');
            imgError.classList.add('form__iconError');
            imgError.classList.add('ocultar');
            
            const span = document.createElement('SPAN');
            span.classList.add('textFileSelect');
            
            const imgEliminar = document.createElement('IMG');
            imgEliminar.setAttribute('src','/build/img/sistema/eliminar.svg');
            imgEliminar.setAttribute('alt','icono de eliminar');
            
            const linkEliminar = document.createElement('A');
            linkEliminar.setAttribute('href', '#');
            linkEliminar.classList.add('ocultar','eliminarFile');
            linkEliminar.appendChild(imgEliminar);
            linkEliminar.onclick=eliminarFile;

            const divFile = document.createElement('DIV');
            divFile.classList.add('divFile');
            divFile.appendChild(span);
            divFile.appendChild(linkEliminar);
            
            const labelSugerencia = document.createElement('LABEL');
            labelSugerencia.classList.add('form__labelSugerencia');
            labelSugerencia.classList.add('ocultar');
            
            const labelError = document.createElement('LABEL');
            labelError.classList.add('form__labelError');
            labelError.classList.add('ocultar');
            // labelError.textContent = "<?php echo tipoAlerta($alertas, 'error-archivo', 'nombre');?>";

            const divContenedor = document.createElement('DIV');
            divContenedor.classList.add('form__campo');
            divContenedor.classList.add('campo--file');
            divContenedor.classList.add('t-xxl');

            divContenedor.appendChild(inputFile);
            divContenedor.appendChild(inputBoton);
            // divContenedor.appendChild(span);
            // divContenedor.appendChild(imgError);
            divContenedor.appendChild(divFile);
            divContenedor.appendChild(labelSugerencia);
            divContenedor.appendChild(labelError);
            
            const formulario = document.querySelector('.form');

            const boton = document.querySelector('.form__button');
            formulario.insertBefore(divContenedor, boton);
            
            campoFile();
        }
    });
}

function eliminarFile(e){
    // obtenemos el texto que se encuentra en el span
    const texto = e.target.parentElement.previousElementSibling.textContent;
    if(texto){
        let divContenedor = e.target.parentElement.parentElement.parentElement;
        divContenedor.remove();
    }
}

export function campoFile(){
    const inputFiles = document.querySelectorAll(`[type="file"]`);
    
    inputFiles.forEach(inputFile => {

        const campo = inputFile.parentElement;
        const fileSelect = campo.querySelector('.textFileSelect');
        const inputbutton = campo.querySelector(`[type="button"]`);
        const iconoError = campo.querySelector('.campo--file > .form__iconError');
        const iconoEliminar = campo.querySelector('.eliminarFile');

        // const labelSugerencia = campo.querySelector('.campo--file > .form__labelSugerencia');
        const labelError = campo.querySelector('.campo--file > .form__labelError');
    
        if (inputFile && inputbutton){
            inputbutton.addEventListener('click', ()=>{
                inputFile.click();
                inputFile.addEventListener('input', ()=>{
    
                    fileSelect.textContent = inputFile.value;
                    
                    if (fileSelect.textContent !== ''){
                        iconoEliminar.classList.remove('ocultar');
                    }

                    if(fileSelect.textContent !== '' && !labelError.className.includes('ocultar')){
                        labelError.classList.add('ocultar');
                        iconoError.classList.add('ocultar');
                    }

                    // RESTRICIONES DE ARCHIVO
                    
                    // console.log(inputFile.files[0]);
                    // let pesoArchivo = inputFile.files[0].size;
    
                    // let pesoMB = (pesoArchivo / 1000000).toFixed(2);
                    
                    // if(pesoArchivo > 500000){
                    //     labelSugerencia.textContent = `El archivo seleccionado tiene un peso de ${pesoMB} MB, y NO debe superar el peso de 500 KB`;
                    //     labelSugerencia.classList.remove('ocultar');
                    // }
    
                    // let tipoArchivo = inputFile.files[0].type;
                    // if(!(tipoArchivo === "image/jpeg" || tipoArchivo === "image/jpg" || tipoArchivo === "image/png")){
                    //     labelSugerencia.textContent = `Seleccionó un archivo que NO es permitido`;
                    //     labelSugerencia.classList.remove('ocultar');
                    // }
                });
            });
        }
    });
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

// Se define como POST Elemento, porque es lo que ocurre en el POST
export async function postElemento(proceso, id, valor = 0 ){

    //FormData es como el submit de los datos de un formulario HTML pero en JavaScript 
    const datos = new FormData();
    datos.append('id', id);//Se agregan todos los datos con append
    datos.append('valor', valor);
    // datos.append(globales.llaves[1], nombre);

    try {
        let direccion;
        if(proceso == 'estado'){
            direccion = globalVariables.url + '/estado';
        }else if (proceso == 'eliminar'){
            direccion = globalVariables.url + '/eliminar';
        }

        // Petición hacia la API
        const respuesta = await fetch(direccion, {
            method: 'POST', //método por el que se envia la información al servidor
            body: datos //enviamos datos al servidor definidos anteriormente, solo se hace en el cuerpo de la petición
        });

        const resultado = await respuesta.json();

        if (proceso === 'eliminar'){

            if(resultado === true){
                return true;
            }else{
                return false;
            }
        }

        if (proceso === 'estado'){
            return resultado;
        }


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
