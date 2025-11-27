var tableDepartamentos;
var divLoading = document.querySelector("#divLoading");

document.addEventListener('DOMContentLoaded', function(){

    // Inicialización del DataTables
    tableDepartamentos = $('#tableDepartamentos').dataTable( {
        "aProcessing":true,
        "aServerSide":true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax":{
            "url": base_url+"/Departamentos/getDepartamentos",
            "dataSrc":""
        },
        "columns":[
            {"data":"iddepartamento"},
            {"data":"nombre"},
            {"data":"descripcion"},
            {"data":"umbral_alerta_stress"},
            {"data":"status"}, 
            {"data":"options"}
        ],
        "responsive":true,
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[0,"desc"]] 
    });

    // NUEVO/ACTUALIZAR DEPARTAMENTO
    var formDepartamento = document.querySelector("#formDepartamento");
    formDepartamento.onsubmit = function(e) {
        e.preventDefault();

        var strNombre = document.querySelector('#txtNombre').value;
        var strDescripcion = document.querySelector('#txtDescripcion').value;
        var intUmbral = document.querySelector('#intUmbralAlerta').value; 
        
        if(strNombre == '' || strDescripcion == '' || intUmbral == '')
        {
            swal("Atención", "Todos los campos son obligatorios." , "error");
            return false;
        }

        divLoading.style.display = "flex";
        var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        var ajaxUrl = base_url+'/Departamentos/setDepartamento'; 
        var formData = new FormData(formDepartamento);
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        
        request.onreadystatechange = function(){
           if(request.readyState == 4 && request.status == 200){
                
                var objData = JSON.parse(request.responseText);
                if(objData.status)
                {
                    $('#modalFormDepartamento').modal("hide");
                    formDepartamento.reset();
                    swal("Departamentos", objData.msg ,"success");
                    tableDepartamentos.api().ajax.reload();
                }else{
                    swal("Error", objData.msg , "error");
                }        
            } 
            divLoading.style.display = "none";
            return false;
        }
    }

}); // Fin de DOMContentLoaded

// Función para abrir el Modal (Crear)
function openModal(){
    document.querySelector('#idDepartamento').value ="";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML ="Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Departamento";
    document.querySelector("#formDepartamento").reset();
    $('#modalFormDepartamento').modal('show');
}

// Función para Editar (Cargar datos y abrir Modal)
function fntEditDepartamento(idDepartamento){
    document.querySelector('#titleModal').innerHTML ="Actualizar Departamento";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML ="Actualizar";

    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl  = base_url+'/Departamentos/getDepartamento/'+idDepartamento;
    request.open("GET",ajaxUrl ,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            
            var objData = JSON.parse(request.responseText);
            if(objData.status)
            {
                document.querySelector("#idDepartamento").value = objData.data.id;
                document.querySelector("#txtNombre").value = objData.data.nombre;
                document.querySelector("#txtDescripcion").value = objData.data.descripcion;
                document.querySelector("#intUmbralAlerta").value = objData.data.umbral_alerta_stress;
                
                $('#modalFormDepartamento').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}

// Función para Eliminar
function fntDelDepartamento(idDepartamento){
    swal({
        title: "Eliminar Departamento",
        text: "¿Realmente quiere eliminar este Departamento?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar!",
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        
        if (isConfirm) 
        {
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url+'/Departamentos/delDepartamento/';
            var strData = "idDepartamento="+idDepartamento;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    var objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {
                        swal("Eliminar!", objData.msg , "success");
                        tableDepartamentos.api().ajax.reload();
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }

    });
}