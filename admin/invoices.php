<?php
include_once('includes/header.php');

if (isset($_GET['completeid']) && isset($_GET['invoiceid'])) {
    $cid = intval($_GET['completeid']);
    $invoiceId = intval($_GET['invoiceid']); // Get invoice ID from URL
    $status = 'Completed';

    $stmt = $con->prepare("SELECT AptNumber FROM tblbook WHERE ID = ?");
    $stmt->bind_param("i", $cid);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if ($row) {
        $AptNumber = $row['AptNumber'];

        $stmt = $con->prepare("UPDATE tblbook SET Status = ? WHERE ID = ?");
        $stmt->bind_param("si", $status, $cid);
        $updateSuccess = $stmt->execute();

        if ($updateSuccess) {
            $stmt = $con->prepare("UPDATE tblinvoice SET status = ?, PostingDate = NOW() WHERE id = ?");
            $stmt->bind_param("si", $status, $invoiceId);
            $stmt->execute();

            echo '<script>alert("Appointment and Invoice marked as Completed successfully!");</script>';
            echo "<script>window.location.href='appointment-report.php';</script>";

        } else {
            echo '<script>alert("Error updating appointment. Please try again.");</script>';
        }
    } else {
        echo '<script>alert("Invalid Appointment ID!");</script>';
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
                    <h3 class="title1">Pending Invoice</h3>

                    <div class="table-responsive bs-example widget-shadow">
                        <h4>New Invoice:</h4>
                        <table id="serviceTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice ID</th>
                                    <th>Customer Name</th>
                                    <th>Invoice Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query = mysqli_query($con, "SELECT 
                                        i.id AS invoiceId, 
                                        CONCAT(u.FirstName, ' ', u.LastName) AS fullName, 
                                        i.PostingDate AS invoiceDate,
                                        a.ID AS appointmentId
                                    FROM tblinvoice i
                                    JOIN tblbook a ON i.appointmentNo = a.AptNumber
                                    JOIN tbluser u ON a.UserID = u.ID
                                    WHERE i.status = 'Approved'
                                ");

                                if (mysqli_num_rows($query) > 0) {
                                    $cnt = 1;
                                    while ($row = mysqli_fetch_assoc($query)) {
                                ?>
                                        <tr>
                                            <th scope="row"><?php echo $cnt++; ?></th>
                                            <th><?php echo htmlspecialchars($row['invoiceId']); ?></th>
                                            <th><?php echo htmlspecialchars($row['fullName']); ?></th>
                                            <th><?php echo htmlspecialchars($row['invoiceDate']); ?></th>
                                            <th width="150">
                                                <a href="view-invoice.php?invoiceid=<?php echo $row['invoiceId']; ?>" class="btn btn-primary btn-sm">View</a>
                                                <a href="invoices.php?completeid=<?php echo $row['appointmentId']; ?>&invoiceid=<?php echo $row['invoiceId']; ?>" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to mark this invoice as Completed?')">Complete</a>
                                            </th>
                                        </tr>
                                <?php
                                    }
                                } 
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>
</body>
