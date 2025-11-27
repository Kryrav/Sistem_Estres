let tableCategorias;
let rowTable = "";
let divLoading = document.querySelector("#divLoading");

document.addEventListener('DOMContentLoaded', function(){

    // Inicialización de DataTables
    tableCategorias = $('#tableCategorias').dataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax":{
            "url": " "+base_url+"/Categorias/getCategorias",
            "dataSrc":""
        },
        "columns":[
            {"data":"id"},
            {"data":"nombre"},
            {"data":"descripcion"},
            {"data":"activo"},
            {"data":"options"}
        ],
        "responsive":true,
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[0,"desc"]]  
    });

    // --- Manejo del Formulario (Insertar/Actualizar) ---
    let formCategoria = document.querySelector("#formCategoria");
    formCategoria.onsubmit = function(e) {
        e.preventDefault();

        // Obtener valores del formulario
        let id = document.querySelector('#id').value;
        let nombre = document.querySelector('#txtNombre').value;
        let descripcion = document.querySelector('#txtDescripcion').value;
        let activo = document.querySelector('#listActivo').value;
        
        // Validaciones mínimas
        if(nombre == '') {
            swal("Atención", "El nombre de la categoría es obligatorio.", "error");
            return false;
        }

        divLoading.style.display = "flex";
        let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        let ajaxUrl = base_url+'/Categorias/setCategoria'; 
        let formData = new FormData(formCategoria);
        request.open("POST",ajaxUrl,true);
        request.send(formData);

        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                
                let objData = JSON.parse(request.responseText);
                if(objData.status)
                {
                    // Éxito: Cerrar modal, mostrar mensaje y recargar tabla
                    $('#modalFormCategoria').modal("hide");
                    formCategoria.reset();
                    swal("Categorías", objData.msg, "success");
                    // Recargar DataTables (actualiza la fila si es edición)
                    tableCategorias.api().ajax.reload(null,false);
                }else{
                    swal("Error", objData.msg , "error");
                }
            }
            divLoading.style.display = "none";
            return false;
        }
    }

}, false);


// --- Función para Abrir Modal (Nueva Categoría) ---
function openModal()
{
    // Limpiar campos y configurar para inserción
    document.querySelector('#id').value = "";
    document.querySelector('#titleModal').innerHTML = "Nueva Categoría de Indicador";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector("#formCategoria").reset();
    
    // Asegurar que el selectpicker se muestre correctamente si lo usas
    $('#listActivo').selectpicker('refresh');

    $('#modalFormCategoria').modal('show');
}


// --- Función para Editar Categoría ---
function fntEditCategoria(element, id){
    rowTable = element.parentNode.parentNode.parentNode;
    document.querySelector('#titleModal').innerHTML ="Actualizar Categoría de Indicador";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";

    divLoading.style.display = "flex";
    let request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    let ajaxUrl = base_url+'/Categorias/getCategoria/'+id;
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            let objData = JSON.parse(request.responseText);

            if(objData.status)
            {
                // Llenar campos
                document.querySelector('#id').value = objData.data.id;
                document.querySelector('#txtNombre').value = objData.data.nombre;
                document.querySelector('#txtDescripcion').value = objData.data.descripcion;
                document.querySelector('#listActivo').value = objData.data.activo;
                
                // Refrescar el selectpicker para que tome el nuevo valor
                $('#listActivo').selectpicker('render'); 

                $('#modalFormCategoria').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
        divLoading.style.display = "none";
    }
}


// --- Función para Eliminar Categoría ---
function fntDelCategoria(id){
    swal({
        title: "Eliminar Categoría",
        text: "¿Realmente quiere eliminar la Categoría?",
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
            let ajaxUrl = base_url+'/Categorias/delCategoria/';
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
                        tableCategorias.api().ajax.reload(null,false);
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
                divLoading.style.display = "none";
            }
        }

    });
}