<?php
require("controllers/LoginController.php");

require("models/Contact.php");

if(isset($_GET['id'])) {
    $id = (int)$_GET['id'];
}
elseif(!isset($_GET['id'])) {
    header("Location: listcontacts.php");
}

ob_start();
require("phoneEmailAddress.php");
$phoneEmailAddress = ob_get_contents();
ob_end_clean();

require_once('models/Address.php');

$address = new AddressPDO();

$qCountries = $address->getAllCountry();

$addressData = null;
$action = null;

if(isset($_GET['update'])) {
    $action = "update";
    $updateId = (int)$_GET['update'];
    $addressData = $address->readById($updateId);
    if($addressData == null) {
        header("Location: profile.php?id=" . $_GET["id"]);
    }
} else {
    $action = "create";
    $_GET['update'] = null;
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
    <script src="statedropdown2.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="js/jquery-1.12.0.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <title>Address</title>
</head>
    <body>
        <?php include("includes/header.php"); ?>
    <div class="container">
        <ol class="breadcrumb">
            <li><a href="listcontacts.php">List</a></li>
            <li><a href="profile.php?id=<?php echo $_GET['id']; ?>" >Profile</a></li>
            <li><b><?php if($action == "update") echo "Update"; if($action == "create") echo "Add"; ?> Address</b></li>
        </ol>
<div class="row">

    <section class="col-sm-6">
        <!-- div 1 Start Avatar -->
        <?php require("avatar.php"); ?>
        <!-- End Avatar -->
        <hr />
        <!-- div 2 Start Name and DOB -->
        <div style="float: left; width: 200px">
            <?php require("name_dob.php"); ?>
        </div>
        <div style="clear: both"></div>
        <!-- End Name and DOB -->
    </section>
    <section class="col-sm-6">

        <div style="margin-top: 10px;">

            <div style="clear: both"></div>

            <form class="form-horizontal" action="controllers/AddressController.php?action=<?php

                if($action == "update") {
                    echo "update";
                }

                if($action == "create") {
                    echo "create";
                }

                ?>" method="post" name="addAddress">

                <?php if($action == "update"): ?>
                    <h3 style="float: left">Update Address</h3>
                    <span style='float: right'>
            <a class="btn btn-danger"  id="delete" href="controllers/AddressController.php?action=delete&id=<?php echo $updateId; ?>&personId=<?php echo $_GET['id']; ?>">delete</a>
            </span><br />
                    <div style="clear: both"></div>
                <?php endif; ?>

                <?php if($action == "create"): ?>
                    <h3>Add Address</h3>
                <?php endif; ?>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="address_type">Type</label>
                    <div class="col-sm-10">
                        <select name="address_type" class="form-control" id="address_type">
                            <option <?php if (($action == "update") && ($addressData->address_type == 0)) {echo "selected";} ?> value="0" >  </option>
                            <option <?php if (($action == "update") && ($addressData->address_type == 1)) {echo "selected";} ?>  value="1" > Current Street </option>
                            <option <?php if (($action == "update") && ($addressData->address_type == 2)) {echo "selected";} ?>  value="2" > Current Mailing </option>
                            <option <?php if (($action == "update") && ($addressData->address_type == 3)) {echo "selected";} ?>  value="3" > Previous Street </option>
                            <option <?php if (($action == "update") && ($addressData->address_type == 4)) {echo "selected";} ?>  value="4" > Previous Mailing </option>
                            <option <?php if (($action == "update") && ($addressData->address_type == 5)) {echo "selected";} ?>  value="5" > Current Crash Pad </option>
                            <option <?php if (($action == "update") && ($addressData->address_type == 6)) {echo "selected";} ?>  value="6" > Previous Crash Pad </option>
                            <option <?php if (($action == "update") && ($addressData->address_type == 7)) {echo "selected";} ?>  value="7" > Other </option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="country">Country</label>
                    <div class="col-sm-10">
                        <select name="country_iso" class="form-control" id="country">
                            <?php if($addressData): ?>
                                <option value= "<?php echo $addressData->country_iso;  ?> ">
                                    <?php // echo $address->getCountryByISO($addressData->country_iso);
                                    echo $address->getCountryByISO($addressData->country_iso);                                                              ?> </option>
                            <?php endif ?>

                            <option value= "1"> </option>
                            <option value="US" > United States </option  >
                            <option value="CA" > Canada </option>
                            <option value="MX" > Mexico </option>

                            <?php while($row = $qCountries->fetch(PDO::FETCH_OBJ) ) : ?>
                                <option value="<?php echo $address->getCountryByISO($row->iso) ; ?>" >
                                    <?php echo $row->country; ?> </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="stateSelect">State</label>
                    <div class="col-sm-10">
                        <select name="state" class="form-control" id="stateSelect">
                            <option value="<?php if ($action == "update") {echo $addressData->state;}  ?>"><?php if ($action == "update") {echo $addressData->state;}  ?></option>
                            <option value="">Select Country First</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="city">City</label>
                    <div class="col-sm-10">
                        <input name="city" type="text" class="form-control" id="city"  value="<?php if ($action == "update") {echo $addressData->city;} ?>" /><br />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="street">Street</label>
                    <div class="col-sm-10">
                        <input name="street" type="text" class="form-control" id="street"  value="<?php if ($action == "update") {echo $addressData->street;} ?>" /><br />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="postal_code">Postal Code</label>
                    <div class="col-sm-10">
                        <input name="postal_code" type="text" class="form-control" id="postal_code"  value="<?php if ($action == "update") {echo $addressData->postal_code;} ?>" /><br />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="note">Notes</label>
                    <div class="col-sm-10">
            <textarea name="note" class="form-control" id="note"  ><?php if ($action == "update") {echo $addressData->note;} ?></textarea>
                    </div>
                </div>

                <input type="hidden" name="personId" value="<?php echo $_GET['id'] ?>" />
                <input type="hidden" name="id" value="<?php echo  $_GET['update'] ?>" />

                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input class="btn btn-default"  type="submit" name="addAddress" value="<?php
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
        </div><!--<div style="margin-top: 10px;">-->
    <section>

</div><!--<div class="row">-->

        <div style="clear: both"></div>
            <?php // if($action === 'update')
            echo "<hr />" . $phoneEmailAddress; ?>

        <div style="clear: both"></div>
    <?php include("includes/footer.php"); ?>

    </div><!-- end .container -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/script.js"></script>
    </body>
</html>