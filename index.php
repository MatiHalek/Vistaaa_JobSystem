
<?php
	//error_reporting(0);
	session_start();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="UTF-8">
    <meta name="description" content="Znajdziesz tu tysiące atrakcyjnych i dobrze płatnych ofert pracy od sprawdzonych pracodawców z renomowanych firm w kraju i za granicą. Jeżeli szukasz pracy, ten serwis jest w sam raz dla Ciebie. Zapraszamy!">
    <meta name="keywords" content="praca, oferty, ogłoszenia, system">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Mateusz Marmuźniak">
    <title>Tysiące atrakcyjnych ofert od sprawdzonych pracodawców | System ogłoszeniowy Vistaaa</title>
    <base href="https://127.0.0.1/Vistaaa/">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="img/vistaaa_small_logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body id="mainpageBody">
	<?php
		header('Content-Type: text/html; charset=utf-8');
		include "header.php";
		require "connect.php";
		$connect = new mysqli($host, $db_user, $db_password, $db_name);
		$connect->set_charset('utf8mb4');
	?>  
	<main>
		<article id="banner" class="rounded m-1 shadow">
			<section class="p-3 m-3 fw-bold">
				<div>
					Mamy już
					<div id="counter"></div>
					ofert od sprawdzonych pracodawców
				</div>
			</section>
		</article>
		<article class="container-md d-flex flex-column mt-5 p-1" style="gap: 1.5rem;">
			<section class="p-3 rounded-3 bg-primary bg-opacity-10" id="offerSearch">
				<h2 class="text-center py-2">Wyszukiwarka ofert</h2>
				<form action="offers.php" method="GET">
					<div class='modal fade show' id='searchModal' tabindex='-1' aria-hidden='true'>
						<div class='modal-dialog modal-dialog-centered modal-dialog-scrollable'>
							<div class='modal-content'>
								<div class='modal-header'>
									<h1 class='modal-title fs-5'>Szukaj oferty po kategorii</h1>
									<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
								</div>
								<div class='modal-body'>
									<div class="d-none">
										<?php
											$positionResult = $connect->execute_query("SELECT position_name, COUNT(advertisement_id) AS number FROM advertisement GROUP BY position_name ORDER BY number DESC");
											while($position = $positionResult->fetch_assoc())
											{
												echo "<div class='d-flex justify-content-between mb-1'>";
												echo "<label><input type='checkbox' class='form-check-input me-2' name='search_position_name[]' value='{$position['position_name']}'>{$position['position_name']}</label>";
												echo "<span class='text-secondary'>{$position['number']}</span>";
												echo "</div>";
											}
										?>
									</div>
									<div class="d-none">
										<?php
											$categoryResult = $connect->execute_query("SELECT name, COUNT(advertisement_id) AS number FROM advertisement_category INNER JOIN category USING(category_id) GROUP BY name ORDER BY number DESC");
											while($category = $categoryResult->fetch_assoc())
											{
												echo "<div class='d-flex justify-content-between mb-1'>";
												echo "<label><input type='checkbox' class='form-check-input me-2' name='search_category[]' value='{$category['name']}'>{$category['name']}</label>";
												echo "<span class='text-secondary'>{$category['number']}</span>";
												echo "</div>";
											}
										?>
									</div>
									<div class="d-none">
										<?php
											$locationResult = $connect->execute_query("SELECT city, COUNT(advertisement_id) AS number FROM advertisement INNER JOIN company USING(company_id) GROUP BY city ORDER BY number DESC");
											while($location = $locationResult->fetch_assoc())
											{
												echo "<div class='d-flex justify-content-between mb-1'>";
												echo "<label><input type='checkbox' class='form-check-input me-2' name='search_city[]' value='{$location['city']}'>{$location['city']}</label>";
												echo "<span class='text-secondary'>{$location['number']}</span>";
												echo "</div>";
											}
										?>
									</div>
									<div class="d-none">
										<?php
											$companyResult = $connect->execute_query("SELECT name, COUNT(advertisement_id) AS number FROM advertisement INNER JOIN company USING(company_id) GROUP BY name ORDER BY number DESC");
											while($company = $companyResult->fetch_assoc())
											{
												echo "<div class='d-flex justify-content-between mb-1'>";
												echo "<label><input type='checkbox' class='form-check-input me-2' name='search_name[]' value='{$company['name']}'>{$company['name']}</label>";
												echo "<span class='text-secondary'>{$company['number']}</span>";
												echo "</div>";
											}
										?>
									</div>
									<div class="d-none">
										<?php
											$positionLevelResult = $connect->execute_query("SELECT position_level, COUNT(advertisement_id) AS number FROM advertisement GROUP BY position_level ORDER BY number DESC");
											while($positionLevel = $positionLevelResult->fetch_assoc())
											{
												echo "<div class='d-flex justify-content-between mb-1'>";
												echo "<label><input type='checkbox' class='form-check-input me-2' name='search_position_level[]' value='{$positionLevel['position_level']}'>{$positionLevel['position_level']}</label>";
												echo "<span class='text-secondary'>{$positionLevel['number']}</span>";
												echo "</div>";
											}
										?>
									</div>
									<div class="d-none">
										<?php
											$contractTypeResult = $connect->execute_query("SELECT contract_type, COUNT(advertisement_id) AS number FROM advertisement GROUP BY contract_type ORDER BY number DESC");
											while($contractType = $contractTypeResult->fetch_assoc())
											{
												echo "<div class='d-flex justify-content-between mb-1'>";
												echo "<label><input type='checkbox' class='form-check-input me-2' name='search_contract_type[]' value='{$contractType['contract_type']}'>{$contractType['contract_type']}</label>";
												echo "<span class='text-secondary'>{$contractType['number']}</span>";
												echo "</div>";
											}
										?>
									</div>
									<div class="d-none">
										<?php
											$employmentTypeResult = $connect->execute_query("SELECT employment_type, COUNT(advertisement_id) AS number FROM advertisement GROUP BY employment_type ORDER BY number DESC");
											while($employmentType = $employmentTypeResult->fetch_assoc())
											{
												echo "<div class='d-flex justify-content-between mb-1'>";
												echo "<label><input type='checkbox' class='form-check-input me-2' name='search_employment_type[]' value='{$employmentType['employment_type']}'>{$employmentType['employment_type']}</label>";
												echo "<span class='text-secondary'>{$employmentType['number']}</span>";
												echo "</div>";
											}
										?>
									</div>
									<div class="d-none">
										<?php
											$workTypeResult = $connect->execute_query("SELECT work_type, COUNT(advertisement_id) AS number FROM advertisement GROUP BY work_type ORDER BY number DESC");
											while($workType = $workTypeResult->fetch_assoc())
											{
												echo "<div class='d-flex justify-content-between mb-1'>";
												echo "<label><input type='checkbox' class='form-check-input me-2' name='search_work_type[]' value='{$workType['work_type']}'>{$workType['work_type']}</label>";
												echo "<span class='text-secondary'>{$workType['number']}</span>";
												echo "</div>";
											}
										?>
									</div>
								</div>
								<div class='modal-footer'>
									<button type='button' class='successButton' data-bs-dismiss='modal'>OK</button>
								</div>
							</div>
						</div>
					</div>
					<div class="position-relative formInput mt-3">                       
                        <input type="search" id="search" name="search" minlength="3" maxlength="100" placeholder="Wpisz szukaną frazę tutaj..." class="rounded-4 border-0 w-100 py-2 px-3">
                         <label for="search" class="position-absolute">Wpisz szukaną frazę tutaj...</label>
                    </div>
					<div class="row justify-content-center" id="searchCheckboxes">
						<div class="col-12 col-md-6 col-lg-4">
							<div class="position-relative formInput mt-3">                       
                        		<input type="text" id="position_search" form="nosubmit" placeholder="" class="rounded-4 border-0 w-100 py-2 px-3 pe-4" readonly>
                         		<label for="position_search" class="position-absolute">Stanowisko</label>
                    		</div>
						</div>
						<div class="col-12 col-md-6 col-lg-4">
							<div class="position-relative formInput mt-3">                       
                        		<input type="text" id="category_search" form="nosubmit" placeholder="" class="rounded-4 border-0 w-100 py-2 px-3 pe-4" readonly>
                         		<label for="category_search" class="position-absolute">Kategoria</label>
                    		</div>
						</div>
						<div class="col-12 col-md-6 col-lg-4">
							<div class="position-relative formInput mt-3">                       
                        		<input type="text" id="location_search" form="nosubmit" placeholder="" class="rounded-4 border-0 w-100 py-2 px-3 pe-4" readonly>
                         		<label for="location_search" class="position-absolute">Lokalizacja</label>
                    		</div>
						</div>
						<div class="col-12 col-md-6 col-lg-4">
							<div class="position-relative formInput mt-3">                       
                        		<input type="text" id="company_search" form="nosubmit" placeholder="" class="rounded-4 border-0 w-100 py-2 px-3 pe-4" readonly>
                         		<label for="company_search" class="position-absolute">Firma</label>
                    		</div>
						</div>
						<div class="col-12 col-md-6 col-lg-4">
							<div class="position-relative formInput mt-3">                       
                        		<input type="text" id="position_level_search" form="nosubmit" placeholder="" class="rounded-4 border-0 w-100 py-2 px-3 pe-4" readonly>
                         		<label for="position_level_search" class="position-absolute">Poziom stanowiska</label>
                    		</div>
						</div>
						<div class="col-12 col-md-6 col-lg-4">
							<div class="position-relative formInput mt-3">                       
                        		<input type="text" id="contract_type_search" form="nosubmit" placeholder="" class="rounded-4 border-0 w-100 py-2 px-3 pe-4" readonly>
                         		<label for="contract_type_search" class="position-absolute">Rodzaj umowy</label>
                    		</div>
						</div>
						<div class="col-12 col-md-6 col-lg-4">
							<div class="position-relative formInput mt-3">                       
                        		<input type="text" id="employment_type_search" form="nosubmit" placeholder="" class="rounded-4 border-0 w-100 py-2 px-3 pe-4" readonly>
                         		<label for="employment_search" class="position-absolute">Wymiar pracy</label>
                    		</div>
						</div>
						<div class="col-12 col-md-6 col-lg-4">
							<div class="position-relative formInput mt-3">                       
                        		<input type="text" id="work_type_search" form="nosubmit" placeholder="" class="rounded-4 border-0 w-100 py-2 px-3 pe-4" readonly>
                         		<label for="work_type_search" class="position-absolute">Tryb pracy</label>
                    		</div>
						</div>
					</div>
					<div class="row text-center mt-3">
							<button type="submit" class="w-75 successButton w-auto mx-auto text-decoration-none" href="#"><i class="bi bi-search me-2"></i>Szukaj</button>
					</div>
				</form>				
			</section>
			<section id="recentlyViewedOffers">
				<?php
					$months = array("stycznia", "lutego", "marca", "kwietnia", "maja", "czerwca", "lipca", "sierpnia", "września", "października", "listopada", "grudnia");
					if(isset($_COOKIE["vistaaaRecentlyViewedOffers"]) && !empty($_COOKIE["vistaaaRecentlyViewedOffers"]))
					{
						$heading = "oglądane";
						$recentlyViewed = json_decode($_COOKIE["vistaaaRecentlyViewedOffers"]);
					}
					else
					{
						$heading = "dodane";
						$recentOffers = $connect->execute_query("SELECT advertisement_id FROM advertisement ORDER BY date_added DESC LIMIT 10");
						$recentlyViewed = array();
						while($row = $recentOffers->fetch_assoc())
							array_push($recentlyViewed, $row["advertisement_id"]);
					}
					echo "<h2 class='text-center py-2'>Ostatnio $heading</h2>";
					echo "<div class='position-relative'><button type='button' title='Wstecz' class='position-absolute border-0 rounded-circle bg-primary text-white'><i class='bi bi-caret-left-fill fs-5'></i></button><div class='d-flex overflow-y-auto'>";
					foreach($recentlyViewed as $id)
					{
						$offerResult = $connect->execute_query("SELECT *, IF(date_expiration >= NOW(), 1, 0) AS available FROM advertisement INNER JOIN company USING(company_id) WHERE advertisement_id = ?", [$id]);
						if($offerResult->num_rows > 0)
						{
							while($row = $offerResult->fetch_assoc())
							{
								echo "<div class='flex-shrink-0'>";
								echo "<a data-offer='".$row["advertisement_id"]."' class='d-block w-100 text-decoration-none bg-white shadow rounded position-relative p-3 pt-4 position-relative jobOffer ".($row["available"] == 0 ? " jobOfferDisabled" : "")."' href='offerdetails.php?id=".$row["advertisement_id"]."'>";
								$date_added = new DateTime($row["date_added"]);
								echo "<div class='position-absolute bg-success text-white rounded-pill py-1 px-3 top-0'>".$date_added->format("j")." ".mb_substr($months[(int)($date_added->format("n")) - 1], 0, 3)." ".$date_added->format("Y")."</div>";
								$categoryResult = $connect->execute_query('SELECT name FROM advertisement_category INNER JOIN category USING(category_id) WHERE advertisement_id = ?', [$row["advertisement_id"]]);
								$categoryArray = array();
								while($categoryRow = $categoryResult->fetch_assoc())
									array_push($categoryArray, $categoryRow["name"]);
								echo "<p class='text-secondary fw-bold fs-6 mb-1 me-4'>".implode(", ", $categoryArray)."</p>";
								if(isset($_SESSION["logged"]) && array_key_exists("user_id", $_SESSION["logged"]))
								{
									$savedResult = $connect->execute_query('SELECT * FROM user_saved WHERE user_id = ? AND advertisement_id = ?', [$_SESSION["logged"]["user_id"], $row["advertisement_id"]]);
									if($savedResult->num_rows > 0)
										echo "<div data-bs-toggle='tooltip' title='Zapisano' class='color-orange mt-4 me-3 position-absolute end-0 top-0'><i class='fs-4 bi bi-star-fill'></i></div>";
								}
								echo "<p class='text-primary fw-bold fs-5 mb-0'>{$row["title"]}</p>";           
								echo "<p class='text-primary-emphasis mb-2'>przez: ".$row["name"]."</p>";             
								echo "<p class='text-success fw-bold'>".(is_null($row["salary_lowest"]) ? number_format($row["salary_highest"], 2, ",", " ") : number_format($row["salary_lowest"], 2, ",", " ")." - ".number_format($row["salary_highest"], 2, ",", " "))." zł</p>";
								echo "<hr class='text-body-tertiary'>";
								echo "<p class='text-body-secondary'>".mb_strtolower($row["position_level"])." &#x2022; ".mb_strtolower($row["contract_type"])." &#x2022; ".strtolower($row["employment_type"])." &#x2022; ".mb_strtolower($row["work_type"])."</p>";
								echo "</a>";
								echo "</div>";
							}
							
						}
					}
					echo "</div><button type='button' title='Dalej' class='position-absolute border-0 rounded-circle bg-primary text-white'><i class='bi bi-caret-right-fill fs-5'></i></button></div>";							
				?>
			</section>
		</article>			
	</main>
	<?php
		include "footer.php";
		$connect->close();
	?>
	<script>
		let advertisementCount = 6550;
		for(let i = 0; i < 6; i ++)
		{
			const number = document.createElement("div");
			for(let j = 0; j < 10; j++)
			{
				const digit = document.createElement("div");
				if(i > 0 && j == 0)
					digit.innerText = "";
				else
					digit.innerText = j;
				number.appendChild(digit);
			}
			document.querySelector("#counter").appendChild(number);
		}
		function UpdateCounter()
		{
			advertisementCount = advertisementCount + (Math.floor(Math.random() * 7) - 3);
			for(let i = 0; i < 6; i++)
			{
				document.querySelector("#counter").children[i].children[0].innerText = "0";
				document.querySelector("#counter").children[i].style.transition = `transform .${9 - i}s ease-in-out`;
			}
			const numbers = document.querySelectorAll("#counter > div");
			for(let i = 0; i < numbers.length; i++)
			{
				if(i < advertisementCount.toString().length)
				{
					numbers[numbers.length - advertisementCount.toString().length + i].style.display = "block";
					numbers[numbers.length - advertisementCount.toString().length + i].style.transform = `translateY(${-2.5 * advertisementCount.toString()[i]}rem)`;
				}
				else
					numbers[numbers.length - i - 1].style.display = "none";
				
			}
		}
		setInterval(UpdateCounter, 1500);
		const modalHeaders = ["stanowisku", "kategorii", "lokalizacji", "firmie", "poziomie stanowiska", "rodzaju umowy", "wymiarze pracy", "trybie pracy"];
		const searchModal = new bootstrap.Modal(document.querySelector("#searchModal"));
		function GetChildIndex(parent, child) 
		{
  			return Array.prototype.indexOf.call(parent.children, child);
		}
		document.querySelectorAll("#searchCheckboxes > div > div").forEach(input => {
			input.addEventListener("click", () => {
				const index = GetChildIndex(document.querySelector("#searchCheckboxes"), input.parentElement);
				document.querySelector("#searchModal .modal-header h1").innerText = `Szukaj oferty po  ${modalHeaders[index]}`;
				document.querySelector(`#searchModal .modal-body > div:nth-child(${index + 1})`).classList.remove("d-none");
				document.querySelectorAll(`#searchModal .modal-body > div:not(:nth-child(${index + 1}))`).forEach(div => {
					div.classList.add("d-none");
				});
				searchModal.show();
			});
		});
		document.querySelector("#searchModal").addEventListener("hide.bs.modal", UpdateInputs);
		function UpdateInputs()
		{
			const modals = document.querySelectorAll("#searchModal .modal-body > div");
			for(let i = 0; i < modals.length; i++)
			{
				const checkedCheckboxes = document.querySelectorAll(`#searchModal .modal-body > div:nth-child(${i + 1}) input:checked`);
				const selectedValues = [];
				checkedCheckboxes.forEach(checkbox => {
					selectedValues.push(checkbox.value);
				});
				if(selectedValues.length > 0)
				{
					if(selectedValues.length > 3)
						document.querySelector(`#searchCheckboxes > div:nth-child(${i + 1}) input`).value = selectedValues.slice(0, 3).join(", ") + " i " + (selectedValues.length - 3) + " innych";
					else
						document.querySelector(`#searchCheckboxes > div:nth-child(${i + 1}) input`).value = selectedValues.join(", ");
				}
				else
					document.querySelector(`#searchCheckboxes > div:nth-child(${i + 1}) input`).value = "";
			}
		}
		UpdateInputs();
		window.addEventListener("pageshow", UpdateInputs);
		document.querySelector("#recentlyViewedOffers > div > div")?.addEventListener("scroll", function(){
			if(this.offsetWidth + this.scrollLeft >= this.scrollWidth)
				this.parentElement.parentElement.classList.remove("moreRight");
			else
				this.parentElement.parentElement.classList.add("moreRight");
			if (this.scrollLeft === 0)
        		this.parentElement.parentElement.classList.remove("moreLeft");
    		else
        		this.parentElement.parentElement.classList.add("moreLeft");
		});
		function TriggerScrollEvent()
		{
			document.querySelector("#recentlyViewedOffers > div > div").dispatchEvent(new Event("scroll"));
		}
		addEventListener("resize", TriggerScrollEvent);
		TriggerScrollEvent();
		document.querySelector("#recentlyViewedOffers > div > button:first-of-type").addEventListener("click", function() {
			document.querySelector("#recentlyViewedOffers > div > div").scrollBy({
      			left: -140,
      			behavior: 'smooth'
    		});
		});
		document.querySelector("#recentlyViewedOffers > div > button:last-of-type").addEventListener("click", function() {
			document.querySelector("#recentlyViewedOffers > div > div").scrollBy({
	  			left: 140,
	  			behavior: 'smooth'
			});
		});
	</script>
</body>
</html>