import { consultarAPI, mostrarErrorCampo, quitarErrorCampo, url } from "../parametros.js";

export class ModeloBase{
    constructor (objeto){
        this.objeto = objeto;
        //URL API
        this.urlApiListar = objeto.urlApiListar;
        this.urlAgregar = objeto.urlAgregar;
        this.urlActualizar = objeto.urlActualizar;
        this.urlEliminar = objeto.urlEliminar;
        //Estructura en la que creará la tabla de los registros
        this.tabla = objeto.estructuraTabla;
        //Requisitos de cada campo para ser llenado
        this.validacionCampos = objeto.validacionCampos;
        //Variable global para guardar todos los registros del JSON
        this.registros = '';
        //Esta variable confirma si se está realizando la petición desde una ventana modal y se usa unicamente para mostrar los errores del backend en el método handleResponse()
        this.isModal = objeto.isModal;
    }

    asignarValidacionCampos(){
        this.validacionCampos.forEach( objeto => {

            let nombreCampo = Object.keys(objeto)[0];
            const regla = objeto[nombreCampo];
            const campo = document.querySelector(`#${nombreCampo}`);

            //Se construye un objeto para pasarlo por parámetro en el método estadoCampo y la function quitarErrorCampo
            const objetoParametro = {    
                nombreCampo : nombreCampo,
                inputDOM : campo,
                regla : regla,
                value : ''
            }

            if (campo.tagName === 'INPUT'){

                campo.addEventListener('input', e => {
                    //Completamos el valor de la propiedad value del objetoParametro
                    objetoParametro.value = e.target.value;
                    this.estadoCampo(objetoParametro);
                    if (this.registros != ''){
                        this.buscarRegistros(e.target.value);
                    }
                });

            }else if (campo.tagName === 'SELECT'){

                campo.addEventListener('change', e => {
                    //Completamos el valor de la propiedad value del objetoParametro
                    objetoParametro.value = e.target.value;
                    this.estadoCampo(objetoParametro);  

                    if (this.registros != ''){
                        const option = campo.querySelector(`option[value="${e.target.value}"]`);
                        this.buscarRegistros(option.textContent);
                    }
                });
            }
        });
    }  
    
    estadoCampo(objetoCliente){
        //Se obtiene la posición del arreglo en donde se encuentra el objeto, cuya llave coincide dentro del objeto
        const posicion = this.validacionCampos.findIndex(obj => objetoCliente.nombreCampo in obj);
        if (objetoCliente.value.match(objetoCliente.regla)){
            this.validacionCampos[posicion].estado = true;
            quitarErrorCampo(objetoCliente);
        }else{
            this.validacionCampos[posicion].estado = false;
            mostrarErrorCampo(objetoCliente.nombreCampo, this.validacionCampos[posicion].message);
        }
    }

    // Método para buscar registros en base al texto ingresado
    buscarRegistros(filtro) {
        // Filtrar los registros que incluyan el texto en cualquier propiedad del objeto
        const registrosFiltrados = this.registros.filter(registro =>
            Object.values(registro).some(valor =>
                valor.toString().toLowerCase().includes(filtro.toLowerCase())
            )
        );

        // Actualizar la tabla con los registros filtrados
        this.crearTabla(registrosFiltrados);
    }

    crearTabla(registros = this.registros){
        //Definimos cuales son los campos en los que necesitamos iterar
        const tbody = document.querySelector('.tbody');
        tbody.innerHTML = ''; //Limpiar la tabla antes de llenarla
        const arrayCamposTabla = this.tabla.map(obj => Object.keys(obj)[0]); 
        const nombresCamposTabla = this.tabla.map(obj => [Object.keys(obj)[0], Object.values(obj)[0]]); 

        registros.forEach(registro => {
    
            const tr = document.createElement('TR');
            // Se convierte el objeto en un array de arrays, donde cada subarray corresponde a la propiedad y el valor, parecido a un objeto
            let arrayRegistro = Object.entries(registro);
 
            arrayRegistro.forEach(campo => {
    
                if(arrayCamposTabla.includes(campo[0])){
    
                    const td = document.createElement('TD');
                    const span = document.createElement('SPAN');
                    const texto = document.createElement('SPAN');
    
                    span.classList.add('tbody__td--titulo');
    
                    nombresCamposTabla.forEach(nombreCampo => {
                        if (nombreCampo[0] == campo[0]){
                            span.textContent = nombreCampo[1];
                        }
                    })
    
                    texto.textContent = campo[1];
    
                    td.appendChild(span);
                    td.appendChild(texto);
                    tr.appendChild(td);
                }
            });
            
            //Obtenemos el id del registro para guardarlo en los links
            const idRegistro = arrayRegistro[0][1];
            tr.appendChild(this.crearTdModificar(idRegistro));
            tr.appendChild(this.crearTdEliminar(idRegistro));
            tbody.appendChild(tr);
        });
    }

    crearTdModificar(idRegistro){
        const spanModificar = document.createElement('SPAN');
        spanModificar.textContent="Editar";
        
        const imgModificar = document.createElement('IMG');
        imgModificar.setAttribute('src','/build/img/sistema/editar-ng.svg');
        imgModificar.setAttribute('alt','Imagen Editar');
    
        const linkModificar = document.createElement('A');
        linkModificar.setAttribute('href', '#');
        linkModificar.appendChild(imgModificar);
        linkModificar.appendChild(spanModificar);
        linkModificar.addEventListener('click', e => this.mostrarModal(e, idRegistro));
    
        const tdModificar = document.createElement('TD');
        tdModificar.classList.add('tbody__td--icon');
        tdModificar.appendChild(linkModificar);
        return tdModificar;
    }

    crearTdEliminar(idRegistro){
        const spanEliminar = document.createElement('SPAN');
        spanEliminar.textContent="Eliminar";
        spanEliminar.dataset.id = idRegistro;
    
        const imgEliminar = document.createElement('IMG');
        imgEliminar.setAttribute('src','/build/img/sistema/eliminar.svg');
        imgEliminar.setAttribute('alt','Imagen Eliminar');
                
        const linkEliminar = document.createElement('A');
        linkEliminar.setAttribute('href', '#');
        linkEliminar.appendChild(imgEliminar);
        linkEliminar.appendChild(spanEliminar);
        linkEliminar.addEventListener('click', () => this.confirmarEliminacion(idRegistro));

        const tdEliminar = document.createElement('TD');
        tdEliminar.classList.add('tbody__td--icon');
        tdEliminar.appendChild(linkEliminar);
        return tdEliminar;
    }

    // Verifica que los campos requeridos sean llenados
    revisarCampos(){
        let arrayObjetos = [];
        this.validacionCampos.forEach(objeto => {
            if(objeto.estado == false){
                arrayObjetos.push(objeto);
            }
        });

        if (arrayObjetos.length > 0){
            arrayObjetos.forEach(objeto => {
                const nombreCampo = Object.keys(objeto)[0];
                const mensajeError = objeto.message;
                
                mostrarErrorCampo(nombreCampo, mensajeError);
            });
            // Si encontró errores retorne false
            return false;
        }else {
            return true;
        }
    }

    // Función únicamente para obtener los registros en memoria y realizar búsquedas
    async obtenerRegistros(){
        if (!this.urlApiListar) {
            console.error("La URL para listar no está definida.");
            return;
        }
        try { 
            const datos = await consultarAPI(this.urlApiListar); 
            this.registros = datos;
            return true;

        } catch (error) { 
            console.error('Error API:', error); 
            return false;
        }
    }

    //Se reutiliza el método para obtener los registros y adicionalmente crear la tabla con los mismos datos
    async listarRegistros(){
        try { 
            const dataOk = await this.obtenerRegistros();
            if (dataOk){
                this.crearTabla();
            }
        } catch (error) { 
            console.error('Error API:', error); 
        }
    }

    async agregarRegistro(formulario){

        const urlGuardar = url + this.urlAgregar;

        // Crear una instancia de FormData con el formulario
        const formData = new FormData(formulario);
   
        // Hacer una solicitud fetch para enviar los datos del formulario
        try {

            const response = await fetch(urlGuardar, {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) throw new Error('No hay conexión con el servidor');

            const data = await response.json();
            const rta = this.handleResponse(data);
            return rta;

        } catch (error) {
            console.error('Codigo de error:', error);
        }
    }

    async actualizarRegistro(formulario){
        
        const urlActualizar = url + this.urlActualizar;
        // Hacer una solicitud fetch para enviar los datos del formulario
        try {
            const response = await fetch(urlActualizar, {
                method: 'POST',
                body: formulario,
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) 
                throw new Error('No hay conexión con el servidor');
            
            const data = await response.json();

            const rta = this.handleResponse(data);
            return rta;

        } catch (error) {
            console.error('Codigo de error:', error);
        }
    }

    confirmarEliminacion(idRegistro){
        
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
                const rta = this.eliminarRegistro(idRegistro);
                // Si se elimina el registro satisfactoriamente, se refresca la tabla
                if (rta){
                    this.listarRegistros();
                }
            }
        })
    }

    async eliminarRegistro(id){

        const urlEliminar = url + this.urlEliminar;
        const formData = new FormData();
        formData.append('id', id);

        try {
            const response = await fetch(urlEliminar, {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) throw new Error('No hay conexión con el servidor');

            const data = await response.json();
            const rta = this.handleResponse(data);
            return rta;

        } catch (error) {
            console.error('Codigo de error:', error);
        }
    }

    handleResponse(data) {
        if (data.rta == "true") {
            Swal.fire({
                title: data.message,
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
            });
            
            return true;

        } else if (data.alertas) {

            const errores = data.alertas['error'];

            for (let propiedad in errores) {

                const msg = errores[propiedad][0];

                if (errores.hasOwnProperty(propiedad)) {

                    if (this.isModal){
                        mostrarErrorCampo(propiedad + "_modal", msg);
                    }else{
                        mostrarErrorCampo(propiedad, msg);
                    }
                }
            }

            Swal.fire({
                title: data.message,
                icon: 'error',
                confirmButtonColor: '#f00',
                confirmButtonText: 'Aceptar'
            });

            return false;

        } else {
            console.log(data);

            return false;

        }
    }
}



//# sourceMappingURL=ModeloBase.js.map
