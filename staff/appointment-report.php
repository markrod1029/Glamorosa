<?php include_once('includes/header.php'); ?>

<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/menubar.php'); ?>

        <!-- main content start-->
        <div id="page-wrapper" class="row calender widget-shadow">
            <div class="main-page">
                <div class="tables">
                    <h3 class="title1">Appointment Report</h3>

                    <div class="table-responsive bs-example widget-shadow">
                        <h4>Appointment Completed:</h4>
                        <table id="serviceTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Appointment Number</th>
                                    <th>Name</th>
                                    <th>Mobile Number</th>
                                    <th>Appointment Date</th>
                                    <th>Appointment Time</th>
                                    <th>Package</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $serviceId = $user['sID'];
                                $ret = mysqli_query($con, "
                                    SELECT 
                                        tbluser.FirstName,
                                        tbluser.LastName,
                                        tbluser.Email,
                                        tbluser.MobileNumber,
                                        tblbook.ID as bid,
                                        tblbook.AptNumber,
                                        tblbook.AptDate,
                                        tblbook.AptTimeStart,
                                        tblbook.AptTimeEnd,
                                        tblbook.Message,
                                        tblbook.BookingDate,
                                        tblbook.Status,
                                        packages.title AS PackageTitle,
                                        packages.price AS PackagePrice
                                    FROM tblbook
                                    JOIN tbluser ON tbluser.ID = tblbook.UserID
                                    LEFT JOIN packages ON tblbook.PackageID = packages.id
                                    LEFT JOIN events ON packages.event_id = events.id
                                    WHERE tblbook.Status = 'Completed' AND events.service_id = '$serviceId'
                                ");

                                $cnt = 1;
                                while ($row = mysqli_fetch_array($ret)) {
                                ?>
                                    <tr>
                                        <td><?php echo $cnt++; ?></td>
                                        <td><?php echo $row['AptNumber']; ?></td>
                                        <td><?php echo $row['FirstName'] . ' ' . $row['LastName']; ?></td>
                                        <td><?php echo $row['MobileNumber']; ?></td>
                                        <td><?php echo $row['AptDate']; ?></td>
                                        <td><?php echo date("h:i A", strtotime($row['AptTimeStart'])) . " - " . date("h:i A", strtotime($row['AptTimeEnd'])); ?></td>
                                        <td><?php echo $row['PackageTitle'] ?? 'None'; ?></td>
                                        <td>₱<?php echo number_format($row['PackagePrice'], 2); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php include_once('includes/footer.php'); ?>
