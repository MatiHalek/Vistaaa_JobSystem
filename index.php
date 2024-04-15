<!DOCTYPE html>
<html lang="pl">
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Ogromny wybór, wspaniałe produkty i niskie ceny w MatiTechShop! Wiele okazji dla każdego, kto chce kupić urządzenie. Uczta dla wszystkich fanów technologii. Zapraszamy!">
    <meta name="keywords" content="sklep, elektronika, telefony, laptopy, tablety, akcesoria, oferty, niskie ceny, promocje, okazje">
    <meta name="robots" content="index, follow">
    <meta name="author" content="Mateusz Marmuźniak">
    <title>Tysiące atrakcyjnych ofert od sprawdzonych pracodawców | System ogłoszeniowy Vistaaa</title>
    <base href="https://127.0.0.1/vistaaa/">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="img/vistaaa_small_logo.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
	<?php
		header('Content-Type: text/html; charset=utf-8');
		include "header.php";
	?>  
	<main>
		<article id="banner">
			<section class="p-3 m-3 fw-bold">
				<div>
					Mamy już
					<div id="counter"></div>
					ofert od sprawdzonych pracodawców
				</div>
			</section>
		</article>
		<article class="container-md d-flex flex-column" style="gap: 1.5rem;">
			<section class="row p-3">
				<h2 class="text-center py-2">Wyszukiwarka ofert</h2>
				<div class="input-group input-group-lg mb-3">
					<span class="input-group-text" id="basic-addon1">@</span>
					<input type="text" class="form-control" placeholder="Szukaj..." aria-label="Username">
				</div>
					<div class="col-md">
					<select class="form-select form-select-lg mb-3" aria-label="Large select example">
						<option selected>Rodzaj umowy</option>
						<option value="1">One</option>
						<option value="2">Two</option>
						<option value="3">Three</option>
					</select>
				</div>
				<div class="col-md">
					<select class="form-select form-select-lg mb-3" aria-label="Large select example">
						<option selected>Tryb pracy</option>
						<option value="1">One</option>
						<option value="2">Two</option>
						<option value="3">Three</option>
					</select>
				</div>
				<div class="col-md">
					<select class="form-select form-select-lg mb-3" aria-label="Large select example">
						<option selected>Kategoria</option>
						<option value="1">One</option>
						<option value="2">Two</option>
						<option value="3">Three</option>
					</select>
				</div>
				<div class="col-12 text-center">
					<a href="advertisement.html" class="btn w-75 successButton w-auto mx-auto" href="#">Szukaj</a>
				</div>
			</section>
			<section>
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
	?>
	<script>
		let advertisementCount = 6550;
		for(let i = 0; i < 6; i ++)
		{
			const number = document.createElement("div");
			for(let j = 0; j < 10; j++)
			{
				const digit = document.createElement("div");
				digit.innerText = j;
				number.appendChild(digit);
			}
			document.querySelector("#counter").appendChild(number);
		}
		function UpdateCounter()
		{
			advertisementCount = advertisementCount + (Math.floor(Math.random() * 7) - 3);
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
		setInterval(UpdateCounter, 2000);
	</script>
</body>
</html>