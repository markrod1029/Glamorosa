<?php
session_start();
include_once('includes/header.php');
include_once('includes/menubar.php');

?>

<!-- breadcrumbs -->
<section class="w3l-inner-banner-main">
    <div class="about-inner contact ">
        <div class="container">
            <div class="main-titles-head text-center">
                <h3 class="header-name ">

                    Appointment Confirmation
                </h3>
            </div>
        </div>
    </div>
    
    </div>
</section>
<!-- breadcrumbs //-->
<section class="w3l-contact-info-main" id="contact">
    <div class="contact-sec	">
        <div class="container">

            <div>


                <h4 class="w3ls_head text-center">Thank you for applying. Your Appointment no is <?php echo $_SESSION['aptno']; ?> </h4>


            </div>

        </div>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>