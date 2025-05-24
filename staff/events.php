<?php
include_once('includes/header.php');
$msg = "";

// Handle Delete
if (isset($_GET['delid'])) {
    $delid = intval($_GET['delid']);
    $query = "DELETE FROM events WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $delid);

    if ($stmt->execute()) {
        echo "<script>
                alert('Event deleted successfully!');
                window.location.href = 'events.php';
              </script>";
    } else {
        echo "<script>
                alert('Error deleting event.');
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
                    <h3 class="title1">Manage Events</h3>
                    <div class="table-responsive bs-example widget-shadow">
                        <h4>Event List:</h4>

                        <?php if (isset($_GET['msg'])): ?>
                            <p style="font-size:16px; color:green; text-align:center;">
                                <?php echo htmlspecialchars($_GET['msg']); ?>
                            </p>
                        <?php endif; ?>

                        <table id="serviceTable" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $cnt = 1;
                                $ret = mysqli_query($con, "SELECT * FROM events ORDER BY id DESC");

                                while ($row = mysqli_fetch_assoc($ret)) { ?>
                                    <tr>
                                        <td><?php echo $cnt++; ?></td>
                                        <td><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><?php echo substr(strip_tags($row['description']), 0, 50) . '...'; ?></td>
                                        <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                                        <td>
                                            <a href="add-event.php?edit_id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                            <a href="events.php?delid=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm"
                                               onClick="return confirm('Are you sure you want to delete this event?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                      
                    </div>
                </div>
            </div>
        </div>

        <?php include_once('includes/footer.php'); ?>
    </div>
</body>
