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
			<section class="p-3 rounded-3 bg-primary bg-opacity-25" id="offerSearch">
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
												echo "<label><input type='checkbox' class='form-check-input me-2' name='search_position[]' value='{$position['position_name']}'>{$position['position_name']}</label>";
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
												echo "<label><input type='checkbox' class='form-check-input me-2' name='search_location[]' value='{$location['city']}'>{$location['city']}</label>";
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
												echo "<label><input type='checkbox' class='form-check-input me-2' name='search_company[]' value='{$company['name']}'>{$company['name']}</label>";
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
                        		<input type="text" id="position_search" form="nosubmit" placeholder="Stanowisko" class="rounded-4 border-0 w-100 py-2 px-3" readonly>
                         		<label for="position_search" class="position-absolute">Stanowisko</label>
                    		</div>
						</div>
						<div class="col-12 col-md-6 col-lg-4">
							<div class="position-relative formInput mt-3">                       
                        		<input type="text" id="category_search" form="nosubmit" placeholder="Kategoria" class="rounded-4 border-0 w-100 py-2 px-3" readonly>
                         		<label for="category_search" class="position-absolute">Kategoria</label>
                    		</div>
						</div>
						<div class="col-12 col-md-6 col-lg-4">
							<div class="position-relative formInput mt-3">                       
                        		<input type="text" id="location_search" form="nosubmit" placeholder="Lokalizacja" class="rounded-4 border-0 w-100 py-2 px-3" readonly>
                         		<label for="location_search" class="position-absolute">Lokalizacja</label>
                    		</div>
						</div>
						<div class="col-12 col-md-6 col-lg-4">
							<div class="position-relative formInput mt-3">                       
                        		<input type="text" id="company_search" form="nosubmit" placeholder="Firma" class="rounded-4 border-0 w-100 py-2 px-3" readonly>
                         		<label for="company_search" class="position-absolute">Firma</label>
                    		</div>
						</div>
						<div class="col-12 col-md-6 col-lg-4">
							<div class="position-relative formInput mt-3">                       
                        		<input type="text" id="position_level_search" form="nosubmit" placeholder="Poziom stanowiska" class="rounded-4 border-0 w-100 py-2 px-3" readonly>
                         		<label for="position_level_search" class="position-absolute">Poziom stanowiska</label>
                    		</div>
						</div>
						<div class="col-12 col-md-6 col-lg-4">
							<div class="position-relative formInput mt-3">                       
                        		<input type="text" id="contract_type_search" form="nosubmit" placeholder="Rodzaj umowy" class="rounded-4 border-0 w-100 py-2 px-3" readonly>
                         		<label for="contract_type_search" class="position-absolute">Rodzaj umowy</label>
                    		</div>
						</div>
						<div class="col-12 col-md-6 col-lg-4">
							<div class="position-relative formInput mt-3">                       
                        		<input type="text" id="employment_type_search" form="nosubmit" placeholder="Wymiar pracy" class="rounded-4 border-0 w-100 py-2 px-3" readonly>
                         		<label for="employment_search" class="position-absolute">Wymiar pracy</label>
                    		</div>
						</div>
						<div class="col-12 col-md-6 col-lg-4">
							<div class="position-relative formInput mt-3">                       
                        		<input type="text" id="work_type_search" form="nosubmit" placeholder="Tryb pracy" class="rounded-4 border-0 w-100 py-2 px-3" readonly>
                         		<label for="work_type_search" class="position-absolute">Tryb pracy</label>
                    		</div>
						</div>
					</div>
					<div class="row text-center mt-3">
							<button type="submit" class="w-75 successButton w-auto mx-auto text-decoration-none" href="#"><i class="bi bi-search me-2"></i>Szukaj</button>
					</div>
				</form>
				
			</section>
			<section id="latestOffers">
				<h2 class="text-center py-2">Ostatnio oglądane</h2>
				<div class="d-flex overflow-y-auto" style="gap: .5rem;">
					<div style="width: 280px;" class="flex-shrink-0">
						<div class="card h-100">
							<div class="card-body">
							<h5 class="card-title">Tytuł ogłoszenia</h5>
							<p class="card-text">Treść ogłoszenia</p>
							</div>
							<div class="card-footer">
							<small class="text-body-secondary">Przeglądane 58 sekund temu</small>
							</div>
						</div>
					</div>
					<div style="width: 280px;" class="flex-shrink-0">
						<div class="card h-100">
							<div class="card-body">
							<h5 class="card-title">Tytuł ogłoszenia</h5>
							<p class="card-text">Treść ogłoszenia</p>
							</div>
							<div class="card-footer">
							<small class="text-body-secondary">Przeglądane 58 sekund temu</small>
							</div>
						</div>
					</div><div style="width: 280px;" class="flex-shrink-0">
						<div class="card h-100">
							<div class="card-body">
							<h5 class="card-title">Tytuł ogłoszenia</h5>
							<p class="card-text">Treść ogłoszenia</p>
							</div>
							<div class="card-footer">
							<small class="text-body-secondary">Przeglądane 58 sekund temu</small>
							</div>
						</div>
					</div><div style="width: 280px;" class="flex-shrink-0">
						<div class="card h-100">
							<div class="card-body">
							<h5 class="card-title">Tytuł ogłoszenia</h5>
							<p class="card-text">Treść ogłoszenia</p>
							</div>
							<div class="card-footer">
							<small class="text-body-secondary">Przeglądane 58 sekund temu</small>
							</div>
						</div>
					</div><div style="width: 280px;" class="flex-shrink-0">
						<div class="card h-100">
							<div class="card-body">
							<h5 class="card-title">Tytuł ogłoszenia</h5>
							<p class="card-text">Treść ogłoszenia</p>
							</div>
							<div class="card-footer">
							<small class="text-body-secondary">Przeglądane 58 sekund temu</small>
							</div>
						</div>
					</div>
				</div>
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
		document.querySelector("#searchModal").addEventListener("hide.bs.modal", () => {
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
		});
	</script>
</body>
</html>