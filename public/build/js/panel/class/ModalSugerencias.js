import { rutaServidor} from "./Parametros.js";

export class DatosSugerencia{
    constructor(objeto){
        this.entidad = objeto.entidad;
        this.registrosAPI='';
        this.arrayAPI = this.arrayAPI;
        this.stringAPI = objeto.stringAPI;
        this.convertirMayuscula = objeto.convertirMayuscula;

        this.reglas='';
        this.sugerencias='';
        this.datosSugerencia = objeto.datosSugerencia;
    }

    asignarBusqueda(){

    }

    obtenerRegistros(){
        let resultado = getRegistrosAPI(this.entidad);
        resultado.then((result) => {
            if (result){
              this.registrosAPI = result;
            //   this.cargarDatos();
            //   this.estadoRegistro();
                setTimeout(() => {
                    Swal.close();
                }, 500);
            }            
        }); 
    }
}


async function getRegistrosAPI(entidad){

    try {

        let resultado = await fetch(rutaServidor + entidad + '/api');
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