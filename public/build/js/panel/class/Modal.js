import { rutaServidor, 
         formatearTexto, 
         mostrarOcultarSugerencias, 
         estadoBoton,
        alertaErrorCampo } from "./Parametros.js";

import { validarCombo } from "./ModelMultiCampo.js";

export class Modal{

    constructor(entidad, expresion, msg, campo){

        this.entidad = entidad;
        this.expresion = expresion;
        this.msg = msg;

        this.registros = [];
        this.llaves = [];
        this.arrayCampo = [];
        this.stringCampo = '';
        this.campo = campo;
        
    }

    asignarValidacion(){
        const formulario = document.querySelector(`[data-form="${this.entidad}"]`);
        const input = formulario.querySelector(`.fondo-notificacion.${this.entidad} input`);
        const labelSugerencia = formulario.querySelector('.form__labelSugerencia');
        const labelError = formulario.querySelector('.form__labelError');
        const iconoError = formulario.querySelector('.form__iconError ');
        const iconoLimpiar = formulario.querySelector('.form__limpiar');
        const botonGuardar = formulario.querySelector('[data-button="btn-envio"]');
        const botonCancelar = formulario.querySelector('[data-button="btn-cancelar-modal"]');

        // Funcionalidad del INPUT
        input.addEventListener('input', e => {

            let expresionRegular;
            expresionRegular = e.target.value.match(this.expresion);
            let texto = formatearTexto(e.target.value);
            let existeRegistro = this.arrayCampo.includes(texto);
            
            if(expresionRegular && !existeRegistro){
                mostrarOcultarSugerencias(labelSugerencia, '', false);
                estadoBoton(botonGuardar, true);
                
            }else if(!expresionRegular && !existeRegistro){
                mostrarOcultarSugerencias(labelSugerencia, this.msg, true);
                estadoBoton(botonGuardar, false);
                
            }else if((!expresionRegular && existeRegistro)||(expresionRegular && existeRegistro)){
                mostrarOcultarSugerencias(labelSugerencia, 'Esta categoría ya se encuentra registrada', true);
                estadoBoton(botonGuardar, false);
            }
    
            if(labelError && labelError.textContent != ''){
                alertaErrorCampo(labelError, iconoError, iconoLimpiar, false);
            }
            
        });

        // Funcionalidad del boton Guardar
        botonGuardar.addEventListener('click', e => {
            
            e.preventDefault();

            let arrayValores = [];
            let obj={};

            this.llaves.forEach( llave => {
                
                if (llave.includes('Id')){
                    obj[llave]; 
                    arrayValores = [obj];
                }
                
                if (llave.includes('Descripcion')){
                    obj[llave] = input.value; 
                    arrayValores = [...arrayValores, obj];
                }
                
                if (llave.includes('Status')){
                    obj[llave] = 'E'; 
                    arrayValores = [...arrayValores, obj];
                }
            });

            Swal.fire({
                title: 'Guardando registro...',
                showConfirmButton: false,
                didOpen: () => {
                  Swal.showLoading();
                  let resultado = guardar( obj, this.entidad);
                  resultado.then((result) => {

                      if (result){
                        
                          this.cargarComboBox();
                          this.validarComboBox();
                          botonCancelar.click();
                          setTimeout(() => {
                              Swal.close();
                          }, 500);
                      }       
                  }); 
                }
            });
        });
    }

    mostrarModal(){

        const comboBox = document.querySelector(`#${this.entidad}`);
        comboBox.addEventListener('change', e => {

            if(e.target.value === ""){

                const notificacion = document.querySelector(`.fondo-notificacion.${this.entidad}`);
                const input = notificacion.querySelector('input');

                if (notificacion){
                    input.value='';
                    notificacion.classList.remove('ocultar');
                    input.focus();
                }

            }

        });
        
        const contenedor = document.querySelector(`.fondo-notificacion.${this.entidad}`);
        const input = contenedor.querySelector('input');
        input.removeAttribute('required');
        // console.log(input);
        // const formulario = document.querySelector(`[data-form="${modal}"]`);
        const btnCancelar = contenedor.querySelector('[data-button="btn-cancelar-modal"]');
    
        btnCancelar.addEventListener('click', e => {
            e.preventDefault();
            if (contenedor){
                contenedor.classList.add('ocultar');
            }
        });

    }

    getRegistrosAPI(){
        // obtenerRegistrosAPI(this.entidad);
        let resultado = obtenerRegistrosAPI(this.entidad);
        resultado.then((result) => {
            this.registros = result;
            this.cargarRegistros();
        }); 
    }

    cargarRegistros(){

        // Obtenemos las llaves de la entidad
        if (this.registros[0]){
            this.llaves = Object.keys(this.registros[0]);
        }

        // Limpiamos el arreglo antes de llenarlo
        this.arrayCampo = [];

        // llenamos el array principal de la entidad
        this.registros.forEach( registro => {
            let texto = formatearTexto(registro[this.campo]);

            if (registro){
                this.arrayCampo =  [...this.arrayCampo, texto];
            }
        });

        this.arrayCampo.forEach( nombre => {
            this.stringCampo += nombre.toString() + ","; 
        });

    }

    cargarComboBox(){

        const input = document.querySelector(`.fondo-notificacion.${this.entidad} input`);
        const comboBox = document.querySelector(`#${this.entidad}`);

        let optionEntidad='';
        
        // Borramos las opciones que puedan existir
        const options = comboBox.querySelectorAll('option');
        options.forEach( option => {
            
            if(option.getAttribute('selected')){
                option.removeAttribute('selected');
            }

            if (option.getAttribute('value') != "" ){
                option.remove();
            }

        });
    
        let resultado = obtenerRegistrosAPI(this.entidad);
    
        resultado.then(result => {
    
            if (result){
                this.registros = [];
                this.registros = result;
                this.cargarRegistros();
    
                let keysObject = Object.keys(this.registros[0]);
    
                if (this.registros){
    
                    this.registros.forEach( registro => {
    
                        if(registro[keysObject[2]] == 'E'){
            
                            optionEntidad = document.createElement('OPTION'); 
                            optionEntidad.setAttribute('value', registro[keysObject[0]]);
                            optionEntidad.textContent=registro[keysObject[1]];

                            if (optionEntidad.textContent == input.value){
                                optionEntidad.selected = true ;
                            }

                            comboBox.appendChild(optionEntidad);
                        }
                    });
                }
            }
        });
    }

    validarComboBox(){
        validarCombo(this.entidad);
    }
}

async function obtenerRegistrosAPI(tabla = ''){

    try {
        const resultado = await fetch(rutaServidor + tabla + "/api");
        let registros = await resultado.json();
        
        return registros;

    } catch (error) {

        Swal.fire({
            icon:'error',
            title:'Error',
            text:error + '  "No fue posible cargar los registros de la base de datos"'
        });

        return false;

    }
}

// Se define como POST Elemento, porque es lo que ocurre en el POST
async function guardar(objeto, entidad){

    //FormData es como el submit de los datos de un formulario HTML pero en JavaScript
    const formulario = new FormData();
    
    for (const clave in objeto){

        formulario.append(clave, objeto[clave]);

    }

    try {
        let direccion = rutaServidor + entidad + '/api';
        // Petición hacia la API
        const respuesta = await fetch(direccion, {
            method: 'POST', //método por el que se envia la información al servidor
            body: formulario //enviamos datos al servidor definidos anteriormente, solo se hace en el cuerpo de la petición
        });

        const resultado = await respuesta.json();
        if (resultado){
            return true;
        }else{
            return false;
        }

    } catch (error) {

        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un error al momento de guardar el registro',
            button: 'OK' 
        });
    }
}




