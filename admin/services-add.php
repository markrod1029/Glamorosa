<?php
include_once('includes/dbconnection.php'); // Ensure database connection

if (isset($_POST['submit'])) {
    $service_id = isset($_POST['service_id']) ? $_POST['service_id'] : null;
    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $lname = mysqli_real_escape_string($con, $_POST['lname']);
    $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $sername = mysqli_real_escape_string($con, $_POST['sername']);
    $serdesc = mysqli_real_escape_string($con, $_POST['serdesc']);
    $cost = mysqli_real_escape_string($con, $_POST['cost']);
    $role = 'Staff';

    // File Upload Handling
    $image = $_FILES['image']['name'] ?? null;
    if ($image) {
        $target_dir = "../assets/images/";
        $target_file = $target_dir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    }

    // **Check if Editing or Adding a New Record**
    if ($service_id) {
        // **Update User**
        $query_user = "UPDATE tbluser 
                        SET FirstName = ?, LastName = ?, MobileNumber = ?, Email = ? 
                        WHERE id = (SELECT user_id FROM tblservices WHERE ID = ?)";
        $stmt = $con->prepare($query_user);
        $stmt->bind_param("ssssi", $fname, $lname, $mobile, $email, $service_id);
        $stmt->execute();
        $stmt->close();

        // **Check if a New Password is Provided**
        if (!empty($_POST['password'])) {
            $password = md5(mysqli_real_escape_string($con, $_POST['password']));
            $query_password = "UPDATE tbluser SET Password = ? WHERE id = (SELECT user_id FROM tblservices WHERE ID = ?)";
            $stmt = $con->prepare($query_password);
            $stmt->bind_param("si", $password, $service_id);
            $stmt->execute();
            $stmt->close();
        }

        // **Update Service**
        $query_service = "UPDATE tblservices 
                            SET ServiceName = ?, ServiceDescription = ?, Cost = ?";
        if ($image) {
            $query_service .= ", image = ?";
        }
        $query_service .= " WHERE ID = ?";

        $stmt = $con->prepare($query_service);
        if ($image) {
            $stmt->bind_param("sssisi", $sername, $serdesc, $cost, $image, $service_id);
        } else {
            $stmt->bind_param("sssi", $sername, $serdesc, $cost, $service_id);
        }
    } else {
        // **Insert New User**
        $password = md5($_POST['password']) ;
        $query_user = "INSERT INTO tbluser (FirstName, LastName, MobileNumber, Email, Password, role, RegDate) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query_user);
        $stmt->bind_param("sssssss", $fname, $lname, $mobile, $email, $password, $role, date('Y-m-d'));
        $stmt->execute();
        $user_id = $stmt->insert_id;
        $stmt->close();

        // **Insert New Service**
        $query_service = "INSERT INTO tblservices (user_id, ServiceName, ServiceDescription, Cost, image) 
                            VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query_service);
        $stmt->bind_param("issss", $user_id, $sername, $serdesc, $cost, $image);
    }

    if ($stmt->execute()) {
        $msg = $service_id ? "Service updated successfully!" : "Service added successfully!";
    } else {
        $msg = "Something went wrong. Please try again.";
    }

    $stmt->close();
    $con->close();

    // Redirect with message
    header("Location: manage-services.php?msg=" . urlencode($msg));
    exit();
}
