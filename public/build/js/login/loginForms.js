document.addEventListener('DOMContentLoaded', function(){
    iniciarCampos();
});

function iniciarCampos(){
    animacionlabels();
    alertas();
    validarInputs();
}

function animacionlabels(){

    const iconoError = document.querySelectorAll('.icono-error');
    const labels = document.querySelectorAll('.campo-descripcion');
    const inputs = document.querySelectorAll('.campo .input');
    
    //Recorremos los inputs con el fin de verificar si contiene algún texto digitado por el usuario
    inputs.forEach ((input,indice) => {
        
        // Al momento de cargar la página hay que validar si existe algún valor digitado por el usuario.
        if(input.value != ""){
            subirLabel(iconoError, labels, indice);
        }else{
            bajarLabel(iconoError, labels, indice);
        }

        //Al recibir el foco en la caja mediante un tab se debe subir el label sobre la caja
        input.addEventListener('focus', () => {
            subirLabel(iconoError, labels, indice);
        })

        //Validar si existe algún valor cuando la caja pierde el foco y evitar que el label vuelva a bajar
        input.addEventListener('blur', () => {
            if(input.value != ""){
                subirLabel(iconoError, labels, indice);
            }else{
                bajarLabel(iconoError, labels, indice);
            }            
        });

    });

    //Animación del label al dar click sobre este elemento
    labels.forEach( ( label, indice ) => {
        label.addEventListener('click', () => {
            subirLabel(iconoError, labels, indice);
        });
    });
}

//Función para cambiar la posición del label y el icono de error dependiendo 
function subirLabel(iconoError, labels, indice){
    labels[indice].classList.add('subirLabel');
    iconoError[indice].style.top = "3rem";
}

//Función para cambiar la posición del label y el icono de error dependiendo 
function bajarLabel(iconoError, labels, indice){
    labels[indice].classList.remove('subirLabel');
    iconoError[indice].style.top = "1rem";
}

function alertas(){
    const iconoError = document.querySelectorAll('.icono-error');
    const msgError = document.querySelectorAll('.msg-error');
    
    msgError.forEach((msg, indice) =>{
        if(msg.textContent == ''){
            msg.classList.add('ocultar');
            iconoError[indice].classList.add('ocultar');
        }else{
            msg.classList.remove('ocultar');
            iconoError[indice].classList.remove('ocultar');
        }
    });
}

function validarInputs(){
    //Es obligatorio usar template string para buscar por atributos HTML
    const inputNombre = document.querySelectorAll(`[data-tipo="nombre"]`);
    const inputNickname = document.querySelectorAll(`[data-tipo="nickname"]`);
    const inputTelefono = document.querySelectorAll(`[type="tel"]`);
    const inputEmail = document.querySelectorAll(`[type="email"]`);
    const inputPassword = document.querySelectorAll(`[type="password"]`);

    if(inputNombre){
        const expresion="^[A-ZÑa-züñáéíóúÁÉÍÓÚÜ'° ]{7,50}$";
        const msg="Solo debe contener letras, mayor a 7 caracteres";
        inputNombre.forEach((input) => {
            const iconoError=input.nextElementSibling; //Obtenemos el icono del error
            const labelError=input.nextElementSibling.nextElementSibling;//obtenemos el label donde se coloca el error
            input.addEventListener('input', e => {
                validarTexto(expresion, e.target.value, msg, labelError, iconoError);
            });
        });
    }
    
    if(inputNickname){
        const expresion = /^[a-zA-Z]{5,20}$/;
        const msg="Debe contener al menos 5 LETRAS sin espacios";
        inputNickname.forEach((input) => {
            const iconoError=input.nextElementSibling; //Obtenemos el icono del error
            const labelError=input.nextElementSibling.nextElementSibling;//obtenemos el label donde se coloca el error
            input.addEventListener('input', e => {
                validarTexto(expresion, e.target.value, msg, labelError, iconoError);
            });
        });
    }
    
    if(inputTelefono){
        const expresion = "^[0-9]{10}$";
        const msg="Debe contener 10 Números";
        inputTelefono.forEach((input) => {
            const iconoError=input.nextElementSibling; //Obtenemos el icono del error
            const labelError=input.nextElementSibling.nextElementSibling;//obtenemos el label donde se coloca el error
            input.addEventListener('input', e => {
                validarTexto(expresion, e.target.value, msg, labelError, iconoError);
            });
        });
    }

    if(inputEmail){
        const expresion = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/;
        const msg="No cumple con los requisitos de un correo electrónico";
        inputEmail.forEach((input) => {
            const iconoError=input.nextElementSibling; //Obtenemos el icono del error
            const labelError=input.nextElementSibling.nextElementSibling;//obtenemos el label donde se coloca el error
            input.addEventListener('input', e => {
                validarTexto(expresion, e.target.value, msg, labelError, iconoError);
            });
        });
    
    }
    if(inputPassword){
        const expresion = /(?=(.*[0-9]))(?=.*[\!@#$%^&*()\\[\]{}\-_+=|:;"'<>,./?])(?=.*[a-z])(?=(.*[A-Z]))(?=(.*)).{8,}/;
        const msg="Debe contener mínimo 8 caracteres, mínimo 1 letra Mayúscula, mínimo 1 letra Minúscula, mínimo un número, y mínimo 1 caractér especial (@#$%&*)";
        inputPassword.forEach((input) => {
            const iconoError=input.nextElementSibling; //Obtenemos el icono del error
            const labelError=input.nextElementSibling.nextElementSibling;//obtenemos el label donde se coloca el error
            input.addEventListener('input', e => {
                validarTexto(expresion, e.target.value, msg, labelError, iconoError);
            });
        });
    }    
}

function validarTexto(expresion, valor, msg, labelError, iconoError){  
    if (valor.match(expresion) !== null){
        iconoError.classList.add("ocultar");
        labelError.classList.add("ocultar");
        labelError.textContent="";
    }else{
        iconoError.classList.remove("ocultar");
        labelError.classList.remove("ocultar");
        labelError.textContent=msg;
    }
}
