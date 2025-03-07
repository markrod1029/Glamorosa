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
$query = "SELECT s.ID, s.user_id, s.ServiceName, s.Cost, s.ServiceDescription, s.Image,
           u.FirstName, u.LastName, u.MobileNumber, u.Email 
    FROM tblservices s
    LEFT JOIN tbluser u ON s.user_id = u.ID
    WHERE s.ID = ?";
$stmt = $con->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$service = $result->fetch_assoc();

if (!$service) {
    echo "<h3 class='text-center'>Service Not Found</h3>";
    exit();
}

// Fetch all images related to the service from tblimages
$imageQuery = mysqli_query($con, "SELECT image FROM tblimages WHERE serviceID = '$id'");
$images = mysqli_fetch_all($imageQuery, MYSQLI_ASSOC);

// Fetch video related to the service from tblvideo
$videoQuery = mysqli_query($con, "SELECT video FROM tblvideo WHERE serviceID = '$id' LIMIT 1");
$video = mysqli_fetch_assoc($videoQuery);
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

            <!-- Service Images Section -->
            <div class="row mt-5">
                <div class="col-lg-12 text-center">
                    <h3 class="team-head">Service Images</h3>
                </div>
            </div>

            <div class="row justify-content-center">
                <?php if (!empty($images)) { ?>
                    <?php foreach ($images as $img) { ?>
                        <div class="col-lg-2 col-md-3 col-sm-4 col-6 text-center mb-3">
                            <img src="assets/images/<?php echo htmlspecialchars($img['image']); ?>"
                                class="img-thumbnail shadow-sm"
                                style="width:100%; height:150px; object-fit:cover; cursor:pointer;"
                                onclick="showModal('assets/images/<?php echo htmlspecialchars($img['image']); ?>', 'image')">
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">No images available for this service.</p>
                    </div>
                <?php } ?>
            </div>

            <!-- Service Video Section -->
            <div class="row mt-5">
                <div class="col-lg-12 text-center">
                    <h3 class="team-head">Service Video</h3>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <?php if (!empty($video['video'])) { ?>
                        <video width="100%" controls class="shadow rounded"
                            style="cursor:pointer;"
                            onclick="showModal('assets/videos/<?php echo htmlspecialchars($video['video']); ?>', 'video')">
                            <source src="assets/videos/<?php echo htmlspecialchars($video['video']); ?>" type="video/mp4">
                        </video>
                    <?php } else { ?>
                        <p class="text-muted">No video available for this service.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal for Fullscreen View -->
<div id="mediaModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid d-none">
                <video id="modalVideo" controls class="w-100 d-none">
                    <source id="videoSource" src="" type="video/mp4">
                </video>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>

<script>
    function showModal(src, type) {
        let modalImage = document.getElementById('modalImage');
        let modalVideo = document.getElementById('modalVideo');
        let videoSource = document.getElementById('videoSource');

        if (type === 'image') {
            modalImage.src = src;
            modalImage.classList.remove('d-none');
            modalVideo.classList.add('d-none');
        } else if (type === 'video') {
            videoSource.src = src;
            modalVideo.load();
            modalVideo.classList.remove('d-none');
            modalImage.classList.add('d-none');
        }

        let modal = new bootstrap.Modal(document.getElementById('mediaModal'));
        modal.show();
    }
</script>
