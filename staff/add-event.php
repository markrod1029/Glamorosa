<?php
include_once('includes/header.php');

$msg = "";
$title = "";
$description = "";
$update_mode = false;
$id = 0;

// Make sure $user['sID'] exists and is the service ID assigned to current staff
// If not available, replace with the correct service ID you want to assign
$service_id_default = isset($user['sID']) ? intval($user['sID']) : 0;

// Handle Edit
if (isset($_GET['edit_id'])) {
    $id = intval($_GET['edit_id']);
    $res = mysqli_query($con, "SELECT * FROM events WHERE id = $id");
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $title = $row['title'];
        $description = $row['description'];
        $service_id_default = intval($row['service_id']);  // load current service_id for editing
        $update_mode = true;
    }
}

// Handle Submit
if (isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($con, $_POST['pagetitle']);
    $description = $_POST['pagedes'];
    $service_id = intval($_POST['service_id']); // get service_id from form

    if (!empty($_POST['id'])) {
        // Update
        $id = intval($_POST['id']);
        $query = "UPDATE events SET title = ?, description = ?, service_id = ? WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssii", $title, $description, $service_id, $id);
        $stmt->execute();
        $msg = "Event updated successfully!";
    } else {
        // Insert
        $query = "INSERT INTO events (title, description, service_id, created_at) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        
        $created_at = date('Y-m-d H:i:s');
        $stmt->bind_param("ssis", $title, $description, $service_id, $created_at);
        $stmt->execute();
        $msg = "Event added successfully!";
    }
}
?>

<!-- TinyMCE Editor -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    tinymce.init({
        selector: '#pagedes',
        height: 300,
        plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
        toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor | link image media table | code fullscreen',
        menubar: false,
        branding: false
    });
});
</script>

<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/menubar.php'); ?>

        <div id="page-wrapper" class="row calender widget-shadow">
            <div class="main-page">
                <div class="forms">
                    <h3 class="title1 "><?php echo $update_mode ? 'Edit Event' : 'Add Event'; ?></h3>

                    <div class="form-grids row widget-shadow">
                        <?php if ($msg): ?>
                            <div class="alert alert-success text-center">
                                <?php echo $msg; ?>
                            </div>
                        <?php endif; ?>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title"><?php echo $update_mode ? 'Update Existing Event' : 'Create New Event'; ?></h4>
                            </div>

                            <div class="panel-body">
                                <form method="post">
                                    <?php if ($update_mode): ?>
                                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                                    <?php endif; ?>

                                    <div class="form-group">
                                        <label for="pagetitle">Event Title</label>
                                        <input type="text" name="pagetitle" id="pagetitle" class="form-control" value="<?php echo htmlspecialchars($title); ?>" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="pagedes">Event Description</label>
                                        <textarea name="pagedes" id="pagedes" class="form-control" rows="10"><?php echo htmlspecialchars($description); ?></textarea>
                                    </div>

                                    <!-- Hidden input for service_id -->
                                    <input type="hidden" name="service_id" id="service_id" value="<?php echo $service_id_default; ?>">

                                    <div class="form-group text-center">
                                        <button type="submit" name="submit" class="btn btn-success btn-lg">
                                            <?php echo $update_mode ? 'Update Event' : 'Add Event'; ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Optional: Return to list button -->
                        
                    </div>
                </div>
            </div>
        </div>

        <?php include_once('includes/footer.php'); ?>
    </div>
</body>
