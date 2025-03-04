<?php include_once('includes/header.php'); ?>

<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/menubar.php'); ?>

        <!-- main content start-->
        <div id="page-wrapper" class="row calender widget-shadow">
            <div class="main-page">

                <!-- First Row -->
                <div class="row  ">
                    <div class="row-one">

                        <div class="col-md-4 widget">
                            <?php
                            $query1 = mysqli_query($con, "SELECT * FROM tbluser Where role = 'Customer'");
                            $totalcust = mysqli_num_rows($query1);
                            ?>
                            <div class="stats-left">
                                <h5>Total</h5>
                                <h4>Customer</h4>
                            </div>
                            <div class="stats-right">
                                <label><?php echo $totalcust; ?></label>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="col-md-4 widget states-mdl">
                            <?php
                            $query2 = mysqli_query($con, "SELECT * FROM tblbook WHERE Status is null");
                            $totalappointment = mysqli_num_rows($query2);
                            ?>
                            <div class="stats-left">
                                <h5>Total</h5>
                                <h4> Appointment</h4>
                            </div>
                            <div class="stats-right">
                                <label><?php echo $totalappointment; ?></label>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="col-md-4 widget states-last">
                            <?php
                            $query3 = mysqli_query($con, "SELECT * FROM tblinvoice WHERE status = 'Approved'");
                            $totalInvoice = mysqli_num_rows($query3);
                            ?>
                            <div class="stats-left">
                                <h5>Total</h5>
                                <h4>Invoice</h4>
                            </div>
                            <div class="stats-right">
                                <label><?php echo $totalInvoice; ?></label>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>

                <div class="mapouter">
                    <div class="gmap_canvas"><iframe class="gmap_iframe" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=600&amp;height=400&amp;hl=en&amp;q=Malasiqui-Santa Barbara Rd, 2421 Malasiqui, Philippines, Malasiqui, Philippines&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe><a href="https://sprunkin.com/">Sprunki Phases</a></div>
                    <style>
                        .mapouter {
                            position: relative;
                            text-align: right;
                            width: 100%;
                            height: 400px;
                            margin-top:30px;
                        }

                        .gmap_canvas {
                            overflow: hidden;
                            background: none !important;
                            width: 100%;
                            height: 400px;
                        }

                        .gmap_iframe {
                            width: 100% !important;
                            height: 400px !important;
                        }
                    </style>
                </div>



                <div class="clearfix"></div>
            </div>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>