// Ubicación: Assets/js/functions_trabajador.js

let tableTrabajadores;
let rowTable = null; 
let divLoading = document.querySelector("#divLoading");

document.addEventListener('DOMContentLoaded', function(){

    // Inicializar DataTables
    tableTrabajadores = $('#tableTrabajadores').dataTable( {
        "aProcessing":true,
        "aServerSide":true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax":{
            "url": " "+base_url+"/Trabajador/getTrabajadores",
            "dataSrc":""
        },
        "columns":[
            {"data":"id"},
            {"data":"nombre_completo"},
            {"data":"departamento"},
            {"data":"cargo"},
            {"data":"supervisor_nombre"},
            {"data":"fecha_ingreso"},
            {"data":"activo"},
            {"data":"options"}
        ],
        // Configuración de Botones de Exportación
        'dom': 'lBfrtip',
        'buttons': [
            {
                "extend": "copyHtml5", "text": "<i class='far fa-copy'></i> Copiar", "titleAttr":"Copiar", "className": "btn btn-secondary"
            },{
                "extend": "excelHtml5", "text": "<i class='fas fa-file-excel'></i> Excel", "titleAttr":"Exportar a Excel", "className": "btn btn-success"
            },{
                "extend": "pdfHtml5", "text": "<i class='fas fa-file-pdf'></i> PDF", "titleAttr":"Exportar a PDF", "className": "btn btn-danger"
            },{
                "extend": "csvHtml5", "text": "<i class='fas fa-file-csv'></i> CSV", "titleAttr":"Exportar a CSV", "className": "btn btn-info"
            }
        ],
        "responsive": true,
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[0,"desc"]]  
    });

    // Manejar el envío del formulario (SET TRABAJADOR)
    let formTrabajador = document.querySelector("#formTrabajador");
    formTrabajador.onsubmit = function(e) {
        e.preventDefault();

        let id = document.querySelector('#id').value;
        let idPersona = document.querySelector('#listIdPersona').value;
        let idDepartamento = document.querySelector('#listDepartamento').value;
        let cargo = document.querySelector('#txtCargo').value;
        let horasDiarias = document.querySelector('#listHorasDiarias').value;
        let idSupervisor = document.querySelector('#listSupervisor').value;
        let activo = document.querySelector('#listActivo').value;

        // Validaciones...
        if((id == 0 && idPersona == '') || idDepartamento == '' || cargo == '' || activo == '' || horasDiarias == '')
        {
            swal("Atención", "Faltan campos obligatorios (*)." , "error");
            return false;
        }
        if(horasDiarias < 1 || horasDiarias > 16) {
            swal("Atención", "Las horas diarias deben ser un valor razonable." , "error");
            return false;
        }
        
        divLoading.style.display = "flex";
        
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'/Trabajador/setTrabajador'; 
        let formData = new FormData(formTrabajador);

        request.open("POST",ajaxUrl,true);
        request.send(formData);

        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                
                let objData = JSON.parse(request.responseText);
                if(objData.status)
                {
                    $('#modalFormTrabajador').modal("hide");
                    formTrabajador.reset();
                    swal("Trabajadores", objData.msg ,"success");
                    tableTrabajadores.api().ajax.reload();
                    rowTable = null; 
                }else{
                    swal("Error", objData.msg , "error");
                }
            }
            divLoading.style.display = "none";
            return false;
        }
    }
}, false); // Fin DOMContentLoaded

// --------------------------------------------------------------------------
// LÓGICA DE SELECTS Y MODALES
// --------------------------------------------------------------------------

// Llama al controlador para obtener los selects
function fntGetSelects()
{
    // Carga inicial solo si los elementos existen
    if(document.querySelector('#listIdPersona')) {
        let ajaxUrl = base_url+'/Trabajador/getSelects'; // <--- Llama al método del controlador
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        request.open("GET",ajaxUrl,true);
        request.send();
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                let objData = JSON.parse(request.responseText);
                
                $('#listIdPersona').selectpicker('destroy');
                $('#listDepartamento').selectpicker('destroy');
                $('#listSupervisor').selectpicker('destroy');

                // Llenar SELECT de Personas Disponibles
                let htmlPersonas = '';
                objData.personas_disponibles.forEach(persona => {
                    htmlPersonas += `<option value="${persona.idpersona}">${persona.nombre_completo} (${persona.nombrerol})</option>`;
                });
                document.querySelector('#listIdPersona').innerHTML = htmlPersonas;
                
                // Llenar SELECT de Departamentos
                let htmlDepartamentos = '';
                objData.departamentos.forEach(dep => {
                    htmlDepartamentos += `<option value="${dep.id}">${dep.nombre}</option>`;
                });
                document.querySelector('#listDepartamento').innerHTML = htmlDepartamentos;

                // Llenar SELECT de Supervisores
                let htmlSupervisores = '<option value="0">Sin Supervisor Asignado</option>';
                objData.supervisores.forEach(sup => {
                    htmlSupervisores += `<option value="${sup.idpersona}">${sup.nombre_completo}</option>`;
                });
                document.querySelector('#listSupervisor').innerHTML = htmlSupervisores;

                $('#listIdPersona').selectpicker(); 
                $('#listDepartamento').selectpicker();
                $('#listSupervisor').selectpicker();
            }
        }
    }
}


function openModal()
{
    document.querySelector('#id').value ="0";
    document.querySelector('#titleModal').innerHTML = "Asignar Nuevo Trabajador";
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    
    // Configuración para Inserción
    document.querySelector('#containerIdPersona').style.display = 'block';
    document.querySelector('#listIdPersona').setAttribute('required', 'required');
    document.querySelector("#formTrabajador").reset();
    rowTable = null;

    fntGetSelects(); // <--- Llamada a la función de carga de selects
    
    $('#modalFormTrabajador').modal('show');
}

// --------------------------------------------------------------------------
// LÓGICA DE ACCIONES (VER, EDITAR, ELIMINAR)
// --------------------------------------------------------------------------

// Visualizar detalles del trabajador
function fntViewTrabajador(idtrabajador){
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Trabajador/getTrabajador/'+idtrabajador;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            if(objData.status)
            {
                let estadoTrabajador = objData.data.activo == 1 ? '<span class="badge badge-success">Activo</span>' : '<span class="badge badge-danger">Inactivo</span>';
                let supervisor = objData.data.nombre_supervisor ? objData.data.nombre_supervisor : 'N/A';
                
                document.querySelector("#celIdTrabajador").innerHTML = objData.data.id;
                document.querySelector("#celIdentificacion").innerHTML = objData.data.identificacion;
                document.querySelector("#celNombreCompleto").innerHTML = `${objData.data.nombres} ${objData.data.apellidos}`;
                document.querySelector("#celRolSistema").innerHTML = objData.data.nombrerol;
                document.querySelector("#celEmail").innerHTML = objData.data.email_user;
                document.querySelector("#celTelefono").innerHTML = objData.data.telefono;
                document.querySelector("#celDepartamento").innerHTML = objData.data.nombre_departamento;
                document.querySelector("#celCargo").innerHTML = objData.data.cargo;
                document.querySelector("#celSupervisor").innerHTML = supervisor;
                document.querySelector("#celHorasDiarias").innerHTML = objData.data.horas_trabajo_diarias + ' h.';
                // La fecha ya viene formateada desde el modelo:
                document.querySelector("#celFechaIngreso").innerHTML = objData.data.fecha_ingreso; 
                document.querySelector("#celEstado").innerHTML = estadoTrabajador;
                
                $('#modalViewTrabajador').modal('show');
            } else {
                swal("Error", objData.msg , "error");
            }
        }
    }
}

// Función para edición (carga de datos)
function fntEditTrabajador(element, idtrabajador){
    rowTable = element.parentNode.parentNode.parentNode; 

    document.querySelector('#titleModal').innerHTML = "Actualizar Trabajador";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";
    
    // 1. Ocultar el select de Persona y quitar 'required'
    document.querySelector('#containerIdPersona').style.display = 'none';
    document.querySelector('#listIdPersona').removeAttribute('required');

    // 2. Llenar los SELECTS auxiliares primero 
    fntGetSelects(); 

    // 3. Obtener Datos del Trabajador
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Trabajador/getTrabajador/'+idtrabajador;
    request.open("GET",ajaxUrl,true);
    request.send();
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);
            if(objData.status)
            {
                // 4. Llenar los campos del modal
                document.querySelector("#id").value = objData.data.id;
                
                // Estos selects ya deben estar llenos por fntGetSelects()
                document.querySelector("#listDepartamento").value = objData.data.departamento_id;
                document.querySelector("#listSupervisor").value = objData.data.supervisor_id;
                document.querySelector("#listActivo").value = objData.data.activo;
                
                document.querySelector("#txtCargo").value = objData.data.cargo;
                document.querySelector("#listHorasDiarias").value = objData.data.horas_trabajo_diarias;

                // 5. Mostrar Modal y Actualizar Selects
                
                
                $('#listDepartamento').selectpicker('refresh');
                $('#listSupervisor').selectpicker('refresh');
                $('#modalFormTrabajador').modal('show');
            } else {
                swal("Error", objData.msg , "error");
            }
        }
    }
}

// Eliminar lógicamente un trabajador
function fntDelTrabajador(idtrabajador){
    swal({
        title: "Eliminar Trabajador",
        text: "¿Realmente quiere eliminar este registro de Trabajador?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar!",
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        
        if (isConfirm) 
        {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Trabajador/delTrabajador';
            let strData = "id="+idtrabajador; 
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {
                        swal("Eliminado!", objData.msg , "success");
                        tableTrabajadores.api().ajax.reload();
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }
    });
}