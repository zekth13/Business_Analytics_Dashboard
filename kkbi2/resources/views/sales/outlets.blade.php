@extends('layouts.admin')

<head>
    <title>Outlet Sales</title>
</head>
@section('main-content')

<!-- Page Heading -->

<h1 class="h3 mb-4 text-gray-800">{{ __('Outlet Sales') }}</h1>

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

    <div class="col-lg-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Please Choose:</h6>
            </div>
            <div class="card-body">
                <label class="m-1"><strong>Year:</strong></label>
                <select name="yeardropdown2" id="yeardropdown2">
                    <option value="" disabled selected hidden>2015</option>
                </select>
                <label class="m-1"><strong>Month:</strong></label>
                <select name="monthdropdown2" id="monthdropdown2">
                    <option value="" selected hidden>All</option>
                    <option value="">All</option>
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">Jun</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                <label class="m-1"><strong>State:</strong></label>
                <select class="form-select" name="StateList" id="StateList">
                    <option value="" selected hidden>Selangor</option>
                    <option value="MELAKA">Melaka</option>
                    <option value="NEGERI SEMBILAN">Negeri Sembilan</option>
                    <option value="SARAWAK">Sarawak</option>
                    <option value="SELANGOR">Selangor</option>
                    <option value="WILAYAH PERSEKUTUAN">Wilayah Persekutuan</option>
                </select>
                <container class="pl-2">
                    <button type="button" class="btn btn-primary btn-sm" id="loadbutton2">Load</button>
                </container>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-sm-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Number of Outlets by State</h6>
            </div>
            <div class="card-body">
                <canvas id="totalOutlets"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-sm-6">
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow mb-2">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Total Sales (RM) by State Yearly</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="stateSalesYearly" height="123px"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card shadow mb-2">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Total Sales (RM) by State Monthly</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="stateSalesMonthly" height="123px"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-md-12 col-sm-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Outlet Total Sales By Month</h6>
            </div>
            <div class="card-body">
                <div>
                    <table id="outletSalesTable" class="display">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>STR_NO</th>
                                <th>STR_NA</th>
                                <th>OUTLET_NO</th>
                                <th>AREA_NO</th>
                                <th>AREA_NA</th>
                                <th>TOTAL SALES</th>
                            </tr>
                        </thead>
                        <tbody id="tableData">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection

@section('script')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.4.0/js/dataTables.select.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js" integrity="sha512-R/QOHLpV1Ggq22vfDAWYOaMd5RopHrJNMxi8/lJu8Oihwi4Ho4BRFeiMiCefn9rasajKjnx9/fTQ/xkWnkDACg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://unpkg.com/chart.js-plugin-labels-dv/dist/chartjs-plugin-labels.min.js"></script>
<link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" />
<link href="https://cdn.datatables.net/select/1.4.0/css/select.dataTables.css" rel="stylesheet" />

<style>
    #TotalOfOutletsAndSalesTable,
    #outletSalesTable {
        font-size: 14px;
    }
</style>

<script>
    $(document).ready(function() {
        $('#outletSalesTable').DataTable();
        $('#TotalOfOutletsAndSalesTable').DataTable();

        let currentYear = new Date().getFullYear();
        let startYear = 2014;
        let yearDropdown2 = document.getElementById('yeardropdown2');
        for (let i = 0; i <= currentYear - startYear; i++) {
            let dateOption = document.createElement('option');
            dateOption.text = currentYear - i;
            dateOption.value = currentYear - i;
            yearDropdown2.add(dateOption);
        }

        $(document).ready(function() {

            $('#loadbutton2').click(function() {
                event.preventDefault();
                var selectedYear = $("#yeardropdown2").val();
                var selectedMonth = $("#monthdropdown2").val();
                var selectedState = $("#StateList").val();
                console.log(selectedYear)
                // AJAX to get total sales of outlet monthly
                $.ajax({
                    type: "GET",
                    url: "{{ route('outlet-total-Sales') }}",
                    data: {
                        year: selectedYear,
                        month: selectedMonth,
                        state: selectedState
                    },
                    datatype: 'text',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        // alert(result);
                        var outletCode = result[0];
                        var outlet = result[1];
                        var outletNum = result[2];
                        var areaCode = result[3];
                        var area = result[4];
                        var totalSales = result[5];

                        //update table
                        var newTableData = [];
                        for (var i = 0; i < outletCode.length; i++) { //table: row i, column 0-6
                            newTableData[i] = [];
                            newTableData[i][0] = (i + 1).toString();
                            newTableData[i][1] = outletCode[i];
                            newTableData[i][2] = outlet[i];
                            newTableData[i][3] = outletNum[i];
                            newTableData[i][4] = areaCode[i];
                            newTableData[i][5] = area[i];
                            newTableData[i][6] = totalSales[i];
                        }
                        // console.log(newTableData);
                        $('#outletSalesTable').DataTable().clear().rows.add(newTableData).draw();
                    },
                    error: function(result) {
                        alert('Table fail');
                    }
                });

                // AJAX to get total sales of states yearly

                $.ajax({
                    type: "GET",
                    url: "{{ route('outlet-sales-by-state-yearly') }}",
                    data: {
                        state: selectedState
                    },
                    datatype: 'text',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        var yearData = result[0];
                        var stateSalesData = result[1].map(Number);
                        //update chart
                        // setup
                        const data = {
                            labels: yearData,
                            datasets: [{
                                label: 'Sales (RM)',
                                data: stateSalesData,
                                backgroundColor: [
                                    'rgba(102,66,0,0.4)',
                                    'rgba(153,99,0,0.4)',
                                    'rgba(204,133,0,0.4)',
                                    'rgba(255,171,15,0.4)',
                                    'rgba(255,198,92,0.4)'
                                ],
                                borderColor: [
                                    'rgba(102,66,0,1)',
                                    'rgba(153,99,0,1)',
                                    'rgba(204,133,0,1)',
                                    'rgba(255,171,15,1)',
                                    'rgba(255,198,92,1)'
                                ],
                                borderWidth: 1
                            }]
                        };
                        outletSalesByState.data = data;
                        outletSalesByState.update();
                    },
                    error: function(result) {
                        alert('outlet-sales-by-state-yearly fail');
                    }
                });


                // AJAX to get total sales of states monthly


                $.ajax({
                    type: "GET",
                    url: "{{ route('outlet-sales-by-state-monthly') }}",
                    data: {
                        year: selectedYear,
                        states: selectedState
                    },
                    datatype: 'text',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        var month = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                        var stateSalesData = result[1].map(Number);
                        //update chart
                        // setup
                        const data = {
                            labels: month,
                            datasets: [{
                                label: 'Sales (RM)',
                                data: stateSalesData,
                                backgroundColor: [
                                    'rgba(102,66,0,0.4)',
                                    'rgba(153,99,0,0.4)',
                                    'rgba(204,133,0,0.4)',
                                    'rgba(255,171,15,0.4)',
                                    'rgba(255,198,92,0.4)'
                                ],
                                borderColor: [
                                    'rgba(102,66,0,1)',
                                    'rgba(153,99,0,1)',
                                    'rgba(204,133,0,1)',
                                    'rgba(255,171,15,1)',
                                    'rgba(255,198,92,1)'
                                ],
                                borderWidth: 1
                            }]
                        };
                        outletSalesByStateMonthly.data = data;
                        outletSalesByStateMonthly.update();
                    },
                    error: function(result) {
                        alert('outlet-sales-by-state-monthly-fail');
                    }
                });
            });
        })
    });

    //Outlet Sales (RM) by State Yearly
    //data
    const data = {
        labels: <?php echo json_encode($index['outletTotalSalesByStateYearlyData'][0]) ?>,
        datasets: [{
            label: 'Sales (RM)',
            data: [0],
            backgroundColor: [
                'rgba(122, 80, 0, 0.4)',
                'rgba(153, 99, 0, 0.4)',
                'rgba(184, 119, 0, 0.4)',
                'rgba(214, 139, 0, 0.4)',
                'rgba(245, 159, 0, 0.4)',
                'rgba(255, 182, 46, 0.4)',
                'rgba(255, 198, 92, 0.4)',
                'rgba(255, 214, 138, 0.4)',
                'rgba(255, 230, 184, 0.4)',
                'rgba(255, 246, 230, 0.4)'
            ],
            borderColor: [
                'rgba(122, 80, 0, 1)',
                'rgba(153, 99, 0, 1)',
                'rgba(184, 119, 0, 1)',
                'rgba(214, 139, 0, 1)',
                'rgba(245, 159, 0, 1)',
                'rgba(255, 182, 46, 1)',
                'rgba(255, 198, 92, 1',
                'rgba(255, 214, 138, 1)',
                'rgba(255, 230, 184, 1)',
                'rgba(255, 246, 230, 1)'
            ],
            borderWidth: 1,
            barPercentage: 0.5,
            categoryPercentage: 0.5
        }]
    };

    //Outlet Sales (RM) by State Yearly
    //Config
    const config = {
        type: 'bar',
        data: data,
        options: {


        },
    };
    const data1 = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Sales (RM)',
            data: [0],
            backgroundColor: [
                'rgba(122, 80, 0, 0.4)',
                'rgba(153, 99, 0, 0.4)',
                'rgba(184, 119, 0, 0.4)',
                'rgba(214, 139, 0, 0.4)',
                'rgba(245, 159, 0, 0.4)',
                'rgba(255, 182, 46, 0.4)',
                'rgba(255, 198, 92, 0.4)',
                'rgba(255, 214, 138, 0.4)',
                'rgba(255, 230, 184, 0.4)',
                'rgba(255, 246, 230, 0.4)'
            ],
            borderColor: [
                'rgba(122, 80, 0, 1)',
                'rgba(153, 99, 0, 1)',
                'rgba(184, 119, 0, 1)',
                'rgba(214, 139, 0, 1)',
                'rgba(245, 159, 0, 1)',
                'rgba(255, 182, 46, 1)',
                'rgba(255, 198, 92, 1',
                'rgba(255, 214, 138, 1)',
                'rgba(255, 230, 184, 1)',
                'rgba(255, 246, 230, 1)'
            ],
            borderWidth: 1
        }]
    };
    // config
    const config1 = {
        type: 'bar',
        data: data1,
        options: {

        },
    };
    const data2 = {
        labels: <?php echo json_encode($index['TotalOutletsByStateData'][1]) ?>,
        datasets: [{
            data: <?php echo json_encode($index['TotalOutletsByStateData'][0]) ?>,
            backgroundColor: [
                'rgba(122, 80, 0, 0.4)',
                'rgba(153, 99, 0, 0.4)',
                'rgba(184, 119, 0, 0.4)',
                'rgba(214, 139, 0, 0.4)',
                'rgba(245, 159, 0, 0.4)',
                'rgba(255, 182, 46, 0.4)',
                'rgba(255, 198, 92, 0.4)',
                'rgba(255, 214, 138, 0.4)',
                'rgba(255, 230, 184, 0.4)',
                'rgba(255, 246, 230, 0.4)'
            ],
            borderColor: [
                'rgba(122, 80, 0, 1)',
                'rgba(153, 99, 0, 1)',
                'rgba(184, 119, 0, 1)',
                'rgba(214, 139, 0, 1)',
                'rgba(245, 159, 0, 1)',
                'rgba(255, 182, 46, 1)',
                'rgba(255, 198, 92, 1',
                'rgba(255, 214, 138, 1)',
                'rgba(255, 230, 184, 1)',
                'rgba(255, 246, 230, 1)'
            ],
            borderWidth: 1
        }]
    };
    // config
    const config2 = {
        type: 'doughnut',
        data: data2,
        options: {

            layout: {
                padding: 40
            },
            plugins: {
                legend: {
                    display: false
                },
                labels: {
                    render: 'label',
                    fontColor: data.datasets[0].borderColor,
                    fontStyle: 'bolder',
                    position: 'outside',
                    textMargin: 10,
                }
            }
        },
    };
    // render
    const outletSalesByState = new Chart(
        document.getElementById('stateSalesYearly'),
        config
    );
    const outletSalesByStateMonthly = new Chart(
        document.getElementById('stateSalesMonthly'),
        config1
    );
    const totalOutlets = new Chart(
        document.getElementById('totalOutlets'),
        config2
    );
</script>

@endsection