import { showHideMensajeGeneral, showHideMensajesError, rutaServidor } from "./class/Parametros.js";
import { Ciudades } from "./class/ModelCiudades.js";
import { Persona } from "./class/ModelPersonas.js";

document.addEventListener('DOMContentLoaded', () => { 
    let ciudades = new Ciudades();
    ciudades.cargaComboCiudades();

    seleccionarCiudadDepart();

    const formulario = document.querySelector('[data-form="cliente"]');
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
        entidad:'cliente',
    
        // Se colocan los id de los campos a evaluar
        validarCampos:[
                        'Cli_TipoCliente',
                        'Cli_Ced_Nit',
                        'Cli_RazonSocial',
                        'Cli_Tel',
                        'Cli_Email',
                        'Cli_Direccion',
                        'Cli_FkCiud_Id',
                        'Ciud_CodDepart'
        ],
                    
        estadoCampos:{
            Cli_TipoCliente: true,
            Cli_Ced_Nit: false,
            Cli_RazonSocial: false,
            Cli_Tel: false,
            Cli_Email: false,
            Cli_Direccion: false,
            Cli_FkCiud_Id: false,
            Ciud_CodDepart: false
        },
    
        reglas : {
            Cli_TipoCliente: '^[NC]{1}$',
            Cli_Ced_Nit: '^[0-9-]{4,15}$',
            Cli_RazonSocial: '^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ. ]{3,40}$',
            Cli_Tel: '^[0-9]{10}$',
            Cli_Email: /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/,
            Cli_Direccion: /^[-0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ# ]{6,40}$/,
            Cli_FkCiud_Id: '^[0-9]{1,4}$',
            Ciud_CodDepart: '^[0-9]{1,2}$'
        },
    
        sugerencias : {
            Cli_TipoCliente: 'Debe seleccionar un tipo de cliente',
            Cli_Ced_Nit: 'Solo debe contener números, mayor a 4 caracteres',
            Cli_RazonSocial: 'Solo debe contener letras y números, mayor a 3 caracteres',
            Cli_Tel: 'El campo Teléfono debe contener 10 números',
            Cli_Email: 'El email no es válido',
            Cli_Direccion: 'Dirección no válida',
            Cli_FkCiud_Id: 'Ciudad no válida',
            Ciud_CodDepart: 'Departamento no válido'
        },
        
        boton: document.querySelector('[data-btn="btn-enviar"]'),
    
        nombreTabla : 'cliente',
    
        camposTabla : [
    
            { Cli_Id: 'Id', posicion: null, class: [] },
            { Cli_TipoCliente: 'Tipo Cliente', posicion: null, class: [] },
            { Nombre_TipoCliente: 'Tipo Cliente', posicion: 1, class: [] },
            { Cli_RazonSocial: 'RazonSocial', posicion: 2, class: [] },
            { Cli_Ced_Nit: 'Cédula / Nit', posicion: 3, class: [] },
            { Cli_Tel: 'Tel', posicion: 4, class: [] },
            { Cli_Email: 'Email', posicion: 5, class: [] },
            { Cli_Direccion: 'Dirección', posicion: 6, class: []},
            { Cli_FkCiud_Id: 'Cod Ciudad', posicion: null, class: [] },
            { Ciud_Nombre: 'Ciudad', posicion: 7, class: [] },
            { Ciud_CodDepart: 'Cod Departamento', posicion: null, class: [] },
            { Depart_Nombre: 'Departamento', posicion: 8, class: [] },
            { Cli_Status: 'Estado', posicion: 9, class: [] }
            
        ],
    
        arrayAPI : {
            Cli_TipoCliente:[],
            Cli_Ced_Nit: [],
            Cli_RazonSocial: [],
            Cli_FkCiud_Id:[],
            Ciud_CodDepart:[]
        },
    
        stringAPI : {
            Cli_TipoCliente:'',
            Cli_Ced_Nit:'',
            Cli_RazonSocial:'',
            Cli_FkCiud_Id:'',
            Ciud_CodDepart:''
        },

        existe:['Cli_Ced_Nit'],
    
        convertirMayuscula : ['Cli_RazonSocial', 'Cli_Direccion'],
        convertirMinuscula : ['Cli_Email'],

        idAdd : ['Cli_FkCiud_Id', 'Ciud_CodDepart']

    };
        
    const clientes = new Persona(objeto);
    clientes.asignarValidacion();
    clientes.obtenerRegistros();
    showHideMensajeGeneral();
    showHideMensajesError();
    clientes.estadoAllCampos();
    clientes.botonLimpiar();
    
    let alertaExito = document.querySelector('.alerta.exito');
    if (alertaExito && alertaExito.textContent != ''){
        window.location.href = rutaServidor + "cliente";
    }

    const selectDepart = document.querySelector('#Ciud_CodDepart');
    selectDepart.addEventListener('change', ()=>{
        clientes.estadoCampo('Cli_FkCiud_Id', false);
    });

});

function seleccionarCiudadDepart(){
    
    // Obtener la URL actual
    let urlString = window.location.href;
    // Crear un objeto URL con la URL actual
    let url = new URL(urlString);
    // Acceder a las variables de la URL
    let codCiudad = url.searchParams.get("Cli_FkCiud_Id");
    let codDepart = url.searchParams.get("Ciud_CodDepart");

    const selectDepart = document.querySelector('#Ciud_CodDepart');
    selectDepart.value = codDepart;

    const selectCiudades = document.querySelector('#Cli_FkCiud_Id');
    selectCiudades.value = codCiudad;

}
