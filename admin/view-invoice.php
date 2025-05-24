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

                    // Tax rate - ADJUSTAN DITO
                    $taxRate = 0.10; // 10% VAT (Philippine standard)
                    
                    $invid = intval($_GET['invoiceid']);
                    $query = mysqli_query($con, "
                        SELECT DISTINCT 
                            DATE(i.PostingDate) AS invoicedate,
                            b.AptNumber, b.AptDate, b.AptTimeStart, b.AptTimeEnd,
                            u.FirstName, u.LastName, u.Email, u.MobileNumber, u.RegDate,
                            GROUP_CONCAT(s.ServiceName SEPARATOR ', ') AS ServicesAvailed
                        FROM tblinvoice i
                        JOIN tblbook b ON b.AptNumber = i.appointmentNo
                        JOIN tbluser u ON u.ID = b.UserID
                        JOIN tblservices s ON s.ID = b.ServiceID
                        WHERE i.id = '$invid'
                        GROUP BY i.id, b.AptNumber, u.ID
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
                                    <th>Services Availed</th>
                                    <td colspan="2"><?php echo htmlspecialchars($row['ServicesAvailed']); ?></td>
                                    <th>Appointment Date</th>
                                    <td colspan="2"><?php echo htmlspecialchars($row['AptDate']); ?></td>
                                </tr>
                                <tr>
                                    <th>Registration Date</th>
                                    <td><?php echo htmlspecialchars($row['RegDate']); ?></td>
                                    <th>Appointment Time</th>
                                    <td><?php echo htmlspecialchars($row['AptTimeStart']) . ' - ' . htmlspecialchars($row['AptTimeEnd']); ?></td>
                                    <th>Invoice Date</th>
                                    <td><?php echo htmlspecialchars($row['invoicedate']); ?></td>
                                </tr>
                            <?php } ?>
                            </table>

                            <table class="table table-bordered">
                                <tr>
                                    <th colspan="5">Services Details</th>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>Service</th>
                                    <th>Description</th>
                                    <th>Cost (Before Tax)</th>
                                    <th>Tax (<?php echo ($taxRate * 100); ?>%)</th>
                                </tr>

                                <?php
                                $query = mysqli_query($con, "
                                  SELECT s.ServiceName, s.ServiceDescription, s.Cost  
                                  FROM tblinvoice i
                                  JOIN tblbook b ON b.AptNumber = i.appointmentNo
                                  JOIN tblservices s ON s.ID = b.ServiceID
                                  WHERE  i.id = '$invid'
                                ");

                                $cnt = 1;
                                $subtotalBeforeTax = 0;
                                $totalTax = 0;
                                while ($row = mysqli_fetch_assoc($query)) {
                                    $serviceCost = $row['Cost'];
                                    $serviceTax = $serviceCost * $taxRate;
                                    $subtotalBeforeTax += $serviceCost;
                                    $totalTax += $serviceTax;
                                ?>

                                    <tr>
                                        <th><?php echo $cnt++; ?></th>
                                        <td><?php echo htmlspecialchars($row['ServiceName']); ?></td>
                                        <td><?php echo htmlspecialchars($row['ServiceDescription'] ? $row['ServiceDescription'] : 'No description available'); ?></td>
                                        <td>₱ <?php echo number_format($serviceCost, 2); ?></td>
                                        <td>₱ <?php echo number_format($serviceTax, 2); ?></td>
                                    </tr>
                                <?php } ?>

                                <!-- Summary Section -->
                                <tr style="background-color: #f8f9fa;">
                                    <th colspan="3" style="text-align:center">Subtotal (Before Tax)</th>
                                    <th colspan="2">₱ <?php echo number_format($subtotalBeforeTax, 2); ?></th>
                                </tr>
                                <tr style="background-color: #f8f9fa;">
                                    <th colspan="3" style="text-align:center">Total Tax (<?php echo ($taxRate * 100); ?>%)</th>
                                    <th colspan="2">₱ <?php echo number_format($totalTax, 2); ?></th>
                                </tr>
                                <tr style="background-color: #e9ecef; font-weight: bold; font-size: 1.1em;">
                                    <th colspan="3" style="text-align:center">Grand Total (Tax Inclusive)</th>
                                    <th colspan="2">₱ <?php echo number_format($subtotalBeforeTax + $totalTax, 2); ?></th>
                                </tr>
                            </table>

                            <!-- Tax Summary Box -->
                            <div class="alert alert-info" style="margin-top: 15px;">
                                <h5><strong>Tax Breakdown:</strong></h5>
                                <p class="mb-1"><strong>Subtotal:</strong> ₱<?php echo number_format($subtotalBeforeTax, 2); ?></p>
                                <p class="mb-1"><strong>VAT (<?php echo ($taxRate * 100); ?>%):</strong> ₱<?php echo number_format($totalTax, 2); ?></p>
                                <p class="mb-0"><strong>Total Amount:</strong> ₱<?php echo number_format($subtotalBeforeTax + $totalTax, 2); ?></p>
                            </div>
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
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .alert { 
            padding: 15px; 
            margin-bottom: 20px; 
            border: 1px solid #bee5eb; 
            background-color: #d1ecf1; 
            border-radius: 4px; 
        }
        .alert h5 { margin-top: 0; color: #0c5460; }
        .alert p { margin: 5px 0; color: #0c5460; }
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