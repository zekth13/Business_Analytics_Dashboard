<!DOCTYPE html>
<html>

<head>
    <title>Python Flask Create a Progress Bar Data Insert using Jquery Ajax Bootstrap and Mysqldb database</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- @yield('main-content') -->
                    <!-- Letak code body dekat sini -->
                    <h1 class="h1 pt-2 text-center">{{ __('Please Wait For A While') }}</h1>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Loading</h3>
                        </div>
                        <div class="panel-body">
                            <div class="form-group" id="process" style="display:block;">
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped active bg-success" role="progressbar" aria-valuemin="0" aria-valuemax="100" id="progress" style=""></div>
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
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script>
    var percentage = 0;

    var timer = setInterval(function() {
        percentage = percentage + 20;
        progress_bar_process(percentage, timer)
    }, 6000);

    function progress_bar_process(percentage, timer) {
        document.getElementById("progress").style.width = percentage + "%"
        if (percentage > 100) {
            window.close();
        }
    }
</script>

</html>