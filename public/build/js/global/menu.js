document.addEventListener('DOMContentLoaded', () =>{
    mostrarMenu();
    cerrarMenu();
});

function mostrarMenu(){
    const menu = document.querySelector('#menu');
    const btnMostrarMenu = document.querySelector('#icono__menu');

    btnMostrarMenu.addEventListener('click', e => {
        e.preventDefault();
        if (!menu.className.includes('mostrar')){
            menu.classList.add('mostrar');
            menu.classList.remove('ocultar');
        }
    })
}

function cerrarMenu(){

    const menu = document.querySelector('#menu');
    const btnCerrarMenu = document.querySelector('#cerrar_menu');

    btnCerrarMenu.addEventListener('click', e => {

        e.preventDefault();

        if (!menu.className.includes('ocultar')){
            menu.classList.remove('mostrar');
            menu.classList.add('ocultar');
        }

    });
}