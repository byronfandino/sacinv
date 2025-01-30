import { ModeloBase } from "./ModeloBase.js";

export class Deudor extends ModeloBase{
    constructor(objeto){
        super(objeto);
    }

    formularioAgregar(idFormulario){
        const formulario = document.querySelector(`#${idFormulario}`);
        
        formulario.addEventListener('submit', async (e) => {
            // Prevenir el comportamiento por defecto del formulario
            // e.preventDefault();
    
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
}
//# sourceMappingURL=deudores.js.map
