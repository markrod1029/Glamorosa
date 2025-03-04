<?php
include_once('includes/header.php');

$msg = "";

if (isset($_POST['submit'])) {
    $adminid = $user['ID'];
    $cpassword = md5($_POST['currentpassword']); // Convert to MD5
    $newpassword = md5($_POST['newpassword']); // Convert to MD5

    // Fetch current password from database
    $stmt = $con->prepare("SELECT Password FROM tbluser WHERE ID=?");
    $stmt->bind_param("s", $adminid);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        if ($cpassword === $hashedPassword) { // Compare MD5 hashes
            $update_stmt = $con->prepare("UPDATE tbluser SET Password=? WHERE ID=?");
            $update_stmt->bind_param("ss", $newpassword, $adminid);

            if ($update_stmt->execute()) {
                $msg = "Your password has been successfully changed.";
            } else {
                $msg = "Something went wrong. Please try again.";
            }
            $update_stmt->close();
        } else {
            $msg = "Your current password is incorrect.";
        }
    } else {
        $msg = "User not found.";
    }

    $stmt->close();
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
                    <h3 class="title1">Change Password</h3>
                    <div class="form-grids row widget-shadow" data-example-id="basic-forms">
                        <div class="form-title">
                            <h4>Reset Your Password:</h4>
                        </div>
                        <div class="form-body">
                            <form method="post" name="changepassword" onsubmit="return checkpass();">
                                <?php if (!empty($msg)) { ?>
                                    <p style="font-size:16px; color:red; text-align:center;"><?php echo htmlspecialchars($msg); ?></p>
                                <?php } ?>

                                <div class="form-group">
                                    <label for="currentpassword">Current Password</label>
                                    <input type="password" name="currentpassword" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="newpassword">New Password</label>
                                    <input type="password" name="newpassword" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <label for="confirmpassword">Confirm Password</label>
                                    <input type="password" name="confirmpassword" class="form-control" required>
                                </div>

                                <button type="submit" name="submit" class="btn btn-primary">Change</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>
</body>

<script type="text/javascript">
    function checkpass() {
        var newpassword = document.changepassword.newpassword.value;
        var confirmpassword = document.changepassword.confirmpassword.value;
        
        if (newpassword.length < 6) {
            alert('Password must be at least 6 characters long.');
            document.changepassword.newpassword.focus();
            return false;
        }
        
        if (newpassword !== confirmpassword) {
            alert('New Password and Confirm Password do not match.');
            document.changepassword.confirmpassword.focus();
            return false;
        }
        return true;
    }
</script>
