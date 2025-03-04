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
    <div class="contact-sec	">
        <div class="container">

            <div>
                <div class="cont-details">
                    <div class="table-content table-responsive cart-table-content m-t-30">
                        <?php
                        $cid = $_GET['aptnumber'];
                        $ret = mysqli_query($con, "select tbluser.FirstName,tbluser.LastName,tbluser.Email,tbluser.MobileNumber,tblbook.ID as bid,tblbook.AptNumber,tblbook.AptDate,tblbook.AptTime,tblbook.Message,tblbook.BookingDate,tblbook.Remark,tblbook.Status,tblbook.RemarkDate from tblbook join tbluser on tbluser.ID=tblbook.UserID where tblbook.AptNumber='$cid'");
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
                                    <td><?php echo $row['FirstName']; ?> <?php echo $row['LastName']; ?></td>
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
                                    <td><?php echo $row['AptTime']; ?></td>
                                </tr>


                                <tr>
                                    <th>Apply Date</th>
                                    <td><?php echo $row['BookingDate']; ?></td>
                                </tr>


                                <tr>
                                    <th>Status</th>
                                    <td> <?php
                                            if ($row['Status'] == "") {
                                                echo "Not Updated Yet";
                                            }

                                            if ($row['Status'] == "Selected") {
                                                echo "Selected";
                                            }

                                            if ($row['Status'] == "Rejected") {
                                                echo "Rejected";
                                            }; ?></td>
                                </tr>
                            </table><?php } ?>
                    </div>
                </div>

            </div>

        </div>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>