import {EntidadUniCampo} from './class/ModelUniCampo.js';

let marca = new EntidadUniCampo('http://192.168.18.120:3000/marca',
                                "^[A-Z0-9Ña-züñáéíóúÁÉÍÓÚÜ'° ]{2,50}$",
                                "Solo debe contener letras, mayor a 2 caracteres",
                                "marca", "", "", "", [], []);

// import {globales,
//     managerAlert,
//     verificarMensajeGeneral,
//     eventListeners,
//     botonStatus,
//     estadoBoton,
//     botonLimpiar} from './class/ModelUniCampo.js';

marca.managerAlert();
marca.verificarMensajeGeneral();
// managerAlert();
// verificarMensajeGeneral();

// Carga de funciones de forma directa de la pagina
eventListeners();
botonStatus();
estadoBoton();
botonLimpiar();
