import { EntidadUniCampo, objetoUniCampo } from './class/ModelUniCampo.js';

let categoria = new EntidadUniCampo();

objetoUniCampo.entidad = 'categoria';
objetoUniCampo.url = 'http://192.168.18.120:3000/categoria';
objetoUniCampo.expresion = "^[A-Z0-9Ña-züñáéíóúÁÉÍÓÚÜ'° ]{2,50}$";
objetoUniCampo.msg = "Solo debe contener letras, mayor a 2 caracteres";

categoria.managerAlert();
categoria.verificarMensajeGeneral();

// Carga de funciones de forma directa de la pagina
categoria.eventListeners();
categoria.estadoRegistro();

categoria.estadoBotonSubmit();
categoria.botonLimpiar();
