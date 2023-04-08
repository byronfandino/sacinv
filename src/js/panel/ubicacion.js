import {globales,
    managerAlert,
    verificarMensajeGeneral,
    eventListeners,
    botonStatus,
    estadoBoton,
    botonLimpiar} from './class/ModelUniCampo.js';

globales.url = 'http://192.168.18.120:3000/ubicacion';
globales.expresion = "^[A-Z0-9Ña-züñáéíóúÁÉÍÓÚÜ'° ]{4,50}$";
globales.msg = "Solo debe contener letras y/o números, mayor a 4 caracteres";
globales.entidad = "ubicacion";

// Verificación de alertas
managerAlert();
verificarMensajeGeneral();

// Carga de funciones de forma directa de la pagina
eventListeners();
botonStatus();
estadoBoton();
botonLimpiar();
