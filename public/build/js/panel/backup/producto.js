import {globales, cargarComboBox } from './class/ModelUniCampo.js';

import { objeto,
    reglas,
    globalVariables,
    reglaInput, 
    sugerencias,
    mensajeSugerencia,
    estadoCampo,
    limpiarCaja,
    mostrarOcultarSugerencias,
    borrarFilas,
    mostrarRegistrosAPI,
    ocultarError,
    formatearTexto,
    asignarValidacion,
    verificarMensajeGeneral,
    managerAlert,
    botonAdjuntarArchivo,
    campoFile,
    botonStatus,
    btnSubmit
 } from './class/ModelMultiCampo.js'; 

document.addEventListener('DOMContentLoaded', () =>{
    // Estos son los campos que tienen un tratamiento diferente
    globalVariables.camposIndividuales = ['codigoBarras', 'codigoManual', 'prodDescripcion']
    globalVariables.tabla = [
                             {id:'Id', posicion : null, class:[]}, 
                             {codigoManual : 'Cod. Manual', posicion: 1, class:[]}, 
                             {codigoBarras : 'Cod. Barras', posicion: null, class:[]}, 
                             {descripcion : 'Descripción', posicion: 0, class:[]}, 
                             {categoria : 'Categoría', posicion: 2, class:[]}, 
                             {marca : 'Marca', posicion: 3, class:[]}, 
                             {valorVenta : 'Valor Venta', posicion: 5, class:['tbody__td--center','tbody__td--verde']}, 
                             {valorDescuento : 'Valor Descuento', posicion: 6, class:['tbody__td--center','tbody__td--rojo']}, 
                             {cantMinStock : 'Cant Min. Stock', posicion: 4, class:['tbody__td--center']}, 
                             {cantOferta : 'Cant. Oferta', posicion: 7, class:['tbody__td--center','tbody__td--azul']}, 
                             {valorOrferta : 'Valor Oferta', posicion: 8, class:['tbody__td--center','tbody__td--azul']}, 
                             {estado: 'Estado', posicion: 9, class:[]}
                            ];

    //Cargar el ComboBox de Categoría 
    globales.url = 'http://192.168.18.120:3000/categoria';
    globales.entidad = "categoria";

    cargarComboBox();

    // Cargar el comboBox de Marca
    globales.url = 'http://192.168.18.120:3000/marca';
    globales.entidad = "marca";

    cargarComboBox();

    globalVariables.url = 'http://192.168.18.120:3000/producto';

    renombrarObjetos();

    asignarValidacion();
    botonesSubmit();

    verificarMensajeGeneral();
    managerAlert();
    botonLimpiar();
    botonAdjuntarArchivo();
    campoFile();
    botonStatus();

    campoCodigoBarras();
    campoCodigoManual();
    campoDescripcion();
});

// Agregamos las propiedades y los valores a los objetos
function renombrarObjetos(){
    // Asignar las propiedades al objeto
    objeto.codigoManual=false;
    objeto.prodDescripcion=false;
    objeto.categoria=false;
    objeto.marca=false;
    objeto.cantStock=false;
    objeto.valorVenta=false;

    //Asignar la validació al objeto reglas
    reglas.codigoBarras = "^[0-9A-Za-z]{4,20}$";
    reglas.codigoManual = "^[0-9A-Za-z]{4,20}$";
    reglas.prodDescripcion = "^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{4,100}$";
    reglas.cantStock = "^[0-9]{1,3}$";
    reglas.valorVenta = "^[0-9]{2,6}$";
    reglas.valorDescuento = "^[0-9]{2,6}$";
    reglas.cantOferta = "^[0-9]{1,3}$";
    reglas.valorOferta = "^[0-9]{2,6}$";
    reglas.prodObservaciones = "^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{4,100}$";

    //Asignar la validación al objeto mensajes de sugerencia
    sugerencias.codigoBarras = "Este campo es alfanumérico, mayor a 4 caracteres y no puede contener espacios ni caracteres especiales";
    sugerencias.codigoManual = "Este campo es alfanumérico, mayor a 4 caracteres y no puede contener espacios ni caracteres especiales";
    sugerencias.prodDescripcion = "Este campo es alfanumérico, no se permite caractéres especiales, y debe tener más de 4 caracteres";
    sugerencias.categoria = "Debe seleccionar una categoría";
    sugerencias.marca = "Debe seleccionar una marca";
    sugerencias.cantStock = "Este campo solo puede contener números, no mayor a 3 digitos";
    sugerencias.valorVenta = "Este campo solo puede contener números, no mayor a 6 dígitos";
    sugerencias.valorDescuento = "Este campo solo puede contener números, no mayor a 6 dígitos";
    sugerencias.cantOferta = "Este campo solo puede contener números, no mayor a 3 digitos";
    sugerencias.valorOferta = "Este campo solo puede contener números, no mayor a 6 dígitos";
    sugerencias.prodObservaciones = "Este campo es alfanumérico, mayor a 10 caracteres y no puede contener espacios ni caracteres especiales";

}

function campoCodigoBarras(){

    const txtCodigoBarras = document.querySelector('#codigoBarras');
    const labelSugr = txtCodigoBarras.parentElement.querySelector('.form__labelSugerencia');
    const labelError = txtCodigoBarras.parentElement.querySelector('.form__labelError');
    const iconoError = txtCodigoBarras.parentElement.querySelector('.form__iconError');
    const txtCodigoManual = document.querySelector('#codigoManual');

    let expresionRegular;
    let tipo = txtCodigoBarras.getAttribute('data-tipo');
    let regla = reglaInput(tipo);
    let msg = mensajeSugerencia(tipo);

    txtCodigoBarras.addEventListener('input', e =>{

        // Convertir a mayusculas el contenido
        txtCodigoBarras.value=e.target.value.toUpperCase();

        // Agregamos de forma automática el código manual 
        let texto = e.target.value;

        if (e.target.value.length > 5 ){

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
            if(expresionRegular2){

                 // Si coincide la cadena de forma completa se muestra la alerta que este código ya se encuentra registrado
                 if (globalVariables.arrayCodigoManual.includes(txtCodigoManual.value)){

                    mostrarOcultarSugerencias(labelSugr2, 'Este código ya se encuentra registrado', true);
    
                }else{
                    // Si no coincide en su totalidad con algún registro entonces quitamos la alerta
                    mostrarOcultarSugerencias(labelSugr2, '', false);
                    estadoCampo('codigoManual', true);
                }
                
            }else{
                mostrarOcultarSugerencias(labelSugr2, msg2, true);
                estadoCampo('codigoManual', false);
            }
      

        }else{

            txtCodigoManual.value = "";
            limpiarCaja(txtCodigoManual.parentElement, false);
            estadoCampo('codigoManual', false);

        }

        //buscamos los registros que coincidan
        expresionRegular = e.target.value.match(regla);

        // Si cumple con la expresión regular
        if(expresionRegular){
            
            // Se elimina cualquier sugerencia que se haya generado
            mostrarOcultarSugerencias(labelSugr, '', false);

            if(globalVariables.stringCodigoBarras.includes(txtCodigoBarras.value)){

                // Se mostrarán los registros que coinciden de forma parcial o completa los registros
                borrarFilas();
                mostrarRegistrosAPI( 'input', 'Cod_Barras', e.target.value);
    
            }else{

                //Si no coincide la busqueda se muestran todos los registros de la base de datos
                borrarFilas();
                mostrarRegistrosAPI();
            }

        }else{
            // Si no cumple con la expresión regular se muestra la sugerencia
            mostrarOcultarSugerencias(labelSugr, msg, true);
            // Se muestran todos los registros porque no cumple con el requisito principal de la validación por expresión regular
            borrarFilas();
            mostrarRegistrosAPI();
        }

        // Ocultar los errores de la base de datos en caso de que se muestren en pantalla
        if(labelError && labelError.textContent != ''){
            ocultarError(labelError, iconoError);
        }
    });
}

function campoCodigoManual(){

    const txtCodigoManual = document.querySelector(`[data-tipo="codigoManual"]`);
    const labelSugr = txtCodigoManual.parentElement.querySelector('.form__labelSugerencia');
    const labelError = txtCodigoManual.parentElement.querySelector('.form__labelError');
    const iconoError = txtCodigoManual.parentElement.querySelector('.form__iconError');

    let expresionRegular;
    let tipo = txtCodigoManual.getAttribute('data-tipo');
    let regla = reglaInput(tipo);
    let msg = mensajeSugerencia(tipo);

    txtCodigoManual.addEventListener('input', e =>{

        // Convertir a mayusculas el contenido
        txtCodigoManual.value=e.target.value.toUpperCase();

        expresionRegular = e.target.value.match(regla);

        // Si cumple con la expresión regular
        if(expresionRegular){

            // Se elimina cualquier sugerencia que se haya generado
            mostrarOcultarSugerencias(labelSugr, '', false);
            estadoCampo('codigoManual', true);

            // Verificamos si coincide de forma parcial o completa
            if(globalVariables.stringCodigoManual.includes(e.target.value)){

                // Si coincide la cadena de forma completa se muestra la alerta que este código ya se encuentra registrado
                if (globalVariables.arrayCodigoManual.includes(e.target.value)){

                    mostrarOcultarSugerencias(labelSugr, 'Este código ya se encuentra registrado', true);
    
                }else{
                    // Si no coincide en su totalidad con algún registro entonces quitamos la alerta
                    mostrarOcultarSugerencias(labelSugr, '', false);
                }
                
                // Se mostrarán los registros que coinciden de forma parcial o completa los registros
                borrarFilas();
                mostrarRegistrosAPI( 'input', 'Cod_Manual', e.target.value);
    
            }else{
                //Si no coincide la busqueda se muestran todos los registros de la base de datos
                borrarFilas();
                mostrarRegistrosAPI();
            }

        }else{

            // Si no cumple con la expresión regular se muestra la sugerencia
            estadoCampo('codigoManual', false);
            mostrarOcultarSugerencias(labelSugr, msg, true);
            // Se muestran todos los registros porque no cumple con el requisito principal de la validación por expresión regular
            borrarFilas();
            mostrarRegistrosAPI();
        }

        // Ocultar los errores de la base de datos en caso de que se muestren en pantalla
        if(labelError && labelError.textContent != ''){
            ocultarError(labelError, iconoError);
        }
    });
}

function campoDescripcion(){
    
    const txtDescripcion = document.querySelector(`[data-tipo="prodDescripcion"]`);
    const labelSugr = txtDescripcion.parentElement.querySelector('.form__labelSugerencia');
    const labelError = txtDescripcion.parentElement.querySelector('.form__labelError');
    const iconoError = txtDescripcion.parentElement.querySelector('.form__iconError');

    let expresionRegular;
    let tipo = txtDescripcion.getAttribute('data-tipo');
    let regla = reglaInput(tipo);
    let msg = mensajeSugerencia(tipo);

    txtDescripcion.addEventListener('input', e =>{

        // Convertir a minúsculas el contenido
        let texto = formatearTexto(e.target.value.toLowerCase());

        expresionRegular = e.target.value.match(regla);

        // Si cumple con la expresión regular
        if(expresionRegular){
            // Se elimina cualquier sugerencia que se haya generado
            mostrarOcultarSugerencias(labelSugr, '', false);

            // Verificamos si coincide de forma parcial o completa
            if(globalVariables.stringDescripcion.includes(texto)){

                // Si coincide la cadena de forma completa se muestra la alerta que este código ya se encuentra registrado
                if (globalVariables.arrayDescripcion.includes(texto)){

                    mostrarOcultarSugerencias(labelSugr, 'Este producto ya se encuentra registrado', true);
                    estadoCampo('prodDescripcion', false);
                }else{
                    // Si no coincide en su totalidad con algún registro entonces quitamos la alerta
                    mostrarOcultarSugerencias(labelSugr, '', false);
                    estadoCampo('prodDescripcion', true);
                }
                
                // Se mostrarán los registros que coinciden de forma parcial o completa los registros
                borrarFilas();
                mostrarRegistrosAPI( 'input', 'prodDescripcion', texto);
    
            }else{
                
                //Si no coincide la busqueda se muestran todos los registros de la base de datos
                estadoCampo('prodDescripcion', true);
                borrarFilas();
                mostrarRegistrosAPI();
            }
            
        }else{
            // Si no cumple con la expresión regular se muestra la sugerencia
            estadoCampo('prodDescripcion', false);
            mostrarOcultarSugerencias(labelSugr, msg, true);
            // Se muestran todos los registros porque no cumple con el requisito principal de la validación por expresión regular
            borrarFilas();
            mostrarRegistrosAPI();
        }

        // Ocultar los errores de la base de datos en caso de que se muestren en pantalla
        if(labelError && labelError.textContent != ''){
            ocultarError(labelError, iconoError);
        }
    });
}

function botonesSubmit(){
    const submitFormularioProducto = document.querySelector(`form[action="/producto"] input[type="submit"]`);
    const submitformularioMarca = document.querySelector(`form[action="/marca"] input[type="submit"]`);
    const submitformularioCategoria = document.querySelector(`form[action="/categoria"] input[type="submit"]`);

    if(submitFormularioProducto){
        submitFormularioProducto.addEventListener('click', btnSubmit);
    }
    if(submitformularioMarca){
        submitformularioMarca.addEventListener('click', btnSubmit);
    }
    if(submitformularioCategoria){
        submitformularioCategoria.addEventListener('click', btnSubmit);
    }    
    
}

// limpia el campo y agrega algunas funcionalidades según algunas condiciones
function botonLimpiar(){
    
    const btnLimpiar = document.querySelectorAll('.form__limpiar');

    //obtenemos los 3 campos por los cuales se filtra la informacion
    const txtCodBarras = document.querySelector(`[data-tipo="codigoBarras"]`);
    const txtCodManual = document.querySelector(`[data-tipo="codigoManual"]`);
    const txtDescripcion = document.querySelector(`[data-tipo="prodDescripcion"]`);

    btnLimpiar.forEach( boton => {
        boton.addEventListener('click', (e) => {
            e.preventDefault();
            limpiarCaja(boton.parentElement);

            if( txtCodBarras.value == '' ||  txtCodManual.value == '' ||  txtDescripcion.value == '' ){

                borrarFilas();
                mostrarRegistrosAPI();

                if(txtCodManual.value == ''){
                    estadoCampo('codigoManual', false);
                }

                if(txtDescripcion.value == ''){
                    estadoCampo('prodDescripcion', false);
                }
            }
        });
    });
}

