<?php
 require_once(dirname(dirname(__FILE__)).'/models/Person.php');

//tDebug --  Db2 -- User -- Person -- Address -- PhoneNumber -- EmailAddress -- Image

// VARIABLES: $id, $lastName, $firstName, $middleName, $aliasName, $birthMonth, $birthDay, $birthYear, $note


// echo var_dump($_POST);


class PersonController
{
    private $_id, $_lastName, $_firstName, $_middleName, $_aliasName, $_birthMonth, $_birthDay, $_birthYear, $_note;
    public $validate;

    function __construct($id, $lastName, $firstName, $middleName, $aliasName, $birthMonth, $birthDay, $birthYear, $note) {

        $this->_id = $id;
        $this->_lastName = $lastName;
        $this->_firstName = $firstName;
        $this->_middleName = $middleName;
        $this->_aliasName = $aliasName;
        $this->_birthMonth = $birthMonth;
        $this->_birthDay = $birthDay;
        $this->_birthYear = $birthYear;
        $this->_note = $note;
    }


    public function getId(){return $this->_id;}
    public function getLastName(){return $this->_lastName;}
    public function getFirstName(){return $this->_firstName;}
    public function getMiddleName(){return $this->_middleName;}
    public function getAliasName(){return $this->_aliasName;}
    public function getBirthMonth(){return $this->_birthMonth;}
    public function getBirthDay(){return $this->_birthDay;}
    public function getBirthYear(){return $this->_birthYear;}
    public function getNote(){return $this->_note;}


    /*
     * A person must have at least one name or alias
     */
    public function nameFieldValidate()
    {
        $this->validate = 1;

        if( ($this->_lastName == null) && ($this->_firstName == null) &&
            ($this->_middleName == null) && ($this->_aliasName == null) ) {
            $this->validate = 0;
        }

        return $this->validate;
    }


    public function getAge($birth_year, $birth_month, $birth_day)
    {
        $birthday = $birth_year . "-" . $birth_month . "-" . $birth_day;
        $age = date_create($birthday)->diff(date_create('today'))->y;
        return $age;
    }


} // End class PersonController


if(array_key_exists('addNewContact', $_POST))
{

    $cPerson = new PersonController( (int)$_POST['personId'], trim($_POST['lastName']), trim($_POST['firstName']), trim($_POST['middleName']),
        trim($_POST['aliasName']), (int)$_POST['birthMonth'], (int)$_POST['birthDay'], (int)$_POST['birthYear'], trim($_POST['note'])    );

    $model = new PersonPDO();

if($cPerson->nameFieldValidate()) {

    if( isset($_GET['action']) && ($_GET['action']  === 'create') ) {
        $insertId = $model->create($cPerson);
        header("Location: ../profile.php?id=".(int)$insertId);
    }

    if( isset($_GET['action']) && ($_GET['action'] === 'update') ) {

      $model->updateById($cPerson);

       header("Location: ../profile.php?id=".$_POST['personId']);
    }

    } elseif($_GET['action'] === 'update') {
        header("Location: ../newcontact.php?id={$_POST['personId']}&action=update&validate=error");
    } else {
        header("Location: ../newcontact.php?validate=error");
    }

}

/*
 * Delete a contact and return to the list
 */
if( isset($_GET['action']) && $_GET['action'] == 'delete' ) {
    $deleteId = (int)$_GET['id'];
    $cPerson = new PersonPDO();
    $cPerson->deleteById($deleteId);
    header("Location: ../listcontacts.php");
}


$months = [" ", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October",
    "November", "December"];