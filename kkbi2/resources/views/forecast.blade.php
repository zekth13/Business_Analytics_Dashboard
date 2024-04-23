<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.includes.header')
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        @include('layouts.includes.sidebarforecast')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- @yield('main-content') -->
                    <!-- Letak code body dekat sini -->
                    <h1 class="h1 pt-2 text-center">{{ __('Sales Forecasting') }}</h1>

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="col-md-12 col-sm-6">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Monthly Forecast</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <div>
                                                <canvas id="monthly_forecast"></canvas>
                                            </div>
                                        </div>
                                        <div class="container">
                                            <h2 class="mt-4 mb-4">Model Evaluation Metrics</h2>

                                            <!-- Model 1 Metrics -->
                                            <div class="card mb-4">
                                                <div class="card-header">
                                                    <h5>Gradient Boostring Regressor:</h5>
                                                </div>
                                                <div class="card-body">
                                                    <p id="month_gbr_mape"><strong>MAPE:</strong> </p>
                                                    <p id="month_gbr_rmse"><strong>RMSE:</strong> </p>
                                                </div>
                                            </div>

                                            <!-- Model 2 Metrics -->
                                            <div class="card mb-4">
                                                <div class="card-header">
                                                    <h5>Polynomial Regression</h5>
                                                </div>
                                                <div class="card-body">
                                                    <p id="month_poly_mape"><strong>MAPE:</strong> </p>
                                                    <p id="month_poly_rmse"><strong>RMSE:</strong> </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="col-md-12 col-sm-6">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Yearly Forecast</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            <div>
                                                <canvas id="annual_forecast"></canvas>
                                            </div>
                                        </div>
                                        <div class="container">
                                            <h2 class="mt-4 mb-4">Model Evaluation Metrics</h2>

                                            <!-- Model 1 Metrics -->
                                            <div class="card mb-4">
                                                <div class="card-header">
                                                    <h5>GrAdient Boostring Regressor:</h5>
                                                </div>
                                                <div class="card-body">
                                                    <p id="year_gbr_mape"><strong>MAPE:</strong> </p>
                                                    <p id="year_gbr_rmse"><strong>RMSE:</strong> </p>
                                                </div>
                                            </div>

                                            <!-- Model 2 Metrics -->
                                            <div class="card mb-4">
                                                <div class="card-header">
                                                    <h5>Polynomial Regression</h5>
                                                </div>
                                                <div class="card-body">
                                                    <p id="year_poly_mape"><strong>MAPE:</strong> </p>
                                                    <p id="year_poly_rmse"><strong>RMSE:</strong> </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

                @include('layouts.includes.footer')

            </div>
            <!-- End of Content Wrapper -->

        </div>

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        @include('layouts.includes.script')
        @yield('script')

</body>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script type="text/javascript">
    const allMonths = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const allYears = ['2022', '2023', '2024', '2025'];
    const chartsColour = 'orange';

    function extract_monthly(data) {
        // Initialize an empty object to store data by year
        const organizedData = {};

        // Iterate through the data and organize it by year
        data.forEach(entry => {
            // Destructure the entry into year, month, and value
            const [year, month, value] = entry;

            // Create a new array for the year if it doesn't exist
            if (!organizedData[year]) {
                organizedData[year] = {
                    months: [],
                    values: []
                };
            }

            // Add the month and value to the corresponding year
            organizedData[year].months.push(month);
            organizedData[year].values.push(value);
        });

        // If you want the result as an array of objects
        // const result = Object.values(organizedData);

        // Print or use the result as needed
        // console.log(organizedData);
        return organizedData
    }

    function extract_yearly(data) {
        // Initialize an empty object to store data by year
        const organizedData = {};

        // Iterate through the data and organize it by year
        data.forEach(entry => {
            // Destructure the entry into year, month, and value
            const [year, value] = entry;

            // Create a new array for the year if it doesn't exist
            if (!organizedData[year]) {
                organizedData[year] = {
                    values: []
                };
            }
            // Add the month and value to the corresponding year
            organizedData[year].values.push(value);
        });

        // If you want the result as an array of objects
        // const result = Object.values(organizedData);

        // Print or use the result as needed
        // console.log(organizedData);
        return organizedData
    }

    function tofind_monthly(organizedData) {
        const targetYear = 2023;

        // Check if the target year exists
        if (organizedData && organizedData[targetYear]) {
            if (organizedData[targetYear].values) {
                const targetYearObject = organizedData[targetYear];

                // Find the index of the target month
                const list_values = targetYearObject.values;

                return list_values;
            } else {

            }

        } else {
            console.log(`Year ${targetYear} not found`);
        }
    }

    function tofind_yearly(organizedData, startYear) {
        const result = [];

        for (let year = startYear; year <= new Date().getFullYear(); year++) {
            // Check if the year exists
            if (organizedData && organizedData[year] && organizedData[year].values) {
                result.push(...organizedData[year].values);
            } else {
                console.log(`Values not found for ${year}`);
            }
        }

        return result;
    }
    window.addEventListener('load', function() {
        event.preventDefault();
        var selectedmodel = $("#modeldropdown").val();
        // monthly_forecast
        $.ajax({
            type: "GET",
            url: "{{ route('monthly') }}",
            // beforeSend: function() {
            //     window.open('forecast/loading', 'Loading page', 'width=600, height=400');
            // },
            data: {
                model: selectedmodel
            },
            datatype: 'text',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(result) {
                // console.log(result);
                xgb_original = extract_monthly(result[0]);
                poly_original = extract_monthly(result[1]);
                xgb_prediction = tofind_monthly(xgb_original);
                poly_prediction = tofind_monthly(poly_original);
                original = tofind_monthly(extract_monthly(result[2]))
                //Setup
                const labels1 = allMonths
                const data1 = {
                    labels: labels1,
                    datasets: [{
                        label: 'XGB',
                        borderColor: 'rgba(0,0,255, 0.5)', // Blue color
                        backgroundColor: 'rgba(0,0,255, 0.2)',
                        data: xgb_prediction,
                        borderDash: [5, 5]
                    }, {
                        label: 'Actual',
                        borderColor: 'rgba(255, 165, 0, 1)', // Orange color for emphasis
                        backgroundColor: 'rgba(255, 165, 0, 0.2)',
                        data: original
                    }, {
                        label: 'Poly',
                        borderColor: 'rgba(0,128,0, 0.5)', // Green color
                        backgroundColor: 'rgba(0,128,0, 0.2)',
                        data: poly_prediction,
                        borderDash: [5, 5]
                    }]
                };
                monthly_forecast.data = data1;
                monthly_forecast.update();
                document.getElementById('month_gbr_mape').innerHTML = 'MAPE = ' + result[3][0];
                document.getElementById('month_gbr_rmse').innerHTML = 'RMSE = ' + result[3][1];
                document.getElementById('month_poly_mape').innerHTML = 'MAPE = ' + result[4][0];
                document.getElementById('month_poly_rmse').innerHTML = 'RMSE = ' + result[4][1];
            },
            error: function(result) {
                console.log("error");
            }
        });
        // annual_forecast
        $.ajax({
            type: "GET",
            url: "{{ route('annual') }}",
            data: {
                model: selectedmodel
            },
            datatype: 'text',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(result) {
                xgb_original = extract_yearly(result[0]);
                poly_original = extract_yearly(result[1]);
                xgb_prediction = tofind_yearly(xgb_original, 2022);
                poly_prediction = tofind_yearly(poly_original, 2022);
                original = tofind_yearly(extract_yearly(result[2]), 2022);
                console.log(original)
                //Setup
                const labels1 = allYears;
                const data1 = {
                    labels: labels1,
                    datasets: [{
                        label: 'XGB',
                        borderColor: 'rgba(0,0,255, 0.5)', // Blue color
                        backgroundColor: 'rgba(0,0,255, 0.2)',
                        data: xgb_prediction,
                        borderDash: [5, 5]
                    }, {
                        label: 'Actual',
                        borderColor: 'rgba(255, 165, 0, 1)', // Orange color for emphasis
                        backgroundColor: 'rgba(255, 165, 0, 0.2)',
                        data: original
                    }, {
                        label: 'Poly',
                        borderColor: 'rgba(0,128,0, 0.5)', // Green color
                        backgroundColor: 'rgba(0,128,0, 0.2)',
                        data: poly_prediction,
                        borderDash: [5, 5]
                    }, ]
                };
                annual_forecast.data = data1;
                annual_forecast.update();
                document.getElementById('year_gbr_mape').innerHTML = 'MAPE = ' + result[3][0];
                document.getElementById('year_gbr_rmse').innerHTML = 'RMSE = ' + result[3][1];
                document.getElementById('year_poly_mape').innerHTML = 'MAPE = ' + result[4][0];
                document.getElementById('year_poly_rmse').innerHTML = 'RMSE = ' + result[4][1];
            },
            error: function(result) {
                console.log("error");
            }
        });
    })
    //// forecast_monthly_chart ////
    // setup
    const labels = allMonths;
    const data = {
        labels: labels,
        datasets: [{
            label: 'Sales (RM)',
            backgroundColor: chartsColour,
            borderColor: chartsColour,
            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
        }]
    };
    //Config
    const config = {
        type: 'line',
        data: data,
        options: {}
    };
    //Render
    const monthly_forecast = new Chart(
        document.getElementById('monthly_forecast'),
        config
    );
    //// forecast_annual_chart ////
    // setup
    const labels2 = allYears;
    const data2 = {
        labels: labels2,
        datasets: [{
            label: 'Sales (RM)',
            backgroundColor: chartsColour,
            borderColor: chartsColour,
            data: [0, 0, 0, 0]
        }]
    };
    //Config
    const config2 = {
        type: 'line',
        data: data2,
        options: {}
    };
    //Render
    const annual_forecast = new Chart(
        document.getElementById('annual_forecast'),
        config2
    );
</script>

</html>