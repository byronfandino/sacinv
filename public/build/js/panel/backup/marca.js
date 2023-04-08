import {globales,
    managerAlert,
    verificarMensajeGeneral,
    eventListeners,
    botonStatus,
    estadoBoton,
    botonLimpiar} from './class/ModelUniCampo.js';

globales.url = 'http://192.168.18.120:3000/marca';
globales.expresion = "^[A-Z0-9Ña-züñáéíóúÁÉÍÓÚÜ'° ]{2,50}$";
globales.msg = "Solo debe contener letras, mayor a 2 caracteres";
globales.entidad = "marca";
//-----------------------------------

// Verificación de alertas
managerAlert();
verificarMensajeGeneral();

// Carga de funciones de forma directa de la pagina
eventListeners();
botonStatus();
estadoBoton();
botonLimpiar();
