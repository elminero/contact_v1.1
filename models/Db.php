<?php
//tDebug --  (Db2) -- User -- Person -- Address -- PhoneNumber -- EmailAddress -- Image

// require_once(dirname(dirname(__FILE__)).'/models/tDebug.php');


require("tDebug.php");

/*

abstract class Db2
{

// use tDebug;

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

interface crud
{
    public function create($data);
    public function readAll();
 //   public function readAllByPersonId($id);
    public function readById($id);
    public function updateById($data);
    public function deleteById($id);
}


abstract class Db3 implements crud
{
    use tDebug;

    protected $pdo;

    abstract public function create($data);
    abstract public function readAll();
    abstract public function readById($id);
    abstract public function updateById($data);
    abstract public function deleteById($id);

    function __construct($personId = null) {
        $this->personId = $personId;

        /*
         * Database localhost
         */

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


        /*
         * Database production server
         */

        /*
        if (!defined('DB_SERVER')) {
            define("DB_SERVER", "");
        }

        if (!defined('DB_USER')) {
            define("DB_USER", "");
        }

        if (!defined('DB_PASS')) {
            define("DB_PASS", "");
        }

        if (!defined('DB_NAME')) {
            define("DB_NAME", "contact");
        }
        */


        // $this->pdo = new PDO("mysql:host=localhost; dbname=contact; charset=utf8", "ian", "super1964");

        $this->pdo = new PDO("mysql:host=" . DB_SERVER . "; dbname=" . DB_NAME . "; charset=utf8", DB_USER, DB_PASS);
    }

    function __destruct ()
    {
        unset($this->pdo);
    }
}



