<?php
	error_reporting(0);
	session_start();
	if(!isset($_GET["id"]))
	{
		header("Location: offerdetails.php?id=1");
		exit();
	}
	if(!isset($_COOKIE["vistaaaRecentlyViewedOffers"]) || empty($_COOKIE["vistaaaRecentlyViewedOffers"]))
		setcookie("vistaaaRecentlyViewedOffers", json_encode(array($_GET["id"])), time() + 30 * 24 * 3600, "/");
	else
	{
		require "connect.php";
		$connect = new mysqli($host, $db_user, $db_password, $db_name);
		$recentlyViewed = json_decode($_COOKIE["vistaaaRecentlyViewedOffers"]);
		$offer_ids = $connect->execute_query("SELECT advertisement_id FROM advertisement");
		$offer_ids_array = array();
		while($offer_id = $offer_ids->fetch_assoc())
			array_push($offer_ids_array, $offer_id["advertisement_id"]);
		for($i = 0; $i < count($recentlyViewed); $i++)
		{
			if(!in_array($recentlyViewed[$i], $offer_ids_array))
				array_splice($recentlyViewed, $i, 1);
		}			
		array_unshift($recentlyViewed, $_GET["id"]);
		$recentlyViewed = array_values(array_unique($recentlyViewed));
		$recentlyViewed = array_slice($recentlyViewed, 0, 10);
		setcookie("vistaaaRecentlyViewedOffers", json_encode($recentlyViewed), time() + 30 * 24 * 3600, "/");
		$connect->close();
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
    <title>Szczegóły ogłoszenia | System ogłoszeniowy Vistaaa</title>
    <base href="https://127.0.0.1/Vistaaa/">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="img/vistaaa_small_logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body id="offerBody">
	<?php
		header('Content-Type: text/html; charset=utf-8');
		$pageName = "Szczegóły ogłoszenia";
		include "header.php";
		if(isset($_SESSION["logged"]) && $_SESSION["logged"] && array_key_exists("user_id", $_SESSION["logged"]))
		{
			echo "<div class='modal fade' id='applyModal' tabindex='-1' aria-hidden='true'>";
			echo "<div class='modal-dialog modal-dialog-centered'>";
			echo "<div class='modal-content'>";
			echo "<div class='modal-header'>";
			echo "<h1 class='modal-title fs-5'>Potwierdź aplikowanie</h1>";
			echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
			echo "</div>";
			echo "<div class='modal-body'>";
			echo "<strong>Raz wysłana aplikacja nie może zostać cofnięta.</strong><br><br>";
			echo "Aby aplikować na ogłoszenia, wymagane jest uzupełnienie kilku podstawowych informacji w profilu. Zalecamy jednak uzupełnienie wszystkich danych, a także sprawdzenie poprawności tych istniejących, aby zwiększyć swoje szanse na zatrudnienie.<br>";
			echo "<a href='profile.php?id=".$_SESSION["logged"]["user_id"]."' id='applyingProfileButton' class='commonButton mt-2 d-inline-block text-decoration-none' target='_blank'><i class='bi bi-person-bounding-box me-2'></i>Zobacz profil</a><br><br>";
			$completedProfile = true;
			if(!empty($_SESSION["logged"]["name"]) && !empty($_SESSION["logged"]["surname"]) && !ctype_space($_SESSION["logged"]["name"]) && !ctype_space($_SESSION["logged"]["surname"]))
				echo "<p class='text-success mb-0'><i class='bi bi-check-circle-fill me-2'></i><b>Imię i nazwisko: </b>".$_SESSION["logged"]["name"]." ".$_SESSION["logged"]["surname"]."</p>";
			else
			{
				echo "<p class='text-danger mb-0 fw-bold'><i class='bi bi-x-circle-fill me-2'></i>Imię i/lub nazwisko nie jest uzupełnione.</p>";
				$completedProfile = false;
			}		
			if(!empty($_SESSION["logged"]["street"]) && !empty($_SESSION["logged"]["home_number"]) && !empty($_SESSION["logged"]["postcode"]) && !empty($_SESSION["logged"]["city"]) && !ctype_space($_SESSION["logged"]["street"]) && !ctype_space($_SESSION["logged"]["home_number"]) && !ctype_space($_SESSION["logged"]["postcode"]) && !ctype_space($_SESSION["logged"]["city"]))
				echo "<p class='text-success mb-0'><i class='bi bi-check-circle-fill me-2'></i><b>Adres: </b>".$_SESSION["logged"]["street"]." ".$_SESSION["logged"]["home_number"].", ".$_SESSION["logged"]["postcode"]." ".$_SESSION["logged"]["city"]."</p>";
			else
			{
				echo "<p class='text-danger mb-0 fw-bold'><i class='bi bi-x-circle-fill me-2'></i>Adres zamieszkania nie jest uzupełniony.</p>";
				$completedProfile = false;
			}
			if(!empty($_SESSION["logged"]["position"]) && !empty($_SESSION["logged"]["experience"]) && !ctype_space($_SESSION["logged"]["position"]) && !ctype_space($_SESSION["logged"]["experience"]))
				echo "<p class='text-success mb-0'><b><i class='bi bi-check-circle-fill me-2'></i>Stanowisko (".$_SESSION["logged"]["position"].") i podsumowanie zawodowe jest uzupełnione.</b></p>";
			else
			{
				echo "<p class='text-danger mb-0 fw-bold'><i class='bi bi-x-circle-fill me-2'></i>Stanowisko i/lub podsumowanie zawodowe nie jest uzupełnione.</p>";
				$completedProfile = false;
			}
			echo "<br>";
			if($completedProfile)
				echo "<strong>Aplikując, zgadzasz się na udostępnienie danych pracodawcy przez serwis Vistaaa. Czy na pewno chcesz teraz aplikować na to ogłoszenie?</strong>";
			else
				echo "<strong>Nie wszystkie wymagane informacje są uzupełnione. Aby móc aplikować na ogłoszenia, uzupełnij swoje dane w profilu.</strong>";
			echo "</div>";
			echo "<div class='modal-footer'>";
			echo "<button type='button' class='dangerButton' data-bs-dismiss='modal'>".($completedProfile ? "Anuluj" : "Zamknij")."</button>";
			if($completedProfile)
				echo "<button type='button' class='successButton' id='confirmApplying'>Aplikuj teraz</button>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
		}
	?>
	<main>
	
		<?php
			require "connect.php";
            $connect = new mysqli($host, $db_user, $db_password, $db_name);
            $connect->set_charset('utf8mb4');
            $result = $connect->execute_query('SELECT * FROM advertisement WHERE advertisement_id = ?', [$_GET["id"]]);
            if($result->num_rows > 0)
            {
				$row = $result->fetch_assoc();
				echo "<script>document.title='Ogłoszenie: ".$row["title"]." | System ogłoszeniowy Vistaaa';</script>";
				echo "<iframe class='w-100' src='https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2580.6341520898554!2d20.41741487692789!3d49.69886194119375!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47161cd0d7dfe0e5%3A0x8b8e90f28d06c112!2zWmVzcMOzxYIgU3prw7PFgiBUZWNobmljem55Y2ggaSBPZ8OzbG5va3N6dGHFgmNhY3ljaCBpbS4gSmFuYSBQYXfFgmEgSUk!5e0!3m2!1spl!2spl!4v1704599107714!5m2!1spl!2spl' height='450' style='border: 0;' allowfullscreen='' loading='lazy' referrerpolicy='no-referrer-when-downgrade'></iframe>";
				echo "<article class='container-lg' id='advertisementInfo'>";
				echo "<section class='d-grid'>";
				echo "<section class='align-items-center justify-content-center d-flex flex-column' id='saveApplySection'>";				
				echo "</section><section>";
				echo "<h1 class='fs-2'>".$row["title"]." <span class='fs-6'>#".str_pad($row["advertisement_id"], 6, "0", STR_PAD_LEFT)."</span></h1>";
				$categoryResult = $connect->execute_query('SELECT name FROM advertisement_category INNER JOIN category USING(category_id) WHERE advertisement_id = ?', [$row["advertisement_id"]]);
				$categoryArray = array();
				while($categoryRow = $categoryResult->fetch_assoc())
					array_push($categoryArray, $categoryRow["name"]);
				echo "<h2 class='fs-5 text-secondary fw-bold'>".implode(", ", $categoryArray)."</h2>";		
				echo "<div class='d-flex justify-content-between flex-wrap'>";
				$months = array("stycznia", "lutego", "marca", "kwietnia", "maja", "czerwca", "lipca", "sierpnia", "września", "października", "listopada", "grudnia");
				$date_added = new DateTime($row["date_added"]);
				echo "<p class='me-3'><i class='bi bi-calendar-plus-fill me-2'></i>Dodano ".$date_added->format("j")." ".mb_substr($months[(int)($date_added->format("n")) - 1], 0, 3)." ".$date_added->format("Y G:i:s")."</p>";
				$date_expiration = new DateTime($row["date_expiration"]);
				echo "<p><i class='bi bi-calendar-check-fill me-2'></i>Ważne do ".$date_expiration->format("j")." ".mb_substr($months[(int)($date_expiration->format("n")) - 1], 0, 3)." ".$date_expiration->format("Y G:i:s")."</p>";
				echo "</div>";	
				echo "<div class='mx-auto bg-success-subtle p-4 p-sm-2 rounded-pill'>";
				echo "<h3 class='text-center text-success fw-bold mb-0'><i class='bi bi-cash-coin me-2'></i>".(is_null($row["salary_lowest"]) ? number_format($row["salary_highest"], 2, ",", " ") : number_format($row["salary_lowest"], 2, ",", " ")." - ".number_format($row["salary_highest"], 2, ",", " "))." zł / mies.</h3>";
				echo "</div></section>";
				echo "<section class='d-flex bg-primary bg-opacity-25'>";
				$path = "./img/company/".$row["company_id"]."/";
				$companyResult = $connect->execute_query('SELECT * FROM company WHERE company_id = ?', [$row["company_id"]]);
				$companyRow = $companyResult->fetch_assoc();
				echo "<img class='align-self-start me-4 rounded-3' src='".(is_dir($path) ? $path.scandir($path)[2] : "./img/user.png")."' width='75' alt='Logo firmy ".$companyRow["name"]."'>";
				echo "<div>";
				echo "<h5 class='fs-6 text-primary'>O firmie</h5>";
				echo "<h6 class='fs-4'>".$companyRow["name"]."</h6>";
				echo "<p>".$companyRow["description"]."</p>";
				echo "<p class='fw-bold text-primary-emphasis'><i class='bi bi-building-fill me-2'></i>".$companyRow["street"]." ".$companyRow["number"].", ".$companyRow["postcode"]." ".$companyRow["city"]."</p>";
				echo "<a href='./profile.php?id=".$row["company_id"]."&type=company' target='_blank' class='commonButton companyProfileButton py-2 mt-2 d-inline-block text-decoration-none'><i class='bi bi-person-bounding-box me-2'></i>Profil firmy</a>";
				echo "</div>";
				echo "</section>";
				echo<<<info
				<section>
					<div class="row">
						<div class="d-flex col-md-6 jobInfo">
							<div class="bg-secondary mt-2 rounded d-flex align-items-center justify-content-center jobInfoIcon text-white">
								<i class="bi bi-pencil-square"></i>
							</div>
							<div>
								<strong class='text-secondary'>Rodzaj umowy</strong>
								<p>{$row["contract_type"]}</p>
							</div>
						</div>
						<div class="d-flex col-md-6 jobInfo">
							<div class="bg-secondary mt-2 rounded d-flex align-items-center justify-content-center jobInfoIcon text-white">
								<i class="bi bi-person-vcard-fill"></i>
							</div>
							<div>
								<strong class='text-secondary'>Stanowisko</strong>
								<p>{$row["position_name"]}</p>
							</div>
						</div>
						<div class="d-flex col-md-6 jobInfo">
							<div class="bg-secondary mt-2 rounded d-flex align-items-center justify-content-center jobInfoIcon text-white">
								<i class="bi bi-person-standing"></i>
							</div>
							<div>
								<strong class='text-secondary'>Poziom stanowiska</strong>
								<p>{$row["position_level"]}</p>
							</div>
						</div>
						<div class="d-flex col-md-6 jobInfo">
							<div class="bg-secondary mt-2 rounded d-flex align-items-center justify-content-center jobInfoIcon text-white">
								<i class="bi bi-clock-fill"></i>
							</div>
							<div>
								<strong class='text-secondary'>Wymiar etatu</strong>
								<p>{$row["employment_type"]}</p>
							</div>
						</div>
						<div class="d-flex col-md-6 jobInfo">
							<div class="bg-secondary mt-2 rounded d-flex align-items-center justify-content-center jobInfoIcon text-white">
								<i class="bi bi-person-workspace"></i>
							</div>
							<div>
								<strong class='text-secondary'>Rodzaj pracy</strong>
								<p>{$row["work_type"]}</p>
							</div>
						</div>
						<div class="d-flex col-md-6 jobInfo">
							<div class="bg-secondary mt-2 rounded d-flex align-items-center justify-content-center jobInfoIcon text-white">
								<i class="bi bi-calendar2-week-fill"></i>
							</div>
							<div>
								<strong class='text-secondary'>Godziny i dni pracy</strong>
				info;
				echo "<p>".str_replace(["\r\n", "\r", "\n"], "<br>", $row["work_days"])."</p>";
				echo "</div></div></div></section>";
				echo "<section>";
				echo "<h4 class='text-center'>Zakres obowiązków:</h4>";
				echo "<ul>";
				$responsibilities = preg_split('/\r\n|\r|\n/', $row["responsibilities"]);
				foreach($responsibilities as $responsibility)
					echo "<li>{$responsibility}</li>";
				echo "</ul></section>";
				echo "<section>";
				echo "<h4 class='text-center'>Wymagania od kandydata:</h4>";
				echo "<ul>";
				$requirements = preg_split('/\r\n|\r|\n/', $row["requirements"]);
				foreach($requirements as $requirement)
					echo "<li>{$requirement}</li>";
				echo "</ul></section>";
				echo "<section>";
				echo "<h4 class='text-center'>Oferujemy:</h4>";
				echo "<ul>";
				$offer = preg_split('/\r\n|\r|\n/', $row["offer"]);
				foreach($offer as $off)
					echo "<li>{$off}</li>";
				echo "</ul></section>";
			}
			else
				include "404.html";
            $result->free_result();
            $connect->close();
		?>
			</section>				
		</article>
	</main>
	<?php
		include "footer.php";
	?>
	<script>
		const saveApplySection = document.querySelector("#saveApplySection");
		const loadingAnimation = "<div class='spinner-border text-primary' style='scale: 2;' role='status'><span class='visually-hidden'>Loading...</span></div>";
		async function RefreshData(mode = null)
		{
			try
            {
                saveApplySection.innerHTML = loadingAnimation;
				const sendData = new FormData();
        		sendData.append("advertisement_id", <?php echo $_GET["id"]?>);  
				if(mode != null)
        			sendData.append("mode", mode);
                const response = await fetch("./fetch/save_apply.php", {
                    method: "POST",
                    body: sendData
                });
                saveApplySection.innerHTML = await response.text();    
            }
            catch
            {
                if(saveApplySection != null)
					saveApplySection.innerHTML = "<div class='alert alert-danger mb-0 shadow text-center'><p class='fw-bold mb-1'>Coś poszło nie tak. Spróbuj ponownie lub odśwież stronę.</p>Kod błędu: 1 (Nie udało połączyć się z serwerem)<br><button type='button' class='commonButton mt-1' id='reload'><i class='bi bi-arrow-clockwise me-2'></i>Załaduj ponownie</a></div>";
            }
			AddFeatures(); 
		}
		let applyModal = null;
		if(document.querySelector("#applyModal") != null)
			applyModal = new bootstrap.Modal(document.querySelector("#applyModal"));
		function AddFeatures()
		{
			const logged = <?php echo isset($_SESSION["logged"]) && $_SESSION["logged"] ? "true" : "false"?>;
			document.querySelector("#saveButton")?.addEventListener("click", () => {
				RefreshData("saved");
				if(!logged)
					bsOffcanvas.show();
			});
			document.querySelector("#confirmApplying")?.addEventListener("click", () => {
				RefreshData("applied");
				applyModal?.hide();
				//applyModal?.dispose();
			});
			document.querySelector("#applyButton")?.addEventListener("click", () => {
				if(!logged)
					bsOffcanvas.show();
				else
					applyModal?.show();
			});
			document.querySelector("#reload")?.addEventListener("click", () => RefreshData());
		}
		RefreshData();
	</script>
</body>
</html>