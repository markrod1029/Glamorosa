<?php 
include_once('includes/header.php'); 

if (isset($_POST['submit'])) {
    $cid = mysqli_real_escape_string($con, $_GET['viewid']);
    $remark = mysqli_real_escape_string($con, $_POST['remark']);
    $status = mysqli_real_escape_string($con, $_POST['status']);
    $AptNumber = mysqli_real_escape_string($con, $_POST['AptNumber']);

    // Update the appointment status
    $query = mysqli_query($con, "UPDATE tblbook SET Remark='$remark', Status='$status', RemarkDate=NOW() WHERE ID='$cid'");

    // Insert invoice only if the appointment is approved
    if ($query && $status === 'Approved') {
        $query1 = mysqli_query($con, "INSERT INTO tblinvoice (appointmentNo, status, PostingDate) VALUES ('$AptNumber', '$status', NOW())");
    }

    if ($query) {
        if( $status === 'Approved') {
            echo '<script>alert("Appointment status updated successfully.");</script>';
            echo "<script>window.location.href='accepted-appointment.php';</script>";
        } else {
                echo '<script>alert("Appointment status updated successfully.");</script>';
                echo "<script>window.location.href='rejected-appointment.php';</script>";
    
        }
      
    } else {
        echo '<script>alert("Something went wrong. Please try again.");</script>';
    }
}
?>

<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/menubar.php'); ?>

        <div id="page-wrapper" class="row calender widget-shadow">
            <div class="main-page">
                <div class="tables">
                    <h3 class="title1">View Appointment</h3>
                    <div class="table-responsive bs-example widget-shadow">
                        <h4>Appointment Details:</h4>

                        <?php
                        if (!isset($_GET['viewid']) || empty($_GET['viewid'])) {
                            echo "<p class='text-danger'>Invalid appointment ID.</p>";
                        } else {
                            $cid = mysqli_real_escape_string($con, $_GET['viewid']);
                            $ret = mysqli_query($con, "SELECT 
                                u.FirstName, u.LastName, u.Email, u.MobileNumber, 
                                b.ID AS bid, b.AptNumber, b.AptDate, b.AptTime, 
                                b.Message, b.BookingDate, b.Remark, b.Status, b.RemarkDate 
                                FROM tblbook b 
                                JOIN tbluser u ON u.ID = b.UserID 
                                WHERE b.ID='$cid'");

                            if (mysqli_num_rows($ret) > 0) {
                                $row = mysqli_fetch_assoc($ret);
                        ?>

                                <table class="table table-bordered">
                                    <tr><th>Appointment Number</th><td><?php echo $row['AptNumber']; ?></td></tr>
                                    <tr><th>Name</th><td><?php echo $row['FirstName'] . ' ' . $row['LastName']; ?></td></tr>
                                    <tr><th>Email</th><td><?php echo $row['Email']; ?></td></tr>
                                    <tr><th>Mobile Number</th><td><?php echo $row['MobileNumber']; ?></td></tr>
                                    <tr><th>Appointment Date</th><td><?php echo $row['AptDate']; ?></td></tr>
                                    <tr><th>Appointment Time</th><td><?php echo $row['AptTime']; ?></td></tr>
                                    <tr><th>Apply Date</th><td><?php echo $row['BookingDate']; ?></td></tr>
                                    <tr><th>Status</th>
                                        <td>
                                            <?php echo $row['Status'] ? $row['Status'] : "Not Updated Yet"; ?>
                                        </td>
                                    </tr>
                                </table>

                                <?php if (empty($row['Status'])) { ?>
                                    <form method="post">
                                        <table class="table table-bordered">
                                            <tr>
                                                <th>Remark:</th>
                                                <td><textarea name="remark" class="form-control" rows="3" required></textarea></td>
                                            </tr>
                                            <tr>
                                                <th>Status:</th>
                                                <td>
                                                    <select name="status" class="form-control" required>
                                                        <option value="">Select</option>
                                                        <option value="Approved">Approved</option>
                                                        <option value="Rejected">Rejected</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <tr class="text-center">
                                                <input type="hidden" name="AptNumber" value="<?php echo $row['AptNumber']; ?>">
                                                <td colspan="2"><button type="submit" name="submit" class="btn btn-primary">Submit</button></td>
                                            </tr>
                                        </table>
                                    </form>
                                <?php } else { ?>
                                    <table class="table table-bordered">
                                        <tr><th>Remark</th><td><?php echo $row['Remark']; ?></td></tr>
                                        <tr><th>Status</th><td><?php echo $row['Status']; ?></td></tr>
                                        <tr><th>Remark Date</th><td><?php echo $row['RemarkDate']; ?></td></tr>
                                    </table>
                                <?php } ?>
                        <?php 
                            } else {
                                echo "<p class='text-danger'>No appointment found.</p>";
                            }
                        } 
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <?php include_once('includes/footer.php'); ?>
    </div>
</body>
