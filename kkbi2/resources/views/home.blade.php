@extends('layouts.admin')

<head>
    <title>Dashboard</title>
</head>
@section('main-content')

<!-- Page Heading -->
<h1 class="d-flex h3 mb-4 text-gray-800">{{ __('Dashboard') }}</h1>

@if (session('success'))
<div class="alert alert-success border-left-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if (session('status'))
<div class="alert alert-success border-left-success" role="alert">
    {{ session('status') }}
</div>
@endif

<div class="row">


    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-md font-weight-bold text-success text-uppercase mb-2">{{ __('Annual Sales') }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ "RM ".number_format($widget["annual_sales"], 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-md font-weight-bold text-success text-uppercase mb-2">{{ __('Previous Month Sales') }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ "RM ".number_format($widget["monthly_sales"], 2) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-md font-weight-bold text-info text-uppercase mb-2">{{ __('Annual Product Quantity') }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($widget["annual_quantity"]) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-md font-weight-bold text-warning text-uppercase mb-2">{{ __('Previous Month Product Quantity') }}</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($widget["monthly_quantity"]) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Column + Column -->
<div class="row">

    <!-- Content Column + Chart + Projects (%) + Color System -->
    <div class="col-lg-6">

        <div class="row">
            <div class="col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h6 class="m-0 text-md font-weight-bold text-primary">Monthly Sales Chart</h6>
                    </div>
                    <div class="card-body">
                        <div>
                            <canvas id="MonthlySalesChart" height="117px"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mb-4">
                <div class="card shadow">
                    <div class="card-header">
                        <h6 class="m-0 text-md font-weight-bold text-primary">Top 10 Product Department Sales</h6>
                    </div>
                    <div class="card-body">
                        <div>
                            <canvas id="TopDepartmentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- Top State Charts -->

    <div class="col-lg-6">

        <!-- Illustrations + Chart / CardShadow -->
        <div class="card shadow">
            <div class="card-header">
                <h6 class="m-0  text-md font-weight-bold text-primary">Top 5 State Sales</h6>
            </div>
            <div class="card-body">
                <div>
                    <canvas id="TopStateChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js" integrity="sha512-R/QOHLpV1Ggq22vfDAWYOaMd5RopHrJNMxi8/lJu8Oihwi4Ho4BRFeiMiCefn9rasajKjnx9/fTQ/xkWnkDACg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://unpkg.com/chart.js-plugin-labels-dv/dist/chartjs-plugin-labels.min.js"></script>
<script>
    const data = {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [{
            type: 'bar',
            label: 'Total Sales',
            data: <?php echo json_encode($widget['whole_year']); ?>,
            backgroundColor: 'orange',
            borderColor: 'orange',
            borderWidth: 1
        }, {
            type: 'line',
            label: 'Total Sales',
            data: <?php echo json_encode($widget['whole_year']); ?>,
            backgroundColor: 'black',
            borderColor: 'black',
            borderWidth: 2
        }]
    };
    const HorData = {
        labels: <?php echo json_encode(($widget['top_department'][0]))
                ?>,
        datasets: [{
            data: <?php echo json_encode(($widget['top_department'][1]))
                    ?>,
            backgroundColor: 'orange',
            borderColor: 'orange',
            borderWidth: 1
        }]
    };
    const DogData = {
        labels: <?php echo json_encode(($widget['top_state'][0])) ?>,
        datasets: [{
            label: 'My First Dataset',
            data: <?php echo json_encode(($widget['top_state'][1])) ?>,
            backgroundColor: ['#feac5e', '#ba8b02', '#d1913c', '#ffd194'],
            hoverOffset: 4
        }]
    };
    const option = {
        // indexAxis: 'y',
        plugins: {
            legend: {
                display: false,

            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    };
    const HorOption = {
        plugins: {
            legend: {
                display: false,
            }
        },
        indexAxis: 'y',
        scales: {
            y: {
                beginAtZero: true
            }
        }
    };

    const Config = {
        type: 'scatter',
        data: data,
        options: option
    };
    const HorConfig = {
        type: 'bar',
        data: HorData,
        options: HorOption
    };
    const DogConfig = {
        type: 'doughnut',
        data: DogData,
        options: {
            layout: {
                padding: 60
            },
            plugins: {
                labels: {
                    render: 'percentage',
                    fontStyle: 'bold',
                    fontColor: DogData.datasets[0].backgroundColor,
                    position: 'outside',
                    textMargin: 5,
                },

            }
        },
    };
    const VerChart = new Chart(document.getElementById('MonthlySalesChart'), Config);
</script>
<script>
    const HorChart = new Chart(document.getElementById('TopDepartmentChart'), HorConfig);
</script>
<script>
    const DogChart = new Chart(document.getElementById('TopStateChart'), DogConfig);
</script>
@endsection