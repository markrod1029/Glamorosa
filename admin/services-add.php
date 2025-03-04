<?php
include_once('includes/dbconnection.php'); // Ensure database connection

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Ensure PHPMailer is autoloaded

if (isset($_POST['submit'])) {
    // Retrieve and sanitize form inputs
    $service_id = isset($_POST['service_id']) ? $_POST['service_id'] : null;
    $fname = mysqli_real_escape_string($con, $_POST['fname']);
    $lname = mysqli_real_escape_string($con, $_POST['lname']);
    $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $sername = mysqli_real_escape_string($con, $_POST['sername']);
    $serdesc = mysqli_real_escape_string($con, $_POST['serdesc']);
    $cost = mysqli_real_escape_string($con, $_POST['cost']);
    $role = 'Staff';

    // Handle file upload
    $image = $_FILES['image']['name'] ?? null;
    if ($image) {
        $target_dir = "../assets/images/";
        $target_file = $target_dir . basename($image);
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    }

    // Check if editing an existing record or adding a new one
    if ($service_id) {
        // Update existing user details
        $query_user = "UPDATE tbluser 
                       SET FirstName = ?, LastName = ?, MobileNumber = ?, Email = ? 
                       WHERE id = (SELECT user_id FROM tblservices WHERE ID = ?)";
        $stmt = $con->prepare($query_user);
        $stmt->bind_param("ssssi", $fname, $lname, $mobile, $email, $service_id);
        $stmt->execute();
        $stmt->close();

        // Update password if provided
        if (!empty($_POST['password'])) {
            $password = md5(mysqli_real_escape_string($con, $_POST['password']));
            $query_password = "UPDATE tbluser SET Password = ? WHERE id = (SELECT user_id FROM tblservices WHERE ID = ?)";
            $stmt = $con->prepare($query_password);
            $stmt->bind_param("si", $password, $service_id);
            $stmt->execute();
            $stmt->close();
        }

        // Update service details
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
        $stmt->execute();
        $stmt->close();

        $msg = "Service updated successfully!";
    } else {
        // Insert new user
        $password_plain = $_POST['password'];
        $password_hashed = md5($password_plain);
        $query_user = "INSERT INTO tbluser (FirstName, LastName, MobileNumber, Email, Password, role, RegDate) 
                       VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query_user);
        $stmt->bind_param("sssssss", $fname, $lname, $mobile, $email, $password_hashed, $role, date('Y-m-d'));
        $stmt->execute();
        $user_id = $stmt->insert_id;
        $stmt->close();

        // Insert new service
        $query_service = "INSERT INTO tblservices (user_id, ServiceName, ServiceDescription, Cost, image) 
                          VALUES (?, ?, ?, ?, ?)";
        $stmt = $con->prepare($query_service);
        $stmt->bind_param("issss", $user_id, $sername, $serdesc, $cost, $image);
        $stmt->execute();
        $stmt->close();

        // Send email with credentials
        if (sendCredentialsEmail($email, $fname, $email, $password_plain)) {
            $msg = "Service added successfully! Credentials have been sent to the user's email.";
        } else {
            $msg = "Service added, but failed to send credentials email.";
        }
    }

    $con->close();

    // Redirect with message
    header("Location: manage-services.php?msg=" . urlencode($msg));
    exit();
}

// Function to send credentials email
function sendCredentialsEmail($toEmail, $name, $userEmail, $userPassword) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'milcenresort@gmail.com'; 
        $mail->Password   = 'sqkcigkgngypnnpj'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('admin@gmail.com', 'Glamorosa'); 
        $mail->addAddress($toEmail, $name);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your Account Credentials';
        $mail->Body    = "Dear $name,<br><br>Your account has been created. Here are your credentials:<br>Email: $userEmail<br>Password: $userPassword<br><br>Please keep this information secure.";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
?>
