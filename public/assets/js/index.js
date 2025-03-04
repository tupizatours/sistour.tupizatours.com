$(document).ready(function () {
  "use strict";

  // Función para inicializar gráficos
  function createChart(chartId, type, data, options) {
      var canvas = document.getElementById(chartId);
      if (canvas) {
          var ctx = canvas.getContext('2d');
          new Chart(ctx, {
              type: type,
              data: data,
              options: options
          });
      } else {
          console.warn(`⚠️ El canvas con ID "${chartId}" no existe en el DOM.`);
      }
  }

  // **Chart 1 - Barras**
  createChart("chart1", 'bar', {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      datasets: [{
          label: 'Laptops',
          data: [65, 59, 80, 81,65, 59, 80, 81,59, 80, 81,65],
          backgroundColor: '#6078ea'
      }, {
          label: 'Mobiles',
          data: [28, 48, 40, 19,28, 48, 40, 19,40, 19,28, 48],
          backgroundColor: '#ff8359'
      }]
  }, {
      maintainAspectRatio: false,
      plugins: { legend: { display: false }},
      scales: { y: { beginAtZero: true }}
  });

  // **Chart 2 - Dona**
  createChart("chart2", 'doughnut', {
      labels: ["Jeans", "T-Shirts", "Shoes", "Lingerie"],
      datasets: [{
          backgroundColor: ['#fc4a1a', '#4776e6', '#ee0979', '#42e695'],
          data: [25, 80, 25, 25]
      }]
  }, {
      maintainAspectRatio: false,
      cutout: 82,
      plugins: { legend: { display: false }}
  });

  // **Chart 3 - Línea**
  createChart("chart3", 'line', {
      labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
      datasets: [{
          label: 'Facebook',
          data: [5, 30, 16, 23, 8, 14, 2],
          borderColor: '#00b09b'
      }]
  }, {
      maintainAspectRatio: false,
      plugins: { legend: { display: false }},
      scales: { y: { beginAtZero: true }}
  });

  // **Chart 4 - Pie**
  createChart("chart4", 'pie', {
      labels: ["Completed", "Pending", "Process"],
      datasets: [{
          backgroundColor: ['#ee0979', '#283c86', '#7f00ff'],
          data: [50, 50, 50]
      }]
  }, {
      maintainAspectRatio: false,
      cutout: 95,
      plugins: { legend: { display: false }}
  });

  // **Chart 5 - Barras**
  createChart("chart5", 'bar', {
      labels: [1, 2, 3, 4, 5],
      datasets: [{
          label: 'Clothing',
          data: [40, 30, 60, 35, 60],
          backgroundColor: '#f54ea2'
      }, {
          label: 'Electronic',
          data: [50, 60, 40, 70, 35],
          backgroundColor: '#42e695'
      }]
  }, {
      maintainAspectRatio: false,
      barPercentage: 0.5,
      categoryPercentage: 0.8,
      plugins: { legend: { display: false }},
      scales: { y: { beginAtZero: true }}
  });

});
