<?php
	//error_reporting(0);
	session_start();
	if(!isset($_GET["id"]))
	{
		header("Location: offerdetails.php?id=1");
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
	?>
	<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">  Launch demo modal</button>
	<div class="modal fade" id="applyModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header">
					<h1 class="modal-title fs-5" id="exampleModalLabel">Potwierdź aplikowanie</h1>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					...
				</div>
				<div class="modal-footer">
					<button type="button" class="dangerButton" data-bs-dismiss="modal">Anuluj</button>
					<button type="button" class="successButton">Aplikuj teraz</button>
				</div>
			</div>
		</div>
	</div>
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
				echo "<img class='align-self-start me-4 rounded-3' src='".$path.scandir($path)[2]."' width='75' alt='Logo firmy ".$companyRow["name"]."'>";
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
								<strong>Rodzaj umowy</strong>
								<p>{$row["contract_type"]}</p>
							</div>
						</div>
						<div class="d-flex col-md-6 jobInfo">
							<div class="bg-secondary mt-2 rounded d-flex align-items-center justify-content-center jobInfoIcon text-white">
								<i class="bi bi-person-vcard-fill"></i>
							</div>
							<div>
								<strong>Stanowisko</strong>
								<p>{$row["position_name"]}</p>
							</div>
						</div>
						<div class="d-flex col-md-6 jobInfo">
							<div class="bg-secondary mt-2 rounded d-flex align-items-center justify-content-center jobInfoIcon text-white">
								<i class="bi bi-person-standing"></i>
							</div>
							<div>
								<strong>Poziom stanowiska</strong>
								<p>{$row["position_level"]}</p>
							</div>
						</div>
						<div class="d-flex col-md-6 jobInfo">
							<div class="bg-secondary mt-2 rounded d-flex align-items-center justify-content-center jobInfoIcon text-white">
								<i class="bi bi-clock-fill"></i>
							</div>
							<div>
								<strong>Wymiar etatu</strong>
								<p>{$row["employment_type"]}</p>
							</div>
						</div>
						<div class="d-flex col-md-6 jobInfo">
							<div class="bg-secondary mt-2 rounded d-flex align-items-center justify-content-center jobInfoIcon text-white">
								<i class="bi bi-person-workspace"></i>
							</div>
							<div>
								<strong>Rodzaj pracy</strong>
								<p>{$row["work_type"]}</p>
							</div>
						</div>
						<div class="d-flex col-md-6 jobInfo">
							<div class="bg-secondary mt-2 rounded d-flex align-items-center justify-content-center jobInfoIcon text-white">
								<i class="bi bi-calendar2-week-fill"></i>
							</div>
							<div>
								<strong>Godziny i dni pracy</strong>
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
				echo "<article class='container-md'><div class='alert alert-danger information'><strong>Błąd 404: Nieprawidłowy identyfikator produktu.</strong> <a href='index.php'>Wróć na stronę główną</a></div></article>";
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
                saveApplySection.innerHTML = "<div class='alert alert-danger mb-0 shadow text-center'><p class='fw-bold mb-1'>Coś poszło nie tak. Spróbuj ponownie lub odśwież stronę.</p>Kod błędu: 1 (Nie udało połączyć się z serwerem)<br><button type='button' class='commonButton mt-1' id='reload'><i class='bi bi-arrow-clockwise me-2'></i>Załaduj ponownie</a></div>";
            }
			AddFeatures(); 
		}
		function AddFeatures()
		{
			const logged = <?php echo isset($_SESSION["logged"]) && $_SESSION["logged"] ? "true" : "false"?>;
			document.querySelector("#saveButton")?.addEventListener("click", () => {
				RefreshData("saved");
				if(!logged)
					bsOffcanvas.show();
			});
			/*document.querySelector("#applyButton")?.addEventListener("click", () => {
				RefreshData("applied");
				if(!logged)
					bsOffcanvas.show();
			});*/
			document.querySelector("#reload")?.addEventListener("click", () => RefreshData());
		}
		RefreshData();
	</script>
</body>
</html>