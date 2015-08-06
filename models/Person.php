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


class Person extends Db2 {

    private  $_id, $_lastName, $_firstName, $_middleName, $_aliasName, $_birthMonth, $_birthDay, $_birthYear, $_note;

    private function setPersonParam(PersonController $person)
    {
        $this->_id = $person->getId();
        $this->_lastName = $person->getLastName();
        $this->_firstName = $person->getFirstName();
        $this->_middleName = $person->getMiddleName();
        $this->_aliasName = $person->getAliasName();
        $this->_birthMonth = $person->getBirthMonth();
        $this->_birthDay = $person->getBirthDay();
        $this->_birthYear = $person->getBirthYear();
        $this->_note = $person->getNote();
    }


    public function addPerson(PersonController $person)  // class Person
    {

        self::setPersonParam($person);

        $insertId = null;

        $stmt = $this->mysqli->prepare("
				INSERT INTO person
				  (last_name, first_name, middle_name, alias_name, birth_month, birth_day, birth_year, note)
				VALUES
				  (?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("ssssiiis", $this->_lastName, $this->_firstName, $this->_middleName, $this->_aliasName,
            $this->_birthMonth, $this->_birthDay, $this->_birthYear, $this->_note);
        $stmt->execute();
        $stmt->close();

        $insertId = $this->mysqli->insert_id;

        return $insertId;

    }


    public function updatePerson($person)  // class Person
    {

        self::setPersonParam($person);

        $stmt = $this->mysqli->prepare("
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

        $stmt->bind_param("ssssiiisi", $this->_lastName, $this->_firstName, $this->_middleName, $this->_aliasName,
            $this->_birthMonth, $this->_birthDay, $this->_birthYear, $this->_note, $this->_id);
        $stmt->execute();

        $affectedRow = $this->mysqli->affected_rows;

        $stmt->close();

        return $affectedRow;
    }



    public function getAllPerson()
    {
        $persons = null;


 $sql = "
                    SELECT person.id, person.last_name, person.first_name, person.middle_name, person.alias_name,
                    person.birth_month, person.birth_day, person.birth_year, person.note,
                    address.state, address.country_iso
					FROM person LEFT OUTER JOIN address
					ON person.id = address.id
					ORDER BY person.last_name";

        $qResults = $this->mysqli->query($sql);

        return $qResults;
    }


    public function getPersonById($id)  // class Person
    {
        $stmt = $this->mysqli->prepare("
					SELECT id, last_name, first_name, middle_name, alias_name, birth_month, birth_day, birth_year, note
					FROM person
					WHERE id = ?");

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->bind_result($id, $last, $first, $middle, $alias, $birthMonth, $birthDay, $birthYear, $note);
        $stmt->fetch();

        $person = ['id'=>$id, 'last'=>$last, 'first'=>$first, 'middle'=>$middle, 'alias'=>$alias,
                   'birthMonth'=>$birthMonth, 'birthDay'=>$birthDay, 'birthYear'=>$birthYear, 'note'=>$note];
        $stmt->close();

        return $person;
    }



    public function deletePerson($id)
    {
        $stmt = $this->mysqli->prepare("
                                DELETE FROM person
                                WHERE id = ? ");

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }


    public function getAge($birth_year, $birth_month, $birth_day)
    {
        $birthday = $birth_year . "-" . $birth_month . "-" . $birth_day;
        $age = date_create($birthday)->diff(date_create('today'))->y;
        return $age;
    }



    function getMonthNameByNumber($monthNumber)
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

}

class PersonPDO extends db3
{
<<<<<<< HEAD

    public function getPersonById($id) {

        $stmt =  $this->pdo->prepare("
            SELECT id, last_name, first_name, middle_name, alias_name, birth_month, birth_day, birth_year, note
            FROM person
            WHERE id = ?");

        $stmt->execute(array($id));

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

}








































=======
>>>>>>> origin/pdo2

    public function getPersonById($id) {

        $stmt =  $this->pdo->prepare("
            SELECT id, last_name, first_name, middle_name, alias_name, birth_month, birth_day, birth_year, note
            FROM person
            WHERE id = ?");

        $stmt->execute(array($id));

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

}
