import { EntidadUniCampo, objetoUniCampo } from './class/ModelUniCampo.js';

let ubicacion = new EntidadUniCampo();

objetoUniCampo.entidad = 'ubicacion';
// objetoUniCampo.url = 'http://192.168.18.120:3000/ubicacion';
objetoUniCampo.expresion = "^[A-Z0-9Ña-züñáéíóúÁÉÍÓÚÜ'° ]{4,50}$";
objetoUniCampo.msg = "Solo debe contener letras y/o números, mayor a 4 caracteres";

ubicacion.managerAlert();
ubicacion.verificarMensajeGeneral();

// Carga de funciones de forma directa de la pagina
ubicacion.eventListeners();
ubicacion.estadoRegistro();

ubicacion.estadoBotonSubmit();
ubicacion.botonLimpiar();

