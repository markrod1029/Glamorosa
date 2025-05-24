<?php
include_once('includes/header.php');

$msg = "";
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}

$userID = $user['ID']; // Assuming $user is the logged-in user
$baseDir = "../assets/images/qrcode/";
$targetDir = $baseDir . $userID . "/";

// Create directory if not exists
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
}

// Fetch existing QR code path from database
$result = mysqli_query($con, "SELECT qrcode FROM tbluser WHERE ID = '$userID'");
$row = mysqli_fetch_assoc($result);
$existingQRCode = $row['qrcode'];

// Handle QR code upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    if (!empty($_FILES["image"]["name"])) {
        // Delete old QR code if exists
        if (!empty($existingQRCode)) {
            $oldPath = "../assets/images/" . $existingQRCode;
            if (file_exists($oldPath)) unlink($oldPath);
        }

        $originalName = basename($_FILES["image"]["name"]);
        $imageFileType = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowedTypes = ["jpg", "jpeg", "png", "gif"];

        if (in_array($imageFileType, $allowedTypes)) {
            $newFileName = "qr_" . time() . "_" . uniqid() . "." . $imageFileType;
            $imageFilePath = $targetDir . $newFileName;

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $imageFilePath)) {
                $relativePath = "qrcode/$userID/$newFileName";
                $updateQuery = mysqli_query($con, "UPDATE tbluser SET qrcode = '$relativePath' WHERE ID = '$userID'");

                if ($updateQuery) {
                    $_SESSION['msg'] = "✅ QR Code uploaded successfully!";
                } else {
                    $_SESSION['msg'] = "❌ Database update failed.";
                }
            } else {
                $_SESSION['msg'] = "❌ Failed to move uploaded file.";
            }
        } else {
            $_SESSION['msg'] = "⚠️ Invalid file format. Use JPG, JPEG, PNG, or GIF.";
        }

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['msg'] = "⚠️ Please select an image.";
    }
}

// Handle QR code deletion
if (isset($_GET['delete'])) {
    if (!empty($existingQRCode)) {
        $deletePath = "../assets/images/" . $existingQRCode;
        if (file_exists($deletePath)) unlink($deletePath);

        mysqli_query($con, "UPDATE tbluser SET qrcode = NULL WHERE ID = '$userID'");
        $_SESSION['msg'] = "✅ QR Code deleted successfully!";
    } else {
        $_SESSION['msg'] = "⚠️ No QR code to delete.";
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<body class="cbp-spmenu-push">
    <div class="main-content">
        <?php include_once('includes/sidebar.php'); ?>
        <?php include_once('includes/menubar.php'); ?>

        <div id="page-wrapper" class="row calender widget-shadow">
            <div class="main-page">
                <div class="forms">
                    <h3 class="title1">QR Code Upload</h3>
                    <div class="form-grids row widget-shadow" data-example-id="basic-forms">
                        <div class="form-title">
                            <h4>Upload QR Code:</h4>
                        </div>
                        <div class="form-body">
                            <form method="post" enctype="multipart/form-data">
                                <?php if (!empty($msg)) { ?>
                                    <p style="color:green; text-align:center;"><?php echo $msg; ?></p>
                                <?php } ?>

                                <div class="form-group">
                                    <label>QR Code Image</label><br>
                                    <input type="file" name="image" class="form-control"><br>
                                </div>

                                <button type="submit" name="submit" class="btn btn-primary">Upload</button>
                            </form>
                        </div>
                    </div>


                    <?php if (!empty($existingQRCode)) { ?>
                        <div class="form-grids row widget-shadow">
                            <div class="form-title">
                                <h4>Current QR Code:</h4>
                            </div>
                            <div class="form-body text-center">
                                <img src="../assets/images/<?php echo $existingQRCode; ?>" class="img-thumbnail" style="width:150px;height:150px;">
                                <br><br>
                                <a href="?delete=1" class="btn btn-danger">Delete QR Code</a>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>
</body