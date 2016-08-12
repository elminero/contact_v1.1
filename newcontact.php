<?php
require("controllers/LoginController.php");

require("models/Contact.php");


ob_start();
require("phoneEmailAddress.php");
$phoneEmailAddress = ob_get_contents();
ob_end_clean();

require("controllers/PersonController.php");

$updateForm = null;

// http://localhost/contact_v1.1/newcontact.php?id=1&action=update

if(  isset($_GET['action']) && $_GET['action'] === 'update'  ) {

    $action = "update";
    $id = (int)$_GET['id'];

    $contact = new PersonPDO();

    $updateForm = $contact->readById($id);
} else {
    $action = "create";
}

$error = NULL;
if( isset($_GET['validate'])  && ($_GET['validate'] == "error") ) {
    $error = 1;
}

?>

<?php
$id = NULL;

if( isset($_GET['id']) )
    $id = $_GET['id'];

if( isset($_POST['id']) )
    $id = $_POST['id'];

$contact = new Contact($id);
$contact->getContactById();
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
    <title>Add a New Contact</title>
</head>
<body>

<?php include("includes/header.php"); ?>
<div class="container" >

    <?php if($action === 'update'):?>
    <ol class="breadcrumb">
        <li><a href="listcontacts.php">List</a></li>
        <li><a href="profile.php?id=<?php echo $id; ?>">Profile</a></li>
        <li><b>Update</b></li>
    </ol>

    <div class="row">
    <section class="col-sm-6">
        <!-- div 1 Start Avatar -->
        <?php require("avatar.php"); ?>
        <!-- End Avatar -->
        <hr />
        <!-- div 2 Start Name and DOB -->
        <?php require("name_dob.php"); ?>
        <!-- End Name and DOB -->
    </section>
    <section class="col-sm-6">
        <?php endif; ?>

        <div style="<?php  if($action === 'update') echo "" ?>">
        <form class="form-horizontal" action="controllers/PersonController.php?action=<?php
        if($action == "update") {
            echo "update";
        }

        if($action == "create") {
            echo "create";
        }
        ?>" method="post" name="addContact">

            <?php if($action == "update"): ?>
            <h3 style="float: left">Update Contact</h3>
            <span style='float: right'>
                <a class="btn btn-danger" id="delete" href="controllers/PersonController.php?action=delete&id=<?php echo $id; ?>">delete</a>
            </span><br />
            <div style="clear: both"></div>
            <?php endif; ?>

            <?php if($action == "create"): ?>
            <h3>Create a New Contact</h3>
            <?php endif; ?>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="lastName">Last<span id="error"><?php if($error == 1) {echo "*";} ?></span></label>
                <div class="col-sm-10">
                    <input name="lastName" type="text" class="form-control" id="lastName"  value="<?php if($action == "update") echo $updateForm->last_name; ?>" /><br />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="firstName">First<span id="error"><?php if($error == 1) {echo "*";} ?></span></label>
                <div class="col-sm-10">
                    <input name="firstName" type="text" class="form-control" id="firstName"  value="<?php if($action == "update") echo $updateForm->first_name; ?>" /><br />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="middleName">Middle<span id="error"><?php if($error == 1) {echo "*";} ?></span></label>
                <div class="col-sm-10">
                    <input name="middleName" type="text" class="form-control" id="middleName"  value="<?php if($action == "update") echo $updateForm->middle_name; ?>" /><br />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="aliasName">Alias<span id="error"><?php if($error == 1) {echo "*";} ?></span></label>
                <div class="col-sm-10">
                    <input name="aliasName" type="text" class="form-control" id="aliasName"  value="<?php if($action == "update") echo $updateForm->alias_name; ?>" /><br />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="birthMonth">Month</label>
                <div class="col-sm-10">
                    <select name="birthMonth" class="form-control" id="birthMonth">
                        <?php if($action == "update"): ?>
                            <option value="<?php echo $updateForm->birth_month; ?>"><?php echo $contact->getMonthNameByNumber($updateForm->birth_month); ?></option>
                        <?php endif; ?>

                        <?php foreach($months as $value => $month): ?>
                            <option value="<?php echo $value; ?>"><?php echo $month; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="birthDay">Day</label>
                <div class="col-sm-10">
                    <select name="birthDay" class="form-control" id="birthDay">
                    <?php if($action == "update"): ?>
                        <option value="<?php echo $updateForm->birth_day; ?>"><?php echo $updateForm->birth_day; ?></option>
                    <?php endif; ?>

                    <option value="0"> </option>

                    <?php for($i = 1; $i <=31; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="birthYear">Year</label>
                <div class="col-sm-10">
                    <select name="birthYear" class="form-control" id="birthYear">

                        <?php if($action == "update"): ?>
                            <option value="<?php echo $updateForm->birth_year; ?>"><?php echo $updateForm->birth_year; ?></option>
                        <?php endif; ?>

                        <option value="0"> </option>

                        <?php for ($y = date('Y'); $y >= date('Y') - 110; $y--): ?>
                            <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                        <?php endfor; ?>

                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="note">Notes</label>
                <div class="col-sm-10">
                    <textarea name="note" class="form-control" id="note"  ><?php
                    if($action == 'update') {
                        echo $updateForm->note;
                    }
                    ?></textarea>
                </div>
            </div>

            <input type="hidden" name="personId" value="<?php echo $updateForm->id ?>" />

            <div class="form-group">
                <div class="col-sm-10 col-sm-offset-2">
                    <input class="btn btn-default"  type="submit" name="addNewContact" value="<?php
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

    </section>
    </div>

    <div style="clear: both"></div>
    <?php if($action === 'update') echo "<hr />" . $phoneEmailAddress; ?>

    <?php include("includes/footer.php"); ?>

</div><!-- end .container -->
<script src="js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>