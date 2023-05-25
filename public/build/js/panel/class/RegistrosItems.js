import { rutaServidor} from "./Parametros.js";

export class RegistrosItems{

    constructor(entidad, nombreTabla, camposTabla, arrayAPI, stringAPI){

        this.entidad = entidad;
        this.nombreTabla = nombreTabla;
        this.camposTabla = camposTabla;
        this.arrayAPI = arrayAPI;
        this.stringAPI = stringAPI;
        this.registrosAPI = '';
    }

    // Metodo para obtener los registros y mostrarlo en las tablas del HTML
    obtenerRegistros(id){
        let resultado = getRegistrosAPI(id, this.entidad);
        resultado.then(result => {
            // Devuelve el arreglo de la api
            this.registrosAPI = result;
            this.cargarDatos();
        });
    }

    cargarDatos(){
        let tabla = document.querySelector(`table[data-tipo="${this.nombreTabla}"]`);
        
        let llavesArray = Object.keys(this.arrayAPI);
        console.log(llavesArray);
        if(this.registrosAPI){
            // Limpiamos los registros almacenados en cada array
            llavesArray.forEach( key => {
                this.arrayAPI[key]='';
            });
        }

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
        
                    // if(llave.includes('Status')){
                        
                    //     const formStatus = document.createElement('FORM');
                    //     formStatus.classList.add('display-inline');
                    //     formStatus.setAttribute('method', 'POST');
        
                    //     const divCampoEstado = document.createElement('DIV');         
                    //     divCampoEstado.classList.add('form__campo', 'check');
        
                    //     const divContent = document.createElement('DIV');         
                    //     divContent.classList.add('check__content');
        
                    //     const divCheck = document.createElement('DIV');         
                    //     divCheck.classList.add('check__estado');
                        
                    //     const lbSi = document.createElement('LABEL');         
                    //     lbSi.classList.add('check__label');
                    //     lbSi.textContent='Si';
        
                    //     const lbNo = document.createElement('LABEL');         
                    //     lbNo.classList.add('check__label');
                    //     lbNo.textContent='No';   
        
                    //     const inputHiddenId = document.createElement('INPUT'); 
                    //     inputHiddenId.setAttribute('type', 'hidden');
                    //     inputHiddenId.setAttribute('value', id);
        
                    //     const inputHiddenStatus = document.createElement('INPUT'); 
                    //     inputHiddenStatus.setAttribute('type', 'hidden');
        
                    //     if(registro[llave] === 'E'){
        
                    //         if (lbSi.className.includes('ocultar')){
                    //             lbSi.classList.remove('ocultar');
                    //         }
                    //         if (!lbNo.className.includes('ocultar')){
                    //             lbNo.classList.add('ocultar');
                    //         }
                    //         inputHiddenStatus.setAttribute('value', 'E');
                            
                    //     }else{
                            
                    //         if (!lbSi.className.includes('ocultar')){
                    //             lbSi.classList.add('ocultar');
                    //         }
                    //         if (lbNo.className.includes('ocultar')){
                    //             lbNo.classList.remove('ocultar');
                    //         }
                            
                    //         inputHiddenStatus.setAttribute('value', 'D');
        
                    //         divCheck.classList.add('inactivo');
                    //         divContent.classList.add('inactivo');
                    //     }
        
                    //     divContent.appendChild(divCheck);
                    //     divContent.appendChild(lbSi);
                    //     divContent.appendChild(lbNo);
                    //     divContent.appendChild(inputHiddenId);
                    //     divContent.appendChild(inputHiddenStatus);
                        
                    //     divCampoEstado.appendChild(divContent);
                        
                    //     formStatus.appendChild(divCampoEstado);
        
                    //     td.appendChild(span);
                    //     td.appendChild(formStatus);
        
                    // }else{
                    if(!llave.includes('Status')){

                        texto.textContent = registro[llave];
                        td.appendChild(span);
                        td.appendChild(texto);
                        
                        if(clases.length !== 0){
                            clases.forEach( clase => {
                                td.classList.add(clase);
                            });
                        }
                    }
                        
                    // }
        
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

        // Eliminar-----------------------------------------------
        const spanEliminar = document.createElement('SPAN');
        spanEliminar.textContent="Eliminar";
        spanEliminar.dataset.idRegistro = id;
        // spanEliminar.onclick = eliminarItem;//Asignamos la función eliminarItem
    
        const imgEliminar = document.createElement('IMG');
        imgEliminar.setAttribute('src','/build/img/sistema/eliminar.svg');
        imgEliminar.setAttribute('alt','Imagen Eliminar');
        imgEliminar.dataset.idRegistro = id;
        // imgEliminar.onclick = eliminarItem;//Asignamos la función eliminarItem
        
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
        console.log(this.registrosAPI);
    }
}

async function getRegistrosAPI(id, entidad){

    try {

        let resultado = '';
        
        if (id){
            resultado = await fetch(rutaServidor + entidad + '/api?id=' + id);
        }

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

