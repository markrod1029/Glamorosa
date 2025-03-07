<?php 
include_once('includes/header.php');

$msg = "";
$serviceID = $user['sID'];

// Check for existing video
$videoQuery = mysqli_query($con, "SELECT video FROM tblvideo WHERE serviceID = '$serviceID'");
$existingVideo = mysqli_fetch_assoc($videoQuery);
$videoPath = $existingVideo ? "../assets/videos/" . $existingVideo['video'] : "";

if (isset($_POST['submit'])) {
    if (!empty($_FILES["video"]["name"])) {
        $baseDir = "../assets/videos/"; // Base folder
        $targetDir = $baseDir . $serviceID . "/"; // Service ID folder
        $videoName = basename($_FILES["video"]["name"]);
        $videoFilePath = $targetDir . $videoName;
        $videoFileType = strtolower(pathinfo($videoFilePath, PATHINFO_EXTENSION));

        // Allowed video formats
        $allowedTypes = ["mp4", "avi", "mov", "wmv"];

        // Create folder if it doesn't exist
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        if (in_array($videoFileType, $allowedTypes)) {
            // Move uploaded file
            if (move_uploaded_file($_FILES["video"]["tmp_name"], $videoFilePath)) {
                if ($existingVideo) {
                    // Update existing video
                    $query = mysqli_query($con, "UPDATE tblvideo SET video = '$serviceID/$videoName', RegDate = NOW() WHERE serviceID = '$serviceID'");
                    $msg = "Video updated successfully!";
                } else {
                    // Insert new video
                    $query = mysqli_query($con, "INSERT INTO tblvideo (serviceID, video, RegDate) VALUES ('$serviceID', '$serviceID/$videoName', NOW())");
                    $msg = "Video uploaded successfully!";
                }

                if ($query) {
                    $videoPath = "../assets/videos/" . $serviceID . "/" . $videoName; // Update video path for display
                } else {
                    $msg = "Database error: Could not upload video.";
                }
            } else {
                $msg = "Error uploading video file.";
            }
        } else {
            $msg = "Invalid file format. Only MP4, AVI, MOV, and WMV are allowed.";
        }
    } else {
        $msg = "Please select a video file to upload.";
    }
}
?>

<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/menubar.php'); ?>

        <div id="page-wrapper" class="row calender widget-shadow">
            <div class="main-page">
                <div class="forms">
                    <h3 class="title1">Service Video</h3>
                    <div class="form-grids row widget-shadow" data-example-id="basic-forms">
                        <div class="form-title">
                            <h4><?php echo $existingVideo ? "Update" : "Add"; ?> Service Video:</h4>
                        </div>
                        <div class="form-body">
                            <form method="post" enctype="multipart/form-data">
                                <?php if (!empty($msg)) { ?>
                                    <p style="font-size:16px; color:green; text-align:center;"><?php echo $msg; ?></p>
                                <?php } ?>

                                <div class="form-group">
                                    <label>Service Video</label><br>
                                    <input type="file" class="form-control" id="video" name="video" required>
                                    <br>
                                </div>

                                <?php if ($videoPath) { ?>
                                <div class="form-group">
                                    <h4>Current Video:</h4>
                                    <video width="400" controls>
                                        <source src="<?php echo $videoPath; ?>" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            <?php } ?>
                            
                                <button type="submit" name="submit" class="btn btn-primary">
                                    <?php echo $existingVideo ? "Update" : "Add"; ?>
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
