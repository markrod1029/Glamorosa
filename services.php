<?php
session_start(); // Start the session

include_once('includes/header.php'); ?>
<?php include_once('includes/menubar.php'); ?>

<!-- Services Section -->

<section class="w3l-inner-banner-main">
    <div class="about-inner contact ">
        <div class="container">
            <div class="main-titles-head text-center" >
                <h3 class="header-name ">

                    Our Services
                </h3>
            </div>
        </div>
    </div>

    </div>
</section>

<section class="w3l-recent-work-hobbies">
    <div class="recent-work">
        <div class="container">
            <div class="row">
                <?php
                $ret = mysqli_query($con, "SELECT * FROM tblservices");
                while ($row = mysqli_fetch_array($ret)) {
                ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                        <div class="card">

                            <a href="service-details.php?id=<?php echo $row['ID']; ?>">
                                <img src="assets/images/<?php echo htmlspecialchars($row['Image']); ?>"
                                    alt="<?php echo htmlspecialchars($row['ServiceName']); ?>"
                                class="card-img-top" style="height:200px; width:100%; object-fit:cover;">

                                    </a>

                                <div class="card-body text-center">
                                    <h5 class="card-title"><?php echo htmlspecialchars($row['ServiceName']); ?></h5>
                                    <p class="card-text text-danger mb-3">
                                        Cost of Service: $<?php echo htmlspecialchars($row['Cost']); ?>
                                    </p>
                                    <a href="service-details.php?id=<?php echo $row['ID']; ?>" class="btn btn-primary">
                                        View Details
                                    </a>
                                </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>