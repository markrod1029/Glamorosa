<?php 
session_start(); // Start the session

include_once('includes/header.php');
include_once('includes/menubar.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<h3 class='text-center'>Invalid Request</h3>";
    exit();
}

$id = intval($_GET['id']);

// Fetch service and staff details securely
$query = "  SELECT s.ID, s.user_id, s.ServiceName, s.Cost, s.ServiceDescription, s.Image, 
           u.FirstName, u.LastName, u.MobileNumber, u.Email 
    FROM tblservices s
    LEFT JOIN tbluser u ON s.user_id = u.ID
    WHERE s.ID = ?
";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$service = $result->fetch_assoc();

if (!$service) {
    echo "<h3 class='text-center'>Service Not Found</h3>";
    exit();
}
?>

<section class="w3l-inner-banner-main">
    <div class="about-inner contact">
        <div class="container">   
            <div class="main-titles-head text-center">
                <h3 class="header-name"><?php echo htmlspecialchars($service['ServiceName']); ?></h3>
            </div>
        </div>
    </div>
</section>

<section class="w3l-specification-6">
    <div class="specification-layout">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 text-center">
                    <img src="assets/images/<?php echo htmlspecialchars($service['Image']); ?>" 
                         alt="<?php echo htmlspecialchars($service['ServiceName']); ?>" 
                         class="img-fluid rounded shadow" 
                         style="width:100%; max-height:400px; object-fit:cover;">
                </div>
                <div class="col-lg-6 align-self-center">
                    <h3 class="title-big">Handled by: <strong>
                        <?php echo htmlspecialchars($service['FirstName'] . ' ' . $service['LastName']); ?>
                    </strong></h3>
                    <p class="mt-1 para text-muted">Contact No: <strong>
                        <?php echo htmlspecialchars($service['MobileNumber']); ?>
                    </strong></p>
                    <p class="mt-1 para text-muted">Email Address: <strong>
                        <?php echo htmlspecialchars($service['Email']); ?>
                    </strong></p>
                    
                    <h4 class="text-danger">Cost: ₱<?php echo htmlspecialchars($service['Cost']); ?></h4>

                    <a href="book-appointment.php?service_id=<?php echo $service['ID']; ?>" class="btn btn-primary mt-4">Book an Appointment</a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="w3l-teams-15">
    <div class="team-single-main">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h3 class="team-head">Service Details</h3>
                    <p class="para text"><?php echo nl2br(htmlspecialchars($service['ServiceDescription'])); ?></p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>
