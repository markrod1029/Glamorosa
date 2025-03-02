<?php 
session_start();
include_once('includes/header.php'); 
include_once('includes/menubar.php'); 

$errorMsg = "";

// Generate CSRF Token
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

if(isset($_POST['submit'])) {
    // CSRF protection
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Invalid CSRF token");
    }

    $fname = mysqli_real_escape_string($con, $_POST['firstname']);
    $lname = mysqli_real_escape_string($con, $_POST['lastname']);
    $contno = mysqli_real_escape_string($con, $_POST['mobilenumber']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $role = 'Customer';
    $password = $_POST['password'];
    $repeatpassword = $_POST['repeatpassword'];

    // Password validation
    if ($password !== $repeatpassword) {
        $errorMsg = "Passwords do not match!";
    } elseif (strlen($password) < 8) {
        $errorMsg = "Password must be at least 8 characters long!";
    } else {
        // Secure password hashing
        $hashed_password = md5($password);

        // Check if email or mobile number exists
        $stmt = mysqli_prepare($con, "SELECT Email FROM tbluser WHERE Email=? OR MobileNumber=?");
        mysqli_stmt_bind_param($stmt, "ss", $email, $contno);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_fetch_array($result)) {
            $errorMsg = "This email or contact number is already in use!";
        } else {
            // Insert new user
            $stmt = mysqli_prepare($con, "INSERT INTO tbluser (FirstName, LastName, MobileNumber, Email, Password, role) 
                                          VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssssss", $fname, $lname, $contno, $email, $hashed_password, $role);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['user_email'] = $email;
                echo "<script>alert('You have successfully registered.');</script>";
                echo "<script>window.location.href='login.php';</script>";
                exit();
            } else {
                $errorMsg = "Something went wrong. Please try again.";
            }
        }
    }
}
?>

<!-- Signup Form -->
<section class="w3l-contact-info-main my-5" id="contact">
    <div class="container">
        <div class="map-content-9 mt-lg-0 mt-4">
            <h3>Register with us!!</h3>

            <!-- Display Error Message -->
            <?php if ($errorMsg) { ?>
                <div class="alert alert-danger text-center">
                    <?php echo $errorMsg; ?>
                </div>
            <?php } ?>

            <form method="post" name="signup">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div style="padding-top: 30px;">
                    <label>First Name</label>
                    <input type="text" class="form-control" name="firstname" placeholder="First Name" required>
                </div>

                <div style="padding-top: 30px;">
                    <label>Last Name</label>
                    <input type="text" class="form-control" name="lastname" placeholder="Last Name" required>
                </div>

                <div style="padding-top: 30px;">
                    <label>Mobile Number</label>
                    <input type="text" class="form-control" name="mobilenumber" placeholder="Mobile Number" required pattern="[0-9]+" maxlength="10">
                </div>

                <div style="padding-top: 30px;">
                    <label>Email address</label>
                    <input type="email" class="form-control" name="email" placeholder="Email address" required>
                </div>

                <div style="padding-top: 30px;">
                    <label>Password</label>
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                </div>

                <div style="padding-top: 30px;">
                    <label>Confirm password</label>
                    <input type="password" class="form-control" name="repeatpassword" placeholder="Confirm password" required>
                </div>

                <button type="submit" class="btn btn-contact mb-5" name="submit">Signup</button>
            </form>
        </div>
    </div>
</section>

