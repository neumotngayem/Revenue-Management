<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Revenue Management</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
          rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

<!-- Page Wrapper -->
<div id="wrapper">

    <!-- Sidebar -->
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
            <div class="sidebar-brand-icon rotate-n-15">
                <i class="fas fa-laugh-wink"></i>
            </div>
            <div class="sidebar-brand-text mx-3">SB Admin <sup>2</sup></div>
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0">

        <!-- Nav Item - Dashboard -->
        <li class="nav-item active">
            <a class="nav-link" href="index.php">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Setting
        </div>

        <!-- Nav Item - Upload file -->
        <li class="nav-item">
            <a class="nav-link" href="./uploadfile/upload.php">
                <i class="fas fa-fw fa-file-upload"></i>
                <span>Upload file</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider">

        <!-- Heading -->
        <div class="sidebar-heading">
            Datatables
        </div>

        <!-- Nav Item - Tables -->
        <li class="nav-item">
            <a class="nav-link" href="datarec.php">
                <i class="fas fa-fw fa-table"></i>
                <span>Receipt Datatable</span></a>
        </li>

        <!-- Nav Item - Tables -->
        <li class="nav-item">
            <a class="nav-link" href="datasite.php">
                <i class="fas fa-fw fa-table"></i>
                <span>Site Datatable</span></a>
        </li>

        <!-- Nav Item - Tables -->
        <li class="nav-item">
            <a class="nav-link" href="datastaff.php">
                <i class="fas fa-fw fa-table"></i>
                <span>Staff Datatable</span></a>
        </li>
        <!-- Divider -->
        <hr class="sidebar-divider d-none d-md-block">

        <!-- Sidebar Toggler (Sidebar) -->
        <div class="text-center d-none d-md-inline">
            <button class="rounded-circle border-0" id="sidebarToggle"></button>
        </div>

    </ul>
    <!-- End of Sidebar -->
    <?php
    $servername = "localhost";
    $username = "root";
    $password = "root123456@";
    $dbname = "stovestore";
    $conn = new mysqli($servername, $username, $password, $dbname);

    $sql = "SELECT SUM(stv.price*rec.quantity) AS TOTAL FROM receipt rec JOIN stove stv ON rec.STOVE_ID = stv.ID";
    $result = $conn->query($sql);
    $totalEarn = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $totalEarn = $row['TOTAL'];
    }

    $sql = "SELECT sit.NM, SUM(stv.price*rec.quantity) AS MAX_SITE_EARN FROM receipt rec JOIN stove stv ON rec.STOVE_ID = stv.ID JOIN site sit ON sit.id = rec.LOCA_ID GROUP BY rec.LOCA_ID ORDER BY MAX_SITE_EARN DESC";
    $result = $conn->query($sql);
    $maxSiteName = "Unknown";
    $maxSiteEarn = 0;
    $siteNameArr = array();
    $siteEarnArr = array();
    if ($result->num_rows > 0) {
        $i = 0;
        while ($row = $result->fetch_assoc()) {
            if ($i == 0) {
                $maxSiteName = $row['NM'];
                $maxSiteEarn = $row['MAX_SITE_EARN'];
            }
            $siteNameArr[$i] = $row['NM'];
            $siteEarnArr[$i] = $row['MAX_SITE_EARN'];
            $i += 1;
        }
    }

    $sql = "SELECT stv.ID,stv.NM, SUM(stv.price*rec.quantity) AS STOVE_EARN, SUM(rec.QUANTITY) AS MAX_STOVE_QUANT FROM receipt rec JOIN stove stv ON rec.STOVE_ID = stv.ID GROUP BY rec.STOVE_ID ORDER BY MAX_STOVE_QUANT DESC  LIMIT 1";
    $result = $conn->query($sql);
    $maxStoveName = "Unknown";
    $maxStoveID = "Unknown";
    $maxStoveQuant = 0;
    $maxStoveEarn = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $maxStoveName = $row['NM'];
        $maxStoveID = $row['ID'];
        $maxStoveQuant = $row['MAX_STOVE_QUANT'];
        $maxStoveEarn = $row['STOVE_EARN'];
    }

    $sql = "SELECT sta.ID,sta.NM, SUM(stv.price*rec.quantity) AS STAFF_EARN FROM receipt rec JOIN stove stv ON rec.STOVE_ID = stv.ID JOIN staff sta ON rec.STAFF_ID = sta.ID GROUP BY rec.STAFF_ID ORDER BY STAFF_EARN DESC LIMIT 1";
    $result = $conn->query($sql);
    $maxStaffName = "Unknown";
    $maxStaffID = "Unknown";
    $maxStaffEarn = 0;
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $maxStaffID = $row['ID'];
        $maxStaffName = $row['NM'];
        $maxStaffEarn = $row['STAFF_EARN'];
    }
    ?>
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

        <!-- Main Content -->
        <div id="content">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">Valerie Luna</span>
                        </a>
                    </li>
                    <div class="topbar-divider d-none d-sm-block"></div>
                    <li class="nav-item dropdown no-arrow">
                        <a href="#" class="btn btn-danger btn-circle" style="top: 20%; position: inherit">
                            <i class="fas fa-sign-out-alt"></i>
                        </a>
                    </li>
                </ul>

            </nav>
            <!-- End of Topbar -->

            <!-- Begin Page Content -->
            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                </div>

                <!-- Content Row -->
                <div class="row">

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Earnings
                                            (Totaly)
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            $<?php echo(number_format($totalEarn)) ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Best
                                            Seller Site - <?php echo($maxSiteName) ?></div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            $<?php echo(number_format($maxSiteEarn)) ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Earnings (Monthly) Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Best Seller
                                            Product - <?php echo($maxStoveID) ?>(<?php echo($maxStoveName) ?>)
                                        </div>
                                        <div class="row no-gutters align-items-center">
                                            <div class="col-auto">
                                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800"><?php echo(number_format($maxStoveQuant)) ?>
                                                    Products
                                                </div>
                                            </div>
                                            <div class="col">
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    $<?php echo(number_format($maxStoveEarn)) ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pending Requests Card Example -->
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Best
                                            Seller Staff - <?php echo($maxStaffID) ?>(<?php echo($maxStaffName) ?>)
                                        </div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                                            $<?php echo(number_format($maxStaffEarn)) ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Row -->

                <div class="row">

                    <!-- Area Chart -->
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Earnings Overview - <span
                                            id="siteMonthSelected"></span></h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                         aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Site:</div>
                                        <button class="dropdown-item" onclick="loadMonthSite('all')">All</button>
                                        <button class="dropdown-item" onclick="loadMonthSite('HN')">Hanoi</button>
                                        <button class="dropdown-item" onclick="loadMonthSite('HCM')">Ho Chi Minh
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="chart-area">
                                    <canvas id="myAreaChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pie Chart -->
                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Revenue Sources</h6>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <div class="chart-pie pt-4 pb-2">
                                    <canvas id="myPieChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content Row -->
                <div class="row">

                    <!-- Content Column -->
                    <div class="col-lg-6 mb-4">

                        <!-- Project Card Example -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Top 5 Products Revenue - <span
                                            id="siteProductSelected"></span></h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                         aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Site:</div>
                                        <button class="dropdown-item" onclick="loadProductSite('all')">All</button>
                                        <button class="dropdown-item" onclick="loadProductSite('HN')">Hanoi</button>
                                        <button class="dropdown-item" onclick="loadProductSite('HCM')">Ho Chi Minh
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-bar">
                                    <canvas id="myBarChart"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-6 mb-4">

                        <!-- Project Card Example -->
                        <div class="card shadow mb-4">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Top 5 Sellers - <span id="siteStaffSelected"></span></h6>
                                <div class="dropdown no-arrow">
                                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                       data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                                         aria-labelledby="dropdownMenuLink">
                                        <div class="dropdown-header">Site:</div>
                                        <button class="dropdown-item" onclick="loadStaffSite('all')">All</button>
                                        <button class="dropdown-item" onclick="loadStaffSite('HN')">Hanoi</button>
                                        <button class="dropdown-item" onclick="loadStaffSite('HCM')">Ho Chi Minh
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart-bar">
                                    <canvas id="myBarChart2"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- End of Main Content -->

        <!-- Footer -->
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Hoai Bac 2019</span>
                </div>
            </div>
        </footer>
        <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

</div>
<!-- End of Page Wrapper -->

<!-- Scroll to Top Button-->
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<!-- Logout Modal-->
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <a class="btn btn-primary" href="login.html">Logout</a>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<!-- Page level plugins -->
<script src="vendor/chart.js/Chart.min.js"></script>
<script>
    // Set new default font family and font color to mimic Bootstrap's default styling
    Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
    Chart.defaults.global.defaultFontColor = '#858796';

    var myLineChart;
    var myBarChart;
    var myBarChart2;

    function number_format(number, decimals, dec_point, thousands_sep) {
        // *     example: number_format(1234.56, 2, ',', ' ');
        // *     return: '1 234,56'
        number = (number + '').replace(',', '').replace(' ', '');
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
            dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
            s = '',
            toFixedFix = function (n, prec) {
                var k = Math.pow(10, prec);
                return '' + Math.round(n * k) / k;
            };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }

    loadMonthSite('all');
    loadProductSite('all');
    loadStaffSite('all');

    function loadMonthSite(strSite) {
        switch (strSite) {
            case 'all': {
                $('#siteMonthSelected').text("All");
                break;
            }
            case 'HN': {
                $('#siteMonthSelected').text("Ha Noi");
                break;
            }
            case 'HCM' : {
                $('#siteMonthSelected').text("Ho Chi Minh");
                break;
            }
        }
        $.ajax({
            url: 'monthreveAction.php',
            dataType: 'text',
            cache: false,
            method: 'post',
            data: {
                site: strSite
            },
            success: function (res) {
                var data = JSON.parse(res);
                loadAreaChart(data.monthNameArr, data.monthEarnArr);
                console.log(data.monthNameArr)
            }
        });
    }


    function loadAreaChart(js_arrayMonth, js_arrayValue) {
        // Area Chart Example
        var ctx = document.getElementById("myAreaChart");
        if (myLineChart) {
            myLineChart.destroy();
        }
        myLineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: js_arrayMonth,
                datasets: [{
                    label: "Earnings",
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: js_arrayValue,
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'date'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            // Include a dollar sign in the ticks
                            callback: function (value, index, values) {
                                return '$' + number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false,
                    position: 'bottom',
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function (tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
                        }
                    }
                }
            }
        });

    }

    var ctx = document.getElementById("myPieChart");

    var js_arraySiteName = [<?php echo '"' . implode('","', $siteNameArr) . '"' ?>];
    var js_arraySiteValue = [<?php echo '"' . implode('","', $siteEarnArr) . '"' ?>];

    var myPieChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: js_arraySiteName,
            datasets: [{
                data: js_arraySiteValue,
                backgroundColor: ['#4e73df', '#1cc88a'],
                hoverBackgroundColor: ['#2e59d9', '#17a673'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            maintainAspectRatio: false,
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                caretPadding: 10,
                callbacks: {
                    label: function (tooltipItem, chart) {
                        var datasetLabel = chart.labels[tooltipItem.index] || '';
                        return datasetLabel + ': $' + number_format(chart.datasets[tooltipItem.datasetIndex].data[tooltipItem.index]);
                    }
                }
            },
            legend: {
                display: true,
                position: 'bottom',
            },
            cutoutPercentage: 80,
        },
    });

    function loadProductSite(strSite) {
        switch (strSite) {
            case 'all': {
                $('#siteProductSelected').text("All");
                break;
            }
            case 'HN': {
                $('#siteProductSelected').text("Ha Noi");
                break;
            }
            case 'HCM' : {
                $('#siteProductSelected').text("Ho Chi Minh");
                break;
            }
        }
        $.ajax({
            url: 'productreveAction.php',
            dataType: 'text',
            cache: false,
            method: 'post',
            data: {
                site: strSite
            },
            success: function (res) {
                var data = JSON.parse(res);
                loadProductBarChart(data.stoveIDArr, data.stoveEarnArr, data.stoveNmArr);
            }
        });
    }

    function loadProductBarChart(js_arrayStoveId, js_arrayStoveEarn, js_arrayStoveName) {
        var ctx = document.getElementById("myBarChart");
        if (myBarChart) {
            myBarChart.destroy();
        }
        myBarChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: js_arrayStoveId,
                datasets: [{
                    label: "Revenue",
                    backgroundColor: "#4e73df",
                    hoverBackgroundColor: "#2e59d9",
                    borderColor: "#4e73df",
                    data: js_arrayStoveEarn,
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'month'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 6
                        },
                        maxBarThickness: 25,
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            maxTicksLimit: 5,
                            padding: 10,
                            // Include a dollar sign in the ticks
                            callback: function (value, index, values) {
                                return '$' + number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        title: function (tooltipItem, chart) {
                            var datasetLabel = chart.labels[tooltipItem[0].index] + ' - ' + js_arrayStoveName[tooltipItem[0].index] || '';
                            return datasetLabel;
                        },
                        label: function (tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
                        }
                    }
                },
            }
        });
    }

    function loadStaffSite(strSite) {
        switch (strSite) {
            case 'all': {
                $('#siteStaffSelected').text("All");
                break;
            }
            case 'HN': {
                $('#siteStaffSelected').text("Ha Noi");
                break;
            }
            case 'HCM' : {
                $('#siteStaffSelected').text("Ho Chi Minh");
                break;
            }
        }
        $.ajax({
            url: 'staffreveAction.php',
            dataType: 'text',
            cache: false,
            method: 'post',
            data: {
                site: strSite
            },
            success: function (res) {
                var data = JSON.parse(res);
                loadStaffBarChart(data.staffIDArr, data.staffEarnArr, data.staffNameArr);
            }
        });
    }

    function loadStaffBarChart(js_arrayStaffId, js_arrayStaffEarn, js_arrayStaffName) {
        var ctx = document.getElementById("myBarChart2");

        if(myBarChart2){
            myBarChart2.destroy();
        }

        myBarChart2 = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: js_arrayStaffId,
                datasets: [{
                    label: "Revenue",
                    backgroundColor: "#4e73df",
                    hoverBackgroundColor: "#2e59d9",
                    borderColor: "#4e73df",
                    data: js_arrayStaffEarn,
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'staff'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 6
                        },
                        maxBarThickness: 25,
                    }],
                    yAxes: [{
                        ticks: {
                            min: 0,
                            maxTicksLimit: 5,
                            padding: 10,
                            // Include a dollar sign in the ticks
                            callback: function (value, index, values) {
                                return '$' + number_format(value);
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        title: function (tooltipItem, chart) {
                            var datasetLabel = chart.labels[tooltipItem[0].index] + ' - ' + js_arrayStaffName[tooltipItem[0].index] || '';
                            return datasetLabel;
                        },
                        label: function (tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': $' + number_format(tooltipItem.yLabel);
                        }
                    }
                },
            }
        });
    }
</script>
</body>

</html>
