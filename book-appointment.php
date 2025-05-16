<?php
include_once('includes/session.php');
include_once('includes/header.php');
include_once('includes/menubar.php');

$service_id = isset($_GET['service_id']) ? intval($_GET['service_id']) : 0;

// Get service info
$query = mysqli_query($con, "SELECT s.ID, s.user_id, s.ServiceName, s.Cost, s.ServiceDescription, s.Image, 
           u.FirstName, u.LastName, u.MobileNumber, u.Email 
    FROM tblservices s
    LEFT JOIN tbluser u ON s.user_id = u.ID
    WHERE s.ID = '$service_id'");
$service = mysqli_fetch_array($query);
$service_name = $service ? $service['ServiceName'] : "Unknown Service";
$fullname = $service ? ($service['FirstName'] . ' ' . $service['LastName']) : "Unknown Name";

// Initialize
$selected_date = $_POST['date'] ?? '';
$book_time = $_POST['time'] ?? '';
$transaction_id = $_POST['transaction_id'] ?? '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['final_booking'])) {
    if (!isset($_SESSION['customer'])) {
        echo "<script>alert('Please log in to book an appointment.'); window.location.href='login.php';</script>";
        exit();
    }

    $uid = $_SESSION['customer'];
    $adate = $_POST['date'];
    $atime = $_POST['time'];
    $txn = $_POST['transaction_id'];

    // Parse time (e.g., "07:00 AM - 08:59 AM")
    [$atimeS, $atimeE] = explode(" - ", $atime);
    $aptnumber = mt_rand(100000000, 999999999);

    $checkQuery = mysqli_query($con, "SELECT * FROM tblbook 
        WHERE AptDate = '$adate' 
        AND ServiceID = '$service_id' 
        AND (Status IS NULL OR Status NOT IN ('Approved')) 
        AND (
            ('$atimeS' < AptTimeEnd AND '$atimeE' > AptTimeStart)
        )");

    if (mysqli_num_rows($checkQuery) > 0) {
        echo "<script>alert('You already have an appointment during this time.');</script>";
    } else {
        $query = mysqli_query($con, "INSERT INTO tblbook (UserID, ServiceID, AptNumber, AptDate, AptTimeStart, AptTimeEnd, Message, TransactionID) 
        VALUES ('$uid', '$service_id', '$aptnumber', '$adate', '$atimeS', '$atimeE', '', '$txn')");

        if ($query) {
            $_SESSION['aptno'] = $aptnumber;
            echo "<script>window.location.href='thank-you.php'</script>";
        } else {
            echo '<script>alert("Something went wrong. Please try again.")</script>';
        }
    }
}
?>

<!-- HTML -->

<div class="text-center mt-3">
    <h3>Service: <?= htmlspecialchars($service_name) ?></h3>
    <p class="text-muted">Price: ₱<?= number_format($service['Cost'], 2) ?></p>
</div>

<h3 class="text-center mt-4">Please select your preferred date and time of appointment.</h3>

<div class="container mt-4 mb-5">
    <form method="POST" class="text-center mb-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <label>Select a Date</label>
                <input type="text" id="appointmentDate" name="date" class="form-control" placeholder="Choose Date" value="<?= htmlspecialchars($selected_date) ?>">
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Show Slots</button>
    </form>

    <?php if (!empty($selected_date)): ?>
        <div class="row justify-content-center mt-4">
            <div class="col-md-10">
                <h5 class="text-center">Selected: <?= htmlspecialchars($selected_date) ?></h5>
                <table class="table table-bordered text-center">
                    <thead class="table-light">
                        <tr>
                            <th>Time</th>
                            <th>Available Slots</th>
                            <th>In Percentage</th>
                            <th>Select</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $slots = [
                            "07:00 AM - 08:59 AM",
                            "09:00 AM - 10:59 AM",
                            "11:00 AM - 12:59 PM",
                            "01:00 PM - 02:59 PM",
                            "03:00 PM - 04:59 PM"
                        ];
                        $max_slot_capacity = 1;

                        foreach ($slots as $slot):
                            [$start, $end] = explode(" - ", $slot);

                            $result = mysqli_query($con, "SELECT COUNT(*) as booked_count 
        FROM tblbook 
        WHERE AptDate = '$selected_date' 
        AND ServiceID = '$service_id'
        AND (Status IS NULL OR Status NOT IN ('Cancelled', 'Declined'))
        AND ('$start' < AptTimeEnd AND '$end' > AptTimeStart)");

                            $row = mysqli_fetch_assoc($result);
                            $booked = intval($row['booked_count']);
                            $available = $max_slot_capacity - $booked;
                            $percentage = round(($available / $max_slot_capacity) * 100) . "%";
                        ?>
                            <tr>
                                <td><?= $slot ?></td>
                                <td><?= $available ?> / <?= $max_slot_capacity ?></td>
                                <td><?= $percentage ?></td>
                                <td>
                                    <?php if ($available > 0): ?>
                                        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#paymentModal" onclick="selectSlot('<?= $selected_date ?>', '<?= $slot ?>')">Select</button>
                                    <?php else: ?>
                                        <button class="btn btn-sm btn-dark" disabled>Book</button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Appointment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
           <div class="modal-body text-center">
    <img src="assets/images/qr/qr.png" alt="QR Code" width="200" class="mb-3">
    <p>Scan the QR code and enter the transaction ID below.</p>

    <?php
    $price = $service['Cost'];
    $tax = $price * 0.10;
    $total = $price + $tax;
    ?>

    <div class="mb-2 text-start">
        <label class="form-label">Service Price</label>
        <input type="text" class="form-control" value="₱<?= number_format($price, 2) ?>" readonly>

        <label class="form-label mt-2">Tax (10%)</label>
        <input type="text" class="form-control" value="₱<?= number_format($tax, 2) ?>" readonly>

        <label class="form-label mt-2">Total Amount</label>
        <input type="text" class="form-control" value="₱<?= number_format($total, 2) ?>" readonly>

    <input type="hidden" name="date" id="modalDate">
    <input type="hidden" name="time" id="modalTime">

    <label class="form-label mt-3">Transaction ID</label>
    <input type="text" name="transaction_id" class="form-control" placeholder="Enter Transaction ID" required>
</div>
    </div>


            <div class="modal-footer">
                <button type="submit" name="final_booking" class="btn btn-primary  mx-auto ">Confirm Booking</button>
            </div>
        </form>
    </div>
</div>

<!-- Flatpickr & Bootstrap JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    flatpickr("#appointmentDate", {
        dateFormat: "Y-m-d",
        minDate: "today"
    });

    function selectSlot(date, time) {
        document.getElementById('modalDate').value = date;
        document.getElementById('modalTime').value = time;
    }
</script>

<!-- Bootstrap 5 JS (required for modal) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>