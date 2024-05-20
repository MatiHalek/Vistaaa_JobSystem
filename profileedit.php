<?php
	//error_reporting(0);
	session_start();
	if(!isset($_SESSION["logged"]) || (array_key_exists("company_id", $_SESSION["logged"])))
        header("Location: index.php");
    if(isset($_POST["mode"]) && $_POST["mode"] == "edit")
    {
        $errorMessages = array();
        $success = true;
        function ValidateFile($name, $size, $tmp_name)
        {
            global $errorMessages;
            $extensions = array("png", "jpg", "jpeg", "gif", "bmp", "tiff");
            $tmp = explode(".", $name);
            $file_ext = strtolower(end($tmp));         
            if($size > 2097152)
                array_push($errorMessages, "Plik jest za duży: maksymalny rozmiar pliku wynosi 2 MB.");           
            if(!in_array($file_ext, $extensions))
                array_push($errorMessages, "Błędne rozszerzenie pliku: wybierz plik graficzny o rozszerzeniu .png, .jpg, .jpeg, .gif, .bmp lub .tiff.");
            if(count($errorMessages) == 0)
            {
                $info = getimagesize($tmp_name);
                list($width, $height) = $info;
                if($width < 50)
                    array_push($errorMessages, "Szerokość obrazu jest za mała ($width px): powinna wynosić minimum 50 pikseli.");
                if($height < 50)
                {
                    array_push($errorMessages, "Wysokość obrazu jest za mała ($height px): powinna wynosić minimum 50 pikseli.");
                } 
            }
            if(count($errorMessages) > 0)
                return false;             
            return true;  
        }
        require "connect.php";
        $connect = new mysqli($host, $db_user, $db_password, $db_name);
        $connect->set_charset("utf8mb4");
        try
        {
            $connect->begin_transaction();
            if(is_uploaded_file($_FILES["profile_picture"]["tmp_name"]))
            {
                if(!ValidateFile($_FILES["profile_picture"]["name"], $_FILES["profile_picture"]["size"], $_FILES["profile_picture"]["tmp_name"]))
                    throw new Exception();
                if(!is_dir("img/user/".$_SESSION["logged"]["user_id"]."/"))
                    mkdir("img/user/".$_SESSION["logged"]["user_id"]."/");
                array_map("unlink", glob("img/user/".$_SESSION["logged"]["user_id"]."/*"));        
                $file_name = $_FILES["profile_picture"]["name"];
                $tmp_name = $_FILES["profile_picture"]["tmp_name"];
                $target_dir = "img/user/".$_SESSION["logged"]["user_id"]."/";
                move_uploaded_file($tmp_name, $target_dir.$file_name);
            }
            $connect->execute_query("UPDATE user SET name = ?, surname = ?, email = ?, position = ?, experience = ?, street = ?, home_number = ?, postcode = ?, city = ? WHERE user_id = ?;", [$_POST["profile_name"], $_POST["profile_surname"], $_POST["profile_email"], $_POST["profile_position"], $_POST["profile_experience"], $_POST["profile_street"], $_POST["profile_number"], $_POST["profile_postcode"], $_POST["profile_city"], $_SESSION["logged"]["user_id"]]);
            $connect->execute_query("DELETE FROM user_link WHERE user_id = ?;", [$_SESSION["logged"]["user_id"]]);
            $portalResult = $connect->execute_query("SELECT * FROM portal");
            while($portalRow = $portalResult->fetch_assoc())
            {
                if(isset($_POST["profile_link_".$portalRow["name"]]) && $_POST["profile_link_".$portalRow["name"]] != "")
                {
                    $connect->execute_query("INSERT INTO user_link(user_id, portal_id, link) VALUES(?, ?, ?);", [$_SESSION["logged"]["user_id"], $portalRow["portal_id"], $_POST["profile_link_".$portalRow["name"]]]);
                }
            }
            $connect->execute_query("DELETE FROM user_position WHERE user_id = ?;", [$_SESSION["logged"]["user_id"]]);
            if(isset($_POST["profile_experiences_dates_from"]))
            {
                for($i = 0; $i < count($_POST["profile_experiences_dates_from"]); $i++)
                {
                    $connect->execute_query("INSERT INTO user_position(user_id, company_id, position, description, date_start, date_end) VALUES(?, ?, ?, ?, ?, ?);", [$_SESSION["logged"]["user_id"], $_POST["profile_experiences_companies"][$i], $_POST["profile_experiences_positions"][$i], null, $_POST["profile_experiences_dates_from"][$i], (empty($_POST["profile_experiences_dates_to"][$i]) ? null : $_POST["profile_experiences_dates_to"][$i])]);
                }
            }           
            $connect->execute_query("DELETE FROM user_education WHERE user_id = ?;", [$_SESSION["logged"]["user_id"]]);          
            if(isset($_POST["profile_educations_dates_from"]))
            {
                for($i = 0; $i < count($_POST["profile_educations_dates_from"]); $i++)
                {
                    $checkingForSchoolResult = $connect->execute_query("SELECT * FROM school WHERE name = ? AND city = ?;", [$_POST["profile_educations_names"][$i], $_POST["profile_educations_cities"][$i]]);
                    if($checkingForSchoolResult->num_rows == 0)
                    {
                        $connect->execute_query("INSERT INTO school(name, city) VALUES(?, ?);", [$_POST["profile_educations_names"][$i], $_POST["profile_educations_cities"][$i]]);
                        $schoolId = $connect->insert_id;
                    }
                    else
                    {
                        $schoolRow = $checkingForSchoolResult->fetch_assoc();
                        $schoolId = $schoolRow["school_id"];
                    }
                    $connect->execute_query("INSERT INTO user_education(user_id, school_id, level, field, date_start, date_end) VALUES(?, ?, ?, ?, ?, ?);", [$_SESSION["logged"]["user_id"], $schoolId,  $_POST["profile_educations_levels"][$i], (empty($_POST["profile_educations_fields"][$i]) ? null : $_POST["profile_educations_fields"][$i]), $_POST["profile_educations_dates_from"][$i], (empty($_POST["profile_educations_dates_to"][$i]) ? null : $_POST["profile_educations_dates_to"][$i])]);
                }
            }
            $connect->execute_query("DELETE FROM user_language WHERE user_id = ?;", [$_SESSION["logged"]["user_id"]]);
            if(isset($_POST["profile_languages"]))
            {
                for($i = 0; $i < count($_POST["profile_languages"]); $i++)
                {
                    $connect->execute_query("INSERT INTO user_language(user_id, language_id, level) VALUES(?, ?, ?);", [$_SESSION["logged"]["user_id"], $_POST["profile_languages"][$i], $_POST["profile_languages_values"][$i]]);
                }
            }
            $connect->execute_query("DELETE FROM user_skill WHERE user_id = ?;", [$_SESSION["logged"]["user_id"]]);
            if(isset($_POST["profile_skills"]))
            {
                for($i = 0; $i < count($_POST["profile_skills"]); $i++)
                {
                    $connect->execute_query("INSERT INTO user_skill(user_id, skill) VALUES(?, ?);", [$_SESSION["logged"]["user_id"], $_POST["profile_skills"][$i]]);
                }
            }
            $connect->execute_query("DELETE FROM user_course WHERE user_id = ?;", [$_SESSION["logged"]["user_id"]]);
            if(isset($_POST["profile_courses_dates_from"]))
            {
                for($i = 0; $i < count($_POST["profile_courses_dates_from"]); $i++)
                {
                    $connect->execute_query("INSERT INTO user_course(user_id, name, company_id, date_start, date_end) VALUES(?, ?, ?, ?, ?);", [$_SESSION["logged"]["user_id"], $_POST["profile_courses_names"][$i], $_POST["profile_courses_companies"][$i], $_POST["profile_courses_dates_from"][$i], (empty($_POST["profile_courses_dates_to"][$i]) ? null : $_POST["profile_courses_dates_to"][$i])]);
                }
            }
            $connect->commit();
            $_SESSION["logged"] = ($connect->execute_query('SELECT * FROM user WHERE user_id = ?', [$_SESSION["logged"]["user_id"]]))->fetch_assoc();
            $connect->close();
            header("Location: profile.php?id=".$_SESSION["logged"]["user_id"]);
            exit();
        }
        catch(Exception $e)
        {
            $success = false;
            $connect->rollback();
            $connect->close();
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
    <title>Edytowanie profilu | System ogłoszeniowy Vistaaa</title>
    <base href="https://127.0.0.1/Vistaaa/">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="img/vistaaa_small_logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
	<?php
		header('Content-Type: text/html; charset=utf-8');
		$pageName = "Edytowanie profilu"; 
		include "header.php";
		echo "<main class='position-relative'>";
		require "connect.php";
		$connect = new mysqli($host, $db_user, $db_password, $db_name);
		$connect->set_charset("utf8mb4");		
        $result = $connect->execute_query("SELECT * FROM user WHERE user_id = ?;", [$_SESSION["logged"]["user_id"]]);
        $row = $result->fetch_assoc();
		$user_id = $row["user_id"];
		echo "<form action='profileedit.php' method='post' enctype='multipart/form-data'>";
        if(isset($_POST["mode"]) && $_POST["mode"] == "edit" && !$success)
        {
            echo "<div class='alert alert-danger m-2 mt-0 shadow' role='alert'>";
            echo "<strong>Wystąpił błąd podczas zapisywania zmian. Może to wynikać z problemów po naszej stronie lub niepoprawnych danych. Sprawdź wpisywane dane i spróbuj ponownie.</strong>";
            if(count($errorMessages) > 0)
            {
                echo "<p>Dodatkowe informacje o błędzie z naszego serwera:</p>";
                echo "<ul class='mb-0'>";
                foreach($errorMessages as $message)
                    echo "<li>".$message."</li>";
                echo "</ul>";
            }
            echo "</div>";
        }
		echo "<div class='profileHeader m-2 rounded'>";
		echo "<div class='container-fluid flex-wrap p-2 d-flex justify-content-center align-items-center'>";
		$path = './img/user/'.$user_id.'/';
        if(is_dir($path) && scandir($path))
			$files = array_diff(scandir($path), array(".", "..", "default")); 
		else
			$files = array();
        echo "<div class='d-flex flex-column align-items-center text-center' id='profilePictureEdit'>";
        echo "<label class='position-relative' title='Zmień zdjęcie profilowe' data-bs-toggle='tooltip'>";
        echo "<input type='file' name='profile_picture' class='d-none' accept='image/*'>";
        echo "<img id='profilePicture' class='bg-white' src='".(count($files) > 0 ? ($path.scandir($path)[2]) : "./img/user.png")."' alt='Profil' width='100' height='100'>";
        echo "</label>";
        echo "<p class='text-white fw-bold text-break text-center mt-2 mb-0' id='uploadedFileName'></p>";
        echo "</div>";
        echo "<div class='m-2 flex-grow-1'>";
        echo "<div class='position-relative formInput mt-3'>                       
                <input type='text' id='profile_name' name='profile_name' maxlength='30' placeholder='Imię' class='rounded-4 border-0 w-100 py-2 px-3' value='".(isset($_SESSION["remember_profile_name"]) ? htmlspecialchars($_SESSION["remember_profile_name"]) : htmlspecialchars($row["name"]))."'>
                <label for='profile_name' class='position-absolute'>Imię</label>
        </div>";
        echo "<div class='position-relative formInput mt-3'>                       
            <input type='text' id='profile_surname' name='profile_surname' maxlength='50' placeholder='Nazwisko' class='rounded-4 border-0 w-100 py-2 px-3' value='".(isset($_SESSION["remember_profile_surname"]) ? htmlspecialchars($_SESSION["remember_profile_surname"]) : htmlspecialchars($row["surname"]))."'>
            <label for='profile_surname' class='position-absolute'>Nazwisko</label>
        </div>";
        echo "<div class='position-relative formInput mt-3'>                       
                <input type='email' id='profile_email' name='profile_email' maxlength='254' required placeholder='Email' class='rounded-4 border-0 w-100 py-2 px-3' value='".(isset($_SESSION["remember_profile_email"]) ? htmlspecialchars($_SESSION["remember_profile_email"]) : htmlspecialchars($row["email"]))."'>
                <label for='profile_email' class='position-absolute'>Email</label>
        </div>";
        echo "<div class='position-relative formInput mt-3'>                       
                <input type='text' id='profile_position' name='profile_position' maxlength='50' placeholder='Stanowisko' class='rounded-4 border-0 w-100 py-2 px-3' value='".(isset($_SESSION["remember_profile_position"]) ? htmlspecialchars($_SESSION["remember_profile_position"]) : htmlspecialchars($row["position"]))."'>
                <label for='profile_position' class='position-absolute'>Stanowisko</label>
        </div>";
        echo "</div></div>";
        $portalResult = $connect->execute_query("SELECT * FROM portal");
        echo "<div class='profileLinks p-2 pb-1 rounded'>";
        echo "<div class='container'>";
        while($portalRow = $portalResult->fetch_assoc())
        {
            echo "<div class='row mb-2 align-items-center'>";
            echo "<div class='col-6'>";
            echo "<p class='d-flex fs-5 mb-1'><i class='bi bi-".mb_strtolower($portalRow["name"])." me-2'></i> ".$portalRow["name"]."</p>";
            echo "</div>";
            echo "<div class='col-6'>";
            $linkresult = $connect->execute_query("SELECT * FROM user_link WHERE user_id = ? AND portal_id = ?;", [$user_id, $portalRow["portal_id"]]);
            $defaultvalue = "";
            if($linkresult->num_rows > 0)
            {
                $linkRow = $linkresult->fetch_assoc();
                $defaultvalue = $linkRow["link"];
            }
            echo "<input type='url' name='profile_link_".$portalRow["name"]."' class='form-control rounded-4' placeholder='Link do profilu' value='".(isset($_SESSION["remember_profile_link_".$portalRow["name"]]) ? htmlspecialchars($_SESSION["remember_profile_link_".$portalRow["name"]]) : htmlspecialchars($defaultvalue))."' maxlength='30'>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";					
        echo "</div>";												
        echo "</div>";
        echo "<div class='container pt-4'>";
        echo "<section class='p-2'>";			
        echo "<div class='position-relative formInput mt-3'>";                  
        echo "<textarea minlength='5' maxlength='1000' rows='4' id='profile_experience' name='profile_experience' placeholder='Podsumowanie zawodowe' class='rounded-4 border-0 w-100 py-2 px-3'>";
        if(isset($_SESSION["remember_profile_experience"]))
            echo htmlspecialchars($_SESSION["remember_profile_experience"]);
        else
            echo htmlspecialchars($row["experience"]);
        echo "</textarea><label for='profile_experience' class='position-absolute'>Podsumowanie zawodowe</label>";
        echo "</div>";
        echo "</section>";
        echo "</div><hr>";
        echo "<div class='container'>";
        echo "<section class='p-2'>";
        echo "<h4>Adres zamieszkania <span class='bi bi-eye-slash-fill ms-2' data-bs-toggle='tooltip' title='Mając na uwadze Twoją prywatność, ta część profilu jest widoczna tylko dla Ciebie i dla pracodawców, którym zgodziłeś się przekazać te dane podczas aplikowania.'></span></h4>";
        echo "<div class='row'>";
        echo "<div class='col-12 col-md-6'>";
        echo "<div class='position-relative formInput mt-3'>";
        echo "<input type='text' id='profile_street' name='profile_street' minlength='3' maxlength='100' placeholder='Ulica' class='rounded-4 border-0 w-100 py-2 px-3' value='".(isset($_SESSION["remember_profile_street"]) ? htmlspecialchars($_SESSION["remember_profile_street"]) : htmlspecialchars($row["street"]))."'>
            <label for='profile_street' class='position-absolute'>Ulica</label>
        </div>";
        echo "</div>";
        echo "<div class='col-12 col-md-6'>";
        echo "<div class='position-relative formInput mt-3'>";
        echo "<input type='text' id='profile_number' name='profile_number' maxlength='10' placeholder='Numer budynku' class='rounded-4 border-0 w-100 py-2 px-3' value='".(isset($_SESSION["remember_profile_home_number"]) ? htmlspecialchars($_SESSION["remember_profile_home_number"]) : htmlspecialchars($row["home_number"]))."'>
            <label for='profile_number' class='position-absolute'>Numer budynku</label>
        </div>";
        echo "</div>";
        echo "<div class='col-12 col-md-6'>";
        echo "<div class='position-relative formInput mt-3'>";
        echo "<input type='text' id='profile_postcode' name='profile_postcode' pattern='[0-9]{2}-[0-9]{3}' title='Proszę wpisać poprawny kod pocztowy.' placeholder='Kod pocztowy' class='rounded-4 border-0 w-100 py-2 px-3' value='".(isset($_SESSION["remember_profile_postcode"]) ? htmlspecialchars($_SESSION["remember_profile_postcode"]) : htmlspecialchars($row["postcode"]))."'>
            <label for='profile_postcode' class='position-absolute'>Kod pocztowy</label>
        </div>";
        echo "</div>";
        echo "<div class='col-12 col-md-6'>";
        echo "<div class='position-relative formInput mt-3'>";
        echo "<input type='text' id='profile_city' name='profile_city' maxlength='50' placeholder='Miejscowość' class='rounded-4 border-0 w-100 py-2 px-3' value='".(isset($_SESSION["remember_profile_city"]) ? htmlspecialchars($_SESSION["remember_profile_city"]) : htmlspecialchars($row["city"]))."'>
            <label for='profile_city' class='position-absolute'>Miejscowość</label>
        </div>";
        echo "</section>";
        echo "</div><hr>";
        echo "<div class='container'>";
        echo "<section class='p-2'>";
        echo "<h4>Doświadczenie zawodowe</h4>";
        $months = array("stycznia", "lutego", "marca", "kwietnia", "maja", "czerwca", "lipca", "sierpnia", "września", "października", "listopada", "grudnia");
        echo "<button type='button' class='commonButton addToProfileButton py-2 px-3' id='addExperienceButton'><i class='bi bi-plus-circle-fill me-2'></i>Dodaj pracę</button>";
        echo "<div id='userExperiences'></div>";			
        echo "</section></div><hr>";
        echo "<div class='container'>";
        echo "<section class='p-2'>";
        echo "<h4>Wykształcenie</h4>";
        echo "<button type='button' class='commonButton addToProfileButton py-2 px-3' id='addEducationButton'><i class='bi bi-plus-circle-fill me-2'></i>Dodaj wykształcenie</button>";
        echo "<div id='userEducations'></div>";
        echo "<datalist id='schoolsList'>";
        $schoolResult = $connect->execute_query("SELECT DISTINCT * FROM school");
        if($schoolResult->num_rows > 0)
        {
            while($schoolRow = $schoolResult->fetch_assoc())
                echo "<option value='".htmlspecialchars($schoolRow["name"])."'>";
        }
        echo "</datalist>";
        echo "<datalist id='citiesList'>";
        $cityResult = $connect->execute_query("SELECT DISTINCT city FROM school");
        if($cityResult->num_rows > 0)
        {
            while($cityRow = $cityResult->fetch_assoc())
                echo "<option value='".htmlspecialchars($cityRow["city"])."'>";
        }
        echo "</datalist>";			
        echo "</section></div><hr>";
        echo "<div class='container'>";
        echo "<section class='p-2'>";
        echo "<h4>Znajomość języków</h4>";
        echo "<button type='button' class='commonButton addToProfileButton py-2 px-3' id='addLanguageButton'><i class='bi bi-plus-circle-fill me-2'></i>Dodaj język</button>";
        echo "<div id='userLanguages'></div>";				
        echo "</section></div><hr>";
        echo "<div class='container'>";
        echo "<section class='p-2'>";
        echo "<h4>Umiejętności</h4>";
        echo "<button type='button' class='commonButton addToProfileButton py-2 px-3' id='addSkillButton'><i class='bi bi-plus-circle-fill me-2'></i>Dodaj umiejętność</button>";
        echo "<div id='userSkills'></div>";			
        echo "</section></div><hr>";
        echo "<div class='container'>";
        echo "<section class='p-2'>";
        echo "<h4>Kursy, szkolenia, certyfikaty</h4>";
        echo "<button type='button' class='commonButton addToProfileButton py-2 px-3' id='addCourseButton'><i class='bi bi-plus-circle-fill me-2'></i>Dodaj kurs/szkolenie</button>";
        echo "<div id='userCourses'></div>";		
		echo "</section></div>";
        echo "<hr><input type='hidden' name='mode' value='edit'><button type='submit' class='successButton d-block mx-auto mt-3'><i class='bi bi-check-circle-fill me-2'></i>Zapisz zmiany</button>";
		echo "</form>";
		echo "</main>";
	?>  			
	<?php
		include "footer.php";
	?>
	<script>
		class Language
		{
			constructor(id, name, level)
			{
				this.id = id;
				this.name = name;
				this.level = level;
			}
		}
		const languages = [];
		const languageValues = ["podstawowy", "średniozaawansowany", "zaawansowany"];
		const educationLevels = ["podstawowe", "średnie", "wyższe"];
		let languageDivsCount = 0;
		let experienceDivsCount = 0;
		let educationDivsCount = 0;
		let courseDivsCount = 0;
		<?php
            $result = $connect->execute_query('SELECT * FROM language');
            if($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc())
                    echo "languages.push(new Language('".htmlspecialchars($row["language_id"])."', '".htmlspecialchars($row["language"])."', ''));";
            }
		?>
		function GetCompaniesSelect(id, forCourses = false, selected = null)
		{
			let companiesSelect = "<select name='profile_" + (forCourses ? "courses" : "experiences") + "_companies[]' class='form-control rounded-4 form-select' id='" + (forCourses ? ("cc" + id) : ("ec" + id)) + "' required><option value='' disabled selected hidden>Wybierz...</option>";
			<?php
                $result = $connect->execute_query('SELECT * FROM company');
                if($result->num_rows > 0)
                {
                    echo "let tmp = null;";
                    while($row = $result->fetch_assoc())
                    {
                        echo "tmp = ".$row["company_id"].";";
                        echo "companiesSelect += \"<option value='".$row["company_id"]."'\" + ((tmp == selected) ? \" selected\" : \"\") + \">".htmlspecialchars($row["name"])."</option>\";";
                    }
                        
                }
			?>
			companiesSelect += "</select>";
			return companiesSelect;
		}
		const oldImage = document.querySelector("#profilePicture").getAttribute("src");
		document.querySelector("input[type='file']")?.addEventListener("change", function(){
		if(this.files[0])
		{
			document.querySelector("#uploadedFileName").textContent = this.files[0].name;
    		document.querySelector("#profilePicture").setAttribute("src", URL.createObjectURL(this.files[0]));
  		}			
		else
		{
			document.querySelector("#profilePicture").setAttribute("src", oldImage);
			document.querySelector("#uploadedFileName").textContent = "";
		}
		});

		function AddExperience(dateFrom = "", dateTo = "", companyId = "", position = "")
		{
			if(document.querySelector("#userExperiences").children.length >= 10)
                return;
			const today = new Date().toISOString().split("T")[0];
			document.querySelector("#userExperiences").innerHTML += `<div class='d-flex align-items-center py-3'><div class='row flex-grow-1'><div class='col-12 col-sm-6 col-lg-3'><div class='position-relative formInput mt-3'><label class='position-absolute' for='edf${experienceDivsCount}'>Data rozpoczęcia</label><input type='date' id='edf${experienceDivsCount}' name='profile_experiences_dates_from[]' min='1900-01-01' max='${today}' class='form-control rounded-4' value='${dateFrom}' required></div></div><div class='col-12 col-sm-6 col-lg-3'><div class='position-relative formInput mt-3'><label class='position-absolute' for='edt${experienceDivsCount}'>Data zakończenia</label><input type='date' value='${dateTo}' id='edt${experienceDivsCount}' name='profile_experiences_dates_to[]' min='1900-01-01' max='${today}' class='form-control rounded-4'></div></div><div class='col-12 col-sm-6 col-lg-3'><div class='position-relative formInput mt-3'><label class='position-absolute' for='ec${experienceDivsCount}'>Firma</label>${((companyId == "") ? GetCompaniesSelect(experienceDivsCount) : GetCompaniesSelect(experienceDivsCount, false, companyId))}</div></div><div class='col-12 col-sm-6 col-lg-3'><div class='position-relative formInput mt-3'><input type='text' maxlength='50' value='${position}' placeholder='Stanowisko' id='ep${experienceDivsCount}' name='profile_experiences_positions[]' class='form-control rounded-4' required><label class='position-absolute' for='ep${experienceDivsCount}'>Stanowisko</label></div></div></div><button class='dangerButton mt-2' type='button' onclick='bootstrap.Tooltip.getInstance(this).dispose();this.parentElement.remove();' data-bs-toggle='tooltip' title='Usuń pracę'><span class='bi bi-trash-fill'></span></button></div>`;
			UpdateTooltips();
			experienceDivsCount++;
		}
		document.querySelector("#addExperienceButton")?.addEventListener("click", () => AddExperience());
		function AddEducation(dateFrom = "", dateTo = "", schoolName = "", city = "", field = "", level = "")
		{
			if(document.querySelector("#userEducations").children.length >= 10)
				return;
			const today = new Date().toISOString().split("T")[0];
			let insertHTML = "";
			insertHTML += `<div class='d-flex align-items-center py-3'><div class='row flex-grow-1'><div class='col-12 col-sm-6 col-lg-4'><div class='position-relative formInput mt-3'><label class='position-absolute' for='sdf${educationDivsCount}'>Data rozpoczęcia</label><input type='date' id='sdf${educationDivsCount}' min='1900-01-01' max='${today}' name='profile_educations_dates_from[]' class='form-control rounded-4' value='${dateFrom}' required></div></div><div class='col-12 col-sm-6 col-lg-4'><div class='position-relative formInput mt-3'><label class='position-absolute' for='sdt${educationDivsCount}'>Data zakończenia</label><input type='date' value='${dateTo}' id='sdt${educationDivsCount}' min='1900-01-01' max='${today}' name='profile_educations_dates_to[]' class='form-control rounded-4'></div></div><div class='col-12 col-sm-6 col-lg-4'><div class='position-relative formInput mt-3'><input type='text' list='schoolsList' value='${schoolName}' placeholder='Nazwa szkoły' id='ss${educationDivsCount}' name='profile_educations_names[]' class='form-control rounded-4' required><label class='position-absolute' for='ss${educationDivsCount}'>Nazwa szkoły</label></div></div><div class='col-12 col-sm-6 col-lg-4'><div class='position-relative formInput mt-3'><input type='text' list='citiesList' value='${city}' placeholder='Miejscowość' id='sc${educationDivsCount}' name='profile_educations_cities[]' class='form-control rounded-4' required><label class='position-absolute' for='sc${educationDivsCount}'>Miejscowość</label></div></div><div class='col-12 col-sm-6 col-lg-4'><div class='position-relative formInput mt-3'><select class='form-control form-select rounded-4' name='profile_educations_levels[]' id='sl${educationDivsCount}' required><option value='' disabled selected hidden>Wybierz...</option>`;
			for(let i = 0; i < educationLevels.length; i++)
				insertHTML += "<option value='" + educationLevels[i] + "'" + ((level == educationLevels[i]) ? " selected" : "") + ">" + educationLevels[i] + "</option>";
			insertHTML += `</select><label class='position-absolute' for='sl${educationDivsCount}'>Poziom wykształcenia</label></div></div><div class='col-12 col-sm-6 col-lg-4'><div class='position-relative formInput mt-3'><input type='text' value='${field}' placeholder='Kierunek' id='sf${educationDivsCount}' name='profile_educations_fields[]' class='form-control rounded-4'><label class='position-absolute' for='sf${educationDivsCount}'>Kierunek</label></div></div></div><button class='dangerButton mt-2' type='button' onclick='bootstrap.Tooltip.getInstance(this).dispose();this.parentElement.remove();' data-bs-toggle='tooltip' title='Usuń wykształcenie'><span class='bi bi-trash-fill'></span></button></div>`;
			document.querySelector("#userEducations").innerHTML += insertHTML;
			UpdateTooltips();
			educationDivsCount++;
		}
		document.querySelector("#addEducationButton")?.addEventListener("click", () => AddEducation());
		function AddCourse(dateFrom = "", dateTo = "", companyId = "", name = "")
		{
			if(document.querySelector("#userCourses").children.length >= 10)
                return;
			const today = new Date().toISOString().split("T")[0];
			document.querySelector("#userCourses").innerHTML += `<div class='d-flex align-items-center py-3'><div class='row flex-grow-1'><div class='col-12 col-sm-6 col-lg-3'><div class='position-relative formInput mt-3'><label class='position-absolute' for='cdf${courseDivsCount}'>Data rozpoczęcia</label><input type='date' id='cdf${courseDivsCount}' min='1900-01-01' max='${today}' name='profile_courses_dates_from[]' class='form-control rounded-4' value='${dateFrom}' required></div></div><div class='col-12 col-sm-6 col-lg-3'><div class='position-relative formInput mt-3'><label class='position-absolute' for='cdt${courseDivsCount}'>Data zakończenia</label><input type='date' value='${dateTo}' id='cdt${courseDivsCount}' name='profile_courses_dates_to[]' min='1900-01-01' max='${today}' class='form-control rounded-4'></div></div><div class='col-12 col-sm-6 col-lg-3'><div class='position-relative formInput mt-3'><label class='position-absolute' for='cc${courseDivsCount}'>Firma</label>${((companyId == "") ? GetCompaniesSelect(experienceDivsCount, true) : GetCompaniesSelect(courseDivsCount, true, companyId))}</div></div><div class='col-12 col-sm-6 col-lg-3'><div class='position-relative formInput mt-3'><input type='text' value='${name}' placeholder='Nazwa' id='cn${courseDivsCount}' name='profile_courses_names[]' class='form-control rounded-4' required><label class='position-absolute' for='cc${courseDivsCount}'>Nazwa</label></div></div></div><button class='dangerButton mt-2' type='button' onclick='bootstrap.Tooltip.getInstance(this).dispose();this.parentElement.remove();' data-bs-toggle='tooltip' title='Usuń kurs/szkolenie'><span class='bi bi-trash-fill'></span></button></div>`;
			UpdateTooltips();
			experienceDivsCount++;
		}
		document.querySelector("#addCourseButton")?.addEventListener("click", () => AddCourse());
		function AddSkill(value = "")
        {
            if(document.querySelector("#userSkills").children.length >= 10)
                return;
            const skillDiv = document.createElement("div")
			skillDiv.className = "d-flex m-2";
            document.querySelector("#userSkills").appendChild(skillDiv);
            const newSkill = document.createElement("input");
            newSkill.type = "text";
            newSkill.name = "profile_skills[]";
            newSkill.className = "form-control rounded-4";
            newSkill.placeholder = "Wpisz swoją umiejętność...";   
            newSkill.value = value;        
            skillDiv.appendChild(newSkill);
            newSkill.setAttribute("required", "");
            newSkill.setAttribute("minlength", "2");
            newSkill.setAttribute("maxlength", "50");
            const deleteButton = document.createElement("button");
            deleteButton.className = "dangerButton";
            deleteButton.type = "button";
            deleteButton.innerHTML = "<span class='bi bi-trash-fill'></span>";
            deleteButton.title = "Usuń umiejętność";   
			deleteButton.setAttribute("data-bs-toggle", "tooltip");        
            deleteButton.addEventListener("click", function(){     
				bootstrap.Tooltip.getInstance(this).dispose();           
                this.parentElement.remove();
            });
            skillDiv.appendChild(deleteButton);
			UpdateTooltips();
        }
		document.querySelector("#addSkillButton")?.addEventListener("click", () => AddSkill());
		function AddLanguage(language = new Language(0, "", ""))
        {
            if(document.querySelector("#userLanguages").children.length >= languages.length)
                return;
            const languageDiv = document.createElement("div");
			languageDiv.className = "d-flex m-2 flex-wrap justify-content-center";
            document.querySelector("#userLanguages").appendChild(languageDiv);
			const languageLabelDiv = document.createElement("div");
			languageLabelDiv.className = "position-relative formInput mt-3 flex-grow-1 me-2";
			languageDiv.appendChild(languageLabelDiv);		
			const LanguageLabel = document.createElement("label");
			LanguageLabel.className = "position-absolute";
			LanguageLabel.setAttribute("for", `l${languageDivsCount}`);
			LanguageLabel.textContent = "Język";
			languageLabelDiv.appendChild(LanguageLabel);
			const newLanguage = document.createElement("select");
			newLanguage.id = `l${languageDivsCount}`;
			let defaultOption = document.createElement("option");
			defaultOption.value = "";
			defaultOption.text = "Wybierz...";
			defaultOption.selected = true;
			defaultOption.disabled = true;
			defaultOption.hidden = true;
			newLanguage.options.add(defaultOption);
			for(let i = 0; i < languages.length; i++)
				newLanguage.options.add(new Option(languages[i].name, languages[i].id, languages[i].id == language.id, languages[i].id == language.id));
            newLanguage.name = "profile_languages[]";
            newLanguage.className = "form-control form-select rounded-4";          
            languageLabelDiv.appendChild(newLanguage);
            newLanguage.setAttribute("required", "");
            const newLanguageValue = document.createElement("select");
			const languageValueLabelDiv = document.createElement("div");
			languageValueLabelDiv.className = "position-relative formInput mt-3 flex-grow-1";
			languageDiv.appendChild(languageValueLabelDiv);
			const LanguageValueLabel = document.createElement("label");
			LanguageValueLabel.className = "position-absolute";
			LanguageValueLabel.setAttribute("for", `lv${languageDivsCount}`);
			LanguageValueLabel.textContent = "Poziom";
			languageValueLabelDiv.appendChild(LanguageValueLabel);
			newLanguageValue.id = `lv${languageDivsCount}`;
			defaultOption = document.createElement("option");
			defaultOption.value = "";
			defaultOption.text = "Wybierz...";
			defaultOption.selected = true;
			defaultOption.disabled = true;
			defaultOption.hidden = true;
			newLanguageValue.options.add(defaultOption);
			for(let i = 0; i < languageValues.length; i++)
				newLanguageValue.options.add(new Option(languageValues[i], languageValues[i], languageValues[i] == language.level, languageValues[i] == language.level));
            newLanguageValue.name = "profile_languages_values[]";
            newLanguageValue.className = "form-control form-select rounded-4";          
            languageValueLabelDiv.appendChild(newLanguageValue);
            newLanguageValue.setAttribute("required", "");
            const deleteButton = document.createElement("button");
            deleteButton.className = "dangerButton mt-2";
            deleteButton.type = "button";
            deleteButton.innerHTML = "<span class='bi bi-trash-fill'></span>";
            deleteButton.title = "Usuń język";      
			deleteButton.setAttribute("data-bs-toggle", "tooltip");   
            deleteButton.addEventListener("click", function(){     
				bootstrap.Tooltip.getInstance(this).dispose();          
                this.parentElement.remove();				
            });
            languageDiv.appendChild(deleteButton);
			UpdateTooltips();
			languageDivsCount++;
        }
		document.querySelector("#addLanguageButton")?.addEventListener("click", () => AddLanguage());
	</script>
	<?php
        $result = $connect->execute_query('SELECT skill FROM user_skill WHERE user_id = ? LIMIT 10', [$user_id]);	
        if($result->num_rows > 0)
        {
            echo "<script>";
            while($row = $result->fetch_assoc())
                echo "AddSkill('".htmlspecialchars($row["skill"])."');";
            echo "</script>";                  
        }        
        $result = $connect->execute_query('SELECT language_id, language, level FROM user_language INNER JOIN language USING(language_id) WHERE user_id = ?', [$user_id]);        
        if($result->num_rows > 0)
        {
            echo "<script>";
            while($row = $result->fetch_assoc())
                echo "AddLanguage(new Language('".$row["language_id"]."', '".htmlspecialchars($row["language"])."', '".htmlspecialchars($row["level"])."'));";
            echo "</script>";                  
        }      
        $result = $connect->execute_query(("SELECT * FROM user_position WHERE user_id = ? LIMIT 10"), [$user_id]);
        if($result->num_rows > 0)
        {
            echo "<script>";
            while($row = $result->fetch_assoc())
                echo "AddExperience('".htmlspecialchars($row["date_start"])."', '".htmlspecialchars($row["date_end"])."', '".htmlspecialchars($row["company_id"])."', '".htmlspecialchars($row["position"])."');";
            echo "</script>";
        }
        $result = $connect->execute_query('SELECT * FROM user_course WHERE user_id = ? LIMIT 10', [$user_id]);
        if($result->num_rows > 0)
        {
            echo "<script>";
            while($row = $result->fetch_assoc())
                echo "AddCourse('".htmlspecialchars($row["date_start"])."', '".htmlspecialchars($row["date_end"])."', '".htmlspecialchars($row["company_id"])."', '".htmlspecialchars($row["name"])."');";
            echo "</script>";
        }
        $result = $connect->execute_query('SELECT * FROM user_education INNER JOIN school USING(school_id) WHERE user_id = ? LIMIT 10', [$user_id]);
        if($result->num_rows > 0)
        {
            echo "<script>";
            while($row = $result->fetch_assoc())
                echo "AddEducation('".htmlspecialchars($row["date_start"])."', '".htmlspecialchars($row["date_end"])."', '".htmlspecialchars($row["name"])."', '".htmlspecialchars($row["city"])."', '".htmlspecialchars($row["field"])."', '".htmlspecialchars($row["level"])."');";
            echo "</script>";
        }
        $result->free_result();    
        $connect->close();
    ?>
</body>
</html>