<?php
    //error_reporting(0);
    session_start();
    if(isset($_SESSION["logged"]) && $_SESSION["logged"])
    {
        header("Location: ./");
        exit();
    }
    $isCompany = false;
    if(isset($_GET["type"]) && $_GET["type"] == "company")
        $isCompany = true;
    require "functions.php";
    if(isset($_POST["reg_email"]))
    {
        $success = true;
        $reg_email = $_POST["reg_email"];
        $reg_password = $_POST["reg_password"];
        $reg_password2 = $_POST["reg_password2"];
        if($isCompany)
        {
            $reg_name = $_POST["reg_name"];
            $reg_street = $_POST["reg_street"];
            $reg_number = $_POST["reg_number"];
            $reg_postcode = $_POST["reg_postcode"];
            $reg_city = $_POST["reg_city"];
            $tests = array(ValidateName($reg_name), ValidateStreet($reg_street), ValidateNumber($reg_number), ValidatePostcode($reg_postcode), ValidateCity($reg_city), ValidateEmail($reg_email), ValidatePassword($reg_password, $reg_email), ValidatePassword2($reg_password2, $reg_password), ValidateRegulations((isset($_POST["reg_regulations"]))?("true"):("false")), ValidateCaptcha());
        }
        else
        {
            $reg_birth = $_POST["reg_birth"];
            $tests = array(ValidateEmail($reg_email), ValidatePassword($reg_password, $reg_email), ValidatePassword2($reg_password2, $reg_password), ValidateBirth($reg_birth), ValidateRegulations((isset($_POST["reg_regulations"]))?("true"):("false")), ValidateCaptcha());
        }
        foreach($tests as $i)
        {
            if(!$i["passed"])
            {
                $success = false;
                $_SESSION["reg_error_".$i["parameter"]] = $i["note"];
            }               
        }  
        $_SESSION["remember_reg_email"] = $reg_email;
        if($isCompany)
        {
            $_SESSION["remember_reg_name"] = $reg_name;
            $_SESSION["remember_reg_street"] = $reg_street;
            $_SESSION["remember_reg_number"] = $reg_number;
            $_SESSION["remember_reg_postcode"] = $reg_postcode;
            $_SESSION["remember_reg_city"] = $reg_city;
        }
        else
            $_SESSION["remember_reg_birth"] = $reg_birth;            
        if($success)
        {
            $pass_hash = password_hash($reg_password, PASSWORD_DEFAULT);
            require "connect.php";
            $connect = new mysqli($host, $db_user, $db_password, $db_name);
            $connect->set_charset('utf8mb4');
            if($isCompany)
                $connect->execute_query("INSERT INTO company(email, password, name, description, street, number, city, postcode) VALUES(?, ?, ?, ?, ?, ?, ?, ?)", [$reg_email, $pass_hash, $reg_name, null, $reg_street, $reg_number, $reg_city, $reg_postcode]);
            else
                $connect->execute_query("INSERT INTO user(name, surname, email, password, date_of_birth, phone, street, home_number, city, postcode, position, experience, is_admin) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [null, null, $reg_email, $pass_hash, $reg_birth, null, null, null, null, null, null, null, 0]);           
            unset($_SESSION["remember_email"]);
            if($isCompany)
            {
                unset($_SESSION["remember_reg_name"]);
                unset($_SESSION["remember_reg_street"]);
                unset($_SESSION["remember_reg_number"]);
                unset($_SESSION["remember_reg_postcode"]);
                unset($_SESSION["remember_reg_city"]);
            }
            else
                unset($_SESSION["remember_birth"]);
            if($isCompany)
                $result = $connect->execute_query("SELECT * FROM company WHERE email = ?", [$reg_email]);
            else
                $result = $connect->execute_query("SELECT * FROM user WHERE email = ?", [$reg_email]);
            $_SESSION["logged"] = $result->fetch_assoc();
            $connect->close();
            header("Location: ./");
            exit();
        }
    }
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
            <form action=<?php echo "registration.php".($isCompany ? "?type=company" : "")?> method="POST"> 
                <?php
                    if($isCompany)
                    {
                        echo "<div class='position-relative formInput mt-3'>                       
                            <input type='text' id='reg_name' name='reg_name' minlength='3' maxlength='100' placeholder='Nazwa firmy' required class='rounded-4 border border-secondary w-100 py-2 px-3' value='";
                        if(isset($_SESSION["remember_reg_name"]))
                        {
                            echo $_SESSION["remember_reg_name"];
                            unset($_SESSION["remember_reg_name"]);
                        }
                        echo "'>
                            <label for='reg_name' class='position-absolute'>Nazwa firmy</label>";
                        echo "</div>
                            <div>";
                        if(isset($_SESSION["reg_error_name"]))
                        {
                            echo "<div class='text-danger'>".$_SESSION["reg_error_name"]."</div>";
                            unset($_SESSION["reg_error_name"]);
                        }
                        echo "</div>";
                    }
                ?>
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
                            echo "<div class='text-danger'>".$_SESSION["reg_error_email"]."</div>";
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
                            echo "<div class='text-danger'>".$_SESSION["reg_error_password"]."</div>";
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
                            echo "<div class='text-danger'>".$_SESSION["reg_error_password2"]."</div>";
                            unset($_SESSION["reg_error_password2"]);
                        }
                    ?>
                </div>
                <?php
                    if(!$isCompany)
                    {
                        echo "<div class='position-relative formInput mt-3'>                       
                            <input type='date' id='reg_birth' name='reg_birth' required class='rounded-4 border border-secondary w-100 py-2 px-3' value='";
                        if(isset($_SESSION["remember_reg_birth"]))
                        {
                            echo $_SESSION["remember_reg_birth"];
                            unset($_SESSION["remember_reg_birth"]);
                        }
                        echo "'>
                            <label for='email' class='position-absolute'>Data urodzenia</label>
                        </div>
                        <div>";
                        if(isset($_SESSION["reg_error_birth"]))
                        {
                            echo "<div class='text-danger'>".$_SESSION["reg_error_birth"]."</div>";
                            unset($_SESSION["reg_error_birth"]);
                        }
                        echo "</div>";
                    }
                    else
                    {
                        echo "<div class='row'>";
                        echo "<div class='col-12 col-md-6'>";
                        echo "<div class='position-relative formInput mt-3'>";
                        echo "<input type='text' id='reg_street' name='reg_street' minlength='3' maxlength='100' placeholder='Ulica' required class='rounded-4 border border-secondary w-100 py-2 px-3' value='";
                        if(isset($_SESSION["remember_reg_street"]))
                        {
                            echo $_SESSION["remember_reg_street"];
                            unset($_SESSION["remember_reg_street"]);
                        }
                        echo "'>
                            <label for='reg_street' class='position-absolute'>Ulica</label>
                        </div>
                        <div>";
                        if(isset($_SESSION["reg_error_street"]))
                        {
                            echo "<div class='text-danger'>".$_SESSION["reg_error_street"]."</div>";
                            unset($_SESSION["reg_error_street"]);
                        }
                        echo "</div>";
                        echo "</div>";
                        echo "<div class='col-12 col-md-6'>";
                        echo "<div class='position-relative formInput mt-3'>";
                        echo "<input type='text' id='reg_number' name='reg_number' maxlength='10' placeholder='Numer budynku' required class='rounded-4 border border-secondary w-100 py-2 px-3' value='";
                        if(isset($_SESSION["remember_reg_number"]))
                        {
                            echo $_SESSION["remember_reg_number"];
                            unset($_SESSION["remember_reg_number"]);
                        }
                        echo "'>
                            <label for='reg_number' class='position-absolute'>Numer budynku</label>
                        </div>
                        <div>";
                        if(isset($_SESSION["reg_error_number"]))
                        {
                            echo "<div class='text-danger'>".$_SESSION["reg_error_number"]."</div>";
                            unset($_SESSION["reg_error_number"]);
                        }
                        echo "</div>";
                        echo "</div>";
                        echo "<div class='col-12 col-md-6'>";
                        echo "<div class='position-relative formInput mt-3'>";
                        echo "<input type='text' id='reg_postcode' name='reg_postcode' pattern='[0-9]{2}-[0-9]{3}' title='Proszę wpisać poprawny kod pocztowy.' placeholder='Kod pocztowy' required class='rounded-4 border border-secondary w-100 py-2 px-3' value='";
                        if(isset($_SESSION["remember_reg_postcode"]))
                        {
                            echo $_SESSION["remember_reg_postcode"];
                            unset($_SESSION["remember_reg_postcode"]);
                        }
                        echo "'>
                            <label for='reg_postcode' class='position-absolute'>Kod pocztowy</label>
                        </div>
                        <div>";
                        if(isset($_SESSION["reg_error_postcode"]))
                        {
                            echo "<div class='text-danger'>".$_SESSION["reg_error_postcode"]."</div>";
                            unset($_SESSION["reg_error_postcode"]);
                        }
                        echo "</div>";
                        echo "</div>";
                        echo "<div class='col-12 col-md-6'>";
                        echo "<div class='position-relative formInput mt-3'>";
                        echo "<input type='text' id='reg_city' name='reg_city' maxlength='50' placeholder='Miejscowość' required class='rounded-4 border border-secondary w-100 py-2 px-3' value='";
                        if(isset($_SESSION["remember_reg_city"]))
                        {
                            echo $_SESSION["remember_reg_city"];
                            unset($_SESSION["remember_reg_city"]);
                        }
                        echo "'>
                            <label for='reg_city' class='position-absolute'>Miejscowość</label>
                        </div>
                        <div>";
                        if(isset($_SESSION["reg_error_city"]))
                        {
                            echo "<div class='text-danger'>".$_SESSION["reg_error_city"]."</div>";
                            unset($_SESSION["reg_error_city"]);
                        }
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                ?>      
                <div class="my-3">
                    <div>
                        <input type="checkbox" name="reg_regulations" id="reg_regulations" class="me-2" required>                  
                        <label for="reg_regulations">Oświadczam, że znam i akceptuję postanowienia serwisu Vistaaa.</label>   
                    </div>                   
                    <div>
                        <?php
                            if(isset($_SESSION["reg_error_regulations"]))
                            {
                                echo "<div class='text-danger'>".$_SESSION["reg_error_regulations"]."</div>";
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
            document.querySelectorAll(":is(#reg_email, #reg_password, #reg_password2, #reg_birth, #reg_regulations, #reg_name, #reg_street, #reg_number, #reg_city, #reg_postcode)").forEach(function(el){
                el.addEventListener(event, function(){
                    Validate(this);
                });
            });
        });
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