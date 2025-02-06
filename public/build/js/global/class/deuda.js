import { cargarFechaHoraActual, limpiarFormulario, url } from "../parametros.js";
import { ModeloBase } from "./ModeloBase.js";

export class Deuda extends ModeloBase{
    constructor(objeto){
        super(objeto);
    }

    formularioAgregar(idFormulario){

        const formulario = document.querySelector(`#${idFormulario}`);
        
        formulario.addEventListener('submit', async (e) => {
            
            // Prevenir el comportamiento por defecto del formulario
            e.preventDefault();
           
            const campoCliente = document.querySelector('#fk_cliente_deudor');
            const msgError = campoCliente.nextElementSibling;
            // console.log(campoCliente.value);
            
            //Se verifica si se seleccionó un cliente
            if (campoCliente.value != ''){

                msgError.classList.add('ocultar');
                msgError.textContent = "";

                // Si pasa la validación de los campos envie la petición al post
                if(this.revisarCampos()){

                    const dateTimeNow = cargarFechaHoraActual();
        
                    let formData = new FormData(formulario);
                    formData.append('fecha', dateTimeNow.fecha);
                    formData.append('hora', dateTimeNow.hora);

                    //También se sobreescribe el método agregarRegistro
                    const rta = await this.agregarRegistro(formData);
                    
                    // Si se agregó el registro correctamente debe refrescar la tabla
                    if (rta){
                        
                        //Obtenemos un arreglo de nombres de los campos a partir del array de objetos validacionCampos[];
                        const nombreCamposFormulario = this.validacionCampos.map(obj => Object.keys(obj)[0]);
    
                        limpiarFormulario(nombreCamposFormulario);
                        this.listarRegistros(campoCliente.value);

                    }
                }
            
            }else{
    
                msgError.classList.remove('ocultar');
                msgError.textContent = "Debe seleccionar un cliente";
            }
            
        });
    }

    //Se pasa por parámetro el id del cliente
    async listarRegistros(id){
        //Se cambia la url 
        if (!this.url.apiConsultar) {
            console.error("La URL para consultar no está definida.");
            return;
        }

        const urlFiltrar = url + this.url.apiConsultar;
        const formData = new FormData();
        
        formData.append('id', id);

        try {
            const response = await fetch(urlFiltrar, {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) throw new Error('No hay conexión con el servidor');

            const data = await response.json();
            this.registros = data;
            console.log(this.registros);
            //Se sobreescribe esta parte del uso del método crearTabla
            this.crearTabla(this.registros);

        } catch (error) {
            console.error('Codigo de error:', error);
        }
    }

    //Se pasa por parámetro del formData creado
    async agregarRegistro(formulario){

        const urlGuardar = url + this.url.agregar;
   
        // Hacer una solicitud fetch para enviar los datos del formulario
        try {

            const response = await fetch(urlGuardar, {
                method: 'POST',
                body: formulario,
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
}
//# sourceMappingURL=deuda.js.map
