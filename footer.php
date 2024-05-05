<?php
	error_reporting(0);
	$version = array(
		"major" => 1,
		"minor" => 0,
		"patch" => 0,
		"otherInfo" => "",
		"releaseDate" => new DateTime("2024-05-15")
	);
?>
<footer class="px-3 pb-2">
	<article class="container mb-4">
		<section class="row">
			<div class="col-sm-6 col-md-4 pt-5">
				<h3 class="text-uppercase footerHeading mb-3">Vistaaa</h3>
				<p class="mb-2"><a href="./#mainpageBody" class="text-decoration-none mb-2">O nas</a></p>
				<p class="mb-2"><a href="./#banner" class="text-decoration-none mb-2">Oferty pracy</a></p>
				<p class="mb-2"><a href="./#offerSearch" class="text-decoration-none mb-2">Znajdź </a></p>
				<p class="mb-2"><a href="./#latestOffers" class="text-decoration-none mb-2">Ostatnio oglądane</a></p>
			</div>
			<div class="col-sm-6 col-md-4 pt-5">
				<h3 class="text-uppercase footerHeading mb-3">Znajdź też nas na</h3>
				<?php
				echo "<p class='mb-2 fs-5'><a href='https://github.com/MatiHalek/Vistaaa_JobSystem/releases/tag/v{$version['major']}.{$version['minor']}.{$version['patch']}' target='_blank' class='text-white text-decoration-none'><i class='bi bi-github me-2'></i> GitHub</a></p>";
				?>
				<p class="mb-2 fs-5"><a href="https://www.reddit.com/user/MatiHalek/" target="_blank" class="text-white text-decoration-none"><i class="bi bi-reddit me-2"></i> Reddit</a></p>
				<p class="mb-2 fs-5"><a href="https://www.youtube.com/MatiHalek" target="_blank" class="text-white text-decoration-none"><i class="bi bi-youtube me-2"></i> YouTube</a></p>
				<p class="mb-2 fs-5"><a href="https://twitter.com/MatiHalek" target="_blank" class="text-white text-decoration-none"><i class="bi bi-twitter-x me-2"></i> X</a></p>
			</div>
			<div class="col-sm-6 col-md-4 pt-5">
				<h3 class="text-uppercase footerHeading mb-3">Kontakt</h3>
				<p class="text-white mb-2">Serwis ogłoszeniowy Vistaaa Sp. z o.o.</p>
				<p class="text-white mb-2">ul. Zielona 5</p>
				<p class="text-white mb-2">34-600 Limanowa</p>
				<p class="text-white mb-2">tel. <a href="tel:123456789" class="text-decoration-none">123 456 789</a></p>
				<p class="text-white mb-2">e-mail: <a href="mailto:vistaaa_advertising@outlook.com" class="text-decoration-none">vistaaa_advertising@outlook.com</a></p>
			</div>
		</section>
		<?php
			echo "<p class='mb-1 text-center text-primary'>Wersja {$version['major']}.{$version['minor']}.{$version['patch']}".(empty($version["otherInfo"]) ? "" : " ".$version["otherInfo"])."</p>";
			echo "<p class='text-center text-primary'><i class='bi bi-calendar-check-fill me-2'></i>".$version["releaseDate"]->format("d.m.Y")."</p>";
		?>
	</article>
	<?php
		echo "<p class='w-100'>&copy; 2023 - ".$version["releaseDate"]->format("Y")." MH Corporation. Wszelkie prawa zastrzeżone. Wszystkie znaki handlowe są własnością ich prawnych właścicieli w serwisie Vistaaa i innych firmach.</div></div>";
	?>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="./headerAnimation.js"></script>
<script>
	const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
	const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
	const bsOffcanvas = new bootstrap.Offcanvas('#offcanvasDarkNavbar');
</script>