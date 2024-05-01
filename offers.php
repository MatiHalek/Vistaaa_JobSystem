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
        <article class="row g-3 justify-content-center">
          <?php
            require "connect.php";
            $connect = new mysqli($host, $db_user, $db_password, $db_name);
            $connect->set_charset("utf8mb4");
            $page = (isset($_GET["page"]) && is_numeric($_GET["page"]) && $_GET["page"] > 0) ? $_GET["page"] : 1;
            $offersPerPage = 1;
            if(isset($_SESSION["logged"]) && array_key_exists("company_id", $_SESSION["logged"]))
            {
              $result = $connect->execute_query("SELECT *, 1 AS avaliable FROM advertisement WHERE company_id = ? AND date_expiration >= NOW() UNION SELECT *, 0 AS avaliable FROM advertisement WHERE company_id = ? AND date_expiration < NOW() ORDER BY avaliable DESC LIMIT ".($page - 1).", 1;", [$_SESSION["logged"]["company_id"], $_SESSION["logged"]["company_id"]]);
              $totalOffers = ($connect->execute_query("SELECT advertisement_id FROM advertisement WHERE company_id = ?", [$_SESSION["logged"]["company_id"]]))->num_rows;
            }                
            else
            {
              $result = $connect->execute_query("SELECT *, 1 AS avaliable FROM advertisement WHERE date_expiration >= NOW() UNION SELECT *, 0 AS avaliable FROM advertisement WHERE date_expiration < NOW() ORDER BY avaliable DESC LIMIT ".($page - 1).", 1;");
              $totalOffers = ($connect->execute_query("SELECT advertisement_id FROM advertisement"))->num_rows;
            }
            $totalPages = ceil($totalOffers / $offersPerPage);
            if($result->num_rows > 0)
            {
                echo "<p class='text-center signika-negative fs-4 fw-bold'>Znalezione wyniki: ".$totalOffers."</p>";
                $months = array("stycznia", "lutego", "marca", "kwietnia", "maja", "czerwca", "lipca", "sierpnia", "września", "października", "listopada", "grudnia");
                while($row = $result->fetch_assoc())
                {
                    echo "<div class='d-flex col-12 col-sm-6 col-lg-4 col-xl-3'><a class='d-block w-100 text-decoration-none bg-white shadow rounded position-relative p-3 pt-4 position-relative jobOffer ".($row["avaliable"] == 0 ? " jobOfferDisabled" : "")."' href='offerdetails.php?id=".$row["advertisement_id"]."'>";
                    $date_added = new DateTime($row["date_added"]);
                    echo "<div class='position-absolute bg-success text-white rounded-pill py-1 px-3 top-0'>".$date_added->format("j")." ".mb_substr($months[(int)($date_added->format("n")) - 1], 0, 3)." ".$date_added->format("Y")."</div>";
                    $categoryResult = $connect->execute_query('SELECT name FROM advertisement_category INNER JOIN category USING(category_id) WHERE advertisement_id = ?', [$row["advertisement_id"]]);
                    $categoryArray = array();
                    while($categoryRow = $categoryResult->fetch_assoc())
                        array_push($categoryArray, $categoryRow["name"]);
                    echo "<p class='text-secondary fw-bold fs-6 mb-1'>".implode(", ", $categoryArray)."</p>";
                    echo "<p class='text-primary fw-bold fs-5 mb-0'>{$row["title"]}</p>"; 
                    $companyResult = $connect->execute_query('SELECT name FROM company WHERE company_id = ?', [$row["company_id"]]);  
                    $companyRow = $companyResult->fetch_assoc();             
                    echo "<p class='text-primary-emphasis mb-2'>przez: ".$companyRow["name"]."</p>";             
                    echo "<p class='text-success fw-bold'>".(is_null($row["salary_lowest"]) ? number_format($row["salary_highest"], 2, ",", " ") : number_format($row["salary_lowest"], 2, ",", " ")." - ".number_format($row["salary_highest"], 2, ",", " "))." zł</p>";
                    echo "<hr class='text-body-tertiary'>";
                    echo "<p class='text-body-secondary'>{$row["position_level"]} &#x2022; {$row["contract_type"]} &#x2022; {$row["employment_type"]} &#x2022; {$row["work_type"]}</p>";
                    echo "</a></div>";
                    echo "<nav class='row mt-5'>";
                    echo "<ul class='pagination d-flex justify-content-center'>";
                    echo "<li class='page-item'>";
                    echo "<a title='Pierwsza strona' data-bs-toggle='tooltip' class='page-link".($page > 1 ? "' href='offers.php?page=1'" : " disabled'")."><i class='bi bi-caret-left-fill'></i><i class='bi bi-caret-left-fill'></i></a>";
                    echo "</li>";
                    echo "<li class='page-item'>";
                    echo "<a title='Poprzednia strona' data-bs-toggle='tooltip' class='page-link".($page > 1 ? "' href='offers.php?page=".($page - 1)."'" : " disabled'")."><i class='bi bi-caret-left-fill'></i></a>";
                    echo "</li>";
                    for($i = $page - 3; $i <= $page + 3; $i++)
                    {
                        if($i > 0 && $i <= $totalPages)
                        {
                            echo "<li class='page-item".($i == $page ? " active" : "")."'>";
                            echo "<a class='page-link' href='offers.php?page=$i'>$i</a>";
                            echo "</li>";
                        }
                    }
                    echo "<li class='page-item'>";
                    echo "<a title='Następna strona' data-bs-toggle='tooltip' class='page-link".($page >= $totalPages ? " disabled'" : "' href='offers.php?page=".($page + 1)."'")."><i class='bi bi-caret-right-fill'></i></a>";
                    echo "</li>";
                    echo "<li class='page-item'>";
                    echo "<a title='Ostatnia strona' data-bs-toggle='tooltip' class='page-link".($page >= $totalPages ? " disabled'" : "' href='offers.php?page=$totalPages'")."><i class='bi bi-caret-right-fill'></i><i class='bi bi-caret-right-fill'></i></a>";
                    echo "</li>";
                    echo "</ul></nav>";
                }
            }
            else
                echo "<p class='text-center signika-negative fs-4 fw-bold'>Niestety, nie znaleźliśmy pasujących ofert pracy.</p>";
            $result->free_result();
            $connect->close();
          ?>
        </article>
        <article class="row"></article>
        </article>
        </article>
       
  </main>
  <?php
		  include "footer.php";
	?>
</body>
</html>