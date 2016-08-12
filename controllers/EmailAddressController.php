<?php
require_once(dirname(dirname(__FILE__)).'/models/EmailAddress.php');

//tDebug --  Db2 -- User -- Person -- Address -- PhoneNumber -- EmailAddress2 -- Image

// VARIABLES: $id, $personId, $emailAddress, $emailType, $note

class EmailAddressController
{
    private $_id, $_personId, $_emailAddress, $_emailType, $_note;

    function __construct($id, $personId, $emailAddress, $emailType, $note) {

        $this->_id = $id;
        $this->_personId = $personId;
        $this->_emailAddress = $emailAddress;
        $this->_emailType = $emailType;
        $this->_note = $note;
    }


    public function getPersonId(){return $this->_personId;}
    public function getId(){return $this->_id;}
    public function getType(){return $this->_emailType;}
    public function getEmailAddress(){return $this->_emailAddress;}
    public function getNote(){return $this->_note;}


    public function emailFieldValidate()
    {
        $emailFieldValidate = 0;

        if(
            preg_match("/\A[a-z0-9]+([-._][a-z0-9]+)*@([a-z0-9]+(-[a-z0-9]+)*\.)+[a-z]{2,4}\z/",  strtolower($this->_emailAddress)) &&
            preg_match('/^(?=.{1,64}@.{4,64}$)(?=.{6,100}$).*/', strtolower($this->_emailAddress))
          ) {
            $emailFieldValidate = 1;
        }

        return $emailFieldValidate;
    }

} // End PhoneNumberController


if(array_key_exists('addEmail', $_POST))
{

    $cEmail = new EmailAddressController((int)$_POST['emailId'], trim($_POST['personId']),
                                        trim($_POST['emailAddress']), (int)$_POST['type'] , trim($_POST['note']));

    if($cEmail->emailFieldValidate()) {

        $model = new EmailAddressPDO();

        if( isset($_GET['action']) && ($_GET['action']  === 'create') ) {

            $model->create($cEmail);
            header("Location: ../profile.php?id=".$_POST['personId']);
        }

        if( isset($_GET['action']) && ($_GET['action'] === 'update') ) {

            $model->updateById($cEmail);
            header("Location: ../profile.php?id=".$_POST['personId']);
        }

    } elseif($_GET['action'] === 'update') {
        header("Location: ../email.php?id={$_POST['personId']}&action=update&validate=error&update={$_POST['emailId']}");
    } else {
        header("Location: ../email.php?id={$_POST['personId']}&validate=error");
    }
}


if( isset($_GET['action']) && $_GET['action'] == 'delete' ) {
    $deleteId = (int)$_GET['id'];
    $model = new EmailAddressPDO();
    $model->deleteById($deleteId);
    header("Location: ../profile.php?id=".$_GET['personId']);
}