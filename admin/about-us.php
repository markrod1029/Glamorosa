<?php
include_once('includes/header.php');

$msg = "";

if (isset($_POST['submit'])) {
    $pagetitle = mysqli_real_escape_string($con, $_POST['pagetitle']);
    $pagedes = $_POST['pagedes']; // Huwag gamitin ang mysqli_real_escape_string upang hindi mawala ang HTML formatting

    $query = "UPDATE tblpage SET PageTitle = ?, PageDescription = ? WHERE PageType = 'aboutus'";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ss", $pagetitle, $pagedes);

    if ($stmt->execute()) {
        $msg = "About Us page updated successfully!";
    } else {
        $msg = "Something went wrong. Please try again.";
    }

    $stmt->close();
}

$ret = mysqli_query($con, "SELECT * FROM tblpage WHERE PageType = 'aboutus'");
$row = mysqli_fetch_assoc($ret);
?>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        tinymce.init({
            selector: '#pagedes',
            height: 300,
            plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
            toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | forecolor backcolor | link image media table | code fullscreen',
            menubar: false,
            branding: false,
            content_css: '/tinymce/skins/ui/oxide/skin.min.css' // Siguraduhin na ito ay tama
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
                    <h3 class="title1">Update About Us</h3>
                    <div class="form-grids row widget-shadow">
                        <div class="form-title">
                            <h4>Update About Us:</h4>
                        </div>
                        <div class="form-body">
                            <form method="post">
                                <p style="font-size:16px; color:green; text-align:center;">
                                    <?php if ($msg) { echo $msg; } ?>
                                </p>

                                <div class="form-group">
                                    <label for="pagetitle">Page Title</label>
                                    <input type="text" class="form-control" name="pagetitle" id="pagetitle" value="<?php echo htmlspecialchars($row['PageTitle']); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label for="pagedes">Page Description</label>
                                    <textarea name="pagedes" id="pagedes" rows="5" class="form-control" required><?php echo htmlspecialchars($row['PageDescription']); ?></textarea>
                                </div>

                                <button type="submit" name="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once('includes/footer.php'); ?>
</body>
