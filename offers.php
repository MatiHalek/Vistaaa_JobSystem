<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Znajdziesz tu tysiące atrakcyjnych i dobrze płatnych ofert pracy od sprawdzonych pracodawców z renomowanych firm w kraju i za granicą. Jeżeli szukasz pracy, ten serwis jest w sam raz dla Ciebie. Zapraszamy!">
  <meta name="keywords" content="praca, oferty, ogłoszenia, system">
  <meta name="robots" content="index, follow">
  <meta name="author" content="Mateusz Marmuźniak">
  <title>Oferty pracy | System ogłoszeniowy Vistaaa</title>
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
    $pageName = "Oferty pracy"; 
	  include "header.php";
	?>  
    <main class="container-lg">
        <article class="d-flex justify-content-between">
            <section>Sortuj: </section>
            <section>Strona 1 z 31</section>
        </article>
        <article class="row">
             <article class="col-4">Filtry</article>
        </article>
        <article class="row m-2">
            <a class="d-block text-decoration-none bg-white shadow rounded col-12 col-sm-6 col-lg-4 col-xl-3 position-relative p-3 pt-4 me-2 mb-2 position-relative jobOffer" href="#">
                <div class="position-absolute bg-success text-white rounded-pill py-1 px-3 top-0">27 gru 2023</div>
                <p class="text-secondary fw-bold fs-6 mb-1">Kategoria</p>
                <p class="text-primary fw-bold fs-5 mb-0">Programowanie</p>              
                <p class="text-primary-emphasis mb-2">przez: Vistaaa</p>              
                <p class="text-success fw-bold">od 2 399 zł</p>
                <hr class="text-body-tertiary">
                <p class="text-body-secondary">PositionLevel &#x2022; ContractTypeName &#x2022; EmploymentTypeName &#x2022; WorkTypeName</p>
            </a>
        </article>
        <article class="row"></article>
        <nav class="row" aria-label="...">
  <ul class="pagination">
    <li class="page-item disabled">
      <span class="page-link">Previous</span>
    </li>
    <li class="page-item"><a class="page-link" href="#">1</a></li>
    <li class="page-item active" aria-current="page">
      <span class="page-link">2</span>
    </li>
    <li class="page-item"><a class="page-link" href="#">3</a></li>
    <li class="page-item">
      <a class="page-link" href="#">Next</a>
    </li>
  </ul>
</nav>
        </article>
        </article>
       
  </main>
  <?php
		  include "footer.php";
	?>
</body>
</html>