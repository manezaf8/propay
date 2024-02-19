<?php

/**
 * @package   User Management class
 * @author    Ntabethemba Ntshoza
 * @date      15-102-2024
 * @copyright Copyright © 2024 VMP By Maneza
 */

namespace Model;

use Core\Database;
use DateTime;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use mysqli;

class Person
{
    private $id;
    private $name;
    private $surname;
    private $saIdNumber;
    private $mobileNumber;
    private $emailAddress;
    private $birthDate;
    private $language;
    private $interests;
    private $db;
    private $logger;

    /**
     * Person constructor
     *
     * @param [type] $id
     * @param [type] $name
     * @param [type] $surname
     * @param [type] $saIdNumber
     * @param [type] $mobileNumber
     * @param [type] $emailAddress
     * @param [type] $birthDate
     * @param [type] $language
     * @param [type] $interests
     * @param mysqli|null $db
     */
    public function __construct(
        $id = null,
        $name = null,
        $surname = null,
        $saIdNumber = null,
        $mobileNumber = null,
        $emailAddress = null,
        $birthDate = null,
        $language = null,
        $interests = null,
        mysqli $db = null
    ) {
        $this->id;
        $this->name = $name;
        $this->surname = $surname;
        $this->saIdNumber = $saIdNumber;
        $this->mobileNumber = $mobileNumber;
        $this->emailAddress = $emailAddress;
        $this->birthDate = $birthDate;
        $this->language = $language;
        $this->interests = $interests;
        $this->db = Database::connect();
        $this->logger = new Logger('Person-Model');
        $this->logger->pushHandler(new StreamHandler('var/System.log', Logger::DEBUG));
    }

    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    public function setSaIdNumber($saIdNumber)
    {
        $this->saIdNumber = $saIdNumber;
    }

    public function setMobileNumber($mobileNumber)
    {
        $this->mobileNumber = $mobileNumber;
    }

    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }

    public function setInterests($interests)
    {
        $this->interests = $interests;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getName()
    {
        return $this->name;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function getSaIdNumber()
    {
        return $this->saIdNumber;
    }

    public function getMobileNumber()
    {
        return $this->mobileNumber;
    }

    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    public function getBirthDate()
    {
        return $this->birthDate;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function getInterests()
    {
        return $this->interests;
    }

    /**
     * Email validation
     *
     * @param [type] $email
     * @return void
     */
    public function validateEmail($email)
    {
        // Use PHP's built-in filter_var function to validate email
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Mobile validation
     *
     * @param [type] $mobileNumber
     * @return void
     */
    public function validateMobileNumber($mobileNumber)
    {
        // Remove non-numeric characters from the mobile number
        $numericMobileNumber = preg_replace('/[^0-9]/', '', $mobileNumber);

        // Check if the resulting numeric mobile number has a valid length
        return strlen($numericMobileNumber) >= 10 && strlen($numericMobileNumber) <= 14;
    }

    /**
     * RSA ID Validation
     *
     * @param [type] $rsaIdNumber
     * @return void
     */
    public function validateSAIdNumber($rsaIdNumber)
    {
        // Remove non-numeric characters from the mobile number
        $numericMobileNumber = preg_replace('/[^0-9]/', '', $rsaIdNumber);

        // Check if the resulting numeric mobile number has a valid length
        return strlen($numericMobileNumber) == 13;
    }

    /**
     * Submit to database
     *
     * @return void
     */
    public function submitToDatabase()
    {
        // Serialize the interests array before storing in the database
        $serializedInterests = serialize($this->interests);

        // Convert the birth date to MySQL-friendly format
        $birthDate = DateTime::createFromFormat('d/m/Y', $this->birthDate);
        $formattedBirthDate = $birthDate ? $birthDate->format('Y-m-d') : null;

        if (!$this->validateEmail($this->emailAddress) || !$this->validateMobileNumber($this->mobileNumber)) {
            // Handle validation failure (e.g., set an error message, redirect, etc.)
            $_SESSION['registration_error'] = 'Invalid email or mobile number format.';
            return false;
        }

        if (!$this->validateSAIdNumber($this->saIdNumber)) {
            // Handle validation failure (e.g., set an error message, redirect, etc.)
            $_SESSION['registration_error'] = 'Invalid email or mobile number format.';
            return false;
        }

        $sql = "INSERT INTO user (name, surname, sa_id_number, mobile_number, email_address, birth_date, language, interests) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        // Bind parameters and execute the query
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ssssssss", $this->name, $this->surname, $this->saIdNumber, $this->mobileNumber, $this->emailAddress, $formattedBirthDate, $this->language, $serializedInterests);

        $result = $stmt->execute();

        try {
            if ($result) {
                $userId = $this->db->insert_id;
                $this->setId($userId);

                $_SESSION['registration_success'] = 'Heey!!! ' . $this->name . ' you registered successfully.';
                $this->logger->info("{$this->getName()} Added successfully!!");

                return true; // User saved successfully
            } else {
                $_SESSION['registration_error'] = 'An error occurred while adding.';
                $this->logger->critical(var_export($_SESSION['registration_error'], true));

                return false; // User could not be saved
            }
        } catch (mysqli_sql_exception $e) {
            $_SESSION['registration_error'] = 'An error occurred while adding. This email address might be already in use.';
            $this->logger->error('An exception occurred', ['exception' => $e]);
        }
    }

    /**
     * Check if email or RSA ID exist
     *
     * @param string $email
     * @param string $saIdNumber
     * @return void
     */
    public function checkIfExists($email, $saIdNumber)
    {
        $sql = "SELECT COUNT(*) as count FROM user WHERE email_address = ? OR sa_id_number = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ss", $email, $saIdNumber);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return ($row['count'] > 0);
    }

    /**
     * Get All userss
     *
     * @return array
     */
    public static function getAllusers()
    {
        $db = Database::connect();

        // Perform a query to fetch users from the database
        $sql = "SELECT * FROM user";
        $result = $db->query($sql);

        $users = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $user = new Person();
                $user->setId($row['id']);
                $user->setName($row['name']);
                $user->setSurname($row['surname']);
                $user->setSaIdNumber($row['sa_id_number']);
                $user->setMobileNumber($row['mobile_number']);
                $user->setEmailAddress($row['email_address']);
                $user->setBirthDate($row['birth_date']);
                $user->setLanguage($row['language']);
                $user->setInterests($row['interests']);

                $users[] = $user;
            }
        }

        return $users;
    }

    /**
     * Get a user by ID
     *
     * @param string|int $userId
     * @return mixed
     */
    public function getUserById($userId)
    {
        // Prepare the SQL statement to retrieve user data by user_id
        $sql = "SELECT * FROM user WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);

        if ($stmt->execute()) {
            // Execute the query
            $result = $stmt->get_result();

            // Check if a user with the provided user_id exists
            if ($result->num_rows === 1) {
                // Fetch user data
                $userData = $result->fetch_assoc();
                return $userData;
            }
        }

        return null; // User not found or query failed
    }

    /**
     * Get user by ID
     *
     * @param mixed $id
     *
     */
    public static function getUserInfoById($id)
    {
        $db = Database::connect();
        // Prepare the SQL statement
        $sql = "SELECT * FROM user WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Fetch the result as an associative array
            $result = $stmt->get_result();
            if ($result->num_rows === 1) {
                $userData = $result->fetch_assoc();
                $user = new Person();
                $user->setId($userData['id']);
                $user->setName($userData['name']);
                $user->setSurname($userData['surname']);
                $user->setSaIdNumber($userData['sa_id_number']);
                $user->setMobileNumber($userData['mobile_number']);
                $user->setEmailAddress($userData['email_address']);
                $user->setBirthDate($userData['birth_date']);
                $user->setLanguage($userData['language']);
                $user->setInterests($userData['interests']);
                return $user;
            }
        }

        return null; // User not found
    }

    /**
     * Update User
     *
     * @return bool
     */
    public function update()
    {
        $serializedInterests = serialize($this->interests);

        // Prepare the SQL statement
        $sql = "UPDATE user
                SET name = ?, surname = ?,  mobile_number = ?, email_address = ?, language = ?, interests = ?
                WHERE id = ?";

        // Bind parameters and execute the query
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param(
            "ssssssi",
            $this->name,
            $this->surname,
            //$this->saIdNumber,
            $this->mobileNumber,
            $this->emailAddress,
            $this->language,
            $serializedInterests,
            $this->id
        );

        // Check for errors in the prepared statement
        if (!$stmt) {
            $_SESSION['user_update_error'] = "Error during preparation of the SQL statement: " . $this->db->error;
            return false;
        }

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $_SESSION['user_updated'] = "User: ({$this->name}) Updated successfully";
                return true; // User updated successfully
            } else {
                $_SESSION['user_update_error'] = "Error during execution of the SQL statement: " . $stmt->error;
                $_SESSION['user_update_error'] .= " SQL: " . $sql;
                $this->logger->error($_SESSION['user_update_error']);
                return false; // No rows were updated
            }
        } else {
            // Log the error information for debugging
            $_SESSION['user_update_error'] = "Error during execution of the SQL statement: " . $stmt->error;
            $_SESSION['user_update_error'] .= " SQL: " . $sql;
            $this->logger->error($_SESSION['user_update_error']);
            return false;
        }
    }

    /**
     * Delete A User
     *
     * @param [type] $id
     * @return boolean
     */
    public function delete($id)
    {
        $user = new Person();
        // Prepare the SQL statement
        $sql = "DELETE FROM user WHERE id = ?";

        // Bind parameters and execute the query
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            $this->logger->info('user (' . $user->getName() . ') is deleted successfully ');
            return true;
        } else {
            return false;
        }
    }
}
