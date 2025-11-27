// Variables Globales
let tableEncuestas;
let divLoading = document.querySelector("#divLoading");
let formEncuesta = document.querySelector("#formEncuesta"); // Formulario de Datos Generales
let formAsignacion = document.querySelector("#formAsignacion"); // Nuevo Formulario de Asignación

// Variables SortableJS
let sortableAvailable;
let sortableAssigned;

document.addEventListener('DOMContentLoaded', function(){

    // 1. Inicialización de DataTables (Se mantiene igual)
    tableEncuestas = $('#tableEncuestas').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax":{
            "url": " "+base_url+"/Encuestas/getEncuestas",
            "dataSrc":""
        },
        "columns":[
            {"data":"id"},
            {"data":"titulo"},
            {"data":"descripcion"},
            {"data":"fecha_inicio"},
            {"data":"fecha_fin"},
            {"data":"estado"},
            {"data":"options"}
        ],
        "responsive":true,
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[0,"desc"]]  
    });

    // 2. Manejo del Formulario de Datos Generales (Insertar/Actualizar)
    formEncuesta.onsubmit = function(e) {
        e.preventDefault();

        let id = document.querySelector('#id').value;
        let titulo = document.querySelector('#txtTitulo').value;
        let fechaInicio = document.querySelector('#txtFechaInicio').value;
        let fechaFin = document.querySelector('#txtFechaFin').value;
        let estado = document.querySelector('#listEstado').value;

        if(titulo == '' || fechaInicio == '' || fechaFin == '' || estado == '') {
            swal("Atención", "Todos los campos marcados con (*) son obligatorios.", "error");
            return false;
        }
        
        divLoading.style.display = "flex";
        
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'/Encuestas/setEncuesta'; 
        let formData = new FormData(formEncuesta);
        
        request.open("POST",ajaxUrl,true);
        request.send(formData);

        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                
                let objData = JSON.parse(request.responseText);
                if(objData.status)
                {
                    // Si se creó o actualizó, actualizamos la tabla
                    swal("Encuestas", objData.msg, "success");
                    tableEncuestas.api().ajax.reload(null,false);
                    
                    if(id == "") {
                        // Si es nueva, reseteamos el formulario y forzamos la recarga de datos para edición
                        formEncuesta.reset();
                        $('#modalFormEncuesta').modal("hide"); 
                    } 
                    // Si es edición, se mantiene abierto y se pueden ir a la pestaña de preguntas
                }else{
                    swal("Error", objData.msg , "error");
                }
            }
            divLoading.style.display = "none";
        }
    }
    
    // 3. Manejo del Formulario de Asignación (Guardar Orden y Asignación)
    formAsignacion.onsubmit = function(e) {
        e.preventDefault();

        let idEncuesta = document.querySelector('#assignEncuestaId').value;
        if(idEncuesta === "") {
             swal("Atención", "Error: ID de encuesta no encontrado.", "error");
             return false;
        }

        // Obtener la lista de IDs de preguntas en el orden actual
        let assignedItems = document.querySelectorAll('#preguntasAsignadasList .draggable-item');
        let preguntasIds = Array.from(assignedItems).map(item => item.dataset.id);

        if(preguntasIds.length === 0) {
            swal("Atención", "Debe asignar al menos una pregunta a la encuesta.", "error");
            return false;
        }
        
        divLoading.style.display = "flex";
        
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'/Encuestas/setAsignacionPreguntas'; 
        let formData = new FormData();
        
        formData.append('assignEncuestaId', idEncuesta);
        formData.append('preguntas_ids', JSON.stringify(preguntasIds)); // Enviamos el array como JSON
        
        request.open("POST",ajaxUrl,true);
        request.send(formData);

        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                
                let objData = JSON.parse(request.responseText);
                if(objData.status)
                {
                    swal("Encuestas", objData.msg, "success");
                    // Recargar solo la lista de preguntas asignadas para actualizar el orden
                    fntLoadPreguntasAsignadas(idEncuesta); 
                }else{
                    swal("Error", objData.msg , "error");
                }
            }
            divLoading.style.display = "none";
        }
    }


    // 4. Inicialización de SortableJS
    const disponiblesList = document.getElementById('preguntasDisponiblesList');
    const asignadasList = document.getElementById('preguntasAsignadasList');

    // Instancia para Preguntas Disponibles (Origen)
    sortableAvailable = new Sortable(disponiblesList, {
        group: {
            name: 'preguntas',
            pull: 'clone', // Se clona el elemento para moverlo a la otra lista
            put: false // No se puede recibir elementos
        },
        sort: true, // Se pueden reordenar en la lista disponible si fuera necesario
        animation: 150,
        handle: '.handle-icon', // Icono de arrastre
    });

    // Instancia para Preguntas Asignadas (Destino y Ordenación)
    sortableAssigned = new Sortable(asignadasList, {
        group: {
            name: 'preguntas',
            pull: true, // Se pueden sacar de esta lista (volver al banco)
            put: true // Se pueden recibir elementos
        },
        sort: true, // PERMITIR REORDENACIÓN
        animation: 150,
        handle: '.handle-icon', // Icono de arrastre

        // Evento al añadir un elemento a la lista de Asignadas
        onAdd: function (evt) {
            // Asegurarse de que el elemento clonado tenga el data-id de la pregunta original
            let itemEl = evt.item;
            if (itemEl.dataset.id === undefined) {
                // Si el item no tiene data-id, buscar el original (solo sucede en ciertos navegadores/versiones)
                // En teoría, el pull: 'clone' maneja esto, pero es una precaución.
                // Mejor simplemente usar el data-id del elemento arrastrado.
            }
            
            // Opcional: Cambiar estilo al ser asignado
            itemEl.classList.remove('list-group-item-light');
            itemEl.classList.add('list-group-item-success');

            // Actualizar contador
            fntUpdateAssignedCount();
        },
        // Evento al quitar un elemento de la lista de Asignadas
        onRemove: function (evt) {
             // Opcional: Cambiar estilo al ser devuelto (si se reinserta en el disponibles)
             evt.item.classList.remove('list-group-item-success');
             evt.item.classList.add('list-group-item-light');

             // Actualizar contador
             fntUpdateAssignedCount();
        },
        // Evento al terminar la ordenación (para eliminar texto 'Arrastre preguntas aquí.')
        onUpdate: function (evt) {
            fntUpdateAssignedCount();
        },
    });

    // 5. Manejo de Eventos de la Pestaña de Asignación
    
    // Cargar categorías de filtro al cargar la página
    fntLoadCategoriasSelectFilter(); 

    // Evento para el filtro de Categoría
    document.querySelector('#selectCategoryFilter').addEventListener('change', function() {
        // Obtenemos el ID de la encuesta para mantener el contexto
        let idEncuesta = document.querySelector('#assignEncuestaId').value; 
        if(idEncuesta) {
            fntLoadPreguntas(idEncuesta);
        }
    });

    // Evento para el campo de búsqueda
    document.querySelector('#inputSearchPreguntas').addEventListener('keyup', function() {
        let idEncuesta = document.querySelector('#assignEncuestaId').value;
        if(idEncuesta) {
            fntLoadPreguntas(idEncuesta);
        }
    });


    // Asegurar que selectpicker se inicialice
    $('.selectpicker').selectpicker();

}, false);


// --- FUNCIONES AUXILIARES DE LA PESTAÑA DE ASIGNACIÓN ---

/**
 * Carga las categorías activas para el filtro de preguntas disponibles.
 */
function fntLoadCategoriasSelectFilter() {
    let selectFilter = document.querySelector('#selectCategoryFilter');
    let ajaxUrl = base_url+'/Categorias/getSelectCategorias';
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            // Añadimos la opción de "Todas las categorías" antes de las opciones
            selectFilter.innerHTML = '<option value="0">-- Todas las Categorías --</option>' + request.responseText;
            $(selectFilter).selectpicker('refresh');
        }
    }
}

/**
 * Carga las preguntas disponibles y las ya asignadas para una encuesta.
 * @param {int} idEncuesta ID de la encuesta.
 */
function fntLoadPreguntas(idEncuesta) {
    if(idEncuesta <= 0) return;

    // 1. Cargar preguntas asignadas (para saber cuáles excluir)
    fntLoadPreguntasAsignadas(idEncuesta, function(assignedIds) {
        // 2. Cargar preguntas disponibles (excluyendo las asignadas)
        fntLoadPreguntasDisponibles(assignedIds);
    });
}

/**
 * Carga las preguntas ya asignadas a la encuesta (columna derecha).
 * @param {int} idEncuesta ID de la encuesta.
 * @param {function} callback Función a ejecutar después de la carga. Recibe un array de IDs asignados.
 */
function fntLoadPreguntasAsignadas(idEncuesta, callback = null) {
    divLoading.style.display = "flex";
    const asignadasList = document.querySelector("#preguntasAsignadasList");
    asignadasList.innerHTML = '<div class="list-group-item text-center p-3 text-muted">Cargando asignadas...</div>';
    
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Encuestas/getPreguntasAsignadas/'+idEncuesta;
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);
            if(objData.status) {
                asignadasList.innerHTML = objData.data;
                fntUpdateAssignedCount();

                // Recolectar IDs de preguntas asignadas
                let assignedItems = document.querySelectorAll('#preguntasAsignadasList .draggable-item');
                let assignedIds = Array.from(assignedItems).map(item => parseInt(item.dataset.id));

                if (callback) {
                    callback(assignedIds);
                }
            } else {
                asignadasList.innerHTML = '<div class="list-group-item text-center p-3 text-muted">Arrastre preguntas aquí.</div>';
                fntUpdateAssignedCount();
                if (callback) {
                    callback([]);
                }
            }
        }
        divLoading.style.display = "none";
    }
}

/**
 * Carga las preguntas disponibles para arrastrar (columna izquierda).
 * @param {array} assignedIds Array de IDs de preguntas ya asignadas que deben ser excluidas.
 */
function fntLoadPreguntasDisponibles(assignedIds = []) {
    divLoading.style.display = "flex";
    const disponiblesList = document.querySelector("#preguntasDisponiblesList");
    disponiblesList.innerHTML = '<div class="list-group-item text-center p-3 text-muted">Cargando disponibles...</div>';
    
    let categoriaId = document.querySelector('#selectCategoryFilter').value;
    let search = document.querySelector('#inputSearchPreguntas').value;

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Encuestas/getPreguntasDisponibles';
    let formData = new FormData();
    formData.append('categoriaId', categoriaId);
    formData.append('search', search);
    formData.append('assignedIds', JSON.stringify(assignedIds)); // Opcional: enviar IDs a excluir

    request.open("POST",ajaxUrl,true);
    request.send(formData);

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            disponiblesList.innerHTML = request.responseText;
        }
        divLoading.style.display = "none";
    }
}


/**
 * Actualiza el contador de preguntas asignadas.
 */
function fntUpdateAssignedCount() {
    let count = document.querySelectorAll('#preguntasAsignadasList .draggable-item').length;
    document.querySelector('#countAsignadas').textContent = count;
    // Si la lista está vacía, mostrar el mensaje de ayuda
    const asignadasList = document.querySelector("#preguntasAsignadasList");
    if(count === 0 && asignadasList.innerHTML.indexOf('Arrastre preguntas aquí.') === -1) {
         asignadasList.innerHTML = '<div class="list-group-item text-center p-3 text-muted">Arrastre preguntas aquí.</div>';
    } else if (count > 0) {
        // Remover el mensaje de ayuda si hay elementos
        const helper = asignadasList.querySelector('div.text-muted');
        if(helper) helper.remove();
    }
}


// --- FUNCIONES CRUD MODIFICADAS ---

/**
 * Abre el modal para Nueva Encuesta (Solo permite Datos Generales).
 */
function openModal()
{
    // Limpieza de Datos Generales
    document.querySelector('#id').value = "";
    document.querySelector('#titleModal').innerHTML = "Nueva Encuesta";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar Encuesta";
    formEncuesta.reset();
    
    // OCULTAR PESTAÑA DE PREGUNTAS
    document.querySelector('#tabPreguntasLink').style.display = 'none';
    
    // Mostrar solo la pestaña de Datos Generales
    $('#datos-tab').tab('show'); 

    // Habilitar campos y botones para la creación
    document.querySelector('#btnActionForm').style.display = 'inline-block';
    setModalFormReadOnly(false);

    // Seleccionar estado por defecto 'BORRADOR'
    $('#listEstado').val('BORRADOR').selectpicker('refresh'); 

    $('#modalFormEncuesta').modal('show');
}


/**
 * Función para Editar Encuesta (Permite Datos Generales y Asignación).
 * Se mantiene la lógica de carga de datos y se añade la carga de preguntas.
 */
function fntEditEncuesta(element, id){
    // Configuración inicial del modal
    document.querySelector('#titleModal').innerHTML ="Actualizar Encuesta";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";
    
    // MOSTRAR PESTAÑA DE PREGUNTAS
    document.querySelector('#tabPreguntasLink').style.display = 'block';
    // Volver a la pestaña de Datos Generales por defecto al abrir
    $('#datos-tab').tab('show'); 

    // Habilitar campos y mostrar botón de acción
    setModalFormReadOnly(false); 
    document.querySelector('#btnActionForm').style.display = 'inline-block';


    divLoading.style.display = "flex";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Encuestas/getEncuesta/'+id;
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            if(objData.status)
            {
                let data = objData.data;
                
                // 1. Llenar campos de Datos Generales
                document.querySelector('#id').value = data.id;
                document.querySelector('#txtTitulo').value = data.titulo;
                document.querySelector('#txtDescripcion').value = data.descripcion;
                document.querySelector('#txtFechaInicio').value = data.fecha_inicio; 
                document.querySelector('#txtFechaFin').value = data.fecha_fin; 
                $('#listEstado').val(data.estado).selectpicker('refresh');
                
                // 2. Llenar campo del ID en la pestaña de asignación
                document.querySelector('#assignEncuestaId').value = data.id; 

                // 3. Cargar las preguntas para la pestaña de asignación (el banco y las asignadas)
                fntLoadPreguntas(data.id);
                
                $('#modalFormEncuesta').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
        divLoading.style.display = "none";
    }
}

// Las funciones fntViewEncuesta, fntDelEncuesta y setModalFormReadOnly se mantienen iguales.
// ... (código anterior de estas funciones)

/**
 * Función para Ver los Detalles de una Encuesta (Solo lectura) - Se mantiene igual
 */
function fntViewEncuesta(id) {
    divLoading.style.display = "flex";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Encuestas/getEncuesta/'+id;
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            if(objData.status)
            {
                let data = objData.data;
                
                // Llenar los campos de datos generales (Correcto)
                document.querySelector('#cellTitulo').innerHTML = data.titulo;
                document.querySelector('#cellDescripcion').innerHTML = data.descripcion;
                document.querySelector('#cellFechaInicio').innerHTML = data.fecha_inicio;
                document.querySelector('#cellFechaFin').innerHTML = data.fecha_fin;
                document.querySelector('#cellEstado').innerHTML = data.estado;
                
                // Cargar la lista de preguntas
                let preguntasHTML = '';
                if(data.preguntas_asignadas && data.preguntas_asignadas.length > 0) {
                    data.preguntas_asignadas.forEach(pregunta => {
                        preguntasHTML += `
                            <tr>
                                <td>${pregunta.orden}</td>
                                <td>${pregunta.texto_pregunta}</td>
                                <td>${pregunta.tipo_pregunta}</td>
                                <td>${pregunta.categoria}</td>
                            </tr>
                        `;
                    });
                } else {
                    preguntasHTML = '<tr><td colspan="4" class="text-center">No hay preguntas asociadas a esta encuesta.</td></tr>';
                }
                document.querySelector('#preguntasContainerView').innerHTML = preguntasHTML;
                
                $('#modalViewEncuesta').modal('show'); 
            }else{
                swal("Error", objData.msg , "error");
            }
        }
        divLoading.style.display = "none";
    }
}


/**
 * Función para Eliminar Encuesta (físico, con chequeo de dependencias) - Se mantiene igual
 */



/**
 * Función auxiliar para deshabilitar/habilitar todos los campos del modal de formulario. - Se mantiene igual
 */
function setModalFormReadOnly(isReadOnly) {
    let formElements = formEncuesta.querySelectorAll('input, select, textarea');
    formElements.forEach(element => {
        if (!element.classList.contains('close')) {
            element.disabled = isReadOnly;
        }
    });
}

function fntDelEncuesta(id){
    swal({
        title: "Eliminar Encuesta",
        text: "¿Realmente quiere eliminar la Encuesta? Esta acción es permanente y sólo es posible si no tiene respuestas asociadas (tabla 'encuesta_respondida').",
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
            let ajaxUrl = base_url+'/Encuestas/delEncuesta/';
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
                        tableEncuestas.api().ajax.reload(null,false);
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
                divLoading.style.display = "none";
            }
        }
    });
}


/**
 * Función auxiliar para deshabilitar/habilitar todos los campos del modal de formulario.
 * Se usa para limpiar el estado del modal entre operaciones.
 * @param {boolean} isReadOnly Si es true, deshabilita.
 */
function setModalFormReadOnly(isReadOnly) {
    let formElements = formEncuesta.querySelectorAll('input, select, textarea');
    formElements.forEach(element => {
        // Excluir el botón de cerrar
        if (!element.classList.contains('close')) {
            element.disabled = isReadOnly;
        }
    });
}

function fntDelPreguntaEncuesta(idAsignacion) {
    if (!idAsignacion || isNaN(idAsignacion)) {
        swal("Error", "ID de asignación no válido. Recargue la página y vuelva a intentar.", "error");
        return; // Detiene la ejecución si el ID es inválido
    }
    swal({
        title: "Eliminar Pregunta",
        text: "¿Realmente quiere desasignar esta pregunta de la encuesta? Esta acción es irreversible.",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar!",
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            divLoading.style.display = "flex";
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            // La URL apunta a un nuevo método en tu controlador
            let ajaxUrl = base_url + '/Encuestas/delPreguntaEncuesta/' + idAsignacion; 
            
            // Usamos POST o DELETE para la eliminación, pero GET con parámetros es común en AJAX simple
            request.open("POST", ajaxUrl, true);
            request.send();

            request.onreadystatechange = function() {
                if (request.readyState == 4 && request.status == 200) {
                    let objData = JSON.parse(request.responseText);
                    if (objData.status) {
                        swal("Eliminada!", objData.msg, "success");
                        
                        // Recargar la lista de preguntas para actualizar la vista
                        // Si estás en un modal de gestión, recarga la función que lo abre.
                        // Ejemplo: Si la encuesta tenía ID 50, llama a fntGestionPreguntas(50)
                        // Para este ejemplo, deberías obtener el ID de la encuesta, pero lo haremos simple por ahora:
                        window.location.reload(); 
                        
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                    divLoading.style.display = "none";
                }
            }
        }
    });
}

