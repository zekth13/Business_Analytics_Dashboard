<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Report</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/select/1.4.0/css/select.dataTables.css">
</head>

<body>

    @extends('layouts.admin')

    @section('main-content')
    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">{{ __('Create Report') }}</h1>

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
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Report Type:</h6><br>
                <button type="button" class="btn btn-primary btn-rounded" id="salessummarybutton">Sales Summary</button>
                <button type="button" class="btn btn-success btn-rounded" id="productcategorybutton">Product Category</button>
                <button type="button" class="btn btn-danger btn-rounded" id="outletperformancebutton">Outlet Performance</button>
                <button type="button" class="btn btn-warning btn-rounded" id="supplierperformancebutton">Supplier Performance</button>
                <button type="button" class="btn btn-info btn-rounded" id="customizereport">Customize Report</button>
            </div>
            <div class="card-body" id="report-content">
                <!-- Content for selected report type will be displayed here, which is in the JavaScript section based on the clicked button from above -->
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
    <script>
        $(document).ready(function() {
            $("#salessummarybutton").click(function() {
                loadSalesSummaryReport();
            });

            $("#productcategorybutton").click(function() {
                loadProductCategoryReport();
            });

            $("#outletperformancebutton").click(function() {
                loadOutletPerformanceReport();
            });

            $("#supplierperformancebutton").click(function() {
                loadSupplierPerformanceReport();
            });

            $("#customizereport").click(function() {
                loadCustomizeReport();
            });


        });

        /////SALES SUMMARY REPORT/////
        function loadSalesSummaryReport() {
            // Clear existing content
            $("#report-content").html("");

            // Add the desired content
            $("#report-content").append(`
                <h2>
                    Product Sales Summary Report
                    <a href="#" data-toggle="tooltip" title="Provide an overview of total sales, revenue, and quantities sold to allow users to quickly grasp the overall sales performance.">
                        <i class="fas fa-info-circle"></i>
                    </a>
                </h2>
                <div id="info" class="hidden-content">
                    <label class="m-1"><strong>Time Interval:</strong></label>
                    <button type="button" class="btn btn-primary btn-rounded" id="monthlybutton">Monthly</button>
                    <button type="button" class="btn btn-primary btn-rounded" id="quarterlybutton">Quarterly</button>
                    <button type="button" class="btn btn-primary btn-rounded" id="annualbutton">Annually</button>

                    <label class="m-1" id="yearButtonLabel"><strong>Year:</strong></label>
                    <select name="yearDropDown" id="yearDropDown" class="btn btn-primary btn-rounded content">
                        <option value="" disabled selected hidden>Select</option>
                    </select>

                    <div class="row">
                        <div class="col-lg-6 col-sm-6">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Total Sales</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="totalSalesChart"></canvas>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">Total Product Quantity Sold</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="totalProducQuantitySold"></canvas>
                            </div>
                        </div>

                    </div><br>

                    <div class="row">

                        <div class="col-lg-6 col-sm-6">
                            <table id="total-sales-table" class="display">
                                <thead>
                                    <tr>
                                        <th>Time Interval</th>
                                        <th>Total Sales (RM)</th>
                                    </tr>
                                </thead>
                                <tbody id="sales-summary-table-data">
                                    <tr>
                                        <td></td>
                                        <td>Please select the Time Interval</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="col-lg-6 col-sm-6">
                            <table id="product-quantity-table" class="display">
                                <thead>
                                    <tr>
                                        <th>Time Interval</th>
                                        <th>Product Quantity Sold</th>
                                    </tr>
                                </thead>
                                <tbody id="sales-summary-table-data">
                                    <tr>
                                        <td></td>
                                        <td>Please select the Time Interval</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-sm-12">
                            <table id="sales-summary-table" class="display">
                                <thead>
                                    <tr>
                                        <th>Year</th>
                                        <th>Month</th>
                                        <th>Total Sales</th>
                                        <th>Total Product Quantity Sold</th>
                                    </tr>
                                </thead>
                                <tbody id="sales-summary-table-data">
                                    <?php
                                    $yearData = $controllerData['salesSummaryTableData'][0];
                                    $monthData = $controllerData['salesSummaryTableData'][1];
                                    $totalSalesData = $controllerData['salesSummaryTableData'][2];
                                    $totalProductQUantityData = $controllerData['salesSummaryTableData'][3];
                                    $numberOfRows = count($yearData);

                                    for ($i = 0; $i < $numberOfRows; $i++) {
                                        echo '<tr>
                                                    <td>' . $yearData[$i] . '</td>
                                                    <td>' . $monthData[$i] . '</td>
                                                    <td>' . $totalSalesData[$i] . '</td>
                                                    <td>' . $totalProductQUantityData[$i] . '</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                    <label class="m-1"><strong>Export: </strong></label>
                    <button type="button" class="btn btn-primary btn-rounded" id="exportPdfButton">PDF</button>
                    <button type="button" class="btn btn-primary btn-rounded" id="exportCsvButton">CSV</button>
                </div>
            `);
            var choosenTimeInterval = "";
            // Hide and show the year label and year dropdown
            $("#yearDropDown").hide();
            $("#yearButtonLabel").hide();
            $("#monthlybutton").click(function() {
                $("#yearDropDown").show();
                $("#yearButtonLabel").show();
                choosenTimeInterval = "monthly";
            });
            $("#quarterlybutton").click(function() {
                // Show the year dropdown when Quarterly is selected
                $("#yearDropDown").show();
                $("#yearButtonLabel").show();
                choosenTimeInterval = "quarterly";
            });
            $("#annualbutton").click(function() {
                $("#yearDropDown").hide();
                $("#yearButtonLabel").hide();
                choosenTimeInterval = "annually";
            });

            //Add all years until from 2014 to the current year in the yeardropdown
            function addAllYearsInDropdown(dropdownVariable) {
                let currentYear = new Date().getFullYear();
                let startYear = 2014;
                for (let i = 0; i <= currentYear - startYear; i++) {
                    let dateOption = document.createElement('option');
                    dateOption.text = currentYear - i;
                    dateOption.value = currentYear - i;
                    dropdownVariable.add(dateOption);
                }
            }
            let yearDropdown = document.getElementById('yearDropDown');
            addAllYearsInDropdown(yearDropdown);

            const allMonthsLabel = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            var allYearsLabel = [];
            let currentYear = new Date().getFullYear();
            for (let i = 2014; i <= currentYear; i++) {
                allYearsLabel.push(i);
            }

            var selectedTimeInterval = "";
            $("#monthlybutton").click(function() {
                selectedTimeInterval = "monthly";
            });
            $("#quarterlybutton").click(function() {
                selectedTimeInterval = "quarterly";
            });
            $("#annualbutton").click(function() {
                selectedTimeInterval = "annually";

                // UPDATE total sales chart ajax
                // UPDATE total sales table ajax
                $.ajax({
                    type: "GET",
                    url: "{{ route('total-sales-report') }}",
                    data: {
                        timeInterval: selectedTimeInterval,
                    },
                    datatype: 'text',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {

                        // Update total sales chart
                        //setup
                        const labels1 = result[0];
                        const data1 = {
                            labels: labels1,
                            datasets: [{
                                label: 'Sales (RM)',
                                backgroundColor: 'blue',
                                borderColor: 'blue',
                                data: result[1]
                            }]
                        };
                        totalSalesChart.data = data1;
                        totalSalesChart.update();

                        // Update total sales table
                        // update column headers
                        var newColumnHeaders = ["Year", "Total Sales (RM)"];

                        $('#total-sales-table').DataTable().columns().header().each(function(cell, colIdx) {
                            $(cell).text(newColumnHeaders[colIdx]);
                        });

                        //update table's rows
                        var newTableData = [];
                        newNumberOfRows = result[0].length;
                        for (var i = 0; i < newNumberOfRows; i++) { //table: row i, column 0-2
                            newTableData[i] = [];
                            newTableData[i][0] = result[0][i];
                            newTableData[i][1] = result[1][i];
                        }
                        $('#total-sales-table').DataTable().clear().rows.add(newTableData).draw();
                    },
                    error: function(result) {
                        alert("ajax error");
                        console.log(result);
                    }
                });

                // UPDATE product quantity chart
                // UPDATE product quantity table
                $.ajax({
                    type: "GET",
                    url: "{{ route('total-product-quantity-report') }}",
                    data: {
                        timeInterval: choosenTimeInterval,
                    },
                    datatype: 'text',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        //ajax for updating product quantity chart
                        //Setup
                        const labels2 = result[0];
                        const data2 = {
                            labels: labels2,
                            datasets: [{
                                label: 'Product Quantity',
                                backgroundColor: 'blue',
                                borderColor: 'blue',
                                data: result[1]
                            }]
                        };
                        totalProducQuantitySold.data = data2;
                        totalProducQuantitySold.update();

                        // UPDATE Product Quantity Sold table
                        // update column headers
                        var timeIntervalColumnHeaderTotalSales = "Year";

                        var newColumnHeaders2 = [timeIntervalColumnHeaderTotalSales, "Product Quantity"];

                        $('#product-quantity-table').DataTable().columns().header().each(function(cell, colIdx) {
                            $(cell).text(newColumnHeaders2[colIdx]);
                        });
                        //update table's rows
                        var newTableData2 = [];
                        newNumberOfRows2 = result[0].length;
                        for (var i = 0; i < newNumberOfRows2; i++) { //table: row i, column 0-2
                            newTableData2[i] = [];
                            newTableData2[i][0] = result[0][i];
                            newTableData2[i][1] = result[1][i];
                        }
                        $('#product-quantity-table').DataTable().clear().rows.add(newTableData2).draw();
                    },
                    error: function(result) {
                        alert("ajax UPDATE Product Quantity Sold Chart error");
                        console.log(result);
                    }
                });
            });

            //TOTAL PRODUCT SALES LINE CHART//
            //Setup
            const totalSalesLabels = "";
            const totalSalesData = {
                labels: totalSalesLabels,
                datasets: [{
                    label: 'Sales (RM)',
                    backgroundColor: 'blue',
                    borderColor: 'blue',
                    data: [0]
                }]
            };
            //Config
            const totalSalesConfig = {
                type: 'line',
                data: totalSalesData,
                options: {}
            };
            //Render
            const totalSalesChart = new Chart(
                document.getElementById('totalSalesChart'),
                totalSalesConfig
            );

            //TOTAL PRODUCT QUANTITY SOLD BAR CHART//
            //Setup
            const productQuantityLabels = "";
            const productQuantityData = {
                labels: productQuantityLabels,
                datasets: [{
                    label: 'Product Quantity',
                    backgroundColor: 'blue',
                    borderColor: 'blue',
                    data: ""
                }]
            };
            //Config
            const productQuantityConfig = {
                type: 'bar',
                data: productQuantityData,
                options: {}
            };
            //Render
            const totalProducQuantitySold = new Chart(
                document.getElementById('totalProducQuantitySold'),
                productQuantityConfig
            );

            $(document).ready(function() {
                $('#yearDropDown').change(function() {
                    event.preventDefault();
                    var selectedYear = $(this).val();
                    // UPDATE Total Sales chart ajax
                    // UPDATE Total Sales table
                    $.ajax({
                        type: "GET",
                        url: "{{ route('total-sales-report') }}",
                        data: {
                            timeInterval: choosenTimeInterval,
                            year: selectedYear
                        },
                        datatype: 'text',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(result) {
                            //UPDATE Total Sales Chart
                            //Setup
                            const labels1 = result[0];
                            const data1 = {
                                labels: labels1,
                                datasets: [{
                                    label: 'Sales (RM)',
                                    backgroundColor: 'blue',
                                    borderColor: 'blue',
                                    data: result[1]
                                }]
                            };
                            totalSalesChart.data = data1;
                            totalSalesChart.update();


                            // UPDATE Total Sales table
                            // update column headers
                            var timeIntervalColumnHeaderTotalSales = "";
                            if (selectedTimeInterval == "monthly") {
                                timeIntervalColumnHeaderTotalSales = "Month";
                            } else if (selectedTimeInterval == "quarterly") {
                                var timeIntervalColumnHeaderTotalSales = "Quarter";
                            } else {
                                timeIntervalColumnHeaderTotalSales = "Year"
                            }
                            var newColumnHeaders = [timeIntervalColumnHeaderTotalSales, "Total Sales (RM)"];

                            $('#total-sales-table').DataTable().columns().header().each(function(cell, colIdx) {
                                $(cell).text(newColumnHeaders[colIdx]);
                            });

                            //update table's rows
                            var newTableData = [];
                            newNumberOfRows = result[0].length;
                            for (var i = 0; i < newNumberOfRows; i++) { //table: row i, column 0-2
                                newTableData[i] = [];
                                newTableData[i][0] = result[0][i];
                                newTableData[i][1] = result[1][i];
                            }
                            $('#total-sales-table').DataTable().clear().rows.add(newTableData).draw();

                        },
                        error: function(result) {
                            alert("ajax error");
                            console.log(result);
                        }
                    });

                    //ajax UPDATE Product Quantity Sold chart
                    //ajax UPDATE Product Quantity Sold table
                    $.ajax({
                        type: "GET",
                        url: "{{ route('total-product-quantity-report') }}",
                        data: {
                            timeInterval: choosenTimeInterval,
                            year: selectedYear
                        },
                        datatype: 'text',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(result) {
                            //UPDATE Product Quantity Sold Chart
                            //Setup
                            const labels2 = result[0];
                            const data2 = {
                                labels: labels2,
                                datasets: [{
                                    label: 'Product Quantity',
                                    backgroundColor: 'blue',
                                    borderColor: 'blue',
                                    data: result[1]
                                }]
                            };
                            totalProducQuantitySold.data = data2;
                            totalProducQuantitySold.update();

                            // UPDATE Product Quantity Sold table
                            // update column headers
                            var timeIntervalColumnHeaderTotalSales = "";
                            if (selectedTimeInterval == "monthly") {
                                timeIntervalColumnHeaderTotalSales = "Month";
                            } else if (selectedTimeInterval == "quarterly") {
                                var timeIntervalColumnHeaderTotalSales = "Quarter";
                            } else {
                                timeIntervalColumnHeaderTotalSales = "Year"
                            }
                            var newColumnHeaders2 = [timeIntervalColumnHeaderTotalSales, "Product Quantity"];

                            $('#product-quantity-table').DataTable().columns().header().each(function(cell, colIdx) {
                                $(cell).text(newColumnHeaders2[colIdx]);
                            });
                            //update table's rows
                            var newTableData2 = [];
                            newNumberOfRows2 = result[0].length;
                            for (var i = 0; i < newNumberOfRows2; i++) { //table: row i, column 0-2
                                newTableData2[i] = [];
                                newTableData2[i][0] = result[0][i];
                                newTableData2[i][1] = result[1][i];
                            }
                            $('#product-quantity-table').DataTable().clear().rows.add(newTableData2).draw();
                        },
                        error: function(result) {
                            alert("ajax UPDATE Product Quantity error");
                            console.log(result);
                        }
                    });
                });

                $('#total-sales-table').DataTable({
                    paging: false,
                    scrollY: 370,
                    bLengthChange: false,
                    autoWidth: false,
                    columnDefs: [{
                            "width": "30%",
                            "targets": 0
                        },
                        {
                            "width": "70%",
                            "targets": 1
                        },
                    ]
                });

                $('#product-quantity-table').DataTable({
                    paging: false,
                    scrollY: 370,
                    bLengthChange: false,
                    autoWidth: false,
                    columnDefs: [{
                            "width": "30%",
                            "targets": 0
                        },
                        {
                            "width": "70%",
                            "targets": 1
                        },
                    ]
                });

                $('#sales-summary-table').DataTable({
                    paging: false,
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
                            "width": "45%",
                            "targets": 2
                        },
                        {
                            "width": "45%",
                            "targets": 3
                        },
                    ]
                });




            });

        } /////END OF SALES SUMMARY REPORT/////

        /////PRODUCT CATEGORY REPORT/////
        function loadProductCategoryReport() {
            $("#report-content").html("");
            $("#report-content").append(`
                <h2>Product Category Report</h2>
                <p>aaaaaa</p>
            `);
        }
        /////END OF PRODUCT CATEGORY REPORT/////

        /////OUTLET PERFORMANCE REPORT/////
        function loadOutletPerformanceReport() {
            $("#report-content").html("");
            $("#report-content").append(`
                <h2>Outlet Performance Report</h2>
                <p>aaaaaaa</p>
            `);
        }
        /////END OF OUTLET PERFORMANCE REPORT/////

        /////SUPPLIER PERFORMANCE REPORT/////
        function loadSupplierPerformanceReport() {
            $("#report-content").html("");
            $("#report-content").append(`
                <h2>Supplier Performance Report</h2>
                <p>aaaaaaaa</p>
            `);
        }
        /////END OF SUPPLIER PERFORMANCE REPORT/////

        /////CUSTOMIZE REPORT/////
        function loadCustomizeReport() {
            $("#report-content").html("");
            $("#report-content").append(`
                <h2>Customize Report</h2>
                <p> aaaaaaaaa</p>
            `);
        }
        /////END OF CUZTOMIZE REPORT/////
    </script>
</body>

</html>