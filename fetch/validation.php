<?php
    error_reporting(0);
    require "../functions.php";
    $fetch_property = "Validate".ucfirst($_POST["property"]);
    $fetch_value = $_POST["q"];
    if(isset($_POST["tmp"]))
        $fetch_success = call_user_func($fetch_property, $fetch_value, $_POST["tmp"]);
    else
        $fetch_success = call_user_func($fetch_property, $fetch_value);
    if(!$fetch_success["passed"])
        echo "<div class='text-danger'>".$fetch_success["note"]."</div>";
    else
        echo "<div class='text-success'></div>";
?>