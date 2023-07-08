import { rutaServidor } from "./Parametros.js";
export class Ciudades{
    
    cargaComboCiudades(){

        const selectDeptos = document.querySelector('[data-select ="departamento"]');
        const selectCiudades = document.querySelector('[data-select ="ciudad"]');

        selectDeptos.addEventListener('change', e => {
            if (e.target.value != ''){
        
                Swal.fire({
                    title: 'Cargando registros...',
                    showConfirmButton: false,
                    didOpen: () => {
        
                        Swal.showLoading();
                        let resultado = getRegistrosAPI( e.target.value);
                        resultado.then(result => {
        
                            let ciudades = document.querySelectorAll('[data-select ="ciudad"] > option');
        
                            if (ciudades.length > 0 ){
                                ciudades.forEach( ciudad => {
                                    if (ciudad.value != ''){
                                        ciudad.remove();
                                    }
                                });
                            }
        
                            // Devuelve el arreglo de la api
                            result.forEach(ciudad => {
        
                                let option = document.createElement('OPTION');
                                option.value = ciudad['Ciud_Id'];
                                option.textContent = ciudad['Ciud_Nombre'];
        
                                selectCiudades.appendChild(option);
                                
                            });
                        });
        
                        setTimeout(() => {
                            Swal.close();
                        }, 500);
                    }
                });
            }
        } );
    }

}


async function getRegistrosAPI(codDepart){
 
    try {

        let resultado = '';

        if (codDepart){
            resultado = await fetch(rutaServidor + 'ciudad' + "/api?codDepart=" + codDepart);
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