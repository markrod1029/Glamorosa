<?php 
include_once('includes/header.php');

$msg = "";
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']); // Clear message after displaying
}

$serviceID = $user['sID'];

// Base folder for images
$baseDir = "../assets/images/service/";
$targetDir = $baseDir . $serviceID . "/";

// Create images directory if it doesn't exist
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Check how many images are already uploaded
$imageQuery = mysqli_query($con, "SELECT * FROM tblimages WHERE serviceID = '$serviceID'");
$imageCount = mysqli_num_rows($imageQuery);

// Handle image upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    if (!empty($_FILES["image"]["name"])) {
        if ($imageCount < 5) { // Limit to 5 images
            $originalName = basename($_FILES["image"]["name"]);
            $imageFileType = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

            // Allowed image formats
            $allowedTypes = ["jpg", "jpeg", "png", "gif"];

            if (in_array($imageFileType, $allowedTypes)) {
                // Generate a unique filename
                $newFileName = "IMG_" . time() . "_" . uniqid() . "." . $imageFileType;
                $imageFilePath = $targetDir . $newFileName;

                if (move_uploaded_file($_FILES["image"]["tmp_name"], $imageFilePath)) {
                    // Save relative path to database
                    $dbPath = "service/$serviceID/$newFileName";
                    $query = mysqli_query($con, "INSERT INTO tblimages (serviceID, image, RegDate) VALUES ('$serviceID', '$dbPath', NOW())");

                    if ($query) {
                        $_SESSION['msg'] = "✅ Image uploaded successfully!";
                    } else {
                        $_SESSION['msg'] = "❌ Database error: Could not upload image.";
                    }
                } else {
                    $_SESSION['msg'] = "❌ Error uploading image file.";
                }
            } else {
                $_SESSION['msg'] = "⚠️ Invalid file format. Only JPG, JPEG, PNG, and GIF are allowed.";
            }
        } else {
            $_SESSION['msg'] = "⚠️ Maximum of 5 images allowed. Delete an existing image to upload a new one.";
        }
    } else {
        $_SESSION['msg'] = "⚠️ Please select an image file to upload.";
    }

    // Prevent form resubmission by redirecting
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Handle image deletion
if (isset($_GET['delete'])) {
    $imageID = $_GET['delete'];

    // Get image path
    $deleteQuery = mysqli_query($con, "SELECT image FROM tblimages WHERE ID = '$imageID' AND serviceID = '$serviceID'");
    $imageData = mysqli_fetch_assoc($deleteQuery);
    
    if ($imageData) {
        $filePath = "../assets/images/" . $imageData['image'];

        // Delete image file
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Remove from database
        mysqli_query($con, "DELETE FROM tblimages WHERE ID = '$imageID'");
        $_SESSION['msg'] = "✅ Image deleted successfully!";

        // Prevent delete resubmission on refresh
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['msg'] = "❌ Image not found.";
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
                    <h3 class="title1">Service Images</h3>
                    <div class="form-grids row widget-shadow" data-example-id="basic-forms">
                        <div class="form-title">
                            <h4>Add Service Image:</h4>
                        </div>
                        <div class="form-body">
                            <form method="post" enctype="multipart/form-data">
                                <?php if (!empty($msg)) { ?>
                                    <p style="font-size:16px; color:green; text-align:center;"><?php echo $msg; ?></p>
                                <?php } ?>

                                <div class="form-group">
                                    <label>Service Image</label><br>
                                    <input type="file" class="form-control" id="image" name="image">
                                    <br>
                                </div>

                                <button type="submit" name="submit" class="btn btn-primary">Upload</button>
                            </form>
                        </div>
                    </div>

                    <div class="form-grids row widget-shadow">
                        <div class="form-title">
                            <h4>Uploaded Images:</h4>
                        </div>
                        <div class="form-body">
                            <div class="row">
                                <?php
                                $imageResult = mysqli_query($con, "SELECT * FROM tblimages WHERE serviceID = '$serviceID'");
                                while ($row = mysqli_fetch_assoc($imageResult)) {
                                    $imageURL = "../assets/images/" . $row['image'];
                                    echo '
                                    <div class="col-md-2 text-center">
                                        <img src="' . $imageURL . '" class="img-thumbnail" style="width:100px; height:100px;">
                                        <br>
                                        <a href="?delete=' . $row['ID'] . '" class="btn btn-danger btn-sm mt-2">Delete</a>
                                    </div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>
</body
