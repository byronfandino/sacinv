
import { EntidadUniCampo, objetoUniCampo } from './class/ModelUniCampo.js';

let marca = new EntidadUniCampo();

objetoUniCampo.entidad = 'marca';
// objetoUniCampo.url = 'http://192.168.18.120:3000/marca';
objetoUniCampo.expresion = "^[A-Z0-9Ña-züñáéíóúÁÉÍÓÚÜ'° ]{2,50}$";
objetoUniCampo.msg = "Solo debe contener letras, mayor a 2 caracteres";

marca.managerAlert();
marca.verificarMensajeGeneral();

// Carga de funciones de forma directa de la pagina
marca.eventListeners();
marca.estadoRegistro();

marca.estadoBotonSubmit();
marca.botonLimpiar();
