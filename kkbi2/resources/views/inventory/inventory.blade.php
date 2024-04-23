@extends('layouts.admin')

<head>
    <title>Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/1.4.0/css/select.dataTables.css" rel="stylesheet" />
</head>
@section('main-content')

<!-- Page Heading -->

<h1 class="h3 mb-4 text-gray-800">{{ __('Inventory') }}</h1>

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

    <div class="card shadow mb-4">

        <div class="col-md-12 col-sm-12">
            <div class="card-header py-3">
                <h6 class="m-0 text-lg font-weight-bold text-primary">Available Products</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-sm-6">
                        <div class="d-inline-flex flex-row mb-4 align-items-start">
                            <label class="mr-2"><strong>Department:</strong></label>
                            <div class="mr-2">
                                <select class="form-select " style="margin-right:2px ;" name="departmentdropdown" id="departmentdropdown">
                                    <option value="" disabled selected hidden>All</option>
                                    <option value="">All</option>
                                    <option value="Electronics">Electronics</option>
                                    <option value="Home Appliances">Home Appliances</option>
                                    <option value="Apparel">Apparel</option>
                                    <option value="Books">Books</option>
                                    <option value="Furniture">Furniture</option>
                                    <option value="Sports and Outdoors">Sports and Outdoors</option>
                                    <option value="Hobby and Crafts">Hobby and Crafts</option>
                                    <option value="Outdoor">Outdoor</option>
                                    <option value="Kitchen">Kitchen</option>
                                </select>
                            </div>
                            <label class="mr-2"><strong>Class:</strong></label>
                            <div class="mr-2">
                                <select class="form-select" name="classDropDown" id="classDropDown">
                                    <option value="" disabled selected hidden>All</option>
                                </select>
                            </div>
                            <container class="pl-2">
                                <button type="button" class="btn btn-primary btn-sm" id="loadbutton">Load</button>
                            </container>
                        </div>

                        <table id="product-status" class="display">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Product No.</th>
                                    <th>Product Name</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="table_data">
                                <?php
                                $productNoData = $index['getTable'][0];
                                $productNameData = $index['getTable'][1];
                                $StatuesData = $index['getTable'][2];
                                $numberOfRows = count($productNameData);

                                for ($i = 0; $i < $numberOfRows; $i++) {
                                    echo '<tr>
                                        <td>' . ($i + 1) . '</td>
                                        <td>' . $productNoData[$i] . '</td>
                                        <td>' . $productNameData[$i] . '</td>
                                        <td>' . $StatuesData[$i] . '</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-md-6 col-sm-6">
                        <div class="card-header py-3">
                            <h6 class="m-0 text-md font-weight-bold text-primary">Products Sales by Year</h6>
                            <p class="m-0 font-weight text-primary">(Select product from the table to show).</p>
                        </div>
                        <div class="card-body">
                            <div><canvas id="anualSales"></canvas></div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>
</div>

@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/select/1.4.0/js/dataTables.select.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


<script>
    var allYears = [
        '2020',
        '2021',
        '2022',
        '2023',
    ];
    $(document).ready(function() {
        $('#departmentdropdown').select2();
        var table = $('#product-status').DataTable({
            scrollY: 400,
            select: true,
            autoWidth: false,
            columnDefs: [{
                    "width": "1%",
                    "targets": 0
                },
                {
                    "width": "1%",
                    "targets": 1
                },
                {
                    "width": "97%",
                    "targets": 2
                },
                {
                    "width": "1%",
                    "targets": 3
                }
            ]
        });
        $(document).ready(function() {
            $("#departmentdropdown").change(function() {
                var chosenDepartment = $(this).val();
                $.ajax({
                    type: "GET",
                    url: "{{route('getClass')}}",
                    data: {
                        dep_na: chosenDepartment
                    },
                    dataType: 'Text',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        console.log(result);
                        $("#classDropDown").html(result);
                    }
                })
            });
            $('#loadbutton').click(function() {
                event.preventDefault();
                var selectedClass = $("#classDropDown").val();
                // console.log(selectedYear + selectedCategory);

                $.ajax({
                    type: "GET",
                    url: "{{ route('getTable') }}",
                    data: {
                        cla_na: selectedClass
                    },
                    datatype: 'text',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(result) {
                        // alert("Result: " + result[1]);
                        var newProductNoData = result[0];
                        var newProductNameData = result[1];
                        var newStatuesData = result[2];

                        //update table
                        var newTableData = [];
                        for (var i = 0; i < newStatuesData.length; i++) { //table: row i, column 0-3
                            newTableData[i] = [];
                            newTableData[i][0] = (i + 1).toString();
                            newTableData[i][1] = newProductNoData[i];
                            newTableData[i][2] = newProductNameData[i];
                            newTableData[i][3] = newStatuesData[i];

                        }
                        // console.log(newTableData);
                        productSalesDataTable = $('#product-status').DataTable().clear().rows.add(newTableData).draw();
                    },
                    error: function(result) {
                        alert('fail');
                    }
                });
            });

        })


        table.on('select', function(e, dt, type, indexes) {
            var temp = table.rows({
                selected: true
            }).data()[0][1];
            // console.log(temp);
            $.ajax({
                type: "GET",
                url: "{{ route('getChart') }}",
                data: {
                    goo_no: temp
                },
                datatype: 'text',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(result) {
                    // alert(result);
                    const labels2 = allYears;
                    const data2 = {
                        labels: labels2,
                        datasets: [{
                            label: "Sales (RM)",
                            backgroundColor: 'green',
                            borderColor: 'green',
                            data: result
                        }]
                    };
                    anualSales.data = data2;
                    anualSales.update();
                },
                error: function(result) {
                    console.log('fail');
                }
            });
        })
    });

    //PRODUCT SALES CHART
    const labels = allYears;
    const data = {
        labels: labels,
        datasets: [{
            label: '',
            backgroundColor: 'green',
            borderColor: 'green',
            data: [0, 0, 0, 0, 0, 0, 0, 0, 0]
        }]
    };
    //Config
    const config = {
        type: 'bar',
        data: data,
        options: {}
    };
    //Render
    const anualSales = new Chart(
        document.getElementById('anualSales'),
        config
    );
</script>

<style>
    #product-status {
        font-size: 14px;
    }
</style>

@endsection