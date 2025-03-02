<?php 
session_start(); // Start the session
include_once('includes/header.php'); ?>
<?php include_once('includes/menubar.php'); ?>

<div class="w3l-hero-headers-9">
  <div class="css-slider">
    <input id="slide-1" type="radio" name="slides" checked>
    <section class="slide slide-one">
      <div class="container">
        <div class="banner-text">
          <h4>Glamorosa Make-up Artistry</h4>
          <h3>Unleash Your Inner Glam <br>  Beauty, Elegance, Confidence! </h3>
          <a href="services.php" class="btn logo-button top-margin my-3">Get An Appointment</a>
        </div>
      </div>
    </section>

    <input id="slide-2" type="radio" name="slides">
    <section class="slide slide-two">
      <div class="container">
        <div class="banner-text">
          <h4>Glamorosa Make-up Artistry </h4>
          <h3>Effortless Appointments <br> for Flawless Transformations!</h3>
          <a href="services.php" class="btn logo-button top-margin my-3">Get An Appointment</a>
        </div>
      </div>
    </section>


    <header>
      <label for="slide-1" id="slide-label-1"></label>
      <label for="slide-2" id="slide-label-2"></label>
    </header>
  </div>
</div>

<section class="w3l-recent-work">
  <div class="jst-two-col">
    <div class="container">
      <div class="row">
        <div class="my-bio col-lg-6">
          <div class="hair-make">
            <?php
            $ret = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType='aboutus' ");
            while ($row = mysqli_fetch_array($ret)) {
            ?>
              <h5><a href="blog.html"><?php echo $row['PageTitle']; ?></a></h5>
              <p class="para mt-2"><?php echo $row['PageDescription']; ?></p>
            <?php } ?>
          </div>
        </div>
        <div class="col-lg-6">
          <img src="assets/images/2.png" alt="product" class="img-responsive about-me" height="500" width="500">
        </div>
      </div>
    </div>
  </div>
</section>

<section class="w3l-teams-15">
  <div class="team-single-main">
    <div class="container">
      <div class="column2 image-text">
        <h3 class="team-head"> Glamorosa Make-up Artistry</h3>
        <p class="para text">
        is a seamless and user-friendly appointment platform designed to enhance the convenience of booking professional hair and makeup services in San Carlos City, Pangasinan. With just a few clicks, clients can schedule their beauty sessions, select preferred dates and times, and receive instant confirmations—eliminating the hassle of manual bookings. This system ensures an organized and efficient workflow for makeup artists while providing clients with a stress-free and glamorous experience. Whether for weddings, special events, or everyday glam, Glamorosa makes beauty accessible anytime, anywhere! 
        </p>
        <a href="services.php" class="btn logo-button top-margin mt-4">Get An Appointment</a>
      </div>
    </div>
  </div>
</section>

<script>
  let currentIndex = 1;
  const totalSlides = 2;

  function autoSlide() {
    currentIndex = currentIndex % totalSlides + 1;
    document.getElementById(`slide-${currentIndex}`).checked = true;
  }

  setInterval(autoSlide, 5000); // Change slide every 5 seconds
</script>

<style>
  .w3l-hero-headers-9 .slide-one {
    background-image: url("assets/images/1.png");
    background-size: cover;
    background-position: center;
  }

  .w3l-hero-headers-9 .slide-two {
    background-image: url("assets/images/2.png");
    background-size: cover;
    background-position: center;
  }

  .css-slider {
    position: relative;
    overflow: hidden;
    width: 100%;
  }

  .css-slider section {
    transition: opacity 1s ease-in-out;
  }

  input[name="slides"] {
    display: none;
  }

  .btn.logo-button {
    background: #ff4081;
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
  }

  .btn.logo-button:hover {
    background: #e6005c;
  }
</style>

<?php include_once('includes/footer.php'); ?>
