@extends('layout.base')

@section('styleCSS')
   <title>Dashboard</title>
   <link href="/assets/css/dashboard.css" rel="stylesheet">
@endsection

@section('Page') 
<div class="grid-container">
    <main class="main-container">
      <div class="main-cards">

        <div class="card">
          <div class="card-inner">
            <p class="text-primary">PRODUCTS DAY</p>
            <i class='bi bi-clipboard-data text-blue'></i>
          </div>
          <span class="text-primary font-weight-bold">{{$articles}}</span>
        </div>

        <div class="card">
          <div class="card-inner">
            <p class="text-primary">ORDERS DAY</p>
            <i class='bi bi-basket text-orange'></i>
          </div>
          <span class="text-primary font-weight-bold">{{$orders}}</span>
        </div>

        <div class="card">
          <div class="card-inner">
            <p class="text-primary">SALES DAY</p>
            <i class='bi bi-cart3 text-green'></i>
          </div>
          <span class="text-primary font-weight-bold">{{$sales}} €</span>
        </div>

        <div class="card">
          <div class="card-inner">
            <p class="text-primary">CUSTOMERS</p>
            <i class='bi bi-activity text-violet'></i>
          </div>
          <span class="text-primary font-weight-bold">{{$customers}}</span>
        </div>

        <div class="card">
          <div class="card-inner">
            <p class="text-primary">REPORTS DAY</p>
            <i class='bi bi-flag text-red'></i>
          </div>
          <span class="text-primary font-weight-bold">{{$reports}}</span>
        </div>

      </div>

      <div class="charts">

        <div class="charts-card">
          <p class="chart-title">Visitors Count this Week</p>
          <div id="area-chartV"></div>
        </div>

        <div class="charts-card">
          <p class="chart-title">Top 4 Order Categories</p>
          <div id="bar-chart"></div>
        </div>

        <div class="charts-card">
          <p class="chart-title">Order Count this Week</p>
          <div id="area-chart"></div>
        </div>

      </div>
    </main>
  </div>


  <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.35.3/apexcharts.min.js"></script>

  <div id="areaChartDataV" data-visitors="{{ json_encode($visitors) }}"></div>
  <div id="chartData" data-series="{{ json_encode($topSales) }}" data-categories="{{ json_encode($topCategories) }}"></div>
  <div id="areaChartData" data-values="{{ json_encode($orderCounts) }}"></div>

  <script src="/assets/js/dashboard.js"></script>
@endsection