<?php

namespace Controller;

use Model\Person;
use Model\Task;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class UserDeleteController
{
    private $logger;

    /**
     * Delete user constructor
     *
     * @param Logger $logger
     */
    public function __construct( $logger = null)
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
