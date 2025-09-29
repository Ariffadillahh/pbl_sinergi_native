<?php

class BaseModel
{
    protected static $conn;

    protected static function getConnection()
    {
        if (self::$conn === null) {
            require_once __DIR__ . '/../../config/database.php';
            self::$conn = $conn;
        }
        return self::$conn;
    }
}
