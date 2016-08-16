<?php
namespace dbPdo;
use PDO;
//tDebug --  (Db2) -- User -- Person -- Address -- PhoneNumber -- EmailAddress -- Image

require("CRUDInterface.php");
require("tDebug.php");

abstract class Db implements \dbPdo\crud
{
    use \dbPdo\tDebug;

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