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
        <article class="d-flex justify-content-between flex-wrap">
            <section class='d-flex align-items-center mb-2'>
              <span class='fw-bold fs-5'>Sortuj: </span>
              <select class="form-select ms-2 w-auto" aria-label="Sortowanie ofert" id="sortingOffersSelect">
                <option value="">Od najnowszych</option>
                <option value="salary">Od najlepiej płatnych</option>
              </select>
            </section>
            <section class='d-flex align-items-center mb-2'>
              <span class='fw-bold fs-5'>Liczba ogłoszeń na stronie: </span>
              <select class="form-select ms-2 w-auto" aria-label="Liczba ogłoszeń na jednej stronie" id="offersPerPageSelect">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20" selected>20</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </section>
        </article>
        <?php
          if(isset($_SESSION["logged"]) && (array_key_exists("company_id", $_SESSION["logged"]) || $_SESSION["logged"]["is_admin"]))
          {
            echo "<article class='my-2 d-flex align-items-center justify-content-end flex-wrap'>";
            echo "<label for='deleteModeSwitch' class='me-2 mb-2'>Tryb usuwania</label>";
            echo "<div class='form-check form-switch mb-2'>";
            echo "<input class='form-check-input' type='checkbox' role='switch' id='deleteModeSwitch'>";
            echo "</div>";
            echo "<button id='deleteButton' class='mb-2 dangerButton disabled'><i class='bi bi-trash-fill me-2'></i>Usuń zaznaczone (0)</button>";
            echo "</article>";
          }           
        ?>
        <article class="row g-3 justify-content-center" id="offersContainer">         
        </article>     
  </main>
  <?php
		  include "footer.php";
      $searches = array("position_name", "category", "city", "company", "position_level", "contract_type", "employment_type", "work_type");
	?>
  <script>
    let deleteMode = false;
    document.querySelector("#deleteModeSwitch")?.addEventListener("input", function() {
      if(this.checked)
        deleteMode = true;
      else
        deleteMode = false;
    });
    const deletingOffers = [];
    const loadingAnimation = "<div class='spinner-border text-primary' style='scale: 2;' role='status'><span class='visually-hidden'>Loading...</span></div>";
    let page = <?php echo isset($_GET["page"]) ? $_GET["page"] : 1; ?>;
    let sort = "<?php echo isset($_GET["sort"]) ? $_GET["sort"] : ""; ?>";
    let search = "<?php echo isset($_GET["search"]) ? $_GET["search"] : ""; ?>";
    <?php
      foreach($searches as $search)
        echo "const $search = \"".(isset($_GET["search_$search"]) ? implode(";", $_GET["search_$search"]) : "")."\";\n";
    ?>
    if(localStorage.getItem("offersPerPage") !== null)
      document.querySelector("#offersPerPageSelect").value = localStorage.getItem("offersPerPage");
    else
      localStorage.setItem("offersPerPage", document.querySelector("#offersPerPageSelect").value);
    document.querySelector("#offersPerPageSelect").addEventListener("input", function() {
      localStorage.setItem("offersPerPage", this.value);
      GetOffers();
    });
    if(sort !== "")
      document.querySelector("#sortingOffersSelect").value = sort;
    document.querySelector("#sortingOffersSelect").addEventListener("input", function() {
      sort = this.value;
      const url = new URL(location.href);
      if(url.searchParams.has("sort") && this.value === "")
        url.searchParams.delete("sort");
      else
        url.searchParams.set("sort", this.value);
      history.replaceState(null, "", url);
      GetOffers();
    });
    async function GetOffers()
    {
      try
      {
        document.querySelector("#offersContainer").innerHTML = loadingAnimation;
        const sendData = new FormData();
        sendData.append("search", search);
        sendData.append("page", page);
        sendData.append("sort", sort);
        sendData.append("offersPerPage", localStorage.getItem("offersPerPage"));
        <?php
          foreach($searches as $search)
            echo "sendData.append(\"$search\", $search);\n";
        ?>
        const response = await fetch("./fetch/getoffers.php", {
          method: "POST",
          body: sendData
        });
        document.querySelector("#offersContainer").innerHTML = await response.text(); 
      }
      catch
      {
        document.querySelector("#offersContainer").innerHTML = "<div class='alert alert-danger mb-0 shadow text-center'><p class='fw-bold mb-1'>Coś poszło nie tak. Spróbuj ponownie lub odśwież stronę.</p>Kod błędu: 1 (Nie udało połączyć się z serwerem)<br><button type='button' class='commonButton mt-1' id='reload'><i class='bi bi-arrow-clockwise me-2'></i>Załaduj ponownie</a></div>";
        document.querySelector("#reload").addEventListener("click", GetOffers);
      }      
      document.querySelectorAll("[data-page]").forEach(element => {
        element.addEventListener("click", function() {
          page = this.getAttribute("data-page");
          const url = new URL(location.href);
          if(url.searchParams.has("page") && page === "1")
            url.searchParams.delete("page");
          else
            url.searchParams.set("page", page);
          history.replaceState(null, "", url);
          GetOffers();
        });
      });
      document.querySelectorAll("[data-offer]").forEach(element => {
        element.addEventListener("click", function(e) {
          if(deleteMode)
          {
            e.preventDefault();
            if(this.classList.contains("bg-danger"))
            {
              this.classList.remove("bg-danger");
              this.classList.remove("bg-opacity-50");
              this.classList.add("bg-white");
              deletingOffers.splice(deletingOffers.indexOf(this.getAttribute("data-offer")), 1);
            }            
            else
            {
              this.classList.add("bg-opacity-50");
              this.classList.remove("bg-white");
              this.classList.add("bg-danger");
              deletingOffers.push(this.getAttribute("data-offer"));

            } 
            console.log(deletingOffers);            
            const deleteButton = document?.querySelector("#deleteButton");
            if(document.querySelectorAll(".bg-danger").length > 0)
            {
              deleteButton.classList.remove("disabled");
              deleteButton.textContent = `Usuń zaznaczone (${document.querySelectorAll(".bg-danger").length})`;
            }
            else
            {
              deleteButton.classList.add("disabled");
              deleteButton.textContent = "Usuń zaznaczone (0)";
            }
          }
        });
      });
    } 
    GetOffers();    
  </script>
</body>
</html>