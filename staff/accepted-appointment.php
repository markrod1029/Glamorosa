<?php include_once('includes/header.php');



?>

<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/menubar.php'); ?>

        <!-- main content start-->
        <div id="page-wrapper" class="row calender widget-shadow">
            <div class="main-page">
                <div class="tables">
                    <h3 class="title1">Accepted Appointment</h3>



                    <div class="table-responsive bs-example widget-shadow">
                        <h4>New Appointment:</h4>
                        <table id="serviceTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th> Appointment Number</th>
                                    <th>Name</th>
                                    <th>Mobile Number</th>
                                    <th>Appointment Date</th>
                                    <th>Appointment Time</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $serviceId = $user['sID'];
                                $ret = mysqli_query($con, "SELECT tbluser.FirstName,tbluser.LastName,tbluser.Email,tbluser.MobileNumber,tblbook.ID as bid,tblbook.AptNumber,tblbook.AptDate,tblbook.AptTimeStart,tblbook.AptTimeEnd,tblbook.Message,tblbook.BookingDate,tblbook.Status from tblbook join tbluser on tbluser.ID=tblbook.UserID where tblbook.Status='Approved' AND tblbook.ServiceID='$serviceId' ");
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($ret)) {

                                ?>

                                    <tr>
                                        <th scope="row"><?php echo $cnt; ?></th>
                                        <td><?php echo $row['AptNumber']; ?></td>
                                        <td><?php echo $row['FirstName']; ?> <?php echo $row['LastName']; ?></td>
                                        <td><?php echo $row['MobileNumber']; ?></td>
                                        <td><?php echo $row['AptDate']; ?></td>

                                        <td>
                                            <?php
                                            echo date("h:i A", strtotime($row['AptTimeStart'])) . " - " . date("h:i A", strtotime($row['AptTimeEnd']));
                                            ?>

                                        </td>

                                        <?php if ($row['Status'] == "") { ?>

                                            <td class="font-w600"><?php echo "Not Updated Yet"; ?></td>
                                        <?php } else { ?>
                                            <td><?php echo $row['Status']; ?></td><?php } ?>
                                        <td width="150"><a href="view-appointment.php?viewid=<?php echo $row['bid']; ?>" class="btn btn-primary btn-sm">View</a>
                                        </td>
                                    </tr> <?php
                                            $cnt = $cnt + 1;
                                        } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>