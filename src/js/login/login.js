let registros = "";

document.addEventListener('DOMContentLoaded', function(){
    iniciarLogin();
});

function iniciarLogin(){
    consultarRegistros();
    formularios();
    navegacionfija();
    menuPhone();
}

//Se utiliza para controlar la navegación entre los formularios Login y Registrate, ya que comparten un mismo <DIV>
//También para obtener los inputs de cada formulario para validar el contenido
function formularios(){
    //Deshabilitamos todos los botones submit de todos los formularios, con el fin de habilitarlos solo cuamdo se cumpla la condiciones de campos llenos
    const botonesForm = document.querySelectorAll(`input[type="submit"]`);
    botonesForm.forEach(boton => {
        boton.disabled = true;
    });

    //Se declara un array de elementos HTML solo con el fin de pasarlos por parámetro hacia otras funciones
    let arrayElements = new Array();
    arrayElements['enlaceLogin'] = document.querySelector('#iniciaSesion');
    arrayElements['enlaceRegistrate'] = document.querySelector('#registrate');
    arrayElements['formularioLogin'] = document.querySelector('#form-login');
    arrayElements['formularioRegistrate'] = document.querySelector('#form-registrate');

    if(window.location.pathname == '/crear-cuenta'){
        ocultarFormulario("FormLogin", arrayElements);
    }else{
        ocultarFormulario("FormRegistrate", arrayElements);
    }

    arrayElements['enlaceLogin'].addEventListener('click', function(e){
        e.preventDefault();
        ocultarFormulario("FormRegistrate", arrayElements);
    });

    arrayElements['enlaceRegistrate'].addEventListener('click', function(e){
        e.preventDefault();
        ocultarFormulario("FormLogin", arrayElements);
    });
    
    // -----------Empieza la verificacion de formulario login---------------------- 
    
    //obtiene todos los inputs y los labels del formulario login para realizar la validación de campos diligenciados, también obtenemos los labels con el fin de saber si mencionan algún error
    let arrayLogin = new Array();
    arrayLogin['inputs'] = document.querySelectorAll(`#form-login .input`);
    arrayLogin['labels'] = document.querySelectorAll(`#form-login .msg-error`);

    arrayLogin['inputs'].forEach( input => {
        input.addEventListener('input', () => {
            if (validarFormulario(arrayLogin)){
                botonesForm[0].disabled=false;
                botonesForm[0].classList.remove('disabled');
            }else{
                botonesForm[0].disabled=true;
                botonesForm[0].classList.add('disabled');
            }
        });
    });

    // -----------Empieza la verificacion de formulario registrate---------------------- 
    
    //obtiene todos los inputs y los labels del formulario login para realizar la validación de campos diligenciados, también obtenemos los labels con el fin de saber si mencionan algún error
    let arrayRegistro = new Array();
    arrayRegistro['inputs'] = document.querySelectorAll(`#form-registrate .input`);
    arrayRegistro['labels'] = document.querySelectorAll(`#form-registrate .msg-error`);

    arrayRegistro['inputs'].forEach( input => {
        input.addEventListener('input', () => {
            if (validarFormulario(arrayRegistro)){
                botonesForm[1].disabled=false;
                botonesForm[1].classList.remove('disabled');
            }else{
                botonesForm[1].disabled=true;
                botonesForm[1].classList.add('disabled');
            }
        });
    });

    // -----------Empieza la verificacion de formulario Contacto---------------------- 
    
    //obtiene todos los inputs y los labels del formulario login para realizar la validación de campos diligenciados, también obtenemos los labels con el fin de saber si mencionan algún error
    let arrayContacto = new Array();
    arrayContacto['inputs'] = document.querySelectorAll(`#form-contacto .input`);
    arrayContacto['labels'] = document.querySelectorAll(`#form-contacto .msg-error`);

    arrayContacto['inputs'].forEach( input => {
        input.addEventListener('input', () => {
            if (validarFormulario(arrayContacto)){
                botonesForm[2].disabled=false;
                botonesForm[2].classList.remove('disabled');
            }else{
                botonesForm[2].disabled=true;
                botonesForm[2].classList.add('disabled');
            }
        });
    });
}

function navegacionfija(){
    const barra = document.querySelector('.barra-navegacion');

    //Tener presente si en cualquier .main va a posesinarse la barra de navegación flotante.
    const header = document.querySelector('.header-inicio');
    const body = document.querySelector('body');

    window.addEventListener('scroll', function(){
        
        if( header.getBoundingClientRect().top < -30 ){
            //Fijar la navegación en cualquier resolución
            barra.classList.add('fijo-js');
            //se añade un padding superior exactamente igual al alto del menú de navegación
            body.classList.add('body-scroll-js');
        }else{
            barra.classList.remove('fijo-js');
            body.classList.remove('body-scroll-js');
        }
    });
}

function menuPhone(){

    const iconoMenu = document.querySelector('#iconoMenu');
    const cerrarMenu = document.querySelector('#cerrar');
    const menu = document.querySelector('.navegacion-menu');
    
    //Mostrar el menú en resolución Movil
    iconoMenu.addEventListener('click', function (e) {
        e.preventDefault();
        menu.classList.add('mostrarMenu-js');
    });

    //Ocultar el menú en resolución Movil
    cerrarMenu.addEventListener('click', function (e){
        e.preventDefault();
        menu.classList.remove('mostrarMenu-js');
    });

    //Asignar el evento cerrar a cada uno de los enlaces
    const enlacesMenu = document.querySelectorAll('.navegacion-menu a');
    enlacesMenu.forEach( enlace => {
        enlace.addEventListener('click', function(e){
            // e.preventDefault();
            menu.classList.remove('mostrarMenu-js');
        });
    })
}

async function consultarRegistros(){
    try{
        const url='http://192.168.18.120:3000/usuario/api';
        const resultado = await fetch(url);
        registros = await resultado.json();

        validarNickNameEmail();

    }catch(error){
        console.log(error);
    }
}

// Se valida los campos de Email y Nickname del formulario registro para que no existan duplicados, utilizando el contenido de la api
function validarNickNameEmail(){
    const regNickname = document.querySelector('#reg-nickname');
    const nicknameIconError=regNickname.nextElementSibling; //Obtenemos el icono del error
    const nicknameLabel=regNickname.nextElementSibling.nextElementSibling;//obtenemos el label donde se coloca el error
  
    const regEmail = document.querySelector('#reg-email');
    const emailIconError=regEmail.nextElementSibling; //Obtenemos el icono del error
    const emailLabel=regEmail.nextElementSibling.nextElementSibling;//obtenemos el label donde se coloca el error

    regNickname.addEventListener('input', e => {
        if(registros[0].includes(e.target.value)){
            console.error('Este nickname no está disponible');
            nicknameIconError.classList.remove("ocultar");
            nicknameLabel.classList.remove("ocultar");
            nicknameLabel.textContent="Este nickname no está disponible";
        }
    });
    
    regEmail.addEventListener('input', e => {
        if(registros[1].includes(e.target.value)){
            console.error('Este nickname no está disponible');
            emailIconError.classList.remove("ocultar");
            emailLabel.classList.remove("ocultar");
            emailLabel.textContent="Este email ya se encuentra registrado";
        }
    });
}

//Valida que todos los campos del formulario que se esté diligenciando se encuentren llenos, para habilitar el botón de submit
function validarFormulario(arrayForm){

    let inputVerificado=true;
    let labelVerificado=true;

    arrayForm['inputs'].forEach(input => {
        if(input.value === ""){
            inputVerificado=false;
        }
    });
    
    arrayForm['labels'].forEach(label => {        
        if(label.textContent !== ""){
            labelVerificado=false;
        }
    });

    if(inputVerificado && labelVerificado){
        return true;
    }else{
        return false;
    }
}

function ocultarFormulario(formulario, arrayElements){

    if(formulario == 'FormLogin'){
        arrayElements['enlaceRegistrate'].classList.remove('inactivo');
        arrayElements['enlaceLogin'].classList.add('inactivo');
        
        arrayElements['formularioLogin'].classList.add('ocultar-form');
        arrayElements['formularioRegistrate'].classList.remove('ocultar-form');
    }
    
    if(formulario == 'FormRegistrate'){
        arrayElements['enlaceLogin'].classList.remove('inactivo');
        arrayElements['enlaceRegistrate'].classList.add('inactivo');
        
        arrayElements['formularioRegistrate'].classList.add('ocultar-form');
        arrayElements['formularioLogin'].classList.remove('ocultar-form');
    }
}