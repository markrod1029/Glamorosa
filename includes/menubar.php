<?php 
include_once('includes/conn.php'); 
?>

<section class="header header-sticky py-2">
    <header class="absolute-top">
        <div class="container">
            <nav class="navbar navbar-expand-lg navbar-light">
                <h1><a class="navbar-brand" href="index.php">
                        Glamorosa
                    </a></h1>
                <button class="navbar-toggler bg-gradient collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="fa icon-expand fa-bars"></span>
                    <span class="fa icon-close fa-times"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="services.php">Services</a>
                        </li>

                        <?php if (isset($_SESSION['role'])) { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Dashboard</a>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="signup.php">Signup</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Login</a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </nav>
        </div>
    </header>
</section>

<style>
    /* Background color for header */
    .header {
        background-color: #cf7dad;
    }

    /* White text for navigation links */
    .header .navbar-nav .nav-link,
    .header .navbar-brand {
        color: #fff !important;
        font-weight: bold;
    }

    /* Toggler button styling */
    .header .navbar-toggler {
        border: none;
        outline: none;
    }

    .header .navbar-toggler .fa {
        color: white;
        font-size: 1.5rem;
    }

    /* Hover effect */
    .header .navbar-nav .nav-link:hover {
        color: #f1c5e1 !important;
        transition: 0.3s;
    }
</style>
