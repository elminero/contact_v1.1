
<!-- div 1 Start Avatar -->
    <div style="width: 175px; padding-bottom: 20px">

    <?php if($contact->avatar): ?>
        <a href="picture.php?id=<?php echo $contact->avatar->id; ?>"><img alt="" src="images/<?php echo $contact->avatar->path_file; ?>_t.jpg" /></a>
    <?php endif ?>
    <br />
    <div style="float: left">
        <a href="addphotos.php?id=<?php echo $id ?>">View All</a>
    </div>
    <div style="float: right">
        <a href="editphotos.php?id=<?php echo $id ?>">Edit</a>
    </div>
    </div>

<!-- End Avatar -->


