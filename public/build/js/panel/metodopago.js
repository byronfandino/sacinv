import { EntidadUniCampo, objetoUniCampo } from './class/ModelUniCampo.js';

let metodoPago = new EntidadUniCampo();

objetoUniCampo.entidad = 'metodo-pago';
objetoUniCampo.url = 'http://192.168.18.120:3000/metodo-pago';
objetoUniCampo.expresion = "^[A-ZÑa-züñáéíóúÁÉÍÓÚÜ'° ]{4,50}$";
objetoUniCampo.msg = "Solo debe contener letras, mayor a 4 caracteres";

metodoPago.managerAlert();
metodoPago.verificarMensajeGeneral();

// Carga de funciones de forma directa de la pagina
metodoPago.eventListeners();
metodoPago.estadoRegistro();

metodoPago.estadoBotonSubmit();
metodoPago.botonLimpiar();
