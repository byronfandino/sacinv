import { cargarFechaHoraActual, cierreAutModal, formatearMiles, limpiarFormulario, url } from "../parametros.js";
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
                        nombreCamposFormulario.push('valor_total');

                        limpiarFormulario(nombreCamposFormulario);

                        //Asignar valores predeterminados a los campos
                        const selecTipoMov = document.querySelector('#tipo_mov');
                        selecTipoMov.value = "D";

                        const selecTipoMovActualizar = document.querySelector('#tipo_mov_actualizar');
                        selecTipoMovActualizar.value = "D";

                        const inputCant = document.querySelector('#cant');
                        inputCant.value = 1;

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
            
            const tituloSaldo = document.querySelector('#saldo');
            if (this.registros.length > 0){
                const ultimoRegistro = this.registros.length - 1;
                tituloSaldo.textContent = "Saldo " + formatearMiles(this.registros[ultimoRegistro].saldo);
            }else{
                tituloSaldo.textContent = "Saldo $0";
            }
            
            this.crearTabla(data);

            //Se procede a modificar el enlace del botón Descargar
            const botonDescargar = document.querySelector('#reporte_deuda_cliente');

            const id_cliente = document.querySelector('#fk_cliente_deudor').value;
            const url_reporte = "/deuda/reporte_movimientos_deudor?id=" + id_cliente;
            botonDescargar.setAttribute('href', url_reporte);

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
            
            console.log(rta);
            return rta;

        } catch (error) {
            console.error('Codigo de error:', error);
        }
    }

    formularioActualizar(idFormulario, idInputHidden, deudor){

        const formulario = document.querySelector(`#${idFormulario}`);
        const inputHidden = document.querySelector(`#${idInputHidden}`);

        if(formulario && inputHidden){

            formulario.addEventListener('submit', async (e) => {
                // Prevenir el comportamiento por defecto del formulario
                e.preventDefault();
    
                // Si pasa la validación de los campos envie la petición al post
                if(this.revisarCampos()){
        
                    let formData = new FormData(formulario); // Crear un objeto FormData con los datos del formulario
    
                    const rta = await this.actualizarRegistro(formData);
    
                    if(rta){
                        deudor.listarRegistros(inputHidden.value);
                        cierreAutModal(this.modal.idVentanaModal);
                    }
                }
            });
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
                    const inputHidden = document.querySelector('#fk_cliente_deudor');
                    const evento = new Event ('change');
                    inputHidden.dispatchEvent(evento);
                }
            }
        })
    }
}