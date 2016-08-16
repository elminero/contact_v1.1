<?php
namespace dbPdo;
//(tDebug) --  Db2 -- User -- Person -- Address -- PhoneNumber -- EmailAddress -- Image

trait tDebug {

    // Method dumps out a lot of data about the current object:
    public function dumpObject() {

        // Get the class name:
        $class = get_class($this);

        // Get the attributes:
        $attributes = get_object_vars($this);

        // Get the methods:
        $methods = get_class_methods($this);

        // Print a heading:
        echo "<h2>Information about the $class object</h2>";

        // Print the attributes:
        echo '<h3>Attributes</h3><ul>';
        foreach ($attributes as $k => $v) {
            echo "<li>" .   $k . ": ".  var_dump($v) .   "</li>";
        }
        echo '</li></ul>';

        // Print the methods:
        echo '<h3>Methods</h3><ul>';
        foreach ($methods as $v) {
            echo "<li>$v</li>";
        }
        echo '</li></ul>';

    }

}
