<?php
require("controllers/LoginController.php");

require("models/Contact.php");

if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
}
elseif(!isset($_GET['id'])) {
    header("Location: listcontacts.php");
}
/*
ob_start();
require("avatarNameDOB.php");
$avatarNameDOB = ob_get_contents();
ob_end_clean();
*/

ob_start();
require("phoneEmailAddress.php");
$phoneEmailAddress = ob_get_contents();
ob_end_clean();

require_once('models/EmailAddress.php');

$email = new EmailAddressPDO();

$emailData = null;
$action = null;

if( (isset($_GET['update'])) &&  ($_GET['update'] === '') ) {
    header("Location: profile.php?id=" . $_GET["id"]);
}

if(isset($_GET['update'])) {
    $action = "update";
    $updateId = (int)$_GET['update'];
    $emailData = $email->readById($updateId);

    if($emailData == null) {
        header("Location: profile.php?id=" . $_GET["id"]);
    }
} else {
    $action = "create";
}

if( (isset($_GET['validate'])) && ($_GET['validate'] == 'error') ) {
    $validate = "error";
} else {
    $validate = null;
}



$contact = new Contact($id);
$contact->getContactById();

if ($contact->nameDOB === false) {
    header("Location: listcontacts.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="js/jquery-1.12.0.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <title>Add E-Mail Address</title>
</head>
<body>
<?php include("includes/header.php"); ?>
<div class="container">
    <ol class="breadcrumb">
        <li><a href="listcontacts.php">List</a></li>
        <li><a href="profile.php?id=<?php echo $_GET['id']; ?>" >Profile</a></li>
        <li><b><?php if($action == "update") echo "Update"; if($action == "create") echo "Add"; ?> Email Address</b></li>
    </ol>

    <div class="row">
        <section class="col-sm-6">
            <?php require("avatar.php"); ?>
        </section>

        <section class="col-sm-6">

            <form class="form-horizontal"  action="controllers/EmailAddressController.php?action=<?php

                if($action == "update") {
                    echo "update";
                }

                if($action == "create") {
                    echo "create";
                }

                ?>" method="post" name="addEmail">

                <?php if($action == "update"): ?>
                    <h3 style="float: left">Update E-Mail Address</h3>
                    <span style='float: right'>
                    <a class="btn btn-danger" id="delete"
                    href="controllers/EmailAddressController.php?action=delete&id=<?php echo $emailData->id; ?>&personId=<?php echo $_GET['id']; ?> ">delete</a>
                    </span><br />
                    <div style="clear: both"></div>
                <?php endif; ?>

                <?php if($action == "create"): ?>
                    <h3>Add E-Mail Address</h3>
                <?php endif; ?>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="type">Type</label>
                    <div class="col-sm-10">
                        <select name="type" class="form-control" id="type">
                            <option <?php if(($action == "update") && ($emailData->email_type == 0)) {echo "selected";}  ?> value="0"> </option>
                            <option <?php if(($action == "update") && ($emailData->email_type == 1)) {echo "selected";} ?> value="1">Business</option>
                            <option <?php if(($action == "update") && ($emailData->email_type == 2)) {echo "selected";} ?> value="2">Home</option>
                            <option <?php if(($action == "update") && ($emailData->email_type == 3)) {echo "selected";} ?> value="3">Shared</option>
                            <option <?php if(($action == "update") && ($emailData->email_type == 4)) {echo "selected";} ?> value="4">Previous</option>
                            <option <?php if(($action == "update") && ($emailData->email_type == 5)) {echo "selected";} ?> value="5">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="emailAddress">E-Mail</label>
                    <div class="col-sm-10">
                        <input name="emailAddress" type="text" class="form-control" id="emailAddress"  value="<?php if($action == "update") {echo $emailData->email_address;} ?>" /><br />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="note">Notes</label>
                    <div class="col-sm-10">
                        <textarea name="note" class="form-control" id="note"  ><?php
                        if($action == 'update') {
                            echo $emailData->note;;
                        }
                        ?></textarea>
                    </div>
                </div>

                <input type="hidden" name="emailId" value="<?php echo  $_GET['update'] ?>" />
                <input type="hidden" name="personId" value="<?php echo $_GET['id'] ?>" />

                <div style="float: left; color: #990000; margin-top: 10px;">
                    <?php if($validate == "error") {echo "* E-Mail Address Is Not Valid.";} ?>
                </div>

                <div class="form-group">
                    <div class="col-sm-10 col-sm-offset-2">
                        <input class="btn btn-default"  type="submit" name="addEmail" value="<?php
                        if($action == "create") {
                            echo "Create";
                        }elseif($action == "update") {
                            echo "Update";
                        }
                        ?>"
                        id="<?php
                        if($action == "create") {
                            echo "create";
                        }elseif($action == "update") {
                            echo "update";
                        }
                        ?>"
                        />
                    </div>
                </div>

            </form>

        </section><!--<div class="col-sm-5">-->

    </div><!--<div class="row">-->
    <!-- array(4) { ["personId"]=> int(37) ["phoneNumber"]=> string(12) "914-331-8584" ["phoneType"]=> int(2) ["note"]=> string(2) "NY" } -->
    <hr/>

    <div class="row">
        <!-- div 2 Start Name and DOB -->
        <div class="col-sm-12">
            <p>
                <?php require("name_dob.php"); ?>
            </p>
        </div>
    </div>
    <hr />
    <?php echo $phoneEmailAddress; ?>

    <?php include("includes/footer.php"); ?>
</div><!-- end .container -->
<script src="js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>