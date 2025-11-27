var tableIntervenciones;
var divLoading = document.querySelector("#divLoading");

document.addEventListener('DOMContentLoaded', function(){
    // Cargar estadísticas del dashboard
    loadDashboardStats();

    // Configurar DataTable para intervenciones
    tableIntervenciones = $('#tableIntervenciones').dataTable( {
        "aProcessing":true,
        "aServerSide":true,
        "language": {
            "url": "<?= base_url() ?>/Assets/plugins/datatables/Spanish.json"
        },
        "ajax":{
            "url": " "+base_url+"/Intervenciones/getIntervenciones",
            "dataSrc":""
        },
        "columns":[
            {"data":"id"},
            {"data":"trabajador_nombre"},
            {"data":"tipo_alerta_formatted"},
            {"data":"mensaje"},
            {"data":"estado_formatted"},
            {"data":"fecha_formatted"},
            {"data":"options"}
        ],
        "responsive":true,
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[0,"desc"]],
        "columnDefs": [
            {
                "targets": [3], // Columna mensaje
                "render": function(data, type, row) {
                    if (type === 'display' && data.length > 100) {
                        return data.substr(0, 100) + '...';
                    }
                    return data;
                }
            }
        ]
    });

    // Formulario de intervenciones
    var formIntervencion = document.querySelector("#formIntervencion");
    formIntervencion.onsubmit = function(e) {
        e.preventDefault();

        var intIdIntervencion = document.querySelector('#idIntervencion').value;
        var intTrabajador = document.querySelector('#listTrabajador').value;
        var strTipoAlerta = document.querySelector('#listTipoAlerta').value;
        var strMensaje = document.querySelector('#txtMensaje').value;
        var strEstado = document.querySelector('#listEstado').value;
        
        if(intTrabajador == '' || strTipoAlerta == '' || strMensaje == '' || strEstado == '')
        {
            swal("Atención", "Todos los campos son obligatorios." , "error");
            return false;
        }

        divLoading.style.display = "flex";
        var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        var ajaxUrl = base_url+'/Intervenciones/setIntervencion'; 
        var formData = new FormData(formIntervencion);
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        
        request.onreadystatechange = function(){
           if(request.readyState == 4 && request.status == 200){
                var objData = JSON.parse(request.responseText);
                if(objData.status)
                {
                    $('#modalFormIntervencion').modal("hide");
                    formIntervencion.reset();
                    swal("Intervenciones", objData.msg ,"success");
                    tableIntervenciones.api().ajax.reload();
                    loadDashboardStats(); // Actualizar estadísticas
                }else{
                    swal("Error", objData.msg , "error");
                }              
            } 
            divLoading.style.display = "none";
            return false;
        }
    }
});

// Cargar estadísticas del dashboard
function loadDashboardStats() {
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Intervenciones/getEstadisticas';
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            
            var totalPendientes = 0;
            var totalAplicadas = 0;
            var totalBurnout = 0;
            var totalRedistribuciones = 0;

            objData.forEach(function(item) {
                if(item.estado === 'pendiente') totalPendientes += item.total;
                if(item.estado === 'aplicada') totalAplicadas += item.total;
                if(item.tipo_alerta === 'alerta_burnout') totalBurnout += item.total;
                if(item.tipo_alerta === 'redistribucion_carga') totalRedistribuciones += item.total;
            });

            // Actualizar UI
            document.getElementById('totalPendientes').textContent = totalPendientes;
            document.getElementById('totalAplicadas').textContent = totalAplicadas;
            document.getElementById('totalBurnout').textContent = totalBurnout;
            document.getElementById('totalRedistribuciones').textContent = totalRedistribuciones;
        }
    }
}

// En la función fntEditIntervencion, cambia esto:
function fntEditIntervencion(idintervencion){
    document.querySelector('#titleModal').innerHTML = "Actualizar Estado de Intervención";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";

    var idintervencion = idintervencion;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl  = base_url+'/Intervenciones/getIntervencion/'+idintervencion;
    request.open("GET",ajaxUrl ,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status)
            {
                document.querySelector("#idIntervencion").value = objData.data.id;
                document.querySelector("#listTrabajador").value = objData.data.trabajador_id;
                document.querySelector("#listTipoAlerta").value = objData.data.tipo_alerta;
                document.querySelector("#txtMensaje").value = objData.data.mensaje;
                document.querySelector("#listEstado").value = objData.data.estado;
                
                // Deshabilitar campos excepto estado para edición
                document.querySelector('#listTrabajador').disabled = true;
                document.querySelector('#listTipoAlerta').disabled = true;
                document.querySelector('#txtMensaje').disabled = true;
                document.querySelector('#listEstado').disabled = false;
                
                $('#modalFormIntervencion').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}

// Y en openModal, asegúrate de habilitar todos los campos:
function openModal(){
    document.querySelector('#idIntervencion').value = "";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nueva Intervención";
    document.querySelector("#formIntervencion").reset();
    
    // Asegurar que todos los campos estén habilitados para nueva intervención
    document.querySelector('#listTrabajador').disabled = false;
    document.querySelector('#listTipoAlerta').disabled = false;
    document.querySelector('#txtMensaje').disabled = false;
    document.querySelector('#listEstado').disabled = false;
    
    $('#modalFormIntervencion').modal('show');
}

function fntDelIntervencion(idintervencion){
    var idintervencion = idintervencion;
    swal({
        title: "Eliminar Intervención",
        text: "¿Realmente quiere eliminar esta intervención?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar!",
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        
        if (isConfirm) 
        {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url+'/Intervenciones/delIntervencion/';
            var strData = "idintervencion="+idintervencion;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    var objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {
                        swal("Eliminar!", objData.msg , "success");
                        tableIntervenciones.api().ajax.reload();
                        loadDashboardStats();
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }
    });
}

// Función para ver intervenciones por trabajador
function fntViewIntervencionesTrabajador(idTrabajador){
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Intervenciones/getIntervencionesTrabajador/'+idTrabajador;
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var intervenciones = JSON.parse(request.responseText);
            var htmlContent = '<div class="list-group">';
            
            intervenciones.forEach(function(interv) {
                var badgeClass = '';
                switch(interv.estado){
                    case 'pendiente': badgeClass = 'warning'; break;
                    case 'leida': badgeClass = 'primary'; break;
                    case 'aplicada': badgeClass = 'success'; break;
                    case 'ignorada': badgeClass = 'secondary'; break;
                }
                
                htmlContent += `
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">${interv.tipo_alerta}</h6>
                            <small><span class="badge badge-${badgeClass}">${interv.estado}</span></small>
                        </div>
                        <p class="mb-1">${interv.mensaje}</p>
                        <small>${new Date(interv.fecha_generada).toLocaleDateString()}</small>
                    </div>
                `;
            });
            
            htmlContent += '</div>';
            
            swal({
                title: "Intervenciones del Trabajador",
                html: true,
                text: htmlContent,
                width: 600
            });
        }
    }
}