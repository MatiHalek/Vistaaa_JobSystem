<?php
    //error_reporting(0);
    session_start();
    if(isset($_SESSION["logged"]) && $_SESSION["logged"])
    {
        header("Location: ./");
        exit();
    }/*
    require "functions.php";
    if(isset($_POST["reg_login"]))
    {
        $success = true;
        $reg_login = $_POST["reg_login"];
        $reg_email = $_POST["reg_email"];
        $reg_password = $_POST["reg_password"];
        $reg_password2 = $_POST["reg_password2"];
        $reg_date = $_POST["reg_date"];
        $reg_phone = $_POST["reg_tel"];
        $tests = array(ValidateLogin($reg_login), ValidatePassword($reg_password, $reg_login), ValidatePassword2($reg_password2, $reg_password), ValidateEmail($reg_email), ValidateDate($reg_date),ValidateRegulations((isset($_POST["reg_regulations"]))?("true"):("false")), ValidateCaptcha());
        foreach($tests as $i)
        {
            if(!$i["passed"])
            {
                $success = false;
                $_SESSION["reg_error_".$i["parameter"]] = $i["note"];
            }               
        }  
        $_SESSION["remember_login"] = $reg_login;
        $_SESSION["remember_email"] = $reg_email;
        $_SESSION["remember_date"] = $reg_date;
        $_SESSION["remember_phone"] = $reg_phone;                
        if($success)
        {
            $pass_hash = password_hash($reg_password, PASSWORD_DEFAULT);
            require "connect.php";
            $connect = new mysqli($host, $db_user, $db_password, $db_name);
            $connect->set_charset('utf8mb4');
            $query = $connect->prepare("INSERT INTO uzytkownik(nazwa_uzytkownika, haslo, email, data_urodzenia, numer_telefonu, czy_zalogowany, stanowisko_id) VALUES(?, ?, ?, ?, ?, 1, 1)");
            $query->bind_param('sssss', $reg_login, $pass_hash, $reg_email, $reg_date, $reg_phone);
            $query->execute();
            
            unset($_SESSION["remember_login"]);
            unset($_SESSION["remember_email"]);
            unset($_SESSION["remember_date"]);
            unset($_SESSION["remember_phone"]);
            $_SESSION["logged"] = true;
            $_SESSION["user_id"] = mysqli_insert_id($connect);
            $_SESSION["username"] = $reg_login;
            $_SESSION["position"] = 1;
            $_SESSION["user_data"] = array(
                "user_id" => mysqli_insert_id($connect),
                "username" => $reg_login,
                "email" => $reg_email,
                "birth_date" => $reg_date,
                "phone_number" => $reg_phone,
                "first_name" => null,
                "surname" => null,
                "postcode" => null,
                "city" => null,
                "street" => null,
                "house_number" => null,
                "position" => 1
            );
            $connect->close();
            header("Location: index.php");
            exit();
        }
    }*/
?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8">
    <meta name="description" content="Znajdziesz tu tysiące atrakcyjnych i dobrze płatnych ofert pracy od sprawdzonych pracodawców z renomowanych firm w kraju i za granicą. Jeżeli szukasz pracy, ten serwis jest w sam raz dla Ciebie. Zapraszamy!">
    <meta name="keywords" content="praca, oferty, ogłoszenia, system">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Mateusz Marmuźniak">
    <title>Rejestracja | System ogłoszeniowy Vistaaa</title>
    <base href="https://127.0.0.1/Vistaaa/">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="img/vistaaa_small_logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://www.google.com/recaptcha/api.js?render=6LfiUfknAAAAAKF3I0Lw4sYPLhNeU2eEhLFvtd9C"></script>
</head>
<body id="registrationBackground" class="overflow-x-hidden">
   <?php
        header('Content-Type: text/html; charset=utf-8');
        $pageName = "Rejestracja";
        include "header.php";
   ?>   
    <main class="container-lg d-grid align-items-center">
        <article id="registrationForm" class="p-3 text-center bg-white rounded-2 shadow-lg col-12 col-md-9 col-lg-7 mx-auto">
            <?php
                if(isset($_SESSION["reg_error_captcha"]))
                {
                    echo "<div class='alert alert-danger font-weight-bold shadow'>Weryfikacja za pomocą systemu reCAPTCHA nie powiodła się. Prosimy spróbować ponownie.<br><small>Uwaga: Być może korzystasz z nieobsługiwanej przeglądarki. <a href='https://support.google.com/recaptcha/answer/6223828?hl=pl' target='_blank'>Dowiedz się więcej</a></small></div>";           
                    unset($_SESSION["reg_error_captcha"]);
                }
            ?>
            <section class='d-flex justify-content-end'>
                <label for="companyRegistrationSwitch" class="me-2">Konto dla firmy</label>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="companyRegistrationSwitch" <?php echo ((isset($_GET["type"]) && $_GET["type"] == "company") ? "checked" : "") ?>>
                </div>
            </section>
            <form action="registration" method="POST"> 
                <div class="position-relative formInput mt-3">                       
                    <input type="email" id="reg_email" name="reg_email" minlength="3" maxlength="254" placeholder="E-mail" required class="rounded-4 border border-secondary w-100 py-2 px-3" value="<?php
                    if(isset($_SESSION["remember_reg_email"]))
                    {
                        echo $_SESSION["remember_reg_email"];
                        unset($_SESSION["remember_reg_email"]);
                    }?>">
                    <label for="email" class="position-absolute">E-mail</label>
                </div>
                <div>
                    <?php
                        if(isset($_SESSION["reg_error_email"]))
                        {
                            echo "<div class='invalid-tooltip'>".$_SESSION["reg_error_email"]."</div>";
                            unset($_SESSION["reg_error_email"]);
                        }
                    ?>
                </div> 
                <div class="position-relative formInput input-group mt-3">     
                    <input type="password" id="reg_password" name="reg_password" minlength="8" maxlength="255" placeholder="Hasło" required class="w-100 rounded-4 border border-secondary py-2 px-3">                       
                    <button type="button" class="passwordToggler position-absolute h-100 end-0 px-3 btn btn-primary rounded-end-4" title="Pokaż znaki" data-bs-toggle="tooltip"><span class="bi bi-eye-fill"></span></button>
                    <label for="reg_password" class="position-absolute">Hasło</label>                                                  
                </div>
                <div>
                    <?php
                        if(isset($_SESSION["reg_error_password"]))
                        {
                            echo "<div class='invalid-tooltip'>".$_SESSION["reg_error_password"]."</div>";
                            unset($_SESSION["reg_error_password"]);
                        }
                    ?>
                </div>
                <div class="position-relative formInput input-group mt-3">     
                    <input type="password" id="reg_password2" name="reg_password2" minlength="8" maxlength="255" placeholder="Powtórz hasło" required class="w-100 rounded-4 border border-secondary py-2 px-3">                       
                    <button type="button" class="passwordToggler position-absolute h-100 end-0 px-3 btn btn-primary rounded-end-4" title="Pokaż znaki" data-bs-toggle="tooltip"><span class="bi bi-eye-fill"></span></button>
                    <label for="reg_password2" class="position-absolute">Powtórz hasło</label>                                                  
                </div>
                <div>
                    <?php
                        if(isset($_SESSION["reg_error_password2"]))
                        {
                            echo "<div class='invalid-tooltip'>".$_SESSION["reg_error_password2"]."</div>";
                            unset($_SESSION["reg_error_password2"]);
                        }
                    ?>
                </div>
                <div class="position-relative formInput mt-3">                       
                    <input type="date" id="reg_birth" name="reg_birth" required class="rounded-4 border border-secondary w-100 py-2 px-3" value="<?php
                    if(isset($_SESSION["remember_reg_birth"]))
                    {
                        echo $_SESSION["remember_reg_birth"];
                        unset($_SESSION["remember_breg_irth"]);
                    }?>">
                    <label for="email" class="position-absolute">Data urodzenia</label>
                </div>
                <div>
                    <?php
                        if(isset($_SESSION["reg_error_birth"]))
                        {
                            echo "<div class='invalid-tooltip'>".$_SESSION["reg_error_birth"]."</div>";
                            unset($_SESSION["reg_error_birth"]);
                        }
                    ?>
                </div>         
                <div class="my-3">
                    <div>
                        <input type="checkbox" name="reg_regulations" id="reg_regulations" class="me-2" required>                  
                        <label for="reg_regulations">Oświadczam, że znam i akceptuję postanowienia serwisu Vistaaa.</label>   
                    </div>                   
                    <div>
                        <?php
                            if(isset($_SESSION["reg_error_regulations"]))
                            {
                                echo "<div class='invalid-tooltip'>".$_SESSION["reg_error_regulations"]."</div>";
                                unset($_SESSION["reg_error_regulations"]);
                            }
                        ?>
                    </div>
                </div>                                             
                <button type="submit" id="registrationButton" class="g-recaptcha commonButton" data-size="invisible" data-badge="left" data-sitekey="6LfiUfknAAAAAKF3I0Lw4sYPLhNeU2eEhLFvtd9C" data-callback='OnSubmit' data-action='submit'><i class='bi bi-person-badge me-2'></i> Zarejestruj się</button>
            </form>
            <hr>
            <strong>Masz już konto? </strong><span id="openNavbarSpan" role="button" class='ms-2 badge text-bg-success fs-6 rounded-pill'>Zaloguj się</span>        
        </article>
    </main>
    <?php
        include "footer.php";
    ?>
    <script>   
        document.querySelectorAll(".passwordToggler").forEach(function(el){
            el.addEventListener("click", function(){
                const tooltip = bootstrap.Tooltip.getInstance(this);
                if(this.parentElement.children[0].type == "password")
                {
                    this.parentElement.children[0].type = "text";
                    tooltip.setContent({'.tooltip-inner' : "Ukryj znaki"});
                    this.innerHTML = "<span class='bi bi-eye-slash-fill'></span>";
                }
                else
                {
                    this.parentElement.children[0].type = "password";
                    tooltip.setContent({'.tooltip-inner' : "Pokaż znaki"});
                    this.innerHTML = "<span class='bi bi-eye-fill'></span>";
                }
            });
        });
        document.querySelector("#openNavbarSpan").addEventListener("click", () => bsOffcanvas.show());
        document.querySelector("#companyRegistrationSwitch").addEventListener("click", function(){
            if(this.checked)
                window.location.href = "./registration.php?type=company";
            else
                window.location.href = "./registration.php";
        });
        async function Validate(element)
        {
            const sendData = new FormData();
            sendData.append("property", element.id.replace("reg_", ""));
            if(element.id == "reg_regulations")
                sendData.append("q", element.checked);
            else
                sendData.append("q", element.value);
            if(element.id == "reg_password2")
                sendData.append("tmp", document.querySelector("#reg_password").value);
            else if(element.id == "reg_password")
                sendData.append("tmp", document.querySelector("#reg_email").value);
            const response = await fetch("fetch/validation.php", {
                method: "POST",
                body: sendData
            });
            const result = await response.text();
            console.log(result);
            if(result.lastIndexOf("<div class='text-danger'>") == 0)
            {
                element.parentElement.nextElementSibling.innerHTML = result;
                element.classList.remove("valid");
                element.classList.add("invalid");
            }
            else
            {
                element.parentElement.nextElementSibling.innerHTML = "";
                element.classList.remove("invalid");
                element.classList.add("valid");
            }
        }
        ["keyup", "change", "input"].forEach(function(event){
            document.querySelectorAll(":is(#reg_email, #reg_password, #reg_password2, #reg_birth, #reg_regulations)").forEach(function(el){
                el.addEventListener(event, function(){
                    Validate(this);
                });
            });
        });
        /*$("#login, #password, #password2, #email, #date, #regulations").on("keyup change input", function(){         
            var element = this;
            var data = new Object();
            data.property = element.id;
            if(element.id == "regulations")
                data.q = element.checked;
            else
                data.q = element.value;
            if(element.id == "password2")
                data.tmp = document.querySelector("#password").value;
            else if(element.id == "password")
                data.tmp = document.querySelector("#login").value;
            $.ajax({
            method: "POST",
            url: "test.php",
            data: data,
            success: 
            function(result){
                if(result.lastIndexOf("<div class='invalid-tooltip'>") == 0)
                {
                    $("#" + element.id + " ~ div:last-of-type").html(result);
                    $(element).removeClass("valid").addClass("invalid");
                }
                else
                {
                    $("#" + element.id + " ~ div:last-of-type").html("");
                    $(element).removeClass("invalid").addClass("valid");
                }
            }});
            if(element.id == "password" && document.querySelector("#password2").value)
                $("#password2").keyup();
            if(element.id == "login" && document.querySelector("#password").value)
                $("#password").keyup();
        });*/
        function OnSubmit(token)
        {
            if(document.querySelector("main form").checkValidity())
                document.querySelector("main form").submit();
            else
                document.querySelector("main form").reportValidity();
        }
    </script>
</body>
</html>