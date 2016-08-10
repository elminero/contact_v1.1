<?php
//tDebug --  Db2 -- User -- Person -- Address -- (PhoneNumber) -- EmailAddress -- Image
// require("Address.php");
require_once(dirname(dirname(__FILE__)).'/models/Db.php');

/*
        TABLE:   phone_number
        COLUMNS: id, person_id, phone_number, phone_type, note
        VARIABLES: $id, $personId, $phoneNumber, $phoneType, $note

        id              integer
        person_id       integer
        phone_number    string
        phone_type      integer
        note            text
*/

class PhoneNumberPDO extends Db3  {

    private $_id, $_personId, $_phoneNumber, $_phoneType, $_note;


    public function create($data){}
    public function readAll(){}
    public function readById($id){}
    public function updateById($data){}
    public function deleteById($id){}


    public function setPhoneParam (PhoneNumberController $phone) {

        $this->_id = $phone->getId();
        $this->_personId = $phone->getPersonId();
        $this->_phoneNumber = $phone->getNumber();
        $this->_phoneType = $phone->getType();
        $this->_note = $phone->getNote();
    }


    public function addPhoneNumber(PhoneNumberController $phone)  // class PhoneNumber
    {
        self::setPhoneParam($phone);

        $stmt = $this->pdo->prepare("
					INSERT INTO phone_number(
                    person_id, phone_number, phone_type, note )
					VALUES(
					?,?,?,?)");

        $stmt->execute([$this->_personId, $this->_phoneNumber, $this->_phoneType, $this->_note]);

        return $this->pdo->lastInsertId();
    }


    public function updatePhoneNumber($phone)  // class PhoneNumber
    {
        self::setPhoneParam($phone);

        $stmt = $this->pdo->prepare("
                                UPDATE phone_number
                                SET
                                phone_number = ?,
                                phone_type = ?,
                                note = ?
                                WHERE id = ? ");

        $stmt->execute([$this->_phoneNumber, $this->_phoneType, $this->_note, $this->_id]);

        return $this->_id;
    }


    public function getPhoneNumberById($id)  // class PhoneNumber
    {
        $stmt = $this->pdo->prepare("
					SELECT id, person_id, phone_number, phone_type, note
					FROM phone_number
					WHERE id = ?");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    public function getAllPhoneNumberByPersonId($personId)
    {
        $stmt = $this->pdo->prepare("
					SELECT id, phone_number, phone_type, note
					FROM phone_number
					WHERE person_id = ?");

        $stmt->execute([$personId]);

        return $stmt;
    }


    public function deletePhoneNumber($id)  // class PhoneNumber
    {
        $stmt = $this->pdo->prepare("
                    DELETE FROM phone_number
                    WHERE id = ? ");

        $stmt->execute([$id]);
    }

}
