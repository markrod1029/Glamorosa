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
    $age = trim($_POST['age']);
    $gender = trim($_POST['gender']);

    $new_password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Check if the email or mobile number is already in use by another user
    $stmt = mysqli_prepare($con, "SELECT ID FROM tbluser WHERE (Email=? OR MobileNumber=?) AND ID != ?");
    mysqli_stmt_bind_param($stmt, "ssi", $email, $mobilenumber, $uid);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_fetch_array($result)) {
        echo '<script>alert("This email or mobile number is already taken!");</script>';
    } else {
        // Check if password fields are filled
        if (!empty($new_password) || !empty($confirm_password)) {
            // Ensure passwords match
            if ($new_password !== $confirm_password) {
                echo '<script>alert("Passwords do not match!");</script>';
            } elseif (strlen($new_password) < 8) {
                echo '<script>alert("Password must be at least 8 characters long!");</script>';
            } else {
                // Hash the password using md5 (better to use password_hash)
                $hashed_password = md5($new_password);
                $stmt = mysqli_prepare($con, "UPDATE tbluser SET FirstName=?, LastName=?, Age=?, Gender=?, MobileNumber=?, Email=?, Password=? WHERE ID=?");
                mysqli_stmt_bind_param($stmt, "sssssssi", $fname, $lname, $age, $gender, $mobilenumber, $email, $hashed_password, $uid);
            }
        } else {
            // Update profile WITHOUT changing password
            $stmt = mysqli_prepare($con, "UPDATE tbluser SET FirstName=?, LastName=?, Age=?, Gender=?, MobileNumber=?, Email=? WHERE ID=?");
            mysqli_stmt_bind_param($stmt, "ssssssi", $fname, $lname, $age, $gender, $mobilenumber, $email, $uid);
        }

        // Execute the query
        if (mysqli_stmt_execute($stmt)) {
            echo '<script>alert("Profile updated successfully."); window.location.href="profile.php";</script>';
        } else {
            echo '<script>alert("Something went wrong. Please try again.");</script>';
        }
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
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">

            <div>
                <div class="map-content-9 mt-lg-0 mt-4">
                    <form method="post" name="signup" onsubmit="return checkpass();" class="mx-auto mt-5 mb-5" style="max-width: 600px;">
                        <div class="row">
                            <?php
                            $uid = $_SESSION['customer'];
                            $ret = mysqli_query($con, "SELECT * FROM tbluser WHERE ID='$uid'");
                            $row = mysqli_fetch_array($ret);
                            ?>

                            <!-- First Name -->
                            <div class="col-md-6" style="padding-top: 30px;">
                                <label>First Name</label>
                                <input type="text" class="form-control" name="firstname" value="<?php echo $row['FirstName']; ?>" required>
                            </div>

                            <!-- Last Name -->
                            <div class="col-md-6" style="padding-top: 30px;">
                                <label>Last Name</label>
                                <input type="text" class="form-control" name="lastname" value="<?php echo $row['LastName']; ?>" required>
                            </div>

                            <!-- Age -->
                            <div class="col-md-6" style="padding-top: 30px;">
                                <label>Age</label>
                                <input type="number" class="form-control" name="age" value="<?php echo isset($row['Age']) ? $row['Age'] : ''; ?>" required>
                            </div>

                            <!-- Gender -->
                            <div class="col-md-6" style="padding-top: 30px;">
                                <label>Gender</label>
                                <select class="form-control" name="gender" required>
                                    <option value="Male" <?php if (isset($row['Gender']) && $row['Gender'] == 'Male') echo 'selected'; ?>>Male</option>
                                    <option value="Female" <?php if (isset($row['Gender']) && $row['Gender'] == 'Female') echo 'selected'; ?>>Female</option>
                                    <option value="Other" <?php if (isset($row['Gender']) && $row['Gender'] == 'Other') echo 'selected'; ?>>Other</option>
                                </select>
                            </div>

                            <!-- Mobile Number -->
                            <div class="col-md-6" style="padding-top: 30px;">
                                <label>Mobile Number</label>
                                <input type="text" class="form-control" name="mobilenumber" value="<?php echo $row['MobileNumber']; ?>">
                            </div>

                            <!-- Email -->
                            <div class="col-md-6" style="padding-top: 30px;">
                                <label>Email Address</label>
                                <input type="email" class="form-control" name="email" value="<?php echo $row['Email']; ?>">
                            </div>

                            <!-- Password -->
                            <div class="col-md-6" style="padding-top: 30px; position: relative;">
                                <label>New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password">
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-md-6" style="padding-top: 30px; position: relative;">
                                <label>Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="confirm_password">
                                    <button class="btn btn-outline-secondary toggle-password" type="button">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Save Button -->
                            <div class="col-md-12 text-center" style="padding-top: 30px;">
                                <button type="submit" class="btn btn-primary" name="submit">Save Changes</button>
                            </div>
                        </div>
                    </form>
                </div>

                <script>
                    // Toggle password visibility
                    document.querySelectorAll('.toggle-password').forEach(button => {
                        button.addEventListener('click', function() {
                            let input = this.parentNode.querySelector('input');
                            if (input.type === "password") {
                                input.type = "text";
                                this.innerHTML = '<i class="fa fa-eye-slash"></i>';
                            } else {
                                input.type = "password";
                                this.innerHTML = '<i class="fa fa-eye"></i>';
                            }
                        });
                    });
                </script>

            </div>
        </div>

    </div>
    </div>
</section>

<?php include_once('includes/footer.php'); ?>
