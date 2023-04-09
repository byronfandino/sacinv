import { EntidadUniCampo, objetoUniCampo } from './class/ModelUniCampo.js';

let categoria = new EntidadUniCampo();

objetoUniCampo.entidad = 'tabla';
objetoUniCampo.url = 'http://192.168.18.120:3000/tabla';
objetoUniCampo.expresion = "^[A-ZÑa-züñáéíóúÁÉÍÓÚÜ'°_]{5,50}$";
objetoUniCampo.msg = "Solo debe contener letras y/o guión bajo ( _ ), mayor a 4 caracteres";

categoria.managerAlert();
categoria.verificarMensajeGeneral();

// Carga de funciones de forma directa de la pagina
categoria.eventListeners();
categoria.estadoRegistro();

categoria.estadoBotonSubmit();
categoria.botonLimpiar();
