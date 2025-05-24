<?php
session_start();
include_once('includes/header.php');
include_once('includes/menubar.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<p>Invalid service ID.</p>";
    exit;
}

$service_id = intval($_GET['id']); // sanitize input

// Your database connection here (assuming $con)

// Fetch service details (optional, to show service name)
$serviceQuery = mysqli_query($con, "SELECT ServiceName FROM tblservices WHERE ID = $service_id");
$service = mysqli_fetch_assoc($serviceQuery);
?>

<section class="w3l-inner-banner-main">
    <div class="about-inner contact ">
        <div class="container">
            <div class="main-titles-head text-center">
                <h3 class="header-name">
                    Events for Service: <?php echo htmlspecialchars($service['ServiceName'] ?? 'Unknown'); ?>
                </h3>
            </div>
        </div>
    </div>
</section>

<section class="w3l-recent-work-hobbies">
    <div class="recent-work">
        <div class="container">
            <div class="row">

                <?php
                // Fetch all events assigned to this service
                $eventQuery = mysqli_query($con, "SELECT * FROM events WHERE service_id = $service_id ORDER BY created_at DESC");

                if (mysqli_num_rows($eventQuery) == 0) {
                    echo '<p class="text-center">No events assigned to this service.</p>';
                } else {
                    while ($event = mysqli_fetch_assoc($eventQuery)) {
                ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        <div class="card p-3 h-100">
                            <div class="card-body d-flex flex-column justify-content-between text-center">
                                <div>
                                    <h5 class="card-title"><?php echo htmlspecialchars($event['title']); ?></h5>
                                    <p class="card-text">
                                        <?php echo substr(strip_tags($event['description']), 0, 100) . '...'; ?>
                                    </p>
                                </div>
                                <div>
                                    <div class="mt-2 " >
                                        <a href="view_packages.php?id=<?php echo $event['id']; ?>" class="btn btn-info btn-sm">View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
                    } // end while
                }
                ?>

            </div>
        </div>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>
