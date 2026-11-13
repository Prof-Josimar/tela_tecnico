<?php
namespace App\Config;

class Database {
    private static $connection = null;

    public static function getConnection() {
        if (self::$connection === null) {
            self::$connection = new \PDO(
                "mysql:host=localhost;dbname=nexa;charset=utf8mb4",
                "root",
                ""
            );

            self::$connection->setAttribute(
                \PDO::ATTR_ERRMODE,
                \PDO::ERRMODE_EXCEPTION
            );
        }

        return self::$connection;
    }
}
