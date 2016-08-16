<?php


/*
class db
{
    protected $mysqli;
    protected $personId;
    private static $count=0;

    function __construct($personId = null)
    {
        self::$count++;

        $this->personId = $personId;

        if (!defined('DB_SERVER')) {
            define("DB_SERVER", "localhost");
        }

        if (!defined('DB_USER')) {
            define("DB_USER", "ian");
        }

        if (!defined('DB_PASS')) {
            define("DB_PASS", "super1964");
        }

        if (!defined('DB_NAME')) {
            define("DB_NAME", "contact");
        }

        if (self::$count <= 20) {
            $this->mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
            if (mysqli_connect_errno()) {
                die("Database connection failed. " . mysqli_connect_error() . ": " . mysqli_connect_errno());
            }
        } else {
            header("Location: controllers/LoginController.php?action=logout");
        }
    }
}
*/