<?php
include_once('includes/session.php');
include_once('includes/header.php');
include_once('includes/menubar.php');

$service_id = isset($_GET['service_id']) ? intval($_GET['service_id']) : 0;

$query = mysqli_query($con, "SELECT s.ID, s.user_id, s.ServiceName, s.Cost, s.ServiceDescription, s.Image, 
           u.FirstName, u.LastName, u.MobileNumber, u.Email 
    FROM tblservices s
    LEFT JOIN tbluser u ON s.user_id = u.ID
    WHERE s.ID = '$service_id'");

$service = mysqli_fetch_array($query);
$service_name = $service ? $service['ServiceName'] : "Unknown Service";
$fullname = $service ? ($service['FirstName'] . ' ' . $service['LastName']) : "Unknown Name";

if (isset($_POST['submit'])) {
    if (!isset($_SESSION['customer'])) {
        echo "<script>alert('Please log in to book an appointment.'); window.location.href='login.php';</script>";
        exit();
    }

    $uid = $_SESSION['customer'];
    $adate = $_POST['adate'];
    $atimeS = $_POST['atimeS'];
    $atimeE = $_POST['atimeE'];
    $msg = $_POST['message'];
    $aptnumber = mt_rand(100000000, 999999999);

    // **Check for overlapping appointments**
    $checkQuery = mysqli_query($con, "SELECT * FROM tblbook 
    WHERE AptDate = '$adate' 
    AND ServiceID = '$service_id' -- Check only for the same service
    AND (Status IS NULL OR Status NOT IN ('Approved')) 
    AND (
        ('$atimeS' < AptTimeEnd AND '$atimeE' > AptTimeStart) -- Prevents Overlap
    )");


    if (mysqli_num_rows($checkQuery) > 0) {
        echo "<script>alert('You already have an appointment during this time. Please select another time slot.');</script>";
    } else {
        // **Insert New Appointment**
        $query = mysqli_query($con, "INSERT INTO tblbook (UserID, ServiceID, AptNumber, AptDate, AptTimeStart, AptTimeEnd, Message) 
            VALUES ('$uid', '$service_id', '$aptnumber', '$adate', '$atimeS', '$atimeE', '$msg')");

        if ($query) {
            $_SESSION['aptno'] = $aptnumber;
            echo "<script>window.location.href='thank-you.php'</script>";
        } else {
            echo '<script>alert("Something went wrong. Please try again.")</script>';
        }
    }
}
?>

<section class="w3l-inner-banner-main">
    <div class="about-inner contact">
        <div class="container">
            <div class="main-titles-head text-center">
                <h3 class="header-name">Book Appointment</h3>
            </div>
        </div>
    </div>
</section>

<section class="w3l-contact-info-main" id="contact">
    <div class="contact-sec">
        <div class="container">
            <div class="d-grid contact-view">
                <div class="cont-details">
                    <?php
                    $ret = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='contactus'");
                    while ($row = mysqli_fetch_array($ret)) {
                    ?>
                        <div class="cont-top">
                            <div class="cont-left text-center">
                                <span class="fa fa-phone text-primary"></span>
                            </div>
                            <div class="cont-right">
                                <h6>Call Us</h6>
                                <p class="para">
                                    <a href="tel:+<?php echo htmlspecialchars($row['MobileNumber']); ?>">
                                        +<?php echo htmlspecialchars($row['MobileNumber']); ?>
                                    </a>
                                </p>
                            </div>
                        </div>

                        <div class="cont-top margin-up">
                            <div class="cont-left text-center">
                                <span class="fa fa-envelope-o text-primary"></span>
                            </div>
                            <div class="cont-right">
                                <h6>Email Us</h6>
                                <p class="para">
                                    <a href="mailto:<?php echo htmlspecialchars($row['Email']); ?>" class="mail">
                                        <?php echo htmlspecialchars($row['Email']); ?>
                                    </a>
                                </p>
                            </div>
                        </div>

                        <div class="cont-top margin-up">
                            <div class="cont-left text-center">
                                <span class="fa fa-map-marker text-primary"></span>
                            </div>
                            <div class="cont-right">
                                <h6>Address</h6>
                                <p class="para"><?php echo nl2br(htmlspecialchars($row['PageDescription'])); ?></p>
                            </div>
                        </div>

                        <div class="cont-top margin-up">
                            <div class="cont-left text-center">
                                <span class="fa fa-clock-o text-primary"></span>
                            </div>
                            <div class="cont-right">
                                <h6>Opening Hours</h6>
                                <p class="para"><?php echo htmlspecialchars($row['Timing']); ?></p>
                            </div>
                        </div>
                    <?php } ?>
                </div>

                <div class="map-content-9 mt-lg-0 mt-4">
                    <form method="post">
                        <div class="form-group">
                            <label>Full Name</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($fullname); ?>" readonly>
                        </div>

                        <div class="form-group">
                            <label>Service</label>
                            <input type="text" class="form-control" value="<?php echo htmlspecialchars($service_name); ?>" readonly>
                        </div>

                        <div class="form-group mt-3">
                            <label>Appointment Date</label>
                            <input type="date" class="form-control" name="adate" required>
                        </div>

                        <div class="form-group mt-3">
                            <label>Appointment Time Start</label>
                            <input type="time" class="form-control" name="atimeS" id="atimeS" required>
                        </div>

                        <div class="form-group mt-3">
                            <label>Appointment Time End</label>
                            <input type="time" class="form-control" name="atimeE" id="atimeE" readonly>
                        </div>

                        <div class="form-group mt-3">
                            <label>Message</label>
                            <textarea class="form-control" name="message" placeholder="Message" required></textarea>
                        </div>

                        <?php if (isset($_SESSION['customer'])) { ?>
                            <button type="submit" class="btn btn-primary mt-4" name="submit">Make an Appointment</button>
                        <?php } else { ?>
                            <a href="login.php" class="btn btn-primary mt-4">Login to Book </a>
                        <?php } ?>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>

<script>
    document.getElementById('atimeS').addEventListener('change', function() {
        let startTime = this.value;
        if (startTime) {
            let [hours, minutes] = startTime.split(':').map(Number);
            hours += 2; // Add 2 hours
            if (hours >= 24) hours -= 24; // Handle 24-hour format overflow
            let endTime = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
            document.getElementById('atimeE').value = endTime;
        }
    });
</script>
