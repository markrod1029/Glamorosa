<?php
include_once('includes/header.php');
include_once('includes/session.php');
include_once('includes/menubar.php');
?>

<section class="w3l-inner-banner-main">
    <div class="about-inner contact">
        <div class="container">
            <div class="main-titles-head text-center">
                <h3 class="header-name">Appointment Details</h3>
            </div>
        </div>
    </div>
    <section class=" header-sticky py-2">
        <?php include_once('includes/sidebar.php'); ?>
    </section>
</section>

<section class="w3l-contact-info-main" id="contact">
    <div class="contact-sec">
        <div class="container">
            <div>
                <div class="cont-details">
                    <div class="table-content table-responsive cart-table-content m-t-30">
                        <?php
                        $cid = $_GET['aptnumber'];
                        $ret = mysqli_query($con, "
                            SELECT 
                                tbluser.FirstName, 
                                tbluser.LastName, 
                                tbluser.Email, 
                                tbluser.MobileNumber,
                                tblbook.ID AS bid,
                                tblbook.AptNumber,
                                tblbook.AptDate,
                                tblbook.AptTimeStart,
                                tblbook.AptTimeEnd,
                                tblbook.Message,
                                tblbook.TransactionID,
                                tblbook.BookingDate,
                                tblbook.Remark,
                                tblbook.Status,
                                tblbook.RemarkDate,
                                packages.title AS PackageTitle,
                                packages.price AS PackagePrice,
                                events.title AS EventTitle
                            FROM tblbook
                            JOIN tbluser ON tbluser.ID = tblbook.UserID
                            LEFT JOIN packages ON tblbook.PackageID = packages.id
                            LEFT JOIN events ON packages.event_id = events.id
                            WHERE tblbook.AptNumber = '$cid'
                        ");
                        $cnt = 1;
                        while ($row = mysqli_fetch_array($ret)) {
                        ?>
                            <table class="table table-bordered">
                                <tr>
                                    <th>Appointment Number</th>
                                    <td><?php echo $row['AptNumber']; ?></td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td><?php echo $row['FirstName'] . ' ' . $row['LastName']; ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo $row['Email']; ?></td>
                                </tr>
                                <tr>
                                    <th>Mobile Number</th>
                                    <td><?php echo $row['MobileNumber']; ?></td>
                                </tr>
                                <tr>
                                    <th>Appointment Date</th>
                                    <td><?php echo $row['AptDate']; ?></td>
                                </tr>
                                <tr>
                                    <th>Appointment Time</th>
                                    <td><?php echo date("h:i A", strtotime($row['AptTimeStart'])) . " - " . date("h:i A", strtotime($row['AptTimeEnd'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Package</th>
                                    <td><?php echo $row['PackageTitle'] ? $row['PackageTitle'] : 'None'; ?></td>
                                </tr>
                                <tr>
                                    <th>Package Price</th>
                                    <td>
                                        <?php echo $row['PackagePrice'] ? '₱' . number_format($row['PackagePrice'], 2) : 'None'; ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Event</th>
                                    <td><?php echo $row['EventTitle'] ? $row['EventTitle'] : 'None'; ?></td>
                                </tr>
                                <tr>
                                    <th>Apply Date</th>
                                    <td><?php echo $row['BookingDate']; ?></td>
                                </tr>
                                <tr>
                                    <th>Transaction Code</th>
                                    <td><?php echo $row['TransactionID']; ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <?php
                                        if ($row['Status'] == "") {
                                            echo "Not Updated Yet";
                                        } elseif ($row['Status'] == "Selected") {
                                            echo "Selected";
                                        } elseif ($row['Status'] == "Approved") {
                                            echo "Approved";
                                        } elseif ($row['Status'] == "Rejected") {
                                            echo "Rejected";
                                        }
                                        ?>
                                    </td>
                                </tr>
                            </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>
