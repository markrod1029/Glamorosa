<?php
include_once('includes/header.php');
include_once('includes/session.php');
include_once('includes/menubar.php');
?>

<section class="w3l-inner-banner-main">
    <div class="about-inner contact">
        <div class="container">
            <div class="main-titles-head text-center">
                <h3 class="header-name">Appointment History</h3>
            </div>
        </div>
    </div>
        <?php include_once('includes/sidebar.php'); ?>


</section>

<!-- Main Content Section -->
<section class="w3l-contact-info-main" id="contact">
    <div class="container">
        <div class="contact-sec flex gap-6">
            <!-- Table Section -->
            <div class="w-3/4">
                <div class="table-content table-responsive cart-table-content m-t-30">
                    <table border="2" class="table w-full">
                        <thead class="bg-gray-300">
                            <tr>
                                <th>#</th>
                                <th>Appointment Number</th>
                                <th>Appointment Date</th>
                                <th>Appointment Time</th>
                                <th>Appointment Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $userid = $_SESSION['customer'];
                            $query = mysqli_query($con, "SELECT tbluser.ID as uid, tbluser.FirstName, tbluser.LastName, tbluser.Email, tbluser.MobileNumber, tblbook.ID as bid, tblbook.AptNumber, tblbook.AptDate, tblbook.AptTimeStart, tblbook.AptTimeEnd, tblbook.Message, tblbook.BookingDate, tblbook.Status FROM tblbook JOIN tbluser ON tbluser.ID=tblbook.UserID WHERE tbluser.ID='$userid'");
                            $cnt = 1;
                            $count = mysqli_num_rows($query);
                            if ($count > 0) {
                                while ($row = mysqli_fetch_array($query)) { ?>
                                    <tr>
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo $row['AptNumber']; ?></td>
                                        <td><?php echo $row['AptDate']; ?></td>
                                        <td> <?php
                                            echo date("h:i A", strtotime($row['AptTimeStart'])) . " - " . date("h:i A", strtotime($row['AptTimeEnd']));
                                            ?></td>
                                        <td>
                                            <?php echo $row['Status'] ? $row['Status'] : "Waiting for confirmation"; ?>
                                        </td>
                                        <td>
                                            <a href="appointment-detail.php?aptnumber=<?php echo $row['AptNumber']; ?>" class="btn btn-primary">View</a>
                                        </td>
                                    </tr>
                                <?php
                                    $cnt++;
                                }
                            } else { ?>
                                <tr>
                                    <td colspan="6" class="text-red-500 text-center">No Record Found</td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>