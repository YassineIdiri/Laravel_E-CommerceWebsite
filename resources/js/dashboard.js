
// ---------- CHARTS ----------
document.addEventListener("DOMContentLoaded", function () {
  const chartDataElement = document.getElementById("chartData");

  if (chartDataElement) {
    const seriesData = JSON.parse(chartDataElement.getAttribute("data-series"));
    const categoriesData = JSON.parse(chartDataElement.getAttribute("data-categories"));

    // BAR CHART
    var barChartOptions = {
      series: [{
        data: seriesData
      }],
      chart: {
        type: 'bar',
        height: 350,
        toolbar: {
          show: false
        },
      },
      colors: [
        "#246dec",
        "#cc3c43",
        "#367952",
        "#f5b74f",
        "#4f35a1"
      ],
      plotOptions: {
        bar: {
          distributed: true,
          borderRadius: 4,
          horizontal: false,
          columnWidth: '40%',
        }
      },
      dataLabels: {
        enabled: false
      },
      legend: {
        show: false
      },
      xaxis: {
        categories: categoriesData,
      },
      yaxis: {
        floating: false, // Rendre les nombres fixes sur l'axe des ordonnées
        min: 0, // Valeur minimale de l'axe des ordonnées
        max: 40, // Valeur maximale de l'axe des ordonnées
        tickAmount: 6, // Nombre de ticks sur l'axe des ordonnées (y-axis)
        labels: {
          formatter: function (value) {
            // Formater les valeurs d'affichage selon vos besoins
            return value.toFixed(0); // Par exemple, arrondir les valeurs à l'entier le plus proche
          }
        }
      }
    };

    var barChart = new ApexCharts(document.querySelector("#bar-chart"), barChartOptions);
    barChart.render();
  }

// AREA CHART
 const areaChartDataElement = document.getElementById("areaChartData"); // Utilisez une nouvelle variable pour l'AREA CHART
 if (areaChartDataElement) { 
   const values = JSON.parse(areaChartDataElement.getAttribute("data-values"));
   var areaChartOptions = {
  series: [{
    name: 'Purchase Orders',
    data: values
  }],
  chart: {
    height: 350,
    type: 'area',
    toolbar: {
      show: false,
    },
  },
  colors: ["#4f35a1"],
  dataLabels: {
    enabled: false,
  },
  stroke: {
    curve: 'smooth'
  },
  labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
  
  markers: {
    size: 0
  },
  yaxis: {
    floating: false, // Rendre les nombres fixes sur l'axe des ordonnées
    min: 0, // Valeur minimale de l'axe des ordonnées
    max: 100, // Valeur maximale de l'axe des ordonnées
    tickAmount: 6, // Nombre de ticks sur l'axe des ordonnées (y-axis)
    labels: {
      formatter: function (value) {
        // Formater les valeurs d'affichage selon vos besoins
        return value.toFixed(0); // Par exemple, arrondir les valeurs à l'entier le plus proche
      }
     }
  },
  tooltip: {
    shared: true,
    intersect: false,
  }
};

var areaChart = new ApexCharts(document.querySelector("#area-chart"), areaChartOptions);
areaChart.render();
}

// AREA CHART
const areaChartDataV = document.getElementById("areaChartDataV"); // Utilisez une nouvelle variable pour l'AREA CHART
if (areaChartDataV) {
  const valuesV = JSON.parse(areaChartDataV.getAttribute("data-visitors"));
  console.log(valuesV)
  
var areaChartOptions2 = {
 series: [{
   name: 'Visitors',
   data: valuesV
 }],
 chart: {
   height: 350,
   type: 'area',
   toolbar: {
     show: false,
   },
 },
 colors: ["#4f35a1"],
 dataLabels: {
   enabled: false,
 },
 stroke: {
   curve: 'smooth'
 },
 labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],

 markers: {
   size: 0
 },
 yaxis: {
   floating: false, // Rendre les nombres fixes sur l'axe des ordonnées
   min: 0, // Valeur minimale de l'axe des ordonnées
   max: 100, // Valeur maximale de l'axe des ordonnées
   tickAmount: 6, // Nombre de ticks sur l'axe des ordonnées (y-axis)
   labels: {
     formatter: function (value) {
       // Formater les valeurs d'affichage selon vos besoins
       return value.toFixed(0); // Par exemple, arrondir les valeurs à l'entier le plus proche
     }
   }
 },
 tooltip: {
   shared: true,
   intersect: false,
 }
};

var areaChart2 = new ApexCharts(document.querySelector("#area-chartV"), areaChartOptions2);
areaChart2.render();

}
});



