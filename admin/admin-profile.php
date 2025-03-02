<?php 
include_once('includes/header.php');

$msg = "";

$adminid = $user['ID'];
if (isset($_POST['submit'])) {
    $aname = mysqli_real_escape_string($con, $_POST['adminname']);
    $lname = mysqli_real_escape_string($con, $_POST['lastname']);
    $mobno = mysqli_real_escape_string($con, $_POST['contactnumber']);
    $email = mysqli_real_escape_string($con, $_POST['email']);

    $query = "UPDATE tbluser SET FirstName=?, LastName=?, MobileNumber=?, Email=? WHERE ID=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssss", $aname, $lname, $mobno, $email, $adminid); // ✅ Ginawang "sssss" lahat

    if ($stmt->execute()) {
        $msg = "Admin profile has been updated.";
       
    } else {
        $msg = "Something went wrong. Please try again.";
    }
    $stmt->close();
}
?>

<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/menubar.php'); ?>

        <!-- main content start-->
        <div id="page-wrapper" class="row calender widget-shadow">
            <div class="main-page">
                <div class="forms">
                    <h3 class="title1">Admin Profile</h3>
                    <div class="form-grids row widget-shadow" data-example-id="basic-forms">
                        <div class="form-title">
                            <h4>Update Profile :</h4>
                        </div>
                        <div class="form-body">
                            <form method="post">
                                <?php if (!empty($msg)) { ?>
                                    <p style="font-size:16px; color:red; text-align:center;"><?php echo $msg; ?></p>
                                <?php } ?>

                                <div class="form-group">
                                    <label for="adminname">First Name</label>
                                    <input type="text" class="form-control" id="adminname" name="adminname" value="<?php echo htmlspecialchars($user['FirstName']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="lastname">Last Name</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo htmlspecialchars($user['LastName']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="contactnumber">Contact Number</label>
                                    <input type="text" id="contactnumber" name="contactnumber" class="form-control" value="<?php echo htmlspecialchars($user['MobileNumber']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
                                </div>

                                <button type="submit" name="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>
</body>
