// functions_dashboard.js - Versión Corregida
console.log('✅ functions_dashboard.js cargado correctamente');

let charts = {};

function initDashboardCharts() {
    console.log("🎯 Inicializando gráficos del dashboard...");
    
    try {
        createStressByDepartmentChart();
        createStressTrendChart();
        createStressDistributionChart();
        createTasksChart();
        createInterventionsChart();
        console.log("✅ Todos los gráficos inicializados correctamente");
    } catch (error) {
        console.error("❌ Error al inicializar gráficos:", error);
        showChartError('general', 'Error al crear gráficos: ' + error.message);
    }
}

function createStressByDepartmentChart() {
    console.log("📊 Creando gráfico de estrés por departamento...");
    const ctx = document.getElementById('chartStressDeptos');
    if (!ctx) {
        showChartError('stressDeptos', 'Canvas no encontrado');
        return;
    }

    const data = dashboardData.estresDepartamentos;
    console.log('Datos departamentos:', data);
    
    if (!data.labels || data.labels.length === 0) {
        showChartError('stressDeptos', 'No hay datos de departamentos disponibles');
        return;
    }

    try {
        // Colores basados en umbrales
        const backgroundColors = data.values.map((level, index) => {
            const threshold = data.thresholds[index] || 7;
            return level > threshold ? '#dc3545' : 
                   level > 5 ? '#ffc107' : '#28a745';
        });

        // Destruir gráfico anterior si existe
        if (charts.stressDeptos) {
            charts.stressDeptos.destroy();
        }

        charts.stressDeptos = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Nivel de Estrés Promedio',
                    data: data.values,
                    backgroundColor: backgroundColors,
                    borderColor: backgroundColors,
                    borderWidth: 1
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
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Estrés: ${context.parsed.y.toFixed(2)}`;
                            }
                        }
                    }
                }
            }
        });
        
        hideChartError('stressDeptos');
        console.log("✅ Gráfico de departamentos creado");
    } catch (error) {
        console.error("❌ Error en gráfico departamentos:", error);
        showChartError('stressDeptos', 'Error al crear gráfico: ' + error.message);
    }
}

function createStressTrendChart() {
    console.log("📈 Creando gráfico de tendencia...");
    const ctx = document.getElementById('chartTendenciaEstres');
    if (!ctx) {
        showChartError('tendencia', 'Canvas no encontrado');
        return;
    }

    const data = dashboardData.tendenciasEstres;
    console.log('Datos tendencia:', data);
    
    if (!data.dates || data.dates.length === 0) {
        showChartError('tendencia', 'No hay datos de tendencias disponibles');
        return;
    }

    try {
        if (charts.stressTrend) {
            charts.stressTrend.destroy();
        }

        charts.stressTrend = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.dates,
                datasets: [{
                    label: 'Estrés Promedio Diario',
                    data: data.levels,
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
        
        hideChartError('tendencia');
        console.log("✅ Gráfico de tendencia creado");
    } catch (error) {
        console.error("❌ Error en gráfico tendencia:", error);
        showChartError('tendencia', 'Error al crear gráfico: ' + error.message);
    }
}

function createStressDistributionChart() {
    console.log("🥧 Creando gráfico de distribución...");
    const ctx = document.getElementById('chartDistribucionEstres');
    if (!ctx) {
        showChartError('distribucion', 'Canvas no encontrado');
        return;
    }

    const data = dashboardData.distribucionEstres;
    const total = data.bajo + data.medio + data.alto;
    console.log('Datos distribución:', data, 'Total:', total);
    
    if (total === 0) {
        showChartError('distribucion', 'No hay datos de distribución disponibles');
        return;
    }

    try {
        if (charts.stressDistribution) {
            charts.stressDistribution.destroy();
        }

        charts.stressDistribution = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Bajo (0-3.5)', 'Medio (3.6-6.5)', 'Alto (6.6-10)'],
                datasets: [{
                    data: [data.bajo, data.medio, data.alto],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        
        hideChartError('distribucion');
        console.log("✅ Gráfico de distribución creado");
    } catch (error) {
        console.error("❌ Error en gráfico distribución:", error);
        showChartError('distribucion', 'Error al crear gráfico: ' + error.message);
    }
}

function createTasksChart() {
    console.log("📋 Creando gráfico de tareas...");
    const ctx = document.getElementById('chartTareas');
    if (!ctx) {
        showChartError('tareas', 'Canvas no encontrado');
        return;
    }

    const data = dashboardData.tareas;
    const total = data.completadas + data.pendientes;
    console.log('Datos tareas:', data, 'Total:', total);
    
    if (total === 0) {
        showChartError('tareas', 'No hay datos de tareas disponibles');
        return;
    }

    try {
        if (charts.tasks) {
            charts.tasks.destroy();
        }

        charts.tasks = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Completadas', 'Pendientes'],
                datasets: [{
                    data: [data.completadas, data.pendientes],
                    backgroundColor: ['#28a745', '#ffc107']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
        
        hideChartError('tareas');
        console.log("✅ Gráfico de tareas creado");
    } catch (error) {
        console.error("❌ Error en gráfico tareas:", error);
        showChartError('tareas', 'Error al crear gráfico: ' + error.message);
    }
}

function createInterventionsChart() {
    console.log("🔄 Creando gráfico de intervenciones...");
    const ctx = document.getElementById('chartIntervenciones');
    if (!ctx) {
        showChartError('intervenciones', 'Canvas no encontrado');
        return;
    }

    const data = dashboardData.intervenciones;
    const total = data.descanso + data.redistribucion + data.burnout + data.felicitacion;
    console.log('Datos intervenciones:', data, 'Total:', total);
    
    if (total === 0) {
        showChartError('intervenciones', 'No hay datos de intervenciones disponibles');
        return;
    }

    try {
        if (charts.interventions) {
            charts.interventions.destroy();
        }

        charts.interventions = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Descanso', 'Redistribución', 'Burnout', 'Felicitación'],
                datasets: [{
                    label: 'Intervenciones',
                    data: [data.descanso, data.redistribucion, data.burnout, data.felicitacion],
                    backgroundColor: ['#17a2b8', '#ffc107', '#dc3545', '#28a745']
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
        
        hideChartError('intervenciones');
        console.log("✅ Gráfico de intervenciones creado");
    } catch (error) {
        console.error("❌ Error en gráfico intervenciones:", error);
        showChartError('intervenciones', 'Error al crear gráfico: ' + error.message);
    }
}

function showChartError(chartType, message) {
    const errorId = 'error' + chartType.charAt(0).toUpperCase() + chartType.slice(1);
    const errorElement = document.getElementById(errorId);
    if (errorElement) {
        errorElement.style.display = 'block';
        errorElement.textContent = message;
    }
    console.error(`❌ Error en gráfico ${chartType}:`, message);
}

function hideChartError(chartType) {
    const errorId = 'error' + chartType.charAt(0).toUpperCase() + chartType.slice(1);
    const errorElement = document.getElementById(errorId);
    if (errorElement) {
        errorElement.style.display = 'none';
    }
}