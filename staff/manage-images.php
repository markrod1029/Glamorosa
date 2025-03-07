<?php 
include_once('includes/header.php'); 

if(isset($_GET['delid'])) {
    $sid = intval($_GET['delid']); // Sanitize input

    // Check if the user is a staff member before deleting
    $checkQuery = "SELECT role FROM tbluser WHERE ID = ?";
    $stmt = $con->prepare($checkQuery);
    $stmt->bind_param("i", $sid);
    $stmt->execute();
    $stmt->bind_result($role);
    $stmt->fetch();
    $stmt->close();

    if ($role === 'Staff') {
        // First, delete all services associated with the user
        $deleteServicesQuery = "DELETE FROM tblservices WHERE user_id = ?";
        $stmt = $con->prepare($deleteServicesQuery);
        $stmt->bind_param("i", $sid);
        $stmt->execute();
        $stmt->close();

        // Then, delete the user
        $deleteUserQuery = "DELETE FROM tbluser WHERE ID = ?";
        $stmt = $con->prepare($deleteUserQuery);
        $stmt->bind_param("i", $sid);

        if ($stmt->execute()) {
            echo "<script>alert('Staff and their services have been deleted successfully');</script>";
        } else {
            echo "<script>alert('Error deleting staff');</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('You cannot delete this user because they are not a staff member');</script>";
    }

    echo "<script>window.location.href='manage-services.php'</script>";
}
?>

<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/menubar.php'); ?>

        <div id="page-wrapper" class="row calender widget-shadow">
            <div class="main-page">
                <div class="tables">
                    <h3 class="title1">Manage Services</h3>
                    <div class="table-responsive bs-example widget-shadow">
                        <h4>Staff & Services List:</h4>
                        <p style="font-size:16px; color:green; text-align:center;">
                            <?php if (isset($_GET['msg'])) { echo $_GET['msg']; } ?>
                        </p>

                        <table id="serviceTable" class="table table-bordered display">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Images</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $serviceId = $user['sID'];
                                $query = "SELECT * FROM tblimages WHERE serviceID = '$serviceId'";
                                $result = mysqli_query($con, $query);
                                $cnt = 1;

                                while ($row = mysqli_fetch_array($result)) {
                                ?>
                                    <tr>
                                        <td><?php echo $cnt; ?></td>
                                        <td><?php echo htmlspecialchars($row['image']); ?></td>
                                        <td>
                                            <a href="manage-images.php?delid=<?php echo $row['uID']; ?>" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure you want to delete?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php
                                    $cnt++;
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>

