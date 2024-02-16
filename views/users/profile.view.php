<?php

/**
 * @package   Task Management
 * @author    Ntabethemba Ntshoza
 * @date      11-10-2023
 * @copyright Copyright © 2023 VMP By Maneza
 */

use Model\Person;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Propay user website</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Responsive Minimal Bootstrap Theme">
    <meta name="keywords" content="responsive,minimal,bootstrap,theme">
    <meta name="author" content="">

    <!--[if lt IE 9]>
	<script src="assets/js/html5shiv.js"></script>
    <link rel="stylesheet" href="css/ie.css" type="text/css">
	<![endif]-->

    <!-- CSS Files
    ================================================== -->
    <link rel="stylesheet" href="assets/css/main.css" type="text/css" id="main-css">

    <!-- Include DataTables CSS and JavaScript
     =================================================-->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


    <!-- Javascript Files
    ================================================== -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/jquery.isotope.min.js"></script>
    <script src="assets/js/jquery.prettyPhoto.js"></script>
    <script src="assets/js/easing.js"></script>
    <script src="assets/js/jquery.ui.totop.js"></script>
    <script src="assets/js/selectnav.js"></script>
    <script src="assets/js/ender.js"></script>
    <script src="assets/js/jquery.lazyload.js"></script>
    <script src="assets/js/jquery.flexslider-min.js"></script>
    <script src="assets/js/custom.js"></script>
    <script src="assets/js/contact.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

</head>

<body>
    <div id="wrapper">


        <!-- added a partial and the code can be reusable -->
        <?php //require __DIR__ . '/../views/partials/nav.php';
?>

        <div id="subheader">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <h1>Welcome</h1>
                        <span><a href="<?= BASE_URL ?>">Propay</a> / user infortmation</span>
                        <ul class="crumb">
                            <?php if (isset($_SESSION['user_id'])): ?>
                                <li><a href="<?=BASE_URL . '/users'?>">Home</a>
                                </li>
                            <?php elseif (!isset($_SESSION['user_id'])): ?>
                                <li><a href="<?=BASE_URL . '/'?>">Home</a>
                                </li>
                            <?php endif;?>
                            <li class="sep">/</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- subheader close -->

        <!-- content begin -->
        <div id="content">
            <!-- services section begin -->
            <section id="services" data-speed="10" data-type="background">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-md-offset-3">
                            <?php

// Check if the user_id parameter is set in the URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Create an instance of the User class
    $user = new Person();

    // Use a function to fetch user details by user_id
    $userData = $user->getUserById($userId);

    if ($userData) {
        // User details found, display them
        $name = $userData['name'];
        $surname = $userData['surname'];
        $saIdNumber = $userData['sa_id_number'];
        $mobileNumber = $userData['mobile_number'];
        $emailAddress = $userData['email_address'];
        $birthDate = $userData['birth_date'];
        $language = $userData['language'];

        if (!empty($userData['interests']) && is_serialized($userData['interests'])) {
            $interests = unserialize($userData['interests']);
        } else {
            $interests = $userData['interests'];
        }

        $interests = is_array($interests) ? implode(', ', $interests) : $userData['interests'];

        echo "<h1>User Details</h1>";
        echo "<br>";
        echo "<div class='panel panel-default'>";
        echo "<div class='panel-body'>";
        echo "<br>";
        echo "<p><strong>Name:</strong> $name</p>";
        echo "<p><strong>Surename:</strong> $surname</p>";
        echo "<p><strong>Mobile Number:</strong> $mobileNumber</p>";
        echo "<p><strong>RSA ID:</strong> $saIdNumber</p>";
        echo "<p><strong>Lungauage:</strong> $language</p>";
        echo "<p><strong>interests:</strong> $interests</p>";
        echo "</div>";
        echo "</div>";
        echo "<br>";

        $base = BASE_URL;
        // Add a "Return to All Tasks" button
        echo "<a href='" . BASE_URL . '/' . "' class='btn btn-primary'>Return to Users</a>";
    } else {
        // User not found, display an error message
        echo "<div class='alert alert-danger'>User not found, make sure you provide a correct user id.</div>";
    }
} else {
    // user_id parameter not set, display an error message
    echo "<div class='alert alert-danger'>Invalid request. Please provide a user ID.</div>";
}
?>
                        </div>
                    </div>
                </div>
            </section>
            <!-- content close -->
            <br />

            <?php require __DIR__ . '/../../views/partials/footer.php'?>

</html>