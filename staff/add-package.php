<?php
include_once('includes/header.php');
$msg = "";

// Handle Add or Update
if (isset($_POST['submit'])) {
    $title = mysqli_real_escape_string($con, $_POST['title']);
    $description = $_POST['description'];
    $service = mysqli_real_escape_string($con, $_POST['service']);
    $price = mysqli_real_escape_string($con, $_POST['price']);
    $event_id = isset($_POST['event_id']) ? intval($_POST['event_id']) : null;

    $photo = $_FILES['photo']['name'];
    $photo_tmp = $_FILES['photo']['tmp_name'];

    if (isset($_GET['editid'])) {
        // Update
        $editid = intval($_GET['editid']);

        if (empty($photo)) {
            // Get current photo from DB
            $result = mysqli_query($con, "SELECT photo FROM packages WHERE id=$editid");
            $row = mysqli_fetch_assoc($result);
            $photo = $row['photo'];
        } else {
            // Move new uploaded image
            move_uploaded_file($photo_tmp, "../assets/images/packages/" . $photo);
        }

        $query = "UPDATE packages SET title=?, description=?, service=?, price=?, photo=?, event_id=? WHERE id=?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssssssi", $title, $description, $service, $price, $photo, $event_id, $editid);

        if ($stmt->execute()) {
            $msg = "Package updated successfully!";
        } else {
            $msg = "Something went wrong during update.";
        }
    } else {
        // Insert
        move_uploaded_file($photo_tmp, "../assets/images/packages/" . $photo);
        $query = "INSERT INTO packages (title, description, service, price, photo, event_id) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("sssssi", $title, $description, $service, $price, $photo, $event_id);

        if ($stmt->execute()) {
            $msg = "Package added successfully!";
        } else {
            $msg = "Something went wrong during insertion.";
        }
    }

    $stmt->close();
}

// Fetch data if editing
$package = null;
if (isset($_GET['editid'])) {
    $editid = intval($_GET['editid']);
    $result = mysqli_query($con, "SELECT * FROM packages WHERE id=$editid");
    $package = mysqli_fetch_assoc($result);
}
?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        tinymce.init({
            selector: '#description',
            height: 300,
            plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | link image media table | code fullscreen',
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
                <h3 class="title1"><?php echo isset($package) ? "Update" : "Add"; ?> Package</h3>
                <div class="form-grids row widget-shadow">
                    <div class="form-title">
                        <h4>Package Details:</h4>
                    </div>
                    <div class="form-body">
                        <p style="font-size:16px; color:green; text-align:center;"><?php echo $msg; ?></p>
                        <form method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" class="form-control" name="title" required
                                       value="<?php echo htmlspecialchars($package['title'] ?? ''); ?>">
                            </div>
                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" id="description" class="form-control"
                                          required><?php echo htmlspecialchars($package['description'] ?? ''); ?></textarea>
                            </div>

                            <div class="form-group">
                                <label for="service">Service</label>
                                <select class="form-control" name="service" required>
                                    <option value="">-- Select Service --</option>
                                    <option value="home" <?php echo (isset($package['service']) && $package['service'] == 'home') ? 'selected' : ''; ?>>Home Service</option>
                                    <option value="walk-in" <?php echo (isset($package['service']) && $package['service'] == 'walk-in') ? 'selected' : ''; ?>>Walk-in Service</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="number" class="form-control" name="price" required
                                       value="<?php echo htmlspecialchars($package['price'] ?? ''); ?>">
                            </div>

                            <div class="form-group">
                                <label for="event_id">Event</label>
                                <select name="event_id" class="form-control" required>
                                    <option value="">-- Select Event --</option>
                                    <?php
                                    $eventResult = mysqli_query($con, "SELECT id, title FROM events");
                                    while ($event = mysqli_fetch_assoc($eventResult)) {
                                        $selected = (isset($package['event_id']) && $package['event_id'] == $event['id']) ? 'selected' : '';
                                        echo "<option value='{$event['id']}' $selected>{$event['title']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="photo">Photo</label>
                                <input type="file" class="form-control" name="photo">
                                <?php if (!empty($package['photo'])): ?>
                                    <br>
                                    <img src="../assets/images/packages/<?php echo htmlspecialchars($package['photo']); ?>"
                                         width="100" alt="Current Image">
                                <?php endif; ?>
                            </div>

                            <button type="submit" name="submit" class="btn btn-primary">
                                <?php echo isset($package) ? "Update" : "Add"; ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('includes/footer.php'); ?>
</body>
