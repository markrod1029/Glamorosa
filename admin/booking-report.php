<?php include_once('includes/header.php'); ?>

<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/menubar.php'); ?>

        <!-- main content start-->
        <div id="page-wrapper" class="row calender widget-shadow">
            <div class="main-page">
                <div class="tables">
                    <h3 class="title1">Booking Reports</h3>

                    <!-- Service Booking Summary Report -->
                    <div class="table-responsive bs-example widget-shadow">
                        <h4>Service Booking Summary:</h4>
                        <table id="serviceSummaryTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Service Name</th>
                                    <th>Total Bookings</th>
                                    <th>Completed</th>
                                    <th>Pending</th>
                                    <th>Total Revenue</th>
                                    <th>Last Booking Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query to get service booking summary based on actual DB structure
                                $ret = mysqli_query($con, "
                                    SELECT 
                                        s.ServiceName,
                                        s.Cost,
                                        COUNT(b.ID) AS TotalBookings,
                                        SUM(CASE WHEN b.Status = 'Completed' THEN 1 ELSE 0 END) AS CompletedBookings,
                                        SUM(CASE WHEN b.Status != 'Completed' OR b.Status IS NULL THEN 1 ELSE 0 END) AS PendingBookings,
                                        SUM(CASE WHEN b.Status = 'Completed' THEN COALESCE(s.Cost, 0) ELSE 0 END) AS TotalRevenue,
                                        MAX(b.AptDate) AS LastBookingDate
                                    FROM 
                                        tblservices s
                                    LEFT JOIN 
                                        tblbook b ON s.ID = b.ServiceID
                                    GROUP BY 
                                        s.ID, s.ServiceName, s.Cost
                                    ORDER BY TotalBookings DESC
                                ");
                                
                                $cnt = 1;
                                while ($row = mysqli_fetch_array($ret)) {
                                ?>
                                    <tr>
                                        <th scope="row"><?php echo $cnt; ?></th>
                                        <td><?php echo htmlspecialchars($row['ServiceName']); ?></td>
                                        <td><?php echo htmlspecialchars($row['TotalBookings']); ?></td>
                                        <td><?php echo htmlspecialchars($row['CompletedBookings']); ?></td>
                                        <td><?php echo htmlspecialchars($row['PendingBookings']); ?></td>
                                        <td>₱<?php echo number_format($row['TotalRevenue'], 2); ?></td>
                                        <td><?php echo $row['LastBookingDate'] ? htmlspecialchars($row['LastBookingDate']) : 'No bookings'; ?></td>
                                    </tr>
                                <?php
                                    $cnt++;
                                } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Monthly Booking Trends -->
                    <div class="table-responsive bs-example widget-shadow" style="margin-top: 30px;">
                        <h4>Monthly Booking Trends:</h4>
                        <table id="monthlyTrendsTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Month-Year</th>
                                    <th>Total Bookings</th>
                                    <th>Completed Services</th>
                                    <th>Revenue Generated</th>
                                    <th>Average Service Cost</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query for monthly trends
                                $ret2 = mysqli_query($con, "
                                    SELECT 
                                        DATE_FORMAT(b.AptDate, '%M %Y') AS MonthYear,
                                        DATE_FORMAT(b.AptDate, '%Y-%m') AS SortDate,
                                        COUNT(b.ID) AS TotalBookings,
                                        SUM(CASE WHEN b.Status = 'Completed' THEN 1 ELSE 0 END) AS CompletedServices,
                                        SUM(CASE WHEN b.Status = 'Completed' THEN s.Cost ELSE 0 END) AS RevenueGenerated,
                                        AVG(s.Cost) AS AvgServiceCost
                                    FROM 
                                        tblbook b
                                    JOIN 
                                        tblservices s ON b.ServiceID = s.ID
                                    WHERE 
                                        b.AptDate IS NOT NULL
                                    GROUP BY 
                                        DATE_FORMAT(b.AptDate, '%Y-%m')
                                    ORDER BY 
                                        SortDate DESC
                                    LIMIT 12
                                ");
                                
                                $cnt2 = 1;
                                while ($row2 = mysqli_fetch_array($ret2)) {
                                ?>
                                    <tr>
                                        <th scope="row"><?php echo $cnt2; ?></th>
                                        <td><?php echo htmlspecialchars($row2['MonthYear']); ?></td>
                                        <td><?php echo htmlspecialchars($row2['TotalBookings']); ?></td>
                                        <td><?php echo htmlspecialchars($row2['CompletedServices']); ?></td>
                                        <td>₱<?php echo number_format($row2['RevenueGenerated'], 2); ?></td>
                                        <td>₱<?php echo number_format($row2['AvgServiceCost'], 2); ?></td>
                                    </tr>
                                <?php
                                    $cnt2++;
                                } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Invoice Status Report -->
                    <div class="table-responsive bs-example widget-shadow" style="margin-top: 30px;">
                        <h4>Invoice Status Report:</h4>
                        <table id="invoiceStatusTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice Status</th>
                                    <th>Number of Invoices</th>
                                    <th>Total Amount</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query for invoice status summary
                                $ret3 = mysqli_query($con, "
                                    SELECT 
                                        COALESCE(i.status, 'No Invoice') AS InvoiceStatus,
                                        COUNT(*) AS InvoiceCount,
                                        SUM(CASE WHEN b.Status = 'Completed' THEN s.Cost ELSE 0 END) AS TotalAmount
                                    FROM 
                                        tblbook b
                                    JOIN 
                                        tblservices s ON b.ServiceID = s.ID
                                    LEFT JOIN 
                                        tblinvoice i ON b.AptNumber = i.appointmentNo
                                    GROUP BY 
                                        COALESCE(i.status, 'No Invoice')
                                ");
                                
                                // Get total count for percentage calculation
                                $totalCount = 0;
                                $totalAmount = 0;
                                $statusData = array();
                                
                                while ($row3 = mysqli_fetch_array($ret3)) {
                                    $statusData[] = $row3;
                                    $totalCount += $row3['InvoiceCount'];
                                    $totalAmount += $row3['TotalAmount'];
                                }
                                
                                $cnt3 = 1;
                                foreach ($statusData as $row3) {
                                    $percentage = $totalCount > 0 ? ($row3['InvoiceCount'] / $totalCount) * 100 : 0;
                                ?>
                                    <tr>
                                        <th scope="row"><?php echo $cnt3; ?></th>
                                        <td><?php echo htmlspecialchars($row3['InvoiceStatus']); ?></td>
                                        <td><?php echo htmlspecialchars($row3['InvoiceCount']); ?></td>
                                        <td>₱<?php echo number_format($row3['TotalAmount'], 2); ?></td>
                                        <td><?php echo number_format($percentage, 1); ?>%</td>
                                    </tr>
                                <?php
                                    $cnt3++;
                                } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Recent Bookings -->
                    <div class="table-responsive bs-example widget-shadow" style="margin-top: 30px;">
                        <h4>Recent Bookings (Last 10):</h4>
                        <table id="recentBookingsTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Appointment #</th>
                                    <th>Service Name</th>
                                    <th>Appointment Date</th>
                                    <th>Time Slot</th>
                                    <th>Status</th>
                                    <th>Cost</th>
                                    <th>Booking Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Query for recent bookings
                                $ret4 = mysqli_query($con, "
                                    SELECT 
                                        b.AptNumber,
                                        s.ServiceName,
                                        b.AptDate,
                                        b.AptTimeStart,
                                        b.AptTimeEnd,
                                        b.Status,
                                        s.Cost,
                                        b.BookingDate
                                    FROM 
                                        tblbook b
                                    JOIN 
                                        tblservices s ON b.ServiceID = s.ID
                                    ORDER BY 
                                        b.BookingDate DESC
                                    LIMIT 10
                                ");
                                
                                $cnt4 = 1;
                                while ($row4 = mysqli_fetch_array($ret4)) {
                                ?>
                                    <tr>
                                        <th scope="row"><?php echo $cnt4; ?></th>
                                        <td><?php echo htmlspecialchars($row4['AptNumber']); ?></td>
                                        <td><?php echo htmlspecialchars($row4['ServiceName']); ?></td>
                                        <td><?php echo htmlspecialchars($row4['AptDate']); ?></td>
                                        <td><?php echo htmlspecialchars($row4['AptTimeStart']) . ' - ' . htmlspecialchars($row4['AptTimeEnd']); ?></td>
                                        <td>
                                            <span class="badge <?php echo ($row4['Status'] == 'Completed') ? 'badge-success' : 'badge-warning'; ?>">
                                                <?php echo htmlspecialchars($row4['Status'] ?: 'Pending'); ?>
                                            </span>
                                        </td>
                                        <td>₱<?php echo number_format($row4['Cost'], 2); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($row4['BookingDate'])); ?></td>
                                    </tr>
                                <?php
                                    $cnt4++;
                                } ?>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>

    <!-- DataTables JavaScript for better table functionality -->
    <script>
        $(document).ready(function() {
            $('#serviceSummaryTable, #monthlyTrendsTable, #invoiceStatusTable, #recentBookingsTable').DataTable({
                "pageLength": 10,
                "ordering": true,
                "searching": true,
                "lengthChange": true,
                "info": true
            });
        });
    </script>

</body>