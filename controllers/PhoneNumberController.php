<?php

// echo "You are here !";

require_once(dirname(dirname(__FILE__)).'/models/PhoneNumber.php');





//tDebug --  Db2 -- User -- Person -- Address -- PhoneNumber -- EmailAddress -- Image

//VARIABLES: $id, $personId, $phoneNumber, $phoneType, $note

class PhoneNumberController
{
    private  $_id, $_personId, $_phoneType, $_phoneNumber, $_note;

    function __construct($id, $personId, $phoneType, $phoneNumber, $note) {

        $this->_id = $id;
        $this->_personId = $personId;
        $this->_phoneType = $phoneType;
        $this->_phoneNumber = $phoneNumber;
        $this->_note = $note;
    }


    public function getPersonId(){return $this->_personId;}
    public function getId(){return $this->_id;}
    public function getType(){return $this->_phoneType;}
    public function getNumber(){return $this->_phoneNumber;}
    public function getNote(){return $this->_note;}


    public function phoneFieldValidate()
    {
        $phoneFieldValidate = 0;

        if($this->_phoneNumber) {
            $phoneFieldValidate = 1;
        }

        return $phoneFieldValidate;
    }

} // End PhoneNumberController


if(array_key_exists('addPhone', $_POST))
{


    $cPhone = new PhoneNumberController((int)$_POST['phoneId'], trim($_POST['personId']), (int)$_POST['type'],
                                        trim($_POST['phone']), trim($_POST['note']));

    if($cPhone->phoneFieldValidate()) {

        $model = new PhoneNumberPDO();

        if( isset($_GET['action']) && ($_GET['action']  === 'create') ) {




            $model->create($cPhone);
            header("Location: ../profile.php?id=".$_POST['personId']);
        }

        if( isset($_GET['action']) && ($_GET['action'] === 'update') ) {

            $model->updateById($cPhone);
            header("Location: ../profile.php?id=".$_POST['personId']);
        }

        } elseif($_GET['action'] === 'update') {
            header("Location: ../phonenumber.php?id={$_POST['personId']}&action=update&validate=error&update={$_POST['phoneId']}");
        } else {
            header("Location: ../phonenumber.php?id={$_POST['personId']}&validate=error");
    }

}


if( isset($_GET['action']) && $_GET['action'] == 'delete' ) {

    $deleteId = (int)$_GET['id'];
    $model = new PhoneNumberPDO();
    $model->deleteById($deleteId);
    header("Location: ../profile.php?id=".$_GET['personId']);
}

