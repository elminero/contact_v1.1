<?php
//tDebug --  Db2 -- User -- (Person) -- Address -- PhoneNumber -- EmailAddress -- Image

 require_once(dirname(dirname(__FILE__)).'/models/Db.php');

/*
        TABLE:   person
        COLUMNS: id, last_name, first_name, middle_name, alias_name, birth_month, birth_day, birth_year, note
        VARIABLES: $id, $lastName, $firstName, $middleName, $aliasName, $birthMonth, $birthDay, $birthYear, $note

        id              integer
        last_name       string
        first_name      string
        middle_name     string
        alias_name      string
        birth_month     integer
        birth_day       integer
        birth_year      integer
        note            string
*/


class PersonPDO extends \dbPdo\Db
{

    private  $_id, $_lastName, $_firstName, $_middleName, $_aliasName, $_birthMonth, $_birthDay, $_birthYear, $_note;

//    public function create($data){}
//    public function readAll(){}
//    public function readAllByPersonId($id){}
//    public function readById($id){}
//    public function updateById($data){}
//    public function deleteById($id){}


    private function setPersonParam(PersonController $cPerson)
    {
        $this->_id = $cPerson->getId();
        $this->_lastName = $cPerson->getLastName();
        $this->_firstName = $cPerson->getFirstName();
        $this->_middleName = $cPerson->getMiddleName();
        $this->_aliasName = $cPerson->getAliasName();
        $this->_birthMonth = $cPerson->getBirthMonth();
        $this->_birthDay = $cPerson->getBirthDay();
        $this->_birthYear = $cPerson->getBirthYear();
        $this->_note = $cPerson->getNote();
    }


    public function create($person)
    {
        self::setPersonParam($person);

        $insertId = null;

        $stmt = $this->pdo->prepare("
				INSERT INTO person
				  (last_name, first_name, middle_name, alias_name, birth_month, birth_day, birth_year, note)
				VALUES
				  (:last_name, :first_name, :middle_name, :alias_name, :birth_month, :birth_day, :birth_year, :note)");

        $stmt->execute(array(':last_name'=>$this->_lastName, ':first_name'=>$this->_firstName, ':middle_name'=>$this->_middleName, ':alias_name'=>$this->_aliasName, ':birth_month'=>$this->_birthMonth, ':birth_day'=>$this->_birthDay, ':birth_year'=>$this->_birthYear, ':note'=>$this->_note));

        return $this->pdo->lastInsertId();

    }


    public function readAll()
    {
        $sql = "
            SELECT person.id, person.last_name, person.first_name, person.middle_name, person.alias_name,
            person.birth_month, person.birth_day, person.birth_year, person.note,
            address.state, address.country_iso
            FROM person LEFT OUTER JOIN address
            ON person.id = address.id
            ORDER BY person.last_name";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt;
    }


    public function readById($id)
    {
        $stmt =  $this->pdo->prepare("
            SELECT id, last_name, first_name, middle_name, alias_name, birth_month, birth_day, birth_year, note
            FROM person
            WHERE id = ?");

        $stmt->execute(array($id));

        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    public function updateById($person)
    {
        self::setPersonParam($person);

        $stmt = $this->pdo->prepare("
                                UPDATE person
                                SET
                                last_name = ?,
                                first_name = ?,
                                middle_name = ?,
                                alias_name = ?,
                                birth_month = ?,
                                birth_day = ?,
                                birth_year = ?,
                                note = ?
                                WHERE id = ? ");

        $stmt->execute([$this->_lastName,
            $this->_firstName,
            $this->_middleName,
            $this->_aliasName,
            $this->_birthMonth,
            $this->_birthDay,
            $this->_birthYear,
            $this->_note,
            $this->_id]);

        $affected_rows = $stmt->rowCount();
    }


    public function deleteById($id)
    {
        $stmt = $this->pdo->prepare("
                                DELETE FROM person
                                WHERE id = ? ");

        $stmt->execute([$id]);
    }


    public function getMonthNameByNumber($monthNumber)
    {
        switch($monthNumber)
        {
            case 0:
                $month = " ";
                break;
            case 1:
                $month = "January";
                break;
            case 2:
                $month = "February";
                break;
            case 3:
                $month = "March";
                break;
            case 4:
                $month = "April";
                break;
            case 5:
                $month = "May";
                break;
            case 6:
                $month = "June";
                break;
            case 7:
                $month = "July";
                break;
            case 8:
                $month = "August";
                break;
            case 9:
                $month = "September";
                break;
            case 10:
                $month = "October";
                break;
            case 11:
                $month = "November";
                break;
            case 12:
                $month = "December";
                break;
            default:
                $month = null;
        }
        return $month;
    }


    public function getAge($birth_year, $birth_month, $birth_day)
    {
        $birthday = $birth_year . "-" . $birth_month . "-" . $birth_day;
        $age = date_create($birthday)->diff(date_create('today'))->y;
        return $age;
    }


}



