import { EntidadUniCampo, objetoUniCampo } from './class/ModelUniCampo.js';

let categoria = new EntidadUniCampo();

objetoUniCampo.entidad = 'categoria';
objetoUniCampo.expresion = "^[A-Z0-9Ña-züñáéíóúÁÉÍÓÚÜ'° ]{2,50}$";
objetoUniCampo.msg = "Solo debe contener letras, mayor a 2 caracteres";

categoria.managerAlert();
categoria.verificarMensajeGeneral();

categoria.eventListeners();
categoria.estadoRegistro();

categoria.estadoBotonSubmit();
categoria.botonLimpiar();
