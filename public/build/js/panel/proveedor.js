import { showHideMensajeGeneral, showHideMensajesError,limpiarFormulario, resetFormulario } from "./class/Parametros.js";
import { Ciudades } from "./class/ModelCiudades.js";
import { Persona } from "./class/ModelPersonas.js";

document.addEventListener('DOMContentLoaded', () => { 

    let ciudades = new Ciudades();
    ciudades.cargaComboCiudades();
    
    const formulario = document.querySelector('[data-form="proveedor"]');
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
    const objeto = {
        entidad:'proveedor',
    
        // Se colocan los id de los campos a evaluar
        validarCampos:[
                        'Prov_Nit',
                        'Prov_RazonSocial',
                        'Prov_Tel',
                        'Prov_Email',
                        'Prov_Direccion',
                        'Prov_FkCiud_Id',
                        'Ciud_CodDepart'
        ],
                    
        estadoCampos:{
            Prov_Nit: false,
            Prov_RazonSocial: false,
            Prov_Tel: false,
            Prov_Email: false,
            Prov_Direccion: false,
            Prov_FkCiud_Id: false,
            Ciud_CodDepart: false
        },
    
        reglas : {
            Prov_Nit: '^[0-9-]{4,15}$',
            Prov_RazonSocial: '^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ. ]{3,40}$',
            Prov_Tel: '^[0-9]{10}$',
            Prov_Email: /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/,
            Prov_Direccion: /^[-0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ# ]{6,40}$/,
            Prov_FkCiud_Id: '^[0-9]{1,4}$',
            Ciud_CodDepart: '^[0-9]{1,2}$'
        },
    
        sugerencias : {
            Prov_Nit: 'Solo debe contener números, mayor a 4 caracteres',
            Prov_RazonSocial: 'Solo debe contener letras y números, mayor a 3 caracteres',
            Prov_Tel: 'El campo Teléfono debe contener 10 números',
            Prov_Email: 'El email no es válido',
            Prov_Direccion: 'Dirección no válida',
            Prov_FkCiud_Id: 'Ciudad no válida',
            Ciud_CodDepart: 'Departamento no válido'
        },
        
        boton: document.querySelector('[data-btn="btn-enviar"]'),
    
        nombreTabla : 'proveedor',
    
        camposTabla : [
    
            { Prov_Id: 'Id', posicion: null, class: [] },
            { Prov_RazonSocial: 'RazonSocial', posicion: 1, class: [] },
            { Prov_Nit: 'Nit', posicion: 2, class: ['tbody__td--right'] },
            { Prov_Tel: 'Tel', posicion: 3, class: ['tbody__td--center'] },
            { Prov_Email: 'Email', posicion: 4, class: [] },
            { Prov_Direccion: 'Dirección', posicion: 5, class: []},
            { Prov_FkCiud_Id: 'Cod Ciudad', posicion: null, class: [] },
            { Ciud_Nombre: 'Ciudad', posicion: 6, class: ['tbody__td--center'] },
            { Ciud_CodDepart: 'Cod Departamento', posicion: null, class: [] },
            { Depart_Nombre: 'Departamento', posicion: 7, class: ['tbody__td--center'] },
            { Prov_Status: 'Estado', posicion: 8, class: [] }
            
        ],
    
        arrayAPI : {
            Prov_RazonSocial: [],
            Prov_Nit: [],
            Prov_FkCiud_Id:[],
            Ciud_CodDepart:[]
        },
    
        stringAPI : {

            Prov_RazonSocial:'',
            Prov_Nit:'',
            Prov_FkCiud_Id:'',
            Ciud_CodDepart:''
        },
        
        existe:['Prov_Nit', 'Prov_RazonSocial'],
    
        convertirMayuscula : ['Prov_RazonSocial', 'Prov_Direccion'],
        convertirMinuscula : ['Prov_Email'],

        idAdd : ['Prov_FkCiud_Id', 'Ciud_CodDepart'],

        datosSugerencia : false

    };
    
    const proveedor = new Persona(objeto);
    proveedor.asignarValidacion();
    proveedor.obtenerRegistros();
    showHideMensajeGeneral();
    showHideMensajesError();
    limpiarFormulario(formulario);
    proveedor.estadoAllCampos();
    proveedor.botonLimpiar();
    
    const btnReset = document.querySelector('.btn-reset');
    if (btnReset){
        btnReset.addEventListener('click', () => {
            resetFormulario();
            proveedor.eliminarFilasTabla();               
            proveedor.obtenerRegistros();
        });
    }

    const selectDepart = document.querySelector('#Ciud_CodDepart');
    selectDepart.addEventListener('change', ()=>{
        proveedor.estadoCampo('Prov_FkCiud_Id', false);
    });

});
