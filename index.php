<?php 
session_start(); // Start the session
include_once('includes/header.php'); 
include_once('includes/menubar.php'); 
?>

<div class="w3l-hero-headers-9">
  <div class="css-slider">
    
    <input id="slide-1" type="radio" name="slides" checked>
    <section class="slide slide-one">
      <div class="container">
        <div class="banner-text">
          <h4>Glamorosa Make-up Artistry</h4>
          <h3>Unleash Your Inner Glam <br> Beauty, Elegance, Confidence!</h3>
          <p>Experience the transformation with our expert makeup artists who bring out the best in you.</p>
          <a href="services.php" class="btn logo-button top-margin my-3">Get An Appointment</a>
        </div>
      </div>
    </section>

    <input id="slide-2" type="radio" name="slides">
    <section class="slide slide-two">
      <div class="container">
        <div class="banner-text">
          <h4>Glamorosa Positivity</h4>
          <h3>Effortless Appointments <br> for Flawless Transformations!</h3>
          <p>Book your session with ease and let us pamper you with our top-notch services.</p>
          <a href="services.php" class="btn logo-button top-margin my-3">Get An Appointment</a>
        </div>
      </div>
    </section>

    <input id="slide-3" type="radio" name="slides">
    <section class="slide slide-three">
      <div class="container">
        <div class="banner-text">
          <h4>Glamorosa Confidence</h4>
          <h3>Makeup is for Everyone <br> Embrace Your Beauty!</h3>
          <p>Our services are designed to boost your confidence and highlight your natural beauty.</p>
          <a href="services.php" class="btn logo-button top-margin my-3">Get An Appointment</a>
        </div>
      </div>
    </section>

    <header>
      <label for="slide-1" id="slide-label-1"></label>
      <label for="slide-2" id="slide-label-2"></label>
      <label for="slide-3" id="slide-label-3"></label>
    </header>
  </div>
</div>

<section class="w3l-recent-work fade-in">
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

<section class="w3l-teams-15" style="background-color: rgb(140, 190, 230) !important;">
  <div class="team-single-main jst-two-col">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-md-12 image-text">
          <img src="assets/images/2.png" alt="product" class="img-responsive about-me fade-in" height="500" width="500">
        </div>

        <div class="col-lg-6 col-md-12 image-text fade-in">
          <h3 class="team-head">Glamorosa Make-up Artistry</h3>
          <p class="para text">
            A seamless and user-friendly appointment platform designed to enhance the convenience of booking professional hair and makeup services in San Carlos City, Pangasinan.
          </p>
          <p>Our team of experienced artists is dedicated to providing personalized services that cater to your unique style and preferences.</p>
          <a href="services.php" class="btn logo-button top-margin mt-4">Get An Appointment</a>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  let currentIndex = 1;
  const totalSlides = 3;

  function autoSlide() {
    currentIndex = currentIndex % totalSlides + 1;
    document.getElementById(`slide-${currentIndex}`).checked = true;
  }

  setInterval(autoSlide, 5000); // Change slide every 5 seconds

  // Fade-in effect on scroll using Intersection Observer
  document.addEventListener("DOMContentLoaded", function () {
    const sections = document.querySelectorAll(".fade-in");

    const observer = new IntersectionObserver(
      (entries, observer) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("show");
            observer.unobserve(entry.target); // Stop observing once shown
          }
        });
      },
      { threshold: 0.2 } // 20% visibility to trigger animation
    );

    sections.forEach((section) => observer.observe(section));
  });
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

  .w3l-hero-headers-9 .slide-three {
    background-image: url("assets/images/3.png"); /* Updated to use a different image */
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

  /* APPOINTMENT BUTTON CSS */
  .btn.logo-button {
    background: rgb(122, 142, 255);
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    text-decoration: none;
    font-weight: bold;
  }

  .btn.logo-button:hover {
    background: rgb(31, 245, 48); /* BUTTONS COLOR HOVER */
  }

  /* Fade-in animation */
  .fade-in {
    opacity: 0;
    transform: translateY(50px);
    transition: opacity 1s ease-out, transform 1s ease-out;
  }

  .fade-in.show {
    opacity: 1;
    transform: translateY(0);
  }
</style>

<?php include_once('includes/footer.php'); ?>
