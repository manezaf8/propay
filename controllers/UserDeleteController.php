<?php

/**
 * @package   Delete Controller
 * @author    Ntabethemba Ntshoza
 * @date      16-02-2024
 * @copyright Copyright © 2024 VMP By Maneza
 */

namespace Controller;

use Model\Person;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

class UserDeleteController
{
    private $logger;

    /**
     * Delete user constructor
     *
     * @param Logger $logger
     */
    public function __construct($logger = null)
    {
        $this->logger = new Logger('delete-user-controller');
        $this->logger->pushHandler(new StreamHandler('var/System.log', Logger::DEBUG));
    }

    /**
     * Delete user
     *
     * @return void
     */
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
            $userId = $_GET['id'];

            $user = new Person();

            if ($user->delete($userId)) {

                $this->logger->info('User deleted succeefully');
                return redirect('/?delete_success=1');
            } else {
                echo "Failed to delete the task.";
            }
        } else {
            echo "Invalid request.";
        }
    }
}
