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
    $contno = $_POST['mobilenumber'];
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

<section class="w3l-contact-info-main" id="contact">
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-lg-6 text-center my-5">
                <img src="assets/images/login.jpg" alt="Login Illustration" class="img-fluid rounded shadow-lg" style="max-width: 100%; height: auto;">
            </div>
            <div class="col-lg-6 my-5">
                <div class="map-content-9 mt-lg-0 mt-4">
                    <form method="post" name="signup">
                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                        <div class="row">
                            <div class="col-md-6">
                                <label>First Name</label>
                                <input type="text" class="form-control" name="firstname" placeholder="First Name" required>
                            </div>
                            <div class="col-md-6">
                                <label>Last Name</label>
                                <input type="text" class="form-control" name="lastname" placeholder="Last Name" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <label>Mobile Number</label>
                                <input type="text" class="form-control" name="mobilenumber" placeholder="Mobile Number" required maxlength="11">
                            </div>
                            <div class="col-md-6">
                                <label>Email address</label>
                                <input type="email" class="form-control" name="email" placeholder="Email address" required>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-6 position-relative">
                                <label>Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="password" id="password" placeholder="Password" required>
                                    <span class="input-group-text"><i class="fa fa-eye" id="togglePassword"></i></span>
                                </div>
                            </div>
                            
                            <div class="col-md-6 position-relative">
                                <label>Confirm password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" name="repeatpassword" id="repeatpassword" placeholder="Confirm password" required>
                                    <span class="input-group-text"><i class="fa fa-eye" id="toggleRepeatPassword"></i></span>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-contact w-100" name="submit">Signup</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        var passwordField = document.getElementById('password');
        var icon = this;
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });

    document.getElementById('toggleRepeatPassword').addEventListener('click', function () {
        var repeatPasswordField = document.getElementById('repeatpassword');
        var icon = this;
        if (repeatPasswordField.type === 'password') {
            repeatPasswordField.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            repeatPasswordField.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
</script>

<style>
    .map-content-9 {
        max-width: 900px; /* Increase form width */
        margin: auto;
    }
    .map-content-9 input {
        width: 100%; /* Ensure input fields take full width */
    }
    .col-lg-6.text-center img {
        max-width: 80%; /* Reduce image width */
    }

    
</style>

