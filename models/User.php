<?php
//tDebug --  Db2 -- (User) -- Person -- Address -- PhoneNumber -- EmailAddress -- Image

require_once(dirname(dirname(__FILE__)).'/models/Db.php');


/*
        TABLE:   user
        COLUMNS: id, username, pass_hash, role, note, date_last_login, ip_last_login
        VARIABLES: $id, $username, $passHash, $role, $note, $dateLastLogin, $ipLastLogin


        id              integer
        username        string
        timezone        integer
        pass_hash       string
        roles           string
        note            string
        date_last_login TIMESTAMP
        ip_last_login   string

*/


class UserPDO extends \dbPdo\Db  {

    public function create($data){}
    public function readAll(){}
    public function readAllByPersonId($id){}
    public function readById($id){}
    public function updateById($data){}
    public function deleteById($id){}

    public function addUser($user)  // class Person
    {
        $stmt = $this->pdo->prepare("
				INSERT INTO user
				  (username, timezone, pass_hash, role, note, date_last_login, ip_last_login)
				VALUES
				  (?, ?, ?, ?, ?, ?, ?)");

        $stmt->execute([$user['username'], $user['timezone'], $user['passHash'], $user['role'], $user['note'], $user['dateLastLogin'], $user['ipLastLogin']]);

        return $this->pdo->lastInsertId();
    }

    public function getUserByName($userName)
    {
        $stmt = $this->pdo->prepare("
                SELECT id, username, pass_hash, role, note, date_last_login, ip_last_login
                FROM user
                WHERE username = ?");

        $stmt->execute([$userName]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    public function getUserById($id)
    {
        $stmt = $this->pdo->prepare("
                SELECT id, username, pass_hash, role, note, date_last_login, ip_last_login
                FROM user
                WHERE pass_hash = ?");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    private function getTimeZoneByInt($timeZoneInt)
    {

        switch($timeZoneInt) {
            case 0:
                $timeZone = "trouble";
                break;

            case 1:
                $timeZone = "America/New_York";
                break;
            case 2:
                $timeZone = "America/Chicago";
                break;
            case 3:
                $timeZone = "America/Denver";
                break;
            case 4:
                $timeZone = "America/Phoenix";
                break;
            case 5:
                $timeZone = "America/Los_Angeles";
                break;
            case 6:
                $timeZone = "America/Anchorage";
                break;
            case 7:
                $timeZone = "America/Adak";
                break;
            case 8:
                $timeZone = "Pacific/Honolulu";
                break;
            case 9:
                $timeZone = "America/Los_Angeles";
                break;
            default:
                $timeZone = "America/Los_Angeles";
                break;
        }
        return $timeZone;
    }


    public function getTimeZoneByUserId($id)
    {
        $stmt = $this->pdo->prepare("
               SELECT timezone
               FROM user
               WHERE id = ?");

        $stmt->execute([$id]);

        return self::getTimeZoneByInt($stmt->fetch(PDO::FETCH_OBJ)->timezone);
    }

}





/*


Eastern ........... America/New_York
Central ........... America/Chicago
Mountain .......... America/Denver
Mountain no DST ... America/Phoenix
Pacific ........... America/Los_Angeles
Alaska ............ America/Anchorage
Hawaii ............ America/Adak
Hawaii no DST ..... Pacific/Honolulu
*/



/*
$userModel = new UserPDO();

$password = "super8";


$options = [
    'cost' => 11,
    'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
];
$passHash = password_hash($password, PASSWORD_BCRYPT, $options);



$date = new DateTime();
$dateLastLogin = $date->format('Y-m-d H:i:s');

echo $dateLastLogin;

$userField =
['username'=>'elminero', 'timezone'=>9, 'passHash'=>$passHash, 'role'=>'1', 'note'=>'The sly fox', 'dateLastLogin'=>$dateLastLogin,
    'ipLastLogin'=>$_SERVER['REMOTE_ADDR']];


$userModel->addUser($userField);



$user =  $userModel->getUserByName('Robert');



echo var_dump($user);

*/