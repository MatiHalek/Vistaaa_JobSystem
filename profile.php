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
            $files = array_diff(scandir($path), array(".", "..", "default")); 
			echo "<img id='profilePicture' class='bg-white' src='".(count($files) > 0 ? ($path.scandir($path)[2]) : "./img/user.png")."' alt='Profil' width='100'>";
			echo "<div class='m-2'>";
			echo "<h3 class='text-white fw-bold d-flex justify-content-center flex-wrap align-items-center'>".(($row["name"] != null || $row["surname"] != null) ? ($row["name"]." ".$row["surname"]) : $row["email"]);
			if($row["position"] != null)
				echo "<span class='badge rounded-pill text-bg-primary ms-2'>".$row["position"]."</span></h3>";
			if($row["name"] != null || $row["surname"] != null)
				echo "<h5 class='fs-6'>".$row["email"]."</h5>";
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
			echo "<h4>Podsumowanie zawodowe</h4>";
			echo "<p class='mb-0'>".$row["experience"]."</p>";
			echo "</section>";
			echo "</div><hr>";
			$positionResult = $connect->execute_query("SELECT * FROM user_position WHERE user_id = ? ORDER BY date_start DESC;", [$_GET["id"]]);
			$months = array("stycznia", "lutego", "marca", "kwietnia", "maja", "czerwca", "lipca", "sierpnia", "września", "października", "listopada", "grudnia");
			if($positionResult->num_rows > 0)
			{
				echo "<div class='container'>";
				echo "<section class='p-2'>";
				echo "<h4>Doświadczenie zawodowe</h4>";
				while($positionRow = $positionResult->fetch_assoc())
				{
					echo "<div class='d-flex mt-2'>";
					//$months[(int)($date_added->format("n")) - 1]
					$dateStart = new DateTime($positionRow["date_start"]);
					$dateEnd = new DateTime($positionRow["date_end"]);
					echo "<div class='profilePositionDate border-end border-black border-2'><b>".$dateStart->format("j")." ".$months[(int)($dateStart->format("n")) - 1]." ".$dateStart->format("Y")." r. - ".($positionRow["date_end"] != null ? ($dateEnd->format("j")." ".$months[(int)($dateEnd->format("n")) - 1]." ".$dateEnd->format("Y")." r. ") : "nadal")."</b></div>";
					echo "<div class='profilePositionInfo p-2'>";
					echo "<p class='mb-0'><i>".$positionRow["company"]."</i></p>";
					echo "<p class='mb-0'>".$positionRow["address"]."</p>";
					echo "<p class='mb-0 text-primary'>".$positionRow["position"]."</p>";
					echo "</div></div>";
				}
				echo "</section></div><hr>";
			}
		}
		else
			echo "<article class='container-md'><div class='alert alert-danger information'><strong>Błąd 404: Nieprawidłowy identyfikator profilu.</strong> <a href='index.php'>Wróć na stronę główną</a></div></article>";
		$connect->close();
		echo "</main>";
	?>  			
			<!--<div class="container">
				<section class="p-2">
					<h4>Wykształcenie</h4>
					<div class="d-flex mt-2">
						<div class="border-end border-black border-2" style="width: 150px;"><b>wrzesień 2016 - maj 2020</b></div>
						<div class="p-2" style="width: calc(100% - 150px);">
							<p class="mb-0">Nazwa szkoły</p>
							<p class="mb-0">Adres szkoły</p>
							<p class="mb-0">Poziom wykształcenia</p>
							<p class="mb-0">Kierunek</p>
						</div>
					</div>
					<div class="d-flex mt-2">
						<div class="border-end border-black border-2" style="width: 150px;"><b>wrzesień 2008 - czerwiec 2016</b></div>
						<div class="p-2" style="width: calc(100% - 150px);">
							<p class="mb-0">Nazwa szkoły</p>
							<p class="mb-0">Adres szkoły</p>
							<p class="mb-0">Poziom wykształcenia</p>
							<p class="mb-0">Kierunek</p>
						</div>
					</div>
				</section>
			</div>
			<hr>
			<div class="container">
				<section class="p-2">
					<h4>Znajomość języków</h4>
					<ul>
						<li>Angielski - <b>Poziom zaawansowany</b></li>
						<li>Niemiecki - <b>Poziom podstawowy</b></li>
					</ul>
				</section>
			</div>
			<hr>
			<div class="container">
				<section class="p-2">
					<h4>Umiejętności</h4>
					<ul>
						<li>Test</li>
						<li>Test</li>
					</ul>
				</section>
			</div>
			<hr>
			<div class="container">
				<section class="p-2">
					<h4>Kursy, szkolenia, certyfikaty</h4>
					<div class="accordion" id="accordionExample">
					  <div class="accordion-item">
						<h2 class="accordion-header">
						  <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-controls="collapseOne">
							Nazwa szkolenia
						  </button>
						</h2>
						<div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
						  <div class="accordion-body">
							<p><strong>Organizator: </strong>Nazwa organizatora</p>
							<p><strong>Data: </strong>22 stycznia 2023 r. do 1 lutego 2023 r.</p>							
						  </div>
						</div>
					  </div>
					</div>
				</section>
			</div>-->
	<?php
		include "footer.php";
	?>
</body>
</html>