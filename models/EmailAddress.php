<?php
//tDebug --  Db2 -- User -- Person -- Address -- PhoneNumber -- (EmailAddress) -- Image
// require("PhoneNumber.php");
require_once(dirname(dirname(__FILE__)).'/models/Db.php');

/*
        TABLE:   email_address
        COLUMNS: id, person_id, email_address, email_type, note
        VARIABLES: $id, $personId, $emailAddress, $emailType, $note

        id              integer
        person_id       integer
        email_address   string
        email_type      integer
        note            string
*/

class EmailAddressPDO extends \dbPdo\Db {

    private $_id, $_personId, $_emailAddress, $_type, $_note;

//    public function create($data){}
//    public function readAll(){}
//    public function readAllByPersonId($id){}
//    public function readById($id){}
//    public function updateById($data){}
//    public function deleteById($id){}


    public function setEmailParam (EmailAddressController $email) {

        $this->_personId = $email->getPersonId();
        $this->_emailAddress =  $email->getEmailAddress();
        $this->_type = $email->getType();
        $this->_note = $email->getNote();
        $this->_id = $email->getId();
    }


    public function create($email)
    {
        self::setEmailParam($email);

        $stmt = $this->pdo->prepare("
					INSERT INTO email_address(
                    person_id, email_address, email_type, note )
					VALUES(
					?,?,?,?)");

        $stmt->execute([$this->_personId, $this->_emailAddress, $this->_type, $this->_note]);

        return $this->pdo->lastInsertId();
    }


    public function readAll()
    {
        $stmt = $this->pdo->prepare("
                    SELECT id, email_address, email_type, note
                    FROM email_address");

        $stmt->execute();

        return $stmt;
    }

    public function readAllByPersonId($personId)
    {
        $stmt = $this->pdo->prepare("
                    SELECT id, email_address, email_type, note
                    FROM email_address
                    WHERE person_id = ?");

        $stmt->execute([$personId]);

        return $stmt;
    }


    public function readById($id)
    {
        $stmt = $this->pdo->prepare("
					SELECT id, person_id, email_address, email_type, note
					FROM email_address
					WHERE id = ?");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    public function updateById($email)
    {
        self::setEmailParam($email);

        $stmt = $this->pdo->prepare("
                    UPDATE email_address
                    SET
                    email_address = ?,
                    email_type = ?,
                    note = ?
                    WHERE id = ? ");

        $stmt->execute([$this->_emailAddress, $this->_type, $this->_note, $this->_id]);

        return $this->pdo->lastInsertId();
    }


    public function deleteById($id)
    {
        $stmt = $this->pdo->prepare("
                    DELETE FROM email_address
                    WHERE id = ? ");

        $stmt->execute([$id]);

    }

}


