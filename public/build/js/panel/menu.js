document.addEventListener('DOMContentLoaded', () =>{
    mostarOcultarMenu();
    itemsMenu();
    itemsSubMenu();
    nav();
    cargarItemPath();
});

// Se utiliza el botón de menú para desplazar el menú
function mostarOcultarMenu(){
    // Resolucion mobil
    const nav = document.querySelector('.nav');
    const btnMenu = document.getElementById('iconoMenu');
    const btnCerrar = document.getElementById('cerrar');
    const subItems = document.querySelectorAll('.subnav__a');
    
    btnMenu.addEventListener('click', function (){
        nav.classList.add('show');
        
        subItems.forEach( subItem => {
            if(subItem.getAttribute('data-estado') === 'seleccionado'){
                mostrarSubMenus(subItem);
            }
        });
    });

    btnCerrar.addEventListener('click', function (){
        nav.classList.remove('show');        
    });
    
}

// Lee el path de la url y carga el item correspondiente
function cargarItemPath(){
    const items = document.querySelectorAll('a');
    items.forEach( item => {
    
        let itemHref = item.getAttribute('href');
        // Verificamos si la url contiene la ruta del href del item
        if (window.location.pathname.includes(itemHref)){
             
            if(item.className.includes('subnav__a')){
                // Si es un subItem se cambia el estado 
                let ruta = item.firstElementChild.getAttribute('src');
                let texto = item.firstElementChild.nextElementSibling;

                item.setAttribute('data-estado','seleccionado');
                cambiarEstadoItem(item, ruta, texto, true, 'nivel2');

                // Buscamos el item desplegable
                let idParent = item.parentElement.getAttribute('data-parent');
                let itemParent = document.querySelector(`[data-id="${idParent}"]`);

                let rutaParent = itemParent.firstElementChild.getAttribute('src');
                let textoParent = itemParent.firstElementChild.nextElementSibling;
                itemParent.setAttribute('data-estado','seleccionado');
                cambiarEstadoItem(itemParent, rutaParent, textoParent, true, 'nivel1');
            
            }else if(item.className.includes('nav__a')){
                // si se cumple, quiere decir que es un item de primer nivel
                let ruta = item.firstElementChild.getAttribute('src');
                let texto = item.firstElementChild.nextElementSibling;
                
                item.setAttribute('data-estado','seleccionado');
                cambiarEstadoItem(item, ruta, texto, true, 'nivel1');
                
            }
        }
    
    });
}

// cambia el estado de los items del menú
function itemsMenu(){
    const items = document.querySelectorAll(`[data-id]`);
    eventosItems(items, 'nivel1');
}

// cambia el estado de los items del Submenú
function itemsSubMenu(){
    const items = document.querySelectorAll(`[data-subid]`);
    eventosItems(items, 'nivel2');
}

// Se evalua el evento hover y clic sobre los items del menu
function eventosItems(items, tipoItem){
    items.forEach( item => {

        //Asignamos la funcionalidad cuando el mouse da clic sobre algún item del menu
        item.addEventListener('click', function (){
            // cambiar el estado de todos los items
            let idItem;
            if(tipoItem === 'nivel1'){
                idItem = item.getAttribute('data-id');
            }else{
                idItem = item.getAttribute('data-subId');
            }
            item.setAttribute('data-estado', 'seleccionado');
            clickItem(idItem, tipoItem);

            // Evaluamos si es un item desplegable
            if(item.lastElementChild.className.includes('hat')){
                const subMenu = item.nextElementSibling;
                const subItems = document.querySelectorAll(`[data-parent="${idItem}"] > a`);
                if(subMenu.className.includes('ocultar')){
                    subMenu.classList.remove('ocultar');
                    subItems.forEach(subItem => {
                        subItem.classList.remove('ocultar');
                    });
                }else{
                    subMenu.classList.add('ocultar');
                    subItems.forEach(subItem => {
                        subItem.classList.add('ocultar');
                    });
                }
            }
        });
    });
}

// Esta función es utilizada en otras funciones para cambiar el estado del item o subItem en el evento Hover
function cambiarEstadoItem(item, ruta, texto, estado, tipoItem){
    if(tipoItem==='nivel1'){
        if(estado){
            ruta = ruta.replace('-bl','-ng');
            item.firstElementChild.setAttribute('src', ruta);
            item.style.background="#e7e4e4";
            texto.style.fontWeight="bold";
            texto.style.color ="#000";

            // Verificamos si es un item desplegable
            if(item.lastElementChild.className.includes('hat')){
                item.lastElementChild.style.color = "#000";
            }
        }
        else{
            ruta = ruta.replace('-ng','-bl');
            item.firstElementChild.setAttribute('src', ruta);
            item.style.background="#000";
            texto.style.fontWeight="normal";
            texto.style.color ="#fff";

            // Verificamos si es un item desplegable
            if(item.lastElementChild.className.includes('hat')){
                item.lastElementChild.style.color = "#fff";
            }
        }
    }else{
        if(estado){
            ruta = ruta.replace('-bl','-ng');
            item.firstElementChild.setAttribute('src', ruta);
            item.style.background="#e7e4e4";
            texto.style.fontWeight="bold";
            texto.style.color ="#000";
        }
        else{
            ruta = ruta.replace('-ng','-bl');
            item.firstElementChild.setAttribute('src', ruta);
            item.style.background="transparent";
            texto.style.fontWeight="normal";
            texto.style.color ="#fff";
        }
    }
}

// Esta función es utilizada en otra función para cambiar el estado del item o subitem en el evento Click
function clickItem(idItem, tipoItem){
    // Volvemos a seleccionar todos los items 

    let items;
    if(tipoItem === 'nivel1'){
        items = document.querySelectorAll(`[data-id]`);
    }else{
        items = document.querySelectorAll(`[data-subId]`);
    }
    
    items.forEach(item => {
    
       let imagen = item.firstElementChild;
       texto = item.firstElementChild.nextElementSibling;
       let ruta = imagen.getAttribute('src');
    
       if(tipoItem === 'nivel1'){
           // Si el id que itera es diferente al id seleccionado por el usuario
           if(item.getAttribute('data-id') !== idItem){
    
                item.setAttribute('data-estado','');
                
                if (ruta && ruta.includes('-ng')){
                    ruta = ruta.replace('-ng','-bl');
                    imagen.setAttribute('src', ruta);
                    texto.style.fontWeight="normal";
                    texto.style.color = "#fff";
                    item.style.background = "#000";

                    // Verificamos si es un item desplegable
                    if(item.lastElementChild.className.includes('hat')){
                        item.lastElementChild.style.color = "#fff";
                        ocultarSubMenus(item.getAttribute('data-id'));
                    }
                }
            }else{

                if (ruta && ruta.includes('-bl')){
                    ruta = ruta.replace('-bl','-ng');
                    imagen.setAttribute('src', ruta);
                    texto.style.fontWeight="bold";
                    texto.style.color = "#000";
                    item.style.background = "#fff";

                    // Verificamos si es un item desplegable
                    if(item.lastElementChild.className.includes('hat')){
                        item.lastElementChild.style.color = "#000";

                    }
                }
            }
       }else{
            if(item.getAttribute('data-subId') !== idItem){
        
                item.setAttribute('data-estado','');

                if (ruta && ruta.includes('-ng')){
                    ruta = ruta.replace('-ng','-bl');
                    imagen.setAttribute('src', ruta);
                    texto.style.fontWeight="normal";
                    texto.style.color = "#fff";
                    item.style.background = "transparent";
                }

            }else{

                if (ruta && ruta.includes('-bl')){
                    ruta = ruta.replace('-bl','-ng');
                    imagen.setAttribute('src', ruta);
                    texto.style.fontWeight="bold";
                    texto.style.color = "#000";
                    item.style.background = "#fff";
                }
            }
       }
    });
}

// Se utiliza cuando se necesita cambiar el estado a oculto a los subitems de items en los que no dió clic el usuario
function ocultarSubMenus(dataId){
    let contentSubItems = document.querySelector(`[data-parent="${dataId}"]`);
    let subItems = document.querySelectorAll(`[data-parent="${dataId}"] > a`);

    // Ocultamos el contenedor
    contentSubItems.classList.add('ocultar');
    subItems.forEach( subItem => {
        if(!subItem.className.includes('ocultar')){
            subItem.classList.add('ocultar');
        }

        if (subItem.getAttribute('data-estado') === 'seleccionado'){
            subItem.setAttribute('data-estado', '');

            let ruta = subItem.firstElementChild.getAttribute('src');
            let texto = subItem.firstElementChild.nextElementSibling;
            cambiarEstadoItem(subItem, ruta, texto, false, 'nivel2');
        }
    });
}

// ocutal los subitems cuando el mouse sale del foco del menu principal de navecación
function nav(){
    const nav = document.querySelector('nav');
    const items = document.querySelectorAll('.nav__a');
    const subItems = document.querySelectorAll('.subnav__a');
    
    nav.addEventListener('mouseleave', () => {


        items.forEach( item => {
            if(item.getAttribute('data-estado') === 'seleccionado' && !window.location.pathname.includes(item.getAttribute('href'))){
                
                item.setAttribute('data-estado', '');
                let ruta = item.firstElementChild.getAttribute('src');
                let texto = item.firstElementChild.nextElementSibling;
                cambiarEstadoItem(item, ruta, texto, false, 'nivel1');
            }
        });

        // ocultamos los subitems
        subItems.forEach( subItem => {
            subItem.classList.add('ocultar');
            if (!subItem.parentElement.className.includes('ocultar')){
                subItem.parentElement.classList.add('ocultar');
            }
        });

        cargarItemPath();
    });

    nav.addEventListener('mouseenter', () =>{
        subItems.forEach( subItem => {
            if(subItem.getAttribute('data-estado') == 'seleccionado'){
                mostrarSubMenus(subItem);
            }
        });
    });
}

function mostrarSubMenus(subItem){
    // obtenemos el padre
    const subNavContent = subItem.parentElement;
    // quitamos la clase ocultar del elemento padre
    subNavContent.classList.remove('ocultar');

    const dataParent = subNavContent.getAttribute('data-parent');
    // quitamos la clase ocultar de los subItems de ese item
    const allSubItems = document.querySelectorAll(`[data-parent="${dataParent}"] > a`);
    allSubItems.forEach( item => {
        item.classList.remove('ocultar');
    });
}
