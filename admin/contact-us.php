<?php
include_once('includes/header.php');
include_once('includes/dbconnection.php');

$msg = "";

if (isset($_POST['submit'])) {
    $pagetitle = mysqli_real_escape_string($con, $_POST['pagetitle']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $mobnumber = mysqli_real_escape_string($con, $_POST['mobnumber']);
    $timing = mysqli_real_escape_string($con, $_POST['timing']);
    $pagedes = mysqli_real_escape_string($con, $_POST['pagedes']);

    $query = "UPDATE tblpage SET PageTitle = ?, Email = ?, MobileNumber = ?, Timing = ?, PageDescription = ? WHERE PageType = 'contactus'";
    $stmt = $con->prepare($query);
    $stmt->bind_param("sssss", $pagetitle, $email, $mobnumber, $timing, $pagedes);

    if ($stmt->execute()) {
        $msg = "Contact Us page updated successfully!";
    } else {
        $msg = "Something went wrong. Please try again.";
    }

    $stmt->close();
}

$ret = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType = 'contactus'");
$row = mysqli_fetch_assoc($ret);
?>

<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/menubar.php'); ?>

        <!-- main content start-->
        <div id="page-wrapper" class="row calender widget-shadow">
            <div class="main-page">
                <div class="forms">
                    <h3 class="title1">Update Contact Us</h3>
                    <div class="form-grids row widget-shadow">
                        <div class="form-title">
                            <h4>Update Contact Us:</h4>
                        </div>
                        <div class="form-body">
                            <form method="post">
                                <p style="font-size:16px; color:green; text-align:center;">
                                    <?php if ($msg) {
                                        echo $msg;
                                    } ?>
                                </p>

                                <div class="form-group">
                                    <label for="pagetitle">Page Title</label>
                                    <input type="text" class="form-control" name="pagetitle" id="pagetitle" value="<?php echo htmlspecialchars($row['PageTitle']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($row['Email']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="mobnumber">Mobile Number</label>
                                    <input type="text" class="form-control" name="mobnumber" id="mobnumber" value="<?php echo htmlspecialchars($row['MobileNumber']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="timing">Timing</label>
                                    <input type="text" class="form-control" name="timing" id="timing" value="<?php echo htmlspecialchars($row['Timing']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="pagedes">Page Description</label>
                                    <textarea name="pagedes" id="pagedes" rows="5" class="form-control" required><?php echo htmlspecialchars($row['PageDescription']); ?></textarea>
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