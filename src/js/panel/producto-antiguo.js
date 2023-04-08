// Guarda los registros de la tabla y de los combobox
let registrosProductos;
let registrosCategorias;
let registrosMarcas;

// Se guarda en un array
let arrayDescripcion=new Array();
let arrayCodigoBarras=new Array();
let arrayCodigoManual=new Array();
let stringDescripcion='';
let stringCodigoBarras='';
let stringCodigoManual='';
let objeto = {
    codigoManual:false,
    prodDescripcion:false,
    categoria:false,
    marca:false,
    cantStock:false,
    valorVenta:false
}

let cntEstado = 0;



let registrosMarcasAPI;
let nombreMarcas=new Array();
let stringMarcas='';




document.addEventListener('DOMContentLoaded', () => {
    verificarMensajeGeneral();
    managerAlert();
    asignarValidacion();
    botonLimpiar();
    botonAdjuntarArchivo();
    campoFile();

});

// Valida las sugerencias y los errores
function asignarValidacion(){
    const campos = document.querySelectorAll('.form__campo');
    const btnEliminar = document.querySelector('.eliminar-imagen');

    // Se dividen en varias funciones debido a que la función actual ya está sobrecargada
    campoCodigoBarras();
    campoCodigoManual();
    campoDescripcion();

    campos.forEach(campo => {

        const input = campo.querySelector('input');
        const select = campo.querySelector('select');
        const labelSugr = campo.querySelector('.form__labelSugerencia');
        const labelError = campo.querySelector('.form__labelError');
        const iconoError = campo.querySelector('.form__iconError');
        let tipo;
        let regla;
        let msg;
        let expresionRegular;

        //Aquí se valida únicamente los inputs
        if(input){

            tipo = input.getAttribute('data-tipo');
            
            // Colocamos las excepciones de los tres campos que ya tienen su propio código
            if(tipo != 'codigoBarras' && tipo != 'codigoManual' &&  tipo != 'prodDescripcion'){

                regla = reglaInput(tipo);
                msg = mensajeSugerencia(tipo);

                // Evento de carga de formulario---------
                expresionRegular = input.value.match(regla);

                if(expresionRegular){
                    estadoCampo(tipo, true);
                }else{
                    estadoCampo(tipo, false);
                }

                //---TERMINA LA EVALUACIÓN DE LOS CAMPOS
                input.addEventListener('input', e => {
                    expresionRegular = e.target.value.match(regla);
                    if(expresionRegular){
                        estadoCampo(tipo, true);
                        mostrarOcultarSugerencias(labelSugr, '', false);
                    }else{
                        estadoCampo(tipo, false);
                        mostrarOcultarSugerencias(labelSugr, msg, true);
                    }
                    if(labelError && labelError.textContent != ''){
                        ocultarError(labelError, iconoError);
                    }
                });
            }
            // evento de cambios
        }//Fin de las acciones de los inputs

        if(select){
            tipo = select.getAttribute('data-tipo');
            msg = mensajeSugerencia(tipo);

            // Se coloca esta condición para activar el botón cuando carga el formulario
            if(select.value != ''){
                estadoCampo(tipo, true);
            }

            select.addEventListener('change', ()=>{
                if (select.value !== ''){
                    mostrarOcultarSugerencias(labelSugr, '', false);
                    estadoCampo(tipo, true);
                    ocultarError(labelError, iconoError);
                }else{
                    mostrarOcultarSugerencias(labelSugr, msg, true);
                    estadoCampo(tipo, false);
                }
            });
        }
    });

    if (btnEliminar){
        
        btnEliminar.addEventListener('click', () => {

            Swal.fire({
                title: '¿Está seguro(a) de eliminar la imagen?',
                // text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Si, Eliminar!',
                cancelButtonText: 'No, Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
    
                    eliminarLogo();
                }
            })
        })
    }

}

/* CAMPOS EN ESPECIFICO */
function campoCodigoBarras(){

    const txtCodigoBarras = document.querySelector(`[data-tipo="codigoBarras"]`);
    const labelSugr = txtCodigoBarras.parentElement.querySelector('.form__labelSugerencia');
    const labelError = txtCodigoBarras.parentElement.querySelector('.form__labelError');
    const iconoError = txtCodigoBarras.parentElement.querySelector('.form__iconError');
    const txtCodigoManual = document.querySelector(`[data-tipo="codigoManual"]`);

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
            txtCodigoManual.value = e.target.value.substr(conteo, 6);
            estadoCampo('codigoManual', true);
        }else{
            txtCodigoManual.value = "";
            limpiarCaja(txtCodigoManual.parentElement, false);
            estadoCampo('codigoManual', false);
        }

        //buscamos los registros que coincidan
        
        // console.log('------------------');
        // console.log('Texto: ' + e.target.value + ' = ' + coincidencia);
        expresionRegular = e.target.value.match(regla);

        // Si cumple con la expresión regular
        if(expresionRegular){
            // Se elimina cualquier sugerencia que se haya generado
            mostrarOcultarSugerencias(labelSugr, '', false);

            // Verificamos si coincide de forma parcial o completa
            if(stringCodigoBarras.includes(e.target.value)){

                // Si coincide la cadena de forma completa se muestra la alerta que este código ya se encuentra registrado
                if (arrayCodigoBarras.includes(e.target.value)){

                    mostrarOcultarSugerencias(labelSugr, 'Este código ya se encuentra registrado', true);
    
                }else{
                    // Si no coincide en su totalidad con algún registro entonces quitamos la alerta
                    mostrarOcultarSugerencias(labelSugr, '', false);
                    // estadoCampo('codigoManual', true);
                }
                
                // Se mostrarán los registros que coinciden de forma parcial o completa los registros
                borrarFilas();
                mostrarRegistrosAPI( 'input', 'Cod_Barras', texto);
    
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
            if(stringCodigoManual.includes(e.target.value)){

                // Si coincide la cadena de forma completa se muestra la alerta que este código ya se encuentra registrado
                if (arrayCodigoManual.includes(e.target.value)){

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
            if(stringDescripcion.includes(texto)){

                // Si coincide la cadena de forma completa se muestra la alerta que este código ya se encuentra registrado
                if (arrayDescripcion.includes(texto)){

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

/* FINALIZA CAMPOS */

function reglaInput(tipo){
    let regla;

    if (tipo == 'codigoBarras'){
        regla="^[0-9A-Za-z]{4,20}$";
    }

    if (tipo == 'codigoManual'){
        regla="^[0-9A-Za-z]{4,20}$";
    }
    
    if (tipo == 'prodDescripcion'){
        regla="^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{4,100}$";
    }

    if (tipo == 'cantStock'){
        regla="^[0-9]{1,3}$";
    }
    
    if (tipo == 'valorVenta'){
        regla="^[0-9]{2,6}$";
    }
    
    if (tipo == 'valorDescuento'){
        regla="^[0-9]{2,6}$";
    }
    
    if (tipo == 'cantOferta'){
        regla="^[0-9]{1,3}$";
    }
    
    if (tipo == 'valorOferta'){
        regla="^[0-9]{2,6}$";
    }
    
    if (tipo == 'prodObservaciones'){
        regla="^[0-9A-ZÑa-züñáéíóúÁÉÍÓÚÜ ]{4,100}$";
    }

    return regla;
}

function mensajeSugerencia(tipo){
    let msg;

    if (tipo == 'codigoBarras'){
        msg="Este campo es alfanumérico, mayor a 4 caracteres y no puede contener espacios ni caracteres especiales";
    }

    if (tipo == 'codigoManual'){
        msg="Este campo es alfanumérico, mayor a 4 caracteres y no puede contener espacios ni caracteres especiales";
    }

    if (tipo == 'prodDescripcion'){
        msg="Este campo es alfanumérico, no se permite caractéres especiales, y debe tener más de 4 caracteres";
    }

    if (tipo == 'categoria'){
        msg="Debe seleccionar una categoría";
    }

    if (tipo == 'marca'){
        msg="Debe seleccionar una marca";
    }

    if (tipo == 'cantStock'){
        msg="Este campo solo puede contener números, no mayor a 3 digitos";
    }
    
    if (tipo == 'valorVenta'){
        msg="Este campo solo puede contener números, no mayor a 6 dígitos";
    }

    if (tipo == 'valorDescuento'){
        msg="Este campo solo puede contener números, no mayor a 6 dígitos";
    }
    
    if (tipo == 'cantOferta'){
        msg="Este campo solo puede contener números, no mayor a 3 digitos";
    }
    
    if (tipo == 'valorOferta'){
        msg="Este campo solo puede contener números, no mayor a 6 dígitos";
    }

    if (tipo == 'prodObservaciones'){
        msg="Este campo es alfanumérico, mayor a 10 caracteres y no puede contener espacios ni caracteres especiales";
    }

    return msg;
}

// Se inicia en vacío ya que se utiliza en la carga del DOM
function estadoCampo(tipo, estado){

    if (tipo == 'codigoManual'){
        objeto['codigoManual']=estado;
    }

    if (tipo == 'prodDescripcion'){
        objeto['prodDescripcion']=estado;
    }

    if (tipo == 'categoria'){
        objeto['categoria']=estado;
    }

    if (tipo == 'marca'){
        objeto['marca']=estado;
    }

    if (tipo == 'cantStock'){
        objeto['cantStock']=estado;
    }

    if (tipo == 'valorVenta'){
        objeto['valorVenta']=estado;
    }

    // console.log(objeto);

    for (let llave in objeto){
        if(objeto[llave] == false){
            cntEstado=0;
        }else{
            cntEstado++;
        }
    }
    
    if(cntEstado == 6){
        estadoBoton(true);
    }else{
        estadoBoton(false);
    }

    cntEstado=0;
}

function ocultarError(labelError, iconoError){
    labelError.textContent='';
    labelError.classList.add('ocultar');
    iconoError.classList.add('ocultar');
    iconoError.previousElementSibling.style.right = "0.5rem";
}

// Función dependiente de la función 'validarcampos()'
// muestra u oculta el error
function mostrarOcultarSugerencias(label, msg, estado){
    if (estado){
        label.classList.remove('ocultar');
        label.textContent=msg;
    }else{
        label.classList.add('ocultar');
        label.textContent="";
    }
}

// Función dependiente de la función 'asignarValidacion()'
// activa o desactiva el botón de Guardar
function estadoBoton(estado){
    const boton = document.querySelector(`[type="submit"]`);

    if (estado){
        boton.classList.remove('disabled');
        boton.removeAttribute('disabled');                                
    }else{
        boton.classList.add('disabled');
        boton.setAttribute('disabled', '');                                
    }
}

// Muestra la alerta de carga de datos y ejecuta la función de consulta la APi de categorías
function managerAlert(){

    const alertaExito = document.querySelector('.alerta.exito');
    const alertaError = document.querySelector('.alerta.error');

        
    // Si existe una alerta de exito proveniente del servidor se da un tiempo de espera para que la alerta de cargar registros
    if (alertaExito){
        setTimeout(() => {
            cargaRegistros();
        },2000);
    }else if(!alertaError){
        cargaRegistros();
    }

    // Verificamos el error local del campo
    let labelsError = document.querySelectorAll('.form__labelError');

    // Mostrar los mensajes de error que aparecen en el label cuando provienen del servidor
    labelsError.forEach( label => {
        const campo = label.parentElement;
        const iconoError = campo.querySelector('.form__iconError');
        const iconoLimpiar = campo.querySelector('.form__limpiar');
        
        if(label.textContent != ''){

            label.classList.remove('ocultar');
            iconoError.classList.remove('ocultar');

            if (iconoLimpiar){
                iconoLimpiar.style.right = "3rem";
            }

        }else{

            label.classList.add('ocultar');
            iconoError.classList.add('ocultar');

            if(iconoLimpiar){
                iconoLimpiar.style.right = "0.5rem";
            }
        }
    });
}

function cargaRegistros(){
    
    Swal.fire({
        title: 'Cargando registros...',
        showConfirmButton: false,
        didOpen: () => {
          Swal.showLoading();
          let resultado = obtenerRegistrosAPI();
          resultado.then((result) => {
              if (result){
                  setTimeout(() => {
                      Swal.close();
                  }, 500);
              }            
          }); 
        }
    });
}

//Función dependiente de la función cargaRegistros
// Consulta la API y ejecuta la función para crear los elementos
async function obtenerRegistrosAPI(){

    try {

        const url='http://192.168.18.120:3000/producto/api';
        const resultado = await fetch(url);
        registrosProductos = await resultado.json();

        const urlCategorias = 'http://192.168.18.120:3000/categoria/api';
        const resultadoCategorias = await fetch(urlCategorias);
        registrosCategorias = await resultadoCategorias.json();

        const urlMarcas = 'http://192.168.18.120:3000/marca/api';
        const resultadoMarcas = await fetch(urlMarcas);
        registrosMarcas = await resultadoMarcas.json();

        mostrarRegistrosAPI();
        cargarRegistrosCategorias();
        cargarRegistrosMarcas();

        return true;

    } catch (error) {
        Swal.fire({
            icon:'error',
            title:'Error',
            text:error + ' No fue posible cargar los registros de la base de datos'
        });
        return false;
    }
}

// Función dependiente de la función 'obtenerRegistrosAPI()'
// Muestra en el HTML los resultados de la API
// Utilizamos el evento para evitar cargar el arreglo general de nombres en un evento diferente a la carga del formulario
function mostrarRegistrosAPI( evento = 'DOM', campo = '', valor = ''){
    // Verificamos que exista una tabla en el body, ya que si no está... quiere decir que estamos utilizando este codigo en editar-categoria, y allí no existe la tabla de registros
    const tabla = document.querySelector('.table');

    if (registrosProductos){


        if (evento === 'DOM'){

            arrayDescripcion=[];
            arrayCodigoBarras=[];
            arrayCodigoManual=[];

        }

        registrosProductos.forEach( registro => {
            
            const {Prod_Descripcion, Cod_Manual, Cod_Barras} = registro;
            
            if(evento === 'DOM' && valor === ''){

                // Almacenamos en el arreglo global 'nombreCategorias' todos los nombres para usarlos en otra función
                arrayDescripcion.push(formatearTexto(Prod_Descripcion));
                arrayCodigoBarras.push(Cod_Barras);
                arrayCodigoManual.push(Cod_Manual);
    
                // Si la tabla si existe, entonces se procede a cargar los registros 
                if (tabla){
                    crearRegistro(registro);
                }
            }
            
            // Si lo que digitó el usuario se encuentra dentro del codigo de barras entonces se agrega el registro
            if(evento === 'input' && campo === 'Cod_Barras' && Cod_Barras.includes(valor)){
                if(tabla){
                    crearRegistro(registro);
                }
            }

            // Si lo que digitó el usuario se encuentra dentro del codigo de barras entonces se agrega el registro
            if(evento === 'input' && campo === 'Cod_Manual' && Cod_Manual.includes(valor)){
                if(tabla){
                    crearRegistro(registro);
                }
            }

            // Si lo que digitó el usuario se encuentra dentro del codigo de barras entonces se agrega el registro
            if(evento === 'input' && campo === 'prodDescripcion' && formatearTexto(Prod_Descripcion.toLowerCase()).includes(valor)){
                if(tabla){
                    crearRegistro(registro);
                }
            }
        });
    }

    // Guardamos todos los nombres de las categorias en un solo string para utilizarlo en el filtro del campo
    stringDescripcion=arrayDescripcion.toString();
    stringCodigoManual=arrayCodigoManual.toString();
    stringCodigoBarras=arrayCodigoBarras.toString();

}

function cargarRegistrosCategorias(){
    // Verificamos que exista una tabla en el body, ya que si no está... quiere decir que estamos utilizando este codigo en editar-categoria, y allí no existe la tabla de registros
    const cmbCategoria = document.querySelector(`[data-tipo="categoria"]`);
    let optionCategoria='';
   
    if (registrosCategorias){

        // Borramos las opciones que puedan existir
        const options = cmbCategoria.querySelectorAll('option');
        options.forEach( option => {
            if (option.getAttribute('value') != "" ){
                option.remove();
            }
        });
        
        registrosCategorias.forEach( registro => {
            
            let {Ctg_Id, Ctg_Descripcion, Ctg_Status} = registro;
            
            if(Ctg_Status == 'E'){
                optionCategoria = document.createElement('OPTION'); 
                optionCategoria.setAttribute('value', Ctg_Id);
                optionCategoria.textContent=Ctg_Descripcion;
            }
            cmbCategoria.appendChild(optionCategoria);
        });
    }   
}

function cargarRegistrosMarcas(){
    // Verificamos que exista una tabla en el body, ya que si no está... quiere decir que estamos utilizando este codigo en editar-categoria, y allí no existe la tabla de registros
    const cmbMarca = document.querySelector(`[data-tipo="marca"]`);
    let optionMarca='';
   
    if (registrosMarcas){

        // Borramos las opciones que puedan existir
        const options = cmbMarca.querySelectorAll('option');
        options.forEach( option => {
            if (option.getAttribute('value') != "" ){
                option.remove();
            }
        });
        
        registrosMarcas.forEach( registro => {
            
            let {Mc_Id, Mc_Descripcion, Mc_Status} = registro;
            
            if(Mc_Status == 'E'){
                optionMarca = document.createElement('OPTION'); 
                optionMarca.setAttribute('value', Mc_Id);
                optionMarca.textContent=Mc_Descripcion;
            }

            cmbMarca.appendChild(optionMarca);

        });
    }   
}

// Se borra las filas para realizar el filtro de búsqueda
function borrarFilas(){
    const filas = document.querySelectorAll('.tbody tr');
    filas.forEach( fila => {
        fila.remove();
    });
}

function crearRegistro(registro){
    const {Prod_Id, Cod_Manual, Prod_Descripcion, Ctg_Descripcion, 
        Mc_Descripcion, Prod_ValorVenta, Prod_ValorDesc, 
        Prod_CantMinStock, PO_Cant, PO_ValorOferta, Prod_Status} = registro;

    const spanCod_Manual = document.createElement('SPAN');    
    spanCod_Manual.classList.add('tbody__td--titulo');
    spanCod_Manual.textContent = "Cod. Manual: ";
    
    const textoCod_Manual = document.createElement('SPAN');
    textoCod_Manual.textContent = Cod_Manual;
    
    const tdCod_Manual = document.createElement('TD');
    tdCod_Manual.dataset.idItem = Prod_Id;
    tdCod_Manual.appendChild(spanCod_Manual);
    tdCod_Manual.appendChild(textoCod_Manual);
    
    // -----------------------------------------------------------
    
    const spanProd_Descripcion = document.createElement('SPAN');    
    spanProd_Descripcion.classList.add('tbody__td--titulo');
    spanProd_Descripcion.textContent = "Descripción: ";
    
    const textoProd_Descripcion = document.createElement('SPAN');
    textoProd_Descripcion.textContent = Prod_Descripcion;

    const tdProd_Descripcion = document.createElement('TD');
    tdProd_Descripcion.classList.add('tbody__td--nombre');
    tdProd_Descripcion.appendChild(spanProd_Descripcion);
    tdProd_Descripcion.appendChild(textoProd_Descripcion);

    // ------------------------------------------------------------

    const spanCtg_Descripcion = document.createElement('SPAN');    
    spanCtg_Descripcion.classList.add('tbody__td--titulo');
    spanCtg_Descripcion.textContent = "Categoría: ";
    
    const textoCtg_Descripcion = document.createElement('SPAN');
    textoCtg_Descripcion.textContent = Ctg_Descripcion;

    const tdCtg_Descripcion = document.createElement('TD');
    tdCtg_Descripcion.appendChild(spanCtg_Descripcion);
    tdCtg_Descripcion.appendChild(textoCtg_Descripcion);

    // ------------------------------------------------------

    const spanMc_Descripcion = document.createElement('SPAN');    
    spanMc_Descripcion.classList.add('tbody__td--titulo');
    spanMc_Descripcion.textContent = "Marca: ";
    
    const textoMc_Descripcion = document.createElement('SPAN');
    textoMc_Descripcion.textContent = Mc_Descripcion;

    const tdMc_Descripcion = document.createElement('TD');
    tdMc_Descripcion.appendChild(spanMc_Descripcion);
    tdMc_Descripcion.appendChild(textoMc_Descripcion);

    //----------------------------------------------------

    const spanProd_ValorVenta = document.createElement('SPAN');    
    spanProd_ValorVenta.classList.add('tbody__td--titulo');
    spanProd_ValorVenta.textContent = "Valor de Venta: ";
    
    const textoProd_ValorVenta = document.createElement('SPAN');
    textoProd_ValorVenta.textContent = Prod_ValorVenta;

    const tdProd_ValorVenta = document.createElement('TD');
    tdProd_ValorVenta.classList.add('tbody__td--verde');
    tdProd_ValorVenta.classList.add('tbody__td--center');
    tdProd_ValorVenta.appendChild(spanProd_ValorVenta);
    tdProd_ValorVenta.appendChild(textoProd_ValorVenta);
    
    //----------------------------------------------------
    
    const spanProd_CantMinStock = document.createElement('SPAN');    
    spanProd_CantMinStock.classList.add('tbody__td--titulo');
    spanProd_CantMinStock.textContent = "Cant Min Stock: ";
    
    const textoProd_CantMinStock = document.createElement('SPAN');
    textoProd_CantMinStock.textContent = Prod_CantMinStock;
    
    const tdProd_CantMinStock = document.createElement('TD');
    tdProd_CantMinStock.classList.add('tbody__td--center');
    tdProd_CantMinStock.appendChild(spanProd_CantMinStock);
    tdProd_CantMinStock.appendChild(textoProd_CantMinStock);
    
    //-------------------------------------------------------
    
    const spanProd_ValorDesc = document.createElement('SPAN');    
    spanProd_ValorDesc.classList.add('tbody__td--titulo');
    spanProd_ValorDesc.textContent = "Valor Descuento: ";
    
    const textoProd_ValorDesc = document.createElement('SPAN');
    textoProd_ValorDesc.textContent = Prod_ValorDesc;
    
    const tdProd_ValorDesc = document.createElement('TD');
    tdProd_ValorDesc.classList.add('tbody__td--center');
    tdProd_ValorDesc.classList.add('tbody__td--rojo');
    tdProd_ValorDesc.appendChild(spanProd_ValorDesc);
    tdProd_ValorDesc.appendChild(textoProd_ValorDesc);
    
    //-------------------------------------------------
    
    const spanPO_Cant = document.createElement('SPAN');    
    spanPO_Cant.classList.add('tbody__td--titulo');
    spanPO_Cant.textContent = "Valor Descuento: ";
    
    const textoPO_Cant = document.createElement('SPAN');
    textoPO_Cant.textContent = PO_Cant;
    
    const tdPO_Cant = document.createElement('TD');
    tdPO_Cant.classList.add('tbody__td--center');
    tdPO_Cant.classList.add('tbody__td--azul');
    tdPO_Cant.appendChild(spanPO_Cant);
    tdPO_Cant.appendChild(textoPO_Cant);
    
    //------------------------------------------------
    
    const spanPO_ValorOferta = document.createElement('SPAN');    
    spanPO_ValorOferta.classList.add('tbody__td--titulo');
    spanPO_ValorOferta.textContent = "Valor Oferta: ";
    
    const textoPO_ValorOferta = document.createElement('SPAN');
    textoPO_ValorOferta.textContent = PO_ValorOferta;
    
    const tdPO_ValorOferta = document.createElement('TD');
    tdPO_ValorOferta.classList.add('tbody__td--azul');
    tdPO_ValorOferta.classList.add('tbody__td--center');
    tdPO_ValorOferta.appendChild(spanPO_ValorOferta);
    tdPO_ValorOferta.appendChild(textoPO_ValorOferta);

    //-------------------------------------------------------

    const spanEstado = document.createElement('SPAN');    
    spanEstado.classList.add('tbody__td--titulo');
    spanEstado.textContent = "Estado: ";
    
    const textoEstado = document.createElement('SPAN');
    if (Prod_Status==='E'){
        textoEstado.textContent = "Habilitado";
    }else if (Prod_Status==='D'){
        textoEstado.textContent = "Deshabilitado";
    }else{
        textoEstado.textContent = "";
    }
    
    const tdEstado = document.createElement('TD');
    tdEstado.classList.add('tbody__td--estado');
    tdEstado.classList.add('center');
    tdEstado.appendChild(spanEstado);
    tdEstado.appendChild(textoEstado);


    //1. Creamos el bloque de editar, empezando por los hijos hasta el padre
    //1.1 Creamos el SPAN donde la se coloca la palabra Modificar
    const spanModificar = document.createElement('SPAN');
    spanModificar.textContent="Modificar";

    // 1.2.Creamos la imagen del icono editar
    const imgModificar = document.createElement('IMG');
    imgModificar.setAttribute('src','/build/img/sistema/editar-ng.svg');
    imgModificar.setAttribute('alt','Imagen Editar');

    // 1.3. Creamos el contenedor el cual es el enlace de la etiqueta <a>
    const linkModificar = document.createElement('A');
    linkModificar.setAttribute('href', `/producto/editar?id=${Prod_Id}`);
    linkModificar.appendChild(imgModificar);
    linkModificar.appendChild(spanModificar);

    //2. Creamos el bloque de editar, empezando por los hijos hasta el padre
    //2.1 Creamos el SPAN donde la se coloca la palabra Modificar
    const spanHistorial = document.createElement('SPAN');
    spanHistorial.textContent="Historial";

    // 2.2.Creamos la imagen del icono editar
    const imgHistorial = document.createElement('IMG');
    imgHistorial.setAttribute('src','/build/img/sistema/historial.svg');
    imgHistorial.setAttribute('alt','Imagen Historial');

    // 2.3. Creamos el contenedor el cual es el enlace de la etiqueta <a>
    const linkHistorial = document.createElement('A');
    linkHistorial.setAttribute('href', `/historial?id=${Prod_Id}`);
    linkHistorial.appendChild(imgHistorial);
    linkHistorial.appendChild(spanHistorial);

    // 3 Creamos el siguiente contenedor que es la celda de la tabla para los items de Modificar
    const tdModificar = document.createElement('TD');
    tdModificar.classList.add('tbody__td--icon');
    tdModificar.appendChild(linkModificar);

    // 4 Creamos el siguiente contenedor que es la celda de la tabla para los items de Historial
    const tdHistorial = document.createElement('TD');
    tdHistorial.classList.add('tbody__td--icon');
    tdHistorial.appendChild(linkHistorial);

    // 5. Creamos el <tr> donde irá todo el contenido creado anteriormente
    const tr = document.createElement('TR');
    tr.appendChild(tdProd_Descripcion);
    tr.appendChild(tdCod_Manual);
    tr.appendChild(tdCtg_Descripcion);
    tr.appendChild(tdMc_Descripcion);
    tr.appendChild(tdProd_CantMinStock);
    tr.appendChild(tdProd_ValorVenta);
    tr.appendChild(tdProd_ValorDesc);
    tr.appendChild(tdPO_Cant);
    tr.appendChild(tdPO_ValorOferta);
    tr.appendChild(tdEstado);
    tr.appendChild(tdModificar);
    tr.appendChild(tdHistorial);

    // Por último se muestra en el HTML
    document.querySelector('.tbody').appendChild(tr);

}

// Mostrar Alerta de error o Exito con Sweet Alert
function verificarMensajeGeneral(){
    const alerta = document.querySelector('.alerta');

    if (alerta){
        let textoAlerta = alerta.textContent;

        if(alerta.className.includes('error')){
            Swal.fire({
                icon: 'error',
                title: textoAlerta,
                showConfirmButton: true
            });
        }
        
        if(alerta.className.includes('exito')){              
            Swal.fire({
                icon: 'success',
                title: textoAlerta,
                timer:1800
            }); 
        }
    }
}

// limpia el campo de categoria
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

            // verificamos sin los tres campos por los cuales se filtra la información se encuentran vacíos para resetear los registros
            if ( txtCodBarras.value == '' ||  txtCodManual.value == '' ||  txtDescripcion.value == '' ){

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

// limpia el campo y los mensajes de error
function limpiarCaja(item, foco = true){
    const input = item.querySelector('.form__input');
    const labelSugerencia = item.querySelector('.form__labelSugerencia');
    const labelError = item.querySelector('.form__labelError');
    const iconoError = item.querySelector('.form__iconError');
    const iconoLimpiar = item.querySelector('.form__limpiar');
    const tipo = input.getAttribute('data-tipo');

    // Ocultamos y vaciamos los errores
    if (!labelSugerencia.className.includes('ocultar')){
        labelSugerencia.classList.add('ocultar');
        labelSugerencia.textContent = '';
    }

    if(!labelError.className.includes('ocultar')){
        labelError.classList.add('ocultar');
        labelError.textContent = '';
    }
    
    if(!iconoError.className.includes('ocultar')){
        iconoError.classList.add('ocultar');
    }
    
    iconoLimpiar.style.right = "0.5rem";
    input.value='';

    if (foco){
        input.focus();
    }
}

function botonAdjuntarArchivo(){
    const btnAdjuntar = document.querySelector(`[data-tipo="boton-adjuntar"]`);
    
    let crearBotonAdjuntar = true;
    
    btnAdjuntar.addEventListener('click', () =>{

        // Verificamos si se adjuntaron 
        const filesAttachment = document.querySelectorAll('.textFileSelect');
        if (filesAttachment){
            filesAttachment.forEach( file => {
                if (file.textContent!=''){
                    crearBotonAdjuntar = true;
                }else{
                    crearBotonAdjuntar = false;
                }
            });
        }

        if(crearBotonAdjuntar){
         
            const inputFile = document.createElement('INPUT');
            inputFile.setAttribute('type','file');
            inputFile.setAttribute('data-tipo','archivo');
            inputFile.setAttribute('name','ImVd_Nombre[]');
            inputFile.setAttribute('accept','image/png,image/jpg,image/jpeg,video/mp4');

            const inputBoton = document.createElement('INPUT');
            inputBoton.setAttribute('type', 'button');
            inputBoton.setAttribute('data-tipo', 'boton-archivo');
            inputBoton.setAttribute('value', 'Seleccionar archivo');
            inputBoton.classList.add('form__btn');
            inputBoton.classList.add('btn-terciario');

            const span = document.createElement('SPAN');
            span.classList.add('textFileSelect');

            const img = document.createElement('IMG');
            img.setAttribute('src','/build/img/sistema/error.svg');
            img.setAttribute('alt','icono de error');
            img.classList.add('form__iconError');
            img.classList.add('ocultar');

            const labelSugerencia = document.createElement('LABEL');
            labelSugerencia.classList.add('form__labelSugerencia');
            labelSugerencia.classList.add('ocultar');
            
            const labelError = document.createElement('LABEL');
            labelError.classList.add('form__labelError');
            labelError.classList.add('ocultar');
            // labelError.textContent = "<?php echo tipoAlerta($alertas, 'error-archivo', 'nombre');?>";

            const divContenedor = document.createElement('DIV');
            divContenedor.classList.add('form__campo');
            divContenedor.classList.add('campo--file');
            divContenedor.classList.add('t-xxl');

            divContenedor.appendChild(inputFile);
            divContenedor.appendChild(inputBoton);
            divContenedor.appendChild(span);
            divContenedor.appendChild(img);
            divContenedor.appendChild(labelSugerencia);
            divContenedor.appendChild(labelError);
            
            const formulario = document.querySelector('.form');

            const boton = document.querySelector('.form__button');
            formulario.insertBefore(divContenedor, boton);
            
            campoFile();
            
        }
    });

}

function campoFile(){
    const inputFiles = document.querySelectorAll(`[type="file"]`);
    
    inputFiles.forEach(inputFile => {

        const campo = inputFile.parentElement;
        const fileSelect = campo.querySelector('.textFileSelect');
        const inputbutton = campo.querySelector(`[type="button"]`);
        const iconoError = campo.querySelector('.campo--file > .form__iconError');
        const labelSugerencia = campo.querySelector('.campo--file > .form__labelSugerencia');
        const labelError = campo.querySelector('.campo--file > .form__labelError');
    
        if (inputFile && inputbutton){
            inputbutton.addEventListener('click', ()=>{
                inputFile.click();
                inputFile.addEventListener('input', ()=>{
    
                    fileSelect.textContent = inputFile.value;
    
                    if(fileSelect.textContent !== '' && !labelError.className.includes('ocultar')){
                        labelError.classList.add('ocultar');
                        iconoError.classList.add('ocultar');
                    }
    
                    // console.log(inputFile.files[0]);
                    // let pesoArchivo = inputFile.files[0].size;
    
                    // let pesoMB = (pesoArchivo / 1000000).toFixed(2);
                    
                    // if(pesoArchivo > 500000){
                    //     labelSugerencia.textContent = `El archivo seleccionado tiene un peso de ${pesoMB} MB, y NO debe superar el peso de 500 KB`;
                    //     labelSugerencia.classList.remove('ocultar');
                    // }
    
                    // let tipoArchivo = inputFile.files[0].type;
                    // if(!(tipoArchivo === "image/jpeg" || tipoArchivo === "image/jpg" || tipoArchivo === "image/png")){
                    //     labelSugerencia.textContent = `Seleccionó un archivo que NO es permitido`;
                    //     labelSugerencia.classList.remove('ocultar');
                    // }
                });
            });
        }
    });
}

function formatearTexto(cadena){
    
    cadena=cadena.toLowerCase().trim();
    cadena=cadena.replaceAll('á', 'a');
    cadena=cadena.replaceAll('é', 'e');
    cadena=cadena.replaceAll('í', 'i');
    cadena=cadena.replaceAll('ó', 'o');
    cadena=cadena.replaceAll('ú', 'u');
    
    cadena=cadena.replaceAll('à', 'a');
    cadena=cadena.replaceAll('è', 'e');
    cadena=cadena.replaceAll('ì', 'i');
    cadena=cadena.replaceAll('ò', 'o');
    cadena=cadena.replaceAll('ù', 'u');
    return cadena;

}
