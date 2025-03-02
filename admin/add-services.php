<?php
include_once('includes/dbconnection.php');
include_once('includes/header.php');

$editMode = isset($_GET['editid']);
$serviceData = [
    'FirstName' => '',
    'LastName' => '',
    'MobileNumber' => '',
    'Email' => '',
    'ServiceName' => '',
    'ServiceDescription' => '',
    'Cost' => '',
    'Image' => ''
];

if ($editMode) {
    $editid = $_GET['editid'];
    $query = "SELECT s.*, u.FirstName, u.LastName, u.MobileNumber, u.Email FROM tblservices s
              INNER JOIN tbluser u ON s.user_id = u.id WHERE s.ID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $editid);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $serviceData = $row;
    }
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
                    <h3 class="title1"><?php echo $editMode ? 'Edit' : 'Add'; ?> Services</h3>
                    <div class="form-grids row widget-shadow">
                        <div class="form-title">
                            <h4>Parlour Services:</h4>
                        </div>
                        <div class="form-body">
                            <form method="post" action="services-add.php" enctype="multipart/form-data">
                                <input type="hidden" name="service_id" value="<?php echo $editMode ? $editid : ''; ?>">

                                <p style="font-size:16px; color:red" align="center">
                                    <?php if (isset($_GET['msg'])) {
                                        echo $_GET['msg'];
                                    } ?>
                                </p>

                                <div class="form-group">
                                    <label for="fname">First Name</label>
                                    <input type="text" class="form-control" id="fname" name="fname" placeholder="Juan" value="<?php echo htmlspecialchars($serviceData['FirstName']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="lname">Last Name</label>
                                    <input type="text" class="form-control" id="lname" name="lname" placeholder="Dela Cruz" value="<?php echo htmlspecialchars($serviceData['LastName']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="mobile">Contact No.</label>
                                    <input type="text" class="form-control" id="mobile" name="mobile" placeholder="09123456789" value="<?php echo htmlspecialchars($serviceData['MobileNumber']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="juan@gmail.com" value="<?php echo htmlspecialchars($serviceData['Email']); ?>" required>
                                </div>

                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
                                    </div>

                                <div class="form-group">
                                    <label for="sername">Service Name</label>
                                    <input type="text" class="form-control" id="sername" name="sername" placeholder="Service Name" value="<?php echo htmlspecialchars($serviceData['ServiceName']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="serdesc">Service Description</label>
                                    <textarea class="form-control" id="serdesc" name="serdesc" placeholder="Service Description" required><?php echo htmlspecialchars($serviceData['ServiceDescription']); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="cost">Cost</label>
                                    <input type="text" id="cost" name="cost" class="form-control" placeholder="Cost" value="<?php echo htmlspecialchars($serviceData['Cost']); ?>" required>
                                </div>

                                <?php if (!$editMode) { ?>
                                    <div class="form-group">
                                        <label for="image">Upload Image</label>
                                        <input type="file" class="form-control" id="image" name="image" required>
                                    </div>
                                <?php } else if ($editMode && $serviceData['Image']) { ?>
                                    <div class="form-group">
                                        <label>Current Image</label><br>
                                        <img src="../assets/images/<?php echo $serviceData['Image']; ?>" width="100" height="100">
                                    </div>
                                <?php } ?>

                                <button type="submit" name="submit" class="btn btn-<?php echo $editMode ? 'primary' : 'success'; ?>">
                                    <?php echo $editMode ? 'Update' : 'Add'; ?>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>