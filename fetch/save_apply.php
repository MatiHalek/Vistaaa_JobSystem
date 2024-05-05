<?php
    //error_reporting(0);
    session_start();
    require "../connect.php";
    $connect = new mysqli($host, $db_user, $db_password, $db_name);
    $connect->set_charset('utf8mb4');
    $result = $connect->execute_query('SELECT * FROM advertisement WHERE advertisement_id = ?', [$_POST["advertisement_id"]]); 
    $row = $result->fetch_assoc();
    $currentDate = new DateTime(date("Y-m-d G:i:s"));
    $expirationDate = new DateTime($row["date_expiration"]);
    if(isset($_SESSION["logged"]) && $_SESSION["logged"] && array_key_exists("user_id", $_SESSION["logged"]) && isset($_POST["mode"]) && $_POST["mode"] == "saved")
    {
        $savedResult = $connect->execute_query('SELECT * FROM user_saved WHERE user_id = ? AND advertisement_id = ?', [$_SESSION["logged"]["user_id"], $_POST["advertisement_id"]]);
        if($savedResult->num_rows == 0)
        {
            if($currentDate <= $expirationDate)
                $connect->execute_query('INSERT INTO user_saved (user_id, advertisement_id) VALUES (?, ?)', [$_SESSION["logged"]["user_id"], $_POST["advertisement_id"]]);
        }
        else
            $connect->execute_query('DELETE FROM user_saved WHERE user_id = ? AND advertisement_id = ?', [$_SESSION["logged"]["user_id"], $_POST["advertisement_id"]]);
    }
    if(isset($_SESSION["logged"]) && $_SESSION["logged"] && array_key_exists("user_id", $_SESSION["logged"]) && isset($_POST["mode"]) && $_POST["mode"] == "applied")
    {
        $savedResult = $connect->execute_query('SELECT * FROM user_applied WHERE user_id = ? AND advertisement_id = ?', [$_SESSION["logged"]["user_id"], $_POST["advertisement_id"]]);
        if($savedResult->num_rows == 0)
        {
            if($currentDate <= $expirationDate)
                $connect->execute_query('INSERT INTO user_applied (user_id, advertisement_id) VALUES (?, ?)', [$_SESSION["logged"]["user_id"], $_POST["advertisement_id"]]);
        }
    }
    $saveButton = "<button id='saveButton' class='w-75 commonButton border-0' type='button'><i class='bi bi-star-fill me-2'></i>Zapisz</button>";
    $savedButton = "<button id='saveButton' class='w-75 dangerButton border-0' type='button'><i class='bi bi-star me-2'></i>Usuń z zapisanych</button>";
    if($currentDate > $expirationDate)
    {
        echo "<div class='alert alert-primary mb-0 shadow w-100'><p class='fw-bold mb-1'><i class='bi bi-info-circle-fill me-2'></i>Ogłoszenie archiwalne</p>Opcje zapisu i aplikacji nie są dostępne.</div>";
        if(isset($_SESSION["logged"]) && $_SESSION["logged"] && array_key_exists("user_id", $_SESSION["logged"]))
        {
            $savedResult = $connect->execute_query('SELECT * FROM user_saved WHERE user_id = ? AND advertisement_id = ?', [$_SESSION["logged"]["user_id"], $_POST["advertisement_id"]]);
            if($savedResult->num_rows > 0)
                echo $savedButton;
        }
    }
    else
    {
        if(isset($_SESSION["logged"]) && $_SESSION["logged"] && array_key_exists("company_id", $_SESSION["logged"]))
        {
            echo "<div class='alert alert-primary mb-0 shadow w-100'><p class='fw-bold mb-0'><i class='bi bi-info-circle-fill me-2'></i>Opcje zapisu i aplikacji są wyłączone w kontach firmowych</p></div>";
        }
        else
        {
            if(isset($_SESSION["logged"]) && $_SESSION["logged"])
            {
                $savedResult = $connect->execute_query('SELECT * FROM user_saved WHERE user_id = ? AND advertisement_id = ?', [$_SESSION["logged"]["user_id"], $_POST["advertisement_id"]]);
                if($savedResult->num_rows > 0)
                    echo $savedButton;
                else
                    echo $saveButton;
            }
            else
                echo $saveButton;
                
        }
    }
    $applyButton = "<button id='applyButton' class='w-75 successButton border-0' type='button'><i class='bi bi-check-circle me-2'></i>Aplikuj</button>";
    $appliedButton = "<button disabled class='w-75 successButton border-0 opacity-50' type='button'><i class='bi bi-check-circle-fill me-2'></i>Aplikowano</button>";
    if($currentDate > $expirationDate)
    {
        if(isset($_SESSION["logged"]) && $_SESSION["logged"] && array_key_exists("user_id", $_SESSION["logged"]))
        {
            $appliedResult = $connect->execute_query('SELECT * FROM user_applied WHERE user_id = ? AND advertisement_id = ?', [$_SESSION["logged"]["user_id"], $_POST["advertisement_id"]]);
            if($appliedResult->num_rows > 0)
                echo $appliedButton;
        }
    }
    else
    {
        if(isset($_SESSION["logged"]) && $_SESSION["logged"])
        {
            if(array_key_exists("user_id", $_SESSION["logged"]))
            {
                 $appliedResult = $connect->execute_query('SELECT * FROM user_applied WHERE user_id = ? AND advertisement_id = ?', [$_SESSION["logged"]["user_id"], $_POST["advertisement_id"]]);
                if($appliedResult->num_rows > 0)
                    echo $appliedButton;
                else
                    echo $applyButton;
            }
           
        }
        else
            echo $applyButton;
    }
    $connect->close();
?>