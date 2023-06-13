import { formatearTexto, mostrarOcultarSugerencias, 
    ocultarError, limpiarCaja } from './class/Parametros.js';
import { EntidadUniCampo, objetoUniCampo } from './class/ModelUniCampo.js';
import { Modal } from './class/Modal.js';
import {
    EntidadMultiCampo,
    objetoMultiCampo,
    reglaInput,
    mensajeSugerencia,
    estadoCampo,
    borrarFilas,
    mostrarRegistrosAPI,
    btnSubmit
} from './class/ModelMultiCampo.js';
import { RegistrosItems, cerrarPreview } from './class/RegistrosItems.js';


let producto = new EntidadMultiCampo();

function botonesSubmit() {
    const submitFormularioProducto = document.querySelector(`form[action="/producto"] input[type="submit"]`);
    const submitformularioMarca = document.querySelector(`form[action="/marca"] input[type="submit"]`);
    const submitformularioCategoria = document.querySelector(`form[action="/categoria"] input[type="submit"]`);

    if (submitFormularioProducto) {
        submitFormularioProducto.addEventListener('click', btnSubmit);
    }
    if (submitformularioMarca) {
        submitformularioMarca.addEventListener('click', btnSubmit);
    }
    if (submitformularioCategoria) {
        submitformularioCategoria.addEventListener('click', btnSubmit);
    }
}
// limpia el campo y agrega algunas funcionalidades según algunas condiciones
function botonLimpiar() {

    const btnLimpiar = document.querySelectorAll('.form__limpiar');

    //obtenemos los 3 campos por los cuales se filtra la informacion
    const txtCodBarras = document.querySelector(`[data-tipo="codigoBarras"]`);
    const txtCodManual = document.querySelector(`[data-tipo="codigoManual"]`);
    const txtDescripcion = document.querySelector(`[data-tipo="prodDescripcion"]`);

    btnLimpiar.forEach(boton => {
        boton.addEventListener('click', (e) => {
            e.preventDefault();
            limpiarCaja(boton.parentElement);

            if (txtCodBarras.value == '' || txtCodManual.value == '' || txtDescripcion.value == '') {

                borrarFilas();
                mostrarRegistrosAPI();

                if (txtCodManual.value == '') {
                    estadoCampo('codigoManual', false);
                }

                if (txtDescripcion.value == '') {
                    estadoCampo('prodDescripcion', false);
                }
            }
        });
    });
}
// Agregamos las propiedades y los valores a los objetos
function renombrarObjetos() {

    objetoMultiCampo.nombreTabla = 'producto';

    objetoMultiCampo.datosTabla.nombre = "producto";
    objetoMultiCampo.datosTabla.tipo = "";

    objetoMultiCampo.camposIndividuales = ['codigoBarras', 'codigoManual', 'prodDescripcion'];
    
    if (!window.location.pathname.includes('editar')){

        objetoMultiCampo.tabla = [

            { id: 'Id', posicion: null, class: [] },
            { codigoManual: 'Cod. Manual', posicion: 1, class: [] },
            { codigoBarras: 'Cod. Barras', posicion: null, class: [] },
            { descripcion: 'Descripción', posicion: 0, class: [] },
            { categoria: 'Categoría', posicion: 2, class: [] },
            { marca: 'Marca', posicion: 3, class: [] },
            { valorVenta: 'Valor Venta', posicion: 5, class: ['tbody__td--center', 'tbody__td--verde'] },
            { valorDescuento: 'Valor Descuento', posicion: 6, class: ['tbody__td--center', 'tbody__td--rojo'] },
            { cantMinStock: 'Cant Min. Stock', posicion: 4, class: ['tbody__td--center'] },
            { cantOferta: 'Cant. Oferta', posicion: 7, class: ['tbody__td--center', 'tbody__td--azul'] },
            { valorOrferta: 'Valor Oferta', posicion: 8, class: ['tbody__td--center', 'tbody__td--azul'] },
            { estado: 'Estado', posicion: 9, class: [] }

        ];
    }

    // objetoMultiCampo.campos = {};
    objetoMultiCampo.reglas = {};
    objetoMultiCampo.sugerencias = {};

    objetoMultiCampo.arrayAPI = {

        Prod_Descripcion: [],
        Cod_Manual: [],
        Cod_Barras: []

    };

    objetoMultiCampo.stringAPI = {

        Prod_Descripcion: '',
        Cod_Manual: '',
        Cod_Barras: ''

    };

    objetoMultiCampo.convertirMinusculas = ['Prod_Descripcion'];

    // Asignar las propiedades al objeto
    objetoMultiCampo.campos.codigoManual = false;
    objetoMultiCampo.campos.prodDescripcion = false;
    objetoMultiCampo.campos.categoria = false;
    objetoMultiCampo.campos.marca = false;
    objetoMultiCampo.campos.cantStock = false;
    objetoMultiCampo.campos.valorVenta = false;

    //Asignar la validació al objeto reglas
    objetoMultiCampo.reglas.codigoBarras = "^[0-9A-Za-z]{4,20}$";
    objetoMultiCampo.reglas.codigoManual = "^[0-9A-Za-z]{4,20}$";
    objetoMultiCampo.reglas.prodDescripcion = "^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{4,100}$";
    objetoMultiCampo.reglas.cantStock = "^[0-9]{1,3}$";
    objetoMultiCampo.reglas.valorVenta = "^[0-9]{2,6}$";
    objetoMultiCampo.reglas.valorDescuento = "^[0-9]{2,6}$";
    objetoMultiCampo.reglas.cantOferta = "^[0-9]{1,3}$";
    objetoMultiCampo.reglas.valorOferta = "^[0-9]{2,6}$";
    objetoMultiCampo.reglas.prodObservaciones = "^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{4,100}$";

    //Asignar la validación al objeto mensajes de sugerencia
    objetoMultiCampo.sugerencias.codigoBarras = "Este campo es alfanumérico, mayor a 4 caracteres y no puede contener espacios ni caracteres especiales";
    objetoMultiCampo.sugerencias.codigoManual = "Este campo es alfanumérico, mayor a 4 caracteres y no puede contener espacios ni caracteres especiales";
    objetoMultiCampo.sugerencias.prodDescripcion = "Este campo es alfanumérico, no se permite caractéres especiales, y debe tener más de 4 caracteres";
    objetoMultiCampo.sugerencias.categoria = "Debe seleccionar una categoría";
    objetoMultiCampo.sugerencias.marca = "Debe seleccionar una marca";
    objetoMultiCampo.sugerencias.cantStock = "Este campo solo puede contener números, no mayor a 3 digitos";
    objetoMultiCampo.sugerencias.valorVenta = "Este campo solo puede contener números, no mayor a 6 dígitos";
    objetoMultiCampo.sugerencias.valorDescuento = "Este campo solo puede contener números, no mayor a 6 dígitos";
    objetoMultiCampo.sugerencias.cantOferta = "Este campo solo puede contener números, no mayor a 3 digitos";
    objetoMultiCampo.sugerencias.valorOferta = "Este campo solo puede contener números, no mayor a 6 dígitos";
    objetoMultiCampo.sugerencias.prodObservaciones = "Este campo es alfanumérico, mayor a 10 caracteres y no puede contener espacios ni caracteres especiales";

}

function campoCodigoBarras() {
    const txtCodigoBarras = document.querySelector('#codigoBarras');
    const labelSugr = txtCodigoBarras.parentElement.querySelector('.form__labelSugerencia');
    const labelError = txtCodigoBarras.parentElement.querySelector('.form__labelError');
    const iconoError = txtCodigoBarras.parentElement.querySelector('.form__iconError');
    const txtCodigoManual = document.querySelector('#codigoManual');

    let expresionRegular;
    let tipo = txtCodigoBarras.getAttribute('data-tipo');
    let regla = reglaInput(tipo);
    let msg = mensajeSugerencia(tipo);

    txtCodigoBarras.addEventListener('input', e => {

        // Convertir a mayusculas el contenido
        txtCodigoBarras.value = e.target.value.toUpperCase();

        // Agregamos de forma automática el código manual 
        let texto = e.target.value;

        if (e.target.value.length > 5) {
            
            let conteo = parseInt(e.target.value.length) - 6;
            let stringCortado = e.target.value.substr(conteo, 6);
            //  Convertir a mayusculas el contenido
            txtCodigoManual.value = stringCortado.toUpperCase();

            // ----------------------------------------------------

            let expresionRegular2;
            let tipo2 = txtCodigoManual.getAttribute('data-tipo');
            let regla2 = reglaInput(tipo2);
            let msg2 = mensajeSugerencia(tipo2);
            expresionRegular2 = txtCodigoManual.value.match(regla2);
            let labelSugr2 = txtCodigoManual.nextElementSibling.nextElementSibling.nextElementSibling;

            // Si cumple con la expresión regular
            if (expresionRegular2) {

                // Si coincide la cadena de forma completa se muestra la alerta que este código ya se encuentra registrado
                if (objetoMultiCampo.arrayAPI.Cod_Manual.includes(txtCodigoManual.value)) {

                    mostrarOcultarSugerencias(labelSugr2, 'Este código ya se encuentra registrado', true);

                } else {
                    // Si no coincide en su totalidad con algún registro entonces quitamos la alerta
                    mostrarOcultarSugerencias(labelSugr2, '', false);
                    estadoCampo('codigoManual', true);
                }

            } else {
                mostrarOcultarSugerencias(labelSugr2, msg2, true);
                estadoCampo('codigoManual', false);
            }


        } else {

            txtCodigoManual.value = "";
            limpiarCaja(txtCodigoManual.parentElement, false);
            estadoCampo('codigoManual', false);

        }

        //buscamos los registros que coincidan
        expresionRegular = e.target.value.match(regla);

        // Si cumple con la expresión regular
        if (expresionRegular) {

            // Se elimina cualquier sugerencia que se haya generado
            mostrarOcultarSugerencias(labelSugr, '', false);
            // console.log(objetoMultiCampo.stringAPI.Cod_Barras.includes(txtCodigoBarras.value));
            if (objetoMultiCampo.stringAPI.Cod_Barras.includes(txtCodigoBarras.value)) {

                // Se mostrarán los registros que coinciden de forma parcial o completa los registros
                borrarFilas();
                mostrarRegistrosAPI('input', 'Cod_Barras', e.target.value);


            } else {

                //Si no coincide la busqueda se muestran todos los registros de la base de datos
                borrarFilas();
                mostrarRegistrosAPI();

            }

        } else {
            // Si no cumple con la expresión regular se muestra la sugerencia
            mostrarOcultarSugerencias(labelSugr, msg, true);
            // Se muestran todos los registros porque no cumple con el requisito principal de la validación por expresión regular
            borrarFilas();
            mostrarRegistrosAPI();
        }

        // Ocultar los errores de la base de datos en caso de que se muestren en pantalla
        if (labelError && labelError.textContent != '') {
            ocultarError(labelError, iconoError);
        }
    });
}

function campoCodigoManual() {

    const txtCodigoManual = document.querySelector(`[data-tipo="codigoManual"]`);
    const labelSugr = txtCodigoManual.parentElement.querySelector('.form__labelSugerencia');
    const labelError = txtCodigoManual.parentElement.querySelector('.form__labelError');
    const iconoError = txtCodigoManual.parentElement.querySelector('.form__iconError');

    let expresionRegular;
    let tipo = txtCodigoManual.getAttribute('data-tipo');
    let regla = reglaInput(tipo);
    let msg = mensajeSugerencia(tipo);

    txtCodigoManual.addEventListener('input', e => {

        // Convertir a mayusculas el contenido
        txtCodigoManual.value = e.target.value.toUpperCase();

        expresionRegular = e.target.value.match(regla);

        // Si cumple con la expresión regular
        if (expresionRegular) {

            // Se elimina cualquier sugerencia que se haya generado
            mostrarOcultarSugerencias(labelSugr, '', false);
            estadoCampo('codigoManual', true);

            // Verificamos si coincide de forma parcial o completa
            if (objetoMultiCampo.stringAPI.Cod_Manual.includes(e.target.value)) {

                // Si coincide la cadena de forma completa se muestra la alerta que este código ya se encuentra registrado
                if (objetoMultiCampo.arrayAPI.Cod_Manual.includes(e.target.value)) {

                    mostrarOcultarSugerencias(labelSugr, 'Este código ya se encuentra registrado', true);

                } else {
                    // Si no coincide en su totalidad con algún registro entonces quitamos la alerta
                    mostrarOcultarSugerencias(labelSugr, '', false);
                }

                // Se mostrarán los registros que coinciden de forma parcial o completa los registros
                borrarFilas();
                mostrarRegistrosAPI('input', 'Cod_Manual', e.target.value);

            } else {
                //Si no coincide la busqueda se muestran todos los registros de la base de datos
                borrarFilas();
                mostrarRegistrosAPI();
            }

        } else {

            // Si no cumple con la expresión regular se muestra la sugerencia
            estadoCampo('codigoManual', false);
            mostrarOcultarSugerencias(labelSugr, msg, true);
            // Se muestran todos los registros porque no cumple con el requisito principal de la validación por expresión regular
            borrarFilas();
            mostrarRegistrosAPI();
        }

        // Ocultar los errores de la base de datos en caso de que se muestren en pantalla
        if (labelError && labelError.textContent != '') {
            ocultarError(labelError, iconoError);
        }
    });
}

function campoDescripcion() {

    const txtDescripcion = document.querySelector(`[data-tipo="prodDescripcion"]`);
    const labelSugr = txtDescripcion.parentElement.querySelector('.form__labelSugerencia');
    const labelError = txtDescripcion.parentElement.querySelector('.form__labelError');
    const iconoError = txtDescripcion.parentElement.querySelector('.form__iconError');

    let expresionRegular;
    let tipo = txtDescripcion.getAttribute('data-tipo');
    let regla = reglaInput(tipo);
    let msg = mensajeSugerencia(tipo);

    txtDescripcion.addEventListener('input', e => {

        // Convertir a minúsculas el contenido
        let texto = formatearTexto(e.target.value.toLowerCase());

        expresionRegular = e.target.value.match(regla);

        // Si cumple con la expresión regular
        if (expresionRegular) {
            // Se elimina cualquier sugerencia que se haya generado
            mostrarOcultarSugerencias(labelSugr, '', false);
            // Verificamos si coincide de forma parcial o completa
            if (objetoMultiCampo.stringAPI.Prod_Descripcion.includes(texto)) {

                // Si coincide la cadena de forma completa se muestra la alerta que este código ya se encuentra registrado
                if (objetoMultiCampo.arrayAPI.Prod_Descripcion.includes(texto)) {

                    mostrarOcultarSugerencias(labelSugr, 'Este producto ya se encuentra registrado', true);
                    estadoCampo('prodDescripcion', false);
                } else {
                    // Si no coincide en su totalidad con algún registro entonces quitamos la alerta
                    mostrarOcultarSugerencias(labelSugr, '', false);
                    estadoCampo('prodDescripcion', true);
                }

                // Se mostrarán los registros que coinciden de forma parcial o completa los registros
                borrarFilas();
                mostrarRegistrosAPI('input', 'Prod_Descripcion', texto);


            } else {

                //Si no coincide la busqueda se muestran todos los registros de la base de datos
                estadoCampo('prodDescripcion', true);
                borrarFilas();
                mostrarRegistrosAPI();

            }

        } else {
            // Si no cumple con la expresión regular se muestra la sugerencia
            estadoCampo('prodDescripcion', false);
            mostrarOcultarSugerencias(labelSugr, msg, true);
            // Se muestran todos los registros porque no cumple con el requisito principal de la validación por expresión regular
            borrarFilas();
            mostrarRegistrosAPI();

        }

        // Ocultar los errores de la base de datos en caso de que se muestren en pantalla
        if (labelError && labelError.textContent != '') {
            ocultarError(labelError, iconoError);
        }
    });
}

function campoDescripcionEditar() {

    const txtDescripcion = document.querySelector(`[data-tipo="prodDescripcion"]`);
    const labelSugr = txtDescripcion.parentElement.querySelector('.form__labelSugerencia');
    const labelError = txtDescripcion.parentElement.querySelector('.form__labelError');
    const iconoError = txtDescripcion.parentElement.querySelector('.form__iconError');

    let expresionRegular;
    let tipo = txtDescripcion.getAttribute('data-tipo');
    let regla = reglaInput(tipo);
    let msg = mensajeSugerencia(tipo);

    txtDescripcion.addEventListener('input', e => {

        expresionRegular = e.target.value.match(regla);

        // Si cumple con la expresión regular
        if (expresionRegular) {
            // Se elimina cualquier sugerencia que se haya generado
            estadoCampo('prodDescripcion', true);
            mostrarOcultarSugerencias(labelSugr, '', false);

        } else {
            // Si no cumple con la expresión regular se muestra la sugerencia
            estadoCampo('prodDescripcion', false);
            mostrarOcultarSugerencias(labelSugr, msg, true);
        }

        // Ocultar los errores de la base de datos en caso de que se muestren en pantalla
        if (labelError && labelError.textContent != '') {
            ocultarError(labelError, iconoError);
        }
    });
}

function cargarCombosDatos(){
        //Cargar el ComboBox de Categoría 
        objetoUniCampo.entidad = "categoria";
        let categoria = new EntidadUniCampo;
        categoria.cargarComboBox();
    
        // Cargar el comboBox de Marca
        objetoUniCampo.entidad = "marca";
        let marca = new EntidadUniCampo;
        marca.cargarComboBox();
}

function cargarVentanasModal(){
    let marcaModal = new Modal("marca",
    "^[A-Z0-9Ña-züñáéíóúÁÉÍÓÚÜ'° ]{2,50}$",
    "Solo debe contener letras, mayor a 2 caracteres",
    "Mc_Descripcion");
    marcaModal.mostrarModal();
    marcaModal.getRegistrosAPI();
    marcaModal.cargarRegistros();
    marcaModal.asignarValidacion();
    
    let categoriaModal = new Modal("categoria",
    "^[A-Z0-9Ña-züñáéíóúÁÉÍÓÚÜ'° ]{2,50}$",
    "Solo debe contener letras, mayor a 2 caracteres",
    "Ctg_Descripcion");
    categoriaModal.mostrarModal();
    categoriaModal.getRegistrosAPI();
    categoriaModal.cargarRegistros();
    categoriaModal.asignarValidacion();
}

function cargarRegistrosCodigos(){

    let registrosCodigos = new RegistrosItems(
        {
            entidad: "productocodigos",
            estadoCampos: {
                Cod_Manual: false,
                Cod_Barras: false
            },
            nombreTabla: "tblCodigo",
            camposTabla: [
                { id: 'Id', posicion: null, class: [] },
                { codigoBarras: 'Cod. Barras', posicion: 0, class: [] },
                { codigoManual: 'Cod. Manual', posicion: 1, class: [] }
            ],
            arrayAPI: {
                Cod_Manual: [],
                Cod_Barras: []
            },
            stringAPI:{
                Cod_Manual: '',
                Cod_Barras: ''
            },
            boton:document.querySelector('[data-tipo="boton-codigo"]')
        }
    );
    
    const params = new URLSearchParams(window.location.search);
    // Obtiene el valor de la variable "variable"
    const valorId = params.get('id');
    
    registrosCodigos.obtenerRegistros(valorId);
    // pasamos por parámentro los nombres de los campos a cambiar en false
    //y luego obtener su caja de texto para limpiarla
    registrosCodigos.botonLimpiar(['Cod_Manual', 'Cod_Barras']);
    
    const txtCodigoBarras = document.querySelector('#Cod_Manual');
    const txtCodigoManual = document.querySelector('#Cod_Barras');
    const labelSugrCodBarras = txtCodigoBarras.parentElement.querySelector('.form__labelSugerencia');
    const labelSugrCodManual = txtCodigoManual.parentElement.querySelector('.form__labelSugerencia');
    const labelErrorCodManual = txtCodigoManual.parentElement.querySelector('.form__labelError');
    const iconoErrorCodManual = txtCodigoManual.parentElement.querySelector('.form__iconError');
    const txtId = document.querySelector('[data-id="prod_Id"]');
    const botonAgregar = document.querySelector('[data-tipo="boton-codigo"]');

    let expresionRegular2;
    let regla2 = "^[0-9A-Za-z]{4,20}$";
    let msg2 = "Este campo es alfanumérico, mayor a 5 caracteres y no puede contener espacios ni caracteres especiales";
    
    // nueva funcionalidad del campo codigo de barras 
    txtCodigoBarras.addEventListener('input', e => {

        txtCodigoBarras.value = e.target.value.toUpperCase();

        if (e.target.value.length > 5) {
            
            let conteo = parseInt(e.target.value.length) - 6;
            let stringCortado = e.target.value.substr(conteo, 6);

            //  Convertir a mayusculas el contenido
            txtCodigoManual.value = stringCortado;

            expresionRegular2 = txtCodigoManual.value.match(regla2);

            // Si cumple con la expresión regular
            if (expresionRegular2) {

                mostrarOcultarSugerencias(labelSugrCodBarras, '', false);
                // Si coincide la cadena de forma completa se muestra la alerta que este código ya se encuentra registrado
                if (registrosCodigos.arrayAPI.Cod_Manual.includes(txtCodigoManual.value)) {

                    mostrarOcultarSugerencias(labelSugrCodManual, 'Este código ya se encuentra registrado', true);
                    registrosCodigos.onOffBoton('Cod_Manual', false);
                    registrosCodigos.onOffBoton('Cod_Barras', false);

                } else {
                    // Si no coincide en su totalidad con algún registro entonces quitamos la alerta
                    mostrarOcultarSugerencias(labelSugrCodManual, '', false);
                    registrosCodigos.onOffBoton('Cod_Manual', true);
                    registrosCodigos.onOffBoton('Cod_Barras', true);
                }

            } else {
                mostrarOcultarSugerencias(labelSugrCodBarras, msg2, true);
                registrosCodigos.onOffBoton('Cod_Manual', false);
                registrosCodigos.onOffBoton('Cod_Barras', false);
            }
            
            
        } else {
            
            mostrarOcultarSugerencias(labelSugrCodBarras, msg2, true);
            limpiarCaja(txtCodigoManual.parentElement, false);
            registrosCodigos.onOffBoton('Cod_Manual', false);
            registrosCodigos.onOffBoton('Cod_Barras', false);

        }
    });

    // nueva funcionalidad del campo codigo manual para buscar registros 
    let expresionRegular;
    let regla = "^[0-9A-Za-z]{4,20}$";
    let msg = "Este campo es alfanumérico, mayor a 3 caracteres y no puede contener espacios ni caracteres especiales";

    txtCodigoManual.addEventListener('input', e => {

        txtCodigoManual.value = e.target.value.toUpperCase();

        expresionRegular = txtCodigoManual.value.match(regla);

        if (expresionRegular) {
            
            if (registrosCodigos.arrayAPI.Cod_Manual.includes(e.target.value)) {
                
                mostrarOcultarSugerencias(labelSugrCodManual, 'Este código ya se encuentra registrado', true);
                registrosCodigos.onOffBoton('Cod_Manual', false);
                
            } else {
                // Si no coincide en su totalidad con algún registro entonces quitamos la alerta
                mostrarOcultarSugerencias(labelSugrCodManual, '', false);
                registrosCodigos.onOffBoton('Cod_Manual', true);
            }
            
        } else {
            
            // Si no cumple con la expresión regular se muestra la sugerencia
            mostrarOcultarSugerencias(labelSugrCodManual, msg, true);
            registrosCodigos.onOffBoton('Cod_Manual', false);
        }

        // Ocultar los errores de la base de datos en caso de que se muestren en pantalla
        if (labelErrorCodManual && labelErrorCodManual.textContent != '') {
            ocultarError(labelErrorCodManual, iconoErrorCodManual);
        }

    });

    botonAgregar.addEventListener('click', () => {
        
        // Se contempla la opción que ninguna oferta exista por lo tanto se crea el objeto desde 0
        const objetoDatos = {};
        
        objetoDatos.Cod_Manual = txtCodigoManual.value;
        objetoDatos.Cod_Barras = txtCodigoBarras.value;
        objetoDatos.Cod_FkProd_Id = txtId.value;

        registrosCodigos.guardarRegistro(objetoDatos, valorId);

        // Limpiar cajas de texto tanto el contenido como los mensajes
        limpiarCaja(txtCodigoManual.parentElement, false);
        limpiarCaja(txtCodigoBarras.parentElement, true);

        // Estado de campos y deshabilitar el boton
        registrosCodigos.onOffBoton('Cod_Manual', false);
        registrosCodigos.onOffBoton('Cod_Barras', false);

    });

}

function cargarRegistrosOfertas(){

    let registrosOfertas = new RegistrosItems(
        {
            entidad:"productoofertas",
            estadoCampos: {
                PO_Cant: false,
                PO_ValorOferta: false
            },
            nombreTabla:"tblOferta",
            camposTabla:[
                { id: 'Id', posicion: null, class: [] },
                { cant: 'Cantidad', posicion: 0, class: [] },
                { valorOferta: 'Valor Oferta', posicion: 1, class: [] }
            ],
            arrayAPI:{
                Cant: [],
                Valor_Oferta: []
            },
            stringAPI:{
                Cant: '',
                Valor_Oferta: ''
            },
            reglas:{
                // Se colocan estos nombres de llaves porque así se encuentran en el id del formulario
                PO_Cant:"^[0-9]{1,3}$",
                PO_ValorOferta:"^[0-9]{2,6}$"
            },
            sugerencias:{
                // Mensajes de error
                PO_Cant : "Este campo solo puede contener números, no mayor a 3 digitos",
                PO_ValorOferta : "Este campo solo puede contener números, no mayor a 6 dígitos"
            },
            // Se envia el boton solamente para activarlo o desactivarlo de acuerdo a lo digitado por el usuario
            boton : document.querySelector('[data-tipo="boton-oferta"]')
        }
    );

    const params = new URLSearchParams(window.location.search);
    // Obtiene el valor de la variable "variable"
    const valorId = params.get('id');
    
    registrosOfertas.obtenerRegistros(valorId);
    
    // pasamos por parámetro los id de los elementos HTML 
    registrosOfertas.asignarValidacion(['PO_Cant', 'PO_ValorOferta']);
    // pasamos por parámentro los nombres de los campos a cambiar en false
    //y luego obtener su caja de texto para limpiarla
    registrosOfertas.botonLimpiar(['PO_Cant', 'PO_ValorOferta']);
    
    // Obtenemos las cajas del formulario
    const botonAgregar = document.querySelector('[data-tipo="boton-oferta"]');
    const txtCantidad = document.querySelector('#PO_Cant');
    const txtValor = document.querySelector('#PO_ValorOferta');
    const txtId = document.querySelector('[data-id="prod_Id"]');

    botonAgregar.addEventListener('click', () => {
        
        // Se contempla la opción que ninguna oferta exista por lo tanto se crea el objeto desde 0
        const objetoDatos = {};
        
        objetoDatos.PO_Cant = txtCantidad.value;
        objetoDatos.PO_ValorOferta = txtValor.value;
        objetoDatos.PO_FkProducto_Id = txtId.value;

        registrosOfertas.guardarRegistro(objetoDatos, valorId);

        // Limpiar cajas de texto tanto el contenido como los mensajes
        limpiarCaja(txtValor.parentElement, false);
        limpiarCaja(txtCantidad.parentElement, true);

        // Estado de campos y deshabilitar el boton
        registrosOfertas.onOffBoton('PO_Cant', false);
        registrosOfertas.onOffBoton('PO_ValorOferta', false);

    });
}

function cargarArchivos(){
    
    let registrosArchivos = new RegistrosItems(
        {
            entidad:'productoimgvideo',
            nombreTabla:'',
            camposTabla:'',
            arrayAPI:'',
            stringAPI:''
        }
    );

    const params = new URLSearchParams(window.location.search);
    // Obtiene el valor de la variable "variable"
    const valorId = params.get('id');
    
    registrosArchivos.getFiles(valorId);
    cerrarPreview();

}

document.addEventListener('DOMContentLoaded', () => {

    cargarCombosDatos();
    cargarVentanasModal();
    
    if (window.location.pathname.includes('editar')){

        Swal.fire({
            title: 'Cargando registros...',
            showConfirmButton: false,
            didOpen: () => {

                Swal.showLoading();
                cargarRegistrosCodigos();
                cargarRegistrosOfertas();
                cargarArchivos();

                setTimeout(() => {
                    Swal.close();
                }, 500);

            }
        });

        campoDescripcionEditar();

    }else{

        objetoMultiCampo.entidad = "producto";
        renombrarObjetos();
        producto.asignarValidacion();
        producto.verificarMensajeGeneral();
        producto.managerAlert();
        producto.botonAdjuntarArchivo();
        producto.campoFile();
        botonesSubmit();
        botonesLimpiar();
        botonLimpiar();
        campoCodigoBarras();
        campoCodigoManual();
        campoDescripcion();
        producto.estadoRegistro();
    }

});



