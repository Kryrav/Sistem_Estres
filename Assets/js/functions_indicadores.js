var tableIndicadores;
var divLoading = document.querySelector("#divLoading");

document.addEventListener('DOMContentLoaded', function(){
    // Cargar estadísticas del dashboard
    loadDashboardStats();
    loadCharts();

    // Configurar DataTable para indicadores
    tableIndicadores = $('#tableIndicadores').dataTable( {
        "aProcessing":true,
        "aServerSide":true,
        "language": {
            "url": "<?= base_url() ?>/Assets/plugins/datatables/Spanish.json"
        },
        "ajax":{
            "url": " "+base_url+"/Indicadores/getIndicadores",
            "dataSrc":""
        },
        "columns":[
            {"data":"id"},
            {"data":"trabajador_nombre"},
            {"data":"departamento_nombre"},
            {"data":"nivel_estres_formatted"},
            {"data":"categoria_formatted"},
            {"data":"metodo_calculo"},
            {"data":"fecha_formatted"},
            {"data":"options"}
        ],
        "responsive":true,
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[0,"desc"]]  
    });

    // Formulario de indicadores
    var formIndicador = document.querySelector("#formIndicador");
    formIndicador.onsubmit = function(e) {
        e.preventDefault();

        var intIdIndicador = document.querySelector('#idIndicador').value;
        var intTrabajador = document.querySelector('#listTrabajador').value;
        var intDepartamento = document.querySelector('#listDepartamento').value;
        var strNivelEstres = document.querySelector('#txtNivelEstres').value;
        var strCategoria = document.querySelector('#listCategoria').value;
        var strMetodoCalculo = document.querySelector('#listMetodoCalculo').value;
        
        if(intTrabajador == '' || intDepartamento == '' || strNivelEstres == '' || strCategoria == '' || strMetodoCalculo == '')
        {
            swal("Atención", "Todos los campos son obligatorios." , "error");
            return false;
        }

        // Validar nivel de estrés
        if(strNivelEstres < 0 || strNivelEstres > 10) {
            swal("Atención", "El nivel de estrés debe estar entre 0 y 10." , "error");
            return false;
        }

        divLoading.style.display = "flex";
        var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        var ajaxUrl = base_url+'/Indicadores/setIndicador'; 
        var formData = new FormData(formIndicador);
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        
        request.onreadystatechange = function(){
           if(request.readyState == 4 && request.status == 200){
                var objData = JSON.parse(request.responseText);
                if(objData.status)
                {
                    $('#modalFormIndicador').modal("hide");
                    formIndicador.reset();
                    swal("Indicadores de Estrés", objData.msg ,"success");
                    tableIndicadores.api().ajax.reload();
                    loadDashboardStats(); // Actualizar estadísticas
                    loadCharts(); // Actualizar gráficos
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
    var ajaxUrl = base_url+'/Indicadores/getEstadisticas';
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            
            // Actualizar UI
            document.getElementById('totalIndicadores').textContent = objData.total_indicadores || 0;
            document.getElementById('totalTrabajadores').textContent = objData.trabajadores_monitoreados || 0;
            document.getElementById('totalDepartamentos').textContent = objData.departamentos_monitoreados || 0;
            document.getElementById('promedioEstres').textContent = objData.promedio_general ? parseFloat(objData.promedio_general).toFixed(2) : '0.00';
        }
    }
}

// Cargar gráficos
function loadCharts() {
    if (typeof Chart === 'undefined') {
        console.warn('Chart.js no disponible para cargar gráficos');
        return;
    }

    loadTendenciaChart();
    loadDistribucionChart();
}

function loadTendenciaChart() {
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Indicadores/getTendencias?periodo=30 DAY';
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            try {
                var tendenciasData = JSON.parse(request.responseText);
                
                var fechas = [];
                var niveles = [];

                tendenciasData.forEach(function(item) {
                    fechas.push(new Date(item.fecha).toLocaleDateString('es-ES', { 
                        month: 'short', 
                        day: 'numeric' 
                    }));
                    niveles.push(parseFloat(item.promedio_diario));
                });

                var ctx = document.getElementById('chartTendenciaIndicadores');
                if (!ctx) return;

                // Destruir gráfico anterior si existe
                if (window.tendenciaChart) {
                    window.tendenciaChart.destroy();
                }

                window.tendenciaChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: fechas,
                        datasets: [{
                            label: 'Estrés Promedio Diario',
                            data: niveles,
                            borderColor: '#dc3545',
                            backgroundColor: 'rgba(220, 53, 69, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: { 
                            y: { 
                                beginAtZero: true, 
                                max: 10,
                                title: {
                                    display: true,
                                    text: 'Nivel de Estrés'
                                }
                            }
                        }
                    }
                });

            } catch (error) {
                console.error('Error al crear gráfico de tendencia:', error);
            }
        }
    }
}

function loadDistribucionChart() {
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Indicadores/getDistribucion';
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            try {
                var distribucionData = JSON.parse(request.responseText);
                
                var categorias = [];
                var totales = [];

                distribucionData.forEach(function(item) {
                    categorias.push(item.categoria);
                    totales.push(item.total);
                });

                var ctx = document.getElementById('chartDistribucionCategoria');
                if (!ctx) return;

                // Destruir gráfico anterior si existe
                if (window.distribucionChart) {
                    window.distribucionChart.destroy();
                }

                window.distribucionChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: categorias,
                        datasets: [{
                            label: 'Cantidad de Indicadores',
                            data: totales,
                            backgroundColor: ['#3498db', '#2ecc71', '#e74c3c', '#f39c12', '#9b59b6']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: { 
                            y: { 
                                beginAtZero: true
                            }
                        }
                    }
                });

            } catch (error) {
                console.error('Error al crear gráfico de distribución:', error);
            }
        }
    }
}

// Funciones para el modal de indicadores
function openModal(){
    document.querySelector('#idIndicador').value = "";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Indicador";
    document.querySelector("#formIndicador").reset();
    
    // Habilitar todos los campos
    document.querySelector('#listTrabajador').disabled = false;
    document.querySelector('#listDepartamento').disabled = false;
    document.querySelector('#txtNivelEstres').disabled = false;
    document.querySelector('#listCategoria').disabled = false;
    document.querySelector('#listMetodoCalculo').disabled = false;
    
    $('#modalFormIndicador').modal('show');
}

function fntEditIndicador(idindicador){
    document.querySelector('#titleModal').innerHTML = "Actualizar Indicador";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";

    // Deshabilitar campos que no se deben editar
    document.querySelector('#listTrabajador').disabled = true;
    document.querySelector('#listDepartamento').disabled = true;
    
    // Habilitar campos editables
    document.querySelector('#txtNivelEstres').disabled = false;
    document.querySelector('#listCategoria').disabled = false;
    document.querySelector('#listMetodoCalculo').disabled = false;

    var idindicador = idindicador;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl  = base_url+'/Indicadores/getIndicador/'+idindicador;
    request.open("GET",ajaxUrl ,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status)
            {
                document.querySelector("#idIndicador").value = objData.data.id;
                document.querySelector("#listTrabajador").value = objData.data.trabajador_id;
                document.querySelector("#listDepartamento").value = objData.data.departamento_id;
                document.querySelector("#txtNivelEstres").value = objData.data.nivel_estres;
                document.querySelector("#listCategoria").value = objData.data.categoria;
                document.querySelector("#listMetodoCalculo").value = objData.data.metodo_calculo;
                $('#modalFormIndicador').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}

function fntDelIndicador(idindicador){
    var idindicador = idindicador;
    swal({
        title: "Eliminar Indicador",
        text: "¿Realmente quiere eliminar este indicador?",
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
            var ajaxUrl = base_url+'/Indicadores/delIndicador/';
            var strData = "idindicador="+idindicador;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    var objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {
                        swal("Eliminar!", objData.msg , "success");
                        tableIndicadores.api().ajax.reload();
                        loadDashboardStats();
                        loadCharts();
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }
    });
}

// Función para ver indicadores por trabajador
function fntViewIndicadoresTrabajador(idTrabajador){
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Indicadores/getIndicadoresTrabajador/'+idTrabajador;
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var indicadores = JSON.parse(request.responseText);
            var htmlContent = '<div class="list-group">';
            
            indicadores.forEach(function(ind) {
                var badgeClass = ind.nivel_estres <= 3.5 ? 'success' : (ind.nivel_estres <= 6.5 ? 'warning' : 'danger');
                
                htmlContent += `
                    <div class="list-group-item">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1">${ind.departamento_nombre || 'Sin departamento'}</h6>
                            <small><span class="badge badge-${badgeClass}">${ind.nivel_estres}</span></small>
                        </div>
                        <p class="mb-1">Categoría: ${ind.categoria}</p>
                        <small>${new Date(ind.fecha_calculo).toLocaleDateString()} - ${ind.metodo_calculo}</small>
                    </div>
                `;
            });
            
            htmlContent += '</div>';
            
            swal({
                title: "Indicadores del Trabajador",
                html: true,
                text: htmlContent,
                width: 600
            });
        }
    }
}

// Función para filtrar por período
function filterByPeriod(periodo) {
    loadTendenciaChartWithPeriod(periodo);
}

function loadTendenciaChartWithPeriod(periodo) {
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Indicadores/getTendencias?periodo=' + periodo;
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            try {
                var tendenciasData = JSON.parse(request.responseText);
                
                var fechas = [];
                var niveles = [];

                tendenciasData.forEach(function(item) {
                    fechas.push(new Date(item.fecha).toLocaleDateString('es-ES', { 
                        month: 'short', 
                        day: 'numeric' 
                    }));
                    niveles.push(parseFloat(item.promedio_diario));
                });

                var ctx = document.getElementById('chartTendenciaIndicadores');
                if (!ctx) return;

                if (window.tendenciaChart) {
                    window.tendenciaChart.destroy();
                }

                window.tendenciaChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: fechas,
                        datasets: [{
                            label: 'Estrés Promedio Diario (' + periodo + ')',
                            data: niveles,
                            borderColor: '#dc3545',
                            backgroundColor: 'rgba(220, 53, 69, 0.1)',
                            borderWidth: 2,
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: { 
                            y: { 
                                beginAtZero: true, 
                                max: 10
                            }
                        }
                    }
                });

            } catch (error) {
                console.error('Error al crear gráfico de tendencia:', error);
            }
        }
    }
}