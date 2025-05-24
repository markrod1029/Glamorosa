<?php
include_once('includes/header.php');

// Handle deletion
if (isset($_GET['delid'])) {
    $id = intval($_GET['delid']);
    $check = mysqli_query($con, "SELECT photo FROM packages WHERE id=$id");
    $row = mysqli_fetch_assoc($check);

    // Delete image file
    if (!empty($row['photo']) && file_exists("../assets/images/packages/" . $row['photo'])) {
        unlink("../assets/images/packages/" . $row['photo']);
    }

    // Delete record
    mysqli_query($con, "DELETE FROM packages WHERE id=$id");
    echo "<script>alert('Package deleted successfully'); window.location='packages.php';</script>";
}
?>

<body class="cbp-spmenu-push">
<div class="main-content">
    <?php include_once('includes/sidebar.php'); ?>
    <?php include_once('includes/menubar.php'); ?>

    <div id="page-wrapper" class="row calender widget-shadow">
        <div class="main-page">
            <div class="tables">
                <h3 class="title1">All Packages</h3>
                <div class="table-responsive bs-example widget-shadow">
                    <h4>Package List</h4>
                    <table id="serviceTable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Service</th>
                                <th>Price</th>
                                <th>Event</th>
                                <th>Photo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Get packages with event names using LEFT JOIN
                            $ret = mysqli_query($con, "
                                SELECT 
                                    p.*, 
                                    e.title AS event_name
                                FROM 
                                    packages p 
                                LEFT JOIN 
                                    events e ON p.event_id = e.id 
                                ORDER BY p.id DESC
                            ");
                            $cnt = 1;
                            while ($row = mysqli_fetch_array($ret)) {
                                ?>
                                <tr>
                                    <td><?php echo $cnt++; ?></td>
                                    <td><?php echo htmlspecialchars($row['title']); ?></td>
                                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                                    <td><?php echo htmlspecialchars($row['service']); ?></td>
                                    <td>₱<?php echo number_format($row['price'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($row['event_name'] ?? 'No Event'); ?></td>
                                    <td>
                                        <?php if (!empty($row['photo'])): ?>
                                            <img src="../assets/images/packages/<?php echo htmlspecialchars($row['photo']); ?>" width="100">
                                        <?php else: ?>
                                            No image
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="add-package.php?editid=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                        <a href="packages.php?delid=<?php echo $row['id']; ?>"
                                           onclick="return confirm('Are you sure you want to delete this package?')"
                                           class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                            <?php } ?>
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>
</body>
