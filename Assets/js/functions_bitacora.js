var divLoading = document.querySelector("#divLoading");

// En functions_bitacora.js - CORREGIR TODAS LAS URLs
var tableBitacora;

document.addEventListener('DOMContentLoaded', function(){
    tableBitacora = $('#tableBitacora').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json"
        },
        "ajax": {
            "url": base_url + "/Bitacora/getRegistros",
            "dataSrc": ""
        },
        "columns": [
            {"data": "fecha_formateada"},
            {"data": "hora_formateada"},
            {"data": "tipo_badge"},
            {"data": "stress_badge"},
            {"data": "energia_badge"},
            {"data": "sentimiento_texto"},
            {"data": "tarea"},
            {"data": "options"}
        ],
        "responsive": true,
        "bDestroy": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]]
        
    });

        document.getElementById('listTipoRegistro').addEventListener('change', function() {
        var tipoRegistro = this.value;
        var tareaSelect = document.getElementById('listTarea');
        
        if (tipoRegistro === 'cierre_tarea') {
            // Si es cierre de tarea, hacer obligatoria la selección de tarea
            tareaSelect.required = true;
            tareaSelect.disabled = false;
        } else {
            // Para otros tipos, la tarea es opcional
            tareaSelect.required = false;
            tareaSelect.disabled = false;
        }
    });

    setTimeout(cargarMetricasPersonales, 1000);

    // Actualizar métricas cada 2 minutos
    setInterval(cargarMetricasPersonales, 120000);
    // Cargar métricas iniciales CON RETRASO para asegurar que el DOM esté listo
    setTimeout(function() {
        cargarMetricasPersonales();
    }, 500);

    // Cargar métricas iniciales
    cargarMetricasPersonales();
    
    // Event Listeners para los range inputs
    document.getElementById('txtStress').addEventListener('input', function() {
        document.getElementById('stressValue').textContent = this.value + ' - ' + getDescripcionStress(this.value);
    });

    document.getElementById('txtEnergia').addEventListener('input', function() {
        document.getElementById('energiaValue').textContent = this.value + ' - ' + getDescripcionEnergia(this.value);
    });

    // Submit del formulario
    var formBitacora = document.querySelector("#formBitacora");
    formBitacora.onsubmit = function(e) {
        e.preventDefault();
        var strTipoRegistro = document.querySelector('#listTipoRegistro').value;
        
        if(strTipoRegistro == ''){
            swal("Atención", "El tipo de registro es obligatorio." , "error");
            return false;
        }

        divLoading.style.display = "flex";
        var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
        var ajaxUrl = base_url + '/Bitacora/setRegistro'; 
        var formData = new FormData(formBitacora);
        
        request.open("POST", ajaxUrl, true);
        request.send(formData);
        
        request.onreadystatechange = function(){
            if(request.readyState == 4 && request.status == 200){
                var objData = JSON.parse(request.responseText);
                if(objData.status) {
                    $('#modalFormBitacora').modal("hide");
                    formBitacora.reset();
                    swal("Bitácora Emocional", objData.msg, "success");
                    tableBitacora.ajax.reload();
                    cargarMetricasPersonales();
                } else {
                    swal("Error", objData.msg, "error");
                }
                divLoading.style.display = "none";
            }
        }
    };
});


// Función mejorada para abrir modal
function openModal(){
    document.querySelector('#idRegistro').value = "";
    document.querySelector('.modal-header').classList.replace("headerUpdate", "headerRegister");
    document.querySelector('#btnActionForm').classList.replace("btn-info", "btn-primary");
    document.querySelector('#btnText').innerHTML = "Guardar Registro";
    document.querySelector('#titleModal').innerHTML = "Nuevo Registro Emocional";
    document.querySelector("#formBitacora").reset();
    
    // Resetear valores de los ranges
    document.getElementById('stressValue').textContent = '5 - Moderado';
    document.getElementById('energiaValue').textContent = '5 - Moderada';
    
    // CARGAR TAREAS DEL TRABAJADOR ANTES DE MOSTRAR EL MODAL
    cargarTareasTrabajador();
    
    $('#modalFormBitacora').modal('show');
}
// Función para botón pánico
function fntRegistroPanico(){
    swal({
        title: "¿Necesitas ayuda?",
        text: "Al presionar OK, se registrará una alerta de estrés y se notificará a tu supervisor.",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((willDelete) => {
        if (willDelete) {
            divLoading.style.display = "flex";
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Bitacora/registroPanico';
            
            request.open("POST", ajaxUrl, true);
            request.send();
            
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    var objData = JSON.parse(request.responseText);
                    if(objData.status) {
                        swal("Alerta Registrada", objData.msg, "success");
                        tableBitacora.ajax.reload();
                        cargarMetricasPersonales();
                    } else {
                        swal("Error", objData.msg, "error");
                    }
                    divLoading.style.display = "none";
                }
            }
        }
    });
}

// Función para ver detalles
function fntViewRegistro(idRegistro){
    divLoading.style.display = "flex";
    var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
    var ajaxUrl = base_url+'/Bitacora/getRegistro/' + idRegistro;
    
    request.open("GET", ajaxUrl, true);
    request.send();
    
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status) {
                var registro = objData.data;
                var html = `
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Fecha:</strong> ${formatFecha(registro.fecha)}</p>
                            <p><strong>Hora:</strong> ${formatHora(registro.hora)}</p>
                            <p><strong>Tipo:</strong> ${getTipoTexto(registro.tipo_registro)}</p>
                            <p><strong>Tarea:</strong> ${registro.tarea || 'No relacionada'}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Nivel Stress:</strong> ${getBadgeStressHTML(registro.nivel_stress_percibido)}</p>
                            <p><strong>Nivel Energía:</strong> ${getBadgeEnergiaHTML(registro.nivel_energia)}</p>
                            <p><strong>Sentimiento:</strong> ${getSentimientoTexto(registro.sentimiento_predominante)}</p>
                        </div>
                    </div>
                    ${registro.comentario_libre ? `
                    <div class="row mt-3">
                        <div class="col-12">
                            <p><strong>Comentario:</strong></p>
                            <div class="alert alert-light">${registro.comentario_libre}</div>
                        </div>
                    </div>
                    ` : ''}
                `;
                document.querySelector('#detallesRegistro').innerHTML = html;
                $('#modalViewRegistro').modal('show');
            } else {
                swal("Error", objData.msg, "error");
            }
            divLoading.style.display = "none";
        }
    }
}

// Función para eliminar registro
function fntDelRegistro(idRegistro){
    swal({
        title: "Eliminar Registro",
        text: "¿Realmente quiere eliminar este registro de la bitácora?",
        type: "warning",
        showCancelButton: true,
        confirmButtonText: "Si, eliminar!",
        cancelButtonText: "No, cancelar!",
        closeOnConfirm: false,
        closeOnCancel: true
    }, function(isConfirm) {
        if (isConfirm) {
            divLoading.style.display = "flex";
            var request = (window.XMLHttpRequest) ? new XMLHttpRequest() : new ActiveXObject('Microsoft.XMLHTTP');
            var ajaxUrl = base_url + '/Bitacora/delRegistro/';
            var strData = "idRegistro=" + idRegistro;
            
            request.open("POST", ajaxUrl, true);
            request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            request.send(strData);
            
            request.onreadystatechange = function(){
                if(request.readyState == 4 && request.status == 200){
                    var objData = JSON.parse(request.responseText);
                    if(objData.status) {
                        swal("Eliminado!", objData.msg, "success");
                        tableBitacora.ajax.reload();
                        cargarMetricasPersonales();
                    } else {
                        swal("Atención!", objData.msg, "error");
                    }
                    divLoading.style.display = "none";
                }
            }
        }
    });
}

// Función para cargar métricas personales
// En functions_bitacora.js - actualizar la función cargarMetricasPersonales


// En functions_bitacora.js - FUNCIÓN ACTUALIZADA
function cargarMetricasPersonales(){
    var request = new XMLHttpRequest();
    var ajaxUrl = base_url + '/Bitacora/getMetricasPersonales';
    
    request.open("GET", ajaxUrl, true);
    request.send();
    
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            try {
                var objData = JSON.parse(request.responseText);
                if(objData.status) {
                    var metricas = objData.metricas;
                    
                    // ====================
                    // TARJETAS PRINCIPALES
                    // ====================
                    
                    // Stress y Energía (convertir a número)
                    document.getElementById('stressPromedio').textContent = 
                        metricas.stress_promedio ? parseFloat(metricas.stress_promedio).toFixed(1) : '--';
                    
                    document.getElementById('energiaPromedio').textContent = 
                        metricas.energia_promedio ? parseFloat(metricas.energia_promedio).toFixed(1) : '--';
                    
                    // Registros y Alertas
                    document.getElementById('registrosTotales').textContent = 
                        metricas.total_registros || '0';
                    
                    const alertasPanico = parseInt(metricas.total_alertas_panico) || 0;
                    const alertasStress = parseInt(metricas.alertas_stress_alto) || 0;
                    document.getElementById('totalAlertas').textContent = alertasPanico + alertasStress;
                    
                    // ====================
                    // TARJETAS SECUNDARIAS
                    // ====================
                    
                    // Sentimiento predominante
                    const sentimientoElem = document.getElementById('sentimientoPredominante');
                    if(sentimientoElem) {
                        const sentimientos = {
                            'motivado': {text: 'Motivado', emoji: '😊', color: 'text-success'},
                            'cansado': {text: 'Cansado', emoji: '😴', color: 'text-warning'},
                            'frustrado': {text: 'Frustrado', emoji: '😠', color: 'text-danger'},
                            'ansioso': {text: 'Ansioso', emoji: '😰', color: 'text-danger'},
                            'satisfecho': {text: 'Satisfecho', emoji: '😌', color: 'text-info'},
                            'otro': {text: 'Otro', emoji: '❓', color: 'text-secondary'}
                        };
                        
                        const sentimientoData = sentimientos[metricas.sentimiento_frecuente] || sentimientos['otro'];
                        sentimientoElem.textContent = `${sentimientoData.emoji} ${sentimientoData.text}`;
                        sentimientoElem.className = sentimientoData.color;
                    }
                    
                    // Días activos
                    document.getElementById('diasActivos').textContent = 
                        metricas.dias_con_registro || '0';
                    
                    // Rango de stress
                    document.getElementById('stressMinimo').textContent = 
                        metricas.stress_minimo || '--';
                    document.getElementById('stressMaximo').textContent = 
                        metricas.stress_maximo || '--';
                    document.getElementById('rangoStress').textContent = 
                        metricas.stress_minimo && metricas.stress_maximo ? 
                        `${metricas.stress_minimo}-${metricas.stress_maximo}` : '--';
                    
                    // ====================
                    // RESUMEN RÁPIDO
                    // ====================
                    
                    document.getElementById('resumenStress').textContent = 
                        metricas.stress_promedio ? parseFloat(metricas.stress_promedio).toFixed(1) : '--';
                    document.getElementById('resumenEnergia').textContent = 
                        metricas.energia_promedio ? parseFloat(metricas.energia_promedio).toFixed(1) : '--';
                    document.getElementById('resumenRegistros').textContent = 
                        metricas.total_registros || '0';
                    document.getElementById('resumenAlertas').textContent = 
                        alertasPanico + alertasStress;
                    
                    // ====================
                    // ANÁLISIS Y RECOMENDACIONES
                    // ====================
                    
                    mostrarAnalisisBienestar(metricas);
                    mostrarRecomendaciones(metricas);
                    
                    console.log('✅ Métricas cargadas correctamente:', metricas);
                }
            } catch (error) {
                console.error('❌ Error al cargar métricas:', error);
                // Valores por defecto en caso de error
                document.getElementById('stressPromedio').textContent = '--';
                document.getElementById('energiaPromedio').textContent = '--';
                document.getElementById('registrosTotales').textContent = '--';
                document.getElementById('totalAlertas').textContent = '0';
            }
        }
    }
}

// FUNCIÓN PARA MOSTRAR ANÁLISIS DE BIENESTAR
function mostrarAnalisisBienestar(metricas) {
    const stress = parseFloat(metricas.stress_promedio) || 0;
    const energia = parseFloat(metricas.energia_promedio) || 0;
    
    let mensaje = '';
    let tipo = 'success';
    let icono = '✅';
    
    if (stress <= 3 && energia >= 7) {
        mensaje = '¡Excelente estado de bienestar! Mantén este equilibrio saludable.';
        tipo = 'success';
        icono = '🎉';
    } else if (stress <= 5 && energia >= 5) {
        mensaje = 'Estado equilibrado. Continúa con tus buenos hábitos.';
        tipo = 'info';
        icono = '👍';
    } else if (stress >= 7) {
        mensaje = 'Nivel de estrés elevado. Recomendamos pausas activas y ejercicios de respiración.';
        tipo = 'warning';
        icono = '⚠️';
    } else if (energia <= 3) {
        mensaje = 'Baja energía detectada. Recuerda mantener una alimentación balanceada y descanso adecuado.';
        tipo = 'danger';
        icono = '🔋';
    } else {
        mensaje = 'Estado regular. Monitorea tu bienestar y mantén hábitos saludables.';
        tipo = 'secondary';
        icono = '📊';
    }
    
    // Actualizar contenedor de alerta
    const container = document.getElementById('alertaAnalisisContainer');
    container.innerHTML = `
        <div class="alert alert-${tipo} alert-dismissible fade show">
            <h5 class="alert-heading">${icono} Análisis de Bienestar</h5>
            ${mensaje}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `;
}


// FUNCIÓN MEJORADA CON VALIDACIONES
function safeToFixed(value, decimals = 1) {
    if (value === null || value === undefined || value === '') {
        return '--';
    }
    const num = parseFloat(value);
    return isNaN(num) ? '--' : num.toFixed(decimals);
}

function safeParseInt(value) {
    if (value === null || value === undefined || value === '') {
        return 0;
    }
    const num = parseInt(value);
    return isNaN(num) ? 0 : num;
}

// Función para mostrar el sentimiento con emoji
function mostrarSentimientoPredominante(sentimiento) {
    const sentimientos = {
        'motivado': {emoji: '😊', text: 'Motivado', color: 'success'},
        'cansado': {emoji: '😴', text: 'Cansado', color: 'warning'},
        'frustrado': {emoji: '😠', text: 'Frustrado', color: 'danger'},
        'ansioso': {emoji: '😰', text: 'Ansioso', color: 'danger'},
        'satisfecho': {emoji: '😌', text: 'Satisfecho', color: 'info'},
        'otro': {emoji: '❓', text: 'Otro', color: 'secondary'}
    };
    
    const sentimientoData = sentimientos[sentimiento] || sentimientos['otro'];
    
    // Crear o actualizar tarjeta de sentimiento
    let sentimientoCard = document.getElementById('sentimientoCard');
    if (!sentimientoCard) {
        // Agregar tarjeta si no existe
        const cardsContainer = document.querySelector('.row.mb-4');
        cardsContainer.innerHTML += `
            <div class="col-md-3">
                <div class="widget-small ${sentimientoData.color} coloured-icon">
                    <i class="icon">${sentimientoData.emoji}</i>
                    <div class="info">
                        <h4>Estado Predominante</h4>
                        <p><b>${sentimientoData.text}</b></p>
                    </div>
                </div>
            </div>
        `;
    }
}

// Funciones helper
function getDescripcionStress(valor) {
    if(valor <= 3) return 'Muy Bajo';
    if(valor <= 5) return 'Bajo';
    if(valor <= 7) return 'Moderado';
    if(valor <= 9) return 'Alto';
    return 'Muy Alto';
}

function getDescripcionEnergia(valor) {
    if(valor <= 3) return 'Muy Baja';
    if(valor <= 5) return 'Baja';
    if(valor <= 7) return 'Moderada';
    if(valor <= 9) return 'Alta';
    return 'Muy Alta';
}

function formatFecha(fecha) {
    return new Date(fecha).toLocaleDateString('es-ES');
}

function formatHora(hora) {
    return new Date('1970-01-01T' + hora + 'Z').toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'});
}

function getTipoTexto(tipo) {
    const textos = {
        'login_checkin': 'Check-in Diario',
        'cierre_tarea': 'Cierre de Tarea',
        'boton_panico': 'Alerta de Estrés',
        'logout': 'Logout',
        'auto': 'Automático'
    };
    return textos[tipo] || tipo;
}

function getBadgeStressHTML(nivel) {
    if(nivel === null) return '<span class="badge badge-secondary">No registrado</span>';
    if(nivel <= 3) return `<span class="badge badge-success">${nivel} - Bajo</span>`;
    if(nivel <= 7) return `<span class="badge badge-warning">${nivel} - Medio</span>`;
    return `<span class="badge badge-danger">${nivel} - Alto</span>`;
}

function getBadgeEnergiaHTML(nivel) {
    if(nivel === null) return '<span class="badge badge-secondary">No registrado</span>';
    if(nivel <= 3) return `<span class="badge badge-danger">${nivel} - Baja</span>`;
    if(nivel <= 7) return `<span class="badge badge-warning">${nivel} - Media</span>`;
    return `<span class="badge badge-success">${nivel} - Alta</span>`;
}

function getSentimientoTexto(sentimiento) {
    const textos = {
        'motivado': '😊 Motivado',
        'cansado': '😴 Cansado',
        'frustrado': '😠 Frustrado',
        'ansioso': '😰 Ansioso',
        'satisfecho': '😌 Satisfecho',
        'otro': '❓ Otro'
    };
    return textos[sentimiento] || sentimiento;
}

// Actualizar métricas cada 5 minutos
setInterval(cargarMetricasPersonales, 300000);

// FUNCIÓN PARA MOSTRAR RECOMENDACIONES
function mostrarRecomendaciones(metricas) {
    const stress = parseFloat(metricas.stress_promedio) || 0;
    const energia = parseFloat(metricas.energia_promedio) || 0;
    const alertas = (parseInt(metricas.total_alertas_panico) || 0) + (parseInt(metricas.alertas_stress_alto) || 0);
    
    let recomendaciones = [];
    
    if (stress >= 6) {
        recomendaciones.push('💆 Practica ejercicios de respiración profunda');
        recomendaciones.push('🕒 Toma pausas activas cada 2 horas');
        recomendaciones.push('🎵 Escucha música relajante durante el trabajo');
    }
    
    if (energia <= 4) {
        recomendaciones.push('💤 Asegura 7-8 horas de sueño nocturno');
        recomendaciones.push('🥗 Mantén una alimentación balanceada');
        recomendaciones.push('🚶 Realiza caminatas cortas durante el día');
    }
    
    if (alertas > 0) {
        recomendaciones.push('🆘 Utiliza el botón pánico cuando sientas sobrecarga');
        recomendaciones.push('👥 Comunica tu estado a tu supervisor');
    }
    
    if (recomendaciones.length === 0) {
        recomendaciones.push('🎯 Mantén tus buenos hábitos actuales');
        recomendaciones.push('📝 Continúa registrando tu estado emocional');
        recomendaciones.push('💪 Sigue con tu rutina de bienestar');
    }
    
    const container = document.getElementById('recomendacionesContainer');
    container.innerHTML = '<ul class="list-unstyled">' + 
        recomendaciones.map(rec => `<li>${rec}</li>`).join('') + 
        '</ul>';
}


// Función para cargar tareas del trabajador
function cargarTareasTrabajador() {
    var request = new XMLHttpRequest();
    var ajaxUrl = base_url + '/Bitacora/getTareasTrabajador';
    
    request.open("GET", ajaxUrl, true);
    request.send();
    
    request.onreadystatechange = function(){
        if(request.readyState == 4 && request.status == 200){
            var objData = JSON.parse(request.responseText);
            if(objData.status) {
                var tareas = objData.tareas;
                var selectTarea = document.getElementById('listTarea');
                
                // Limpiar opciones existentes (excepto la primera)
                while(selectTarea.options.length > 1) {
                    selectTarea.remove(1);
                }
                
                // Agregar tareas al select
                tareas.forEach(function(tarea) {
                    var option = document.createElement('option');
                    option.value = tarea.id;
                    option.textContent = tarea.titulo + ' (' + tarea.estado + ')';
                    selectTarea.appendChild(option);
                });
                
                console.log('✅ Tareas cargadas:', tareas.length);
            } else {
                console.warn('⚠️ No se pudieron cargar las tareas:', objData.msg);
            }
        }
    }
}