<?php
session_start();
include_once('includes/header.php');
include_once('includes/menubar.php');

// Redirect if already logged in
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Customer') {
        header('location: profile.php');
    } elseif ($_SESSION['role'] == 'Staff') {
        header('location: staff/dashboard.php');
    } elseif ($_SESSION['role'] == 'Admin') {
        header('location: admin/dashboard.php');
    }
    exit();
}

// Error message initialization
$errorMsg = "";

if (isset($_POST['login'])) {
    $emailcon = trim($_POST['emailcont']);
    $password = trim($_POST['password']);

    if (!empty($emailcon) && !empty($password)) {
        // Prepare SQL Query to fetch user details
        $stmt = mysqli_prepare($con, "SELECT ID, Role, Password FROM tbluser WHERE Email=?");
        mysqli_stmt_bind_param($stmt, "s", $emailcon);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($ret = mysqli_fetch_array($result)) {
            // Verify hashed password (using MD5, but should be upgraded to bcrypt)
            if ($ret['Password'] === md5($password)) {
                // Successful login
                $_SESSION['role'] = $ret['Role'];

                if ($_SESSION['role'] == 'Customer') {
                    $_SESSION['customer'] = $ret['ID'];
                    header('location: profile.php');
                } elseif ($_SESSION['role'] == 'Staff') {
                    $_SESSION['staff'] = $ret['ID'];
                    header('location: staff/dashboard.php');
                } elseif ($_SESSION['role'] == 'Admin') {
                    $_SESSION['admin'] = $ret['ID'];
                    header('location: admin/dashboard.php');
                }
                exit();
            } else {
                $errorMsg = "Incorrect email or password.";
            }
        } else {
            $errorMsg = "Incorrect email or password.";
        }
    } else {
        $errorMsg = "Please enter both email and password.";
    }
}
?>

<!-- Bootstrap and Font Awesome -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


<!-- Breadcrumbs -->
<!-- <section class="w3l-inner-banner-main">
    <div class="about-inner contact">
        <div class="container">
            <div class="main-titles-head text-center">
                <h3 class="header-name">Login Page</h3>
            </div>
        </div>
    </div>
</section> -->

<!-- Login Section -->
<section class="w3l-contact-info-main" id="contact">
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <!-- Image Section -->
            <div class="col-lg-6 text-center my-5">
                <img src="assets/images/login.jpg" alt="Login Illustration" class="img-fluid rounded shadow-lg" style="max-width: 100%; height: auto;">
            </div>

            <!-- Login Form -->
            <div class="col-lg-6 my-5">
                <div class="map-content-9 mt-lg-0 mt-4">
                    <form method="post">
                        <?php if ($errorMsg) { ?>
                            <div class="alert alert-danger text-center">
                                <?php echo $errorMsg; ?>
                            </div>
                        <?php } ?>
                        <!-- Email Input -->
                        <div class="mb-3 position-relative">
                            <span class="position-absolute top-50 start-0 translate-middle-y ps-3">
                                <i class="fas fa-envelope text-muted"></i>
                            </span>
                            <input type="email" class="form-control ps-5" name="emailcont" placeholder="Registered Email" required>
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3 position-relative">
                            <span class="position-absolute top-50 start-0 translate-middle-y ps-3">
                                <i class="fas fa-lock text-muted"></i>
                            </span>
                            <input type="password" class="form-control ps-5 pe-5" name="password" id="password" placeholder="Password" required>
                            <span class="position-absolute top-50 end-0 translate-middle-y pe-3" id="togglePassword" style="cursor: pointer;">
                                <i class="fas fa-eye text-muted"></i>
                            </span>
                        </div>
                        <!-- <div class="d-flex justify-content-between align-items-center">
                            <a href="forgot-password.php" class="text-decoration-none">Forgot Password?</a>
                        </div> -->
                        <button type="submit" class="btn btn-contact" name="login">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Toggle Password Visibility -->
<script>
    document.getElementById('togglePassword').addEventListener('click', function() {
        var passwordField = document.getElementById('password');
        var icon = this.querySelector('i');
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
</script>