<?php
    error_reporting(0);
    session_start();
    if(!isset($_POST["email"]) || !isset($_POST["password"]))
    {
        header("Location: ./");
        exit();
    }
    require "connect.php";
    $connect = new mysqli($host, $db_user, $db_password, $db_name);
    $connect->set_charset('utf8mb4');
    $email = $_POST["email"];
    $password = $_POST["password"];
    $result = $connect->execute_query('SELECT * FROM (SELECT email, password FROM company UNION SELECT email, password FROM user) AS accounts WHERE email = ?', [$email]);
    if($result->num_rows > 0)
    {
        $row = $result->fetch_assoc();
        if(password_verify($password, $row["password"]))
        {
            $result = $connect->execute_query('SELECT * FROM user WHERE email = ?', [$email]);
            if($result->num_rows > 0)
                $_SESSION["logged"] = $result->fetch_assoc();
            else
                $_SESSION["logged"] = ($connect->execute_query('SELECT * FROM company WHERE email = ?', [$email]))->fetch_assoc();
            unset($_SESSION["login_error"]);
        }
        else
            $_SESSION["login_error"] = "<div class='alert alert-danger'><strong>Nieprawidłowy adres e-mail lub hasło.</strong></div>";          
    }
    else
        $_SESSION["login_error"] = "<div class='alert alert-danger'><strong>Nieprawidłowy adres e-mail lub hasło.</strong></div>";    
    $connect->close(); 
    if(isset($_SERVER['HTTP_REFERER']))
        header("Location: ".$_SERVER['HTTP_REFERER']);  
    else
        header("Location: ./");  
?>