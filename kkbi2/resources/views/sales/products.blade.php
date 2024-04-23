@extends('layouts.admin')

<head>
    <title>Product Sales</title>
</head>
@section('main-content')

<!-- Page Heading -->
<h1 class="h3 mb-4 text-gray-800">{{ __('Product Sales') }}</h1>

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

<div class="col-lg-12 col-sm-12">
    <div class="card shadow mb-3">
        <div class="row">
            <div class="col-lg-4 col-sm-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Annual Sales</h6>
                    <p class="m-0 font-weight text-primary">(Select product from the table below to show)</p>
                </div>
                <div class="card-body">
                    <canvas id="annualProductSales"></canvas>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quarterly Sales</h6>
                    <label class="m-1"><strong>Year:</strong></label>
                    <select name="yeardropdown3" id="yeardropdown3">
                        <option value="2020" disabled selected hidden>2020</option>
                    </select>
                </div>
                <div class="card-body">
                    <canvas id="quarterlyProductSales"></canvas>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Monthly Sales</h6>
                    <label class="m-1"><strong>Year:</strong></label>
                    <select name="yeardropdown1" id="yeardropdown1">
                        <option value="2020" disabled selected hidden>2020</option>
                    </select>
                </div>
                <div class="card-body">
                    <canvas id="monthlyProductSales"></canvas>
                </div>
            </div>
        </div>

        <div class="card-body">
            <table id="all-product-list" class="display">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Product No.</th>
                        <th>Product Name</th>
                        <th>Supplier No.</th>
                        <th>Supplier Name</th>
                    </tr>
                </thead>
                <tbody id="table1_data">
                    <?php
                    $productNoData = $charts['allProductNameData'][0];
                    $productNameData = $charts['allProductNameData'][1];
                    $productSupplierNoData = $charts['allProductNameData'][2];
                    $productSupplierNameData = $charts['allProductNameData'][3];
                    $numberOfRows = count($productNoData);

                    for ($i = 0; $i < $numberOfRows; $i++) {
                        echo '<tr>
                                        <td>' . ($i + 1) . '</td>
                                        <td>' . $productNoData[$i] . '</td>
                                        <td>' . $productNameData[$i] . '</td>
                                        <td>' . $productSupplierNoData[$i] . '</td>
                                        <td>' . $productSupplierNameData[$i] . '</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </div>

    <div class="col-lg-12 col-sm-12">
        <div class="card shadow mb-3">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Product Total Sales by Product Name</h6>
                <label class="m-1"><strong>Year:</strong></label>
                <select name="yeardropdown" id="yeardropdown">
                    <option value="" disabled selected hidden>All</option>
                    <option value="">All</option>
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
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                <label class="m-1"><strong>Category:</strong></label>
                <select name="categorydropdown" id="categorydropdown">
                    <option value="" disabled selected hidden>All</option>
                    <option value="">All</option>
                    <?php   //fetch from DB (in controller)
                    $allProductCategoryData = $charts['allProductCategoryData'];
                    foreach ($allProductCategoryData as $val) {
                        echo '<option value="' . $val . '">' . $val .  '</option>';
                    }
                    ?>
                </select>
                <label class="m-1"><strong>Limit:</strong></label>
                <select name="limitdropdown" id="limitdropdown">
                    <option value="" disabled selected hidden>20</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                    <option value="150">150</option>
                    <option value="200">200</option>
                    <option value="300">300</option>
                    <option value="500">500</option>
                    <option value="1000">1000</option>
                    <option value="5000">5000</option>
                    <option value="10000">10000</option>
                    <option value="9999999999">All</option>
                </select>
                <container class="pl-2">
                    <button type="button" class="btn btn-primary btn-sm" id="loadbutton">Load</button>
                </container>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-6 col-sm-6">
                        <table id="product-sales" class="display">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Product No.</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Sales (RM)</th>
                                </tr>
                            </thead>
                            <tbody id="table2_data">
                                <?php
                                $productNoData = $charts['productSalesData'][0];
                                $productNameData = $charts['productSalesData'][1];
                                $productSalesQuantityData = $charts['productSalesData'][2];
                                $productSalesData = $charts['productSalesData'][3];
                                $numberOfRows = count($productNameData);

                                for ($i = 0; $i < $numberOfRows; $i++) {
                                    echo '<tr>
                                        <td>' . ($i + 1) . '</td>
                                        <td>' . $productNoData[$i] . '</td>
                                        <td>' . $productNameData[$i] . '</td>
                                        <td>' . $productSalesQuantityData[$i] . '</td>
                                        <td>' . $productSalesData[$i] . '</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6 col-sm-6">

                        <h6 class="m-0 font-weight-bold text-primary">Top 10 (Sales %)</h6>

                        <div class="chartBox">
                            <canvas id="top10ProductSalesChart"></canvas>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>

    <div class="col-lg-12 col-sm-12">
        <div class="card shadow">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Product Total Sales by Product Category</h6>
                <label class="m-1"><strong>Year:</strong></label>
                <select name="yeardropdown2" id="yeardropdown2">
                    <option value="" disabled selected hidden>All</option>
                    <option value="">All</option>
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
                <label class="m-1"><strong>Limit:</strong></label>
                <select name="limitdropdown2" id="limitdropdown2">
                    <option value="" disabled selected hidden>20</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="9999999999">All</option>
                </select>
                <container class="pl-2">
                    <button type="button" class="btn btn-primary btn-sm" id="loadbutton2">Load</button>
                </container>
            </div>
            <div class="card-body">

                <div class="row">

                    <div class="col-md-6">
                        <table id="product-category-sales" class="display">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Product Category</th>
                                    <th>Sales (RM)</th>
                                </tr>
                            </thead>
                            <tbody id="table3_data">
                                <?php
                                $productCategoryNameData = $charts['productCategorySalesData'][0];
                                $productCategorySalesData = $charts['productCategorySalesData'][1];
                                $numberOfRows2 = count($productCategoryNameData);

                                for ($i = 0; $i < $numberOfRows2; $i++) {
                                    echo '<tr>
                            <td>' . ($i + 1) . '</td>
                            <td>' . $productCategoryNameData[$i] . '</td>
                            <td>' . $productCategorySalesData[$i] . '</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6">
                        <h6 class="m-0 font-weight-bold text-primary">Top 10 (Sales %)</h6>
                        <canvas id="top10ProductCategorySales"></canvas>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @endsection

    @section('script')
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.4.0/js/dataTables.select.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-datalabels/2.0.0/chartjs-plugin-datalabels.min.js" integrity="sha512-R/QOHLpV1Ggq22vfDAWYOaMd5RopHrJNMxi8/lJu8Oihwi4Ho4BRFeiMiCefn9rasajKjnx9/fTQ/xkWnkDACg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://unpkg.com/chart.js-plugin-labels-dv/dist/chartjs-plugin-labels.min.js"></script>

    <script type="text/javascript">
        function addAllYearInDropdown(dropdownVariable) {
            let currentYear = new Date().getFullYear();
            let startYear = 2014;
            for (let i = 0; i <= currentYear - startYear; i++) {
                let dateOption = document.createElement('option');
                dateOption.text = currentYear - i;
                dateOption.value = currentYear - i;
                dropdownVariable.add(dateOption);
            }
        }
        let yearDropdown1 = document.getElementById('yeardropdown1');
        addAllYearInDropdown(yearDropdown1);
        let yearDropdown = document.getElementById('yeardropdown');
        addAllYearInDropdown(yearDropdown);
        let yearDropdown2 = document.getElementById('yeardropdown2');
        addAllYearInDropdown(yearDropdown2);
        let yearDropdown3 = document.getElementById('yeardropdown3');
        addAllYearInDropdown(yearDropdown3);

        $(document).ready(function() {
            var allProductListTable = $('#all-product-list').DataTable({
                select: true,
                scrollY: 370,
                bLengthChange: true,
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
                        "width": "40%",
                        "targets": 2
                    },
                    {
                        "width": "5%",
                        "targets": 3
                    },
                    {
                        "width": "45%",
                        "targets": 3
                    }
                ]
            });

            $('#product-sales').DataTable({
                scrollY: 370,
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
                        "width": "40%",
                        "targets": 2
                    },
                    {
                        "width": "5%",
                        "targets": 3
                    },
                    {
                        "width": "45%",
                        "targets": 3
                    }
                ]
            });

            productCategorySalesDataTable = $('#product-category-sales').DataTable({
                scrollY: 370,
                bLengthChange: false,
                autoWidth: false,
                columnDefs: [{
                        "width": "5%",
                        "targets": 0
                    },
                    {
                        "width": "75%",
                        "targets": 1
                    },
                    {
                        "width": "20%",
                        "targets": 2
                    }
                ]
            });
            allProductListTable.on('select', function(e, dt, type, indexes) { //when select the All Products List table row
                var selectedProduct = allProductListTable.rows({
                    selected: true
                }).data()[0][1];
                console.log(selectedProduct);

                //Annual Product Sales AJAX//
                $.ajax({
                    type: "GET",
                    url: "{{ route('product-annual-sales') }}",
                    data: {
                        productNo: selectedProduct
                    },
                    datatype: 'text',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        // alert("Annual sales for Product No. " + selectedProduct + ": " + result[1]);

                        //update chart
                        //Setup
                        const data = {
                            labels: result[0],
                            datasets: [{
                                label: 'Sales (RM)',
                                backgroundColor: 'orange',
                                borderColor: 'orange',
                                data: result[1]
                            }]
                        };
                        annualProductSales.data = data;
                        annualProductSales.update();

                    },
                    error: function(result) {
                        alert('Error, result: ' + result);
                    }
                });

                var selectedYear = $(yeardropdown3).val();
                //Quarterly Prouct Sales AJAX//
                $.ajax({
                    type: "GET",
                    url: "{{ route('product-quarterly-sales') }}",
                    data: {
                        productNo: selectedProduct,
                        year: selectedYear

                    },
                    datatype: 'text',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        // alert("Quarterly sales in " + selectedYear + ": " + result[1]);
                        //update chart
                        //Setup
                        const labels = ["Q1", "Q2", "Q3", "Q4"];
                        const data = {
                            labels: labels,
                            datasets: [{
                                label: 'Sales (RM)',
                                backgroundColor: 'orange',
                                borderColor: 'orange',
                                data: result[1]
                            }]
                        };
                        quarterlyProductSales.data = data;
                        quarterlyProductSales.update();
                    },
                    error: function(result) {
                        console.log(result);
                    }
                });

                //Monthly Product Sales AJAX//
                $.ajax({
                    type: "GET",
                    url: "{{ route('product-monthly-sales') }}",
                    data: {
                        productNo: selectedProduct,
                        year: selectedYear

                    },
                    datatype: 'text',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        // alert("Monthly sales in " + selectedYear + ": " + result[1]);
                        //Setup
                        const labels1 = result[0]
                        const data1 = {
                            labels: labels1,
                            datasets: [{
                                label: 'Sales (RM)',
                                backgroundColor: 'orange',
                                borderColor: 'orange',
                                data: result[1]
                            }]
                        };
                        monthlyProductSales.data = data1;
                        monthlyProductSales.update();
                    },
                    error: function(result) {
                        console.log(result);
                    }
                });
            });

            $('#yeardropdown3').change(function() {
                event.preventDefault();
                var selectedYear = $(this).val();
                var selectedProduct = allProductListTable.rows({
                    selected: true
                }).data()[0][1];
                $.ajax({
                    type: "GET",
                    url: "{{ route('product-quarterly-sales') }}",
                    data: {
                        productNo: selectedProduct,
                        year: selectedYear
                    },
                    datatype: 'text',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        // alert("Monthly sales in " + selectedYear + ": " + result[1]);
                        //Setup
                        const labels = result[0]
                        const data = {
                            labels: labels,
                            datasets: [{
                                label: 'Sales (RM)',
                                backgroundColor: 'orange',
                                borderColor: 'orange',
                                data: result[1]
                            }]
                        };
                        quarterlyProductSales.data = data;
                        quarterlyProductSales.update();
                    },
                    error: function(result) {
                        console.log(result);
                    }
                });
            });
            $('#yeardropdown1').change(function() {
                event.preventDefault();
                var selectedYear = $(this).val();
                var selectedProduct = allProductListTable.rows({
                    selected: true
                }).data()[0][1];
                $.ajax({
                    type: "GET",
                    url: "{{ route('product-monthly-sales') }}",
                    data: {
                        productNo: selectedProduct,
                        year: selectedYear
                    },
                    datatype: 'text',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        // alert("Monthly sales in " + selectedYear + ": " + result[1]);
                        //Setup
                        const labels1 = result[0]
                        const data1 = {
                            labels: labels1,
                            datasets: [{
                                label: 'Sales (RM)',
                                backgroundColor: 'orange',
                                borderColor: 'orange',
                                data: result[1]
                            }]
                        };
                        monthlyProductSales.data = data1;
                        monthlyProductSales.update();
                    },
                    error: function(result) {
                        console.log(result);
                    }
                });
            });

            $('#loadbutton').click(function() {
                event.preventDefault();
                var selectedYear = $("#yeardropdown").val();
                var selectedMonth = $("#monthdropdown").val();
                var selectedCategory = $("#categorydropdown").val();
                var selectedLimit = $("#limitdropdown").val();
                console.log(selectedYear + selectedMonth + selectedCategory + selectedLimit);

                $.ajax({
                    type: "GET",
                    url: "{{ route('product-sales') }}",
                    data: {
                        year: selectedYear,
                        month: selectedMonth,
                        category: selectedCategory,
                        limit: selectedLimit
                    },
                    datatype: 'text',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        // alert("Result: " + result);
                        var newProductNoData = result[0]
                        var newProductNameData = result[1];
                        var newProductSalesQuantityData = result[2];
                        var newProductSalesData = result[3];
                        var newProductSalesPercentageData = result[4];
                        var newNumberOfRows = newProductNoData.length;

                        //update table
                        var newTableData = [];
                        for (var i = 0; i < newNumberOfRows; i++) { //table: row i, column 0-3
                            newTableData[i] = [];
                            newTableData[i][0] = (i + 1).toString();
                            newTableData[i][1] = newProductNoData[i];
                            newTableData[i][2] = newProductNameData[i];
                            newTableData[i][3] = newProductSalesQuantityData[i];
                            newTableData[i][4] = newProductSalesData[i];
                        }
                        $('#product-sales').DataTable().clear().rows.add(newTableData).draw();

                        //update chart
                        if (newNumberOfRows > 10) {
                            newProductNameData = newProductNameData.slice(0, 10);
                            newProductSalesPercentageData = newProductSalesPercentageData.slice(0,
                                10);
                        }
                        newProductSalesPercentageData = newProductSalesPercentageData.map(
                            Number); //convert string[] to float[]
                        //Setup
                        const data = {
                            labels: newProductNameData,
                            datasets: [{
                                label: 'Sales Percentage (%)',
                                data: newProductSalesPercentageData,
                                backgroundColor: [
                                    'rgba(122,80,0,0.4)',
                                    'rgba(153,99,0,0.4)',
                                    'rgba(184,119,0,0.4)',
                                    'rgba(214,139,0,0.4)',
                                    'rgba(245,159,0,0.4)',
                                    'rgba(255,182,46,0.4)',
                                    'rgba(255,198,92,0.4)',
                                    'rgba(255,214,138,0.4)',
                                    'rgba(255,230,184,0.4)',
                                    'rgba(255,246,230,0.4)',
                                ],
                                borderColor: [
                                    'rgba(122,80,0,1)',
                                    'rgba(153,99,0,1)',
                                    'rgba(184,119,0,1)',
                                    'rgba(214,139,0,1)',
                                    'rgba(245,159,0,1)',
                                    'rgba(255,182,46,1)',
                                    'rgba(255,198,92,1)',
                                    'rgba(255,214,138,1)',
                                    'rgba(255,230,184,1)',
                                    'rgba(255,246,230,1)',
                                ]
                            }]
                        };
                        productSalesChart.data = data;
                        productSalesChart.update();
                    },
                    error: function(result) {
                        console.log(result);
                    }
                });
            });

            $('#loadbutton2').click(function() {
                // event.preventDefault();
                var selectedYear = $("#yeardropdown2").val();
                var selectedMonth = $("#monthdropdown2").val();
                var selectedLimit = $("#limitdropdown2").val();
                console.log(selectedYear + selectedMonth + selectedLimit);

                $.ajax({
                    type: "GET",
                    url: "{{ route('product-category-sales') }}",
                    data: {
                        year: selectedYear,
                        month: selectedMonth,
                        limit: selectedLimit
                    },
                    datatype: 'text',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        // alert("Result: " + result);

                        var newProductCategoryNameData = result[0];
                        var newProductCategorySalesData = result[1];
                        var newProductCategorySalesPercentageData = result[2];
                        var newNumberOfRows2 = newProductCategorySalesData.length;

                        //update table
                        var newTable2Data = [];
                        for (var i = 0; i < newNumberOfRows2; i++) { //table: row i, column 0-3
                            newTable2Data[i] = [];
                            newTable2Data[i][0] = (i + 1).toString();
                            newTable2Data[i][1] = newProductCategoryNameData[i];
                            newTable2Data[i][2] = newProductCategorySalesData[i];
                        }
                        $('#product-category-sales').DataTable().clear().rows.add(newTable2Data)
                            .draw();

                        //update chart
                        if (newNumberOfRows2 > 10) {
                            newProductCategoryNameData = newProductCategoryNameData.slice(0, 10);
                            newProductCategorySalesPercentageData =
                                newProductCategorySalesPercentageData.slice(0, 10);
                        }
                        newProductCategorySalesPercentageData =
                            newProductCategorySalesPercentageData.map(Number); //convert string[] to float[]
                        //Setup
                        const data = {
                            labels: newProductCategoryNameData,
                            datasets: [{
                                label: 'Sales Percentage (%)',
                                data: newProductCategorySalesPercentageData,
                                backgroundColor: [
                                    'rgba(122,80,0,0.4)',
                                    'rgba(153,99,0,0.4)',
                                    'rgba(184,119,0,0.4)',
                                    'rgba(214,139,0,0.4)',
                                    'rgba(245,159,0,0.4)',
                                    'rgba(255,182,46,0.4)',
                                    'rgba(255,198,92,0.4)',
                                    'rgba(255,214,138,0.4)',
                                    'rgba(255,230,184,0.4)',
                                    'rgba(255,246,230,0.4)',
                                ],
                                borderColor: [
                                    'rgba(122,80,0,1)',
                                    'rgba(153,99,0,1)',
                                    'rgba(184,119,0,1)',
                                    'rgba(214,139,0,1)',
                                    'rgba(245,159,0,1)',
                                    'rgba(255,182,46,1)',
                                    'rgba(255,198,92,1)',
                                    'rgba(255,214,138,1)',
                                    'rgba(255,230,184,1)',
                                    'rgba(255,246,230,1)',
                                ]
                            }]
                        };
                        productCategorySalesChart.data = data;
                        productCategorySalesChart.update();
                    },
                    error: function(result) {
                        console.log(result);
                    }
                });
            });

        });

        //ANNUAL PRODUCT SALES CHART//
        //Setup
        const data1 = {
            labels: "",
            datasets: [{
                label: 'Sales (RM)',
                backgroundColor: 'orange',
                borderColor: 'orange',
                data: [0]
            }]
        };
        //Config
        const config1 = {
            type: 'line',
            data: data1,
            options: {}
        };
        //Render
        const annualProductSales = new Chart(
            document.getElementById('annualProductSales'),
            config1
        );

        //QUARTERLY PRODUCT SALES CHART//
        //Setup
        const labels5 = "";
        const data5 = {
            labels: labels5,
            datasets: [{
                label: 'Sales (RM)',
                backgroundColor: 'orange',
                borderColor: 'orange',
                data: ""
            }]
        };
        //Config
        const config5 = {
            type: 'bar',
            data: data5,
            options: {}
        };
        //Render
        const quarterlyProductSales = new Chart(
            document.getElementById('quarterlyProductSales'),
            config5
        );

        //MONTHLY PRODUCT SALES CHART//
        //Setup
        const data2 = {
            labels: "",
            datasets: [{
                label: 'Sales (RM)',
                backgroundColor: 'orange',
                borderColor: 'orange',
                data: [0]
            }]
        };
        //Config
        const config2 = {
            type: 'line',
            data: data2,
            options: {}
        };
        //Render
        const monthlyProductSales = new Chart(
            document.getElementById('monthlyProductSales'),
            config2
        );

        //TOP 10 PRODUCT SALES CHART//
        <?php
        $productSalesPercentage = $charts['productSalesData'][4];
        if (count($productNameData) > 10) {
            $productNameData = array_slice($productNameData, 0, 10); //declared in table view
            $productSalesPercentage = array_slice($productSalesPercentage, 0, 10);
        }
        $productSalesPercentage = array_map('floatval', $productSalesPercentage); //convert string[] to float[]
        ?>
        //Setup
        const data3 = {
            labels: <?php echo json_encode($productNameData); ?>,
            datasets: [{
                label: 'Sales Percentage (%)',
                data: <?php echo json_encode($productSalesPercentage); ?>,
                backgroundColor: [
                    'rgba(122,80,0,0.4)',
                    'rgba(153,99,0,0.4)',
                    'rgba(184,119,0,0.4)',
                    'rgba(214,139,0,0.4)',
                    'rgba(245,159,0,0.4)',
                    'rgba(255,182,46,0.4)',
                    'rgba(255,198,92,0.4)',
                    'rgba(255,214,138,0.4)',
                    'rgba(255,230,184,0.4)',
                    'rgba(255,246,230,0.4)',
                ],
                borderColor: [
                    'rgba(122,80,0,1)',
                    'rgba(153,99,0,1)',
                    'rgba(184,119,0,1)',
                    'rgba(214,139,0,1)',
                    'rgba(245,159,0,1)',
                    'rgba(255,182,46,1)',
                    'rgba(255,198,92,1)',
                    'rgba(255,214,138,1)',
                    'rgba(255,230,184,1)',
                    'rgba(255,246,230,1)',
                ]
            }]
        };
        //Config
        const config3 = {
            type: 'doughnut',
            data: data3,
            options: {
                layout: {
                    padding: 40
                },
                plugins: {
                    labels: {
                        render: 'label',
                        fontColor: data3.datasets[0].borderColor,
                        fontStyle: 'bold',
                        position: 'outside',
                        textMargin: 5,
                    }
                }
            },
            plugins: [ChartDataLabels]
        };
        //Render
        const productSalesChart = new Chart(
            document.getElementById('top10ProductSalesChart'),
            config3
        );

        //TOP 10 CATEGORY SALES CHART//
        <?php
        $productCategorySalesPercentage = $charts['productCategorySalesData'][2];

        if (count($productCategoryNameData) > 10) {
            $productCategoryNameData = array_slice($productCategoryNameData, 0, 10);    //declared in table view
            $productCategorySalesPercentage = array_slice($productCategorySalesPercentage, 0, 10);
        }
        $productCategorySalesPercentage = array_map('floatval', $productCategorySalesPercentage); //convert string[] to float[]
        ?>
        //Setup
        const data4 = {
            labels: <?php echo json_encode($productCategoryNameData); ?>,
            datasets: [{
                label: 'Sales Percentage (%)',
                data: <?php echo json_encode($productCategorySalesPercentage); ?>,
                backgroundColor: [
                    'rgba(122,80,0,0.4)',
                    'rgba(153,99,0,0.4)',
                    'rgba(184,119,0,0.4)',
                    'rgba(214,139,0,0.4)',
                    'rgba(245,159,0,0.4)',
                    'rgba(255,182,46,0.4)',
                    'rgba(255,198,92,0.4)',
                    'rgba(255,214,138,0.4)',
                    'rgba(255,230,184,0.4)',
                    'rgba(255,246,230,0.4)',
                ],
                borderColor: [
                    'rgba(122,80,0,1)',
                    'rgba(153,99,0,1)',
                    'rgba(184,119,0,1)',
                    'rgba(214,139,0,1)',
                    'rgba(245,159,0,1)',
                    'rgba(255,182,46,1)',
                    'rgba(255,198,92,1)',
                    'rgba(255,214,138,1)',
                    'rgba(255,230,184,1)',
                    'rgba(255,246,230,1)',
                ]
            }]
        };
        //Config
        const config4 = {
            type: 'doughnut',
            data: data4,
            options: {
                layout: {
                    padding: 40
                },
                plugins: {
                    labels: {
                        render: 'label',
                        fontColor: data4.datasets[0].borderColor,
                        fontStyle: 'bold',
                        position: 'outside',
                        textMargin: 5,
                    }
                }
            },
            plugins: [ChartDataLabels]
        };
        //Render
        const productCategorySalesChart = new Chart(
            document.getElementById('top10ProductCategorySales'),
            config4
        );
    </script>

    <style>
        #all-product-list,
        #product-sales,
        #product-category-sales {
            font-size: 11px;
        }

        .chartBox {
            width: 450px;
        }
    </style>

    @endsection