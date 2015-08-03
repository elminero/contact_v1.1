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

        /*
        while ($row = mysqli_fetch_object($qResults) ) {

            $persons[] = [$row->id, $row->last_name, $row->first_name, $row->middle_name, $row->alias_name,
               $row->birth_month, $row->birth_day, $row->birth_year, $row->note,
                $row->state, $row->country_iso];

        }
        */

    //   mysqli_fetch_object();

        /*

    $sql = " SELECT * FROM subdivision WHERE iso = 'us' ";

    $qResults = $dbConnect->query($sql);

    while ($row = mysqli_fetch_object($qResults) ) {
        echo $row->subdivision . "<br />";
    }


        $stmt = $this->mysqli->prepare("
                    SELECT person.id, person.last_name, person.first_name, person.middle_name, person.alias_name,
                    person.birth_month, person.birth_day, person.birth_year, person.note,
                    address.state, address.country_iso
					FROM person LEFT OUTER JOIN address
					ON person.id = address.id
					ORDER BY person.last_name");

        $stmt->execute();

        $stmt->bind_result($id, $last, $first, $middle, $alias, $birthMonth, $birthDay, $birthYear, $note, $state, $iso);

        while($stmt->fetch()) {

            $persons[] = ['id'=>$id, 'last'=>$last, 'first'=>$first, 'middle'=>$middle, 'alias'=>$alias,
                'birthMonth'=>$birthMonth, 'birthDay'=>$birthDay, 'birthYear'=>$birthYear, 'note'=>$note,
                'state'=>$state, 'iso'=>$iso];
        }

        $stmt->free_result();
        $stmt->close();


        */





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

/*
$person = new Person();

$people = $person->getAllPerson();

while ($row = mysqli_fetch_object($people) ) {

    echo $row->id . " " . $row->last_name . " " . $row->first_name . " " . $row->middle_name . " " . $row->alias_name .
        " " . $row->birth_month . " " . $row->birth_day . " " . $row->birth_year . " " . $row->note . " " . $row->state . " " . $row->country_iso . "<br /> ";

}

*/



/*
$personj =  $person->getPersonById(1);


echo var_dump($personj);







$nameField = ['lastName'=>'Brown', 'firstName'=>'Mike',
    'middleName'=>'Joe', 'aliasName'=>'Pit Bull',
    'birthMonth'=>'11', 'birthDay'=>'27',
    'birthYear'=>'1964', 'note'=>'The quick brown fox'];

$name->add($nameField);





$name->delete(2);


$nameFieldUpdate = ['lastName'=>'Jackson', 'firstName'=>'Foomanchow',
    'middleName'=>'Peter', 'aliasName'=>'Poop',
    'birthMonth'=>'11', 'birthDay'=>'27',
    'birthYear'=>'1964', 'note'=>'jumped over the dog', 'id'=>1];



$name->update($nameFieldUpdate);


// $name->update($nameFieldUpdate);
// $names = $name->getAll();

foreach($name->getAll() as $contactName) {
    echo $contactName['first'] . "<br />";
}




$contactName = $name->getById(2);

echo $contactName['first'] . " " . $contactName['middle'] . " " . $contactName['last'] . " " .
    $contactName['alias'] . " " . $contactName['birthMonth'] . " " . $contactName['birthDay'] . " " .
    $contactName['birthYear'] . " " . $contactName['note'];








$nameFieldUpdate = ['lastName'=>'Farber', 'firstName'=>'Robert',
    'middleName'=>'Ian', 'aliasName'=>'Robby',
    'birthMonth'=>'11', 'birthDay'=>'27',
    'birthYear'=>'1964', 'note'=>'Hello', 'id'=>75];


$id = $name->add($nameField);

echo "<br />";

$name->update($nameFieldUpdate);


$name->getById(25);

$name->delete(76);


$name->getById(75);

echo $name->first . " " . $name->last;

$names = $name->getAll();
echo "<p>";
foreach($names as $name) {
    echo $name->first . " " . $name->last;
}







for ($i = 0; $i <= 75; $i++) {
    $name->delete($i);
}







$names = $name->getAll();
foreach($names as $name) {
    echo $name->first;
}

*/














































/*
public function getAll()
{
    $stmt = $this->mysqli->prepare("
                SELECT person.id, person.last_name, person.first_name, person.middle_name, person.alias_name,
                person.birth_month, person.birth_day, person.birth_year, person.note,
                address.state, address.iso
                FROM person LEFT OUTER JOIN address
                ON person.id = address.id
                ORDER BY person.last_name");

    $stmt->execute();

    $stmt->bind_result($_nameId, $_lastName, $_firstName, $_middleName, $_aliasName,
        $_birthMonth, $_birthDay, $_birthYear, $_note, $_state, $_iso);

    while($stmt->fetch()) {

        $this->_objNameDOB = new Person();
        $this->_objNameDOB->nameId = $_nameId;
        $this->_objNameDOB->last = $_lastName;
        $this->_objNameDOB->first = $_firstName;
        $this->_objNameDOB->middle = $_middleName;
        $this->_objNameDOB->alias = $_aliasName;
        $this->_objNameDOB->birthMonth = $_birthMonth;
        $this->_objNameDOB->birthDay = $_birthDay;
        $this->_objNameDOB->birthYear = $_birthYear;
        $this->_objNameDOB->note = $_note;
        $this->_objNameDOB->state = $_state;
        $this->_objNameDOB->countryISO = $_iso;

        $this->_arrayOfObjNameDOB[] = $this->_objNameDOB;
    }

    $stmt->close();

    return $this->_arrayOfObjNameDOB;
}

*/
