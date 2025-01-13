import { consultarAPI, url } from "../parametros.js";

export class Persona{
    constructor (objeto){
        this.objeto = objeto;
        this.urlApiListar = objeto.urlApiListar;
        this.urlAgregar = objeto.urlAgregar;
        this.urlActualizar = objeto.urlActualizar;
        this.urlEliminar = objeto.urlEliminar;
    }

    async listar(){
        if (!this.urlApiListar) {
            console.error("La URL para listar no está definida.");
            return;
        }
        try { 
            const datos = await consultarAPI(this.urlApiListar); 
            return datos;
        } catch (error) { 
            console.error('Error al listar clientes:', error); 
        }
    }

    async agregar(formData){

        const urlGuardar = url + this.urlAgregar;
   
        // Hacer una solicitud fetch para enviar los datos del formulario
        try {
            const response = await fetch(urlGuardar, {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            });
            if (!response.ok) throw new Error('No hay conexión con el servidor');
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('Codigo de error:', error);
        }
    }

    async actualizar(formData){
        
        const urlActualizar = url + this.urlActualizar;
        // Hacer una solicitud fetch para enviar los datos del formulario
        try {
            const response = await fetch(urlActualizar, {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) 
                throw new Error('No hay conexión con el servidor');
            
            const data = await response.json();
            return data;

        } catch (error) {
            console.error('Codigo de error:', error);
        }
    }

    async eliminar(formData){

        try {
            const response = await fetch(this.urlEliminar, {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' }
            });
            if (!response.ok) throw new Error('No hay conexión con el servidor');
            const data = await response.json();
            return data;

        } catch (error) {
            console.error('Codigo de error:', error);
        }
    }
}



//# sourceMappingURL=persona.js.map
