<?php
require_once(dirname(dirname(__FILE__)).'/models/Address.php');

//tDebug --  Db2 -- User -- Person -- Address -- PhoneNumber -- EmailAddress -- Image

// VARIABLES: $id, $personId, $addressType, $countryIso, $state, $street, $city, $postalCode, $note

class AddressController
{
    private $_id, $_personId, $_addressType, $_countryIso, $_state, $_street, $_city, $_postalCode, $_note;

    function __construct($id, $personId, $addressType, $countryIso, $state, $street, $city, $postalCode, $note) {

        $this->_id = $id;
        $this->_personId = $personId;
        $this->_addressType = $addressType;
        $this->_countryIso = $countryIso;
        $this->_state = $state;
        $this->_street = $street;
        $this->_city = $city;
        $this->_postalCode = $postalCode;
        $this->_note = $note;
    }


    public function getId(){return $this->_id;}
    public function getPersonId(){return $this->_personId;}
    public function getAddressType(){return $this->_addressType;}
    public function getCountryIso(){return $this->_countryIso;}
    public function getState(){return $this->_state;}
    public function getStreet(){return $this->_street;}
    public function getCity(){return $this->_city;}
    public function getPostalCode(){return $this->_postalCode;}
    public function getNote(){return $this->_note;}


    public function addressFieldValidate()
    {
        $addressFieldValidate = 0;

        if($this->_countryIso) {
            $addressFieldValidate = 1;
        }

        return $addressFieldValidate;
    }

} // End AddressController


if(array_key_exists('addAddress', $_POST))
{
   $cAddress = new AddressController( (int)$_POST['id'], (int)$_POST['personId'], (int)$_POST['address_type'],
                                      $_POST['country_iso'], $_POST['state'], $_POST['street'], $_POST['city'],
                                      $_POST['postal_code'], $_POST['note'] );

    if($cAddress->addressFieldValidate()) {

        $model = new AddressPDO();

        if( isset($_GET['action']) && ($_GET['action']  === 'create') ) {

            $model->create($cAddress);
            header("Location: ../profile.php?id=".$_POST['personId']);
        }

        if( isset($_GET['action']) && ($_GET['action'] === 'update') ) {

            $model->updateById($cAddress);
            header("Location: ../profile.php?id=".$_POST['personId']);
        }

    } elseif($_GET['action'] === 'update') {
        header("Location: ../email.php?id={$_POST['personId']}&action=update&validate=error&update={$_POST['id']}");
    } else {
        header("Location: ../email.php?id={$_POST['personId']}&validate=error");
    }

}


if( isset($_GET['action']) && $_GET['action'] == 'delete' ) {
    $deleteId = (int)$_GET['id'];

    $cAddress = new AddressPDO();
    $cAddress->deleteById($deleteId);
    header("Location: ../profile.php?id=".$_GET['personId']);
}


/*
    echo "getId ". $cAddress->getId();
    echo "<br />";
    echo "getPersonId ". $cAddress->getPersonId();
    echo "<br />";
    echo "getAddressType ". $cAddress->getAddressType();
    echo "<br />";
    echo "getCountryIso ". $cAddress->getCountryIso();
    echo "<br />";
    echo "getState ". $cAddress->getState();
    echo "<br />";
    echo "getStreet ". $cAddress->getStreet();
    echo "<br />";
    echo "getCity ". $cAddress->getCity();
    echo "<br />";
    echo "getPostalCode ". $cAddress->getPostalCode();
    echo "<br />";
    echo "getNote ". $cAddress->getNote();
    echo "<br />";
*/