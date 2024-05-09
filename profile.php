<?php
	//error_reporting(0);
	session_start();
	if(!isset($_GET["id"]))
	{
		header("Location: profile.php?id=1");
		exit();
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
    <title>Profil | System ogłoszeniowy Vistaaa</title>
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
		$pageName = "Profil"; 
		include "header.php";
		echo "<main class='position-relative'>";
		require "connect.php";
		$connect = new mysqli($host, $db_user, $db_password, $db_name);
		$connect->set_charset("utf8mb4");
		if(!isset($_GET["type"]) || $_GET["type"] != "company")
		{
			$result = $connect->execute_query("SELECT * FROM user WHERE user_id = ?;", [$_GET["id"]]);
			if($result->num_rows > 0)
			{
				$editMode = false;
				if(isset($_SESSION["logged"]) && $_SESSION["logged"]["user_id"] == $_GET["id"])
					$editMode = true;
				$row = $result->fetch_assoc();
				if(($row["name"] != null && $row["surname"] != null) || $row["name"] != null)
					echo "<script>document.title = 'Profil użytkownika ".$row["name"]." ".$row["surname"]." | System ogłoszeniowy Vistaaa';</script>";
				else
				echo "<script>document.title = 'Profil | ".$row["name"]." ".$row["surname"]." | System ogłoszeniowy Vistaaa';</script>";
				if($editMode)
					echo "<form action='profile.php?id=".$_GET["id"].((isset($_GET["type"]) && $_GET["type"] == "company") ? "&type=company" : "")."' method='post' enctype='multipart/form-data'>";
				echo "<div class='profileHeader m-2".(!$editMode ? " position-sticky" : "")." rounded'>";
				echo "<div class='container-fluid flex-wrap p-2 d-flex justify-content-center align-items-center'>";
				$path = './img/user/'.$row["user_id"].'/';
				$files = array_diff(scandir($path), array(".", "..", "default")); 
				if(!$editMode)
					echo "<img id='profilePicture' class='bg-white' src='".(count($files) > 0 ? ($path.scandir($path)[2]) : "./img/user.png")."' alt='Profil' width='100' height='100'>";
				else
				{
					echo "<div class='d-flex flex-column align-items-center text-center' id='profilePictureEdit'>";
					echo "<label class='position-relative'>";
					echo "<input type='file' name='profile_picture' class='d-none' accept='image/*'>";
					echo "<img id='profilePicture' class='bg-white' src='".(count($files) > 0 ? ($path.scandir($path)[2]) : "./img/user.png")."' alt='Profil' width='100' height='100'>";
					echo "</label>";
					echo "<p class='text-white fw-bold text-break text-center mt-2 mb-0' id='uploadedFileName'></p>";
					echo "</div>";
				}
				if(!$editMode)
				{
					echo "<div class='m-2'>";
					echo "<h3 class='text-white fw-bold d-flex justify-content-center flex-wrap align-items-center'>".(($row["name"] != null || $row["surname"] != null) ? ($row["name"]." ".$row["surname"]) : $row["email"]);
					if($row["position"] != null)
						echo "<span class='badge rounded-pill text-bg-primary ms-2'>".$row["position"]."</span></h3>";
					if($row["name"] != null || $row["surname"] != null)
						echo "<h5 class='fs-6'>".$row["email"]."</h5>";
				}
				else
				{
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
				}
				echo "</div></div>";
				
				$linksResult = $connect->execute_query("SELECT * FROM user_link INNER JOIN portal USING(portal_id) WHERE user_id = ?;", [$_GET["id"]]);
				if($linksResult->num_rows > 0)
				{
					echo "<div class='profileLinks d-flex justify-content-center p-2 rounded'>";
					while($linkRow = $linksResult->fetch_assoc())
					{
						echo "<a href='".$linkRow["link"]."' class='text-decoration-none' target='_blank'>";
						echo "<span class='badge rounded-pill text-primary bg-white'><i class='bi bi-".mb_strtolower($linkRow["name"])."' title='".$linkRow["name"]."'></i> ".$linkRow["name"]."</span>";
						echo "</a>";
					}
					echo "</div>";	
				}										
				echo "</div>";
				echo "<div class='container pt-4'>";
				echo "<section class='p-2'>";
				if(!$editMode)
				{
					echo "<h4>Podsumowanie zawodowe</h4>";
					echo "<p class='mb-0'>".$row["experience"]."</p>";
				}				
				else
				{
					echo "<div class='position-relative formInput mt-3'>";                  
                    echo "<textarea minlength='5' maxlength='1000' rows='4' id='profile_experience' name='profile_experience' placeholder='Podsumowanie zawodowe' class='rounded-4 border-0 w-100 py-2 px-3'>";
					if(isset($_SESSION["remember_profile_experience"]))
						echo htmlspecialchars($_SESSION["remember_profile_experience"]);
					else
						echo htmlspecialchars($row["experience"]);
                    echo "</textarea><label for='profile_experience' class='position-absolute'>Podsumowanie zawodowe</label>";
                    echo "</div>";
				}
				echo "</section>";
				echo "</div><hr>";
				echo "<div class='container'>";
				echo "<section class='p-2'>";
				echo "<h4>Doświadczenie zawodowe</h4>";
				$positionResult = $connect->execute_query("SELECT * FROM user_position INNER JOIN company USING(company_id) WHERE user_id = ? ORDER BY date_start DESC;", [$_GET["id"]]);
				$months = array("stycznia", "lutego", "marca", "kwietnia", "maja", "czerwca", "lipca", "sierpnia", "września", "października", "listopada", "grudnia");
				if($positionResult->num_rows > 0)
				{
					while($positionRow = $positionResult->fetch_assoc())
					{
						echo "<div class='d-flex mt-2'>";
						$dateStart = new DateTime($positionRow["date_start"]);
						$dateEnd = new DateTime($positionRow["date_end"]);
						echo "<div class='profilePositionDate border-end border-black border-2'><b>".$dateStart->format("j")." ".$months[(int)($dateStart->format("n")) - 1]." ".$dateStart->format("Y")." r. - ".($positionRow["date_end"] != null ? ($dateEnd->format("j")." ".$months[(int)($dateEnd->format("n")) - 1]." ".$dateEnd->format("Y")." r. ") : "nadal")."</b></div>";
						echo "<div class='profilePositionInfo p-2'>";
						echo "<p class='mb-0 fs-5 text-break signika-negative'><i>".$positionRow["name"]."</i></p>";
						echo "<p class='mb-0'>".$positionRow["street"]." ".$positionRow["number"].", ".$positionRow["postcode"]." ".$positionRow["city"]."</p>";
						echo "<p class='mb-0 text-primary'>".$positionRow["position"]."</p>";
						echo "</div></div>";
					}
				}
				else
					echo "<div class='alert alert-info information'><strong>Brak informacji o doświadczeniu zawodowym.</strong></div>";
				echo "</section></div><hr>";
				echo "<div class='container'>";
				echo "<section class='p-2'>";
				echo "<h4>Wykształcenie</h4>";
				$educationResult = $connect->execute_query("SELECT * FROM user_education INNER JOIN school USING(school_id) WHERE user_id = ? ORDER BY date_start DESC;", [$_GET["id"]]);
				if($educationResult->num_rows > 0)
				{
					while($educationRow = $educationResult->fetch_assoc())
					{
						echo "<div class='d-flex mt-2'>";
						$dateStart = new DateTime($educationRow["date_start"]);
						$dateEnd = new DateTime($educationRow["date_end"]);
						echo "<div class='profileEducationDate border-end border-black border-2'><b>".$dateStart->format("j")." ".$months[(int)($dateStart->format("n")) - 1]." ".$dateStart->format("Y")." r. - ".($educationRow["date_end"] != null ? ($dateEnd->format("j")." ".$months[(int)($dateEnd->format("n")) - 1]." ".$dateEnd->format("Y")." r. ") : "nadal")."</b></div>";
						echo "<div class='profileEducationInfo p-2'>";
						echo "<p class='mb-0 fs-5 text-break signika-negative'><i>".$educationRow["name"]."</i> (".$educationRow["city"].")</p>";
						echo "<p class='mb-0'>Wykształcenie ".$educationRow["level"]."</p>";
						if($educationRow["field"] != null)
							echo "<p class='mb-0'><u>Kierunek:</u> ".$educationRow["field"]."</p>";
						echo "</div></div>";
					}
				}
				else
					echo "<div class='alert alert-info information'><strong>Brak informacji o wykształceniu.</strong></div>";
				echo "</section></div><hr>";
				echo "<div class='container'>";
				echo "<section class='p-2'>";
				echo "<h4>Znajomość języków</h4>";
				if(!$editMode)
				{
					$languageResult = $connect->execute_query("SELECT * FROM user_language INNER JOIN language USING(language_id) WHERE user_id = ?;", [$_GET["id"]]);
					if($languageResult->num_rows > 0)
					{
						echo "<ul>";
						while($languageRow = $languageResult->fetch_assoc())
						{
							echo "<li>".$languageRow["language"]." - <b>".$languageRow["level"]."</b></li>";
						}
						echo "</ul>";
					}
					else
						echo "<div class='alert alert-info information'><strong>Brak informacji o znajomości języków obcych.</strong></div>";
				}
				else
				{
					echo "<button type='button' class='commonButton addToProfileButton py-2 px-3' id='addLanguageButton'><i class='bi bi-plus-circle-fill me-2'></i>Dodaj język</button>";
					echo "<div id='userLanguages'></div>";
				}				
				echo "</section></div><hr>";
				echo "<div class='container'>";
				echo "<section class='p-2'>";
				echo "<h4>Umiejętności</h4>";
				if(!$editMode)
				{
					$skillResult = $connect->execute_query("SELECT * FROM user_skill WHERE user_id = ?;", [$_GET["id"]]);
					if($skillResult->num_rows > 0)
					{
						echo "<ul>";
						while($skillRow = $skillResult->fetch_assoc())
						{
							echo "<li>".$skillRow["skill"]."</li>";
						}
						echo "</ul>";
					}
					else
						echo "<div class='alert alert-info information'><strong>Brak informacji o umiejętnościach.</strong></div>";
				}
				else
				{
					echo "<button type='button' class='commonButton addToProfileButton py-2 px-3' id='addSkillButton'><i class='bi bi-plus-circle-fill me-2'></i>Dodaj umiejętność</button>";
					echo "<div id='userSkills'></div>";
				}				
				echo "</section></div><hr>";
				echo "<div class='container'>";
				echo "<section class='p-2'>";
				echo "<h4>Kursy, szkolenia, certyfikaty</h4>";
				$courseResult = $connect->execute_query("SELECT user_course.name AS course_name, company.name AS company_name, user_course.date_start, user_course.date_end FROM user_course INNER JOIN company USING(company_id) WHERE user_id = ? ORDER BY date_start DESC;", [$_GET["id"]]);
				if($courseResult->num_rows > 0)
				{
					echo "<div class='accordion' id='accordionExample'>";
					$i = 1;
					while($courseRow = $courseResult->fetch_assoc())
					{
						echo "<div class='accordion-item'>";
						echo "<h2 class='accordion-header'>";
						echo "<button class='accordion-button collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#collapse".$i."' aria-controls='collapse".$i."'>";
						echo $i.". ".$courseRow["course_name"];
						echo "</button></h2>";
						echo "<div id='collapse".$i."' class='accordion-collapse collapse' data-bs-parent='#accordionExample'>";
						echo "<div class='accordion-body'>";
						echo "<p><strong>Organizator: </strong>".$courseRow["company_name"]."</p>";
						echo "<p><strong>Data: </strong>".date("j.m.Y", strtotime($courseRow["date_start"]))." r.".($courseRow["date_end"] != null ? (" - ".date("j.m.Y", strtotime($courseRow["date_end"]))." r.") : "")."</p>";
						echo "</div></div></div>";
						$i++;
					}
					echo "</div>";
				}
				else
					echo "<div class='alert alert-info information'><strong>Brak informacji o kursach, szkoleniach, certyfikatach.</strong></div>";
				echo "</section></div>";
				if($editMode)
				{
					echo "<button type='submit' class='successButton d-block mx-auto mt-3'><i class='bi bi-check-circle-fill me-2'></i>Zapisz zmiany</button>";
					echo "</form>";
				}
				
			}
			else
				echo "<article class='container-md'><div class='alert alert-danger information'><strong>Błąd 404: Nieprawidłowy identyfikator profilu.</strong> <a href='index.php'>Wróć na stronę główną</a></div></article>";
		}
		else
		{
			$result = $connect->execute_query("SELECT * FROM company WHERE company_id = ?;", [$_GET["id"]]);
			if($result->num_rows > 0)
			{
				$row = $result->fetch_assoc();
				echo "<script>document.title = 'Profil firmy ".$row["name"]." | System ogłoszeniowy Vistaaa';</script>";
				echo "<div class='profileHeader companyProfileHeader m-2 position-sticky rounded'>";
				echo "<div class='container-fluid flex-wrap p-2 d-flex justify-content-center align-items-center'>";
				$path = './img/company/'.$row["company_id"].'/';
				$files = array_diff(scandir($path ? $path : "./img/company/default"), array(".", "..", "default"));
				echo "<img id='profilePicture' class='bg-white' src='".(count($files) > 0 ? ($path.scandir($path)[2]) : "./img/user.png")."' alt='Profil' width='100'>";
				echo "<div class='m-2'>";
				echo "<h3 class='text-white fw-bold d-flex justify-content-center flex-wrap align-items-center'>".$row["name"];
				echo "<span class='badge rounded-pill ms-2'>Firma</span></h3>";
				echo "<h5 class='fs-6'>".$row["email"]."</h5>";
				echo "</div></div>";
				echo "</div>";
				echo "<div class='container pt-4'>";
				echo "<section class='p-2'>";
				echo "<h4>Adres firmy</h4>";
				echo "<p class='mb-0'>".$row["street"]." ".$row["number"].", ".$row["postcode"]." ".$row["city"]."</p>";
				echo "</section>";
				echo "</div><hr>";
				echo "<div class='container'>";
				echo "<section class='p-2'>";
				echo "<h4>Opis firmy</h4>";
				echo "<p class='mb-0'>".$row["description"]."</p>";
				echo "</section></div>";
			}
			else
				echo "<article class='container-md'><div class='alert alert-danger information'><strong>Błąd 404: Nieprawidłowy identyfikator profilu.</strong> <a href='index.php'>Wróć na stronę główną</a></div></article>";
		}
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
		let languageDivsCount = 0;
		<?php
			if($editMode && (!isset($_GET["type"]) || $_GET["type"] != "company"))
			{
				$result = $connect->execute_query('SELECT * FROM language');
				if($result->num_rows > 0)
				{
					while($row = $result->fetch_assoc())
						echo "languages.push(new Language('".htmlspecialchars($row["language_id"])."', '".htmlspecialchars($row["language"])."', ''));";
				}
			}
		?>
		const oldImage = document.querySelector("#profilePicture").getAttribute("src");
		document.querySelector("input[type='file']").addEventListener("change", function(){
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
            newSkill.className = "form-control";
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
            deleteButton.addEventListener("click", function(){               
                this.parentElement.remove();
            });
            skillDiv.appendChild(deleteButton);
        }
		document.querySelector("#addSkillButton").addEventListener("click", () => AddSkill());
		function AddLanguage(language = new Language(0, "", ""))
        {
            if(document.querySelector("#userLanguages").children.length >= languages.length)
                return;
            const languageDiv = document.createElement("div");
			languageDiv.className = "d-flex m-2";
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
            newLanguage.className = "form-control";          
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
            newLanguageValue.name = "profile_language_values[]";
            newLanguageValue.className = "form-control";          
            languageValueLabelDiv.appendChild(newLanguageValue);
            newLanguageValue.setAttribute("required", "");
            const deleteButton = document.createElement("button");
            deleteButton.className = "dangerButton mt-2";
            deleteButton.type = "button";
            deleteButton.innerHTML = "<span class='bi bi-trash-fill'></span>";
            deleteButton.title = "Usuń język";         
            deleteButton.addEventListener("click", function(){               
                this.parentElement.remove();
            });
            languageDiv.appendChild(deleteButton);
			languageDivsCount++;
        }
		document.querySelector("#addLanguageButton").addEventListener("click", () => AddLanguage());
	</script>
	<?php
        if($editMode && (!isset($_GET["type"]) || $_GET["type"] != "company"))
        {
            $result = $connect->execute_query('SELECT skill FROM user_skill WHERE user_id = ? LIMIT 10', [$_GET["id"]]);	
            if($result->num_rows > 0)
            {
                echo "<script>";
                while($row = $result->fetch_assoc())
                    echo "AddSkill('".htmlspecialchars($row["skill"])."');";
                echo "</script>";                  
            }        
			$result = $connect->execute_query('SELECT language_id, language, level FROM user_language INNER JOIN language USING(language_id) WHERE user_id = ?', [$_GET["id"]]);        
			if($result->num_rows > 0)
			{
				echo "<script>";
				while($row = $result->fetch_assoc())
					echo "AddLanguage(new Language('".$row["language_id"]."', '".htmlspecialchars($row["language"])."', '".htmlspecialchars($row["level"])."'));";
				echo "</script>";                  
			}      
            $result->free_result();
        }       
        $connect->close();
    ?>
</body>
</html>