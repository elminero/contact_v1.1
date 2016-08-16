<?php
//tDebug --  Db2 -- User -- Person -- Address -- PhoneNumber -- EmailAddress -- (Image)
// require("EmailAddress.php");
require_once(dirname(dirname(__FILE__)).'/models/Db.php');

/*
        TABLE:   image
        COLUMNS: id, person_id, path_file, caption, avatar, visible
        VARIABLES: $id, $personId, $pathFile, $caption, $avatar, $visible

        id              integer
        person_id       integer
        path_file       string
        caption         string
        avatar          boolean
        visible         boolean
*/


class ImagePDO extends \dbPdo\Db
{
    private $_id, $_personId,  $_pathFile, $_caption, $_avatar, $_visible;
    public $previousImageId, $nextImageId;


//      public function create($data){}
//      public function readAll(){}
//      public function readAllByPersonId($id){}
//      public function readById($id){}
//      public function updateById($data){}
//      public function deleteById($id){}

//      public function getPersonIdByImageId($imageId){}
//      public function setPreviousNextImageId($id){}
//      public function getAvatarImageByPersonId($personId){}


    private function setImageParam(ImageController $image)
    {
        $this->_id = $image->getId();
        $this->_personId = $image->getPersonId();
        $this->_pathFile = $image->getPathFile();
        $this->_caption = $image->getCaption();
        $this->_avatar = $image->getAvatar();
        $this->_visible = $image->getVisible();
    }


    public function getId(){return $this->_id;}
    public function getPersonId(){return $this->_personId;}
    public function getCaption(){return $this->_caption;}
    public function getAvatar(){return $this->_avatar;}
    public function getVisible(){return $this->_visible;}


    private function resetAvatarToZero($personId)  // class Image
    {
        $stmt = $this->pdo->prepare("
                    UPDATE image
                    SET
                    avatar = 0
                    WHERE person_id = ?");

        $stmt->execute(array($personId));
    }


    public function create($image)
    {
        self::setImageParam($image);

        if($this->_avatar === 1) {
            self::resetAvatarToZero($this->_personId);
        }

        $stmt = $this->pdo->prepare("
                    INSERT INTO image
                    (person_id, path_file, caption, avatar, visible)
                    VALUES
                    (?, ?, ?, ?, ? )");

        $stmt->execute([$this->_personId, $this->_pathFile, $this->_caption, $this->_avatar, $this->_visible]);
    }


    public function readAll()
    {
        $stmt = $this->pdo->prepare("
                    SELECT id, person_id, path_file, caption, avatar, visible
                    FROM image
                    WHERE person_id = ? ORDER BY id DESC;");

        $stmt->execute();

        return $stmt;
    }


    public function readAllByPersonId($personId)
    {
        $stmt = $this->pdo->prepare("
                    SELECT id, person_id, path_file, caption, avatar, visible
                    FROM image
                    WHERE person_id = ? ORDER BY id DESC;");


        $stmt->execute([$personId]);

        return $stmt;
    }


    public function readById($id)
    {
        $stmt = $this->pdo->prepare("
					SELECT id, person_id, path_file, caption, avatar, visible
					FROM image
					WHERE id = ?");

        $stmt->execute([$id]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    public function updateById($image)
    {
        self::setImageParam($image);

        if($this->_avatar === 1) {
            self::resetAvatarToZero($this->_personId);
        }

        $stmt = $this->pdo->prepare("
                    UPDATE image
                    SET
                    caption = ?,
                    avatar = ?
                    WHERE id = ? ");

        $stmt->execute([$this->_caption, $this->_avatar,  $this->_id]);
    }


    public function deleteById($id)
    {
        $stmt = $this->pdo->prepare("
                    DELETE FROM image
                    WHERE id = ?");

        $stmt->execute([$id]);
    }


    public function getPersonIdByImageId($imageId)
    {
        $stmt = $this->pdo->prepare("
					SELECT person_id
					FROM image
					WHERE id = ?");

        $stmt->execute([$imageId]);

        return $stmt->fetch(PDO::FETCH_OBJ)->person_id;
    }


    private function getMaxImageByPersonId($personId)
    {
        $stmt = $this->pdo->prepare("
					SELECT MAX(id) as id
					FROM image
					WHERE person_id = ?");

        $stmt->execute([$personId]);

        return $stmt->fetch(PDO::FETCH_OBJ)->id;
    }


    private function getMinImageByPersonId($personId)
    {
        $stmt = $this->pdo->prepare("
					SELECT MIN(id) as id
					FROM image
					WHERE person_id = ?");

        $stmt->execute([$personId]);

        return $stmt->fetch(PDO::FETCH_OBJ)->id;
    }


    private function getNextLowerImageId($id)
    {
        $nextLowerImageId = null;

        $personId = self::getPersonIdByImageId($id);

        $stmt = $this->pdo->prepare("
					SELECT id
					FROM image
					WHERE person_id = ?
					AND id < ?
					ORDER BY id
					DESC LIMIT 1");

        $stmt->execute([$personId, $id]);

        if($stmt->rowCount()) {
            $nextLowerImageId = $stmt->fetchObject()->id;
        }

        return $nextLowerImageId;
    }


    private function getNextHigherImageId($id)
    {
        $nextHigherImageId = null;

        $personId = self::getPersonIdByImageId($id);

        $stmt = $this->pdo->prepare("
					SELECT id
					FROM image
					WHERE person_id = ?
					AND id > ?
					ORDER BY id
					LIMIT 1");

        $stmt->execute([$personId, $id]);

        if($stmt->rowCount()) {
            $nextHigherImageId = $stmt->fetchObject()->id;
        }

        return $nextHigherImageId;
    }


    private function getPreviousImageId($id)
    {
        $nextLowerImageId = self::getnextLowerImageId($id);

        if(!$nextLowerImageId) {
            $nextLowerImageId = self::getMaxImageByPersonId(self::getPersonIdByImageId($id));
        }

        return $nextLowerImageId;
    }


    private function getNextImageId($id)
    {
        $nextHigherImageId = self::getNextHigherImageId($id);

        if(!$nextHigherImageId) {
            $nextHigherImageId = self::getMinImageByPersonId(self::getPersonIdByImageId($id));
        }

        return $nextHigherImageId;
    }


    public function setPreviousNextImageId($id)
    {
        $this->previousImageId = self::getPreviousImageId($id);
        $this->nextImageId = self::getNextImageId($id);
    }


    public function getAvatarImageByPersonId($personId)  // class Image
    {

        $stmt = $this->pdo->prepare("
                    SELECT id, path_file, caption, avatar, visible
                    FROM image
                    WHERE avatar = 1 AND person_id = ? LIMIT 1");

        $stmt->execute([$personId]);

        return $stmt->fetchObject();
    }

}
