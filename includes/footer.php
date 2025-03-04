<section class="footer w3l-footer-29-main">
    <div class=" py-5">
      <div class="container py-lg-4">
        <div class="row footer-top-29">

        <div class="col-lg-5 col-md-6 col-sm-7 footer-list-29 footer-4">
            <h6 class="footer-title-29">Glamorosa</h6>
            <p>Welcome to our GlamBook: Hassle-Free Beauty Appointments for Glamorosa</p>
          </div>

          
          <div class="col-lg-4 col-md-6 col-sm-8 footer-list-29 footer-1">
            <h6 class="footer-title-29">Contact Us</h6>
            <ul>
              <?php
              $ret = mysqli_query($con, "select * from tblpage where PageType='contactus' ");
              while ($row = mysqli_fetch_array($ret)) {
              ?>
              <li>
                <span class="fa fa-map-marker"></span> <p><?php echo $row['PageDescription']; ?>.</p>
              </li>
              <li><span class="fa fa-phone"></span><a href="tel:+<?php echo $row['MobileNumber']; ?>"> +<?php echo $row['MobileNumber']; ?></a></li>
              <li><span class="fa fa-envelope-open-o"></span><a href="mailto:<?php echo $row['Email']; ?>" class="mail">
                  <?php echo $row['Email']; ?></a></li>
              <?php } ?>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 col-sm-4 footer-list-29 footer-2">
            <ul>
              <h6 class="footer-title-29">Useful Links</h6>
              <li><a href="index.php">Home</a></li>
              <li><a href="about.php">About</a></li>
              <li><a href="services.php"> Services</a></li>
              <li><a href="contact.php">Contact us</a></li>
            </ul>
          </div>
         
          
        </div>
      </div>
    </div>
</section>


<button onclick="topFunction()" id="movetop" title="Go to top">
	<span class="fa fa-long-arrow-up"></span>
</button>

<script>
window.onscroll = function () { scrollFunction(); };

function scrollFunction() {
	if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
		document.getElementById("movetop").style.display = "block";
	} else {
		document.getElementById("movetop").style.display = "none";
	}
}

function topFunction() {
	document.body.scrollTop = 0;
	document.documentElement.scrollTop = 0;
}
</script>

<script src="assets/public/jquery-3.3.1.min.js"></script>
<script src="assets/public/bootstrap.min.js"></script>

<script>
$(function () {
  $('.navbar-toggler').click(function () {
    $('body').toggleClass('noscroll');
  });
});
</script>

<style>
/* Footer background color */
.w3l-footer-29-main {
    background: #8D7984 !important;
    color: #FFFFFF !important;
}

/* Ensure all text inside the footer is white */
.w3l-footer-29-main .footer-title-29,
.w3l-footer-29-main p,
.w3l-footer-29-main ul li a,
.w3l-footer-29-main span {
    color: #FFFFFF !important;
}

/* Ensure footer links stay white */
.w3l-footer-29-main ul li a:hover {
    color: #f8f9fa !important;
    text-decoration: underline;
}

/* Social media icons white */
.main-social-footer-29 a {
    color: #FFFFFF !important;
}

/* Ensure copyright text is white */
.copy-footer-29 {
    color: #FFFFFF !important;
}

w3l-footer-29-main .main-social-footer-29 a {
    color: #ccc;
    background: #cf7dad !important;
}
</style>
