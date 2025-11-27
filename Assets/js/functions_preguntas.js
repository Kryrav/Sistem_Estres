let tablePreguntas;
let divLoading = document.querySelector("#divLoading");
let formPregunta = document.querySelector("#formPregunta");
let opcionesContainer = document.querySelector("#opciones-container");
let btnAddOpcion = document.querySelector("#btnAddOpcion");
let listTipoPregunta = document.querySelector("#listTipoPregunta");

document.addEventListener('DOMContentLoaded', function(){

    // 1. Cargar el SELECT de Categorías (AJAX)
    fntCategoriasSelect();
    
    // 2. Inicialización de DataTables
    tablePreguntas = $('#tablePreguntas').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax":{
            "url": " "+base_url+"/Preguntas/getPreguntas",
            "dataSrc":""
        },
        "columns":[
            {"data":"id"},
            {"data":"texto_pregunta"},
            {"data":"categoria"},
            {"data":"tipo_pregunta"},
            {"data":"activo"},
            {"data":"options"}
        ],
        "responsive":true,
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[0,"desc"]]  
    });

    // 3. Manejo Dinámico de Opciones
    listTipoPregunta.addEventListener('change', function() {
        showOpcionesPanel();
    });

    // Evento para añadir una nueva fila de opción
    btnAddOpcion.addEventListener('click', function() {
        addRowOpcion();
    });

    // 4. Manejo del Formulario (Insertar/Actualizar)
    formPregunta.onsubmit = function(e) {
        e.preventDefault();

        let categoriaId = document.querySelector('#listCategoria').value;
        let textoPregunta = document.querySelector('#txtTextoPregunta').value;
        let tipoPregunta = document.querySelector('#listTipoPregunta').value;

        if(categoriaId == '' || textoPregunta == '' || tipoPregunta == '') {
            swal("Atención", "Todos los campos marcados con (*) son obligatorios.", "error");
            return false;
        }

        let opcionesData = [];
        let needsOptions = (tipoPregunta == 'ESCALA' || tipoPregunta == 'OPCION');

        if (needsOptions) {
            let opcionesRows = opcionesContainer.querySelectorAll('tr');
            if (opcionesRows.length === 0) {
                 swal("Atención", "Debe agregar al menos una opción de respuesta para este tipo de pregunta.", "error");
                 return false;
            }

            try {
                opcionesRows.forEach(row => {
                    let texto = row.querySelector('.txtOpcionTexto').value;
                    let valor = row.querySelector('.txtOpcionValor').value;

                    if (texto.trim() === "") {
                        swal("Atención", "El texto de todas las opciones es obligatorio.", "error");
                        throw new Error("Opción vacía"); 
                    }

                    opcionesData.push({
                        texto: texto,
                        valor: valor
                    });
                });
            } catch (error) {
                // Captura el error de la opción vacía para detener el submit
                return false; 
            }
        }
        
        divLoading.style.display = "flex";
        
        let formData = new FormData(formPregunta);
        // Enviamos el array de opciones serializado como JSON
        formData.append('opciones', JSON.stringify(opcionesData)); 
        
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'/Preguntas/setPregunta'; 
        
        request.open("POST",ajaxUrl,true);
        request.send(formData);

        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                
                let objData = JSON.parse(request.responseText);
                if(objData.status)
                {
                    $('#modalFormPregunta').modal("hide");
                    formPregunta.reset();
                    swal("Banco de Preguntas", objData.msg, "success");
                    tablePreguntas.api().ajax.reload(null,false);
                }else{
                    swal("Error", objData.msg , "error");
                }
            }
            divLoading.style.display = "none";
        }
    }

}, false);


// --- FUNCIONES AUXILIARES ---

/**
 * Carga las categorías activas en el SELECT del formulario.
 */
function fntCategoriasSelect() {
    let ajaxUrl = base_url+'/Categorias/getSelectCategorias';
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            document.querySelector('#listCategoria').innerHTML = '<option value="">Seleccione Categoría</option>' + request.responseText;
            $('#listCategoria').selectpicker('refresh');
        }
    }
}

/**
 * Muestra u oculta el panel de opciones y el botón 'Agregar Opción'
 */
function showOpcionesPanel() {
    let tipo = listTipoPregunta.value;
    let alerta = document.querySelector('#opciones-alerta');
    
    opcionesContainer.innerHTML = ''; // Limpiar opciones al cambiar tipo
    
    if (tipo === 'ESCALA' || tipo === 'OPCION') {
        btnAddOpcion.style.display = 'block';
        alerta.style.display = 'none';
    } else {
        // Tipo Texto Libre
        btnAddOpcion.style.display = 'none';
        alerta.style.display = 'block';
    }
}

/**
 * Añade una nueva fila de opción a la tabla dinámica (MODO ESCRITURA).
 */
function addRowOpcion(texto = '', valor = '') {
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td><input type="text" class="form-control form-control-sm txtOpcionTexto" placeholder="Texto de la respuesta" value="${texto}" required></td>
        <td><input type="number" class="form-control form-control-sm txtOpcionValor" placeholder="Valor" value="${valor}" required></td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRowOpcion(this)">
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
    `;
    opcionesContainer.appendChild(newRow);
}

/**
 * Añade una nueva fila de opción a la tabla dinámica (MODO SÓLO LECTURA).
 */
function addRowOpcionReadOnly(texto = '', valor = '') {
    const newRow = document.createElement('tr');
    newRow.innerHTML = `
        <td><input type="text" class="form-control form-control-sm" value="${texto}" disabled></td>
        <td><input type="number" class="form-control form-control-sm" value="${valor}" disabled></td>
        <td class="text-center">
            <button type="button" class="btn btn-secondary btn-sm" disabled>
                <i class="fas fa-eye"></i>
            </button>
        </td>
    `;
    opcionesContainer.appendChild(newRow);
}

/**
 * Elimina una fila de opción.
 */
function removeRowOpcion(element) {
    element.closest('tr').remove();
}

/**
 * Función auxiliar para deshabilitar/habilitar todos los campos del modal y ocultar/mostrar botones.
 * @param {boolean} isReadOnly Si es true, deshabilita y oculta el botón principal.
 */
function setModalReadOnly(isReadOnly) {
    let formElements = formPregunta.querySelectorAll('input, select, textarea');
    formElements.forEach(element => {
        // Solo deshabilitar si no es el botón de cerrar
        if (!element.classList.contains('close')) {
            element.disabled = isReadOnly;
        }
    });

    // Ocultar o mostrar botones de acción del formulario
    document.querySelector('#btnActionForm').style.display = isReadOnly ? 'none' : 'inline-block';
}


/**
 * Abre el modal para Nueva Pregunta.
 */
function openModal()
{
    document.querySelector('#id').value = "";
    document.querySelector('#titleModal').innerHTML = "Nueva Pregunta para Encuesta";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar Pregunta";
    formPregunta.reset();
    
    // HABILITAR CAMPOS Y BOTONES
    setModalReadOnly(false); 
    
    // Configurar selectores y panel dinámico al abrir
    $('#listCategoria').val('').selectpicker('refresh');
    $('#listTipoPregunta').val('ESCALA').selectpicker('refresh');
    $('#listActivo').val('1').selectpicker('refresh');
    showOpcionesPanel(); // Inicializar panel de opciones (vacío) y mostrar btnAddOpcion

    $('#modalFormPregunta').modal('show');
}


/**
 * Función para Editar Pregunta
 */
function fntEditPregunta(element, id){
    document.querySelector('#titleModal').innerHTML ="Actualizar Pregunta";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";
    
    // Asegurar que el modal está en modo ESCRITURA
    setModalReadOnly(false); 

    divLoading.style.display = "flex";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Preguntas/getPregunta/'+id;
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            if(objData.status)
            {
                let data = objData.data;
                
                // 1. Llenar campos principales
                document.querySelector('#id').value = data.id;
                document.querySelector('#txtTextoPregunta').value = data.texto_pregunta;
                
                // 2. Seleccionar Categoría y Tipo
                $('#listCategoria').val(data.categoria_id).selectpicker('refresh');
                $('#listTipoPregunta').val(data.tipo_pregunta).selectpicker('refresh');
                
                // 3. Seleccionar Estado
                $('#listActivo').val(data.activo).selectpicker('refresh');
                
                // 4. Configurar panel de opciones (limpiar e inicializar)
                showOpcionesPanel();
                
                // 5. Cargar Opciones Dinámicas
                if(data.tipo_pregunta === 'ESCALA' || data.tipo_pregunta === 'OPCION') {
                    opcionesContainer.innerHTML = ''; 
                    data.opciones.forEach(opcion => {
                        addRowOpcion(opcion.texto_opcion, opcion.valor_numerico);
                    });
                    document.querySelector('#btnAddOpcion').style.display = 'block';
                } else {
                     document.querySelector('#btnAddOpcion').style.display = 'none';
                }
                
                $('#modalFormPregunta').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
        divLoading.style.display = "none";
    }
}


/**
 * Función para Ver los Detalles de una Pregunta (Solo lectura)
 */
function fntViewPregunta(id) {
    divLoading.style.display = "flex";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Preguntas/getPregunta/'+id;
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            if(objData.status)
            {
                let data = objData.data;
                
                // 1. Llenar campos principales
                document.querySelector('#id').value = data.id;
                document.querySelector('#txtTextoPregunta').value = data.texto_pregunta;
                
                // 2. Seleccionar Categoría y Tipo
                $('#listCategoria').val(data.categoria_id).selectpicker('refresh');
                $('#listTipoPregunta').val(data.tipo_pregunta).selectpicker('refresh');
                
                // 3. Seleccionar Estado
                $('#listActivo').val(data.activo).selectpicker('refresh');
                
                // 4. Configurar panel de opciones (limpiar e inicializar)
                showOpcionesPanel();
                
                // 5. Cargar Opciones Dinámicas en modo SOLO LECTURA
                opcionesContainer.innerHTML = ''; 
                if(data.tipo_pregunta === 'ESCALA' || data.tipo_pregunta === 'OPCION') {
                    
                    data.opciones.forEach(opcion => {
                        addRowOpcionReadOnly(opcion.texto_opcion, opcion.valor_numerico);
                    });
                }

                // 6. Deshabilitar todos los campos del formulario y ocultar botones de acción
                setModalReadOnly(true);
                
                // 7. Cambiar el título del modal
                document.querySelector('#titleModal').innerHTML ="Detalles de la Pregunta";
                $('#modalFormPregunta').modal('show');

            }else{
                swal("Error", objData.msg , "error");
            }
        }
        divLoading.style.display = "none";
    }
}


/**
 * Función para Eliminar Pregunta (soft delete)
 */
function fntDelPregunta(id){
    swal({
        title: "Eliminar Pregunta",
        text: "¿Realmente quiere eliminar la Pregunta? (Se inactivará)",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "No, cancelar",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {

        if (isConfirm) {
            divLoading.style.display = "flex";
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Preguntas/delPregunta/';
            let strData = "id="+id;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {
                        swal("Eliminar!", objData.msg , "success");
                        tablePreguntas.api().ajax.reload(null,false);
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
                divLoading.style.display = "none";
            }
        }

    });
}