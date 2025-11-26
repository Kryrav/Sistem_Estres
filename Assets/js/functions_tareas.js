let tableTareas;

document.addEventListener("DOMContentLoaded", function() {

    // Inicializar DataTable
    tableTareas = $('#tableTareas').DataTable({
        "ajax": {
            "url": "tareas/listar",
            "dataSrc": ""
        },
        "columns": [
            { "data": "id" },
            { "data": "titulo" },
            { "data": "trabajador" },
            { "data": "tipo" },
            { "data": "minutos_estimados" },
            { "data": "estado" },
            { "data": "options" }
        ],
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        },
        "responsive": true
    });

    // Formulario: crear o actualizar tarea
    $('#formTarea').submit(function(e){
        e.preventDefault();
        const formData = new FormData(this);
        fetch("Tareas/guardar", {
            method: "POST",
            body: formData
        }).then(res => res.json())
          .then(data => {
              if(data.status){
                  $('#modalTarea').modal('hide');
                  tableTareas.ajax.reload(null, false);
                  alert(data.msg);
              } else {
                  alert(data.msg);
              }
          });
    });

});

// Abrir modal para nueva tarea
function openModalTarea(){
    $('#formTarea')[0].reset();
    $('#idTarea').val(0);
    $('#modalTareaLabel').text('Nueva Tarea');
    $('#modalTarea').modal('show');
}

// Abrir modal para editar tarea
function fntEditTarea(id){
    fetch("Tareas/getTarea/"+id)
    .then(res => res.json())
    .then(data => {
        if(data.status){
            const tarea = data.data;
            $('#idTarea').val(tarea.id);
            $('#titulo').val(tarea.titulo);
            $('#descripcion').val(tarea.descripcion);
            $('#trabajador_id').val(tarea.trabajador_id);
            $('#tipo_tarea_id').val(tarea.tipo_tarea_id);
            $('#minutos_estimados').val(tarea.minutos_estimados);
            $('#estado').val(tarea.estado);
            $('#modalTareaLabel').text('Editar Tarea');
            $('#modalTarea').modal('show');
        } else {
            alert(data.msg);
        }
    });
}

// Eliminar tarea
function fntDelTarea(id){
    if(confirm("¿Está seguro de eliminar esta tarea?")){
        fetch("Tareas/eliminar/"+id)
        .then(res => res.json())
        .then(data => {
            if(data.status){
                tableTareas.ajax.reload(null, false);
                alert(data.msg);
            } else {
                alert(data.msg);
            }
        });
    }
}
