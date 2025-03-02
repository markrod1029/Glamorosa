<?php
include_once('includes/header.php');

$msg = "";

if (isset($_GET['delid'])) {
    $delid = intval($_GET['delid']); // Ensure it's an integer
    $query = "DELETE FROM tbluser WHERE ID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $delid);

    if ($stmt->execute()) {
        echo "<script>
                alert('Customer deleted successfully!');
                window.location.href = 'customer-list.php'; 
              </script>";
    } else {
        echo "<script>
                alert('Error deleting customer.');
              </script>";
    }
    $stmt->close();
}

?>

<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/menubar.php'); ?>

        <div id="page-wrapper" class="row calender widget-shadow">
            <div class="main-page">
                <div class="tables">
                    <h3 class="title1">Manage Customer</h3>
                    <div class="table-responsive bs-example widget-shadow">
                        <h4>Customer List:</h4>
                        <p style="font-size:16px; color:green; text-align:center;">
                            <?php if (isset($_GET['msg'])) {
                                echo $_GET['msg'];
                            } ?>
                        </p>

                        <table id="serviceTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Mobile Number</th>
                                    <th>Email</th>
                                    <th>Registration Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cnt = 1;
                                $ret = mysqli_query($con, "SELECT * FROM tbluser WHERE Role='Customer' ORDER BY ID DESC");

                                while ($row = mysqli_fetch_array($ret)) { ?>
                                    <tr>
                                        <th scope="row"><?php echo $cnt; ?></th>
                                        <td><?php echo htmlspecialchars($row['FirstName'] . ' ' . $row['LastName']); ?></td>
                                        <td><?php echo htmlspecialchars($row['MobileNumber']); ?></td>
                                        <td><?php echo htmlspecialchars($row['Email']); ?></td>
                                        <td><?php echo htmlspecialchars($row['RegDate']); ?></td>
                                        <td>
                                            <a href="customer-list.php?delid=<?php echo $row['ID']; ?>" class="btn btn-danger"
                                                onClick="return confirm('Are you sure you want to delete?')">Delete</a>
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