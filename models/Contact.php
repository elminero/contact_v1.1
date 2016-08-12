<?php
// Db2 -- Person -- Address -- PhoneNumber -- EmailAddress -- Image -- (Contact)

require_once(dirname(dirname(__FILE__)).'/models/Person.php');
require_once(dirname(dirname(__FILE__)).'/models/Address.php');
require_once(dirname(dirname(__FILE__)).'/models/PhoneNumber.php');
require_once(dirname(dirname(__FILE__)).'/models/EmailAddress.php');
require_once(dirname(dirname(__FILE__)).'/models/Image.php');

class Contact extends PersonPDO
{

    public $nameDOB, $address, $phoneNumber, $emailAddress, $avatar, $image;


    public function getContactById()
    {
        $person = new PersonPDO();
        $this->nameDOB = $person->readById($this->personId);

        $address = new AddressPDO();
        $this->address = $address->readAllByPersonId($this->personId);

        $phoneNumber = new PhoneNumberPDO();
        $this->phoneNumber = $phoneNumber->readAllByPersonId($this->personId);

        $emailAddress = new EmailAddressPDO();
        $this->emailAddress = $emailAddress->readAllByPersonId($this->personId);

        $image = new ImagePDO();
        $this->avatar = $image->getAvatarImageByPersonId($this->personId);
        $this->image = $image->readAllByPersonId($this->personId);
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


if(isset($_GET['id']) )  {
    $id = (int)$_GET['id'];
}
elseif(!isset($_GET['id'])) {
    header("Location: listcontacts.php");
}


if( (isset($_GET['validate']))  &&  ($_GET['validate'] === 'error') ) {
    $error = 1;
} else {
    $error = null;
}