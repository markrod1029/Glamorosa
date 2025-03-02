<?php include_once('includes/header.php'); ?>

<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/menubar.php'); ?>

        <!-- main content start -->
        <div id="page-wrapper" class="row calender widget-shadow">
            <div class="main-page">
                <div class="tables">
                    <h3 class="title1">Invoice Details</h3>

                    <?php
                    include_once('includes/dbconnection.php');

                    $invid = intval($_GET['invoiceid']);
                    $query = mysqli_query($con, "
                        SELECT DISTINCT 
                            DATE(i.PostingDate) AS invoicedate,
                            b.AptNumber, 
                            u.FirstName, u.LastName, u.Email, u.MobileNumber, u.RegDate
                        FROM tblinvoice i
                        JOIN tblbook b ON b.AptNumber = i.appointmentNo
                        JOIN tbluser u ON u.ID = b.UserID
                        JOIN tblservices s ON s.ID = b.ServiceID
                        WHERE i.id = '$invid'
                    ");

                    while ($row = mysqli_fetch_assoc($query)) {
                    ?>

                        <div class="table-responsive bs-example widget-shadow" id="invoiceCard">
                            <h4>Invoice #<?php echo $row['AptNumber']; ?></h4>
                            <table class="table table-bordered">
                                <tr>
                                    <th colspan="6">Customer Details</th>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td><?php echo htmlspecialchars($row['FirstName'] . " " . $row['LastName']); ?></td>
                                    <th>Contact no.</th>
                                    <td><?php echo htmlspecialchars($row['MobileNumber']); ?></td>
                                    <th>Email</th>
                                    <td><?php echo htmlspecialchars($row['Email']); ?></td>
                                </tr>
                                <tr>
                                    <th>Registration Date</th>
                                    <td><?php echo htmlspecialchars($row['RegDate']); ?></td>
                                    <th>Invoice Date</th>
                                    <td colspan="3"><?php echo htmlspecialchars($row['invoicedate']); ?></td>
                                </tr>
                            <?php } ?>
                            </table>

                            <table class="table table-bordered">
                                <tr>
                                    <th colspan="3">Services Details</th>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>Service</th>
                                    <th>Cost</th>
                                </tr>

                                <?php
                                $query = mysqli_query($con, "
                                  SELECT s.ServiceName, s.Cost  
                                  FROM tblinvoice i
                                  JOIN tblbook b ON b.AptNumber = i.appointmentNo
                                  JOIN tblservices s ON s.ID = b.ServiceID
                                  WHERE  i.id = '$invid'
                                ");

                                $cnt = 1;
                                $gtotal = 0;
                                while ($row = mysqli_fetch_assoc($query)) {
                                    $subtotal = $row['Cost'];
                                    $gtotal += $subtotal;
                                ?>

                                    <tr>
                                        <th><?php echo $cnt++; ?></th>
                                        <td>₱ <?php echo htmlspecialchars($row['ServiceName']); ?></td>
                                        <td>₱ <?php echo number_format($subtotal, 2); ?></td>
                                    </tr>
                                <?php } ?>

                                <tr>
                                    <th colspan="2" style="text-align:center">Grand Total</th>
                                    <th>₱ <?php echo number_format($gtotal, 2); ?></th>
                                </tr>
                            </table>
                        </div>

                        <p style="margin-top:1%" align="center">
                            <button onclick="CallPrint()" class="btn btn-primary">
                                <i class="fa fa-print"></i> Print Invoice
                            </button>
                        </p>

                </div>
            </div>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>

</body>

<script>
function CallPrint() {
    var printContents = document.getElementById("invoiceCard").innerHTML;
    var originalContents = document.body.innerHTML;

    var printWindow = window.open('', '', 'width=800,height=900');
    printWindow.document.write('<html><head><title>Print Invoice</title>');
    printWindow.document.write('<style>');
    printWindow.document.write(`
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    `);
    printWindow.document.write('</style></head><body>');
    printWindow.document.write(printContents);
    printWindow.document.write('</body></html>');

    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}
</script>
