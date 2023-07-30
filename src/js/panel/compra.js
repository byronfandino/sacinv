import { showHideMensajeGeneral, 
    showHideMensajesError,
    limpiarFormulario, 
    resetFormulario, 
    formatearTexto} from "./class/Parametros.js";
import { Persona } from "./class/ModelPersonas.js";
import { Modal } from './class/Modal.js';

document.addEventListener('DOMContentLoaded', () => { 
    cargarVentanasModal();

    const formulario = document.querySelector('[data-form="compra"]');
    formulario.addEventListener('submit', (e) => {
        e.preventDefault();
        Swal.fire({
            title: 'Guardando registro...',
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
                formulario.submit();
            }
        });
    })            
    
    // Creando la configuración del objeto
    const objetoProveedor = {

        entidad:'proveedor',

        // Se colocan los id de los campos a evaluar
        validarCampos:['Prov_RazonSocial'],
        
        estadoCampos:{
            Prov_Id: false
        },

        reglas : {
            Prov_RazonSocial: '^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ. ]{2,40}$'
        },

        sugerencias : {
            Prov_RazonSocial: 'Solo debe contener letras y números, mayor a 3 caracteres'
        },

        arrayAPI : {
            Prov_RazonSocial: [],
            Prov_Nit: []
        },

        stringAPI : {
            Prov_RazonSocial:'',
            Prov_Nit:''
        },
        
        existe:[],

        convertirMayuscula : ['Prov_RazonSocial'],

        datosSugerencia : ['Prov_RazonSocial'],

        autollenado: ['Prov_Id', 'Prov_Nit', 'Prov_RazonSocial', 'Prov_Tel', 'Ciud_Nombre', 'Depart_Nombre']

    };
    
    const proveedores = new Persona(objetoProveedor);
    proveedores.asignarValidacion();
    proveedores.obtenerRegistros();
    // setTimeout(()=>{
    //     console.log(proveedores.registrosAPI);
    //     console.log(proveedores.arrayAPI);
    // },1000);
    proveedores.botonLimpiar();

    // Creando la configuración del objeto
    const objetoProducto = {

        entidad:'producto',

        // Se colocan los id de los campos a evaluar
        validarCampos:['Cod_Barras', 'Cod_Manual', 'Prod_Descripcion'],
        
        estadoCampos:{
            Prod_Id: false
        },

        reglas : {
            Cod_Barras: "^[0-9A-Za-z]{4,20}$",
            Cod_Manual: "^[0-9A-Za-z]{4,20}$",
            Prod_Descripcion: "^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{4,100}$"
        },

        sugerencias : {
            Cod_Barras: 'Este campo es alfanumérico, mayor a 4 caracteres y no puede contener espacios ni caracteres especiales',
            Cod_Manual: 'Este campo es alfanumérico, mayor a 4 caracteres y no puede contener espacios ni caracteres especiales',
            Prod_Descripcion: 'Este campo es alfanumérico, mayor a 4 caracteres y no puede contener espacios ni caracteres especiales'
        },

        arrayAPI : {
            Cod_Barras: [],
            Cod_Manual: [],
            Prod_Descripcion: []
        },

        stringAPI : {
            Cod_Barras:'',
            Cod_Manual:'',
            Prod_Descripcion:''
        },

        existe:[],

        convertirMayuscula : ['Cod_Barras', 'Cod_Manual'],

        datosSugerencia : ['Cod_Barras', 'Cod_Manual', 'Prod_Descripcion'],

        autollenado: ['Prod_Id', 'Cod_Barras', 'Cod_Manual', 'Prod_Descripcion']
    };

    const producto = new Persona(objetoProducto);
    producto.asignarValidacion();
    producto.obtenerRegistros();
    producto.botonLimpiar();
    // limpiarFormulario(formulario);

    operaciones();
    
});

function redondearAl50MasCercano(numero) {
    const residuo = numero % 50;
    return numero + (50 - residuo);
}

function cargarVentanasModal(){
    let metodoPagoModal = new Modal("metodo-pago",
    "^[A-Z0-9Ña-züñáéíóúÁÉÍÓÚÜ'° ]{2,50}$",
    "Solo debe contener letras, mayor a 2 caracteres",
    "MP_Nombre",
    "Efectivo");
    metodoPagoModal.mostrarModal();
    metodoPagoModal.getRegistrosAPI();
    metodoPagoModal.cargarRegistros();
    metodoPagoModal.asignarValidacion();
    metodoPagoModal.cargarComboBox();
}

function operaciones(){
    //Confirguarción Propia
    let inputCant = document.querySelector('#CD_Cantidad');
    let inputValorUnit = document.querySelector('#CD_ValorUnit');
    let inputValorTotal = document.querySelector('#CD_Total');
    let inputXjeVenta = document.querySelector('#CD_XjeVenta');
    let inputValorVenta = document.querySelector('#CD_ValorVenta');
    let inputXjeDesc = document.querySelector('#CD_XjeDesc');
    let inputValorDesc = document.querySelector('#CD_ValorDesc');
    
    inputCant.addEventListener('input', () => {
        inputValorTotal.value = parseInt(inputCant.value) * parseInt(inputValorUnit.value);
    });

    inputValorUnit.addEventListener('input', () => {
        inputValorTotal.value = parseInt(inputCant.value) * parseInt(inputValorUnit.value);
        let operacion = (parseInt(inputValorUnit.value) * parseInt(inputXjeVenta.value) /100) + parseInt(inputValorUnit.value);
        inputValorVenta.value = redondearAl50MasCercano(operacion);
    });

    inputXjeVenta.addEventListener('input', () => {
        let operacion = (parseInt(inputValorUnit.value) * parseInt(inputXjeVenta.value) /100) + parseInt(inputValorUnit.value);
        inputValorVenta.value = redondearAl50MasCercano(operacion);
    });
    
    inputValorVenta.addEventListener('input', () => {
        inputXjeVenta.value = (parseInt(inputValorVenta.value) * 100 / parseInt(inputValorUnit.value)) - 100;
    });

    inputXjeDesc.addEventListener('input', () => {
        inputValorDesc.value = parseInt(inputValorUnit.value) * parseInt(inputXjeDesc.value) /100;
    });
}
    
    // showHideMensajeGeneral();
    // showHideMensajesError();
    // clientes.estadoAllCampos();

    // const btnReset = document.querySelector('.btn-reset');
    // if (btnReset){
    //     btnReset.addEventListener('click', () => {
    //         resetFormulario();
    //         clientes.eliminarFilasTabla();        
    //         clientes.obtenerRegistros();     
    //     });
    // }

    // const selectDepart = document.querySelector('#Ciud_CodDepart');
    // selectDepart.addEventListener('change', ()=>{
    //     clientes.estadoCampo('Cli_FkCiud_Id', false);
    // });