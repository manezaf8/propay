<?php

/**
 * @package   Add a new user
 * @author    Ntabethemba Ntshoza
 * @date      16-02-2024
 * @copyright Copyright © 2024 VMP By Maneza
 */

namespace Controller;

use Events\NewUserRegisteredEvent;
use League\Event\EventDispatcher;
use Model\Person;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class AddPersonController
{
    /**
     * @var Logger
     */
    private $logger;

    private $dispatcher;

    /**
     * Add Users constructor
     *
     * @param EventDispatcher $dispatcher
     */
    public function __construct(EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->logger = new Logger('submit-controller');
        $this->logger->pushHandler(new StreamHandler('var/System.log', Logger::DEBUG));
    }

    /**
     * Submit A user
     *
     * @return void
     */
    public function submit()
    {
        $person = new Person();

        // Handle login form submission logic
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // User clicked the "Create User" button
            $name = $_POST['name'];
            $surname = $_POST['surname'];
            $saIdNumber = $_POST['sa_id_number'];
            $mobileNumber = $_POST['mobile_number'];
            $emailAddress = $_POST['email_address'];
            $birthDate = $_POST['birth_date'];
            $language = $_POST['language'];
            $interests = $_POST['interests'];

            // Check if email and SA ID already exist
            if ($person->checkIfExists($emailAddress, $saIdNumber)) {
                $_SESSION['registration_error'] = 'This email address or SA ID is already in use.';
                return redirect('/');
            }

            // Password is valid, create the user
            $person->setName($name);
            $person->setSurname($surname);
            $person->setBirthDate($birthDate);
            $person->setMobileNumber($mobileNumber);
            $person->setEmailAddress($emailAddress);
            $person->setLanguage($language);
            $person->setInterests($interests);
            $person->setSaIdNumber($saIdNumber);

            try {
                if ($person->submitToDatabase()) {
                    $_SESSION['registration_success'] = 'Heey!!! you added  ' . $person->getName() . '  successfully...';
                    $userId = $person->getId();
                    // Trigger the event
                    $event = new NewUserRegisteredEvent((int) $userId);
                    $this->dispatcher->dispatch($event);

                    return redirect('/');
                }
            } catch (\Throwable $th) {
                $_SESSION['registration_error'] = 'An error occurred while registering.';
                $this->logger->critical(var_export($th, true));
            }
        }

        return redirect('/');
    }
}
