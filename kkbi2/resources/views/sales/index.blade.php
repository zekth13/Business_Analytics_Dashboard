@extends('layouts.admin')

<head>
    <title>Sales Summary</title>
</head>
@section('main-content')

<!-- Page Heading -->

<h1 class="h3 mb-4 text-gray-800">{{ __('Sales Summary') }}</h1>

<!-- @if (session('success'))
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
@endif -->

<div class="row">

    <div class="col-md-12 col-sm-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Monthly Sales and Growth</h6>
                <label class="m-1"><strong>Year:</strong></label>
                <select name="yeardropdown" id="yeardropdown">
                    <option value="" disabled selected hidden>2020</option>
                </select>
                <button class="btn btn-primary btn-sm" onclick="openpopup()">
                    Forecast
                </button>
            </div>
            <div class="row">

                <div class="col-md-6 col-sm-6">
                    <div class="card-body">
                        <div><canvas id="monthlySales"></canvas></div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6">
                    <div class="card-body">
                        <div><canvas id="monthlySalesGrowth"></canvas></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

<div class="row">

    <div class="col-md-12 col-sm-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Annual Sales and Growth</h6>
            </div>
            <div class="row">

                <div class="col-md-6 col-sm-6">
                    <div class="card-body">
                        <div><canvas id="anualSales"></canvas></div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6">
                    <div class="card-body">
                        <div><canvas id="annualSalesGrowth"></canvas></div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script type="text/javascript">
    var newwin;
    function openpopup(){
        newwin = window.open('api/forecast','About us','width = 800, height = 500');

        document.onmousedown = focusPopup;
        document.onkeyup = focusPopup;
        document.onmousemove = focusPopup;
    }
    function focusPopup(){
        if(!newwin.closed){
            newwin.focus();
        }
    }

    let currentYear = new Date().getFullYear();
    let startYear = 2014;
    let yearDropdown = document.getElementById('yeardropdown');
    for (let i = 0; i <= currentYear - startYear; i++) {
        let dateOption = document.createElement('option');
        dateOption.text = currentYear - i;
        dateOption.value = currentYear - i;
        yearDropdown.add(dateOption);
    }

    const allMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var allYears = [];
    for (let i = 2014; i <= currentYear; i++) {
        allYears.push(i);
    }

    const chartsColour ='orange';

    $(document).ready(function() {
        $('#yeardropdown').change(function() {
            event.preventDefault();
            var selectedYear = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('monthly-sales') }}",
                data: {
                    year: selectedYear
                },
                datatype: 'text',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    // alert("Monthly sales on " + selectedYear + ": " + result);
                    //Setup
                    const labels1 = allMonths
                    const data1 = {
                        labels: labels1,
                        datasets: [{
                            label: 'Sales (RM)',
                            backgroundColor: chartsColour,
                            borderColor: chartsColour,
                            data: result
                        }]
                    };
                    monthlySales.data = data1;
                    monthlySales.update();
                },
                error: function(result) {
                    console.log(result);
                }
            });

            $.ajax({
                type: "GET",
                url: "{{ route('monthly-sales-growth') }}",
                data: {
                    year: selectedYear
                },
                datatype: 'text',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    // alert("Sales growth on " + selectedYear + ": " + result);
                    //Setup
                    const labels2 = allMonths;
                    const data2 = {
                        labels: labels2,
                        datasets: [{
                            label: 'Sales Growth (%)',
                            backgroundColor: chartsColour,
                            borderColor: chartsColour,
                            data: result
                        }]
                    };
                    monthlySalesGrowth.data = data2;
                    monthlySalesGrowth.update();
                },
                error: function(result) {
                    console.log(result);
                }
            });
        });
    });

    

    //// MONTHLYSALES ////
    //Setup
    const labels1 = allMonths
    const data1 = {
        labels: labels1,
        datasets: [{
            label: 'Sales (RM)',
            backgroundColor: chartsColour,
            borderColor: chartsColour,
            data: <?php echo json_encode($charts['monthlySalesData']); ?>
        }]
    };
    //Config
    const config1 = {
        type: 'line',
        data: data1,
        options: {}
    };
    //Render
    const monthlySales = new Chart(
        document.getElementById('monthlySales'),
        config1
    );

    //// MONTHLY SALES GROWTH ////
    //Setup
    const labels2 = allMonths;
    const data2 = {
        labels: labels2,
        datasets: [{
            label: 'Sales Growth (%)',
            backgroundColor: chartsColour,
            borderColor: chartsColour,
            data: <?php echo json_encode($charts['monthlySalesGrowthData']); ?>
        }]
    };
    //Config
    const config2 = {
        type: 'line',
        data: data2,
        options: {}
    };
    //Render
    const monthlySalesGrowth = new Chart(
        document.getElementById('monthlySalesGrowth'),
        config2
    );

    //// ANUAL SALES ////
    //Setup
    const labels3 = allYears;
    const data3 = {
        labels: labels3,
        datasets: [{
            label: 'Sales (RM)',
            backgroundColor: chartsColour,
            borderColor: chartsColour,
            data: <?php echo json_encode($charts['anualSalesAndGrowthData'][0]); ?>
        }]
    };
    //Config
    const config3 = {
        type: 'bar',
        data: data3,
        options: {}
    };
    //Render
    const anualSales = new Chart(
        document.getElementById('anualSales'),
        config3
    );
    
    //// ANNUAL SALES GROWTH ////
    //Setup
    const labels4 = allYears;
    const data4 = {
        labels: labels4,
        datasets: [{
            label: 'Sales Growth (%)',
            backgroundColor: chartsColour,
            borderColor: chartsColour,
            data: <?php echo json_encode($charts['anualSalesAndGrowthData'][1]); ?>
        }]
    };
    //Config
    const config4 = {
        type: 'line',
        data: data4,
        options: {}
    };
    //Render
    const annualSalesGrowth = new Chart(
        document.getElementById('annualSalesGrowth'),
        config4
    );
</script>

@endsection