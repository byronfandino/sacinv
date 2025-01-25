import { cierreManualModal } from "../global/parametros.js";

document.addEventListener('DOMContentLoaded', () => {

    // Muestra la ventana modal de la tabla de registros 
    const btnBuscarCliente = document.querySelector('#buscar_cliente');
    btnBuscarCliente.addEventListener('click', () => {
        const ventanaModal =  document.querySelector('#modal_cliente_select');
       if (ventanaModal.className.includes('ocultar')){
            ventanaModal.classList.remove('ocultar');
       }
    });

    // Hay varias ventanas modal, y por lo tanto es necesario programar cada botón
    const btnCerraModal = document.querySelectorAll('.cerrar__modal');
    btnCerraModal.forEach( boton => {
        boton.addEventListener('click', e => cierreManualModal(e));
    });
});

//# sourceMappingURL=cxc.js.map
