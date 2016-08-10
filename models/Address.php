<?php
//tDebug --  Db2 -- User -- Person -- (Address) -- PhoneNumber -- EmailAddress -- Image
require_once(dirname(dirname(__FILE__)).'/models/Db.php');

/*
        TABLE:   address
        COLUMNS: id, person_id, address_type, country_iso, state, street, city, postal_code, note
        VARIABLES: $id, $personId, $addressType, $countryIso, $state, $street, $city, $postalCode, $note

        id              integer,
        person_id       integer
        address_type    integer
        country_iso     string
        state           string
        street          string
        city            string
        postal_code     string
        note            string
*/


class AddressPDO extends Db3 {

    private $_id, $_personId, $_addressType, $_countryIso, $_state, $_street, $_city, $_postalCode, $_note;


    public function create($data){}
    public function readAll(){}
    public function readById($id){}
    public function updateById($data){}
    public function deleteById($id){}


    public function setAddressParam (AddressController $address) {

        $this->_id = $address->getId();
        $this->_personId = $address->getPersonId();
        $this->_addressType = $address->getAddressType();
        $this->_countryIso = $address->getCountryIso();
        $this->_state = $address->getState();
        $this->_street = $address->getStreet();
        $this->_city = $address->getCity();
        $this->_postalCode = $address->getPostalCode();
        $this->_note = $address->getNote();
    }


    public function addAddress($address)  // class Address
    {
        self::setAddressParam($address);

        $stmt = $this->pdo->prepare("
					INSERT INTO address(
                    person_id, address_type, country_iso, state, street, city, postal_code, note )
					VALUES(
					?,?,?,?,?,?,?,?)");

        $stmt->execute([$this->_personId, $this->_addressType, $this->_countryIso,
            $this->_state, $this->_street, $this->_city, $this->_postalCode,
            $this->_note]);
    }


    public function updateAddress($address)  // class Address
    {
        self::setAddressParam($address);

        $stmt = $this->pdo->prepare("
                    UPDATE address
                    SET
                    address_type = ?,
                    country_iso = ?,
                    state = ?,
                    street = ?,
                    city = ?,
                    postal_code = ?,
                    note = ?
                    WHERE id = ? ");

        $stmt->execute([$this->_addressType, $this->_countryIso, $this->_state,
            $this->_street, $this->_city, $this->_postalCode, $this->_note, $this->_id]);
    }


    public function getAddressById($id)  // class Address
    {
        $stmt = $this->pdo->prepare("
					SELECT person_id, address_type, country_iso, state, street, city, postal_code, note
					FROM address
					WHERE id = ?");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    public function deleteAddress($id)  // class Address
    {
        $stmt = $this->pdo->prepare("
                    DELETE FROM address
                    WHERE id = ? ");

        $stmt->execute([$id]);
    }


    public function getAllCountry()
    {
        $stmt = $this->pdo->prepare("
                    SELECT country, iso
                    FROM country");

        $stmt->execute();

        return $stmt;
    }


    public function getCountryByISO($iso)
    {
        $stmt = $this->pdo->prepare("
                    SELECT country
                    FROM country
                    WHERE iso = ?");

        $stmt->execute([$iso]);

        return $stmt->fetch(PDO::FETCH_OBJ)->country;
    }


    public function getStatesByCountry($iso)
    {
        $stmt = $this->pdo->prepare("
                    SELECT subdivision, iso
                    FROM subdivision
                    WHERE iso = ?");

        $stmt->execute([$iso]);

        return $stmt;
    }


    function getStateAbbr($USState)
    {
        switch($USState) {

            case "Alabama":
                $stateAbbr = "AL";
                break;
            case "Alaska":
                $stateAbbr = "AK";
                break;
            case "Arizona":
                $stateAbbr = "AZ";
                break;
            case "Arkansas":
                $stateAbbr = "AR";
                break;
            case "California":
                $stateAbbr = "CA";
                break;
            case "Colorado":
                $stateAbbr = "CO";
                break;
            case "Connecticut":
                $stateAbbr = "CT";
                break;
            case "Delaware":
                $stateAbbr = "DE";
                break;
            case "District Of Columbia":
                $stateAbbr = "DC";
                break;
            case "Florida":
                $stateAbbr = "FL";
                break;
            case "Georgia":
                $stateAbbr = "GA";
                break;
            case "Hawaii":
                $stateAbbr = "HI";
                break;
            case "Idaho":
                $stateAbbr = "IA";
                break;
            case "Illinois":
                $stateAbbr = "IL";
                break;
            case "Indiana":
                $stateAbbr = "IN";
                break;
            case "Iowa":
                $stateAbbr = "IA";
                break;
            case "Kansas":
                $stateAbbr = "KS";
                break;
            case "Kentucky":
                $stateAbbr = "KY";
                break;
            case "Louisiana":
                $stateAbbr = "LA";
                break;
            case "Maine":
                $stateAbbr = "MA";
                break;
            case "Maryland":
                $stateAbbr = "MD";
                break;
            case "Massachusetts":
                $stateAbbr = "MA";
                break;
            case "Michigan":
                $stateAbbr = "MI";
                break;
            case "Minnesota":
                $stateAbbr = "MN";
                break;
            case "Mississippi":
                $stateAbbr = "MS";
                break;
            case "Missouri":
                $stateAbbr = "MO";
                break;
            case "Montana":
                $stateAbbr = "MT";
                break;
            case "Nebraska":
                $stateAbbr = "NE";
                break;
            case "Nevada":
                $stateAbbr = "NV";
                break;
            case "New Hampshire":
                $stateAbbr = "NH";
                break;
            case "New Jersey":
                $stateAbbr = "NJ";
                break;
            case "New Mexico":
                $stateAbbr = "NM";
                break;
            case "New York":
                $stateAbbr = "NY";
                break;
            case "North Carolina":
                $stateAbbr = "NC";
                break;
            case "North Dakota":
                $stateAbbr = "ND";
                break;
            case "Ohio":
                $stateAbbr = "OH";
                break;
            case "Oklahoma":
                $stateAbbr = "OK";
                break;
            case "Oregon":
                $stateAbbr = "OR";
                break;
            case "Pennsylvania":
                $stateAbbr = "PA";
                break;
            case "Rhode Island":
                $stateAbbr = "RI";
                break;
            case "South Carolina":
                $stateAbbr = "SC";
                break;
            case "South Dakota":
                $stateAbbr = "SD";
                break;
            case "Tennessee":
                $stateAbbr = "TN";
                break;
            case "Texas":
                $stateAbbr = "TX";
                break;
            case "Utah":
                $stateAbbr = "UT";
                break;
            case "Vermont":
                $stateAbbr = "VT";
                break;
            case "Virginia":
                $stateAbbr = "VA";
                break;
            case "Washington":
                $stateAbbr = "WA";
                break;
            case "West Virginia":
                $stateAbbr = "WV";
                break;
            case "Wisconsin":
                $stateAbbr = "WI";
                break;
            case "Wyoming":
                $stateAbbr = "WY";
                break;
            default:
                $stateAbbr = "notOnList";
        }

        return $stateAbbr;

    }


    public function getAllCityByState($stateAbbr)
    {
        $stmt = $this->pdo->prepare("
                        SELECT DISTINCT city
                        FROM us_zip_code
                        WHERE state = ?
                        AND zip_code_type  = 'STANDARD' ORDER BY city;");

        $stmt->execute([$stateAbbr]);

        return $stmt;
    }


    public function getAllAddressByPersonId($personId)  // class Address
    {
        $stmt = $this->pdo->prepare("
					SELECT id, address_type, country_iso, state, street, city, postal_code, note
					FROM address
					WHERE person_id = ?");

        $stmt->execute([$personId]);

        return $stmt;
    }
}

