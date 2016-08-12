<?php
require_once(dirname(dirname(__FILE__)).'/models/Image.php');
//tDebug --  Db2 -- User -- Person -- Address -- PhoneNumber -- EmailAddress -- Image

// VARIABLES: $id, $personId, $pathFile, $caption, $avatar, $visible

class ImageController {

    // WHERE TO PUT IMAGES
    const IMAGE_FOLDER = "../images/";

    // WIDTH OF LARGE IMAGE
    const LARGE_IMAGE_WIDTH = 800;

    // WIDTH OF THUMB NAIL
    const THUMB_NAIL_IMAGE_WIDTH = 175;

    private $_id, $_personId,  $_pathFile, $_caption, $_avatar, $_visible;

    private $_fileName, $_fileType, $_fileTmpName, $_fileSize;

    private $_randHex, $_imageFolderLocationFullSize, $_imageFolderLocationThumbSize, $_imageLocation;


    function __construct($id, $personId, $caption, $avatar, $fileName = null, $fileType = null, $fileTmpName = null, $fileSize = null ) {

        $this->_id = $id;
        $this->_personId = $personId;
        $this->_caption = $caption;
        $this->_avatar = $avatar;

        $this->_fileName = $fileName;
        $this->_fileType = $fileType;
        $this->_fileTmpName = $fileTmpName;
        $this->_fileSize = $fileSize;
    }


    public function getId(){return $this->_id;}
    public function getPersonId(){return $this->_personId;}
    public function getPathFile(){return $this->_imageLocation . $this->_randHex;}
    public function getCaption(){return $this->_caption;}
    public function getAvatar(){return $this->_avatar;}
    public function getVisible(){return $this->_visible;}



    public function imageFieldValidate()
    {
        $imageFieldValidate = ["jpeg" => 1, "fileSize" => 1];

        if($this->_fileType != 'image/jpeg') {
            $imageFieldValidate["jpeg"] = 0;
        }

        if($this->_fileSize > 400000) {
            $imageFieldValidate["fileSize"] = 0;
        }

        return $imageFieldValidate;
    }


    public function createImageFolder()
    {
        // Create the folders YY/MM/DD/HH
        $date = explode( "|", date("y|m|d|H") );
        list($y, $m, $d, $h) = $date;

        if(!file_exists(self::IMAGE_FOLDER . $y))
        {
            mkdir(self::IMAGE_FOLDER . $y, 0777, true);
            chmod(self::IMAGE_FOLDER . $y, 0777);
        }


        if(!file_exists(self::IMAGE_FOLDER . $y . "/" . $m))
        {
            mkdir(self::IMAGE_FOLDER . $y . "/" . $m, 0777, true);
            chmod(self::IMAGE_FOLDER . $y . "/" . $m, 0777);
        }

        if(!file_exists(self::IMAGE_FOLDER . $y . "/" .  $m . "/" . $d))
        {
            mkdir(self::IMAGE_FOLDER . $y . "/" .  $m . "/" . $d, 0777, true);
            chmod(self::IMAGE_FOLDER . $y . "/" .  $m . "/" . $d, 0777);
        }

        if(!file_exists(self::IMAGE_FOLDER . $y . "/" .  $m . "/" . $d . "/" . $h))
        {
            mkdir(self::IMAGE_FOLDER . $y . "/" .  $m . "/" . $d . "/" . $h, 0777, true);
            chmod(self::IMAGE_FOLDER . $y . "/" .  $m . "/" . $d . "/" . $h, 0777);
        }

        $this->_imageLocation = $y . "/" .  $m . "/" . $d . "/" . $h . "/";
    }


    public function moveToImageFolderRenameCopy()
    {

        move_uploaded_file($this->_fileTmpName, self::IMAGE_FOLDER . $this->_imageLocation . $this->_fileName);

//        move_uploaded_file($_FILES["file"]["tmp_name"], self::IMAGE_FOLDER . $this->_imageLocation . $_FILES["file"]["name"]);

        $this->_randHex = substr(md5(rand()), 0, 8);

        $fullSize = $this->_randHex . ".jpg";

        $thumbSize = $this->_randHex . "_t.jpg";

        $this->_imageFolderLocationFullSize = self::IMAGE_FOLDER . $this->_imageLocation . $fullSize;

        $this->_imageFolderLocationThumbSize = self::IMAGE_FOLDER . $this->_imageLocation . $thumbSize;

        rename(self::IMAGE_FOLDER . $this->_imageLocation . $this->_fileName ,  $this->_imageFolderLocationFullSize) ;

        copy($this->_imageFolderLocationFullSize, $this->_imageFolderLocationThumbSize);
    }


    public function resizeFullSize()
    {
        //Resize the full size image only if original is more than 800 width
        $imageOriginal = imagecreatefromjpeg($this->_imageFolderLocationFullSize);
        $imageOriginalWidth = imagesx($imageOriginal);
        if($imageOriginalWidth > self::LARGE_IMAGE_WIDTH)
        {
            $imageOriginalHeight = imagesy($imageOriginal);

            // Make the width 800px and find the new height
            $displayHeight = intval(self::LARGE_IMAGE_WIDTH * $imageOriginalHeight / $imageOriginalWidth);

            $displayImage = imagecreatetruecolor(self::LARGE_IMAGE_WIDTH, $displayHeight);

            imagecopyresampled($displayImage, $imageOriginal, 0, 0, 0, 0, self::LARGE_IMAGE_WIDTH, $displayHeight,
                $imageOriginalWidth, $imageOriginalHeight);

            imagejpeg($displayImage, $this->_imageFolderLocationFullSize);
        }
    }


    public function resizeThumbNail()
    {
        $imageOriginal = imagecreatefromjpeg($this->_imageFolderLocationThumbSize);
        $imageOriginalWidth = imagesx($imageOriginal);

        $imageOriginalHeight = imagesy($imageOriginal);

        // Make the width and find the new height
        $displayHeight = intval(self::THUMB_NAIL_IMAGE_WIDTH * $imageOriginalHeight / $imageOriginalWidth);

        $displayImage = imagecreatetruecolor(self::THUMB_NAIL_IMAGE_WIDTH, $displayHeight);

        imagecopyresampled($displayImage, $imageOriginal, 0, 0, 0, 0, self::THUMB_NAIL_IMAGE_WIDTH, $displayHeight,
            $imageOriginalWidth, $imageOriginalHeight);

        imagejpeg($displayImage, $this->_imageFolderLocationThumbSize );
    }


    // $imageData = ["personId"=>$personId, "caption"=>$caption, "avatar"=>$avatar];

    public function upload($cImage)
    {
        self::createImageFolder();

        self::moveToImageFolderRenameCopy();

        self::resizeFullSize();

        self::resizeThumbNail();

        $model = new ImagePDO();

        $model->create($cImage);

    }


}


if( array_key_exists("imageUpLoad", $_POST)) {

    if( isset($_POST['avatar']) ) {
        $avatar = 1;
    } else {
        $avatar = 0;
    }

    if( !isset($_FILES['file']['name']) ) {
        $_FILES['file']['name'] = null;
    }

    if( !isset($_FILES['file']['tmp_name']) ) {
        $_FILES['file']['tmp_name'] = null;
    }

    if( !isset($_FILES['file']['size']) ) {
        $_FILES['file']['size'] = null;
    }

    if( !isset($_FILES['file']['type']) ) {
        $_FILES['file']['type'] = null;
    }

    // $id, $personId, $caption, $avatar, $fileName, $fileType, $fileTmpName, $fileSize
    $cImage = new ImageController( (int)$_POST['id'], $_POST['personId'], $_POST['caption'], $avatar,
        $_FILES['file']['name'], $_FILES['file']['type'], $_FILES['file']['tmp_name'], $_FILES['file']['size']  );

 //



//  if( ($cImage->imageFieldValidate()->$validate['jpeg'] == 1)  && ($cImage->imageFieldValidate()->$validate == 1)  ) {


  if( (isset($_GET['action'])) && ($_GET['action'] === 'create') ) {

      $validate = $cImage->imageFieldValidate();

      if(($validate['jpeg'] === 1) && ($validate['fileSize'] === 1) ) {
          $cImage->upload($cImage);
          header("Location: ../addphotos.php?id=".$_POST['personId']."&action=update");
      } else {
          header("Location: ../addphotos.php?id=".$_POST['personId']."&validate=error&action=update");
      }

  }


    if( (isset($_GET['action'])) && ($_GET['action'] === 'update') ) {
        $model = new ImagePDO();
        $model->updateById($cImage);
        header("Location: ../editphotos.php?id=".$_POST['personId']."&action=update");
    }

}


if( isset($_GET['action']) && $_GET['action'] == 'delete' ) {

    $deleteId = (int)$_GET['id'];

    $cImage = new ImagePDO();
    $cImage->deleteById($deleteId);

    header("Location: ../editphotos.php?id=".$_GET['personId']."&action=update");

}