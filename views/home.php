<?php
require BASE_PATH . '/vendor/autoload.php';

$config = include 'config.php';

use Model\Person;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$users = new Person();
$logger = new Logger('Geo-location');
// Now add some handlers
$logger->pushHandler(new StreamHandler('var/System.log', Logger::DEBUG));

$extensionPath = $config['path']['additionalPath'];
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
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>


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
                            <?php if (isset($_SESSION['user_id'])) : ?>
                                <li><a href="<?= BASE_URL . '/users' ?>">Home</a>
                                </li>
                            <?php elseif (!isset($_SESSION['user_id'])) : ?>
                                <li><a href="<?= BASE_URL . '' ?>">Home</a>
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
                                <form id="contact submitForm" class="row" action="<?= $extensionPath ?>/user-submit" method="post">

                                    <div class="form-group">
                                        <label for="name">Name:</label>
                                        <input type="text" class="form-control" id="name" name="name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="surname">Surname:</label>
                                        <input type="text" class="form-control" id="surname" name="surname" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="sa_id_number">SA ID Number:</label>
                                        <input type="text" pattern="[0-9]{13}" class="form-control" id="sa_id_number" name="sa_id_number" required>
                                        <small id="sa_id_number_error" class="text-danger" style="display: none; color: red;">Invalid SA ID Number format (13 digits required).</small>
                                    </div>

                                    <div class="form-group">
                                        <label for="mobile_number">Mobile Number:</label>
                                        <input type="text" pattern="[0-9]{10,14}" class="form-control" id="mobile_number" name="mobile_number" required>
                                        <small id="mobile_number_error" class="text-danger" style="display: none; color: red;">Invalid Mobile Number format (10 to 14 digits required).</small>
                                    </div>


                                    <div class="form-group">
                                        <label for="email_address">Email Address:</label>
                                        <input type="email" class="form-control" id="email_address" name="email_address" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="birth_date">Birth Date:</label>
                                        <input type="text" placeholder="01/01/1900" class="form-control" id="birth_date" name="birth_date" required>
                                        <small id="birthdateError" style="color: red; display: none;">Invalid date must be DD/MM/YYYY or doesn't match ID</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="language">Language:</label>
                                        <select class="form-control" id="language" name="language" required>
                                            <option value="" disabled selected>Select a language</option>
                                            <?php
                                            // Array of South African languages
                                            // TODO: Add the laguages in the database and retrive them to a dropdown
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
                                            // TODO: Add interests in the database and retrive them to a multiselect
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


            <!-- services section begin -->
            <section id="services" data-speed="10" data-type="background">
                <div id="userTable" class="container">
                    <div class="row">
                        <div class="text-center">
                            <h2>users</h2>
                        </div>
                        <hr class="blank">

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

                        <?php if (isset($_SESSION['registration_success'])) : ?>
                            <div class="alert alert-success"><?php echo $_SESSION['registration_success']; ?></div>
                            <?php unset($_SESSION['registration_success']); // Clear the message after displaying
                            ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['login_success'])) : ?>
                            <div class="alert alert-success"><?php echo $_SESSION['login_success']; ?></div>
                            <?php unset($_SESSION['login_success']); // Clear the message after displaying 
                            ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['user_saved'])) : ?>
                            <div class="alert alert-success"><?php echo $_SESSION['user_saved']; ?></div>
                            <?php unset($_SESSION['user_saved']); // Clear the message after displaying 
                            ?>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['user_updated'])) : ?>
                            <div class="alert alert-success"><?php echo $_SESSION['user_updated']; ?></div>
                            <?php unset($_SESSION['user_updated']); // Clear the message after displaying 
                            ?>
                        <?php endif; ?>

                        <?php
                        // Check if the delete_success query parameter is set
                        if (isset($_GET['delete_success']) && $_GET['delete_success'] == 1) {
                            echo '<div class="alert alert-success">user ' . $users->getId() . ' deleted successfully!</div>';
                        }
                        ?>

                        <?php
                        $allUsers = $users->getAllusers();
                        // Check if there are no users, and display the "Create user" button if true
                        if (empty($allUsers)) {
                            // echo '<a  href="createuser.php" class="btn btn-primary">Create a User</a>';
                        } else {
                        ?>

                            <table id="taskTable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th data-orderable="false">Last Name</th>
                                        <th>Birthday</th>
                                        <th>SA ID</th>
                                        <th>Mobile Number</th>
                                        <th>Email Address</th>
                                        <th>Display</th>
                                        <th data-orderable="false">Edit</th>
                                        <th data-orderable="false">Delete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $allUsers = $users->getAllusers();
                                    ?>
                                    <!-- Loop through your users and display them as table rows -->
                                    <?php foreach ($allUsers as $user) : ?>
                                        <tr>
                                            <td><?php echo $user->getId(); ?></td>
                                            <td><?php echo $user->getName(); ?></td>
                                            <td><?php echo $user->getSurname(); ?></td>
                                            <td><?php echo $user->getBirthDate(); ?></td>
                                            <td><?php echo $user->getSaIdNumber();  ?></td>
                                            <td><?php echo $user->getMobileNumber(); ?></td>
                                            <td><?php echo $user->getEmailAddress(); ?></td>

                                            <td>
                                                <!-- Display user ID as a clickable link -->
                                                <a href="<?= $extensionPath ?>/user-display?id=<?php echo $user->getId(); ?>">
                                                    <button class="btn btn-primary btn-sm">Display</button>
                                                </a>
                                            </td>
                                            <td>
                                                <!-- Edit button -->
                                                <button onclick="edituser(<?php echo $user->getId(); ?>)" class="btn btn-primary btn-sm">Edit</button>
                                            </td>
                                            <td>
                                                <!-- Delete button -->
                                                <button onclick="deleteuser(<?php echo $user->getId(); ?>)" class="btn btn-danger btn-sm">Delete</button>
                                            </td>

                                            <!-- JavaScript function to confirm and delete the user -->
                                            <script>
                                                function deleteuser(userId) {
                                                    if (confirm("Are you sure you want to delete this user?")) {
                                                        window.location.href = "<?= $extensionPath ?>/user-delete?id=" + userId;
                                                    }
                                                }

                                                function edituser(userId) {
                                                    if (confirm("Are you sure you want to edit this user?")) {
                                                        window.location.href = "<?= $extensionPath ?>/user-edit?id=" + userId;
                                                    }
                                                }
                                            </script>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php
                        } // End of else block
                        echo '<a id="addUserButton" href="#" class="btn btn-primary">Add user</a>';
                        ?>

                        <div class="map">
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
    </div>



    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://momentjs.com/downloads/moment.min.js"></script>


    <script>
        $(document).ready(function() {
            // Initially hide the form and table
            $('#contacts').hide();
            $('#userTable').show();

            // Show the form and hide the table when the "Add user" button is clicked
            $('#addUserButton').click(function() {
                $('#contacts').show();
                $('#userTable').hide();
            });

            // Show the table and hide the form when the "Show Table" button is clicked
            $('#showTableButton').click(function() {
                $('#contacts').hide();
                $('#userTable').show();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Function to check SA ID Number pattern
            // TODO: Write a proper RSA ID with PHP
            function checkSAIDNumber() {
                var saIdNumber = $('#sa_id_number').val();
                var isValid = /^[0-9]{13}$/.test(saIdNumber);

                toggleErrorMessage(isValid, '#sa_id_number_error');
            }

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

            // Attach event handlers
            $('#sa_id_number').on('input', checkSAIDNumber);
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


    <script>
        document.getElementById('birth_date').addEventListener('change', function() {
            // Get the selected date
            var selectedDate = new Date(this.value);

            // Get the current date
            var currentDate = new Date();

            // Compare selected date with the current date
            if (selectedDate >= currentDate) {
                // Display error message and clear the input
                document.getElementById('birthdateError').style.display = 'block';
                this.value = '';
            } else {
                // Hide error message
                document.getElementById('birthdateError').style.display = 'none';
            }
        });
    </script>

    <script>
        // ID number and Birthday validation 
        $(document).ready(function () {
        $('#birth_date').on('blur', function () {
            var birthDate = $(this).val();
            var idNumber = $('#sa_id_number').val();

            // Parse date using moment.js with the expected format
            var parsedDate = moment(birthDate, 'DD/MM/YYYY', true);

            // Check if the parsed date is valid and matches the first 6 digits of the ID
            if (parsedDate.isValid() && idNumber.startsWith(parsedDate.format('YYMMDD'))) {
                $('#birthdateError').hide();
            } else {
                $('#birthdateError').show();
            }
        });

        // Form submission validation
        $('form').on('submit', function (event) {
            var birthDate = $('#birth_date').val();
            var idNumber = $('#sa_id_number').val();

            // Parse date using moment.js with the expected format
            var parsedDate = moment(birthDate, 'DD/MM/YYYY', true);

            // Check if the parsed date is valid and matches the first 6 digits of the ID
            if (!parsedDate.isValid() || !idNumber.startsWith(parsedDate.format('YYMMDD'))) {
                $('#birthdateError').show();
                event.preventDefault(); // Prevent form submission
            } else {
                $('#birthdateError').hide();
            }
        });
    });
    </script>



    <?php require __DIR__ . '/../views/partials/footer.php';
