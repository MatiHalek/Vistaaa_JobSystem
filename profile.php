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
				$row = $result->fetch_assoc();
				if(($row["name"] != null && $row["surname"] != null) || $row["name"] != null)
					echo "<script>document.title = 'Profil użytkownika ".$row["name"]." ".$row["surname"]." | System ogłoszeniowy Vistaaa';</script>";
				else
				echo "<script>document.title = 'Profil | ".$row["name"]." ".$row["surname"]." | System ogłoszeniowy Vistaaa';</script>";
				echo "<div class='profileHeader m-2 position-sticky rounded'>";
				echo "<div class='container-fluid flex-wrap p-2 d-flex justify-content-center align-items-center'>";
				$path = './img/user/'.$row["user_id"].'/';
				if(is_dir($path) && scandir($path))
					$files = array_diff(scandir($path), array(".", "..", "default")); 
				else
					$files = array();
				echo "<img id='profilePicture' class='bg-white' src='".(count($files) > 0 ? ($path.scandir($path)[2]) : "./img/user.png")."' alt='Profil' width='100' height='100'>";
				echo "<div class='m-2'>";
				echo "<h3 class='text-white fw-bold d-flex justify-content-center flex-wrap align-items-center'>".(($row["name"] != null || $row["surname"] != null) ? ($row["name"]." ".$row["surname"]) : $row["email"]);
				if($row["position"] != null)
					echo "<span class='badge rounded-pill text-bg-primary ms-2'>".$row["position"]."</span></h3>";
				if($row["name"] != null || $row["surname"] != null)
					echo "<h5 class='fs-6'>".$row["email"]."</h5>";
				echo "</div></div>";
				if(isset($_SESSION["logged"]) && array_key_exists("user_id", $_SESSION["logged"]) && $_SESSION["logged"]["user_id"] == $_GET["id"])
					echo "<div class='text-center pt-2 pb-4'><a href='./profileedit.php' class='commonButton text-decoration-none'><i class='bi bi-pen-fill me-2'></i>Edytuj profil</a></div>";
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
				echo "<h4>Podsumowanie zawodowe</h4>";
				echo "<p class='mb-0'>".$row["experience"]."</p>";			
				echo "</section>";
				echo "</div><hr>";
				echo "<div class='container'>";
				echo "<section class='p-2'>";
				echo "<h4>Doświadczenie zawodowe</h4>";
				$months = array("stycznia", "lutego", "marca", "kwietnia", "maja", "czerwca", "lipca", "sierpnia", "września", "października", "listopada", "grudnia");
				$positionResult = $connect->execute_query("SELECT * FROM user_position INNER JOIN company USING(company_id) WHERE user_id = ? ORDER BY date_start DESC;", [$_GET["id"]]);
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
				echo "</section></div><hr>";
				echo "<div class='container'>";
				echo "<section class='p-2'>";
				echo "<h4>Umiejętności</h4>";
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
				if(isset($_SESSION["logged"]) && $_SESSION["logged"]["user_id"] == $_GET["id"])
				{
					echo "<hr><div class='container'>";
					echo "<section class='p-2'>";
					echo "<h4>Aktywne aplikacje</h4>";
					$applicationResult = $connect->execute_query("SELECT * FROM user_applied INNER JOIN advertisement USING(advertisement_id) INNER JOIN company USING(company_id) WHERE user_id = ?", [$_GET["id"]]);
					if($applicationResult->num_rows > 0)
					{
						while($applicationRow = $applicationResult->fetch_assoc())
						{
							echo "<a href='offerdetails.php?id=".$applicationRow["advertisement_id"]."' class='profileAppliedOffers text-black text-decoration-none bg-white rounded m-3 p-2 d-flex align-items-center flex-wrap'>";
							$path = './img/company/'.$applicationRow["company_id"].'/';
							if(is_dir($path) && scandir($path))
								$files = array_diff(scandir($path ? $path : "./img/company/default"), array(".", "..", "default"));
							else
								$files = array();
							echo "<img title='".$applicationRow["name"]."' data-bs-toggle='tooltip' class='bg-white' src='".(count($files) > 0 ? ($path.scandir($path)[2]) : "./img/user.png")."' alt='Logo firmy ".$applicationRow["name"]."' width='100' height='100'>";
							echo "<div class='flex-grow-1 fs-5 fw-bold signika-negative'>".$applicationRow["title"]."</div>";
							echo "<div class='flex-grow-1'>";
							echo "<p class='text-body-secondary mb-0'>Poziom stanowiska: <i>".mb_strtolower(htmlspecialchars($applicationRow["position_level"]))."</i></p>";
							echo "<p class='text-body-secondary mb-0'>Rodzaj umowy: <i>".mb_strtolower(htmlspecialchars($applicationRow["contract_type"]))."</i></p>";
							echo "<p class='text-body-secondary mb-0'>Rodzaj zatrudnienia: <i>".mb_strtolower(htmlspecialchars($applicationRow["employment_type"]))."</i></p>";
							echo "<p class='text-body-secondary mb-0'>Rodzaj pracy: <i>".mb_strtolower(htmlspecialchars($applicationRow["work_type"]))."</i></p>";
							echo "</div>";
							$date_applied = new DateTime($applicationRow["applied_date"]);
							echo "<div><i class='bi bi-alarm-fill me-2'></i>Aplikowano ".$date_applied->format("j")." ".mb_substr($months[(int)($date_applied->format("n")) - 1], 0, 3)." ".$date_applied->format("Y")."</div>";
							echo "</a>";
						}
					}
					else
						echo "<div class='alert alert-info information'><strong>Brak aktywnych aplikacji.</strong></div>";
				}				
			}
			else
				include "404.html";
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
				if(is_dir($path) && scandir($path))
					$files = array_diff(scandir($path), array(".", "..", "default"));
				else
					$files = array();
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
				include "404.html";
		}
		echo "</main>";
	?>  			
	<?php
		include "footer.php";
		$result->free_result();
		$connect->close();
	?>
</body>
</html>