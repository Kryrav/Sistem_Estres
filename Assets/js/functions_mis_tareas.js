// Assets/js/functions_mis_tareas.js
var tableMisTareas;
var divLoading = document.querySelector("#divLoading");

document.addEventListener('DOMContentLoaded', function(){
    cargarMisTareas();
});

function cargarMisTareas(){
    if($.fn.DataTable.isDataTable('#tableMisTareas')){
        tableMisTareas.destroy();
    }
    
    tableMisTareas = $('#tableMisTareas').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": base_url + "/Tareas/listarMisTareas",
            "dataSrc": ""
        },
        "columns": [
            {"data": "titulo"},
            {"data": "tipo"},
            {"data": "minutos_estimados"},
            {"data": "minutos_reales_invertidos"},
            {"data": "fecha_vencimiento_formateada"},
            {"data": "estado_badge"},
            {"data": "options"}
        ],
        "responsive": true,
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "asc"]]
    });
}

function fntUpdateEstado(idTarea){
    document.getElementById('idTarea').value = idTarea;
    document.getElementById('minutos_reales').value = '';
    document.getElementById('motivo_bloqueo').value = '';
    document.getElementById('estado').value = '';
    
    $('#modalEstadoTarea').modal('show');
}

function fntGuardarEstado(){
    // DEPURACIÓN: Ver qué valores tienen los campos
    var strEstado = document.querySelector('#estado').value;
    var minutosReales = document.querySelector('#minutos_reales').value;
    var motivoBloqueo = document.querySelector('#motivo_bloqueo').value;
    var idTarea = document.querySelector('#idTarea').value;
    
    console.log('🔍 DEPURACIÓN - Valores del formulario:');
    console.log('ID Tarea:', idTarea);
    console.log('Estado:', strEstado);
    console.log('Minutos Reales:', minutosReales);
    console.log('Motivo Bloqueo:', motivoBloqueo);
    
    if(strEstado == ''){
        swal("Atención", "Selecciona un estado.", "error");
        return false;
    }
    
    // Mostrar loading
    if(divLoading) divLoading.style.display = "flex";
    
    var formData = new FormData(document.querySelector('#formEstadoTarea'));
    
    // DEPURACIÓN: Ver qué se está enviando
    console.log('📤 Datos a enviar:');
    for (var pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
    
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Tareas/actualizarEstado';
    
    request.open("POST", ajaxUrl, true);
    request.send(formData);
    
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            // Ocultar loading
            if(divLoading) divLoading.style.display = "none";
            
            console.log('📥 Respuesta del servidor:', request.responseText);
            
            var objData = JSON.parse(request.responseText);
            if(objData.status){
                $('#modalEstadoTarea').modal('hide');
                swal("Estado actualizado", objData.msg, "success");
                cargarMisTareas();
            } else {
                swal("Error", objData.msg, "error");
            }
        }
    }
}

// Función para recargar la tabla
function recargarTabla(){
    if(tableMisTareas){
        tableMisTareas.ajax.reload();
    }
}