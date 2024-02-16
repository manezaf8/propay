<?php 

namespace Core;

use mysqli;

class Database
{
    private static $connection;

    public static function connect()
    {
        if (!self::$connection) {
            $config = include __DIR__ . '/../config.php';

            $host = $config['host'];
            $username = $config['username'];
            $password = $config['password'];
            $database = $config['database'];

            self::$connection = new mysqli($host, $username, $password, $database);

            if (self::$connection->connect_error) {
                die("Connection failed: " . self::$connection->connect_error);
            }
        }

        return self::$connection;
    }

    public function prepare($sql)
    {
        return self::$connection->prepare($sql);
    }
}
