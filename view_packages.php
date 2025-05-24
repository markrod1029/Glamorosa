<?php
session_start();
include_once('includes/header.php');
include_once('includes/menubar.php');
?>

<section class="w3l-inner-banner-main">
    <div class="about-inner contact">
        <div class="container">
            <div class="main-titles-head text-center">
                <h3 class="header-name">Home Service Packages</h3>
            </div>
        </div>
    </div>
</section>

<section class="w3l-recent-work-hobbies">
    <div class="recent-work">
        <div class="container">
            <div class="row">
                <?php
                $id = $_GET['id'];
                $homeQuery = mysqli_query($con, "SELECT * FROM packages WHERE event_id = '$id' AND service = 'Home Service' ORDER BY id DESC");

                if (mysqli_num_rows($homeQuery) == 0) {
                    echo '<p class="text-center">No Home Service packages available.</p>';
                } else {
                    while ($package = mysqli_fetch_assoc($homeQuery)) {
                ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card">
                                <img src="assets/images/packages/<?php echo htmlspecialchars($package['photo']); ?>"
                                    alt="<?php echo htmlspecialchars($package['title']); ?>"
                                    class="card-img-top"
                                    style="height:200px; width:100%; object-fit:cover;">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo htmlspecialchars($package['title']); ?></h5>
                                    <p class="card-text">
                                        <?php echo substr(strip_tags($package['description']), 0, 100) . '...'; ?>
                                    </p>
                                    <p class="text-danger mb-3">Price: $<?php echo number_format($package['price'], 2); ?></p>
                                    <a href="book-appointment.php?id=<?php echo $package['id']; ?>" class="btn btn-success">Book</a>
                                </div>
                            </div>
                        </div>
                <?php }} ?>
            </div>
        </div>
    </div>
</section>

<section class="w3l-inner-banner-main mt-5">
    <div class="about-inner contact">
        <div class="container">
            <div class="main-titles-head text-center">
                <h3 class="header-name">Walk-in Packages</h3>
            </div>
        </div>
    </div>
</section>

<section class="w3l-recent-work-hobbies">
    <div class="recent-work">
        <div class="container">
            <div class="row">
                <?php
                $walkinQuery = mysqli_query($con, "SELECT * FROM packages WHERE event_id = '$id' AND service = 'Walk-in' ORDER BY id DESC");

                if (mysqli_num_rows($walkinQuery) == 0) {
                    echo '<p class="text-center">No Walk-in packages available.</p>';
                } else {
                    while ($package = mysqli_fetch_assoc($walkinQuery)) {
                ?>
                        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                            <div class="card">
                                <img src="assets/images/packages/<?php echo htmlspecialchars($package['photo']); ?>"
                                    alt="<?php echo htmlspecialchars($package['title']); ?>"
                                    class="card-img-top"
                                    style="height:200px; width:100%; object-fit:cover;">
                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo htmlspecialchars($package['title']); ?></h5>
                                    <p class="card-text">
                                        <?php echo substr(strip_tags($package['description']), 0, 100) . '...'; ?>
                                    </p>
                                    <p class="text-danger mb-3">Price: $<?php echo number_format($package['price'], 2); ?></p>
                                    <a href="book-appointment.php?id=<?php echo $package['id']; ?>" class="btn btn-success">Book</a>
                                </div>
                            </div>
                        </div>
                <?php }} ?>
            </div>
        </div>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>
