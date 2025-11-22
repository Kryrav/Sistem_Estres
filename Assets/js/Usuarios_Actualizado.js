// Variable global para la tabla de DataTables. ¡RENOMBRAR! (Ej: tableDepartamentos)
let tableUsuarios; 
let rowTable = "";
let divLoading = document.querySelector("#divLoading");

document.addEventListener('DOMContentLoaded', function(){

    // 1. INICIALIZACIÓN DE DATATABLES
    tableUsuarios = $('#tableUsuarios').dataTable( { // ¡RENOMBRAR EL ID DE LA TABLA!
        "aProcessing":true,
        "aServerSide":true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax":{
            // ¡MODIFICAR URL!
            "url": " "+base_url+"/Usuarios/getUsuarios", 
            "dataSrc":""
        },
        "columns":[
            {"data":"idpersona"}, // ¡MODIFICAR COLUMNAS!
            {"data":"nombres"},
            {"data":"apellidos"},
            {"data":"email_user"},
            {"data":"telefono"},
            {"data":"nombrerol"},
            {"data":"status"},
            {"data":"options"}
        ],
        'dom': 'lBfrtip',
        'buttons': [ 
            // Botones de exportación (Mantener)
            { "extend": "copyHtml5", "text": "<i class='far fa-copy'></i> Copiar", "titleAttr":"Copiar", "className": "btn btn-secondary" },
            { "extend": "excelHtml5", "text": "<i class='fas fa-file-excel'></i> Excel", "titleAttr":"Esportar a Excel", "className": "btn btn-success" },
            { "extend": "pdfHtml5", "text": "<i class='fas fa-file-pdf'></i> PDF", "titleAttr":"Esportar a PDF", "className": "btn btn-danger" },
            { "extend": "csvHtml5", "text": "<i class='fas fa-file-csv'></i> CSV", "titleAttr":"Esportar a CSV", "className": "btn btn-info" }
        ],
        "responsive":true, // CORRECCIÓN: Estaba 'resonsieve'
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[0,"desc"]]  
    });

    // 2. FUNCIÓN DE GUARDAR/ACTUALIZAR REGISTRO
    if(document.querySelector("#formUsuario")){ // ¡MODIFICAR ID DE FORMULARIO!
        let formUsuario = document.querySelector("#formUsuario");
        formUsuario.onsubmit = function(e) {
            e.preventDefault();
            
            // Recolección y Validación de datos del formulario.
            // ¡AJUSTAR LAS VARIABLES Y VALIDACIONES PARA EL NUEVO MÓDULO!
            let strIdentificacion = document.querySelector('#txtIdentificacion').value;
            let strNombre = document.querySelector('#txtNombre').value;
            // ... otras variables ...

            if(strIdentificacion == '' || strNombre == '') // Ajustar campos obligatorios
            {
                swal("Atención", "Todos los campos son obligatorios." , "error");
                return false;
            }

            // ... (Lógica de validación de campos inválidos 'is-invalid' - Mantener) ...
            
            divLoading.style.display = "flex";
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Usuarios/setUsuario'; // ¡MODIFICAR URL! (Ej: /Departamentos/setDepartamento)
            let formData = new FormData(formUsuario);
            request.open("POST",ajaxUrl,true);
            request.send(formData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    let objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {
                        if(rowTable == ""){
                            tableUsuarios.api().ajax.reload();
                        }else{
                            // Lógica de actualización de fila (Mantener, pero ajustar las celdas)
                            // ...
                            rowTable="";
                        }
                        $('#modalFormUsuario').modal("hide"); // ¡MODIFICAR ID DE MODAL!
                        formUsuario.reset();
                        swal("Éxito", objData.msg ,"success"); // ¡MODIFICAR TÍTULO DE SWAL!
                    }else{
                        swal("Error", objData.msg , "error");
                    }
                }
                divLoading.style.display = "none";
                return false;
            }
        }
    }
    // ... (Mantener las funciones de Perfil y Datos Fiscales si aplican a todos los usuarios)
    
}, false); // Fin de DOMContentLoaded

// Función para cargar opciones en select (ej. Roles).
// ¡MODIFICAR PARA CARGAR OTROS SELECTS (ej. Departamentos)
function fntRolesUsuario(){
    if(document.querySelector('#listRolid')){
        let ajaxUrl = base_url+'/Roles/getSelectRoles'; // ¡MODIFICAR URL!
        // ...
    }
}

// Función para VER registro
function fntViewUsuario(idpersona){ // ¡RENOMBRAR FUNCIÓN! (Ej: fntViewDepartamento)
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Usuarios/getUsuario/'+idpersona; // ¡MODIFICAR URL!
    request.open("GET",ajaxUrl,true);
    request.send();
    // ... Lógica de respuesta AJAX, ¡MODIFICAR SELECTORES (#cel...)!
}

// Función para EDITAR registro
function fntEditUsuario(element,idpersona){ // ¡RENOMBRAR FUNCIÓN! (Ej: fntEditDepartamento)
    rowTable = element.parentNode.parentNode.parentNode; 
    document.querySelector('#titleModal').innerHTML ="Actualizar Registro"; // ¡MODIFICAR TÍTULO!
    // ... (Cambio de clases de modal y botón - Mantener)

    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Usuarios/getUsuario/'+idpersona; // ¡MODIFICAR URL!
    request.open("GET",ajaxUrl,true);
    request.send();
    // ... Lógica de respuesta AJAX, ¡MODIFICAR IDs DE INPUTS (#txt...) y SELECTS!
}

// Función para ELIMINAR registro
function fntDelUsuario(idpersona){ // ¡RENOMBRAR FUNCIÓN! (Ej: fntDelDepartamento)
    swal({
        title: "Eliminar Registro", // ¡MODIFICAR TÍTULO!
        text: "¿Realmente quiere eliminar el registro?", // ¡MODIFICAR TEXTO!
        // ... (Sweetalert config - Mantener)
    }, function(isConfirm) {
        if (isConfirm) 
        {
            let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            let ajaxUrl = base_url+'/Usuarios/delUsuario'; // ¡MODIFICAR URL!
            let strData = "idUsuario="+idpersona; // ¡MODIFICAR PARÁMETRO DE DATA!
            // ... (Lógica de eliminación - Mantener)
        }
    });
}

// Función para abrir el modal de CREACIÓN
function openModal()
{
    document.querySelector('#idUsuario').value =""; // ¡MODIFICAR ID DE INPUT OCULTO!
    // ... (Cambio de clases de modal y botón - Mantener)
    document.querySelector('#titleModal').innerHTML = "Nuevo Registro"; // ¡MODIFICAR TÍTULO!
    document.querySelector("#formUsuario").reset(); // ¡MODIFICAR ID DE FORMULARIO!
    $('#modalFormUsuario').modal('show'); // ¡MODIFICAR ID DE MODAL!
}