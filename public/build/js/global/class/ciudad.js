import { consultarAPI } from "../parametros.js";

export class Ciudad {
    constructor() {
        this.comboCiudades;
    }

    async cargarCiudades(idCmbDepart, idCmbCiudad) {
        const comboBoxDepart = document.querySelector(`#${idCmbDepart}`);
        this.comboCiudades = document.querySelector(`#${idCmbCiudad}`);
        
        // Aseguramos que las ciudades no se carguen hasta que se dispare el evento change
        await new Promise(resolve => {
            
            comboBoxDepart.addEventListener('change', async (e) => {
                // Obtenemos los datos
                const datos = await consultarAPI("/ciudad/api?cod_depart=" + e.target.value);
                
                // Borramos las opciones que puedan existir
                const options = this.comboCiudades.querySelectorAll('option');
                options.forEach(option => {
                    if (option.getAttribute('value') !== "") {
                        option.remove();
                    }
                });
    
                // Creamos los nuevos options
                datos.forEach(ciudad => {
                    const optionEntidad = document.createElement('OPTION');
                    optionEntidad.setAttribute('value', ciudad.id_ciudad);
                    optionEntidad.textContent = ciudad.nombre_ciudad;
                    this.comboCiudades.appendChild(optionEntidad);
                });
                
                // Resolvemos la promesa después de haber actualizado las ciudades
                resolve();
            });
    
            // Desencadenar el evento change manualmente para asegurar la carga inicial
            comboBoxDepart.dispatchEvent(new Event('change'));
        });
    }

    cambiarValue(valor){
        this.comboCiudades.value = valor;
    }
}
