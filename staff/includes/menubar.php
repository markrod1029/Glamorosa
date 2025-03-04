<div class="sticky-header header-section ">
      <div class="header-left">
        <!--toggle button start-->
        <!--toggle button end-->
        <!--logo -->
        <div class="logo">
          <a href="index.html">
            <h1>Glamorosa</h1>
            <span>Staff</span>
          </a>
        </div>
        <!--//logo-->
        <button id="showLeftPush"><i class="fa fa-bars"></i></button>
       
       
        <div class="clearfix"> </div>
      </div>
      <div class="header-right">
        <div class="profile_details_left"><!--notifications of menu start -->
          <ul class="nofitications-dropdown">
 
          
          </ul>
          <div class="clearfix"> </div>
        </div>
        <!--notification menu end -->
        <div class="profile_details">  
        <?php
// $adid=$_SESSION['bpmsaid'];
// $ret=mysqli_query($con,"select AdminName from tbladmin where ID='$adid'");
// $row=mysqli_fetch_array($ret);
// $name=$row['AdminName'];

?> 
          <ul>
            <li class="dropdown profile_details_drop">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <div class="profile_img"> 
                  <span class="prfil-img"><img src="../assets/images/<?php echo $user['Image']?>" alt="" width="50" height="50"> </span> 
                  <div class="user-name">
                    <span><?php echo $user['FirstName']?> <?php echo $user['LastName']?></span>
                  </div>
                  <i class="fa fa-angle-down lnr"></i>
                  <i class="fa fa-angle-up lnr"></i>
                  <div class="clearfix"></div>  
                </div>  
              </a>
              <ul class="dropdown-menu drp-mnu">
                <li> <a href="staff-profile.php"><i class="fa fa-user"></i> Profile</a> </li> 
                <li> <a href="change-password.php"><i class="fa fa-cog"></i> Password</a> </li> 
                <li> <a href="../logout.php"><i class="fa fa-sign-out"></i> Logout</a> </li>
              </ul>
            </li>
          </ul>
        </div>  
        <div class="clearfix"> </div> 
      </div>
      <div class="clearfix"> </div> 
    </div>