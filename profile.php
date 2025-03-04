  <?php
    include_once('includes/session.php');
    include_once('includes/header.php');

    if (!isset($_SESSION['customer']) || strlen($_SESSION['customer']) == 0) {
        header('location:logout.php');
        exit();
    }

    if (isset($_POST['submit'])) {
        $uid = $_SESSION['customer'];
        $fname = trim($_POST['firstname']);
        $lname = trim($_POST['lastname']);
        $mobilenumber = trim($_POST['mobilenumber']);
        $email = trim($_POST['email']);

        // Secure Update Query
        $stmt = mysqli_prepare($con, "UPDATE tbluser SET FirstName=?, LastName=?, MobileNumber=?, Email=? WHERE ID=?");
        mysqli_stmt_bind_param($stmt, "ssssi", $fname, $lname, $mobilenumber, $email, $uid);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo '<script>alert("Profile updated successfully.")</script>';
            echo '<script>window.location.href="profile.php"</script>';
        } else {
            echo '<script>alert("Something went wrong. Please try again.")</script>';
        }
    }

    include_once('includes/menubar.php');
    ?>

  <section class="w3l-inner-banner-main">
      <div class="about-inner contact ">
          <div class="container">
              <div class="main-titles-head text-center">
                  <h3 class="header-name ">

                      Profile Information
                  </h3>
              </div>
          </div>
      </div>

      </div>
  </section>
        <?php include_once('includes/sidebar.php'); ?>


  <section class="w3l-contact-info-main" id="contact">
      <div class="contact-sec	">
          <div class="container">

              <div class="d-grid contact-view">
                  <div class="cont-details">
                      <?php

                        $ret = mysqli_query($con, "select * from tblpage where PageType='contactus' ");
                        $cnt = 1;
                        while ($row = mysqli_fetch_array($ret)) {

                        ?>
                          <div class="cont-top">
                              <div class="cont-left text-center">
                                  <span class="fa fa-phone text-primary"></span>
                              </div>
                              <div class="cont-right">
                                  <h6>Call Us</h6>
                                  <p class="para"><a href="tel:+44 99 555 42">+<?php echo $row['MobileNumber']; ?></a></p>
                              </div>
                          </div>
                          <div class="cont-top margin-up">
                              <div class="cont-left text-center">
                                  <span class="fa fa-envelope-o text-primary"></span>
                              </div>
                              <div class="cont-right">
                                  <h6>Email Us</h6>
                                  <p class="para"><a href="mailto:example@mail.com" class="mail"><?php echo $row['Email']; ?></a></p>
                              </div>
                          </div>
                          <div class="cont-top margin-up">
                              <div class="cont-left text-center">
                                  <span class="fa fa-map-marker text-primary"></span>
                              </div>
                              <div class="cont-right">
                                  <h6>Address</h6>
                                  <p class="para"> <?php echo $row['PageDescription']; ?></p>
                              </div>
                          </div>
                          <div class="cont-top margin-up">
                              <div class="cont-left text-center">
                                  <span class="fa fa-map-marker text-primary"></span>
                              </div>
                              <div class="cont-right">
                                  <h6>Time</h6>
                                  <p class="para"> <?php echo $row['Timing']; ?></p>
                              </div>
                          </div>
                      <?php } ?>
                  </div>
                  <div class="map-content-9 mt-lg-0 mt-4">
                      <h3>User Profile!!</h3>
                      <form method="post" name="signup" onsubmit="return checkpass();">
                          <?php
                            $uid = $_SESSION['customer'];
                            $ret = mysqli_query($con, "select * from tbluser where ID='$uid'");
                            $cnt = 1;
                            while ($row = mysqli_fetch_array($ret)) {

                            ?>
                              <div style="padding-top: 30px;">
                                  <label>First Name</label>

                                  <input type="text" class="form-control" name="firstname" value="<?php echo $row['FirstName']; ?>" required="true">
                              </div>
                              <div style="padding-top: 30px;">
                                  <label>Last Name</label>

                                  <input type="text" class="form-control" name="lastname" value="<?php echo $row['LastName']; ?>" required="true">
                              </div>
                              <div style="padding-top: 30px;">
                                  <label>Mobile Number</label>

                                  <input type="text" class="form-control" name="mobilenumber" value="<?php echo $row['MobileNumber']; ?>">
                              </div>

                              <div style="padding-top: 30px;">
                                  <label>Password</label>

                                  <input type="text" class="form-control" name="password" >
                              </div>

                              <div style="padding-top: 30px;">
                                  <label>Email address</label>

                                  <input type="text" class="form-control" name="email" value="<?php echo $row['Email']; ?>">
                              </div>
                              <div style="padding-top: 30px;">
                                  <label>Registration Date</label>

                                  <input type="text" class="form-control" name="regdate" value="<?php echo $row['RegDate']; ?>" readonly>
                              </div>

                          <?php } ?>
                          <button type="submit" class="btn btn-contact" name="submit">Save Change</button>
                      </form>
                  </div>
              </div>

          </div>
      </div>
  </section>

  <?php include_once('includes/footer.php'); ?>