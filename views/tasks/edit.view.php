<?php

require BASE_PATH . '/vendor/autoload.php';

$config = include 'config.php';

use Model\Person;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

$user = new Person();
$logger = new Logger('Geo-location');
// Now add some handlers
$logger->pushHandler(new StreamHandler('var/System.log', Logger::DEBUG));

$extensionPath = $config['path']['additionalPath'];

// Fetch the task details for the given ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Create an instance of the Task class and fetch the task by ID
    $user = Person::getUserInfoById($id);

    $birthDate = date('Y-m-d', strtotime($user->getBirthDate()));

    if (!$user) {
        // Handle the case where the task with the provided ID does not exist
        redirect("/");
    }
} else {
    // Handle the case where ID is not provided, perhaps show an error message or redirect
    redirect("/");
    exit();
}

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
    <link rel="stylesheet" href="assets/style.css" type="text/css">

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

        <div id="subheader">
            <div class="container">
                <div class="row">
                    <div class="span12">
                        <h1>Welcome</h1>
                        <span><a href="<?= BASE_URL ?>">Propay</a> / user infortmation</span>
                        <ul class="crumb">
                            <?php if (isset($_SESSION['user_id'])) : ?>
                                <li><a href="<?= BASE_URL . '/users' ?>">Home</a>
                                </li>
                            <?php elseif (!isset($_SESSION['user_id'])) : ?>
                                <li><a href="<?= BASE_URL . '/' ?>">Home</a>
                                </li>
                            <?php endif; ?>
                            <li class="sep">/</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- subheader close -->

        <!-- content begin -->
        <div id="content">
            <div class="container">
                <div class="row">

                    <?php
                    if (isset($_SESSION['registration_error'])) {
                        // Use SweetAlert to display the error message
                        echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "' . $_SESSION['registration_error'] . '"
                            });
                        </script>';
                        // Clear the session variable
                        unset($_SESSION['registration_error']);
                    }
                    ?>

                    <?php if (isset($_SESSION['login_error'])) : ?>
                        <div class="alert alert-error"><?php echo $_SESSION['login_error']; ?></div>
                        <?php //unset($_SESSION['login_error']); // Clear the message after displaying
                        ?>
                    <?php endif; ?>

                    <?php
                    if (isset($_SESSION['login_error'])) {
                        // Use SweetAlert to display the error message
                        echo '<script>
                        Swal.fire({
                            icon: "error",
                            title: "Error",
                            text: "' . $_SESSION['login_error'] . '"
                            });
                        </script>';
                        // Clear the session variable
                        unset($_SESSION['login_error']);
                    }
                    ?>


                    <?php if (isset($_SESSION['registration_success'])) : ?>
                        <div class="alert alert-success"><?php echo $_SESSION['registration_success']; ?></div>
                        <?php unset($_SESSION['registration_success']); // Clear the message after displaying
                        ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['updated_password'])) : ?>
                        <div class="alert alert-success"><?php echo $_SESSION['updated_password']; ?></div>
                        <?php unset($_SESSION['updated_password']); // Clear the message after displaying
                        ?>
                    <?php endif; ?>
                    <!-- Inside your HTML body -->
                    <?php if (isset($error)) : ?>
                        <div class="error-message">
                            <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                        </div>
                    <?php endif; ?>


                    <div class="span8">
                        <div>


                            <div id="contacts" class=" form-container">
                                <form id="contact submitForm" class="row" action="<?= $extensionPath ?>/user-edit-submit" method="post">
                                    <input type="hidden" name="id" value="<?php echo $user->getId(); ?>">
                                    <div class="form-group">
                                        <label for="name">Name:</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $user->getName(); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="surname">Surname:</label>
                                        <input type="text" class="form-control" id="surname" name="surname" value="<?php echo $user->getSurname(); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="sa_id_number">SA ID Number:</label>
                                        <input type="text" class="form-control" id="sa_id_number" name="sa_id_number" disabled value="<?php echo $user->getSaIdNumber(); ?>" required>
                                        <small class="text-danger" style="color: red;">RSA ID can not be edited.</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="mobile_number">Mobile Number:</label>
                                        <input type="text" pattern="[0-9]{10,14}" class="form-control" id="mobile_number" value="<?php echo $user->getMobileNumber(); ?>" name="mobile_number" required>
                                        <small id="mobile_number_error" class="text-danger" style="display: none; color: red;">Invalid Mobile Number format (10 to 14 digits required).</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="email_address">Email Address:</label>
                                        <input type="email" class="form-control" id="email_address" name="email_address" value="<?php echo $user->getEmailAddress(); ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="birth_date">Birth Date:</label>
                                        <input type="date" class="form-control" id="birth_date" name="birth_date" disabled value="<?php echo $user->getBirthDate(); ?>" required>
                                        <small class="text-danger" style="color: red;">Date of birth can not be edited.</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="language">Language:</label>
                                        <select class="form-control" id="language" name="language" required>
                                            <option value="<?php echo $user->getLanguage(); ?>" disabled selected>Select a language</option>
                                            <?php
                                            // Array of South African languages
                                            $languages = ["Zulu", "Xhosa", "Afrikaans", "English", "Sotho", "Tswana", "Venda", "Tsonga", "Swati", "Ndebele"];

                                            // Loop through the languages and create an option for each
                                            foreach ($languages as $language) {
                                                echo "<option value=\"$language\">$language</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="interests">Interests:</label>
                                        <select class="form-control" id="interests" name="interests[]" multiple="multiple" required>
                                            <?php
                                            $interests = ['Reading', 'Traveling', 'Sports', 'Music', 'Coding', 'Hiking', 'Driving', 'surfing'];

                                            foreach ($interests as $interest) {
                                                echo "<option value=\"$interest\">$interest</option>";
                                            }
                                            ?>

                                        </select>
                                    </div>

                                    <div id="btnsubmit">
                                        <button type="submit" class="btn btn-primary" name="Submit">Submit</button>
                                    </div>
                            </div>
                            </form>

                        </div>

                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>
    </div>



    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            // Function to check SA ID Number pattern
            // TODO: Write a proper RSA ID with PHP
            // Function to check Mobile Number pattern
            function checkMobileNumber() {
                var mobileNumber = $('#mobile_number').val();
                var isValid = /^[0-9]{10,14}$/.test(mobileNumber);

                toggleErrorMessage(isValid, '#mobile_number_error');
            }

            // Function to toggle error message visibility
            function toggleErrorMessage(isValid, errorElement) {
                if (!isValid) {
                    $(errorElement).show();
                } else {
                    $(errorElement).hide();
                }
            }

            $('#mobile_number').on('input', checkMobileNumber);
        });
    </script>

    <script>
        $(document).ready(function() {
            function validateEmail(email) {
                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                return emailRegex.test(email);
            }

            function validateMobileNumber(mobileNumber) {
                var mobileNumberRegex = /^\d{10,14}$/;
                return mobileNumberRegex.test(mobileNumber);
            }

            function validateForm() {
                var name = $('#name').val();
                var email = $('#email').val();
                var mobileNumber = $('#mobile_number').val();

                // Validate email and mobile number
                if (!validateEmail(email)) {
                    alert('Invalid email format.');
                    return false;
                }

                if (!validateMobileNumber(mobileNumber)) {
                    alert('Invalid mobile number format.');
                    return false;
                }

                return true;
            }

            $('#submitForm').click(function(e) {
                e.preventDefault();

                if (validateForm()) {
                    $('#contact').submit();
                }
            });
        });
    </script>


    <?php require __DIR__ . '/../../views/partials/footer.php';
