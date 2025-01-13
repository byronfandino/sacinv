import { mostrarErrorCampo, quitarErrorCampo } from "../parametros.js";
import { Ciudad } from "./ciudad.js";
import { Persona } from "./persona.js";

export class Cliente extends Persona {
    constructor (objeto){
        super(objeto);
        //Variable global para guardar todos los registros del JSON
        this.registros = '';
        //Estructura en la que creará la tabla de los registros
        this.tabla = objeto.estructuraTabla;
        //Obtenemos el listado de los campos modal y su equivalente a los campos del formulario principal para evitar conflicto de nombres y cargar los datos en la ventana modal del cliente.
        this.camposModalCliente = objeto.camposModalCliente;
        // Guarda el id de la ciudad seleccionada para hacer el cambio de valor en el combo de la ventana modal
        this.valueComboCiudad = null;
        // Verifica que se cumpla el llenado de los campos
        this.validacionCampos = objeto.validacionCampos;
        //id de los elementos del DOM con los que se interactua
        this.idVentanaModal = objeto.idVentanaModal;
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
    
    // estadoCampo(valorInput, nombreCampo, regla){
    estadoCampo(objetoCliente){
        //Se obtiene la posición del arreglo en donde se encuentra el objeto, cuya llave coincide dentro del objeto
        const posicion = this.validacionCampos.findIndex(obj => objetoCliente.nombreCampo in obj);
        if (objetoCliente.value.match(objetoCliente.regla)){
            this.validacionCampos[posicion].estado = true;
            quitarErrorCampo(objetoCliente);
        }else{
            this.validacionCampos[posicion].estado = false;
        }
    }

    // 
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

    async listarRegistro(){
        const datos = await super.listar();
        this.registros = datos;
        this.crearTabla(this.registros);
    }

    async agregarRegistro(formulario){

        // Crear una instancia de FormData con el formulario
        const formData = new FormData(formulario);

        // Obtenemos los datos 
        const data = await super.agregar(formData);
        const rta = this.handleResponse(data);
        return rta;
    }

    async actualizarRegistro(formulario){
        const data = await super.actualizar(formulario);
        const rta = this.handleResponse(data);
        return rta;
    }

    async eliminarRegistro(id){
        const formData = new FormData();
        formData.append('id_cliente', id);

        const data = await super.eliminar(formData);
        const rta = this.handleResponse(data);
        return rta;
    }

    handleResponse(data) {
        if (data.rta == "true") {
            Swal.fire({
                title: data.message,
                icon: 'success',
                showConfirmButton: false,
                timer: 1500
                // confirmButtonText: 'Aceptar'
            });
            
            return true;

        } else if (data.alertas) {

            const errores = data.alertas['error-cliente'];

            for (let propiedad in errores) {

                const msg = errores[propiedad][0];

                if (errores.hasOwnProperty(propiedad)) {
                    mostrarErrorCampo(propiedad, msg);
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

    crearTabla(registros){
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
        linkModificar.dataset.id = idRegistro;
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
        linkEliminar.dataset.id = idRegistro;
        linkEliminar.appendChild(imgEliminar);
        linkEliminar.appendChild(spanEliminar);
        linkEliminar.addEventListener('click', () => this.confirmarEliminacion(idRegistro));

        const tdEliminar = document.createElement('TD');
        tdEliminar.classList.add('tbody__td--icon');
        tdEliminar.appendChild(linkEliminar);
        return tdEliminar;
    }

    mostrarModal(e, idRegistro){
        e.preventDefault();
        const ventanaModal = document.querySelector(`#${this.idVentanaModal}`);
        if (ventanaModal.className.includes('ocultar')){
            ventanaModal.classList.remove('ocultar');
        }
        const clienteEncontrado = this.buscarCliente(idRegistro);
        this.asignarValoresVentanaModal(clienteEncontrado);
    }

    buscarCliente(idRegistro){
        // Usar el método find para buscar el objeto en el arreglo
        const clienteEncontrado = this.registros.find(cliente => cliente.id_cliente === idRegistro);
        return clienteEncontrado;
    }

    asignarValoresVentanaModal(clienteEncontrado) {
        // Convertir el objeto encontrado en un array de pares clave-valor
        const arrayCliente = Object.entries(clienteEncontrado);
    
        arrayCliente.forEach(([key, value]) => {

            // Encontrar el campo correspondiente en this.camposModalCliente
            const campo = this.camposModalCliente.find(campo => campo[key]);
    
            if (campo) {
                // Desestructurar el par clave-valor del campo
                const [, campoId] = Object.entries(campo)[0];
                const campoModal = document.querySelector(`#${campoId}`);
                
                if (campoModal) {
                    
                    campoModal.value = value;
                    
                    if(campoModal.tagName == 'SELECT' && campoModal.id == 'fk_ciudad_modal'){
                        
                        this.valueComboCiudad = value;             
                    }

                }
            }
        });

        this.cargarComboCiudadModal();
    }

    async cargarComboCiudadModal(){
        const ciudad = new Ciudad();
        //Se pasa por parámetro el id del Departamento, id 
        await ciudad.cargarCiudades('cod_depart_modal', 'fk_ciudad_modal');
        
        ciudad.cambiarValue(this.valueComboCiudad);
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
                    this.listarRegistro();
                }
            }
        })
    }

}

//# sourceMappingURL=cliente.js.map
