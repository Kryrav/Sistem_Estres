var tableReportes;
var divLoading = document.querySelector("#divLoading");

document.addEventListener('DOMContentLoaded', function(){
    // Cargar métricas en tiempo real
    loadDashboardMetrics();
    loadCharts();

    // Configurar DataTable para reportes
    tableReportes = $('#tableReportes').dataTable( {
        "aProcessing":true,
        "aServerSide":true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax":{
            "url": " "+base_url+"/Analiticas/getReportes",
            "dataSrc":""
        },
        "columns":[
            {"data":"id"},
            {"data":"departamento_nombre"},
            {"data":"nivel_estres_formatted"},
            {"data":"observaciones"},
            {"data":"generado_por_nombre"},
            {"data":"fecha_reporte"},
            {"data":"options"}
        ],
        "responsive":true,
        "bDestroy": true,
        "iDisplayLength": 10,
        "order":[[0,"desc"]]  
    });

    // Formulario de reportes
    var formReporte = document.querySelector("#formReporte");
    formReporte.onsubmit = function(e) {
        e.preventDefault();

        var intIdReporte = document.querySelector('#idReporte').value;
        var intDepartamento = document.querySelector('#listDepartamento').value;
        var strNivelEstres = document.querySelector('#txtNivelEstres').value;
        var strObservaciones = document.querySelector('#txtObservaciones').value;
        
        if(intDepartamento == '' || strNivelEstres == '' || strObservaciones == '')
        {
            swal("Atención", "Todos los campos son obligatorios." , "error");
            return false;
        }

        divLoading.style.display = "flex";
        var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        var ajaxUrl = base_url+'/Analiticas/setReporte'; 
        var formData = new FormData(formReporte);
        request.open("POST",ajaxUrl,true);
        request.send(formData);
        
        request.onreadystatechange = function(){
           if(request.readyState == 4 && request.status == 200){
                var objData = JSON.parse(request.responseText);
                if(objData.status)
                {
                    $('#modalFormReporte').modal("hide");
                    formReporte.reset();
                    swal("Reportes", objData.msg ,"success");
                    tableReportes.api().ajax.reload();
                    loadDashboardMetrics(); // Actualizar métricas
                }else{
                    swal("Error", objData.msg , "error");
                }              
            } 
            divLoading.style.display = "none";
            return false;
        }
    }
});

// Cargar métricas del dashboard
function loadDashboardMetrics() {
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Analiticas/getMetricasGenerales';
    request.open("GET",ajaxUrl,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            
            // Calcular métricas
            var totalEstres = 0;
            var totalMuestras = 0;
            var alertasActivas = 0;
            var deptosCriticos = 0;
            var encuestasCompletas = 0;

            // Procesar datos de estrés por departamento
            objData.estres_departamentos.forEach(function(depto) {
                totalEstres += parseFloat(depto.promedio_estres) * depto.total_muestras;
                totalMuestras += depto.total_muestras;
                if(depto.promedio_estres > 6.5) deptosCriticos++;
            });

            // Procesar intervenciones
            objData.intervenciones.forEach(function(interv) {
                if(interv.estado === 'pendiente') alertasActivas += interv.total;
            });

            // Procesar encuestas
            objData.metricas_encuestas.forEach(function(enc) {
                encuestasCompletas += enc.total_respuestas;
            });

            // Actualizar UI
            document.getElementById('avgStress').textContent = totalMuestras > 0 ? (totalEstres / totalMuestras).toFixed(2) : '0.00';
            document.getElementById('activeAlerts').textContent = alertasActivas;
            document.getElementById('completedSurveys').textContent = encuestasCompletas;
            document.getElementById('criticalDepts').textContent = deptosCriticos;
        }
    }
}

// Cargar gráficos
function loadCharts() {
    // Gráfico de barras - Estrés por departamento
    var request1 = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl1 = base_url+'/Analiticas/getEstresDepartamentos';
    request1.open("GET",ajaxUrl1,true);
    request1.send();

    request1.onreadystatechange = function(){
        if(request1.readyState == 4 && request1.status == 200){
            var departamentosData = JSON.parse(request1.responseText);
            
            var deptos = [];
            var niveles = [];
            var colores = [];

            departamentosData.forEach(function(item) {
                deptos.push(item.departamento);
                niveles.push(parseFloat(item.promedio_estres));
                colores.push(item.promedio_estres > 6.5 ? '#dc3545' : 
                            item.promedio_estres > 3.5 ? '#ffc107' : '#28a745');
            });

            var barData = {
                labels: deptos,
                datasets: [{
                    label: 'Nivel de Estrés',
                    backgroundColor: colores,
                    borderColor: 'rgba(2,117,216,1)',
                    data: niveles
                }]
            };

            var barOptions = {
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 10
                    }
                }
            };

            var barChart = new Chart(document.getElementById('barChartDemo'), {
                type: 'bar',
                data: barData,
                options: barOptions
            });
        }
    }

    // Gráfico de línea - Tendencia
    var request2 = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl2 = base_url+'/Analiticas/getTendencias';
    request2.open("GET",ajaxUrl2,true);
    request2.send();

    request2.onreadystatechange = function(){
        if(request2.readyState == 4 && request2.status == 200){
            var tendenciasData = JSON.parse(request2.responseText);
            
            var fechas = [];
            var niveles = [];

            tendenciasData.slice(0, 7).reverse().forEach(function(item) { // Últimos 7 días
                fechas.push(new Date(item.fecha).toLocaleDateString());
                niveles.push(parseFloat(item.promedio_diario));
            });

            var lineData = {
                labels: fechas,
                datasets: [{
                    label: 'Estrés Promedio Diario',
                    backgroundColor: 'rgba(2,117,216,0.2)',
                    borderColor: 'rgba(2,117,216,1)',
                    data: niveles,
                    fill: true
                }]
            };

            var lineChart = new Chart(document.getElementById('lineChartDemo'), {
                type: 'line',
                data: lineData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 10
                        }
                    }
                }
            });
        }
    }
}

// Funciones para el modal de reportes
function openModal(){
    document.querySelector('#idReporte').value = "";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar";
    document.querySelector('#titleModal').innerHTML = "Nuevo Reporte";
    document.querySelector("#formReporte").reset();
    $('#modalFormReporte').modal('show');
}

function fntEditReporte(idreporte){
    document.querySelector('#titleModal').innerHTML = "Actualizar Reporte";
    document.querySelector('.modal-header').classList.replace("headerRegister", "headerUpdate");
    document.querySelector('#btnActionForm').classList.replace("btn-primary", "btn-info");
    document.querySelector('#btnText').innerHTML = "Actualizar";

    var idreporte = idreporte;
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl  = base_url+'/Analiticas/getReporte/'+idreporte;
    request.open("GET",ajaxUrl ,true);
    request.send();

    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status)
            {
                document.querySelector("#idReporte").value = objData.data.id;
                document.querySelector("#listDepartamento").value = objData.data.departamento_id;
                document.querySelector("#txtNivelEstres").value = objData.data.nivel_general_estres;
                document.querySelector("#txtObservaciones").value = objData.data.observaciones;
                $('#modalFormReporte').modal('show');
            }else{
                swal("Error", objData.msg , "error");
            }
        }
    }
}

function fntDelReporte(idreporte){
    var idreporte = idreporte;
    swal({
        title: "Eliminar Reporte",
        text: "¿Realmente quiere eliminar este reporte?",
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
            var ajaxUrl = base_url+'/Analiticas/delReporte/';
            var strData = "idreporte="+idreporte;
            request.open("POST",ajaxUrl,true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    var objData = JSON.parse(request.responseText);
                    if(objData.status)
                    {
                        swal("Eliminar!", objData.msg , "success");
                        tableReportes.api().ajax.reload();
                        loadDashboardMetrics();
                    }else{
                        swal("Atención!", objData.msg , "error");
                    }
                }
            }
        }
    });
}

function loadBarChart() {
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url + '/Analiticas/getEstresDepartamentos';
    request.open("GET", ajaxUrl, true);
    request.send();

    request.onreadystatechange = function() {
        if (request.readyState == 4 && request.status == 200) {
            try {
                var departamentosData = JSON.parse(request.responseText);
                
                console.log('Datos recibidos para gráfico:', departamentosData); // DEBUG
                
                var deptos = [];
                var niveles = [];
                var colores = [];

                // Verificar si hay datos
                if (departamentosData.length === 0) {
                    console.warn('No hay datos de departamentos');
                    showChartError('barChartDemo', 'No hay datos disponibles para mostrar');
                    return;
                }

                departamentosData.forEach(function(item) {
                    deptos.push(item.departamento);
                    niveles.push(parseFloat(item.promedio_estres));
                    
                    console.log(`Departamento: ${item.departamento}, Estrés: ${item.promedio_estres}, Muestras: ${item.total_muestras}`); // DEBUG
                    
                    // Asignar colores según nivel de estrés
                    if (item.promedio_estres > 6.5) {
                        colores.push('#dc3545'); // Rojo - Alto
                    } else if (item.promedio_estres > 3.5) {
                        colores.push('#ffc107'); // Amarillo - Medio
                    } else {
                        colores.push('#28a745'); // Verde - Bajo
                    }
                });

                var ctx = document.getElementById('barChartDemo');
                if (!ctx) {
                    console.error('Canvas barChartDemo no encontrado');
                    return;
                }

                // Destruir gráfico anterior si existe
                if (charts.barChart) {
                    charts.barChart.destroy();
                }

                charts.barChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: deptos,
                        datasets: [{
                            label: 'Nivel de Estrés Promedio',
                            data: niveles,
                            backgroundColor: colores,
                            borderColor: colores,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                max: 10,
                                title: {
                                    display: true,
                                    text: 'Nivel de Estrés (0-10)'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Departamentos'
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Estrés: ' + context.parsed.y.toFixed(2);
                                    }
                                }
                            }
                        }
                    }
                });

            } catch (error) {
                console.error('Error al crear gráfico de barras:', error);
                showChartError('barChartDemo', 'Error al cargar datos del gráfico');
            }
        }
    };
}