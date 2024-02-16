<?php

/**
 * @package   Update new user
 * @author    Ntabethemba Ntshoza
 * @date      16-02-2024
 * @copyright Copyright © 2024 VMP By Maneza
 */

namespace Controller;

use Model\Person;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class UserEditSubmitController
{
    private $logger;

    /**
     * User Edit constructor
     *
     * @param [type] $logger
     */
    public function __construct($logger = null)
    {
        $this->logger = new Logger('update-user-controller');
        $this->logger->pushHandler(new StreamHandler('var/System.log', Logger::DEBUG));
    }

    /**
     * Submit user update
     *
     * @return void
     */
    public function submit()
    {
        if (!isset($_POST['id'])) {
            // Redirect to the index page
            redirect('/');
        }

        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Create an instance of the Task class
            $person = new Person();

            $id = $_POST['id'];
            $name = $_POST['name'];
            $surname = $_POST['surname'];
            //$saIdNumber = $_POST['sa_id_number'];
            $mobileNumber = $_POST['mobile_number'];
            $emailAddress = $_POST['email_address'];
            // $birthDate = $_POST['birth_date'];
            $language = $_POST['language'];
            $interests = $_POST['interests'];

            $person->setId($id);
            $person->setName($name);
            $person->setSurname($surname);
            // $person->setBirthDate($birthDate);
            $person->setMobileNumber($mobileNumber);
            $person->setEmailAddress($emailAddress);
            $person->setLanguage($language);
            $person->setInterests($interests);
            //$person->setSaIdNumber($saIdNumber);

            // Update the task in the database
            if ($person->update()) {
                $this->logger->info("User {$person->getName()} is updated succesfully ");
                // Redirect back to edit page with success message
                return redirect("/");
            } else {
                $_SESSION['user_update_error'] = "Error during execution of the SQL statement: ";
                return redirect("/user-edit?id=$id");
            }
        }
    }
}
