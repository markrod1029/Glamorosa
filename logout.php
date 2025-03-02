<?php
session_start(); // Start session kung hindi pa
session_unset(); // I-unset lahat ng session variables
session_destroy(); // I-destroy ang session
header("Location: login.php"); // Redirect sa homepage o login page
exit(); // Siguraduhin na walang ibang code na mag-e-execute