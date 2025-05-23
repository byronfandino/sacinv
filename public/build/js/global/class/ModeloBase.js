import { 
    cierreAutModal, 
    consultarAPI, 
    formatearMiles, 
    habilitarBotonSubmit, 
    limpiarFormulario, 
    mostrarErrorCampo, 
    quitarErrorCampo, 
    url 
} from "../parametros.js";

export class ModeloBase{
    constructor (objeto){
        this.objeto = objeto;
        //url de conexión
        this.url = objeto.url;
        //id del texto en el que se muestra la totalidad de los registros
        this.idTotalRegistros = objeto.idTotalRegistros;
        //Estructura en la que creará la tabla de los registros
        this.tabla = objeto.tabla;
        //Obtenemos el listado de los campos modal y su equivalente a los campos del formulario principal para evitar conflicto de nombres y cargar los datos en la ventana modal del cliente.
        this.equivalenciaCamposModal = objeto.equivalenciaCamposModal;
        //Requisitos de cada campo para ser llenado
        this.validacionCampos = objeto.validacionCampos;
        //Esta variable confirma si se está realizando la petición desde una ventana modal y se usa unicamente para mostrar los errores del backend y del frontend en el método handleResponse()
        this.modal = objeto.modal;
        //Abre la ventana modal con el registro cargado en el formulario para actualizar el registro, este está referenciado en la tabla de registros ubicado en el formulario principal
        this.idVentanaModal = objeto.idVentanaModal;
        // Se utiliza para trasladar el registro de una tabla de una ventana modal al formulario principal
        this.equivalenciaTablaAForm = objeto.equivalenciaTablaAForm;
        //Variable global para guardar todos los registros del JSON
        this.registros = '';
        //Se utiliza para obtener los registros filtrados con el fin llenar la tabla de una pagina principal a partir de una tabla que se encuentra en la ventana modal
        this.registrosAlternos = '';
        //Filtra los resultados en la tabla de acuerdo a los valores que digite el usuario en los campos
        this.filtroBusqueda = objeto.filtroBusqueda;
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
                    if (this.registros != '' && this.filtroBusqueda === true){
                        this.buscarRegistros(e.target.value);
                    }
                });

            }else if (campo.tagName === 'SELECT'){

                campo.addEventListener('change', e => {
                    //Completamos el valor de la propiedad value del objetoParametro
                    objetoParametro.value = e.target.value;
                    this.estadoCampo(objetoParametro);  

                    if (this.registros != ''&& this.filtroBusqueda === true){
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

    crearTabla(registros){

        const contadorRegistros = document.querySelector(`#${this.idTotalRegistros}`);
        contadorRegistros.textContent = `Registros encontrados ( ${registros.length} )`;

        const tbody = document.querySelector(`#${this.tabla.idTabla} .tbody`);
        const arrayCamposTabla = this.tabla.estructura.map(obj => Object.keys(obj)[0]); 
        const nombresCamposTabla = this.tabla.estructura.map(obj => [Object.keys(obj)[0], Object.values(obj)[0]]); 
        
        tbody.innerHTML = ''; //Limpiar la tabla antes de llenarla

        registros.forEach(registro => {
            // Se convierte el objeto en un array de arrays, donde cada subarray corresponde a la propiedad y el valor, parecido a un objeto
            let arrayRegistro = Object.entries(registro);

            //Obtenemos el id del registro
            const idRegistro = arrayRegistro[0][1];
            
            const tr = document.createElement('TR');

            let arrayTD = [];
            let classTD = '';

            arrayRegistro.forEach(campo => {

                if(arrayCamposTabla.includes(campo[0])){

                    const td = document.createElement('TD');
                    const span = document.createElement('SPAN');
                    
                    span.classList.add('tbody__td--titulo');
                    
                    // Se agrega el nombre del dato dentro del td cuando este se encuentre en modo Mobile
                    const keyCampo = nombresCamposTabla.find(nombreCampo => nombreCampo[0] === campo[0]);

                    //buscamos el objeto dentro de la tabla para obtener la posición en la tabla 
                    const objetoTabla = this.tabla.estructura.find(obj => keyCampo[0] in obj);

                    // Si existe la propiedad reemplazar, utilizada para reemplazar los datos provenientes de las tabla original y mostrar datos legibles al usuario
                    if (objetoTabla.reemplazar){

                        // Buscamos en la sección reemplazar de la tabla para saber si el valor del campo por ejemplo 'D' corresponde a la llave del arreglo que está en reemplazar y obtener su equivalente 
                        const textoReemplazado = objetoTabla.reemplazar.find(obj => campo[1] in obj);
                        const arrayCampoModificado = Object.entries(textoReemplazado);
                        campo[1] = arrayCampoModificado[0][1];

                        // almacenamos el valor del campo en la variable classTD, para aplicar el estilo a todos los TD del TR posteriormente
                        classTD = textoReemplazado.class;
                    }

                    if (keyCampo) {
                        span.textContent = keyCampo[1];
                    }

                    //Se verifica si la tabla está en una ventana modal para convertir el campo en un link
                    const textoTD = this.verificarTipoRegistro(campo, idRegistro);

                    // Agregamos el span y el texto al td para aplicar posteriormente las clases a nivel general
                    td.appendChild(span);
                    td.appendChild(textoTD);

                    //Agregando las posibles clases existentes al TD definidas en la estructura, estas clases son en específico al campo td y no a todo el registro 
                    if (objetoTabla.class){
                        objetoTabla.class.forEach(clase => {
                            td.classList.add(clase);
                        });
                    }

                    //Agregamos el td a la posición del arreglo según la posición del objeto dentro de la estructura de la tabla
                    arrayTD[objetoTabla.posicion] = td;

                }
            });

            //Agregamos todos los td almacenados en el array
            arrayTD.forEach(td => {
                // Verificamos si quedó almacenada una clase de la seccion reemplazar para aplicar el color de fondo en todos los td por ejemplo al momento de abonar toda la sección queda con fondo amarillo
                if(classTD != ''){
                    //Agregamos la clase
                    td.classList.add(classTD);
                }

                tr.appendChild(td);
            });
            
            const nombreCampoId = Object.entries(registro)[0][0];

            // Si se requiere la columna modificar se crea el td y se aplica la clase de la seccion reemplazar en caso de que exista
            if(this.tabla.columnaModificar){

                let tdModificar = '';

                if(classTD != ''){
                    tdModificar = this.crearTdModificar(nombreCampoId, idRegistro);
                    tdModificar.classList.add(classTD);
                    tr.appendChild(tdModificar);

                }else{
                    tr.appendChild(this.crearTdModificar(nombreCampoId, idRegistro));
                }
            }

            // Si se requiere la columna eliminar se crea el td y se aplica la clase de la seccion reemplazar en caso de que exista
            if(this.tabla.columnaEliminar){

                let tdEliminar = '';

                if(classTD != ''){
                    tdEliminar = this.crearTdEliminar(idRegistro);
                    tdEliminar.classList.add(classTD);
                    tr.appendChild(tdEliminar);

                }else{
                    tr.appendChild(this.crearTdEliminar(idRegistro));
                }
            }

            tbody.appendChild(tr);
        });
    }

    // Método para determinar si crear un hipervínculo o no, y eso depende si la tabla se está cargando desde una ventana modal
    verificarTipoRegistro(campo, idRegistro){

        const texto = document.createElement('SPAN');
        const keyCampo = campo[0];
        let valorCampo = campo[1];

        // Encuentra el objeto donde la clave existe
        const objetoEncontrado = this.tabla.estructura.find(obj => keyCampo in obj);
       
        //Verificamos el formato del dato
        if (objetoEncontrado.formato){
            if (objetoEncontrado.formato == "numero"){
                valorCampo = formatearMiles(valorCampo);
            }
        }
        
        if (this.modal.isModal){
            // Si la tabla se encuentra dentro de un modal se crea una etiqueta <A> en lugar de un <SPAN>
            const aLink = document.createElement('A');
            aLink.setAttribute('href', '#');

            // Se verifica si el dato debe ser un hipervínculo
            if(objetoEncontrado && objetoEncontrado.class.includes('tbody__td--enlace')){

                aLink.textContent = valorCampo;
                aLink.dataset.id = idRegistro;
                aLink.addEventListener('click', e => { 
                    e.preventDefault();
                    this.asignarDatosAFormulario(e)}
                );
                
                return aLink;
                
            }else{

                // Se asigna el valor del campo al span
                texto.textContent = valorCampo;
                return texto;         
            }

        }else{

            texto.textContent = valorCampo;
            return texto;
        }
    }

    // Este método copia los datos del objeto encontrado en la tabla de registro que está dentro de la ventana modal y los envia al formulario principal
    asignarDatosAFormulario(e){
        const idBusqueda = Object.entries(this.equivalenciaTablaAForm[0])[0][1]; 

        // Ejemplo [id_cliente_deudor , 'id_cliente']
        const objeto = this.encontrarRegistro(idBusqueda, e.target.getAttribute('data-id'));
        if (objeto){

            this.equivalenciaTablaAForm.forEach(item => {

                const arrayCampo = Object.entries(item)[0];
                const campo = document.querySelector(`#${arrayCampo[0]}`);
                campo.value = objeto[arrayCampo[1]];
            });

            cierreAutModal(this.modal.idVentanaModal);
            habilitarBotonSubmit(this.modal.idFormularioPrincipal);

            // Se dispara el evento change en el campo oculto
            const inputHidden = document.querySelector(`#${this.modal.campoHidden}`);
            const event = new Event ('change');
            inputHidden.dispatchEvent(event);
        }
    }

    crearTdModificar(nombreCampoId, idRegistro){
        const spanModificar = document.createElement('SPAN');
        spanModificar.textContent="Editar";
        
        const imgModificar = document.createElement('IMG');
        imgModificar.setAttribute('src','/build/img/sistema/editar-ng.svg');
        imgModificar.setAttribute('alt','Imagen Editar');
    
        const linkModificar = document.createElement('A');
        linkModificar.setAttribute('href', '#');
        linkModificar.appendChild(imgModificar);
        linkModificar.appendChild(spanModificar);
        linkModificar.addEventListener('click', e => this.mostrarModal(e, nombreCampoId, idRegistro));
        
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

    // Este evento se dispara cuando se hace clic en el botón modificar dentro de una tabla de registros, el cual traslada no solo desoculta la ventana sino que traslada los datos del registro al formulario, pero se ejecuta un método de la clase hija Cliente llamado asignarValoresVentanaModal() para darle un tratamiento particular a los campos.
    mostrarModal(e, nombreCampo, idRegistro){

        e.preventDefault();
        // console.log(this.idVentanaModal);
        const ventanaModal = document.querySelector(`#${this.idVentanaModal}`);

        if (ventanaModal.className.includes('ocultar')){
            ventanaModal.classList.remove('ocultar');
        }

        const objetoEncontrado = this.encontrarRegistro(nombreCampo, idRegistro);
        // console.log(objetoEncontrado);
        // Este método se encuentra en la clase hija debido a que existen tipos de campos de tipo input y select
        this.asignarValoresVentanaModal(objetoEncontrado);
    }

    asignarValoresVentanaModal(objeto){

        const arrayObjeto = Object.entries(objeto);

        arrayObjeto.forEach(([key, value]) => {

            // Encontrar el campo correspondiente en this.equivalenciaCamposModal
            const campo = this.equivalenciaCamposModal.find(campo => campo[key]);

            if (campo) {
                // Desestructurar el par clave-valor del campo
                const [, campoId] = Object.entries(campo)[0];
                const campoModal = document.querySelector(`#${campoId}`);
                
                if (campoModal) {
                    
                    campoModal.value = value;
                }
            }
        });
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

    // Método para encontrar un objeto dentro del arreglo global de los almacenados en la variable this.registros,para evitar solicitudes al backend.
    encontrarRegistro(campo, id){
        return (this.registros.find(registro => registro[campo] == id)) || false;
    }

    //Se reutiliza el método para obtener los registros y adicionalmente crear la tabla con los mismos datos
    async listarRegistros(){

        if (!this.url.apiConsultar) {
            console.error("La URL para consultar no está definida.");
            return;
        }

        try { 
            const datos = await consultarAPI(this.url.apiConsultar); 
            this.registros = datos;
            this.crearTabla(this.registros);

        } catch (error) { 
            console.error('Error API:', error); 
        }
    }

    async agregarRegistro(formulario){

        const urlGuardar = url + this.url.agregar;

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
        
        const urlActualizar = url + this.url.actualizar;
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

        const urlEliminar = url + this.url.eliminar;
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
        
        // Si el registro fue exitoso el data.rta = true
        if (data.rta == "true") {
            Swal.fire({
                title: data.message,
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
            });
            
            return true;

        // Si el registro no se guardó por validación de campos en el backend, muestra el error en los campos
        } else if (data.alertas) {

            const errores = data.alertas['error'];

            for (let propiedad in errores) {

                const msg = errores[propiedad][0];

                if (errores.hasOwnProperty(propiedad)) {

                    if (this.modal.isModal){
                        mostrarErrorCampo(propiedad + this.modal.nombreCampoComplemento, msg);
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

        // Si es un error del backend
        } else {

            console.log(data.error);

            Swal.fire({
                title: data.message,
                icon: 'error',
                confirmButtonColor: '#f00',
                confirmButtonText: 'Aceptar'
            });

            return false;

        }
    }

    formularioAgregar(idFormulario){
        const formulario = document.querySelector(`#${idFormulario}`);
        
        formulario.addEventListener('submit', async (e) => {
            // Prevenir el comportamiento por defecto del formulario
            e.preventDefault();
    
            // Si pasa la validación de los campos envie la petición al post
            if(this.revisarCampos()){
    
                const rta = await this.agregarRegistro(formulario);
                
                // Si se agregó el registro correctamente debe refrescar la tabla
                if (rta){
                    
                    //Obtenemos un arreglo de nombres de los campos a partir del array de objetos validacionCampos[];
                    const nombreCamposFormulario = this.validacionCampos.map(obj => Object.keys(obj)[0]);

                    limpiarFormulario(nombreCamposFormulario);
                    this.listarRegistros();

                }
            }
        });
    }

    formularioActualizar(idFormulario, objetoPrincipal){

        const formulario = document.querySelector(`#${idFormulario}`);
        formulario.addEventListener('submit', async (e) => {
            // Prevenir el comportamiento por defecto del formulario
            e.preventDefault();

            // Si pasa la validación de los campos envie la petición al post
            if(this.revisarCampos()){
    
                let formData = new FormData(formulario); // Crear un objeto FormData con los datos del formulario

                const rta = await this.actualizarRegistro(formData);

                if(rta){

                    objetoPrincipal.listarRegistros();
                    // Cerramos el modal
                    cierreAutModal(this.modal.idVentanaModal);
                }
            }
        });
    }
}


