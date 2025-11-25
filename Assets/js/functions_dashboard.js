var ctxStress = document.getElementById('chartStress').getContext('2d');
var chartStress = new Chart(ctxStress, {
    type: 'bar',
    data: {
        labels: ['Dept. 1', 'Dept. 2', 'Dept. 3'],
        datasets: [{
            label: 'Nivel de estrés',
            data: [3, 5, 2],
            backgroundColor: ['#ff6384', '#36a2eb', '#ffcd56']
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true, max: 5 } }
    }
});

var ctxTasks = document.getElementById('chartTasks').getContext('2d');
var chartTasks = new Chart(ctxTasks, {
    type: 'doughnut',
    data: {
        labels: ['Pendientes', 'Completadas'],
        datasets: [{
            data: [40, 60],
            backgroundColor: ['#ff6384', '#36a2eb']
        }]
    },
    options: { responsive: true }
});
