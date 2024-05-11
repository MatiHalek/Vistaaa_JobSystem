<?php
    //error_reporting(0);
    function ValidateName($item)
    {
        $function = substr(strtolower(__FUNCTION__), 8);
        if(empty($item) || ctype_space($item))
            return ["parameter" => $function, "passed" => false, "note" => "Proszę podać nazwę firmy."];
        if(strlen($item) < 3 || strlen($item) > 100)
            return ["parameter" => $function, "passed" => false, "note" => "Nazwa firmy musi zawierać od 3 do 100 znaków."];    
        require "connect.php";
        $connect = new mysqli($host, $db_user, $db_password, $db_name);
        $connect->set_charset("utf8mb4");
        $result= $connect->execute_query("SELECT * FROM company WHERE name = ?", [$item]);
        $howManyRows = $result->num_rows;
        $connect->close();
        if($howManyRows > 0)
            return ["parameter" => $function, "passed" => false, "note" => "Taka nazwa firmy już istnieje."];
        return ["parameter" => $function, "passed" => true, "note" => null];          
    }
    function ValidatePostcode($item)
    {
        $function = substr(strtolower(__FUNCTION__), 8);
        if(empty($item))
            return ["parameter" => $function, "passed" => false, "note" => "Proszę podać kod pocztowy."];
        if(!preg_match("/^[0-9]{2}-[0-9]{3}$/", $item))
            return ["parameter" => $function, "passed" => false, "note" => "Kod pocztowy musi być w formacie XX-XXX."];
        return ["parameter" => $function, "passed" => true, "note" => null]; 
    }
    function ValidateStreet($item)
    {
        $function = substr(strtolower(__FUNCTION__), 8);
        if(empty($item) || ctype_space($item))
            return ["parameter" => $function, "passed" => false, "note" => "Proszę podać nazwę ulicy."];
        if(strlen($item) < 3 || strlen($item) > 100)
            return ["parameter" => $function, "passed" => false, "note" => "Nazwa ulicy musi zawierać od 3 do 100 znaków."];    
        return ["parameter" => $function, "passed" => true, "note" => null]; 
    }
    function ValidateNumber($item)
    {
        $function = substr(strtolower(__FUNCTION__), 8);
        if(empty($item) || ctype_space($item))
            return ["parameter" => $function, "passed" => false, "note" => "Proszę podać numer budynku."];
        if(strlen($item) > 10)
            return ["parameter" => $function, "passed" => false, "note" => "Numer budynku nie może mieć więcej niż 10 znaków."];    
        return ["parameter" => $function, "passed" => true, "note" => null];
    }
    function ValidateCity($item)
    {
        $function = substr(strtolower(__FUNCTION__), 8);
        if(empty($item) || ctype_space($item))
            return ["parameter" => $function, "passed" => false, "note" => "Proszę podać nazwę miasta."];
        if(strlen($item) > 50)
            return ["parameter" => $function, "passed" => false, "note" => "Nazwa miasta nie może mieć więcej niż 50 znaków."]; 
        return ["parameter" => $function, "passed" => true, "note" => null];
    }
    function ValidatePassword($item, $check)
    {
        $function = substr(strtolower(__FUNCTION__), 8);
        if(empty($item))
            return ["parameter" => $function, "passed" => false, "note" => "Proszę wprowadzić hasło."];
        if(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!-\/:-@[-`{-~])[a-zA-Z\d!-\/:-@[-`{-~ ]{8,255}$/", $item))
            return ["parameter" => $function, "passed" => false, "note" => "Hasło musi zawierać minimum 8 znaków (w tym cyfry, małe i duże litery oraz znaki specjalne)."];
        if($item == $check)
            return ["parameter" => $function, "passed" => false, "note" => "Hasło powinno być inne niż adres email."];
        return ["parameter" => $function, "passed" => true, "note" => null]; 
    }
    function ValidatePassword2($item, $check)
    {
        $function = substr(strtolower(__FUNCTION__), 8);
        if(empty($item))
            return ["parameter" => $function, "passed" => false, "note" => "Proszę wprowadzić hasło."];
        if($item != $check)
            return ["parameter" => $function, "passed" => false, "note" => "Wprowadzone hasła nie są identyczne."];
        return ["parameter" => $function, "passed" => true, "note" => null]; 
    }
    function ValidateEmail($item)
    {
        $function = substr(strtolower(__FUNCTION__), 8);
        if(empty($item))
            return ["parameter" => $function, "passed" => false, "note" => "Proszę podać adres e-mail."];
        $item2 = filter_var($item, FILTER_SANITIZE_EMAIL);
        if(filter_var($item2, FILTER_VALIDATE_EMAIL) == false || $item != $item2)
            return ["parameter" => $function, "passed" => false, "note" => "Ten adres e-mail jest nieprawidłowy."];
        require "connect.php";
        $connect = new mysqli($host, $db_user, $db_password, $db_name);
        $connect->set_charset("utf8mb4");
        $result = $connect->execute_query("SELECT email FROM user UNION SELECT email FROM company");
        while($row = $result->fetch_assoc())
        {
            if($row["email"] == $item)
            {
                $connect->close();
                return ["parameter" => $function, "passed" => false, "note" => "Taki email jest już przypisany do innego konta."];
            }
        }
        $connect->close();
        return ["parameter" => $function, "passed" => true, "note" => null];          
    }
    function ValidateBirth($item)
    {
        $function = substr(strtolower(__FUNCTION__), 8);
        if(empty($item))
            return ["parameter" => $function, "passed" => false, "note" => "Proszę podać poprawną datę."];
        $date_arr = explode("-", $item);
        if(count($date_arr) != 3 || !checkdate((int)$date_arr[1], (int)$date_arr[2], (int)$date_arr[0]))
            return ["parameter" => $function, "passed" => false, "note" => "Proszę podać poprawną datę (format RRRR-MM-DD)."];
        if(mktime(0, 0, 0, $date_arr[1], $date_arr[2], $date_arr[0]) > strtotime("- 18 years"))
            return ["parameter" => $function, "passed" => false, "note" => "Rejestracja jest dostępna dla użytkowników, którzy ukończyli 18 lat."];
        return ["parameter" => $function, "passed" => true, "note" => null];
    }
    function ValidateRegulations($item)
    {
        $function = substr(strtolower(__FUNCTION__), 8);
        if(!filter_var($item, FILTER_VALIDATE_BOOLEAN))
            return ["parameter" => $function, "passed" => false, "note" => "Aby kontynuować, musisz zaakceptować postanowienia."];
        return ["parameter" => $function, "passed" => true, "note" => null];
    }
    function ValidateCaptcha()
    {
        $function = substr(strtolower(__FUNCTION__), 8);
        $secret = "6LfiUfknAAAAAAFOnaJDuO2oBJHv5gHjnKYDUSFz";
        $check = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$_POST["g-recaptcha-response"]);
        $answer = json_decode($check);
        if($answer->success && $answer->score >= 0.5)
            return ["parameter" => $function, "passed" => true, "note" => null];
        return ["parameter" => $function, "passed" => false, "note" => "Zweryfikuj, że jesteś człowiekiem."];        
    }
?>