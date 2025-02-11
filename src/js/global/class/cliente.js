import { Ciudad } from "./ciudad.js";
import { ModeloBase } from "./ModeloBase.js";


export class Cliente extends ModeloBase {
    constructor (objeto){
        super(objeto);
        //Obtenemos el listado de los campos modal y su equivalente a los campos del formulario principal para evitar conflicto de nombres y cargar los datos en la ventana modal del cliente.
        this.equivalenciaCamposModal = objeto.equivalenciaCamposModal;
       
        this.idComboDepartModal = objeto.idComboDepartModal;
        this.idComboCiudadModal = objeto.idComboCiudadModal;
        // Guarda el id de la ciudad seleccionada para hacer el cambio de valor en el combo de la ventana modal
        this.valueComboCiudad = null;
        
    }

    //Este método solo es utilizado siempre y cuando el usuario de clic en el icono Editar de una tabla
    asignarValoresVentanaModal(objetoEncontrado) {
        // Convertir el objeto encontrado en un array de pares clave-valor
        const arrayCliente = Object.entries(objetoEncontrado);
    
        arrayCliente.forEach(([key, value]) => {

            // Encontrar el campo correspondiente en this.equivalenciaCamposModal
            const campo = this.equivalenciaCamposModal.find(campo => campo[key]);
    
            if (campo) {
                // Desestructurar el par clave-valor del campo
                const [, campoId] = Object.entries(campo)[0];
                const campoModal = document.querySelector(`#${campoId}`);
                
                if (campoModal) {
                    
                    campoModal.value = value;
                    
                    if(campoModal.tagName == 'SELECT' && campoModal.id == this.idComboCiudadModal){
                        
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
        await ciudad.cargarCiudades(this.idComboDepartModal, this.idComboCiudadModal);
        
        ciudad.cambiarValue(this.valueComboCiudad);
    }
}
