@extends('layouts.admin')

<head>
    <title>Supplier Product Sales</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" />
</head>
@section('main-content')

<!-- Page Heading -->

<h1 class="h3 mb-4 text-gray-800">{{ __('Supplier Product Sales') }}</h1>

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

    <div class="col-lg-5 col-sm-5">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">All Suppliers List</h6>
                <label class="m-1"><strong>State:</strong></label>
                <select name="statedropdown" id="statedropdown">
                    <option value="" disabled selected hidden>All</option>
                    <option value="*">All</option>
                    <option value="NULL">Not Applicable</option>
                    <option value="JOHOR">Johor</option>
                    <option value="KEDAH">Kedah</option>
                    <option value="KELANTAN">Kelantan</option>
                    <option value="MELAKA">Melaka</option>
                    <option value="NEGERI SEMBILAN">Negeri Sembilan</option>
                    <option value="PAHANG">Pahang</option>
                    <option value="PENANG">Penang</option>
                    <option value="PERAK">Perak</option>
                    <option value="SABAH">Sabah</option>
                    <option value="SARAWAK">Sarawak</option>
                    <option value="SELANGOR">Selangor</option>
                    <option value="TERENGGANU">Terengganu</option>
                    <option value="WILAYAH PERSEKUTUAN ">Wilayah Persekutuan</option>
                </select>
            </div>
            <table id="all-suppliers" class="display">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Supplier No.</th>
                        <th>Supplier Name</th>
                    </tr>
                </thead>
                <tbody id="table_data">
                    <?php
                    $supplierNoData = $charts['supplierNameData'][0];
                    $supplierNameData = $charts['supplierNameData'][1];
                    $numberOfRows = count($supplierNameData);
                    for ($i = 0; $i < $numberOfRows; $i++) {
                        echo '<tr>
                            <td>' . ($i + 1) . '</td>
                            <td>' . $supplierNoData[$i] . '</td>
                            <td>' . $supplierNameData[$i] . '</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-lg-7 col-sm-7">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Top 10 Suppliers</h6>
                <label class="m-1"><strong>Year:</strong></label>
                <select name="yeardropdown" id="yeardropdown">
                    <option value="" disabled selected hidden>2020</option>
                </select>
                <label class="m-1"><strong>Month:</strong></label>
                <select name="monthdropdown" id="monthdropdown">
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
                    <option value="9">October</option>
                    <option value="9">November</option>
                    <option value="9">December</option>
                </select>
                <label class="m-1"><strong>State:</strong></label>
                <select name="statedropdown2" id="statedropdown2">
                    <option value="" disabled selected hidden>All</option>
                    <option value="*">All</option>
                    <option value="NULL">Not Applicable</option>
                    <option value="JOHOR">Johor</option>
                    <option value="KEDAH">Kedah</option>
                    <option value="KELANTAN">Kelantan</option>
                    <option value="MELAKA">Melaka</option>
                    <option value="NEGERI SEMBILAN">Negeri Sembilan</option>
                    <option value="PAHANG">Pahang</option>
                    <option value="PENANG">Penang</option>
                    <option value="PERAK">Perak</option>
                    <option value="SABAH">Sabah</option>
                    <option value="SARAWAK">Sarawak</option>
                    <option value="SELANGOR">Selangor</option>
                    <option value="TERENGGANU">Terengganu</option>
                    <option value="WILAYAH PERSEKUTUAN">Wilayah Persekutuan</option>
                </select>
                <container class="pl-2">
                    <button type="button" class="btn btn-primary btn-sm" id="loadbutton">Load</button>
                </container>
            </div>
            <canvas id="top20SupplierChart" height="515px"></canvas>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-sm-12">
        <div class="card shadow mb-4">

            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Suppliers' Products</h6>
                <label class="m-1"><strong>Supplier:</strong></label>
                <select name="supplierdropdown" id="supplierdropdown" class="form-select">
                    <option value="" disabled selected hidden>{SupplierName} {SupplierNo}---{State}</option>
                    <option value="" selected>All</option>
                    <?php   //fetch dropdown value from database (in controller)
                    $allSupplierNoData = $charts['supplierNoAndNameData'][0];
                    $allSupplierNameData = $charts['supplierNoAndNameData'][1];
                    $allSupplierStateData = $charts['supplierNoAndNameData'][2];
                    for ($i = 0; $i < count($allSupplierNoData); $i++) {
                        echo '<option value="' . $allSupplierNoData[$i] . '">' . $allSupplierNameData[$i] . "--(" . $allSupplierNoData[$i] . ")--(" . $allSupplierStateData[$i] . ")" . '</option>';
                    }
                    ?>
                </select>
                <label class="m-1"><strong>Year:</strong></label>
                <select name="yeardropdown2" id="yeardropdown2">
                    <option value="" disabled selected hidden>2020</option>
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
                    <option value="9">October</option>
                    <option value="9">November</option>
                    <option value="9">December</option>
                </select>
                <container class="pl-2">
                    <button type="button" class="btn btn-primary btn-sm" id="loadbutton2">Load</button>
                </container>
            </div>
            <table id="supplier-product-sales" class="display">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Product No</th>
                        <th>Product Name</th>
                        <th>Sales Quantity</th>
                        <th>Sales (RM)</th>
                    </tr>
                </thead>
                <tbody id="table2_data">
                    <?php
                    $productNoData = $charts['supplierProductSalesData'][0];
                    $productNameData = $charts['supplierProductSalesData'][1];
                    $salesQuantityData = $charts['supplierProductSalesData'][2];
                    $totalSalesData = $charts['supplierProductSalesData'][3];
                    $numberOfRows2 = count($productNoData);

                    for ($i = 0; $i < $numberOfRows2; $i++) {
                        echo '<tr>
                            <td>' . ($i + 1) . '</td>
                            <td>' . $productNoData[$i] . '</td>
                            <td>' . $productNameData[$i] . '</td>
                            <td>' . $salesQuantityData[$i] . '</td>
                            <td>' . $totalSalesData[$i] . '</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

<script>
    let currentYear = new Date().getFullYear();
    let startYear = 2014;
    let yearDropdown = document.getElementById('yeardropdown');
    for (let i = 0; i <= currentYear - startYear; i++) {
        let dateOption = document.createElement('option');
        dateOption.text = currentYear - i;
        dateOption.value = currentYear - i;
        yearDropdown.add(dateOption);
    }
    let yearDropdown2 = document.getElementById('yeardropdown2');
    for (let i = 0; i <= currentYear - startYear; i++) {
        let dateOption = document.createElement('option');
        dateOption.text = currentYear - i;
        dateOption.value = currentYear - i;
        yearDropdown2.add(dateOption);
    }

    $(document).ready(function() {
        $('#supplierdropdown').select2();

        $('#all-suppliers').DataTable({
            bLengthChange: false,
            autoWidth: false,
            scrollY: 300,
            columnDefs: [{
                    "width": "5%",
                    "targets": 0
                },
                {
                    "width": "5%",
                    "targets": 1
                },
                {
                    "width": "90%",
                    "targets": 2
                }
            ]
        });

        $('#supplier-product-sales').DataTable({
            bLengthChange: false,
            autoWidth: false,
            columnDefs: [{
                    "width": "5%",
                    "targets": 0
                },
                {
                    "width": "5%",
                    "targets": 1
                },
                {
                    "width": "80%",
                    "targets": 2
                },
                {
                    "width": "5%",
                    "targets": 3
                },
                {
                    "width": "5%",
                    "targets": 4
                }
            ]
        });

        $('#statedropdown').change(function() {
            event.preventDefault();
            var selectedState = $(this).val();
            $.ajax({
                type: "GET",
                url: "{{ route('supplier-name-no') }}",
                data: {
                    state: selectedState
                },
                datatype: 'text',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    // alert("Result: " + result);

                    var newSupplierNameData = result[0];
                    var newSupplierNumberData = result[1];
                    var newNumberOfRows = newSupplierNameData.length;

                    //update table
                    var newTableData = [];
                    for (var i = 0; i < newNumberOfRows; i++) { //table: row i, column 0-3
                        newTableData[i] = [];
                        newTableData[i][0] = (i + 1).toString();
                        newTableData[i][1] = newSupplierNameData[i];
                        newTableData[i][2] = newSupplierNumberData[i];
                    }
                    // console.log(newTableData);
                    $('#all-suppliers').DataTable().clear().rows.add(newTableData).draw();

                },
                error: function(result) {
                    console.log(result);
                }
            });
        });

        //TOP 10 SUPPLIERS CHART AJAX
        $('#loadbutton').click(function() {
            event.preventDefault();
            var selectedYear = $('#yeardropdown').val();
            var selectedMonth = $('#monthdropdown').val();
            var selectedState = $('#statedropdown2').val();
            console.log(selectedYear, selectedState);
            $.ajax({
                type: "GET",
                url: "{{ route('top10-supplier') }}",
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
                    // alert("Result: " + result);
                    var newSupplierNameData = result[0];
                    var newSupplierSalesData = result[1];

                    //update chart
                    //Setup
                    const labels = newSupplierNameData;
                    const data = {
                        labels: labels,
                        datasets: [{
                            axis: 'y',
                            label: 'Total Sales (RM)',
                            data: newSupplierSalesData,
                            fill: false,
                            backgroundColor: [
                                'rgba(255, 165, 0,0.4)'
                            ],
                            borderColor: [
                                'rgba(255, 165, 0,1)'
                            ],
                            borderWidth: 1
                        }]
                    };
                    top20SupplierChart.data = data;
                    top20SupplierChart.update();

                },
                error: function(result) {
                    console.log("FAIL: " + result);
                }
            });
        });

        //SUPLLIER PRODUCT SALES TABLE AJAX
        $('#loadbutton2').click(function() {
            event.preventDefault();
            var selectedSupplier = $('#supplierdropdown').val();
            console.log(selectedSupplier);
            var selectedYear = $('#yeardropdown2').val();
            var selectedMonth = $('#monthdropdown2').val();

            $.ajax({
                type: "GET",
                url: "{{ route('supplier-product-sales') }}",
                data: {
                    supplierName: selectedSupplier,
                    year: selectedYear,
                    month: selectedMonth
                },
                datatype: 'text',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    // alert("Result: " + result);
                    var newProductNoData = result[0];
                    var newProductNameData = result[1];
                    var newSalesQtyData = result[2];
                    var newProductSalesData = result[3];
                    var newNumberOfRows2 = newProductNoData.length;

                    //update table
                    var newTableData = [];
                    for (var i = 0; i < newNumberOfRows2; i++) { //table: row i, column 0-4
                        newTableData[i] = [];
                        newTableData[i][0] = (i + 1).toString();
                        newTableData[i][1] = newProductNoData[i];
                        newTableData[i][2] = newProductNameData[i];
                        newTableData[i][3] = newSalesQtyData[i];
                        newTableData[i][4] = newProductSalesData[i];
                    }
                    $('#supplier-product-sales').DataTable().clear().rows.add(newTableData).draw();
                },
                error: function(result) {
                    console.log(result);
                }
            });
        });

    });

    //TOP 10 SUPPLIERS CHART
    //Setup
    const labels = <?php echo json_encode($charts['top20SupplierData'][0]); ?> //supplier name data
    const data = {
        labels: labels,
        datasets: [{
            axis: 'y',
            label: 'Total Sales',
            data: <?php echo json_encode($charts['top20SupplierData'][1]); ?>, //supplier sales data
            fill: false,
            backgroundColor: [
                'rgba(255, 165, 0,0.4)'
            ],
            borderColor: [
                'rgba(255, 165, 0,1)'
            ],
            borderWidth: 1
        }]
    };
    //Config
    const config = {
        type: 'bar',
        data,
        options: {
            indexAxis: 'y',
        }
    };
    //Render
    const top20SupplierChart = new Chart(
        document.getElementById('top20SupplierChart'),
        config
    );
</script>

<style>
    #all-suppliers,
    #supplier-product-sales {
        font-size: 11px;
    }
</style>

@endsection