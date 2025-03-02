<?php 
include_once('includes/header.php');

$msg = "";
$adminid = $user['ID'];

if (isset($_POST['submit'])) {
    // Sanitize inputs
    $aname = mysqli_real_escape_string($con, $_POST['adminname']);
    $lname = mysqli_real_escape_string($con, $_POST['lastname']);
    $mobno = mysqli_real_escape_string($con, $_POST['contactnumber']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    
    $sername = mysqli_real_escape_string($con, $_POST['sername']);
    $serdesc = mysqli_real_escape_string($con, $_POST['serdesc']);
    $cost = mysqli_real_escape_string($con, $_POST['cost']);

    // Handle Image Upload (Keep the old image if no new image is uploaded)
    $image = $user['Image']; // Retain old image by default
    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . $_FILES['image']['name'];
        move_uploaded_file($_FILES['image']['tmp_name'], "../assets/images/" . $image);
    }

    // Update user details
    if (!empty($_FILES['image']['name'])) {
        $query = "UPDATE tbluser SET FirstName=?, LastName=?, MobileNumber=?, Email=?, Image=? WHERE ID=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("sssssi", $aname, $lname, $mobno, $email, $image, $adminid);
    } else {
        $query = "UPDATE tbluser SET FirstName=?, LastName=?, MobileNumber=?, Email=? WHERE ID=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssssi", $aname, $lname, $mobno, $email, $adminid);
    }

    if ($stmt->execute()) {
        // Check if the user already has a service
        $serviceCheckQuery = "SELECT ID FROM tblservices WHERE user_id=?";
        $stmt = $con->prepare($serviceCheckQuery);
        $stmt->bind_param("i", $adminid);
        $stmt->execute();
        $stmt->store_result();
        $serviceExists = $stmt->num_rows > 0;
        $stmt->close();

        if ($serviceExists) {
            // Update existing service
            $updateServiceQuery = "UPDATE tblservices SET ServiceName=?, ServiceDescription=?, Cost=? WHERE user_id=?";
            $stmt = $con->prepare($updateServiceQuery);
            $stmt->bind_param("ssdi", $sername, $serdesc, $cost, $adminid);
        } else {
            // Insert new service
            $insertServiceQuery = "INSERT INTO tblservices (user_id, ServiceName, ServiceDescription, Cost) VALUES (?, ?, ?, ?)";
            $stmt = $con->prepare($insertServiceQuery);
            $stmt->bind_param("issd", $adminid, $sername, $serdesc, $cost);
        }

        if ($stmt->execute()) {
            $msg = "Profile and Service updated successfully.";
            echo "<script>
            alert('Profile and Service updated successfully.');
            window.location.href = 'staff-profile.php'; 
          </script>";
        } else {
            $msg = "Error updating service details.";
        }
        $stmt->close();
    } else {
        $msg = "Something went wrong. Please try again.";
    }
}
?>

<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/menubar.php'); ?>

        <!-- main content start -->
        <div id="page-wrapper" class="row calender widget-shadow">
            <div class="main-page">
                <div class="forms">
                    <h3 class="title1">Staff Profile</h3>
                    <div class="form-grids row widget-shadow" data-example-id="basic-forms">
                        <div class="form-title">
                            <h4>Update Profile:</h4>
                        </div>
                        <div class="form-body">
                            <form method="post" enctype="multipart/form-data">
                                <?php if (!empty($msg)) { ?>
                                    <p style="font-size:16px; color:green; text-align:center;"><?php echo $msg; ?></p>
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

                                <div class="form-group">
                                    <label for="sername">Service Name</label>
                                    <input type="text" class="form-control" id="sername" name="sername" value="<?php echo htmlspecialchars($user['ServiceName'] ?? ''); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="serdesc">Service Description</label>
                                    <textarea class="form-control" id="serdesc" name="serdesc" required><?php echo htmlspecialchars($user['ServiceDescription'] ?? ''); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="cost">Cost</label>
                                    <input type="text" id="cost" name="cost" class="form-control" value="<?php echo htmlspecialchars($user['Cost'] ?? ''); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Profile Image</label><br>
                                    <input type="file" class="form-control" id="image" name="image">
                                    <br>
                                    <?php if (!empty($user['Image'])) { ?>
                                        <img src="../assets/images/<?php echo htmlspecialchars($user['Image']); ?>" width="100" height="100">
                                    <?php } ?>
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
